<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issuance Report - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .bar { display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; }
        th { background: #f6f6f6; text-align: left; }
        input, select, button { padding: 0.4rem; }
    </style>
</head>
<body>
    <h1>Report: Issuances</h1>

    <div class="bar">
        <a href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
        <a href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
        <a href="<?= site_url('reports/low-stock') ?>">Low Stock</a>
        <a href="<?= site_url('reports/fast-moving') ?>">Fast Moving</a>
    </div>

    <form method="get" action="<?= site_url('reports/issuances') ?>">
        <label>Status</label>
        <select name="status">
            <option value="">All</option>
            <?php foreach (['draft', 'submitted', 'approved', 'rejected', 'released', 'cancelled'] as $opt): ?>
                <option value="<?= esc($opt) ?>" <?= (($status ?? '') === $opt) ? 'selected' : '' ?>><?= esc($opt) ?></option>
            <?php endforeach ?>
        </select>
        <label>Date From</label>
        <input type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>">
        <label>Date To</label>
        <input type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>">
        <button type="submit">Apply</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Issuance #</th>
                <th>Requestor</th>
                <th>Issue Date</th>
                <th>Department</th>
                <th>Status</th>
                <th>Total Requested</th>
                <th>Total Issued</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($rows ?? []) === []): ?>
                <tr><td colspan="8">No records found.</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= esc((string) $row['id']) ?></td>
                        <td><?= esc((string) $row['issuance_number']) ?></td>
                        <td><?= esc((string) $row['requestor_id']) ?></td>
                        <td><?= esc((string) $row['issue_date']) ?></td>
                        <td><?= esc((string) ($row['department'] ?? '')) ?></td>
                        <td><?= esc((string) $row['status']) ?></td>
                        <td><?= esc((string) $row['total_requested_qty']) ?></td>
                        <td><?= esc((string) $row['total_issued_qty']) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</body>
</html>
