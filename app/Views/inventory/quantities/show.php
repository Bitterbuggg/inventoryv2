<?php

declare(strict_types=1);

$title = 'Inventory Stock Details - InventoryV2';
$pageTitle = 'Inventory Stock #' . (string) ($stock['id'] ?? '');
$pageSubtitle = 'Movement history and current balance for this stock record.';
$crumbs = [
    ['label' => 'Inventory Quantities', 'url' => site_url('inventory/quantities')],
    ['label' => 'Stock Details'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>">Back to Inventory Quantities</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <p><strong>Item:</strong> <?= esc((string) ($stock['item_name'] ?? '')) ?></p>
    <p><strong>Unit:</strong> <?= esc((string) ($stock['unit'] ?? '')) ?></p>
    <p><strong>On Hand:</strong> <?= esc((string) ($stock['on_hand_qty'] ?? '0')) ?></p>
    <p><strong>Available:</strong> <?= esc((string) ($stock['available_qty'] ?? '0')) ?></p>
</section>

<section class="card stack-md" style="margin-top: 16px;">
    <h2>Stock Movements</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Movement Number</th>
                    <th>Type</th>
                    <th>Reference</th>
                    <th>Qty In</th>
                    <th>Qty Out</th>
                    <th>Balance After</th>
                    <th>Performed At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($stock['movements'] ?? []) === []): ?>
                    <tr><td colspan="8" class="empty-state">No movements found.</td></tr>
                <?php else: ?>
                    <?php foreach ($stock['movements'] as $movement): ?>
                        <tr>
                            <td><?= esc((string) $movement['id']) ?></td>
                            <td><?= esc((string) $movement['movement_number']) ?></td>
                            <td><?= esc((string) $movement['movement_type']) ?></td>
                            <td><?= esc((string) $movement['reference_type']) ?> #<?= esc((string) $movement['reference_id']) ?></td>
                            <td><?= esc((string) $movement['qty_in']) ?></td>
                            <td><?= esc((string) $movement['qty_out']) ?></td>
                            <td><?= esc((string) $movement['balance_after']) ?></td>
                            <td><?= esc((string) $movement['performed_at']) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
