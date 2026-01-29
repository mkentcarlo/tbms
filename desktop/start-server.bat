@echo off
title TBMS Server
cd /d "%~dp0"
set PHP_CMD=php
if exist "php\php.exe" set PHP_CMD=php\php.exe

echo Starting TBMS server...
"%PHP_CMD%" artisan serve --host=127.0.0.1 --port=8000 >nul 2>&1

:: Wait a moment for server to start
timeout /t 2 /nobreak >nul

:: Check if server is running
curl -s http://127.0.0.1:8000 >nul 2>&1
if %ERRORLEVEL% neq 0 (
  echo Server failed to start. Check PHP and MySQL.
  pause
  exit /b 1
)

echo Server started at http://127.0.0.1:8000
echo Keep this window open while using TBMS.

:: Keep window open
:loop
timeout /t 5 /nobreak >nul
curl -s http://127.0.0.1:8000 >nul 2>&1
if %ERRORLEVEL% neq 0 (
  echo Server stopped.
  pause
  exit /b 1
)
goto loop
