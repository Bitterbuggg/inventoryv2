<?php

declare(strict_types=1);

$title = 'Admin Dashboard - InventoryV2';
$pageTitle = 'Admin Dashboard';
$pageSubtitle = 'Operational snapshot aligned to your current workflow modules.';

// Mapped to specific pastel icon styles and actual relevant concepts
$moduleStatus = [
    ['name' => 'Auth & RBAC', 'note' => 'Shield login, security', 'icon' => 'auth', 'accent' => 'kpi-accent-slate'], 
    ['name' => 'Procurement', 'note' => 'PR, approvals, PO', 'icon' => 'procurement', 'accent' => 'kpi-accent-violet'], 
    ['name' => 'Receiving', 'note' => 'Draft, validate, post', 'icon' => 'receiving', 'accent' => 'kpi-accent-royal'], 
    ['name' => 'Inventory', 'note' => 'Issuance, analytics', 'icon' => 'inventory', 'accent' => 'kpi-accent-amber'], 
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- V2 DESIGN SYSTEM VARIABLES --- */
    :root {
        --v2-border: #b2e0eb; /* Soft cyan border */
        --v2-title: #00476b;  /* Deep navy/teal for headers */
        --v2-label: #00668c;  /* Bright teal for small labels and icons */
        --v2-active-bg: #00638a; /* Solid dark blue for active hover state */
        --v2-text-muted: #64748b;
    }

    /* --- NO-SCROLL VIEWPORT WRAPPER --- */
    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        min-height: calc(100vh - 120px);
        padding-bottom: 20px;
    }

    /* --- PASTEL KPI CARDS --- */
    .kpi-grid { 
        display: grid; 
        grid-template-columns: repeat(4, minmax(0, 1fr)); 
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
        width: 50px; height: 50px; 
        border-radius: 12px; 
        display: flex; align-items: center; justify-content: center; 
        flex-shrink: 0;
    }
    
    .icon-auth { background: #f1f5f9; color: #475569; }        
    .icon-procurement { background: #f5f3ff; color: #8b5cf6; } 
    .icon-receiving { background: #eff6ff; color: #2563eb; }   
    .icon-inventory { background: #ecfccb; color: #d97706; }   

    .kpi-details { display: flex; flex-direction: column; flex: 1; justify-content: center; min-width: 0; }

    .kpi-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .kpi-value { 
        font-size: 1.05rem; 
        font-weight: 800; 
        color: var(--v2-title); 
        line-height: 1.2; 
        margin: 0; 
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
    }
    
    .kpi-label { 
        font-size: 0.75rem; 
        font-weight: 500; 
        color: var(--v2-text-muted); 
        margin: 0; 
        margin-top: 4px; 
        line-height: 1.35;
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 9999px;
        background: #dcfce7;
        color: #166534;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        flex-shrink: 0;
    }

    @media (max-width: 900px) {
        .kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
    }
    .status-dot {
        width: 6px; height: 6px;
        background: #16a34a;
        border-radius: 50%;
    }

    /* --- DASHBOARD GROUPS --- */
    .dashboard-group {
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        overflow: hidden;
    }

    /* FIXED: flex: 1 removed to allow shrink-wrap around buttons */
    .dashboard-group-main { min-height: 0; }

    .dashboard-section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px; 
        border-bottom: 1px solid var(--v2-border); 
        background: rgba(178, 224, 235, 0.15);
    }
    
    .dashboard-section-header h3 { 
        margin: 0; 
        font-size: 1.05rem; 
        color: var(--v2-title); 
        font-weight: 800; 
    }

    .action-grid-wrap { padding: 14px 20px 20px; }

    /* --- ACTION CARDS --- */
    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
    }

    .action-card {
        display: flex;
        align-items: center;
        gap: 14px; 
        padding: 14px 16px; 
        min-height: 72px;
        background: #ffffff;
        border: 1px solid var(--v2-border); 
        border-radius: 8px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .action-card .text-wrap {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .action-icon-box {
        width: 40px; 
        height: 40px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f5f9; 
        color: var(--v2-label); 
        transition: all 0.2s ease;
    }

    .action-label {
        font-weight: 800;
        font-size: 0.9rem; 
        color: var(--v2-title);
        line-height: 1.2;
        transition: color 0.2s ease;
    }

    .action-desc {
        font-size: 0.75rem;
        color: var(--v2-label);
        margin-top: 2px;
        transition: color 0.2s ease;
    }

    /* THE HOVER MAGIC */
    .action-card:hover {
        background: var(--v2-active-bg);
        border-color: var(--v2-active-bg);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 71, 107, 0.2);
    }
    .action-card:hover .action-icon-box {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }
    .action-card:hover .action-label,
    .action-card:hover .action-desc {
        color: #ffffff;
    }

    @media (max-width: 900px) {
        .viewport-wrapper {
            min-height: auto;
        }

        .action-grid-wrap {
            padding: 12px 16px 16px;
        }

        .dashboard-section-header {
            padding: 12px 16px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Operations Console</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <?php foreach ($moduleStatus as $card): ?>
                <article class="kpi-card <?= esc((string) $card['accent']) ?>">
                    <div class="kpi-icon-box icon-<?= $card['icon'] ?>">
                        <?php if($card['icon'] === 'auth'): ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <?php elseif($card['icon'] === 'procurement'): ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        <?php elseif($card['icon'] === 'receiving'): ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <?php else: ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                        <?php endif; ?>
                    </div>
                    <div class="kpi-details">
                        <div class="kpi-header">
                            <span class="kpi-value"><?= esc((string) $card['name']) ?></span>
                            <div class="status-pill">
                                <div class="status-dot"></div>
                                Active
                            </div>
                        </div>
                        <span class="kpi-label"><?= esc((string) $card['note']) ?></span>
                    </div>
                </article>
            <?php endforeach ?>
        </div>
    </section>

    <div class="dashboard-group dashboard-group-main">
        <div class="dashboard-section-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--v2-title)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <h3>Core Operations</h3>
        </div>
        
        <div class="action-grid-wrap">
            <div class="action-grid">
                <a href="<?= site_url('admin/users') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Manage Users</span>
                        <span class="action-desc">Roles & Security</span>
                    </div>
                </a>
                <a href="<?= site_url('admin/products') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73L13 2.27a2 2 0 0 0-2 0L4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><line x1="12" y1="22" x2="12" y2="12"></line></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Products</span>
                        <span class="action-desc">Master Catalog</span>
                    </div>
                </a>
                <a href="<?= site_url('admin/suppliers') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"></path><path d="M5 21V7l8-4v18"></path><path d="M19 21V11l-6-4"></path></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Suppliers</span>
                        <span class="action-desc">Vendor Catalog</span>
                    </div>
                </a>
                <a href="<?= site_url('procurement/purchase-requests') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">PR Requests</span>
                        <span class="action-desc">Submission Workflow</span>
                    </div>
                </a>
                <a href="<?= site_url('procurement/approvals/pending') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="10"></circle></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Pending</span>
                        <span class="action-desc">Decision Queue</span>
                    </div>
                </a>
                <a href="<?= site_url('procurement/purchase-orders') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Orders</span>
                        <span class="action-desc">Tracking</span>
                    </div>
                </a>
                <a href="<?= site_url('procurement/po-requests') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">PO Requests</span>
                        <span class="action-desc">Conversion Flow</span>
                    </div>
                </a>
                <a href="<?= site_url('receiving') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Receiving</span>
                        <span class="action-desc">Inbound Stock</span>
                    </div>
                </a>
                <a href="<?= site_url('inventory/quantities') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Inventory</span>
                        <span class="action-desc">Stock Live View</span>
                    </div>
                </a>
                <a href="<?= site_url('inventory/issuance') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Issuance</span>
                        <span class="action-desc">Stock Release</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="dashboard-group">
        <div class="dashboard-section-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--v2-title)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
            <h3>Reporting & Analytics</h3>
        </div>

        <div class="action-grid-wrap">
            <div class="action-grid">
                <a href="<?= site_url('reports/stock-balance') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Stock Balance</span>
                        <span class="action-desc">Audit Ledger</span>
                    </div>
                </a>
                <a href="<?= site_url('reports/stock-movements') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Movements</span>
                        <span class="action-desc">Audit Log</span>
                    </div>
                </a>
                <a href="<?= site_url('reports/issuances') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Issuances Audit</span>
                        <span class="action-desc">Distribution Audit</span>
                    </div>
                </a>
                <a href="<?= site_url('analytics/activity-logs') ?>" class="action-card">
                    <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg></div>
                    <div class="text-wrap">
                        <span class="action-label">Activity Logs</span>
                        <span class="action-desc">System Analytics</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
