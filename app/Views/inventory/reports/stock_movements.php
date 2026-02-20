<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Movement Report - InventoryV2</title>
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
    <h1>Report: Stock Movements</h1>

    <div class="bar">
        <a href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
        <a href="<?= site_url('reports/issuances') ?>">Issuance Report</a>
        <a href="<?= site_url('reports/low-stock') ?>">Low Stock</a>
        <a href="<?= site_url('reports/fast-moving') ?>">Fast Moving</a>
    </div>

    <form method="get" action="<?= site_url('reports/stock-movements') ?>">
        <label>Date From</label>
        <input type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>">
        <label>Date To</label>
        <input type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>">
        <label>Type</label>
        <select name="movement_type">
            <option value="">All</option>
            <?php foreach (['receiving', 'issuance', 'adjustment_in', 'adjustment_out', 'return'] as $type): ?>
                <option value="<?= esc($type) ?>" <?= (($movement_type ?? '') === $type) ? 'selected' : '' ?>><?= esc($type) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit">Apply</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Movement #</th>
                <th>Type</th>
                <th>Reference</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Qty In</th>
                <th>Qty Out</th>
                <th>Balance After</th>
                <th>Performed At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($rows ?? []) === []): ?>
                <tr><td colspan="10">No records found.</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= esc((string) $row['id']) ?></td>
                        <td><?= esc((string) $row['movement_number']) ?></td>
                        <td><?= esc((string) $row['movement_type']) ?></td>
                        <td><?= esc((string) $row['reference_type']) ?> #<?= esc((string) $row['reference_id']) ?></td>
                        <td><?= esc((string) $row['item_name']) ?></td>
                        <td><?= esc((string) $row['unit']) ?></td>
                        <td><?= esc((string) $row['qty_in']) ?></td>
                        <td><?= esc((string) $row['qty_out']) ?></td>
                        <td><?= esc((string) $row['balance_after']) ?></td>
                        <td><?= esc((string) $row['performed_at']) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</body>
</html>
