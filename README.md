# InventoryV2 - Easy Run Guide

This guide is for non-IT users.

## A. Daily Use (If setup is already done)

Do these steps only:

1. Open XAMPP and start:
   - Apache
   - MySQL
2. Open this link in your browser:
   - http://localhost/inventoryv2/public/
3. Sign in:
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
app.baseURL = 'http://localhost/inventoryv2/public/'

database.default.hostname = 127.0.0.1
database.default.database = inventoryv2
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 5. Start XAMPP

Start both:
- Apache
- MySQL

### 6. Create database and tables

```powershell
php spark db:create inventoryv2
php spark migrate --all
```

### 7. Create default user accounts

```powershell
php spark db:seed AuthRbacSeeder
```

### 8. Open the system

- Main page: http://localhost/inventoryv2/public/
- Login page: http://localhost/inventoryv2/public/login

Sample accounts:
- Admin: admin@local.test / Admin@1234
- Employee: employee@local.test / Employee@1234
- IT Staff: itstaff@local.test / Itstaff@1234

Important: Change these passwords before real use.

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
