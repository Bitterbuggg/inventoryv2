<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Requests - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .bar { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .message { color: #0a7a2a; }
        .error { color: #b00020; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; vertical-align: top; }
        th { background: #f6f6f6; text-align: left; }
        form.inline { display: inline-flex; gap: 0.35rem; margin: 0.1rem 0.2rem 0.1rem 0; }
        input, select, button { padding: 0.4rem; }
    </style>
</head>
<body>
    <h1>Procurement - Purchase Requests</h1>

    <div class="bar">
        <a href="<?= site_url('procurement/purchase-requests/create') ?>">Create Purchase Request</a>
        <a href="<?= site_url('procurement/approvals/pending') ?>">Pending Approvals</a>
        <a href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
        <a href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
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

    <form method="get" action="<?= site_url('procurement/purchase-requests') ?>">
        <label for="status">Filter status:</label>
        <select id="status" name="status">
            <option value="">All</option>
            <?php foreach (['draft', 'submitted', 'approved', 'rejected', 'cancelled', 'converted_to_po'] as $option): ?>
                <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit">Apply</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>PR Number</th>
                <th>Requested By</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($requests ?? []) === []): ?>
                <tr>
                    <td colspan="7">No purchase requests found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?= esc((string) $request['id']) ?></td>
                        <td><?= esc((string) $request['pr_number']) ?></td>
                        <td><?= esc((string) $request['requested_by']) ?></td>
                        <td><?= esc((string) $request['request_date']) ?></td>
                        <td><?= esc((string) $request['status']) ?></td>
                        <td><?= esc((string) ($request['remarks'] ?? '')) ?></td>
                        <td>
                            <?php if (($request['status'] ?? '') === 'draft'): ?>
                                <form class="inline" method="post" action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/submit') ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit">Submit</button>
                                </form>
                            <?php endif ?>

                            <?php if (in_array((string) ($request['status'] ?? ''), ['draft', 'submitted'], true)): ?>
                                <form class="inline" method="post" action="<?= site_url('procurement/purchase-requests/' . $request['id'] . '/cancel') ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit">Cancel</button>
                                </form>
                            <?php endif ?>

                            <?php if (($request['status'] ?? '') === 'approved'): ?>
                                <form class="inline" method="post" action="<?= site_url('procurement/purchase-orders/from-pr/' . $request['id']) ?>">
                                    <?= csrf_field() ?>
                                    <input type="text" name="supplier_name" placeholder="Supplier (optional)">
                                    <button type="submit">Generate PO</button>
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
