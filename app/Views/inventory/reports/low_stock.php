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
<?php
$lowStockRows = $rows ?? [];
$totalRows = count($lowStockRows);
$totalAvailable = array_sum(array_map(static fn (array $row): float => (float) ($row['available_qty'] ?? 0), $lowStockRows));
$criticalRows = count(array_filter($lowStockRows, static fn (array $row): bool => (float) ($row['available_qty'] ?? 0) <= 0));
$nearExpiryRows = count(array_filter(
    $lowStockRows,
    static fn (array $row): bool => isset($row['expiry_date']) && (string) $row['expiry_date'] !== '' && strtotime((string) $row['expiry_date']) <= strtotime('+60 days')
));
?>
    <div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Low Stock Items</p>
                <p class="kpi-value"><?= esc((string) $totalRows) ?></p>
                <p class="kpi-note">Items at or below threshold.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Available</p>
                <p class="kpi-value"><?= esc(number_format($totalAvailable, 2)) ?></p>
                <p class="kpi-note">Combined available quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Critical (<= 0)</p>
                <p class="kpi-value"><?= esc((string) $criticalRows) ?></p>
                <p class="kpi-note">Immediate replenishment needed.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Near Expiry (60d)</p>
                <p class="kpi-value"><?= esc((string) $nearExpiryRows) ?></p>
                <p class="kpi-note">Needs expiry risk review.</p>
            </article>
        </div>
    </section>

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
</div>
<?= $this->endSection() ?>
