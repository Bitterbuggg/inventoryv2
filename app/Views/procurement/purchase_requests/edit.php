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

// Items list (passed from Controller, or fallback)
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
    .header-layout {
        display: flex; gap: 16px; align-items: flex-end; flex-wrap: nowrap;
    }
    .header-field-date { flex: 0 0 180px; }
    .header-field-remarks { flex: 1; }
    .form-control-header {
        width: 100%; height: 38px; padding: 6px 12px;
        border: 1px solid var(--color-border-strong); border-radius: 6px;
        font-family: inherit; font-size: 0.85rem; box-sizing: border-box;
        transition: border-color 0.2s;
    }
    .form-control-header:focus { 
        border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
    }
    .field-label { display: block; font-weight: 700; font-size: 0.85rem; color: #1e293b; margin-bottom: 6px; }

    /* --- TABLE ALIGNMENT --- */
    .table-wrap { overflow-x: auto; }
    #items-table { width: 100%; min-width: 900px; }
    #items-table tbody td { vertical-align: middle; }

    /* STRICT INPUT SIZING */
    .table-control {
        width: 100%; height: 38px; padding: 6px 12px; margin: 0;
        border: 1px solid var(--color-border-strong); border-radius: 4px;
        font-size: 0.85rem; font-family: inherit;
        background: var(--color-surface); color: var(--color-text); box-sizing: border-box;
    }
    .table-control:focus { border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15); }

    /* HYBRID INPUT GROUP */
    .hybrid-group { display: flex; width: 100%; height: 38px; }
    .hybrid-group select { flex-grow: 1; border-top-right-radius: 0; border-bottom-right-radius: 0; }
    .hybrid-group .btn-add-item {
        flex: 0 0 40px; height: 38px; margin: 0; padding: 0;
        border: 1px solid var(--color-border-strong); border-left: none; border-radius: 0 4px 4px 0;
        background: var(--color-surface-alt); color: var(--color-brand-600);
        font-size: 1.25rem; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;
    }
    .hybrid-group .btn-add-item:hover { background: var(--color-brand-100); color: var(--color-brand-700); }

    /* ROW REMOVAL BUTTON */
    .notes-group { display: flex; gap: 6px; align-items: center; }
    .btn-remove-row {
        flex: 0 0 32px; height: 32px; background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; border-radius: 4px;
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: bold; cursor: pointer; transition: all 0.2s ease;
    }
    .btn-remove-row:hover { background: #fecaca; color: #b91c1c; }

    /* Column Sizing */
    .col-item { width: 32%; } .col-unit { width: 14%; } .col-qty { width: 12%; } .col-cost { width: 16%; } .col-notes { width: 26%; }

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
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Back to List</a>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests/' . $purchaseRequest['id'] . '/items.csv') ?>">Export Items CSV</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $currentStatus = (string) ($purchaseRequest['status'] ?? 'draft'); ?>

<div class="stack-lg">
    <div class="status-callout status-callout-info">
        <strong>Edit guidance:</strong> You can update draft rows safely. At least one valid item row is required before saving.
    </div>
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

    <form method="post" action="<?= site_url('procurement/purchase-requests/' . $purchaseRequest['id'] . '/update') ?>" class="stack-lg" id="pr-form">
        <?= csrf_field() ?>

        <section class="card stack-md">
            <div class="stack-sm" style="margin-bottom: 4px;">
                <h2 style="font-size: 1.25rem; margin: 0;">Request Header</h2>
                <p class="muted" style="margin: 0; font-size: 0.85rem;">Update request details before adjusting line items.</p>
            </div>

            <div class="header-layout">
                <div class="header-field-date">
                    <label for="request_date" class="field-label">Request Date <span style="color:var(--color-danger);">*</span></label>
                    <input id="request_date" type="date" name="request_date" class="form-control-header" value="<?= esc((string) old('request_date', (string) ($purchaseRequest['request_date'] ?? ''))) ?>" required>
                </div>
                <div class="header-field-date">
                    <label for="needed_date" class="field-label">Needed Date <span class="muted" style="font-weight: normal;">(Optional)</span></label>
                    <input id="needed_date" type="date" name="needed_date" class="form-control-header" value="<?= esc((string) old('needed_date', (string) ($purchaseRequest['needed_date'] ?? ''))) ?>">
                </div>
                <div class="header-field-remarks">
                    <label for="remarks" class="field-label">Remarks / Notes</label>
                    <input id="remarks" type="text" name="remarks" class="form-control-header" placeholder="Optional notes regarding this request..." value="<?= esc((string) old('remarks', (string) ($purchaseRequest['remarks'] ?? ''))) ?>">
                </div>
            </div>
        </section>

        <section class="card stack-md">
            <div class="stack-sm" style="margin-bottom: 8px;">
                <h2 style="font-size: 1.25rem; margin: 0;">Requested Items</h2>
                <p class="muted" style="margin: 0; font-size: 0.85rem;">Fill at least one row. Blank rows are automatically ignored.</p>
            </div>

            <div class="table-wrap">
                <table class="table" id="items-table">
                    <thead>
                        <tr>
                            <th class="col-item">Item Name</th>
                            <th class="col-unit">Unit</th>
                            <th class="col-qty">Req Qty</th>
                            <th class="col-cost">Est. Unit Cost</th>
                            <th class="col-notes">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < $rowCount; $i++): ?>
                            <?php 
                                // Fetch the existing values cleanly
                                $currentItem = (string) old('item_name.' . $i, (string) ($existingItems[$i]['item_name'] ?? ''));
                                $currentUnit = (string) old('unit.' . $i, (string) ($existingItems[$i]['unit'] ?? ''));
                                $currentQty = (string) old('requested_qty.' . $i, (string) ($existingItems[$i]['requested_qty'] ?? ''));
                                $currentCost = (string) old('estimated_unit_cost.' . $i, (string) ($existingItems[$i]['estimated_unit_cost'] ?? ''));
                                $currentNotes = (string) old('notes.' . $i, (string) ($existingItems[$i]['notes'] ?? ''));

                                // If the item name is populated, flag it as "existing data"
                                $hasData = !empty($currentItem) ? 'true' : 'false';
                            ?>
                            <tr>
                                <td>
                                    <div class="hybrid-group">
                                        <select name="item_name[]" class="table-control item-dropdown" id="item-select-<?= $i ?>">
                                            <option value="">Select Item...</option>
                                            <?php 
                                            $itemFound = false;
                                            foreach ($itemsList as $item): 
                                                $selected = ($currentItem === $item) ? 'selected' : '';
                                                if ($selected) $itemFound = true;
                                            ?>
                                                <option value="<?= esc($item) ?>" <?= $selected ?>><?= esc($item) ?></option>
                                            <?php endforeach; ?>
                                            
                                            <?php if ($currentItem && !$itemFound): ?>
                                                <option value="<?= esc($currentItem) ?>" selected><?= esc($currentItem) ?></option>
                                            <?php endif; ?>
                                        </select>
                                        <button type="button" class="btn-add-item" onclick="openAddItemModal('item-select-<?= $i ?>')" title="Add New Item">+</button>
                                    </div>
                                </td>
                                <td>
                                    <select name="unit[]" class="table-control">
                                        <option value="">Select...</option>
                                        <?php 
                                        $unitFound = false;
                                        foreach ($predefinedUnits as $u): 
                                            $selected = ($currentUnit === $u) ? 'selected' : '';
                                            if ($selected) $unitFound = true;
                                        ?>
                                            <option value="<?= esc($u) ?>" <?= $selected ?>><?= esc($u) ?></option>
                                        <?php endforeach; ?>
                                        
                                        <?php if ($currentUnit && !$unitFound): ?>
                                            <option value="<?= esc($currentUnit) ?>" selected><?= esc($currentUnit) ?></option>
                                        <?php endif; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="1" min="1" name="requested_qty[]" class="table-control" placeholder="0" value="<?= esc($currentQty) ?>">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" name="estimated_unit_cost[]" class="table-control" placeholder="0.00" value="<?= esc($currentCost) ?>">
                                </td>
                                <td>
                                    <div class="notes-group">
                                        <input type="text" name="notes[]" class="table-control" placeholder="Optional" value="<?= esc($currentNotes) ?>">
                                        <button type="button" class="btn-remove-row" title="Remove Row" onclick="smartRemoveRow(this, <?= $hasData ?>)">&times;</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endfor ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="padding: 12px; text-align: center; border-top: 1px dashed var(--color-border-strong);">
                                <button type="button" class="btn btn-outline" onclick="addNewRow()" style="font-weight: 700; font-size: 0.85rem;">+ Add Another Item</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="toolbar" style="margin-top: 16px; border-top: 1px solid var(--color-border); padding-top: 16px;">
                <button type="submit" class="btn btn-primary" style="padding: 8px 24px; font-size: 1rem;">Update Purchase Request</button>
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
                <button type="button" class="btn-remove-row" title="Remove Row" onclick="smartRemoveRow(this, false)">&times;</button>
            </div>
        </td>
    </tr>
</template>

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
    // --- SMART REMOVAL LOGIC ---
    function smartRemoveRow(buttonElement, containsSavedData) {
        if (containsSavedData) {
            // Visual cue when removing a pre-loaded row from draft
            const row = buttonElement.closest('tr');
            if (row) {
                row.style.backgroundColor = '#fff7ed';
                row.style.transition = 'background-color 0.25s ease';
                setTimeout(function () { row.style.backgroundColor = ''; }, 300);
            }
        }

        // Proceed with removing the row
        buttonElement.closest('tr').remove();
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

    function saveNewItem() {
        const newItemName = document.getElementById('new_item_name').value.trim();
        if (newItemName === '') { alert('Please enter an item name.'); return; }

        const allDropdowns = document.querySelectorAll('.item-dropdown');
        allDropdowns.forEach(dropdown => {
            const newOption = document.createElement('option');
            newOption.value = newItemName;
            newOption.text = newItemName;
            dropdown.add(newOption);
        });

        if (activeSelectId) { document.getElementById(activeSelectId).value = newItemName; }
        closeAddItemModal();
    }

    document.getElementById('new_item_name').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); saveNewItem(); }
    });

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
    }
</script>

<?= $this->endSection() ?>