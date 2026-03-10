# InventoryV2 Pharmacy System - Architecture Overview

## System Overview
**InventoryV2 Pharmacy System** is an internal pharmacy inventory and procurement management system for controlled stock operations, built as an on-premise web application. The system manages purchase requests, approvals, purchase orders, receiving, stock updates, and issuance workflows for internal users only.

**Technology Stack:**
- **Backend:** CodeIgniter 4 + Service & Repository Pattern
- **Frontend:** Server-rendered CodeIgniter Views (HTML/CSS/JS)
- **Database:** MySQL (InnoDB)
- **Authentication:** Session-based login/signup with role-based access
- **Deployment:** XAMPP on-premise (LAN/Intranet access)

**📋 Complete Database Schema:** [PHARMACY_DATABASE_SCHEMA.md](PHARMACY_DATABASE_SCHEMA.md) - foundational tables for procurement, receiving, inventory, and issuance with relationships and constraints

## Current State
- ✅ Base architecture defined with CodeIgniter MVC structure
- ✅ Authentication and role strategy documented (`admin`, `employee`, `IT dev/staff`)
- ✅ Core workflow documented from purchase request to issuance
- ✅ Service and repository layering planned for lean controllers
- ✅ Unit and integration testing strategy documented
- ✅ **Phase 1 implemented**: runnable CI4 baseline with Shield auth/RBAC, admin route guards, and passing unit/integration tests
- ✅ **Phase 2 implemented**: Procurement PR -> Approval -> PO -> PO Request workflow with repositories/services/controllers/views and passing tests
- ✅ **Phase 3 implemented**: Receiving conversion, quantity validation, stock posting, and inventory movement logging with passing tests

## Implementation Strategy

**Modular Development Approach:**
1. **Phase 1:** Foundation + Auth/RBAC Module - *Priority Implementation*
2. **Phase 2:** Procurement Module (PR, Approval, PO, PO Request) - *Implemented*
3. **Phase 3:** Receiving + Inventory Quantity Module - *Implemented*
4. **Phase 4:** Issuance + Reports Module - *Implemented*

**Core Architectural Principles:**
1. **Clean Architecture**: Service and repository patterns for maintainable backend
2. **Server-Rendered Frontend**: Fast delivery through CodeIgniter Views
3. **No Separate API Requirement**: Controller-to-view rendering for internal app workflows
4. **Role-Based Access**: Permission boundaries by user role and operation
5. **Modular Design**: Procurement, receiving, inventory, and issuance evolve independently

## Core Architecture Foundation

### Backend Architecture (CodeIgniter 4 + Service & Repository Pattern)

**Flow:** `Controllers -> Services -> Repositories -> Models -> Database`

**Key Components:**
- **Controllers**: Handle HTTP requests and delegate to services
- **Services**: Business rules, workflow transitions, transaction control
- **Repositories**: Data access abstraction and query optimization
- **Models**: CodeIgniter models for table interaction and entity mapping
- **Actions**: Single-purpose operations (state transition, document output, stock posting)

### Frontend Architecture (CodeIgniter Views - No Separate API)

**Flow:** `Routes -> Controllers -> Services -> View Data -> Views -> User Interface`

**Key Benefits:**
- ✅ Simple deployment on XAMPP/Apache
- ✅ Shared auth/session state across modules
- ✅ Lower complexity for internal operations
- ✅ Direct server-side validation feedback
- ✅ Faster initial implementation for workflow-heavy screens
- ✅ Easy progressive enhancement with JavaScript

