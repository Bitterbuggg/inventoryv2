# Architecture Overview
This document serves as a critical, living template designed to equip agents with a rapid and comprehensive understanding of the codebase's architecture, enabling efficient navigation and effective contribution from day one. Update this document as the codebase evolves.

## 1. Project Structure
This section provides a high-level overview of the project's directory and file structure, categorised by architectural layer or major functional area. It is essential for quickly navigating the codebase, locating relevant files, and understanding the overall organization and separation of concerns.

[Project Root]/
├── app/                            # Main CodeIgniter 4 application code
│   ├── Config/                     # App, routes, filters, and validation
│   ├── Controllers/                # HTTP controllers (lean; delegates to services)
│   │   ├── Admin/                  # Admin routes and dashboard controllers
│   │   ├── Analytics/              # Activity logs, metrics aliases, architecture reference
│   │   ├── Auth/                   # Login and logout
│   │   ├── Inventory/              # Issuance, reporting, and stock visibility controllers
│   │   ├── Procurement/            # Purchase request, approval, PO, and PO request controllers
│   │   └── Receiving/              # Receiving conversion and inventory quantity controllers
│   ├── Database/
│   │   ├── Migrations/             # Database schema definitions
│   │   └── Seeds/                  # Seeders for roles, users, and sample data
│   ├── Entities/                   # Domain entities (optional but recommended in CI4)
│   ├── Filters/                    # Auth, permission, role, and multi-session route guards
│   ├── Helpers/                    # Shared helper functions
│   ├── Models/                     # Direct data models for core tables
│   ├── Repositories/               # Data access abstraction layer
│   │   ├── Contracts/              # Repository interfaces
│   │   └── EloquentLike/           # Concrete repository implementations
│   ├── Services/                   # Business logic layer
│   │   ├── Admin/                  # User management and admin operations
│   │   ├── Analytics/              # Event logging and analytics aggregation
│   │   ├── Auth/                   # Authentication and account provisioning logic
│   │   ├── Catalog/                # Product and supplier master data management
│   │   ├── Inventory/              # Stock reporting and export services
│   │   │   └── Reports/            # Read models for stock balance, movements, issuance, trends
│   │   ├── Procurement/            # PR, approval, PO workflow logic and presenters
│   │   ├── Receiving/              # Receiving conversion, inventory quantity, and stock movement logic
│   │   └── Shared/                 # Cross-module services (approval workflow, etc.)
│   ├── Validation/                 # Custom validation rules
│   └── Views/                      # HTML/CSS views (server-rendered)
│       ├── admin/                  # Dashboard, user management, product/supplier catalogs
│       ├── analytics/              # Activity logs and system architecture reference
│       ├── auth/                   # Login page
│       ├── inventory/              # Stock reports, issuance, low stock alerts
│       ├── procurement/            # Purchase requests, approval queues, purchase orders
│       ├── receiving/              # Receiving conversion and stock intake views
│       └── layouts/                # Shared layout templates
├── docs/                           # Architecture, process, schema, and rollout references
│   ├── Architecture.md             # Living architecture reference
│   ├── AUTH_RBAC_MODULE_ARCHITECTURE.md
│   ├── PROCUREMENT_MODULE_ARCHITECTURE.md
│   ├── RECEIVING_INVENTORY_MODULE_ARCHITECTURE.md
│   ├── ISSUANCE_REPORTING_MODULE_ARCHITECTURE.md
│   └── FRONTEND_DESIGN_ANALYTICS_ARCHITECTURE.md
├── public/                         # Web root (index.php, static assets)
│   └── assets/                     # CSS, JS, images
│       ├── css/
│       │   ├── procurement-queue.css           # Procurement workflow UI styling
│       │   ├── table-density.css               # Shared dense-table widths and column sizing
│       │   ├── purchase-request-form.css       # Purchase request form styling
│       │   └── [other stylesheets]
│       └── js/
│           ├── procurement-queue.js            # Procurement queue pagination, filtering, sorting
│           ├── purchase-request-form.js        # Dynamic form management and CSV import
│           └── [other scripts]
├── tests/                          # PHPUnit + CodeIgniter test suites
│   ├── unit/                       # Unit tests for services/repositories
│   │   ├── Services/
│   │   │   ├── Admin/UserManagementServiceTest.php
│   │   │   ├── Analytics/ActivityLogQueryServiceTest.php
│   │   │   ├── Analytics/AnalyticsExportPresenterTest.php
│   │   │   ├── Catalog/ProductServiceTest.php
│   │   │   ├── Catalog/SupplierServiceTest.php
│   │   │   ├── Inventory/ReportingServiceTest.php
│   │   │   ├── Inventory/Reports/ReportingExportPresenterTest.php
│   │   │   ├── Procurement/ProcurementExportPresenterTest.php
│   │   │   ├── Procurement/ProcurementListPresenterTest.php
│   │   │   ├── Receiving/InventoryQuantityServiceTest.php
│   │   │   ├── Receiving/ReceivingWorkflowContextServiceTest.php
│   │   │   ├── Receiving/StockMovementServiceTest.php
│   │   │   └── Shared/ApprovalWorkflowServiceTest.php
│   ├── integration/                # Integration tests for controllers + DB flow
│   │   ├── Analytics/              # Route guards and analytics access behavior
│   │   ├── Auth/                   # Auth, admin, and catalog management flows
│   │   ├── Inventory/              # Issuance and reporting integration coverage
│   │   ├── Procurement/            # PR, approval, PO, and PO request workflow coverage
│   │   └── Receiving/              # Receiving conversion, validation, and posting coverage
│   └── _support/                   # Shared test utilities/fixtures
├── writable/                       # Cache, logs, sessions, uploads
├── .env                            # Environment variables
├── composer.json                   # PHP dependencies
├── phpunit.xml.dist                # Test configuration
├── spark                           # CodeIgniter CLI entrypoint
└── README.md                       # Project overview and setup

