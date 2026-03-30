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

    .icon-ranked { background: #f1f5f9; color: #475569; }        
    .icon-qty { background: #e0f2fe; color: #0284c7; } 
    .icon-top { background: #fef3c7; color: #d97706; }   
    .icon-topqty { background: #ecfccb; color: #16a34a; }   

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
        gap: 8px; 
        align-items: center; 
        flex: 1; 
        justify-content: flex-end; 
    }

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

    .search-wrap { position: relative; width: 220px; }
    .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 14px; height: 14px; }
    .search-input { width: 100%; padding-left: 30px; }

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

    /* --- VOLUME BAR & BADGES --- */
    .volume-bar-container { position: relative; display: flex; align-items: center; justify-content: flex-end; min-height: 24px; padding-right: 8px; background: #f8fafc; border-radius: 4px; overflow: hidden; border: 1px solid #f1f5f9; }
    .volume-bar-fill { position: absolute; right: 0; top: 0; bottom: 0; background: linear-gradient(to left, var(--v2-label), #0ea5e9); z-index: 1; opacity: 0.25; transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
    .volume-bar-text { position: relative; z-index: 2; font-weight: 800; font-family: var(--font-mono); color: var(--v2-title); font-size: 0.85rem; }
    
    .rank-badge { display: inline-flex; align-items: center; justify-content: center; background: #f1f5f9; color: var(--v2-text-muted); border-radius: 50%; width: 28px; height: 28px; font-size: 0.75rem; font-weight: 900; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .rank-1 { background: #fef08a; color: #854d0e; border: 1px solid #fde047; }
    .rank-2 { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
    .rank-3 { background: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }

    /* --- PAGINATION FOOTER --- */
    .table-footer { padding: 10px 20px; border-top: 1px solid var(--v2-border); display: flex; justify-content: space-between; align-items: center; background: #ffffff; flex-shrink: 0; }
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li a, .ci-pager li span { display: inline-flex; align-items: center; justify-content: center; padding: 4px 10px; font-size: 0.75rem; min-width: 28px; border: 1px solid var(--v2-border); border-radius: 4px; background: #ffffff; color: var(--v2-label); text-decoration: none; font-weight: 700; transition: all 0.2s ease; }
    .ci-pager li a:hover { background: rgba(178, 224, 235, 0.3); border-color: var(--v2-label); }
    .ci-pager li.active a { background: var(--v2-label); color: #ffffff; border-color: var(--v2-label); }
    .ci-pager li.disabled a { opacity: 0.5; background: #f1f5f9; color: var(--v2-text-muted); pointer-events: none; border-color: #cbd5e1; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $fastMovingExportQuery = http_build_query(['export' => 'csv', 'date_from' => ($date_from ?? ''), 'date_to' => ($date_to ?? ''), 'limit' => ($limit ?? 20)]); ?>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') . '?' . $fastMovingExportQuery ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Low Stock</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$fastRows = $rows ?? [];
$totalRows = count($fastRows);
$totalQtyOut = array_sum(array_map(static fn (array $row): float => (float) ($row['total_qty_out'] ?? 0), $fastRows));
$topItem = $fastRows[0]['item_name'] ?? 'N/A';
$topItemQty = (float) ($fastRows[0]['total_qty_out'] ?? 0);
$maxQty = $topItemQty > 0 ? $topItemQty : 1; 
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Fast Moving Items</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-ranked"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-ranked"><?= esc((string) $totalRows) ?></span>
                    <span class="kpi-label">Ranked Items</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-qty"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-qty"><?= esc(app_format_quantity($totalQtyOut)) ?></span>
                    <span class="kpi-label">Total Volume</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-amber">
                <div class="kpi-icon-box icon-top"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" style="font-size: 0.9rem; word-break: break-all; line-height: 1.1;"><?= esc((string) $topItem) ?></span>
                    <span class="kpi-label">#1 Top Item</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-topqty"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc(app_format_quantity($topItemQty)) ?></span>
                    <span class="kpi-label">Max Qty Out</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Movement Ranking</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Showing top movers by issued quantity.</p>
            </div>
            
            <div class="toolbar-controls">
                <form method="get" action="<?= site_url('reports/fast-moving') ?>" style="display: flex; gap: 8px; align-items: center; margin: 0; border-right: 1px solid var(--v2-border); padding-right: 12px; margin-right: 4px;">
                    <input type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>" class="input-v2">
                    <input type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>" class="input-v2">
                    <span style="font-size: 0.7rem; font-weight: 800; color: var(--v2-text-muted);">LIMIT:</span>
                    <input type="number" name="limit" value="<?= esc((string) ($limit ?? 20)) ?>" class="input-v2" style="width: 60px; text-align: center;">
                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 800; background: var(--v2-label); border: none; border-radius: 6px; color: white;">Apply</button>
                </form>

                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Quick find item" autocomplete="off">
                </div>
                
                <button type="button" class="btn btn-outline" id="btn-clear-search" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px; height: 32px;">Clear</button>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="fast-table" style="table-layout: fixed; width: 100%; min-width: 800px;">
                <colgroup>
                    <col style="width: 80px;">
                    <col style="width: 45%;">
                    <col style="width: 15%;">
                    <col style="width: 32%;">
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0" style="text-align: center;">Rank</th>
                        <th class="sortable" data-col="1">Item Name</th>
                        <th class="sortable" data-col="2">Unit</th>
                        <th class="sortable numeric asc" data-col="3" style="text-align: right;">Volume Performance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($rows ?? []) === []): ?>
                        <tr class="no-records-row">
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No movement data found.</strong><br>
                                <span style="font-size: 0.8rem;">Try adjusting the date range or limit.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $rankCounter = 1;
                        foreach ($rows as $row): 
                            $qtyOut = (float) $row['total_qty_out'];
                            $percent = ($qtyOut / $maxQty) * 100;
                            $rankClass = ($rankCounter === 1) ? 'rank-1' : (($rankCounter === 2) ? 'rank-2' : (($rankCounter === 3) ? 'rank-3' : ''));
                        ?>
                            <tr class="fast-row" style="display: none;" data-qty="<?= esc((string) $qtyOut) ?>">
                                <td style="text-align: center;">
                                    <span class="rank-badge <?= $rankClass ?>"><?= $rankCounter ?></span>
                                </td>
                                <td style="font-weight: 800; color: var(--v2-text-main); word-break: break-word;"><?= esc((string) $row['item_name']) ?></td>
                                <td style="font-size: 0.85rem; font-weight: 600; color: var(--v2-text-muted);"><?= esc((string) $row['unit']) ?></td>
                                <td>
                                    <div class="volume-bar-container">
                                        <div class="volume-bar-fill" style="width: <?= esc((string) $percent) ?>%;"></div>
                                        <span class="volume-bar-text"><?= esc(app_format_quantity($qtyOut)) ?></span>
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

        function applySearch() {
            const query = searchInput.value.toLowerCase().trim();
            currentRows = query === '' ? [...allRows] : allRows.filter(row => row.children[1].innerText.toLowerCase().includes(query));
            
            currentRows.forEach(row => tbody.appendChild(row));
            updateKPIs();
            showPage(1);
        }

        function updateKPIs() {
            let sumQty = 0;
            currentRows.forEach(row => { sumQty += parseFloat(row.getAttribute('data-qty')) || 0; });
            kpiRanked.innerText = currentRows.length;
            kpiQty.innerText = sumQty.toLocaleString('en-US');
            totalIndicator.innerText = currentRows.length;
        }

        if(searchInput) searchInput.addEventListener('input', applySearch);
        if(clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
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
            currentRows.forEach((row, index) => { if (index >= startPoint && index < endPoint) row.style.display = ''; });
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

        document.querySelectorAll('#fast-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const isNumericCol = th.classList.contains('numeric');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 
                document.querySelectorAll('#fast-table th.sortable').forEach(header => header.classList.remove('asc', 'desc'));
                th.classList.add(isAsc ? 'desc' : 'asc');
                currentRows.sort((a, b) => {
                    let aVal = a.children[colIndex].innerText.trim();
                    let bVal = b.children[colIndex].innerText.trim();
                    if (colIndex === 3) { aVal = a.getAttribute('data-qty'); bVal = b.getAttribute('data-qty'); }
                    if (isNumericCol) return (parseFloat(aVal) - parseFloat(bVal)) * direction;
                    return aVal.localeCompare(bVal) * direction;
                });
                currentRows.forEach(row => tbody.appendChild(row));
                showPage(1);
            });
        });

        showPage(1);
    });
</script>
<?= $this->endSection() ?>
