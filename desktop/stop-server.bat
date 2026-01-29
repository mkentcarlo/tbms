@echo off
title Stop TBMS Server
echo Stopping TBMS server...

:: Find and kill php artisan serve processes
for /f "tokens=2" %%a in ('tasklist /FI "IMAGENAME eq php.exe" /FO LIST ^| findstr /C:"PID:"') do (
  wmic process where "ProcessId=%%a" get CommandLine | findstr /C:"artisan serve" >nul
  if !errorlevel! equ 0 (
    taskkill /PID %%a /F >nul 2>&1
  )
)

:: Also try to kill by window title
taskkill /FI "WINDOWTITLE eq TBMS Server*" /F >nul 2>&1

echo Server stopped.
timeout /t 2 /nobreak >nul
