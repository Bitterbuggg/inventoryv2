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
$canApprovePr = $user !== null && method_exists($user, 'can') && $user->can('procurement.pr.approve');
$canManagePo = $user !== null && method_exists($user, 'can') && $user->can('procurement.po.create');
$canManagePoRequests = $user !== null && method_exists($user, 'can') && $user->can('procurement.por.manage');
$purchaseOrderStatusOptions = $statusOptions ?? [];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/procurement-queue.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $purchaseOrderExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') . '?' . $purchaseOrderExportQuery ?>" data-filtered-csv-export data-export-table="#po-table" data-export-row-selector=".po-row" data-export-exclude-columns="7" data-export-filename="purchase_orders.csv" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<?php if ($canApprovePr): ?>
<a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Pending Approvals</a>
<?php endif ?>
<?php if ($canManagePoRequests): ?>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">PO Requests</a>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $purchaseOrders ?? [];
$totalOrders = count($rows);
$draftOrders = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'draft'));
$issuedOrders = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'issued'));
$receivedOrders = count(array_filter($rows, static fn (array $row): bool => in_array((string) ($row['status'] ?? ''), ['partially_received', 'fully_received'], true)));
?>

<div class="procurement-queue procurement-queue--purchase-orders">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Purchase Orders</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-total"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-visible"><?= esc((string) $totalOrders) ?></span>
                    <span class="kpi-label">Visible POs</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-amber">
                <div class="kpi-icon-box icon-draft--amber"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-draft" style="color: #b45309;"><?= esc((string) $draftOrders) ?></span>
                    <span class="kpi-label">Draft Status</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-issued"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-issued" style="color: var(--v2-label);"><?= esc((string) $issuedOrders) ?></span>
                    <span class="kpi-label">Issued Orders</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
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
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Search PO number or supplier" autocomplete="off">
                </div>
                
                <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('procurement/purchase-orders') ?>" style="margin: 0; display: flex; gap: 8px;">
                    <select id="status" name="status" class="filter-select" aria-label="Filter purchase orders by status">
                        <option value="">All Statuses</option>
                        <?php foreach ($purchaseOrderStatusOptions as $option => $label): ?>
                            <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach ?>
                    </select>
                    <button type="button" class="btn btn-outline" id="btn-clear-search" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px;">Clear</button>
                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px; background: var(--v2-label); border: none;">Filter Server</button>
                </form>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table modern-table--compact modern-table--purchase-orders" id="po-table">
                <colgroup>
                    <col class="col-id" style="width: 58px;">
                    <col class="col-po-number" style="width: 170px;">
                    <col class="col-pr-id" style="width: 88px;">
                    <col class="col-supplier" style="width: 27%;">
                    <col class="col-order-date" style="width: 110px;">
                    <col class="col-status" style="width: 138px;">
                    <col class="col-total" style="width: 110px;">
                    <col class="col-actions" style="width: 156px;">
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric col-id" data-col="0">ID</th>
                        <th class="sortable col-po-number" data-col="1">PO Number</th>
                        <th class="sortable numeric col-pr-id" data-col="2">PR ID</th>
                        <th class="sortable col-supplier" data-col="3">Supplier</th>
                        <th class="sortable date col-order-date" data-col="4">Order Date</th>
                        <th class="sortable col-status" data-col="5" id="status-header">
                            Status <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>
                        </th>
                        <th class="sortable numeric col-total" data-col="6">Total</th>
                        <th class="actions col-actions">Actions</th>
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
                                <td class="mono-cell" data-label="ID" style="font-weight: 700; color: #94a3b8;"><?= esc((string) $order['id']) ?></td>
                                <td class="primary-cell" data-label="PO Number">
                                    <span class="primary-value"><?= esc((string) $order['po_number']) ?></span>
                                    <span class="secondary-value">PR-<?= esc((string) $order['purchase_request_id']) ?></span>
                                </td>
                                <td class="mono-cell col-pr-id" data-label="PR ID" style="font-size: 0.8rem; color: var(--v2-text-muted);">PR-<?= esc((string) $order['purchase_request_id']) ?></td>
                                <td class="supplier-cell" data-label="Supplier" style="font-weight: 700; color: var(--v2-text-main);">
                                    <span class="primary-value"><?= esc((string) ($order['supplier_name'] ?? '-')) ?></span>
                                    <span class="secondary-value">Order Date: <?= esc((string) $order['order_date']) ?></span>
                                </td>
                                <td class="date-cell col-order-date" data-label="Order Date" style="font-size: 0.8rem;"><?= esc((string) $order['order_date']) ?></td>
                                
                                <td class="status-cell" data-label="Status">
                                    <span class="status-badge <?= esc((string) ($order['status_badge_class'] ?? 'status-draft')) ?>">
                                        <?= esc((string) ($order['status_label'] ?? 'Draft')) ?>
                                    </span>
                                </td>
                                
                                <td class="currency-cell" data-label="Total" style="font-family: var(--font-mono); font-weight: 700; color: var(--v2-title);">
                                    <?= esc(number_format((float) ($order['total_amount'] ?? 0), 2)) ?>
                                </td>
                                
                                <td class="actions-cell" data-label="Actions">
                                    <div class="action-forms-container action-forms-container--stacked">
                                        <?php if (($order['status'] ?? '') === 'draft' && $canManagePo): ?>
                                            <form method="post" action="<?= site_url('procurement/purchase-orders/' . $order['id'] . '/issue') ?>" class="action-form">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table btn-action-primary">Issue</button>
                                            </form>
                                        <?php endif ?>

                                        <?php if (($order['status'] ?? '') === 'issued' && $canManagePoRequests && ! (bool) ($order['has_open_po_request'] ?? false)): ?>
                                            <form method="post" action="<?= site_url('procurement/po-requests/from-po/' . $order['id']) ?>" class="action-form">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table btn-action-indigo">PO Req &rarr;</button>
                                            </form>
                                        <?php endif ?>

                                        <?php if (($order['status'] ?? '') === 'issued' && (bool) ($order['has_open_po_request'] ?? false)): ?>
                                            <span class="<?= esc((string) ($order['po_request_badge_class'] ?? 'action-badge-warning')) ?>">
                                                <?= esc((string) ($order['po_request_badge_label'] ?? 'PO REQ: PENDING')) ?>
                                            </span>
                                        <?php endif ?>
                                        
                                        <?php if (! $canManagePo && ! $canManagePoRequests && in_array((string) ($order['status'] ?? ''), ['draft', 'issued'], true)): ?>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/procurement-queue.js') ?>"></script>
