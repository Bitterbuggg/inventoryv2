<?php

declare(strict_types=1);

$title = 'Fast Moving Report - InventoryV2';
$pageTitle = 'Report: Fast Moving Items';
$pageSubtitle = 'Top items by issued quantity in the selected date range.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Fast Moving'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>">Issuance Report</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>">Low Stock</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$fastRows = $rows ?? [];
$totalRows = count($fastRows);
$totalQtyOut = array_sum(array_map(static fn (array $row): float => (float) ($row['total_qty_out'] ?? 0), $fastRows));
$topItem = $fastRows[0]['item_name'] ?? 'N/A';
$topItemQty = (float) ($fastRows[0]['total_qty_out'] ?? 0);
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Ranked Items</p>
                <p class="kpi-value"><?= esc((string) $totalRows) ?></p>
                <p class="kpi-note">Items included in current ranking.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Qty Out</p>
                <p class="kpi-value"><?= esc(number_format($totalQtyOut, 2)) ?></p>
                <p class="kpi-note">Aggregate outbound quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Top Item</p>
                <p class="kpi-value"><?= esc((string) $topItem) ?></p>
                <p class="kpi-note">Highest outbound volume item.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Top Item Qty</p>
                <p class="kpi-value"><?= esc(number_format($topItemQty, 2)) ?></p>
                <p class="kpi-note">Outbound quantity of top item.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <form class="inline-form" method="get" action="<?= site_url('reports/fast-moving') ?>">
            <label for="date_from">Date From</label>
            <input id="date_from" type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>">
            <label for="date_to">Date To</label>
            <input id="date_to" type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>">
            <label for="limit">Limit</label>
            <input id="limit" type="number" min="1" name="limit" value="<?= esc((string) ($limit ?? 20)) ?>">
            <button type="submit" class="btn btn-outline">Apply</button>
        </form>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Unit</th>
                        <th>Total Qty Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($rows ?? []) === []): ?>
                        <tr><td colspan="3" class="empty-state">No records found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= esc((string) $row['item_name']) ?></td>
                                <td><?= esc((string) $row['unit']) ?></td>
                                <td><?= esc((string) $row['total_qty_out']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
