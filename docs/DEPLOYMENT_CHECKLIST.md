# InventoryV2 - Deployment Checklist (XAMPP/LAN)

## Objective
Promote the tested build into the target on-prem environment safely.

## 1. Pre-Deployment
- [x] Confirm UAT status = PASS (`docs/UAT_CHECKLIST.md`)
- [x] Confirm latest backup exists (database + project files)
- [x] Confirm `.env` production values are ready
- [x] Confirm Apache and MySQL target host availability
- [x] Confirm maintenance window schedule and owner

## 2. Build Validation
- [x] Run `composer install --no-dev --optimize-autoloader`
- [x] Run `php spark migrate:status`
- [x] Run `vendor\bin\phpunit` on staging/local baseline
- [x] Verify no pending critical defects

## 3. Deployment Steps
1. Put app in maintenance mode (manual access control / brief downtime notice)
2. Pull/copy latest project files to target path
3. Verify `.env`:
   - `CI_ENVIRONMENT = production`
   - correct `app.baseURL`
   - correct DB credentials
4. Run migrations:
   - `php spark migrate --all`
5. Seed baseline access (safe/idempotent):
   - `php spark db:seed AuthRbacSeeder`
6. Clear caches/log noise if needed:
   - `php spark cache:clear`

## 4. Post-Deployment Smoke Test
- [x] Open login page
- [x] Login as admin
- [x] Open `/admin/dashboard`
- [x] Open `/inventory/issuance`
- [x] Open `/reports/stock-balance`
- [x] Create + submit one issuance (test record)
- [x] Approve + release issuance and verify stock movement
- [x] Verify audit row exists in `audit_logs`

## 5. Rollback Plan
If blocking issue occurs:
1. Restore DB backup immediately
2. Restore previous project version
3. Re-validate login + dashboard + reports
4. Announce rollback completion

## 6. Final Handover
- [x] Deployment timestamp recorded
- [x] Deployed commit/version recorded
- [x] Known issues list shared
- [x] Owner for monitoring assigned

## Deployment Record
- Date/Time: 2026-02-20 11:02 (Local deployment execution)
- Environment: XAMPP Local (Executed deployment + smoke)
- Deployed By: Codex + Requester
- Version/Commit: e79f1f2
- Status: `SUCCESS`
- Notes: Local deployment checklist completed end-to-end. Remaining action for external rollout: repeat on final target host if different from this machine.

## Execution Notes
- 2026-02-20: UAT marked PASS and requester/stakeholder approval recorded in `docs/UAT_CHECKLIST.md`.
- 2026-02-20: Ran `php spark migrate:status` successfully (all migrations applied, batch 1).
- 2026-02-20: Ran `vendor\bin\phpunit` successfully -> `OK (62 tests, 192 assertions)`.
- 2026-02-20: Ran `composer install --no-dev --optimize-autoloader` (success).
- 2026-02-20: Ran deployment-step commands locally: `php spark migrate --all`, `php spark db:seed AuthRbacSeeder`, `php spark cache:clear` (all success).
- 2026-02-20: Created DB backup `writable/backups/inventoryv2_20260220_105852.sql`.
- 2026-02-20: Smoke HTTP checks passed for `/login`, `/admin/dashboard`, `/inventory/issuance`, `/reports/stock-balance` (all HTTP 200 after admin login).
- 2026-02-20: Smoke issuance flow passed: created issuance `#1`, submitted, approved, released.
- 2026-02-20: DB verification for issuance `#1` -> `status=released`, `stock_movements count=1`, `audit_logs issuance.released count=1`.
- 2026-02-20: `.env` switched to production mode and validated (`CI_ENVIRONMENT = production`), backup saved as `.env.bak_20260220_110238`.
- 2026-02-20: Maintenance window owner/monitoring owner recorded as Requester (local execution window).
- Known issues shared: none identified in this run.
- 2026-02-20: Analytics hardening completed (Phase F4): added `analytics:aggregate` and `analytics:prune` commands.
- 2026-02-20: Executed `php spark analytics:aggregate --days 1` and `php spark analytics:prune` successfully.
- 2026-02-20: Re-ran full test suite -> `OK (72 tests, 220 assertions)`.
- 2026-02-20: Scheduled analytics tasks configured on host `MSI`:
  - `InventoryV2_Analytics_Aggregate_Daily` (Daily 11:55 PM)
  - `InventoryV2_Analytics_Prune_Weekly` (Weekly Sunday 11:50 PM)
- 2026-02-20: Manual task run verification complete (`Last Result = 0` for both tasks).
