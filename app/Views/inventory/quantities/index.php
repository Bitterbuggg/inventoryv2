<?php

declare(strict_types=1);

$title = 'Inventory Quantities - InventoryV2';
$pageTitle = 'Inventory Quantities';
$pageSubtitle = 'Search current stock balances, batches, lots, and available quantities.';
$crumbs = [
    ['label' => 'Inventory Quantities'],
];
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
        --v2-text-main: #1e3a8a; /* TRUE Dark Blue */
        --v2-text-muted: #64748b;
    }

    /* --- NO-SCROLL VIEWPORT WRAPPER --- */
    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
        height: calc(100vh - 120px); 
        min-height: 640px;
        overflow: hidden;
    }

    /* --- PASTEL KPI CARDS --- */
    .kpi-grid { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 16px; 
        flex-shrink: 0; 
    }
    
    .kpi-card { 
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        padding: 16px 18px; 
        display: flex; 
        align-items: center; 
        gap: 14px; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
    }

    .kpi-icon-box {
        width: 46px; height: 46px; 
        border-radius: 10px; 
        display: flex; align-items: center; justify-content: center; 
        flex-shrink: 0;
    }

    .icon-total { background: #f1f5f9; color: #475569; }        
    .icon-onhand { background: #e0f2fe; color: #0284c7; } 
    .icon-available { background: #ecfccb; color: #16a34a; }   
    .icon-zero { background: #fef2f2; color: #ef4444; }   

    .kpi-details { display: flex; flex-direction: column; flex: 1; justify-content: center; min-width: 0; }
    
    .kpi-value { font-size: 1.15rem; font-weight: 800; color: var(--v2-title); line-height: 1.2; margin: 0; }
    .kpi-label { font-size: 0.75rem; font-weight: 500; color: var(--v2-text-muted); margin: 0; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.05em; }

    /* --- V2 TABLE CARD --- */
    .table-card {
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        display: flex;
        flex-direction: column;
        flex: 1; 
        min-height: 0; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
        overflow: hidden;
    }

    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 12px 20px; 
        border-bottom: 1px solid var(--v2-border); 
        background: #ffffff; 
        flex-shrink: 0;
        flex-wrap: wrap; /* Allows wrapping on very small screens */
    }
    
    .table-toolbar h3 { margin: 0; font-size: 1.05rem; color: var(--v2-title); font-weight: 800; }
    
    /* --- FIXED INLINE TOOLBAR CONTROLS --- */
    .toolbar-controls { 
        display: flex; 
        gap: 10px; 
        align-items: center; 
        flex: 1; 
        justify-content: flex-end; 
        flex-wrap: nowrap; /* Forces them to stay on one line */
    }
    
    .search-wrap { position: relative; width: 260px; flex-shrink: 0; }
    .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 14px; height: 14px; }
    
    .search-input, .filter-select { 
        padding: 6px 12px; 
        font-size: 0.85rem; 
        border: 1px solid var(--v2-border); 
        border-radius: 6px; 
        outline: none; 
        color: var(--v2-text-main);
        background: #ffffff;
        transition: all 0.2s;
        height: 34px; /* Uniform height */
    }
    .search-input { width: 100%; padding-left: 30px; }
    .filter-select { width: 160px; flex-shrink: 0; cursor: pointer; } /* Fixed width so they don't expand */
    
    .search-input:focus, .filter-select:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }

    /* Scrollable Table Area */
    .table-scroll-container {
        flex: 1;
        overflow-y: auto; 
        background: #ffffff;
    }

    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .modern-table th { 
        position: sticky; top: 0; z-index: 10;
        background: #ffffff !important; 
        padding: 14px 16px; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        font-weight: 800; 
        color: var(--v2-title); 
        border-bottom: 2px solid var(--v2-border); 
        text-align: left; 
        letter-spacing: 0.05em; 
        vertical-align: middle; 
    }
    .modern-table td { padding: 12px 16px; font-size: 0.85rem; color: var(--v2-text-main); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .modern-table tr:hover td { background: #f8fafc; }

    /* --- SORTABLE HEADERS --- */
    th.sortable { cursor: pointer; padding-right: 18px !important; user-select: none; transition: background 0.2s ease, color 0.2s ease; }
    th.sortable:hover { background-color: #f1f5f9 !important; color: var(--v2-title) !important; }
    th.sortable::after { content: '↕'; position: absolute; right: 6px; top: 50%; transform: translateY(-50%); font-size: 0.75rem; opacity: 0.3; color: var(--v2-title); }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--v2-label); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--v2-label); font-weight: bold; }

    /* --- PAGINATION FOOTER --- */
    .table-footer {
        padding: 10px 20px;
        border-top: 1px solid var(--v2-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
        flex-shrink: 0;
    }
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li a, .ci-pager li span {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; font-size: 0.75rem; min-width: 28px;
        border: 1px solid var(--v2-border); border-radius: 4px;
        background: #ffffff; color: var(--v2-label);
        text-decoration: none; font-weight: 700; transition: all 0.2s ease;
    }
    .ci-pager li a:hover { background: rgba(178, 224, 235, 0.3); border-color: var(--v2-label); }
    .ci-pager li.active a { background: var(--v2-label); color: #ffffff; border-color: var(--v2-label); }
    .ci-pager li.disabled a { opacity: 0.5; background: #f1f5f9; color: var(--v2-text-muted); pointer-events: none; border-color: #cbd5e1; }
    .ci-pager li span.ellipsis { border: none !important; background: transparent !important; padding: 0 4px !important; min-width: auto; color: var(--v2-text-muted); }

    /* --- V2 ACTION BUTTONS --- */
    .action-row { display: flex; gap: 6px; align-items: center; justify-content: flex-end; flex-wrap: nowrap; }

    .btn-link-view { font-size: 0.75rem; color: var(--v2-label); text-decoration: none; font-weight: 800; padding: 4px 8px; border-radius: 4px; transition: background 0.2s ease; cursor: pointer; border: none; background: transparent; }
    .btn-link-view:hover { color: var(--v2-title); background: rgba(178, 224, 235, 0.3); }
    
    /* Refined Dispose Button (Outline style so it isn't overwhelming) */
    .btn-table-danger-outline { 
        background: #ffffff; 
        color: #ef4444; 
        border: 1px solid #fca5a5; 
        font-size: 0.7rem; 
        font-weight: 800; 
        padding: 4px 10px; 
        border-radius: 4px; 
        cursor: pointer; 
        text-transform: uppercase; 
        transition: all 0.2s ease; 
    }
    .btn-table-danger-outline:hover { 
        background: #ef4444; 
        color: #ffffff; 
        border-color: #ef4444; 
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); 
    }

    /* --- FIXED MODAL STYLES --- */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.5); display: none; align-items: center; justify-content: center; z-index: 9999; backdrop-filter: blur(2px); }
    .modal-overlay.active { display: flex; }
    .modal-content { 
        background: #ffffff !important; /* Forces out any weird inherited colors */
        padding: 24px; 
        border-radius: 12px; 
        width: 100%; 
        max-width: 420px; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.15); 
        border: 1px solid var(--v2-border); 
    }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--v2-border); padding-bottom: 12px; }
    .modal-header h3 { margin: 0; font-size: 1.1rem; color: var(--v2-title); font-weight: 800; }
    .btn-close-modal { background: transparent; border: none; font-size: 1.5rem; cursor: pointer; color: var(--v2-text-muted); margin:0; padding:0; line-height: 1; transition: color 0.2s; }
    .btn-close-modal:hover { color: #ef4444; }
    
    .modal-body .field { margin-bottom: 16px; }
    .modal-body label { display: block; font-weight: 700; font-size: 0.8rem; color: var(--v2-title); margin-bottom: 6px; }
    .modal-input, .modal-select { width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; color: var(--v2-text-main); background: #ffffff; }
    .modal-input:focus, .modal-select:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }
    
    .modal-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--v2-border); }
    .btn-danger-solid { background: #dc2626; color: white; border: none; padding: 8px 16px; font-weight: 800; font-size: 0.85rem; border-radius: 6px; cursor: pointer; transition: background 0.2s; }
    .btn-danger-solid:hover { background: #b91c1c; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $inventoryQuantityExportQuery = http_build_query(['export' => 'csv', 'q' => ($keyword ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('inventory/quantities') . '?' . $inventoryQuantityExportQuery ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Export CSV</a>
<a class="btn btn-outline" href="<?= site_url('receiving') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Receiving</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $stocks ?? [];
$totalRows = count($rows);
$totalOnHand = array_sum(array_map(static fn (array $row): float => (float) ($row['on_hand_qty'] ?? 0), $rows));
$totalAvailable = array_sum(array_map(static fn (array $row): float => (float) ($row['available_qty'] ?? 0), $rows));
$zeroAvailable = count(array_filter($rows, static fn (array $row): bool => (float) ($row['available_qty'] ?? 0) <= 0));
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Inventory Quantities</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-total"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-skus"><?= esc((string) $totalRows) ?></span>
                    <span class="kpi-label">Visible SKUs</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-sky">
                <div class="kpi-icon-box icon-onhand"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-onhand"><?= esc(app_format_quantity($totalOnHand)) ?></span>
                    <span class="kpi-label">Total On Hand</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-green">
                <div class="kpi-icon-box icon-available"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-available" style="color: #15803d;"><?= esc(app_format_quantity($totalAvailable)) ?></span>
                    <span class="kpi-label">Available</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-red">
                <div class="kpi-icon-box icon-zero"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-zero" style="color: #ef4444;"><?= esc((string) $zeroAvailable) ?></span>
                    <span class="kpi-label">Zero / Negative</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Stock Register</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Manage lots, batches, and expiries.</p>
            </div>
            
            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Search item, batch, or lot..." autocomplete="off">
                </div>
                
                <select id="filter-stock-status" class="filter-select">
                    <option value="all">All Levels</option>
                    <option value="in_stock">In Stock (> 0)</option>
                    <option value="low_stock">Low Stock (≤ 10)</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>

                <select id="filter-expiry-status" class="filter-select">
                    <option value="all">All Expiries</option>
                    <option value="expired">Expired</option>
                    <option value="expiring_30">Expiring ≤ 30 Days</option>
                    <option value="expiring_90">Expiring ≤ 90 Days</option>
                </select>

                <button type="button" class="btn btn-outline" id="btn-clear-filters" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px; height: 34px;">Clear</button>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="inventory-table" style="table-layout: fixed; width: 100%; min-width: 1050px;">
                <colgroup>
                    <col style="width: 60px;">  
                    <col style="width: 22%;">   
                    <col style="width: 80px;">  
                    <col style="width: 12%;">   
                    <col style="width: 10%;">   
                    <col style="width: 100px;"> 
                    <col style="width: 10%;">   
                    <col style="width: 10%;">   
                    <col style="width: 10%;">   
                    <col style="width: 90px;"> 
                    <col style="width: 120px;"> 
                </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">Item</th>
                        <th class="sortable" data-col="2">Unit</th>
                        <th class="sortable" data-col="3">Batch</th>
                        <th class="sortable" data-col="4">Lot</th>
                        <th class="sortable date" data-col="5">Expiry</th>
                        <th class="sortable numeric" data-col="6" style="text-align: right;">On Hand</th>
                        <th class="sortable numeric" data-col="7" style="text-align: right;">Reserved</th>
                        <th class="sortable numeric" data-col="8" style="text-align: right;">Available</th>
                        <th class="sortable numeric" data-col="9" style="text-align: right;">Avg Cost</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($rows ?? []) === []): ?>
                        <tr class="no-records-row">
                            <td colspan="11" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No inventory stocks found.</strong><br>
                                <span style="font-size: 0.8rem;">Adjust your filters to see more results.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $stock): ?>
                            <?php 
                                $expiryRaw = (string) ($stock['expiry_date'] ?? '');
                                $daysUntilExpiry = 9999; 
                                $expiryClass = '';
                                if ($expiryRaw !== '') {
                                    $expDate = strtotime($expiryRaw);
                                    $now = time();
                                    $daysUntilExpiry = ($expDate - $now) / (60 * 60 * 24);
                                    
                                    if ($daysUntilExpiry < 0) $expiryClass = 'color: #ef4444; font-weight: 800;';
                                    elseif ($daysUntilExpiry <= 30) $expiryClass = 'color: #d97706; font-weight: 800;'; 
                                }
                            ?>
                            <tr class="inventory-row" style="display: none;" data-days-expiry="<?= esc((string) $daysUntilExpiry) ?>">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) $stock['id']) ?></td>
                                <td style="font-weight: 800; color: var(--v2-label); word-break: break-word;"><?= esc((string) $stock['item_name']) ?></td>
                                <td style="font-size: 0.85rem; color: var(--v2-text-muted);"><?= esc((string) $stock['unit']) ?></td>
                                <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($stock['batch_no'] ?? '')) ?></td>
                                <td style="font-size: 0.85rem; font-family: var(--font-mono);"><?= esc((string) ($stock['lot_no'] ?? '')) ?></td>
                                <td style="font-size: 0.85rem; <?= $expiryClass ?>"><?= esc($expiryRaw) ?></td>
                                <td style="text-align: right; font-weight: 600;"><?= esc(app_format_quantity($stock['on_hand_qty'] ?? 0)) ?></td>
                                <td style="text-align: right; font-weight: 600;"><?= esc(app_format_quantity($stock['reserved_qty'] ?? 0)) ?></td>
                                
                                <?php 
                                    $availQty = (float) $stock['available_qty'];
                                    $qtyColor = $availQty <= 0 ? 'color: #ef4444; font-weight: 800;' : 'font-weight: 800; color: var(--v2-title);';
                                ?>
                                <td style="text-align: right; <?= $qtyColor ?>"><?= esc(app_format_quantity($stock['available_qty'] ?? 0)) ?></td>
                                <td style="text-align: right; font-family: var(--font-mono); font-size: 0.85rem;">₱<?= esc(number_format((float) ($stock['average_unit_cost'] ?? 0), 2)) ?></td>
                                
                                <td style="text-align: right;">
                                    <div class="action-row">
                                        <?php if ((float)$stock['on_hand_qty'] > 0): ?>
                                            <button type="button" class="btn-table-danger-outline" 
                                                    onclick="openDisposalModal(<?= $stock['id'] ?>, '<?= esc(addslashes((string)$stock['item_name'])) ?>', <?= (float)$stock['on_hand_qty'] ?>)"
                                                    title="Record stock disposal">
                                                Dispose
                                            </button>
                                        <?php endif; ?>
                                        <a class="btn-link-view" href="<?= site_url('inventory/quantities/' . $stock['id']) ?>">View</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalRows) ?></span>)
            </p>
            <nav aria-label="Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </div>
