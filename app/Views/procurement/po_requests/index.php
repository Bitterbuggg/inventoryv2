<?php

declare(strict_types=1);

$title = 'PO Requests - InventoryV2';
$pageTitle = 'Procurement - PO Requests';
$pageSubtitle = 'Approve or reject PO requests before receiving conversion.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'PO Requests'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* Custom JS Pager */
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li { display: block; }
    .ci-pager li a, .ci-pager li span { display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; font-size: 0.85rem; min-width: 32px; border: 1px solid var(--color-border-strong); border-radius: var(--radius-sm); background: var(--color-surface); color: var(--color-brand-700); text-decoration: none; font-weight: 600; transition: all 0.2s ease; }
    .ci-pager li a:hover { background: var(--color-brand-100); border-color: var(--color-brand-500); }
    .ci-pager li.active a { background: var(--color-brand-500); color: #ffffff; border-color: var(--color-brand-600); }
    .ci-pager li.disabled a { opacity: 0.5; background: var(--color-surface-alt); color: var(--color-text-muted); pointer-events: none; border-color: var(--color-border); }
    .ci-pager li span.ellipsis { border: none !important; background: transparent !important; padding: 0 4px !important; min-width: auto; color: var(--color-text-muted); }

    /* Fixed Sortable Headers - Prevents clipping at the top and gives arrow space */
    #po-req-table th {
        padding-top: 12px !important; /* Extra breathing room at the top to prevent clipping */
        padding-bottom: 12px !important;
    }
    th.sortable { 
        cursor: pointer; 
        position: relative; 
        padding-right: 24px !important; 
        user-select: none; 
        transition: background 0.2s ease; 
        line-height: 1.4 !important; 
        white-space: normal !important; /* Allows long headers to safely wrap */
        vertical-align: middle !important;
        overflow: visible !important; /* Ensures descenders/ascenders are not chopped */
    }
    th.sortable:hover { background: rgba(0, 0, 0, 0.03) !important; }
    th.sortable::after { 
        content: '↕'; 
        position: absolute; 
        right: 6px; 
        top: 50%; 
        transform: translateY(-50%); 
        font-size: 0.85rem; 
        opacity: 0.3; 
    }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    
    .filter-active-text { color: var(--color-brand-600); font-weight: 800; text-transform: capitalize; }

    .action-forms-container { display: flex; gap: 8px; align-items: center; justify-content: flex-start; flex-wrap: wrap; }
    .reject-input { padding: 4px 8px; font-size: 0.8rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); width: 140px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>">Pending Approvals</a>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Correct Variables for PO Requests
$rows = $poRequests ?? [];
$totalRequests = count($rows);
$pendingRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'pending'));
$approvedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'approved'));
$rejectedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'rejected'));
?>

