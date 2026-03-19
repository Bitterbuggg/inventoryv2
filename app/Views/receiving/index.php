<?php

declare(strict_types=1);

$title = 'Receiving - InventoryV2';
$pageTitle = 'Receiving';
$pageSubtitle = 'Track receiving drafts, posting status, and conversion-ready PO requests.';
$crumbs = [
    ['label' => 'Receiving'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* Custom JS Pager */
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li { display: block; }
    .ci-pager li a, .ci-pager li span { display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; font-size: 0.85rem; min-width: 32px; border: 1px solid var(--color-border-strong); border-radius: var(--radius-sm); background: var(--color-surface); color: var(--color-brand-700); text-decoration: none; font-weight: 600; transition: all 0.2s ease; }
    .ci-pager li a:hover { background: var(--color-brand-100); border-color: var(--color-brand-500); }
    .ci-pager li.active a { background: var(--color-brand-500); color: #ffffff; border-color: var(--color-brand-600); }
    .ci-pager li.disabled a { opacity: 0.5; background: var(--color-surface-alt); color: var(--color-text-muted); pointer-events: none; border-color: var(--color-border); }
    .ci-pager li span.ellipsis { border: none !important; background: transparent !important; padding: 0 4px !important; min-width: auto; color: var(--color-text-muted); }

    /* Sortable Headers */
    th.sortable { cursor: pointer; position: relative; padding-right: 18px !important; user-select: none; transition: background 0.2s ease; }
    th.sortable:hover { background: rgba(0, 0, 0, 0.03) !important; }
    th.sortable::after { content: '↕'; position: absolute; right: 6px; top: 50%; transform: translateY(-50%); font-size: 0.75rem; opacity: 0.3; }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    
    .filter-active-text { color: var(--color-brand-600); font-weight: 800; text-transform: uppercase; }

    /* --- HCI COMPLIANT ACTION QUEUE --- */
    .action-widget {
        background: var(--color-surface);
        border: 1px solid var(--color-border-strong);
        border-radius: var(--radius-md);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    .action-widget-header {
        padding: 16px 24px;
        background: #f8fafc; /* Very subtle cool gray, replaces the loud cyan */
        border-bottom: 1px solid var(--color-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .action-widget-list {
        max-height: 260px; /* Fits exactly ~4.5 rows to hint at scrolling */
        overflow-y: auto;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    /* Elegant scrollbar for the queue */
    .action-widget-list::-webkit-scrollbar { width: 6px; }
    .action-widget-list::-webkit-scrollbar-track { background: transparent; }
    .action-widget-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .action-widget-list::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    .action-widget-row {
        display: grid;
        grid-template-columns: 2fr 1fr auto; /* Clean alignment */
        align-items: center;
        gap: 16px;
        padding: 12px 24px;
        border-bottom: 1px solid var(--color-border-subtle);
        transition: background-color 0.15s ease;
    }
    .action-widget-row:last-child { border-bottom: none; }
    .action-widget-row:hover { background-color: #f1f5f9; } /* Subtle hover state */
    
    .row-meta { display: flex; flex-direction: column; gap: 2px; }
    .row-title { font-family: var(--font-mono); font-weight: 600; color: var(--color-brand-700); font-size: 0.95rem; }
    .row-sub { font-size: 0.8rem; color: var(--color-text-muted); }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $receivingExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('receiving') . '?' . $receivingExportQuery ?>" title="Download the current list of receiving records as a CSV file">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>" title="View approved purchase requests ready for receiving">PO Requests</a>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>" title="View all issued purchase orders">Purchase Orders</a>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>" title="View current inventory stock levels">Inventory Quantities</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $receivings ?? [];
$totalReceivings = count($rows);
$draftReceivings = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'draft'));
$postedReceivings = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'posted'));
$convertibleCount = count($convertiblePoRequests ?? []);
?>
<div class="stack-lg">
    
    <section>
        <div class="action-widget">
            <div class="action-widget-header">
                <div>
                    <h2 style="margin: 0; font-size: 1.15rem; color: var(--color-text); display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-brand-600)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Ready for Conversion
                    </h2>
                    <p class="muted" style="margin: 4px 0 0 0; font-size: 0.85rem;">Approved Purchase Orders awaiting receiving documentation.</p>
                </div>
                <?php if ($convertibleCount > 0): ?>
                    <span class="badge" style="background: var(--color-brand-600); color: white; padding: 4px 10px; font-size: 0.85rem; border-radius: 6px;">
                        <?= esc((string) $convertibleCount) ?> Pending
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if (($convertiblePoRequests ?? []) === []): ?>
                <div style="padding: 32px; text-align: center; color: var(--color-text-muted);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.5;"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                    <p style="margin: 0; font-weight: 500;">You are all caught up.</p>
                    <p style="margin: 4px 0 0 0; font-size: 0.85rem;">No approved PO requests require conversion at this time.</p>
                </div>
            <?php else: ?>
                <ul class="action-widget-list">
                    <?php foreach ($convertiblePoRequests as $poRequest): ?>
                        <li class="action-widget-row">
                            <div class="row-meta">
                                <span class="row-title">PO-<?= esc((string) $poRequest['purchase_order_id']) ?></span>
                                <span class="row-sub">Request Ref: #<?= esc((string) $poRequest['id']) ?></span>
                            </div>
                            <div>
                                <?= view('components/shared/table_status_badge', ['status' => $poRequest['status'] ?? 'unknown']) ?>
                            </div>
                            <div>
                                <a class="btn btn-primary" style="padding: 6px 16px; font-size: 0.85rem;" href="<?= site_url('receiving/create/from-po-request/' . $poRequest['id']) ?>">
                                    Convert
                                </a>
                            </div>
                        </li>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>
        </div>
    </section>

    <section class="card stack-md">
        <h2 style="margin: 0; color: var(--color-text); font-size: 1.25rem; border-bottom: 1px solid var(--color-border); padding-bottom: 12px; margin-bottom: 8px;">Receiving History</h2>
        
        <div class="kpi-grid" style="margin-bottom: 8px;">
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Visible Receivings</p>
                <p class="kpi-value" id="kpi-visible" style="font-size: 1.25rem;"><?= esc((string) $totalReceivings) ?></p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Draft Status</p>
                <p class="kpi-value" id="kpi-draft" style="font-size: 1.25rem;"><?= esc((string) $draftReceivings) ?></p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Posted Status</p>
                <p class="kpi-value" id="kpi-posted" style="font-size: 1.25rem;"><?= esc((string) $postedReceivings) ?></p>
            </article>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <div style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
                <input type="text" id="instant-search-input" placeholder="Search by receiving number, PO request, or ID..." autocomplete="off" aria-label="Search receiving records" style="flex: 1;">
                <button type="button" class="btn btn-outline" id="btn-clear-search">Clear</button>
            </div>
            
            <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('receiving') ?>" style="margin: 0;">
                <?php $receivingStatusLabels = ['draft' => 'Draft', 'posted' => 'Posted', 'voided' => 'Voided']; ?>
                <select id="status" name="status" aria-label="Filter receiving by status" style="padding: 6px 12px; font-size: 0.85rem;">
                    <option value="">All Statuses</option>
                    <?php foreach ($receivingStatusLabels as $option => $label): ?>
                        <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($label) ?></option>
                    <?php endforeach ?>
                    </select>
                <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.85rem;" title="Filter history by status">Filter</button>
            </form>
        </div>

        <div id="filter-chips-container" style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 28px;">
            <!-- Chips injected by JS -->
        </div>

        <div class="table-wrap">
            <table class="table" id="receivings-table" style="table-layout: fixed; width: 100%; min-width: 800px;">
                <colgroup>
                    <col style="width: 80px;">  
                    <col style="width: 25%;">   
                    <col style="width: 20%;">   
                    <col style="width: 18%;">   
                    <col style="width: 130px;"> 
                    <col style="width: 130px;"> 
                    <col style="width: 100px;"> 
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">Receiving Number</th>
                        <th class="sortable" data-col="2">PO Request</th>
                        <th class="sortable" data-col="3">Delivery Ref</th>
                        <th class="sortable date" data-col="4">Received Date</th>
                        <th class="sortable" data-col="5" id="status-header" title="Click to cycle status filters!">Status (All)</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($receivings ?? []) === []): ?>
                        <tr class="no-records-row"><td colspan="7" class="empty-state">No receiving records found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($receivings as $receiving): ?>
                            <tr class="rec-row" style="display: none;" data-status="<?= esc(strtolower((string) ($receiving['status'] ?? ''))) ?>">
                                <td><?= esc((string) $receiving['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 500; color: var(--color-brand-700);"><?= esc((string) $receiving['receiving_number']) ?></td>
                                <td style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--color-text-muted);">#<?= esc((string) $receiving['po_request_id']) ?></td>
                                <td style="font-size: 0.85rem;"><?= esc((string) ($receiving['delivery_reference'] ?? '-')) ?></td>
                                <td style="font-size: 0.85rem;"><?= esc((string) $receiving['received_date']) ?></td>
                                <td><?= view('components/shared/table_status_badge', ['status' => $receiving['status'] ?? 'unknown']) ?></td>
                                <td class="actions">
                                    <div class="action-row">
                                        <a class="btn btn-outline view-action" style="padding: 4px 8px; font-size: 0.75rem;" href="<?= site_url('receiving/' . $receiving['id']) ?>">View</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
            <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                Showing records <span id="page-indicator"></span> (Total: <span id="total-indicator"><?= esc((string) $totalReceivings) ?></span>)
            </p>
            <nav aria-label="Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ==========================================
        // MAIN TABLE (RECEIVINGS) SCRIPT
        // ==========================================
        const rowsPerPage = 10; 
        const tbodyMain = document.querySelector('#receivings-table tbody');
        
        if (tbodyMain && tbodyMain.querySelector('.rec-row')) {
            const allRows = Array.from(tbodyMain.querySelectorAll('.rec-row'));
            let currentRows = [...allRows]; 

            const pagerContainer = document.getElementById('client-pager');
            const pageIndicator = document.getElementById('page-indicator');
            const totalIndicator = document.getElementById('total-indicator');
            const statusHeader = document.getElementById('status-header');
            
            const kpiVisible = document.getElementById('kpi-visible');
            const kpiDraft = document.getElementById('kpi-draft');
            const kpiPosted = document.getElementById('kpi-posted');

            const searchInput = document.getElementById('instant-search-input');
            const clearBtn = document.getElementById('btn-clear-search');
            const chipsContainer = document.getElementById('filter-chips-container');

            const statusCycle = ['All', 'draft', 'posted', 'voided'];
            let cycleIndex = 0;
            let currentStatusFilter = 'All';

            function updateChips() {
                chipsContainer.innerHTML = '';
                const query = searchInput.value.trim();
                
                if (query !== '') {
                    createChip(`Search: "${query}"`, () => {
                        searchInput.value = '';
                        applyFilters();
                    });
                }

                if (currentStatusFilter !== 'All') {
                    createChip(`Status: ${currentStatusFilter}`, () => {
                        currentStatusFilter = 'All';
                        cycleIndex = 0;
                        statusHeader.innerHTML = `Status (All)`;
                        applyFilters();
                    });
                }

                if (query !== '' || currentStatusFilter !== 'All') {
                    const clearAll = document.createElement('button');
                    clearAll.innerText = 'Clear All';
                    clearAll.style = 'background:none; border:none; color:var(--color-danger); font-size:0.75rem; cursor:pointer; font-weight:600; padding:4px 8px;';
                    clearAll.onclick = () => {
                        searchInput.value = '';
                        currentStatusFilter = 'All';
                        cycleIndex = 0;
                        statusHeader.innerHTML = `Status (All)`;
                        applyFilters();
                    };
                    chipsContainer.appendChild(clearAll);
                }
            }

            function createChip(label, onClear) {
                const chip = document.createElement('div');
                chip.className = 'status-badge';
                chip.style = 'background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; display:flex; align-items:center; gap:6px; padding:2px 8px; font-size:0.75rem;';
                
                const span = document.createElement('span');
                span.innerText = label;
                
                const close = document.createElement('span');
                close.innerHTML = '&times;';
                close.style = 'cursor:pointer; font-size:1.1rem; line-height:1; font-weight:bold;';
                close.onclick = onClear;

                chip.appendChild(span);
                chip.appendChild(close);
                chipsContainer.appendChild(chip);
            }

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();

                currentRows = allRows.filter(row => {
                    const id = row.children[0].innerText.toLowerCase();
                    const recNum = row.children[1].innerText.toLowerCase();
                    const poReq = row.children[2].innerText.toLowerCase();
                    const deliveryRef = row.children[3].innerText.toLowerCase();
                    const statusVal = row.getAttribute('data-status');

                    const matchesText = query === '' || id.includes(query) || recNum.includes(query) || poReq.includes(query) || deliveryRef.includes(query);
                    
                    let matchesStatus = true;
                    if (currentStatusFilter !== 'All') {
                        if (currentStatusFilter === 'voided') {
                            matchesStatus = statusVal.includes('void');
                        } else {
                            matchesStatus = statusVal === currentStatusFilter;
                        }
                    }

                    return matchesText && matchesStatus;
                });

                currentRows.forEach(row => tbodyMain.appendChild(row));
                updateKPIs();
                updateChips();
                showPage(1);
            }

            function updateKPIs() {
                let countDraft = 0, countPosted = 0;
                currentRows.forEach(row => {
                    const stat = row.getAttribute('data-status');
                    if (stat === 'draft') countDraft++;
                    if (stat === 'posted') countPosted++;
                });
                
                kpiVisible.innerText = currentRows.length;
                kpiDraft.innerText = countDraft;
                kpiPosted.innerText = countPosted;
                totalIndicator.innerText = currentRows.length;
            }

            if(searchInput) searchInput.addEventListener('input', applyFilters);
            if(clearBtn) {
                clearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    applyFilters();
                });
            }

            if(statusHeader) {
                statusHeader.addEventListener('click', (e) => {
                    e.stopPropagation(); 
                    
                    cycleIndex = (cycleIndex + 1) % statusCycle.length;
                    currentStatusFilter = statusCycle[cycleIndex];

                    if (currentStatusFilter === 'All') {
                        statusHeader.innerHTML = `Status (All)`;
                    } else {
                        statusHeader.innerHTML = `Status (<span class="filter-active-text">${currentStatusFilter}</span>)`;
                    }
                    
                    applyFilters();
                });
            }

            document.querySelectorAll('#receivings-table th.sortable').forEach(th => {
                if (parseInt(th.getAttribute('data-col')) === 4) return; 

                th.addEventListener('click', () => {
                    const colIndex = parseInt(th.getAttribute('data-col'));
                    const isNumericCol = th.classList.contains('numeric');
                    const isDateCol = th.classList.contains('date');
                    const isAsc = th.classList.contains('asc');
                    const direction = isAsc ? -1 : 1; 
                    
                    document.querySelectorAll('#receivings-table th.sortable').forEach(header => {
                        if (parseInt(header.getAttribute('data-col')) !== 4) {
                            header.classList.remove('asc', 'desc');
                        }
                    });
                    
                    th.classList.add(isAsc ? 'desc' : 'asc');
                    
                    currentRows.sort((a, b) => {
                        let aText = a.children[colIndex].innerText.trim().replace('#', '');
                        let bText = b.children[colIndex].innerText.trim().replace('#', '');
                        
                        if (isNumericCol) return (parseFloat(aText) - parseFloat(bText)) * direction;
                        if (isDateCol) {
                            let dateA = aText === '' ? 0 : new Date(aText).getTime();
                            let dateB = bText === '' ? 0 : new Date(bText).getTime();
                            return (dateA - dateB) * direction;
                        }
                        return aText.localeCompare(bText) * direction;
                    });
                    
                    currentRows.forEach(row => tbodyMain.appendChild(row));
                    showPage(1);
                });
            });

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
                    if (index >= startPoint && index < endPoint) row.style.display = '';
                });

                const actualEnd = Math.min(endPoint, totalRows);
                if (pageIndicator) pageIndicator.innerText = totalRows === 0 ? '0' : `${startPoint + 1} - ${actualEnd}`;

                if (pagerContainer) {
                    pagerContainer.innerHTML = '';
                    if (totalPages > 1) {
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
                }
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

            showPage(1);
        }
    });
</script>
<?= $this->endSection() ?>