<?php

declare(strict_types=1);

$title = 'Purchase Orders - InventoryV2';
$pageTitle = 'Procurement - Purchase Orders';
$pageSubtitle = 'Issue draft purchase orders and convert issued orders to PO requests.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Purchase Orders'],
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

    /* Specific Icon Colors for POs */
    .icon-total { background: #f1f5f9; color: #475569; }        
    .icon-draft { background: #fffbeb; color: #d97706; } 
    .icon-issued { background: #e0f2fe; color: #0284c7; }   
    .icon-received { background: #ecfccb; color: #16a34a; }   

    .kpi-details { display: flex; flex-direction: column; flex: 1; justify-content: center; min-width: 0; }
    
    .kpi-value { font-size: 1.15rem; font-weight: 800; color: var(--v2-title); line-height: 1.2; margin: 0; }
    .kpi-label { font-size: 0.75rem; font-weight: 500; color: var(--v2-text-muted); margin: 0; margin-top: 2px; }

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
    
    .search-wrap { position: relative; width: 260px; }
    .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 14px; height: 14px; }
    .search-input, .filter-select { 
        padding: 6px 12px; 
        font-size: 0.85rem; 
        border: 1px solid var(--v2-border); 
        border-radius: 6px; 
        outline: none; 
        color: var(--v2-text-main);
        background: #ffffff;
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
        background: #ffffff !important; 
        padding: 14px 16px; /* Matched to PR page */
        font-size: 0.75rem; /* Matched to PR page */
        text-transform: uppercase; 
        font-weight: 800; 
        color: var(--v2-title); /* BOLD DEEP BLUE matched to PR page */
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

    /* --- V2 ACTION BUTTONS & BADGES --- */
    .action-forms-container { display: flex; gap: 6px; align-items: center; justify-content: flex-end; flex-wrap: nowrap; }

    .btn-link-view { font-size: 0.75rem; color: var(--v2-label); text-decoration: none; font-weight: 800; padding: 4px 8px; border-radius: 4px; transition: background 0.2s ease; cursor: pointer; border: none; background: transparent; }
    .btn-link-view:hover { color: var(--v2-title); background: rgba(178, 224, 235, 0.3); }
    
    .btn-table { padding: 4px 10px !important; font-size: 0.7rem !important; font-weight: 800 !important; border-radius: 4px !important; text-transform: uppercase !important; transition: all 0.2s ease; border: none !important; cursor: pointer; display: inline-flex; text-decoration: none !important; align-items: center; justify-content: center; white-space: nowrap; }
    
    .btn-action-primary { background: var(--v2-label) !important; color: white !important; }
    .btn-action-primary:hover { background: var(--v2-active-bg) !important; transform: translateY(-1px); }

    /* Updated to Solid Violet for PO Request consistency */
    .btn-action-indigo { background: #7C3AED !important; color: white !important; border: 1px solid #7C3AED !important; }
    .btn-action-indigo:hover { background: #6D28D9 !important; border-color: #6D28D9 !important; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(109, 40, 217, 0.2); }

    /* Specific Action Column Badges */
    .action-badge-neutral { background: #f8fafc; color: #64748b; border: 1px dashed #cbd5e1; padding: 3px 10px; font-size: 0.7rem; font-weight: 800; border-radius: 4px; text-transform: uppercase; cursor: default; white-space: nowrap; }
    .action-badge-warning { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; padding: 3px 10px; font-size: 0.7rem; font-weight: 800; border-radius: 4px; text-transform: uppercase; cursor: default; white-space: nowrap; }
    .action-badge-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 3px 10px; font-size: 0.7rem; font-weight: 800; border-radius: 4px; text-transform: uppercase; cursor: default; white-space: nowrap; }

    /* Main Status Column Badges */
    .status-badge { padding: 3px 10px; font-size: 0.7rem; font-weight: 800; border-radius: 9999px; text-transform: uppercase; white-space: nowrap; display: inline-block; letter-spacing: 0.05em; }
    .status-draft { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
    .status-issued { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
    .status-partial { background: #ccfbf1; color: #0f766e; border: 1px solid #99f6e4; }
    .status-full { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-cancelled { background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $purchaseOrderExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') . '?' . $purchaseOrderExportQuery ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<?php if ($isAdmin): ?>
<a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Pending Approvals</a>
<?php endif ?>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">PO Requests</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $purchaseOrders ?? [];
$totalOrders = count($rows);
$draftOrders = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'draft'));
$issuedOrders = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'issued'));
$receivedOrders = count(array_filter($rows, static fn (array $row): bool => in_array((string) ($row['status'] ?? ''), ['partially_received', 'fully_received'], true)));
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Purchase Orders</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card">
                <div class="kpi-icon-box icon-total"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-visible"><?= esc((string) $totalOrders) ?></span>
                    <span class="kpi-label">Visible POs</span>
                </div>
            </article>
            <article class="kpi-card">
                <div class="kpi-icon-box icon-draft"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-draft" style="color: #b45309;"><?= esc((string) $draftOrders) ?></span>
                    <span class="kpi-label">Draft Status</span>
                </div>
            </article>
            <article class="kpi-card">
                <div class="kpi-icon-box icon-issued"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-issued" style="color: var(--v2-label);"><?= esc((string) $issuedOrders) ?></span>
                    <span class="kpi-label">Issued Orders</span>
                </div>
            </article>
            <article class="kpi-card">
                <div class="kpi-icon-box icon-received"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-received" style="color: #15803d;"><?= esc((string) $receivedOrders) ?></span>
                    <span class="kpi-label">Received</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Purchase Order Queue</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Issue drafts and manage PO requests.</p>
            </div>
            
            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Search PO number or supplier..." autocomplete="off">
                </div>
                
                <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('procurement/purchase-orders') ?>" style="margin: 0; display: flex; gap: 8px;">
                    <?php $poStatusLabels = ['draft' => 'Draft', 'issued' => 'Issued', 'partially_received' => 'Partially Received', 'fully_received' => 'Fully Received', 'cancelled' => 'Cancelled']; ?>
                    <select id="status" name="status" class="filter-select" aria-label="Filter purchase orders by status">
                        <option value="">All Statuses</option>
                        <?php foreach ($poStatusLabels as $option => $label): ?>
                            <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach ?>
                    </select>
                    <button type="button" class="btn btn-outline" id="btn-clear-search" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px;">Clear</button>
                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px; background: var(--v2-label); border: none;">Filter Server</button>
                </form>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="po-table" style="table-layout: fixed; width: 100%; min-width: 1050px;">
                <colgroup>
                    <col style="width: 60px;">  
                    <col style="width: 150px;"> 
                    <col style="width: 80px;">  
                    <col style="width: 25%;">   
                    <col style="width: 120px;"> 
                    <col style="width: 150px;"> 
                    <col style="width: 120px;"> 
                    <col style="width: 220px;"> 
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">PO Number</th>
                        <th class="sortable numeric" data-col="2">PR ID</th>
                        <th class="sortable" data-col="3">Supplier</th>
                        <th class="sortable date" data-col="4">Order Date</th>
                        <th class="sortable" data-col="5" id="status-header">
                            Status <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>
                        </th>
                        <th class="sortable numeric" data-col="6" style="text-align: right;">Total</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($purchaseOrders ?? []) === []): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No purchase orders found.</strong><br>
                                <span style="font-size: 0.8rem;">Adjust your filters to see more results.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($purchaseOrders as $order): ?>
                            <tr class="po-row" style="display: none;" data-status="<?= esc(strtolower((string) ($order['status'] ?? ''))) ?>">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) $order['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 700; color: var(--v2-label);"><?= esc((string) $order['po_number']) ?></td>
                                <td style="font-family: var(--font-mono); font-size: 0.8rem; color: var(--v2-text-muted);">PR-<?= esc((string) $order['purchase_request_id']) ?></td>
                                <td style="font-weight: 700; color: var(--v2-text-main); word-break: break-word;"><?= esc((string) ($order['supplier_name'] ?? '-')) ?></td>
                                <td style="font-size: 0.8rem;"><?= esc((string) $order['order_date']) ?></td>
                                
                                <td>
                                    <?php 
                                        $rawStatus = strtolower((string) ($order['status'] ?? 'draft'));
                                        $sClass = 'status-draft';
                                        if ($rawStatus === 'issued') $sClass = 'status-issued';
                                        if ($rawStatus === 'partially_received') $sClass = 'status-partial';
                                        if ($rawStatus === 'fully_received') $sClass = 'status-full';
                                        if ($rawStatus === 'cancelled') $sClass = 'status-cancelled';
                                        $cleanStatus = ucwords(str_replace('_', ' ', $rawStatus));
                                    ?>
                                    <span class="status-badge <?= $sClass ?>"><?= esc($cleanStatus) ?></span>
                                </td>
                                
                                <td style="text-align: right; font-family: var(--font-mono); font-weight: 700; color: var(--v2-title);">
                                    <?= esc(number_format((float) ($order['total_amount'] ?? 0), 2)) ?>
                                </td>
                                
                                <td>
                                    <div class="action-forms-container">
                                        <?php if (($order['status'] ?? '') === 'draft' && $isAdmin): ?>
                                            <form method="post" action="<?= site_url('procurement/purchase-orders/' . $order['id'] . '/issue') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table btn-action-primary">Issue</button>
                                            </form>
                                        <?php endif ?>

                                        <?php if (($order['status'] ?? '') === 'issued' && $isAdmin && ! (bool) ($order['has_open_po_request'] ?? false)): ?>
                                            <form method="post" action="<?= site_url('procurement/po-requests/from-po/' . $order['id']) ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table btn-action-indigo">PO Req &rarr;</button>
                                            </form>
                                        <?php endif ?>

                                        <?php if (($order['status'] ?? '') === 'issued' && (bool) ($order['has_open_po_request'] ?? false)): ?>
                                            <?php 
                                                $reqStatus = strtolower((string) ($order['po_request_status'] ?? 'pending'));
                                                $badgeClass = 'action-badge-warning'; 
                                                if ($reqStatus === 'approved') {
                                                    $badgeClass = 'action-badge-success'; 
                                                }
                                                $cleanReqStatus = strtoupper(str_replace('_', ' ', $reqStatus));
                                            ?>
                                            <span class="<?= $badgeClass ?>">PO REQ: <?= esc($cleanReqStatus) ?></span>
                                        <?php endif ?>
                                        
                                        <?php if (! $isAdmin && in_array((string) ($order['status'] ?? ''), ['draft', 'issued'], true)): ?>
                                            <span class="action-badge-neutral">Read-only</span>
                                        <?php endif ?>

                                        <?php if (!in_array(($order['status'] ?? ''), ['draft', 'issued'], true)): ?>
                                            <span class="muted" style="font-size: 0.85rem; font-weight: 600; padding: 4px 10px;">&mdash;</span>
                                        <?php endif ?>

                                        <a class="btn-link-view" href="<?= site_url('procurement/purchase-orders/' . $order['id']) ?>">View</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 600; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalOrders) ?></span>)
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
        const tbody = document.querySelector('#po-table tbody');
        
        if (tbody && tbody.querySelector('.po-row')) {
            const allRows = Array.from(tbody.querySelectorAll('.po-row'));
            let currentRows = [...allRows]; 

            const pagerContainer = document.getElementById('client-pager');
            const pageIndicator = document.getElementById('page-indicator');
            const totalIndicator = document.getElementById('total-indicator');
            const statusHeader = document.getElementById('status-header');
            
            const kpiVisible = document.getElementById('kpi-visible');
            const kpiDraft = document.getElementById('kpi-draft');
            const kpiIssued = document.getElementById('kpi-issued');
            const kpiReceived = document.getElementById('kpi-received');

            const searchInput = document.getElementById('instant-search-input');
            const clearBtn = document.getElementById('btn-clear-search');

            const statusCycle = ['All', 'draft', 'issued', 'partially_received', 'fully_received', 'cancelled'];
            let cycleIndex = 0;
            let currentStatusFilter = 'All';

            function toTitleCase(str) { return str.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '); }

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();
                currentRows = allRows.filter(row => {
                    const poNum = row.children[1].innerText.toLowerCase();
                    const prId = row.children[2].innerText.toLowerCase();
                    const supplier = row.children[3].innerText.toLowerCase();
                    const statusVal = row.getAttribute('data-status');
                    
                    const matchesText = query === '' || poNum.includes(query) || prId.includes(query) || supplier.includes(query);
                    const matchesStatus = currentStatusFilter === 'All' || statusVal === currentStatusFilter;
                    
                    return matchesText && matchesStatus;
                });
                
                currentRows.forEach(row => tbody.appendChild(row));
                updateKPIs(); 
                showPage(1);
            }

            function updateKPIs() {
                let countDraft = 0, countIssued = 0, countReceived = 0;
                currentRows.forEach(row => {
                    const stat = row.getAttribute('data-status');
                    if (stat === 'draft') countDraft++;
                    if (stat === 'issued') countIssued++;
                    if (stat === 'partially_received' || stat === 'fully_received') countReceived++;
                });
                kpiVisible.innerText = currentRows.length; 
                kpiDraft.innerText = countDraft; 
                kpiIssued.innerText = countIssued; 
                kpiReceived.innerText = countReceived; 
                totalIndicator.innerText = currentRows.length;
            }

            if(searchInput) searchInput.addEventListener('input', applyFilters);
            if(clearBtn) { clearBtn.addEventListener('click', () => { searchInput.value = ''; applyFilters(); }); }

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

            document.querySelectorAll('#po-table th.sortable').forEach(th => {
                if (parseInt(th.getAttribute('data-col')) === 5) return; // Skip Status col

                th.addEventListener('click', () => {
                    const colIndex = parseInt(th.getAttribute('data-col'));
                    const isNumericCol = th.classList.contains('numeric');
                    const isDateCol = th.classList.contains('date');
                    const isAsc = th.classList.contains('asc');
                    const direction = isAsc ? -1 : 1; 
                    
                    document.querySelectorAll('#po-table th.sortable').forEach(header => {
                        if (parseInt(header.getAttribute('data-col')) !== 5) {
                            header.classList.remove('asc', 'desc');
                        }
                    });
                    
                    th.classList.add(isAsc ? 'desc' : 'asc');
                    
                    currentRows.sort((a, b) => {
                        let aText = a.children[colIndex].innerText.trim().replace(/PR-|#|,/g, '');
                        let bText = b.children[colIndex].innerText.trim().replace(/PR-|#|,/g, '');
                        
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
                currentRows.forEach((row, index) => { if (index >= startPoint && index < endPoint) row.style.display = ''; });
                
                const actualEnd = Math.min(endPoint, totalRows);
                if (pageIndicator) pageIndicator.innerText = totalRows === 0 ? '0' : `${startPoint + 1} - ${actualEnd}`;

                if (pagerContainer) {
                    pagerContainer.innerHTML = '';
                    if (totalPages > 1) {
                        let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;
                        let startPage = Math.max(1, currentPage - 2);
                        let endPage = Math.min(totalPages, startPage + 4);
                        if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);
                        if (startPage > 1) { html += `<li><a href="#" data-page="1">1</a></li>`; if (startPage > 2) html += `<li><span class="ellipsis">...</span></li>`; }
                        for (let i = startPage; i <= endPage; i++) { html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`; }
                        if (endPage < totalPages) { if (endPage < totalPages - 1) html += `<li><span class="ellipsis">...</span></li>`; html += `<li><a href="#" data-page="${totalPages}">${totalPages}</a></li>`; }
                        html += `<li class="${currentPage === totalPages ? 'disabled' : ''}"><a href="#" data-page="${currentPage + 1}">Next &raquo;</a></li>`;
                        pagerContainer.innerHTML = html;
                    }
                }
            }

            if (pagerContainer) {
                pagerContainer.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (!link) return; e.preventDefault();
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