# InventoryV2 System Process Guide

This document explains how the current system works end-to-end, with simple step-by-step flow and how each part connects to the others.

## 1. System Purpose and Stack

InventoryV2 is a CodeIgniter 4 web system for pharmacy operations with these connected modules:

- Authentication and RBAC
- Admin user/role management
- Procurement (PR -> approval -> PO -> PO Request)
- Receiving and inventory posting
- Inventory quantities and stock history
- Issuance (request -> approval -> release)
- Reports
- Analytics (events and daily metrics)
- Audit logging

Core stack:

- PHP 8.2+
- CodeIgniter 4.7
- Shield (session auth + groups)
- MySQL (production), SQLite memory (tests)

## 2. High-Level Architecture (How Parts Connect)

```text
Browser/UI
  -> Routes (app/Config/Routes.php)
  -> Filters (csrf + auth + role + required forcehttps)
  -> Controllers (app/Controllers/*)
  -> Services (business rules)
  -> Repositories (data access contracts + implementations)
  -> Models
  -> MySQL tables
  -> Response (views or redirects/json)
```

Dependency style:

- Controllers do not talk directly to tables.
- Controllers call services through `Config\RepositoryServices`.
- Services enforce workflow/state rules.
- Repositories isolate all table operations.

## 3. Request Lifecycle (From URL to DB)

1. Request enters through `public/index.php`.
2. CodeIgniter boots and loads route definitions.
3. Required/global filters run:
   - Required before: `forcehttps`
   - Global before: `csrf`
4. Route-level filters run (`auth` and `role:*` where configured).
5. Controller action runs.
6. Controller validates input and calls service(s).
7. Service checks business state and performs writes/reads through repositories.
8. Repositories use models/query builder to hit tables.
9. Controller returns:
   - server-rendered view, or
   - redirect with flash messages, or
   - JSON (`/analytics/track`).

## 4. Authentication and Access Control Flow

### 4.1 Signup

1. `GET /signup` shows signup form.
2. `POST /signup` validates username/email/password.
3. `AuthenticationService::register()` checks uniqueness, creates user, assigns default `employee` group.
4. User is auto-logged in and redirected.
5. Analytics event `auth.signup_success` is recorded.

### 4.2 Login

1. `GET /login` shows login form.
2. `POST /login` validates payload.
3. `AuthenticationService::login()` attempts Shield session auth by email or username.
4. On success, redirect by role:
   - `admin` -> `/admin/dashboard`
   - others -> `/`
5. On failure, stay on login with error and analytics `auth.login_failed`.

### 4.3 Logout

1. `POST /logout` logs analytics event.
2. Session is destroyed via `AuthenticationService::logout()`.
3. Redirect to `/login`.

### 4.4 Route Guards

- `auth` filter: user must be logged in.
- `role` filter: user must be in at least one allowed group.
- Main groups:
  - `admin`
  - `employee`
  - `it_staff`

## 5. Module-by-Module Process Flow

### 5.1 Home and Navigation

1. `GET /` (`Home::index`) checks current user.
2. Redirect rules:
   - guest -> `/login`
   - admin -> `/admin/dashboard`
   - it_staff -> `/receiving`
   - employee -> `/procurement/purchase-requests`
3. Main layout (`app/Views/layouts/main_layout.php`) builds sidebar sections by group.

### 5.2 Admin Module

### User List and Role Assignment

1. `GET /admin/users` loads users with groups.
2. `POST /admin/users/{id}/role` validates role (`admin`, `employee`, `it_staff`).
3. `UserRepository::assignGroup()` uses `syncGroups()` (single-role behavior in this project).

### 5.3 Procurement Module

This is the first half of the core supply chain workflow.

### A. Purchase Request (PR)

1. User creates PR draft (`POST /procurement/purchase-requests`).
2. `PurchaseRequestService::create()`:
   - validates requester/date/items
   - normalizes lines
   - prevents duplicate item+unit lines
   - writes `purchase_requests(status=draft)`
   - writes `purchase_request_items`
3. User may edit draft (`POST /procurement/purchase-requests/{id}/update`).
4. Submit PR (`POST /procurement/purchase-requests/{id}/submit`):
   - requires `draft` and at least one item
   - sets PR status to `submitted`
   - creates pending `approvals` row if missing (`reference_type=purchase_request`)
5. PR can be cancelled from `draft` or `submitted`:
   - sets PR status `cancelled`
   - updates pending approval to rejected (if exists)

### B. PR Approval

1. Approver opens pending list (`GET /procurement/approvals/pending`).
2. Approve (`POST /procurement/approvals/{approvalId}/approve`):
   - approval decision -> `approved`
   - linked PR status -> `approved`
