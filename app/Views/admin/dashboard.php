<?php

declare(strict_types=1);

$title = 'Admin Dashboard - InventoryV2';
$pageTitle = 'Admin Dashboard';
$pageSubtitle = 'Operational snapshot aligned to your current workflow modules.';

$moduleStatus = [
    ['name' => 'Auth and RBAC', 'note' => 'Shield login, signup', 'color' => '#1e293b'], 
    ['name' => 'Procurement', 'note' => 'PR, approvals, PO', 'color' => '#0ea5e9'],    
    ['name' => 'Receiving', 'note' => 'Draft, validate, post', 'color' => 'var(--color-accent-amber-500)'], 
    ['name' => 'Inventory and Reports', 'note' => 'Issuance, analytics', 'color' => 'var(--color-success-700)'], 
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- SINGLE SCREEN DENSITY --- */
    :root {
        --card-bg: #ffffff;
        --header-navy: #1e293b;
        /* Global Icon Colors from Analytics request */
        --icon-box-bg: #e0f2fe; 
        --icon-color: #0369a1;
    }

    .stack-lg { gap: 0.85rem !important; }

    /* --- KPI GRID: Fixing pseudo-elements and requested colors --- */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
        margin-top: 10px;
    }

    .kpi-card {
        position: relative;
        padding: 14px 20px 14px 28px !important; 
        border-radius: 10px;
        background: var(--card-bg);
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        min-height: 85px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow: hidden;
    }

    /* Pixel-perfect bar alignment */
    .kpi-card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
        background-color: var(--bar-color);
        z-index: 1;
    }

    .kpi-value { 
        font-size: 1.25rem !important; 
        font-weight: 800;
        margin: 2px 0;
        color: var(--header-navy);
        line-height: 1;
    }

    .kpi-label { font-size: 0.75rem !important; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; opacity: 0.85; }
    .kpi-note { font-size: 0.7rem !important; margin: 0; opacity: 0.65; white-space: nowrap; }

    /* --- SECTION CONTAINERS --- */
    .dashboard-group {
        background: rgba(255, 255, 255, 0.45); 
        padding: 14px 18px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.7);
    }

    .dashboard-section-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }
    
    .dashboard-section-header h3 { 
        margin: 0; 
        font-size: 0.85rem; 
        color: var(--header-navy); 
        text-transform: uppercase; 
        letter-spacing: 0.1em; 
        font-weight: 900; 
    }

    /* --- ACTION CARDS: Layout and Hover fixes --- */
    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); 
        gap: 10px;
    }

    .action-card {
        display: flex;
        align-items: center;
        gap: 14px; 
        padding: 14px 16px; 
        background: var(--card-bg);
        border: 1px solid #e2e8f0; 
        border-radius: 8px;
        text-decoration: none !important; /* Prevents underlined text */
        transition: all 0.2s ease;
    }

    .action-card .text-wrap {
        display: flex;
        flex-direction: column; /* Vertical stack fix */
        justify-content: center;
    }

    .action-card:hover {
        border-color: var(--color-brand-500);
        transform: translateY(-2px); 
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
    }

    /* Removal of underlining on hover */
    .action-card:hover .action-label, 
    .action-card:hover .action-desc {
        text-decoration: none !important;
    }

    /* Global Icon Box Theme */
    .action-icon-box {
        width: 38px; 
        height: 38px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: var(--icon-box-bg); 
        color: var(--icon-color); 
        transition: all 0.2s ease;
    }

    .action-card:hover .action-icon-box {
        background: var(--color-brand-600);
        color: #ffffff;
    }

    .action-label {
        font-weight: 700;
        font-size: 0.9rem; 
        color: #0f172a;
        line-height: 1.1;
    }

    .action-desc {
        font-size: 0.7rem;
        color: #64748b;
        margin-top: 1px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    <section class="card" style="padding: 1.1rem 1.75rem !important;">
        <div style="margin-bottom: 12px;">
            <h2 style="margin:0; font-size: 1.6rem; color: var(--header-navy); font-weight: 900; letter-spacing: -0.02em;">Operations Console</h2>
            <p class="muted" style="font-size: 0.9rem; margin-top: 4px;">Operational snapshot of InventoryV2</p>
        </div>

        <div class="kpi-grid">
            <?php foreach ($moduleStatus as $card): ?>
                <article class="kpi-card" style="--bar-color: <?= $card['color'] ?>;">
                    <p class="kpi-label" style="color: <?= $card['color'] ?>;"><?= esc((string) $card['name']) ?></p>
                    <p class="kpi-value">Active</p>
                    <p class="kpi-note muted"><?= esc((string) ($card['note'] ?? '')) ?></p>
                </article>
            <?php endforeach ?>
        </div>
    </section>

    <div class="dashboard-group">
        <div class="dashboard-section-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-brand-600);"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <h3>Core Operations</h3>
        </div>
        
        <div class="action-grid">
            <a href="<?= site_url('admin/users') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>
                <div class="text-wrap">
                    <span class="action-label">Manage Users</span>
                    <span class="action-desc">Roles & Security</span>
                </div>
            </a>
            <a href="<?= site_url('procurement/purchase-requests') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg></div>
                <div class="text-wrap">
                    <span class="action-label">PR Requests</span>
                    <span class="action-desc">Submission Workflow</span>
                </div>
            </a>
            <a href="<?= site_url('procurement/approvals/pending') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="10"></circle></svg></div>
                <div class="text-wrap">
                    <span class="action-label">Pending</span>
                    <span class="action-desc">Decision Queue</span>
                </div>
            </a>
            <a href="<?= site_url('procurement/purchase-orders') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg></div>
                <div class="text-wrap">
                    <span class="action-label">Orders</span>
                    <span class="action-desc">Tracking</span>
                </div>
            </a>
            <a href="<?= site_url('procurement/po-requests') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path></svg></div>
                <div class="text-wrap">
                    <span class="action-label">PO Requests</span>
                    <span class="action-desc">Conversion Flow</span>
                </div>
            </a>
            <a href="<?= site_url('receiving') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path></svg></div>
                <div class="text-wrap">
                    <span class="action-label">Receiving</span>
                    <span class="action-desc">Inbound Stock</span>
                </div>
            </a>
            <a href="<?= site_url('inventory/quantities') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg></div>
                <div class="text-wrap">
                    <span class="action-label">Inventory</span>
                    <span class="action-desc">Stock Live View</span>
                </div>
            </a>
            <a href="<?= site_url('inventory/issuance') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path></svg></div>
                <div class="text-wrap">
                    <span class="action-label">Issuance</span>
                    <span class="action-desc">Stock Release</span>
                </div>
            </a>
        </div>
    </div>

    <div class="dashboard-group">
        <div class="dashboard-section-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--header-navy);"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
            <h3>Reporting & Analytics</h3>
        </div>

        <div class="action-grid">
            <a href="<?= site_url('reports/stock-balance') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg></div>
                <div class="text-wrap">
                    <span class="action-label">Stock Balance</span>
                    <span class="action-desc">Audit Ledger</span>
                </div>
            </a>
            <a href="<?= site_url('reports/stock-movements') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline></svg></div>
                <div class="text-wrap">
                    <span class="action-label">Movements</span>
                    <span class="action-desc">Audit Log</span>
                </div>
            </a>
            <a href="<?= site_url('reports/issuances') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg></div>
                <div class="text-wrap">
                    <span class="action-label">Issuances Audit</span>
                    <span class="action-desc">Distribution Audit</span>
                </div>
            </a>
            <a href="<?= site_url('analytics/activity-logs') ?>" class="action-card">
                <div class="action-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg></div>
                <div class="text-wrap">
                    <span class="action-label">Activity Logs</span>
                    <span class="action-desc">Analytics</span>
                </div>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
