<?php

declare(strict_types=1);

$title = 'System Architecture - InventoryV2';
$pageTitle = 'System Architecture';
$pageSubtitle = 'Implemented module map, request pipeline, and end-to-end operational flow for the current application.';
$crumbs = [
    ['label' => 'Analytics'],
    ['label' => 'System Architecture'],
];

$requestPipeline = [
    [
        'step' => '01',
        'title' => 'Routes',
        'summary' => 'HTTP endpoints are declared in one place and grouped by business domain.',
        'points' => ['Public auth routes', 'Protected admin/procurement/receiving/inventory/reporting/analytics groups', 'Role filters attached per route'],
    ],
    [
        'step' => '02',
        'title' => 'Filters',
        'summary' => 'Each request passes through authentication, role checks, CSRF, and multi-session validation.',
        'points' => ['Shield session auth', 'RoleFilter for group-based access', 'MultiSessionFilter to validate or switch tracked sessions'],
    ],
    [
        'step' => '03',
        'title' => 'Controllers',
        'summary' => 'Controllers validate input, call services, and render views or redirects.',
        'points' => ['CSV exports handled here', 'Analytics tracking emitted from actions', 'Shared layouts wrap all screens'],
    ],
    [
        'step' => '04',
        'title' => 'Services',
        'summary' => 'Business rules, workflow state changes, transactions, and audit writing live here.',
        'points' => ['Procurement lifecycle', 'Receiving validation and inventory posting', 'Issuance approval, allocation, and release'],
    ],
    [
        'step' => '05',
        'title' => 'Repositories',
        'summary' => 'Repository interfaces isolate data access and are bound centrally through RepositoryServices.',
        'points' => ['Procurement repositories', 'Receiving and inventory repositories', 'Analytics and audit repositories'],
    ],
    [
        'step' => '06',
        'title' => 'Models and Database',
        'summary' => 'CodeIgniter models persist workflow state into transactional tables.',
        'points' => ['purchase_requests / purchase_orders / po_requests', 'receivings / inventory_stocks / stock_movements', 'issuances / approvals / analytics / audit / multi_sessions'],
    ],
];

