@echo off
REM TBMS Desktop Launcher
REM This script starts the server and opens the app in a desktop window

cd /d "%~dp0\.."

REM Check if Electron app exists
if exist "desktop\electron\dist\TBMS.exe" (
  echo Starting TBMS Desktop...
  start "" "desktop\electron\dist\TBMS.exe"
  exit /b 0
)

REM Fallback: Start server and open in default browser (hidden)
set PHP_CMD=php
if exist "php\php.exe" set PHP_CMD=php\php.exe

echo Starting TBMS server...
start /min "" "%PHP_CMD%" artisan serve --host=127.0.0.1 --port=8000

timeout /t 3 /nobreak >nul

REM Open in default browser (minimized)
start /min "" "http://127.0.0.1:8000"

echo TBMS is starting. Check your browser or system tray.
