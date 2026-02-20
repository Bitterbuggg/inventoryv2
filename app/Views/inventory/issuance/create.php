<?php

declare(strict_types=1);

$title = 'Create Issuance - InventoryV2';
$pageTitle = 'Create Issuance Draft';
$pageSubtitle = 'Prepare issuance header details and requested item quantities.';
$crumbs = [
    ['label' => 'Inventory Issuance', 'url' => site_url('inventory/issuance')],
    ['label' => 'Create'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('inventory/issuance') ?>">Back to List</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card stack-md">
    <form method="post" action="<?= site_url('inventory/issuance') ?>" class="stack-md">
        <?= csrf_field() ?>

        <div class="form-grid-2">
            <div class="field">
                <label for="issue_date">Issue Date</label>
                <input id="issue_date" type="date" name="issue_date" value="<?= esc((string) old('issue_date', date('Y-m-d'))) ?>" required>
            </div>
            <div class="field">
                <label for="department">Department</label>
                <input id="department" type="text" name="department" value="<?= esc((string) old('department')) ?>">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="field">
                <label for="purpose">Purpose</label>
                <textarea id="purpose" name="purpose"><?= esc((string) old('purpose')) ?></textarea>
            </div>
            <div class="field">
                <label for="remarks">Remarks</label>
                <textarea id="remarks" name="remarks"><?= esc((string) old('remarks')) ?></textarea>
            </div>
        </div>

        <h2>Items</h2>
        <p class="muted">Fill at least one row.</p>

        <div class="table-wrap">
            <table class="table">
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
        </div>

        <div class="toolbar">
            <button type="submit" class="btn btn-primary">Save Issuance Draft</button>
            <a class="btn btn-outline" href="<?= site_url('inventory/issuance') ?>">Cancel</a>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
