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

<?= $this->section('head') ?>
<style>
    /* --- COMPACT HEADER LAYOUT --- */
    .header-layout {
        display: flex;
        gap: 16px;
        align-items: flex-end; 
        flex-wrap: wrap;
    }
    .header-field-date { flex: 0 0 200px; }
    .header-field-remarks { flex: 1; min-width: 300px; }
    
    .form-control-header {
        width: 100%; height: 38px; padding: 6px 12px;
        border: 1px solid var(--color-border-strong); border-radius: 6px;
        font-family: inherit; font-size: 0.85rem; box-sizing: border-box;
        transition: border-color 0.2s;
    }
    .form-control-header:focus { 
        border-color: var(--color-brand-500); outline: none; 
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
    }
    .field-label { display: block; font-weight: 700; font-size: 0.85rem; color: #1e293b; margin-bottom: 6px; }

    /* --- STRICT TABLE ALIGNMENT (NO SCROLLBAR) --- */
    .table-wrap { 
        width: 100%; 
        overflow-x: auto; /* Allows graceful degradation on tiny screens */
    }
    #conversion-table { 
        width: 100%; 
        min-width: 1050px; /* Minimum safety width */
        border-collapse: collapse; 
        table-layout: fixed; /* Forces exact column percentages */
    }
    #conversion-table th {
        font-size: 0.75rem; text-transform: uppercase; color: var(--color-text-muted);
        padding: 10px 6px !important; border-bottom: 2px solid var(--color-border-strong); text-align: left;
    }
    #conversion-table tbody td { 
        vertical-align: middle; border-bottom: 1px solid var(--color-border); padding: 6px 4px !important; 
    }

    /* COMPACT INPUTS FOR DENSE TABLES */
    .table-control {
        width: 100%; height: 34px; padding: 4px 8px; margin: 0;
        border: 1px solid var(--color-border-strong); border-radius: 4px;
        font-size: 0.8rem; font-family: inherit;
        background: var(--color-surface); color: var(--color-text);
        box-sizing: border-box; transition: border-color 0.2s;
    }
    .table-control:focus { border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15); }
    
    /* Highlight empty required fields lightly */
    .table-control:required:invalid { border-left: 3px solid var(--color-danger); }

    /* Exact Column Percentages */
    .col-item { width: 16%; }
    .col-unit { width: 6%; }
    .col-qty { width: 8%; } /* Rcvd, Acc, Rej share this */
    .col-batch { width: 12%; }
    .col-lot { width: 12%; }
    .col-exp { width: 12%; }
    .col-cost { width: 8%; }
    .col-notes { width: 10%; }

    .text-right { text-align: right !important; }
    .item-text { font-weight: 700; color: var(--color-brand-700); font-size: 0.8rem; line-height: 1.2; word-break: break-word; }
    .unit-text { font-size: 0.75rem; color: var(--color-text-muted); font-weight: 600; text-transform: capitalize; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('receiving') ?>">Back to Receiving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$itemRows = $items ?? [];
// Ensure we always have today's date for expiry validation
$todayDate = date('Y-m-d'); 
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card"><p class="kpi-label">PO Request</p><p class="kpi-value">#<?= esc((string) ($po_request['id'] ?? '')) ?></p><p class="kpi-note">Source request.</p></article>
            <article class="kpi-card"><p class="kpi-label">Purchase Order</p><p class="kpi-value">#<?= esc((string) ($purchase_order['id'] ?? '')) ?></p><p class="kpi-note">Linked PO reference.</p></article>
            <article class="kpi-card"><p class="kpi-label">Line Items</p><p class="kpi-value"><?= esc((string) count($itemRows)) ?></p><p class="kpi-note">Rows to process.</p></article>
            <article class="kpi-card"><p class="kpi-label">Traceability</p><p class="kpi-value" style="color:var(--color-danger);">Strict</p><p class="kpi-note">Lot/Batch/Expiry required.</p></article>
        </div>
    </section>

    <section class="card stack-md">
        <form id="conversion-form" method="post" action="<?= site_url('receiving') ?>" class="stack-md">
            <?= csrf_field() ?>
            <input type="hidden" name="po_request_id" value="<?= esc((string) ($po_request['id'] ?? 0)) ?>">

            <div class="status-callout status-callout-info">
                <strong>Receiving rule:</strong> For each row, ensure Accepted + Rejected equals Received. Batch, lot, and expiry are mandatory for traceability.
            </div>

            <div class="form-section stack-md">
                <div class="stack-sm" style="border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">
                    <h2 style="margin: 0; font-size: 1.25rem;">Receiving Header</h2>
                    <p class="muted" style="margin: 0; font-size: 0.85rem;">Confirm document references and optional delivery notes.</p>
                </div>

                <div class="header-layout">
                    <div class="header-field-date">
                        <label for="received_date" class="field-label">Received Date <span style="color:var(--color-danger);">*</span></label>
                        <input id="received_date" type="date" name="received_date" class="form-control-header" value="<?= esc((string) old('received_date', $todayDate)) ?>" max="<?= $todayDate ?>" required>
                    </div>
                    <div class="header-field-date">
                        <label for="delivery_reference" class="field-label">Delivery Reference <span style="color:var(--color-danger);">*</span></label>
                        <input id="delivery_reference" type="text" name="delivery_reference" class="form-control-header" placeholder="e.g., DR-10293" value="<?= esc((string) old('delivery_reference')) ?>" required>
                    </div>
                    <div class="header-field-remarks">
                        <label for="remarks" class="field-label">Remarks / Notes</label>
                        <input id="remarks" type="text" name="remarks" class="form-control-header" placeholder="Optional notes regarding this delivery..." value="<?= esc((string) old('remarks')) ?>">
                    </div>
                </div>
            </div>

            <div class="stack-sm" style="border-bottom: 1px solid var(--color-border); padding-bottom: 8px; margin-top: 16px;">
                <h2 style="margin: 0; font-size: 1.25rem;">Receiving Items</h2>
                <p class="muted" style="margin: 0; font-size: 0.85rem;">Quantities must be whole numbers. Batch, Lot, and Expiry are strictly required.</p>
            </div>

            <div class="table-wrap">
                <table class="table" id="conversion-table">
                    <colgroup>
                        <col class="col-item">
                        <col class="col-unit">
                        <col class="col-qty">
                        <col class="col-qty">
                        <col class="col-qty">
                        <col class="col-batch">
                        <col class="col-lot">
                        <col class="col-exp">
                        <col class="col-cost">
                        <col class="col-notes">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Unit</th>
                            <th>Rcvd Qty</th>
                            <th>Acc Qty</th>
                            <th>Rej Qty</th>
                            <th>Batch No. <span style="color:var(--color-danger);">*</span></th>
                            <th>Lot No. <span style="color:var(--color-danger);">*</span></th>
                            <th>Expiry Date <span style="color:var(--color-danger);">*</span></th>
                            <th class="text-right">Unit Cost</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $minExpiryDate = date('Y-m-d', strtotime('+3 months')); 
                        ?>
                        <?php foreach ($itemRows as $index => $item): ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="purchase_order_item_id[]" value="<?= esc((string) ($item['purchase_order_item_id'] ?? 0)) ?>">
                                    <input type="hidden" name="product_id[]" value="<?= esc((string) ($item['product_id'] ?? '')) ?>">
                                    <input type="hidden" name="item_name[]" value="<?= esc((string) ($item['item_name'] ?? '')) ?>">
                                    <div class="item-text"><?= esc((string) ($item['item_name'] ?? '')) ?></div>
                                </td>
                                <td>
                                    <input type="hidden" name="unit[]" value="<?= esc((string) ($item['unit'] ?? 'unit')) ?>">
                                    <div class="unit-text"><?= esc((string) ($item['unit'] ?? 'unit')) ?></div>
                                </td>
                                
                                <td><input type="number" step="1" min="0" name="received_qty[]" class="table-control" value="<?= esc(app_format_quantity(old('received_qty.' . $index, $item['received_qty'] ?? 0), '', 3, false)) ?>" required></td>
                                <td><input type="number" step="1" min="0" name="accepted_qty[]" class="table-control" value="<?= esc(app_format_quantity(old('accepted_qty.' . $index, $item['accepted_qty'] ?? 0), '', 3, false)) ?>" required></td>
                                <td><input type="number" step="1" min="0" name="rejected_qty[]" class="table-control" value="<?= esc(app_format_quantity(old('rejected_qty.' . $index, $item['rejected_qty'] ?? 0), '', 3, false)) ?>" required></td>
                                
                                <td><input type="text" name="batch_no[]" class="table-control solid-input" placeholder="Required" value="<?= esc((string) old('batch_no.' . $index)) ?>" pattern="[A-Z0-9\-_]{3,}" title="Minimum 3 characters, alphanumeric, hyphen, or underscore only." required></td>
                                <td><input type="text" name="lot_no[]" class="table-control solid-input" placeholder="Optional" value="<?= esc((string) old('lot_no.' . $index)) ?>" pattern="[A-Z0-9\-_]{3,}" title="Minimum 3 characters, alphanumeric, hyphen, or underscore only."></td>
                                
                                <td><input type="date" name="expiry_date[]" class="table-control" value="<?= esc((string) old('expiry_date.' . $index)) ?>" min="<?= $minExpiryDate ?>" required></td>
                                
                                <td><input type="number" step="0.01" min="0" name="unit_cost[]" class="table-control text-right" value="<?= esc((string) old('unit_cost.' . $index, (string) ($item['unit_cost'] ?? 0))) ?>"></td>
                                <td><input type="text" name="item_remarks[]" class="table-control" placeholder="Optional" value="<?= esc((string) old('item_remarks.' . $index)) ?>"></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <div class="toolbar" style="margin-top: 16px; border-top: 1px solid var(--color-border); padding-top: 16px;">
                <button type="submit" class="btn btn-primary" style="padding: 8px 24px; font-size: 1rem;">Create Receiving Draft</button>
                <a class="btn btn-outline" href="<?= site_url('receiving') ?>" style="padding: 8px 24px; font-size: 1rem;">Cancel</a>
            </div>
        </form>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('conversion-form');
        const rows = document.querySelectorAll('#conversion-table tbody tr');

        // --- TWO-WAY SMART MATH ---
        rows.forEach(row => {
            const rcvdInput = row.querySelector('input[name="received_qty[]"]');
            const accInput = row.querySelector('input[name="accepted_qty[]"]');
            const rejInput = row.querySelector('input[name="rejected_qty[]"]');

            // Helper function to prevent entering numbers higher than Received Qty
            function enforceBounds(input, maxVal) {
                let val = parseInt(input.value) || 0;
                if (val < 0) val = 0;
                if (val > maxVal) {
                    alert(`Value cannot exceed the Total Received Qty (${maxVal})!`);
                    val = maxVal;
                }
                input.value = val;
                return val;
            }

            // 1. If user changes the Total RECEIVED Qty
            rcvdInput.addEventListener('input', function() {
                let r = parseInt(rcvdInput.value) || 0;
                if (r < 0) { r = 0; rcvdInput.value = 0; }
                
                let j = parseInt(rejInput.value) || 0;
                // If they lower Received so much that Rejected is now higher than Received, reset Rejected
                if (j > r) {
                    j = 0;
                    rejInput.value = 0;
                }
                accInput.value = r - j;
            });

            // 2. If user changes the ACCEPTED Qty
            accInput.addEventListener('input', function() {
                let r = parseInt(rcvdInput.value) || 0;
                let a = enforceBounds(accInput, r);
                // Auto-calculate Rejected
                rejInput.value = r - a;
            });

            // 3. If user changes the REJECTED Qty
            rejInput.addEventListener('input', function() {
                let r = parseInt(rcvdInput.value) || 0;
                let j = enforceBounds(rejInput, r);
                // Auto-calculate Accepted
                accInput.value = r - j;
            });
        });

        // --- SOLID BATCH/LOT FORMATTING ---
        document.querySelectorAll('.solid-input').forEach(input => {
            input.addEventListener('input', function() {
                // Auto-uppercase and remove spaces/special chars (allow hyphen and underscore)
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-_]/g, '');
            });
        });

        // --- SUBMIT VALIDATION ---
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Ideal Expiry: +3 months
            const minExpiry = new Date();
            minExpiry.setMonth(minExpiry.getMonth() + 3);
            const minExpiryStr = minExpiry.toISOString().split('T')[0];
            
            // Validate Expiry Dates
            const expiryInputs = document.querySelectorAll('input[name="expiry_date[]"]');
            for(let i=0; i < expiryInputs.length; i++) {
                if (expiryInputs[i].value && expiryInputs[i].value < minExpiryStr) {
                    const confirmMsg = `Row ${i+1}: The expiry date (${expiryInputs[i].value}) is less than 3 months away. Are you sure you want to receive this? Items expiring soon might be wasted.`;
                    if (!confirm(confirmMsg)) {
                        expiryInputs[i].focus();
                        isValid = false;
                        break;
                    }
                }
            }

            if (!isValid) {
                e.preventDefault();
                return;
            }

            // Final Math Safety Check
            for(let i=0; i < rows.length; i++) {
                const rcvd = parseInt(rows[i].querySelector('input[name="received_qty[]"]').value) || 0;
                const acc = parseInt(rows[i].querySelector('input[name="accepted_qty[]"]').value) || 0;
                const rej = parseInt(rows[i].querySelector('input[name="rejected_qty[]"]').value) || 0;

                if (acc + rej !== rcvd) {
                    alert(`Row ${i+1} Quantity Error:\nAccepted Qty (${acc}) + Rejected Qty (${rej}) MUST equal the Total Received Qty (${rcvd}).`);
                    isValid = false;
                    break;
                }
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>

<?= $this->endSection() ?>
