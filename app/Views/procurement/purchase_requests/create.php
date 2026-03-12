<?php

declare(strict_types=1);

$title = 'Create Purchase Request - InventoryV2';
$pageTitle = 'Create Purchase Request';
$pageSubtitle = 'Build a draft request with at least one line item.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Create'],
];

// Fallback just in case the Controller hasn't passed $dbItems yet
$itemsList = $dbItems ?? [
    'Paracetamol 500mg', 'Amoxicillin 250mg', 'Surgical Masks', 'Latex Gloves (Medium)', 'Syringe 5ml'
];

$predefinedUnits = [
    'Box', 'Piece', 'Vial', 'Bottle', 'Pack', 'Roll', 'Tablet', 'Capsule', 'Ampoule', 'Tube', 'Set'
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- COMPACT HEADER LAYOUT --- */
    .header-layout { display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
    .header-field-date { flex: 0 0 180px; }
    .header-field-remarks { flex: 1; min-width: 300px; }
    
    .form-control-header {
        width: 100%; height: 38px; padding: 6px 12px;
        border: 1px solid var(--color-border-strong); border-radius: 6px;
        font-family: inherit; font-size: 0.85rem; box-sizing: border-box; transition: border-color 0.2s;
    }
    .form-control-header:focus { border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15); }
    .field-label { display: block; font-weight: 700; font-size: 0.85rem; color: #1e293b; margin-bottom: 6px; }

    /* --- TABLE ALIGNMENT --- */
    .table-wrap { overflow-x: auto; width: 100%; }
    #items-table { width: 100%; min-width: 900px; border-collapse: collapse; table-layout: fixed;}
    #items-table th { padding: 10px 6px; font-size: 0.8rem; text-align: left; color: var(--color-text-muted); text-transform: uppercase; }
    #items-table tbody td { vertical-align: middle; border-bottom: 1px solid var(--color-border); padding: 4px; }

    /* STRICT INPUT SIZING FOR ALIGNMENT */
    .table-control {
        width: 100%; height: 34px; padding: 4px 8px; margin: 0;
        border: 1px solid var(--color-border-strong); border-radius: 4px;
        font-size: 0.85rem; font-family: inherit; background: var(--color-surface); color: var(--color-text);
        box-sizing: border-box; transition: border-color 0.2s;
    }
    .table-control:focus { border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15); }

    /* HYBRID INPUT GROUP */
    .hybrid-group { display: flex; width: 100%; height: 34px; }
    .hybrid-group select { flex-grow: 1; border-top-right-radius: 0; border-bottom-right-radius: 0; }
    .hybrid-group .btn-add-item {
        flex: 0 0 36px; height: 34px; margin: 0; padding: 0;
        border: 1px solid var(--color-border-strong); border-left: none; border-radius: 0 4px 4px 0;
        background: var(--color-surface-alt); color: var(--color-brand-600);
        font-size: 1.25rem; font-weight: bold; cursor: pointer;
        display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; box-sizing: border-box;
    }
    .hybrid-group .btn-add-item:hover { background: var(--color-brand-100); color: var(--color-brand-700); }

    /* ROW REMOVAL BUTTON */
    .notes-group { display: flex; gap: 6px; align-items: center; height: 34px; }
    .btn-remove-row {
        flex: 0 0 34px; height: 100%; background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; border-radius: 4px;
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: bold; cursor: pointer; transition: all 0.2s ease; box-sizing: border-box;
    }
    .btn-remove-row:hover { background: #fecaca; color: #b91c1c; }

    /* Column Sizing */
    .col-item { width: 35%; }
    .col-unit { width: 15%; }
    .col-qty { width: 12%; }
    .col-cost { width: 14%; }
    .col-notes { width: 24%; }

    /* --- MODAL STYLES --- */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.6); display: none; align-items: center; justify-content: center; z-index: 9999; backdrop-filter: blur(2px); }
    .modal-overlay.active { display: flex; }
    .modal-content { background: var(--color-surface); padding: 24px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px; }
    .modal-header h3 { margin: 0; font-size: 1.1rem; color: var(--color-text); }
    .btn-close-modal { background: transparent; border: none; font-size: 1.5rem; cursor: pointer; color: var(--color-text-muted); margin:0; padding:0; line-height: 1; }
    .btn-close-modal:hover { color: var(--color-danger); }
    .modal-body .field { margin-bottom: 16px; }
    .modal-body input { width: 100%; padding: 8px; border: 1px solid var(--color-border-strong); border-radius: 4px; box-sizing: border-box; font-family: inherit;}
    .modal-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 24px; }

    /* File Input hidden */
    #csv-file-input { display: none; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Back to List</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    <div class="status-callout status-callout-info">
        <strong>Request guidance:</strong> Fill at least one item row with item, unit, and requested quantity. Blank rows are ignored when saving.
    </div>
    
    <form method="post" action="<?= site_url('procurement/purchase-requests') ?>" class="stack-lg" id="pr-form">
        <?= csrf_field() ?>

        <section class="card stack-md">
            <div class="stack-sm" style="margin-bottom: 4px;">
                <h2 style="font-size: 1.25rem; margin: 0;">Request Header</h2>
                <p class="muted" style="margin: 0; font-size: 0.85rem;">Set request timeline and optional instructions.</p>
            </div>

            <div class="header-layout">
                <div class="header-field-date">
                    <label for="request_date" class="field-label">Request Date <span style="color:var(--color-danger);">*</span></label>
                    <input id="request_date" type="date" name="request_date" class="form-control-header" value="<?= esc((string) old('request_date', date('Y-m-d'))) ?>" required>
                </div>
                <div class="header-field-date">
                    <label for="needed_date" class="field-label">Needed Date <span class="muted" style="font-weight: normal;">(Optional)</span></label>
                    <input id="needed_date" type="date" name="needed_date" class="form-control-header" value="<?= esc((string) old('needed_date')) ?>">
                </div>
                <div class="header-field-remarks">
                    <label for="remarks" class="field-label">Remarks / Notes</label>
                    <input id="remarks" type="text" name="remarks" class="form-control-header" placeholder="Optional notes regarding this request..." value="<?= esc((string) old('remarks')) ?>">
                </div>
            </div>
        </section>

        <section class="card stack-md">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 1px solid var(--color-border); padding-bottom: 8px; margin-bottom: 8px;">
                <div class="stack-sm">
                    <h2 style="font-size: 1.25rem; margin: 0;">Requested Items</h2>
                    <p class="muted" style="margin: 0; font-size: 0.85rem;">Fill manually or import from a CSV (Excel) file. Format: <em>Item Name, Unit, Qty, Cost, Notes</em>.</p>
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
                        <col class="col-cost">
                        <col class="col-notes">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Unit</th>
                            <th>Req Qty</th>
                            <th>Est. Unit Cost</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < 3; $i++): ?>
                            <tr>
                                <td>
                                    <div class="hybrid-group">
                                        <select name="item_name[]" class="table-control item-dropdown" id="item-select-<?= $i ?>">
                                            <option value="">Select Item...</option>
                                            <?php foreach ($itemsList as $item): ?>
                                                <?php $selected = (old('item_name.' . $i) === $item) ? 'selected' : ''; ?>
                                                <option value="<?= esc($item) ?>" <?= $selected ?>><?= esc($item) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn-add-item" onclick="openAddItemModal('item-select-<?= $i ?>')" title="Add New Item">+</button>
                                    </div>
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
                                    <input type="number" step="1" min="1" name="requested_qty[]" class="table-control" placeholder="0" value="<?= esc((string) old('requested_qty.' . $i)) ?>">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" name="estimated_unit_cost[]" class="table-control" placeholder="0.00" value="<?= esc((string) old('estimated_unit_cost.' . $i)) ?>">
                                </td>
                                <td>
                                    <div class="notes-group">
                                        <input type="text" name="notes[]" class="table-control" placeholder="Optional" value="<?= esc((string) old('notes.' . $i)) ?>">
                                        <button type="button" class="btn-remove-row" title="Remove Row" onclick="removeRow(this)">&times;</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endfor ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="padding: 12px; text-align: center; border-top: 1px dashed var(--color-border-strong);">
                                <button type="button" class="btn btn-outline" onclick="addNewRow()" style="font-weight: 700; font-size: 0.85rem;">+ Add Manual Row</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="toolbar" style="margin-top: 16px; border-top: 1px solid var(--color-border); padding-top: 16px;">
                <button type="submit" class="btn btn-primary" style="padding: 8px 24px; font-size: 1rem;">Save Draft Purchase Request</button>
                <a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>" style="padding: 8px 24px; font-size: 1rem;">Cancel</a>
            </div>
        </section>
    </form>
