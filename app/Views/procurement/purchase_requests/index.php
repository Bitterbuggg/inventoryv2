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
<section class="card stack-md">
    <div class="kpi-grid">
        <article class="kpi-card">
            <p class="kpi-label">Visible Requests</p>
            <p class="kpi-value"><?= esc((string) $totalRequests) ?></p>
            <p class="kpi-note">Current list size after filter.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Draft</p>
            <p class="kpi-value"><?= esc((string) $draftRequests) ?></p>
            <p class="kpi-note">Ready for review and submit.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Submitted</p>
            <p class="kpi-value"><?= esc((string) $submittedRequests) ?></p>
            <p class="kpi-note">Awaiting approval actions.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Approved</p>
            <p class="kpi-value"><?= esc((string) $approvedRequests) ?></p>
            <p class="kpi-note">Eligible for PO generation.</p>
        </article>
    </div>
</section>

<section class="card stack-md">
    <div class="stack-sm">
        <h2>Request Queue</h2>
        <p class="muted">Filter requests and process actions directly from this list.</p>
    </div>

    <form class="inline-form" method="get" action="<?= site_url('procurement/purchase-requests') ?>">
        <label for="status">Filter status</label>
        <select id="status" name="status">
            <option value="">All</option>
            <?php foreach (['draft', 'submitted', 'approved', 'rejected', 'cancelled', 'converted_to_po'] as $option): ?>
                <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit" class="btn btn-outline">Apply</button>
        <a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Reset</a>
    </form>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>PR Number</th>
                    <th>Requested By</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($requests ?? []) === []): ?>
                    <tr>
                        <td colspan="7" class="empty-state">No purchase requests found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?= esc((string) $request['id']) ?></td>
                            <td><?= esc((string) $request['pr_number']) ?></td>
                            <td><?= esc((string) $request['requested_by']) ?></td>
                            <td><?= esc((string) $request['request_date']) ?></td>
                            <td><?= view('components/shared/table_status_badge', ['status' => $request['status'] ?? 'unknown']) ?></td>
                            <td><?= esc((string) ($request['remarks'] ?? '')) ?></td>
                            <td>
                                <div class="toolbar">
                                    <?php if (($request['status'] ?? '') === 'draft'): ?>
                                        <form class="inline-form" method="post" action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/submit') ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-primary">Submit Request</button>
                                        </form>
                                        <a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/edit') ?>">Edit Draft</a>
                                    <?php endif ?>

                                    <?php if (in_array((string) ($request['status'] ?? ''), ['draft', 'submitted'], true)): ?>
                                        <form class="inline-form" method="post" action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/cancel') ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger">Cancel Request</button>
                                        </form>
                                    <?php endif ?>

                                    <?php if (($request['status'] ?? '') === 'approved'): ?>
                                        <form class="inline-form" method="post" action="<?= site_url('procurement/purchase-orders/from-pr/' . $request['id']) ?>">
                                            <?= csrf_field() ?>
                                            <input type="text" name="supplier_name" placeholder="Supplier name (optional)">
                                            <button type="submit" class="btn btn-primary">Create PO</button>
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

