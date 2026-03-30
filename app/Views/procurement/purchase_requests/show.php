<?php

declare(strict_types=1);

$title = 'Purchase Request Details - InventoryV2';
$pageTitle = 'Purchase Request #' . (string) ($purchaseRequest['pr_number'] ?? '');
$pageSubtitle = 'Review full purchase request content and line items.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Details'],
];

$user = function_exists('auth') ? auth()->user() : null;
$canCreatePr = $user !== null && method_exists($user, 'can') && $user->can('procurement.pr.create');
$canApprovePr = $user !== null && method_exists($user, 'can') && $user->can('procurement.pr.approve');
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests') ?>">Back to Requests</a>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-requests/' . (int) ($purchaseRequest['id'] ?? 0) . '/items.csv') ?>">Export Items CSV</a>
<?php if (($purchaseRequest['status'] ?? '') === 'draft' && $canCreatePr): ?>
<a class="btn btn-primary" href="<?= site_url('procurement/purchase-requests/' . (int) ($purchaseRequest['id'] ?? 0) . '/edit') ?>">Edit Draft</a>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$items = $purchaseRequest['items'] ?? [];
$totalRequested = array_sum(array_map(static fn (array $row): float => (float) ($row['requested_qty'] ?? 0), $items));
$totalEstimated = array_sum(array_map(static fn (array $row): float => (float) ($row['estimated_unit_cost'] ?? 0) * (float) ($row['requested_qty'] ?? 0), $items));
?>
<div class="stack-lg">
    <?= view('procurement/purchase_requests/_show_overview', [
        'purchaseRequest' => $purchaseRequest,
        'items' => $items,
        'totalRequested' => $totalRequested,
        'totalEstimated' => $totalEstimated,
        'canApprovePr' => $canApprovePr,
    ]) ?>

    <?= view('procurement/purchase_requests/_show_items_table', [
        'items' => $items,
    ]) ?>
</div>
<?= $this->endSection() ?>
