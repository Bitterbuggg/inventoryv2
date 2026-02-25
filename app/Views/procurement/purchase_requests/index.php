<?php

declare(strict_types=1);

$title = 'Purchase Requests - InventoryV2';
$pageTitle = 'Procurement - Purchase Requests';
$pageSubtitle = 'Create, submit, and track purchase requests by workflow status.';
$crumbs = [
    ['label' => 'Purchase Requests'],
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

    /* Fixed Sortable Headers */
    #pr-table th { padding-top: 12px !important; padding-bottom: 12px !important; }
    th.sortable { 
        cursor: pointer; 
        position: relative; 
        padding-right: 28px !important; 
        user-select: none; 
        transition: background 0.2s ease; 
        line-height: 1.4 !important; 
        white-space: normal !important; 
        vertical-align: middle !important;
        overflow: visible !important; 
    }
    th.sortable:hover { background: rgba(0, 0, 0, 0.03) !important; }
    th.sortable::after { content: '↕'; position: absolute; right: 8px; top: 50%; transform: translateY(-50%); font-size: 0.85rem; opacity: 0.3; pointer-events: none; }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    
    .filter-active-text { color: var(--color-brand-600); font-weight: 800; text-transform: capitalize; }

    /* --- HCI COMPLIANT TABLE ACTIONS --- */
    .action-row { display: flex; gap: 6px; align-items: center; justify-content: flex-start; }
    
    /* Unified Input + Button Group for Create PO */
    .create-po-group {
        display: inline-flex;
        align-items: stretch;
        border: 1px solid var(--color-border-strong);
        border-radius: var(--radius-sm);
        overflow: hidden;
        background: var(--color-surface);
        transition: border-color 0.2s ease;
    }
    .create-po-group:focus-within { border-color: var(--color-brand-500); }
    .create-po-input {
        border: none;
        padding: 4px 8px;
        font-size: 0.75rem;
        width: 120px;
        outline: none;
        background: transparent;
    }
    .create-po-btn {
        border: none;
        background: var(--color-brand-600);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .create-po-btn:hover { background: var(--color-brand-700); }

    /* Subtle Danger Action (Cancel) */
    .btn-danger-subtle {
        background: transparent;
        border: 1px solid transparent;
        color: var(--color-danger);
        padding: 4px 8px;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        cursor: pointer;
        text-decoration: none;
    }
    .btn-danger-subtle:hover { background: #fee2e2; border-color: #f87171; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-primary" href="<?= site_url('procurement/purchase-requests/create') ?>">Create Request</a>
<a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>">Pending Approvals</a>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $requests ?? [];
$totalRequests = count($rows);
$draftRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'draft'));
$submittedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'submitted'));
$approvedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'approved'));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Visible Requests</p>
                <p class="kpi-value" id="kpi-visible" style="font-size: 1.25rem;"><?= esc((string) $totalRequests) ?></p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Draft</p>
                <p class="kpi-value" id="kpi-draft" style="font-size: 1.25rem;"><?= esc((string) $draftRequests) ?></p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Submitted</p>
                <p class="kpi-value" id="kpi-submitted" style="font-size: 1.25rem; color: #d97706;"><?= esc((string) $submittedRequests) ?></p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Approved</p>
                <p class="kpi-value" id="kpi-approved" style="font-size: 1.25rem; color: var(--color-success);"><?= esc((string) $approvedRequests) ?></p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <div class="stack-sm" style="border-bottom: 1px solid var(--color-border); padding-bottom: 12px; margin-bottom: 8px;">
            <h2 style="margin: 0; font-size: 1.25rem;">Request Queue</h2>
            <p class="muted" style="margin: 4px 0 0 0; font-size: 0.85rem;">Filter requests and process actions directly from this list.</p>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <div style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
                <input type="text" id="instant-search-input" placeholder="Search PR Number or Requestor..." autocomplete="off" style="flex: 1;">
                <button type="button" class="btn btn-outline" id="btn-clear-search">Clear</button>
            </div>
            
            <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('procurement/purchase-requests') ?>" style="margin: 0;">
                <select id="status" name="status" style="padding: 6px 12px; font-size: 0.85rem;">
                    <option value="">DB Sync: All</option>
                    <?php foreach (['draft', 'submitted', 'approved', 'rejected', 'cancelled', 'converted_to_po'] as $option): ?>
                        <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
                    <?php endforeach ?>
                </select>
                <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.85rem;">Sync</button>
            </form>
        </div>

        <div class="table-wrap">
            <table class="table" id="pr-table" style="table-layout: fixed; width: 100%; min-width: 1050px;">
                <colgroup>
                    <col style="width: 60px;">  <col style="width: 160px;"> <col style="width: 120px;"> <col style="width: 110px;"> <col style="width: 180px;"> <col style="width: 15%;">   <col style="width: 250px;"> 
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">PR Number</th>
                        <th class="sortable" data-col="2">Requested By</th>
                        <th class="sortable date" data-col="3">Request Date</th>
                        <th class="sortable" data-col="4" id="status-header" title="Click to cycle status filters!" style="line-height: 1.2;">
                            Status <br><span style="font-size: 0.75rem; font-weight: normal; opacity: 0.7;">(All)</span>
                        </th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($requests ?? []) === []): ?>
                        <tr><td colspan="7" class="empty-state">No purchase requests found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($requests as $request): ?>
                            <tr class="pr-row" style="display: none;" data-status="<?= esc(strtolower((string) ($request['status'] ?? ''))) ?>">
                                <td><?= esc((string) $request['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 500; color: var(--color-brand-700); font-size: 0.9rem;"><?= esc((string) $request['pr_number']) ?></td>
                                <td style="font-size: 0.85rem;"><?= esc((string) $request['requested_by']) ?></td>
                                <td style="font-size: 0.85rem;"><?= esc((string) $request['request_date']) ?></td>
                                <td><?= view('components/shared/table_status_badge', ['status' => $request['status'] ?? 'unknown']) ?></td>
                                <td style="font-size: 0.85rem; color: var(--color-text-muted); word-break: break-word;"><?= esc((string) ($request['remarks'] ?? '')) ?></td>
                                <td>
                                    <div class="action-row">
                                        <?php if (($request['status'] ?? '') === 'draft'): ?>
                                            <form method="post" action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/submit') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px; font-size: 0.75rem;">Submit</button>
                                            </form>
                                            <a class="btn btn-outline" style="padding: 4px 10px; font-size: 0.75rem;" href="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/edit') ?>">Edit</a>
                                            <form method="post" action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/cancel') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-danger-subtle">Cancel</button>
                                            </form>
                                        <?php elseif (($request['status'] ?? '') === 'submitted'): ?>
                                            <form method="post" action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/cancel') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-danger-subtle">Cancel Request</button>
                                            </form>
                                        <?php elseif (($request['status'] ?? '') === 'approved'): ?>
                                            <form method="post" action="<?= site_url('procurement/purchase-orders/from-pr/' . $request['id']) ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <div class="create-po-group">
                                                    <input type="text" name="supplier_name" class="create-po-input" placeholder="Supplier (Opt)">
                                                    <button type="submit" class="create-po-btn">Create PO</button>
                                                </div>
                                            </form>
                                        <?php else: ?>
                                            <span class="muted" style="font-size: 0.8rem;">No actions</span>
                                        <?php endif ?>
                                    </div>
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
        const tbody = document.querySelector('#pr-table tbody');
        
        if (tbody && tbody.querySelector('.pr-row')) {
            const allRows = Array.from(tbody.querySelectorAll('.pr-row'));
            let currentRows = [...allRows]; 

            const pagerContainer = document.getElementById('client-pager');
            const pageIndicator = document.getElementById('page-indicator');
            const totalIndicator = document.getElementById('total-indicator');
            const statusHeader = document.getElementById('status-header');
            
            const kpiVisible = document.getElementById('kpi-visible');
            const kpiDraft = document.getElementById('kpi-draft');
            const kpiSubmitted = document.getElementById('kpi-submitted');
            const kpiApproved = document.getElementById('kpi-approved');

            const searchInput = document.getElementById('instant-search-input');
            const clearBtn = document.getElementById('btn-clear-search');

            const statusCycle = ['All', 'draft', 'submitted', 'approved', 'rejected', 'cancelled', 'converted_to_po'];
            let cycleIndex = 0;
            let currentStatusFilter = 'All';

            function toTitleCase(str) {
                return str.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            }

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();

                currentRows = allRows.filter(row => {
                    const prNum = row.children[1].innerText.toLowerCase();
                    const reqBy = row.children[2].innerText.toLowerCase();
                    const statusVal = row.getAttribute('data-status');

                    const matchesText = query === '' || prNum.includes(query) || reqBy.includes(query);
                    const matchesStatus = currentStatusFilter === 'All' || statusVal === currentStatusFilter;

                    return matchesText && matchesStatus;
                });

                currentRows.forEach(row => tbody.appendChild(row));
                updateKPIs();
                showPage(1);
            }

            function updateKPIs() {
                let countDraft = 0, countSubmitted = 0, countApproved = 0;
                
                currentRows.forEach(row => {
                    const stat = row.getAttribute('data-status');
                    if (stat === 'draft') countDraft++;
                    if (stat === 'submitted') countSubmitted++;
                    if (stat === 'approved') countApproved++;
                });
                
                kpiVisible.innerText = currentRows.length;
                kpiDraft.innerText = countDraft;
                kpiSubmitted.innerText = countSubmitted;
                kpiApproved.innerText = countApproved;
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
                        statusHeader.innerHTML = `Status <br><span style="font-size: 0.75rem; font-weight: normal; opacity: 0.7;">(All)</span>`;
                    } else {
                        statusHeader.innerHTML = `Status <br><span class="filter-active-text">${toTitleCase(currentStatusFilter)}</span>`;
                    }
                    applyFilters();
                });
            }

            document.querySelectorAll('#pr-table th.sortable').forEach(th => {
                if (parseInt(th.getAttribute('data-col')) === 4) return; // Skip Status

                th.addEventListener('click', () => {
                    const colIndex = parseInt(th.getAttribute('data-col'));
                    const isNumericCol = th.classList.contains('numeric');
                    const isDateCol = th.classList.contains('date');
                    const isAsc = th.classList.contains('asc');
                    const direction = isAsc ? -1 : 1; 
                    
                    document.querySelectorAll('#pr-table th.sortable').forEach(header => {
                        if (parseInt(header.getAttribute('data-col')) !== 4) {
                            header.classList.remove('asc', 'desc');
                        }
                    });
                    
                    th.classList.add(isAsc ? 'desc' : 'asc');
                    
                    currentRows.sort((a, b) => {
                        let aText = a.children[colIndex].innerText.trim();
                        let bText = b.children[colIndex].innerText.trim();
                        
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