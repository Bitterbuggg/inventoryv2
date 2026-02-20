<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PO Requests - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .bar { display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap; }
        .message { color: #0a7a2a; }
        .error { color: #b00020; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; vertical-align: top; }
        th { background: #f6f6f6; text-align: left; }
        form.inline { margin: 0.15rem 0; display: inline-flex; gap: 0.35rem; }
        input, select, button { padding: 0.4rem; }
    </style>
</head>
<body>
    <h1>Procurement - PO Requests</h1>

    <div class="bar">
        <a href="<?= site_url('procurement/purchase-requests') ?>">Purchase Requests</a>
        <a href="<?= site_url('procurement/approvals/pending') ?>">Pending Approvals</a>
        <a href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
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

    <form method="get" action="<?= site_url('procurement/po-requests') ?>">
        <label for="status">Filter status:</label>
        <select id="status" name="status">
            <option value="">All</option>
            <?php foreach (['pending', 'approved', 'rejected', 'converted_to_receiving', 'closed'] as $option): ?>
                <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit">Apply</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>PO Request Number</th>
                <th>PO ID</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Approved/Rej By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($poRequests ?? []) === []): ?>
                <tr>
                    <td colspan="7">No PO requests found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($poRequests as $poRequest): ?>
                    <tr>
                        <td><?= esc((string) $poRequest['id']) ?></td>
                        <td><?= esc((string) $poRequest['po_request_number']) ?></td>
                        <td><?= esc((string) $poRequest['purchase_order_id']) ?></td>
                        <td><?= esc((string) $poRequest['request_date']) ?></td>
                        <td><?= esc((string) $poRequest['status']) ?></td>
                        <td>
                            <?= esc((string) ($poRequest['approved_by'] ?? $poRequest['rejected_by'] ?? '')) ?>
                        </td>
                        <td>
                            <?php if (($poRequest['status'] ?? '') === 'pending'): ?>
                                <form class="inline" method="post" action="<?= site_url('procurement/po-requests/' . $poRequest['id'] . '/approve') ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit">Approve</button>
                                </form>

                                <form class="inline" method="post" action="<?= site_url('procurement/po-requests/' . $poRequest['id'] . '/reject') ?>">
                                    <?= csrf_field() ?>
                                    <input type="text" name="reason" placeholder="Rejection reason" required>
                                    <button type="submit">Reject</button>
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
