# InventoryV2 - Easy Run Guide

This guide is for non-IT users.

## A. Daily Use (If setup is already done)

Do these steps only:

1. Open XAMPP and start MySQL.
2. Open PowerShell in the project folder:

```powershell
cd C:\xampp\htdocs\inventoryv2
```

3. Run the app server:

```powershell
php spark serve --host 127.0.0.1 --port 8080
```

Keep this terminal open while using the system.

4. Open this link in your browser:
   - http://127.0.0.1:8080/
5. Sign in:
   - Email: admin@local.test
   - Password: Admin@1234

## B. First-Time Setup

Requirements:
- XAMPP
- PHP 8.2+
- Composer 2+

### Fastest setup

1. Open XAMPP and start MySQL.
2. Open PowerShell in the project folder.
3. Run:

```powershell
.\scripts\setup-local.bat
```

That script will:
- create `.env` if missing
- ensure writable folders exist
- install Composer dependencies if `vendor` is missing
- create the database if possible
- run migrations
- seed the demo accounts and sample data

After it finishes, continue with the Daily Use steps above: start `php spark serve --host 127.0.0.1 --port 8080` and open `http://127.0.0.1:8080/`.

### Manual setup

```powershell
cd C:\xampp\htdocs\<project-folder>
composer install
Copy-Item env .env
php spark db:create inventoryv2
php spark migrate --all
php spark db:seed AuthRbacSeeder
php spark db:seed SampleCatalogSeeder
php spark db:seed SampleWorkflowSeeder
```

Important `.env` rule:
- Leave `app.baseURL` commented out for portability.

Default local database values are already included in `env`:

```dotenv
CI_ENVIRONMENT = development
# Keep app.baseURL commented for portability.
# The app now auto-detects the correct base URL on each machine/server mode.
# app.baseURL = ''

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

### 7. Create default user accounts and sample demo data

```powershell
php spark db:seed AuthRbacSeeder
php spark db:seed SampleCatalogSeeder
php spark db:seed SampleWorkflowSeeder
```

### 8. Open the system

- Main page: http://127.0.0.1:8080/
- Login page: http://127.0.0.1:8080/login

Sample accounts:
- Admin: `admin@local.test` / `Admin@1234`
- Employee: `employee@local.test` / `Employee@1234`
- IT Staff: `itstaff@local.test` / `Itstaff@1234`

Change these passwords before real use.

## D. Common Problems

Link not working on another PC:
- Make sure the project is inside `xampp\htdocs`.
- Open `http://127.0.0.1:8080/` when using `php spark serve`, or `http://localhost/<project-folder>/` when using Apache/XAMPP.
- Make sure `.env` does not hardcode `app.baseURL`.
- If a clean URL like `/login` fails, use the main page first or enable Apache `mod_rewrite`.

Database not found:
- Run `php spark db:create inventoryv2`

Database connection error:
- Make sure MySQL is running
- Recheck `.env` database values

CSRF error after form submit:
- Refresh the page and submit again

403 on admin/report pages:
- Login using an Admin or IT Staff account

## E. Advanced

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

2. Start the app on IPv4 (recommended for this setup):

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
