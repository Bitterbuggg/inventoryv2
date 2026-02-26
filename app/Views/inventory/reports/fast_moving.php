<?php

declare(strict_types=1);

$title = 'Fast Moving Report - InventoryV2';
$pageTitle = 'Report: Fast Moving Items';
$pageSubtitle = 'Top items by issued quantity in the selected date range.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Fast Moving'],
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

    /* Volume Bar for Fast Moving */
    .volume-bar-container {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        min-height: 24px;
        padding-right: 8px;
    }
    .volume-bar-fill {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        background-color: var(--color-brand-100);
        border-right: 2px solid var(--color-brand-400);
        z-index: 1;
        border-radius: 4px;
        opacity: 0.6;
    }
    .volume-bar-text {
        position: relative;
        z-index: 2;
        font-weight: 600;
        font-family: var(--font-mono);
    }
    
    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--color-surface-alt);
        color: var(--color-text-muted);
        border-radius: 50%;
        width: 28px;
        height: 28px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    .rank-1 { background: #fef08a; color: #92400e; } /* Gold */
    .rank-2 { background: #e5e7eb; color: #475569; } /* Silver */
    .rank-3 { background: #fed7aa; color: #9a3412; } /* Bronze */
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $fastMovingExportQuery = http_build_query(['export' => 'csv', 'date_from' => ($date_from ?? ''), 'date_to' => ($date_to ?? ''), 'limit' => ($limit ?? 20)]); ?>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') . '?' . $fastMovingExportQuery ?>">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>">Low Stock</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$fastRows = $rows ?? [];
$totalRows = count($fastRows);
$totalQtyOut = array_sum(array_map(static fn (array $row): float => (float) ($row['total_qty_out'] ?? 0), $fastRows));
$topItem = $fastRows[0]['item_name'] ?? 'N/A';
$topItemQty = (float) ($fastRows[0]['total_qty_out'] ?? 0);

// We need the max quantity to calculate the width of the visual data bars
$maxQty = $topItemQty > 0 ? $topItemQty : 1; 
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Ranked Items</p>
                <p class="kpi-value" id="kpi-ranked"><?= esc((string) $totalRows) ?></p>
                <p class="kpi-note">Items included in current ranking.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Qty Out</p>
                <p class="kpi-value" id="kpi-qty"><?= esc(number_format($totalQtyOut, 2)) ?></p>
                <p class="kpi-note">Aggregate outbound quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Top Item</p>
                <p class="kpi-value" style="font-size: 1.25rem; word-break: break-all; line-height: 1.2; padding-top: 4px;"><?= esc((string) $topItem) ?></p>
                <p class="kpi-note">Highest outbound volume item.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Top Item Qty</p>
                <p class="kpi-value"><?= esc(number_format($topItemQty, 2)) ?></p>
                <p class="kpi-note">Outbound quantity of top item.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('reports/fast-moving') ?>">
            <label for="date_from">Date From</label>
            <input id="date_from" type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>">
            <label for="date_to">Date To</label>
            <input id="date_to" type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>">
            <label for="limit">Limit</label>
            <input id="limit" type="number" min="1" name="limit" value="<?= esc((string) ($limit ?? 20)) ?>" style="width: 80px;">
            <button type="submit" class="btn btn-outline">Apply</button>
        </form>

        <div style="display: flex; gap: 8px; margin-top: 8px;">
            <input type="text" id="instant-search-input" placeholder="Search item name to filter the ranking below..." autocomplete="off" style="flex: 1;">
            <button type="button" class="btn btn-outline" id="btn-clear-search">Clear Search</button>
        </div>

        <div id="full-fast-container">
            <div class="table-wrap">
                <table class="table" id="fast-table" style="table-layout: fixed; width: 100%; min-width: 600px;">
                    <colgroup>
                        <col style="width: 80px;">  <col style="width: 50%;">   <col style="width: 15%;">   <col style="width: 35%;">   </colgroup>
                    <thead>
                        <tr>
                            <th class="sortable numeric" data-col="0" style="text-align: center;">Rank</th>
                            <th class="sortable" data-col="1">Item</th>
                            <th class="sortable" data-col="2">Unit</th>
                            <th class="sortable numeric asc" data-col="3" style="text-align: right;">Total Qty Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (($rows ?? []) === []): ?>
                            <tr class="no-records-row"><td colspan="4" class="empty-state">No records found.</td></tr>
                        <?php else: ?>
                            <?php 
                            $rankCounter = 1;
                            foreach ($rows as $row): 
                                $qtyOut = (float) $row['total_qty_out'];
                                $percent = ($qtyOut / $maxQty) * 100;
                                
                                // Badge styling for top 3
                                $rankClass = '';
                                if ($rankCounter === 1) $rankClass = 'rank-1';
                                elseif ($rankCounter === 2) $rankClass = 'rank-2';
                                elseif ($rankCounter === 3) $rankClass = 'rank-3';
                            ?>
                                <tr class="fast-row" style="display: none;" data-qty="<?= esc((string) $qtyOut) ?>">
                                    <td style="text-align: center;">
                                        <span class="rank-badge <?= $rankClass ?>"><?= $rankCounter ?></span>
                                    </td>
                                    <td style="font-weight: 500; word-break: break-word;"><?= esc((string) $row['item_name']) ?></td>
                                    <td style="font-size: 0.85rem; color: var(--color-text-muted);"><?= esc((string) $row['unit']) ?></td>
                                    <td>
                                        <div class="volume-bar-container">
                                            <div class="volume-bar-fill" style="width: <?= esc((string) $percent) ?>%;"></div>
                                            <span class="volume-bar-text"><?= esc(number_format($qtyOut, 2)) ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php 
                                $rankCounter++;
                            endforeach; 
                            ?>
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
        const tbody = document.querySelector('#fast-table tbody');
        if (!tbody) return;

        const allRows = Array.from(tbody.querySelectorAll('.fast-row'));
        let currentRows = [...allRows]; 

        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalIndicator = document.getElementById('total-indicator');
        
        const kpiRanked = document.getElementById('kpi-ranked');
        const kpiQty = document.getElementById('kpi-qty');
        const searchInput = document.getElementById('instant-search-input');
        const clearBtn = document.getElementById('btn-clear-search');

        if (allRows.length === 0) return;

        // ==========================================
        // 1. INSTANT SEARCH & KPI UPDATE
        // ==========================================
        function applySearch() {
            const query = searchInput.value.toLowerCase().trim();

            if (query === '') {
                currentRows = [...allRows];
            } else {
                currentRows = allRows.filter(row => {
                    const name = row.children[1].innerText.toLowerCase();
                    return name.includes(query);
                });
            }

            // Restore the DOM order based on the current filtered set so pagination works
            currentRows.forEach(row => tbody.appendChild(row));

            updateKPIs();
            showPage(1);
        }

        function updateKPIs() {
            let sumQty = 0;
            currentRows.forEach(row => {
                sumQty += parseFloat(row.getAttribute('data-qty')) || 0;
            });
            
            kpiRanked.innerText = currentRows.length;
            kpiQty.innerText = sumQty.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            totalIndicator.innerText = currentRows.length;
        }

        if(searchInput) searchInput.addEventListener('input', applySearch);
        if(clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                applySearch();
            });
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
        // 3. SORTING LOGIC
        // ==========================================
        document.querySelectorAll('#fast-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const isNumericCol = th.classList.contains('numeric');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 
                
                document.querySelectorAll('#fast-table th.sortable').forEach(header => {
                    header.classList.remove('asc', 'desc');
                });
                
                th.classList.add(isAsc ? 'desc' : 'asc');
                
                currentRows.sort((a, b) => {
                    let aText = a.children[colIndex].innerText.trim();
                    let bText = b.children[colIndex].innerText.trim();
                    
                    // Special case for column 3 (Qty) to read from data attribute to ignore formatting
                    if (colIndex === 3) {
                        aText = a.getAttribute('data-qty');
                        bText = b.getAttribute('data-qty');
                    }
                    
                    if (isNumericCol) {
                        return (parseFloat(aText) - parseFloat(bText)) * direction;
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