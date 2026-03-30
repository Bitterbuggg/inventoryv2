<?php

declare(strict_types=1);

$title = 'Purchase Requests - InventoryV2';
$pageTitle = 'Procurement - Purchase Requests';
$pageSubtitle = 'Create, submit, and track purchase requests by workflow status.';
$crumbs = [
    ['label' => 'Purchase Requests'],
];

$user = function_exists('auth') ? auth()->user() : null;
$canCreatePr = $user !== null && method_exists($user, 'can') && $user->can('procurement.pr.create');
$canApprovePr = $user !== null && method_exists($user, 'can') && $user->can('procurement.pr.approve');
$canCreatePo = $user !== null && method_exists($user, 'can') && $user->can('procurement.po.create');
$canManagePoRequests = $user !== null && method_exists($user, 'can') && $user->can('procurement.por.manage');
$supplierCatalog = $suppliers ?? [];
$requestStatusOptions = $statusOptions ?? [];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/procurement-queue.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php if ($canCreatePr): ?>
    <a class="btn btn-primary" href="<?= site_url('procurement/purchase-requests/create') ?>" title="Create a new draft purchase request" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem; box-shadow: 0 2px 4px rgba(2, 132, 199, 0.2);">Create Request</a>
<?php endif ?>
<?php $purchaseRequestExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') . '?' . $purchaseRequestExportQuery ?>" title="Download the current list of purchase requests as a CSV file" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<?php if ($canApprovePr): ?>
    <a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Pending Approvals</a>
<?php endif ?>
<?php if ($canCreatePo): ?>
    <a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Purchase Orders</a>
<?php endif ?>
<?php if ($canManagePoRequests): ?>
    <a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">PO Requests</a>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $requests ?? [];
$totalRequests = count($rows); 
$draftRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'draft'));
$submittedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'submitted'));
$approvedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'approved'));
?>

