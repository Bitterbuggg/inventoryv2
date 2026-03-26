<?php

declare(strict_types=1);

$title = 'Purchase Requests - InventoryV2';
$pageTitle = 'Procurement - Purchase Requests';
$pageSubtitle = 'Create, submit, and track purchase requests by workflow status.';
$crumbs = [
    ['label' => 'Purchase Requests'],
];

$user = function_exists('auth') ? auth()->user() : null;
$isAdmin = $user !== null && method_exists($user, 'inGroup') && $user->inGroup('admin');
$isItStaff = $user !== null && method_exists($user, 'inGroup') && $user->inGroup('it_staff');
$canOps = $isAdmin || $isItStaff;
$canCreatePo = $isAdmin
    || ($user !== null && method_exists($user, 'can') && $user->can('procurement.po.create'));
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- V2 DESIGN SYSTEM VARIABLES --- */
    :root {
        --v2-border: #b2e0eb; /* Soft cyan border */
        --v2-title: #00476b;  /* Deep navy/teal for headers */
        --v2-label: #00668c;  /* Bright teal for small labels and icons */
        --v2-active-bg: #00638a; /* Solid dark blue for active hover state */
        --v2-text-main: #1e3a8a; /* TRUE Dark Blue so it doesn't look black */
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

    .icon-total { background: #f1f5f9; color: #475569; }        
    .icon-draft { background: #f5f3ff; color: #8b5cf6; } 
    .icon-submitted { background: #fffbeb; color: #d97706; }   
    .icon-approved { background: #ecfccb; color: #16a34a; }   

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
        padding: 16px 20px; 
        border-bottom: 1px solid var(--v2-border); 
        background: #ffffff; 
        flex-shrink: 0;
        flex-wrap: wrap;
    }
    
    .table-toolbar h3 { margin: 0; font-size: 1.1rem; color: var(--v2-title); font-weight: 800; }
    
    .toolbar-controls { display: flex; gap: 8px; align-items: center; flex: 1; justify-content: flex-end; }
    
    .search-wrap { position: relative; width: 280px; }
    .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 14px; height: 14px; }
    .search-input, .filter-select { 
        padding: 8px 12px; 
        font-size: 0.85rem; 
        border: 1px solid var(--v2-border); 
        border-radius: 6px; 
        outline: none; 
        color: var(--v2-text-main);
        background: #ffffff;
        transition: all 0.2s;
    }
    .search-input { width: 100%; padding-left: 32px; }
    .search-input:focus, .filter-select:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }

    /* Scrollable Table Area */
    .table-scroll-container {
        flex: 1;
        overflow-y: auto; 
        background: #ffffff;
    }

    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    
    .modern-table th { 
        position: sticky; top: 0; z-index: 20;
        background-color: #ffffff !important; /* Pure white header */
        padding: 14px 16px; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        font-weight: 800; 
        color: var(--v2-title); 
        border-bottom: 2px solid var(--v2-border); /* 2px distinct separation line */
        text-align: left; 
        letter-spacing: 0.05em; 
        vertical-align: middle; 
    }
    .modern-table td { padding: 12px 16px; font-size: 0.85rem; color: var(--v2-text-main); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .modern-table tr:hover td { background: #f8fafc; }

    /* --- SORTABLE HEADERS --- */
    th.sortable { cursor: pointer; padding-right: 18px !important; user-select: none; transition: background 0.2s ease, color 0.2s ease; }
    th.sortable:hover { background-color: #f1f5f9 !important; color: var(--v2-title) !important; } /* Subtle hover so it stays mostly white */
    th.sortable::after { content: '↕'; position: absolute; right: 6px; top: 50%; transform: translateY(-50%); font-size: 0.75rem; opacity: 0.3; color: var(--v2-title); }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--v2-label); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--v2-label); font-weight: bold; }

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
    .action-row { display: flex; gap: 6px; align-items: center; justify-content: flex-end; flex-wrap: nowrap; }

    .btn-link-view { font-size: 0.75rem; color: var(--v2-label); text-decoration: none; font-weight: 800; padding: 4px 8px; border-radius: 4px; transition: background 0.2s ease; }
    .btn-link-view:hover { color: var(--v2-title); background: rgba(178, 224, 235, 0.3); }
    
    .btn-table { padding: 4px 10px; font-size: 0.7rem; font-weight: 800; border-radius: 4px; text-transform: uppercase; transition: all 0.2s ease; border: none; cursor: pointer; display: inline-flex; text-decoration: none; align-items: center; justify-content: center; white-space: nowrap; }
    
    .btn-submit-blue { background: var(--v2-label); color: white; }
    .btn-submit-blue:hover { background: var(--v2-active-bg); }
    .btn-edit-outline { background: white; border: 1px solid var(--v2-label); color: var(--v2-label); }
    .btn-edit-outline:hover { background: rgba(178, 224, 235, 0.2); }
    .btn-cancel-red { background: #ffffff; color: #ef4444; border: 1px solid #fca5a5; }
    .btn-cancel-red:hover { background: #fef2f2; color: #dc2626; border-color: #f87171; }
    
    /* V2 THEMED CREATE PO BUTTON */
    .btn-create-po { background: #7C3AED !important; color: #ffffff !important; border: 1px solid #7C3AED !important; }
    .btn-create-po:hover { background: #6D28D9 !important; border-color: #6D28D9 !important; box-shadow: 0 2px 4px rgba(109, 40, 217, 0.2); }

    /* V2 THEMED PO NAVIGATION BUTTON */
    .po-nav-btn { background: #f0f9ff !important; color: var(--v2-label) !important; border: 1px solid var(--v2-border) !important; }
    .po-nav-btn:hover { background: var(--v2-label) !important; color: #ffffff !important; border-color: var(--v2-label) !important; }

    .status-badge-special { background: #f0f9ff; color: var(--v2-label); border: 1px solid var(--v2-border); padding: 3px 10px; font-size: 0.7rem; font-weight: 800; border-radius: 9999px; white-space: nowrap; text-transform: uppercase; }
    .action-badge-waiting { background: #f8fafc; color: #64748b; border: 1px dashed #cbd5e1; padding: 3px 10px; font-size: 0.7rem; font-weight: 800; border-radius: 4px; text-transform: uppercase; cursor: not-allowed; }
    .action-badge-done { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 3px 10px; font-size: 0.7rem; font-weight: 800; border-radius: 4px; text-transform: uppercase; cursor: default; }

    /* --- MODAL STYLES --- */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.5); display: none; align-items: center; justify-content: center; z-index: 9999; backdrop-filter: blur(2px); }
    .modal-overlay.active { display: flex; }
    .modal-content { background: #ffffff; padding: 24px; border-radius: 12px; width: 100%; max-width: 420px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid var(--v2-border); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--v2-border); padding-bottom: 12px; }
    .modal-header h3 { margin: 0; font-size: 1.1rem; color: var(--v2-title); font-weight: 800; }
    .btn-close-modal { background: transparent; border: none; font-size: 1.5rem; cursor: pointer; color: var(--v2-text-muted); margin:0; padding:0; line-height: 1; transition: color 0.2s; }
    .btn-close-modal:hover { color: #ef4444; }
    .modal-body .field { margin-bottom: 16px; }
    .modal-body label { display: block; font-weight: 700; font-size: 0.8rem; color: var(--v2-text-main); margin-bottom: 6px; }
    .modal-body select { width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; }
    .modal-body select:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }
    .modal-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--v2-border); }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-primary" href="<?= site_url('procurement/purchase-requests/create') ?>" title="Create a new draft purchase request" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem; box-shadow: 0 2px 4px rgba(2, 132, 199, 0.2);">Create Request</a>
<?php $purchaseRequestExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') . '?' . $purchaseRequestExportQuery ?>" title="Download the current list of purchase requests as a CSV file" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<?php if ($canOps): ?>
    <a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Pending Approvals</a>
    <a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Purchase Orders</a>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $requests ?? [];
$totalRequests = count($rows); 
$draftRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'draft'));
$submittedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'submitted'));
$approvedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'approved'));
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Purchase Requests</h2>
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
            <article class="kpi-card kpi-accent-violet">
                <div class="kpi-icon-box icon-draft"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-draft"><?= esc((string) $draftRequests) ?></span>
                    <span class="kpi-label">Draft Status</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-amber">
                <div class="kpi-icon-box icon-submitted"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-submitted" style="color: #b45309;"><?= esc((string) $submittedRequests) ?></span>
                    <span class="kpi-label">Submitted</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-approved"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-approved" style="color: #15803d;"><?= esc((string) $approvedRequests) ?></span>
                    <span class="kpi-label">Approved</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Request Queue</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Process and track procurement requests.</p>
            </div>
            
            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Search PR Number or Requestor..." autocomplete="off">
                </div>
                
                <?php
                $statusLabels = [
                    'draft'           => 'Draft',
                    'submitted'       => 'Submitted',
                    'approved'        => 'Approved',
                    'rejected'        => 'Rejected',
                    'cancelled'       => 'Cancelled',
                    'converted_to_po' => 'Converted to PO',
                ];
                ?>
                <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('procurement/purchase-requests') ?>" style="margin: 0; display: flex; gap: 8px;">
                    <select id="status" name="status" class="filter-select" aria-label="Filter by status">
                        <option value="">All Statuses</option>
                        <?php foreach ($statusLabels as $val => $lbl): ?>
                            <option value="<?= esc($val) ?>" <?= (($status ?? '') === $val) ? 'selected' : '' ?>><?= esc($lbl) ?></option>
                        <?php endforeach ?>
                    </select>
                    <button type="button" class="btn btn-outline" id="btn-clear-search" style="padding: 8px 16px; font-size: 0.8rem; font-weight: 800; border-radius: 6px; border: 1px solid var(--v2-border); color: var(--v2-title); background: #ffffff;">Clear</button>
                    <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.8rem; font-weight: 800; border-radius: 6px; background: var(--v2-label); color: #ffffff; border: none;">Filter Server</button>
                </form>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="pr-table" style="table-layout: fixed; width: 100%;">
                <colgroup>
                    <col style="width: 5%;">  
                    <col style="width: 14%;">   
                    <col style="width: 14%;">   
                    <col style="width: 10%;">   
                    <col style="width: 11%;">   
                    <col style="width: 16%;">    
                    <col style="width: 30%;"> 
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">PR Number</th>
                        <th class="sortable" data-col="2">Requested By</th>
                        <th class="sortable date" data-col="3">Date</th>
                        <th class="sortable" data-col="4" id="status-header" title="Click to cycle status filters!">
                            Status <span style="font-size: 0.65rem; font-weight: normal; color: var(--v2-label);">(All)</span>
                        </th>
                        <th>Remarks</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($rows ?? []) === []): ?>
                        <tr class="no-records-row">
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No purchase requests found.</strong><br>
                                <span style="font-size: 0.8rem;">Adjust your filters or create a new request.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $request): ?>
                            <tr class="pr-row" style="display: none;" data-status="<?= esc(strtolower((string) ($request['status'] ?? ''))) ?>">
                                <td style="font-weight: 800; color: #94a3b8;"><?= esc((string) $request['id']) ?></td>
                                <td><a href="<?= site_url('procurement/purchase-requests/' . $request['id']) ?>" style="font-family: var(--font-mono); font-weight: 800; color: var(--v2-label); text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'"><?= esc((string)$request['pr_number']) ?></a></td>
                                <td style="font-weight: 800; color: var(--v2-text-main);"><?= esc((string) $request['requested_by']) ?></td>
                                <td style="white-space: nowrap; font-size: 0.8rem; font-weight: 600; color: var(--v2-text-main);"><?= esc((string) $request['request_date']) ?></td>
                                
                                <td>
                                    <?php if (($request['status'] ?? '') === 'converted_to_po'): ?>
                                        <span class="status-badge-special">Converted to PO</span>
                                    <?php else: ?>
                                        <?= view('components/shared/table_status_badge', ['status' => $request['status'] ?? 'unknown']) ?>
                                    <?php endif ?>
                                </td>
                                
                                <td style="color: var(--v2-text-muted); font-size: 0.8rem; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= esc((string) ($request['remarks'] ?? '')) ?>"><?= esc((string) ($request['remarks'] ?? '')) ?></td>
                                
                                <td>
                                    <div class="action-row">
                                        <?php if (($request['status'] ?? '') === 'draft'): ?>
                                            <form method="post" action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/submit') ?>" style="margin:0">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table btn-submit-blue">Submit</button>
                                            </form>
                                            <a class="btn-table btn-edit-outline" href="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/edit') ?>">Edit</a>
                                            <form method="post"
                                                  data-confirm="Cancel this draft request? This cannot be undone."
                                                  data-confirm-title="Cancel Draft"
                                                  action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/cancel') ?>" style="margin:0">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table btn-cancel-red">Cancel</button>
                                            </form>

                                        <?php elseif (($request['status'] ?? '') === 'submitted'): ?>
                                            <form method="post"
                                                  data-confirm="Cancel this submitted request? It will need to be re-submitted for approval."
                                                  data-confirm-title="Cancel Submitted Request"
                                                  action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/cancel') ?>" style="margin:0">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table btn-cancel-red">Cancel</button>
                                            </form>

                                        <?php elseif (($request['status'] ?? '') === 'approved' && $canCreatePo): ?>
                                            <button type="button" class="btn-create-po btn-table" 
                                                    onclick="openPoModal(<?= $request['id'] ?>, '<?= esc((string)$request['pr_number']) ?>')">
                                                Create PO
                                            </button>

                                        <?php elseif (($request['status'] ?? '') === 'approved'): ?>
                                            <span class="action-badge-waiting">⏳ Awaiting PO</span>

                                        <?php elseif (($request['status'] ?? '') === 'converted_to_po'): ?>
                                            <?php if ($canOps): ?>
                                                <a href="<?= site_url('procurement/purchase-orders') ?>" class="btn-table po-nav-btn">PO &rarr;</a>
                                            <?php else: ?>
                                                <span class="action-badge-done">✓ Converted</span>
                                            <?php endif ?>
                                        <?php else: ?>
                                            <span class="muted" style="font-size: 0.75rem;">&mdash;</span>
                                        <?php endif ?>
                                        
                                        <a class="btn-link-view view-action" href="<?= site_url('procurement/purchase-requests/' . $request['id']) ?>">View</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalRequests) ?></span>)
            </p>
            <nav aria-label="Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </div>
