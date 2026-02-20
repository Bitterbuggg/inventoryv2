<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Issuance - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; max-width: 1100px; }
        .error { color: #b00020; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.45rem; }
        th { background: #f6f6f6; text-align: left; }
        input, textarea, button { width: 100%; box-sizing: border-box; padding: 0.45rem; }
        textarea { min-height: 70px; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; margin-bottom: 0.8rem; }
        .actions { margin-top: 1rem; display: flex; gap: 0.6rem; }
        .actions a { align-self: center; }
    </style>
</head>
<body>
    <h1>Create Issuance Draft</h1>

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

    <form method="post" action="<?= site_url('inventory/issuance') ?>">
        <?= csrf_field() ?>

        <div class="row">
            <div>
                <label for="issue_date">Issue Date</label>
                <input id="issue_date" type="date" name="issue_date" value="<?= esc((string) old('issue_date', date('Y-m-d'))) ?>" required>
            </div>
            <div>
                <label for="department">Department</label>
                <input id="department" type="text" name="department" value="<?= esc((string) old('department')) ?>">
            </div>
        </div>

        <div class="row">
            <div>
                <label for="purpose">Purpose</label>
                <textarea id="purpose" name="purpose"><?= esc((string) old('purpose')) ?></textarea>
            </div>
            <div>
                <label for="remarks">Remarks</label>
                <textarea id="remarks" name="remarks"><?= esc((string) old('remarks')) ?></textarea>
            </div>
        </div>

        <h2>Items</h2>
        <p>Fill at least one row.</p>

        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th>Requested Qty</th>
                    <th>Item Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <tr>
                        <td><input name="item_name[]" value="<?= esc((string) old('item_name.' . $i)) ?>"></td>
                        <td><input name="unit[]" value="<?= esc((string) old('unit.' . $i, 'unit')) ?>"></td>
                        <td><input type="number" step="0.001" min="0" name="requested_qty[]" value="<?= esc((string) old('requested_qty.' . $i)) ?>"></td>
                        <td><input name="item_remarks[]" value="<?= esc((string) old('item_remarks.' . $i)) ?>"></td>
                    </tr>
                <?php endfor ?>
            </tbody>
        </table>

        <div class="actions">
            <button type="submit">Save Issuance Draft</button>
            <a href="<?= site_url('inventory/issuance') ?>">Back to list</a>
        </div>
    </form>
</body>
</html>
