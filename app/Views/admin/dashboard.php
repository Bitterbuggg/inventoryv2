<?php

declare(strict_types=1);

$title = 'Admin Dashboard - InventoryV2';
$pageTitle = 'Admin Dashboard';
$pageSubtitle = 'Central navigation for procurement, receiving, inventory, and reporting workflows.';
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <p>Welcome, <?= esc((string) ($user->username ?? 'Admin')) ?>.</p>
    <p class="muted">Current baseline: Phase 1 auth/RBAC, Phase 2 procurement workflow, Phase 3 receiving/inventory, and Phase 4 issuance/reporting routes are active.</p>

    <div class="toolbar">
        <a class="btn btn-outline" href="<?= site_url('admin/users') ?>">Manage Users</a>
        <a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Purchase Requests</a>
        <a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>">Pending Approvals</a>
        <a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
        <a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
        <a class="btn btn-outline" href="<?= site_url('receiving') ?>">Receiving</a>
        <a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
        <a class="btn btn-outline" href="<?= site_url('inventory/issuance') ?>">Inventory Issuance</a>
        <a class="btn btn-outline" href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
        <a class="btn btn-outline" href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
        <a class="btn btn-outline" href="<?= site_url('reports/issuances') ?>">Issuances Report</a>
    </div>
</section>
<?= $this->endSection() ?>
