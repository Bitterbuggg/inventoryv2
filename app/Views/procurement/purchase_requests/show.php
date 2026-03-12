<?php

declare(strict_types=1);

$title = 'Purchase Request Details - InventoryV2';
$pageTitle = 'Purchase Request #' . (string) ($purchaseRequest['pr_number'] ?? '');
$pageSubtitle = 'Review full purchase request content and line items.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Details'],
];

$user = function_exists('auth') ? auth()->user() : null;
$isAdmin = $user !== null && method_exists($user, 'inGroup') && $user->inGroup('admin');
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Back to Requests</a>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests/' . (int) ($purchaseRequest['id'] ?? 0) . '/items.csv') ?>">Export Items CSV</a>
<?php if (($purchaseRequest['status'] ?? '') === 'draft'): ?>
<a class="btn btn-primary" href="<?= site_url('procurement/purchase-requests/' . (int) ($purchaseRequest['id'] ?? 0) . '/edit') ?>">Edit Draft</a>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$items = $purchaseRequest['items'] ?? [];
$totalRequested = array_sum(array_map(static fn (array $row): float => (float) ($row['requested_qty'] ?? 0), $items));
$totalEstimated = array_sum(array_map(static fn (array $row): float => (float) ($row['estimated_unit_cost'] ?? 0) * (float) ($row['requested_qty'] ?? 0), $items));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Status</p>
                <p class="kpi-value"><?= esc(ucfirst((string) ($purchaseRequest['status'] ?? 'unknown'))) ?></p>
                <p class="kpi-note">Current workflow state.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Item Lines</p>
                <p class="kpi-value"><?= esc((string) count($items)) ?></p>
                <p class="kpi-note">Requested line entries.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Total Qty</p>
                <p class="kpi-value"><?= esc(number_format($totalRequested, 0)) ?></p>
                <p class="kpi-note">Whole-number item request quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Estimated Cost</p>
                <p class="kpi-value">PHP <?= esc(number_format($totalEstimated, 2)) ?></p>
                <p class="kpi-note">Based on requested qty x estimated unit cost.</p>
            </article>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">PR Number</span>
                <span class="detail-value"><?= esc((string) ($purchaseRequest['pr_number'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Requested By</span>
                <span class="detail-value"><?= esc((string) ($purchaseRequest['requested_by'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Request Date</span>
                <span class="detail-value"><?= esc((string) ($purchaseRequest['request_date'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Needed Date</span>
                <span class="detail-value"><?= esc((string) ($purchaseRequest['needed_date'] ?? '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Remarks</span>
                <span class="detail-value"><?= esc((string) ($purchaseRequest['remarks'] ?? '-')) ?></span>
            </div>
        </div>

        <?php if (($purchaseRequest['status'] ?? '') === 'submitted' && $isAdmin): ?>
            <div class="toolbar">
                <a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>">Open Pending Approvals</a>
            </div>
        <?php endif ?>
    </section>

    <section class="card stack-md">
        <h2>Requested Items</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Unit</th>
                        <th>Requested Qty</th>
                        <th>Approved Qty</th>
                        <th>Est. Unit Cost</th>
                        <th>Line Estimate</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($items === []): ?>
                        <tr><td colspan="8" class="empty-state">No purchase request items found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $qty = (float) ($item['requested_qty'] ?? 0);
                            $cost = (float) ($item['estimated_unit_cost'] ?? 0);
                            ?>
                            <tr>
                                <td><?= esc((string) ($item['id'] ?? '')) ?></td>
                                <td><?= esc((string) ($item['item_name'] ?? '')) ?></td>
                                <td><?= esc((string) ($item['unit'] ?? '')) ?></td>
                                <td><?= esc((string) ((int) round($qty))) ?></td>
                                <td><?= esc((string) ($item['approved_qty'] ?? '-')) ?></td>
                                <td><?= esc(number_format($cost, 2)) ?></td>
                                <td><?= esc(number_format($qty * $cost, 2)) ?></td>
                                <td><?= esc((string) ($item['notes'] ?? '')) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
