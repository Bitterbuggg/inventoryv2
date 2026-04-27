<?php

declare(strict_types=1);

$title = 'Receiving - InventoryV2';
$pageTitle = 'Receiving';
$pageSubtitle = 'Track receiving drafts, posting status, and conversion-ready PO requests.';
$crumbs = [
    ['label' => 'Receiving'],
];

$user = function_exists('auth') ? auth()->user() : null;
$canManagePo = $user !== null && method_exists($user, 'can') && $user->can('procurement.po.create');
$canManagePoRequests = $user !== null && method_exists($user, 'can') && $user->can('procurement.por.manage');
$canViewInventory = $user !== null && method_exists($user, 'can') && $user->can('inventory.view', 'inventory.quantity.update');
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- V2 DESIGN SYSTEM VARIABLES --- */
    :root {
        --v2-border: #cbd5e1; 
        --v2-title: #0f172a;  
        --v2-label: #0284c7;  
        --v2-active-bg: #0369a1; 
        --v2-text-main: #334155; 
        --v2-text-muted: #64748b;
        --v2-bg-main: #f8fafc;
    }

    /* --- VIEWPORT WRAPPER --- */
    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        min-height: 800px;
        padding-bottom: 40px;
    }

    /* --- BULLETPROOF KPI CARDS --- */
    .kpi-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
        gap: 16px; 
        flex-shrink: 0; 
    }
    
    .kpi-card { 
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 10px; 
        padding: 16px; 
        display: flex; 
        align-items: center; 
        gap: 16px; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.05); 
    }
    
    .kpi-icon-box {
        width: 48px; height: 48px; 
        border-radius: 10px; 
        display: flex; align-items: center; justify-content: center; 
        flex-shrink: 0;
    }

    .icon-slate { background: #f1f5f9; color: #475569; }        
    .icon-amber { background: #fffbeb; color: #d97706; } 
    .icon-green { background: #f0fdf4; color: #16a34a; }   

    .kpi-details { display: flex; flex-direction: column; justify-content: center; flex: 1; min-width: 0; }
    .kpi-value { font-size: 1.5rem; font-weight: 900; color: var(--v2-title); line-height: 1; margin: 0 0 4px 0; display: block; }
    .kpi-label { font-size: 0.75rem; font-weight: 700; color: var(--v2-text-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.05em; display: block; }

    /* --- V2 TABLE CARD --- */
    .table-card {
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 10px; 
        display: flex;
        flex-direction: column;
        flex: 1; 
        min-height: 0; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.05); 
        overflow: hidden;
    }

    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 16px 20px; 
        border-bottom: 1px solid var(--v2-border); 
        background: #ffffff; 
        flex-shrink: 0;
        flex-wrap: wrap;
    }
    
    .table-toolbar h3 { margin: 0; font-size: 1.1rem; color: var(--v2-title); font-weight: 800; }
    .table-toolbar p { margin: 4px 0 0 0; font-size: 0.8rem; color: var(--v2-text-muted); }
    
    /* Clean Flex Form for Toolbar */
    .toolbar-controls { 
        display: flex; 
        gap: 12px; 
        align-items: center; 
        flex: 1; 
        justify-content: flex-end; 
        flex-wrap: wrap; 
    }
    
    .search-wrap { position: relative; width: 300px; max-width: 100%; flex-shrink: 0; }
    .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 14px; height: 14px; }
    
    .input-v2, .filter-select { 
        padding: 8px 12px; 
        font-size: 0.85rem; 
        border: 1px solid var(--v2-border); 
        border-radius: 6px; 
        outline: none; 
        color: var(--v2-title);
        background: #ffffff;
        transition: all 0.2s;
        height: 36px; 
        box-sizing: border-box;
    }
    .search-input { width: 100%; padding-left: 32px; }
    .filter-select { width: 160px; flex-shrink: 0; cursor: pointer; font-weight: 600; }
    .input-v2:focus, .filter-select:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15); }

    /* --- RESPONSIVE SCROLLING TABLE (Anti-Squish) --- */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Standardized V2 Table - NO table-layout: fixed! Let it breathe! */
    .modern-table { 
        width: 100%; 
        border-collapse: separate; 
        border-spacing: 0; 
        min-width: 900px; /* Forces horizontal scroll on small screens instead of squishing */
    }
    .modern-table th { 
        position: sticky; top: 0; z-index: 10;
        background: #f8fafc !important; 
        padding: 14px 16px; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        font-weight: 800; 
        color: var(--v2-title); 
        box-shadow: inset 0 -1px 0 var(--v2-border); 
        text-align: left; 
        white-space: nowrap; 
    }
    .modern-table td { 
        padding: 14px 16px; 
        font-size: 0.85rem; 
        color: var(--v2-text-main); 
        border-bottom: 1px solid #f1f5f9; 
        vertical-align: middle; 
        white-space: nowrap; /* CRITICAL: Prevents text from squishing onto multiple lines */
    }
    .modern-table tr:hover td { background: #f0f9ff; }

    /* Action Widget Table Override */
    .action-table { min-width: 100%; } 

    /* --- SORTABLE HEADERS --- */
    th.sortable { cursor: pointer; padding-right: 24px !important; user-select: none; transition: background 0.2s ease; }
    th.sortable:hover { background-color: #e2e8f0 !important; }
    th.sortable::after { content: '↕'; position: absolute; right: 8px; top: 50%; transform: translateY(-50%); font-size: 0.7rem; opacity: 0.3; color: var(--v2-title); }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--v2-label); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--v2-label); font-weight: bold; }

    .filter-active-text { color: var(--v2-label); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; display: inline-block; margin-left: 4px; }

    /* --- PAGINATION FOOTER --- */
    .table-footer {
        padding: 12px 20px;
        border-top: 1px solid var(--v2-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
        flex-shrink: 0;
    }
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li a, .ci-pager li span { display: inline-flex; align-items: center; justify-content: center; padding: 4px 10px; font-size: 0.8rem; min-width: 32px; border: 1px solid var(--v2-border); border-radius: 6px; background: #ffffff; color: var(--v2-text-main); text-decoration: none; font-weight: 700; transition: all 0.2s ease; }
    .ci-pager li a:hover { background: #f1f5f9; border-color: #94a3b8; }
    .ci-pager li.active a { background: var(--v2-label); color: #ffffff; border-color: var(--v2-label); }
    .ci-pager li.disabled a { opacity: 0.5; background: #f8fafc; color: var(--v2-text-muted); pointer-events: none; border-color: #cbd5e1; }
    
    /* Action Buttons */
    .btn-link-view { font-size: 0.75rem; color: var(--v2-label); text-decoration: none; font-weight: 800; padding: 6px 12px; border-radius: 6px; transition: background 0.2s ease; border: 1px solid transparent; text-transform: uppercase; letter-spacing: 0.05em; }
    .btn-link-view:hover { color: var(--v2-title); background: rgba(2, 132, 199, 0.1); border-color: #bae6fd; }
    
    .btn-action-primary { background: var(--v2-label); color: white; padding: 6px 16px; font-size: 0.8rem; font-weight: 800; border-radius: 6px; text-decoration: none; transition: background 0.2s; border: none; display: inline-flex; align-items: center; justify-content: center; height: 36px; cursor: pointer; }
    .btn-action-primary:hover { background: var(--v2-active-bg); }

    /* Chip Styling */
    #filter-chips-container { display: flex; flex-wrap: wrap; gap: 8px; padding: 0 20px; background: #ffffff; flex-shrink: 0; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $receivingExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('receiving') . '?' . $receivingExportQuery ?>" title="Download the current list of receiving records as a CSV file" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<?php if ($canManagePoRequests): ?>
    <a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>" title="View approved purchase requests ready for receiving" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">PO Requests</a>
<?php endif ?>
<?php if ($canManagePo): ?>
    <a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>" title="View all issued purchase orders" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Purchase Orders</a>
<?php endif ?>
<?php if ($canViewInventory): ?>
    <a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>" title="View current inventory stock levels" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Inventory Quantities</a>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $receivings ?? [];
$totalReceivings = count($rows);
$draftReceivings = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'draft'));
$postedReceivings = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'posted'));
$convertibleCount = count($convertiblePoRequests ?? []);
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0; display: flex; justify-content: space-between; align-items: flex-end;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Receiving</h2>
    </div>

    <div class="table-card" style="flex: none;">
        <div class="table-toolbar">
            <div>
                <h3 style="display: flex; align-items: center; gap: 8px; color: var(--v2-label);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Ready for Conversion
                </h3>
                <p>Approved Purchase Orders awaiting receiving documentation.</p>
            </div>
            <?php if ($convertibleCount > 0): ?>
                <span style="background: #e0f2fe; color: #0284c7; padding: 6px 12px; font-weight: 900; font-size: 0.75rem; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid #bae6fd;">
                    <?= esc((string) $convertibleCount) ?> Pending
                </span>
            <?php endif; ?>
        </div>
        
        <?php if (($convertiblePoRequests ?? []) === []): ?>
            <div style="padding: 30px; text-align: center; color: var(--v2-text-muted);">
                <p style="margin: 0; font-weight: 800; font-size: 1rem; color: var(--v2-title);">You are all caught up.</p>
                <p style="margin: 4px 0 0 0; font-size: 0.85rem;">No approved PO requests require conversion at this time.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive" style="max-height: 250px;">
                <table class="modern-table action-table">
                    <colgroup>
                        <col style="width: 30%;">
                        <col style="width: auto;">
                        <col style="width: 150px;">
                        <col style="width: 100px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Purchase Order</th>
                            <th>Request Ref</th>
                            <th>Status</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($convertiblePoRequests as $poRequest): ?>
                            <tr>
                                <td style="font-family: var(--font-mono); font-weight: 800; color: var(--v2-title);">PO-<?= esc((string) $poRequest['purchase_order_id']) ?></td>
                                <td style="font-weight: 600; color: var(--v2-text-muted);">#<?= esc((string) $poRequest['id']) ?></td>
                                <td><?= view('components/shared/table_status_badge', ['status' => $poRequest['status'] ?? 'unknown']) ?></td>
                                <td style="text-align: right;">
                                    <a class="btn-action-primary" href="<?= site_url('receiving/create/from-po-request/' . $poRequest['id']) ?>">Convert</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card">
                <div class="kpi-icon-box icon-slate"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value" id="kpi-visible"><?= esc((string) $totalReceivings) ?></p>
                    <p class="kpi-label">Visible Records</p>
                </div>
            </article>
            <article class="kpi-card">
                <div class="kpi-icon-box icon-amber"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value" id="kpi-draft" style="color: #d97706;"><?= esc((string) $draftReceivings) ?></p>
                    <p class="kpi-label">Draft Status</p>
                </div>
            </article>
            <article class="kpi-card">
                <div class="kpi-icon-box icon-green"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value" id="kpi-posted" style="color: #16a34a;"><?= esc((string) $postedReceivings) ?></p>
                    <p class="kpi-label">Posted Status</p>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Receiving History</h3>
                <p>View past receiving records and draft statuses.</p>
            </div>
            
            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="input-v2 search-input" placeholder="Search by number or PO..." autocomplete="off">
                </div>
                
                <form id="server-filter-form" method="get" action="<?= site_url('receiving') ?>" style="margin: 0; display: flex; gap: 8px; align-items: center;">
                    <?php $receivingStatusLabels = ['draft' => 'Draft', 'posted' => 'Posted', 'voided' => 'Voided']; ?>
                    <select id="status" name="status" class="filter-select" aria-label="Filter receiving by status">
                        <option value="">All Statuses</option>
                        <?php foreach ($receivingStatusLabels as $option => $label): ?>
                            <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach ?>
                    </select>
                    <button type="submit" class="btn-action-primary">Filter Server</button>
                </form>
            </div>
        </div>

        <div id="filter-chips-container"></div>

        <div class="table-responsive">
            <table class="modern-table" id="receivings-table">
                <colgroup>
                    <col style="width: 80px;">  <col style="width: 250px;"> <col style="width: 150px;"> <col style="width: auto;">  <col style="width: 150px;"> <col style="width: 130px;"> <col style="width: 100px;"> </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">Receiving Number</th>
                        <th class="sortable" data-col="2">PO Request</th>
                        <th class="sortable" data-col="3">Delivery Ref</th>
                        <th class="sortable date" data-col="4">Received Date</th>
                        <th class="sortable" data-col="5" id="status-header" title="Click to cycle status filters!">
                            Status <span class="filter-active-text">(All)</span>
                        </th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($receivings ?? []) === []): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No receiving records found.</strong><br>
                                <span style="font-size: 0.8rem;">Adjust your filters to see more results.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($receivings as $receiving): ?>
                            <tr class="rec-row" style="display: none;" data-status="<?= esc(strtolower((string) ($receiving['status'] ?? ''))) ?>">
                                <td style="font-weight: 800; color: #94a3b8;"><?= esc((string) $receiving['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 800; color: var(--v2-label);"><?= esc((string) $receiving['receiving_number']) ?></td>
                                <td style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--v2-text-muted); font-weight: 600;">#<?= esc((string) $receiving['po_request_id']) ?></td>
                                <td style="font-size: 0.85rem; font-weight: 600; color: var(--v2-title);"><?= esc((string) ($receiving['delivery_reference'] ?? '-')) ?></td>
                                <td style="font-size: 0.85rem; font-weight: 600; color: var(--v2-text-main);"><?= esc((string) $receiving['received_date']) ?></td>
                                <td><?= view('components/shared/table_status_badge', ['status' => $receiving['status'] ?? 'unknown']) ?></td>
                                <td style="text-align: right;">
                                    <a class="btn-link-view" href="<?= site_url('receiving/' . $receiving['id']) ?>">View</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalReceivings) ?></span>)
            </p>
            <nav aria-label="Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
            const chipsContainer = document.getElementById('filter-chips-container');

            const statusCycle = ['All', 'draft', 'posted', 'voided'];
            let cycleIndex = 0;
            let currentStatusFilter = 'All';

            function toTitleCase(str) {
                return str.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            }

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
                    createChip(`Status: ${toTitleCase(currentStatusFilter)}`, () => {
                        currentStatusFilter = 'All';
                        cycleIndex = 0;
                        statusHeader.innerHTML = `Status <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>`;
                        applyFilters();
                    });
                }

                if (query !== '' || currentStatusFilter !== 'All') {
                    const clearAll = document.createElement('button');
                    clearAll.innerText = 'Clear All';
                    clearAll.style = 'background:none; border:none; color:#ef4444; font-size:0.75rem; cursor:pointer; font-weight:800; padding:4px 8px; text-transform: uppercase;';
                    clearAll.onclick = () => {
                        searchInput.value = '';
                        currentStatusFilter = 'All';
                        cycleIndex = 0;
                        statusHeader.innerHTML = `Status <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>`;
                        applyFilters();
                    };
                    chipsContainer.appendChild(clearAll);
                    chipsContainer.style.padding = "10px 20px 10px 20px"; 
                } else {
                    chipsContainer.style.padding = "0px";
                }
            }

            function createChip(label, onClear) {
                const chip = document.createElement('div');
                chip.style = 'background:#f1f5f9; color:var(--v2-title); border:1px solid var(--v2-border); display:flex; align-items:center; gap:6px; padding:4px 10px; font-size:0.75rem; font-weight: 700; border-radius: 6px;';
                
                const span = document.createElement('span');
                span.innerText = label;
                
                const close = document.createElement('span');
                close.innerHTML = '&times;';
                close.style = 'cursor:pointer; font-size:1.2rem; line-height:0.8; font-weight:bold; color: #94a3b8; transition: color 0.2s;';
                close.onmouseover = () => close.style.color = '#ef4444';
                close.onmouseout = () => close.style.color = '#94a3b8';
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

            if(statusHeader) {
                statusHeader.addEventListener('click', (e) => {
                    e.stopPropagation(); 
                    
                    cycleIndex = (cycleIndex + 1) % statusCycle.length;
                    currentStatusFilter = statusCycle[cycleIndex];

                    if (currentStatusFilter === 'All') {
                        statusHeader.innerHTML = `Status <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>`;
                    } else {
                        statusHeader.innerHTML = `Status <br><span class="filter-active-text" style="color: var(--v2-label); font-weight: 800; font-size: 0.65rem; text-transform: uppercase;">${toTitleCase(currentStatusFilter)}</span>`;
                    }
                    
                    applyFilters();
                });
            }

            document.querySelectorAll('#receivings-table th.sortable').forEach(th => {
                if (parseInt(th.getAttribute('data-col')) === 5) return; 

                th.addEventListener('click', () => {
                    const colIndex = parseInt(th.getAttribute('data-col'));
                    const isNumericCol = th.classList.contains('numeric');
                    const isDateCol = th.classList.contains('date');
                    const isAsc = th.classList.contains('asc');
                    const direction = isAsc ? -1 : 1; 
                    
                    document.querySelectorAll('#receivings-table th.sortable').forEach(header => {
                        if (parseInt(header.getAttribute('data-col')) !== 5) {
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

            // Init
            updateChips();
            showPage(1);
        }
    });
</script>
<?= $this->endSection() ?>