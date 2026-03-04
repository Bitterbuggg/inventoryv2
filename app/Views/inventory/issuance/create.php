<?php

declare(strict_types=1);

$title = 'Create Issuance - InventoryV2';
$pageTitle = 'Create Issuance Draft';
$pageSubtitle = 'Prepare issuance header details and requested item quantities.';
$crumbs = [
    ['label' => 'Inventory Issuance', 'url' => site_url('inventory/issuance')],
    ['label' => 'Create'],
];

// Variables passed from the controller
$itemsList = $dbItems ?? [];
$predefinedUnits = ['Box', 'Piece', 'Vial', 'Bottle', 'Pack', 'Roll', 'Tablet', 'Capsule', 'Ampoule', 'Tube', 'Set'];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- HEADER STYLING --- */
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .field label { display: block; font-weight: 700; font-size: 0.85rem; color: #1e293b; margin-bottom: 4px; }
    .field-hint { font-size: 0.75rem; color: var(--color-text-muted); margin-top: 0; margin-bottom: 8px; }
    
    .form-control-header {
        width: 100%; padding: 8px 12px; border: 1px solid var(--color-border-strong); border-radius: 6px;
        font-family: inherit; font-size: 0.9rem; box-sizing: border-box; transition: border-color 0.2s;
    }
    .form-control-header:focus { border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15); }
    textarea.form-control-header { resize: vertical; min-height: 60px; }

    /* --- COMPACT TABLE ALIGNMENT --- */
    .table-wrap { overflow-x: auto; width: 100%; }
    #items-table { width: 100%; min-width: 800px; border-collapse: collapse; table-layout: fixed; }
    #items-table tbody td { vertical-align: middle; border-bottom: 1px solid var(--color-border); padding: 6px 4px; }
    #items-table th { padding: 10px 6px; font-size: 0.8rem; text-align: left; color: var(--color-text-muted); text-transform: uppercase; }

    /* STRICT INPUT SIZING FOR ALIGNMENT */
    .table-control {
        width: 100%; height: 36px; padding: 4px 10px; margin: 0;
        border: 1px solid var(--color-border-strong); border-radius: 4px;
        font-size: 0.85rem; font-family: inherit; background: var(--color-surface); color: var(--color-text);
        box-sizing: border-box; transition: border-color 0.2s ease;
    }
    .table-control:focus { border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15); }

    /* ROW REMOVAL BUTTON */
    .notes-group { display: flex; gap: 6px; align-items: center; height: 36px; }
    .btn-remove-row {
        flex: 0 0 36px; height: 100%; background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; border-radius: 4px;
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: bold; cursor: pointer; transition: all 0.2s ease; box-sizing: border-box;
    }
    .btn-remove-row:hover { background: #fecaca; color: #b91c1c; }

    /* Column Sizing */
    .col-item { width: 35%; }
    .col-unit { width: 15%; }
    .col-qty { width: 15%; }
    .col-notes { width: 35%; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('inventory/issuance') ?>">Back to List</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card"><p class="kpi-label">Mode</p><p class="kpi-value">Create</p><p class="kpi-note">New issuance request draft.</p></article>
            <article class="kpi-card"><p class="kpi-label">Default Date</p><p class="kpi-value"><?= esc(date('Y-m-d')) ?></p><p class="kpi-note">Pre-filled issue date.</p></article>
            <article class="kpi-card"><p class="kpi-label">Stock Rule</p><p class="kpi-value" style="color:#d97706;">Strict</p><p class="kpi-note">Items must exist in inventory.</p></article>
            <article class="kpi-card"><p class="kpi-label">Workflow</p><p class="kpi-value">Draft</p><p class="kpi-note">Submit for approval after save.</p></article>
        </div>
    </section>

    <section class="card stack-md">
        <form method="post" action="<?= site_url('inventory/issuance') ?>" class="stack-md">
            <?= csrf_field() ?>

            <div class="form-section stack-md">
                <div class="stack-sm" style="border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">
                    <h2 style="margin: 0; font-size: 1.25rem;">Issuance Header</h2>
                    <p class="muted" style="margin: 0; font-size: 0.85rem;">Set issuance details before filling requested item quantities.</p>
                </div>

                <div class="form-grid-2">
                    <div class="field">
                        <label for="issue_date">Issue Date <span style="color:var(--color-danger);">*</span></label>
                        <input id="issue_date" type="date" name="issue_date" class="form-control-header" value="<?= esc((string) old('issue_date', date('Y-m-d'))) ?>" required>
                    </div>
                    <div class="field">
                        <label for="department">Department</label>
                        <input id="department" type="text" name="department" class="form-control-header" placeholder="e.g., Ward A, Emergency..." value="<?= esc((string) old('department')) ?>">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="field">
                        <label for="purpose">Purpose</label>
                        <textarea id="purpose" name="purpose" class="form-control-header" placeholder="Reason for issuance..."><?= esc((string) old('purpose')) ?></textarea>
                    </div>
                    <div class="field">
                        <label for="remarks">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-control-header" placeholder="Optional internal notes..."><?= esc((string) old('remarks')) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="stack-sm" style="border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">
                <h2 style="margin: 0; font-size: 1.25rem;">Items to Issue</h2>
                <p class="muted" style="margin: 0; font-size: 0.85rem;">Select items from your existing inventory database. Blank rows are automatically ignored.</p>
            </div>

            <div class="table-wrap">
                <table class="table" id="items-table">
                    <colgroup>
                        <col class="col-item">
                        <col class="col-unit">
                        <col class="col-qty">
                        <col class="col-notes">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Unit</th>
                            <th>Requested Qty</th>
                            <th>Item Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <tr>
                                <td>
                                    <select name="item_name[]" class="table-control">
                                        <option value="">Select Item from Database...</option>
                                        <?php foreach ($itemsList as $item): ?>
                                            <?php $selected = (old('item_name.' . $i) === $item) ? 'selected' : ''; ?>
                                            <option value="<?= esc($item) ?>" <?= $selected ?>><?= esc($item) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="unit[]" class="table-control">
                                        <option value="">Select...</option>
                                        <?php foreach ($predefinedUnits as $u): ?>
                                            <?php $selected = (old('unit.' . $i) === $u) ? 'selected' : ''; ?>
                                            <option value="<?= esc($u) ?>" <?= $selected ?>><?= esc($u) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.001" min="0" name="requested_qty[]" class="table-control" placeholder="0" value="<?= esc((string) old('requested_qty.' . $i)) ?>">
                                </td>
                                <td>
                                    <div class="notes-group">
                                        <input type="text" name="item_remarks[]" class="table-control" placeholder="Optional notes..." value="<?= esc((string) old('item_remarks.' . $i)) ?>">
                                        <button type="button" class="btn-remove-row" title="Remove Row" onclick="this.closest('tr').remove()">&times;</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endfor ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="padding: 12px; text-align: center; border-top: 1px dashed var(--color-border-strong);">
                                <button type="button" class="btn btn-outline" onclick="addNewRow()" style="font-weight: 700; font-size: 0.85rem;">+ Add Another Row</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="toolbar" style="margin-top: 16px; border-top: 1px solid var(--color-border); padding-top: 16px;">
                <button type="submit" class="btn btn-primary" style="padding: 8px 24px; font-size: 1rem;">Save Issuance Draft</button>
                <a class="btn btn-outline" href="<?= site_url('inventory/issuance') ?>" style="padding: 8px 24px; font-size: 1rem;">Cancel</a>
            </div>
        </form>
    </section>
</div>

<template id="item-row-template">
    <tr>
        <td>
            <select name="item_name[]" class="table-control">
                <option value="">Select Item from Database...</option>
                <?php foreach ($itemsList as $item): ?>
                    <option value="<?= esc($item) ?>"><?= esc($item) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <select name="unit[]" class="table-control">
                <option value="">Select...</option>
                <?php foreach ($predefinedUnits as $u): ?>
                    <option value="<?= esc($u) ?>"><?= esc($u) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="number" step="0.001" min="0" name="requested_qty[]" class="table-control" placeholder="0">
        </td>
        <td>
            <div class="notes-group">
                <input type="text" name="item_remarks[]" class="table-control" placeholder="Optional notes...">
                <button type="button" class="btn-remove-row" title="Remove Row" onclick="this.closest('tr').remove()">&times;</button>
            </div>
        </td>
    </tr>
</template>

<script>
    function addNewRow() {
        const template = document.getElementById('item-row-template');
        const newRow = template.content.cloneNode(true);
        document.querySelector('#items-table tbody').appendChild(newRow);
    }
</script>

<?= $this->endSection() ?>