## 2. High-Level System Diagram
Provide a simple block diagram (e.g., a C4 Model Level 1: System Context diagram, or a basic component diagram) or a clear text-based description of the major components and their interactions. Focus on how data flows, services communicate, and key architectural boundaries.

[Admin / Employee / IT Staff] <--> [CodeIgniter 4 Web App (Views + Controllers)]
                                            |
                                            v
                                  [Service Layer (Business Rules)]
                                            |
                                            v
                                [Repository Layer (Data Access)]
                                            |
                                            v
                                    [MySQL Database]

Core transaction flow:
[Product / Supplier Catalog] -> [Purchase Request] -> [Shared Approval] -> [Purchase Order] -> [PO Request] -> [Receiving Conversion + Validation] -> [Inventory Posting + Stock Movements] -> [Issuance + Shared Approval] -> [Reports / Analytics / Audit]

## 3. Core Components

### 3.0. Service Layer Design Patterns

The service layer implements several key patterns:

- **Composition Root**: `Config\RepositoryServices` statically wires repositories, read models, presenters, and services for controller consumption
- **Read Models**: Specialized database query classes optimized for specific reporting needs (e.g., `StockBalanceReportReadModel`, `FastMovingReportReadModel`)
- **Presenters**: Transform service output for specific contexts:
  - **List Presenters** (e.g., `ProcurementListPresenter`): Enrich domain data with display metadata (status labels, badges, nested details)
  - **Export Presenters** (e.g., `AnalyticsExportPresenter`, `ReportingExportPresenter`): Convert data to exportable formats (CSV rows with headers)
- **Workflow Context Services** (e.g., `ReceivingWorkflowContextService`): Build complex domain contexts by composing multiple repositories for multi-step workflows
- **Domain Services** (e.g., `ProductService`, `SupplierService`): Encapsulate business rules (validation, duplicate detection, normalization) with repository abstraction

All services follow dependency injection via constructor, use type hints for clarity, and throw domain-specific exceptions (`DomainException`, `InvalidArgumentException`) for error handling.

### 3.1. Frontend

Name: Server-Rendered Web App with Progressive Enhancement

Description: The main user interface for pharmacy users to log in, create purchase requests, approve requests, generate POs, manage catalogs, receive stock, update inventory quantities, and issue items. Built as server-rendered pages for fast initial delivery and simple deployment on XAMPP, with progressive JavaScript enhancement for dynamic workflows.

