# Multi-Session Setup using Local Domains

## Ano ang Multi-Session Login?

Ang setup na ito ay magpapahintulot sa iyo na mag-login ng **magkaibang roles nang sabay-sabay** (Admin, Employee, IT Staff) gamit ang **magkatabing browser tabs** lang, **hindi na kailangan mag-switch ng applications**.

## Paano ito gumagana?

Ang browser cookies at PHP sessions ay naka-base sa **domain name**. Kung magkaiba ang domain name, ituturing ng browser na magkaibang websites, kaya gagawa ng **magkahiwalay na sessions**!

---

## Step-by-Step Setup Instructions

### Step 1: I-update ang Windows Hosts File

1. **Buksan ang Notepad bilang Administrator**:
   - Hanapin ang **Notepad** sa Start Menu
   - **Right-click** → **Run as Administrator**
   - Confirm sa UAC prompt

2. **I-open ang hosts file**:
   - Sa Notepad, click **File** → **Open**
   - I-navigate to: `C:\Windows\System32\drivers\etc`
   - Sa file type dropdown (sa baba-right), piliin **All Files (*.*)**
   - I-select ang file na `hosts` at click **Open**

3. **Idagdag ang local domain mappings**:
   - Scroll down sa pinakababa ng file
   - Idagdag ang mga linyang ito:

```
# Pharmacy Inventory System - Multi-Session Domains
127.0.0.1   admin.local.test
127.0.0.1   employee.local.test
127.0.0.1   itstaff.local.test
```

4. **I-save ang file**:
   - Click **File** → **Save**
   - Isara ang Notepad

### Step 2: I-verify ang Setup

1. **Buksan ang Command Prompt**:
   - Press `Win + R`
   - Type: `cmd` at press Enter

2. **I-test ang domains**:
```cmd
ping admin.local.test
ping employee.local.test
ping itstaff.local.test
```

   - Dapat makita mo: `Reply from 127.0.0.1`
   - Meaning: Gumagana na ang local domains!

---

## Paano Gamitin ang Multi-Session

### Scenario: Simultaneous Login ng Admin at Employee

Assuming your development server is running on **port 8080**:

#### Tab 1: Login as Admin
```
http://admin.local.test:8080/
```
- Mag-login gamit ang **Admin credentials**
- I-test ang admin features

#### Tab 2: Login as Employee
```
http://employee.local.test:8080/
```
- Mag-login gamit ang **Employee credentials**
- I-test ang employee features

#### Tab 3: Login as IT Staff
```
http://itstaff.local.test:8080/
```
- Mag-login gamit ang **IT Staff credentials**
- I-test ang IT staff features

### Importante!

✅ **Gagana to**: Magkaibang domains = magkahiwalay na sessions
- `admin.local.test` → Admin session
- `employee.local.test` → Employee session
- `itstaff.local.test` → IT Staff session

❌ **Hindi gagana**: Pareho ang domain = mag-ooverride ang sessions
- `localhost` → Admin login
- `localhost` → Employee login ← **Mapapalitan ang Admin session!**

---

## Development Server Setup

### Kung gumagamit ng PHP Built-in Server:

```bash
php spark serve --host=0.0.0.0 --port=8080
```

**Important**: Gamitin ang `0.0.0.0` bilang host para ma-access ang lahat ng local domains!

### Kung gumagamit ng XAMPP/WAMP:

I-configure ang virtual host para ma-accept ang lahat ng local test domains. Typically, default na gagana na ito.

---

## Troubleshooting

### Problem: "Site can't be reached" or "Connection refused"

**Solution**:
1. Siguruhing **running ang development server**
2. I-check ang **port number** (8080, 8000, etc.)
3. I-verify na naka-bind ang server sa `0.0.0.0` or `127.0.0.1`

### Problem: Sessions pa rin ay nag-ooverride

**Solution**:
1. **Clear browser cookies** for all `.local.test` domains
2. **I-close lahat ng tabs** ng application
3. **I-restart ang browser** completely
4. Subukan ulit gamit ang **Incognito/Private window** for testing

### Problem: DNS not resolving

**Solution**:
1. I-verify na tama ang hosts file entry
2. **Flush DNS cache**:
```cmd
ipconfig /flushdns
```
3. Subukan ulit

---

## Technical Details

### CodeIgniter Configuration

Na-update na ang `app/Config/App.php` para payagan ang mga local domains:

```php
public array $allowedHostnames = [
    'admin.local.test',
    'employee.local.test',
    'itstaff.local.test',
];
```

### Session Configuration

Ang session cookies ay **domain-specific**, kaya:
- Cookie para sa `admin.local.test` ≠ Cookie para sa `employee.local.test`
- Magkahiwalay ang session data
- Walang session conflicts

---

## Best Practices

1. **Gamitin para sa Development lang**:
   - Ang local domains ay for testing purposes lang
   - Sa production, use proper domain names

2. **Organized Testing**:
   - Admin testing → `admin.local.test`
   - Employee testing → `employee.local.test`
   - IT Staff testing → `itstaff.local.test`

3. **Browser DevTools**:
   - I-check ang **Application → Cookies** sa DevTools
   - Makikita mo ang magkahiwalay na cookies per domain

4. **Session Cleanup**:
   - After testing, pwede mong i-clear ang sessions:
     - `writable/session/` - delete old session files
     - Browser cookies - clear via DevTools

---

## Summary

✅ **Setup Complete!**
- Hosts file na-update na ✓
- CodeIgniter config na-configure na ✓
- Ready na para sa multi-session testing ✓

**Testing Multi-Role Features:**
- Open 3 tabs
- Use different domains (admin, employee, itstaff)
- Login with different roles
- Test role-specific features simultaneously!

**Questions or Issues?**
Refer to the troubleshooting section or check the session logs in `writable/logs/`.
