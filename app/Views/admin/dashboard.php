<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .actions { margin-top: 1rem; display: flex; gap: 0.75rem; flex-wrap: wrap; }
        button { padding: 0.55rem 0.85rem; }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?= esc((string) ($user->username ?? 'Admin')) ?>.</p>
    <p>Current baseline: Phase 1 auth/RBAC, Phase 2 procurement workflow, Phase 3 receiving/inventory, and Phase 4 issuance/reporting routes are active.</p>

    <div class="actions">
        <a href="<?= site_url('admin/users') ?>">Manage Users</a>
        <a href="<?= site_url('procurement/purchase-requests') ?>">Procurement - Purchase Requests</a>
        <a href="<?= site_url('procurement/approvals/pending') ?>">Procurement - Pending Approvals</a>
        <a href="<?= site_url('procurement/purchase-orders') ?>">Procurement - Purchase Orders</a>
        <a href="<?= site_url('procurement/po-requests') ?>">Procurement - PO Requests</a>
        <a href="<?= site_url('receiving') ?>">Receiving</a>
        <a href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
        <a href="<?= site_url('inventory/issuance') ?>">Inventory Issuance</a>
        <a href="<?= site_url('reports/stock-balance') ?>">Reports - Stock Balance</a>
        <a href="<?= site_url('reports/stock-movements') ?>">Reports - Stock Movements</a>
        <a href="<?= site_url('reports/issuances') ?>">Reports - Issuances</a>
        <form method="post" action="<?= site_url('logout') ?>">
            <?= csrf_field() ?>
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>

