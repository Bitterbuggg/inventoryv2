# InventoryV2 - Easy Run Guide

This guide is for non-IT users.

## A. Daily Use (If setup is already done)

Do these steps only:

1. Open XAMPP and start:
   - MySQL
2. Open project folder in PowerShell:

```powershell
cd C:\xampp\htdocs\inventoryv2
```

3. Run the app server:

```powershell
php spark serve --host 127.0.0.1 --port 8080
```

4. Open this link in your browser:
   - http://127.0.0.1:8080/
5. Sign in:
   - Email: admin@local.test
   - Password: Admin@1234

---

## B. First-Time Setup (One time only)

### 1. Install requirements first

- XAMPP
- PHP 8.2+
- Composer 2+

### 2. Open project folder in PowerShell

```powershell
cd C:\xampp\htdocs\inventoryv2
```

### 3. Install project dependencies

```powershell
composer install
```

### 4. Create .env file

```powershell
Copy-Item env .env
```

Open .env and make sure these lines exist:

```dotenv
CI_ENVIRONMENT = development
# Keep app.baseURL commented for portability.
# The app auto-detects the correct base URL from the running server.
# app.baseURL = 'http://127.0.0.1:8080/'

database.default.hostname = 127.0.0.1
database.default.database = inventoryv2
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 5. Start MySQL service

Start MySQL in XAMPP Control Panel.

### 6. Create database and tables

```powershell
php spark db:create inventoryv2
php spark migrate --all
```

### 7. Create default user accounts and sample demo data

```powershell
php spark db:seed AuthRbacSeeder
php spark db:seed SampleCatalogSeeder
php spark db:seed SampleWorkflowSeeder
```

### 8. Start the app and open the system

Run the app server:

```powershell
php spark serve --host 127.0.0.1 --port 8080
```

Keep this terminal open while using the system.

- Main page: http://127.0.0.1:8080/
- Login page: http://127.0.0.1:8080/login

Sample accounts:
- Admin: admin@local.test / Admin@1234
- Employee: employee@local.test / Employee@1234
- IT Staff: itstaff@local.test / Itstaff@1234

Important: Change these passwords before real use.
The catalog seeder adds sample products and suppliers so the admin catalog pages are not empty on a fresh setup.
The workflow seeder adds sample procurement, receiving, inventory, issuance, report, and analytics records so the main modules are not blank after first-time setup.

---

## C. Export CSV

1. Open the page you need (Procurement, Receiving, Inventory, Reports, Analytics, or Admin Users).
2. Apply filters if needed.
3. Click Export CSV.
4. Save the downloaded file.

---

## D. Common Problems (Quick Fix)

Database not found:
- Run: php spark db:create inventoryv2

If php spark fails because writable/cache is missing:

```powershell
New-Item -ItemType Directory -Path writable\cache -Force | Out-Null
if (-not (Test-Path writable\cache\index.html)) { New-Item -ItemType File -Path writable\cache\index.html | Out-Null }
```

CSRF error after form submit:
- Refresh the page and submit again.

403 on admin/report pages:
- Login as Admin or IT Staff account.

Database connection error:
- Check MySQL is running.
- Recheck .env database values.

---

## E. IT-Only (Advanced)

Use these only for maintenance or QA.

Multi-session local domains guide:
- docs/MULTI_SESSION_LOCAL_DOMAINS_SETUP.md

### Multi-session domains (admin/employee/itstaff on one browser)

If your hosts entries use `127.0.0.1`, make sure your dev server is also bound to IPv4.

1. Add these entries to `C:\Windows\System32\drivers\etc\hosts`:

```text
127.0.0.1   admin.local.test
127.0.0.1   employee.local.test
127.0.0.1   itstaff.local.test
```

2. Start the app on IPv4 (required for this setup):

```powershell
php spark serve --host 127.0.0.1 --port 8080
```

3. Open these URLs in separate tabs:
- http://admin.local.test:8080/
- http://employee.local.test:8080/
- http://itstaff.local.test:8080/

4. Verify resolution if needed:

```powershell
ping admin.local.test
ping employee.local.test
ping itstaff.local.test
```

5. If site cannot be reached:
- Flush DNS cache: `ipconfig /flushdns`
- Check port binding: `Get-NetTCPConnection -LocalPort 8080 -State Listen`
- If listener is only `::1` (IPv6), either:
   - keep using `php spark serve --host 127.0.0.1 --port 8080`, or
   - add IPv6 hosts entries:

```text
::1   admin.local.test
::1   employee.local.test
::1   itstaff.local.test
```

Run automated tests:

```powershell
vendor\bin\phpunit
```

Analytics commands:

```powershell
php spark analytics:aggregate
php spark analytics:aggregate --days 7
php spark analytics:prune
php spark analytics:prune --raw-days 180 --metric-days 730
```

Quantity cleanup:

```powershell
php spark maintenance:normalize-qty
php spark maintenance:normalize-qty --apply
```