3. Reject (`POST /procurement/approvals/{approvalId}/reject`):
   - approval decision -> `rejected`
   - linked PR status -> `rejected`

### C. Purchase Order (PO)

1. Create from approved PR (`POST /procurement/purchase-orders/from-pr/{prId}`).
2. `PurchaseOrderService::createFromPurchaseRequest()`:
   - requires PR status `approved`
   - blocks duplicate PO per PR
   - copies PR lines into `purchase_order_items`
   - calculates subtotal/total
   - sets PR status -> `converted_to_po`
3. Issue PO (`POST /procurement/purchase-orders/{poId}/issue`):
   - requires PO `draft`
   - sets PO status -> `issued`

### D. PO Request (POR)

1. Create PO request from issued PO (`POST /procurement/po-requests/from-po/{poId}`).
2. `PoRequestService::createFromPurchaseOrder()`:
   - requires PO status `issued`
   - prevents another open `pending/approved` POR for same PO
   - creates `po_requests(status=pending)`
3. Approve POR (`POST /procurement/po-requests/{id}/approve`) -> status `approved`.
4. Reject POR (`POST /procurement/po-requests/{id}/reject`) -> status `rejected`.

### 5.4 Receiving Module

Receiving converts approved PO requests into posted inventory.

### A. Build Conversion Draft

1. Open conversion page (`GET /receiving/create/from-po-request/{id}`).
2. `ReceivingService::buildConversionData()` checks:
   - PO request exists and is `approved`
   - no non-voided receiving already exists for same PO request
   - PO exists and has remaining quantities
3. Service pre-fills receiving lines from remaining `purchase_order_items`.

### B. Save Receiving Draft

1. Submit form (`POST /receiving`).
2. `ReceivingService::createDraft()` checks:
   - approved PO request
   - unique active receiving for that PO request
   - valid receiving lines
3. Validation rule per line (`ReceivingValidationService`):
   - `received_qty > 0`
   - `accepted_qty + rejected_qty == received_qty`
   - accepted does not exceed remaining PO quantity
4. Writes `receivings(status=draft)` + `receiving_items`.

### C. Validate or Post Draft

- Validate only: `POST /receiving/{id}/validate` returns validation result.
- Post: `POST /receiving/{id}/post` (transactional):
  1. re-check draft status + PO request status
  2. compute line totals
  3. `InventoryPostingService::postReceivingItems()` per line:
     - update PO item `received_qty`
     - find/create `inventory_stocks` row by item+unit+batch+lot+expiry
     - increase on-hand/available, update average cost
     - insert stock movement (`movement_type=receiving`)
  4. update receiving status -> `posted`
  5. update PO request status -> `converted_to_receiving`
  6. update PO status:
     - all lines fully received -> `fully_received`
     - partially received -> `partially_received`
  7. write audit entry `receiving.posted`

### D. Void Draft

- `POST /receiving/{id}/void`:
  - only `draft` can be voided
  - sets status `voided` + reason + actor/time
  - writes audit entry `receiving.voided`

### 5.5 Inventory Quantities Module

1. `GET /inventory/quantities` lists inventory stock ledger (optional keyword filter).
2. `GET /inventory/quantities/{stockId}` shows one stock row plus related stock movements.
3. Data sources:
   - `inventory_stocks`
   - `stock_movements`

### 5.6 Issuance Module

Issuance is stock consumption workflow.

### A. Draft Issuance

1. Create draft (`POST /inventory/issuance`).
2. `IssuanceService::createDraft()`:
   - validates requestor/date/items
   - each line requires `requested_qty > 0`
   - writes `issuances(status=draft)` + `issuance_items`
   - audit `issuance.draft_created`

### B. Submit for Approval

1. `POST /inventory/issuance/{id}/submit`.
2. Service requires `draft` + non-empty items.
3. Sets issuance status -> `submitted`.
4. Creates pending approval row if missing (`reference_type=issuance`).
5. Audit `issuance.submitted`.

### C. Approve or Reject

- Approve (`POST /inventory/issuance/{id}/approve`):
  - requires `submitted`
  - pending approval -> decision `approved`
  - issuance status -> `approved`
  - audit `issuance.approved`

- Reject (`POST /inventory/issuance/{id}/reject`):
  - requires `submitted`
  - pending approval -> decision `rejected`
  - issuance status -> `rejected`
  - audit `issuance.rejected`

### D. Release Stock

