<?php

declare(strict_types=1);

$title = 'Stock Movement Report - InventoryV2';
$pageTitle = 'Report: Stock Movements';
$pageSubtitle = 'Inbound and outbound stock movement history.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Stock Movements'],
];

$movementTypeLabels = [
    'receiving'      => 'Receiving',
    'issuance'       => 'Issuance',
    'adjustment_in'  => 'Stock Adjustment In',
    'adjustment_out' => 'Stock Disposal',
    'return'         => 'Return',
];

$referenceTypeLabels = [
    'receiving'         => 'Receiving',
    'issuance'          => 'Issuance',
    'manual_adjustment' => 'Stock Disposal',
];
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

    .icon-rows { background: #f1f5f9; color: #475569; }        
    .icon-in { background: #ecfccb; color: #16a34a; } 
    .icon-out { background: #fef2f2; color: #ef4444; }   
    .icon-distinct { background: #e0f2fe; color: #0284c7; }   

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
    }
    
    .table-toolbar h3 { margin: 0; font-size: 1.05rem; color: var(--v2-title); font-weight: 800; }
    
    /* Toolbar Controls (Server-side date/type filters) */
    .toolbar-controls { 
        display: flex; 
        gap: 8px; 
        align-items: center; 
        flex: 1; 
        justify-content: flex-end; 
    }
    
    .filter-group { display: flex; align-items: center; gap: 6px; }
    .filter-label { font-size: 0.7rem; font-weight: 800; color: var(--v2-text-muted); text-transform: uppercase; }

    .input-v2, .select-v2 { 
        padding: 4px 10px; 
        font-size: 0.8rem; 
        border: 1px solid var(--v2-border); 
        border-radius: 6px; 
        outline: none; 
        color: var(--v2-text-main);
        background: #ffffff;
        height: 32px;
    }
    .input-v2:focus, .select-v2:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }

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
    /* Movement Type Badge */
    .m-badge { background: #f1f5f9; color: var(--v2-text-muted); padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; border: 1px solid #e2e8f0; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $stockMovementsExportQuery = http_build_query(['export' => 'csv', 'date_from' => ($date_from ?? ''), 'date_to' => ($date_to ?? ''), 'movement_type' => ($movement_type ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') . '?' . $stockMovementsExportQuery ?>" data-filtered-csv-export data-export-table="#movements-table" data-export-row-selector=".movement-row" data-export-filename="stock_movements.csv" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Low Stock</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$movementRows = $rows ?? [];
$totalMovements = count($movementRows);
$totalIn = array_sum(array_map(static fn (array $row): float => (float) ($row['qty_in'] ?? 0), $movementRows));
$totalOut = array_sum(array_map(static fn (array $row): float => (float) ($row['qty_out'] ?? 0), $movementRows));
$distinctItems = count(array_unique(array_map(static fn (array $row): string => (string) ($row['item_name'] ?? ''), $movementRows)));
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Stock Movement Report</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-rows"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-rows"><?= esc((string) $totalMovements) ?></span>
                    <span class="kpi-label">Movement Rows</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-in"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"></polyline><polyline points="9 21 3 21 3 15"></polyline><line x1="21" y1="3" x2="14" y2="10"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-in" style="color: #16a34a;"><?= esc(app_format_quantity($totalIn)) ?></span>
                    <span class="kpi-label">Total Qty In</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-red">
                <div class="kpi-icon-box icon-out"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 15 21 21 15 21"></polyline><polyline points="3 9 3 3 9 3"></polyline><line x1="21" y1="21" x2="14" y2="14"></line><line x1="3" y1="3" x2="10" y2="10"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-out" style="color: #ef4444;"><?= esc(app_format_quantity($totalOut)) ?></span>
                    <span class="kpi-label">Total Qty Out</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-distinct"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-distinct"><?= esc((string) $distinctItems) ?></span>
                    <span class="kpi-label">Distinct Items</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Movement Log</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Inbound and outbound ledger.</p>
            </div>
            
            <div class="toolbar-controls">
                <form method="get" action="<?= site_url('reports/stock-movements') ?>" style="display: flex; gap: 10px; align-items: center; margin: 0;">
                    <div class="filter-group">
                        <span class="filter-label">From:</span>
                        <input type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>" class="input-v2">
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">To:</span>
                        <input type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>" class="input-v2">
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">Type:</span>
                        <select name="movement_type" class="select-v2">
                            <option value="">All Types</option>
                            <?php foreach (['receiving', 'issuance', 'adjustment_in', 'adjustment_out', 'return'] as $type): ?>
                                <option value="<?= esc($type) ?>" <?= (($movement_type ?? '') === $type) ? 'selected' : '' ?>><?= esc($movementTypeLabels[$type] ?? ucwords(str_replace('_', ' ', $type))) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 800; background: var(--v2-label); border: none; border-radius: 6px; color: white;">Apply</button>
                    <a href="<?= site_url('reports/stock-movements') ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 800; border-radius: 6px; text-decoration: none;">Reset</a>
                </form>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="movements-table" style="table-layout: fixed; width: 100%;">
                <colgroup>
                    <col style="width: 50px;">
                    <col style="width: 150px;">
                    <col style="width: 130px;">
                    <col style="width: 160px;">
                    <col style="width: 25%;">
                    <col style="width: 80px;">
                    <col style="width: 90px;">
                    <col style="width: 90px;">
                    <col style="width: 90px;">
                    <col style="width: 160px;">
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">Movement #</th>
                        <th class="sortable" data-col="2" id="type-header" title="Click to cycle filters!">Type <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span></th>
                        <th class="sortable" data-col="3">Reference</th>
                        <th class="sortable" data-col="4">Item Name</th>
                        <th class="sortable" data-col="5">Unit</th>
                        <th class="sortable numeric" data-col="6" style="text-align: right;">Qty In</th>
                        <th class="sortable numeric" data-col="7" style="text-align: right;">Qty Out</th>
                        <th class="sortable numeric" data-col="8" style="text-align: right;">Balance</th>
                        <th class="sortable date" data-col="9">Performed At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($rows ?? []) === []): ?>
                        <tr class="no-records-row">
                            <td colspan="10" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No records found.</strong><br>
                                <span style="font-size: 0.8rem;">Adjust your date filters or type to see history.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr class="movement-row" style="display: none;" data-movement-type="<?= esc((string) ($row['movement_type'] ?? '')) ?>">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) $row['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 800; color: var(--v2-label); font-size: 0.85rem;"><?= esc((string) $row['movement_number']) ?></td>
                                <td><span class="m-badge"><?= esc($movementTypeLabels[(string) ($row['movement_type'] ?? '')] ?? ucwords(str_replace('_', ' ', (string) ($row['movement_type'] ?? '')))) ?></span></td>
                                <td style="font-size: 0.8rem; color: var(--v2-text-muted); font-weight: 600;">
                                    <?= esc($referenceTypeLabels[(string) ($row['reference_type'] ?? '')] ?? ucwords(str_replace('_', ' ', (string) ($row['reference_type'] ?? '')))) ?>
                                    <?= ($row['reference_id'] ?? null) !== null ? ' #' . esc((string) $row['reference_id']) : '' ?>
                                </td>
                                <td style="font-weight: 700; white-space: normal; overflow: visible; text-overflow: clip; overflow-wrap: anywhere;" title="<?= esc((string) $row['item_name']) ?>"><?= esc((string) $row['item_name']) ?></td>
                                <td style="font-size: 0.85rem; color: var(--v2-text-muted);"><?= esc((string) $row['unit']) ?></td>
                                <td style="text-align: right; color: #16a34a; font-weight: 800;"><?= esc(app_format_quantity($row['qty_in'] ?? 0)) ?></td>
                                <td style="text-align: right; color: #ef4444; font-weight: 800;"><?= esc(app_format_quantity($row['qty_out'] ?? 0)) ?></td>
                                <td style="text-align: right; font-weight: 900; color: var(--v2-title);"><?= esc(app_format_quantity($row['balance_after'] ?? 0)) ?></td>
                                <td style="font-size: 0.85rem; font-weight: 600;"><?= esc((string) $row['performed_at']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalMovements) ?></span>)
            </p>
            <nav aria-label="Movements Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 15;
        const tbody = document.querySelector('#movements-table tbody');
        if (!tbody) return;

        const allRows = Array.from(tbody.querySelectorAll('.movement-row'));
        let currentRows = [...allRows]; 

        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalIndicator = document.getElementById('total-indicator');
        const typeHeader = document.getElementById('type-header');
        
        const kpiRows = document.getElementById('kpi-rows');
        const kpiIn = document.getElementById('kpi-in');
        const kpiOut = document.getElementById('kpi-out');
        const kpiDistinct = document.getElementById('kpi-distinct');

        if (allRows.length === 0) return;

        const typeCycle = ['All', 'receiving', 'issuance', 'adjustment_in', 'adjustment_out', 'return'];
        const typeLabels = {
            receiving: 'Receiving',
            issuance: 'Issuance',
            adjustment_in: 'Stock Adjustment In',
            adjustment_out: 'Stock Disposal',
            return: 'Return',
        };
        let cycleIndex = 0;

        function updateKPIs() {
            let sumIn = 0, sumOut = 0;
            let uniqueItems = new Set();
            
            currentRows.forEach(row => {
                sumIn += parseFloat(row.children[6].innerText.replace(/,/g, '')) || 0;
                sumOut += parseFloat(row.children[7].innerText.replace(/,/g, '')) || 0;
                uniqueItems.add(row.children[4].innerText.trim());
            });
            
            kpiRows.innerText = currentRows.length;
            kpiIn.innerText = sumIn.toLocaleString('en-US');
            kpiOut.innerText = sumOut.toLocaleString('en-US');
            kpiDistinct.innerText = uniqueItems.size;
            totalIndicator.innerText = currentRows.length;
        }

        function showPage(page) {
            const totalRows = currentRows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);
            let currentPage = page;

            if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

            const startPoint = (currentPage - 1) * rowsPerPage;
            const endPoint = startPoint + rowsPerPage;

            if (window.InventoryV2Hci && typeof window.InventoryV2Hci.markFilteredRows === 'function') {
                window.InventoryV2Hci.markFilteredRows(allRows, currentRows);
            }

            allRows.forEach(row => row.style.display = 'none');
            currentRows.forEach((row, index) => {
                if (index >= startPoint && index < endPoint) row.style.display = '';
            });

            const actualEnd = Math.min(endPoint, totalRows);
            if (pageIndicator) pageIndicator.innerText = totalRows === 0 ? '0' : `${startPoint + 1} - ${actualEnd}`;

            buildPager(currentPage, totalPages);
        }

        function buildPager(currentPage, totalPages) {
            if (!pagerContainer) return;
            pagerContainer.innerHTML = '';
            if (totalPages <= 1) return;

            let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;
            for (let i = 1; i <= totalPages; i++) {
                html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`;
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

        document.querySelectorAll('#movements-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));

                if (colIndex === 2) {
                    cycleIndex = (cycleIndex + 1) % typeCycle.length;
                    const activeType = typeCycle[cycleIndex];
                    typeHeader.innerHTML = activeType === 'All'
                        ? `Type (All)`
                        : `Type <br><span class="filter-active-text">${typeLabels[activeType] ?? activeType.replace('_', ' ')}</span>`;

                    currentRows = activeType === 'All'
                        ? [...allRows]
                        : allRows.filter(row => row.getAttribute('data-movement-type') === activeType);
                    updateKPIs();
                    showPage(1);
                    return;
                }

                const isNumericCol = th.classList.contains('numeric');
                const isDateCol = th.classList.contains('date');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 

                document.querySelectorAll('#movements-table th.sortable').forEach(header => {
                    if(parseInt(header.getAttribute('data-col')) !== 2) header.classList.remove('asc', 'desc');
                });
                th.classList.add(isAsc ? 'desc' : 'asc');

                currentRows.sort((a, b) => {
                    let aText = a.children[colIndex].innerText.trim().replace(/,/g, '');
                    let bText = b.children[colIndex].innerText.trim().replace(/,/g, '');
                    if (isNumericCol) return (parseFloat(aText) - parseFloat(bText)) * direction;
                    if (isDateCol) return (new Date(aText).getTime() - new Date(bText).getTime()) * direction;
                    return aText.localeCompare(bText) * direction;
                });

                currentRows.forEach(row => tbody.appendChild(row));
                showPage(1);
            });
        });

        showPage(1);
    });
</script>
<?= $this->endSection() ?>
