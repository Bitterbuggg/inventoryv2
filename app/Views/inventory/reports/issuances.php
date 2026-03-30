<?php

declare(strict_types=1);

$title = 'Issuance Report - InventoryV2';
$pageTitle = 'Report: Issuances';
$pageSubtitle = 'Issuance totals and status performance over time.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Issuances'],
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
    .icon-requested { background: #fffbeb; color: #d97706; } 
    .icon-issued { background: #e0f2fe; color: #0284c7; }   
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
    
    /* Toolbar Controls (Server-side date/status filters) */
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
    .ci-pager li span.ellipsis { border: none !important; background: transparent !important; padding: 0 4px !important; min-width: auto; color: var(--v2-text-muted); }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $issuancesExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? ''), 'date_from' => ($date_from ?? ''), 'date_to' => ($date_to ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') . '?' . $issuancesExportQuery ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Low Stock</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$issuanceRows = $rows ?? [];
$totalRows = count($issuanceRows);
$totalRequested = array_sum(array_map(static fn (array $row): float => (float) ($row['total_requested_qty'] ?? 0), $issuanceRows));
$totalIssued = array_sum(array_map(static fn (array $row): float => (float) ($row['total_issued_qty'] ?? 0), $issuanceRows));
$releasedCount = count(array_filter($issuanceRows, static fn (array $row): bool => ($row['status'] ?? '') === 'released'));
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Issuance Report</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-rows"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-rows"><?= esc((string) $totalRows) ?></span>
                    <span class="kpi-label">Issuance Records</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-amber">
                <div class="kpi-icon-box icon-requested"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-requested"><?= esc(app_format_quantity($totalRequested)) ?></span>
                    <span class="kpi-label">Total Requested</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-issued"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-issued"><?= esc(app_format_quantity($totalIssued)) ?></span>
                    <span class="kpi-label">Total Issued</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-released"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-released" style="color: #15803d;"><?= esc((string) $releasedCount) ?></span>
                    <span class="kpi-label">Released Records</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Issuance Ledger</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Historical performance and status tracking.</p>
            </div>
            
            <div class="toolbar-controls">
                <form method="get" action="<?= site_url('reports/issuances') ?>" style="display: flex; gap: 10px; align-items: center; margin: 0;">
                    <div class="filter-group">
                        <span class="filter-label">Status:</span>
                        <select name="status" class="select-v2">
                            <option value="">All Statuses</option>
                            <?php foreach (['draft', 'submitted', 'approved', 'rejected', 'released', 'cancelled'] as $opt): ?>
                                <option value="<?= esc($opt) ?>" <?= (($status ?? '') === $opt) ? 'selected' : '' ?>><?= esc(ucwords($opt)) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">From:</span>
                        <input type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>" class="input-v2">
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">To:</span>
                        <input type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>" class="input-v2">
                    </div>
                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 800; background: var(--v2-label); border: none; border-radius: 6px; color: white;">Apply</button>
                    <a href="<?= site_url('reports/issuances') ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 800; border-radius: 6px; text-decoration: none;">Reset</a>
                </form>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="issuances-table" style="table-layout: fixed; width: 100%; min-width: 900px;">
                <colgroup>
                    <col style="width: 50px;">
                    <col style="width: 150px;">
                    <col style="width: 100px;">
                    <col style="width: 120px;">
                    <col style="width: 25%;">
                    <col style="width: 150px;">
                    <col style="width: 130px;">
                    <col style="width: 130px;">
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">Issuance #</th>
                        <th class="sortable numeric" data-col="2">Requestor</th>
                        <th class="sortable date" data-col="3">Issue Date</th>
                        <th class="sortable" data-col="4">Department</th>
                        <th class="sortable" data-col="5" id="status-header" title="Click to cycle status filters!">Status <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span></th>
                        <th class="sortable numeric" data-col="6" style="text-align: right;">Requested</th>
                        <th class="sortable numeric" data-col="7" style="text-align: right;">Issued</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($issuanceRows ?? []) === []): ?>
                        <tr class="no-records-row">
                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No records found.</strong><br>
                                <span style="font-size: 0.8rem;">Adjust your status or date filters to see more data.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($issuanceRows as $row): ?>
                            <tr class="issuance-row" style="display: none;" data-status="<?= esc((string) ($row['status'] ?? '')) ?>">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) $row['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 800; color: var(--v2-label);"><?= esc((string) $row['issuance_number']) ?></td>
                                <td style="font-size: 0.85rem; font-weight: 600;">#<?= esc((string) $row['requestor_id']) ?></td>
                                <td style="font-size: 0.85rem; color: var(--v2-text-muted); font-weight: 600;"><?= esc((string) $row['issue_date']) ?></td>
                                <td style="font-weight: 700; color: var(--v2-text-main); word-break: break-word;"><?= esc((string) ($row['department'] ?? '-')) ?></td>
                                <td><?= view('components/shared/table_status_badge', ['status' => $row['status'] ?? 'unknown']) ?></td>
                                <td style="text-align: right; font-weight: 800; color: var(--v2-title);"><?= esc(app_format_quantity($row['total_requested_qty'] ?? 0)) ?></td>
                                <td style="text-align: right; font-weight: 800; color: #16a34a;"><?= esc(app_format_quantity($row['total_issued_qty'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalRows) ?></span>)
            </p>
            <nav aria-label="Issuances Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 15;
        const tbody = document.querySelector('#issuances-table tbody');
        if (!tbody) return;

        const allRows = Array.from(tbody.querySelectorAll('.issuance-row'));
        let currentRows = [...allRows]; 

        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalIndicator = document.getElementById('total-indicator');
        const statusHeader = document.getElementById('status-header');
        
        const kpiRows = document.getElementById('kpi-rows');
        const kpiRequested = document.getElementById('kpi-requested');
        const kpiIssued = document.getElementById('kpi-issued');
        const kpiReleased = document.getElementById('kpi-released');

        const statusCycle = ['All', 'draft', 'submitted', 'approved', 'rejected', 'released', 'cancelled'];
        let cycleIndex = 0;

        if (allRows.length === 0) return;

        function updateKPIs() {
            let sumRequested = 0, sumIssued = 0, countReleased = 0;
            currentRows.forEach(row => {
                sumRequested += parseFloat(row.children[6].innerText.replace(/,/g, '')) || 0;
                sumIssued += parseFloat(row.children[7].innerText.replace(/,/g, '')) || 0;
                if(row.getAttribute('data-status') === 'released') countReleased++;
            });
            
            kpiRows.innerText = currentRows.length;
            kpiRequested.innerText = sumRequested.toLocaleString('en-US');
            kpiIssued.innerText = sumIssued.toLocaleString('en-US');
            kpiReleased.innerText = countReleased;
            totalIndicator.innerText = currentRows.length;
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
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    html += `<li><span class="ellipsis">...</span></li>`;
                }
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

        document.querySelectorAll('#issuances-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));

                if (colIndex === 5) {
                    cycleIndex = (cycleIndex + 1) % statusCycle.length;
                    const activeType = statusCycle[cycleIndex];
                    statusHeader.innerHTML = activeType === 'All' ? `Status (All)` : `Status <br><span class="filter-active-text">${activeType}</span>`;

                    currentRows = activeType === 'All' ? [...allRows] : allRows.filter(row => row.getAttribute('data-status') === activeType.toLowerCase());
                    updateKPIs();
                    showPage(1);
                    return;
                }

                const isNumericCol = th.classList.contains('numeric');
                const isDateCol = th.classList.contains('date');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 

                document.querySelectorAll('#issuances-table th.sortable').forEach(header => {
                    if(parseInt(header.getAttribute('data-col')) !== 5) header.classList.remove('asc', 'desc');
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
