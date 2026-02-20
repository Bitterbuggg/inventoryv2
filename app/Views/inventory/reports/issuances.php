<?php

declare(strict_types=1);

$title = 'Issuance Report - InventoryV2';
$pageTitle = 'Report: Issuances';
$pageSubtitle = 'Issuance totals and status performance over time.';
$crumbs = [
    ['label' => 'Reports'],
    ['label' => 'Issuances'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
<a class="btn btn-outline" href="<?= site_url('reports/low-stock') ?>">Low Stock</a>
<a class="btn btn-outline" href="<?= site_url('reports/fast-moving') ?>">Fast Moving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$issuanceRows = $rows ?? [];
$totalRows = count($issuanceRows);
$totalRequested = array_sum(array_map(static fn (array $row): float => (float) ($row['total_requested_qty'] ?? 0), $issuanceRows));
$totalIssued = array_sum(array_map(static fn (array $row): float => (float) ($row['total_issued_qty'] ?? 0), $issuanceRows));
$releasedCount = count(array_filter($issuanceRows, static fn (array $row): bool => ($row['status'] ?? '') === 'released'));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Issuance Records</p>
                <p class="kpi-value"><?= esc((string) $totalRows) ?></p>
                <p class="kpi-note">Rows in current report filter.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Requested</p>
                <p class="kpi-value"><?= esc(number_format($totalRequested, 2)) ?></p>
                <p class="kpi-note">Requested quantity aggregate.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Issued</p>
                <p class="kpi-value"><?= esc(number_format($totalIssued, 2)) ?></p>
                <p class="kpi-note">Actual released quantity aggregate.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Released Records</p>
                <p class="kpi-value"><?= esc((string) $releasedCount) ?></p>
                <p class="kpi-note">Completed issuance transactions.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <form class="inline-form" method="get" action="<?= site_url('reports/issuances') ?>">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">All</option>
                <?php foreach (['draft', 'submitted', 'approved', 'rejected', 'released', 'cancelled'] as $opt): ?>
                    <option value="<?= esc($opt) ?>" <?= (($status ?? '') === $opt) ? 'selected' : '' ?>><?= esc($opt) ?></option>
                <?php endforeach ?>
            </select>
            <label for="date_from">Date From</label>
            <input id="date_from" type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>">
            <label for="date_to">Date To</label>
            <input id="date_to" type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>">
            <button type="submit" class="btn btn-outline">Apply</button>
        </form>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Issuance #</th>
                        <th>Requestor</th>
                        <th>Issue Date</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Total Requested</th>
                        <th>Total Issued</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($rows ?? []) === []): ?>
                        <tr><td colspan="8" class="empty-state">No records found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= esc((string) $row['id']) ?></td>
                                <td><?= esc((string) $row['issuance_number']) ?></td>
                                <td><?= esc((string) $row['requestor_id']) ?></td>
                                <td><?= esc((string) $row['issue_date']) ?></td>
                                <td><?= esc((string) ($row['department'] ?? '')) ?></td>
                                <td><?= view('components/shared/table_status_badge', ['status' => $row['status'] ?? 'unknown']) ?></td>
                                <td><?= esc((string) $row['total_requested_qty']) ?></td>
                                <td><?= esc((string) $row['total_issued_qty']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
