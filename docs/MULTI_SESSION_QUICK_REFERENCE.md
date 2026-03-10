# Multi-Session Quick Reference

## Hosts File Entry (Add to C:\Windows\System32\drivers\etc\hosts)

```plaintext
# Pharmacy Inventory - Multi-Session
127.0.0.1   admin.local.test
127.0.0.1   employee.local.test
127.0.0.1   itstaff.local.test
```

## Access URLs (assuming port 8080)

| Role      | URL                               | Purpose                    |
|-----------|-----------------------------------|----------------------------|
| Admin     | http://admin.local.test:8080/     | Admin session & testing    |
| Employee  | http://employee.local.test:8080/  | Employee session & testing |
| IT Staff  | http://itstaff.local.test:8080/   | IT Staff session & testing |

## Start Development Server

```bash
php spark serve --host=0.0.0.0 --port=8080
```

## Quick Commands

### Verify DNS Setup
```cmd
ping admin.local.test
```

### Flush DNS Cache (if needed)
```cmd
ipconfig /flushdns
```

### Clear Sessions
```bash
# Delete session files
del writable\session\ci_session*
```

## Testing Workflow

1. **Start server**: `php spark serve --host=0.0.0.0 --port=8080`
2. **Open Tab 1**: http://admin.local.test:8080/ → Login as Admin
3. **Open Tab 2**: http://employee.local.test:8080/ → Login as Employee
4. **Open Tab 3**: http://itstaff.local.test:8080/ → Login as IT Staff
5. **Test**: All sessions run independently!

## Troubleshooting

| Problem                  | Solution                          |
|--------------------------|-----------------------------------|
| Can't access domains     | Check hosts file, flush DNS cache |
| Sessions still conflict  | Clear cookies, restart browser    |
| Site can't be reached    | Verify server is running          |
| Wrong port               | Check spark serve port number     |
