<?php

declare(strict_types=1);

$title = 'Issuance - InventoryV2';
$pageTitle = 'Inventory Issuance';
$pageSubtitle = 'Create, review, and track issuance workflow states.';
$crumbs = [
    ['label' => 'Inventory Issuance'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* Custom JS Pager */
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li { display: block; }
    .ci-pager li a, .ci-pager li span { display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; font-size: 0.85rem; min-width: 32px; border: 1px solid var(--color-border-strong); border-radius: var(--radius-sm); background: var(--color-surface); color: var(--color-brand-700); text-decoration: none; font-weight: 600; transition: all 0.2s ease; }
    .ci-pager li a:hover { background: var(--color-brand-100); border-color: var(--color-brand-500); }
    .ci-pager li.active a { background: var(--color-brand-500); color: #ffffff; border-color: var(--color-brand-600); }
    .ci-pager li.disabled a { opacity: 0.5; background: var(--color-surface-alt); color: var(--color-text-muted); pointer-events: none; border-color: var(--color-border); }
    .ci-pager li span.ellipsis { border: none !important; background: transparent !important; padding: 0 4px !important; min-width: auto; color: var(--color-text-muted); }

    /* Sortable Headers */
    th.sortable { cursor: pointer; position: relative; padding-right: 18px !important; user-select: none; transition: background 0.2s ease; }
    th.sortable:hover { background: rgba(0, 0, 0, 0.03) !important; }
    th.sortable::after { content: '↕'; position: absolute; right: 6px; top: 50%; transform: translateY(-50%); font-size: 0.75rem; opacity: 0.3; }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    
    .filter-active-text { color: var(--color-brand-600); font-weight: 800; text-transform: uppercase; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-primary" href="<?= site_url('inventory/issuance/create') ?>">Create Issuance</a>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Reports</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $issuances ?? [];
$totalIssuances = count($rows);
$draftIssuances = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'draft'));
$submittedIssuances = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'submitted'));
$releasedIssuances = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'released'));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Visible Issuances</p>
                <p class="kpi-value" id="kpi-visible" style="font-size: 1.25rem;"><?= esc((string) $totalIssuances) ?></p>
                <p class="kpi-note">Records in current list view.</p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Draft</p>
                <p class="kpi-value" id="kpi-draft" style="font-size: 1.25rem;"><?= esc((string) $draftIssuances) ?></p>
                <p class="kpi-note">Still editable by requestor.</p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Submitted</p>
                <p class="kpi-value" id="kpi-submitted" style="font-size: 1.25rem;"><?= esc((string) $submittedIssuances) ?></p>
                <p class="kpi-note">Pending approval decision.</p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Released</p>
                <p class="kpi-value" id="kpi-released" style="color: var(--color-success); font-size: 1.25rem;"><?= esc((string) $releasedIssuances) ?></p>
                <p class="kpi-note">Stock already deducted.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <div style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
                <input type="text" id="instant-search-input" placeholder="Search Issuance #, Dept, or ID..." autocomplete="off" style="flex: 1;">
                <button type="button" class="btn btn-outline" id="btn-clear-search">Clear</button>
            </div>
            
            <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('inventory/issuance') ?>" style="margin: 0;">
                <select id="status" name="status" style="padding: 6px 12px; font-size: 0.85rem;">
                    <option value="">DB Sync: All</option>
                    <?php foreach (['draft', 'submitted', 'approved', 'rejected', 'released', 'cancelled'] as $option): ?>
                        <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
                    <?php endforeach ?>
                </select>
                <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.85rem;">Sync</button>
            </form>
        </div>

        <div class="table-wrap">
            <table class="table" id="issuance-table" style="table-layout: fixed; width: 100%; min-width: 800px;">
                <colgroup>
                    <col style="width: 80px;">  <col style="width: 150px;"> <col style="width: 100px;"> <col style="width: 120px;"> <col style="width: 25%;">   <col style="width: 150px;"> <col style="width: 100px;"> </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">Issuance Number</th>
                        <th class="sortable numeric" data-col="2">Requestor</th>
                        <th class="sortable date" data-col="3">Issue Date</th>
                        <th class="sortable" data-col="4">Department</th>
                        <th class="sortable" data-col="5" id="status-header" title="Click to cycle status filters!">Status (All)</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($issuances ?? []) === []): ?>
                        <tr class="no-records-row"><td colspan="7" class="empty-state">No issuance records found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($issuances as $issuance): ?>
                            <tr class="issuance-row" style="display: none;" data-status="<?= esc(strtolower((string) ($issuance['status'] ?? ''))) ?>">
                                <td><?= esc((string) $issuance['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 500; color: var(--color-brand-700);"><?= esc((string) $issuance['issuance_number']) ?></td>
                                <td style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--color-text-muted);">#<?= esc((string) $issuance['requestor_id']) ?></td>
                                <td style="font-size: 0.85rem;"><?= esc((string) $issuance['issue_date']) ?></td>
                                <td style="font-weight: 500; word-break: break-word;"><?= esc((string) ($issuance['department'] ?? '')) ?></td>
                                <td><?= view('components/shared/table_status_badge', ['status' => $issuance['status'] ?? 'unknown']) ?></td>
                                <td style="text-align: center;">
                                    <a class="btn btn-outline" style="padding: 4px 8px; font-size: 0.75rem;" href="<?= site_url('inventory/issuance/' . $issuance['id']) ?>">View</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
            <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                Showing records <span id="page-indicator"></span> (Total: <span id="total-indicator"><?= esc((string) $totalIssuances) ?></span>)
            </p>
            <nav aria-label="Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ==========================================
        // MAIN TABLE SCRIPT
        // ==========================================
        const rowsPerPage = 15; 
        const tbodyMain = document.querySelector('#issuance-table tbody');
        
        if (tbodyMain && tbodyMain.querySelector('.issuance-row')) {
            const allRows = Array.from(tbodyMain.querySelectorAll('.issuance-row'));
            let currentRows = [...allRows]; 

            const pagerContainer = document.getElementById('client-pager');
            const pageIndicator = document.getElementById('page-indicator');
            const totalIndicator = document.getElementById('total-indicator');
            const statusHeader = document.getElementById('status-header');
            
            // KPI DOM Elements
            const kpiVisible = document.getElementById('kpi-visible');
            const kpiDraft = document.getElementById('kpi-draft');
            const kpiSubmitted = document.getElementById('kpi-submitted');
            const kpiReleased = document.getElementById('kpi-released');

            const searchInput = document.getElementById('instant-search-input');
            const clearBtn = document.getElementById('btn-clear-search');

            const statusCycle = ['All', 'draft', 'submitted', 'approved', 'rejected', 'released', 'cancelled'];
            let cycleIndex = 0;
            let currentStatusFilter = 'All';

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();

                currentRows = allRows.filter(row => {
                    const id = row.children[0].innerText.toLowerCase();
                    const issNum = row.children[1].innerText.toLowerCase();
                    const requestor = row.children[2].innerText.toLowerCase();
                    const dept = row.children[4].innerText.toLowerCase();
                    const statusVal = row.getAttribute('data-status');

                    const matchesText = query === '' || id.includes(query) || issNum.includes(query) || dept.includes(query) || requestor.includes(query);
                    const matchesStatus = currentStatusFilter === 'All' || statusVal === currentStatusFilter;

                    return matchesText && matchesStatus;
                });

                currentRows.forEach(row => tbodyMain.appendChild(row));
                updateKPIs();
                showPage(1);
            }

            function updateKPIs() {
                let countDraft = 0, countSubmitted = 0, countReleased = 0;
                currentRows.forEach(row => {
                    const stat = row.getAttribute('data-status');
                    if (stat === 'draft') countDraft++;
                    if (stat === 'submitted') countSubmitted++;
                    if (stat === 'released') countReleased++;
                });
                
                kpiVisible.innerText = currentRows.length;
                kpiDraft.innerText = countDraft;
                kpiSubmitted.innerText = countSubmitted;
                kpiReleased.innerText = countReleased;
                totalIndicator.innerText = currentRows.length;
            }

            if(searchInput) searchInput.addEventListener('input', applyFilters);
            if(clearBtn) {
                clearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    applyFilters();
                });
            }

            // Status Cycle Logic
            if(statusHeader) {
                statusHeader.addEventListener('click', (e) => {
                    e.stopPropagation(); 
                    
                    cycleIndex = (cycleIndex + 1) % statusCycle.length;
                    currentStatusFilter = statusCycle[cycleIndex];

                    if (currentStatusFilter === 'All') {
                        statusHeader.innerHTML = `Status (All)`;
                    } else {
                        statusHeader.innerHTML = `Status (<span class="filter-active-text">${currentStatusFilter}</span>)`;
                    }
                    
                    applyFilters();
                });
            }

            // Normal Sorting for other columns
            document.querySelectorAll('#issuance-table th.sortable').forEach(th => {
                if (parseInt(th.getAttribute('data-col')) === 5) return; // Skip Status

                th.addEventListener('click', () => {
                    const colIndex = parseInt(th.getAttribute('data-col'));
                    const isNumericCol = th.classList.contains('numeric');
                    const isDateCol = th.classList.contains('date');
                    const isAsc = th.classList.contains('asc');
                    const direction = isAsc ? -1 : 1; 
                    
                    document.querySelectorAll('#issuance-table th.sortable').forEach(header => {
                        if (parseInt(header.getAttribute('data-col')) !== 5) {
                            header.classList.remove('asc', 'desc');
                        }
                    });
                    
                    th.classList.add(isAsc ? 'desc' : 'asc');
                    
                    currentRows.sort((a, b) => {
                        let aText = a.children[colIndex].innerText.trim().replace('#', '');
                        let bText = b.children[colIndex].innerText.trim().replace('#', '');
                        
                        if (isNumericCol) return (parseFloat(aText) - parseFloat(bText)) * direction;
                        if (isDateCol) {
                            let dateA = aText === '' ? 0 : new Date(aText).getTime();
                            let dateB = bText === '' ? 0 : new Date(bText).getTime();
                            return (dateA - dateB) * direction;
                        }
                        return aText.localeCompare(bText) * direction;
                    });
                    
                    currentRows.forEach(row => tbodyMain.appendChild(row));
                    showPage(1);
                });
            });

            // Pagination logic
            let currentPage = 1;
            function showPage(page) {
                currentPage = page;
                const totalRows = currentRows.length;
                const totalPages = Math.ceil(totalRows / rowsPerPage);

                if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

                const startPoint = (currentPage - 1) * rowsPerPage;
                const endPoint = startPoint + rowsPerPage;

                allRows.forEach(row => row.style.display = 'none');
                currentRows.forEach((row, index) => {
                    if (index >= startPoint && index < endPoint) row.style.display = '';
                });

                const actualEnd = Math.min(endPoint, totalRows);
                if (pageIndicator) pageIndicator.innerText = totalRows === 0 ? '0' : `${startPoint + 1} - ${actualEnd}`;

                // Render Pager
                if (pagerContainer) {
                    pagerContainer.innerHTML = '';
                    if (totalPages > 1) {
                        let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;
                        
                        let startPage = Math.max(1, currentPage - 2);
                        let endPage = Math.min(totalPages, startPage + 4);
                        if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

                        if (startPage > 1) {
                            html += `<li><a href="#" data-page="1">1</a></li>`;
                            if (startPage > 2) html += `<li><span class="ellipsis">...</span></li>`;
                        }

                        for (let i = startPage; i <= endPage; i++) {
                            html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`;
                        }

                        if (endPage < totalPages) {
                            if (endPage < totalPages - 1) html += `<li><span class="ellipsis">...</span></li>`;
                            html += `<li><a href="#" data-page="${totalPages}">${totalPages}</a></li>`;
                        }

                        html += `<li class="${currentPage === totalPages ? 'disabled' : ''}"><a href="#" data-page="${currentPage + 1}">Next &raquo;</a></li>`;
                        pagerContainer.innerHTML = html;
                    }
                }
            }

            if (pagerContainer) {
                pagerContainer.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (!link) return;
                    e.preventDefault();
                    const li = link.parentElement;
                    if (li.classList.contains('disabled') || li.classList.contains('active')) return;
                    showPage(parseInt(link.getAttribute('data-page')));
                });
            }

            showPage(1);
        }
    });
</script>
<?= $this->endSection() ?>