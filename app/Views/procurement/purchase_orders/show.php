<?php

declare(strict_types=1);

$purchaseOrder = $purchaseOrder ?? [];
$purchaseRequest = $purchaseRequest ?? null;
$poRequest = $poRequest ?? null;
$receiving = $receiving ?? null;

$title = 'Purchase Order Details - InventoryV2';
$pageTitle = 'Purchase Order #' . (string) ($purchaseOrder['po_number'] ?? '');
$pageSubtitle = 'Review purchase-order details, linked records, and ordered line items.';
$crumbs = [
    ['label' => 'Purchase Orders', 'url' => site_url('procurement/purchase-orders')],
    ['label' => 'Details'],
];

$items = is_array($purchaseOrder['items'] ?? null) ? $purchaseOrder['items'] : [];
$totalOrdered = array_sum(array_map(static fn (array $row): float => (float) ($row['ordered_qty'] ?? 0), $items));
$totalReceived = array_sum(array_map(static fn (array $row): float => (float) ($row['received_qty'] ?? 0), $items));
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>">Back to Orders</a>
<?php if (is_array($purchaseRequest) && (int) ($purchaseRequest['id'] ?? 0) > 0): ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests/' . (int) $purchaseRequest['id']) ?>">Open Purchase Request</a>
<?php endif ?>
<?php if (is_array($receiving) && (int) ($receiving['id'] ?? 0) > 0): ?>
<a class="btn btn-outline" href="<?= site_url('receiving/' . (int) $receiving['id']) ?>">Open Receiving</a>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Status</p>
                <p class="kpi-value"><?= esc(ucfirst((string) ($purchaseOrder['status'] ?? 'unknown'))) ?></p>
                <p class="kpi-note">Current purchase-order workflow state.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Item Lines</p>
                <p class="kpi-value"><?= esc((string) count($items)) ?></p>
                <p class="kpi-note">Ordered line entries.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Ordered Qty</p>
                <p class="kpi-value"><?= esc(app_format_quantity($totalOrdered)) ?></p>
                <p class="kpi-note">Total quantity across all PO items.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Amount</p>
                <p class="kpi-value">PHP <?= esc(number_format((float) ($purchaseOrder['total_amount'] ?? 0), 2)) ?></p>
                <p class="kpi-note">Final purchase-order total.</p>
            </article>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">PO Number</span>
                <span class="detail-value"><?= esc((string) ($purchaseOrder['po_number'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Supplier</span>
                <span class="detail-value"><?= esc((string) ($purchaseOrder['supplier_name'] ?? '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Order Date</span>
                <span class="detail-value"><?= esc((string) ($purchaseOrder['order_date'] ?? '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Purchase Request</span>
                <span class="detail-value">
                    <?php if (is_array($purchaseRequest)): ?>
                        <?= esc((string) ($purchaseRequest['pr_number'] ?? ('#' . (string) ($purchaseOrder['purchase_request_id'] ?? '')))) ?>
                    <?php else: ?>
                        <?= esc((string) ('#' . (string) ($purchaseOrder['purchase_request_id'] ?? '-'))) ?>
                    <?php endif ?>
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">PO Request</span>
                <span class="detail-value"><?= esc((string) (is_array($poRequest) ? ($poRequest['po_request_number'] ?? ('#' . (string) ($poRequest['id'] ?? ''))) : '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Receiving</span>
                <span class="detail-value"><?= esc((string) (is_array($receiving) ? ($receiving['receiving_number'] ?? ('#' . (string) ($receiving['id'] ?? ''))) : '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Issued By</span>
                <span class="detail-value"><?= esc((string) ($purchaseOrder['issued_by'] ?? '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Issued At</span>
                <span class="detail-value"><?= esc((string) ($purchaseOrder['issued_at'] ?? '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Subtotal</span>
                <span class="detail-value">PHP <?= esc(number_format((float) ($purchaseOrder['subtotal_amount'] ?? 0), 2)) ?></span>
            </div>
        </div>
    </section>

    <section class="card stack-md">
        <h2>Ordered Items</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Unit</th>
                        <th>Ordered Qty</th>
                        <th>Received Qty</th>
                        <th>Pending Qty</th>
                        <th>Unit Cost</th>
                        <th>Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($items === []): ?>
                        <tr><td colspan="8" class="empty-state">No purchase-order items found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $orderedQty = (float) ($item['ordered_qty'] ?? 0);
                            $receivedQty = (float) ($item['received_qty'] ?? 0);
                            $pendingQty = max($orderedQty - $receivedQty, 0);
                            ?>
                            <tr>
                                <td><?= esc((string) ($item['id'] ?? '')) ?></td>
                                <td><?= esc((string) ($item['item_name'] ?? '')) ?></td>
                                <td><?= esc((string) ($item['unit'] ?? '')) ?></td>
                                <td><?= esc(app_format_quantity($orderedQty)) ?></td>
                                <td><?= esc(app_format_quantity($receivedQty)) ?></td>
                                <td><?= esc(app_format_quantity($pendingQty)) ?></td>
                                <td><?= esc(number_format((float) ($item['unit_cost'] ?? 0), 2)) ?></td>
                                <td><?= esc(number_format((float) ($item['line_total'] ?? 0), 2)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