</div>

<div class="modal-overlay" id="disposalModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Record Stock Disposal</h3>
            <button type="button" class="btn-close-modal" onclick="closeDisposalModal()">&times;</button>
        </div>
        <form id="disposalForm" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <p style="margin-bottom:16px; font-size:0.85rem; color: var(--v2-text-muted);" id="modal-disposal-text"></p>
                <div class="field">
                    <label for="disposal_qty">Quantity to Remove: <span style="color:#ef4444;">*</span></label>
                    <input type="number" id="disposal_qty" name="qty" step="1" min="1" class="modal-input" required>
                </div>
                <div class="field" style="margin-top:12px;">
                    <label for="disposal_reason">Reason for Disposal: <span style="color:#ef4444;">*</span></label>
                    <select id="disposal_reason" name="reason" class="modal-select" required>
                        <option value="Expired">Expired / Expired Material</option>
                        <option value="Damaged">Damaged / Broken</option>
                        <option value="Recall">Recall by Manufacturer</option>
                        <option value="Lost">Lost / Unaccounted</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" style="padding: 8px 16px; border-radius: 6px; font-weight: 800; font-size: 0.85rem;" onclick="closeDisposalModal()">Cancel</button>
                <button type="submit" class="btn-danger-solid">Confirm Disposal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDisposalModal(stockId, itemName, maxQty) {
        const modal = document.getElementById('disposalModal');
        const form = document.getElementById('disposalForm');
        const text = document.getElementById('modal-disposal-text');
        const qtyInput = document.getElementById('disposal_qty');
        
        form.action = "<?= site_url('inventory/quantities/') ?>" + stockId + "/adjust-out";
        text.innerHTML = `You are recording a disposal for <strong>${itemName}</strong>.<br>Max available on-hand: <strong style="color: var(--v2-title);">${maxQty}</strong>`;
        qtyInput.max = maxQty;
        qtyInput.value = maxQty;
        
        modal.classList.add('active');
    }

    function closeDisposalModal() {
        document.getElementById('disposalModal').classList.remove('active');
    }

    document.addEventListener('DOMContentLoaded', function() {
        function formatQuantity(value) {
            if (!Number.isFinite(value)) return '0';
            if (Math.abs(value - Math.round(value)) <= 0.00001) {
                return Math.round(value).toLocaleString('en-US');
            }

            return value.toLocaleString('en-US', {maximumFractionDigits: 3});
        }

        const rowsPerPage = 15;
        const tbody = document.querySelector('#inventory-table tbody');
        if (!tbody) return;

        const allRows = Array.from(tbody.querySelectorAll('.inventory-row'));
        let currentRows = [...allRows]; 
        
        const pagerContainer = document.getElementById('client-pager');
        const pageIndicator = document.getElementById('page-indicator');
        const totalIndicator = document.getElementById('total-indicator');
        
        const kpiSkus = document.getElementById('kpi-skus');
        const kpiOnHand = document.getElementById('kpi-onhand');
        const kpiAvailable = document.getElementById('kpi-available');
        const kpiZero = document.getElementById('kpi-zero');

        const searchInput = document.getElementById('instant-search-input');
        const statusFilter = document.getElementById('filter-stock-status');
        const expiryFilter = document.getElementById('filter-expiry-status');
        const clearBtn = document.getElementById('btn-clear-filters');

        if (allRows.length === 0) return;

        function applySearch() {
            const query = searchInput.value.toLowerCase().trim();
            const stockStatus = statusFilter.value;
            const expiryStatus = expiryFilter.value;

            currentRows = allRows.filter(row => {
                const id = row.children[0].innerText.toLowerCase();
                const name = row.children[1].innerText.toLowerCase();
                const batch = row.children[3].innerText.toLowerCase();
                const availableQty = parseFloat(row.children[8].innerText.replace(/,/g, '')) || 0;
                const daysUntilExpiry = parseFloat(row.getAttribute('data-days-expiry'));

                const matchesText = query === '' || id.includes(query) || name.includes(query) || batch.includes(query);
                
                let matchesStatus = true;
                if (stockStatus === 'in_stock') matchesStatus = availableQty > 0;
                else if (stockStatus === 'low_stock') matchesStatus = availableQty > 0 && availableQty <= 10;
                else if (stockStatus === 'out_of_stock') matchesStatus = availableQty <= 0;

                let matchesExpiry = true;
                if (expiryStatus === 'expired') matchesExpiry = daysUntilExpiry < 0;
                else if (expiryStatus === 'expiring_30') matchesExpiry = daysUntilExpiry >= 0 && daysUntilExpiry <= 30;
                else if (expiryStatus === 'expiring_90') matchesExpiry = daysUntilExpiry >= 0 && daysUntilExpiry <= 90;

                return matchesText && matchesStatus && matchesExpiry;
            });

            if (query !== '') {
                currentRows.sort((a, b) => {
                    const aId = a.children[0].innerText.toLowerCase();
                    const aName = a.children[1].innerText.toLowerCase();
                    const bId = b.children[0].innerText.toLowerCase();
                    const bName = b.children[1].innerText.toLowerCase();
                    
                    const aScore = aId.includes(query) ? 1 : (aName.includes(query) ? 2 : 3);
                    const bScore = bId.includes(query) ? 1 : (bName.includes(query) ? 2 : 3);
                    
                    return aScore - bScore;
                });
            }

            currentRows.forEach(row => tbody.appendChild(row));
            updateKPIs();
            showPage(1); 
        }

        function updateKPIs() {
            let sumOnHand = 0, sumAvailable = 0, countZero = 0;
            
            currentRows.forEach(row => {
                sumOnHand += parseFloat(row.children[6].innerText.replace(/,/g, '')) || 0;
                const avail = parseFloat(row.children[8].innerText.replace(/,/g, '')) || 0;
                sumAvailable += avail;
                if (avail <= 0) countZero++;
            });
            
            kpiSkus.innerText = currentRows.length;
            kpiOnHand.innerText = formatQuantity(sumOnHand);
            kpiAvailable.innerText = formatQuantity(sumAvailable);
            kpiZero.innerText = countZero;
        }

        if(searchInput) searchInput.addEventListener('input', applySearch);
        if(statusFilter) statusFilter.addEventListener('change', applySearch);
        if(expiryFilter) expiryFilter.addEventListener('change', applySearch);
        
        if(clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                statusFilter.value = 'all';
                expiryFilter.value = 'all';
                applySearch();
            });
        }

        function showPage(page) {
            const totalRows = currentRows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);
            let currentPage = page;

            if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
            
            const startPoint = (currentPage - 1) * rowsPerPage;
            const endPoint = startPoint + rowsPerPage;

            allRows.forEach(row => row.style.display = 'none');

            currentRows.forEach((row, index) => {
                if (index >= startPoint && index < endPoint) {
                    row.style.display = '';
                }
            });

            const actualEnd = Math.min(endPoint, totalRows);
            if (pageIndicator) pageIndicator.innerText = totalRows === 0 ? '0' : `${startPoint + 1} - ${actualEnd}`;
            if (totalIndicator) totalIndicator.innerText = totalRows;

            buildPaginationButtons(currentPage, totalPages);
        }

        function buildPaginationButtons(currentPage, totalPages) {
            if (!pagerContainer) return;
            pagerContainer.innerHTML = '';
            if (totalPages <= 1) return;

            let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

            if (startPage > 1) {
                html += `<li><a href="#" data-page="1">1</a></li>`;
                if (startPage > 2) html += `<li><span class="ellipsis">...</span></li>`;
            }

            for (let i = startPage; i <= endPage; i++) {
                html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) html += `<li><span class="ellipsis">...</span></li>`;
                html += `<li><a href="#" data-page="${totalPages}">${totalPages}</a></li>`;
            }

            html += `<li class="${currentPage === totalPages ? 'disabled' : ''}"><a href="#" data-page="${currentPage + 1}">Next &raquo;</a></li>`;

            pagerContainer.innerHTML = html;
        }

        if (pagerContainer) {
            pagerContainer.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (!link) return;
                e.preventDefault();
                const li = link.parentElement;
                if (li.classList.contains('disabled') || li.classList.contains('active')) return;
                showPage(parseInt(link.getAttribute('data-page')));
            });
        }

        document.querySelectorAll('#inventory-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const colIndex = parseInt(th.getAttribute('data-col'));
                const isNumericCol = th.classList.contains('numeric') || colIndex === 0;
                const isDateCol = th.classList.contains('date');
                const isAsc = th.classList.contains('asc');
                const direction = isAsc ? -1 : 1; 
                
                document.querySelectorAll('#inventory-table th.sortable').forEach(header => {
                    header.classList.remove('asc', 'desc');
                });
                
                th.classList.add(isAsc ? 'desc' : 'asc');
                
                currentRows.sort((a, b) => {
                    let aText = a.children[colIndex].innerText.trim();
                    let bText = b.children[colIndex].innerText.trim();
                    
                    if (isNumericCol) {
                        aText = aText.replace(/,/g, '').replace('₱', '');
                        bText = bText.replace(/,/g, '').replace('₱', '');
                        return (parseFloat(aText) - parseFloat(bText)) * direction;
                    }

                    if (isDateCol) {
                        let dateA = aText === '' ? 0 : new Date(aText).getTime();
                        let dateB = bText === '' ? 0 : new Date(bText).getTime();
                        return (dateA - dateB) * direction;
                    }
                    
                    return aText.localeCompare(bText) * direction;
                });
                
                currentRows.forEach(row => tbody.appendChild(row));
                showPage(1);
            });
        });

        showPage(1);
    });
</script>
<?= $this->endSection() ?>