### 📁 System Folder Structure
```text
app/
├── Config/
│   ├── App.php
│   ├── Routes.php
│   ├── Filters.php
│   └── Validation.php
│
├── Controllers/
│   ├── Admin/                     # Admin dashboard and management
│   ├── Auth/                      # Login, signup, logout
│   ├── Procurement/               # PR, approvals, PO, PO request
│   ├── Receiving/                 # Receiving conversion and intake
│   └── Inventory/                 # Inventory quantity and issuance
│
├── Database/
│   ├── Migrations/                # Schema and index definitions
│   └── Seeds/                     # Roles, users, baseline lookups
│
├── Models/
│   ├── Shared/                    # User, role, permission, lookup models
│   ├── Procurement/               # PR/PO-related models
│   ├── Receiving/                 # Receiving-related models
│   └── Inventory/                 # Stock and issuance models
│
├── Repositories/
│   ├── Contracts/
│   │   ├── Auth/                  # Auth repository interfaces
│   │   ├── Procurement/           # Procurement interfaces
│   │   ├── Receiving/             # Receiving interfaces
│   │   └── Inventory/             # Inventory interfaces
│   └── EloquentLike/
│       ├── Auth/                  # Auth repository implementations
│       ├── Procurement/           # Procurement implementations
│       ├── Receiving/             # Receiving implementations
│       └── Inventory/             # Inventory implementations
│
├── Services/
│   ├── Auth/                      # Authentication and session services
│   ├── Procurement/               # Workflow lifecycle services
│   ├── Receiving/                 # Conversion and stock intake services
│   ├── Inventory/                 # Quantity update and issuance services
│   └── Shared/                    # Cross-module services (Audit, Reporting)
│
├── Actions/
│   ├── Procurement/               # PR/PO-specific actions
│   ├── Receiving/                 # Receiving-specific actions
│   ├── Inventory/                 # Stock posting actions
│   └── Shared/                    # Cross-module actions
│
├── Filters/
│   ├── AuthFilter.php
│   └── RoleFilter.php
│
└── Providers/
    └── RepositoryServiceProvider.php
```

### Frontend Structure (CodeIgniter Views)
```text
app/Views/
├── auth/
│   ├── login.php
│   └── signup.php
├── admin/
│   └── dashboard.php
├── procurement/
│   ├── purchase_requests/
│   ├── approvals/
│   ├── purchase_orders/
│   └── po_requests/
├── receiving/
│   ├── index.php
│   └── conversion.php
├── inventory/
│   ├── quantities/
│   └── issuance/
├── components/
│   └── shared/                    # Reusable table/form fragments
└── layouts/
    ├── auth_layout.php
    └── main_layout.php
```

## Module Implementation Plans

Each module has its own detailed architectural plan and implementation guide:

### 📋 [Foundation + Auth/RBAC Module](AUTH_RBAC_MODULE_ARCHITECTURE.md) - **Implemented**
**Status:** Implemented (Phase 1 complete)  
**Timeline:** Completed in previous implementation cycle  
**Core Features:**
- Signup, login, logout, and secure session lifecycle
- Role assignment and role-protected route access
- Admin bootstrap and dashboard skeleton
- Validation and CSRF defaults enabled
- Baseline tests for auth and middleware

### ⏰ [Procurement Module](PROCUREMENT_MODULE_ARCHITECTURE.md) - **Implemented Baseline**
**Status:** Baseline implemented (Phase 2 complete)  
**Timeline:** Completed in current implementation cycle  
**Core Features:**
- Purchase request creation and itemization
- Approval workflow with status transitions
- Purchase order generation from approved requests
- PO request tracking and lifecycle enforcement
- Supplier linkage and procurement history

### 📦 [Receiving + Inventory Quantity Module](RECEIVING_INVENTORY_MODULE_ARCHITECTURE.md) - **Implemented Baseline**
**Status:** Baseline implemented (Phase 3 complete)  
**Timeline:** Completed in current implementation cycle  
**Core Features:**
- Receiving conversion from PO requests
- Received quantity validation and posting
- Inventory quantity update and stock movement logs
- Batch and expiry support readiness
- Stock reconciliation utilities

### 🚚 [Issuance + Reporting Module](ISSUANCE_REPORTING_MODULE_ARCHITECTURE.md) - **Implemented Baseline**
**Status:** Baseline implemented (Phase 4 complete)  
**Timeline:** 2-3 weeks after Receiving  
**Core Features:**
- Controlled issuance with stock checks
- Issuance records by department/requestor
- Daily and monthly stock movement reports
- Fast-moving and low-stock analytics
- Printable issuance summaries
### 🎨 [Frontend Design + Analytics Module](FRONTEND_DESIGN_ANALYTICS_ARCHITECTURE.md) - **Planned Enhancement**
**Status:** Planned  
**Timeline:** 2-4 weeks (iterative rollout)  
**Core Features:**
- Design system for consistent UI across modules
- Shared layouts/components for forms, tables, and alerts
- Accessibility and responsive baseline hardening
- Internal first-party analytics event tracking
- Analytics dashboard for operational usage insights


