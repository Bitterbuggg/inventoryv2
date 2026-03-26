<?php

declare(strict_types=1);

$title = 'PO Requests - InventoryV2';
$pageTitle = 'Procurement - PO Requests';
$pageSubtitle = 'Approve or reject PO requests before receiving conversion.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'PO Requests'],
];

$user = function_exists('auth') ? auth()->user() : null;
$isAdmin = $user !== null && method_exists($user, 'inGroup') && $user->inGroup('admin');
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

    /* Specific Icon Colors for PO Requests */
    .icon-total { background: #f1f5f9; color: #475569; }        
    .icon-pending { background: #fffbeb; color: #d97706; } 
    .icon-approved { background: #ecfccb; color: #16a34a; }   
    .icon-rejected { background: #fef2f2; color: #ef4444; }   

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
    
    .toolbar-controls { display: flex; gap: 8px; align-items: center; flex: 1; justify-content: flex-end; }
    
    .search-wrap { position: relative; width: 340px; }
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
    }
    .search-input { width: 100%; padding-left: 30px; }
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
        background: #ffffff !important; /* Pure white header */
        padding: 14px 16px; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        font-weight: 800; 
        color: var(--v2-title); /* BOLD DEEP BLUE */
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

    /* --- INLINE ACTION FORMS --- */
    .action-forms-container { display: flex; gap: 8px; align-items: center; justify-content: flex-start; flex-wrap: wrap; }
    
    .approval-input-group {
        display: inline-flex;
        align-items: stretch;
        border: 1px solid var(--v2-border);
        border-radius: 6px;
        overflow: hidden;
        background: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        height: 30px; 
    }
    .approval-input-group:focus-within { border-color: var(--v2-label); box-shadow: 0 0 0 2px rgba(0, 102, 140, 0.1); }
    
    .reject-input {
        border: none;
        padding: 4px 8px;
        font-size: 0.75rem;
        width: 140px;
        outline: none;
        background: transparent;
        color: var(--v2-text-main);
    }
    
    .btn-table { border: none; color: white; font-size: 0.7rem; font-weight: 800; padding: 4px 12px; cursor: pointer; transition: background 0.2s ease; text-transform: uppercase; border-radius: 4px; white-space: nowrap; height: 30px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; }
    .btn-approve { background: var(--v2-label); }
    .btn-approve:hover { background: var(--v2-active-bg); }
    
    .btn-reject { background: #fef2f2; color: #ef4444; border-left: 1px solid #fecaca; border-radius: 0 6px 6px 0; height: 100%; margin: 0;}
    .btn-reject:hover { background: #fee2e2; color: #dc2626; }

    /* --- SPECIAL STATUS BADGE --- */
    .status-badge-special {
        background: #e0e7ff; 
        color: #4338ca; 
        border: 1px solid #c7d2fe;
        padding: 3px 10px; 
        font-size: 0.7rem; 
        font-weight: 800; 
        border-radius: 9999px; 
        white-space: nowrap; 
        display: inline-block;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $poRequestExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') . '?' . $poRequestExportQuery ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<?php if ($isAdmin): ?>
<a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Pending Approvals</a>
<?php endif ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Purchase Orders</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $poRequests ?? [];
$totalRequests = count($rows);
$pendingRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'pending'));
$approvedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'approved'));
$rejectedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'rejected'));
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">PO Requests</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-total"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-visible"><?= esc((string) $totalRequests) ?></span>
                    <span class="kpi-label">Visible Requests</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-amber">
                <div class="kpi-icon-box icon-pending"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-pending" style="color: #b45309;"><?= esc((string) $pendingRequests) ?></span>
                    <span class="kpi-label">Pending</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-approved"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-approved" style="color: #15803d;"><?= esc((string) $approvedRequests) ?></span>
                    <span class="kpi-label">Approved</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-red">
                <div class="kpi-icon-box icon-rejected"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-rejected" style="color: #b91c1c;"><?= esc((string) $rejectedRequests) ?></span>
                    <span class="kpi-label">Rejected</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>PO Request Queue</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Review and process conversion requests.</p>
            </div>
            
            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Search PO request #, PO ID..." autocomplete="off">
                </div>
                
                <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('procurement/po-requests') ?>" style="margin: 0; display: flex; gap: 8px;">
                    <?php $poRequestStatusLabels = ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'converted_to_receiving' => 'Converted to Receiving']; ?>
                    <select id="status" name="status" class="filter-select" aria-label="Filter PO requests by status">
                        <option value="">All Statuses</option>
                        <?php foreach ($poRequestStatusLabels as $option => $label): ?>
                            <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach ?>
                    </select>
                    <button type="button" class="btn btn-outline" id="btn-clear-search" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px;">Clear</button>
                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px; background: var(--v2-label); border: none;">Filter Server</button>
                </form>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="po-req-table" style="table-layout: fixed; width: 100%; min-width: 1150px;">
                <colgroup>
                    <col style="width: 60px;">  
                    <col style="width: 250px;"> 
                    <col style="width: 100px;"> 
                    <col style="width: 120px;"> 
                    <col style="width: 150px;"> 
                    <col style="width: 130px;"> 
                    <col style="width: auto;">  
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">PO Request #</th>
                        <th class="sortable numeric" data-col="2">PO ID</th>
                        <th class="sortable date" data-col="3">Request Date</th>
                        <th class="sortable" data-col="4" id="status-header" title="Click to cycle status filters!">
                            Status <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>
                        </th>
                        <th class="sortable" data-col="5">Action By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($poRequests ?? []) === []): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No PO requests found.</strong><br>
                                <span style="font-size: 0.8rem;">Adjust your filters to see more results.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($poRequests as $poRequest): ?>
                            <tr class="po-req-row" style="display: none;" data-status="<?= esc(strtolower((string) ($poRequest['status'] ?? ''))) ?>">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) $poRequest['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 700; color: var(--v2-label);">
                                    <?= esc((string) $poRequest['po_request_number']) ?>
                                    
                                    <?php $po = $poRequest['purchase_order'] ?? null; ?>
                                    <?php if (is_array($po)): ?>
                                        <details style="margin-top: 6px; font-family: var(--font-sans); font-size: 0.75rem;">
                                            <summary style="cursor: pointer; color: #7C3AED; font-weight: 800;">View PO Details</summary>
                                            <div style="margin-top: 6px; line-height: 1.45; background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; color: var(--v2-text-main);">
                                                <div><strong>PO:</strong> <?= esc((string) ($po['po_number'] ?? '-')) ?></div>
                                                <div><strong>Supplier:</strong> <?= esc((string) ($po['supplier_name'] ?? '-')) ?></div>
                                                <div><strong>Total:</strong> ₱<?= number_format((float) ($po['total_amount'] ?? 0), 2) ?></div>
                                                <?php $items = is_array($po['items'] ?? null) ? $po['items'] : []; ?>
                                                <div style="margin-top: 4px;"><strong>Items (<?= esc((string) count($items)) ?>):</strong></div>
                                                <?php if ($items !== []): ?>
                                                    <ul style="margin: 6px 0 0 16px; padding: 0; color: var(--v2-text-muted);">
                                                        <?php foreach (array_slice($items, 0, 4) as $item): ?>
                                                            <li>
                                                                <?= esc((string) ($item['item_name'] ?? '')) ?>
                                                                (<?= esc((string) ((int) round((float) ($item['ordered_qty'] ?? 0)))) ?>
                                                                <?= esc((string) ($item['unit'] ?? 'unit')) ?>)
                                                            </li>
                                                        <?php endforeach ?>
                                                    </ul>
                                                    <?php if (count($items) > 4): ?>
                                                        <div style="margin-top: 4px; font-style: italic; color: #94a3b8;">+<?= esc((string) (count($items) - 4)) ?> more items</div>
                                                    <?php endif ?>
                                                <?php endif ?>
                                            </div>
                                        </details>
                                    <?php endif ?>
                                </td>
                                <td style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--v2-text-muted);">PO-<?= esc((string) $poRequest['purchase_order_id']) ?></td>
                                <td style="font-size: 0.85rem;"><?= esc((string) $poRequest['request_date']) ?></td>
                                
                                <td>
                                    <?php if (($poRequest['status'] ?? '') === 'converted_to_receiving'): ?>
                                        <span class="status-badge-special">Converted to Receiving</span>
                                    <?php else: ?>
                                        <?= view('components/shared/table_status_badge', ['status' => $poRequest['status'] ?? 'unknown']) ?>
                                    <?php endif; ?>
                                </td>
                                
                                <td style="font-size: 0.85rem; color: var(--v2-text-muted); font-weight: 500;">
                                    <?= esc((string) ($poRequest['approved_by'] ?? $poRequest['rejected_by'] ?? '-')) ?>
                                </td>
                                
                                <td>
                                    <?php if (($poRequest['status'] ?? '') === 'pending' && $isAdmin): ?>
                                        <div class="action-forms-container">
                                            <form method="post" action="<?= site_url('procurement/po-requests/' . $poRequest['id'] . '/approve') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table btn-approve">Approve</button>
                                            </form>
                                            <form method="post" action="<?= site_url('procurement/po-requests/' . $poRequest['id'] . '/reject') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <div class="approval-input-group">
                                                    <input type="text" name="reason" class="reject-input" placeholder="Reason..." required>
                                                    <button type="submit" class="btn-table btn-reject">Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="muted" style="font-size: 0.85rem; font-weight: 600; padding: 4px 10px;">&mdash;</span>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 600; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalRequests) ?></span>)
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
        const tbody = document.querySelector('#po-req-table tbody');
        
        if (tbody && tbody.querySelector('.po-req-row')) {
            const allRows = Array.from(tbody.querySelectorAll('.po-req-row'));
            let currentRows = [...allRows]; 

            const pagerContainer = document.getElementById('client-pager');
            const pageIndicator = document.getElementById('page-indicator');
            const totalIndicator = document.getElementById('total-indicator');
            const statusHeader = document.getElementById('status-header');
            
            const kpiVisible = document.getElementById('kpi-visible');
            const kpiPending = document.getElementById('kpi-pending');
            const kpiApproved = document.getElementById('kpi-approved');
            const kpiRejected = document.getElementById('kpi-rejected');

            const searchInput = document.getElementById('instant-search-input');
            const clearBtn = document.getElementById('btn-clear-search');

            const statusCycle = ['All', 'pending', 'approved', 'rejected', 'converted_to_receiving'];
            let cycleIndex = 0;
            let currentStatusFilter = 'All';

            function toTitleCase(str) {
                return str.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            }

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();

                currentRows = allRows.filter(row => {
                    const id = row.children[0].innerText.toLowerCase();
                    const poReqNum = row.children[1].innerText.toLowerCase();
                    const poId = row.children[2].innerText.toLowerCase();
                    const statusVal = row.getAttribute('data-status');

                    const matchesText = query === '' || id.includes(query) || poReqNum.includes(query) || poId.includes(query);
                    const matchesStatus = currentStatusFilter === 'All' || statusVal === currentStatusFilter;

                    return matchesText && matchesStatus;
                });

                currentRows.forEach(row => tbody.appendChild(row));
                updateKPIs();
                showPage(1);
            }

            function updateKPIs() {
                let countPending = 0, countApproved = 0, countRejected = 0;
                currentRows.forEach(row => {
                    const stat = row.getAttribute('data-status');
                    if (stat === 'pending') countPending++;
                    if (stat === 'approved') countApproved++;
                    if (stat === 'rejected') countRejected++;
                });
                
                kpiVisible.innerText = currentRows.length;
                kpiPending.innerText = countPending;
                kpiApproved.innerText = countApproved;
                kpiRejected.innerText = countRejected;
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
                        statusHeader.innerHTML = `Status <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>`;
                    } else {
                        statusHeader.innerHTML = `Status <br><span class="filter-active-text">${toTitleCase(currentStatusFilter)}</span>`;
                    }
                    applyFilters();
                });
            }

            document.querySelectorAll('#po-req-table th.sortable').forEach(th => {
                if (parseInt(th.getAttribute('data-col')) === 4) return; 

                th.addEventListener('click', () => {
                    const colIndex = parseInt(th.getAttribute('data-col'));
                    const isNumericCol = th.classList.contains('numeric');
                    const isDateCol = th.classList.contains('date');
                    const isAsc = th.classList.contains('asc');
                    const direction = isAsc ? -1 : 1; 
                    
                    document.querySelectorAll('#po-req-table th.sortable').forEach(header => {
                        if (parseInt(header.getAttribute('data-col')) !== 4) {
                            header.classList.remove('asc', 'desc');
                        }
                    });
                    
                    th.classList.add(isAsc ? 'desc' : 'asc');
                    
                    currentRows.sort((a, b) => {
                        // Strip out '#' and 'PO-' text for clean numeric sorting
                        let aText = a.children[colIndex].innerText.trim().replace(/PO-|#/g, '');
                        let bText = b.children[colIndex].innerText.trim().replace(/PO-|#/g, '');
                        
                        if (isNumericCol) return (parseFloat(aText) - parseFloat(bText)) * direction;
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
