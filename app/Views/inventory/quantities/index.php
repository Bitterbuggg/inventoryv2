<?php

declare(strict_types=1);

$title = 'Inventory Quantities - InventoryV2';
$pageTitle = 'Inventory Quantities';
$pageSubtitle = 'Search current stock balances, batches, lots, and available quantities.';
$crumbs = [
    ['label' => 'Inventory Quantities'],
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
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('receiving') ?>">Receiving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $stocks ?? [];
$totalRows = count($rows);
$totalOnHand = array_sum(array_map(static fn (array $row): float => (float) ($row['on_hand_qty'] ?? 0), $rows));
$totalAvailable = array_sum(array_map(static fn (array $row): float => (float) ($row['available_qty'] ?? 0), $rows));
$zeroAvailable = count(array_filter($rows, static fn (array $row): bool => (float) ($row['available_qty'] ?? 0) <= 0));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Visible SKUs</p>
                <p class="kpi-value" id="kpi-skus"><?= esc((string) $totalRows) ?></p>
                <p class="kpi-note">Rows in current inventory view.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total On Hand</p>
                <p class="kpi-value" id="kpi-onhand"><?= esc(number_format($totalOnHand, 2)) ?></p>
                <p class="kpi-note">Current physical quantity total.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Available</p>
                <p class="kpi-value" id="kpi-available"><?= esc(number_format($totalAvailable, 2)) ?></p>
                <p class="kpi-note">On hand minus reserved quantities.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Zero/Negative Available</p>
                <p class="kpi-value" id="kpi-zero" style="color: var(--color-danger);"><?= esc((string) $zeroAvailable) ?></p>
                <p class="kpi-note">Needs restocking review.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <form class="inline-form" id="instant-search-form" style="display: flex; gap: 8px; flex-wrap: wrap;">
            <input type="text" id="instant-search-input" placeholder="Search ID, name, or batch..." autocomplete="off" style="flex: 1; min-width: 220px;">
            
            <select id="filter-stock-status" style="width: auto; padding: 6px 12px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); background: var(--color-surface); color: var(--color-text);">
                <option value="all">All Stock Levels</option>
                <option value="in_stock">In Stock (> 0)</option>
                <option value="low_stock">Low Stock (≤ 10)</option>
                <option value="out_of_stock">Out of Stock (0)</option>
            </select>

            <select id="filter-expiry-status" style="width: auto; padding: 6px 12px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); background: var(--color-surface); color: var(--color-text);">
                <option value="all">All Expiry Dates</option>
                <option value="expired">Expired</option>
                <option value="expiring_30">Expiring in ≤ 30 Days</option>
                <option value="expiring_90">Expiring in ≤ 90 Days</option>
            </select>

            <button type="button" class="btn btn-outline" id="btn-clear-filters">Clear</button>
        </form>

        <div id="full-inventory-container">
            <div class="table-wrap">
                <table class="table" id="inventory-table" style="table-layout: fixed; width: 100%; min-width: 1050px;">
                    <colgroup>
                        <col style="width: 60px;">  
                        <col style="width: 22%;">   
                        <col style="width: 80px;">  
                        <col style="width: 12%;">   
                        <col style="width: 10%;">   
                        <col style="width: 100px;"> 
                        <col style="width: 10%;">   
                        <col style="width: 10%;">   
                        <col style="width: 10%;">   
                        <col style="width: 90px;"> 
                        <col style="width: 70px;"> 
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="sortable" data-col="0">ID</th>
                            <th class="sortable" data-col="1">Item</th>
                            <th class="sortable" data-col="2">Unit</th>
                            <th class="sortable" data-col="3">Batch</th>
                            <th class="sortable" data-col="4">Lot</th>
                            <th class="sortable date" data-col="5">Expiry</th>
                            <th class="sortable numeric" data-col="6" style="text-align: right;">On Hand</th>
                            <th class="sortable numeric" data-col="7" style="text-align: right;">Reserved</th>
                            <th class="sortable numeric" data-col="8" style="text-align: right;">Available</th>
                            <th class="sortable numeric" data-col="9" style="text-align: right;">Avg Cost</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (($rows ?? []) === []): ?>
                            <tr class="no-records-row"><td colspan="11" class="empty-state">No inventory stocks found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($rows as $stock): ?>
                                <?php 
                                    $expiryRaw = (string) ($stock['expiry_date'] ?? '');
                                    $daysUntilExpiry = 9999; // Default for non-expiring items
                                    $expiryClass = '';
                                    if ($expiryRaw !== '') {
                                        $expDate = strtotime($expiryRaw);
                                        $now = time();
                                        $daysUntilExpiry = ($expDate - $now) / (60 * 60 * 24);
                                        
                                        if ($daysUntilExpiry < 0) $expiryClass = 'color: var(--color-danger); font-weight: bold;';
                                        elseif ($daysUntilExpiry <= 30) $expiryClass = 'color: #d97706; font-weight: bold;'; // Orange warning
                                    }
                                ?>
                                <tr class="inventory-row" style="display: none;" data-days-expiry="<?= esc((string) $daysUntilExpiry) ?>">
                                    <td><?= esc((string) $stock['id']) ?></td>
                                    <td style="font-weight: 500; color: var(--color-brand-700); word-break: break-word;"><?= esc((string) $stock['item_name']) ?></td>
                                    <td style="font-size: 0.85rem; color: var(--color-text-muted);"><?= esc((string) $stock['unit']) ?></td>
                                    <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($stock['batch_no'] ?? '')) ?></td>
                                    <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($stock['lot_no'] ?? '')) ?></td>
                                    <td style="font-size: 0.85rem; <?= $expiryClass ?>"><?= esc($expiryRaw) ?></td>
                                    <td style="text-align: right;"><?= esc((string) $stock['on_hand_qty']) ?></td>
                                    <td style="text-align: right;"><?= esc((string) $stock['reserved_qty']) ?></td>
                                    
                                    <?php 
                                        $availQty = (float) $stock['available_qty'];
                                        $qtyColor = $availQty <= 0 ? 'color: var(--color-danger); font-weight: bold;' : 'font-weight: 600;';
                                    ?>
                                    <td style="text-align: right; <?= $qtyColor ?>"><?= esc((string) $stock['available_qty']) ?></td>
                                    <td style="text-align: right; font-family: var(--font-mono); font-size: 0.85rem;">₱<?= esc(number_format((float) ($stock['average_unit_cost'] ?? 0), 2)) ?></td>
                                    <td style="text-align: center;">
                                        <a class="btn btn-outline" style="padding: 4px 8px; font-size: 0.75rem;" href="<?= site_url('inventory/quantities/' . $stock['id']) ?>">View</a>
                                    </td>
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
                <nav aria-label="Inventory Pagination">
                    <ul class="ci-pager" id="client-pager"></ul>
                </nav>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 15;
        const tbody = document.querySelector('#inventory-table tbody');
        if (!tbody) return;

        const allRows = Array.from(tbody.querySelectorAll('.inventory-row'));
        let currentRows = [...allRows]; 
        
        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalIndicator = document.getElementById('total-indicator');
        
        const kpiSkus = document.getElementById('kpi-skus');
        const kpiOnHand = document.getElementById('kpi-onhand');
        const kpiAvailable = document.getElementById('kpi-available');
        const kpiZero = document.getElementById('kpi-zero');

        const searchInput = document.getElementById('instant-search-input');
        const statusFilter = document.getElementById('filter-stock-status');
        const expiryFilter = document.getElementById('filter-expiry-status');
        const clearBtn = document.getElementById('btn-clear-filters');
        const searchForm = document.getElementById('instant-search-form');

        if(searchForm) searchForm.addEventListener('submit', e => e.preventDefault());

        if (allRows.length === 0) return;

        // ==========================================
        // 1. INSTANT SEARCH, FILTERS & KPI LOGIC
        // ==========================================
        function applySearch() {
            const query = searchInput.value.toLowerCase().trim();
            const stockStatus = statusFilter.value;
            const expiryStatus = expiryFilter.value;

            // Step 1: Filter by Text, Stock Status, AND Expiry Status
            currentRows = allRows.filter(row => {
                const id = row.children[0].innerText.toLowerCase();
                const name = row.children[1].innerText.toLowerCase();
                const batch = row.children[3].innerText.toLowerCase();
                const availableQty = parseFloat(row.children[8].innerText.replace(/,/g, '')) || 0;
                
                // Parse the data attribute we set via PHP
                const daysUntilExpiry = parseFloat(row.getAttribute('data-days-expiry'));

                // Check Text
                const matchesText = query === '' || id.includes(query) || name.includes(query) || batch.includes(query);
                
                // Check Stock Dropdown
                let matchesStatus = true;
                if (stockStatus === 'in_stock') matchesStatus = availableQty > 0;
                else if (stockStatus === 'low_stock') matchesStatus = availableQty > 0 && availableQty <= 10;
                else if (stockStatus === 'out_of_stock') matchesStatus = availableQty <= 0;

                // Check Expiry Dropdown
                let matchesExpiry = true;
                if (expiryStatus === 'expired') matchesExpiry = daysUntilExpiry < 0;
                else if (expiryStatus === 'expiring_30') matchesExpiry = daysUntilExpiry >= 0 && daysUntilExpiry <= 30;
                else if (expiryStatus === 'expiring_90') matchesExpiry = daysUntilExpiry >= 0 && daysUntilExpiry <= 90;

                return matchesText && matchesStatus && matchesExpiry;
            });

            // Step 2: Apply Sorting Hierarchy (ID > Item Name > Batch)
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

            // Physically re-order the DOM nodes based on the sort
            currentRows.forEach(row => tbody.appendChild(row));

            updateKPIs();
            showPage(1); 
        }

        function updateKPIs() {
            let sumOnHand = 0, sumAvailable = 0, countZero = 0;
            
            currentRows.forEach(row => {
                sumOnHand += parseFloat(row.children[6].innerText.replace(/,/g, '')) || 0;
                
                const avail = parseFloat(row.children[8].innerText.replace(/,/g, '')) || 0;
                sumAvailable += avail;
                
                if (avail <= 0) countZero++;
            });
            
            kpiSkus.innerText = currentRows.length;
            kpiOnHand.innerText = sumOnHand.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            kpiAvailable.innerText = sumAvailable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            kpiZero.innerText = countZero;
        }

        // Attach Event Listeners to all 3 inputs
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

        // ==========================================
        // 2. PAGINATION LOGIC
        // ==========================================
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
        // 3. SORTING LOGIC
        // ==========================================
        document.querySelectorAll('#inventory-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const isNumericCol = th.classList.contains('numeric') || colIndex === 0;
                const isDateCol = th.classList.contains('date');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 
                
                document.querySelectorAll('#inventory-table th.sortable').forEach(header => {
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

                    // Special handling to properly sort dates chronologically
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

        // Initialize table
        showPage(1);
    });
</script>
<?= $this->endSection() ?>