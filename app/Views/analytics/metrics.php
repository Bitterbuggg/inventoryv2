<?php

declare(strict_types=1);

$title = 'Analytics Metrics - InventoryV2';
$pageTitle = 'Analytics Metrics';
$pageSubtitle = 'Date-based trends and stored daily metric snapshots.';
$crumbs = [
    ['label' => 'Analytics', 'url' => site_url('analytics/dashboard')],
    ['label' => 'Metrics'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('analytics/dashboard') ?>">Dashboard</a>
<a class="btn btn-outline" href="<?= site_url('analytics/events') ?>">Events</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$trendRows = $trends ?? [];
$metricRows = $metrics ?? [];
$trendTotal = array_sum(array_map(static fn (array $row): int => (int) ($row['total'] ?? 0), $trendRows));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Trend Days</p>
                <p class="kpi-value"><?= esc((string) count($trendRows)) ?></p>
                <p class="kpi-note">Date-module trend entries returned.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Trend Events Total</p>
                <p class="kpi-value"><?= esc((string) $trendTotal) ?></p>
                <p class="kpi-note">Aggregate of visible trend counts.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Persisted Metrics</p>
                <p class="kpi-value"><?= esc((string) count($metricRows)) ?></p>
                <p class="kpi-note">Saved daily metric records.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Module Filter</p>
                <p class="kpi-value"><?= esc($module === '' ? 'All' : $module) ?></p>
                <p class="kpi-note">Current module scope.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <form class="inline-form" method="get" action="<?= site_url('analytics/metrics') ?>">
            <label for="date_from">Date From</label>
            <input id="date_from" type="date" name="date_from" value="<?= esc((string) $date_from) ?>">
            <label for="date_to">Date To</label>
            <input id="date_to" type="date" name="date_to" value="<?= esc((string) $date_to) ?>">
            <label for="module">Module</label>
            <input id="module" type="text" name="module" value="<?= esc((string) $module) ?>" placeholder="optional module filter">
            <button type="submit" class="btn btn-outline">Apply</button>
        </form>
    </section>

    <section class="card stack-md">
        <h2>Event Trends by Date</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Module</th>
                        <th>Total Events</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($trends ?? []) === []): ?>
                        <tr><td colspan="3" class="empty-state">No trend data available.</td></tr>
                    <?php else: ?>
                        <?php foreach ($trends as $row): ?>
                            <tr>
                                <td><?= esc((string) ($row['metric_date'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['module'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['total'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="card stack-md">
        <h2>Persisted Daily Metrics</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Metric Key</th>
                        <th>Module</th>
                        <th>Value</th>
                        <th>Dimensions</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($metrics ?? []) === []): ?>
                        <tr><td colspan="6" class="empty-state">No persisted metrics yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($metrics as $row): ?>
                            <tr>
                                <td><?= esc((string) ($row['metric_date'] ?? '')) ?></td>
                                <td><code><?= esc((string) ($row['metric_key'] ?? '')) ?></code></td>
                                <td><?= esc((string) ($row['module'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['metric_value'] ?? 0)) ?></td>
                                <td><code><?= esc((string) ($row['dimension_json'] ?? '')) ?></code></td>
                                <td><?= esc((string) ($row['created_at'] ?? '')) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
