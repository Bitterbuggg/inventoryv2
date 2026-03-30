<?php

declare(strict_types=1);

$title = 'Product Catalog - InventoryV2';
$pageTitle = 'Product Catalog';
$pageSubtitle = 'Maintain the master list used by procurement, receiving, inventory, and issuance.';
$crumbs = [
    ['label' => 'Admin Dashboard', 'url' => site_url('admin/dashboard')],
    ['label' => 'Product Catalog'],
];
$products = $products ?? [];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    .catalog-grid { display: grid; gap: 12px; }
    .catalog-row { display: grid; gap: 12px; grid-template-columns: 160px 1.8fr 140px 140px auto; align-items: end; }
    .field-label { display: block; font-weight: 700; font-size: 0.85rem; color: #1e293b; margin-bottom: 6px; }
    .form-control-header { width: 100%; height: 40px; padding: 8px 12px; border: 1px solid var(--color-border-strong); border-radius: 8px; font: inherit; box-sizing: border-box; }
    .form-control-header:focus { border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14,165,233,.15); }
    .catalog-code { font-family: var(--font-mono); font-weight: 700; color: var(--color-text-muted); }
    @media (max-width: 980px) { .catalog-row { grid-template-columns: 1fr; } }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('admin/dashboard') ?>">Back to Dashboard</a>
<a class="btn btn-outline" href="<?= site_url('admin/suppliers') ?>">Suppliers</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="stack-sm">
            <h2 style="margin:0;">Add Product</h2>
            <p class="muted" style="margin:0;">New request and issuance drafts now resolve products from this catalog.</p>
        </div>
        <form method="post" action="<?= site_url('admin/products') ?>" class="catalog-row">
            <?= csrf_field() ?>
            <div>
                <label class="field-label" for="product_code_preview">Code</label>
                <input id="product_code_preview" class="form-control-header" type="text" value="Auto-generated" readonly>
            </div>
            <div>
                <label class="field-label" for="product_name">Product Name</label>
                <input class="form-control-header" id="product_name" type="text" name="product_name" value="<?= esc((string) old('product_name')) ?>" required>
            </div>
            <div>
                <label class="field-label" for="unit">Base Unit</label>
                <input class="form-control-header" id="unit" type="text" name="unit" value="<?= esc((string) old('unit', 'unit')) ?>" required>
            </div>
            <div>
                <label class="field-label" for="is_active">Status</label>
                <select class="form-control-header" id="is_active" name="is_active">
                    <option value="1" <?= old('is_active', '1') === '1' ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= old('is_active') === '0' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Product</button>
        </form>
    </section>

    <section class="card stack-md">
        <div class="stack-sm">
            <h2 style="margin:0;">Catalog Records</h2>
            <p class="muted" style="margin:0;"><?= esc((string) count($products)) ?> products currently available.</p>
        </div>
        <div class="catalog-grid">
            <?php foreach ($products as $product): ?>
                <form method="post" action="<?= site_url('admin/products/' . (int) ($product['id'] ?? 0)) ?>" class="catalog-row">
                    <?= csrf_field() ?>
                    <div>
                        <label class="field-label">Code</label>
                        <div class="catalog-code"><?= esc((string) ($product['product_code'] ?? '')) ?></div>
                    </div>
                    <div>
                        <label class="field-label">Product Name</label>
                        <input class="form-control-header" type="text" name="product_name" value="<?= esc((string) ($product['product_name'] ?? '')) ?>" required>
                    </div>
                    <div>
                        <label class="field-label">Base Unit</label>
                        <input class="form-control-header" type="text" name="unit" value="<?= esc((string) ($product['unit'] ?? 'unit')) ?>" required>
                    </div>
                    <div>
                        <label class="field-label">Status</label>
                        <select class="form-control-header" name="is_active">
                            <option value="1" <?= (int) ($product['is_active'] ?? 0) === 1 ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= (int) ($product['is_active'] ?? 0) !== 1 ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline">Update</button>
                </form>
            <?php endforeach ?>
            <?php if ($products === []): ?>
                <p class="muted" style="margin:0;">No products found.</p>
            <?php endif ?>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
