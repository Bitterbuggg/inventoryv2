<?php

declare(strict_types=1);

$title = 'Create Issuance - InventoryV2';
$pageTitle = 'Create Issuance Draft';
$pageSubtitle = 'Request stock using products that currently have available inventory.';
$crumbs = [
    ['label' => 'Inventory Issuance', 'url' => site_url('inventory/issuance')],
    ['label' => 'Create'],
];

$products = $products ?? [];
$productOptions = array_map(static fn (array $product): array => [
    'id'        => (int) ($product['id'] ?? 0),
    'name'      => (string) ($product['product_name'] ?? ''),
    'unit'      => (string) ($product['unit'] ?? 'unit'),
    'available' => (float) ($product['available_qty'] ?? 0),
], $products);
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    .form-grid { display: grid; gap: 16px; grid-template-columns: 1fr 1fr; }
    .field label { display: block; font-weight: 700; font-size: 0.85rem; color: #1e293b; margin-bottom: 6px; }
    .form-control-header { width: 100%; min-height: 40px; padding: 8px 12px; border: 1px solid var(--color-border-strong); border-radius: 8px; font: inherit; box-sizing: border-box; }
    .form-control-header:focus { border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14,165,233,.15); }
    textarea.form-control-header { min-height: 90px; resize: vertical; }
    .table-wrap { overflow-x: auto; }
    #items-table { width: 100%; min-width: 780px; table-layout: fixed; border-collapse: collapse; }
    #items-table th { padding: 10px 8px; text-align: left; font-size: .78rem; text-transform: uppercase; color: var(--color-text-muted); border-bottom: 1px solid var(--color-border); }
    #items-table td { padding: 8px 6px; border-bottom: 1px solid var(--color-border); vertical-align: middle; }
    .table-control { width: 100%; height: 38px; padding: 8px 10px; border: 1px solid var(--color-border-strong); border-radius: 8px; font: inherit; box-sizing: border-box; background: var(--color-surface); }
    .table-control:focus { border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14,165,233,.15); }
    .table-control[readonly] { background: #f8fafc; color: var(--color-text-muted); }
    .notes-cell { display: flex; gap: 8px; align-items: center; }
    .btn-remove-row { width: 36px; height: 36px; border-radius: 8px; border: 1px solid #fecaca; background: #fff1f2; color: #dc2626; font-size: 1.1rem; font-weight: 800; cursor: pointer; }
    .toolbar-line { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; justify-content: space-between; }
    .table-feedback { min-height: 1.3rem; margin: 0; color: var(--color-text-muted); font-size: 0.84rem; line-height: 1.45; }
    @media (max-width: 900px) { .form-grid { grid-template-columns: 1fr; } }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('inventory/issuance') ?>">Back to List</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    <?php if ($products === []): ?>
        <div class="status-callout status-callout-warning">
            <p style="margin:0;"><strong>No available products.</strong> Post receiving transactions first so inventory can supply issuance requests.</p>
        </div>
    <?php else: ?>
        <div class="status-callout status-callout-info">
            <p style="margin:0;"><strong>Availability rule:</strong> only products with current available stock are listed here. Release still validates final stock before deduction.</p>
        </div>
    <?php endif ?>

    <form method="post" action="<?= site_url('inventory/issuance') ?>" class="stack-lg" id="issuance-form" data-dirty-form>
        <?= csrf_field() ?>

        <section class="card stack-md">
            <p class="required-note">Issue date and at least one valid line item are required. Review imported rows before saving the draft.</p>
            <div class="form-grid">
                <div class="field field-required">
                    <label for="issue_date">Issue Date</label>
                    <input id="issue_date" class="form-control-header" type="date" name="issue_date" value="<?= esc((string) old('issue_date', date('Y-m-d'))) ?>" max="<?= date('Y-m-d') ?>" required aria-describedby="issue_date_hint">
                    <p id="issue_date_hint" class="field-hint">Use the date this stock request is being prepared.</p>
                </div>
                <div class="field">
                    <label for="department">Department</label>
                    <input id="department" class="form-control-header" type="text" name="department" value="<?= esc((string) old('department')) ?>" placeholder="e.g. ER, Ward A" aria-describedby="department_hint">
                    <p id="department_hint" class="field-hint">Optional destination or requesting unit.</p>
                </div>
                <div class="field">
                    <label for="purpose">Purpose</label>
                    <textarea id="purpose" class="form-control-header" name="purpose" placeholder="Reason for issuance" aria-describedby="purpose_hint"><?= esc((string) old('purpose')) ?></textarea>
                    <p id="purpose_hint" class="field-hint">Add the clinical or operational reason for the request when useful.</p>
                </div>
                <div class="field">
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" class="form-control-header" name="remarks" placeholder="Optional internal notes" aria-describedby="remarks_hint"><?= esc((string) old('remarks')) ?></textarea>
                    <p id="remarks_hint" class="field-hint">Internal notes help approvers understand exceptions or urgency.</p>
                </div>
            </div>
        </section>

        <section class="card stack-md">
            <div class="toolbar-line">
                <div class="stack-sm">
                    <h2 style="margin:0;">Items to Issue</h2>
                    <p class="muted" style="margin:0;" id="issuance_items_help">CSV format: <em>Product, Qty, Notes</em> or legacy <em>Product, Unit, Qty, Notes</em>.</p>
                </div>
                <div class="toolbar" style="margin:0;">
                    <input type="file" id="csv-file-input" accept=".csv" hidden>
                    <button type="button" class="btn btn-outline" id="issuance-csv-trigger" <?= $products === [] ? 'disabled' : '' ?>>Import CSV</button>
                    <button type="button" class="btn btn-outline" id="issuance-add-row" <?= $products === [] ? 'disabled' : '' ?>>Add Row</button>
                </div>
            </div>

            <div class="split-note" aria-live="polite">
                <span id="issuance-row-count">3 line items ready</span>
                <span>Only products with available stock are listed here. Final release still checks live availability.</span>
            </div>
            <p class="table-feedback" id="issuance-feedback" role="status" aria-live="polite"></p>

            <div class="table-wrap">
                <table id="items-table" aria-describedby="issuance_items_help">
                    <caption class="visually-hidden">Issuance line items.</caption>
                    <thead>
                        <tr>
                            <th style="width: 42%;">Product</th>
                            <th style="width: 14%;">Unit</th>
                            <th style="width: 14%;">Requested Qty</th>
                            <th style="width: 30%;">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < 3; $i++): ?>
                            <tr>
                                <td>
                                    <select name="product_id[]" class="table-control product-select" <?= $products === [] ? 'disabled' : '' ?>>
                                        <option value="">Select product</option>
                                        <?php foreach ($products as $product): ?>
                                            <option value="<?= esc((string) ($product['id'] ?? '')) ?>" data-unit="<?= esc((string) ($product['unit'] ?? 'unit')) ?>" data-available="<?= esc((string) ($product['available_qty'] ?? '0')) ?>" <?= old('product_id.' . $i) == ($product['id'] ?? '') ? 'selected' : '' ?>>
                                                <?= esc((string) ($product['product_name'] ?? '')) ?> (<?= esc((string) ($product['unit'] ?? 'unit')) ?>) - Available: <?= esc(app_format_quantity($product['available_qty'] ?? 0)) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                                <td><input type="text" class="table-control unit-display" value="" readonly></td>
                                <td><input type="number" step="1" min="1" inputmode="numeric" name="requested_qty[]" class="table-control" value="<?= esc(app_format_quantity(old('requested_qty.' . $i), '', 3, false)) ?>"></td>
                                <td>
                                    <div class="notes-cell">
                                        <input type="text" name="item_remarks[]" class="table-control" value="<?= esc((string) old('item_remarks.' . $i)) ?>" placeholder="Optional remarks">
                                        <button type="button" class="btn-remove-row">&times;</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endfor ?>
                    </tbody>
                </table>
            </div>

            <div class="toolbar" style="padding-top: 8px; border-top: 1px solid var(--color-border);">
                <button type="submit" class="btn btn-primary" data-loading-label="Saving draft..." <?= $products === [] ? 'disabled' : '' ?>>Save Issuance Draft</button>
                <a class="btn btn-outline" href="<?= site_url('inventory/issuance') ?>">Cancel</a>
            </div>
        </section>
    </form>
</div>

<template id="item-row-template">
    <tr>
        <td>
            <select name="product_id[]" class="table-control product-select" <?= $products === [] ? 'disabled' : '' ?>>
                <option value="">Select product</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= esc((string) ($product['id'] ?? '')) ?>" data-unit="<?= esc((string) ($product['unit'] ?? 'unit')) ?>" data-available="<?= esc((string) ($product['available_qty'] ?? '0')) ?>">
                        <?= esc((string) ($product['product_name'] ?? '')) ?> (<?= esc((string) ($product['unit'] ?? 'unit')) ?>) - Available: <?= esc(app_format_quantity($product['available_qty'] ?? 0)) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </td>
        <td><input type="text" class="table-control unit-display" value="" readonly></td>
        <td><input type="number" step="1" min="1" inputmode="numeric" name="requested_qty[]" class="table-control"></td>
        <td>
            <div class="notes-cell">
                <input type="text" name="item_remarks[]" class="table-control" placeholder="Optional remarks">
                <button type="button" class="btn-remove-row">&times;</button>
            </div>
        </td>
    </tr>
</template>

<script>
    (function () {
        const catalogProducts = <?= json_encode($productOptions, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
        const tbody = document.querySelector('#items-table tbody');
        const template = document.getElementById('item-row-template');
        const csvInput = document.getElementById('csv-file-input');
        const csvTrigger = document.getElementById('issuance-csv-trigger');
        const addRowButton = document.getElementById('issuance-add-row');
        const feedbackNode = document.getElementById('issuance-feedback');
        const rowCountNode = document.getElementById('issuance-row-count');

        if (!tbody || !template) {
            return;
        }

        const setFeedback = (message) => {
            if (feedbackNode) {
                feedbackNode.textContent = message || '';
            }

            if (message && window.InventoryV2Hci && typeof window.InventoryV2Hci.announce === 'function') {
                window.InventoryV2Hci.announce(message);
            }
        };

        const refreshRowMetadata = () => {
            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.forEach((row, index) => {
                const rowNumber = index + 1;
                const productSelect = row.querySelector('.product-select');
                const unitInput = row.querySelector('.unit-display');
                const qtyInput = row.querySelector('input[name="requested_qty[]"]');
                const notesInput = row.querySelector('input[name="item_remarks[]"]');
                const removeButton = row.querySelector('.btn-remove-row');

                if (productSelect instanceof HTMLElement) {
                    productSelect.setAttribute('aria-label', 'Product for line ' + rowNumber);
                }

                if (unitInput instanceof HTMLElement) {
                    unitInput.setAttribute('aria-label', 'Unit for line ' + rowNumber);
                }

                if (qtyInput instanceof HTMLElement) {
                    qtyInput.setAttribute('aria-label', 'Requested quantity for line ' + rowNumber);
                }

                if (notesInput instanceof HTMLElement) {
                    notesInput.setAttribute('aria-label', 'Remarks for line ' + rowNumber);
                }

                if (removeButton instanceof HTMLElement) {
                    removeButton.setAttribute('aria-label', 'Remove line ' + rowNumber);
                }
            });

            if (rowCountNode) {
                rowCountNode.textContent = rows.length + ' line item' + (rows.length === 1 ? '' : 's') + ' ready';
            }
        };

        const syncRow = (row) => {
            const select = row.querySelector('.product-select');
            const unitInput = row.querySelector('.unit-display');
            if (!(select instanceof HTMLSelectElement) || !(unitInput instanceof HTMLInputElement)) {
                return;
            }

            const selected = select.options[select.selectedIndex];
            unitInput.value = selected ? (selected.dataset.unit || '') : '';
        };

        const addNewRow = () => {
            tbody.appendChild(template.content.cloneNode(true));
            const row = tbody.lastElementChild;

            if (row instanceof HTMLElement) {
                syncRow(row);
                refreshRowMetadata();

                const firstControl = row.querySelector('.product-select');
                if (firstControl instanceof HTMLElement) {
                    firstControl.focus();
                }
            }
        };

        const removeRow = (button) => {
            const row = button.closest('tr');
            if (row) {
                row.remove();
            }

            if (!tbody.querySelector('tr')) {
                addNewRow();
                setFeedback('At least one blank line item row is kept for faster entry.');
                return;
            }

            refreshRowMetadata();
            setFeedback('Line item removed.');
        };

        const findProductByName = (name) => {
            const needle = name.trim().toLowerCase();
            return catalogProducts.find(product => product.name.trim().toLowerCase() === needle) || null;
        };

        const importCsv = (text) => {
            const rows = text.split(/\r?\n/).filter(Boolean);
            if (rows.length <= 1) {
                setFeedback('No rows were imported from the selected CSV file.');
                return;
            }

            tbody.querySelectorAll('tr').forEach((row) => {
                const select = row.querySelector('.product-select');
                if (select instanceof HTMLSelectElement && select.value === '') {
                    row.remove();
                }
            });

            let imported = 0;
            let unmatched = 0;

            rows.slice(1).forEach((line) => {
                const cols = line.split(',');
                if (!cols[0] || !cols[0].trim()) {
                    return;
                }

                addNewRow();

                const row = tbody.lastElementChild;
                if (!(row instanceof HTMLElement)) {
                    return;
                }

                const product = findProductByName(cols[0]);
                const qtyIndex = cols.length >= 4 ? 2 : 1;
                const notesIndex = cols.length >= 4 ? 3 : 2;
                const productSelect = row.querySelector('.product-select');
                const qtyInput = row.querySelector('input[name="requested_qty[]"]');
                const notesInput = row.querySelector('input[name="item_remarks[]"]');

                if (product && productSelect instanceof HTMLSelectElement) {
                    productSelect.value = String(product.id);
                } else {
                    unmatched += 1;
                }

                syncRow(row);

                if (qtyInput instanceof HTMLInputElement) {
                    qtyInput.value = String(parseInt(cols[qtyIndex] || '0', 10) || '');
                }

                if (notesInput instanceof HTMLInputElement) {
                    notesInput.value = product ? (cols[notesIndex] || '').trim() : 'Unmatched product: ' + cols[0].trim();
                }

                imported += 1;
            });

            if (!tbody.querySelector('tr')) {
                addNewRow();
            }

            refreshRowMetadata();

            if (imported === 0) {
                setFeedback('No usable rows were found in the CSV file.');
                return;
            }

            setFeedback('Imported ' + imported + ' row(s).' + (unmatched > 0 ? ' ' + unmatched + ' product name(s) need review.' : ' Review quantities before saving.'));
        };

        document.addEventListener('change', function (event) {
            if (event.target.matches('.product-select')) {
                syncRow(event.target.closest('tr'));
            }
        });

        document.addEventListener('click', function (event) {
            const removeButton = event.target.closest('.btn-remove-row');
            if (removeButton) {
                removeRow(removeButton);
            }
        });

        if (csvTrigger) {
            csvTrigger.addEventListener('click', function () {
                csvInput.click();
            });
        }

        if (addRowButton) {
            addRowButton.addEventListener('click', function () {
                addNewRow();
            });
        }

        if (csvInput) {
            csvInput.addEventListener('change', function (event) {
                const file = event.target.files && event.target.files[0];
                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (loadEvent) {
                    importCsv(String(loadEvent.target.result || ''));
                    event.target.value = '';
                };
                reader.readAsText(file);
            });
        }

        tbody.querySelectorAll('tr').forEach((row) => {
            if (row instanceof HTMLElement) {
                syncRow(row);
            }
        });
        refreshRowMetadata();
    })();
</script>
<?= $this->endSection() ?>
