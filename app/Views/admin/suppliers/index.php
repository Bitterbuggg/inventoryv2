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
$searchTerm = trim((string) ($searchTerm ?? ''));
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    .catalog-grid { display: grid; gap: 12px; }
    .catalog-row { display: grid; gap: 12px; grid-template-columns: 140px 1.5fr 1fr 1fr 1.2fr 140px auto; align-items: end; }
    .catalog-toolbar { display: flex; gap: 16px; justify-content: space-between; align-items: start; flex-wrap: wrap; }
    .toolbar-controls { display: flex; gap: 12px; align-items: end; flex-wrap: wrap; width: min(100%, 520px); }
    .search-field { flex: 1 1 280px; }
    .search-wrap { position: relative; }
    .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 16px; height: 16px; pointer-events: none; }
    .search-input { width: 100%; height: 40px; padding: 8px 12px 8px 38px; border: 1px solid var(--color-border-strong); border-radius: 8px; font: inherit; box-sizing: border-box; }
    .search-input:focus { border-color: var(--color-brand-500); outline: none; box-shadow: 0 0 0 3px rgba(14,165,233,.15); }
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
            <input type="hidden" name="catalog_search" value="<?= esc($searchTerm) ?>">
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
        <div class="catalog-toolbar">
            <div class="stack-sm">
                <h2 style="margin:0;">Catalog Records</h2>
                <p class="muted" id="catalog-supplier-count" style="margin:0;">
                    <?php if ($searchTerm !== ''): ?>
                        <?= esc((string) count($suppliers)) ?> matching suppliers for "<?= esc($searchTerm) ?>".
                    <?php else: ?>
                        <?= esc((string) count($suppliers)) ?> suppliers currently available.
                    <?php endif ?>
                </p>
            </div>
            <div class="toolbar-controls">
                <div class="search-field">
                    <label class="field-label" for="catalog-supplier-search">Search Records</label>
                    <div class="search-wrap">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input
                            class="search-input"
                            id="catalog-supplier-search"
                            type="text"
                            value="<?= esc($searchTerm) ?>"
                            placeholder="Search code, supplier, contact, phone, or email"
                            autocomplete="off"
                        >
                    </div>
                </div>
                <button type="button" class="btn btn-outline" id="btn-clear-supplier-search">Clear</button>
            </div>
        </div>
        <div class="catalog-grid">
            <?php foreach ($suppliers as $supplier): ?>
                <form
                    method="post"
                    action="<?= site_url('admin/suppliers/' . (int) ($supplier['id'] ?? 0)) ?>"
                    class="catalog-row catalog-record-row"
                    data-search="<?= esc(strtolower(trim(
                        (string) ($supplier['supplier_code'] ?? '') . ' ' .
                        (string) ($supplier['supplier_name'] ?? '') . ' ' .
                        (string) ($supplier['contact_person'] ?? '') . ' ' .
                        (string) ($supplier['phone'] ?? '') . ' ' .
                        (string) ($supplier['email'] ?? '')
                    ))) ?>"
                >
                    <?= csrf_field() ?>
                    <input type="hidden" name="catalog_search" value="<?= esc($searchTerm) ?>">
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
            <p class="muted" id="catalog-supplier-empty" style="margin:0;<?= $suppliers === [] ? '' : ' display:none;' ?>">
                <?php if ($suppliers === []): ?>
                    <?php if ($searchTerm !== ''): ?>
                        No suppliers matched "<?= esc($searchTerm) ?>". <a href="<?= site_url('admin/suppliers') ?>">Clear search</a>.
                    <?php else: ?>
                        No suppliers found.
                    <?php endif ?>
                <?php endif ?>
            </p>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function () {
        const searchInput = document.getElementById('catalog-supplier-search');
        const clearButton = document.getElementById('btn-clear-supplier-search');
        const countLabel = document.getElementById('catalog-supplier-count');
        const emptyState = document.getElementById('catalog-supplier-empty');
        const rows = Array.from(document.querySelectorAll('.catalog-record-row'));
        const baseUrl = "<?= site_url('admin/suppliers') ?>";
        const totalRows = rows.length;

        function updateCount(rawQuery, visibleCount) {
            if (!countLabel) {
                return;
            }

            const query = rawQuery.trim();
            countLabel.textContent = query === ''
                ? visibleCount + ' suppliers currently available.'
                : visibleCount + ' matching suppliers for "' + query + '".';
        }

        function updateEmptyState(rawQuery, visibleCount) {
            if (!emptyState) {
                return;
            }

            const query = rawQuery.trim();

            if (visibleCount > 0) {
                emptyState.style.display = 'none';
                emptyState.textContent = '';
                return;
            }

            emptyState.style.display = '';
            emptyState.textContent = query === ''
                ? 'No suppliers found.'
                : 'No suppliers matched "' + query + '".';
        }

        function applySearch() {
            const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
            let visibleCount = 0;

            rows.forEach(function (row) {
                const haystack = (row.getAttribute('data-search') || '').toLowerCase();
                const matches = query === '' || haystack.includes(query);

                row.style.display = matches ? '' : 'none';

                if (matches) {
                    visibleCount++;
                }
            });

            updateCount(searchInput ? searchInput.value : '', visibleCount);
            updateEmptyState(searchInput ? searchInput.value : '', visibleCount);
        }

        if (searchInput) {
            searchInput.addEventListener('input', applySearch);
        }

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                if (window.location.search.indexOf('q=') !== -1) {
                    window.location.href = baseUrl;
                    return;
                }

                if (searchInput) {
                    searchInput.value = '';
                    searchInput.focus();
                }

                applySearch();
            });
        }

        updateCount(searchInput ? searchInput.value : '', totalRows);
        applySearch();
    })();
</script>
<?= $this->endSection() ?>
