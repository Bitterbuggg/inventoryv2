<?php

declare(strict_types=1);

$title = 'Activity Logs - InventoryV2';
$pageTitle = 'Activity Logs';
$pageSubtitle = 'Operational telemetry summary for the selected period.';
$crumbs = [
    ['label' => 'Analytics'],
    ['label' => 'Activity Logs'],
];
$periodDays = (int) ($summary['period_days'] ?? 7);
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

    /* --- VIEWPORT WRAPPER (Scrollable for Dashboards) --- */
    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        height: calc(100vh - 120px); 
        min-height: 800px;
        overflow-y: auto;
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

    .icon-total { background: #f1f5f9; color: #475569; }        
    .icon-today { background: #ecfccb; color: #16a34a; } 
    .icon-period { background: #e0f2fe; color: #0284c7; }   
    .icon-modules { background: #dbeafe; color: #1e3a8a; }   

    .kpi-details { display: flex; flex-direction: column; flex: 1; justify-content: center; min-width: 0; }
    
    .kpi-value { font-size: 1.15rem; font-weight: 800; color: var(--v2-title); line-height: 1.2; margin: 0; }
    .kpi-label { font-size: 0.75rem; font-weight: 500; color: var(--v2-text-muted); margin: 0; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.05em; }

    /* --- WIDGET CARDS (For small distribution tables) --- */
    .widget-card {
        background: #ffffff;
        border: 1px solid var(--v2-border);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .widget-card h3 { 
        margin: 0 0 16px 0; 
        font-size: 1rem; 
        color: var(--v2-title); 
        font-weight: 800; 
        display: flex; 
        align-items: center; 
        justify-content: space-between;
    }

    /* --- V2 TABLE CARD --- */
    .table-card {
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
        overflow: hidden;
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

    /* Modern Table Styling */
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
    }
    .modern-table td { padding: 10px 16px; font-size: 0.85rem; color: var(--v2-text-main); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .modern-table tr:hover td { background: #f8fafc; }

    /* Pagination */
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li a, .ci-pager li span { display: inline-flex; align-items: center; justify-content: center; padding: 4px 10px; font-size: 0.75rem; min-width: 28px; border: 1px solid var(--v2-border); border-radius: 4px; background: #ffffff; color: var(--v2-label); text-decoration: none; font-weight: 700; transition: all 0.2s ease; }
    .ci-pager li a:hover { background: rgba(178, 224, 235, 0.3); border-color: var(--v2-label); }
    .ci-pager li.active a { background: var(--v2-label); color: #ffffff; border-color: var(--v2-label); }
    .ci-pager li.disabled a { opacity: 0.5; background: #f1f5f9; color: var(--v2-text-muted); pointer-events: none; border-color: #cbd5e1; }

    /* Form Inputs */
    .input-v2 { padding: 4px 10px; font-size: 0.85rem; border: 1px solid var(--v2-border); border-radius: 6px; outline: none; color: var(--v2-text-main); background: #ffffff; height: 34px; }
    .input-v2:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $dashboardExportQuery = http_build_query(['export' => 'csv', 'days' => ($days ?? 7)]); ?>
<a class="btn btn-outline" href="<?= site_url('analytics/dashboard') . '?' . $dashboardExportQuery ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('analytics/events') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Event Logs</a>
<a class="btn btn-outline" href="<?= site_url('analytics/metrics') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Metrics</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Activity Logs</h2>
        
        <form method="get" action="<?= site_url('analytics/dashboard') ?>" style="display: flex; gap: 8px; align-items: center; margin: 0;">
            <span style="font-size: 0.75rem; font-weight: 800; color: var(--v2-text-muted); text-transform: uppercase;">Window:</span>
            <input type="number" min="1" max="30" name="days" value="<?= esc((string) $days) ?>" class="input-v2" style="width: 70px; text-align: center;">
            <button type="submit" class="btn btn-primary" style="padding: 6px 14px; font-size: 0.8rem; font-weight: 800; border: none; background: var(--v2-label); color: white; border-radius: 6px;">Apply</button>
            <a class="btn btn-outline" href="<?= site_url('analytics/dashboard') ?>" style="padding: 6px 14px; font-size: 0.8rem; font-weight: 800; border-radius: 6px; text-decoration: none;">Reset</a>
        </form>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-total"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) ($summary['total_events'] ?? 0)) ?></span>
                    <span class="kpi-label">Total Events</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-today"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" style="color: #16a34a;"><?= esc((string) ($summary['events_today'] ?? 0)) ?></span>
                    <span class="kpi-label">Events Today</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-period"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" style="color: var(--v2-label);"><?= esc((string) ($summary['events_last_period'] ?? 0)) ?></span>
                    <span class="kpi-label">Last <?= esc((string) $periodDays) ?> Days</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-violet">
                <div class="kpi-icon-box icon-modules"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" style="color: #1E40AF;"><?= esc((string) count($module_totals ?? [])) ?></span>
                    <span class="kpi-label">Active Modules</span>
                </div>
            </article>
        </div>
    </section>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; flex-shrink: 0;">
        
        <div class="widget-card">
            <h3>Module Distribution</h3>
            <table class="modern-table" style="box-shadow: none; border: none;">
                <thead>
                    <tr>
                        <th style="padding-left:0; background:transparent !important; border-bottom: 2px solid #f1f5f9;">Module</th>
                        <th style="text-align: right; padding-right:0; background:transparent !important; border-bottom: 2px solid #f1f5f9;">Events</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($module_totals ?? []) === []): ?>
                        <tr><td colspan="2" style="text-align: center; padding: 20px; color: var(--v2-text-muted);">No activity yet.</td></tr>
                    <?php else: ?>
                        <?php foreach (array_slice($module_totals, 0, 5) as $row): ?>
                            <tr>
                                <td style="padding-left:0; font-weight: 800; color: var(--v2-label);"><?= esc((string) ($row['module'] ?? 'unknown')) ?></td>
                                <td style="text-align: right; padding-right:0; font-weight: 800; color: var(--v2-title);"><?= esc((string) ($row['total'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div class="widget-card">
            <h3>Top Events</h3>
            <table class="modern-table" style="box-shadow: none; border: none;">
                <thead>
                    <tr>
                        <th style="padding-left:0; background:transparent !important; border-bottom: 2px solid #f1f5f9;">Event Name</th>
                        <th style="text-align: right; padding-right:0; background:transparent !important; border-bottom: 2px solid #f1f5f9;">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($top_events ?? []) === []): ?>
                        <tr><td colspan="2" style="text-align: center; padding: 20px; color: var(--v2-text-muted);">No events yet.</td></tr>
                    <?php else: ?>
                        <?php foreach (array_slice($top_events, 0, 5) as $row): ?>
                            <tr>
                                <td style="padding-left:0; font-family: var(--font-mono); color: var(--v2-text-main); font-size: 0.8rem;"><?= esc((string) ($row['event_name'] ?? '')) ?></td>
                                <td style="text-align: right; padding-right:0; font-weight: 800; color: var(--v2-title);"><?= esc((string) ($row['total'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div class="widget-card">
            <h3>Top Routes</h3>
            <table class="modern-table" style="box-shadow: none; border: none;">
                <thead>
                    <tr>
                        <th style="padding-left:0; background:transparent !important; border-bottom: 2px solid #f1f5f9;">Route Path</th>
                        <th style="text-align: right; padding-right:0; background:transparent !important; border-bottom: 2px solid #f1f5f9;">Hits</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($top_routes ?? []) === []): ?>
                        <tr><td colspan="2" style="text-align: center; padding: 20px; color: var(--v2-text-muted);">No route activity yet.</td></tr>
                    <?php else: ?>
                        <?php foreach (array_slice($top_routes, 0, 5) as $row): ?>
                            <tr>
                                <td style="padding-left:0; font-size: 0.8rem; color: var(--v2-text-muted); word-break: break-all;"><?= esc((string) ($row['route'] ?? '')) ?></td>
                                <td style="text-align: right; padding-right:0; font-weight: 800; color: var(--v2-title);"><?= esc((string) ($row['total'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-card">
        <div class="table-toolbar">
            <h3>Recent Operational Events</h3>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 0.75rem; color: var(--v2-text-muted); font-weight: 600;">Full Audit Trail:</span>
                <a href="<?= site_url('analytics/events') ?>" style="font-size: 0.75rem; font-weight: 800; color: var(--v2-label); text-decoration: none;">View Event Logs &rarr;</a>
            </div>
        </div>
        
        <div class="table-scroll-container">
            <table class="modern-table" id="recent-events-table" style="table-layout: fixed; width: 100%;">
                <colgroup>
                    <col style="width: 60px;">
                    <col style="width: 25%;">
                    <col style="width: 15%;">
                    <col style="width: 10%;">
                    <col style="width: 30%;">
                    <col style="width: 160px;">
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable" data-col="0">ID</th>
                        <th class="sortable" data-col="1">Event</th>
                        <th class="sortable" data-col="2">Module</th>
                        <th class="sortable" data-col="3">Actor</th>
                        <th class="sortable" data-col="4">Route Path</th>
                        <th class="sortable" data-col="5">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($recent_events ?? []) === []): ?>
                        <tr><td colspan="6" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">No recent events recorded.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recent_events as $row): ?>
                            <tr class="event-row" style="display: none;">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) ($row['id'] ?? '')) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 800; color: var(--v2-label); font-size: 0.85rem; word-break: break-all;"><?= esc((string) ($row['event_name'] ?? '')) ?></td>
                                <td style="font-weight: 600; text-transform: uppercase; font-size: 0.75rem;"><?= esc((string) ($row['module'] ?? '')) ?></td>
                                <td style="font-weight: 800;">USR-<?= esc((string) ($row['actor_id'] ?? '')) ?></td>
                                <td style="color: var(--v2-text-muted); font-size: 0.8rem; word-break: break-all;"><?= esc((string) ($row['route'] ?? '')) ?></td>
                                <td style="white-space: nowrap; font-weight: 600; font-size: 0.8rem;"><?= esc((string) ($row['created_at'] ?? '')) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);">
                Showing entries <span id="page-indicator" style="color: var(--v2-title);"></span>
            </p>
            <nav aria-label="Recent Events Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 10;
        const tbody = document.querySelector('#recent-events-table tbody');
        if (!tbody) return;

        let rowsArray = Array.from(tbody.querySelectorAll('.event-row'));
        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalRows = rowsArray.length;
        
        if (totalRows === 0) return;

        const totalPages = Math.ceil(totalRows / rowsPerPage);
        let currentPage = 1;

        function showPage(page) {
            currentPage = page;
            const startPoint = (page - 1) * rowsPerPage;
            const endPoint = startPoint + rowsPerPage;

            rowsArray.forEach((row, index) => {
                row.style.display = (index >= startPoint && index < endPoint) ? '' : 'none';
            });

            const actualEnd = Math.min(endPoint, totalRows);
            if (pageIndicator) pageIndicator.innerText = `${startPoint + 1} - ${actualEnd} of ${totalRows}`;

            buildPager();
        }

        function buildPager() {
            if (!pagerContainer) return;
            pagerContainer.innerHTML = '';
            if (totalPages <= 1) return;

            let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo;</a></li>`;
            for (let i = 1; i <= totalPages; i++) {
                html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`;
            }
            html += `<li class="${currentPage === totalPages ? 'disabled' : ''}"><a href="#" data-page="${currentPage + 1}">&raquo;</a></li>`;
            pagerContainer.innerHTML = html;
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

        document.querySelectorAll('#recent-events-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const direction = th.classList.contains('asc') ? -1 : 1; 
                document.querySelectorAll('#recent-events-table th.sortable').forEach(h => h.classList.remove('asc', 'desc'));
                th.classList.add(direction === 1 ? 'asc' : 'desc');
                
                rowsArray.sort((a, b) => {
                    let aT = a.children[colIndex].innerText.trim();
                    let bT = b.children[colIndex].innerText.trim();
                    if (colIndex === 0 || colIndex === 3) return (parseFloat(aT.replace(/[^\d.]/g, '')) - parseFloat(bT.replace(/[^\d.]/g, ''))) * direction;
                    return aT.localeCompare(bT) * direction;
                });
                
                rowsArray.forEach(row => tbody.appendChild(row));
                showPage(1);
            }); 
        });

        showPage(1);
    });
</script>
<?= $this->endSection() ?>
