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
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Mode</p>
                <p class="kpi-value">Create</p>
                <p class="kpi-note">New draft purchase request.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Default Date</p>
                <p class="kpi-value"><?= esc(date('Y-m-d')) ?></p>
                <p class="kpi-note">Pre-filled request date value.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Item Rows</p>
                <p class="kpi-value">5</p>
                <p class="kpi-note">Blank rows are ignored on save.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Workflow</p>
                <p class="kpi-value">Draft</p>
                <p class="kpi-note">Submit from list page when ready.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <form method="post" action="<?= site_url('procurement/purchase-requests') ?>" class="stack-md">
            <?= csrf_field() ?>

            <div class="form-section stack-md">
                <div class="stack-sm">
                    <h2>Request Header</h2>
                    <p class="muted">Set required and optional request details before adding line items.</p>
                </div>

                <div class="form-grid-2">
                    <div class="field">
                        <label for="request_date">Request Date</label>
                        <input id="request_date" type="date" name="request_date" value="<?= esc((string) old('request_date', date('Y-m-d'))) ?>" required>
                    </div>
                    <div class="field">
                        <label for="needed_date">Needed Date</label>
                        <p class="field-hint">Optional</p>
                        <input id="needed_date" type="date" name="needed_date" value="<?= esc((string) old('needed_date')) ?>">
                    </div>
                </div>

                <div class="field">
                    <label for="remarks">Remarks</label>
                    <p class="field-hint">Optional notes</p>
                    <textarea id="remarks" name="remarks" placeholder="Optional notes"><?= esc((string) old('remarks')) ?></textarea>
                </div>
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
</div>
<?= $this->endSection() ?>

