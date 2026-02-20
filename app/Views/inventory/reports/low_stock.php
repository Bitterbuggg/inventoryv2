<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Report - InventoryV2</title>
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
    <h1>Report: Low Stock</h1>

    <div class="bar">
        <a href="<?= site_url('reports/stock-balance') ?>">Stock Balance</a>
        <a href="<?= site_url('reports/stock-movements') ?>">Stock Movements</a>
        <a href="<?= site_url('reports/issuances') ?>">Issuance Report</a>
        <a href="<?= site_url('reports/fast-moving') ?>">Fast Moving</a>
    </div>

    <form method="get" action="<?= site_url('reports/low-stock') ?>">
        <label>Threshold</label>
        <input type="number" step="0.001" min="0" name="threshold" value="<?= esc((string) ($threshold ?? 10)) ?>">
        <button type="submit">Apply</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Batch</th>
                <th>Lot</th>
                <th>Expiry</th>
                <th>Available Qty</th>
                <th>On Hand</th>
                <th>Reserved</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($rows ?? []) === []): ?>
                <tr><td colspan="9">No records found.</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= esc((string) $row['id']) ?></td>
                        <td><?= esc((string) $row['item_name']) ?></td>
                        <td><?= esc((string) $row['unit']) ?></td>
                        <td><?= esc((string) ($row['batch_no'] ?? '')) ?></td>
                        <td><?= esc((string) ($row['lot_no'] ?? '')) ?></td>
                        <td><?= esc((string) ($row['expiry_date'] ?? '')) ?></td>
                        <td><?= esc((string) $row['available_qty']) ?></td>
                        <td><?= esc((string) $row['on_hand_qty']) ?></td>
                        <td><?= esc((string) $row['reserved_qty']) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</body>
</html>