<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Visible PO Requests</p>
                <p class="kpi-value" id="kpi-visible" style="font-size: 1.25rem;"><?= esc((string) $totalRequests) ?></p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Pending</p>
                <p class="kpi-value" id="kpi-pending" style="font-size: 1.25rem; color: #d97706;"><?= esc((string) $pendingRequests) ?></p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Approved</p>
                <p class="kpi-value" id="kpi-approved" style="font-size: 1.25rem; color: var(--color-success);"><?= esc((string) $approvedRequests) ?></p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Rejected</p>
                <p class="kpi-value" id="kpi-rejected" style="font-size: 1.25rem; color: var(--color-danger);"><?= esc((string) $rejectedRequests) ?></p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <div class="stack-sm" style="border-bottom: 1px solid var(--color-border); padding-bottom: 12px; margin-bottom: 8px;">
            <h2 style="margin: 0; font-size: 1.25rem;">PO Request Queue</h2>
            <p class="muted" style="margin: 4px 0 0 0; font-size: 0.85rem;">Filter records and apply approval decisions on pending PO requests.</p>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <div style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
                <input type="text" id="instant-search-input" placeholder="Search PO Req #, PO ID, or Req ID..." autocomplete="off" style="flex: 1;">
                <button type="button" class="btn btn-outline" id="btn-clear-search">Clear</button>
            </div>
            
            <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('procurement/po-requests') ?>" style="margin: 0;">
                <select id="status" name="status" style="padding: 6px 12px; font-size: 0.85rem;">
                    <option value="">DB Sync: All</option>
                    <?php foreach (['pending', 'approved', 'rejected', 'converted_to_receiving', 'closed'] as $option): ?>
                        <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
                    <?php endforeach ?>
                </select>
                <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.85rem;">Sync</button>
            </form>
        </div>

        <div class="table-wrap">
            <table class="table" id="po-req-table" style="table-layout: fixed; width: 100%; min-width: 1150px;">
                <colgroup>
                    <col style="width: 60px;">  <col style="width: 180px;"> <col style="width: 100px;"> <col style="width: 120px;"> <col style="width: 250px;"> <col style="width: 130px;"> <col style="width: auto;">  </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">PO Request #</th>
                        <th class="sortable numeric" data-col="2">PO ID</th>
                        <th class="sortable date" data-col="3">Request Date</th>
                        <th class="sortable" data-col="4" id="status-header" title="Click to cycle status filters!">Status (All)</th>
                        <th class="sortable numeric" data-col="5">Action By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($poRequests ?? []) === []): ?>
                        <tr>
                            <td colspan="7" class="empty-state">No PO requests found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($poRequests as $poRequest): ?>
                            <tr class="po-req-row" style="display: none;" data-status="<?= esc(strtolower((string) ($poRequest['status'] ?? ''))) ?>">
                                <td><?= esc((string) $poRequest['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 500; color: var(--color-brand-700);"><?= esc((string) $poRequest['po_request_number']) ?></td>
                                <td style="font-family: var(--font-mono); font-size: 0.85rem;">PO-<?= esc((string) $poRequest['purchase_order_id']) ?></td>
                                <td style="font-size: 0.85rem;"><?= esc((string) $poRequest['request_date']) ?></td>
                                <td><?= view('components/shared/table_status_badge', ['status' => $poRequest['status'] ?? 'unknown']) ?></td>
                                <td style="font-size: 0.85rem; color: var(--color-text-muted);">
                                    <?= esc((string) ($poRequest['approved_by'] ?? $poRequest['rejected_by'] ?? '-')) ?>
                                </td>
                                <td>
                                    <?php if (($poRequest['status'] ?? '') === 'pending'): ?>
                                        <div class="action-forms-container">
                                            <form method="post" action="<?= site_url('procurement/po-requests/' . $poRequest['id'] . '/approve') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px; font-size: 0.75rem;">Approve</button>
                                            </form>
                                            <form method="post" action="<?= site_url('procurement/po-requests/' . $poRequest['id'] . '/reject') ?>" style="margin: 0; display: flex; gap: 4px;">
                                                <?= csrf_field() ?>
                                                <input type="text" name="reason" class="reject-input" placeholder="Reason..." required>
                                                <button type="submit" class="btn btn-danger" style="padding: 4px 10px; font-size: 0.75rem;">Reject</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="muted" style="font-size: 0.8rem;">No actions available</span>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
            <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                Showing records <span id="page-indicator"></span> (Total: <span id="total-indicator"><?= esc((string) $totalRequests) ?></span>)
            </p>
            <nav aria-label="Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 15; 
        const tbody = document.querySelector('#po-req-table tbody');
        
        if (tbody && tbody.querySelector('.po-req-row')) {
            const allRows = Array.from(tbody.querySelectorAll('.po-req-row'));
            let currentRows = [...allRows]; 

            const pagerContainer = document.getElementById('client-pager');
            const pageIndicator = document.getElementById('page-indicator');
            const totalIndicator = document.getElementById('total-indicator');
            const statusHeader = document.getElementById('status-header');
            
            const kpiVisible = document.getElementById('kpi-visible');
            const kpiPending = document.getElementById('kpi-pending');
            const kpiApproved = document.getElementById('kpi-approved');
            const kpiRejected = document.getElementById('kpi-rejected');

            const searchInput = document.getElementById('instant-search-input');
            const clearBtn = document.getElementById('btn-clear-search');

            // Exact Status Logic cycle
            const statusCycle = ['All', 'pending', 'approved', 'rejected', 'converted_to_receiving', 'closed'];
            let cycleIndex = 0;
            let currentStatusFilter = 'All';

            // Cleanly formats status strings like 'converted_to_receiving' to 'Converted To Receiving'
            function toTitleCase(str) {
                return str.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            }

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();

                currentRows = allRows.filter(row => {
                    const id = row.children[0].innerText.toLowerCase();
                    const poReqNum = row.children[1].innerText.toLowerCase();
                    const poId = row.children[2].innerText.toLowerCase();
                    const statusVal = row.getAttribute('data-status');

                    const matchesText = query === '' || id.includes(query) || poReqNum.includes(query) || poId.includes(query);
                    const matchesStatus = currentStatusFilter === 'All' || statusVal === currentStatusFilter;

                    return matchesText && matchesStatus;
                });

                currentRows.forEach(row => tbody.appendChild(row));
                updateKPIs();
                showPage(1);
            }

            function updateKPIs() {
                let countPending = 0, countApproved = 0, countRejected = 0;
                currentRows.forEach(row => {
                    const stat = row.getAttribute('data-status');
                    if (stat === 'pending') countPending++;
                    if (stat === 'approved') countApproved++;
                    if (stat === 'rejected') countRejected++;
                });
                
                kpiVisible.innerText = currentRows.length;
                kpiPending.innerText = countPending;
                kpiApproved.innerText = countApproved;
                kpiRejected.innerText = countRejected;
                totalIndicator.innerText = currentRows.length;
            }

            if(searchInput) searchInput.addEventListener('input', applyFilters);
            if(clearBtn) {
                clearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    applyFilters();
                });
            }

            if(statusHeader) {
                statusHeader.addEventListener('click', (e) => {
                    e.stopPropagation(); 
                    
                    cycleIndex = (cycleIndex + 1) % statusCycle.length;
                    currentStatusFilter = statusCycle[cycleIndex];

                    if (currentStatusFilter === 'All') {
                        statusHeader.innerHTML = `Status (All)`;
                    } else {
                        statusHeader.innerHTML = `Status (<span class="filter-active-text">${toTitleCase(currentStatusFilter)}</span>)`;
                    }
                    applyFilters();
                });
            }

            document.querySelectorAll('#po-req-table th.sortable').forEach(th => {
                if (parseInt(th.getAttribute('data-col')) === 4) return; 

                th.addEventListener('click', () => {
                    const colIndex = parseInt(th.getAttribute('data-col'));
                    const isNumericCol = th.classList.contains('numeric');
                    const isDateCol = th.classList.contains('date');
                    const isAsc = th.classList.contains('asc');
                    const direction = isAsc ? -1 : 1; 
                    
                    document.querySelectorAll('#po-req-table th.sortable').forEach(header => {
                        if (parseInt(header.getAttribute('data-col')) !== 4) {
                            header.classList.remove('asc', 'desc');
                        }
                    });
                    
                    th.classList.add(isAsc ? 'desc' : 'asc');
                    
                    currentRows.sort((a, b) => {
                        // Strip out '#' and 'PO-' text for clean numeric sorting
                        let aText = a.children[colIndex].innerText.trim().replace(/PO-|#/g, '');
                        let bText = b.children[colIndex].innerText.trim().replace(/PO-|#/g, '');
                        
                        if (isNumericCol) return (parseFloat(aText) - parseFloat(bText)) * direction;
                        if (isDateCol) {
                            let dateA = aText === '' ? 0 : new Date(aText).getTime();
                            let dateB = bText === '' ? 0 : new Date(bText).getTime();
                            return (dateA - dateB) * direction;
                        }
                        return aText.localeCompare(bText) * direction;
                    });
                    
                    currentRows.forEach(row => tbody.appendChild(row));
                    showPage(1);
                });
            });

            let currentPage = 1;
            function showPage(page) {
                currentPage = page;
                const totalRows = currentRows.length;
                const totalPages = Math.ceil(totalRows / rowsPerPage);

                if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

                const startPoint = (currentPage - 1) * rowsPerPage;
                const endPoint = startPoint + rowsPerPage;

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
    });
</script>
<?= $this->endSection() ?>