---

## Shared Foundation Components

### User-Role Relationship Strategy

**Approach**: Role-centric user access using `users`, `roles`, `permissions`, and `user_roles`

**Rationale**:
- All system access is granted through authenticated user accounts
- Role assignment controls what each user can view or execute
- Access boundaries are enforced at route and service layers
- Permission checks can be expanded without changing controller signatures
- Supports operational separation between admin, employee, and IT staff roles

**Benefits**:
- ✅ Clear access boundaries for sensitive stock operations
- ✅ Easier onboarding/offboarding via role assignment changes
- ✅ Consistent enforcement using shared filters and policies
- ✅ Better auditability of who performed each workflow action
- ✅ Flexible extension when new pharmacy roles are introduced

### Database Schema

**📋 Complete Schema Reference**: See [PHARMACY_DATABASE_SCHEMA.md](PHARMACY_DATABASE_SCHEMA.md) for full table definitions.

**Core Foundation Tables**:
- `users` - System authentication identities
- `roles` - Role catalog (`admin`, `employee`, `IT dev/staff`)
- `permissions` - Fine-grained operation permissions
- `user_roles` - User-to-role mapping
- `audit_logs` - Workflow and security audit trail

**Workflow Core Tables**:
- `suppliers`, `products`, `product_categories`, `units`
- `purchase_requests`, `purchase_request_items`, `approvals`
- `purchase_orders`, `purchase_order_items`, `po_requests`
- `receivings`, `receiving_items`
- `inventory_stocks`, `stock_movements`
- `issuances`, `issuance_items`

**Schema Overview**:
- **Total Tables**: 20+ core tables across foundation and inventory lifecycle
- **Foundation**: User, role, permission, and audit tables
- **Procurement**: PR, approval, PO, and PO request tables
- **Receiving**: Receiving header/detail and conversion links
- **Inventory**: Stock balance, movement, and issuance history

### Roles & Permissions

**Implementation**: Native role and permission tables with filter-based enforcement in CodeIgniter 4.

**Database Tables**: See [PHARMACY_DATABASE_SCHEMA.md](PHARMACY_DATABASE_SCHEMA.md) for complete role/permission definitions and mappings.

#### Proposed Roles:
- **Admin**: Full system access, user and configuration management
- **Employee**: PR creation, request tracking, and allowed issuance requests
- **IT Dev/Staff**: Technical maintenance, support operations, controlled admin actions

#### Proposed Permissions:
- **auth.manage_users**: Manage user accounts and roles
- **dashboard.view_admin**: Access admin dashboard
- **procurement.pr.create**: Create purchase requests
- **procurement.pr.approve**: Approve/reject purchase requests
- **procurement.po.create**: Create purchase orders
- **procurement.por.manage**: Manage PO request transitions
- **receiving.convert**: Convert PO requests to receiving records
- **inventory.quantity.update**: Post received inventory quantities
- **inventory.issuance.create**: Create issuance records
- **inventory.issuance.approve**: Approve controlled issuance
- **reports.view**: Access stock and movement reports
- **audit.view**: View workflow audit logs

## System-Wide Implementation Phases

### Phase A: Foundation Setup
**Timeline:** Completed in previous implementation cycle  
**Scope:** Core system
- [x] Set up migrations and seeds for auth + baseline lookups
- [x] Implement login/signup and session security controls
- [x] Configure role and permission middleware/filters
- [x] Implement repository contracts and base service classes
- [x] Create admin routes and dashboard skeleton

### Phase B: Procurement Module Implementation  
**Timeline:** 2-4 weeks  
**Scope:** PR to PO request workflow
- [x] Implement purchase request and item entry flows
- [x] Add approval lifecycle and transition guards
- [x] Generate purchase orders from approved requests
- [x] Add PO request tracking and state validation
- [x] Add procurement unit/integration tests

### Phase C: Receiving + Inventory Quantity Implementation
**Timeline:** 2-3 weeks  
**Scope:** Stock intake and balance updates
- [x] Implement receiving conversion from PO request
- [x] Add quantity validation and posting logic
- [x] Record stock movement history entries
- [x] Add reconciliation and mismatch handling paths
- [x] Add receiving/inventory tests

