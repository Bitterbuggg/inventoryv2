<?php

declare(strict_types=1);

$title = 'Pending Approvals - InventoryV2';
$pageTitle = 'Procurement - Pending Approvals';
$pageSubtitle = 'Review and decide submitted approvals in the procurement flow.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Pending Approvals'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $approvals ?? [];
$totalPending = count($rows);
$level1Pending = count(array_filter($rows, static fn (array $row): bool => (string) ($row['approval_level'] ?? '') === '1'));
$prApprovals = count(array_filter($rows, static fn (array $row): bool => (string) ($row['reference_type'] ?? '') === 'purchase_request'));
$issuanceApprovals = count(array_filter($rows, static fn (array $row): bool => (string) ($row['reference_type'] ?? '') === 'issuance'));
?>
<section class="card stack-md">
    <div class="kpi-grid">
        <article class="kpi-card">
            <p class="kpi-label">Pending Queue</p>
            <p class="kpi-value"><?= esc((string) $totalPending) ?></p>
            <p class="kpi-note">Approvals requiring a decision.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Level 1</p>
            <p class="kpi-value"><?= esc((string) $level1Pending) ?></p>
            <p class="kpi-note">Initial approval-stage records.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">PR References</p>
            <p class="kpi-value"><?= esc((string) $prApprovals) ?></p>
            <p class="kpi-note">Linked to purchase requests.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Issuance References</p>
            <p class="kpi-value"><?= esc((string) $issuanceApprovals) ?></p>
            <p class="kpi-note">Linked to issuance records.</p>
        </article>
    </div>
</section>

<section class="card stack-md">
    <div class="stack-sm">
        <h2>Approval Queue</h2>
        <p class="muted">Review pending references and submit approve/reject decisions with notes.</p>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reference</th>
                    <th>Level</th>
                    <th>Decision</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($approvals ?? []) === []): ?>
                    <tr>
                        <td colspan="5" class="empty-state">No pending approvals.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($approvals as $approval): ?>
                        <tr>
                            <td><?= esc((string) $approval['id']) ?></td>
                            <td><?= esc((string) $approval['reference_type']) ?> #<?= esc((string) $approval['reference_id']) ?></td>
                            <td><?= esc((string) $approval['approval_level']) ?></td>
                            <td><?= view('components/shared/table_status_badge', ['status' => $approval['decision'] ?? 'pending']) ?></td>
                            <td>
                                <div class="toolbar">
                                    <form class="inline-form" method="post" action="<?= site_url('procurement/approvals/' . $approval['id'] . '/approve') ?>">
                                        <?= csrf_field() ?>
                                        <input type="text" name="comments" placeholder="Optional comment">
                                        <button type="submit" class="btn btn-primary">Approve</button>
                                    </form>
                                    <form class="inline-form" method="post" action="<?= site_url('procurement/approvals/' . $approval['id'] . '/reject') ?>">
                                        <?= csrf_field() ?>
                                        <input type="text" name="comments" placeholder="Rejection reason" required>
                                        <button type="submit" class="btn btn-danger">Reject</button>
                                    </form>
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

