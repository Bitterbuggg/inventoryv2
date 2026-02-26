<?php

declare(strict_types=1);

$title = 'Receiving Details - InventoryV2';
$pageTitle = 'Receiving #' . (string) ($receiving['receiving_number'] ?? '');
$pageSubtitle = 'Review draft/posting details and receiving line items.';
$crumbs = [
    ['label' => 'Receiving', 'url' => site_url('receiving')],
    ['label' => 'Receiving Details'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('receiving') ?>">Back to Receiving List</a>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
<a class="btn btn-outline" href="<?= site_url('receiving/' . $receiving['id'] . '/items.csv') ?>">Export Items CSV</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$itemRows = $receiving['items'] ?? [];
$totalReceived = array_sum(array_map(static fn (array $row): float => (float) ($row['received_qty'] ?? 0), $itemRows));
$totalAccepted = array_sum(array_map(static fn (array $row): float => (float) ($row['accepted_qty'] ?? 0), $itemRows));
$totalRejected = array_sum(array_map(static fn (array $row): float => (float) ($row['rejected_qty'] ?? 0), $itemRows));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Status</p>
                <p class="kpi-value"><?= esc(ucfirst((string) ($receiving['status'] ?? 'unknown'))) ?></p>
                <p class="kpi-note">Current receiving workflow state.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Lines</p>
                <p class="kpi-value"><?= esc((string) count($itemRows)) ?></p>
                <p class="kpi-note">Receiving item rows recorded.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Accepted Qty</p>
                <p class="kpi-value"><?= esc(number_format($totalAccepted, 2)) ?></p>
                <p class="kpi-note">Total accepted quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Rejected Qty</p>
                <p class="kpi-value"><?= esc(number_format($totalRejected, 2)) ?></p>
                <p class="kpi-note">Total rejected quantity.</p>
            </article>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Status</span>
                <span class="detail-value"><?= view('components/shared/table_status_badge', ['status' => $receiving['status'] ?? 'unknown']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">PO Request ID</span>
                <span class="detail-value"><?= esc((string) ($receiving['po_request_id'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Purchase Order ID</span>
                <span class="detail-value"><?= esc((string) ($receiving['purchase_order_id'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Received Date</span>
                <span class="detail-value"><?= esc((string) ($receiving['received_date'] ?? '')) ?></span>
            </div>
        </div>

        <?php if (($receiving['status'] ?? '') === 'draft'): ?>
            <div class="toolbar">
                <form class="inline-form" method="post" action="<?= site_url('receiving/' . $receiving['id'] . '/validate') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline">Validate Draft</button>
                </form>
                <form class="inline-form" method="post" action="<?= site_url('receiving/' . $receiving['id'] . '/post') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary">Post Receiving</button>
                </form>
                <form class="inline-form" method="post" action="<?= site_url('receiving/' . $receiving['id'] . '/void') ?>">
                    <?= csrf_field() ?>
                    <input type="text" name="reason" placeholder="Void reason" required>
                    <button type="submit" class="btn btn-danger">Void Draft</button>
                </form>
            </div>
        <?php endif ?>
    </section>

    <section class="card stack-md">
        <h2>Items</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>PO Item ID</th>
                        <th>Item</th>
                        <th>Unit</th>
                        <th>Received</th>
                        <th>Accepted</th>
                        <th>Rejected</th>
                        <th>Batch</th>
                        <th>Lot</th>
                        <th>Expiry</th>
                        <th>Unit Cost</th>
                        <th>Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($receiving['items'] ?? []) === []): ?>
                        <tr><td colspan="11" class="empty-state">No receiving items found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($receiving['items'] as $item): ?>
                            <tr>
                                <td><?= esc((string) $item['purchase_order_item_id']) ?></td>
                                <td><?= esc((string) $item['item_name']) ?></td>
                                <td><?= esc((string) $item['unit']) ?></td>
                                <td><?= esc((string) $item['received_qty']) ?></td>
                                <td><?= esc((string) $item['accepted_qty']) ?></td>
                                <td><?= esc((string) $item['rejected_qty']) ?></td>
                                <td><?= esc((string) ($item['batch_no'] ?? '')) ?></td>
                                <td><?= esc((string) ($item['lot_no'] ?? '')) ?></td>
                                <td><?= esc((string) ($item['expiry_date'] ?? '')) ?></td>
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

