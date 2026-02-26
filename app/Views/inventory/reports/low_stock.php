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
<?php $lowStockExportQuery = http_build_query(['export' => 'csv', 'threshold' => ($threshold ?? 10)]); ?>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') . '?' . $lowStockExportQuery ?>">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// 1. Set the strict threshold (Default is 10 if not provided)
$thresholdValue = (float) ($threshold ?? 10);

// 2. STRICT PHP FILTERING: Only keep items that are ACTUALLY low stock!
$lowStockRows = array_filter($rows ?? [], static fn (array $row): bool => (float) ($row['available_qty'] ?? 0) <= $thresholdValue);
$lowStockRows = array_values($lowStockRows); // Re-index array cleanly

// 3. Calculate KPIs based strictly on the low stock items
$totalRows = count($lowStockRows);
$totalAvailable = array_sum(array_map(static fn (array $row): float => (float) ($row['available_qty'] ?? 0), $lowStockRows));
$criticalRows = count(array_filter($lowStockRows, static fn (array $row): bool => (float) ($row['available_qty'] ?? 0) <= 0));
$nearExpiryRows = count(array_filter(
    $lowStockRows,
    static fn (array $row): bool => isset($row['expiry_date']) && (string) $row['expiry_date'] !== '' && strtotime((string) $row['expiry_date']) <= strtotime('+60 days')
));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Low Stock Items</p>
                <p class="kpi-value" id="kpi-rows"><?= esc((string) $totalRows) ?></p>
                <p class="kpi-note">Items at or below threshold (<?= esc((string)$thresholdValue) ?>).</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Available</p>
                <p class="kpi-value" id="kpi-available"><?= esc(number_format($totalAvailable, 2)) ?></p>
                <p class="kpi-note">Combined quantity of low items.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Critical (<= 0)</p>
                <p class="kpi-value" id="kpi-critical" style="color: var(--color-danger);"><?= esc((string) $criticalRows) ?></p>
                <p class="kpi-note">Immediate replenishment needed.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Near Expiry (60d)</p>
                <p class="kpi-value" id="kpi-expiry"><?= esc((string) $nearExpiryRows) ?></p>
                <p class="kpi-note">Needs expiry risk review.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('reports/low-stock') ?>">
            <label for="threshold">Set Warning Threshold</label>
            <input id="threshold" type="number" step="0.001" min="0" name="threshold" value="<?= esc((string) $thresholdValue) ?>" style="width: 100px;">
            <button type="submit" class="btn btn-outline">Apply Server Query</button>
        </form>

        <div style="display: flex; gap: 8px; margin-top: 8px; flex-wrap: wrap;">
            <input type="text" id="instant-search-input" placeholder="Search low stock by name, ID, or batch..." autocomplete="off" style="flex: 1; min-width: 220px;">
            
            <select id="filter-stock-status" style="width: auto; padding: 6px 12px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); background: var(--color-surface); color: var(--color-text);">
                <option value="all">All Low Stock</option>
                <option value="critical">Critical Only (≤ 0)</option>
                <option value="warning">Warning Only (> 0)</option>
            </select>

            <button type="button" class="btn btn-outline" id="btn-clear-search">Clear Filter</button>
        </div>

        <div id="full-low-container">
            <div class="table-wrap">
                <table class="table" id="low-table" style="table-layout: fixed; width: 100%; min-width: 900px;">
                    <colgroup>
                        <col style="width: 60px;">  <col style="width: 25%;">   <col style="width: 80px;">  <col style="width: 12%;">   <col style="width: 12%;">   <col style="width: 100px;"> <col style="width: 15%;">   <col style="width: 10%;">   <col style="width: 10%;">   </colgroup>
                    <thead>
                        <tr>
                            <th class="sortable numeric" data-col="0">ID</th>
                            <th class="sortable" data-col="1">Item</th>
                            <th class="sortable" data-col="2">Unit</th>
                            <th class="sortable" data-col="3">Batch</th>
                            <th class="sortable" data-col="4">Lot</th>
                            <th class="sortable date" data-col="5">Expiry</th>
                            <th class="sortable numeric asc" data-col="6" style="text-align: right;">Available Qty</th>
                            <th class="sortable numeric" data-col="7" style="text-align: right;">On Hand</th>
                            <th class="sortable numeric" data-col="8" style="text-align: right;">Reserved</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($lowStockRows === []): ?>
                            <tr class="no-records-row"><td colspan="9" class="empty-state">No low stock records found for this threshold.</td></tr>
                        <?php else: ?>
                            <?php foreach ($lowStockRows as $row): ?>
                                <?php 
                                    // Visual color coding for urgency
                                    $availQty = (float) $row['available_qty'];
                                    $qtyColor = '';
                                    if ($availQty <= 0) {
                                        $qtyColor = 'color: var(--color-danger); font-weight: 800;';
                                    } elseif ($availQty <= ($thresholdValue / 2)) {
                                        $qtyColor = 'color: #d97706; font-weight: 700;'; // Orange for getting very close to 0
                                    } else {
                                        $qtyColor = 'font-weight: 600; color: #b45309;';
                                    }

                                    // Expiry Check
                                    $expiryRaw = (string) ($row['expiry_date'] ?? '');
                                    $daysUntilExpiry = 9999; 
                                    $expiryClass = '';
                                    if ($expiryRaw !== '') {
                                        $expDate = strtotime($expiryRaw);
                                        $now = time();
                                        $daysUntilExpiry = ($expDate - $now) / (60 * 60 * 24);
                                        
                                        if ($daysUntilExpiry < 0) $expiryClass = 'color: var(--color-danger); font-weight: bold;';
                                        elseif ($daysUntilExpiry <= 60) $expiryClass = 'color: #d97706; font-weight: bold;'; 
                                    }
                                ?>
                                <tr class="low-row" style="display: none;" data-expiry-days="<?= esc((string) $daysUntilExpiry) ?>">
                                    <td><?= esc((string) $row['id']) ?></td>
                                    <td style="font-weight: 500; color: var(--color-brand-700); word-break: break-word;"><?= esc((string) $row['item_name']) ?></td>
                                    <td style="font-size: 0.85rem; color: var(--color-text-muted);"><?= esc((string) $row['unit']) ?></td>
                                    <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($row['batch_no'] ?? '')) ?></td>
                                    <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($row['lot_no'] ?? '')) ?></td>
                                    <td style="font-size: 0.85rem; <?= $expiryClass ?>"><?= esc($expiryRaw) ?></td>
                                    
                                    <td style="text-align: right; font-size: 1.05rem; <?= $qtyColor ?>"><?= esc((string) $row['available_qty']) ?></td>
                                    
                                    <td style="text-align: right; color: var(--color-text-muted);"><?= esc((string) $row['on_hand_qty']) ?></td>
                                    <td style="text-align: right; color: var(--color-text-muted);"><?= esc((string) $row['reserved_qty']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
                <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                    Showing records <span id="page-indicator"></span> (Filtered: <span id="total-indicator"><?= esc((string) $totalRows) ?></span>)
                </p>
                <nav aria-label="Pagination">
                    <ul class="ci-pager" id="client-pager"></ul>
                </nav>
            </div>
        </div>
    </section>
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

        // ==========================================
        // 1. INITIAL SORT (Sort by lowest available first)
        // ==========================================
        allRows.sort((a, b) => {
            const aQty = parseFloat(a.children[6].innerText.replace(/,/g, '')) || 0;
            const bQty = parseFloat(b.children[6].innerText.replace(/,/g, '')) || 0;
            return aQty - bQty; // Ascending order (lowest first)
        });
        allRows.forEach(row => tbody.appendChild(row));
        currentRows = [...allRows];

        // ==========================================
        // 2. INSTANT SEARCH & KPI UPDATE
        // ==========================================
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

            // Sort by relevance logic (ID > Name > Batch) while keeping lowest qty priority if possible
            if (query !== '') {
                currentRows.sort((a, b) => {
                    const aId = a.children[0].innerText.toLowerCase();
                    const aName = a.children[1].innerText.toLowerCase();
                    const bId = b.children[0].innerText.toLowerCase();
                    const bName = b.children[1].innerText.toLowerCase();
                    
                    const aScore = aId.includes(query) ? 1 : (aName.includes(query) ? 2 : 3);
                    const bScore = bId.includes(query) ? 1 : (bName.includes(query) ? 2 : 3);
                    
                    if (aScore !== bScore) return aScore - bScore;

                    // Fallback to lowest quantity
                    const aQty = parseFloat(a.children[6].innerText.replace(/,/g, '')) || 0;
                    const bQty = parseFloat(b.children[6].innerText.replace(/,/g, '')) || 0;
                    return aQty - bQty;
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
            kpiAvailable.innerText = sumAvailable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
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

        // ==========================================
        // 3. PAGINATION LOGIC
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
        // 4. SORTING LOGIC
        // ==========================================
        document.querySelectorAll('#low-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const isNumericCol = th.classList.contains('numeric');
                const isDateCol = th.classList.contains('date');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 
                
                document.querySelectorAll('#low-table th.sortable').forEach(header => {
                    header.classList.remove('asc', 'desc');
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

        // Show page 1
        showPage(1);
    });
</script>
<?= $this->endSection() ?>