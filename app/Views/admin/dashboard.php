<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .actions { margin-top: 1rem; display: flex; gap: 0.75rem; }
        button { padding: 0.55rem 0.85rem; }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?= esc((string) ($user->username ?? 'Admin')) ?>.</p>
    <p>Phase 1 skeleton is active: authentication, RBAC filter, and admin route protection.</p>

    <div class="actions">
        <a href="<?= site_url('admin/users') ?>">Manage Users</a>
        <form method="post" action="<?= site_url('logout') ?>">
            <?= csrf_field() ?>
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>

