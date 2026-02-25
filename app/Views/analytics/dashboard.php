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

    /* Disabled State */
    .ci-pager li.disabled a {
        opacity: 0.5;
        background: var(--color-surface-alt);
        color: var(--color-text-muted);
        pointer-events: none;
        border-color: var(--color-border);
    }
    
    /* Strips out the double-border bug from CodeIgniter's internal tags */
    .ci-pager li span.ellipsis {
        border: none !important;
        background: transparent !important;
        padding: 0 4px !important;
        min-width: auto;
        color: var(--color-text-muted);
    }

    /* --- SORTABLE TABLE HEADERS --- */
    th.sortable {
        cursor: pointer;
        position: relative;
        padding-right: 18px !important;
        user-select: none;
        transition: background 0.2s ease;
    }
    th.sortable:hover {
        background: rgba(0, 0, 0, 0.03) !important;
    }
    th.sortable::after {
        content: '↕';
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.75rem;
        opacity: 0.3;
    }
    th.sortable.asc::after {
        content: '↑';
        opacity: 1;
        color: var(--color-brand-600);
        font-weight: bold;
    }
    th.sortable.desc::after {
        content: '↓';
        opacity: 1;
        color: var(--color-brand-600);
        font-weight: bold;
    }
</style>
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
                <table class="table" id="recent-events-table" style="table-layout: fixed; width: 100%; min-width: 900px;">
                    <colgroup>
                        <col style="width: 60px;">  <col style="width: 25%;">   <col style="width: 15%;">   <col style="width: 10%;">   <col style="width: 30%;">   <col style="width: 160px;"> </colgroup>
                    <thead>
                        <tr>
                            <th class="sortable" data-col="0">ID</th>
                            <th class="sortable" data-col="1">Event</th>
                            <th class="sortable" data-col="2">Module</th>
                            <th class="sortable" data-col="3">Actor</th>
                            <th class="sortable" data-col="4">Route</th>
                            <th class="sortable" data-col="5">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (($recent_events ?? []) === []): ?>
                            <tr><td colspan="6" class="empty-state">No recent events found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recent_events as $row): ?>
                                <tr class="event-row" style="display: none;">
                                    <td><?= esc((string) ($row['id'] ?? '')) ?></td>
                                    <td style="font-family: var(--font-mono); color: var(--color-brand-700); font-size: 0.85rem; word-break: break-all;"><?= esc((string) ($row['event_name'] ?? '')) ?></td>
                                    <td><?= esc((string) ($row['module'] ?? '')) ?></td>
                                    <td><strong><?= esc((string) ($row['actor_id'] ?? '')) ?></strong></td>
                                    <td style="color: var(--color-text-muted); font-size: 0.85rem; word-break: break-all;"><?= esc((string) ($row['route'] ?? '')) ?></td>
                                    <td style="white-space: nowrap;"><?= esc((string) ($row['created_at'] ?? '')) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid var(--color-border);">
                <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                    Showing records <span id="page-indicator"></span> (Total: <?= esc((string) ($total_events ?? 0)) ?>)
                </p>
                
                <nav aria-label="Recent Events Pagination">
                    <ul class="ci-pager" id="client-pager"></ul>
                </nav>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 10; // Keep dashboard compact with 10 rows
        const tbody = document.querySelector('#recent-events-table tbody');
        if (!tbody) return;

        // Convert to Array so we can sort
        let rowsArray = Array.from(tbody.querySelectorAll('.event-row'));
        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalRows = rowsArray.length;
        
        if (totalRows === 0) return;

        const totalPages = Math.ceil(totalRows / rowsPerPage);
        let currentPage = 1;

        // ==========================================
        // 1. PAGINATION LOGIC
        // ==========================================
        function showPage(page) {
            currentPage = page;
            const startPoint = (page - 1) * rowsPerPage;
            const endPoint = startPoint + rowsPerPage;

            rowsArray.forEach((row, index) => {
                row.style.display = (index >= startPoint && index < endPoint) ? '' : 'none';
            });

            const actualEnd = Math.min(endPoint, totalRows);
            if (pageIndicator) pageIndicator.innerText = `${startPoint + 1} - ${actualEnd}`;

            buildPaginationButtons();
        }

        function buildPaginationButtons() {
            if (!pagerContainer) return;
            pagerContainer.innerHTML = '';
            if (totalPages <= 1) return;

            let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            if (startPage > 1) {
                html += `<li><a href="#" data-page="1">1</a></li>`;
                if (startPage > 2) html += `<li><span class="ellipsis">...</span></li>`;
            }

            for (let i = startPage; i <= endPage; i++) {
                html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) html += `<li><span class="ellipsis">...</span></li>`;
                html += `<li><a href="#" data-page="${totalPages}">${totalPages}</a></li>`;
            }

            html += `<li class="${currentPage === totalPages ? 'disabled' : ''}"><a href="#" data-page="${currentPage + 1}">Next &raquo;</a></li>`;

            pagerContainer.innerHTML = html;
        }

        if (pagerContainer) {
            pagerContainer.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (!link) return;
                e.preventDefault();

                const li = link.parentElement;
                if (li.classList.contains('disabled') || li.classList.contains('active')) return;

                const targetPage = parseInt(link.getAttribute('data-page'));
                showPage(targetPage);
            });
        }

        // ==========================================
        // 2. SORTING LOGIC
        // ==========================================
        document.querySelectorAll('#recent-events-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 
                
                // Reset styling on all headers
                document.querySelectorAll('#recent-events-table th.sortable').forEach(header => {
                    header.classList.remove('asc', 'desc');
                });
                
                th.classList.add(isAsc ? 'desc' : 'asc');
                
                rowsArray.sort((a, b) => {
                    let aText = a.children[colIndex].innerText.trim();
                    let bText = b.children[colIndex].innerText.trim();
                    
                    // ID (0) and Actor (3) are sorted mathematically
                    if (colIndex === 0 || colIndex === 3) {
                        return (parseFloat(aText) - parseFloat(bText)) * direction;
                    }
                    
                    return aText.localeCompare(bText) * direction;
                });
                
                // Re-append sorted rows
                rowsArray.forEach(row => tbody.appendChild(row));
                
                // Go back to page 1
                showPage(1);
            });
        });

        // Initialize table on Page 1
        showPage(1);
    });
</script>
<?= $this->endSection() ?>