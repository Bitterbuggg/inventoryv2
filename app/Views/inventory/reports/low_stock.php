<?php

declare(strict_types=1);

$title = 'Low Stock Report - InventoryV2';
$pageTitle = 'Report: Low Stock';
$pageSubtitle = 'Items with available quantity at or below threshold.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Low Stock'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <form class="inline-form" method="get" action="<?= site_url('reports/low-stock') ?>">
        <label for="threshold">Threshold</label>
        <input id="threshold" type="number" step="0.001" min="0" name="threshold" value="<?= esc((string) ($threshold ?? 10)) ?>">
        <button type="submit" class="btn btn-outline">Apply</button>
    </form>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item</th>
                    <th>Unit</th>
                    <th>Batch</th>
                    <th>Lot</th>
                    <th>Expiry</th>
                    <th>Available Qty</th>
                    <th>On Hand</th>
                    <th>Reserved</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($rows ?? []) === []): ?>
                    <tr><td colspan="9" class="empty-state">No records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= esc((string) $row['id']) ?></td>
                            <td><?= esc((string) $row['item_name']) ?></td>
                            <td><?= esc((string) $row['unit']) ?></td>
                            <td><?= esc((string) ($row['batch_no'] ?? '')) ?></td>
                            <td><?= esc((string) ($row['lot_no'] ?? '')) ?></td>
                            <td><?= esc((string) ($row['expiry_date'] ?? '')) ?></td>
                            <td><?= esc((string) $row['available_qty']) ?></td>
                            <td><?= esc((string) $row['on_hand_qty']) ?></td>
                            <td><?= esc((string) $row['reserved_qty']) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
