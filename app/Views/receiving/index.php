<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receiving - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .bar { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .message { color: #0a7a2a; }
        .error { color: #b00020; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; vertical-align: top; }
        th { background: #f6f6f6; text-align: left; }
        .section { margin-top: 2rem; }
        select, button { padding: 0.4rem; }
    </style>
</head>
<body>
    <h1>Receiving</h1>

    <div class="bar">
        <a href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
        <a href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
        <a href="<?= site_url('inventory/quantities') ?>">Inventory Quantities</a>
        <a href="<?= site_url('admin/dashboard') ?>">Admin Dashboard</a>
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

    <form method="get" action="<?= site_url('receiving') ?>">
        <label for="status">Filter status:</label>
        <select id="status" name="status">
            <option value="">All</option>
            <?php foreach (['draft', 'posted', 'voided'] as $option): ?>
                <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit">Apply</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Receiving Number</th>
                <th>PO Request</th>
                <th>Received Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($receivings ?? []) === []): ?>
                <tr><td colspan="6">No receiving records found.</td></tr>
            <?php else: ?>
                <?php foreach ($receivings as $receiving): ?>
                    <tr>
                        <td><?= esc((string) $receiving['id']) ?></td>
                        <td><?= esc((string) $receiving['receiving_number']) ?></td>
                        <td>#<?= esc((string) $receiving['po_request_id']) ?></td>
                        <td><?= esc((string) $receiving['received_date']) ?></td>
                        <td><?= esc((string) $receiving['status']) ?></td>
                        <td><a href="<?= site_url('receiving/' . $receiving['id']) ?>">View</a></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>

    <div class="section">
        <h2>Approved PO Requests Ready for Conversion</h2>
        <table>
            <thead>
                <tr>
                    <th>PO Request ID</th>
                    <th>Purchase Order ID</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (($convertiblePoRequests ?? []) === []): ?>
                    <tr><td colspan="4">No approved PO requests available.</td></tr>
                <?php else: ?>
                    <?php foreach ($convertiblePoRequests as $poRequest): ?>
                        <tr>
                            <td><?= esc((string) $poRequest['id']) ?></td>
                            <td><?= esc((string) $poRequest['purchase_order_id']) ?></td>
                            <td><?= esc((string) $poRequest['status']) ?></td>
                            <td><a href="<?= site_url('receiving/create/from-po-request/' . $poRequest['id']) ?>">Convert to Receiving</a></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</body>
</html>