Key Components:
- **Admin Views**: Dashboard, user management, product/supplier catalog management
- **Procurement Views**: Purchase request creation/editing, approval queues, purchase order lists, PO request workflows  
- **Inventory Views**: Stock balance reports, movement history, issuance management, low stock alerts
- **Shared Layouts**: Navigation, header, sidebar, responsive grid system
- **Dynamic Forms**: Purchase request form with product selection, unit sync, CSV import capability
- **Procurement Queue**: Advanced table with sorting, filtering, pagination, and inline approval/rejection

Frontend Assets:
- **CSS**: 
  - `procurement-queue.css` - Responsive procurement queue styling with KPI cards, status badges, modal dialogs, and action-column alignment for purchase orders and PO requests
  - `table-density.css` - Shared dense-table baseline loaded by the main layout, including procurement queue column widths
  - `purchase-request-form.css` - Form layout for multi-row request items with product lookup
- **JavaScript**:
  - `procurement-queue.js` - Table pagination, sorting, filtering, KPI updates, modal management (257 lines, vanilla JS)
  - `purchase-request-form.js` - Dynamic form row management, product selection, CSV import parsing (151 lines, vanilla JS)

Technologies: PHP 8.x (CodeIgniter 4 Views), HTML5, CSS3, Vanilla JavaScript (no jQuery), Responsive Design

Deployment: Apache via XAMPP (local development and staging)

### 3.2. Backend Services

#### 3.2.1. Authentication and Access Control Service

Name: Auth & RBAC Service

Description: Handles login, logout, admin-managed account provisioning, session lifecycle, password hashing, role membership, granular ability checks, and tracked multi-session validation for `admin`, `employee`, and `it_staff`.

Key Components:
- `AuthenticationService`: Login orchestration, account creation helper, and password handling
- `AuthorizationService`: Permission-aware view helpers and authorization checks
- `PermissionFilter`: Ability-based route gating for operational modules
- `RoleFilter`: Admin-only route protection for dashboard and management areas
- `MultiSessionFilter`: Restores and validates tracked browser sessions
- `UserManagementService`: Admin user lifecycle, role assignment, and permission overrides

Technologies: PHP 8.x, CodeIgniter 4 (Filters, Sessions, Validation, Password Hashing)

Deployment: Runs inside the CodeIgniter 4 monolith on Apache/XAMPP

#### 3.2.2. Catalog Management Service

Name: Product & Supplier Catalog Service

Description: Manages master data for products and suppliers used throughout procurement, receiving, and inventory workflows. Handles creation, updates, and validation of product/supplier records with auto-generated codes and active/inactive status tracking. Service layer handles business logic; repository pattern abstracts data access.

Key Components:
- `ProductService`: Product CRUD, active/inactive filtering, duplicate detection, unit normalization
- `SupplierService`: Supplier CRUD, contact management, active/inactive filtering, duplicate name detection  
- UI Views: Admin dashboard for managing product and supplier catalogs

Technologies: PHP 8.x, CodeIgniter 4 Services + Repositories, Domain exceptions for validation

Deployment: Runs inside the CodeIgniter 4 monolith on Apache/XAMPP

#### 3.2.3. Procurement Workflow Service

Name: Purchase Lifecycle Service

Description: Orchestrates purchase request creation, approval workflow, purchase order generation, and PO request transitions while enforcing status rules and audit consistency. Includes presentation logic for rendering procurement queues and export capabilities.

Key Components:
- `ProcurementListPresenter`: Enriches procurement data with status labels, badges, and nested details for list views
- `ProcurementExportPresenter`: Transforms procurement records to CSV format for purchase requests, PO items, and PO requests
- Approval workflow service for request/PO approval state management

Technologies: PHP 8.x, CodeIgniter 4 Services + Repositories, MySQL transactions

Deployment: Runs inside the CodeIgniter 4 monolith on Apache/XAMPP

#### 3.2.4. Shared Approval Workflow Service

Name: Cross-Module Approval Orchestrator

Description: Centralized service for managing approval workflows across multiple modules (purchase requests, issuance, etc.). Handles approval creation, resolution (approve/reject), comment tracking, and approval state transitions. Used by procurement and inventory modules.

