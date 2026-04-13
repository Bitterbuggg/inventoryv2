<?php

declare(strict_types=1);

$title = 'System Architecture - InventoryV2';
$pageTitle = 'System Architecture';
$pageSubtitle = 'Implemented module map, module flowcharts, role journeys, request pipeline, and end-to-end operational flow for the current application.';
$crumbs = [
    ['label' => 'Analytics'],
    ['label' => 'System Architecture'],
];

// ... [Keep ALL your existing PHP arrays ($requestPipeline, $systemFlow, $moduleCards, etc.) EXACTLY the same here] ...
// (I am omitting the PHP arrays in this block to save space, but DO NOT delete them from your actual file!)

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
        'summary' => 'Each request passes through authentication, permission or role checks, CSRF, and multi-session validation.',
        'points' => ['Shield session auth', 'PermissionFilter for ability-based access plus RoleFilter on admin-only areas', 'MultiSessionFilter to validate or switch tracked sessions'],
    ],
    [
        'step' => '03',
        'title' => 'Controllers',
        'summary' => 'Controllers validate input, call services, and render views or redirects.',
        'points' => ['Controllers delegate list and export presentation work to dedicated presenters where available', 'Analytics tracking emitted from actions', 'Shared layouts wrap all screens'],
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
        'points' => ['Procurement repositories', 'Shared stock-ledger repositories used by both receiving and issuance', 'Analytics and audit repositories'],
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
            'PermissionFilter or RoleFilter allows or rejects access based on the route ability or role requirement.',
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
            'UserController validates admin requests and delegates lifecycle changes to UserManagementService.',
            'UserManagementService creates accounts, edits users, assigns one primary role, and grants or revokes module permissions through UserRepository.',
            'The shared sidebar reads current roles and permissions to show or hide module navigation.',
            'This layer decides whether procurement, receiving, inventory, reports, and analytics appear for a user.',
        ],
        'inputs' => ['users', 'groups', 'permission selections'],
        'outputs' => ['role assignments', 'permission grants', 'module visibility'],
        'routes' => ['/admin/dashboard', '/admin/users'],
    ],
    [
        'step' => '03',
        'title' => 'Catalog Administration',
        'summary' => 'Admin-maintained product and supplier catalogs now provide the canonical references used by new operational records.',
        'happens' => [
            'ProductController and SupplierController expose admin CRUD screens for active and inactive catalog entries.',
            'ProductService normalizes unit values, prevents duplicate product and unit pairs, and exposes active products for request and issuance flows.',
            'SupplierService prevents duplicate supplier names and keeps contact fields normalized before they are used by purchasing records.',
            'The migrations backfilled product_id and supplier_id links from existing operational snapshots while preserving text columns.',
        ],
        'inputs' => ['product_name', 'unit', 'supplier_name', 'optional contact details', 'existing operational snapshot rows'],
        'outputs' => ['products rows', 'suppliers rows', 'backfilled product_id and supplier_id references'],
        'routes' => ['/admin/products', '/admin/suppliers'],
    ],
    [
        'step' => '04',
        'title' => 'Procurement Request Capture',
        'summary' => 'Operational purchasing now starts from the product catalog, which resolves the stored item name and base unit for each draft line.',
        'happens' => [
            'PurchaseRequestController creates a PR draft with request date, remarks, and line items.',
            'PurchaseRequestService enforces valid quantities and prevents duplicate item and unit pairs in the same PR.',
            'Submitting a PR changes status from draft to submitted and creates a pending approval record.',
        ],
        'inputs' => ['request date', 'remarks', 'product_id', 'resolved item_name and unit', 'requested_qty', 'estimated_unit_cost'],
        'outputs' => ['purchase_requests row', 'purchase_request_items rows', 'pending approvals row'],
        'routes' => ['/procurement/purchase-requests', '/procurement/purchase-requests/create'],
    ],
    [
        'step' => '05',
        'title' => 'Procurement Approval, Purchase Order, and PO Request',
        'summary' => 'Approved purchase requests are converted into orders, then into PO requests that are eligible for receiving.',
        'happens' => [
            'ApprovalService resolves purchase request approvals and updates PR status to approved or rejected.',
            'PurchaseOrderService converts approved PR items into purchase order items and marks the PR as converted_to_po.',
            'PurchaseOrderController issues the purchase order.',
            'PoRequestService creates and approves a PO request so the record becomes eligible for receiving conversion.',
        ],
        'inputs' => ['submitted purchase request', 'approval decision', 'supplier_id', 'resolved supplier_name', 'issued purchase order'],
        'outputs' => ['purchase_orders row', 'purchase_order_items rows', 'po_requests row'],
        'routes' => ['/procurement/approvals/pending', '/procurement/purchase-orders', '/procurement/po-requests'],
    ],
    [
        'step' => '06',
        'title' => 'Receiving Conversion and Draft Validation',
        'summary' => 'An approved PO request is converted into a receiving draft that captures accepted, rejected, and traceability quantities.',
        'happens' => [
            'ReceivingService loads remaining quantities from purchase_order_items and builds a conversion form.',
            'ReceivingWorkflowContextService now centralizes the draft and posting context checks around PO request state, purchase order linkage, and remaining receiving scope.',
            'ReceivingValidationService enforces whole numbers, accepted plus rejected equals received, non-past expiry, and no over-receipt.',
            'Creating the receiving draft moves the PO request into an intermediate converting status until posting or voiding.',
        ],
        'inputs' => ['approved po_request', 'purchase order item balances', 'received_qty', 'accepted_qty', 'rejected_qty', 'batch and lot and expiry'],
        'outputs' => ['receivings row', 'receiving_items rows', 'po_request status=converting'],
        'routes' => ['/receiving', '/receiving/create/from-po-request/{id}', '/receiving/{id}/validate'],
    ],
    [
        'step' => '07',
        'title' => 'Inventory Posting and Stock Ledger',
        'summary' => 'Posting a receiving writes accepted stock into inventory and creates inbound movement history.',
        'happens' => [
            'InventoryPostingService increments or creates inventory stock keyed by the resolved product plus item_name/unit plus batch plus lot plus expiry.',
            'Weighted average unit cost is recalculated when stock already exists.',
            'StockMovementService writes inbound, stock-disposal, and issuance movements through one shared movement-number generator path.',
            'Purchase order lines update received_qty and the purchase order moves to partially_received or fully_received.',
            'InventoryQuantityService exposes the stock ledger and supports manual disposal through adjustment_out.',
        ],
        'inputs' => ['posted receiving draft', 'accepted quantities', 'current inventory stock rows'],
        'outputs' => ['inventory_stocks rows', 'stock_movements rows', 'updated purchase_order_items received_qty'],
        'routes' => ['/receiving/{id}/post', '/inventory/quantities', '/inventory/quantities/{id}'],
    ],
    [
        'step' => '08',
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
        'step' => '09',
        'title' => 'Reporting, Audit, and Analytics',
        'summary' => 'The final layer reads the operational trail to explain what happened, who did it, and how often it occurs.',
        'happens' => [
            'ReportingService reads stock balances, movement history, issuance summaries, low-stock rows, and fast-moving items directly from transactional tables.',
            'AnalyticsController and AnalyticsService record controller-level telemetry into analytics_events, aggregate daily metrics, and route the legacy dashboard/events/metrics URLs into the unified Activity Logs surface.',
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
        'purpose' => 'Owns login, logout, admin-managed account creation, group assignment, and protected route entry.',
        'controllers' => ['Auth\\LoginController', 'Auth\\LogoutController', 'Admin\\UserController'],
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
        'services' => ['UserManagementService', 'AuthenticationService'],
        'repositories' => ['UserRepository'],
        'tables' => ['users', 'Shield auth tables'],
        'depends_on' => ['Auth and RBAC'],
        'feeds_into' => ['Catalog Management', 'Route visibility and permission checks across all modules'],
    ],
    [
        'title' => 'Catalog Management',
        'purpose' => 'Maintains product and supplier master data and supplies canonical catalog references for workflows.',
        'controllers' => ['Admin\\ProductController', 'Admin\\SupplierController'],
        'services' => ['ProductService', 'SupplierService'],
        'repositories' => ['ProductRepository', 'SupplierRepository'],
        'tables' => ['products', 'suppliers'],
        'depends_on' => ['Auth and RBAC', 'Admin and User Management'],
        'feeds_into' => ['Procurement', 'Receiving', 'Inventory Stock Ledger', 'Issuance'],
    ],
    [
        'title' => 'Procurement',
        'purpose' => 'Handles purchase requests, approvals, purchase orders, and PO requests.',
        'controllers' => ['Procurement\\PurchaseRequestController', 'Procurement\\PurchaseApprovalController', 'Procurement\\PurchaseOrderController', 'Procurement\\PoRequestController'],
        'services' => ['PurchaseRequestService', 'ApprovalService', 'PurchaseOrderService', 'PoRequestService', 'ProcurementListPresenter', 'ProcurementExportPresenter'],
        'repositories' => ['PurchaseRequestRepository', 'ApprovalRepository', 'PurchaseOrderRepository', 'PoRequestRepository'],
        'tables' => ['purchase_requests', 'purchase_request_items', 'approvals', 'purchase_orders', 'purchase_order_items', 'po_requests'],
        'depends_on' => ['Auth and RBAC', 'Catalog Management'],
        'feeds_into' => ['Receiving'],
    ],
    [
        'title' => 'Shared Approval Workflow',
        'purpose' => 'Provides reusable pending-approval creation and resolution rules for procurement and issuance flows.',
        'controllers' => ['Indirect only'],
        'services' => ['ApprovalWorkflowService'],
        'repositories' => ['ApprovalRepository'],
        'tables' => ['approvals'],
        'depends_on' => ['Foundation and Runtime'],
        'feeds_into' => ['Procurement', 'Issuance'],
    ],
    [
        'title' => 'Receiving',
        'purpose' => 'Converts approved PO requests into receivings, validates item lines, and prepares them for posting.',
        'controllers' => ['Receiving\\ReceivingController'],
        'services' => ['ReceivingService', 'ReceivingWorkflowContextService', 'ReceivingValidationService'],
        'repositories' => ['ReceivingRepository', 'ReceivingItemRepository', 'PoRequestRepository', 'PurchaseOrderRepository'],
        'tables' => ['receivings', 'receiving_items', 'po_requests', 'purchase_order_items'],
        'depends_on' => ['Procurement', 'Catalog Management'],
        'feeds_into' => ['Inventory Stock Ledger'],
    ],
    [
        'title' => 'Inventory Stock Ledger',
        'purpose' => 'Stores on-hand, reserved, and available stock plus movement history and manual adjustment-out records.',
        'controllers' => ['Receiving\\InventoryQuantityController'],
        'services' => ['InventoryPostingService', 'InventoryQuantityService', 'StockMovementService'],
        'repositories' => ['Inventory\\InventoryStockRepository', 'Inventory\\StockMovementRepository'],
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
        'services' => ['ReportingService', 'StockBalanceReportReadModel', 'StockMovementReportReadModel', 'IssuanceReportReadModel', 'LowStockReportReadModel', 'FastMovingReportReadModel', 'ReportingExportPresenter'],
        'repositories' => ['Report read models query transactional tables directly'],
        'tables' => ['inventory_stocks', 'stock_movements', 'issuances', 'issuance_items'],
        'depends_on' => ['Inventory Stock Ledger', 'Issuance'],
        'feeds_into' => ['Operational monitoring and exports'],
    ],
    [
        'title' => 'Analytics & Internal Telemetry',
        'purpose' => 'Captures controller-level events, aggregates daily metrics, and powers the unified Activity Logs area.',
        'controllers' => ['Analytics\\AnalyticsController'],
        'services' => ['AnalyticsService', 'ActivityLogQueryService', 'AnalyticsExportPresenter'],
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

$roleJourneys = [
    [
        'role' => 'Admin',
        'summary' => 'Full control over user access, catalog maintenance, procurement approvals, stock release decisions, reports, and internal analytics.',
        'access' => ['Admin', 'Catalog', 'Procurement', 'Receiving', 'Inventory', 'Reports', 'Analytics'],
        'boundary' => 'The default admin role has full operational coverage, while granular permission overrides can delegate specific actions to other users.',
        'flow' => [
            'Log in and land on the admin or operations workspace.',
            'Create users, assign base roles, and manage module permissions.',
            'Maintain products and suppliers so downstream workflows use current catalog records.',
            'Approve purchase requests and issue purchase orders.',
            'Approve PO requests so receiving can be converted and posted.',
            'Approve and release issuance requests after stock review.',
            'Review reports, activity logs, and architecture references.',
        ],
    ],
    [
        'role' => 'IT Staff',
        'summary' => 'Default operational support role focused on purchase-request approvals, receiving, inventory monitoring, reports, and analytics.',
        'access' => ['Procurement', 'Receiving', 'Inventory', 'Reports', 'Analytics'],
        'boundary' => 'The default IT staff role can approve purchase requests and operate receiving and inventory review flows, but PO-request management and issuance release still require explicit extra permissions.',
        'flow' => [
            'Log in and open procurement or receiving work queues.',
            'Review submitted purchase requests and approve or reject them.',
            'Monitor purchase orders and wait for a user with PO-request approval permission to finalize receiving eligibility.',
            'Convert approved PO requests into receivings and validate line data.',
            'Post inventory updates, inspect stock balances, and handle quantity review.',
            'Use reports and activity logs to monitor operations.',
        ],
    ],
    [
        'role' => 'Employee',
        'summary' => 'Request initiator role that creates procurement and issuance drafts and monitors inventory visibility.',
        'access' => ['Procurement', 'Inventory Quantities', 'Issuance Drafts'],
        'boundary' => 'Employees initiate requests and view stock by default. Approval, receiving, and release actions require explicit extra permissions.',
        'flow' => [
            'Log in and open the purchase request or issuance screens.',
            'Select active catalog products instead of typing free-form item records for new requests.',
            'Create or edit draft purchase requests and submit them for approval.',
            'Track request status while admin or IT staff handles the approval chain.',
            'View inventory quantities and movement history for reference.',
            'Create and submit issuance drafts when stock is needed.',
            'Wait for admin approval and release before items leave inventory.',
        ],
    ],
];

$interconnections = [
    'The same role and permission rules control both route access and sidebar navigation visibility.',
    'Catalog management provides canonical product and supplier references, while transactional tables keep text snapshots for historical display and exports.',
    'Procurement creates the records that receiving needs. Receiving cannot start until a PO request is approved.',
    'Receiving posting is the point where ordered items become real stock in inventory_stocks and stock_movements.',
    'Issuance never creates stock. It only consumes available stock and records outbound movement plus allocation detail.',
    'ApprovalWorkflowService is shared by procurement and issuance so both modules reuse the same pending-approval creation and resolution rules.',
    'Reports read the transactional truth directly from stock, movement, and issuance tables rather than from a separate warehouse.',
    'Analytics records controller-level user activity, while audit logs record business-state transitions inside services.',
];

$implementationNotes = [
    'The implemented application now includes product and supplier master catalogs that back new procurement and issuance records.',
    'The migrations created products and suppliers, then backfilled product_id and supplier_id references across the DB.',
    'RepositoryServices remains the central dependency registry, and admin user-management logic now runs through a dedicated UserManagementService.',
    'Receiving and issuance now share the same inventory stock and stock movement repository implementations.',
    'Receiving draft conversion, draft validation, and posting now reuse ReceivingWorkflowContextService.',
    'ReportingService now composes focused report read models for stock balance, stock movements, issuances, low stock, and fast-moving analysis.',
    'ReportingController now delegates CSV filename, header, row-shaping, and stock-movement label translation to ReportingExportPresenter.',
    'AnalyticsController now delegates activity-log dataset assembly to ActivityLogQueryService and CSV dataset shaping to AnalyticsExportPresenter.',
    'Purchase request and issuance submission and approval resolution now share ApprovalWorkflowService.',
    'StockMovementService now centralizes movement-number generation and write-shaping for receiving, manual stock disposal, and issuance release.',
    'Procurement and issuance controllers now obtain catalog-backed form options through their own workflow services.',
    'PurchaseOrderService now owns the purchase-order index decoration for linked PO request status.',
    'Duplicate purchase-order prevention now stays inside PurchaseOrderService as the single source of truth.',
    'Procurement controllers now delegate approval-list enrichment, procurement status-label presentation, and CSV payload shaping to presenters.',
    'Controller-level analytics and service-level audit logging are intentionally separate pipelines.',
    'Admin user creation now uses only real base roles. Granular permission overrides are still available.'
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- V2 DESIGN SYSTEM VARIABLES --- */
    :root {
        --v2-border: #cbd5e1; 
        --v2-title: #0f172a;  
        --v2-label: #0284c7;  
        --v2-active-bg: #0369a1; 
        --v2-text-main: #334155; 
        --v2-text-muted: #64748b;
        --v2-bg-main: #f8fafc;
    }

    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        min-height: 800px;
        padding-bottom: 40px;
    }

    /* --- TABS --- */
    .section-tabs { 
        display: flex; gap: 8px; border-bottom: 1px solid var(--v2-border); background: transparent; padding: 0; flex-shrink: 0;
    }
    .section-tab { 
        padding: 12px 24px; font-size: 0.85rem; font-weight: 800; color: var(--v2-text-muted); background: #ffffff; border: 1px solid var(--v2-border); border-bottom: none; border-radius: 8px 8px 0 0; cursor: pointer; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: -1px;
    }
    .section-tab:hover { color: var(--v2-label); background: #f0f9ff; }
    .section-tab.active { color: var(--v2-label); border-bottom: 2px solid var(--v2-label); background: #ffffff; z-index: 2; position: relative;}
    
    .tab-panel { display: none; flex-direction: column; gap: 20px; }
    .tab-panel.active { display: flex; }

    /* --- KPI CARDS --- */
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; flex-shrink: 0; }
    .kpi-card { background: #ffffff; border: 1px solid var(--v2-border); border-radius: 10px; padding: 16px; display: flex; align-items: center; gap: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .kpi-icon-box { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    
    .icon-slate { background: #f1f5f9; color: #475569; }        
    .icon-teal { background: #f0fdfa; color: #0d9488; } 
    .icon-blue { background: #e0f2fe; color: #0284c7; }   
    .icon-purple { background: #f5f3ff; color: #8b5cf6; }   
    .icon-amber { background: #fffbeb; color: #d97706; }

    .kpi-details { display: flex; flex-direction: column; justify-content: center; }
    .kpi-value { font-size: 1.5rem; font-weight: 900; color: var(--v2-title); line-height: 1; margin: 0 0 4px 0; }
    .kpi-label { font-size: 0.75rem; font-weight: 700; color: var(--v2-text-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.05em; }

    /* --- V2 CARDS --- */
    .data-card {
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 10px; 
        display: flex;
        flex-direction: column;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05); 
        overflow: hidden;
    }

    .card-header { padding: 16px 20px; border-bottom: 1px solid var(--v2-border); background: #ffffff; display: flex; flex-direction: column; gap: 4px; }
    .card-header h3 { margin: 0; font-size: 1.1rem; color: var(--v2-title); font-weight: 800; }
    .card-header p { margin: 0; font-size: 0.85rem; color: var(--v2-text-muted); line-height: 1.5; max-width: 80ch; }
    
    .card-body { padding: 20px; }

    /* --- CHIPS & BADGES --- */
    .meta-chip-list { display: flex; flex-wrap: wrap; gap: 6px; }
    .meta-chip { 
        display: inline-flex; align-items: center; 
        padding: 4px 8px; border-radius: 4px; 
        background: #f1f5f9; color: var(--v2-text-main); 
        border: 1px solid #e2e8f0; font-size: 0.75rem; 
        font-weight: 700; font-family: var(--font-mono); 
    }
    
    .role-badge {
        display: inline-flex; padding: 4px 10px; border-radius: 6px; 
        background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;
        font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; width: fit-content;
    }

    /* --- HIGH-SCANNABILITY LIST STYLING --- */
    .v2-list { margin: 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 10px; } /* Increased gap */
    .v2-list li { 
        position: relative; padding-left: 16px; font-size: 0.85rem; color: var(--v2-text-main); line-height: 1.6; /* Better line height */
        max-width: 80ch; /* Prevents long lines of text */
    }
    .v2-list li::before {
        content: '•'; position: absolute; left: 0; top: 0; color: var(--v2-label); font-weight: 900;
    }
    
    .v2-list.numbered { counter-reset: custom-counter; }
    .v2-list.numbered li { padding-left: 24px; }
    .v2-list.numbered li::before {
        counter-increment: custom-counter;
        content: counter(custom-counter) ".";
        color: var(--v2-label); font-weight: 900; font-size: 0.85rem;
    }

    /* Use bolding to extract keywords for easy scanning */
    .scannable-keyword { color: var(--v2-title); font-weight: 800; margin-right: 4px;}

    /* --- GRID LAYOUTS --- */
    .pipeline-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; }
    .module-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 16px; }
    .role-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; }

    /* Widgets inside grids */
    .widget-card {
        background: #f8fafc; /* Subtle focus background */
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        background: #e0f2fe; color: #0284c7;
        font-weight: 900; font-size: 0.85rem; 
        width: 32px; height: 32px; border-radius: 8px;
        margin-bottom: 4px;
    }

    .widget-card h4 { margin: 0; font-size: 1rem; color: var(--v2-title); font-weight: 800; }
    .widget-card p.muted { margin: 0; font-size: 0.85rem; color: var(--v2-text-muted); line-height: 1.5; }
    .meta-title { font-size: 0.7rem; font-weight: 800; color: var(--v2-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }

    /* --- DETAILED FLOW LAYOUT --- */
    .flow-stage {
        display: flex; gap: 20px; padding: 24px 20px; border-bottom: 1px solid var(--v2-border);
    }
    .flow-stage:last-child { border-bottom: none; }
    
    .flow-number { width: 40px; flex-shrink: 0; }
    .flow-content { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 16px; }
    
    .flow-meta-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; }

    /* Callout */
    .callout { background: #f0f9ff; border-left: 4px solid var(--v2-label); padding: 16px; border-radius: 0 8px 8px 0; margin-bottom: 20px;}
    .callout p { margin: 0; font-size: 0.85rem; color: var(--v2-text-main); line-height: 1.5; }

    @media (max-width: 768px) {
        .flow-stage { flex-direction: column; gap: 12px; padding: 16px; }
        .flow-number { width: auto; }
        .flow-meta-grid { grid-template-columns: 1fr; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <p style="margin: 0 0 4px 0; font-size: 0.75rem; font-weight: 800; color: var(--v2-label); text-transform: uppercase; letter-spacing: 0.05em;">Internal Reference</p>
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">System Architecture</h2>
        <p style="margin: 8px 0 0 0; font-size: 0.85rem; color: var(--v2-text-muted); max-width: 80ch; line-height: 1.5;">This page describes the running architecture in the current repository: module boundaries, the request pipeline, and the end-to-end flow from authentication through procurement, inventory, and analytics.</p>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card">
                <div class="kpi-icon-box icon-purple"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) count($moduleCards)) ?></p>
                    <p class="kpi-label">Major Modules</p>
                </div>
            </article>
            <article class="kpi-card">
                <div class="kpi-icon-box icon-stages"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) count($systemFlow)) ?></p>
                    <p class="kpi-label">Flow Stages</p>
                </div>
            </article>
            <article class="kpi-card">
                <div class="kpi-icon-box icon-layers"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) count($requestPipeline)) ?></p>
                    <p class="kpi-label">Runtime Layers</p>
                </div>
            </article>
            <article class="kpi-card">
                <div class="kpi-icon-box icon-platform"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value" style="font-size: 1rem;">CI4 + SHIELD</p>
                    <p class="kpi-label">Platform</p>
                </div>
            </article>
        </div>
    </section>

    <nav class="section-tabs" role="tablist">
        <button class="section-tab active" data-tab="pipeline">Core Pipeline</button>
        <button class="section-tab" data-tab="modules">Module Map</button>
        <button class="section-tab" data-tab="flow">End-to-End Flow</button>
        <button class="section-tab" data-tab="roles">Roles & Notes</button>
    </nav>

    <div class="tab-panel active" data-tab="pipeline">
        <div class="callout">
            <p><strong>Implementation note:</strong> The current operational workflow resolves new transactions through product and supplier catalogs. Transactional tables still keep <code>item_name</code>, <code>unit</code>, and <code>supplier_name</code> snapshots for compatibility, display, and reporting.</p>
        </div>

        <section class="data-card">
            <div class="card-header">
                <h3>Common Request Pipeline</h3>
                <p>Every business module uses the same high-level path from browser request to database write.</p>
            </div>
            <div class="card-body pipeline-grid">
                <?php foreach ($requestPipeline as $step): ?>
                    <div class="widget-card">
                        <div class="step-badge"><?= esc($step['step']) ?></div>
                        <h4><?= esc($step['title']) ?></h4>
                        <p class="muted"><?= esc($step['summary']) ?></p>
                        <ul class="v2-list" style="margin-top: 8px;">
                            <?php foreach ($step['points'] as $point): ?>
                                <li><?= esc($point) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endforeach ?>
            </div>
        </section>
    </div>

    <div class="tab-panel" data-tab="modules">
        <section class="data-card">
            <div class="card-header">
                <h3>Module Map</h3>
                <p>Responsibility, main code areas, dependencies, and downstream handoff of a module.</p>
            </div>
            <div class="card-body module-grid">
                <?php foreach ($moduleCards as $module): ?>
                    <div class="widget-card">
                        <h4><?= esc($module['title']) ?></h4>
                        <p class="muted"><?= esc($module['purpose']) ?></p>

                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-top: 8px;">
                            <div class="meta-title">Controllers</div>
                            <div class="meta-chip-list" style="margin-bottom: 12px;">
                                <?php foreach ($module['controllers'] as $item): ?>
                                    <span class="meta-chip"><?= esc($item) ?></span>
                                <?php endforeach ?>
                            </div>

                            <div class="meta-title">Services</div>
                            <div class="meta-chip-list" style="margin-bottom: 12px;">
                                <?php foreach ($module['services'] as $item): ?>
                                    <span class="meta-chip"><?= esc($item) ?></span>
                                <?php endforeach ?>
                            </div>

                            <div class="meta-title">Tables</div>
                            <div class="meta-chip-list">
                                <?php foreach ($module['tables'] as $item): ?>
                                    <span class="meta-chip"><?= esc($item) ?></span>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 8px;">
                            <div>
                                <div class="meta-title">Depends On</div>
                                <ul class="v2-list">
                                    <?php foreach ($module['depends_on'] as $item): ?>
                                        <li><?= esc($item) ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                            <div>
                                <div class="meta-title">Feeds Into</div>
                                <ul class="v2-list">
                                    <?php foreach ($module['feeds_into'] as $item): ?>
                                        <li><?= esc($item) ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </section>
    </div>

    <div class="tab-panel" data-tab="flow">
        <section class="data-card">
            <div class="card-header">
                <h3>Detailed System Flow</h3>
                <p>This is the main business path through the application, including the handoff points between modules.</p>
            </div>
            <div style="display: flex; flex-direction: column;">
                <?php foreach ($systemFlow as $stage): ?>
                    <div class="flow-stage">
                        <div class="flow-number">
                            <div class="step-badge" style="width: 40px; height: 40px; font-size: 1rem; border-radius: 8px;"><?= esc($stage['step']) ?></div>
                        </div>
                        <div class="flow-content">
                            <div>
                                <h4 style="margin: 0 0 6px 0; font-size: 1.15rem; color: var(--v2-title); font-weight: 800;"><?= esc($stage['title']) ?></h4>
                                <p style="margin: 0; font-size: 0.85rem; color: var(--v2-text-main); line-height: 1.5; max-width: 80ch;"><?= esc($stage['summary']) ?></p>
                            </div>
                            
                            <div>
                                <div class="meta-title">What Happens</div>
                                <ul class="v2-list numbered">
                                    <?php foreach ($stage['happens'] as $item): ?>
                                        <?php 
                                            // Split the sentence by the first space to bold the subject (e.g., "LoginController")
                                            $parts = explode(' ', $item, 2);
                                            $subject = $parts[0] ?? '';
                                            $rest = $parts[1] ?? '';
                                        ?>
                                        <li><span class="scannable-keyword"><?= esc($subject) ?></span> <?= esc($rest) ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>

                            <div class="flow-meta-grid">
                                <div>
                                    <div class="meta-title">Inputs</div>
                                    <div class="meta-chip-list">
                                        <?php foreach ($stage['inputs'] as $input): ?>
                                            <span class="meta-chip"><?= esc($input) ?></span>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                                <div>
                                    <div class="meta-title">Outputs</div>
                                    <div class="meta-chip-list">
                                        <?php foreach ($stage['outputs'] as $output): ?>
                                            <span class="meta-chip"><?= esc($output) ?></span>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                                <div>
                                    <div class="meta-title">Key Routes</div>
                                    <div class="meta-chip-list">
                                        <?php foreach ($stage['routes'] as $route): ?>
                                            <span class="meta-chip"><?= esc($route) ?></span>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </section>
    </div>

    <div class="tab-panel" data-tab="roles">
        <section class="data-card">
            <div class="card-header">
                <h3>Role-Based Sample Flows</h3>
                <p>These journeys reflect the actual route guards in the application.</p>
            </div>
            <div class="card-body role-grid">
                <?php foreach ($roleJourneys as $role): ?>
                    <div class="widget-card">
                        <span class="role-badge"><?= esc($role['role']) ?></span>
                        <p class="muted" style="margin-top: 8px;"><?= esc($role['summary']) ?></p>
                        
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-top: 8px;">
                            <div class="meta-title">Primary Access</div>
                            <div class="meta-chip-list">
                                <?php foreach ($role['access'] as $access): ?>
                                    <span class="meta-chip"><?= esc($access) ?></span>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div style="margin-top: 8px;">
                            <div class="meta-title">Typical Flow</div>
                            <ul class="v2-list numbered">
                                <?php foreach ($role['flow'] as $step): ?>
                                    <?php 
                                        $parts = explode(' ', $step, 2);
                                        $subject = $parts[0] ?? '';
                                        $rest = $parts[1] ?? '';
                                    ?>
                                    <li><span class="scannable-keyword"><?= esc($subject) ?></span> <?= esc($rest) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                        
                        <div style="margin-top: 8px; padding-top: 12px; border-top: 1px solid var(--v2-border);">
                            <div class="meta-title">Key Boundary</div>
                            <p style="margin:0; font-size:0.8rem; color: var(--v2-text-main); font-style: italic;"><?= esc($role['boundary']) ?></p>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </section>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
            <section class="data-card">
                <div class="card-header">
                    <h3>Critical Interconnections</h3>
                </div>
                <div class="card-body widget-card" style="margin: 20px; border: none;">
                    <ul class="v2-list">
                        <?php foreach ($interconnections as $item): ?>
                            <li style="padding-bottom: 8px; border-bottom: 1px solid #f1f5f9; margin-bottom: 8px;">
                                <?php 
                                    $parts = explode(' ', $item, 2);
                                    $subject = $parts[0] ?? '';
                                    $rest = $parts[1] ?? '';
                                ?>
                                <span class="scannable-keyword"><?= esc($subject) ?></span> <?= esc($rest) ?>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </section>

            <section class="data-card">
                <div class="card-header">
                    <h3>Implementation Caveats</h3>
                </div>
                <div class="card-body widget-card" style="margin: 20px; border: none;">
                    <ul class="v2-list">
                        <?php foreach ($implementationNotes as $item): ?>
                            <li style="padding-bottom: 8px; border-bottom: 1px solid #f1f5f9; margin-bottom: 8px;">
                                <?php 
                                    $parts = explode(' ', $item, 2);
                                    $subject = $parts[0] ?? '';
                                    $rest = $parts[1] ?? '';
                                ?>
                                <span class="scannable-keyword"><?= esc($subject) ?></span> <?= esc($rest) ?>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </section>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.section-tab');
    const panels = document.querySelectorAll('.tab-panel');
    
    function activateTab(name) {
        tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === name));
        panels.forEach(p => p.classList.toggle('active', p.dataset.tab === name));
        history.replaceState(null, '', '#' + name);
    }

    tabs.forEach(t => t.addEventListener('click', () => activateTab(t.dataset.tab)));
    
    const hash = window.location.hash.replace('#', '');
    if (['pipeline', 'modules', 'flow', 'roles'].includes(hash)) {
        activateTab(hash);
    }
});
</script>
<?= $this->endSection() ?>