<?php

declare(strict_types=1);

$title = 'Admin Dashboard - InventoryV2';
$pageTitle = 'Admin Dashboard';
$pageSubtitle = 'Operational snapshot inspired by Ample-style admin panels, aligned to your current workflow modules.';

$moduleStatus = [
    ['name' => 'Auth and RBAC', 'status' => 'Active', 'note' => 'Shield login, signup, role guards'],
    ['name' => 'Procurement', 'status' => 'Active', 'note' => 'PR, approvals, PO, PO requests'],
    ['name' => 'Receiving', 'status' => 'Active', 'note' => 'Draft, validate, post, void'],
    ['name' => 'Inventory and Reports', 'status' => 'Active', 'note' => 'Issuance, stock balances, analytics'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <p>Welcome, <?= esc((string) ($user->username ?? 'Admin')) ?>.</p>
    <p class="muted">Use this panel as your launch point for administration, transaction workflows, and reporting.</p>

    <div class="kpi-grid">
        <?php foreach ($moduleStatus as $card): ?>
            <article class="kpi-card">
                <p class="kpi-label"><?= esc((string) $card['name']) ?></p>
                <p class="kpi-value"><?= esc((string) $card['status']) ?></p>
                <p class="kpi-note"><?= esc((string) $card['note']) ?></p>
            </article>
        <?php endforeach ?>
    </div>
</section>

<section class="card stack-md">
    <div class="stack-sm">
        <h2>Quick Actions</h2>
        <p class="muted">Use grouped shortcuts to jump into transaction workflows and reporting.</p>
    </div>

    <div class="stack-sm">
        <h3>Operations</h3>
        <div class="toolbar">
            <a class="btn btn-outline" href="<?= site_url('admin/users') ?>">Manage Users</a>
            <a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Purchase Requests</a>
            <a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>">Pending Approvals</a>
            <a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
            <a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
            <a class="btn btn-outline" href="<?= site_url('receiving') ?>">Receiving</a>
            <a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
            <a class="btn btn-outline" href="<?= site_url('inventory/issuance') ?>">Inventory Issuance</a>
        </div>
    </div>

    <div class="stack-sm">
        <h3>Reports and Analytics</h3>
        <div class="toolbar">
            <a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
            <a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
            <a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>">Issuances Report</a>
            <a class="btn btn-outline" href="<?= site_url('analytics/dashboard') ?>">Analytics Dashboard</a>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