Key Components:
- `ApprovalWorkflowService`: Approval CRUD, pending-approval lookup, decision resolution, error handling
- Supports multiple approval levels and modules via configurable reference types

Technologies: PHP 8.x, CodeIgniter 4 Services + Repositories

Deployment: Runs inside the CodeIgniter 4 monolith on Apache/XAMPP

#### 3.2.5. Receiving and Inventory Movement Service

Name: Stock Intake & Issuance Service

Description: Converts approved PO requests to receiving records, updates inventory quantities, logs stock movements, and processes issuance with validation against available stock.

Key Components:
- `ReceivingWorkflowContextService`: Builds workflow context for conversion, draft editing, and posting of receivings; validates remaining quantities
- `InventoryQuantityService`: Manual stock adjustments with transaction support
- `StockMovementService`: Records movement history for all inventory transactions

Technologies: PHP 8.x, CodeIgniter 4 Services + Repositories, MySQL indexing

Deployment: Runs inside the CodeIgniter 4 monolith on Apache/XAMPP

#### 3.2.6. Inventory Reporting Service

Name: Inventory Reporting Engine

Description: Provides comprehensive inventory reporting with multiple report types (stock balance, movements, issuance, low stock, fast movers) and export capabilities. Uses read models for report-specific queries and `ReportingExportPresenter` for CSV shaping.

Key Components:
- **Read Models**:
  - `StockBalanceReportReadModel`: Stock on-hand and available quantities by item
  - `StockMovementReportReadModel`: Historical movement records with date/type filtering
  - `IssuanceReportReadModel`: Issuance transactions with status and date range filtering
  - `LowStockReportReadModel`: Items below threshold quantities
  - `FastMovingReportReadModel`: Most-issued items ranked by volume
- **Exporters**:
  - `ReportingExportPresenter`: Formats all report types to CSV with proper headers and value normalization

Technologies: PHP 8.x, CodeIgniter 4 Query Builder, CSV serialization

Deployment: Runs inside the CodeIgniter 4 monolith on Apache/XAMPP

#### 3.2.7. Activity Logging & Analytics Service

Name: Activity Logs and Internal Telemetry

Description: Captures controller-level system activity events, aggregates daily metrics, and powers the unified Activity Logs surface. The legacy `/analytics/dashboard`, `/analytics/events`, and `/analytics/metrics` routes now feed the same underlying screen and export pipeline.

Key Components:
- `AnalyticsService`: Writes controller-level telemetry into `analytics_events`
- `ActivityLogQueryService`: Builds complex view data combining dashboard summaries, event listings, trends, and daily metrics
- `AnalyticsExportPresenter`: Formats analytics events and metrics to CSV for export/reporting
- `AnalyticsController`: Serves the Activity Logs area, legacy alias routes, and the system architecture page
- Event filtering by module, date range, and metadata
- Daily metrics aggregation with dimension support

Technologies: PHP 8.x, CodeIgniter 4 Query Aggregation, JSON metadata storage

Deployment: Runs inside the CodeIgniter 4 monolith on Apache/XAMPP

#### 3.2.8. Audit Logging Service

Name: Business Audit Trail

Description: Stores service-level workflow transitions where the business-state change matters independently from page-view or controller telemetry. Receiving and issuance flows use this trail for traceability around approvals, posting, rejection, release, cancellation, and voiding.

Key Components:
- `AuditService`: Central write path for business audit entries
- `AuditLogRepository`: Repository abstraction over audit storage
- `AuditLogModel`: Direct table model for `audit_logs`
- Receiving and issuance services: Main producers of audit events

Technologies: PHP 8.x, CodeIgniter 4 Services + Repositories

Deployment: Runs inside the CodeIgniter 4 monolith on Apache/XAMPP

## 4. Data Stores

### 4.1. Relational Data Store

Name: Primary Pharmacy Inventory Database

Type: MySQL (InnoDB)

Purpose: Stores transactional and master data for users, roles, suppliers, products, purchase lifecycle, receiving, stock balances, movements, issuance history, audit trails, analytics, and tracked sessions.

