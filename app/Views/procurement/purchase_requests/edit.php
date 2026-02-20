<?php

declare(strict_types=1);

$title = 'Edit Purchase Request - InventoryV2';
$pageTitle = 'Edit Purchase Request #' . (string) ($purchaseRequest['id'] ?? '');
$pageSubtitle = 'Update draft header details and line items before submission.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Edit Request'],
];
$existingItems = (array) ($purchaseRequest['items'] ?? []);
$rowCount = max(5, count($existingItems));
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Back to List</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$currentStatus = (string) ($purchaseRequest['status'] ?? 'draft');
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Request ID</p>
                <p class="kpi-value"><?= esc((string) ($purchaseRequest['id'] ?? '')) ?></p>
                <p class="kpi-note">Current draft being updated.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Status</p>
                <p class="kpi-value"><?= esc(ucfirst($currentStatus)) ?></p>
                <p class="kpi-note">Only draft should be editable.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Line Items</p>
                <p class="kpi-value"><?= esc((string) count($existingItems)) ?></p>
                <p class="kpi-note">Persisted items before save.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Editable Rows</p>
                <p class="kpi-value"><?= esc((string) $rowCount) ?></p>
                <p class="kpi-note">Form rows rendered in table.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <form method="post" action="<?= site_url('procurement/purchase-requests/' . $purchaseRequest['id'] . '/update') ?>" class="stack-md">
            <?= csrf_field() ?>

            <div class="form-section stack-md">
                <div class="stack-sm">
                    <h2>Request Header</h2>
                    <p class="muted">Update request details before adjusting line items.</p>
                </div>

                <div class="form-grid-2">
                    <div class="field">
                        <label for="request_date">Request Date</label>
                        <input id="request_date" type="date" name="request_date" value="<?= esc((string) old('request_date', (string) ($purchaseRequest['request_date'] ?? ''))) ?>" required>
                    </div>
                    <div class="field">
                        <label for="needed_date">Needed Date</label>
                        <p class="field-hint">Optional</p>
                        <input id="needed_date" type="date" name="needed_date" value="<?= esc((string) old('needed_date', (string) ($purchaseRequest['needed_date'] ?? ''))) ?>">
                    </div>
                </div>

                <div class="field">
                    <label for="remarks">Remarks</label>
                    <p class="field-hint">Optional notes</p>
                    <textarea id="remarks" name="remarks" placeholder="Optional notes"><?= esc((string) old('remarks', (string) ($purchaseRequest['remarks'] ?? ''))) ?></textarea>
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
                        <?php for ($i = 0; $i < $rowCount; $i++): ?>
                            <tr>
                                <td><input name="item_name[]" value="<?= esc((string) old('item_name.' . $i, (string) ($existingItems[$i]['item_name'] ?? ''))) ?>"></td>
                                <td><input type="number" step="0.001" min="0" name="requested_qty[]" value="<?= esc((string) old('requested_qty.' . $i, (string) ($existingItems[$i]['requested_qty'] ?? ''))) ?>"></td>
                                <td><input name="unit[]" value="<?= esc((string) old('unit.' . $i, (string) ($existingItems[$i]['unit'] ?? 'unit'))) ?>"></td>
                                <td><input type="number" step="0.01" min="0" name="estimated_unit_cost[]" value="<?= esc((string) old('estimated_unit_cost.' . $i, (string) ($existingItems[$i]['estimated_unit_cost'] ?? ''))) ?>"></td>
                                <td><input name="notes[]" value="<?= esc((string) old('notes.' . $i, (string) ($existingItems[$i]['notes'] ?? ''))) ?>"></td>
                            </tr>
                        <?php endfor ?>
                    </tbody>
                </table>
            </div>

            <div class="toolbar">
                <button type="submit" class="btn btn-primary">Update Purchase Request</button>
                <a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Cancel</a>
            </div>
        </form>
    </section>
</div>
<?= $this->endSection() ?>