$systemFlow = [
    [
        'step' => '01',
        'title' => 'Authentication and Session Gate',
        'summary' => 'Every protected workflow begins with Shield session authentication plus project-specific multi-session tracking.',
        'happens' => [
            'LoginController validates credentials through AuthenticationService and Shield.',
            'Successful login creates a multi-session record and stores a tracked session token.',
            'RoleFilter allows or rejects access based on the user group required by each route.',
            'MultiSessionFilter keeps the tracked session aligned with the authenticated Shield user.',
        ],
        'inputs' => ['identifier', 'password', 'existing session state'],
        'outputs' => ['authenticated user', 'active role context', 'tracked multi-session token'],
        'routes' => ['/login', '/logout'],
    ],
    [
        'step' => '02',
        'title' => 'Admin Access and Permission Control',
        'summary' => 'Admins control who can reach downstream modules by assigning groups and granular permissions.',
        'happens' => [
            'UserController creates accounts, edits users, assigns one primary role, and grants or revokes module permissions.',
            'The shared sidebar reads current roles and permissions to show or hide module navigation.',
            'This layer decides whether procurement, receiving, inventory, reports, and analytics appear for a user.',
        ],
        'inputs' => ['users', 'groups', 'permission selections'],
        'outputs' => ['role assignments', 'permission grants', 'module visibility'],
        'routes' => ['/admin/dashboard', '/admin/users'],
    ],
    [
        'step' => '03',
        'title' => 'Procurement Request Capture',
        'summary' => 'Operational purchasing starts with a draft purchase request containing free-form item names and units.',
        'happens' => [
            'PurchaseRequestController creates a PR draft with request date, remarks, and line items.',
            'PurchaseRequestService enforces valid quantities and prevents duplicate item and unit pairs in the same PR.',
            'Submitting a PR changes status from draft to submitted and creates a pending approval record.',
        ],
        'inputs' => ['request date', 'remarks', 'item_name', 'unit', 'requested_qty', 'estimated_unit_cost'],
        'outputs' => ['purchase_requests row', 'purchase_request_items rows', 'pending approvals row'],
        'routes' => ['/procurement/purchase-requests', '/procurement/purchase-requests/create'],
    ],
    [
        'step' => '04',
        'title' => 'Procurement Approval, Purchase Order, and PO Request',
        'summary' => 'Approved purchase requests are converted into orders, then into PO requests that are eligible for receiving.',
        'happens' => [
            'ApprovalService resolves purchase request approvals and updates PR status to approved or rejected.',
            'PurchaseOrderService converts approved PR items into purchase order items and marks the PR as converted_to_po.',
            'PurchaseOrderController issues the purchase order.',
            'PoRequestService creates and approves a PO request so the record becomes eligible for receiving conversion.',
        ],
        'inputs' => ['submitted purchase request', 'approval decision', 'supplier_name', 'issued purchase order'],
        'outputs' => ['purchase_orders row', 'purchase_order_items rows', 'po_requests row'],
        'routes' => ['/procurement/approvals/pending', '/procurement/purchase-orders', '/procurement/po-requests'],
    ],
    [
        'step' => '05',
        'title' => 'Receiving Conversion and Draft Validation',
        'summary' => 'An approved PO request is converted into a receiving draft that captures accepted, rejected, and traceability quantities.',
        'happens' => [
            'ReceivingService loads remaining quantities from purchase_order_items and builds a conversion form.',
            'ReceivingValidationService enforces whole numbers, accepted plus rejected equals received, non-past expiry, and no over-receipt.',
            'Creating the receiving draft moves the PO request into an intermediate converting status until posting or voiding.',
        ],
        'inputs' => ['approved po_request', 'purchase order item balances', 'received_qty', 'accepted_qty', 'rejected_qty', 'batch and lot and expiry'],
        'outputs' => ['receivings row', 'receiving_items rows', 'po_request status=converting'],
        'routes' => ['/receiving', '/receiving/create/from-po-request/{id}', '/receiving/{id}/validate'],
    ],
    [
        'step' => '06',
        'title' => 'Inventory Posting and Stock Ledger',
        'summary' => 'Posting a receiving writes accepted stock into inventory and creates inbound movement history.',
        'happens' => [
            'InventoryPostingService increments or creates inventory stock keyed by item_name plus unit plus batch plus lot plus expiry.',
            'Weighted average unit cost is recalculated when stock already exists.',
            'StockMovementService writes inbound receiving movements.',
            'Purchase order lines update received_qty and the purchase order moves to partially_received or fully_received.',
            'InventoryQuantityService exposes the stock ledger and supports manual disposal through adjustment_out.',
        ],
        'inputs' => ['posted receiving draft', 'accepted quantities', 'current inventory stock rows'],
        'outputs' => ['inventory_stocks rows', 'stock_movements rows', 'updated purchase_order_items received_qty'],
        'routes' => ['/receiving/{id}/post', '/inventory/quantities', '/inventory/quantities/{id}'],
    ],
    [
        'step' => '07',
        'title' => 'Issuance Approval and Release',
        'summary' => 'Outgoing stock is requested, approved, allocated from available inventory, and then released.',
        'happens' => [
            'IssuanceService creates a draft request and submits it for approval by creating a pending approval row.',
            'IssuanceApprovalService resolves submitted issuance requests into approved or rejected state.',
            'IssuanceReleaseService allocates stock from inventory using expiry-first ordering, updates balances, creates outbound stock movements, and stores issuance_item_allocations.',
            'Released issuance lines are backfilled with issued quantity, cost, line total, and primary stock reference.',
        ],
        'inputs' => ['approved issuance', 'inventory availability by item and unit', 'requested_qty'],
        'outputs' => ['issuance_items updates', 'issuance_item_allocations rows', 'outbound stock_movements', 'issuances status=released'],
        'routes' => ['/inventory/issuance', '/inventory/issuance/{id}/approve', '/inventory/issuance/{id}/release'],
    ],
    [
        'step' => '08',
        'title' => 'Reporting, Audit, and Analytics',
        'summary' => 'The final layer reads the operational trail to explain what happened, who did it, and how often it occurs.',
        'happens' => [
            'ReportingService reads stock balances, movement history, issuance summaries, low-stock rows, and fast-moving items directly from transactional tables.',
            'AnalyticsController and AnalyticsService record controller-level telemetry into analytics_events and aggregate daily metrics.',
            'AuditService records service-level state transitions for critical workflows like receiving and issuance.',
            'This page sits in the same internal reference area as Activity Logs.',
        ],
        'inputs' => ['inventory_stocks', 'stock_movements', 'issuances', 'analytics_events', 'audit_logs'],
        'outputs' => ['reports', 'activity logs', 'daily metrics', 'audit trail'],
        'routes' => ['/reports/*', '/analytics/activity-logs', '/analytics/system-architecture'],
    ],
];