Key Schemas/Collections: `users`, `roles`, `permissions`, `user_roles`, `multi_sessions`, `suppliers`, `products`, `purchase_requests`, `purchase_request_items`, `approvals`, `purchase_orders`, `purchase_order_items`, `po_requests`, `receivings`, `receiving_items`, `inventory_stocks`, `stock_movements`, `issuances`, `issuance_items`, `issuance_item_allocations`, `audit_logs`, `analytics_events`, `analytics_daily_metrics`

### 4.2. Cache and Session Store

Name: Application Cache and Session Storage

Type: CodeIgniter File Cache + File Session Driver (phase 1), optional Redis (future)

Purpose: Stores authenticated sessions, CSRF/session state, and cacheable lookup results to reduce repetitive database reads.

## 5. External Integrations / APIs

Current State: None. The application is intentionally self-contained for local development and on-prem deployment reliability.

Future Posture: External integrations remain deferred until the core inventory, receiving, and issuance workflows stabilize.

## 6. Deployment & Infrastructure

Cloud Provider: Local on-premise via XAMPP (development baseline)

Key Services Used: Apache HTTP Server, PHP 8.x, MySQL, Composer, CodeIgniter CLI (`spark`)

CI/CD Pipeline: GitHub Actions (recommended baseline: lint, unit tests, integration tests on pull requests)

Monitoring & Logging: CodeIgniter logs (`writable/logs`), Apache logs, MySQL slow query logs, audit trail tables

## 7. Security Considerations

Authentication: Session-based authentication with secure password hashing (`password_hash`), login validation, and session regeneration on login

Authorization: `RoleFilter` protects admin-only routes, `PermissionFilter` enforces ability-based access across operational modules, and sidebar visibility mirrors granted permissions for `admin`, `employee`, and `it_staff`

Data Encryption: TLS in transit outside local environments; password hashes at rest; secure handling of `.env` secrets

Key Security Tools/Practices: CSRF protection, strict input validation, output escaping in views, prepared queries/query builder, secure session cookies, audit logging for critical workflow changes

## 8. Development & Testing Environment

Local Setup Instructions: Install XAMPP + Composer, configure `.env`, run migrations with `php spark migrate`, seed auth and baseline permissions with `php spark db:seed AuthRbacSeeder`, optionally load sample catalog or workflow data with the sample seeders, then start Apache/MySQL and access via `http://localhost/inventoryv2/` (root front controller for XAMPP portability).

### 8.1. Testing Framework

Testing Frameworks: **PHPUnit** + **CodeIgniter 4** testing utilities (Unit + Integration)

Test Organization:
- **Unit Tests** (`tests/unit/`): Business logic validation for services, presenters, and repositories
  - `Services/Admin/UserManagementServiceTest.php` - User creation, role assignment, permission syncing
  - `Services/Analytics/ActivityLogQueryServiceTest.php` - Query building and view data assembly
  - `Services/Analytics/AnalyticsExportPresenterTest.php` - Event, trend, and metric CSV export
  - `Services/Catalog/ProductServiceTest.php` - Product CRUD validation, duplicate detection, inactive handling
  - `Services/Catalog/SupplierServiceTest.php` - Supplier CRUD, field normalization, optional contacts
  - `Services/Inventory/ReportingServiceTest.php` - Stock balance, movements, issuance, low stock, fast moving reports
  - `Services/Inventory/Reports/ReportingExportPresenterTest.php` - Report CSV formatting and filtering
  - `Services/Procurement/ProcurementExportPresenterTest.php` - PR, PO, and PO request CSV export
  - `Services/Procurement/ProcurementListPresenterTest.php` - List enrichment, status labeling, badge assignment
  - `Services/Receiving/InventoryQuantityServiceTest.php` - Manual adjustments, stock movement recording
  - `Services/Receiving/ReceivingWorkflowContextServiceTest.php` - Conversion context, draft editing, posting validation
  - `Services/Receiving/StockMovementServiceTest.php` - Movement history recording
  - `Services/Shared/ApprovalWorkflowServiceTest.php` - Approval creation, resolution, pending list filtering

