@echo off
title Madrassati Launcher
cd /d "%~dp0"

echo ===================================================
echo               Starting Madrassati App
echo ===================================================
echo.

:: 1. Ensure MySQL is running & ready
echo [1/2] Checking MySQL database on port 3306...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0ensure-mysql.ps1"
if %ERRORLEVEL% neq 0 (
    echo.
    echo [ERROR] MySQL database could not be reached!
    pause
    exit /b 1
)

:: 2. Launch Laravel Server
echo.
echo [2/2] Starting Laravel server on http://localhost:8000...
start http://localhost:8000
php artisan serve

pause
