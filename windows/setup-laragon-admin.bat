@echo off
REM Run as Administrator to create symlink
REM Right-click and select "Run as administrator"

echo ========================================
echo TBMS Laragon Setup (Administrator Mode)
echo ========================================
echo.

REM Check if running as admin
net session >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERROR: This script must be run as Administrator!
    echo.
    echo Right-click this file and select "Run as administrator"
    echo.
    pause
    exit /b 1
)

echo Running as Administrator - symlinks will work
echo.

call "%~dp0setup-laragon.bat"