### Phase D: Issuance + Reporting Implementation
**Timeline:** Completed in current implementation cycle  
**Scope:** Controlled stock release and reporting
- [x] Implement issuance requests and stock checks
- [x] Add issuance approval and posting flow
- [x] Build stock balance and movement reports
- [x] Add low-stock and trend summaries
- [x] Add end-to-end workflow tests

## Technical Requirements

### Dependencies to Install
```bash
# Authentication and role management (recommended)
composer require codeigniter4/shield

# PDF generation for printable PR/PO/receiving/issuance forms (optional)
composer require dompdf/dompdf

# Development testing tools
composer require --dev phpunit/phpunit
composer require --dev phpstan/phpstan
```

### Architecture Principles
1. **Single Responsibility**: Each class handles one concern
2. **Dependency Injection**: Services and repositories resolved through DI
3. **Interface Segregation**: Focused repository contracts per domain
4. **Open/Closed**: Extend behavior with new services/actions, not controller rewrites
5. **Repository Pattern**: Encapsulate query logic away from controllers/services
6. **Service Pattern**: Centralize workflow and validation rules in services

### Naming Conventions
- **Models**: Singular, PascalCase (`PurchaseRequest`, `ReceivingItem`)
- **Controllers**: PascalCase with `Controller` suffix (`PurchaseOrderController`)
- **Services**: PascalCase with `Service` suffix (`ReceivingService`)
- **Repositories**: PascalCase with `Repository` suffix (`PurchaseRequestRepository`)
- **Interfaces**: PascalCase with `Interface` suffix (`InventoryRepositoryInterface`)
- **Actions**: PascalCase with `Action` suffix (`PostStockMovementAction`)

### Error Handling Strategy
- Use CodeIgniter exception handling for framework-level errors
- Create domain exceptions for invalid workflow transitions
- Repository layer throws data access exceptions with context
- Service layer translates technical errors to business-safe messages
- Controllers return HTTP responses with consistent error payload/view feedback

## Document Generation System

### Document Types
1. **Purchase Request Form** - Generated for procurement processing
2. **Purchase Order Document** - Generated after PR approval
3. **Receiving Report (GRN)** - Generated after stock intake posting
4. **Issuance Slip** - Generated for released inventory records

### PDF Generation Flow
1. Service calls `DocumentService`
2. `DocumentService` loads the correct template for document type
3. Template is populated with workflow and item data
4. PDF is generated using DomPDF (optional module)
5. File is stored under `writable/uploads/documents`
6. Database record is stored with metadata and reference IDs

## Testing Strategy

### Repository Tests
- Test query correctness and filtering logic
- Mock heavy dependencies where appropriate
- Verify indexed query usage on common filters
- Test not-found and invalid-ID edge cases

### Service Tests
- Mock repository dependencies
- Test workflow transition rules and guards
- Verify quantity math and stock movement outputs
- Test transaction rollback behavior on failures

### Integration Tests
- Test controller-to-database workflow paths
- Verify authentication, CSRF, and role filters
- Test PR-to-issuance happy path and failure paths
- Validate form submissions and error response consistency

## Security Considerations

### Access Control
- Role-based permissions (`admin`, `employee`, `IT dev/staff`)
- Route protection with auth and role filters
- Strict validation and CSRF protection on all forms
- Audit logging for status transitions and stock actions

### Data Protection
- Password hashing and secure session handling
- Input sanitization and output escaping
- Query builder/prepared statements to prevent SQL injection
- Environment secret protection in `.env`

## Performance Optimization

### Database
- Proper indexing on foreign keys and status/date filters
- Use transactions for multi-step workflow writes
- Optimize joins for procurement and stock movement reports
- Apply eager loading patterns where relationships are heavy

### Caching
- Cache role and permission maps
- Cache lookup tables (units, categories, suppliers)
- Use file cache initially; plan Redis for higher concurrency
- Cache expensive report aggregates with TTL invalidation

## Integration Strategy

