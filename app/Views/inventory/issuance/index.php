<?php

declare(strict_types=1);

$title = 'Issuance - InventoryV2';
$pageTitle = 'Inventory Issuance';
$pageSubtitle = 'Create, review, and track issuance workflow states.';
$crumbs = [
    ['label' => 'Inventory Issuance'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-primary" href="<?= site_url('inventory/issuance/create') ?>">Create Issuance</a>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
<a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Reports</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <form class="inline-form" method="get" action="<?= site_url('inventory/issuance') ?>">
        <label for="status">Filter status</label>
        <select id="status" name="status">
            <option value="">All</option>
            <?php foreach (['draft', 'submitted', 'approved', 'rejected', 'released', 'cancelled'] as $option): ?>
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
                    <th>Issuance Number</th>
                    <th>Requestor</th>
                    <th>Issue Date</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($issuances ?? []) === []): ?>
                    <tr><td colspan="7" class="empty-state">No issuance records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($issuances as $issuance): ?>
                        <tr>
                            <td><?= esc((string) $issuance['id']) ?></td>
                            <td><?= esc((string) $issuance['issuance_number']) ?></td>
                            <td><?= esc((string) $issuance['requestor_id']) ?></td>
                            <td><?= esc((string) $issuance['issue_date']) ?></td>
                            <td><?= esc((string) ($issuance['department'] ?? '')) ?></td>
                            <td><?= view('components/shared/table_status_badge', ['status' => $issuance['status'] ?? 'unknown']) ?></td>
                            <td><a class="btn btn-outline" href="<?= site_url('inventory/issuance/' . $issuance['id']) ?>">View</a></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
