<?php

declare(strict_types=1);

$title = 'Issuance - InventoryV2';
$pageTitle = 'Inventory Issuance';
$pageSubtitle = 'Create, review, and track issuance workflow states.';
$crumbs = [
    ['label' => 'Inventory Issuance'],
];

$user = function_exists('auth') ? auth()->user() : null;
$canCreateIssuance = $user !== null && method_exists($user, 'can') && $user->can('inventory.issuance.create');
$canViewInventory = $user !== null && method_exists($user, 'can') && $user->can('inventory.view', 'inventory.quantity.update');
$canViewReports = $user !== null && method_exists($user, 'can') && $user->can('reports.view');
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- V2 DESIGN SYSTEM VARIABLES --- */
    :root {
        --v2-border: #b2e0eb; 
        --v2-title: #00476b;  
        --v2-label: #00668c;  
        --v2-active-bg: #00638a; 
        --v2-text-main: #1e3a8a; /* TRUE Dark Blue */
        --v2-text-muted: #64748b;
    }

    /* --- NO-SCROLL VIEWPORT WRAPPER --- */
    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
        height: calc(100vh - 120px); 
        min-height: 640px;
        overflow: hidden;
    }

    /* --- PASTEL KPI CARDS --- */
    .kpi-grid { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 16px; 
        flex-shrink: 0; 
    }
    
    .kpi-card { 
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        padding: 16px 18px; 
        display: flex; 
        align-items: center; 
        gap: 14px; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
    }

    .kpi-icon-box {
        width: 46px; height: 46px; 
        border-radius: 10px; 
        display: flex; align-items: center; justify-content: center; 
        flex-shrink: 0;
    }

    /* Specific Icon Colors */
    .icon-total { background: #f1f5f9; color: #475569; }        
    .icon-draft { background: #fffbeb; color: #d97706; } 
    .icon-submitted { background: #e0f2fe; color: #0284c7; }   
    .icon-released { background: #ecfccb; color: #16a34a; }   

    .kpi-details { display: flex; flex-direction: column; flex: 1; justify-content: center; min-width: 0; }
    
    .kpi-value { font-size: 1.15rem; font-weight: 800; color: var(--v2-title); line-height: 1.2; margin: 0; }
    .kpi-label { font-size: 0.75rem; font-weight: 500; color: var(--v2-text-muted); margin: 0; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.05em; }

    /* --- V2 TABLE CARD --- */
    .table-card {
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        display: flex;
        flex-direction: column;
        flex: 1; 
        min-height: 0; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
        overflow: hidden;
    }

    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 12px 20px; 
        border-bottom: 1px solid var(--v2-border); 
        background: #ffffff; 
        flex-shrink: 0;
        flex-wrap: wrap; 
    }
    
    .table-toolbar h3 { margin: 0; font-size: 1.05rem; color: var(--v2-title); font-weight: 800; }
    
    /* --- FIXED INLINE TOOLBAR CONTROLS --- */
    .toolbar-controls { 
        display: flex; 
        gap: 10px; 
        align-items: center; 
        flex: 1; 
        justify-content: flex-end; 
        flex-wrap: nowrap; 
    }
    
    .search-wrap { position: relative; width: 260px; flex-shrink: 0; }
    .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 14px; height: 14px; }
    
    .search-input, .filter-select { 
        padding: 6px 12px; 
        font-size: 0.85rem; 
        border: 1px solid var(--v2-border); 
        border-radius: 6px; 
        outline: none; 
        color: var(--v2-text-main);
        background: #ffffff;
        transition: all 0.2s;
        height: 34px; 
    }
    .search-input { width: 100%; padding-left: 30px; }
    .filter-select { width: 140px; flex-shrink: 0; cursor: pointer; } 
    
    .search-input:focus, .filter-select:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }

    /* Scrollable Table Area */
    .table-scroll-container {
        flex: 1;
        overflow-y: auto; 
        background: #ffffff;
    }

    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .modern-table th { 
        position: sticky; top: 0; z-index: 10;
        background: #ffffff !important; 
        padding: 14px 16px; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        font-weight: 800; 
        color: var(--v2-title); 
        border-bottom: 2px solid var(--v2-border); 
        text-align: left; 
        letter-spacing: 0.05em; 
        vertical-align: middle; 
    }
    .modern-table td { padding: 12px 16px; font-size: 0.85rem; color: var(--v2-text-main); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .modern-table tr:hover td { background: #f8fafc; }

    /* --- SORTABLE HEADERS --- */
    th.sortable { cursor: pointer; padding-right: 18px !important; user-select: none; transition: background 0.2s ease, color 0.2s ease; }
    th.sortable:hover { background-color: #f1f5f9 !important; color: var(--v2-title) !important; }
    th.sortable::after { content: '↕'; position: absolute; right: 6px; top: 50%; transform: translateY(-50%); font-size: 0.75rem; opacity: 0.3; color: var(--v2-title); }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--v2-label); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--v2-label); font-weight: bold; }
    .filter-active-text { color: var(--v2-label); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; display: inline-block; }

    /* --- PAGINATION FOOTER --- */
    .table-footer {
        padding: 10px 20px;
        border-top: 1px solid var(--v2-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
        flex-shrink: 0;
    }
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li a, .ci-pager li span {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; font-size: 0.75rem; min-width: 28px;
        border: 1px solid var(--v2-border); border-radius: 4px;
        background: #ffffff; color: var(--v2-label);
        text-decoration: none; font-weight: 700; transition: all 0.2s ease;
    }
    .ci-pager li a:hover { background: rgba(178, 224, 235, 0.3); border-color: var(--v2-label); }
    .ci-pager li.active a { background: var(--v2-label); color: #ffffff; border-color: var(--v2-label); }
    .ci-pager li.disabled a { opacity: 0.5; background: #f1f5f9; color: var(--v2-text-muted); pointer-events: none; border-color: #cbd5e1; }
    /* --- V2 ACTION BUTTONS --- */
    .action-row { display: flex; gap: 6px; align-items: center; justify-content: flex-end; flex-wrap: nowrap; }

    .btn-link-view { font-size: 0.75rem; color: var(--v2-label); text-decoration: none; font-weight: 800; padding: 4px 8px; border-radius: 4px; transition: background 0.2s ease; cursor: pointer; border: none; background: transparent; }
    .btn-link-view:hover { color: var(--v2-title); background: rgba(178, 224, 235, 0.3); }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php if ($canCreateIssuance): ?>
    <a class="btn btn-primary" href="<?= site_url('inventory/issuance/create') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Create Issuance</a>
<?php endif ?>
<?php $issuanceListExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('inventory/issuance') . '?' . $issuanceListExportQuery ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<?php if ($canViewInventory): ?>
    <a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Inventory Quantities</a>
<?php endif ?>
<?php if ($canViewReports): ?>
    <a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Reports</a>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $issuances ?? [];
$totalIssuances = count($rows);
$draftIssuances = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'draft'));
$submittedIssuances = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'submitted'));
$releasedIssuances = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'released'));
?>
<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Inventory Issuance</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-total"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-visible"><?= esc((string) $totalIssuances) ?></span>
                    <span class="kpi-label">Visible Records</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-amber">
                <div class="kpi-icon-box icon-draft"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-draft" style="color: #b45309;"><?= esc((string) $draftIssuances) ?></span>
                    <span class="kpi-label">Draft Status</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-submitted"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-submitted" style="color: var(--v2-label);"><?= esc((string) $submittedIssuances) ?></span>
                    <span class="kpi-label">Submitted</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-released"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-released" style="color: #15803d;"><?= esc((string) $releasedIssuances) ?></span>
                    <span class="kpi-label">Released</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Issuance Queue</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Manage requests and deduct stocks.</p>
            </div>
            
            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Search number, department, or ID" autocomplete="off">
                </div>
                
                <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('inventory/issuance') ?>" style="margin: 0; display: flex; gap: 8px;">
                    <?php $issuanceStatusLabels = ['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'rejected' => 'Rejected', 'released' => 'Released', 'cancelled' => 'Cancelled']; ?>
                    <select id="status" name="status" class="filter-select" aria-label="Filter issuance by status">
                        <option value="">All Statuses</option>
                        <?php foreach ($issuanceStatusLabels as $option => $label): ?>
                            <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach ?>
                    </select>
                    <button type="button" class="btn btn-outline" id="btn-clear-search" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px; height: 34px;">Clear</button>
                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 800; border-radius: 6px; background: var(--v2-label); color: #ffffff; border: none;">Filter Server</button>
                </form>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="issuance-table" style="table-layout: fixed; width: 100%; min-width: 800px;">
                <colgroup>
                    <col style="width: 80px;">  
                    <col style="width: 150px;"> 
                    <col style="width: 100px;"> 
                    <col style="width: 120px;"> 
                    <col style="width: 25%;">   
                    <col style="width: 150px;"> 
                    <col style="width: 100px;"> 
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">Issuance Number</th>
                        <th class="sortable numeric" data-col="2">Requestor</th>
                        <th class="sortable date" data-col="3">Issue Date</th>
                        <th class="sortable" data-col="4">Department</th>
                        <th class="sortable" data-col="5" id="status-header" title="Click to cycle status filters!">
                            Status <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>
                        </th>
                        <th class="actions" style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($issuances ?? []) === []): ?>
                        <tr class="no-records-row">
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No issuance records found.</strong><br>
                                <span style="font-size: 0.8rem;">Adjust your filters to see more results.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($issuances as $issuance): ?>
                            <tr class="issuance-row" style="display: none;" data-status="<?= esc(strtolower((string) ($issuance['status'] ?? ''))) ?>">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) $issuance['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 800; color: var(--v2-label);"><?= esc((string) $issuance['issuance_number']) ?></td>
                                <td style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--v2-text-muted);">#<?= esc((string) $issuance['requestor_id']) ?></td>
                                <td style="font-size: 0.85rem; font-weight: 600; color: var(--v2-text-main);"><?= esc((string) $issuance['issue_date']) ?></td>
                                <td style="font-weight: 600; color: var(--v2-text-main); word-break: break-word;"><?= esc((string) ($issuance['department'] ?? '-')) ?></td>
                                <td><?= view('components/shared/table_status_badge', ['status' => $issuance['status'] ?? 'unknown']) ?></td>
                                <td style="text-align: right;">
                                    <div class="action-row">
                                        <a class="btn-link-view" href="<?= site_url('inventory/issuance/' . $issuance['id']) ?>">View</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalIssuances) ?></span>)
            </p>
            <nav aria-label="Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 15; 
        const tbodyMain = document.querySelector('#issuance-table tbody');
        
        if (tbodyMain && tbodyMain.querySelector('.issuance-row')) {
            const allRows = Array.from(tbodyMain.querySelectorAll('.issuance-row'));
            let currentRows = [...allRows]; 

            const pagerContainer = document.getElementById('client-pager');
            const pageIndicator = document.getElementById('page-indicator');
            const totalIndicator = document.getElementById('total-indicator');
            const statusHeader = document.getElementById('status-header');
            
            const kpiVisible = document.getElementById('kpi-visible');
            const kpiDraft = document.getElementById('kpi-draft');
            const kpiSubmitted = document.getElementById('kpi-submitted');
            const kpiReleased = document.getElementById('kpi-released');

            const searchInput = document.getElementById('instant-search-input');
            const clearBtn = document.getElementById('btn-clear-search');

            const statusCycle = ['All', 'draft', 'submitted', 'approved', 'rejected', 'released', 'cancelled'];
            let cycleIndex = 0;
            let currentStatusFilter = 'All';

            function toTitleCase(str) {
                return str.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            }

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

            if(statusHeader) {
                statusHeader.addEventListener('click', (e) => {
                    e.stopPropagation(); 
                    
                    cycleIndex = (cycleIndex + 1) % statusCycle.length;
                    currentStatusFilter = statusCycle[cycleIndex];

                    if (currentStatusFilter === 'All') {
                        statusHeader.innerHTML = `Status <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>`;
                    } else {
                        statusHeader.innerHTML = `Status <br><span class="filter-active-text">${toTitleCase(currentStatusFilter)}</span>`;
                    }
                    
                    applyFilters();
                });
            }

            document.querySelectorAll('#issuance-table th.sortable').forEach(th => {
                if (parseInt(th.getAttribute('data-col')) === 5) return; 

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

                if (pagerContainer) {
                    pagerContainer.innerHTML = '';
                    if (totalPages > 1) {
                        let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;
                        
                        for (let i = 1; i <= totalPages; i++) {
                            html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`;
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