1. `POST /inventory/issuance/{id}/release` (transactional).
2. `IssuanceReleaseService::release()` requires status `approved`.
3. For each issuance line:
   - allocate quantity using `InventoryAvailabilityService::allocate()`
   - allocation source = `inventory_stocks.available_qty > 0`, ordered by expiry (FEFO-like ordering)
   - deduct on-hand/available from each allocated stock row
   - insert outbound movement (`movement_type=issuance`)
   - update issuance item issued qty/cost/line total
4. After all lines complete:
   - issuance status -> `released`
   - set `released_by/released_at`
   - audit `issuance.released`
5. If allocation fails or any error occurs:
   - transaction rollback
   - audit `issuance.release_failed`
   - issuance remains `approved`

### E. Cancel Issuance

- `POST /inventory/issuance/{id}/cancel`:
  - allowed from `draft` or `submitted`
  - status -> `cancelled`
  - pending approval (if exists) set to rejected
  - audit `issuance.cancelled`

### 5.7 Reports Module

Reports are read-only views over operational tables.

- `GET /reports/stock-balance`
  - source: `inventory_stocks`
- `GET /reports/stock-movements`
  - source: `stock_movements`
  - filters: date range + movement type
- `GET /reports/issuances`
  - source: `issuances` + `issuance_items` aggregate
- `GET /reports/low-stock`
  - source: `inventory_stocks` where available <= threshold
- `GET /reports/fast-moving`
  - source: `stock_movements` issuance totals grouped by item/unit

Every report view also records analytics event `report.viewed`.

### 5.8 Analytics Module

### Runtime Event Tracking

Controllers call `AnalyticsService::trackCurrentUser()` and `trackHttp()` to log events.

Stored fields include:

- event name
- module
- actor (optional)
- reference type/id (optional)
- route/method
- masked IP (hash by default)
- metadata JSON
- timestamp

Table: `analytics_events`.

### Analytics Screens

- `GET /analytics/dashboard`: totals, top modules/events/routes, recent events
- `GET /analytics/events`: filtered event list
- `GET /analytics/metrics`: daily metrics + trends
- `POST /analytics/track`: manual event endpoint (JSON response)

### Aggregation + Retention Jobs

- `php spark analytics:aggregate [--days N|YYYY-MM-DD]`
  - rolls raw events into `analytics_daily_metrics`
- `php spark analytics:prune [--raw-days N --metric-days N]`
  - deletes old raw events and old daily metrics
- Windows task helper scripts:
  - `scripts/analytics/aggregate_daily.bat`
  - `scripts/analytics/prune_weekly.bat`

### 5.9 Audit Logging Module

Audit captures important state changes in receiving and issuance services.

Table: `audit_logs`.

Typical actions:

- `receiving.posted`
- `receiving.voided`
- `issuance.draft_created`
- `issuance.submitted`
- `issuance.approved`
- `issuance.rejected`
- `issuance.released`
- `issuance.release_failed`
- `issuance.cancelled`

Audit failures are intentionally non-blocking so primary workflows still continue.

## 6. Status Lifecycles (Entity State Machines)

### 6.1 Purchase Requests

`draft -> submitted -> approved -> converted_to_po`

Alternative exits:

- `submitted -> rejected`
- `draft/submitted -> cancelled`

### 6.2 Approvals Table Decisions

`pending -> approved` or `pending -> rejected`

Used for references:

- `purchase_request`
- `issuance`

### 6.3 Purchase Orders

`draft -> issued -> partially_received -> fully_received`

### 6.4 PO Requests

`pending -> approved -> converted_to_receiving`

Alternative:

- `pending -> rejected`

### 6.5 Receivings

`draft -> posted`

Alternative:

- `draft -> voided`

### 6.6 Issuances

`draft -> submitted -> approved -> released`

Alternative exits:

- `submitted -> rejected`
- `draft/submitted -> cancelled`

## 7. Core Data Relationships

```text
purchase_requests (1) -> (many) purchase_request_items
purchase_requests (1) -> (many) purchase_orders
purchase_orders (1) -> (many) purchase_order_items
purchase_orders (1) -> (many) po_requests
po_requests (1) -> (many) receivings
receivings (1) -> (many) receiving_items
inventory_stocks (1) -> (many) stock_movements
issuances (1) -> (many) issuance_items
inventory_stocks (0/1) <- issuance_items.inventory_stock_id
approvals references purchase_request or issuance by (reference_type, reference_id)
```

## 8. Route -> Controller -> Service -> Table Connection Map

### Authentication

- `/login` -> `Auth\LoginController` -> `AuthenticationService` -> Shield user tables + analytics events
- `/signup` -> `Auth\SignupController` -> `AuthenticationService` -> Shield user tables + analytics events
- `/logout` -> `Auth\LogoutController` -> `AuthenticationService` -> session + analytics events

