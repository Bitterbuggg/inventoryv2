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
    /* Default neutral arrows */
    th.sortable::after {
        content: '↕';
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.75rem;
        opacity: 0.3;
    }
    /* Active sort arrows */
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
    /* Force the custom JS Pager to match your modern button design */
    .ci-pager {
        display: flex;
        gap: 6px;
        list-style: none;
        margin: 0;
        padding: 0;
        align-items: center;
    }
    .ci-pager li {
        display: block;
    }
    .ci-pager li a, .ci-pager li span {
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
    .ci-pager li.active a {
        background: var(--color-brand-500);
        color: #ffffff;
        border-color: var(--color-brand-600);
    }
    .ci-pager li.disabled a {
        opacity: 0.5;
        background: var(--color-surface-alt);
        color: var(--color-text-muted);
        pointer-events: none;
        border-color: var(--color-border);
    }
    .ci-pager li span.ellipsis {
        border: none !important;
        background: transparent !important;
        padding: 0 4px !important;
        min-width: auto;
        color: var(--color-text-muted);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('analytics/dashboard') ?>">Dashboard</a>
<a class="btn btn-outline" href="<?= site_url('analytics/metrics') ?>">Metrics</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Since $rows is now the full dataset, we calculate KPIs directly from it!
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
                <p class="kpi-label">Events Found</p>
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
                    <label for="limit">Database Limit</label>
                    <input id="limit" type="number" min="1" max="1000" name="limit" value="<?= esc((string) $limit) ?>">
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
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a class="btn btn-outline" href="<?= site_url('analytics/events') ?>">Clear</a>
            </div>
        </form>

        <div class="stack-sm" style="margin-top: var(--space-4);">
            <h2>Event Log</h2>
            <p class="muted">Latest matching events with routing and metadata context.</p>
        </div>

        <div id="full-events-container">
            <div class="table-wrap">
                <table class="table" id="events-table" style="table-layout: fixed; width: 100%; min-width: 1000px;">
                    <colgroup>
                        <col style="width: 60px;">  <col style="width: 18%;">   <col style="width: 10%;">   <col style="width: 10%;">   <col style="width: 12%;">   <col style="width: 16%;">   <col style="width: 8%;">    <col style="width: 12%;">   <col style="width: 150px;"> </colgroup>
                    <thead>
                        <tr>
                            <th class="sortable" data-col="0">ID</th>
                            <th class="sortable" data-col="1">Event</th>
                            <th class="sortable" data-col="2">Module</th>
                            <th class="sortable" data-col="3">Actor</th>
                            <th class="sortable" data-col="4">Reference</th>
                            <th class="sortable" data-col="5">Route</th>
                            <th class="sortable" data-col="6">Method</th>
                            <th>Metadata</th> 
                            <th class="sortable" data-col="8">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (($rows ?? []) === []): ?>
                            <tr class="no-records-row"><td colspan="9" class="empty-state">No analytics events found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($rows as $row): ?>
                                <tr class="event-row" style="display: none;">
                                    <td><?= esc((string) ($row['id'] ?? '')) ?></td>
                                    <td style="font-family: var(--font-mono); color: var(--color-brand-700); font-size: 0.85rem; word-break: break-all;"><?= esc((string) ($row['event_name'] ?? '')) ?></td>
                                    <td><?= esc((string) ($row['module'] ?? '')) ?></td>
                                    <td><strong><?= esc((string) ($row['actor_id'] ?? '')) ?></strong></td>
                                    <td style="font-size: 0.85rem;"><?= esc((string) ($row['reference_type'] ?? '')) ?> <?= esc((string) ($row['reference_id'] ?? '')) ?></td>
                                    <td style="color: var(--color-text-muted); font-size: 0.85rem; word-break: break-all;"><?= esc((string) ($row['route'] ?? '')) ?></td>
                                    <td style="font-size: 0.85rem; font-weight: 600;"><?= esc((string) ($row['method'] ?? '')) ?></td>
                                    <td>
                                        <div style="max-width: 200px; max-height: 40px; overflow: hidden; text-overflow: ellipsis; font-family: var(--font-mono); font-size: 0.75rem; color: var(--color-text-muted); background: var(--color-surface-alt); padding: 4px; border-radius: 4px;">
                                            <?= esc((string) ($row['metadata_json'] ?? '')) ?>
                                        </div>
                                    </td>
                                    <td style="white-space: nowrap; font-size: 0.85rem;"><?= esc((string) ($row['created_at'] ?? '')) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
                <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                    Showing records <span id="page-indicator"></span> (Total: <?= esc((string) ($total_events ?? 0)) ?>)
                </p>
                
                <nav aria-label="Events Pagination">
                    <ul class="ci-pager" id="client-pager"></ul>
                </nav>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 15; // Set to 15 for full page
        const tbody = document.querySelector('#events-table tbody');
        
        // Convert NodeList to an Array so we can easily sort it
        let rowsArray = Array.from(tbody.querySelectorAll('.event-row'));
        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalRows = rowsArray.length;
        
        if (totalRows === 0) return;

        let currentPage = 1;
        let totalPages = Math.ceil(totalRows / rowsPerPage);

        // ==========================================
        // 1. PAGINATION LOGIC
        // ==========================================
        function showPage(page) {
            currentPage = page;
            const startPoint = (page - 1) * rowsPerPage;
            const endPoint = startPoint + rowsPerPage;

            // Loop through our sorted array and apply display rules
            rowsArray.forEach((row, index) => {
                row.style.display = (index >= startPoint && index < endPoint) ? '' : 'none';
            });

            const actualEnd = Math.min(endPoint, totalRows);
            pageIndicator.innerText = `${startPoint + 1} - ${actualEnd}`;

            buildPaginationButtons();
        }

        function buildPaginationButtons() {
            pagerContainer.innerHTML = '';
            if (totalPages <= 1) return;

            let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

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

        pagerContainer.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) return;
            e.preventDefault();

            const li = link.parentElement;
            if (li.classList.contains('disabled') || li.classList.contains('active')) return;

            showPage(parseInt(link.getAttribute('data-page')));
        });

        // ==========================================
        // 2. SORTING LOGIC
        // ==========================================
        document.querySelectorAll('th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; // Toggle direction
                
                // Reset all headers
                document.querySelectorAll('th.sortable').forEach(header => {
                    header.classList.remove('asc', 'desc');
                });
                
                // Add class to the clicked header
                th.classList.add(isAsc ? 'desc' : 'asc');
                
                // Sort the array of rows
                rowsArray.sort((a, b) => {
                    let aText = a.children[colIndex].innerText.trim();
                    let bText = b.children[colIndex].innerText.trim();
                    
                    // If sorting ID or Actor (numeric columns), convert to Float to sort mathematically
                    if (colIndex === 0 || colIndex === 3) {
                        return (parseFloat(aText) - parseFloat(bText)) * direction;
                    }
                    
                    // Otherwise, sort alphabetically (strings and dates)
                    return aText.localeCompare(bText) * direction;
                });
                
                // Re-append the sorted rows to the table body (this instantly moves them in the DOM)
                rowsArray.forEach(row => tbody.appendChild(row));
                
                // Reset to page 1 to show the new top results!
                showPage(1);
            });
        });

        // Initialize table on Page 1 when page loads
        showPage(1);
    });
</script>
<?= $this->endSection() ?>