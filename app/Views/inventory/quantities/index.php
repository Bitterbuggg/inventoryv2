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
