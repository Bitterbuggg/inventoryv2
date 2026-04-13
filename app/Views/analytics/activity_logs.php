<?php

declare(strict_types=1);

$legacySource = trim((string) ($legacy_source ?? ''));
$defaultTab = match ($legacySource) {
    'events' => 'events',
    'metrics' => 'metrics',
    default => 'overview',
};
$pageTitle = match ($defaultTab) {
    'events' => 'Event Logs',
    'metrics' => 'Metric Trends',
    default => 'Analytics Dashboard',
};
$title = $pageTitle . ' - InventoryV2';
$pageSubtitle = match ($defaultTab) {
    'events' => 'Filtered operational event audit trail.',
    'metrics' => 'Daily metric trends and persisted analytics snapshots.',
    default => 'Unified analytics dashboard for overview, event logs, and metrics.',
};
$crumbs = [
    ['label' => 'Analytics'],
    ['label' => $pageTitle],
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

$overviewRoute = site_url('analytics/activity-logs');
$eventsRoute = site_url('analytics/events');
$metricsRoute = site_url('analytics/metrics');
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- V2 DESIGN SYSTEM VARIABLES --- */
    :root {
        --v2-border: #b2e0eb; 
        --v2-title: #00476b;  
        --v2-label: #00668c;  
        --v2-active-bg: #00638a; 
        --v2-text-main: #1e3a8a; /* TRUE Dark Blue */
        --v2-text-muted: #64748b;
    }

    /* --- VIEWPORT WRAPPER (Scrollable for Dashboard Tabs) --- */
    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        height: calc(100vh - 120px); 
        min-height: 800px;
        overflow-y: auto;
        padding-bottom: 40px;
    }

    /* --- TABS --- */
    .section-tabs { 
        display: flex; 
        gap: 8px; 
        border-bottom: 1px solid var(--v2-border); 
        background: #ffffff; 
        padding: 0 16px;
        flex-shrink: 0;
    }
    .section-tab { 
        padding: 12px 20px; 
        font-size: 0.85rem; 
        font-weight: 800; 
        color: var(--v2-text-muted); 
        background: none; 
        border: none; 
        border-bottom: 3px solid transparent; 
        cursor: pointer; 
        transition: all 0.2s; 
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .section-tab:hover { color: var(--v2-label); }
    .section-tab.active { color: var(--v2-label); border-bottom-color: var(--v2-label); }
    
    .tab-panel { display: none; flex-direction: column; gap: 20px; }
    .tab-panel.active { display: flex; }

    /* --- KPI CARDS (Fixed Typography) --- */
    .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; flex-shrink: 0; }
    .kpi-card { background: #ffffff; border: 1px solid var(--v2-border); border-radius: 12px; padding: 16px 18px; display: flex; align-items: center; gap: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .kpi-icon-box { width: 46px; height: 46px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    
    .icon-slate { background: #f1f5f9; color: #475569; }        
    .icon-teal { background: #f0fdfa; color: #0d9488; }
    .icon-blue { background: #e0f2fe; color: #0284c7; }   
    .icon-purple { background: #ede9fe; color: #6d28d9; }
    .icon-amber { background: #fffbeb; color: #d97706; }

    .kpi-details { display: flex; flex-direction: column; flex: 1; justify-content: center; min-width: 0; }
    .kpi-value { font-size: 1.25rem; font-weight: 900; color: var(--v2-title); line-height: 1.1; margin: 0; }
    .kpi-label { font-size: 0.7rem; font-weight: 600; color: var(--v2-text-muted); margin: 0; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.05em; }

    /* --- TABLES & TOOLBARS --- */
    .table-card { background: #ffffff; border: 1px solid var(--v2-border); border-radius: 12px; display: flex; flex-direction: column; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden; }
    .table-toolbar { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 14px 20px; border-bottom: 1px solid var(--v2-border); background: #ffffff; flex-shrink: 0; }
    .table-toolbar h3 { margin: 0; font-size: 1rem; color: var(--v2-title); font-weight: 800; }

    .table-scroll-container { overflow: auto; background: #ffffff; }

    /* Fixed Table Layout to prevent overlapping columns */
    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; table-layout: fixed; }
    .modern-table th { 
        position: sticky; top: 0; z-index: 10; 
        background: #ffffff !important; 
        padding: 12px 10px; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        font-weight: 800; 
        color: var(--v2-title); 
        border-bottom: 2px solid var(--v2-border); 
        text-align: left; 
        line-height: 1.3;
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
    }
    .modern-table td {
        padding: 12px 10px;
        font-size: 0.8rem;
        color: var(--v2-text-main);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        line-height: 1.4;
        overflow-wrap: anywhere;
        word-break: break-word;
    }
    .modern-table tr:hover td { background: #f8fafc; }

    /* --- SORTABLE HEADERS (FIXED STICKY BUG) --- */
    /* Removed 'position: relative;' so it inherits sticky from .modern-table th */
    th.sortable { cursor: pointer; padding-right: 20px !important; user-select: none; transition: background 0.2s ease; }
    th.sortable:hover { background: #f1f5f9 !important; }
    th.sortable::after { content: '↕'; position: absolute; right: 4px; top: 50%; transform: translateY(-50%); font-size: 0.7rem; opacity: 0.3; color: var(--v2-title); }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--v2-label); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--v2-label); font-weight: bold; }

    /* --- FORM INPUTS --- */
    .field { display: flex; flex-direction: column; gap: 6px; }
    .field label { font-size: 0.7rem; font-weight: 800; color: var(--v2-text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
    
    .input-v2 { padding: 6px 12px; font-size: 0.85rem; border: 1px solid var(--v2-border); border-radius: 6px; outline: none; color: var(--v2-text-main); background: #ffffff; height: 34px; width: 100%; box-sizing: border-box; }
    .input-v2:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }

    /* --- PAGINATION --- */
    .table-footer { padding: 10px 20px; border-top: 1px solid var(--v2-border); display: flex; justify-content: space-between; align-items: center; background: #ffffff; flex-shrink: 0; }
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li a, .ci-pager li span { display: inline-flex; align-items: center; justify-content: center; padding: 4px 10px; font-size: 0.75rem; min-width: 28px; border: 1px solid var(--v2-border); border-radius: 4px; background: #ffffff; color: var(--v2-label); text-decoration: none; font-weight: 700; transition: all 0.2s ease; }
    .ci-pager li a:hover { background: rgba(178, 224, 235, 0.3); border-color: var(--v2-label); }
    .ci-pager li.active a { background: var(--v2-label); color: #ffffff; border-color: var(--v2-label); }
    .ci-pager li.disabled a { opacity: 0.5; background: #f1f5f9; color: var(--v2-text-muted); pointer-events: none; border-color: #cbd5e1; }
    /* --- EXPORT DROPDOWN --- */
    .export-menu { position: relative; display: inline-block; }
    .export-menu-items { display: none; position: absolute; right: 0; top: 100%; min-width: 220px; background: #ffffff; border: 1px solid var(--v2-border); border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 50; padding: 8px 0; margin-top: 4px; }
    .export-menu.open .export-menu-items { display: block; }
    .export-menu-items a { display: block; padding: 8px 16px; font-size: 0.8rem; font-weight: 600; color: var(--v2-text-main); text-decoration: none; transition: background 0.1s; }
    .export-menu-items a:hover { background: #f1f5f9; color: var(--v2-label); }

    /* JSON Chip */
    .meta-chip {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        color: var(--v2-text-muted);
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
        max-width: 100%;
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
        line-height: 1.35;
        cursor: help;
        border: 1px solid #e2e8f0;
    }
    
    /* Widget Cards */
    .widget-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; }
    .widget-card { background: #ffffff; border: 1px solid var(--v2-border); border-radius: 12px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php 
    $overviewExportQuery = http_build_query(['export' => 'csv', 'dataset' => 'overview', 'overview_days' => ($overview_days ?? 7)]); 
    $eventsExportQuery = http_build_query(['export' => 'csv', 'dataset' => 'events', 'event_limit' => ($event_limit ?? 500)]); 
    $metricsTrendsExportQuery = http_build_query(['export' => 'csv', 'dataset' => 'trends']); 
    $metricsDailyExportQuery = http_build_query(['export' => 'csv', 'dataset' => 'metrics']); 
?>
<div class="export-menu" id="export-menu">
    <button class="btn btn-outline" type="button" onclick="document.getElementById('export-menu').classList.toggle('open')" style="font-weight: 800; font-size: 0.85rem;">Export Dataset &#9662;</button>
    <div class="export-menu-items">
        <a href="<?= $overviewRoute . '?' . $overviewExportQuery ?>">Export Overview Stats</a>
        <a href="<?= $eventsRoute . '?' . $eventsExportQuery ?>">Export Event Logs</a>
        <a href="<?= $metricsRoute . '?' . $metricsTrendsExportQuery ?>">Export Metric Trends</a>
        <a href="<?= $metricsRoute . '?' . $metricsDailyExportQuery ?>">Export Daily Snapshots</a>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="viewport-wrapper">
    
    <nav class="section-tabs" role="tablist">
        <button type="button" class="section-tab<?= $defaultTab === 'overview' ? ' active' : '' ?>" data-tab="overview">Overview</button>
        <button type="button" class="section-tab<?= $defaultTab === 'events' ? ' active' : '' ?>" data-tab="events">Event Audit Trail</button>
        <button type="button" class="section-tab<?= $defaultTab === 'metrics' ? ' active' : '' ?>" data-tab="metrics">Metric Trends</button>
    </nav>

    <div class="tab-panel<?= $defaultTab === 'overview' ? ' active' : '' ?>" data-tab="overview">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-slate"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) ($overview['total_events'] ?? 0)) ?></p>
                    <p class="kpi-label">Total Events</p>
                </div>
            </article>
            <article class="kpi-card kpi-accent-teal">
                <div class="kpi-icon-box icon-teal"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value" style="color: #16a34a;"><?= esc((string) ($overview['events_today'] ?? 0)) ?></p>
                    <p class="kpi-label">Events Today</p>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-blue"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) ($overview['events_last_period'] ?? 0)) ?></p>
                    <p class="kpi-label">Last <?= esc((string) $periodDays) ?> Days</p>
                </div>
            </article>
            <article class="kpi-card kpi-accent-violet">
                <div class="kpi-icon-box icon-purple"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) count($moduleTotals)) ?></p>
                    <p class="kpi-label">Active Modules</p>
                </div>
            </article>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <h3>Time Window Control</h3>
                <form method="get" action="<?= $overviewRoute ?>#overview" style="display: flex; gap: 8px; align-items: center;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: var(--v2-text-muted);">PERIOD (DAYS):</span>
                    <input type="number" name="overview_days" value="<?= esc((string) ($overview_days ?? 7)) ?>" class="input-v2" style="width: 70px; text-align: center;" min="1" max="30">
                    <button type="submit" class="btn btn-primary" style="height: 32px; font-weight: 800; font-size: 0.75rem; border: none; background: var(--v2-label); color: white; border-radius: 6px;">Apply</button>
                </form>
            </div>
        </div>

        <div class="widget-grid">
            <div class="widget-card">
                <h3 style="color: var(--v2-title); font-weight: 800; font-size: 0.9rem; margin: 0 0 12px 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">Module Breakdown</h3>
                <table class="modern-table" style="box-shadow: none; border: none;">
                    <colgroup><col style="width: 70%;"><col style="width: 30%;"></colgroup>
                    <tbody>
                        <?php if ($moduleTotals === []): ?>
                            <tr><td colspan="2" style="text-align:center; padding:20px; color:var(--v2-text-muted);">No activity yet.</td></tr>
                        <?php else: ?>
                            <?php foreach (array_slice($moduleTotals, 0, 8) as $row): ?>
                                <tr>
                                    <td style="font-weight: 800; color: var(--v2-label); text-transform: uppercase; font-size: 0.75rem;"><?= esc((string)$row['module']) ?></td>
                                    <td style="text-align: right; font-weight: 800;"><?= esc((string)$row['total']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            
            <div class="widget-card">
                <h3 style="color: var(--v2-title); font-weight: 800; font-size: 0.9rem; margin: 0 0 12px 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">Top Event Types</h3>
                <table class="modern-table" style="box-shadow: none; border: none;">
                    <colgroup><col style="width: 75%;"><col style="width: 25%;"></colgroup>
                    <tbody>
                        <?php if ($topEvents === []): ?>
                            <tr><td colspan="2" style="text-align:center; padding:20px; color:var(--v2-text-muted);">No events yet.</td></tr>
                        <?php else: ?>
                            <?php foreach (array_slice($topEvents, 0, 8) as $row): ?>
                                <tr>
                                    <td style="font-family: var(--font-mono); font-size: 0.75rem; white-space: normal; word-break: break-all;"><?= esc((string)$row['event_name']) ?></td>
                                    <td style="text-align: right; font-weight: 800;"><?= esc((string)$row['total']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <div class="widget-card">
                <h3 style="color: var(--v2-title); font-weight: 800; font-size: 0.9rem; margin: 0 0 12px 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">Heavy Routes</h3>
                <table class="modern-table" style="box-shadow: none; border: none;">
                    <colgroup><col style="width: 75%;"><col style="width: 25%;"></colgroup>
                    <tbody>
                        <?php if ($topRoutes === []): ?>
                            <tr><td colspan="2" style="text-align:center; padding:20px; color:var(--v2-text-muted);">No routes yet.</td></tr>
                        <?php else: ?>
                            <?php foreach (array_slice($topRoutes, 0, 8) as $row): ?>
                                <tr>
                                    <td style="font-size: 0.75rem; color: var(--v2-text-muted); white-space: normal; word-break: break-all;"><?= esc((string)$row['route']) ?></td>
                                    <td style="text-align: right; font-weight: 800;"><?= esc((string)$row['total']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-panel<?= $defaultTab === 'events' ? ' active' : '' ?>" data-tab="events">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-slate"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="3.01" y2="6"></line></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) $eventsShown) ?></p>
                    <p class="kpi-label">Matches</p>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-blue"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) $authEvents) ?></p>
                    <p class="kpi-label">Auth</p>
                </div>
            </article>
            <article class="kpi-card kpi-accent-teal">
                <div class="kpi-icon-box icon-teal"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) $procurementEvents) ?></p>
                    <p class="kpi-label">Procurement</p>
                </div>
            </article>
            <article class="kpi-card kpi-accent-amber">
                <div class="kpi-icon-box icon-amber"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) $inventoryEvents) ?></p>
                    <p class="kpi-label">Inventory/Recv</p>
                </div>
            </article>
        </div>

        <div class="table-card">
            <div class="table-toolbar" style="border-bottom: none; padding-bottom: 0;">
                <h3>Filter Audit Logs</h3>
            </div>
            
            <form method="get" action="<?= $eventsRoute ?>#events" style="padding: 16px 20px; border-bottom: 1px solid var(--v2-border);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 16px;">
                    <div class="field"><label>Event Name</label><input name="event_name" value="<?= esc((string) ($event_filters['event_name'] ?? '')) ?>" class="input-v2" placeholder="e.g. pr_submitted"></div>
                    <div class="field"><label>Module</label><input name="event_module" value="<?= esc((string) ($event_filters['module'] ?? '')) ?>" class="input-v2" placeholder="e.g. auth"></div>
                    <div class="field"><label>Actor ID</label><input name="event_actor_id" value="<?= esc((string) ($event_filters['actor_id'] ?? '')) ?>" class="input-v2"></div>
                    <div class="field"><label>From</label><input type="date" name="event_date_from" value="<?= esc((string) ($event_filters['date_from'] ?? '')) ?>" class="input-v2"></div>
                    <div class="field"><label>To</label><input type="date" name="event_date_to" value="<?= esc((string) ($event_filters['date_to'] ?? '')) ?>" class="input-v2"></div>
                    <div class="field"><label>Limit</label><input type="number" name="event_limit" value="<?= esc((string) ($event_limit ?? 500)) ?>" class="input-v2" min="1" max="1000"></div>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                    <button type="submit" class="btn btn-primary" style="padding: 6px 20px; font-weight: 800; background: var(--v2-label); border: none; border-radius: 6px; color: white;">Apply Filters</button>
                    <a href="<?= $eventsRoute ?>#events" class="btn btn-outline" style="padding: 6px 20px; font-weight: 800; border-radius: 6px; text-decoration: none;">Reset</a>
                </div>
            </form>
            
            <div class="table-scroll-container" style="max-height: 500px;">
                <table id="events-table" class="modern-table" style="min-width: 1050px;">
                    <colgroup>
                        <col style="width: 60px;">  <col style="width: 20%;">   <col style="width: 80px;">  <col style="width: 80px;">  <col style="width: 12%;">   <col style="width: 15%;">   <col style="width: 15%;">   <col style="width: 140px;"> </colgroup>
                    <thead>
                        <tr>
                            <th class="sortable numeric" data-col="0">ID</th>
                            <th class="sortable" data-col="1">Event</th>
                            <th class="sortable" data-col="2">Module</th>
                            <th class="sortable numeric" data-col="3">Actor</th>
                            <th class="sortable" data-col="4">Ref</th>
                            <th class="sortable" data-col="5">Route</th>
                            <th>Metadata</th>
                            <th class="sortable date" data-col="7">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($eventRows === []): ?>
                            <tr><td colspan="8" style="text-align:center; padding: 40px; color: var(--v2-text-muted);">No events match the current criteria.</td></tr>
                        <?php else: ?>
                            <?php foreach ($eventRows as $row): ?>
                                <tr class="event-row" style="display: none;">
                                    <td style="font-weight: 700; color: #94a3b8;"><?= esc((string)$row['id']) ?></td>
                                    <td style="font-family: var(--font-mono); font-weight: 800; color: var(--v2-label); white-space: normal; word-break: break-all;"><?= esc((string)$row['event_name']) ?></td>
                                    <td style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; color: var(--v2-text-muted);"><?= esc((string)$row['module']) ?></td>
                                    <td style="font-weight: 800;">USR-<?= esc((string)$row['actor_id']) ?></td>
                                    <td style="font-size: 0.75rem; font-weight: 600; color: var(--v2-text-muted);"><?= esc((string)$row['reference_type']) ?> #<?= esc((string)$row['reference_id']) ?></td>
                                    <td style="font-size: 0.75rem; color: var(--v2-text-muted); white-space: normal; word-break: break-all;"><?= esc((string)$row['route']) ?></td>
                                    <td>
                                        <?php
                                            $metadataRaw = trim((string) ($row['metadata_json'] ?? ''));
                                            if ($metadataRaw === '' || $metadataRaw === 'null' || $metadataRaw === '[]'):
                                        ?>
                                            <span style="color: var(--v2-border);">-</span>
                                        <?php else:
                                            $decodedMetadata = json_decode($metadataRaw, true);
                                            $metadataPretty = is_array($decodedMetadata) ? json_encode($decodedMetadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $metadataRaw;
                                        ?>
                                            <span class="meta-chip" title="<?= esc((string)$metadataPretty) ?>"><?= esc((string)$metadataRaw) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="white-space: nowrap; font-weight: 600; font-size: 0.75rem;"><?= esc((string)$row['created_at']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <p style="margin:0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);">Showing <span id="events-page-indicator"></span></p>
                <ul class="ci-pager" id="events-pager"></ul>
            </div>
        </div>
    </div>

    <div class="tab-panel<?= $defaultTab === 'metrics' ? ' active' : '' ?>" data-tab="metrics">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-slate"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) count($trendRows)) ?></p>
                    <p class="kpi-label">Trend Rows</p>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-blue"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) $trendTotal) ?></p>
                    <p class="kpi-label">Total Events</p>
                </div>
            </article>
            <article class="kpi-card kpi-accent-teal">
                <div class="kpi-icon-box icon-teal"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) count($metricRows)) ?></p>
                    <p class="kpi-label">Persisted</p>
                </div>
            </article>
            <article class="kpi-card kpi-accent-violet">
                <div class="kpi-icon-box icon-purple"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value" style="font-size: 1rem;"><?= esc(($metric_module ?? '') === '' ? 'All' : (string)$metric_module) ?></p>
                    <p class="kpi-label">Scope</p>
                </div>
            </article>
        </div>

        <div class="table-card">
            <div class="table-toolbar" style="border-bottom: none; padding-bottom: 0;">
                <h3>Filter Metrics</h3>
            </div>
            
            <form method="get" action="<?= $metricsRoute ?>#metrics" style="padding: 16px 20px; border-bottom: 1px solid var(--v2-border);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px;">
                    <div class="field"><label>Date From</label><input type="date" name="metric_date_from" value="<?= esc((string) ($metric_date_from ?? '')) ?>" class="input-v2"></div>
                    <div class="field"><label>Date To</label><input type="date" name="metric_date_to" value="<?= esc((string) ($metric_date_to ?? '')) ?>" class="input-v2"></div>
                    <div class="field"><label>Module</label><input type="text" name="metric_module" value="<?= esc((string) ($metric_module ?? '')) ?>" class="input-v2" placeholder="optional scope"></div>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                    <button type="submit" class="btn btn-primary" style="padding: 6px 20px; font-weight: 800; background: var(--v2-label); border: none; border-radius: 6px; color: white;">Apply Filters</button>
                    <a href="<?= $metricsRoute ?>#metrics" class="btn btn-outline" style="padding: 6px 20px; font-weight: 800; border-radius: 6px; text-decoration: none;">Reset</a>
                </div>
            </form>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 16px;">
            
            <div class="table-card">
                <div class="table-toolbar"><h3>Event Trends</h3></div>
                <div class="table-scroll-container" style="max-height: 400px;">
                    <table id="trends-table" class="modern-table">
                        <colgroup><col style="width: 35%;"><col style="width: 40%;"><col style="width: 25%;"></colgroup>
                        <thead><tr><th class="sortable date" data-col="0">Date</th><th class="sortable" data-col="1">Module</th><th class="sortable numeric" data-col="2" style="text-align: right;">Total</th></tr></thead>
                        <tbody>
                            <?php if ($trendRows === []): ?>
                                <tr><td colspan="3" style="text-align: center; padding: 30px; color: var(--v2-text-muted);">No trend data available.</td></tr>
                            <?php else: ?>
                                <?php foreach ($trendRows as $row): ?>
                                    <tr class="trend-row" style="display: none;">
                                        <td style="font-weight: 700;"><?= esc((string)$row['metric_date']) ?></td>
                                        <td style="font-weight: 800; text-transform: uppercase; font-size: 0.75rem; color: var(--v2-label);"><?= esc((string)$row['module']) ?></td>
                                        <td style="text-align: right; font-weight: 900; color: var(--v2-title);"><?= esc((string)$row['total']) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-footer"><p style="margin:0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);"><span id="trends-page-indicator"></span></p><ul class="ci-pager" id="trends-pager"></ul></div>
            </div>

            <div class="table-card">
                <div class="table-toolbar"><h3>Persisted Daily Metrics</h3></div>
                <div class="table-scroll-container" style="max-height: 400px;">
                    <table id="metrics-table" class="modern-table">
                        <colgroup><col style="width: 25%;"><col style="width: 30%;"><col style="width: 25%;"><col style="width: 20%;"></colgroup>
                        <thead><tr><th class="sortable date" data-col="0">Date</th><th class="sortable" data-col="1">Key</th><th class="sortable" data-col="2">Module</th><th class="sortable numeric" data-col="3" style="text-align: right;">Value</th></tr></thead>
                        <tbody>
                            <?php if ($metricRows === []): ?>
                                <tr><td colspan="4" style="text-align: center; padding: 30px; color: var(--v2-text-muted);">No metrics found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($metricRows as $row): ?>
                                    <tr class="metric-row" style="display: none;">
                                        <td style="font-weight: 700; font-size: 0.75rem;"><?= esc((string)$row['metric_date']) ?></td>
                                        <td style="font-family: var(--font-mono); font-size: 0.7rem; color: var(--v2-label); word-break: break-all;"><?= esc((string)$row['metric_key']) ?></td>
                                        <td style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; color: var(--v2-text-muted);"><?= esc((string)$row['module']) ?></td>
                                        <td style="text-align: right; font-weight: 900; color: var(--v2-title);"><?= esc((string)$row['metric_value']) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-footer"><p style="margin:0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);"><span id="metrics-page-indicator"></span></p><ul class="ci-pager" id="metrics-pager"></ul></div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const defaultTab = <?= json_encode($defaultTab, JSON_THROW_ON_ERROR) ?>;
    
    // ==========================================
    // MULTI-TABLE MANAGER (ISOLATED SCOPES)
    // ==========================================
    function setupTable(tableId, rowClass, pagerId, indicatorId, perPage) {
        const table = document.getElementById(tableId);
        if (!table) return;
        const tbody = table.querySelector('tbody');
        const allRows = Array.from(tbody.querySelectorAll('.' + rowClass));
        if (allRows.length === 0) return;

        let currentRows = [...allRows];
        let currentPage = 1;
        const pagerContainer = document.getElementById(pagerId);
        const pageIndicator = document.getElementById(indicatorId);

        function showPage(page) {
            currentPage = page;
            const totalRows = currentRows.length;
            const totalPages = Math.ceil(totalRows / perPage);
            if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

            const startPoint = (currentPage - 1) * perPage;
            const endPoint = startPoint + perPage;

            allRows.forEach(r => r.style.display = 'none');
            currentRows.forEach((r, i) => {
                if (i >= startPoint && i < endPoint) r.style.display = '';
            });

            const actualEnd = Math.min(endPoint, totalRows);
            if (pageIndicator) pageIndicator.innerText = `${startPoint + 1} - ${actualEnd} of ${totalRows}`;
            buildPager(totalPages);
        }

        function buildPager(totalPages) {
            if (!pagerContainer) return;
            pagerContainer.innerHTML = '';
            if (totalPages <= 1) return;

            let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;

            for (let i = 1; i <= totalPages; i++) {
                html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`;
            }

            html += `<li class="${currentPage === totalPages ? 'disabled' : ''}"><a href="#" data-page="${currentPage + 1}">Next &raquo;</a></li>`;
            pagerContainer.innerHTML = html;
        }

        if (pagerContainer) {
            pagerContainer.addEventListener('click', e => {
                const link = e.target.closest('a');
                if (!link) return;
                e.preventDefault();
                if (link.parentElement.classList.contains('disabled') || link.parentElement.classList.contains('active')) return;
                showPage(parseInt(link.getAttribute('data-page')));
            });
        }

        // Sorting Logic
        table.querySelectorAll('th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const isNumericCol = th.classList.contains('numeric');
                const isDateCol = th.classList.contains('date');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1;

                table.querySelectorAll('th.sortable').forEach(header => header.classList.remove('asc', 'desc'));
                th.classList.add(isAsc ? 'desc' : 'asc');

                currentRows.sort((a, b) => {
                    let aT = a.children[colIndex].innerText.trim();
                    let bT = b.children[colIndex].innerText.trim();

                    if (isNumericCol) {
                        aT = aT.replace(/[^\d.-]/g, '');
                        bT = bT.replace(/[^\d.-]/g, '');
                        return (parseFloat(aT) - parseFloat(bT)) * direction;
                    }
                    if (isDateCol) {
                        return (new Date(aT).getTime() - new Date(bT).getTime()) * direction;
                    }
                    return aT.localeCompare(bT) * direction;
                });

                currentRows.forEach(row => tbody.appendChild(row));
                showPage(1);
            });
        });

        // Init
        showPage(1);
    }

    // Apply to all 3 tables with individual pagination states
    setupTable('events-table', 'event-row', 'events-pager', 'events-page-indicator', 15);
    setupTable('trends-table', 'trend-row', 'trends-pager', 'trends-page-indicator', 10);
    setupTable('metrics-table', 'metric-row', 'metrics-pager', 'metrics-page-indicator', 10);

    // ==========================================
    // TAB NAVIGATION
    // ==========================================
    const tabs = document.querySelectorAll('.section-tab');
    const panels = document.querySelectorAll('.tab-panel');
    
    function activateTab(name) {
        tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === name));
        panels.forEach(p => p.classList.toggle('active', p.dataset.tab === name));
        history.replaceState(null, '', '#' + name);
    }

    tabs.forEach(t => t.addEventListener('click', () => activateTab(t.dataset.tab)));
    
    const hash = window.location.hash.replace('#', '');
    if (['overview', 'events', 'metrics'].includes(hash)) {
        activateTab(hash);
    } else {
        activateTab(defaultTab);
    }

    // ==========================================
    // EXPORT DROPDOWN TOGGLE
    // ==========================================
    document.addEventListener('click', e => {
        const menu = document.getElementById('export-menu');
        if (menu && !menu.contains(e.target)) menu.classList.remove('open');
    });
});
</script>
<?= $this->endSection() ?>
