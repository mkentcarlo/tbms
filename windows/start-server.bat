@echo off
title TBMS Server
cd /d "%~dp0.."

echo ========================================
echo Starting TBMS Laravel Server
echo ========================================
echo.

php --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERROR: PHP is not installed or not in PATH!
    echo Please install PHP and add it to PATH
    pause
    exit /b 1
)

echo Starting server at http://localhost:8000
echo Press Ctrl+C to stop
echo.

php artisan serve --host=0.0.0.0 --port=8000

pause
