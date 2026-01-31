@echo off
echo ========================================
echo TBMS - Database Backup Scheduler Setup
echo ========================================
echo.

:: Get the directory of this batch file
set "SCRIPT_DIR=%~dp0"

:: Find PHP path
set "PHP_PATH="
if exist "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" (
    set "PHP_PATH=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe"
) else if exist "C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" (
    set "PHP_PATH=C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe"
) else if exist "C:\xampp\php\php.exe" (
    set "PHP_PATH=C:\xampp\php\php.exe"
) else (
    echo ERROR: PHP not found. Please edit this file and set PHP_PATH manually.
    pause
    exit /b 1
)

echo PHP Path: %PHP_PATH%
echo Project Path: %SCRIPT_DIR%
echo.

:: Create a helper batch file for the scheduled task
echo Creating task helper script...
(
echo @echo off
echo cd /d "%SCRIPT_DIR%"
echo "%PHP_PATH%" artisan schedule:run ^>^> "%SCRIPT_DIR%storage\logs\scheduler.log" 2^>^&1
) > "%SCRIPT_DIR%run-scheduler.bat"

echo.
echo ========================================
echo Choose an option:
echo ========================================
echo 1. Create Windows Task (runs every minute to check schedule)
echo 2. Remove Windows Task
echo 3. Run backup manually now
echo 4. Exit
echo.
set /p choice="Enter choice (1-4): "

if "%choice%"=="1" (
    echo.
    echo Creating Windows Scheduled Task...
    schtasks /create /tn "TBMS-Scheduler" /tr "\"%SCRIPT_DIR%run-scheduler.bat\"" /sc minute /mo 1 /f
    if %errorlevel%==0 (
        echo.
        echo SUCCESS! Task created successfully.
        echo The scheduler will run every minute and execute backup at 4:00 PM daily.
    ) else (
        echo.
        echo ERROR: Failed to create task. Try running this script as Administrator.
    )
) else if "%choice%"=="2" (
    echo.
    echo Removing Windows Scheduled Task...
    schtasks /delete /tn "TBMS-Scheduler" /f
    if %errorlevel%==0 (
        echo Task removed successfully.
    ) else (
        echo Task not found or could not be removed.
    )
) else if "%choice%"=="3" (
    echo.
    echo Running backup now...
    cd /d "%SCRIPT_DIR%"
    "%PHP_PATH%" artisan backup:database
    echo.
    echo Backup complete!
) else if "%choice%"=="4" (
    echo Goodbye!
    exit /b 0
) else (
    echo Invalid choice.
)

echo.
pause
