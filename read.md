# InventoryV2 - Run Guide (XAMPP + Browser)

This document shows how to run the project quickly, and how to do full first-time setup.

## Quick Run (If Already Setup)

Use this if dependencies, database, and seed data were already done before.

1. Start XAMPP services:
   - Apache
   - MySQL
2. Open browser:
   - `http://localhost/inventoryv2/public/`
3. Login:
   - Admin: `admin@local.test` / `Admin@1234`

---

## Full First-Time Setup

## 1. Prerequisites
- PHP 8.2+
- Composer 2+
- XAMPP (Apache + MySQL)

## 2. Open project folder

```powershell
cd C:\xampp\htdocs\inventoryv2
```

## 3. Install dependencies

```powershell
composer install
```

## 4. Prepare `.env`

If missing:

```powershell
Copy-Item env .env
```

Set values in `.env`:

```dotenv
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/inventoryv2/public/'

database.default.hostname = 127.0.0.1
database.default.database = inventoryv2
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

## 5. Start XAMPP
Start:
- Apache
- MySQL

## 6. Create DB + run migrations

```powershell
php spark db:create inventoryv2
php spark migrate --all
```

## 7. Seed roles/users

```powershell
php spark db:seed AuthRbacSeeder
```

## 8. Open browser
- Home: `http://localhost/inventoryv2/public/`
- Login: `http://localhost/inventoryv2/public/login`
- Admin Dashboard: `http://localhost/inventoryv2/public/admin/dashboard`

## 9. Test accounts
- Admin: `admin@local.test` / `Admin@1234`
- Employee: `employee@local.test` / `Employee@1234`
- IT Staff: `itstaff@local.test` / `Itstaff@1234`

## 10. Run tests (recommended)

```powershell
vendor\bin\phpunit
```

Expected: `OK (...)`

---

## Optional Local Server (No Apache)

If you want to use CI built-in server:

1. Set in `.env`:

```dotenv
app.baseURL = 'http://localhost:8080/'
```

2. Run:

```powershell
php spark serve
```

3. Open:

- `http://localhost:8080/`

---

## Analytics Operations

Manual commands:

```powershell
php spark analytics:aggregate
php spark analytics:aggregate --days 7
php spark analytics:prune
php spark analytics:prune --raw-days 180 --metric-days 730
```

Windows scheduled tasks configured on this machine:
- `InventoryV2_Analytics_Aggregate_Daily`
- `InventoryV2_Analytics_Prune_Weekly`

Verify:

```powershell
schtasks /Query /TN "InventoryV2_Analytics_Aggregate_Daily" /V /FO LIST
schtasks /Query /TN "InventoryV2_Analytics_Prune_Weekly" /V /FO LIST
```

Logs:
- `writable/logs/analytics_aggregate_task.log`
- `writable/logs/analytics_prune_task.log`

---

## Troubleshooting
- `Unknown database 'inventoryv2'`:
  - Run `php spark db:create inventoryv2`
- CSRF error on POST:
  - Refresh page, then submit again
- 403 on admin/report pages:
  - Login with admin or `it_staff`
- DB connection errors:
  - Recheck `.env` DB credentials and confirm MySQL is running
