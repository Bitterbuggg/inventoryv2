<?php

declare(strict_types=1);

$title = 'Pending Approvals - InventoryV2';
$pageTitle = 'Procurement - Pending Approvals';
$pageSubtitle = 'Review and decide submitted approvals in the procurement flow.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Pending Approvals'],
];

$user = function_exists('auth') ? auth()->user() : null;
$canManagePo = $user !== null && method_exists($user, 'can') && $user->can('procurement.po.create');
$canManagePoRequests = $user !== null && method_exists($user, 'can') && $user->can('procurement.por.manage');
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/procurement-queue.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php if ($canManagePo): ?>
    <a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Purchase Orders</a>
<?php endif ?>
<?php if ($canManagePoRequests): ?>
    <a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">PO Requests</a>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $approvals ?? [];
$totalPending = count($rows);
$level1Pending = count(array_filter($rows, static fn (array $row): bool => (string) ($row['approval_level'] ?? '') === '1'));
$prApprovals = count(array_filter($rows, static fn (array $row): bool => (string) ($row['reference_type'] ?? '') === 'purchase_request'));
?>

<div class="procurement-queue procurement-queue--approvals">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Pending Approvals</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-total"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-total"><?= esc((string) $totalPending) ?></span>
                    <span class="kpi-label">Pending Queue</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-violet">
                <div class="kpi-icon-box icon-level"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-l1"><?= esc((string) $level1Pending) ?></span>
                    <span class="kpi-label">Level 1 Submissions</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-royal">
                <div class="kpi-icon-box icon-pr"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-pr"><?= esc((string) $prApprovals) ?></span>
                    <span class="kpi-label">Purchase Requests</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Approval Queue</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Review pending references and submit decisions.</p>
            </div>
            
            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Search by reference type, ID, or PR number..." autocomplete="off">
                </div>
                <button type="button" class="btn btn-outline" id="btn-clear-search" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px;">Clear</button>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="approvals-table" style="table-layout: fixed; width: 100%;">
                <colgroup>
                    <col style="width: 5%;">  
                    <col style="width: 30%;">   
                    <col style="width: 8%;">   
                    <col style="width: 12%;">   
                    <col style="width: 45%;">    
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1" id="ref-header" title="Click to cycle type filters!">
                            Reference <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>
                        </th>
                        <th class="sortable numeric" data-col="2">Level</th>
                        <th class="sortable" data-col="3">Status</th>
                        <th>Decisions & Comments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($approvals ?? []) === []): ?>
                        <tr class="no-records-row">
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No pending approvals found.</strong><br>
                                <span style="font-size: 0.8rem;">Your queue is currently empty.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($approvals as $approval): ?>
                            <tr class="approval-row" style="display: none;" data-type="<?= esc(strtolower((string) ($approval['reference_type'] ?? ''))) ?>">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) $approval['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 600; color: var(--v2-label); font-size: 0.85rem;">
                                    <span style="font-family: var(--font-sans); font-size: 0.7rem; text-transform: uppercase; display: block; opacity: 0.6; font-weight: 700; color: var(--v2-title);"><?= esc(str_replace('_', ' ', (string)$approval['reference_type'])) ?></span>
                                    #<?= esc((string) $approval['reference_id']) ?>
                                    
                                    <?php $pr = $approval['purchase_request'] ?? null; ?>
                                    <?php if (is_array($pr)): ?>
                                        <details style="margin-top: 6px; font-family: var(--font-sans); font-size: 0.75rem;">
                                            <summary style="cursor: pointer; color: var(--v2-label); font-weight: 800;">View Details</summary>
                                            <div style="margin-top: 8px; line-height: 1.5; color: var(--v2-text-main); background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                                <div style="margin-bottom: 6px;">
                                                    <a href="<?= site_url('procurement/purchase-requests/' . (int) ($approval['reference_id'] ?? 0)) ?>" style="font-weight: 700; color: var(--v2-title); text-decoration: underline;">Open Full Request</a>
                                                </div>
                                                <div><strong>PR:</strong> <?= esc((string) ($pr['pr_number'] ?? '-')) ?></div>
                                                <div><strong>Date:</strong> <?= esc((string) ($pr['request_date'] ?? '-')) ?></div>
                                                <div><strong>Requested By:</strong> <?= esc((string) ($pr['requested_by'] ?? '-')) ?></div>
                                                <?php $items = is_array($pr['items'] ?? null) ? $pr['items'] : []; ?>
                                                <div style="margin-top: 4px;"><strong>Items (<?= esc((string) count($items)) ?>):</strong></div>
                                                <?php if ($items !== []): ?>
                                                    <ul style="margin: 4px 0 0 16px; padding: 0; color: var(--v2-text-muted);">
                                                        <?php foreach (array_slice($items, 0, 4) as $item): ?>
                                                            <li>
                                                                <?= esc((string) ($item['item_name'] ?? '')) ?>
                                                                (<?= esc(app_format_quantity($item['requested_qty'] ?? 0)) ?> <?= esc((string) ($item['unit'] ?? 'unit')) ?>)
                                                            </li>
                                                        <?php endforeach ?>
                                                    </ul>
                                                    <?php if (count($items) > 4): ?>
                                                        <div style="margin-top: 4px; font-style: italic; color: #94a3b8;">+<?= esc((string) (count($items) - 4)) ?> more items</div>
                                                    <?php endif ?>
                                                <?php endif ?>
                                            </div>
                                        </details>
                                    <?php endif ?>
                                </td>
                                <td><span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; color: var(--v2-text-main); border: 1px solid #e2e8f0;"><?= esc((string) $approval['approval_level']) ?></span></td>
                                <td><?= view('components/shared/table_status_badge', ['status' => $approval['decision'] ?? 'pending']) ?></td>
                                <td>
                                    <div class="action-container">
                                        <form method="post" action="<?= site_url('procurement/approvals/' . $approval['id'] . '/approve') ?>" style="margin: 0;">
                                            <?= csrf_field() ?>
                                            <div class="approval-input-group">
                                                <input type="text" name="comments" class="approval-comment" placeholder="Approval note...">
                                                <button type="submit" class="btn-action btn-approve">Approve</button>
                                            </div>
                                        </form>

                                        <form method="post" action="<?= site_url('procurement/approvals/' . $approval['id'] . '/reject') ?>" style="margin: 0;">
                                            <?= csrf_field() ?>
                                            <div class="approval-input-group">
                                                <input type="text" name="comments" class="approval-comment" placeholder="Rejection reason..." required>
                                                <button type="submit" class="btn-action btn-reject" title="Reject Request">Reject</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 600; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalPending) ?></span>)
            </p>
            <nav aria-label="Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/procurement-queue.js') ?>"></script>
