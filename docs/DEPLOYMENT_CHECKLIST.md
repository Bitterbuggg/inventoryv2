# InventoryV2 - Deployment Checklist (XAMPP/LAN)

## Objective
Promote the tested build into the target on-prem environment safely.

## 1. Pre-Deployment
- [ ] Confirm UAT status = PASS (`docs/UAT_CHECKLIST.md`)
- [ ] Confirm latest backup exists (database + project files)
- [ ] Confirm `.env` production values are ready
- [ ] Confirm Apache and MySQL target host availability
- [ ] Confirm maintenance window schedule and owner

## 2. Build Validation
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Run `php spark migrate:status`
- [ ] Run `vendor\bin\phpunit` on staging/local baseline
- [ ] Verify no pending critical defects

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
- [ ] Open login page
- [ ] Login as admin
- [ ] Open `/admin/dashboard`
- [ ] Open `/inventory/issuance`
- [ ] Open `/reports/stock-balance`
- [ ] Create + submit one issuance (test record)
- [ ] Approve + release issuance and verify stock movement
- [ ] Verify audit row exists in `audit_logs`

## 5. Rollback Plan
If blocking issue occurs:
1. Restore DB backup immediately
2. Restore previous project version
3. Re-validate login + dashboard + reports
4. Announce rollback completion

## 6. Final Handover
- [ ] Deployment timestamp recorded
- [ ] Deployed commit/version recorded
- [ ] Known issues list shared
- [ ] Owner for monitoring assigned

## Deployment Record
- Date/Time: __________________
- Environment: ________________
- Deployed By: ________________
- Version/Commit: _____________
- Status: `SUCCESS / ROLLBACK`
- Notes: ______________________
