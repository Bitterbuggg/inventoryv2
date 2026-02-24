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

<?= $this->section('head') ?>
<?= $this->section('head') ?>
<style>
    /* Force CodeIgniter's default Pager to match your modern button design */
    .ci-pager {
        display: flex;
        gap: 6px;
        list-style: none;
        margin: 0;
        padding: 0;
        align-items: center; /* Perfectly centers the buttons vertically */
    }
    
    .ci-pager li {
        display: block;
    }
    
    /* Target ONLY the outer link, ignoring CodeIgniter's internal spans */
    .ci-pager li a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        font-size: 0.85rem;
        min-width: 32px;
        border: 1px solid var(--color-border-strong);
        border-radius: var(--radius-sm);
        background: var(--color-surface);
        color: var(--color-brand-700);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .ci-pager li a:hover {
        background: var(--color-brand-100);
        border-color: var(--color-brand-500);
    }

    /* Active State */
    .ci-pager li.active a {
        background: var(--color-brand-500);
        color: #ffffff;
        border-color: var(--color-brand-600);
    }

    /* Disabled State (if you ever need it) */
    .ci-pager li.disabled a {
        opacity: 0.5;
        background: var(--color-surface-alt);
        color: var(--color-text-muted);
        pointer-events: none;
        border-color: var(--color-border);
    }
    
    /* Strips out the double-border bug from CodeIgniter's internal tags */
    .ci-pager li a span {
        border: none !important;
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
    }
</style>
<?= $this->endSection() ?>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('analytics/events') ?>">Event Logs</a>
<a class="btn btn-outline" href="<?= site_url('analytics/metrics') ?>">Metrics</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    
    <section class="card stack-md">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div class="stack-sm">
                <h2>Overview & Time Window</h2>
                <p class="muted">Review operational trends and activity volume.</p>
            </div>
            
            <form class="inline-form" method="get" action="<?= site_url('analytics/dashboard') ?>">
                <label for="days" style="font-size: 0.85rem; color: var(--color-text-muted);">Period (days)</label>
                <input id="days" type="number" min="1" max="30" name="days" value="<?= esc((string) $days) ?>" style="width: 80px;">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a class="btn btn-outline" href="<?= site_url('analytics/dashboard') ?>">Reset</a>
            </form>
        </div>

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
                <p class="kpi-label">Last <?= esc((string) $periodDays) ?> Days</p>
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

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--space-4);">
        
        <section class="card stack-md">
            <h3>Module Distribution</h3>
            <div class="table-wrap" style="border: none; box-shadow: none; overflow: hidden;">
                <table class="table" style="min-width: 0; table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 75%;">
                        <col style="width: 25%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th style="background: transparent; border-bottom: 2px solid var(--color-border-strong);">Module</th>
                            <th style="background: transparent; text-align: right; border-bottom: 2px solid var(--color-border-strong);">Events</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (($module_totals ?? []) === []): ?>
                            <tr><td colspan="2" class="empty-state">No activity yet.</td></tr>
                        <?php else: ?>
                            <?php foreach (array_slice($module_totals, 0, 10) as $row): ?>
                                <tr>
                                    <td style="white-space: normal; word-break: break-word;"><strong><?= esc((string) ($row['module'] ?? 'unknown')) ?></strong></td>
                                    <td style="text-align: right;"><?= esc((string) ($row['total'] ?? 0)) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card stack-md">
            <h3>Top Events</h3>
            <div class="table-wrap" style="border: none; box-shadow: none; overflow: hidden;">
                <table class="table" style="min-width: 0; table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 80%;">
                        <col style="width: 20%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th style="background: transparent; border-bottom: 2px solid var(--color-border-strong);">Event Name</th>
                            <th style="background: transparent; text-align: right; border-bottom: 2px solid var(--color-border-strong);">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (($top_events ?? []) === []): ?>
                            <tr><td colspan="2" class="empty-state">No events yet.</td></tr>
                        <?php else: ?>
                            <?php foreach (array_slice($top_events, 0, 10) as $row): ?>
                                <tr>
                                    <td style="white-space: normal; word-break: break-all; font-family: var(--font-mono); color: var(--color-brand-700); font-size: 0.85rem;"><?= esc((string) ($row['event_name'] ?? '')) ?></td>
                                    <td style="text-align: right;"><?= esc((string) ($row['total'] ?? 0)) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card stack-md">
            <h3>Top Routes</h3>
            <div class="table-wrap" style="border: none; box-shadow: none; overflow: hidden;">
                <table class="table" style="min-width: 0; table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 80%;">
                        <col style="width: 20%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th style="background: transparent; border-bottom: 2px solid var(--color-border-strong);">Route</th>
                            <th style="background: transparent; text-align: right; border-bottom: 2px solid var(--color-border-strong);">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (($top_routes ?? []) === []): ?>
                            <tr><td colspan="2" class="empty-state">No route activity yet.</td></tr>
                        <?php else: ?>
                            <?php foreach (array_slice($top_routes, 0, 10) as $row): ?>
                                <tr>
                                    <td style="white-space: normal; word-break: break-all; color: var(--color-text-muted); font-size: 0.85rem;"><?= esc((string) ($row['route'] ?? '')) ?></td>
                                    <td style="text-align: right;"><?= esc((string) ($row['total'] ?? 0)) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </section>
        
    </div>

