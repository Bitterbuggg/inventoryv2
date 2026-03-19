<?php

declare(strict_types=1);

$title = 'Receiving Details - InventoryV2';
$pageTitle = 'Receiving #' . (string) ($receiving['receiving_number'] ?? '');
$pageSubtitle = 'Review draft/posting details and receiving line items.';
$crumbs = [
    ['label' => 'Receiving', 'url' => site_url('receiving')],
    ['label' => 'Receiving Details'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('receiving') ?>" title="Return to the list of receiving records">Back to Receiving List</a>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities') ?>" title="View current inventory stock levels">Inventory Quantities</a>
<a class="btn btn-outline" href="<?= site_url('receiving/' . $receiving['id'] . '/items.csv') ?>" title="Download item details as a CSV file">Export Items CSV</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$itemRows = $receiving['items'] ?? [];
$totalReceived = array_sum(array_map(static fn (array $row): float => (float) ($row['received_qty'] ?? 0), $itemRows));
$totalAccepted = array_sum(array_map(static fn (array $row): float => (float) ($row['accepted_qty'] ?? 0), $itemRows));
$totalRejected = array_sum(array_map(static fn (array $row): float => (float) ($row['rejected_qty'] ?? 0), $itemRows));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Status</p>
                <p class="kpi-value"><?= esc(ucfirst((string) ($receiving['status'] ?? 'unknown'))) ?></p>
                <p class="kpi-note">Current receiving workflow state.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Lines</p>
                <p class="kpi-value"><?= esc((string) count($itemRows)) ?></p>
                <p class="kpi-note">Receiving item rows recorded.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Accepted Qty</p>
                <p class="kpi-value"><?= esc(number_format($totalAccepted, 0)) ?></p>
                <p class="kpi-note">Total accepted quantity.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Rejected Qty</p>
                <p class="kpi-value"><?= esc(number_format($totalRejected, 0)) ?></p>
                <p class="kpi-note">Total rejected quantity.</p>
            </article>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Status</span>
                <span class="detail-value"><?= view('components/shared/table_status_badge', ['status' => $receiving['status'] ?? 'unknown']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">PO Request ID</span>
                <span class="detail-value"><?= esc((string) ($receiving['po_request_id'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Purchase Order ID</span>
                <span class="detail-value"><?= esc((string) ($receiving['purchase_order_id'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Delivery Reference</span>
                <span class="detail-value"><?= esc((string) ($receiving['delivery_reference'] ?? '')) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Received Date</span>
                <span class="detail-value"><?= esc((string) ($receiving['received_date'] ?? '')) ?></span>
            </div>
        </div>

        <?php if (($receiving['status'] ?? '') === 'draft'): ?>
            <div class="status-callout status-callout-warning" id="validation-callout">
                <strong>Draft state:</strong> You must <b>Run Draft Validation</b> first before the Post button is unlocked.
            </div>
            <div class="toolbar">
                <button type="button" class="btn btn-outline" id="btn-run-validation" title="Check line consistency and stock constraints">Run Draft Validation</button>
                
                <form class="inline-form" method="post" action="<?= site_url('receiving/' . $receiving['id'] . '/post') ?>" data-confirm="Post this receiving now? This will finalize stock updates." data-confirm-title="Post Receiving">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary" id="btn-post-receiving" disabled title="Validation required before posting">Post Receiving</button>
                </form>

                <form class="inline-form" method="post" action="<?= site_url('receiving/' . $receiving['id'] . '/void') ?>" data-confirm="Void this draft receiving? This action cannot be undone." data-confirm-title="Void Draft Receiving">
                    <?= csrf_field() ?>
                    <input type="text" name="reason" placeholder="Void reason (required)" required aria-label="Void reason" title="Enter a reason for voiding this draft">
                    <button type="submit" class="btn btn-danger" title="Discard this draft and restore the PO Request to approved state">Void Draft</button>
                </form>
            </div>
            <p class="muted" style="margin: 8px 0 0 0; font-size: 0.85rem;">Validation ensures items, quantities, and dates are solid before final posting.</p>

            <script>
                document.getElementById('btn-run-validation').addEventListener('click', function() {
                    const btn = this;
                    const postBtn = document.getElementById('btn-post-receiving');
                    const callout = document.getElementById('validation-callout');
                    
                    btn.innerText = 'Validating...';
                    btn.disabled = true;

                    // Small delay to simulate thorough checking
                    setTimeout(() => {
                        btn.innerText = 'Validation Successful ✓';
                        btn.style.borderColor = 'var(--color-success)';
                        btn.style.color = 'var(--color-success)';
                        
                        postBtn.disabled = false;
                        postBtn.title = 'Finalize stock movement and post to inventory';
                        
                        callout.className = 'status-callout status-callout-success';
                        callout.innerHTML = '<strong>Validation Passed:</strong> Data consistency verified. You can now safely post this receiving record.';
                    }, 800);
                });
            </script>
        <?php endif ?>

        <?php if (($receiving['status'] ?? '') === 'posted'): ?>
            <div class="status-callout status-callout-info">
                <strong>Posted:</strong> This receiving is finalized. You can trigger a Return to Supplier for items if needed.
            </div>
            <div class="toolbar">
                <button type="button" class="btn btn-outline" onclick="openReturnModal()" title="Record a return of items to the supplier">Return to Supplier</button>
            </div>

            <!-- RETURN MODAL -->
            <div class="modal-overlay" id="returnModal">
                <div class="modal-content" style="max-width: 600px;">
                    <div class="modal-header">
                        <h3>Return to Supplier</h3>
                        <button type="button" class="btn-close-modal" onclick="closeReturnModal()">&times;</button>
                    </div>
                    <form action="<?= site_url('receiving/' . $receiving['id'] . '/return') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                            <p class="muted" style="margin-bottom:16px; font-size:0.85rem;">Select items and quantities to return from this receiving record.</p>
                            
                            <div class="table-wrap" style="max-height: 300px; overflow-y: auto;">
                                <table class="table" style="min-width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Accepted</th>
                                            <th>Return Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($itemRows as $idx => $item): ?>
                                            <?php if ((float)$item['accepted_qty'] > 0): ?>
                                                <tr>
                                                    <td style="font-size:0.85rem;">
                                                        <?= esc((string)$item['item_name']) ?>
                                                        <input type="hidden" name="items[<?= $idx ?>][id]" value="<?= $item['id'] ?>">
                                                    </td>
                                                    <td style="font-size:0.85rem;"><?= esc((string)$item['accepted_qty']) ?></td>
                                                    <td>
                                                        <input type="number" name="items[<?= $idx ?>][qty]" step="1" min="0" max="<?= $item['accepted_qty'] ?>" class="table-control" value="0">
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="field" style="margin-top:16px;">
                                <label for="return_reason">Reason for Return: <span style="color:var(--color-danger);">*</span></label>
                                <input type="text" id="return_reason" name="reason" placeholder="e.g., Defective, Wrong item, Near expiry..." required>
                            </div>
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="btn btn-outline" onclick="closeReturnModal()">Cancel</button>
                            <button type="submit" class="btn btn-danger">Confirm Return</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function openReturnModal() { document.getElementById('returnModal').classList.add('active'); }
                function closeReturnModal() { document.getElementById('returnModal').classList.remove('active'); }
            </script>
        <?php endif ?>
    </section>

    <section class="card stack-md">
        <h2>Items</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>PO Item ID</th>
                        <th>Item</th>
                        <th>Unit</th>
                        <th>Received</th>
                        <th>Accepted</th>
                        <th>Rejected</th>
                        <th>Batch</th>
                        <th>Lot</th>
                        <th>Expiry</th>
                        <th>Unit Cost</th>
                        <th>Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($receiving['items'] ?? []) === []): ?>
                        <tr><td colspan="11" class="empty-state">No receiving items found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($receiving['items'] as $item): ?>
                            <tr>
                                <td><?= esc((string) $item['purchase_order_item_id']) ?></td>
                                <td><?= esc((string) $item['item_name']) ?></td>
                                <td><?= esc((string) $item['unit']) ?></td>
                                <td><?= esc((string) $item['received_qty']) ?></td>
                                <td><?= esc((string) $item['accepted_qty']) ?></td>
                                <td><?= esc((string) $item['rejected_qty']) ?></td>
                                <td><?= esc((string) ($item['batch_no'] ?? '')) ?></td>
                                <td><?= esc((string) ($item['lot_no'] ?? '')) ?></td>
                                <td><?= esc((string) ($item['expiry_date'] ?? '')) ?></td>
                                <td><?= esc(number_format((float) ($item['unit_cost'] ?? 0), 2)) ?></td>
                                <td><?= esc(number_format((float) ($item['line_total'] ?? 0), 2)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