</div>

<div class="modal-overlay" id="poModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Convert to Purchase Order</h3>
            <button type="button" class="btn-close-modal" onclick="closePoModal()">&times;</button>
        </div>
        <form id="poForm" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <p id="modal-pr-text" style="margin-bottom:16px; font-size:0.85rem; color: var(--v2-text-muted);"></p>
                <div class="field">
                    <label for="supplier_name">Please select a Supplier for this order: <span style="color: #ef4444;">*</span></label>
                    <select id="supplier_name" name="supplier_name" required>
                        <option value="">-- Select Supplier --</option>
                        <option value="ACME Pharma Supply">ACME Pharma Supply</option>
                        <option value="Global Meds">Global Meds</option>
                        <option value="Generic Pharm">Generic Pharm</option>
                        <option value="Hospital Logistics">Hospital Logistics</option>
                        <option value="LifeCare Systems">LifeCare Systems</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" style="padding: 8px 16px; border-radius: 6px; font-weight: 800; font-size: 0.85rem;" onclick="closePoModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding: 8px 16px; border-radius: 6px; font-weight: 800; font-size: 0.85rem; background: #7C3AED; color: #ffffff; border: none;">Confirm & Create</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPoModal(prId, prNumber) {
        const modal = document.getElementById('poModal');
        const form = document.getElementById('poForm');
        const text = document.getElementById('modal-pr-text');
        
        form.action = "<?= site_url('procurement/purchase-orders/from-pr/') ?>" + prId;
        text.innerText = "Convert request " + prNumber + " to a finalized Purchase Order.";
        
        modal.classList.add('active');
    }

    function closePoModal() {
        document.getElementById('poModal').classList.remove('active');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 15; 
        const tbody = document.querySelector('#pr-table tbody');
        
        if (tbody && tbody.querySelector('.pr-row')) {
            const allRows = Array.from(tbody.querySelectorAll('.pr-row'));
            let currentRows = [...allRows]; 

            const pagerContainer = document.getElementById('client-pager');
            const pageIndicator = document.getElementById('page-indicator');
            const totalIndicator = document.getElementById('total-indicator');
            const statusHeader = document.getElementById('status-header');
            
            const kpiVisible = document.getElementById('kpi-visible');
            const kpiDraft = document.getElementById('kpi-draft');
            const kpiSubmitted = document.getElementById('kpi-submitted');
            const kpiApproved = document.getElementById('kpi-approved');

            const searchInput = document.getElementById('instant-search-input');
            const clearBtn = document.getElementById('btn-clear-search');

            const statusCycle = ['All', 'draft', 'submitted', 'approved', 'rejected', 'cancelled', 'converted_to_po'];
            let cycleIndex = 0;
            let currentStatusFilter = 'All';

            function toTitleCase(str) {
                if (str === 'converted_to_po') return 'Converted to PO';
                return str.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            }

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();

                currentRows = allRows.filter(row => {
                    const prNum = row.children[1].innerText.toLowerCase();
                    const reqBy = row.children[2].innerText.toLowerCase();
                    const statusVal = row.getAttribute('data-status');

                    const matchesText = query === '' || prNum.includes(query) || reqBy.includes(query);
                    const matchesStatus = currentStatusFilter === 'All' || statusVal === currentStatusFilter;

                    return matchesText && matchesStatus;
                });

                currentRows.forEach(row => tbody.appendChild(row));
                updateKPIs();
                showPage(1);
            }

            function updateKPIs() {
                let countDraft = 0, countSubmitted = 0, countApproved = 0;
                
                currentRows.forEach(row => {
                    const stat = row.getAttribute('data-status');
                    if (stat === 'draft') countDraft++;
                    if (stat === 'submitted') countSubmitted++;
                    if (stat === 'approved') countApproved++;
                });
                
                kpiVisible.innerText = currentRows.length;
                kpiDraft.innerText = countDraft;
                kpiSubmitted.innerText = countSubmitted;
                kpiApproved.innerText = countApproved;
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
                        statusHeader.innerHTML = `Status <span style="font-size: 0.65rem; font-weight: normal; color: var(--v2-label);">(All)</span>`;
                    } else {
                        statusHeader.innerHTML = `Status <br><span style="color: var(--v2-label); font-weight: 800; font-size: 0.65rem; text-transform: uppercase;">${toTitleCase(currentStatusFilter)}</span>`;
                    }
                    applyFilters();
                });
            }

            document.querySelectorAll('#pr-table th.sortable').forEach(th => {
                if (parseInt(th.getAttribute('data-col')) === 4) return;

                th.addEventListener('click', () => {
                    const colIndex = parseInt(th.getAttribute('data-col'));
                    const isNumericCol = th.classList.contains('numeric');
                    const isDateCol = th.classList.contains('date');
                    const isAsc = th.classList.contains('asc');
                    const direction = isAsc ? -1 : 1; 
                    
                    document.querySelectorAll('#pr-table th.sortable').forEach(header => {
                        if (parseInt(header.getAttribute('data-col')) !== 4) {
                            header.classList.remove('asc', 'desc');
                        }
                    });
                    
                    th.classList.add(isAsc ? 'desc' : 'asc');
                    
                    currentRows.sort((a, b) => {
                        let aText = a.children[colIndex].innerText.trim();
                        let bText = b.children[colIndex].innerText.trim();
                        
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
