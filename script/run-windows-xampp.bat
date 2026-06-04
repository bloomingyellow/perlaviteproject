@echo off
:: ═══════════════════════════════════════════════════════════════
::  Perla Vita v8 — Windows XAMPP Setup Script
::  Stack: Apache + MySQL (XAMPP)
:: ═══════════════════════════════════════════════════════════════
setlocal EnableDelayedExpansion

set "PROJECT_NAME=perlavita"
set "SCRIPT_DIR=%~dp0"
if "%SCRIPT_DIR:~-1%"=="\" set "SCRIPT_DIR=%SCRIPT_DIR:~0,-1%"
for %%I in ("%SCRIPT_DIR%\..") do set "PROJECT_ROOT=%%~fI"

:: ── DB settings (must match php/db.php) ──────────────────────
set "DB_NAME=perlavita"
set "DB_USER=root"
set "DB_PASS="

echo.
echo ==============================================
echo   Perla Vita v8 ^| Windows XAMPP Setup
echo ==============================================
echo.

:: ── 1. Locate XAMPP ──────────────────────────────────────────
set "XAMPP_DIR="
for %%D in (
  "C:\xampp"
  "C:\XAMPP"
  "D:\xampp"
  "C:\Program Files\xampp"
  "C:\Program Files (x86)\xampp"
) do (
  if exist "%%~D\htdocs" (
    if not defined XAMPP_DIR set "XAMPP_DIR=%%~D"
  )
)
if not defined XAMPP_DIR (
  echo [ERROR] XAMPP not found.
  echo         Install XAMPP from https://www.apachefriends.org/
  pause & exit /b 1
)
echo [OK] XAMPP found at: %XAMPP_DIR%

:: ── 2. Locate mysql / mariadb CLI ────────────────────────────
set "MYSQL_BIN="
for %%M in (
  "%XAMPP_DIR%\mysql\bin\mysql.exe"
  "%XAMPP_DIR%\mariadb\bin\mysql.exe"
) do (
  if exist "%%~M" if not defined MYSQL_BIN set "MYSQL_BIN=%%~M"
)
if not defined MYSQL_BIN (
  echo [WARN] mysql.exe not found in XAMPP. Skipping automatic DB setup.
  echo        Open phpMyAdmin at http://localhost/phpmyadmin and run sql\perlavita.sql manually.
  goto :copy_files
)
echo [OK] MySQL CLI: %MYSQL_BIN%

:: ── 3. Ensure MySQL/MariaDB is running ───────────────────────
"%MYSQL_BIN%" -u %DB_USER% %DB_PASS% -e "SELECT 1;" >nul 2>&1
if %errorlevel% NEQ 0 (
  echo [>>] Starting MySQL via XAMPP…
  if exist "%XAMPP_DIR%\xampp_start.exe" (
    start "" /b "%XAMPP_DIR%\xampp_start.exe"
    timeout /t 5 /nobreak >nul
  ) else if exist "%XAMPP_DIR%\mysql\bin\mysqld.exe" (
    start "" /b "%XAMPP_DIR%\mysql\bin\mysqld.exe" --console
    timeout /t 5 /nobreak >nul
  ) else (
    echo [WARN] Could not auto-start MySQL. Open XAMPP Control Panel and start MySQL, then re-run.
    pause & exit /b 1
  )
)
echo [OK] MySQL is running.

:: ── 4. Create DB and tables ───────────────────────────────────
echo [>>] Running SQL schema…
if defined DB_PASS (
  "%MYSQL_BIN%" -u %DB_USER% -p%DB_PASS% < "%PROJECT_ROOT%\sql\perlavita.sql"
) else (
  "%MYSQL_BIN%" -u %DB_USER% < "%PROJECT_ROOT%\sql\perlavita.sql"
)
if %errorlevel% EQU 0 (
  echo [OK] Database ready.
) else (
  echo [WARN] SQL import may have failed. Check credentials in php\db.php.
)

:copy_files
:: ── 5. Copy files to htdocs ──────────────────────────────────
set "DEST=%XAMPP_DIR%\htdocs\%PROJECT_NAME%"
echo [>>] Deploying to: %DEST%
if exist "%DEST%" rmdir /s /q "%DEST%"
mkdir "%DEST%"
mkdir "%DEST%\sql"

for %%F in (
  index.php catalog.php checkout.php confirmation.php
  login.php logout.php profile.php admin.php api.php
) do (
  if exist "%PROJECT_ROOT%\%%F" copy /y "%PROJECT_ROOT%\%%F" "%DEST%\%%F" >nul
)
for %%D in (css php script uploads sql) do (
  if exist "%PROJECT_ROOT%\%%D" xcopy /s /e /i /y /q "%PROJECT_ROOT%\%%D" "%DEST%\%%D" >nul
)
echo [OK] Files copied.

:: ── 6. Verify structure ───────────────────────────────────────
set OK=1
for %%F in (
  "index.php" "catalog.php" "php\auth.php" "php\db.php"
  "php\products.php" "script\app.js" "sql\perlavita.sql"
) do (
  if not exist "%DEST%\%%~F" (
    echo [WARN] Missing in destination: %%~F
    set OK=0
  )
)
if %OK% EQU 1 echo [OK] All files verified.

:: ── 7. Start Apache ──────────────────────────────────────────
tasklist /fi "imagename eq httpd.exe" 2>nul | find /i "httpd.exe" >nul
if %errorlevel% EQU 0 (
  echo [OK] Apache already running.
) else (
  echo [>>] Starting Apache…
  if exist "%XAMPP_DIR%\xampp_start.exe" (
    start "" /b "%XAMPP_DIR%\xampp_start.exe"
  ) else if exist "%XAMPP_DIR%\apache\bin\httpd.exe" (
    start "" /b "%XAMPP_DIR%\apache\bin\httpd.exe"
  ) else (
    echo [WARN] Could not auto-start Apache. Use XAMPP Control Panel.
  )
  timeout /t 3 /nobreak >nul
)

:: ── 8. Open in browser ───────────────────────────────────────
set "SITE_URL=http://localhost/%PROJECT_NAME%/"
echo.
echo ==============================================
echo   Perla Vita is ready!
echo   Opening: %SITE_URL%
echo ==============================================
echo.
echo   Admin login: admin@perlavita.com / admin123
echo.
echo   If the page is blank, open php\db.php and
echo   check the DB_USER / DB_PASS settings.
echo.
timeout /t 1 /nobreak >nul
start "" "%SITE_URL%"
pause
endlocal