</div>

<template id="item-row-template">
    <tr>
        <td>
            <div class="hybrid-group">
                <select name="item_name[]" class="table-control item-dropdown">
                    <option value="">Select Item...</option>
                    <?php foreach ($itemsList as $item): ?>
                        <option value="<?= esc($item) ?>"><?= esc($item) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn-add-item" title="Add New Item">+</button>
            </div>
        </td>
        <td>
            <select name="unit[]" class="table-control">
                <option value="">Select...</option>
                <?php foreach ($predefinedUnits as $u): ?>
                    <option value="<?= esc($u) ?>"><?= esc($u) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="number" step="1" min="1" name="requested_qty[]" class="table-control" placeholder="0"></td>
        <td><input type="number" step="0.01" min="0" name="estimated_unit_cost[]" class="table-control" placeholder="0.00"></td>
        <td>
            <div class="notes-group">
                <input type="text" name="notes[]" class="table-control" placeholder="Optional">
                <button type="button" class="btn-remove-row" title="Remove Row" onclick="removeRow(this)">&times;</button>
            </div>
        </td>
    </tr>
</template>

<script>
    const systemItems = <?= json_encode($itemsList) ?>;
    const systemUnits = <?= json_encode($predefinedUnits) ?>;
</script>

<div class="modal-overlay" id="addItemModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Item Master</h3>
            <button type="button" class="btn-close-modal" onclick="closeAddItemModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="muted" style="margin-bottom:16px; font-size:0.85rem;">This will create a new item in the database and add it to your current list.</p>
            <div class="field">
                <label for="new_item_name" style="font-weight: 600; font-size: 0.85rem;">Item Name <span style="color:var(--color-danger);">*</span></label>
                <input type="text" id="new_item_name" placeholder="e.g., Nitrile Gloves (Large)" style="padding: 10px; font-size: 1rem;">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeAddItemModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveNewItem()">Add & Select</button>
            </div>
        </div>
    </div>
