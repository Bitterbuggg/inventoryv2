@echo off
setlocal

for %%I in ("%~dp0..\..") do set "PROJECT_ROOT=%%~fI"
cd /d "%PROJECT_ROOT%"

set "PHP_BIN=php"
if exist "%PROJECT_ROOT%\..\..\php\php.exe" set "PHP_BIN=%PROJECT_ROOT%\..\..\php\php.exe"
set "LOG_FILE=writable\logs\analytics_aggregate_task.log"

"%PHP_BIN%" spark analytics:aggregate --days 1 >> "%LOG_FILE%" 2>&1
set "RC=%ERRORLEVEL%"

echo [%DATE% %TIME%] analytics:aggregate --days 1 exit=%RC% >> "%LOG_FILE%"

endlocal & exit /b %RC%
