@echo off
setlocal

cd /d "C:\xampp\htdocs\inventoryv2"

set "PHP_BIN=C:\xampp\php\php.exe"
if not exist "%PHP_BIN%" set "PHP_BIN=php"
set "LOG_FILE=writable\logs\analytics_prune_task.log"

"%PHP_BIN%" spark analytics:prune >> "%LOG_FILE%" 2>&1
set "RC=%ERRORLEVEL%"

echo [%DATE% %TIME%] analytics:prune exit=%RC% >> "%LOG_FILE%"

endlocal & exit /b %RC%
