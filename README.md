# InventoryV2 - Easy Run Guide

This project now runs directly from the project folder on XAMPP. You no longer need to browse to `/public`, and links now adapt automatically if the folder name changes on another PC.

## A. Daily Use

1. Start `Apache` and `MySQL` in XAMPP.
2. Open:
   - `http://localhost/inventoryv2/`
3. Sign in:
   - Admin: `admin@local.test` / `Admin@1234`

If you renamed the folder, use:
- `http://localhost/<your-folder-name>/`

## B. First-Time Setup

Requirements:
- XAMPP
- PHP 8.2+
- Composer 2+

### Fastest setup

1. Open PowerShell in the project folder.
2. Run:

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
database.default.hostname = 127.0.0.1
database.default.database = inventoryv2
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

## C. Open the System

- Main page: `http://localhost/inventoryv2/`
- Login page: `http://localhost/inventoryv2/index.php/login`

Sample accounts:
- Admin: `admin@local.test` / `Admin@1234`
- Employee: `employee@local.test` / `Employee@1234`
- IT Staff: `itstaff@local.test` / `Itstaff@1234`

Change these passwords before real use.

## D. Common Problems

Link not working on another PC:
- Make sure the project is inside `xampp\htdocs`.
- Open `http://localhost/<project-folder>/`, not the old `/public/` URL.
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
- `docs/MULTI_SESSION_LOCAL_DOMAINS_SETUP.md`

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
