<?php

declare(strict_types=1);

$title = 'Receiving - InventoryV2';
$pageTitle = 'Receiving';
$pageSubtitle = 'Track receiving drafts, posting status, and conversion-ready PO requests.';
$crumbs = [
    ['label' => 'Receiving'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <form class="inline-form" method="get" action="<?= site_url('receiving') ?>">
        <label for="status">Filter status</label>
        <select id="status" name="status">
            <option value="">All</option>
            <?php foreach (['draft', 'posted', 'voided'] as $option): ?>
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
                    <th>Receiving Number</th>
                    <th>PO Request</th>
                    <th>Received Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($receivings ?? []) === []): ?>
                    <tr><td colspan="6" class="empty-state">No receiving records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($receivings as $receiving): ?>
                        <tr>
                            <td><?= esc((string) $receiving['id']) ?></td>
                            <td><?= esc((string) $receiving['receiving_number']) ?></td>
                            <td>#<?= esc((string) $receiving['po_request_id']) ?></td>
                            <td><?= esc((string) $receiving['received_date']) ?></td>
                            <td><?= view('components/shared/table_status_badge', ['status' => $receiving['status'] ?? 'unknown']) ?></td>
                            <td><a class="btn btn-outline" href="<?= site_url('receiving/' . $receiving['id']) ?>">View</a></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>

<section class="card stack-md" style="margin-top: 16px;">
    <h2>Approved PO Requests Ready for Conversion</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>PO Request ID</th>
                    <th>Purchase Order ID</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($convertiblePoRequests ?? []) === []): ?>
                    <tr><td colspan="4" class="empty-state">No approved PO requests available.</td></tr>
                <?php else: ?>
                    <?php foreach ($convertiblePoRequests as $poRequest): ?>
                        <tr>
                            <td><?= esc((string) $poRequest['id']) ?></td>
                            <td><?= esc((string) $poRequest['purchase_order_id']) ?></td>
                            <td><?= view('components/shared/table_status_badge', ['status' => $poRequest['status'] ?? 'unknown']) ?></td>
                            <td>
                                <a class="btn btn-primary" href="<?= site_url('receiving/create/from-po-request/' . $poRequest['id']) ?>">Convert to Receiving</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
