<?php

declare(strict_types=1);

$title = 'Stock Movement Report - InventoryV2';
$pageTitle = 'Report: Stock Movements';
$pageSubtitle = 'Inbound and outbound stock movement history.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Stock Movements'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>">Low Stock</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <form class="inline-form" method="get" action="<?= site_url('reports/stock-movements') ?>">
        <label for="date_from">Date From</label>
        <input id="date_from" type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>">
        <label for="date_to">Date To</label>
        <input id="date_to" type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>">
        <label for="movement_type">Type</label>
        <select id="movement_type" name="movement_type">
            <option value="">All</option>
            <?php foreach (['receiving', 'issuance', 'adjustment_in', 'adjustment_out', 'return'] as $type): ?>
                <option value="<?= esc($type) ?>" <?= (($movement_type ?? '') === $type) ? 'selected' : '' ?>><?= esc($type) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit" class="btn btn-outline">Apply</button>
    </form>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Movement #</th>
                    <th>Type</th>
                    <th>Reference</th>
                    <th>Item</th>
                    <th>Unit</th>
                    <th>Qty In</th>
                    <th>Qty Out</th>
                    <th>Balance After</th>
                    <th>Performed At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($rows ?? []) === []): ?>
                    <tr><td colspan="10" class="empty-state">No records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= esc((string) $row['id']) ?></td>
                            <td><?= esc((string) $row['movement_number']) ?></td>
                            <td><?= esc((string) $row['movement_type']) ?></td>
                            <td><?= esc((string) $row['reference_type']) ?> #<?= esc((string) $row['reference_id']) ?></td>
                            <td><?= esc((string) $row['item_name']) ?></td>
                            <td><?= esc((string) $row['unit']) ?></td>
                            <td><?= esc((string) $row['qty_in']) ?></td>
                            <td><?= esc((string) $row['qty_out']) ?></td>
                            <td><?= esc((string) $row['balance_after']) ?></td>
                            <td><?= esc((string) $row['performed_at']) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
