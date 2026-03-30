<?php

declare(strict_types=1);

$title = 'Low Stock Report - InventoryV2';
$pageTitle = 'Report: Low Stock';
$pageSubtitle = 'Items with available quantity at or below threshold.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Low Stock'],
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
    .icon-available { background: #ecfccb; color: #16a34a; } 
    .icon-critical { background: #fef2f2; color: #ef4444; }   
    .icon-expiry { background: #fffbeb; color: #d97706; }   

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
    
    .toolbar-controls { 
        display: flex; 
        gap: 10px; 
        align-items: center; 
        flex: 1; 
        justify-content: flex-end; 
    }
    
    .search-wrap { position: relative; width: 240px; }
    .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 14px; height: 14px; }
    
    .search-input, .filter-select, .input-threshold { 
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
    .filter-select { width: 150px; cursor: pointer; }
    .input-threshold { width: 80px; text-align: center; font-weight: 800; color: var(--v2-label); }
    
    .search-input:focus, .filter-select:focus, .input-threshold:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }

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
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $lowStockExportQuery = http_build_query(['export' => 'csv', 'threshold' => ($threshold ?? 10)]); ?>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') . '?' . $lowStockExportQuery ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$thresholdValue = (float) ($threshold ?? 10);
$lowStockRows = array_filter($rows ?? [], static fn (array $row): bool => (float) ($row['available_qty'] ?? 0) <= $thresholdValue);
$lowStockRows = array_values($lowStockRows); 

$totalRows = count($lowStockRows);
$totalAvailable = array_sum(array_map(static fn (array $row): float => (float) ($row['available_qty'] ?? 0), $lowStockRows));
$criticalRows = count(array_filter($lowStockRows, static fn (array $row): bool => (float) ($row['available_qty'] ?? 0) <= 0));
$nearExpiryRows = count(array_filter(
    $lowStockRows,
    static fn (array $row): bool => isset($row['expiry_date']) && (string) $row['expiry_date'] !== '' && strtotime((string) $row['expiry_date']) <= strtotime('+60 days')
));
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Low Stock Analysis</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-rows"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-rows"><?= esc((string) $totalRows) ?></span>
                    <span class="kpi-label">Low Stock SKUs</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-available"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-available"><?= esc(app_format_quantity($totalAvailable)) ?></span>
                    <span class="kpi-label">Total Available</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-red">
                <div class="kpi-icon-box icon-critical"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-critical" style="color: #ef4444;"><?= esc((string) $criticalRows) ?></span>
                    <span class="kpi-label">Critical (≤ 0)</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-amber">
                <div class="kpi-icon-box icon-expiry"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-expiry"><?= esc((string) $nearExpiryRows) ?></span>
                    <span class="kpi-label">Near Expiry (60d)</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Replenishment List</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Threshold: <strong><?= esc(app_format_quantity($thresholdValue)) ?> units</strong></p>
            </div>
            
            <div class="toolbar-controls">
                <form method="get" action="<?= site_url('reports/low-stock') ?>" style="display: flex; gap: 8px; align-items: center; margin: 0; border-right: 1px solid var(--v2-border); padding-right: 12px; margin-right: 4px;">
                    <span style="font-size: 0.7rem; font-weight: 800; color: var(--v2-text-muted); text-transform: uppercase;">Threshold:</span>
                    <input type="number" name="threshold" value="<?= esc(app_format_quantity($thresholdValue, '', 3, false)) ?>" class="input-threshold" min="0">
                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 800; background: var(--v2-label); border: none; border-radius: 6px; color: white;">Apply</button>
                </form>

                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Quick search name or ID" autocomplete="off">
                </div>
                
                <select id="filter-stock-status" class="filter-select">
                    <option value="all">All Low Stock</option>
                    <option value="critical">Critical (≤ 0)</option>
                    <option value="warning">Warning (> 0)</option>
                </select>

                <button type="button" class="btn btn-outline" id="btn-clear-search" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px; height: 34px;">Clear</button>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="low-table" style="table-layout: fixed; width: 100%; min-width: 900px;">
                <colgroup>
                    <col style="width: 60px;">
                    <col style="width: 25%;">
                    <col style="width: 80px;">
                    <col style="width: 12%;">
                    <col style="width: 12%;">
                    <col style="width: 110px;">
                    <col style="width: 130px;">
                    <col style="width: 100px;">
                    <col style="width: 100px;">
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">Item</th>
                        <th class="sortable" data-col="2">Unit</th>
                        <th class="sortable" data-col="3">Batch</th>
                        <th class="sortable" data-col="4">Lot</th>
                        <th class="sortable date" data-col="5">Expiry</th>
                        <th class="sortable numeric asc" data-col="6" style="text-align: right;">Available</th>
                        <th class="sortable numeric" data-col="7" style="text-align: right;">On Hand</th>
                        <th class="sortable numeric" data-col="8" style="text-align: right;">Reserved</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($lowStockRows === []): ?>
                        <tr class="no-records-row">
                            <td colspan="9" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>Great news! No low stock items found.</strong><br>
                                <span style="font-size: 0.8rem;">All items are currently above the <?= esc(app_format_quantity($thresholdValue)) ?> unit threshold.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($lowStockRows as $row): ?>
                            <?php 
                                $availQty = (float) $row['available_qty'];
                                if ($availQty <= 0) {
                                    $qtyStyle = 'color: #ef4444; font-weight: 900;';
                                } elseif ($availQty <= ($thresholdValue / 2)) {
                                    $qtyStyle = 'color: #ea580c; font-weight: 800;'; 
                                } else {
                                    $qtyStyle = 'color: #b45309; font-weight: 700;';
                                }

                                $expiryRaw = (string) ($row['expiry_date'] ?? '');
                                $daysUntilExpiry = 9999; 
                                $expiryClass = '';
                                if ($expiryRaw !== '') {
                                    $expDate = strtotime($expiryRaw);
                                    $now = time();
                                    $daysUntilExpiry = ($expDate - $now) / (60 * 60 * 24);
                                    if ($daysUntilExpiry < 0) $expiryClass = 'color: #ef4444; font-weight: 800;';
                                    elseif ($daysUntilExpiry <= 60) $expiryClass = 'color: #d97706; font-weight: 800;'; 
                                }
                            ?>
                            <tr class="low-row" style="display: none;" data-expiry-days="<?= esc((string) $daysUntilExpiry) ?>">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) $row['id']) ?></td>
                                <td style="font-weight: 800; color: var(--v2-label); word-break: break-word;"><?= esc((string) $row['item_name']) ?></td>
                                <td style="font-size: 0.85rem; color: var(--v2-text-muted);"><?= esc((string) $row['unit']) ?></td>
                                <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($row['batch_no'] ?? '')) ?></td>
                                <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($row['lot_no'] ?? '')) ?></td>
                                <td style="font-size: 0.85rem; <?= $expiryClass ?>"><?= esc($expiryRaw) ?></td>
                                <td style="text-align: right; font-size: 1rem; <?= $qtyStyle ?>"><?= esc(app_format_quantity($row['available_qty'] ?? 0)) ?></td>
                                <td style="text-align: right; font-weight: 600; color: var(--v2-text-main);"><?= esc(app_format_quantity($row['on_hand_qty'] ?? 0)) ?></td>
                                <td style="text-align: right; font-weight: 600; color: var(--v2-text-muted);"><?= esc(app_format_quantity($row['reserved_qty'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Filtered: <span id="total-indicator"><?= esc((string) $totalRows) ?></span>)
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
        const tbody = document.querySelector('#low-table tbody');
        if (!tbody) return;

        const allRows = Array.from(tbody.querySelectorAll('.low-row'));
        let currentRows = [...allRows]; 

        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalIndicator = document.getElementById('total-indicator');
        
        const kpiRows = document.getElementById('kpi-rows');
        const kpiAvailable = document.getElementById('kpi-available');
        const kpiCritical = document.getElementById('kpi-critical');
        const kpiExpiry = document.getElementById('kpi-expiry');

        const searchInput = document.getElementById('instant-search-input');
        const statusFilter = document.getElementById('filter-stock-status');
        const clearBtn = document.getElementById('btn-clear-search');

        if (allRows.length === 0) return;

        // Sort by lowest available first by default
        allRows.sort((a, b) => {
            const aQty = parseFloat(a.children[6].innerText.replace(/,/g, '')) || 0;
            const bQty = parseFloat(b.children[6].innerText.replace(/,/g, '')) || 0;
            return aQty - bQty;
        });
        allRows.forEach(row => tbody.appendChild(row));
        currentRows = [...allRows];

        function applySearch() {
            const query = searchInput.value.toLowerCase().trim();
            const statusVal = statusFilter.value;

            currentRows = allRows.filter(row => {
                const id = row.children[0].innerText.toLowerCase();
                const name = row.children[1].innerText.toLowerCase();
                const batch = row.children[3].innerText.toLowerCase();
                const qty = parseFloat(row.children[6].innerText.replace(/,/g, '')) || 0;

                const matchesText = query === '' || id.includes(query) || name.includes(query) || batch.includes(query);
                let matchesStatus = true;
                if (statusVal === 'critical') matchesStatus = qty <= 0;
                else if (statusVal === 'warning') matchesStatus = qty > 0;

                return matchesText && matchesStatus;
            });

            if (query !== '') {
                currentRows.sort((a, b) => {
                    const aId = a.children[0].innerText.toLowerCase();
                    const aName = a.children[1].innerText.toLowerCase();
                    const bId = b.children[0].innerText.toLowerCase();
                    const bName = b.children[1].innerText.toLowerCase();
                    const aScore = aId.includes(query) ? 1 : (aName.includes(query) ? 2 : 3);
                    const bScore = bId.includes(query) ? 1 : (bName.includes(query) ? 2 : 3);
                    if (aScore !== bScore) return aScore - bScore;
                    return (parseFloat(a.children[6].innerText) - parseFloat(b.children[6].innerText));
                });
            }

            currentRows.forEach(row => tbody.appendChild(row));
            updateKPIs();
            showPage(1);
        }

        function updateKPIs() {
            let sumAvailable = 0, countCritical = 0, countExpiry = 0;
            currentRows.forEach(row => {
                const availQty = parseFloat(row.children[6].innerText.replace(/,/g, '')) || 0;
                const daysExpiry = parseFloat(row.getAttribute('data-expiry-days')) || 9999;
                sumAvailable += availQty;
                if (availQty <= 0) countCritical++;
                if (daysExpiry <= 60) countExpiry++;
            });
            kpiRows.innerText = currentRows.length;
            kpiAvailable.innerText = sumAvailable.toLocaleString('en-US');
            kpiCritical.innerText = countCritical;
            kpiExpiry.innerText = countExpiry;
            totalIndicator.innerText = currentRows.length;
        }

        if(searchInput) searchInput.addEventListener('input', applySearch);
        if(statusFilter) statusFilter.addEventListener('change', applySearch);
        if(clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                statusFilter.value = 'all';
                applySearch();
            });
        }

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
            buildPager(totalPages);
        }

        function buildPager(totalPages) {
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

        document.querySelectorAll('#low-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const isNumericCol = th.classList.contains('numeric');
                const isDateCol = th.classList.contains('date');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 
                document.querySelectorAll('#low-table th.sortable').forEach(header => header.classList.remove('asc', 'desc'));
                th.classList.add(isAsc ? 'desc' : 'asc');
                currentRows.sort((a, b) => {
                    let aT = a.children[colIndex].innerText.trim().replace(/,/g, '');
                    let bT = b.children[colIndex].innerText.trim().replace(/,/g, '');
                    if (isNumericCol) return (parseFloat(aT) - parseFloat(bT)) * direction;
                    if (isDateCol) return (new Date(aT).getTime() - new Date(bT).getTime()) * direction;
                    return aT.localeCompare(bT) * direction;
                });
                currentRows.forEach(row => tbody.appendChild(row));
                showPage(1);
            });
        });

        showPage(1);
    });
</script>
<?= $this->endSection() ?>
