<?php

declare(strict_types=1);

$title = 'Create Purchase Request - InventoryV2';
$pageTitle = 'Create Purchase Request';
$pageSubtitle = 'Build a draft request with at least one line item.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Create'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Back to List</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <form method="post" action="<?= site_url('procurement/purchase-requests') ?>" class="stack-md">
        <?= csrf_field() ?>

        <div class="form-grid-2">
            <div class="field">
                <label for="request_date">Request Date</label>
                <input id="request_date" type="date" name="request_date" value="<?= esc((string) old('request_date', date('Y-m-d'))) ?>" required>
            </div>
            <div class="field">
                <label for="needed_date">Needed Date</label>
                <input id="needed_date" type="date" name="needed_date" value="<?= esc((string) old('needed_date')) ?>">
            </div>
        </div>

        <div class="field">
            <label for="remarks">Remarks</label>
            <textarea id="remarks" name="remarks" placeholder="Optional notes"><?= esc((string) old('remarks')) ?></textarea>
        </div>

        <div class="stack-sm">
            <h2>Items</h2>
            <p class="muted">Fill at least one row. Blank rows are ignored.</p>
        </div>

        <div class="table-wrap">
            <table class="table">
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
        </div>

        <div class="toolbar">
            <button type="submit" class="btn btn-primary">Save Draft Purchase Request</button>
            <a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Cancel</a>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
