<?php

declare(strict_types=1);

$title = 'Stock Balance Report - InventoryV2';
$pageTitle = 'Report: Stock Balance';
$pageSubtitle = 'Current on-hand, reserved, and available balances.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Stock Balance'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>">Low Stock</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <form class="inline-form" method="get" action="<?= site_url('reports/stock-balance') ?>">
        <input type="text" name="q" placeholder="Search item name" value="<?= esc((string) ($keyword ?? '')) ?>">
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
                    <th>On Hand</th>
                    <th>Reserved</th>
                    <th>Available</th>
                    <th>Avg Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($rows ?? []) === []): ?>
                    <tr><td colspan="10" class="empty-state">No records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= esc((string) $row['id']) ?></td>
                            <td><?= esc((string) $row['item_name']) ?></td>
                            <td><?= esc((string) $row['unit']) ?></td>
                            <td><?= esc((string) ($row['batch_no'] ?? '')) ?></td>
                            <td><?= esc((string) ($row['lot_no'] ?? '')) ?></td>
                            <td><?= esc((string) ($row['expiry_date'] ?? '')) ?></td>
                            <td><?= esc((string) $row['on_hand_qty']) ?></td>
                            <td><?= esc((string) $row['reserved_qty']) ?></td>
                            <td><?= esc((string) $row['available_qty']) ?></td>
                            <td><?= esc(number_format((float) ($row['average_unit_cost'] ?? 0), 2)) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
