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
    
    /* Highlight empty required dropdowns lightly */
    .table-control:invalid { border-left: 3px solid var(--color-danger); }

    /* ROW REMOVAL BUTTON */
    .notes-group { display: flex; gap: 6px; align-items: center; height: 36px; }
    .btn-remove-row {
        flex: 0 0 36px; height: 100%; background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; border-radius: 4px;
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: bold; cursor: pointer; transition: all 0.2s ease; box-sizing: border-box;
    }
    .btn-remove-row:hover { background: #fecaca; color: #b91c1c; }

    /* Column Sizing */
    .col-item { width: 40%; } /* Given more space since there's no price column */
    .col-unit { width: 15%; }
    .col-qty { width: 15%; }
    .col-notes { width: 30%; }

    /* Hidden file input for CSV */
    #csv-file-input { display: none; }
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
                        <input id="issue_date" type="date" name="issue_date" class="form-control-header" value="<?= esc((string) old('issue_date', date('Y-m-d'))) ?>" max="<?= date('Y-m-d') ?>" required title="Select the date this issuance is performed. Future dates are disabled.">
                    </div>
                    <div class="field">
                        <label for="department">Department</label>
                        <input id="department" type="text" name="department" class="form-control-header" placeholder="e.g., Ward A, Emergency..." value="<?= esc((string) old('department')) ?>" title="The department or ward requesting the items.">
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

            <div style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 1px solid var(--color-border); padding-bottom: 8px; margin-bottom: 8px;">
                <div class="stack-sm">
                    <h2 style="margin: 0; font-size: 1.25rem;">Items to Issue</h2>
                    <p class="muted" style="margin: 0; font-size: 0.85rem;">Select items from your existing inventory. Blank rows are ignored. Format: <em>Item Name, Unit, Qty, Notes</em>.</p>
                </div>
                <div>
                    <input type="file" id="csv-file-input" accept=".csv" />
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('csv-file-input').click()" style="font-weight: 700; color: #0f766e; border-color: #86efac; background: #f0fdf4;">
                        &#x2913; Import CSV
                    </button>
                </div>
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
                        <?php for ($i = 0; $i < 3; $i++): ?>
                            <tr>
                                <td>
                                    <select name="item_name[]" class="table-control item-dropdown">
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
                                    <input type="number" step="1" min="0" name="requested_qty[]" class="table-control" placeholder="0" value="<?= esc((string) old('requested_qty.' . $i)) ?>">
                                </td>
                                <td>
                                    <div class="notes-group">
                                        <input type="text" name="item_remarks[]" class="table-control" placeholder="Optional notes..." value="<?= esc((string) old('item_remarks.' . $i)) ?>">
                                        <button type="button" class="btn-remove-row" title="Remove Row" onclick="removeRow(this)">&times;</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endfor ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="padding: 12px; text-align: center; border-top: 1px dashed var(--color-border-strong);">
                                <button type="button" class="btn btn-outline" onclick="addNewRow()" style="font-weight: 700; font-size: 0.85rem;">+ Add Manual Row</button>
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
            <select name="item_name[]" class="table-control item-dropdown">
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
            <input type="number" step="1" min="0" name="requested_qty[]" class="table-control" placeholder="0">
        </td>
        <td>
            <div class="notes-group">
                <input type="text" name="item_remarks[]" class="table-control" placeholder="Optional notes...">
                <button type="button" class="btn-remove-row" title="Remove Row" onclick="removeRow(this)">&times;</button>
            </div>
        </td>
    </tr>
</template>

<script>
    const systemItems = <?= json_encode($itemsList) ?>;
    const systemUnits = <?= json_encode($predefinedUnits) ?>;
</script>

<script>
    // --- ROW REMOVE ---
    function removeRow(btn) {
        const row = btn.closest('tr');
        if (row) {
            row.remove();
        }
    }

    // --- DYNAMIC ROW LOGIC ---
    function addNewRow() {
        const template = document.getElementById('item-row-template');
        const newRow = template.content.cloneNode(true);
        document.querySelector('#items-table tbody').appendChild(newRow);
    }

    // --- CSV IMPORT LOGIC (STRICT INVENTORY MATCHING) ---
    document.getElementById('csv-file-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
            const text = event.target.result;
            const rows = text.split('\n');
            let rowsAdded = 0;
            let missingItems = [];

            // Remove default empty rows to make room
            const tbody = document.querySelector('#items-table tbody');
            const existingRows = tbody.querySelectorAll('tr');
            existingRows.forEach(tr => {
                const select = tr.querySelector('.item-dropdown');
                if(select && select.value === '') tr.remove();
            });

            // Start at 1 to skip header row (Assumes format: Item Name, Unit, Qty, Notes)
            for(let i = 1; i < rows.length; i++) {
                const cols = rows[i].split(','); 
                
                if(cols.length >= 3 && cols[0].trim() !== '') {
                    const parsedItem = cols[0].trim();
                    const parsedUnit = cols[1].trim();
                    const parsedQty = cols[2].trim();
                    const parsedNotes = cols[3] ? cols[3].trim() : '';

                    // 1. Create a new row
                    addNewRow();
                    const newTr = document.querySelector('#items-table tbody tr:last-child');
                    const itemDropdown = newTr.querySelector('.item-dropdown');

                    // 2. Strict Item Matching (No auto-adding allowed here)
                    if (systemItems.includes(parsedItem)) {
                        itemDropdown.value = parsedItem;
                    } else {
                        // Leave it blank and flag it
                        missingItems.push(parsedItem);
                        newTr.querySelector('input[name="item_remarks[]"]').value = "ERROR: Item not in inventory (" + parsedItem + ")";
                    }

                    // 3. Handle Unit Validation
                    const unitDropdown = newTr.querySelector('select[name="unit[]"]');
                    let unitMatch = '';
                    systemUnits.forEach(u => { if(u.toLowerCase() === parsedUnit.toLowerCase()) unitMatch = u; });
                    
                    if(unitMatch) {
                        unitDropdown.value = unitMatch;
                    } else {
                        unitDropdown.value = 'Piece'; // Safe default
                        newTr.querySelector('input[name="item_remarks[]"]').value += " | Unit Mismatch: " + parsedUnit;
                    }

                    // 4. Fill remaining data
                    newTr.querySelector('input[name="requested_qty[]"]').value = parseInt(parsedQty) || 0; // Enforce whole numbers
                    
                    if(parsedNotes && !missingItems.includes(parsedItem) && unitMatch) {
                        newTr.querySelector('input[name="item_remarks[]"]').value = parsedNotes;
                    }

                    rowsAdded++;
                }
            }
            
            document.getElementById('csv-file-input').value = '';
            
            // Alert user of results
            if (missingItems.length > 0) {
                alert(`Imported ${rowsAdded} rows, but ${missingItems.length} item(s) could not be found in your inventory database. Please review the highlighted rows.`);
            } else {
                alert(`Successfully and safely imported ${rowsAdded} items from CSV.`);
            }
        };
        reader.readAsText(file);
    });
</script>

<?= $this->endSection() ?>