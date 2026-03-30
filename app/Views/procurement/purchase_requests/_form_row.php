<?php

declare(strict_types=1);

$products = $products ?? [];
$selectedProductId = (string) ($selectedProductId ?? '');
$requestedQty = (string) ($requestedQty ?? '');
$estimatedUnitCost = (string) ($estimatedUnitCost ?? '');
$notes = (string) ($notes ?? '');
$productsDisabled = (bool) ($productsDisabled ?? false);

$selectedUnit = '';
foreach ($products as $product) {
    if ((string) ($product['id'] ?? '') !== $selectedProductId) {
        continue;
    }

    $selectedUnit = (string) ($product['unit'] ?? 'unit');
    break;
}
?>
<tr>
    <td>
        <select
            name="product_id[]"
            class="table-control product-select"
            data-pr-product-select
            <?= $productsDisabled ? 'disabled' : '' ?>
        >
            <option value="">Select product...</option>
            <?php foreach ($products as $product): ?>
                <?php $productId = (string) ($product['id'] ?? ''); ?>
                <option
                    value="<?= esc($productId) ?>"
                    data-unit="<?= esc((string) ($product['unit'] ?? 'unit')) ?>"
                    <?= $selectedProductId !== '' && $selectedProductId === $productId ? 'selected' : '' ?>
                >
                    <?= esc((string) ($product['product_name'] ?? '')) ?> (<?= esc((string) ($product['unit'] ?? 'unit')) ?>)
                </option>
            <?php endforeach ?>
        </select>
    </td>
    <td>
        <input
            type="text"
            class="table-control unit-display"
            value="<?= esc($selectedUnit) ?>"
            data-pr-unit-display
            readonly
        >
    </td>
    <td>
        <input
            type="number"
            step="1"
            min="1"
            name="requested_qty[]"
            class="table-control"
            value="<?= esc($requestedQty) ?>"
        >
    </td>
    <td>
        <input
            type="number"
            step="0.01"
            min="0"
            name="estimated_unit_cost[]"
            class="table-control"
            value="<?= esc($estimatedUnitCost) ?>"
        >
    </td>
    <td>
        <div class="notes-cell">
            <input
                type="text"
                name="notes[]"
                class="table-control"
                value="<?= esc($notes) ?>"
                placeholder="Optional notes"
            >
            <button type="button" class="btn-remove-row" data-pr-remove-row aria-label="Remove row">&times;</button>
        </div>
    </td>
</tr>