<section class="card stack-md">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Recent Events</h2>
            <a href="<?= site_url('analytics/events') ?>" class="btn btn-outline" style="font-size: 0.8rem; padding: 4px 10px;">View Full Log &rarr;</a>
        </div>
        
        <div id="recent-events-container">
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
                                    <td style="font-family: var(--font-mono); color: var(--color-brand-700); font-size: 0.85rem;"><?= esc((string) ($row['event_name'] ?? '')) ?></td>
                                    <td><?= esc((string) ($row['module'] ?? '')) ?></td>
                                    <td><strong><?= esc((string) ($row['actor_id'] ?? '')) ?></strong></td>
                                    <td style="color: var(--color-text-muted); font-size: 0.85rem;"><?= esc((string) ($row['route'] ?? '')) ?></td>
                                    <td style="white-space: nowrap;"><?= esc((string) ($row['created_at'] ?? '')) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid var(--color-border);">
                <p class="muted" style="margin: 0; font-size: 0.85rem;">Showing records (Total: <?= esc((string) ($total_events ?? 0)) ?>)</p>
                
                <nav aria-label="Recent Events Pagination" style="display: flex; align-items: center;">
                    <?= str_replace(['<ul class="pagination">'], ['<ul class="ci-pager">'], $pager_links ?? '') ?>
                </nav>
            </div>
        </div>
        </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Listen for clicks anywhere inside the recent-events-container
        const container = document.getElementById('recent-events-container');

        if (container) {
            container.addEventListener('click', function(e) {
                // Check if the user clicked a pagination link
                const pageLink = e.target.closest('.ci-pager a');
                
                if (pageLink) {
                    e.preventDefault(); // Stop the browser from reloading the page
                    const url = pageLink.href;
                    
                    // Add a quick fade effect to show it's loading
                    container.style.opacity = '0.5';
                    container.style.pointerEvents = 'none';

                    // Fetch the next page in the background
                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            // Turn the fetched text into a readable HTML object
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            
                            // Extract just the new table container from the fetched page
                            const newContainer = doc.getElementById('recent-events-container');
                            
                            if (newContainer) {
                                // Swap the old table and buttons with the new ones!
                                container.innerHTML = newContainer.innerHTML;
                            }
                            
                            // Restore full visibility
                            container.style.opacity = '1';
                            container.style.pointerEvents = 'auto';
                        })
                        .catch(err => {
                            console.error('Error fetching page:', err);
                            // Fallback: If AJAX fails, just load the page normally
                            window.location.href = url;
                        });
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>