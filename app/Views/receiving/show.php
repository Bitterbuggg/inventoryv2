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
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <p><strong>Status:</strong> <?= view('components/shared/table_status_badge', ['status' => $receiving['status'] ?? 'unknown']) ?></p>
    <p><strong>PO Request ID:</strong> <?= esc((string) ($receiving['po_request_id'] ?? '')) ?></p>
    <p><strong>Purchase Order ID:</strong> <?= esc((string) ($receiving['purchase_order_id'] ?? '')) ?></p>
    <p><strong>Received Date:</strong> <?= esc((string) ($receiving['received_date'] ?? '')) ?></p>

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

<section class="card stack-md" style="margin-top: 16px;">
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
<?= $this->endSection() ?>
