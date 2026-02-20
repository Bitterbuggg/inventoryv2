<?php

declare(strict_types=1);

$title = 'Inventory Quantities - InventoryV2';
$pageTitle = 'Inventory Quantities';
$pageSubtitle = 'Search current stock balances, batches, lots, and available quantities.';
$crumbs = [
    ['label' => 'Inventory Quantities'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('receiving') ?>">Receiving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $stocks ?? [];
$totalRows = count($rows);
$totalOnHand = array_sum(array_map(static fn (array $row): float => (float) ($row['on_hand_qty'] ?? 0), $rows));
$totalAvailable = array_sum(array_map(static fn (array $row): float => (float) ($row['available_qty'] ?? 0), $rows));
$zeroAvailable = count(array_filter($rows, static fn (array $row): bool => (float) ($row['available_qty'] ?? 0) <= 0));
?>
<section class="card stack-md">
    <div class="kpi-grid">
        <article class="kpi-card">
            <p class="kpi-label">Visible SKUs</p>
            <p class="kpi-value"><?= esc((string) $totalRows) ?></p>
            <p class="kpi-note">Rows in current inventory view.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Total On Hand</p>
            <p class="kpi-value"><?= esc(number_format($totalOnHand, 2)) ?></p>
            <p class="kpi-note">Current physical quantity total.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Total Available</p>
            <p class="kpi-value"><?= esc(number_format($totalAvailable, 2)) ?></p>
            <p class="kpi-note">On hand minus reserved quantities.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Zero/Negative Available</p>
            <p class="kpi-value"><?= esc((string) $zeroAvailable) ?></p>
            <p class="kpi-note">Needs restocking review.</p>
        </article>
    </div>
</section>

<section class="card stack-md">
    <form class="inline-form" method="get" action="<?= site_url('inventory/quantities') ?>">
        <input type="text" name="q" placeholder="Search item name" value="<?= esc((string) ($keyword ?? '')) ?>">
        <button type="submit" class="btn btn-outline">Search</button>
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($stocks ?? []) === []): ?>
                    <tr><td colspan="11" class="empty-state">No inventory stocks found.</td></tr>
                <?php else: ?>
                    <?php foreach ($stocks as $stock): ?>
                        <tr>
                            <td><?= esc((string) $stock['id']) ?></td>
                            <td><?= esc((string) $stock['item_name']) ?></td>
                            <td><?= esc((string) $stock['unit']) ?></td>
                            <td><?= esc((string) ($stock['batch_no'] ?? '')) ?></td>
                            <td><?= esc((string) ($stock['lot_no'] ?? '')) ?></td>
                            <td><?= esc((string) ($stock['expiry_date'] ?? '')) ?></td>
                            <td><?= esc((string) $stock['on_hand_qty']) ?></td>
                            <td><?= esc((string) $stock['reserved_qty']) ?></td>
                            <td><?= esc((string) $stock['available_qty']) ?></td>
                            <td><?= esc(number_format((float) ($stock['average_unit_cost'] ?? 0), 2)) ?></td>
                            <td><a class="btn btn-outline" href="<?= site_url('inventory/quantities/' . $stock['id']) ?>">View</a></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