$moduleCards = [
    [
        'title' => 'Foundation and Runtime',
        'purpose' => 'Bootstraps the app, exposes routes, runs filters, renders shared layouts, and constructs repositories and services.',
        'controllers' => ['BaseController', 'Home'],
        'services' => ['RepositoryServices'],
        'repositories' => ['All repository bindings'],
        'tables' => ['Runtime only'],
        'depends_on' => ['CodeIgniter 4', 'Shield'],
        'feeds_into' => ['Every module'],
    ],
    [
        'title' => 'Auth and RBAC',
        'purpose' => 'Owns signup, login, logout, group assignment, and protected route entry.',
        'controllers' => ['Auth\\LoginController', 'Auth\\SignupController', 'Auth\\LogoutController'],
        'services' => ['AuthenticationService', 'AuthorizationService'],
        'repositories' => ['UserRepository'],
        'tables' => ['users', 'Shield auth tables'],
        'depends_on' => ['Foundation and Runtime'],
        'feeds_into' => ['Admin', 'Procurement', 'Receiving', 'Inventory', 'Reports', 'Analytics'],
    ],
    [
        'title' => 'Multi-Session Tracking',
        'purpose' => 'Keeps multiple active account sessions trackable and restorable inside one browser environment.',
        'controllers' => ['Auth\\LoginController', 'Auth\\LogoutController'],
        'services' => ['SessionManager'],
        'repositories' => ['MultiSessionModel direct usage'],
        'tables' => ['multi_sessions'],
        'depends_on' => ['Auth and RBAC'],
        'feeds_into' => ['All protected requests through MultiSessionFilter'],
    ],
    [
        'title' => 'Admin and User Management',
        'purpose' => 'Creates and manages users, assigns roles, and grants per-module permissions.',
        'controllers' => ['Admin\\DashboardController', 'Admin\\UserController'],
        'services' => ['AuthenticationService for user creation'],
        'repositories' => ['UserRepository plus direct Shield UserModel usage'],
        'tables' => ['users', 'Shield auth tables'],
        'depends_on' => ['Auth and RBAC'],
        'feeds_into' => ['Route visibility and permission checks across all modules'],
    ],
    [
        'title' => 'Procurement',
        'purpose' => 'Handles purchase requests, approvals, purchase orders, and PO requests.',
        'controllers' => ['Procurement\\PurchaseRequestController', 'Procurement\\PurchaseApprovalController', 'Procurement\\PurchaseOrderController', 'Procurement\\PoRequestController'],
        'services' => ['PurchaseRequestService', 'ApprovalService', 'PurchaseOrderService', 'PoRequestService'],
        'repositories' => ['PurchaseRequestRepository', 'ApprovalRepository', 'PurchaseOrderRepository', 'PoRequestRepository'],
        'tables' => ['purchase_requests', 'purchase_request_items', 'approvals', 'purchase_orders', 'purchase_order_items', 'po_requests'],
        'depends_on' => ['Auth and RBAC', 'Admin and User Management'],
        'feeds_into' => ['Receiving'],
    ],
    [
        'title' => 'Receiving',
        'purpose' => 'Converts approved PO requests into receivings, validates item lines, and prepares them for posting.',
        'controllers' => ['Receiving\\ReceivingController', 'Receiving\\ReceivingValidationController'],
        'services' => ['ReceivingService', 'ReceivingValidationService'],
        'repositories' => ['ReceivingRepository', 'ReceivingItemRepository', 'PoRequestRepository', 'PurchaseOrderRepository'],
        'tables' => ['receivings', 'receiving_items', 'po_requests', 'purchase_order_items'],
        'depends_on' => ['Procurement'],
        'feeds_into' => ['Inventory Stock Ledger'],
    ],
    [
        'title' => 'Inventory Stock Ledger',
        'purpose' => 'Stores on-hand, reserved, and available stock plus movement history and manual adjustment-out records.',
        'controllers' => ['Receiving\\InventoryQuantityController'],
        'services' => ['InventoryPostingService', 'InventoryQuantityService', 'StockMovementService'],
        'repositories' => ['Receiving\\InventoryStockRepository', 'Receiving\\StockMovementRepository'],
        'tables' => ['inventory_stocks', 'stock_movements'],
        'depends_on' => ['Receiving'],
        'feeds_into' => ['Issuance', 'Reports'],
    ],
    [
        'title' => 'Issuance',
        'purpose' => 'Creates outgoing stock requests, approves them, allocates stock, and releases inventory out of the ledger.',
        'controllers' => ['Inventory\\IssuanceController', 'Inventory\\IssuanceApprovalController'],
        'services' => ['IssuanceService', 'IssuanceApprovalService', 'IssuanceReleaseService', 'InventoryAvailabilityService'],
        'repositories' => ['IssuanceRepository', 'IssuanceItemRepository', 'IssuanceItemAllocationRepository', 'Inventory\\InventoryStockRepository', 'Inventory\\StockMovementRepository'],
        'tables' => ['issuances', 'issuance_items', 'issuance_item_allocations', 'approvals', 'inventory_stocks', 'stock_movements'],
        'depends_on' => ['Inventory Stock Ledger'],
        'feeds_into' => ['Reports', 'Analytics', 'Audit'],
    ],
    [
        'title' => 'Reporting',
        'purpose' => 'Reads operational tables to produce stock balance, stock movement, issuance, low-stock, and fast-moving outputs.',
        'controllers' => ['Inventory\\ReportingController'],
        'services' => ['ReportingService'],
        'repositories' => ['ReportingRepository'],
        'tables' => ['inventory_stocks', 'stock_movements', 'issuances', 'issuance_items'],
        'depends_on' => ['Inventory Stock Ledger', 'Issuance'],
        'feeds_into' => ['Operational monitoring and exports'],
    ],
    [
        'title' => 'Analytics and Internal Telemetry',
        'purpose' => 'Captures controller-level events, aggregates daily metrics, and exposes analytics screens and exports.',
        'controllers' => ['Analytics\\AnalyticsController'],
        'services' => ['AnalyticsService'],
        'repositories' => ['AnalyticsRepository'],
        'tables' => ['analytics_events', 'analytics_daily_metrics'],
        'depends_on' => ['All controller-facing modules'],
        'feeds_into' => ['Activity Logs', 'Operational analytics', 'This architecture page'],
    ],
    [
        'title' => 'Audit Logging',
        'purpose' => 'Stores service-level state changes for workflows where the business transition itself matters.',
        'controllers' => ['Indirect only'],
        'services' => ['AuditService'],
        'repositories' => ['AuditLogRepository'],
        'tables' => ['audit_logs'],
        'depends_on' => ['Receiving', 'Issuance', 'Other service workflows'],
        'feeds_into' => ['Compliance and traceability'],
    ],
];