<script>
    (function () {
        window.InventoryV2ProcurementQueue.init({
            tableSelector: '#approvals-table',
            rowSelector: '.approval-row',
            searchInputSelector: '#instant-search-input',
            clearButtonSelector: '#btn-clear-search',
            pagerSelector: '#client-pager',
            pageIndicatorSelector: '#page-indicator',
            totalIndicatorSelector: '#total-indicator',
            pagerMode: 'full',
            filter: {
                headerSelector: '#ref-header',
                title: 'Reference',
                values: ['All', 'purchase_request'],
                attribute: 'data-type',
                labelFor: function (value) {
                    return value.replace('_', ' ');
                }
            },
            searchMatcher: function (row, query) {
                return row.children[1].innerText.toLowerCase().includes(query);
            },
            sortValue: function (row, colIndex) {
                return row.children[colIndex].innerText.trim().replace('#', '');
            },
            updateKpis: function (rows) {
                let levelOneCount = 0;
                let purchaseRequestCount = 0;

                rows.forEach(function (row) {
                    const type = row.getAttribute('data-type');
                    const level = row.children[2].innerText.trim();

                    if (level === '1') {
                        levelOneCount++;
                    }

                    if (type === 'purchase_request') {
                        purchaseRequestCount++;
                    }
                });

                document.getElementById('kpi-total').innerText = rows.length;
                document.getElementById('kpi-l1').innerText = levelOneCount;
                document.getElementById('kpi-pr').innerText = purchaseRequestCount;
            }
        });
    })();
</script>
<?= $this->endSection() ?>
