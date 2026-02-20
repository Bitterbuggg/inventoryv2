# InventoryV2 - UAT Checklist (Phase 5)

## Objective
Validate end-to-end pharmacy workflow on the current baseline before deployment.

## Test Environment
- URL: `http://localhost/inventoryv2/public/`
- DB: `inventoryv2`
- Build: latest local branch
- Date: `2026-02-20`

## Test Accounts
- Admin: `admin@local.test` / `Admin@1234`
- Employee: `employee@local.test` / `Employee@1234`
- IT Staff: `itstaff@local.test` / `Itstaff@1234`

## Pre-UAT Reset
1. Run `php spark migrate:refresh --all`
2. Run `php spark db:seed AuthRbacSeeder`
3. Confirm login works for all three accounts

## UAT Scenarios

### 1) Auth and Role Guards
- [x] Employee can login/logout
- [x] Employee cannot open `/admin/dashboard` (403)
- [x] IT staff can open `/reports/stock-balance`
- [x] Guest is redirected to `/login` for protected routes

### 2) Procurement Flow
- [x] Employee creates purchase request with at least one item
- [x] Employee submits PR
- [x] Admin/IT approves PR
- [x] Admin/IT creates PO from approved PR
- [x] Admin/IT issues PO
- [x] Admin/IT creates and approves PO Request

### 3) Receiving and Inventory Posting
- [x] Admin/IT converts approved PO Request to Receiving
- [x] Receiving draft validates successfully
- [x] Receiving post updates inventory quantities
- [x] Stock movement entries are created for receiving

### 4) Issuance Lifecycle
- [x] Employee creates issuance draft
- [x] Employee submits issuance
- [x] Admin/IT approves issuance
- [x] Admin/IT releases issuance
- [x] Released issuance writes outbound stock movements
- [x] Insufficient-stock release is blocked

### 5) Reporting
- [x] Stock Balance report loads
- [x] Stock Movements report filters by date/type correctly
- [x] Issuance report shows released issuance
- [x] Low-stock report returns items under threshold
- [x] Fast-moving report reflects issuance consumption

### 6) Audit Coverage
- [x] `audit_logs` has `issuance.draft_created`
- [x] `audit_logs` has `issuance.submitted`
- [x] `audit_logs` has `issuance.approved`
- [x] `audit_logs` has `issuance.released`
- [x] Failed release writes `issuance.release_failed`

## SQL Spot Checks
```sql
SELECT COUNT(*) FROM stock_movements WHERE movement_type = 'issuance';
SELECT action, COUNT(*) FROM audit_logs GROUP BY action ORDER BY action;
SELECT status, COUNT(*) FROM issuances GROUP BY status;
```

## Exit Criteria
- [x] All scenarios passed
- [x] No blocker/high defects open
- [x] Any medium/low issues documented with owner and target date
- [ ] UAT sign-off approved by requester/stakeholder

## Sign-off
- Tester: Codex (Automated UAT)
- Date: 2026-02-20
- Result: `PASS (Automated)`
- Notes: No blocker/high defects found in automated UAT run; medium/low issues: none identified (Owner: N/A, Target Date: N/A). Awaiting requester/stakeholder final approval.

## Execution Notes
- 2026-02-20: Ran `php spark migrate:refresh --all` (success).
- 2026-02-20: Ran `php spark db:seed AuthRbacSeeder` (success).
- 2026-02-20: Ran `vendor\bin\phpunit` -> `OK (62 tests, 192 assertions)`.
- Checked items above were validated by automated tests in `tests/integration/*` and related unit coverage.


