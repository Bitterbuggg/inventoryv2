<?php

declare(strict_types=1);

$title = 'Analytics Events - InventoryV2';
$pageTitle = 'Analytics Event Logs';
$pageSubtitle = 'Raw event records for troubleshooting and usage analysis.';
$crumbs = [
    ['label' => 'Analytics', 'url' => site_url('analytics/dashboard')],
    ['label' => 'Events'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('analytics/dashboard') ?>">Dashboard</a>
<a class="btn btn-outline" href="<?= site_url('analytics/metrics') ?>">Metrics</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$eventRows = $rows ?? [];
$eventsShown = count($eventRows);
$authEvents = count(array_filter($eventRows, static fn (array $row): bool => (string) ($row['module'] ?? '') === 'auth'));
$procurementEvents = count(array_filter($eventRows, static fn (array $row): bool => (string) ($row['module'] ?? '') === 'procurement'));
$inventoryEvents = count(array_filter($eventRows, static fn (array $row): bool => in_array((string) ($row['module'] ?? ''), ['inventory', 'receiving'], true)));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Events Shown</p>
                <p class="kpi-value"><?= esc((string) $eventsShown) ?></p>
                <p class="kpi-note">Based on active filters and limit.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Auth</p>
                <p class="kpi-value"><?= esc((string) $authEvents) ?></p>
                <p class="kpi-note">Login, signup, and role events.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Procurement</p>
                <p class="kpi-value"><?= esc((string) $procurementEvents) ?></p>
                <p class="kpi-note">PR, PO, and approval activities.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Inventory/Receiving</p>
                <p class="kpi-value"><?= esc((string) $inventoryEvents) ?></p>
                <p class="kpi-note">Stock, issuance, and receiving flow.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <div class="stack-sm">
            <h2>Filter Events</h2>
            <p class="muted">Narrow down event logs by name, module, actor, date range, and record limit.</p>
        </div>

        <form class="stack-sm" method="get" action="<?= site_url('analytics/events') ?>">
            <div class="form-grid-2">
                <div class="field">
                    <label for="event_name">Event Name</label>
                    <input id="event_name" name="event_name" value="<?= esc((string) ($filters['event_name'] ?? '')) ?>" placeholder="e.g. procurement.pr_submitted">
                </div>
                <div class="field">
                    <label for="module">Module</label>
                    <input id="module" name="module" value="<?= esc((string) ($filters['module'] ?? '')) ?>" placeholder="auth, procurement, receiving...">
                </div>
            </div>

            <div class="form-grid-2">
                <div class="field">
                    <label for="actor_id">Actor ID</label>
                    <input id="actor_id" name="actor_id" value="<?= esc((string) ($filters['actor_id'] ?? '')) ?>">
                </div>
                <div class="field">
                    <label for="limit">Limit</label>
                    <input id="limit" type="number" min="1" max="500" name="limit" value="<?= esc((string) $limit) ?>">
                </div>
            </div>

            <div class="form-grid-2">
                <div class="field">
                    <label for="date_from">Date From</label>
                    <input id="date_from" type="date" name="date_from" value="<?= esc((string) ($filters['date_from'] ?? '')) ?>">
                </div>
                <div class="field">
                    <label for="date_to">Date To</label>
                    <input id="date_to" type="date" name="date_to" value="<?= esc((string) ($filters['date_to'] ?? '')) ?>">
                </div>
            </div>

            <div class="toolbar">
                <button type="submit" class="btn btn-outline">Apply Filters</button>
                <a class="btn btn-outline" href="<?= site_url('analytics/events') ?>">Reset</a>
            </div>
        </form>

        <div class="stack-sm">
            <h2>Event Log</h2>
            <p class="muted">Latest matching events with routing and metadata context.</p>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Event</th>
                        <th>Module</th>
                        <th>Actor</th>
                        <th>Reference</th>
                        <th>Route</th>
                        <th>Method</th>
                        <th>Metadata</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($rows ?? []) === []): ?>
                        <tr><td colspan="9" class="empty-state">No analytics events found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= esc((string) ($row['id'] ?? '')) ?></td>
                                <td><code><?= esc((string) ($row['event_name'] ?? '')) ?></code></td>
                                <td><?= esc((string) ($row['module'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['actor_id'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['reference_type'] ?? '')) ?> <?= esc((string) ($row['reference_id'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['route'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['method'] ?? '')) ?></td>
                                <td><code><?= esc((string) ($row['metadata_json'] ?? '')) ?></code></td>
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

