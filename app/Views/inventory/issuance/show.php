<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issuance Details - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .bar { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .message { color: #0a7a2a; }
        .error { color: #b00020; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; vertical-align: top; }
        th { background: #f6f6f6; text-align: left; }
        form.inline { display: inline-flex; gap: 0.35rem; margin-right: 0.6rem; margin-bottom: 0.35rem; }
        input, button { padding: 0.4rem; }
    </style>
</head>
<body>
    <h1>Issuance #<?= esc((string) ($issuance['issuance_number'] ?? '')) ?></h1>

    <div class="bar">
        <a href="<?= site_url('inventory/issuance') ?>">Back to Issuance List</a>
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

    <p><strong>Status:</strong> <?= esc((string) ($issuance['status'] ?? '')) ?></p>
    <p><strong>Requestor ID:</strong> <?= esc((string) ($issuance['requestor_id'] ?? '')) ?></p>
    <p><strong>Issue Date:</strong> <?= esc((string) ($issuance['issue_date'] ?? '')) ?></p>
    <p><strong>Department:</strong> <?= esc((string) ($issuance['department'] ?? '')) ?></p>

    <div class="bar">
        <?php if (($issuance['status'] ?? '') === 'draft'): ?>
            <form class="inline" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/submit') ?>">
                <?= csrf_field() ?>
                <button type="submit">Submit</button>
            </form>
        <?php endif ?>

        <?php if (($issuance['status'] ?? '') === 'submitted'): ?>
            <form class="inline" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/approve') ?>">
                <?= csrf_field() ?>
                <input type="text" name="comments" placeholder="Optional comment">
                <button type="submit">Approve</button>
            </form>
            <form class="inline" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/reject') ?>">
                <?= csrf_field() ?>
                <input type="text" name="reason" placeholder="Rejection reason" required>
                <button type="submit">Reject</button>
            </form>
        <?php endif ?>

        <?php if (($issuance['status'] ?? '') === 'approved'): ?>
            <form class="inline" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/release') ?>">
                <?= csrf_field() ?>
                <button type="submit">Release</button>
            </form>
        <?php endif ?>

        <?php if (in_array((string) ($issuance['status'] ?? ''), ['draft', 'submitted'], true)): ?>
            <form class="inline" method="post" action="<?= site_url('inventory/issuance/' . $issuance['id'] . '/cancel') ?>">
                <?= csrf_field() ?>
                <input type="text" name="reason" placeholder="Cancel reason (optional)">
                <button type="submit">Cancel</button>
            </form>
        <?php endif ?>
    </div>

    <h2>Items</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Requested Qty</th>
                <th>Issued Qty</th>
                <th>Unit Cost</th>
                <th>Line Total</th>
                <th>Stock ID</th>
            </tr>
        </thead>
        <tbody>
            <?php if (($issuance['items'] ?? []) === []): ?>
                <tr><td colspan="8">No issuance items found.</td></tr>
            <?php else: ?>
                <?php foreach ($issuance['items'] as $item): ?>
                    <tr>
                        <td><?= esc((string) $item['id']) ?></td>
                        <td><?= esc((string) $item['item_name']) ?></td>
                        <td><?= esc((string) $item['unit']) ?></td>
                        <td><?= esc((string) $item['requested_qty']) ?></td>
                        <td><?= esc((string) $item['issued_qty']) ?></td>
                        <td><?= esc(number_format((float) ($item['unit_cost'] ?? 0), 2)) ?></td>
                        <td><?= esc(number_format((float) ($item['line_total'] ?? 0), 2)) ?></td>
                        <td><?= esc((string) ($item['inventory_stock_id'] ?? '')) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</body>
</html>
