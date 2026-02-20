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
<section class="card stack-md">
    <form class="inline-form" method="get" action="<?= site_url('procurement/purchase-orders') ?>">
        <label for="status">Filter status</label>
        <select id="status" name="status">
            <option value="">All</option>
            <?php foreach (['draft', 'issued', 'partially_received', 'fully_received', 'cancelled'] as $option): ?>
                <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit" class="btn btn-outline">Apply</button>
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
                                            <button type="submit" class="btn btn-primary">Issue PO</button>
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
