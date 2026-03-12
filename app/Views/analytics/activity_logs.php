<?php

declare(strict_types=1);

$title = 'Activity Logs - InventoryV2';
$pageTitle = 'Activity Logs';
$pageSubtitle = 'Unified analytics dashboard for overview, event logs, and metrics.';
$crumbs = [
    ['label' => 'Analytics'],
    ['label' => 'Activity Logs'],
];

$overview = $summary ?? [];
$periodDays = (int) ($overview['period_days'] ?? $overview_days ?? 7);
$moduleTotals = $overview['module_totals'] ?? [];
$topEvents = $overview['top_events'] ?? [];
$topRoutes = $overview['top_routes'] ?? [];
$recentEvents = $overview['recent_events'] ?? [];

$eventRows = $event_rows ?? [];
$eventsShown = count($eventRows);
$authEvents = count(array_filter($eventRows, static fn (array $row): bool => (string) ($row['module'] ?? '') === 'auth'));
$procurementEvents = count(array_filter($eventRows, static fn (array $row): bool => (string) ($row['module'] ?? '') === 'procurement'));
$inventoryEvents = count(array_filter($eventRows, static fn (array $row): bool => in_array((string) ($row['module'] ?? ''), ['inventory', 'receiving'], true)));

$trendRows = $trends ?? [];
$metricRows = $metrics ?? [];
$trendTotal = array_sum(array_map(static fn (array $row): int => (int) ($row['total'] ?? 0), $trendRows));
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    .activity-events-table {
        table-layout: fixed;
        min-width: 1160px;
        width: 100%;
    }

    .activity-events-table td {
        word-break: break-word;
    }

    .activity-event-name {
        font-family: var(--font-mono);
        color: var(--color-brand-700);
        font-size: 0.85rem;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .activity-route {
        color: var(--color-text-muted);
        font-size: 0.85rem;
        word-break: break-all;
    }

    .activity-meta-details {
        min-width: 0;
    }

    .activity-meta-details > summary {
        cursor: pointer;
        list-style: none;
        color: var(--color-brand-700);
        font-size: 0.8rem;
        font-weight: 600;
        user-select: none;
    }

    .activity-meta-details > summary::-webkit-details-marker {
        display: none;
    }

    .activity-meta-details > summary::before {
        content: '▸';
        display: inline-block;
        margin-right: 6px;
        transition: transform 0.15s ease;
    }

    .activity-meta-details[open] > summary::before {
        transform: rotate(90deg);
    }

    .activity-meta-json {
        margin-top: 6px;
        max-width: 300px;
        max-height: 220px;
        overflow: auto;
        font-family: var(--font-mono);
        font-size: 0.75rem;
        color: var(--color-text-muted);
        background: var(--color-surface-alt);
        border: 1px solid var(--color-border);
        border-radius: 6px;
        padding: 6px 8px;
        line-height: 1.3;
        white-space: pre;
    }

    .activity-timestamp {
        white-space: nowrap;
        font-size: 0.85rem;
    }

    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li { display: block; }
    .ci-pager li a, .ci-pager li span { display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; font-size: 0.85rem; min-width: 32px; border: 1px solid var(--color-border-strong); border-radius: var(--radius-sm); background: var(--color-surface); color: var(--color-brand-700); text-decoration: none; font-weight: 600; transition: all 0.2s ease; }
    .ci-pager li a:hover { background: var(--color-brand-100); border-color: var(--color-brand-500); }
    .ci-pager li.active a { background: var(--color-brand-500); color: #ffffff; border-color: var(--color-brand-600); }
    .ci-pager li.disabled a { opacity: 0.5; background: var(--color-surface-alt); color: var(--color-text-muted); pointer-events: none; border-color: var(--color-border); }
    .ci-pager li span.ellipsis { border: none !important; background: transparent !important; padding: 0 4px !important; min-width: auto; color: var(--color-text-muted); }

    /* Tab navigation */
    .section-tabs { display: flex; gap: 0; border-bottom: 2px solid var(--color-border); padding: 0 16px; background: var(--color-surface); border-radius: var(--radius-sm) var(--radius-sm) 0 0; }
    .section-tab { padding: 12px 24px; font-size: 0.9rem; font-weight: 600; color: var(--color-text-muted); background: none; border: none; border-bottom: 3px solid transparent; margin-bottom: -2px; cursor: pointer; transition: color 0.2s, border-color 0.2s; white-space: nowrap; }
    .section-tab:hover { color: var(--color-brand-700); }
    .section-tab.active { color: var(--color-brand-700); border-bottom-color: var(--color-brand-500); }
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* Export dropdown */
    .export-menu { position: relative; display: inline-block; }
    .export-menu-items { display: none; position: absolute; right: 0; top: calc(100% + 4px); min-width: 220px; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); box-shadow: 0 4px 16px rgba(0,0,0,0.12); z-index: 100; padding: 4px 0; }
    .export-menu.open .export-menu-items { display: block; }
    .export-menu-items a { display: block; padding: 8px 16px; font-size: 0.85rem; color: var(--color-text); text-decoration: none; transition: background 0.15s; }
    .export-menu-items a:hover { background: var(--color-brand-50); }

    /* Collapsible filter panel */
    .filter-panel > summary { cursor: pointer; list-style: none; display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 600; color: var(--color-brand-700); user-select: none; padding: 6px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); background: var(--color-surface); transition: all 0.2s; }
    .filter-panel > summary:hover { background: var(--color-brand-50); border-color: var(--color-brand-500); }
    .filter-panel > summary::-webkit-details-marker { display: none; }
    .filter-panel > summary::after { content: ' \25BE'; font-size: 0.7rem; }
    .filter-panel[open] > summary::after { content: ' \25B4'; }
    .filter-panel .filter-body { margin-top: 12px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $overviewExportQuery = http_build_query(['export' => 'csv', 'dataset' => 'overview', 'overview_days' => ($overview_days ?? 7)]); ?>
<?php $eventsExportQuery = http_build_query(['export' => 'csv', 'dataset' => 'events', 'event_name' => ($event_filters['event_name'] ?? ''), 'event_module' => ($event_filters['module'] ?? ''), 'event_actor_id' => ($event_filters['actor_id'] ?? ''), 'event_date_from' => ($event_filters['date_from'] ?? ''), 'event_date_to' => ($event_filters['date_to'] ?? ''), 'event_limit' => ($event_limit ?? 500)]); ?>
<?php $metricsTrendsExportQuery = http_build_query(['export' => 'csv', 'dataset' => 'trends', 'metric_date_from' => ($metric_date_from ?? ''), 'metric_date_to' => ($metric_date_to ?? ''), 'metric_module' => ($metric_module ?? '')]); ?>
<?php $metricsDailyExportQuery = http_build_query(['export' => 'csv', 'dataset' => 'metrics', 'metric_date_from' => ($metric_date_from ?? ''), 'metric_date_to' => ($metric_date_to ?? ''), 'metric_module' => ($metric_module ?? '')]); ?>
<div class="export-menu" id="export-menu">
    <button class="btn btn-outline" type="button" onclick="document.getElementById('export-menu').classList.toggle('open')">Export CSV &#9662;</button>
    <div class="export-menu-items">
        <a href="<?= site_url('analytics/activity-logs') . '?' . $overviewExportQuery ?>">Overview</a>
        <a href="<?= site_url('analytics/activity-logs') . '?' . $eventsExportQuery ?>">Event Logs</a>
        <a href="<?= site_url('analytics/activity-logs') . '?' . $metricsTrendsExportQuery ?>">Trends</a>
        <a href="<?= site_url('analytics/activity-logs') . '?' . $metricsDailyExportQuery ?>">Daily Metrics</a>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    <nav class="section-tabs" role="tablist">
        <button class="section-tab active" data-tab="overview" role="tab">Overview</button>
        <button class="section-tab" data-tab="events" role="tab">Event Logs</button>
        <button class="section-tab" data-tab="metrics" role="tab">Metrics</button>
    </nav>

    <div class="tab-panel active" data-tab="overview">
    <section id="overview" class="card stack-md">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div class="stack-sm">
                <h2>Overview</h2>
                <p class="muted">Review operational trends and activity volume.</p>
            </div>
            <form class="inline-form" method="get" action="<?= site_url('analytics/activity-logs') ?>#overview">
                <label for="overview_days" style="font-size: 0.85rem; color: var(--color-text-muted);">Period (days)</label>
                <input id="overview_days" type="number" min="1" max="30" name="overview_days" value="<?= esc((string) ($overview_days ?? 7)) ?>" style="width: 80px;">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a class="btn btn-outline" href="<?= site_url('analytics/activity-logs') ?>#overview">Reset</a>
            </form>
        </div>

        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Total Events</p>
                <p class="kpi-value"><?= esc((string) ($overview['total_events'] ?? 0)) ?></p>
                <p class="kpi-note">All tracked events to date.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Events Today</p>
                <p class="kpi-value"><?= esc((string) ($overview['events_today'] ?? 0)) ?></p>
                <p class="kpi-note">Events created today.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Last <?= esc((string) $periodDays) ?> Days</p>
                <p class="kpi-value"><?= esc((string) ($overview['events_last_period'] ?? 0)) ?></p>
                <p class="kpi-note">Window-based activity volume.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Active Modules</p>
                <p class="kpi-value"><?= esc((string) count($moduleTotals)) ?></p>
                <p class="kpi-note">Modules with observed events.</p>
            </article>
        </div>

        <details class="filter-panel">
            <summary>Detailed Breakdown</summary>
            <div class="filter-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--space-4);">
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Module</th><th>Events</th></tr></thead>
                    <tbody>
                        <?php if ($moduleTotals === []): ?>
                            <tr><td colspan="2" class="empty-state">No activity yet.</td></tr>
                        <?php else: ?>
                            <?php foreach (array_slice($moduleTotals, 0, 10) as $row): ?>
                                <tr><td><?= esc((string) ($row['module'] ?? 'unknown')) ?></td><td><?= esc((string) ($row['total'] ?? 0)) ?></td></tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Top Event</th><th>Count</th></tr></thead>
                    <tbody>
                        <?php if ($topEvents === []): ?>
                            <tr><td colspan="2" class="empty-state">No events yet.</td></tr>
                        <?php else: ?>
                            <?php foreach (array_slice($topEvents, 0, 10) as $row): ?>
                                <tr><td><code><?= esc((string) ($row['event_name'] ?? '')) ?></code></td><td><?= esc((string) ($row['total'] ?? 0)) ?></td></tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Top Route</th><th>Count</th></tr></thead>
                    <tbody>
                        <?php if ($topRoutes === []): ?>
                            <tr><td colspan="2" class="empty-state">No route activity yet.</td></tr>
                        <?php else: ?>
                            <?php foreach (array_slice($topRoutes, 0, 10) as $row): ?>
                                <tr><td><?= esc((string) ($row['route'] ?? '')) ?></td><td><?= esc((string) ($row['total'] ?? 0)) ?></td></tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h3>Recent Events</h3>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th><th>Event</th><th>Module</th><th>Actor</th><th>Route</th><th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recentEvents === []): ?>
                        <tr><td colspan="6" class="empty-state">No recent events found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recentEvents as $row): ?>
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
            </div>
        </details>
    </section>
    </div>

    <div class="tab-panel" data-tab="events">
    <section id="events" class="card stack-md">
        <div class="stack-sm">
            <h2>Event Logs</h2>
            <p class="muted">Raw event records for troubleshooting and usage analysis.</p>
        </div>

        <div class="kpi-grid">
            <article class="kpi-card"><p class="kpi-label">Events Found</p><p class="kpi-value"><?= esc((string) $eventsShown) ?></p></article>
            <article class="kpi-card"><p class="kpi-label">Auth</p><p class="kpi-value"><?= esc((string) $authEvents) ?></p></article>
            <article class="kpi-card"><p class="kpi-label">Procurement</p><p class="kpi-value"><?= esc((string) $procurementEvents) ?></p></article>
            <article class="kpi-card"><p class="kpi-label">Inventory/Receiving</p><p class="kpi-value"><?= esc((string) $inventoryEvents) ?></p></article>
        </div>

        <details class="filter-panel">
            <summary>Filters</summary>
            <div class="filter-body">
        <form class="stack-sm" method="get" action="<?= site_url('analytics/activity-logs') ?>#events">
            <div class="form-grid-2">
                <div class="field">
                    <label for="event_name">Event Name</label>
                    <input id="event_name" name="event_name" value="<?= esc((string) ($event_filters['event_name'] ?? '')) ?>">
                </div>
                <div class="field">
                    <label for="event_module">Module</label>
                    <input id="event_module" name="event_module" value="<?= esc((string) ($event_filters['module'] ?? '')) ?>">
                </div>
            </div>
            <div class="form-grid-2">
                <div class="field">
                    <label for="event_actor_id">Actor ID</label>
                    <input id="event_actor_id" name="event_actor_id" value="<?= esc((string) ($event_filters['actor_id'] ?? '')) ?>">
                </div>
                <div class="field">
                    <label for="event_limit">Database Limit</label>
                    <input id="event_limit" type="number" min="1" max="1000" name="event_limit" value="<?= esc((string) ($event_limit ?? 500)) ?>">
                </div>
            </div>
            <div class="form-grid-2">
                <div class="field">
                    <label for="event_date_from">Date From</label>
                    <input id="event_date_from" type="date" name="event_date_from" value="<?= esc((string) ($event_filters['date_from'] ?? '')) ?>">
                </div>
                <div class="field">
                    <label for="event_date_to">Date To</label>
                    <input id="event_date_to" type="date" name="event_date_to" value="<?= esc((string) ($event_filters['date_to'] ?? '')) ?>">
                </div>
            </div>
            <div class="toolbar">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a class="btn btn-outline" href="<?= site_url('analytics/activity-logs') ?>#events">Clear</a>
            </div>
        </form>
            </div>
        </details>

        <div class="table-wrap">
            <table id="events-table" class="table activity-events-table">
                <colgroup>
                    <col style="width: 60px;">
                    <col style="width: 20%;">
                    <col style="width: 10%;">
                    <col style="width: 8%;">
                    <col style="width: 14%;">
                    <col style="width: 20%;">
                    <col style="width: 18%;">
                    <col style="width: 150px;">
                </colgroup>
                <thead>
                    <tr>
                        <th>ID</th><th>Event</th><th>Module</th><th>Actor</th><th>Reference</th><th>Route</th><th>Metadata</th><th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($eventRows === []): ?>
                        <tr><td colspan="8" class="empty-state">No analytics events found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($eventRows as $row): ?>
                            <tr class="event-row">
                                <td><?= esc((string) ($row['id'] ?? '')) ?></td>
                                <td class="activity-event-name"><?= esc((string) ($row['event_name'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['module'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['actor_id'] ?? '')) ?></td>
                                <td><?= esc((string) ($row['reference_type'] ?? '')) ?> <?= esc((string) ($row['reference_id'] ?? '')) ?></td>
                                <td class="activity-route"><?= esc((string) ($row['route'] ?? '')) ?></td>
                                <td>
                                    <?php
                                    $metadataRaw = trim((string) ($row['metadata_json'] ?? ''));
                                    if ($metadataRaw === ''):
                                    ?>
                                        <span class="muted">-</span>
                                    <?php else:
                                        $metadataPretty = $metadataRaw;
                                        $decodedMetadata = json_decode($metadataRaw, true);
                                        if (is_array($decodedMetadata)) {
                                            $metadataPretty = (string) json_encode(
                                                $decodedMetadata,
                                                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                                            );
                                        }
                                    ?>
                                        <details class="activity-meta-details">
                                            <summary>View JSON</summary>
                                            <pre class="activity-meta-json"><?= esc($metadataPretty) ?></pre>
                                        </details>
                                    <?php endif; ?>
                                </td>
                                <td class="activity-timestamp"><?= esc((string) ($row['created_at'] ?? '')) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
            <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                Showing records <span id="events-page-indicator"></span> (Total: <span id="events-total-indicator"><?= esc((string) $eventsShown) ?></span>)
            </p>
            <nav aria-label="Events Pagination">
                <ul class="ci-pager" id="events-pager"></ul>
            </nav>
        </div>
    </section>
    </div>

    <div class="tab-panel" data-tab="metrics">
    <section id="metrics" class="card stack-md">
        <div class="stack-sm">
            <h2>Metrics</h2>
            <p class="muted">Date-based trends and stored daily metric snapshots.</p>
        </div>

        <div class="kpi-grid">
            <article class="kpi-card"><p class="kpi-label">Trend Rows</p><p class="kpi-value"><?= esc((string) count($trendRows)) ?></p></article>
            <article class="kpi-card"><p class="kpi-label">Trend Events Total</p><p class="kpi-value"><?= esc((string) $trendTotal) ?></p></article>
            <article class="kpi-card"><p class="kpi-label">Persisted Metrics</p><p class="kpi-value"><?= esc((string) count($metricRows)) ?></p></article>
            <article class="kpi-card"><p class="kpi-label">Module Filter</p><p class="kpi-value"><?= esc(($metric_module ?? '') === '' ? 'All' : (string) $metric_module) ?></p></article>
        </div>

        <details class="filter-panel">
            <summary>Filters</summary>
            <div class="filter-body">
        <form class="stack-sm" method="get" action="<?= site_url('analytics/activity-logs') ?>#metrics">
            <div class="form-grid-2">
                <div class="field">
                    <label for="metric_date_from">Date From</label>
                    <input id="metric_date_from" type="date" name="metric_date_from" value="<?= esc((string) ($metric_date_from ?? '')) ?>">
                </div>
                <div class="field">
                    <label for="metric_date_to">Date To</label>
                    <input id="metric_date_to" type="date" name="metric_date_to" value="<?= esc((string) ($metric_date_to ?? '')) ?>">
                </div>
            </div>
            <div class="field">
                <label for="metric_module">Module</label>
                <input id="metric_module" type="text" name="metric_module" value="<?= esc((string) ($metric_module ?? '')) ?>" placeholder="optional module filter">
            </div>
            <div class="toolbar">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a class="btn btn-outline" href="<?= site_url('analytics/activity-logs') ?>#metrics">Reset</a>
            </div>
        </form>
            </div>
        </details>

        <h3>Event Trends by Date</h3>
        <div class="table-wrap">
            <table id="trends-table" class="table">
                <thead><tr><th>Date</th><th>Module</th><th>Total Events</th></tr></thead>
                <tbody>
                    <?php if ($trendRows === []): ?>
                        <tr><td colspan="3" class="empty-state">No trend data available.</td></tr>
                    <?php else: ?>
                        <?php foreach ($trendRows as $row): ?>
                            <tr class="trend-row"><td><?= esc((string) ($row['metric_date'] ?? '')) ?></td><td><?= esc((string) ($row['module'] ?? '')) ?></td><td><?= esc((string) ($row['total'] ?? 0)) ?></td></tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
            <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                Showing records <span id="trends-page-indicator"></span> (Total: <span id="trends-total-indicator"><?= esc((string) count($trendRows)) ?></span>)
            </p>
            <nav aria-label="Trends Pagination">
                <ul class="ci-pager" id="trends-pager"></ul>
            </nav>
        </div>
        <h3>Persisted Daily Metrics</h3>
        <div class="table-wrap">
            <table id="metrics-table" class="table">
                <thead><tr><th>Date</th><th>Metric Key</th><th>Module</th><th>Value</th><th>Dimensions</th><th>Created At</th></tr></thead>
                <tbody>
                    <?php if ($metricRows === []): ?>
                        <tr><td colspan="6" class="empty-state">No persisted metrics yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($metricRows as $row): ?>
                            <tr class="metric-row">
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

        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
            <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                Showing records <span id="metrics-page-indicator"></span> (Total: <span id="metrics-total-indicator"><?= esc((string) count($metricRows)) ?></span>)
            </p>
            <nav aria-label="Metrics Pagination">
                <ul class="ci-pager" id="metrics-pager"></ul>
            </nav>
        </div>
    </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function initPagination(tableId, rowClass, pagerId, indicatorId, totalIndicatorId, perPage) {
        const tbody = document.querySelector('#' + tableId + ' tbody');
        if (!tbody || !tbody.querySelector('.' + rowClass)) return;

        const allRows = Array.from(tbody.querySelectorAll('.' + rowClass));
        let currentRows = [...allRows];
        const pagerContainer = document.getElementById(pagerId);
        const pageIndicator = document.getElementById(indicatorId);
        let currentPage = 1;

        function showPage(page) {
            currentPage = page;
            const totalRows = currentRows.length;
            const totalPages = Math.ceil(totalRows / perPage);

            if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

            const startPoint = (currentPage - 1) * perPage;
            const endPoint = startPoint + perPage;

            allRows.forEach(row => row.style.display = 'none');
            currentRows.forEach((row, index) => {
                if (index >= startPoint && index < endPoint) row.style.display = '';
            });

            const actualEnd = Math.min(endPoint, totalRows);
            if (pageIndicator) pageIndicator.innerText = totalRows === 0 ? '0' : `${startPoint + 1} - ${actualEnd}`;

            if (pagerContainer) {
                pagerContainer.innerHTML = '';
                if (totalPages > 1) {
                    let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;

                    let startPg = Math.max(1, currentPage - 2);
                    let endPg = Math.min(totalPages, startPg + 4);
                    if (endPg - startPg < 4) startPg = Math.max(1, endPg - 4);

                    if (startPg > 1) {
                        html += `<li><a href="#" data-page="1">1</a></li>`;
                        if (startPg > 2) html += `<li><span class="ellipsis">...</span></li>`;
                    }

                    for (let i = startPg; i <= endPg; i++) {
                        html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`;
                    }

                    if (endPg < totalPages) {
                        if (endPg < totalPages - 1) html += `<li><span class="ellipsis">...</span></li>`;
                        html += `<li><a href="#" data-page="${totalPages}">${totalPages}</a></li>`;
                    }

                    html += `<li class="${currentPage === totalPages ? 'disabled' : ''}"><a href="#" data-page="${currentPage + 1}">Next &raquo;</a></li>`;
                    pagerContainer.innerHTML = html;
                }
            }
        }

        if (pagerContainer) {
            pagerContainer.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (!link) return;
                e.preventDefault();
                const li = link.parentElement;
                if (li.classList.contains('disabled') || li.classList.contains('active')) return;
                showPage(parseInt(link.getAttribute('data-page')));
            });
        }

        showPage(1);
    }

    initPagination('events-table', 'event-row', 'events-pager', 'events-page-indicator', 'events-total-indicator', 15);
    initPagination('trends-table', 'trend-row', 'trends-pager', 'trends-page-indicator', 'trends-total-indicator', 15);
    initPagination('metrics-table', 'metric-row', 'metrics-pager', 'metrics-page-indicator', 'metrics-total-indicator', 15);

    // Tab navigation
    const tabs = document.querySelectorAll('.section-tab');
    const panels = document.querySelectorAll('.tab-panel');

    function activateTab(tabName) {
        tabs.forEach(t => t.classList.toggle('active', t.getAttribute('data-tab') === tabName));
        panels.forEach(p => p.classList.toggle('active', p.getAttribute('data-tab') === tabName));
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const name = tab.getAttribute('data-tab');
            activateTab(name);
            history.replaceState(null, '', '#' + name);
        });
    });

    // Activate tab from URL hash
    const hash = window.location.hash.replace('#', '');
    if (['overview', 'events', 'metrics'].includes(hash)) activateTab(hash);

    // Close export dropdown on outside click
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('export-menu');
        if (menu && !menu.contains(e.target)) menu.classList.remove('open');
    });
});
</script>
<?= $this->endSection() ?>
