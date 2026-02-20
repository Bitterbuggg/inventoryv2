<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Quantities - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .bar { display: flex; gap: 1rem; margin-bottom: 1rem; }
        .error { color: #b00020; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; }
        th { background: #f6f6f6; text-align: left; }
        input, button { padding: 0.4rem; }
    </style>
</head>
<body>
    <h1>Inventory Quantities</h1>

    <div class="bar">
        <a href="<?= site_url('receiving') ?>">Receiving</a>
        <a href="<?= site_url('admin/dashboard') ?>">Admin Dashboard</a>
    </div>

    <?php if (session('error')): ?>
        <p class="error"><?= esc((string) session('error')) ?></p>
    <?php endif ?>

    <form method="get" action="<?= site_url('inventory/quantities') ?>">
        <input type="text" name="q" placeholder="Search item name" value="<?= esc((string) ($keyword ?? '')) ?>">
        <button type="submit">Search</button>
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
                <th>On Hand</th>
                <th>Reserved</th>
                <th>Available</th>
                <th>Avg Cost</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($stocks ?? []) === []): ?>
                <tr><td colspan="11">No inventory stocks found.</td></tr>
            <?php else: ?>
                <?php foreach ($stocks as $stock): ?>
                    <tr>
                        <td><?= esc((string) $stock['id']) ?></td>
                        <td><?= esc((string) $stock['item_name']) ?></td>
                        <td><?= esc((string) $stock['unit']) ?></td>
                        <td><?= esc((string) ($stock['batch_no'] ?? '')) ?></td>
                        <td><?= esc((string) ($stock['lot_no'] ?? '')) ?></td>
                        <td><?= esc((string) ($stock['expiry_date'] ?? '')) ?></td>
                        <td><?= esc((string) $stock['on_hand_qty']) ?></td>
                        <td><?= esc((string) $stock['reserved_qty']) ?></td>
                        <td><?= esc((string) $stock['available_qty']) ?></td>
                        <td><?= esc(number_format((float) ($stock['average_unit_cost'] ?? 0), 2)) ?></td>
                        <td><a href="<?= site_url('inventory/quantities/' . $stock['id']) ?>">View</a></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</body>
</html>
