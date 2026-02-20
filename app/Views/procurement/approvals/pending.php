<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approvals - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .bar { display: flex; gap: 1rem; margin-bottom: 1rem; }
        .message { color: #0a7a2a; }
        .error { color: #b00020; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; vertical-align: top; }
        th { background: #f6f6f6; text-align: left; }
        form { margin: 0.2rem 0; display: flex; gap: 0.4rem; }
        input, button { padding: 0.4rem; }
        input { min-width: 260px; }
    </style>
</head>
<body>
    <h1>Procurement - Pending Approvals</h1>

    <div class="bar">
        <a href="<?= site_url('procurement/purchase-requests') ?>">Purchase Requests</a>
        <a href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
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

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Reference</th>
                <th>Level</th>
                <th>Decision</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($approvals ?? []) === []): ?>
                <tr>
                    <td colspan="5">No pending approvals.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($approvals as $approval): ?>
                    <tr>
                        <td><?= esc((string) $approval['id']) ?></td>
                        <td><?= esc((string) $approval['reference_type']) ?> #<?= esc((string) $approval['reference_id']) ?></td>
                        <td><?= esc((string) $approval['approval_level']) ?></td>
                        <td><?= esc((string) $approval['decision']) ?></td>
                        <td>
                            <form method="post" action="<?= site_url('procurement/approvals/' . $approval['id'] . '/approve') ?>">
                                <?= csrf_field() ?>
                                <input type="text" name="comments" placeholder="Optional comment">
                                <button type="submit">Approve</button>
                            </form>
                            <form method="post" action="<?= site_url('procurement/approvals/' . $approval['id'] . '/reject') ?>">
                                <?= csrf_field() ?>
                                <input type="text" name="comments" placeholder="Rejection reason" required>
                                <button type="submit">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</body>
</html>
