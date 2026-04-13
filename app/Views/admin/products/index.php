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
$searchTerm = trim((string) ($searchTerm ?? ''));
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- V2 DESIGN SYSTEM VARIABLES --- */
    :root {
        --v2-border: #b2e0eb; 
        --v2-title: #00476b;  
        --v2-label: #00668c;  
        --v2-active-bg: #00638a; 
        --v2-text-main: #1e3a8a; 
        --v2-text-muted: #64748b;
    }

    /* --- VIEWPORT WRAPPER --- */
    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        height: calc(100vh - 120px); 
        min-height: 800px;
        overflow-y: auto;
        padding-bottom: 40px;
    }

    /* --- V2 CARDS --- */
    .data-card {
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
        overflow: hidden;
        flex-shrink: 0;
    }

    .card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--v2-border);
        background: #ffffff;
    }

    .card-header h3 { margin: 0 0 4px 0; font-size: 1.1rem; color: var(--v2-title); font-weight: 800; }
    .card-header p { margin: 0; font-size: 0.8rem; color: var(--v2-text-muted); }

    /* --- V2 FORM INPUTS --- */
    .field-label { display: block; font-weight: 800; font-size: 0.7rem; color: var(--v2-text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
    .input-v2 { width: 100%; height: 36px; padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.85rem; color: var(--v2-text-main); background: #ffffff; transition: all 0.2s; box-sizing: border-box; }
    .input-v2:focus { border-color: var(--v2-label); outline: none; box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }
    .input-v2[readonly] { background: #f8fafc; color: var(--v2-text-muted); font-family: var(--font-mono); font-weight: 600; cursor: not-allowed; }

    /* --- ADD PRODUCT GRID --- */
    .add-form-grid {
        display: grid;
        grid-template-columns: 140px 2fr 150px 140px auto;
        gap: 16px;
        padding: 20px;
        align-items: flex-end;
    }

    /* --- CUSTOM GRID-TABLE (Allows forms inside rows) --- */
    .grid-table-container {
        width: 100%;
        overflow-x: auto;
    }
    
    .grid-table {
        min-width: 900px;
        display: flex;
        flex-direction: column;
    }

    .grid-header {
        display: grid;
        grid-template-columns: 120px 2fr 150px 140px 100px;
        gap: 16px;
        padding: 12px 20px;
        background: #ffffff;
        border-bottom: 2px solid var(--v2-border);
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--v2-title);
        text-transform: uppercase;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .grid-row {
        display: grid;
        grid-template-columns: 120px 2fr 150px 140px 100px;
        gap: 16px;
        padding: 10px 20px;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
        transition: background 0.15s ease;
    }
    .grid-row:hover { background: #f8fafc; }

    /* Inline row inputs */
    .grid-row .input-v2 { height: 32px; font-size: 0.8rem; border-color: transparent; background: transparent; padding: 4px 8px; }
    .grid-row .input-v2:hover { border-color: #cbd5e1; background: #ffffff; }
    .grid-row .input-v2:focus { border-color: var(--v2-label); background: #ffffff; }
    
    .catalog-code { font-family: var(--font-mono); font-weight: 800; color: #94a3b8; font-size: 0.85rem; padding-left: 8px; }

    /* --- TOOLBAR --- */
    .catalog-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 20px;
        background: #ffffff;
        border-bottom: 1px solid var(--v2-border);
        flex-wrap: wrap;
        gap: 16px;
    }
    .search-wrap { position: relative; width: 300px; }
    .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 14px; height: 14px; }
    .search-input { width: 100%; height: 34px; padding: 6px 12px 6px 30px; border: 1px solid var(--v2-border); border-radius: 6px; font-size: 0.85rem; color: var(--v2-text-main); outline: none; transition: border-color 0.2s; }
    .search-input:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }

    .btn-update { padding: 6px 12px; font-size: 0.75rem; font-weight: 800; border-radius: 6px; text-transform: uppercase; border: 1px solid var(--v2-border); background: #ffffff; color: var(--v2-label); cursor: pointer; transition: all 0.2s; width: 100%; }
    .btn-update:hover { background: rgba(178, 224, 235, 0.3); color: var(--v2-title); border-color: var(--v2-label); }

    @media (max-width: 980px) {
        .add-form-grid { grid-template-columns: 1fr; gap: 12px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('admin/dashboard') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Dashboard</a>
<a class="btn btn-outline" href="<?= site_url('admin/suppliers') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Suppliers</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0; display: flex; justify-content: space-between; align-items: flex-end;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Product Catalog</h2>
    </div>

    <section class="data-card">
        <div class="card-header">
            <h3>Add New Product</h3>
            <p>New request and issuance drafts will resolve products from this master catalog.</p>
        </div>
        <form method="post" action="<?= site_url('admin/products') ?>" class="add-form-grid">
            <?= csrf_field() ?>
            <input type="hidden" name="catalog_search" value="<?= esc($searchTerm) ?>">
            
            <div>
                <label class="field-label" for="product_code_preview">Code</label>
                <input id="product_code_preview" class="input-v2" type="text" value="Auto-generated" readonly>
            </div>
            <div>
                <label class="field-label" for="product_name">Product Name</label>
                <input id="product_name" class="input-v2" type="text" name="product_name" value="<?= esc((string) old('product_name')) ?>" placeholder="e.g. Paracetamol 500mg" required>
            </div>
            <div>
                <label class="field-label" for="unit">Base Unit</label>
                <input id="unit" class="input-v2" type="text" name="unit" value="<?= esc((string) old('unit', 'unit')) ?>" required>
            </div>
            <div>
                <label class="field-label" for="is_active">Status</label>
                <select id="is_active" class="input-v2" name="is_active">
                    <option value="1" <?= old('is_active', '1') === '1' ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= old('is_active') === '0' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="height: 36px; width: 100%; border: none; background: var(--v2-label); font-weight: 800; color: white;">Create Item</button>
            </div>
        </form>
    </section>

    <section class="data-card" style="flex: 1; display: flex; flex-direction: column;">
        <div class="catalog-toolbar">
            <div>
                <h3 style="margin: 0 0 4px 0; font-size: 1.05rem; color: var(--v2-title); font-weight: 800;">Catalog Records</h3>
                <p id="catalog-product-count" style="margin: 0; font-size: 0.8rem; color: var(--v2-text-muted); font-weight: 600;">
                    <?php if ($searchTerm !== ''): ?>
                        <?= esc((string) count($products)) ?> matching products for "<?= esc($searchTerm) ?>".
                    <?php else: ?>
                        <?= esc((string) count($products)) ?> items in master list.
                    <?php endif ?>
                </p>
            </div>
            
            <div style="display: flex; gap: 8px; align-items: center;">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input class="search-input" id="catalog-product-search" type="text" value="<?= esc($searchTerm) ?>" placeholder="Search code, name, or unit..." autocomplete="off">
                </div>
                <button type="button" class="btn btn-outline" id="btn-clear-product-search" style="height: 34px; padding: 0 12px; border-radius: 6px; font-weight: 800; font-size: 0.8rem;">Clear</button>
            </div>
        </div>

        <div class="grid-table-container">
            <div class="grid-table">
                <div class="grid-header">
                    <div>Code</div>
                    <div>Product Name</div>
                    <div>Base Unit</div>
                    <div>Status</div>
                    <div style="text-align: center;">Action</div>
                </div>

                <div>
                    <?php if ($products === []): ?>
                        <div style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                            <strong>No products found.</strong>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <form method="post" action="<?= site_url('admin/products/' . (int) ($product['id'] ?? 0)) ?>" class="grid-row catalog-record-row" data-search="<?= esc(strtolower(trim((string) ($product['product_code'] ?? '') . ' ' . (string) ($product['product_name'] ?? '') . ' ' . (string) ($product['unit'] ?? '')))) ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="catalog_search" value="<?= esc($searchTerm) ?>">
                                
                                <div class="catalog-code"><?= esc((string) ($product['product_code'] ?? '')) ?></div>
                                
                                <div>
                                    <input class="input-v2" type="text" name="product_name" value="<?= esc((string) ($product['product_name'] ?? '')) ?>" required style="font-weight: 700; color: var(--v2-label);">
                                </div>
                                
                                <div>
                                    <input class="input-v2" type="text" name="unit" value="<?= esc((string) ($product['unit'] ?? 'unit')) ?>" required style="color: var(--v2-text-muted);">
                                </div>
                                
                                <div>
                                    <select class="input-v2" name="is_active" style="font-weight: 600; color: var(--v2-text-main);">
                                        <option value="1" <?= (int) ($product['is_active'] ?? 0) === 1 ? 'selected' : '' ?>>Active</option>
                                        <option value="0" <?= (int) ($product['is_active'] ?? 0) !== 1 ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <button type="submit" class="btn-update">Save</button>
                                </div>
                            </form>
                        <?php endforeach ?>
                    <?php endif ?>
                    
                    <div id="catalog-product-empty" style="display: none; text-align: center; padding: 40px; color: var(--v2-text-muted);">
                        <strong>No products matched your search.</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('catalog-product-search');
        const clearButton = document.getElementById('btn-clear-product-search');
        const countLabel = document.getElementById('catalog-product-count');
        const emptyState = document.getElementById('catalog-product-empty');
        const rows = Array.from(document.querySelectorAll('.catalog-record-row'));
        const baseUrl = "<?= site_url('admin/products') ?>";
        const totalRows = rows.length;

        function updateCount(query, visibleCount) {
            if (!countLabel) return;
            countLabel.innerHTML = query === ''
                ? `Showing <strong>${visibleCount}</strong> items in master list.`
                : `Showing <strong>${visibleCount}</strong> matching products for "<strong>${query}</strong>".`;
        }

        function updateEmptyState(query, visibleCount) {
            if (!emptyState) return;
            if (visibleCount > 0) {
                emptyState.style.display = 'none';
            } else {
                emptyState.style.display = 'block';
                emptyState.innerHTML = query === '' ? 'No products found.' : `No products matched "<strong>${query}</strong>".`;
            }
        }

        function applySearch() {
            const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
            let visibleCount = 0;

            rows.forEach(function (row) {
                const haystack = (row.getAttribute('data-search') || '').toLowerCase();
                const matches = query === '' || haystack.includes(query);
                row.style.display = matches ? 'grid' : 'none'; // Must use 'grid' to maintain CSS Grid layout
                if (matches) visibleCount++;
            });

            updateCount(query, visibleCount);
            updateEmptyState(query, visibleCount);
        }

        if (searchInput) {
            searchInput.addEventListener('input', applySearch);
        }

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                if (window.location.search.indexOf('q=') !== -1 || window.location.search.indexOf('catalog_search=') !== -1) {
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

        applySearch();
    });
</script>
<?= $this->endSection() ?>