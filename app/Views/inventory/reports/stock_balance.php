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
<?php
$balanceRows = $rows ?? [];
$totalSkus = count($balanceRows);
$onHand = array_sum(array_map(static fn (array $row): float => (float) ($row['on_hand_qty'] ?? 0), $balanceRows));
$reserved = array_sum(array_map(static fn (array $row): float => (float) ($row['reserved_qty'] ?? 0), $balanceRows));
$available = array_sum(array_map(static fn (array $row): float => (float) ($row['available_qty'] ?? 0), $balanceRows));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Visible SKUs</p>
                <p class="kpi-value"><?= esc((string) $totalSkus) ?></p>
                <p class="kpi-note">Inventory lines in report view.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">On Hand</p>
                <p class="kpi-value"><?= esc(number_format($onHand, 2)) ?></p>
                <p class="kpi-note">Total physical quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Reserved</p>
                <p class="kpi-value"><?= esc(number_format($reserved, 2)) ?></p>
                <p class="kpi-note">Allocated but unreleased quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Available</p>
                <p class="kpi-value"><?= esc(number_format($available, 2)) ?></p>
                <p class="kpi-note">Usable stock for issuance.</p>
            </article>
        </div>
    </section>

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
</div>
<?= $this->endSection() ?>
