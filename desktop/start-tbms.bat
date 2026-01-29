@echo off
title TBMS Desktop
cd /d "%~dp0"

:: Check if server is already running
curl -s http://127.0.0.1:8000 >nul 2>&1
if %ERRORLEVEL% equ 0 (
  echo Server is already running.
) else (
  echo Starting TBMS server...
  start /min "" "%~dp0start-server.bat"
  timeout /t 3 /nobreak >nul
)

:: Start PHP Desktop
if exist "phpdesktop.exe" (
  start "" "phpdesktop.exe"
) else (
  echo PHP Desktop not found. Please ensure phpdesktop.exe is in the desktop folder.
  pause
)
