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
$canApprovePr = $user !== null && method_exists($user, 'can') && $user->can('procurement.pr.approve');
$canManagePo = $user !== null && method_exists($user, 'can') && $user->can('procurement.po.create');
$canManagePoRequests = $user !== null && method_exists($user, 'can') && $user->can('procurement.por.manage');
$poRequestStatusOptions = $statusOptions ?? [];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/procurement-queue.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $poRequestExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') . '?' . $poRequestExportQuery ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<?php if ($canApprovePr): ?>
<a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Pending Approvals</a>
<?php endif ?>
<?php if ($canManagePo): ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Purchase Orders</a>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $poRequests ?? [];
$totalRequests = count($rows);
$pendingRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'pending'));
$approvedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'approved'));
$rejectedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'rejected'));
?>

<div class="procurement-queue procurement-queue--po-requests">
    
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
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Search PO request number or PO ID" autocomplete="off">
                </div>
                
                <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('procurement/po-requests') ?>" style="margin: 0; display: flex; gap: 8px;">
                    <select id="status" name="status" class="filter-select" aria-label="Filter PO requests by status">
                        <option value="">All Statuses</option>
                        <?php foreach ($poRequestStatusOptions as $option => $label): ?>
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
                                                                (<?= esc(app_format_quantity($item['ordered_qty'] ?? 0)) ?>
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
                                    <?php if ((bool) ($poRequest['uses_special_status_badge'] ?? false)): ?>
                                        <span class="status-badge-special status-badge-special--indigo" title="<?= esc((string) ($poRequest['status_label'] ?? 'Converted to Receiving')) ?>"><?= esc((string) ($poRequest['status_label'] ?? 'Converted to Receiving')) ?></span>
                                    <?php else: ?>
                                        <?= view('components/shared/table_status_badge', [
                                            'status' => $poRequest['status'] ?? 'unknown',
                                            'label' => $poRequest['status_label'] ?? null,
                                        ]) ?>
                                    <?php endif; ?>
                                </td>
                                
                                <td style="font-size: 0.85rem; color: var(--v2-text-muted); font-weight: 500;">
                                    <?= esc((string) ($poRequest['action_by_label'] ?? '-')) ?>
                                </td>
                                
                                <td>
                                    <?php if (($poRequest['status'] ?? '') === 'pending' && $canManagePoRequests): ?>
                                        <div class="action-forms-container">
                                            <form method="post" action="<?= site_url('procurement/po-requests/' . $poRequest['id'] . '/approve') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table btn-approve">Approve</button>
                                            </form>
                                            <form method="post" action="<?= site_url('procurement/po-requests/' . $poRequest['id'] . '/reject') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <div class="approval-input-group">
                                                    <input type="text" name="reason" class="reject-input" placeholder="Reason" required>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/procurement-queue.js') ?>"></script>
<script>
    (function () {
        const statusLabels = <?= json_encode($poRequestStatusOptions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

        window.InventoryV2ProcurementQueue.init({
            tableSelector: '#po-req-table',
            rowSelector: '.po-req-row',
            searchInputSelector: '#instant-search-input',
            clearButtonSelector: '#btn-clear-search',
            pagerSelector: '#client-pager',
            pageIndicatorSelector: '#page-indicator',
            totalIndicatorSelector: '#total-indicator',
            filter: {
                headerSelector: '#status-header',
                title: 'Status',
                values: ['All', ...Object.keys(statusLabels)],
                attribute: 'data-status',
                labelFor: function (value) {
                    return statusLabels[value] || value.split('_').map(function (word) {
                        return word.charAt(0).toUpperCase() + word.slice(1);
                    }).join(' ');
                }
            },
            searchMatcher: function (row, query) {
                const id = row.children[0].innerText.toLowerCase();
                const poRequestNumber = row.children[1].innerText.toLowerCase();
                const poId = row.children[2].innerText.toLowerCase();

                return id.includes(query) || poRequestNumber.includes(query) || poId.includes(query);
            },
            sortValue: function (row, colIndex) {
                return row.children[colIndex].innerText.trim().replace(/PO-|#/g, '');
            },
            updateKpis: function (rows) {
                let pendingCount = 0;
                let approvedCount = 0;
                let rejectedCount = 0;

                rows.forEach(function (row) {
                    const status = row.getAttribute('data-status');

                    if (status === 'pending') {
                        pendingCount++;
                    }

                    if (status === 'approved') {
                        approvedCount++;
                    }

                    if (status === 'rejected') {
                        rejectedCount++;
                    }
                });

                document.getElementById('kpi-visible').innerText = rows.length;
                document.getElementById('kpi-pending').innerText = pendingCount;
                document.getElementById('kpi-approved').innerText = approvedCount;
                document.getElementById('kpi-rejected').innerText = rejectedCount;
            }
        });
    })();
</script>
<?= $this->endSection() ?>
