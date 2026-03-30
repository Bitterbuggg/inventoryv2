<?php

declare(strict_types=1);

$title = 'Supplier Catalog - InventoryV2';
$pageTitle = 'Supplier Catalog';
$pageSubtitle = 'Maintain the supplier master used when converting approved requests into purchase orders.';
$crumbs = [
    ['label' => 'Admin Dashboard', 'url' => site_url('admin/dashboard')],
    ['label' => 'Supplier Catalog'],
];
$suppliers = $suppliers ?? [];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    .catalog-grid { display: grid; gap: 12px; }
    .catalog-row { display: grid; gap: 12px; grid-template-columns: 140px 1.5fr 1fr 1fr 1.2fr 140px auto; align-items: end; }
    .field-label { display: block; font-weight: 700; font-size: 0.85rem; color: #1e293b; margin-bottom: 6px; }
    .form-control-header { width: 100%; height: 40px; padding: 8px 12px; border: 1px solid var(--color-border-strong); border-radius: 8px; font: inherit; box-sizing: border-box; }
    .form-control-header:focus { border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14,165,233,.15); }
    .catalog-code { font-family: var(--font-mono); font-weight: 700; color: var(--color-text-muted); }
    @media (max-width: 1180px) { .catalog-row { grid-template-columns: 1fr; } }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('admin/dashboard') ?>">Back to Dashboard</a>
<a class="btn btn-outline" href="<?= site_url('admin/products') ?>">Products</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="stack-sm">
            <h2 style="margin:0;">Add Supplier</h2>
            <p class="muted" style="margin:0;">Approved purchase requests now convert to purchase orders using this supplier catalog.</p>
        </div>
        <form method="post" action="<?= site_url('admin/suppliers') ?>" class="catalog-row">
            <?= csrf_field() ?>
            <div>
                <label class="field-label">Code</label>
                <input class="form-control-header" type="text" value="Auto-generated" readonly>
            </div>
            <div>
                <label class="field-label" for="supplier_name">Supplier Name</label>
                <input class="form-control-header" id="supplier_name" type="text" name="supplier_name" value="<?= esc((string) old('supplier_name')) ?>" required>
            </div>
            <div>
                <label class="field-label" for="contact_person">Contact Person</label>
                <input class="form-control-header" id="contact_person" type="text" name="contact_person" value="<?= esc((string) old('contact_person')) ?>">
            </div>
            <div>
                <label class="field-label" for="phone">Phone</label>
                <input class="form-control-header" id="phone" type="text" name="phone" value="<?= esc((string) old('phone')) ?>">
            </div>
            <div>
                <label class="field-label" for="email">Email</label>
                <input class="form-control-header" id="email" type="email" name="email" value="<?= esc((string) old('email')) ?>">
            </div>
            <div>
                <label class="field-label" for="supplier_status">Status</label>
                <select class="form-control-header" id="supplier_status" name="is_active">
                    <option value="1" <?= old('is_active', '1') === '1' ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= old('is_active') === '0' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Supplier</button>
        </form>
    </section>

    <section class="card stack-md">
        <div class="stack-sm">
            <h2 style="margin:0;">Catalog Records</h2>
            <p class="muted" style="margin:0;"><?= esc((string) count($suppliers)) ?> suppliers currently available.</p>
        </div>
        <div class="catalog-grid">
            <?php foreach ($suppliers as $supplier): ?>
                <form method="post" action="<?= site_url('admin/suppliers/' . (int) ($supplier['id'] ?? 0)) ?>" class="catalog-row">
                    <?= csrf_field() ?>
                    <div>
                        <label class="field-label">Code</label>
                        <div class="catalog-code"><?= esc((string) ($supplier['supplier_code'] ?? '')) ?></div>
                    </div>
                    <div>
                        <label class="field-label">Supplier Name</label>
                        <input class="form-control-header" type="text" name="supplier_name" value="<?= esc((string) ($supplier['supplier_name'] ?? '')) ?>" required>
                    </div>
                    <div>
                        <label class="field-label">Contact Person</label>
                        <input class="form-control-header" type="text" name="contact_person" value="<?= esc((string) ($supplier['contact_person'] ?? '')) ?>">
                    </div>
                    <div>
                        <label class="field-label">Phone</label>
                        <input class="form-control-header" type="text" name="phone" value="<?= esc((string) ($supplier['phone'] ?? '')) ?>">
                    </div>
                    <div>
                        <label class="field-label">Email</label>
                        <input class="form-control-header" type="email" name="email" value="<?= esc((string) ($supplier['email'] ?? '')) ?>">
                    </div>
                    <div>
                        <label class="field-label">Status</label>
                        <select class="form-control-header" name="is_active">
                            <option value="1" <?= (int) ($supplier['is_active'] ?? 0) === 1 ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= (int) ($supplier['is_active'] ?? 0) !== 1 ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline">Update</button>
                </form>
            <?php endforeach ?>
            <?php if ($suppliers === []): ?>
                <p class="muted" style="margin:0;">No suppliers found.</p>
            <?php endif ?>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
