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

    /* --- NO-SCROLL VIEWPORT WRAPPER --- */
    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        height: calc(100vh - 120px); 
        min-height: 800px;
        overflow-y: auto; /* Since we have two tables, we allow vertical scrolling of the page */
        padding-bottom: 40px;
    }

    /* --- PASTEL KPI CARDS --- */
    .kpi-grid { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 16px; 
        flex-shrink: 0; 
    }
    
    .kpi-card { 
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        padding: 16px 18px; 
        display: flex; 
        align-items: center; 
        gap: 14px; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
    }

    .kpi-icon-box {
        width: 46px; height: 46px; 
        border-radius: 10px; 
        display: flex; align-items: center; justify-content: center; 
        flex-shrink: 0;
    }

    .icon-trends { background: #f1f5f9; color: #475569; }        
    .icon-events { background: #e0f2fe; color: #0284c7; } 
    .icon-persisted { background: #ecfccb; color: #16a34a; }   
    .icon-module { background: #dbeafe; color: #1e3a8a; }   

    .kpi-details { display: flex; flex-direction: column; flex: 1; justify-content: center; min-width: 0; }
    
    .kpi-value { font-size: 1.15rem; font-weight: 800; color: var(--v2-title); line-height: 1.2; margin: 0; }
    .kpi-label { font-size: 0.75rem; font-weight: 500; color: var(--v2-text-muted); margin: 0; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.05em; }

    /* --- V2 TABLE CARD & TOOLBAR --- */
    .table-card {
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
        overflow: hidden;
        margin-bottom: 10px;
    }

    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 14px 20px; 
        border-bottom: 1px solid var(--v2-border); 
        background: #ffffff; 
        flex-shrink: 0;
    }
    
    .table-toolbar h3 { margin: 0; font-size: 1.05rem; color: var(--v2-title); font-weight: 800; }
    
    .toolbar-controls { 
        display: flex; 
        gap: 10px; 
        align-items: center; 
    }

    .input-v2 { 
        padding: 6px 12px; 
        font-size: 0.85rem; 
        border: 1px solid var(--v2-border); 
        border-radius: 6px; 
        outline: none; 
        color: var(--v2-text-main);
        background: #ffffff;
        height: 34px;
    }
    .input-v2:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }

    /* Tables */
    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .modern-table th { 
        position: sticky; top: 0; z-index: 10;
        background: #ffffff !important; 
        padding: 12px 16px; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        font-weight: 800; 
        color: var(--v2-title); 
        border-bottom: 2px solid var(--v2-border); 
        text-align: left; 
        letter-spacing: 0.05em; 
    }
    .modern-table td { padding: 12px 16px; font-size: 0.85rem; color: var(--v2-text-main); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .modern-table tr:hover td { background: #f8fafc; }

    code { font-family: var(--font-mono); background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: var(--v2-label); font-size: 0.8rem; font-weight: 600; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $metricsTrendsExportQuery = http_build_query(['export' => 'csv', 'dataset' => 'trends', 'date_from' => ($date_from ?? ''), 'date_to' => ($date_to ?? ''), 'module' => ($module ?? '')]); ?>
<?php $metricsDailyExportQuery = http_build_query(['export' => 'csv', 'dataset' => 'metrics', 'date_from' => ($date_from ?? ''), 'date_to' => ($date_to ?? ''), 'module' => ($module ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('analytics/metrics') . '?' . $metricsTrendsExportQuery ?>" data-filtered-csv-export data-export-table="#trends-table" data-export-row-selector=".trend-row" data-export-filename="analytics_trends.csv" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export Trends CSV</a>
<a class="btn btn-outline" href="<?= site_url('analytics/metrics') . '?' . $metricsDailyExportQuery ?>" data-filtered-csv-export data-export-table="#metrics-table" data-export-row-selector=".metric-row" data-export-filename="analytics_daily_metrics.csv" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export Daily CSV</a>
<a class="btn btn-outline" href="<?= site_url('analytics/dashboard') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Dashboard</a>
<a class="btn btn-outline" href="<?= site_url('analytics/events') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Events</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$trendRows = $trends ?? [];
$metricRows = $metrics ?? [];
$trendTotal = array_sum(array_map(static fn (array $row): int => (int) ($row['total'] ?? 0), $trendRows));
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Analytics Metrics</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-trends"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) count($trendRows)) ?></span>
                    <span class="kpi-label">Trend Entries</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-events"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) $trendTotal) ?></span>
                    <span class="kpi-label">Total Events</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-persisted"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) count($metricRows)) ?></span>
                    <span class="kpi-label">Metric Records</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-violet">
                <div class="kpi-icon-box icon-module"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc($module === '' ? 'All' : $module) ?></span>
                    <span class="kpi-label">Active Filter</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card" style="flex: none; overflow: visible;">
        <div class="table-toolbar">
            <h3>Filter Scope</h3>
            <form method="get" action="<?= site_url('analytics/metrics') ?>" class="toolbar-controls">
                <input type="date" name="date_from" value="<?= esc((string) $date_from) ?>" class="input-v2" title="From Date">
                <input type="date" name="date_to" value="<?= esc((string) $date_to) ?>" class="input-v2" title="To Date">
                <input type="text" name="module" value="<?= esc((string) $module) ?>" placeholder="Filter by module" class="input-v2" style="width: 180px;">
                <button type="submit" class="btn btn-primary" style="padding: 6px 16px; font-weight: 800; font-size: 0.8rem; background: var(--v2-label); border: none; border-radius: 6px; color: white;">Apply Filter</button>
                <a href="<?= site_url('analytics/metrics') ?>" class="btn btn-outline" style="padding: 6px 16px; font-weight: 800; font-size: 0.8rem; border-radius: 6px; text-decoration: none;">Reset</a>
            </form>
        </div>
    </div>

    <div class="table-card" style="max-height: 400px;">
        <div class="table-toolbar">
            <h3>Event Trends by Date</h3>
        </div>
        <div class="table-scroll-container">
            <table class="modern-table" id="trends-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Module</th>
                        <th style="text-align: right;">Total Events</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($trends ?? []) === []): ?>
                        <tr><td colspan="3" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">No trend data available.</td></tr>
                    <?php else: ?>
                        <?php foreach ($trends as $row): ?>
                            <tr class="trend-row">
                                <td style="font-weight: 700;"><?= esc((string) ($row['metric_date'] ?? '')) ?></td>
                                <td style="font-weight: 600; color: var(--v2-label);"><?= esc((string) ($row['module'] ?? '')) ?></td>
                                <td style="text-align: right; font-weight: 800; color: var(--v2-title);"><?= esc((string) ($row['total'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-card" style="max-height: 500px;">
        <div class="table-toolbar">
            <h3>Persisted Daily Metrics</h3>
        </div>
        <div class="table-scroll-container">
            <table class="modern-table" id="metrics-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Metric Key</th>
                        <th>Module</th>
                        <th style="text-align: right;">Value</th>
                        <th>Dimensions</th>
                        <th style="text-align: right;">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($metrics ?? []) === []): ?>
                        <tr><td colspan="6" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">No persisted metrics yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($metrics as $row): ?>
                            <tr class="metric-row">
                                <td style="font-weight: 700; white-space: nowrap;"><?= esc((string) ($row['metric_date'] ?? '')) ?></td>
                                <td><code><?= esc((string) ($row['metric_key'] ?? '')) ?></code></td>
                                <td style="font-weight: 600; color: var(--v2-label);"><?= esc((string) ($row['module'] ?? '')) ?></td>
                                <td style="text-align: right; font-weight: 800; color: var(--v2-title);"><?= esc((string) ($row['metric_value'] ?? 0)) ?></td>
                                <td><code><?= esc((string) ($row['dimension_json'] ?? '')) ?></code></td>
                                <td style="text-align: right; font-size: 0.75rem; color: var(--v2-text-muted);"><?= esc((string) ($row['created_at'] ?? '')) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
