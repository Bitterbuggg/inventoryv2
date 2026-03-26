<?php

declare(strict_types=1);

$title = 'Inventory Stock Details - InventoryV2';
$pageTitle = 'Inventory Stock #' . (string) ($stock['id'] ?? '');
$pageSubtitle = 'Movement history and current balance for this stock record.';
$crumbs = [
    ['label' => 'Inventory Quantities', 'url' => site_url('inventory/quantities')],
    ['label' => 'Stock Details'],
];

$movementTypeLabels = [
    'receiving'      => 'Receiving',
    'issuance'       => 'Issuance',
    'adjustment_in'  => 'Stock Adjustment In',
    'adjustment_out' => 'Stock Disposal',
    'return'         => 'Return',
];

$referenceTypeLabels = [
    'receiving'         => 'Receiving',
    'issuance'          => 'Issuance',
    'manual_adjustment' => 'Stock Disposal',
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>">Back to Inventory Quantities</a>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities/' . $stock['id'] . '/movements.csv') ?>">Export Movements CSV</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$movements = $stock['movements'] ?? [];
$totalIn = array_sum(array_map(static fn (array $row): float => (float) ($row['qty_in'] ?? 0), $movements));
$totalOut = array_sum(array_map(static fn (array $row): float => (float) ($row['qty_out'] ?? 0), $movements));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Item</p>
                <p class="kpi-value"><?= esc((string) ($stock['item_name'] ?? 'N/A')) ?></p>
                <p class="kpi-note">Current stock record item name.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">On Hand</p>
                <p class="kpi-value"><?= esc((string) ($stock['on_hand_qty'] ?? '0')) ?></p>
                <p class="kpi-note">Total physical balance.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Available</p>
                <p class="kpi-value"><?= esc((string) ($stock['available_qty'] ?? '0')) ?></p>
                <p class="kpi-note">On hand minus reserved quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Movements</p>
                <p class="kpi-value"><?= esc((string) count($movements)) ?></p>
                <p class="kpi-note">History entries on this stock ID.</p>
            </article>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Item</span>
                <span class="detail-value"><?= esc((string) ($stock['item_name'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Unit</span>
                <span class="detail-value"><?= esc((string) ($stock['unit'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">On Hand</span>
                <span class="detail-value"><?= esc((string) ($stock['on_hand_qty'] ?? '0')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Available</span>
                <span class="detail-value"><?= esc((string) ($stock['available_qty'] ?? '0')) ?></span>
            </div>
        </div>
    </section>

    <section class="card stack-md">
        <h2>Stock Movements</h2>
        <p class="split-note"><span>Total In: <?= esc(number_format($totalIn, 0)) ?></span><span>Total Out: <?= esc(number_format($totalOut, 0)) ?></span></p>
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
                                <td><?= esc($movementTypeLabels[(string) ($movement['movement_type'] ?? '')] ?? ucwords(str_replace('_', ' ', (string) ($movement['movement_type'] ?? '')))) ?></td>
                                <td>
                                    <?= esc($referenceTypeLabels[(string) ($movement['reference_type'] ?? '')] ?? ucwords(str_replace('_', ' ', (string) ($movement['reference_type'] ?? '')))) ?>
                                    <?= ($movement['reference_id'] ?? null) !== null ? ' #' . esc((string) $movement['reference_id']) : '' ?>
                                </td>
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
</div>
<?= $this->endSection() ?>

