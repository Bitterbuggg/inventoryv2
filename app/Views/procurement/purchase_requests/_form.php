<?php

declare(strict_types=1);

$products = $products ?? [];
$itemRows = $itemRows ?? [];
$formAction = (string) ($formAction ?? '');
$submitLabel = (string) ($submitLabel ?? 'Save');
$cancelUrl = (string) ($cancelUrl ?? site_url('procurement/purchase-requests'));
$headerTitle = (string) ($headerTitle ?? 'Request Header');
$headerSubtitle = (string) ($headerSubtitle ?? '');
$itemsTitle = (string) ($itemsTitle ?? 'Requested Items');
$itemsSubtitle = (string) ($itemsSubtitle ?? '');
$requestDateValue = (string) ($requestDateValue ?? '');
$requestDateMax = $requestDateMax ?? null;
$neededDateValue = (string) ($neededDateValue ?? '');
$remarksValue = (string) ($remarksValue ?? '');
$allowCsvImport = (bool) ($allowCsvImport ?? false);
$calloutHtml = (string) ($calloutHtml ?? '');
$calloutVariant = (string) ($calloutVariant ?? 'info');
$productsDisabled = $products === [];

if ($itemRows === []) {
    $itemRows = [[
        'product_id' => '',
        'requested_qty' => '',
        'estimated_unit_cost' => '',
        'notes' => '',
    ]];
}

$productOptions = array_map(static fn (array $product): array => [
    'id' => (int) ($product['id'] ?? 0),
    'name' => (string) ($product['product_name'] ?? ''),
    'unit' => (string) ($product['unit'] ?? 'unit'),
], $products);
?>
<div class="stack-lg purchase-request-form" data-purchase-request-form>
    <?php if ($calloutHtml !== ''): ?>
        <div class="status-callout status-callout-<?= esc($calloutVariant) ?>">
            <p style="margin:0;"><?= $calloutHtml ?></p>
        </div>
    <?php endif ?>

    <form method="post" action="<?= esc($formAction) ?>" class="stack-lg" data-pr-form>
        <?= csrf_field() ?>

        <section class="card stack-md">
            <div class="stack-sm">
                <h2 style="margin:0;"><?= esc($headerTitle) ?></h2>
                <?php if ($headerSubtitle !== ''): ?>
                    <p class="muted" style="margin:0;"><?= esc($headerSubtitle) ?></p>
                <?php endif ?>
            </div>
            <div class="purchase-request-grid">
                <div>
                    <label class="purchase-request-field-label" for="request_date">Request Date</label>
                    <input
                        id="request_date"
                        class="purchase-request-header-control"
                        type="date"
                        name="request_date"
                        value="<?= esc($requestDateValue) ?>"
                        <?= $requestDateMax !== null && $requestDateMax !== '' ? 'max="' . esc((string) $requestDateMax) . '"' : '' ?>
                        required
                    >
                </div>
                <div>
                    <label class="purchase-request-field-label" for="needed_date">Needed Date</label>
                    <input
                        id="needed_date"
                        class="purchase-request-header-control"
                        type="date"
                        name="needed_date"
                        value="<?= esc($neededDateValue) ?>"
                    >
                </div>
                <div>
                    <label class="purchase-request-field-label" for="remarks">Remarks</label>
                    <input
                        id="remarks"
                        class="purchase-request-header-control"
                        type="text"
                        name="remarks"
                        value="<?= esc($remarksValue) ?>"
                        placeholder="Optional notes for the request"
                    >
                </div>
            </div>
        </section>

        <section class="card stack-md">
            <div class="purchase-request-toolbar-line">
                <div class="stack-sm">
                    <h2 style="margin:0;"><?= esc($itemsTitle) ?></h2>
                    <?php if ($itemsSubtitle !== ''): ?>
                        <p class="muted" style="margin:0;"><?= $itemsSubtitle ?></p>
                    <?php endif ?>
                </div>
                <div class="toolbar" style="margin:0;">
                    <?php if ($allowCsvImport): ?>
                        <input type="file" accept=".csv" hidden data-pr-csv-file>
                        <button type="button" class="btn btn-outline" data-pr-csv-trigger <?= $productsDisabled ? 'disabled' : '' ?>>Import CSV</button>
                    <?php endif ?>
                    <button type="button" class="btn btn-outline" data-pr-add-row <?= $productsDisabled ? 'disabled' : '' ?>>Add Row</button>
                </div>
            </div>

            <div class="purchase-request-table-wrap">
                <table class="purchase-request-items-table" data-pr-items-table>
                    <thead>
                        <tr>
                            <th style="width: 34%;">Product</th>
                            <th style="width: 14%;">Unit</th>
                            <th style="width: 12%;">Qty</th>
                            <th style="width: 16%;">Est. Unit Cost</th>
                            <th style="width: 24%;">Notes</th>
                        </tr>
                    </thead>
                    <tbody data-pr-items-body>
                        <?php foreach ($itemRows as $row): ?>
                            <?= view('procurement/purchase_requests/_form_row', [
                                'products' => $products,
                                'selectedProductId' => (string) ($row['product_id'] ?? ''),
                                'requestedQty' => (string) ($row['requested_qty'] ?? ''),
                                'estimatedUnitCost' => (string) ($row['estimated_unit_cost'] ?? ''),
                                'notes' => (string) ($row['notes'] ?? ''),
                                'productsDisabled' => $productsDisabled,
                            ]) ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <div class="toolbar purchase-request-actions">
                <button type="submit" class="btn btn-primary" <?= $productsDisabled ? 'disabled' : '' ?>><?= esc($submitLabel) ?></button>
                <a class="btn btn-outline" href="<?= esc($cancelUrl) ?>">Cancel</a>
            </div>
        </section>
    </form>

    <template data-pr-item-row-template>
        <?= view('procurement/purchase_requests/_form_row', [
            'products' => $products,
            'selectedProductId' => '',
            'requestedQty' => '',
            'estimatedUnitCost' => '',
            'notes' => '',
            'productsDisabled' => $productsDisabled,
        ]) ?>
    </template>

    <script type="application/json" data-pr-form-config><?= json_encode([
        'catalogProducts' => $productOptions,
        'allowCsvImport' => $allowCsvImport,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</div>
