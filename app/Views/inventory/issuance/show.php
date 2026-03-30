<?php

declare(strict_types=1);

$title = 'Issuance Details - InventoryV2';
$pageTitle = 'Issuance #' . (string) ($issuance['issuance_number'] ?? '');
$pageSubtitle = 'Review issuance details and process submit/approval/release actions.';
$crumbs = [
    ['label' => 'Inventory Issuance', 'url' => site_url('inventory/issuance')],
    ['label' => 'Issuance Details'],
];

$user = function_exists('auth') ? auth()->user() : null;
$canCreateIssuance = $user !== null && method_exists($user, 'can') && $user->can('inventory.issuance.create');
$canApproveIssuance = $user !== null && method_exists($user, 'can') && $user->can('inventory.issuance.approve');
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('inventory/issuance') ?>">Back to Issuance List</a>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
<a class="btn btn-outline" href="<?= site_url('inventory/issuance/' . $issuance['id'] . '/items.csv') ?>">Export Items CSV</a>
<a class="btn btn-outline" href="<?= site_url('inventory/issuance/' . $issuance['id'] . '/allocations.csv') ?>">Export Allocations CSV</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$itemRows = $issuance['items'] ?? [];
$allocationRows = $issuance['allocations'] ?? [];
$totalRequested = array_sum(array_map(static fn (array $row): float => (float) ($row['requested_qty'] ?? 0), $itemRows));
$totalIssued = array_sum(array_map(static fn (array $row): float => (float) ($row['issued_qty'] ?? 0), $itemRows));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Status</p>
                <p class="kpi-value"><?= esc(ucfirst((string) ($issuance['status'] ?? 'unknown'))) ?></p>
                <p class="kpi-note">Current issuance workflow state.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Line Items</p>
                <p class="kpi-value"><?= esc((string) count($itemRows)) ?></p>
                <p class="kpi-note">Items included in this request.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Requested Qty</p>
                <p class="kpi-value"><?= esc(app_format_quantity($totalRequested)) ?></p>
                <p class="kpi-note">Total requested quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Issued Qty</p>
                <p class="kpi-value"><?= esc(app_format_quantity($totalIssued)) ?></p>
                <p class="kpi-note">Total released quantity.</p>
            </article>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Status</span>
                <span class="detail-value"><?= view('components/shared/table_status_badge', ['status' => $issuance['status'] ?? 'unknown']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Requestor ID</span>
                <span class="detail-value"><?= esc((string) ($issuance['requestor_id'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Issue Date</span>
                <span class="detail-value"><?= esc((string) ($issuance['issue_date'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Department</span>
                <span class="detail-value"><?= esc((string) ($issuance['department'] ?? '')) ?></span>
            </div>
        </div>

        <div class="toolbar">
            <?php if (($issuance['status'] ?? '') === 'draft'): ?>
                <?php if ($canCreateIssuance): ?>
                    <form class="inline-form" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/submit') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                <?php endif ?>
            <?php endif ?>

            <?php if (($issuance['status'] ?? '') === 'submitted' && $canApproveIssuance): ?>
                <form class="inline-form" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/approve') ?>">
                    <?= csrf_field() ?>
                    <input type="text" name="comments" placeholder="Optional comment">
                    <button type="submit" class="btn btn-primary">Approve</button>
                </form>
                <form class="inline-form" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/reject') ?>">
                    <?= csrf_field() ?>
                    <input type="text" name="reason" placeholder="Rejection reason (required)" required aria-label="Rejection reason">
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            <?php endif ?>

            <?php if (($issuance['status'] ?? '') === 'submitted' && ! $canApproveIssuance): ?>
                <span class="muted" style="font-size: 0.85rem;">Awaiting approval.</span>
            <?php endif ?>

            <?php if (($issuance['status'] ?? '') === 'approved' && $canApproveIssuance): ?>
                <form class="inline-form" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/release') ?>" data-confirm="Release this issuance now? Stock will be deducted and FEFO allocations will be finalized." data-confirm-title="Release Issuance">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary">Release</button>
                </form>
            <?php endif ?>

            <?php if (($issuance['status'] ?? '') === 'approved' && ! $canApproveIssuance): ?>
                <span class="muted" style="font-size: 0.85rem;">Awaiting release approval.</span>
            <?php endif ?>

            <?php if (in_array((string) ($issuance['status'] ?? ''), ['draft', 'submitted'], true) && $canCreateIssuance): ?>
                <form class="inline-form" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/cancel') ?>" data-confirm="Cancel this issuance request? This action cannot be undone." data-confirm-title="Cancel Issuance">
                    <?= csrf_field() ?>
                    <input type="text" name="reason" placeholder="Cancel reason (optional)">
                    <button type="submit" class="btn btn-danger">Cancel</button>
                </form>
            <?php endif ?>
        </div>

        <?php if (($issuance['status'] ?? '') === 'approved' && $canApproveIssuance): ?>
            <div class="status-callout status-callout-warning">
                <strong>Release reminder:</strong> Releasing this issuance immediately deducts stock using FEFO and creates stock movement history.
            </div>
        <?php endif ?>
    </section>

    <section class="card stack-md">
        <h2>Items</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item</th>
                        <th>Unit</th>
                        <th>Requested Qty</th>
                        <th>Issued Qty</th>
                        <th>Unit Cost</th>
                        <th>Line Total</th>
                        <th>Stock ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($issuance['items'] ?? []) === []): ?>
                        <tr><td colspan="8" class="empty-state">No issuance items found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($issuance['items'] as $item): ?>
                            <tr>
                                <td><?= esc((string) $item['id']) ?></td>
                                <td><?= esc((string) $item['item_name']) ?></td>
                                <td><?= esc((string) $item['unit']) ?></td>
                                <td><?= esc(app_format_quantity($item['requested_qty'] ?? 0)) ?></td>
                                <td><?= esc(app_format_quantity($item['issued_qty'] ?? 0)) ?></td>
                                <td><?= esc(number_format((float) ($item['unit_cost'] ?? 0), 2)) ?></td>
                                <td><?= esc(number_format((float) ($item['line_total'] ?? 0), 2)) ?></td>
                                <td><?= esc((string) ($item['inventory_stock_id'] ?? '')) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="card stack-md">
        <h2>FEFO Allocations</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Unit</th>
                        <th>Batch</th>
                        <th>Lot</th>
                        <th>Expiry</th>
                        <th>Qty Issued</th>
                        <th>Unit Cost</th>
                        <th>Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($allocationRows === []): ?>
                        <tr><td colspan="8" class="empty-state">No allocation rows yet (available after release).</td></tr>
                    <?php else: ?>
                        <?php foreach ($allocationRows as $allocation): ?>
                            <tr>
                                <td><?= esc((string) ($allocation['item_name'] ?? '')) ?></td>
                                <td><?= esc((string) ($allocation['unit'] ?? '')) ?></td>
                                <td><?= esc((string) ($allocation['batch_no'] ?? '')) ?></td>
                                <td><?= esc((string) ($allocation['lot_no'] ?? '')) ?></td>
                                <td><?= esc((string) ($allocation['expiry_date'] ?? '')) ?></td>
                                <td><?= esc(app_format_quantity($allocation['qty_issued'] ?? 0)) ?></td>
                                <td><?= esc(number_format((float) ($allocation['unit_cost'] ?? 0), 2)) ?></td>
                                <td><?= esc(number_format((float) ($allocation['line_total'] ?? 0), 2)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

