<?php

declare(strict_types=1);

$title = 'Receiving Conversion - InventoryV2';
$pageTitle = 'Receiving Conversion';
$pageSubtitle = 'PO Request #' . (string) ($po_request['id'] ?? '') . ' to Purchase Order #' . (string) ($purchase_order['id'] ?? '');
$crumbs = [
    ['label' => 'Receiving', 'url' => site_url('receiving')],
    ['label' => 'Conversion'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('receiving') ?>">Back to Receiving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <form method="post" action="<?= site_url('receiving') ?>" class="stack-md">
        <?= csrf_field() ?>
        <input type="hidden" name="po_request_id" value="<?= esc((string) ($po_request['id'] ?? 0)) ?>">

        <div class="form-grid-2">
            <div class="field">
                <label for="received_date">Received Date</label>
                <input id="received_date" type="date" name="received_date" value="<?= esc((string) old('received_date', date('Y-m-d'))) ?>" required>
            </div>
            <div class="field">
                <label for="delivery_reference">Delivery Reference</label>
                <input id="delivery_reference" type="text" name="delivery_reference" value="<?= esc((string) old('delivery_reference')) ?>">
            </div>
        </div>

        <div class="field">
            <label for="remarks">Remarks</label>
            <textarea id="remarks" name="remarks"><?= esc((string) old('remarks')) ?></textarea>
        </div>

        <h2>Receiving Items</h2>

        <div class="table-wrap">
            <table class="table">
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
        </div>

        <div class="toolbar">
            <button type="submit" class="btn btn-primary">Create Receiving Draft</button>
            <a class="btn btn-outline" href="<?= site_url('receiving') ?>">Cancel</a>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
