<?php

declare(strict_types=1);

$title = 'Analytics Dashboard - InventoryV2';
$pageTitle = 'Analytics Dashboard';
$pageSubtitle = 'Operational telemetry summary for the selected period.';
$crumbs = [
    ['label' => 'Analytics'],
    ['label' => 'Dashboard'],
];
$periodDays = (int) ($summary['period_days'] ?? 7);
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('analytics/events') ?>">Event Logs</a>
<a class="btn btn-outline" href="<?= site_url('analytics/metrics') ?>">Metrics</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    <section class="card stack-md">
        <form class="inline-form" method="get" action="<?= site_url('analytics/dashboard') ?>">
            <label for="days">Period (days)</label>
            <input id="days" type="number" min="1" max="30" name="days" value="<?= esc((string) $days) ?>">
            <button type="submit" class="btn btn-outline">Refresh</button>
        </form>

        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Total Events</p>
                <p class="kpi-value"><?= esc((string) ($summary['total_events'] ?? 0)) ?></p>
                <p class="kpi-note">All tracked events to date.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Events Today</p>
                <p class="kpi-value"><?= esc((string) ($summary['events_today'] ?? 0)) ?></p>
                <p class="kpi-note">Events created today.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Last <?= esc((string) $periodDays) ?> Day(s)</p>
                <p class="kpi-value"><?= esc((string) ($summary['events_last_period'] ?? 0)) ?></p>
                <p class="kpi-note">Window-based activity volume.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Active Modules</p>
                <p class="kpi-value"><?= esc((string) count($module_totals ?? [])) ?></p>
                <p class="kpi-note">Modules with observed events.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <h2>Module Distribution</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Events</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($module_totals ?? []) === []): ?>
                        <tr><td colspan="2" class="empty-state">No module activity yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($module_totals as $row): ?>
                            <tr>
                                <td><?= esc((string) ($row['module'] ?? 'unknown')) ?></td>
                                <td><?= esc((string) ($row['total'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="card stack-md">
        <h2>Top Events (Last <?= esc((string) $periodDays) ?> Day(s))</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($top_events ?? []) === []): ?>
                        <tr><td colspan="2" class="empty-state">No events yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($top_events as $row): ?>
                            <tr>
                                <td><code><?= esc((string) ($row['event_name'] ?? '')) ?></code></td>
                                <td><?= esc((string) ($row['total'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="card stack-md">
        <h2>Top Routes (Last <?= esc((string) $periodDays) ?> Day(s))</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Route</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($top_routes ?? []) === []): ?>
                        <tr><td colspan="2" class="empty-state">No route activity yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($top_routes as $row): ?>
                            <tr>
                                <td><code><?= esc((string) ($row['route'] ?? '')) ?></code></td>
                                <td><?= esc((string) ($row['total'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="card stack-md">
        <h2>Recent Events</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Event</th>
                        <th>Module</th>
                        <th>Actor</th>
                        <th>Route</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($recent_events ?? []) === []): ?>
                        <tr><td colspan="6" class="empty-state">No recent events found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recent_events as $row): ?>
                            <tr>
                                <td><?= esc((string) ($row['id'] ?? '')) ?></td>
                                <td><code><?= esc((string) ($row['event_name'] ?? '')) ?></code></td>
                                <td><?= esc((string) ($row['module'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['actor_id'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['route'] ?? '')) ?></td>
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
