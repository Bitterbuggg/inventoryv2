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
<?php
$movementRows = $rows ?? [];
$totalMovements = count($movementRows);
$totalIn = array_sum(array_map(static fn (array $row): float => (float) ($row['qty_in'] ?? 0), $movementRows));
$totalOut = array_sum(array_map(static fn (array $row): float => (float) ($row['qty_out'] ?? 0), $movementRows));
$distinctItems = count(array_unique(array_map(static fn (array $row): string => (string) ($row['item_name'] ?? ''), $movementRows)));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Movement Rows</p>
                <p class="kpi-value"><?= esc((string) $totalMovements) ?></p>
                <p class="kpi-note">Records returned by active filters.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Qty In</p>
                <p class="kpi-value"><?= esc(number_format($totalIn, 2)) ?></p>
                <p class="kpi-note">Inbound stock movement sum.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Qty Out</p>
                <p class="kpi-value"><?= esc(number_format($totalOut, 2)) ?></p>
                <p class="kpi-note">Outbound stock movement sum.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Distinct Items</p>
                <p class="kpi-value"><?= esc((string) $distinctItems) ?></p>
                <p class="kpi-note">Unique item names in this range.</p>
            </article>
        </div>
    </section>

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
</div>
<?= $this->endSection() ?>
