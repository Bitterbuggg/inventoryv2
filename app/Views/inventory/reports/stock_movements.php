<?php

declare(strict_types=1);

$title = 'Stock Movement Report - InventoryV2';
$pageTitle = 'Report: Stock Movements';
$pageSubtitle = 'Inbound and outbound stock movement history.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Stock Movements'],
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
    .filter-active-text { color: var(--color-brand-600); font-weight: 800; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $stockMovementsExportQuery = http_build_query(['export' => 'csv', 'date_from' => ($date_from ?? ''), 'date_to' => ($date_to ?? ''), 'movement_type' => ($movement_type ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') . '?' . $stockMovementsExportQuery ?>">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>">Low Stock</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$movementRows = $rows ?? [];
$totalMovements = count($movementRows);
$totalIn = array_sum(array_map(static fn (array $row): float => (float) ($row['qty_in'] ?? 0), $movementRows));
$totalOut = array_sum(array_map(static fn (array $row): float => (float) ($row['qty_out'] ?? 0), $movementRows));
$distinctItems = count(array_unique(array_map(static fn (array $row): string => (string) ($row['item_name'] ?? ''), $movementRows)));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Movement Rows</p>
                <p class="kpi-value" id="kpi-rows"><?= esc((string) $totalMovements) ?></p>
                <p class="kpi-note">Records returned by active filters.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Qty In</p>
                <p class="kpi-value" id="kpi-in"><?= esc(number_format($totalIn, 0)) ?></p>
                <p class="kpi-note">Total quantity in.</p>
                </article>
                <article class="kpi-card">
                <p class="kpi-label">Total Out</p>
                <p class="kpi-value" id="kpi-out"><?= esc(number_format($totalOut, 0)) ?></p>
                <p class="kpi-note">Outbound stock movement sum.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Distinct Items</p>
                <p class="kpi-value" id="kpi-distinct"><?= esc((string) $distinctItems) ?></p>
                <p class="kpi-note">Unique item names in this range.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <form class="inline-form" method="get" action="<?= site_url('reports/stock-movements') ?>">
            <label for="date_from">Date From</label>
            <input id="date_from" type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>">
            <label for="date_to">Date To</label>
            <input id="date_to" type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>">
            <label for="movement_type">Database Type</label>
            <select id="movement_type" name="movement_type">
                <option value="">All</option>
                <?php foreach (['receiving', 'issuance', 'adjustment_in', 'adjustment_out', 'return'] as $type): ?>
                    <option value="<?= esc($type) ?>" <?= (($movement_type ?? '') === $type) ? 'selected' : '' ?>><?= esc($type) ?></option>
                <?php endforeach ?>
            </select>
            <button type="submit" class="btn btn-outline">Apply Server Filter</button>
            <a href="<?= site_url('reports/stock-movements') ?>" class="btn btn-outline">Reset</a>
        </form>

        <div id="full-movements-container">
            <div class="table-wrap">
                <table class="table" id="movements-table" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 4%;">
                        <col style="width: 11%;">
                        <col style="width: 10%;">
                        <col style="width: 10%;">
                        <col style="width: 24%;">
                        <col style="width: 6%;">
                        <col style="width: 8%;">
                        <col style="width: 8%;">
                        <col style="width: 8%;">
                        <col style="width: 11%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="sortable numeric" data-col="0">ID</th>
                            <th class="sortable" data-col="1">Movement #</th>
                            
                            <th class="sortable" data-col="2" id="type-header" title="Click to cycle filters!">Type (All)</th>
                            
                            <th class="sortable" data-col="3">Reference</th>
                            <th class="sortable" data-col="4">Item</th>
                            <th class="sortable" data-col="5">Unit</th>
                            <th class="sortable numeric" data-col="6" style="text-align: right;">Qty In</th>
                            <th class="sortable numeric" data-col="7" style="text-align: right;">Qty Out</th>
                            <th class="sortable numeric" data-col="8" style="text-align: right;">Balance</th>
                            <th class="sortable date" data-col="9">Performed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (($rows ?? []) === []): ?>
                            <tr class="no-records-row"><td colspan="10" class="empty-state">No records found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($rows as $row): ?>
                                <tr class="movement-row" style="display: none;">
                                    <td><?= esc((string) $row['id']) ?></td>
                                    <td style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--color-brand-700); word-break: break-all;"><?= esc((string) $row['movement_number']) ?></td>
                                    <td>
                                        <span class="badge" style="background: var(--color-surface-alt); color: var(--color-text-muted);">
                                            <?= esc((string) $row['movement_type']) ?>
                                        </span>
                                    </td>
                                    <td style="font-size: 0.85rem; color: var(--color-text-muted); word-break: break-word;"><?= esc((string) $row['reference_type']) ?> #<?= esc((string) $row['reference_id']) ?></td>
                                    <td style="font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= esc((string) $row['item_name']) ?>"><?= esc((string) $row['item_name']) ?></td>
                                    <td style="font-size: 0.85rem; color: var(--color-text-muted);"><?= esc((string) $row['unit']) ?></td>
                                    <td style="text-align: right; color: var(--color-success); font-weight: 600;"><?= esc((string) $row['qty_in']) ?></td>
                                    <td style="text-align: right; color: var(--color-danger); font-weight: 600;"><?= esc((string) $row['qty_out']) ?></td>
                                    <td style="text-align: right; font-weight: bold;"><?= esc((string) $row['balance_after']) ?></td>
                                    <td style="font-size: 0.85rem;"><?= esc((string) $row['performed_at']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
                <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                    Showing records <span id="page-indicator"></span> (Total: <span id="total-indicator"><?= esc((string) $totalMovements) ?></span>)
                </p>
                <nav aria-label="Movements Pagination">
                    <ul class="ci-pager" id="client-pager"></ul>
                </nav>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 15;
        const tbody = document.querySelector('#movements-table tbody');
        if (!tbody) return;

        // Master list and active filtered list
        const allRows = Array.from(tbody.querySelectorAll('.movement-row'));
        let currentRows = [...allRows]; 

        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalIndicator = document.getElementById('total-indicator');
        const typeHeader = document.getElementById('type-header');
        
        // KPI DOM Elements
        const kpiRows = document.getElementById('kpi-rows');
        const kpiIn = document.getElementById('kpi-in');
        const kpiOut = document.getElementById('kpi-out');
        const kpiDistinct = document.getElementById('kpi-distinct');

        if (allRows.length === 0) return;

        // ==========================================
        // 1. COLUMN CYCLE LOGIC
        // ==========================================
        // The exact sequence requested
        const typeCycle = ['All', 'receiving', 'issuance', 'adjustment_in', 'adjustment_out', 'return'];
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
            kpiIn.innerText = sumIn.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 0});
            kpiOut.innerText = sumOut.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 0});
            kpiDistinct.innerText = uniqueItems.size;
            totalIndicator.innerText = currentRows.length;
        }

        // ==========================================
        // 2. PAGINATION LOGIC
        // ==========================================
        const totalPagesObj = { get value() { return Math.ceil(currentRows.length / rowsPerPage); } };
        let currentPage = 1;

        function showPage(page) {
            currentPage = page;
            const totalRows = currentRows.length;
            const totalPages = totalPagesObj.value;

            if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

            const startPoint = (currentPage - 1) * rowsPerPage;
            const endPoint = startPoint + rowsPerPage;

            // Hide everything
            allRows.forEach(row => row.style.display = 'none');

            // Show current slice
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
        document.querySelectorAll('#movements-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));

                // --- THE MAGIC CYCLE FILTER ---
                if (colIndex === 2) {
                    cycleIndex = (cycleIndex + 1) % typeCycle.length;
                    const activeType = typeCycle[cycleIndex];

                    // Update UI text for the header
                    if (activeType === 'All') {
                        typeHeader.innerHTML = `Type (All)`;
                    } else {
                        typeHeader.innerHTML = `Type (<span class="filter-active-text">${activeType}</span>)`;
                    }

                    // Apply Filter
                    if (activeType === 'All') {
                        currentRows = [...allRows];
                    } else {
                        currentRows = allRows.filter(row => {
                            return row.children[2].innerText.trim().toLowerCase() === activeType.toLowerCase();
                        });
                    }

                    // Update metrics and reset to page 1
                    updateKPIs();
                    showPage(1);
                    return; // Exit here. Do not run the sorting logic below!
                }

                // --- NORMAL SORTING LOGIC FOR OTHER COLUMNS ---
                const isNumericCol = th.classList.contains('numeric');
                const isDateCol = th.classList.contains('date');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 
                
                document.querySelectorAll('#movements-table th.sortable').forEach(header => {
                    // Do not reset the visual state of the Type header text!
                    if(parseInt(header.getAttribute('data-col')) !== 2) {
                        header.classList.remove('asc', 'desc');
                    }
                });
                
                th.classList.add(isAsc ? 'desc' : 'asc');
                
                // Sort ONLY the currently filtered rows
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
                
                // Re-append to physical DOM to apply the sort visually
                currentRows.forEach(row => tbody.appendChild(row));
                showPage(1);
            });
        });

        // Initialize table state on load
        showPage(1);
    });
</script>
<?= $this->endSection() ?>