<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Orders - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .bar { display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap; }
        .message { color: #0a7a2a; }
        .error { color: #b00020; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; vertical-align: top; }
        th { background: #f6f6f6; text-align: left; }
        form.inline { display: inline-flex; gap: 0.35rem; margin: 0.15rem 0.2rem 0.15rem 0; }
        select, button { padding: 0.4rem; }
    </style>
</head>
<body>
    <h1>Procurement - Purchase Orders</h1>

    <div class="bar">
        <a href="<?= site_url('procurement/purchase-requests') ?>">Purchase Requests</a>
        <a href="<?= site_url('procurement/approvals/pending') ?>">Pending Approvals</a>
        <a href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
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

    <form method="get" action="<?= site_url('procurement/purchase-orders') ?>">
        <label for="status">Filter status:</label>
        <select id="status" name="status">
            <option value="">All</option>
            <?php foreach (['draft', 'issued', 'partially_received', 'fully_received', 'cancelled'] as $option): ?>
                <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit">Apply</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>PO Number</th>
                <th>PR ID</th>
                <th>Supplier</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($purchaseOrders ?? []) === []): ?>
                <tr>
                    <td colspan="8">No purchase orders found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($purchaseOrders as $order): ?>
                    <tr>
                        <td><?= esc((string) $order['id']) ?></td>
                        <td><?= esc((string) $order['po_number']) ?></td>
                        <td><?= esc((string) $order['purchase_request_id']) ?></td>
                        <td><?= esc((string) ($order['supplier_name'] ?? '')) ?></td>
                        <td><?= esc((string) $order['order_date']) ?></td>
                        <td><?= esc((string) $order['status']) ?></td>
                        <td><?= esc(number_format((float) ($order['total_amount'] ?? 0), 2)) ?></td>
                        <td>
                            <?php if (($order['status'] ?? '') === 'draft'): ?>
                                <form class="inline" method="post" action="<?= site_url('procurement/purchase-orders/' . $order['id'] . '/issue') ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit">Issue PO</button>
                                </form>
                            <?php endif ?>

                            <?php if (($order['status'] ?? '') === 'issued'): ?>
                                <form class="inline" method="post" action="<?= site_url('procurement/po-requests/from-po/' . $order['id']) ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit">Create PO Request</button>
                                </form>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</body>
</html>