- **Integration Tests** (`tests/integration/`): Full workflow validation with database and controllers
  - `Auth/CatalogManagementFlowTest.php` - Admin product and supplier CRUD workflows
  - `Auth/UserManagementFlowTest.php` - User creation with roles, permission overrides, profile updates
  - `Procurement/ProcurementWorkflowTest.php` - PR to PO and PO request lifecycle
  - `Receiving/ReceivingWorkflowTest.php` - Receiving draft, validation, posting, and void rules
  - `Inventory/IssuanceWorkflowTest.php` - Issuance draft, approval, release, and allocation flow
  - `Analytics/AnalyticsRouteGuardTest.php` - Analytics route authorization coverage

### 8.2. Code Quality Tools

Recommended Tools: PHP_CodeSniffer or PHP-CS-Fixer (style), PHPStan (static analysis, strict mode), CI pipeline test gates

Test Execution:
- Run all tests: `./vendor/bin/phpunit`
- Run specific test file: `./vendor/bin/phpunit tests/unit/Services/Catalog/ProductServiceTest.php`
- With coverage: `./vendor/bin/phpunit --coverage-html coverage/`

## 9. Future Considerations / Roadmap
- Introduce Redis for cache and session scaling under higher concurrent usage.
- Expand FEFO, lot, batch, and barcode support deeper into operational workflows.
- Add asynchronous notifications or queue-backed workflow follow-ups where retries matter.
- Continue building first-party on-prem analytics without external trackers.

## 10. Project Identification

Project Name: InventoryV2 Pharmacy Inventory System

Repository URL: Local repository (`c:\xampp\htdocs\inventoryv2`)

Primary Contact/Team: InventoryV2 Engineering Team

Date of Last Update: 2026-03-31

### 10.1. Recent Changes (2026-03-31)

Major Service Layer Expansions:
- Added `UserManagementService` to centralize admin-side account, role, and permission changes
- Added `ProductService` and `SupplierService` for catalog master data management
- Added `ActivityLogQueryService` and `AnalyticsExportPresenter` for comprehensive analytics and event auditing
- Added inventory reporting read models: `StockBalanceReportReadModel`, `StockMovementReportReadModel`, `IssuanceReportReadModel`, `LowStockReportReadModel`, `FastMovingReportReadModel`
- Added `ReportingExportPresenter` for multi-format report exports (CSV)
- Added `ProcurementListPresenter` and `ProcurementExportPresenter` for procurement queue enrichment and CSV export
- Added `ReceivingWorkflowContextService` for receiving conversion and workflow state management
- Added `ApprovalWorkflowService` as a shared cross-module approval orchestrator
- Added a shared `InventoryStockRepository` / `StockMovementRepository` path for receiving and issuance

Frontend & UI Enhancements:
- New admin catalog management views for products and suppliers
- Comprehensive procurement queue UI with sorting, filtering, pagination, KPI cards, and modal dialogs
- Dynamic purchase request form with product selection, unit sync, and CSV import
- Procurement queue tables now coordinate `table-density.css` and `procurement-queue.css` so purchase-order and PO-request action columns keep stable widths and avoid button wrapping
- CSS assets now include `procurement-queue.css` for workflow-specific queue styling, `purchase-request-form.css` for request entry, and `table-density.css` for shared dense-table column sizing
- New JavaScript modules: `procurement-queue.js` (257 lines), `purchase-request-form.js` (151 lines)

Routing, Security, and Data Shape Changes:
- Added `PermissionFilter` and ability-based route groups for procurement, receiving, inventory, reports, and analytics
- Unified legacy analytics dashboard, events, and metrics routes into the Activity Logs surface while keeping the old entry points as aliases
- Added `products` and `suppliers` tables plus `product_id` and `supplier_id` references across operational tables, backfilled from existing text snapshots

Comprehensive Test Suite:
- 23 unit service test files covering services, presenters, and business logic
- 15 integration test files validating route guards and end-to-end workflows

## 11. Glossary / Acronyms
Project-specific terms and acronyms:

PR: Purchase Request

PO: Purchase Order

RBAC: Role-Based Access Control

FEFO: First-Expired, First-Out inventory allocation method

GRN: Goods Received Note (receiving conversion record)
