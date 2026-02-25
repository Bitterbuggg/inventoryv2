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
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>">Low Stock</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$balanceRows = $rows ?? [];
$totalSkus = count($balanceRows);
$onHand = array_sum(array_map(static fn (array $row): float => (float) ($row['on_hand_qty'] ?? 0), $balanceRows));
$reserved = array_sum(array_map(static fn (array $row): float => (float) ($row['reserved_qty'] ?? 0), $balanceRows));
$available = array_sum(array_map(static fn (array $row): float => (float) ($row['available_qty'] ?? 0), $balanceRows));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Visible SKUs</p>
                <p class="kpi-value" id="kpi-skus"><?= esc((string) $totalSkus) ?></p>
                <p class="kpi-note">Inventory lines in report view.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">On Hand</p>
                <p class="kpi-value" id="kpi-onhand"><?= esc(number_format($onHand, 2)) ?></p>
                <p class="kpi-note">Total physical quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Reserved</p>
                <p class="kpi-value" id="kpi-reserved"><?= esc(number_format($reserved, 2)) ?></p>
                <p class="kpi-note">Allocated but unreleased quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Available</p>
                <p class="kpi-value" id="kpi-available"><?= esc(number_format($available, 2)) ?></p>
                <p class="kpi-note">Usable stock for issuance.</p>
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

            <button type="button" class="btn btn-outline" id="btn-clear-filters">Clear</button>
        </form>

        <div id="full-balance-container">
            <div class="table-wrap">
                <table class="table" id="balance-table" style="table-layout: fixed; width: 100%; min-width: 1000px;">
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
                            <th class="sortable" data-col="0">ID</th>
                            <th class="sortable" data-col="1">Item</th>
                            <th class="sortable" data-col="2">Unit</th>
                            <th class="sortable" data-col="3">Batch</th>
                            <th class="sortable" data-col="4">Lot</th>
                            <th class="sortable" data-col="5">Expiry</th>
                            <th class="sortable numeric" data-col="6" style="text-align: right;">On Hand</th>
                            <th class="sortable numeric" data-col="7" style="text-align: right;">Reserved</th>
                            <th class="sortable numeric" data-col="8" style="text-align: right;">Available</th>
                            <th class="sortable numeric" data-col="9" style="text-align: right;">Avg Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (($rows ?? []) === []): ?>
                            <tr class="no-records-row"><td colspan="10" class="empty-state">No records found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($rows as $row): ?>
                                <tr class="balance-row" style="display: none;">
                                    <td><?= esc((string) $row['id']) ?></td>
                                    <td style="font-weight: 500; color: var(--color-brand-700); word-break: break-word;"><?= esc((string) $row['item_name']) ?></td>
                                    <td style="font-size: 0.85rem; color: var(--color-text-muted);"><?= esc((string) $row['unit']) ?></td>
                                    <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($row['batch_no'] ?? '')) ?></td>
                                    <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($row['lot_no'] ?? '')) ?></td>
                                    <td style="font-size: 0.85rem;"><?= esc((string) ($row['expiry_date'] ?? '')) ?></td>
                                    <td style="text-align: right;"><?= esc((string) $row['on_hand_qty']) ?></td>
                                    <td style="text-align: right;"><?= esc((string) $row['reserved_qty']) ?></td>
                                    
                                    <?php 
                                        $availQty = (float) $row['available_qty'];
                                        $qtyColor = $availQty <= 0 ? 'color: var(--color-danger); font-weight: bold;' : 'font-weight: 600;';
                                    ?>
                                    <td style="text-align: right; <?= $qtyColor ?>"><?= esc((string) $row['available_qty']) ?></td>
                                    
                                    <td style="text-align: right; font-family: var(--font-mono); font-size: 0.85rem;">₱<?= esc(number_format((float) ($row['average_unit_cost'] ?? 0), 2)) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
                <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                    Showing records <span id="page-indicator"></span> (Total: <span id="total-indicator"><?= esc((string) $totalSkus) ?></span>)
                </p>
                <nav aria-label="Stock Balance Pagination">
                    <ul class="ci-pager" id="client-pager"></ul>
                </nav>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

            // Step 1: Filter by Text AND Dropdown Status
            currentRows = allRows.filter(row => {
                const id = row.children[0].innerText.toLowerCase();
                const name = row.children[1].innerText.toLowerCase();
                const batch = row.children[3].innerText.toLowerCase();
                const availableQty = parseFloat(row.children[8].innerText.replace(/,/g, '')) || 0;

                // Check Text
                const matchesText = query === '' || id.includes(query) || name.includes(query) || batch.includes(query);
                
                // Check Status Dropdown
                let matchesStatus = true;
                if (stockStatus === 'in_stock') matchesStatus = availableQty > 0;
                else if (stockStatus === 'low_stock') matchesStatus = availableQty > 0 && availableQty <= 10;
                else if (stockStatus === 'out_of_stock') matchesStatus = availableQty <= 0;

                return matchesText && matchesStatus;
            });

            // Step 2: Apply Sorting Hierarchy (ID > Item Name > Batch)
            if (query !== '') {
                currentRows.sort((a, b) => {
                    const aId = a.children[0].innerText.toLowerCase();
                    const aName = a.children[1].innerText.toLowerCase();
                    const bId = b.children[0].innerText.toLowerCase();
                    const bName = b.children[1].innerText.toLowerCase();
                    
                    // Priority: 1 (ID), 2 (Name), 3 (Batch/Fallback)
                    const aScore = aId.includes(query) ? 1 : (aName.includes(query) ? 2 : 3);
                    const bScore = bId.includes(query) ? 1 : (bName.includes(query) ? 2 : 3);
                    
                    return aScore - bScore;
                });
            }

            // Physically re-order the DOM nodes based on the sort
            currentRows.forEach(row => tbody.appendChild(row));

            updateKPIs();
            showPage(1); // Always reset to page 1 when filtering
        }

        function updateKPIs() {
            let sumOnHand = 0, sumReserved = 0, sumAvailable = 0;
            
            currentRows.forEach(row => {
                sumOnHand += parseFloat(row.children[6].innerText.replace(/,/g, '')) || 0;
                sumReserved += parseFloat(row.children[7].innerText.replace(/,/g, '')) || 0;
                sumAvailable += parseFloat(row.children[8].innerText.replace(/,/g, '')) || 0;
            });
            
            kpiSkus.innerText = currentRows.length;
            kpiOnHand.innerText = sumOnHand.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            kpiReserved.innerText = sumReserved.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            kpiAvailable.innerText = sumAvailable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        // Attach Event Listeners to inputs
        if(searchInput) searchInput.addEventListener('input', applySearch);
        if(statusFilter) statusFilter.addEventListener('change', applySearch);
        if(clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                statusFilter.value = 'all';
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
        document.querySelectorAll('#balance-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const isNumericCol = th.classList.contains('numeric') || colIndex === 0;
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 
                
                document.querySelectorAll('#balance-table th.sortable').forEach(header => {
                    header.classList.remove('asc', 'desc');
                });
                
                th.classList.add(isAsc ? 'desc' : 'asc');
                
                currentRows.sort((a, b) => {
                    let aText = a.children[colIndex].innerText.trim().replace(/,/g, '').replace('₱', '');
                    let bText = b.children[colIndex].innerText.trim().replace(/,/g, '').replace('₱', '');
                    
                    if (isNumericCol) {
                        return (parseFloat(aText) - parseFloat(bText)) * direction;
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