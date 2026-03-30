<?php

declare(strict_types=1);

$title = 'Stock Balance Report - InventoryV2';
$pageTitle = 'Report: Stock Balance';
$pageSubtitle = 'Current on-hand, reserved, and available balances.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Stock Balance'],
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

    /* Specific Icon Colors for Report */
    .icon-total { background: #f1f5f9; color: #475569; }        
    .icon-onhand { background: #e0f2fe; color: #0284c7; } 
    .icon-reserved { background: #fffbeb; color: #d97706; }   
    .icon-available { background: #ecfccb; color: #16a34a; }   

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
        flex-wrap: wrap; 
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
    .filter-select { width: 160px; flex-shrink: 0; cursor: pointer; } 
    
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
<?php $stockBalanceExportQuery = http_build_query(['export' => 'csv', 'q' => ($keyword ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') . '?' . $stockBalanceExportQuery ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Low Stock</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$balanceRows = $rows ?? [];
$totalSkus = count($balanceRows);
$onHand = array_sum(array_map(static fn (array $row): float => (float) ($row['on_hand_qty'] ?? 0), $balanceRows));
$reserved = array_sum(array_map(static fn (array $row): float => (float) ($row['reserved_qty'] ?? 0), $balanceRows));
$available = array_sum(array_map(static fn (array $row): float => (float) ($row['available_qty'] ?? 0), $balanceRows));
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Stock Balance Report</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-total"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-skus"><?= esc((string) $totalSkus) ?></span>
                    <span class="kpi-label">Visible SKUs</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-onhand"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-onhand"><?= esc(app_format_quantity($onHand)) ?></span>
                    <span class="kpi-label">On Hand</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-amber">
                <div class="kpi-icon-box icon-reserved"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-reserved" style="color: #d97706;"><?= esc(app_format_quantity($reserved)) ?></span>
                    <span class="kpi-label">Reserved</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-available"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-available" style="color: #15803d;"><?= esc(app_format_quantity($available)) ?></span>
                    <span class="kpi-label">Available</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Stock Balances</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Real-time inventory levels across all items.</p>
            </div>
            
            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Search ID, name, or batch" autocomplete="off">
                </div>
                
                <select id="filter-stock-status" class="filter-select">
                    <option value="all">All Stock Levels</option>
                    <option value="in_stock">In Stock (> 0)</option>
                    <option value="low_stock">Low Stock (≤ 10)</option>
                    <option value="out_of_stock">Out of Stock (0)</option>
                </select>

                <select id="filter-expiry-status" class="filter-select">
                    <option value="all">All Expiries</option>
                    <option value="expired">Expired</option>
                    <option value="expiring_30">Expiring ≤ 30 Days</option>
                    <option value="expiring_90">Expiring ≤ 90 Days</option>
                </select>

                <button type="button" class="btn btn-outline" id="btn-clear-filters" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px; height: 34px;">Clear</button>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="balance-table" style="table-layout: fixed; width: 100%; min-width: 1050px;">
                <colgroup>
                    <col style="width: 60px;">  
                    <col style="width: 25%;">   
                    <col style="width: 80px;">  
                    <col style="width: 12%;">   
                    <col style="width: 10%;">   
                    <col style="width: 100px;"> 
                    <col style="width: 10%;">   
                    <col style="width: 10%;">   
                    <col style="width: 10%;">   
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
                        <th class="sortable numeric" data-col="6" style="text-align: right;">On Hand</th>
                        <th class="sortable numeric" data-col="7" style="text-align: right;">Reserved</th>
                        <th class="sortable numeric" data-col="8" style="text-align: right;">Available</th>
                        <th class="sortable numeric" data-col="9" style="text-align: right;">Avg Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($balanceRows ?? []) === []): ?>
                        <tr class="no-records-row">
                            <td colspan="10" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No records found.</strong><br>
                                <span style="font-size: 0.8rem;">Adjust your filters to see more results.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($balanceRows as $row): ?>
                            <?php 
                                $expiryRaw = (string) ($row['expiry_date'] ?? '');
                                $daysUntilExpiry = 9999; 
                                $expiryClass = '';
                                if ($expiryRaw !== '') {
                                    $expDate = strtotime($expiryRaw);
                                    $now = time();
                                    $daysUntilExpiry = ($expDate - $now) / (60 * 60 * 24);
                                    
                                    if ($daysUntilExpiry < 0) $expiryClass = 'color: #ef4444; font-weight: 800;';
                                    elseif ($daysUntilExpiry <= 30) $expiryClass = 'color: #d97706; font-weight: 800;'; 
                                }
                            ?>
                            <tr class="balance-row" style="display: none;" data-days-expiry="<?= esc((string) $daysUntilExpiry) ?>">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) $row['id']) ?></td>
                                <td style="font-weight: 800; color: var(--v2-label); word-break: break-word;"><?= esc((string) $row['item_name']) ?></td>
                                <td style="font-size: 0.85rem; color: var(--v2-text-muted);"><?= esc((string) $row['unit']) ?></td>
                                <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($row['batch_no'] ?? '')) ?></td>
                                <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($row['lot_no'] ?? '')) ?></td>
                                <td style="font-size: 0.85rem; <?= $expiryClass ?>"><?= esc($expiryRaw) ?></td>
                                <td style="text-align: right; font-weight: 600;"><?= esc(app_format_quantity($row['on_hand_qty'] ?? 0)) ?></td>
                                <td style="text-align: right; font-weight: 600; color: #d97706;"><?= esc(app_format_quantity($row['reserved_qty'] ?? 0)) ?></td>
                                
                                <?php 
                                    $availQty = (float) $row['available_qty'];
                                    $qtyColor = $availQty <= 0 ? 'color: #ef4444; font-weight: 800;' : 'font-weight: 800; color: #15803d;';
                                ?>
                                <td style="text-align: right; <?= $qtyColor ?>"><?= esc(app_format_quantity($row['available_qty'] ?? 0)) ?></td>
                                
                                <td style="text-align: right; font-family: var(--font-mono); font-size: 0.85rem;">₱<?= esc(number_format((float) ($row['average_unit_cost'] ?? 0), 2)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalSkus) ?></span>)
            </p>
            <nav aria-label="Stock Balance Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function formatQuantity(value) {
            if (!Number.isFinite(value)) return '0';
            if (Math.abs(value - Math.round(value)) <= 0.00001) {
                return Math.round(value).toLocaleString('en-US');
            }

            return value.toLocaleString('en-US', {maximumFractionDigits: 3});
        }

        const rowsPerPage = 15;
        const tbody = document.querySelector('#balance-table tbody');
        if (!tbody) return;

        const allRows = Array.from(tbody.querySelectorAll('.balance-row'));
        let currentRows = [...allRows]; 
        
        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalIndicator = document.getElementById('total-indicator');
        
        const kpiSkus = document.getElementById('kpi-skus');
        const kpiOnHand = document.getElementById('kpi-onhand');
        const kpiReserved = document.getElementById('kpi-reserved');
        const kpiAvailable = document.getElementById('kpi-available');

        const searchInput = document.getElementById('instant-search-input');
        const statusFilter = document.getElementById('filter-stock-status');
        const expiryFilter = document.getElementById('filter-expiry-status');
        const clearBtn = document.getElementById('btn-clear-filters');

        if (allRows.length === 0) return;

        function applySearch() {
            const query = searchInput.value.toLowerCase().trim();
            const stockStatus = statusFilter.value;
            const expiryStatus = expiryFilter.value;

            currentRows = allRows.filter(row => {
                const id = row.children[0].innerText.toLowerCase();
                const name = row.children[1].innerText.toLowerCase();
                const batch = row.children[3].innerText.toLowerCase();
                const availableQty = parseFloat(row.children[8].innerText.replace(/,/g, '')) || 0;
                const daysUntilExpiry = parseFloat(row.getAttribute('data-days-expiry'));

                const matchesText = query === '' || id.includes(query) || name.includes(query) || batch.includes(query);
                
                let matchesStatus = true;
                if (stockStatus === 'in_stock') matchesStatus = availableQty > 0;
                else if (stockStatus === 'low_stock') matchesStatus = availableQty > 0 && availableQty <= 10;
                else if (stockStatus === 'out_of_stock') matchesStatus = availableQty <= 0;

                let matchesExpiry = true;
                if (expiryStatus === 'expired') matchesExpiry = daysUntilExpiry < 0;
                else if (expiryStatus === 'expiring_30') matchesExpiry = daysUntilExpiry >= 0 && daysUntilExpiry <= 30;
                else if (expiryStatus === 'expiring_90') matchesExpiry = daysUntilExpiry >= 0 && daysUntilExpiry <= 90;

                return matchesText && matchesStatus && matchesExpiry;
            });

            if (query !== '') {
                currentRows.sort((a, b) => {
                    const aId = a.children[0].innerText.toLowerCase();
                    const aName = a.children[1].innerText.toLowerCase();
                    const bId = b.children[0].innerText.toLowerCase();
                    const bName = b.children[1].innerText.toLowerCase();
                    
                    const aScore = aId.includes(query) ? 1 : (aName.includes(query) ? 2 : 3);
                    const bScore = bId.includes(query) ? 1 : (bName.includes(query) ? 2 : 3);
                    
                    return aScore - bScore;
                });
            }

            currentRows.forEach(row => tbody.appendChild(row));
            updateKPIs();
            showPage(1); 
        }

        function updateKPIs() {
            let sumOnHand = 0, sumReserved = 0, sumAvailable = 0;
            
            currentRows.forEach(row => {
                sumOnHand += parseFloat(row.children[6].innerText.replace(/,/g, '')) || 0;
                sumReserved += parseFloat(row.children[7].innerText.replace(/,/g, '')) || 0;
                sumAvailable += parseFloat(row.children[8].innerText.replace(/,/g, '')) || 0;
            });
            
            kpiSkus.innerText = currentRows.length;
            kpiOnHand.innerText = formatQuantity(sumOnHand);
            kpiReserved.innerText = formatQuantity(sumReserved);
            kpiAvailable.innerText = formatQuantity(sumAvailable);
        }

        if(searchInput) searchInput.addEventListener('input', applySearch);
        if(statusFilter) statusFilter.addEventListener('change', applySearch);
        if(expiryFilter) expiryFilter.addEventListener('change', applySearch);
        
        if(clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                statusFilter.value = 'all';
                expiryFilter.value = 'all';
                applySearch();
            });
        }

        function showPage(page) {
            const totalRows = currentRows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);
            let currentPage = page;

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
            if (totalIndicator) totalIndicator.innerText = totalRows;

            buildPaginationButtons(currentPage, totalPages);
        }

        function buildPaginationButtons(currentPage, totalPages) {
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

        document.querySelectorAll('#balance-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const isNumericCol = th.classList.contains('numeric') || colIndex === 0;
                const isDateCol = th.classList.contains('date');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 
                
                document.querySelectorAll('#balance-table th.sortable').forEach(header => {
                    header.classList.remove('asc', 'desc');
                });
                
                th.classList.add(isAsc ? 'desc' : 'asc');
                
                currentRows.sort((a, b) => {
                    let aText = a.children[colIndex].innerText.trim();
                    let bText = b.children[colIndex].innerText.trim();
                    
                    if (isNumericCol) {
                        aText = aText.replace(/,/g, '').replace('₱', '');
                        bText = bText.replace(/,/g, '').replace('₱', '');
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

        showPage(1);
    });
</script>
<?= $this->endSection() ?>
