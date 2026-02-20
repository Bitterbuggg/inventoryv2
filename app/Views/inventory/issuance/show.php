<?php

declare(strict_types=1);

$title = 'Issuance Details - InventoryV2';
$pageTitle = 'Issuance #' . (string) ($issuance['issuance_number'] ?? '');
$pageSubtitle = 'Review issuance details and process submit/approval/release actions.';
$crumbs = [
    ['label' => 'Inventory Issuance', 'url' => site_url('inventory/issuance')],
    ['label' => 'Issuance Details'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('inventory/issuance') ?>">Back to Issuance List</a>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <p><strong>Status:</strong> <?= view('components/shared/table_status_badge', ['status' => $issuance['status'] ?? 'unknown']) ?></p>
    <p><strong>Requestor ID:</strong> <?= esc((string) ($issuance['requestor_id'] ?? '')) ?></p>
    <p><strong>Issue Date:</strong> <?= esc((string) ($issuance['issue_date'] ?? '')) ?></p>
    <p><strong>Department:</strong> <?= esc((string) ($issuance['department'] ?? '')) ?></p>

    <div class="toolbar">
        <?php if (($issuance['status'] ?? '') === 'draft'): ?>
            <form class="inline-form" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/submit') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        <?php endif ?>

        <?php if (($issuance['status'] ?? '') === 'submitted'): ?>
            <form class="inline-form" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/approve') ?>">
                <?= csrf_field() ?>
                <input type="text" name="comments" placeholder="Optional comment">
                <button type="submit" class="btn btn-primary">Approve</button>
            </form>
            <form class="inline-form" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/reject') ?>">
                <?= csrf_field() ?>
                <input type="text" name="reason" placeholder="Rejection reason" required>
                <button type="submit" class="btn btn-danger">Reject</button>
            </form>
        <?php endif ?>

        <?php if (($issuance['status'] ?? '') === 'approved'): ?>
            <form class="inline-form" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/release') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary">Release</button>
            </form>
        <?php endif ?>

        <?php if (in_array((string) ($issuance['status'] ?? ''), ['draft', 'submitted'], true)): ?>
            <form class="inline-form" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/cancel') ?>">
                <?= csrf_field() ?>
                <input type="text" name="reason" placeholder="Cancel reason (optional)">
                <button type="submit" class="btn btn-danger">Cancel</button>
            </form>
        <?php endif ?>
    </div>
</section>

<section class="card stack-md" style="margin-top: 16px;">
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
                            <td><?= esc((string) $item['requested_qty']) ?></td>
                            <td><?= esc((string) $item['issued_qty']) ?></td>
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
<?= $this->endSection() ?>
