@echo off
echo ========================================
echo TBMS Standalone PHP Setup Script
echo ========================================
echo.

echo This script helps you set up TBMS with standalone PHP and MySQL
echo.

echo Step 1: Checking PHP...
php --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERROR: PHP is not installed or not in PATH!
    echo.
    echo Please install PHP 8.2+:
    echo 1. Download from https://windows.php.net/download/
    echo 2. Extract to C:\php
    echo 3. Add C:\php to Windows PATH
    echo 4. Run this script again
    echo.
    pause
    exit /b 1
)
php --version
echo.

echo Step 2: Checking Composer...
composer --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERROR: Composer is not installed!
    echo.
    echo Please install Composer:
    echo 1. Download from https://getcomposer.org/download/
    echo 2. Install Composer-Setup.exe
    echo 3. Run this script again
    echo.
    pause
    exit /b 1
)
composer --version
echo.

echo Step 3: Checking MySQL...
mysql --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo WARNING: MySQL not found in PATH
    echo Please ensure MySQL is installed and running
    echo.
)

echo Step 4: Setting up .env file...
cd /d "%~dp0.."
if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env"
        echo Created .env from .env.example
    ) else if exist ".env.xample" (
        copy ".env.xample" ".env"
        echo Created .env from .env.xample
    )
    echo.
    echo Please edit .env file with your database settings:
    echo   DB_HOST=127.0.0.1
    echo   DB_DATABASE=tbms
    echo   DB_USERNAME=root
    echo   DB_PASSWORD=your_password
    echo.
    pause
) else (
    echo .env file already exists
)

echo.
echo Step 5: Installing PHP dependencies...
if exist "composer.json" (
    composer install --no-interaction
    if %ERRORLEVEL% neq 0 (
        echo ERROR: Failed to install PHP dependencies
        pause
        exit /b 1
    )
) else (
    echo composer.json not found!
    pause
    exit /b 1
)

echo.
echo Step 6: Installing Node dependencies...
if exist "package.json" (
    call npm install
    if %ERRORLEVEL% neq 0 (
        echo WARNING: Failed to install Node dependencies
        echo You may need to install Node.js from https://nodejs.org/
    )
)

echo.
echo Step 7: Laravel setup...
php artisan key:generate --force 2>nul || echo Key generation skipped

echo.
echo Step 8: Database setup...
echo.
echo Please ensure:
echo 1. MySQL is running
echo 2. Database 'tbms' exists
echo 3. .env file has correct database credentials
echo.
echo To create database:
echo   mysql -u root -p
echo   CREATE DATABASE tbms;
echo   exit
echo.
pause

echo.
echo Step 9: Running migrations...
php artisan migrate --force 2>nul || (
    echo Migration failed. Please check:
    echo 1. MySQL is running
    echo 2. Database exists
    echo 3. .env has correct credentials
    echo.
    echo Then run: php artisan migrate
)

echo.
echo Step 10: Building frontend assets...
if exist "package.json" (
    call npm run build 2>nul || echo Build failed. Run: npm run build
)

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo To run TBMS:
echo   php artisan serve
echo.
echo Then open: http://localhost:8000
echo.
echo To run in background:
echo   start /B php artisan serve
echo.
pause
