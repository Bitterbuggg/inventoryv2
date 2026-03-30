<?php

declare(strict_types=1);

$title = 'Edit Purchase Request - InventoryV2';
$pageTitle = 'Edit Purchase Request #' . (string) ($purchaseRequest['id'] ?? '');
$pageSubtitle = 'Update the draft using catalog-backed product lines.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Edit Request'],
];

$products = $products ?? [];
$existingItems = (array) ($purchaseRequest['items'] ?? []);
$oldProductIds = (array) old('product_id', []);
$oldRequestedQty = (array) old('requested_qty', []);
$oldEstimatedUnitCosts = (array) old('estimated_unit_cost', []);
$oldNotes = (array) old('notes', []);
$rowCount = max(3, count($existingItems), count($oldProductIds), count($oldRequestedQty), count($oldEstimatedUnitCosts), count($oldNotes));
$itemRows = [];

for ($i = 0; $i < $rowCount; $i++) {
    $item = $existingItems[$i] ?? [];
    $itemRows[] = [
        'product_id' => (string) old('product_id.' . $i, (string) ($item['product_id'] ?? '')),
        'requested_qty' => (string) old('requested_qty.' . $i, (string) ($item['requested_qty'] ?? '')),
        'estimated_unit_cost' => (string) old('estimated_unit_cost.' . $i, (string) ($item['estimated_unit_cost'] ?? '')),
        'notes' => (string) old('notes.' . $i, (string) ($item['notes'] ?? '')),
    ];
}
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/purchase-request-form.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Back to List</a>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests/' . (int) ($purchaseRequest['id'] ?? 0) . '/items.csv') ?>">Export Items CSV</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= view('procurement/purchase_requests/_form', [
    'products' => $products,
    'itemRows' => $itemRows,
    'formAction' => site_url('procurement/purchase-requests/' . (int) ($purchaseRequest['id'] ?? 0) . '/update'),
    'submitLabel' => 'Update Purchase Request',
    'cancelUrl' => site_url('procurement/purchase-requests'),
    'headerTitle' => 'Request Header',
    'headerSubtitle' => 'Update the request timing and keep lines aligned with the active product catalog.',
    'itemsTitle' => 'Requested Items',
    'itemsSubtitle' => 'Units stay synchronized with the selected catalog product.',
    'requestDateValue' => (string) old('request_date', (string) ($purchaseRequest['request_date'] ?? '')),
    'neededDateValue' => (string) old('needed_date', (string) ($purchaseRequest['needed_date'] ?? '')),
    'remarksValue' => (string) old('remarks', (string) ($purchaseRequest['remarks'] ?? '')),
    'allowCsvImport' => false,
    'calloutVariant' => 'info',
    'calloutHtml' => '<strong>Edit rule:</strong> draft lines stay attached to the product catalog. If a product was retired, reactivate or replace it before saving.',
]) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/purchase-request-form.js') ?>"></script>
<?= $this->endSection() ?>
