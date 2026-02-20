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

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>">Pending Approvals</a>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $poRequests ?? [];
$totalRequests = count($rows);
$pendingRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'pending'));
$approvedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'approved'));
$rejectedRequests = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'rejected'));
?>
<section class="card stack-md">
    <div class="kpi-grid">
        <article class="kpi-card">
            <p class="kpi-label">Visible PO Requests</p>
            <p class="kpi-value"><?= esc((string) $totalRequests) ?></p>
            <p class="kpi-note">Requests in the selected filter.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Pending</p>
            <p class="kpi-value"><?= esc((string) $pendingRequests) ?></p>
            <p class="kpi-note">Ready for approval action.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Approved</p>
            <p class="kpi-value"><?= esc((string) $approvedRequests) ?></p>
            <p class="kpi-note">Can proceed to receiving flow.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Rejected</p>
            <p class="kpi-value"><?= esc((string) $rejectedRequests) ?></p>
            <p class="kpi-note">Require correction and resubmission.</p>
        </article>
    </div>
</section>

<section class="card stack-md">
    <form class="inline-form" method="get" action="<?= site_url('procurement/po-requests') ?>">
        <label for="status">Filter status</label>
        <select id="status" name="status">
            <option value="">All</option>
            <?php foreach (['pending', 'approved', 'rejected', 'converted_to_receiving', 'closed'] as $option): ?>
                <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit" class="btn btn-outline">Apply</button>
    </form>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>PO Request Number</th>
                    <th>PO ID</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Approved/Rej By</th>
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
                        <tr>
                            <td><?= esc((string) $poRequest['id']) ?></td>
                            <td><?= esc((string) $poRequest['po_request_number']) ?></td>
                            <td><?= esc((string) $poRequest['purchase_order_id']) ?></td>
                            <td><?= esc((string) $poRequest['request_date']) ?></td>
                            <td><?= view('components/shared/table_status_badge', ['status' => $poRequest['status'] ?? 'unknown']) ?></td>
                            <td><?= esc((string) ($poRequest['approved_by'] ?? $poRequest['rejected_by'] ?? '')) ?></td>
                            <td>
                                <div class="toolbar">
                                    <?php if (($poRequest['status'] ?? '') === 'pending'): ?>
                                        <form class="inline-form" method="post" action="<?= site_url('procurement/po-requests/' . $poRequest['id'] . '/approve') ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-primary">Approve</button>
                                        </form>

                                        <form class="inline-form" method="post" action="<?= site_url('procurement/po-requests/' . $poRequest['id'] . '/reject') ?>">
                                            <?= csrf_field() ?>
                                            <input type="text" name="reason" placeholder="Rejection reason" required>
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </form>
                                    <?php endif ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
