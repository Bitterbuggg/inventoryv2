<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receiving Conversion - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .error { color: #b00020; }
        .message { color: #0a7a2a; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.45rem; vertical-align: top; }
        th { background: #f6f6f6; text-align: left; }
        input, textarea, button { width: 100%; box-sizing: border-box; padding: 0.4rem; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem; }
        .actions { margin-top: 1rem; display: flex; gap: 0.6rem; }
        .actions a { align-self: center; }
    </style>
</head>
<body>
    <h1>Receiving Conversion</h1>
    <p>PO Request #<?= esc((string) ($po_request['id'] ?? '')) ?> -> Purchase Order #<?= esc((string) ($purchase_order['id'] ?? '')) ?></p>

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

    <form method="post" action="<?= site_url('receiving') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="po_request_id" value="<?= esc((string) ($po_request['id'] ?? 0)) ?>">

        <div class="row">
            <div>
                <label for="received_date">Received Date</label>
                <input id="received_date" type="date" name="received_date" value="<?= esc((string) old('received_date', date('Y-m-d'))) ?>" required>
            </div>
            <div>
                <label for="delivery_reference">Delivery Reference</label>
                <input id="delivery_reference" type="text" name="delivery_reference" value="<?= esc((string) old('delivery_reference')) ?>">
            </div>
        </div>

        <div>
            <label for="remarks">Remarks</label>
            <textarea id="remarks" name="remarks"><?= esc((string) old('remarks')) ?></textarea>
        </div>

        <h2>Receiving Items</h2>

        <table>
            <thead>
                <tr>
                    <th>PO Item ID</th>
                    <th>Item</th>
                    <th>Unit</th>
                    <th>Received Qty</th>
                    <th>Accepted Qty</th>
                    <th>Rejected Qty</th>
                    <th>Batch No</th>
                    <th>Lot No</th>
                    <th>Expiry Date</th>
                    <th>Unit Cost</th>
                    <th>Item Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($items ?? []) as $index => $item): ?>
                    <tr>
                        <td>
                            <input type="hidden" name="purchase_order_item_id[]" value="<?= esc((string) ($item['purchase_order_item_id'] ?? 0)) ?>">
                            <?= esc((string) ($item['purchase_order_item_id'] ?? 0)) ?>
                        </td>
                        <td>
                            <input type="hidden" name="item_name[]" value="<?= esc((string) ($item['item_name'] ?? '')) ?>">
                            <?= esc((string) ($item['item_name'] ?? '')) ?>
                        </td>
                        <td>
                            <input type="hidden" name="unit[]" value="<?= esc((string) ($item['unit'] ?? 'unit')) ?>">
                            <?= esc((string) ($item['unit'] ?? 'unit')) ?>
                        </td>
                        <td><input type="number" step="0.001" min="0" name="received_qty[]" value="<?= esc((string) old('received_qty.' . $index, (string) ($item['received_qty'] ?? 0))) ?>"></td>
                        <td><input type="number" step="0.001" min="0" name="accepted_qty[]" value="<?= esc((string) old('accepted_qty.' . $index, (string) ($item['accepted_qty'] ?? 0))) ?>"></td>
                        <td><input type="number" step="0.001" min="0" name="rejected_qty[]" value="<?= esc((string) old('rejected_qty.' . $index, (string) ($item['rejected_qty'] ?? 0))) ?>"></td>
                        <td><input type="text" name="batch_no[]" value="<?= esc((string) old('batch_no.' . $index)) ?>"></td>
                        <td><input type="text" name="lot_no[]" value="<?= esc((string) old('lot_no.' . $index)) ?>"></td>
                        <td><input type="date" name="expiry_date[]" value="<?= esc((string) old('expiry_date.' . $index)) ?>"></td>
                        <td><input type="number" step="0.01" min="0" name="unit_cost[]" value="<?= esc((string) old('unit_cost.' . $index, (string) ($item['unit_cost'] ?? 0))) ?>"></td>
                        <td><input type="text" name="item_remarks[]" value="<?= esc((string) old('item_remarks.' . $index)) ?>"></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <div class="actions">
            <button type="submit">Create Receiving Draft</button>
            <a href="<?= site_url('receiving') ?>">Back to receiving list</a>
        </div>
    </form>
</body>
</html>
