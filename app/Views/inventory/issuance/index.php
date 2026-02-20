<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issuance - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .bar { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .message { color: #0a7a2a; }
        .error { color: #b00020; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; vertical-align: top; }
        th { background: #f6f6f6; text-align: left; }
        select, button { padding: 0.4rem; }
    </style>
</head>
<body>
    <h1>Inventory Issuance</h1>

    <div class="bar">
        <a href="<?= site_url('inventory/issuance/create') ?>">Create Issuance</a>
        <a href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
        <a href="<?= site_url('reports/stock-balance') ?>">Reports</a>
        <a href="<?= site_url('admin/dashboard') ?>">Admin Dashboard</a>
    </div>

    <?php if (session('message')): ?>
        <p class="message"><?= esc((string) session('message')) ?></p>
    <?php endif ?>

    <?php if (session('error')): ?>
        <p class="error"><?= esc((string) session('error')) ?></p>
    <?php endif ?>

    <form method="get" action="<?= site_url('inventory/issuance') ?>">
        <label for="status">Filter status:</label>
        <select id="status" name="status">
            <option value="">All</option>
            <?php foreach (['draft', 'submitted', 'approved', 'rejected', 'released', 'cancelled'] as $option): ?>
                <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit">Apply</button>
    </form>

    <table>
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
                <tr><td colspan="7">No issuance records found.</td></tr>
            <?php else: ?>
                <?php foreach ($issuances as $issuance): ?>
                    <tr>
                        <td><?= esc((string) $issuance['id']) ?></td>
                        <td><?= esc((string) $issuance['issuance_number']) ?></td>
                        <td><?= esc((string) $issuance['requestor_id']) ?></td>
                        <td><?= esc((string) $issuance['issue_date']) ?></td>
                        <td><?= esc((string) ($issuance['department'] ?? '')) ?></td>
                        <td><?= esc((string) $issuance['status']) ?></td>
                        <td><a href="<?= site_url('inventory/issuance/' . $issuance['id']) ?>">View</a></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</body>
</html>
