<?php

declare(strict_types=1);

$title = 'Issuance Report - InventoryV2';
$pageTitle = 'Report: Issuances';
$pageSubtitle = 'Issuance totals and status performance over time.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Issuances'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* Force the custom JS Pager to match your modern button design */
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li { display: block; }
    .ci-pager li a, .ci-pager li span { display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; font-size: 0.85rem; min-width: 32px; border: 1px solid var(--color-border-strong); border-radius: var(--radius-sm); background: var(--color-surface); color: var(--color-brand-700); text-decoration: none; font-weight: 600; transition: all 0.2s ease; }
    .ci-pager li a:hover { background: var(--color-brand-100); border-color: var(--color-brand-500); }
    .ci-pager li.active a { background: var(--color-brand-500); color: #ffffff; border-color: var(--color-brand-600); }
    .ci-pager li.disabled a { opacity: 0.5; background: var(--color-surface-alt); color: var(--color-text-muted); pointer-events: none; border-color: var(--color-border); }
    .ci-pager li span.ellipsis { border: none !important; background: transparent !important; padding: 0 4px !important; min-width: auto; color: var(--color-text-muted); }

    /* --- SORTABLE TABLE HEADERS --- */
    th.sortable { cursor: pointer; position: relative; padding-right: 18px !important; user-select: none; transition: background 0.2s ease; }
    th.sortable:hover { background: rgba(0, 0, 0, 0.03) !important; }
    th.sortable::after { content: '↕'; position: absolute; right: 6px; top: 50%; transform: translateY(-50%); font-size: 0.75rem; opacity: 0.3; }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    
    /* Highlight for active filter cycle */
    .filter-active-text { color: var(--color-brand-600); font-weight: 800; text-transform: uppercase; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $issuancesExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? ''), 'date_from' => ($date_from ?? ''), 'date_to' => ($date_to ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') . '?' . $issuancesExportQuery ?>">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>">Low Stock</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$issuanceRows = $rows ?? [];
$totalRows = count($issuanceRows);
$totalRequested = array_sum(array_map(static fn (array $row): float => (float) ($row['total_requested_qty'] ?? 0), $issuanceRows));
$totalIssued = array_sum(array_map(static fn (array $row): float => (float) ($row['total_issued_qty'] ?? 0), $issuanceRows));
$releasedCount = count(array_filter($issuanceRows, static fn (array $row): bool => ($row['status'] ?? '') === 'released'));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Issuance Records</p>
                <p class="kpi-value" id="kpi-rows"><?= esc((string) $totalRows) ?></p>
                <p class="kpi-note">Rows in current report filter.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Requested</p>
                <p class="kpi-value" id="kpi-requested"><?= esc(number_format($totalRequested, 0)) ?></p>
                <p class="kpi-note">Sum of all item requests.</p>
                </article>
                <article class="kpi-card">
                <p class="kpi-label">Total Issued</p>
                <p class="kpi-value" id="kpi-issued"><?= esc(number_format($totalIssued, 0)) ?></p>
                <p class="kpi-note">Actual released quantity aggregate.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Released Records</p>
                <p class="kpi-value" id="kpi-released"><?= esc((string) $releasedCount) ?></p>
                <p class="kpi-note">Completed issuance transactions.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <form class="inline-form" method="get" action="<?= site_url('reports/issuances') ?>">
            <label for="status">Database Status</label>
            <select id="status" name="status">
                <option value="">All</option>
                <?php foreach (['draft', 'submitted', 'approved', 'rejected', 'released', 'cancelled'] as $opt): ?>
                    <option value="<?= esc($opt) ?>" <?= (($status ?? '') === $opt) ? 'selected' : '' ?>><?= esc($opt) ?></option>
                <?php endforeach ?>
            </select>
            <label for="date_from">Date From</label>
            <input id="date_from" type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>">
            <label for="date_to">Date To</label>
            <input id="date_to" type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>">
            <button type="submit" class="btn btn-outline">Apply Server Filter</button>
            <a href="<?= site_url('reports/issuances') ?>" class="btn btn-outline">Reset</a>
        </form>

        <div id="full-issuances-container">
            <div class="table-wrap">
                <table class="table" id="issuances-table" style="table-layout: fixed; width: 100%; min-width: 900px;">
                    <colgroup>
                        <col style="width: 60px;">  <col style="width: 140px;"> <col style="width: 100px;"> <col style="width: 120px;"> <col style="width: 25%;">   <col style="width: 150px;"> <col style="width: 15%;">   <col style="width: 15%;">   </colgroup>
                    <thead>
                        <tr>
                            <th class="sortable numeric" data-col="0">ID</th>
                            <th class="sortable" data-col="1">Issuance #</th>
                            <th class="sortable numeric" data-col="2">Requestor</th>
                            <th class="sortable date" data-col="3">Issue Date</th>
                            <th class="sortable" data-col="4">Department</th>
                            
                            <th class="sortable" data-col="5" id="status-header" title="Click to cycle filters!">Status (All)</th>
                            
                            <th class="sortable numeric" data-col="6" style="text-align: right;">Total Requested</th>
                            <th class="sortable numeric" data-col="7" style="text-align: right;">Total Issued</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (($rows ?? []) === []): ?>
                            <tr class="no-records-row"><td colspan="8" class="empty-state">No records found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($rows as $row): ?>
                                <tr class="issuance-row" style="display: none;" data-status="<?= esc((string) ($row['status'] ?? '')) ?>">
                                    <td><?= esc((string) $row['id']) ?></td>
                                    <td style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--color-brand-700);"><?= esc((string) $row['issuance_number']) ?></td>
                                    <td><?= esc((string) $row['requestor_id']) ?></td>
                                    <td style="font-size: 0.85rem; color: var(--color-text-muted);"><?= esc((string) $row['issue_date']) ?></td>
                                    <td style="font-weight: 500; word-break: break-word;"><?= esc((string) ($row['department'] ?? '')) ?></td>
                                    <td><?= view('components/shared/table_status_badge', ['status' => $row['status'] ?? 'unknown']) ?></td>
                                    <td style="text-align: right; font-weight: 600;"><?= esc((string) $row['total_requested_qty']) ?></td>
                                    <td style="text-align: right; font-weight: 600; color: var(--color-brand-600);"><?= esc((string) $row['total_issued_qty']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
                <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                    Showing records <span id="page-indicator"></span> (Total: <span id="total-indicator"><?= esc((string) $totalRows) ?></span>)
                </p>
                <nav aria-label="Issuances Pagination">
                    <ul class="ci-pager" id="client-pager"></ul>
                </nav>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 15;
        const tbody = document.querySelector('#issuances-table tbody');
        if (!tbody) return;

        const allRows = Array.from(tbody.querySelectorAll('.issuance-row'));
        let currentRows = [...allRows]; 

        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalIndicator = document.getElementById('total-indicator');
        const statusHeader = document.getElementById('status-header');
        
        // KPI DOM Elements
        const kpiRows = document.getElementById('kpi-rows');
        const kpiRequested = document.getElementById('kpi-requested');
        const kpiIssued = document.getElementById('kpi-issued');
        const kpiReleased = document.getElementById('kpi-released');

        if (allRows.length === 0) return;

        // ==========================================
        // 1. COLUMN CYCLE LOGIC
        // ==========================================
        const statusCycle = ['All', 'draft', 'submitted', 'approved', 'rejected', 'released', 'cancelled'];
        let cycleIndex = 0;

        function updateKPIs() {
            let sumRequested = 0, sumIssued = 0, countReleased = 0;
            
            currentRows.forEach(row => {
                sumRequested += parseFloat(row.children[6].innerText.replace(/,/g, '')) || 0;
                sumIssued += parseFloat(row.children[7].innerText.replace(/,/g, '')) || 0;
                
                // Read from the data-attribute we attached to the row
                if(row.getAttribute('data-status') === 'released') {
                    countReleased++;
                }
            });
            
            kpiRows.innerText = currentRows.length;
            kpiRequested.innerText = sumRequested.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            kpiIssued.innerText = sumIssued.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            kpiReleased.innerText = countReleased;
            totalIndicator.innerText = currentRows.length;
        }

        // ==========================================
        // 2. PAGINATION LOGIC
        // ==========================================
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
                if (index >= startPoint && index < endPoint) {
                    row.style.display = '';
                }
            });

            const actualEnd = Math.min(endPoint, totalRows);
            if (pageIndicator) pageIndicator.innerText = totalRows === 0 ? '0' : `${startPoint + 1} - ${actualEnd}`;

            buildPaginationButtons(totalPages);
        }

        function buildPaginationButtons(totalPages) {
            if (!pagerContainer) return;
            pagerContainer.innerHTML = '';
            if (totalPages <= 1) return;

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

        // ==========================================
        // 3. EVENT LISTENER FOR HEADER CLICKS
        // ==========================================
        document.querySelectorAll('#issuances-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));

                // --- THE MAGIC CYCLE FILTER FOR STATUS (Col 5) ---
                if (colIndex === 5) {
                    cycleIndex = (cycleIndex + 1) % statusCycle.length;
                    const activeStatus = statusCycle[cycleIndex];

                    // Update UI text for the header
                    if (activeStatus === 'All') {
                        statusHeader.innerHTML = `Status (All)`;
                    } else {
                        statusHeader.innerHTML = `Status (<span class="filter-active-text">${activeStatus}</span>)`;
                    }

                    // Apply Filter using the hidden data-attribute
                    if (activeStatus === 'All') {
                        currentRows = [...allRows];
                    } else {
                        currentRows = allRows.filter(row => {
                            return row.getAttribute('data-status') === activeStatus.toLowerCase();
                        });
                    }

                    updateKPIs();
                    showPage(1);
                    return; // Exit! Do not run the sorting logic below
                }

                // --- NORMAL SORTING LOGIC FOR OTHER COLUMNS ---
                const isNumericCol = th.classList.contains('numeric');
                const isDateCol = th.classList.contains('date');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 
                
                document.querySelectorAll('#issuances-table th.sortable').forEach(header => {
                    // Don't reset the Status header visually!
                    if(parseInt(header.getAttribute('data-col')) !== 5) {
                        header.classList.remove('asc', 'desc');
                    }
                });
                
                th.classList.add(isAsc ? 'desc' : 'asc');
                
                currentRows.sort((a, b) => {
                    let aText = a.children[colIndex].innerText.trim();
                    let bText = b.children[colIndex].innerText.trim();
                    
                    if (isNumericCol) {
                        aText = aText.replace(/,/g, '');
                        bText = bText.replace(/,/g, '');
                        return (parseFloat(aText) - parseFloat(bText)) * direction;
                    }

                    if (isDateCol) {
                        let dateA = aText === '' ? 0 : new Date(aText).getTime();
                        let dateB = bText === '' ? 0 : new Date(bText).getTime();
                        return (dateA - dateB) * direction;
                    }
                    
                    return aText.localeCompare(bText) * direction;
                });
                
                currentRows.forEach(row => tbody.appendChild(row));
                showPage(1);
            });
        });

        // Initialize
        showPage(1);
    });
</script>
<?= $this->endSection() ?>