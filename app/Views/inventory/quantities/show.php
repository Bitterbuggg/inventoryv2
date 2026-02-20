<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Stock Details - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; }
        th { background: #f6f6f6; text-align: left; }
    </style>
</head>
<body>
    <h1>Inventory Stock #<?= esc((string) ($stock['id'] ?? '')) ?></h1>
    <p><a href="<?= site_url('inventory/quantities') ?>">Back to Inventory Quantities</a></p>

    <p><strong>Item:</strong> <?= esc((string) ($stock['item_name'] ?? '')) ?></p>
    <p><strong>Unit:</strong> <?= esc((string) ($stock['unit'] ?? '')) ?></p>
    <p><strong>On Hand:</strong> <?= esc((string) ($stock['on_hand_qty'] ?? '0')) ?></p>
    <p><strong>Available:</strong> <?= esc((string) ($stock['available_qty'] ?? '0')) ?></p>

    <h2>Stock Movements</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Movement Number</th>
                <th>Type</th>
                <th>Reference</th>
                <th>Qty In</th>
                <th>Qty Out</th>
                <th>Balance After</th>
                <th>Performed At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($stock['movements'] ?? []) === []): ?>
                <tr><td colspan="8">No movements found.</td></tr>
            <?php else: ?>
                <?php foreach ($stock['movements'] as $movement): ?>
                    <tr>
                        <td><?= esc((string) $movement['id']) ?></td>
                        <td><?= esc((string) $movement['movement_number']) ?></td>
                        <td><?= esc((string) $movement['movement_type']) ?></td>
                        <td><?= esc((string) $movement['reference_type']) ?> #<?= esc((string) $movement['reference_id']) ?></td>
                        <td><?= esc((string) $movement['qty_in']) ?></td>
                        <td><?= esc((string) $movement['qty_out']) ?></td>
                        <td><?= esc((string) $movement['balance_after']) ?></td>
                        <td><?= esc((string) $movement['performed_at']) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</body>
</html>