$interconnections = [
    'The same role and permission rules control both route access and sidebar navigation visibility.',
    'Procurement creates the records that receiving needs. Receiving cannot start until a PO request is approved.',
    'Receiving posting is the point where ordered items become real stock in inventory_stocks and stock_movements.',
    'Issuance never creates stock. It only consumes available stock and records outbound movement plus allocation detail.',
    'Reports read the transactional truth directly from stock, movement, and issuance tables rather than from a separate warehouse.',
    'Analytics records controller-level user activity, while audit logs record business-state transitions inside services.',
];

$implementationNotes = [
    'The implemented application currently uses free-form item_name, unit, and supplier_name strings throughout procurement, receiving, inventory, and issuance. The richer product and supplier catalog described in docs is not yet the runtime source of truth.',
    'RepositoryServices is the central dependency registry for most workflows, but Admin\\UserController still talks directly to Shield UserModel for several user-management operations.',
    'The Activity Logs page is the current unified analytics surface for overview, event logs, and metrics. The older dashboard, events, and metrics routes still map into that area.',
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    .architecture-page {
        display: flex;
        flex-direction: column;
        gap: var(--space-4);
        width: 100%;
        max-width: 1320px;
        margin-inline: auto;
        min-width: 0;
    }

    .architecture-page > .card,
    .summary-card,
    .pipeline-card,
    .module-card,
    .flow-stage,
    .mini-card,
    .module-block,
    .flow-stage-body {
        min-width: 0;
    }

    .architecture-hero {
        background:
            radial-gradient(circle at top right, rgba(0, 180, 216, 0.18), transparent 32%),
            linear-gradient(135deg, rgba(3, 4, 94, 0.06), rgba(0, 119, 182, 0.03));
    }

    .architecture-hero .page-subtitle,
    .card .page-subtitle,
    .muted {
        max-width: 78ch;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .architecture-page p,
    .architecture-page li {
        text-wrap: pretty;
    }

    .architecture-page code {
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .architecture-summary-grid,
    .pipeline-grid,
    .module-grid,
    .flow-meta-grid {
        display: grid;
        gap: var(--space-3);
    }

    .architecture-summary-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .summary-card,
    .pipeline-card,
    .module-card,
    .flow-stage,
    .mini-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border-strong);
        border-radius: var(--radius-md);
    }

    .summary-card,
    .pipeline-card,
    .module-card,
    .mini-card {
        padding: var(--space-3);
    }

    .summary-value {
        font-size: clamp(1.2rem, 1.05rem + 0.6vw, 1.6rem);
        font-weight: 800;
        color: var(--color-brand-700);
        line-height: 1.05;
    }

    .summary-label {
        margin-top: 6px;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--color-text-muted);
        font-weight: 700;
    }

    .pipeline-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .step-no {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        border-radius: 999px;
        background: var(--color-brand-100);
        color: var(--color-brand-700);
        font-weight: 800;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .chip-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: flex-start;
    }

    .chip {
        display: inline-flex;
        align-items: flex-start;
        padding: 5px 10px;
        border-radius: 999px;
        background: var(--color-surface-alt);
        border: 1px solid var(--color-border);
        color: var(--color-brand-700);
        font-size: 0.78rem;
        font-weight: 700;
        line-height: 1.35;
        max-width: 100%;
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .flow-list {
        display: flex;
        flex-direction: column;
        gap: var(--space-3);
    }

    .flow-stage {
        display: grid;
        grid-template-columns: 84px minmax(0, 1fr);
        overflow: hidden;
    }

    .flow-stage-index {
        background: linear-gradient(180deg, var(--color-brand-700), var(--color-brand-600));
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: 0.04em;
    }

    .flow-stage-body {
        padding: var(--space-4);
        background: linear-gradient(180deg, #ffffff, #f9fdff);
    }

    .flow-stage-body h3,
    .module-card h3,
    .pipeline-card h3,
    .mini-card h3 {
        margin: 0;
        color: var(--color-brand-700);
        font-size: 1rem;
        line-height: 1.3;
    }

    .flow-columns,
    .module-grid {
        display: grid;
        gap: var(--space-3);
    }

    .flow-columns {
        grid-template-columns: minmax(0, 1.55fr) minmax(0, 1fr);
        margin-top: var(--space-3);
    }

    .flow-meta-grid {
        grid-template-columns: 1fr;
    }

    .section-label {
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--color-text-muted);
        font-weight: 700;
        margin-bottom: 8px;
    }

    .flow-bullets,
    .note-list {
        margin: 0;
        padding-left: 18px;
        color: var(--color-text);
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .flow-bullets li + li,
    .note-list li + li {
        margin-top: 8px;
    }

    .module-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .module-card {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .module-meta {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .module-block {
        padding: 12px;
        border-radius: var(--radius-sm);
        background: var(--color-surface-alt);
        border: 1px solid var(--color-border);
        overflow: hidden;
    }

    .module-block-title {
        margin-bottom: 8px;
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--color-text-muted);
        font-weight: 700;
    }

    .module-list {
        margin: 0;
        padding-left: 16px;
        color: var(--color-text);
        font-size: 0.92rem;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .module-list li + li {
        margin-top: 6px;
    }

    .mini-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: var(--space-3);
    }

    @media (max-width: 1100px) {
        .flow-columns,
        .module-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 1200px) {
        .architecture-summary-grid,
        .pipeline-grid,
        .mini-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .flow-columns,
        .flow-meta-grid,
        .module-meta {
            grid-template-columns: 1fr;
        }

        .flow-stage {
            grid-template-columns: 1fr;
        }

        .flow-stage-index {
            min-height: 58px;
        }
    }

    @media (max-width: 768px) {
        .architecture-summary-grid,
        .pipeline-grid,
        .module-grid,
        .mini-grid {
            grid-template-columns: 1fr;
        }

        .architecture-page > .card,
        .summary-card,
        .pipeline-card,
        .module-card,
        .mini-card {
            padding: var(--space-3);
        }

        .flow-stage-body {
            padding: var(--space-3);
        }

        .chip {
            width: 100%;
            border-radius: var(--radius-sm);
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('analytics/activity-logs') ?>">Activity Logs</a>
<a class="btn btn-outline" href="<?= site_url('analytics/dashboard') ?>">Analytics Dashboard</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="architecture-page">
    <section class="card architecture-hero stack-md">
        <div class="stack-sm">
            <p class="top-kicker">Internal Reference</p>
            <h2>How the implemented system is wired today</h2>
            <p class="page-subtitle">
                This page describes the actual running architecture in the current repository: the module boundaries, the request-processing pipeline,
                and the end-to-end flow from authentication through procurement, receiving, inventory, issuance, reporting, analytics, and audit logging.
            </p>
        </div>

        <div class="architecture-summary-grid">
            <article class="summary-card">
                <div class="summary-value"><?= esc((string) count($moduleCards)) ?></div>
                <div class="summary-label">Major Modules</div>
            </article>
            <article class="summary-card">
                <div class="summary-value"><?= esc((string) count($systemFlow)) ?></div>
                <div class="summary-label">Flow Stages</div>
            </article>
            <article class="summary-card">
                <div class="summary-value"><?= esc((string) count($requestPipeline)) ?></div>
                <div class="summary-label">Runtime Layers</div>
            </article>
            <article class="summary-card">
                <div class="summary-value">CI4 + Shield</div>
                <div class="summary-label">Platform</div>
            </article>
        </div>

        <div class="status-callout status-callout-info">
            <strong>Implementation note:</strong>
            The current operational workflow uses transactional tables built around <code>item_name</code>, <code>unit</code>, and <code>supplier_name</code>.
            The richer product and supplier catalog described in project docs is not yet the runtime source of truth.
        </div>
    </section>

    <section class="card stack-md">
        <div class="stack-sm">
            <h2>Common Request Pipeline</h2>
            <p class="page-subtitle">Every business module uses the same high-level path from browser request to database write.</p>
        </div>

        <div class="pipeline-grid">
            <?php foreach ($requestPipeline as $step): ?>
                <article class="pipeline-card stack-sm">
                    <span class="step-no"><?= esc($step['step']) ?></span>
                    <h3><?= esc($step['title']) ?></h3>
                    <p class="muted"><?= esc($step['summary']) ?></p>
                    <ul class="module-list">
                        <?php foreach ($step['points'] as $point): ?>
                            <li><?= esc($point) ?></li>
                        <?php endforeach ?>
                    </ul>
                </article>
            <?php endforeach ?>
        </div>
    </section>

    <section class="card stack-md">
        <div class="stack-sm">
            <h2>Detailed System Flow</h2>
            <p class="page-subtitle">This is the main business path through the application, including the handoff points between modules.</p>
        </div>

        <div class="flow-list">
            <?php foreach ($systemFlow as $stage): ?>
                <article class="flow-stage">
                    <div class="flow-stage-index"><?= esc($stage['step']) ?></div>
                    <div class="flow-stage-body">
                        <div class="stack-sm">
                            <h3><?= esc($stage['title']) ?></h3>
                            <p class="muted"><?= esc($stage['summary']) ?></p>
                        </div>

                        <div class="flow-columns">
                            <div class="stack-sm">
                                <div class="section-label">What Happens</div>
                                <ul class="flow-bullets">
                                    <?php foreach ($stage['happens'] as $item): ?>
                                        <li><?= esc($item) ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>

                            <div class="stack-sm">
                                <div class="flow-meta-grid">
                                    <div class="mini-card">
                                        <div class="section-label">Inputs</div>
                                        <div class="chip-list">
                                            <?php foreach ($stage['inputs'] as $input): ?>
                                                <span class="chip"><?= esc($input) ?></span>
                                            <?php endforeach ?>
                                        </div>
                                    </div>

                                    <div class="mini-card">
                                        <div class="section-label">Outputs</div>
                                        <div class="chip-list">
                                            <?php foreach ($stage['outputs'] as $output): ?>
                                                <span class="chip"><?= esc($output) ?></span>
                                            <?php endforeach ?>
                                        </div>
                                    </div>

                                    <div class="mini-card">
                                        <div class="section-label">Key Routes</div>
                                        <div class="chip-list">
                                            <?php foreach ($stage['routes'] as $route): ?>
                                                <span class="chip"><?= esc($route) ?></span>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach ?>
        </div>
    </section>

    <section class="card stack-md">
        <div class="stack-sm">
            <h2>Module Map</h2>
            <p class="page-subtitle">Each card shows the responsibility, main code areas, dependencies, and downstream handoff of a module.</p>
        </div>

        <div class="module-grid">
            <?php foreach ($moduleCards as $module): ?>
                <article class="module-card">
                    <div class="stack-sm">
                        <h3><?= esc($module['title']) ?></h3>
                        <p class="muted"><?= esc($module['purpose']) ?></p>
                    </div>

                    <div class="module-meta">
                        <div class="module-block">
                            <div class="module-block-title">Controllers</div>
                            <div class="chip-list">
                                <?php foreach ($module['controllers'] as $item): ?>
                                    <span class="chip"><?= esc($item) ?></span>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div class="module-block">
                            <div class="module-block-title">Services</div>
                            <div class="chip-list">
                                <?php foreach ($module['services'] as $item): ?>
                                    <span class="chip"><?= esc($item) ?></span>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div class="module-block">
                            <div class="module-block-title">Repositories</div>
                            <div class="chip-list">
                                <?php foreach ($module['repositories'] as $item): ?>
                                    <span class="chip"><?= esc($item) ?></span>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div class="module-block">
                            <div class="module-block-title">Main Tables</div>
                            <div class="chip-list">
                                <?php foreach ($module['tables'] as $item): ?>
                                    <span class="chip"><?= esc($item) ?></span>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>

                    <div class="mini-grid">
                        <div class="module-block">
                            <div class="module-block-title">Depends On</div>
                            <ul class="module-list">
                                <?php foreach ($module['depends_on'] as $item): ?>
                                    <li><?= esc($item) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>

                        <div class="module-block">
                            <div class="module-block-title">Feeds Into</div>
                            <ul class="module-list">
                                <?php foreach ($module['feeds_into'] as $item): ?>
                                    <li><?= esc($item) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </article>
            <?php endforeach ?>
        </div>
    </section>

    <section class="card stack-md">
        <div class="stack-sm">
            <h2>Critical Interconnections</h2>
            <p class="page-subtitle">These are the dependency edges that matter most when changing the system.</p>
        </div>

        <ul class="note-list">
            <?php foreach ($interconnections as $item): ?>
                <li><?= esc($item) ?></li>
            <?php endforeach ?>
        </ul>
    </section>

    <section class="card stack-md">
        <div class="stack-sm">
            <h2>Important Implementation Notes</h2>
            <p class="page-subtitle">Practical caveats that explain how the current codebase differs from a more idealized architecture.</p>
        </div>

        <ul class="note-list">
            <?php foreach ($implementationNotes as $item): ?>
                <li><?= esc($item) ?></li>
            <?php endforeach ?>
        </ul>
    </section>
</div>
<?= $this->endSection() ?>