<div class="procurement-queue procurement-queue--purchase-requests">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Purchase Requests</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-total"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-visible"><?= esc((string) $totalRequests) ?></span>
                    <span class="kpi-label">Visible Requests</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-violet">
                <div class="kpi-icon-box icon-draft--violet"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-draft"><?= esc((string) $draftRequests) ?></span>
                    <span class="kpi-label">Draft Status</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-amber">
                <div class="kpi-icon-box icon-submitted"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-submitted" style="color: #b45309;"><?= esc((string) $submittedRequests) ?></span>
                    <span class="kpi-label">Submitted</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-approved"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-approved" style="color: #15803d;"><?= esc((string) $approvedRequests) ?></span>
                    <span class="kpi-label">Approved</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Request Queue</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Process and track procurement requests.</p>
            </div>
            
            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Search PR number or requestor" autocomplete="off">
                </div>
                
                <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('procurement/purchase-requests') ?>" style="margin: 0; display: flex; gap: 8px;">
                    <select id="status" name="status" class="filter-select" aria-label="Filter by status">
                        <option value="">All Statuses</option>
                        <?php foreach ($requestStatusOptions as $val => $lbl): ?>
                            <option value="<?= esc($val) ?>" <?= (($status ?? '') === $val) ? 'selected' : '' ?>><?= esc($lbl) ?></option>
                        <?php endforeach ?>
                    </select>
                    <button type="button" class="btn btn-outline" id="btn-clear-search" style="padding: 8px 16px; font-size: 0.8rem; font-weight: 800; border-radius: 6px; border: 1px solid var(--v2-border); color: var(--v2-title); background: #ffffff;">Clear</button>
                    <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.8rem; font-weight: 800; border-radius: 6px; background: var(--v2-label); color: #ffffff; border: none;">Filter Server</button>
                </form>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="pr-table" style="table-layout: fixed; width: 100%; min-width: 1320px;">
                <colgroup>
                    <col style="width: 6%;">  
                    <col style="width: 16%;">   
                    <col style="width: 15%;">   
                    <col style="width: 12%;">   
                    <col style="width: 15%;">   
                    <col style="width: 18%;">    
                    <col style="width: 18%;"> 
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">PR Number</th>
                        <th class="sortable" data-col="2">Requested By</th>
                        <th class="sortable date" data-col="3">Date</th>
                        <th class="sortable" data-col="4" id="status-header" title="Click to cycle status filters!">
                            Status <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>
                        </th>
                        <th>Remarks</th>
                        <th class="actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($rows ?? []) === []): ?>
                        <tr class="no-records-row">
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No purchase requests found.</strong><br>
                                <span style="font-size: 0.8rem;">Adjust your filters or create a new request.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $request): ?>
                            <tr class="pr-row" style="display: none;" data-status="<?= esc(strtolower((string) ($request['status'] ?? ''))) ?>">
                                <td style="font-weight: 800; color: #94a3b8;"><?= esc((string) $request['id']) ?></td>
                                <td><a href="<?= site_url('procurement/purchase-requests/' . $request['id']) ?>" style="font-family: var(--font-mono); font-weight: 800; color: var(--v2-label); text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'"><?= esc((string)$request['pr_number']) ?></a></td>
                                <td style="font-weight: 800; color: var(--v2-text-main);"><?= esc((string) $request['requested_by']) ?></td>
                                <td style="white-space: nowrap; font-size: 0.8rem; font-weight: 600; color: var(--v2-text-main);"><?= esc((string) $request['request_date']) ?></td>
                                
                                <td class="status-cell">
                                    <?php if ((bool) ($request['uses_special_status_badge'] ?? false)): ?>
                                        <span class="status-badge-special status-badge-special--sky" title="<?= esc((string) ($request['status_label'] ?? 'Converted to PO')) ?>"><?= esc((string) ($request['status_label'] ?? 'Converted to PO')) ?></span>
                                    <?php else: ?>
                                        <?= view('components/shared/table_status_badge', [
                                            'status' => $request['status'] ?? 'unknown',
                                            'label' => $request['status_label'] ?? null,
                                        ]) ?>
                                    <?php endif ?>
                                </td>
                                
                                <td class="remarks-cell" title="<?= esc((string) ($request['remarks'] ?? '')) ?>"><?= esc((string) ($request['remarks'] ?? '')) ?></td>
                                
                                <td>
                                    <div class="action-row">
                                        <?php if (($request['status'] ?? '') === 'draft'): ?>
                                            <?php if ($canCreatePr): ?>
                                                <form method="post" action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/submit') ?>" style="margin:0">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn-table btn-submit-blue">Submit</button>
                                                </form>
                                                <a class="btn-table btn-edit-outline" href="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/edit') ?>">Edit</a>
                                                <form method="post"
                                                      data-confirm="Cancel this draft request? This cannot be undone."
                                                      data-confirm-title="Cancel Draft"
                                                      action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/cancel') ?>" style="margin:0">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn-table btn-cancel-red">Cancel</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="action-badge-neutral">Read-only</span>
                                            <?php endif ?>

                                        <?php elseif (($request['status'] ?? '') === 'submitted'): ?>
                                            <?php if ($canCreatePr): ?>
                                                <form method="post"
                                                      data-confirm="Cancel this submitted request? It will need to be re-submitted for approval."
                                                      data-confirm-title="Cancel Submitted Request"
                                                      action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/cancel') ?>" style="margin:0">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn-table btn-cancel-red">Cancel</button>
                                                </form>
                                            <?php endif ?>

                                        <?php elseif (($request['status'] ?? '') === 'approved' && $canCreatePo): ?>
                                            <button type="button" class="btn-create-po btn-table" 
                                                    onclick="openPoModal(<?= $request['id'] ?>, '<?= esc((string)$request['pr_number']) ?>')">
                                                Create PO
                                            </button>

                                        <?php elseif (($request['status'] ?? '') === 'approved'): ?>
                                            <span class="action-badge-waiting">⏳ Awaiting PO</span>

                                        <?php elseif (($request['status'] ?? '') === 'converted_to_po'): ?>
                                            <?php if ($canCreatePo || $canManagePoRequests): ?>
                                                <a href="<?= site_url('procurement/purchase-orders') ?>" class="btn-table po-nav-btn">PO &rarr;</a>
                                            <?php else: ?>
                                                <span class="action-badge-done">✓ Converted</span>
                                            <?php endif ?>
                                        <?php else: ?>
                                            <span class="muted" style="font-size: 0.75rem;">&mdash;</span>
                                        <?php endif ?>
                                        
                                        <a class="btn-link-view view-action" href="<?= site_url('procurement/purchase-requests/' . $request['id']) ?>">View</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalRequests) ?></span>)
            </p>
            <nav aria-label="Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
        <div class="modal-overlay" id="poModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Convert to Purchase Order</h3>
                    <button type="button" class="btn-close-modal" onclick="closePoModal()">&times;</button>
                </div>
                <form id="poForm" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <p id="modal-pr-text" style="margin-bottom:16px; font-size:0.85rem; color: var(--v2-text-muted);"></p>
                        <div class="field">
                            <label for="supplier_id">Please select a Supplier for this order: <span style="color: #ef4444;">*</span></label>
                            <select id="supplier_id" name="supplier_id" required>
                                <option value="">-- Select Supplier --</option>
                                <?php foreach ($supplierCatalog as $supplier): ?>
                                    <option value="<?= esc((string) ($supplier['id'] ?? '')) ?>">
                                        <?= esc((string) ($supplier['supplier_name'] ?? '')) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                            <p class="muted" style="margin: 6px 0 0; font-size: 0.75rem;">Manage supplier records from the admin catalog.</p>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-outline" style="padding: 8px 16px; border-radius: 6px; font-weight: 800; font-size: 0.85rem;" onclick="closePoModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="padding: 8px 16px; border-radius: 6px; font-weight: 800; font-size: 0.85rem; background: #7C3AED; color: #ffffff; border: none;">Confirm & Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/procurement-queue.js') ?>"></script>
