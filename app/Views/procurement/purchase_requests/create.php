<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Purchase Request - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; max-width: 1100px; }
        .error { color: #b00020; }
        .message { color: #0a7a2a; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.45rem; }
        th { background: #f6f6f6; text-align: left; }
        input, textarea, button { padding: 0.45rem; width: 100%; box-sizing: border-box; }
        textarea { min-height: 70px; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; margin-bottom: 0.8rem; }
        .actions { margin-top: 1rem; display: flex; gap: 0.6rem; }
        .actions a { align-self: center; }
    </style>
</head>
<body>
    <h1>Create Purchase Request</h1>

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

    <form method="post" action="<?= site_url('procurement/purchase-requests') ?>">
        <?= csrf_field() ?>

        <div class="row">
            <div>
                <label for="request_date">Request Date</label>
                <input id="request_date" type="date" name="request_date" value="<?= esc((string) old('request_date', date('Y-m-d'))) ?>" required>
            </div>
            <div>
                <label for="needed_date">Needed Date</label>
                <input id="needed_date" type="date" name="needed_date" value="<?= esc((string) old('needed_date')) ?>">
            </div>
        </div>

        <div>
            <label for="remarks">Remarks</label>
            <textarea id="remarks" name="remarks" placeholder="Optional notes"><?= esc((string) old('remarks')) ?></textarea>
        </div>

        <h2>Items</h2>
        <p>Fill at least one row. Blank item rows are ignored.</p>

        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Requested Qty</th>
                    <th>Unit</th>
                    <th>Estimated Unit Cost</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <tr>
                        <td><input name="item_name[]" value="<?= esc((string) old('item_name.' . $i)) ?>"></td>
                        <td><input type="number" step="0.001" min="0" name="requested_qty[]" value="<?= esc((string) old('requested_qty.' . $i)) ?>"></td>
                        <td><input name="unit[]" value="<?= esc((string) old('unit.' . $i, 'unit')) ?>"></td>
                        <td><input type="number" step="0.01" min="0" name="estimated_unit_cost[]" value="<?= esc((string) old('estimated_unit_cost.' . $i)) ?>"></td>
                        <td><input name="notes[]" value="<?= esc((string) old('notes.' . $i)) ?>"></td>
                    </tr>
                <?php endfor ?>
            </tbody>
        </table>

        <div class="actions">
            <button type="submit">Save Draft Purchase Request</button>
            <a href="<?= site_url('procurement/purchase-requests') ?>">Back to list</a>
        </div>
    </form>
</body>
</html>
