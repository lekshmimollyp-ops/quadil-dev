@echo off
setlocal enabledelayedexpansion

echo ===================================================
echo   QUDIL PRO - FULL DATABASE EXPORT (Windows Native)
echo ===================================================

:: 1. Find PostgreSQL executable
echo [SEARCH] Looking for pg_dump.exe...
set "PG_BIN="

if exist "C:\Program Files\PostgreSQL\17\bin\pg_dump.exe" set "PG_BIN=C:\Program Files\PostgreSQL\17\bin\"
if exist "C:\Program Files\PostgreSQL\16\bin\pg_dump.exe" set "PG_BIN=C:\Program Files\PostgreSQL\16\bin\"
if exist "C:\Program Files\PostgreSQL\15\bin\pg_dump.exe" set "PG_BIN=C:\Program Files\PostgreSQL\15\bin\"
if exist "C:\Program Files\PostgreSQL\14\bin\pg_dump.exe" set "PG_BIN=C:\Program Files\PostgreSQL\14\bin\"
if exist "C:\Program Files\PostgreSQL\13\bin\pg_dump.exe" set "PG_BIN=C:\Program Files\PostgreSQL\13\bin\"

if not defined PG_BIN (
    echo [ERROR] Could not find PostgreSQL in default locations.
    echo Please make sure you have PostgreSQL installed.
    echo Done.
    exit /b 1
)

echo [FOUND] Using PostgreSQL at: !PG_BIN!

:: 2. Set credentials (default)
set PGPASSWORD=password
echo [INFO] Assuming default password 'password'. (Edit script if different)

:: 3. Create dump directory
if not exist "docker\dumps" mkdir "docker\dumps"

:: 4. Dump Loop
set "DBS=db_identity db_tenant db_geo db_oms db_wallet db_pricing db_dispatch db_tracking db_agent db_pod db_notification db_accounting db_analytics db_webhook admin_web"

for %%d in (%DBS%) do (
    echo [DUMPING] %%d ...
    "!PG_BIN!pg_dump.exe" -U postgres -h 127.0.0.1 -p 5432 --clean --if-exists %%d > "docker/dumps/%%d.sql"
    if !errorlevel! equ 0 (
        echo    - Success
    ) else (
        echo    - FAILED (Database might not exist)
    )
)

echo.
echo ===================================================
echo [COMPLETE] Files saved to docker/dumps/
echo ===================================================
echo.
echo NEXT STEPS:
echo 1. git add docker/dumps/*.sql
echo 2. git commit -m "Add native DB dumps"
echo 3. git push
echo 4. Go to AWS and Import
pause