### Procurement

- PR routes -> `PurchaseRequestController` -> `PurchaseRequestService` -> `purchase_requests`, `purchase_request_items`, `approvals`
- Approval routes -> `PurchaseApprovalController` -> `ApprovalService` -> `approvals`, `purchase_requests`
- PO routes -> `PurchaseOrderController` -> `PurchaseOrderService` -> `purchase_orders`, `purchase_order_items`, `purchase_requests`
- PO Request routes -> `PoRequestController` -> `PoRequestService` -> `po_requests`

### Receiving and Inventory Posting

- Receiving routes -> `ReceivingController` / `ReceivingValidationController` -> `ReceivingService` -> `receivings`, `receiving_items`, `purchase_order_items`, `po_requests`, `purchase_orders`, `inventory_stocks`, `stock_movements`, `audit_logs`
- Inventory quantity routes -> `InventoryQuantityController` -> `InventoryQuantityService` -> `inventory_stocks`, `stock_movements`

### Issuance

- Issuance draft/submit/cancel routes -> `IssuanceController` -> `IssuanceService` -> `issuances`, `issuance_items`, `approvals`, `audit_logs`
- Issuance approve/reject routes -> `IssuanceApprovalController` -> `IssuanceApprovalService` -> `approvals`, `issuances`, `audit_logs`
- Issuance release route -> `IssuanceController` -> `IssuanceReleaseService` -> `issuances`, `issuance_items`, `inventory_stocks`, `stock_movements`, `audit_logs`

### Reports and Analytics

- Report routes -> `ReportingController` -> `ReportingService` -> reporting queries on stock/issuance tables + analytics events
- Analytics routes -> `AnalyticsController` -> `AnalyticsService` -> `analytics_events`, `analytics_daily_metrics`

## 9. End-to-End Operational Flow (Main Business Path)

1. Employee creates PR draft with line items.
2. Employee submits PR.
3. Admin/IT approves PR.
4. Admin/IT creates PO from approved PR.
5. Admin/IT issues PO.
6. Admin/IT creates PO request from issued PO.
7. Admin/IT approves PO request.
8. Receiving draft is created from approved PO request.
9. Receiving draft is validated and posted.
10. Posting increases inventory stock and logs inbound movements.
11. Employee creates issuance draft.
12. Employee submits issuance.
13. Admin/IT approves issuance.
14. Admin/IT releases issuance.
15. Release allocates stock, deducts quantities, and logs outbound movements.
16. Reports and analytics show resulting operational data.

## 10. Role-Based Access Summary

- **`admin`**
  - Full access including admin users, procurement approvals, receiving, reports, analytics
  - Can approve PRs, create POs, post receiving, approve issuances
  - Complete operational control and user/role management
  
- **`employee`**
  - Can create/submit PR and issuance, can view inventory quantities
  - Cannot access admin, receiving posting, approvals, reports, analytics dashboards
  - Request creator role with limited visibility
  
- **`it_staff`**
  - Technical support and troubleshooting role with read-only operational visibility
  - Can view all modules (procurement, receiving, inventory) but cannot perform operational actions
  - Can assist users by creating draft PRs and issuances
  - Can access reports and audit logs for troubleshooting
  - Can reset passwords and unlock accounts (not manage roles)
  - Can cancel stuck draft records to unstick workflow
  - **Cannot** approve PRs, create POs, approve PO requests, post receiving, or approve issuances
  - Limited support role - no admin user management or financial/operational approvals

## 11. Quality and Safety Controls Built Into the Process

- CSRF required on form posts
- Auth + role filters on protected routes
- Service-layer state checks prevent invalid transitions
- Transaction boundaries around posting and release operations
- Duplicate and quantity validations on critical line-item operations
- Audit logging for high-impact status changes
- Integration tests for end-to-end procurement, receiving, issuance, analytics tracking, and route guards

## 12. Key Files by Responsibility

- Entry: `public/index.php`
- Routes: `app/Config/Routes.php`
- Filters: `app/Config/Filters.php`, `app/Filters/RoleFilter.php`
- Service wiring: `app/Config/RepositoryServices.php`
- Controllers: `app/Controllers/*`
- Services: `app/Services/*`
- Repositories: `app/Repositories/Contracts/*`, `app/Repositories/EloquentLike/*`
- Models: `app/Models/*`
- DB schema: `app/Database/Migrations/*`
- Seed users/roles: `app/Database/Seeds/AuthRbacSeeder.php`
- Views: `app/Views/*`
- Tests: `tests/unit/*`, `tests/integration/*`

---

Generated from current codebase structure and behavior as of 2026-02-26.
