@echo off
echo ========================================
echo TBMS Laragon Setup Script
echo ========================================
echo.

echo This script helps you set up TBMS with Laragon
echo.

echo Step 1: Checking if Laragon is installed...
if exist "C:\laragon\laragon.exe" (
    echo Laragon found at C:\laragon
    set LARAGON_PATH=C:\laragon
) else if exist "D:\laragon\laragon.exe" (
    echo Laragon found at D:\laragon
    set LARAGON_PATH=D:\laragon
) else (
    echo Laragon not found!
    echo.
    echo Please install Laragon first:
    echo 1. Download from https://laragon.org/download/
    echo 2. Install Laragon
    echo 3. Run this script again
    echo.
    pause
    exit /b 1
)

echo.
echo Step 2: Setting up TBMS in Laragon...
set TBMS_PATH=%~dp0..
set TBMS_NAME=tbms

echo Copying TBMS to Laragon www folder...
if not exist "%LARAGON_PATH%\www\%TBMS_NAME%" (
    echo Creating symlink...
    mklink /D "%LARAGON_PATH%\www\%TBMS_NAME%" "%TBMS_PATH%" >nul 2>&1
    if %ERRORLEVEL% neq 0 (
        echo Symlink failed, copying files instead...
        xcopy "%TBMS_PATH%\*" "%LARAGON_PATH%\www\%TBMS_NAME%\" /E /I /H /Y
    )
) else (
    echo TBMS already exists in Laragon www folder
)

echo.
echo Step 3: Setting up .env file...
cd "%LARAGON_PATH%\www\%TBMS_NAME%"
if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env"
        echo Created .env from .env.example
    ) else if exist ".env.xample" (
        copy ".env.xample" ".env"
        echo Created .env from .env.xample
    )
)

echo.
echo Step 4: Database setup...
echo.
echo Please create a database in Laragon:
echo 1. Open Laragon
echo 2. Click "Database" button (or open HeidiSQL)
echo 3. Create database: tbms
echo 4. Update .env file with database credentials:
echo    DB_HOST=127.0.0.1
echo    DB_DATABASE=tbms
echo    DB_USERNAME=root
echo    DB_PASSWORD=(your MySQL root password, usually empty)
echo.

echo Step 5: Installing dependencies...
if exist "composer.json" (
    echo Installing PHP dependencies...
    composer install --no-interaction
)

if exist "package.json" (
    echo Installing Node dependencies...
    call npm install
)

echo.
echo Step 6: Laravel setup...
php artisan key:generate --force 2>nul
echo.
echo Running migrations...
php artisan migrate --force 2>nul || echo Please configure .env and run: php artisan migrate
echo.
echo Building frontend assets...
call npm run build 2>nul || echo Please run: npm run build

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Open Laragon
echo 2. Click "Start All"
echo 3. Access TBMS at: http://tbms.test
echo    Or: http://localhost/tbms/public
echo.
echo If you need to run migrations:
echo   cd %LARAGON_PATH%\www\%TBMS_NAME%
echo   php artisan migrate
echo.
pause
