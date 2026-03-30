<?php

declare(strict_types=1);

$title = 'Create Purchase Request - InventoryV2';
$pageTitle = 'Create Purchase Request';
$pageSubtitle = 'Build a draft request using the product catalog.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Create'],
];

$products = $products ?? [];
$oldProductIds = (array) old('product_id', []);
$oldRequestedQty = (array) old('requested_qty', []);
$oldEstimatedUnitCosts = (array) old('estimated_unit_cost', []);
$oldNotes = (array) old('notes', []);
$rowCount = max(3, count($oldProductIds), count($oldRequestedQty), count($oldEstimatedUnitCosts), count($oldNotes));
$itemRows = [];

for ($i = 0; $i < $rowCount; $i++) {
    $itemRows[] = [
        'product_id' => (string) old('product_id.' . $i),
        'requested_qty' => (string) old('requested_qty.' . $i),
        'estimated_unit_cost' => (string) old('estimated_unit_cost.' . $i),
        'notes' => (string) old('notes.' . $i),
    ];
}
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/purchase-request-form.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Back to List</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= view('procurement/purchase_requests/_form', [
    'products' => $products,
    'itemRows' => $itemRows,
    'formAction' => site_url('procurement/purchase-requests'),
    'submitLabel' => 'Save Draft Purchase Request',
    'cancelUrl' => site_url('procurement/purchase-requests'),
    'headerTitle' => 'Request Header',
    'headerSubtitle' => 'Set the request timing and optional context.',
    'itemsTitle' => 'Requested Items',
    'itemsSubtitle' => 'CSV format: <em>Product, Qty, Cost, Notes</em> or legacy <em>Product, Unit, Qty, Cost, Notes</em>.',
    'requestDateValue' => (string) old('request_date', date('Y-m-d')),
    'requestDateMax' => date('Y-m-d'),
    'neededDateValue' => (string) old('needed_date'),
    'remarksValue' => (string) old('remarks'),
    'allowCsvImport' => true,
    'calloutVariant' => $products === [] ? 'warning' : 'info',
    'calloutHtml' => $products === []
        ? '<strong>No products available.</strong> Create product records in the admin catalog before starting a purchase request.'
        : '<strong>Catalog rule:</strong> line items now resolve from the product master. The unit is pulled from the selected product automatically.',
]) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/purchase-request-form.js') ?>"></script>
<?= $this->endSection() ?>
