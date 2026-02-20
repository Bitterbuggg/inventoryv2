<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receiving Details - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .bar { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .message { color: #0a7a2a; }
        .error { color: #b00020; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; vertical-align: top; }
        th { background: #f6f6f6; text-align: left; }
        form.inline { display: inline-flex; gap: 0.35rem; margin-right: 0.5rem; }
        input, button { padding: 0.4rem; }
    </style>
</head>
<body>
    <h1>Receiving #<?= esc((string) ($receiving['receiving_number'] ?? '')) ?></h1>

    <div class="bar">
        <a href="<?= site_url('receiving') ?>">Back to Receiving List</a>
        <a href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
    </div>

    <?php if (session('message')): ?>
        <p class="message"><?= esc((string) session('message')) ?></p>
    <?php endif ?>

    <?php if (session('error')): ?>
        <p class="error"><?= esc((string) session('error')) ?></p>
    <?php endif ?>

    <?php if (session('errors')): ?>
        <div class="error">
            <?php foreach ((array) session('errors') as $err): ?>
                <div><?= esc((string) $err) ?></div>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <p><strong>Status:</strong> <?= esc((string) ($receiving['status'] ?? '')) ?></p>
    <p><strong>PO Request ID:</strong> <?= esc((string) ($receiving['po_request_id'] ?? '')) ?></p>
    <p><strong>Purchase Order ID:</strong> <?= esc((string) ($receiving['purchase_order_id'] ?? '')) ?></p>
    <p><strong>Received Date:</strong> <?= esc((string) ($receiving['received_date'] ?? '')) ?></p>

    <?php if (($receiving['status'] ?? '') === 'draft'): ?>
        <div class="bar">
            <form class="inline" method="post" action="<?= site_url('receiving/' . $receiving['id'] . '/validate') ?>">
                <?= csrf_field() ?>
                <button type="submit">Validate Draft</button>
            </form>
            <form class="inline" method="post" action="<?= site_url('receiving/' . $receiving['id'] . '/post') ?>">
                <?= csrf_field() ?>
                <button type="submit">Post Receiving</button>
            </form>
            <form class="inline" method="post" action="<?= site_url('receiving/' . $receiving['id'] . '/void') ?>">
                <?= csrf_field() ?>
                <input type="text" name="reason" placeholder="Void reason" required>
                <button type="submit">Void Draft</button>
            </form>
        </div>
    <?php endif ?>

    <h2>Items</h2>
    <table>
        <thead>
            <tr>
                <th>PO Item ID</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Received</th>
                <th>Accepted</th>
                <th>Rejected</th>
                <th>Batch</th>
                <th>Lot</th>
                <th>Expiry</th>
                <th>Unit Cost</th>
                <th>Line Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($receiving['items'] ?? []) === []): ?>
                <tr><td colspan="11">No receiving items found.</td></tr>
            <?php else: ?>
                <?php foreach ($receiving['items'] as $item): ?>
                    <tr>
                        <td><?= esc((string) $item['purchase_order_item_id']) ?></td>
                        <td><?= esc((string) $item['item_name']) ?></td>
                        <td><?= esc((string) $item['unit']) ?></td>
                        <td><?= esc((string) $item['received_qty']) ?></td>
                        <td><?= esc((string) $item['accepted_qty']) ?></td>
                        <td><?= esc((string) $item['rejected_qty']) ?></td>
                        <td><?= esc((string) ($item['batch_no'] ?? '')) ?></td>
                        <td><?= esc((string) ($item['lot_no'] ?? '')) ?></td>
                        <td><?= esc((string) ($item['expiry_date'] ?? '')) ?></td>
                        <td><?= esc(number_format((float) ($item['unit_cost'] ?? 0), 2)) ?></td>
                        <td><?= esc(number_format((float) ($item['line_total'] ?? 0), 2)) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</body>
</html>