</div>

<script>
    // --- DELETE CONFIRMATION ---
    function removeRow(btn) {
        const row = btn.closest('tr');
        if (row) {
            row.remove();
        }
    }

    // --- MODAL LOGIC ---
    let activeSelectId = null;

    function openAddItemModal(selectId) {
        activeSelectId = selectId;
        document.getElementById('new_item_name').value = ''; 
        document.getElementById('addItemModal').classList.add('active');
        document.getElementById('new_item_name').focus();
    }

    function closeAddItemModal() {
        document.getElementById('addItemModal').classList.remove('active');
        activeSelectId = null;
    }

    function saveNewItem(itemNameOverride = null) {
        // Can accept direct string or read from input
        const newItemName = itemNameOverride ? itemNameOverride.trim() : document.getElementById('new_item_name').value.trim();
        if (newItemName === '') {
            alert('Please enter an item name.'); return;
        }

        // Add to global JS array so future clones have it
        if (!systemItems.includes(newItemName)) {
            systemItems.push(newItemName);
            
            // Add to all existing dropdowns
            const allDropdowns = document.querySelectorAll('.item-dropdown');
            allDropdowns.forEach(dropdown => {
                const newOption = document.createElement('option');
                newOption.value = newItemName;
                newOption.text = newItemName;
                dropdown.add(newOption);
            });
        }

        if (activeSelectId && !itemNameOverride) {
            document.getElementById(activeSelectId).value = newItemName;
        }
        closeAddItemModal();
    }

    // --- DYNAMIC ROW LOGIC ---
    function addNewRow() {
        const template = document.getElementById('item-row-template');
        const newRow = template.content.cloneNode(true);
        const uniqueId = 'item-select-' + Date.now();
        
        const selectElement = newRow.querySelector('.item-dropdown');
        const addButton = newRow.querySelector('.btn-add-item');
        
        selectElement.id = uniqueId;
        addButton.setAttribute('onclick', `openAddItemModal('${uniqueId}')`);
        
        document.querySelector('#items-table tbody').appendChild(newRow);
        return uniqueId; // Returns the ID so we can manipulate it if needed
    }

    // --- CSV IMPORT LOGIC (SEAMLESS CLIENT-SIDE) ---
    document.getElementById('csv-file-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
            const text = event.target.result;
            const rows = text.split('\n');
            let rowsAdded = 0;

            // Remove empty default rows if they are blank to make room for imported ones
            const tbody = document.querySelector('#items-table tbody');
            const existingRows = tbody.querySelectorAll('tr');
            existingRows.forEach(tr => {
                const select = tr.querySelector('.item-dropdown');
                if(select && select.value === '') tr.remove();
            });

            // Start at 1 to skip header row (assumes: Item, Unit, Qty, Cost, Notes)
            for(let i = 1; i < rows.length; i++) {
                // Handle basic comma separation (Doesn't handle quotes perfectly, but good for simple files)
                const cols = rows[i].split(','); 
                
                if(cols.length >= 3 && cols[0].trim() !== '') {
                    const parsedItem = cols[0].trim();
                    const parsedUnit = cols[1].trim();
                    const parsedQty = cols[2].trim();
                    const parsedCost = cols[3] ? cols[3].trim() : '';
                    const parsedNotes = cols[4] ? cols[4].trim() : '';

                    // 1. Create a new row
                    const newSelectId = addNewRow();
                    const newTr = document.querySelector('#items-table tbody tr:last-child');

                    // 2. Handle Item Name (Auto-add if missing)
                    if (!systemItems.includes(parsedItem)) {
                        saveNewItem(parsedItem); // Adds silently to all dropdowns
                    }
                    newTr.querySelector('.item-dropdown').value = parsedItem;

                    // 3. Handle Unit Validation
                    const unitDropdown = newTr.querySelector('select[name="unit[]"]');
                    let unitMatch = '';
                    // Case insensitive check
                    systemUnits.forEach(u => { if(u.toLowerCase() === parsedUnit.toLowerCase()) unitMatch = u; });
                    
                    if(unitMatch) {
                        unitDropdown.value = unitMatch;
                    } else {
                        // Fallback if unit mismatch
                        unitDropdown.value = 'Piece'; 
                        newTr.querySelector('input[name="notes[]"]').value = "Imported Unit Mismatch: " + parsedUnit;
                    }

                    // 4. Fill remaining data
                    newTr.querySelector('input[name="requested_qty[]"]').value = parseInt(parsedQty, 10) || 0;
                    newTr.querySelector('input[name="estimated_unit_cost[]"]').value = parsedCost;
                    if(parsedNotes && !unitMatch) {
                        newTr.querySelector('input[name="notes[]"]').value += " | " + parsedNotes;
                    } else if (parsedNotes) {
                        newTr.querySelector('input[name="notes[]"]').value = parsedNotes;
                    }

                    rowsAdded++;
                }
            }
            
            // Reset input so user can upload the same file again if they want
            document.getElementById('csv-file-input').value = '';
            alert(`Successfully imported ${rowsAdded} items from CSV.`);
        };
        reader.readAsText(file);
    });

    document.getElementById('items-table').addEventListener('input', function (e) {
        if (e.target && e.target.name === 'requested_qty[]') {
            const value = e.target.value.trim();
            if (value === '') {
                return;
            }

            const parsed = Number(value);
            if (!Number.isFinite(parsed)) {
                e.target.value = '';
                return;
            }

            e.target.value = String(Math.max(0, Math.trunc(parsed)));
        }
    });
</script>

<?= $this->endSection() ?>
