# InventoryV2 - Local Setup and Browser Run Guide

This guide shows exactly how to set up and run the current Phase 4 baseline project on your browser.

## 1. Prerequisites

- PHP 8.2+ (your setup already uses PHP 8.2)
- Composer 2+
- XAMPP (Apache + MySQL)
- Git (if cloning)

## 2. Open the project folder

```powershell
cd C:\xampp\htdocs\inventoryv2
```

## 3. Install dependencies

```powershell
composer install
```

## 4. Create environment file

If `.env` does not exist yet:

```powershell
Copy-Item env .env
```

Then set these values in `.env`:

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

## 5. Start XAMPP services

Open XAMPP Control Panel and start:

- Apache
- MySQL

## 6. Ensure project is accessible by Apache

Use one of these:

1. Put project under `C:\xampp\htdocs\inventoryv2`
2. Or configure an Apache VirtualHost/Alias to point this project path to `public/`

For the current `.env` value, the expected URL is:

- `http://localhost/inventoryv2/public/`

## 7. Create database and run migrations

```powershell
php spark db:create inventoryv2
php spark migrate --all
```

## 8. Seed baseline users and roles

```powershell
php spark db:seed AuthRbacSeeder
```

Optional verify:

```powershell
php spark shield:user list
```

## 9. Open in browser

- Home: `http://localhost/inventoryv2/public/`
- Login: `http://localhost/inventoryv2/public/login`
- Signup: `http://localhost/inventoryv2/public/signup`
- Admin dashboard (admin only): `http://localhost/inventoryv2/public/admin/dashboard`
- Issuance list: `http://localhost/inventoryv2/public/inventory/issuance`
- Stock balance report (admin/IT only): `http://localhost/inventoryv2/public/reports/stock-balance`

## 10. Test accounts (seeded)

- Admin  
  - Email: `admin@local.test`  
  - Password: `Admin@1234`
- Employee  
  - Email: `employee@local.test`  
  - Password: `Employee@1234`
- IT Dev/Staff  
  - Email: `itstaff@local.test`  
  - Password: `Itstaff@1234`

## 11. Run automated tests

```powershell
vendor\bin\phpunit
```

Expected result:

- `OK (...)`

## 12. Quick alternative (without Apache)

If you want a fast local check without XAMPP Apache:

1. Set base URL in `.env` to:

```dotenv
app.baseURL = 'http://localhost:8080/'
```

2. Run:

```powershell
php spark serve
```

3. Open:

- `http://localhost:8080/`

## Troubleshooting

- `Unknown database 'inventoryv2'`  
  Run `php spark db:create inventoryv2`

- CSRF error on POST forms  
  Refresh the page and submit again (token expired/invalid)

- 403 on `/admin/dashboard`  
  Log in using the admin account

- DB connection error  
  Check `.env` DB host/user/password/port and ensure MySQL is running

## 13. Immediate Next Step (Proceed Now)

After setup and passing tests, follow this sequence:

1. Run UAT checklist: `docs/UAT_CHECKLIST.md`
2. Fix any failed UAT items
3. Run deployment checklist: `docs/DEPLOYMENT_CHECKLIST.md`
4. Deploy to your target XAMPP/LAN machine

Recommended command flow before UAT:

```powershell
php spark migrate:refresh --all
php spark db:seed AuthRbacSeeder
vendor\bin\phpunit
```

