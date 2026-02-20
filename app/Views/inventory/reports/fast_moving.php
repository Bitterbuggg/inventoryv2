<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fast Moving Report - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .bar { display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; }
        th { background: #f6f6f6; text-align: left; }
        input, button { padding: 0.4rem; }
    </style>
</head>
<body>
    <h1>Report: Fast Moving Items</h1>

    <div class="bar">
        <a href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
        <a href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
        <a href="<?= site_url('reports/issuances') ?>">Issuance Report</a>
        <a href="<?= site_url('reports/low-stock') ?>">Low Stock</a>
    </div>

    <form method="get" action="<?= site_url('reports/fast-moving') ?>">
        <label>Date From</label>
        <input type="date" name="date_from" value="<?= esc((string) ($date_from ?? '')) ?>">
        <label>Date To</label>
        <input type="date" name="date_to" value="<?= esc((string) ($date_to ?? '')) ?>">
        <label>Limit</label>
        <input type="number" min="1" name="limit" value="<?= esc((string) ($limit ?? 20)) ?>">
        <button type="submit">Apply</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Unit</th>
                <th>Total Qty Out</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($rows ?? []) === []): ?>
                <tr><td colspan="3">No records found.</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= esc((string) $row['item_name']) ?></td>
                        <td><?= esc((string) $row['unit']) ?></td>
                        <td><?= esc((string) $row['total_qty_out']) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</body>
</html>
