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
- [ ] Employee can login/logout
- [ ] Employee cannot open `/admin/dashboard` (403)
- [ ] IT staff can open `/reports/stock-balance`
- [ ] Guest is redirected to `/login` for protected routes

### 2) Procurement Flow
- [ ] Employee creates purchase request with at least one item
- [ ] Employee submits PR
- [ ] Admin/IT approves PR
- [ ] Admin/IT creates PO from approved PR
- [ ] Admin/IT issues PO
- [ ] Admin/IT creates and approves PO Request

### 3) Receiving and Inventory Posting
- [ ] Admin/IT converts approved PO Request to Receiving
- [ ] Receiving draft validates successfully
- [ ] Receiving post updates inventory quantities
- [ ] Stock movement entries are created for receiving

### 4) Issuance Lifecycle
- [ ] Employee creates issuance draft
- [ ] Employee submits issuance
- [ ] Admin/IT approves issuance
- [ ] Admin/IT releases issuance
- [ ] Released issuance writes outbound stock movements
- [ ] Insufficient-stock release is blocked

### 5) Reporting
- [ ] Stock Balance report loads
- [ ] Stock Movements report filters by date/type correctly
- [ ] Issuance report shows released issuance
- [ ] Low-stock report returns items under threshold
- [ ] Fast-moving report reflects issuance consumption

### 6) Audit Coverage
- [ ] `audit_logs` has `issuance.draft_created`
- [ ] `audit_logs` has `issuance.submitted`
- [ ] `audit_logs` has `issuance.approved`
- [ ] `audit_logs` has `issuance.released`
- [ ] Failed release writes `issuance.release_failed`

## SQL Spot Checks
```sql
SELECT COUNT(*) FROM stock_movements WHERE movement_type = 'issuance';
SELECT action, COUNT(*) FROM audit_logs GROUP BY action ORDER BY action;
SELECT status, COUNT(*) FROM issuances GROUP BY status;
```

## Exit Criteria
- [ ] All scenarios passed
- [ ] No blocker/high defects open
- [ ] Any medium/low issues documented with owner and target date
- [ ] UAT sign-off approved by requester/stakeholder

## Sign-off
- Tester: ____________________
- Date: ______________________
- Result: `PASS / FAIL`
- Notes: _____________________