### Cross-Module Data Flow
```text
Procurement Module -> Approved PO Request -> Receiving Module -> Inventory Quantity -> Issuance Module
        ↓                     ↓                    ↓                    ↓                    ↓
   PR and PO Data       Status Controls       Stock Intake        Stock Movement       Release Records
```

### Shared Services
- **AuditService**: Centralized activity logging for compliance
- **ReportingService**: Consolidated stock, movement, and trend reporting
- **DocumentService**: Shared printable workflow documents
- **ValidationService**: Reusable business rule validation helpers

### Frontend Integration (No API Mode)
- **Shared Layout**: Consistent navigation and module structure
- **Reusable Components**: Common form/table fragments across workflows
- **State Handling**: Request-response flow via server-rendered pages
- **Form Handling**: Unified validation feedback and submission patterns

---

## Getting Started

### Current Status
✅ **Foundation + Procurement + Receiving + Issuance/Reporting Ready**: Phases 1 to 4 are implemented and verified

### Next Steps
1. **Review Documentation**:  
   - **📋 [Architecture Reference](Architecture.md)** - baseline architecture and constraints
   - **[System Workflow Flowchart](Flowchart.md)** - role-based flow and end-to-end process diagrams
   - [Foundation + Auth/RBAC Module](AUTH_RBAC_MODULE_ARCHITECTURE.md) - first implementation target
   - [Procurement Module](PROCUREMENT_MODULE_ARCHITECTURE.md) - PR to PO lifecycle
   - [Receiving + Inventory Quantity Module](RECEIVING_INVENTORY_MODULE_ARCHITECTURE.md) - stock intake
   - [Issuance + Reporting Module](ISSUANCE_REPORTING_MODULE_ARCHITECTURE.md) - stock release and analytics

2. **Begin Implementation**: Run UAT, finalize deployment checklist, and harden operational monitoring

3. **Development Priority**: UAT execution, deployment handoff, and optional report-export endpoints

### Total Timeline Estimate
- **Foundation Setup**: 1-2 weeks
- **Procurement Module**: 2-4 weeks
- **Receiving + Inventory Quantity**: 2-3 weeks
- **Issuance + Reporting**: 2-3 weeks
- **Total**: ~2-3 months for core end-to-end workflow

### Dependencies
- ✅ **Current Baseline**: CodeIgniter 4 app with Foundation/Auth + Procurement + Receiving/Inventory modules implemented
- ✅ **Installed**: `codeigniter4/shield` for auth/RBAC implementation
- 🔄 **Optional Next**: PDF package and static analysis tooling hardening
- 📋 **To Plan**: Optional report export endpoints and production monitoring hardening

### Phase 1 Notes
- Auth implementation uses **CodeIgniter Shield** (session authenticator).
- Internal technical role key is **`it_staff`** with display label **“IT dev/staff”**.
- Admin route policy is enforced with `auth` + custom `role:admin` filter wrappers.


### Phase 2 Notes
- Procurement routes are implemented under `/procurement/*` with role guards.
- Workflow transitions are enforced in service layer (PR -> Approval -> PO -> PO Request).
- Draft PR edit/update routes and screen are implemented (`/procurement/purchase-requests/{id}/edit`, `/procurement/purchase-requests/{id}/update`).
- Duplicate PR item validation is enforced in service normalization.
- Added procurement unit and integration tests with full lifecycle coverage.



### Phase 3 Notes
- Receiving routes are implemented under `/receiving/*` with role guards.
- Inventory quantity views are available under `/inventory/quantities` and `/inventory/quantities/{id}`.
- Posting enforces quantity validation and writes `inventory_stocks` + `stock_movements` atomically.
- Receiving post/void workflows now write audit events (`receiving.posted`, `receiving.voided`) through shared audit service.
- Added targeted receiving tests for weighted-average cost posting and transaction rollback on posting failure.

### Phase 4 Notes
- Issuance routes are implemented under `/inventory/issuance/*` with role guards for create/submit/cancel (admin/employee/it_staff) and approve/reject/release (admin/it_staff).
- Reporting routes are implemented under `/reports/*` with role guards (admin/it_staff).
- Phase I4 hardening is complete: audit logs are persisted for issuance lifecycle events, high-volume reporting checks are covered in integration tests, and report-focused indexes are applied via migration.




