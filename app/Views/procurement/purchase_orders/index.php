<?php

declare(strict_types=1);

$title = 'Purchase Orders - InventoryV2';
$pageTitle = 'Procurement - Purchase Orders';
$pageSubtitle = 'Issue draft purchase orders and convert issued orders to PO requests.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Purchase Orders'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>">Pending Approvals</a>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $purchaseOrders ?? [];
$totalOrders = count($rows);
$draftOrders = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'draft'));
$issuedOrders = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'issued'));
$receivedOrders = count(array_filter($rows, static fn (array $row): bool => in_array((string) ($row['status'] ?? ''), ['partially_received', 'fully_received'], true)));
?>
<section class="card stack-md">
    <div class="kpi-grid">
        <article class="kpi-card">
            <p class="kpi-label">Visible POs</p>
            <p class="kpi-value"><?= esc((string) $totalOrders) ?></p>
            <p class="kpi-note">Purchase orders in current view.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Draft</p>
            <p class="kpi-value"><?= esc((string) $draftOrders) ?></p>
            <p class="kpi-note">Pending PO issuance.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Issued</p>
            <p class="kpi-value"><?= esc((string) $issuedOrders) ?></p>
            <p class="kpi-note">Ready for PO request flow.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Received</p>
            <p class="kpi-value"><?= esc((string) $receivedOrders) ?></p>
            <p class="kpi-note">Partially or fully received.</p>
        </article>
    </div>
</section>

<section class="card stack-md">
    <div class="stack-sm">
        <h2>Purchase Order Queue</h2>
        <p class="muted">Filter by status, issue draft POs, and convert issued POs to PO requests.</p>
    </div>

    <form class="inline-form" method="get" action="<?= site_url('procurement/purchase-orders') ?>">
        <label for="status">Filter status</label>
        <select id="status" name="status">
            <option value="">All</option>
            <?php foreach (['draft', 'issued', 'partially_received', 'fully_received', 'cancelled'] as $option): ?>
                <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit" class="btn btn-outline">Apply</button>
        <a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>">Reset</a>
    </form>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>PO Number</th>
                    <th>PR ID</th>
                    <th>Supplier</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($purchaseOrders ?? []) === []): ?>
                    <tr>
                        <td colspan="8" class="empty-state">No purchase orders found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($purchaseOrders as $order): ?>
                        <tr>
                            <td><?= esc((string) $order['id']) ?></td>
                            <td><?= esc((string) $order['po_number']) ?></td>
                            <td><?= esc((string) $order['purchase_request_id']) ?></td>
                            <td><?= esc((string) ($order['supplier_name'] ?? '')) ?></td>
                            <td><?= esc((string) $order['order_date']) ?></td>
                            <td><?= view('components/shared/table_status_badge', ['status' => $order['status'] ?? 'unknown']) ?></td>
                            <td><?= esc(number_format((float) ($order['total_amount'] ?? 0), 2)) ?></td>
                            <td>
                                <div class="toolbar">
                                    <?php if (($order['status'] ?? '') === 'draft'): ?>
                                        <form class="inline-form" method="post" action="<?= site_url('procurement/purchase-orders/' . $order['id'] . '/issue') ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-primary">Issue Order</button>
                                        </form>
                                    <?php endif ?>

                                    <?php if (($order['status'] ?? '') === 'issued'): ?>
                                        <form class="inline-form" method="post" action="<?= site_url('procurement/po-requests/from-po/' . $order['id']) ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline">Create PO Request</button>
                                        </form>
                                    <?php endif ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>