<script>
    (function () {
        const statusLabels = <?= json_encode($requestStatusOptions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

        window.openPoModal = function (prId, prNumber) {
            const modal = document.getElementById('poModal');
            const form = document.getElementById('poForm');
            const text = document.getElementById('modal-pr-text');
            const supplierSelect = document.getElementById('supplier_id');

            form.action = "<?= site_url('procurement/purchase-orders/from-pr/') ?>" + prId;
            text.innerText = 'Convert request ' + prNumber + ' to a finalized Purchase Order.';

            if (supplierSelect) {
                supplierSelect.value = '';
            }

            modal.classList.add('active');
        };

        window.closePoModal = function () {
            document.getElementById('poModal').classList.remove('active');
        };

        window.InventoryV2ProcurementQueue.init({
            tableSelector: '#pr-table',
            rowSelector: '.pr-row',
            searchInputSelector: '#instant-search-input',
            clearButtonSelector: '#btn-clear-search',
            pagerSelector: '#client-pager',
            pageIndicatorSelector: '#page-indicator',
            totalIndicatorSelector: '#total-indicator',
            filter: {
                headerSelector: '#status-header',
                title: 'Status',
                values: ['All', ...Object.keys(statusLabels)],
                attribute: 'data-status',
                labelFor: function (value) {
                    return statusLabels[value] || value.split('_').map(function (word) {
                        return word.charAt(0).toUpperCase() + word.slice(1);
                    }).join(' ');
                }
            },
            searchMatcher: function (row, query) {
                const prNumber = row.children[1].innerText.toLowerCase();
                const requestedBy = row.children[2].innerText.toLowerCase();

                return prNumber.includes(query) || requestedBy.includes(query);
            },
            updateKpis: function (rows) {
                let draftCount = 0;
                let submittedCount = 0;
                let approvedCount = 0;

                rows.forEach(function (row) {
                    const status = row.getAttribute('data-status');

                    if (status === 'draft') {
                        draftCount++;
                    }

                    if (status === 'submitted') {
                        submittedCount++;
                    }

                    if (status === 'approved') {
                        approvedCount++;
                    }
                });

                document.getElementById('kpi-visible').innerText = rows.length;
                document.getElementById('kpi-draft').innerText = draftCount;
                document.getElementById('kpi-submitted').innerText = submittedCount;
                document.getElementById('kpi-approved').innerText = approvedCount;
            }
        });
    })();
</script>
<?= $this->endSection() ?>
