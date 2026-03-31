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
        gap: 16px;
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

    .icon-found { background: #f1f5f9; color: #475569; }        
    .icon-auth { background: #dbeafe; color: #1e3a8a; } 
    .icon-proc { background: #e0f2fe; color: #0284c7; }   
    .icon-inv { background: #ecfccb; color: #16a34a; }   

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
    }

    .table-toolbar {
        padding: 16px 20px; 
        border-bottom: 1px solid var(--v2-border); 
        background: #ffffff; 
        flex-shrink: 0;
    }
    .table-toolbar h3 { margin: 0 0 12px 0; font-size: 1.05rem; color: var(--v2-title); font-weight: 800; }

    /* Filter Form Grid */
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .field { display: flex; flex-direction: column; gap: 4px; }
    .field label { font-size: 0.7rem; font-weight: 800; color: var(--v2-text-muted); text-transform: uppercase; }

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

    /* Modern Table */
    .table-scroll-container { flex: 1; overflow: auto; background: #ffffff; }
    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .modern-table th { 
        position: sticky; top: 0; z-index: 10;
        background: #ffffff !important; 
        padding: 12px 16px; 
        font-size: 0.7rem; 
        text-transform: uppercase; 
        font-weight: 800; 
        color: var(--v2-title); 
        border-bottom: 2px solid var(--v2-border); 
        text-align: left; 
    }
    .modern-table td { padding: 12px 16px; font-size: 0.8rem; color: var(--v2-text-main); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .modern-table tr:hover td { background: #f8fafc; }

    /* Sortable arrows */
    th.sortable { cursor: pointer; position: relative; padding-right: 24px !important; }
    th.sortable::after { content: '↕'; position: absolute; right: 8px; top: 50%; transform: translateY(-50%); opacity: 0.2; }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--v2-label); }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--v2-label); }

    /* Metadata JSON Chip */
    .meta-chip {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        color: var(--v2-text-muted);
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 4px;
        display: block;
        max-width: 100%;
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
        line-height: 1.35;
    }

    /* Pagination */
    .table-footer { padding: 10px 20px; border-top: 1px solid var(--v2-border); display: flex; justify-content: space-between; align-items: center; background: #ffffff; flex-shrink: 0; }
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li a, .ci-pager li span { display: inline-flex; align-items: center; justify-content: center; padding: 4px 10px; font-size: 0.75rem; min-width: 28px; border: 1px solid var(--v2-border); border-radius: 4px; background: #ffffff; color: var(--v2-label); text-decoration: none; font-weight: 700; transition: all 0.2s ease; }
    .ci-pager li a:hover { background: rgba(178, 224, 235, 0.3); border-color: var(--v2-label); }
    .ci-pager li.active a { background: var(--v2-label); color: #ffffff; border-color: var(--v2-label); }
    .ci-pager li.disabled a { opacity: 0.5; background: #f1f5f9; color: var(--v2-text-muted); pointer-events: none; border-color: #cbd5e1; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $eventsExportQuery = http_build_query(['export' => 'csv', 'event_name' => ($filters['event_name'] ?? ''), 'module' => ($filters['module'] ?? ''), 'actor_id' => ($filters['actor_id'] ?? ''), 'date_from' => ($filters['date_from'] ?? ''), 'date_to' => ($filters['date_to'] ?? ''), 'limit' => ($limit ?? 500)]); ?>
<a class="btn btn-outline" href="<?= site_url('analytics/events') . '?' . $eventsExportQuery ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('analytics/dashboard') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Dashboard</a>
<a class="btn btn-outline" href="<?= site_url('analytics/metrics') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Metrics</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$eventRows = $rows ?? []; 
$eventsShown = count($eventRows);
$authEvents = count(array_filter($eventRows, static fn (array $row): bool => (string) ($row['module'] ?? '') === 'auth'));
$procurementEvents = count(array_filter($eventRows, static fn (array $row): bool => (string) ($row['module'] ?? '') === 'procurement'));
$inventoryEvents = count(array_filter($eventRows, static fn (array $row): bool => in_array((string) ($row['module'] ?? ''), ['inventory', 'receiving'], true)));
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Event Telemetry</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-found"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) $eventsShown) ?></span>
                    <span class="kpi-label">Events Found</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-violet">
                <div class="kpi-icon-box icon-auth"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) $authEvents) ?></span>
                    <span class="kpi-label">Authentication</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-proc"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) $procurementEvents) ?></span>
                    <span class="kpi-label">Procurement</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-inv"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) $inventoryEvents) ?></span>
                    <span class="kpi-label">Inventory flow</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card" style="flex: none;">
        <div class="table-toolbar">
            <h3>Log Filter Console</h3>
            <form method="get" action="<?= site_url('analytics/events') ?>">
                <div class="filter-grid">
                    <div class="field">
                        <label>Event Name</label>
                        <input name="event_name" value="<?= esc((string) ($filters['event_name'] ?? '')) ?>" class="input-v2" placeholder="e.g. pr_submitted">
                    </div>
                    <div class="field">
                        <label>Module</label>
                        <input name="module" value="<?= esc((string) ($filters['module'] ?? '')) ?>" class="input-v2" placeholder="e.g. auth">
                    </div>
                    <div class="field">
                        <label>Actor ID</label>
                        <input name="actor_id" value="<?= esc((string) ($filters['actor_id'] ?? '')) ?>" class="input-v2">
                    </div>
                    <div class="field">
                        <label>Date From</label>
                        <input type="date" name="date_from" value="<?= esc((string) ($filters['date_from'] ?? '')) ?>" class="input-v2">
                    </div>
                    <div class="field">
                        <label>Date To</label>
                        <input type="date" name="date_to" value="<?= esc((string) ($filters['date_to'] ?? '')) ?>" class="input-v2">
                    </div>
                    <div class="field">
                        <label>DB Limit</label>
                        <div style="display: flex; gap: 8px;">
                            <input type="number" name="limit" value="<?= esc((string) $limit) ?>" class="input-v2" style="width: 80px;">
                            <button type="submit" class="btn btn-primary" style="flex: 1; font-weight: 800; font-size: 0.8rem; background: var(--v2-label); border: none; border-radius: 6px; color: white;">Apply</button>
                            <a href="<?= site_url('analytics/events') ?>" class="btn btn-outline" style="font-weight: 800; font-size: 0.8rem; border-radius: 6px; padding: 6px 12px; text-decoration: none;">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-card">
        <div class="table-scroll-container">
            <table class="modern-table" id="events-table" style="table-layout: fixed; width: 100%;">
                <colgroup>
                    <col style="width: 60px;">
                    <col style="width: 18%;">
                    <col style="width: 10%;">
                    <col style="width: 80px;">
                    <col style="width: 12%;">
                    <col style="width: 18%;">
                    <col style="width: 80px;">
                    <col style="width: 12%;">
                    <col style="width: 150px;">
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable" data-col="0">ID</th>
                        <th class="sortable" data-col="1">Event</th>
                        <th class="sortable" data-col="2">Module</th>
                        <th class="sortable" data-col="3">Actor</th>
                        <th class="sortable" data-col="4">Ref</th>
                        <th class="sortable" data-col="5">Route</th>
                        <th class="sortable" data-col="6">Method</th>
                        <th>Metadata</th>
                        <th class="sortable" data-col="8">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($rows ?? []) === []): ?>
                        <tr><td colspan="9" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">No events match the current criteria.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr class="event-row" style="display: none;">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) ($row['id'] ?? '')) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 800; color: var(--v2-label); word-break: break-all;"><?= esc((string) ($row['event_name'] ?? '')) ?></td>
                                <td style="font-weight: 700; text-transform: uppercase; font-size: 0.7rem; color: var(--v2-text-muted);"><?= esc((string) ($row['module'] ?? '')) ?></td>
                                <td style="font-weight: 800;">USR-<?= esc((string) ($row['actor_id'] ?? '')) ?></td>
                                <td style="font-size: 0.75rem; color: var(--v2-text-muted); font-weight: 600;"><?= esc((string) ($row['reference_type'] ?? '-')) ?> #<?= esc((string) ($row['reference_id'] ?? '')) ?></td>
                                <td style="color: var(--v2-text-muted); font-size: 0.75rem; word-break: break-all;"><?= esc((string) ($row['route'] ?? '')) ?></td>
                                <td style="font-weight: 800; font-size: 0.75rem;"><?= esc((string) ($row['method'] ?? '')) ?></td>
                                <td><span class="meta-chip" title="<?= esc((string) ($row['metadata_json'] ?? '')) ?>"><?= esc((string) ($row['metadata_json'] ?? '')) ?></span></td>
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
            <nav aria-label="Events Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 15;
        const tbody = document.querySelector('#events-table tbody');
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

            let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;
            for (let i = 1; i <= totalPages; i++) {
                html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`;
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
                showPage(parseInt(link.getAttribute('data-page')));
            });
        }

        document.querySelectorAll('#events-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const direction = th.classList.contains('asc') ? -1 : 1; 
                document.querySelectorAll('#events-table th.sortable').forEach(h => h.classList.remove('asc', 'desc'));
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