<script>
    (function () {
        const statusLabels = <?= json_encode($purchaseOrderStatusOptions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

        window.InventoryV2ProcurementQueue.init({
            tableSelector: '#po-table',
            rowSelector: '.po-row',
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
                const poNumber = row.children[1].innerText.toLowerCase();
                const prId = row.children[2].innerText.toLowerCase();
                const supplier = row.children[3].innerText.toLowerCase();

                return poNumber.includes(query) || prId.includes(query) || supplier.includes(query);
            },
            sortValue: function (row, colIndex) {
                return row.children[colIndex].innerText.trim().replace(/PR-|#|,/g, '');
            },
            updateKpis: function (rows) {
                let draftCount = 0;
                let issuedCount = 0;
                let receivedCount = 0;

                rows.forEach(function (row) {
                    const status = row.getAttribute('data-status');

                    if (status === 'draft') {
                        draftCount++;
                    }

                    if (status === 'issued') {
                        issuedCount++;
                    }

                    if (status === 'partially_received' || status === 'fully_received') {
                        receivedCount++;
                    }
                });

                document.getElementById('kpi-visible').innerText = rows.length;
                document.getElementById('kpi-draft').innerText = draftCount;
                document.getElementById('kpi-issued').innerText = issuedCount;
                document.getElementById('kpi-received').innerText = receivedCount;
            }
        });
    })();
</script>
<?= $this->endSection() ?>
