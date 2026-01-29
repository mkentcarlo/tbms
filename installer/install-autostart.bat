@echo off
title TBMS - Add to Windows Startup
cd /d "%~dp0"
set "INSTALL_DIR=%~dp0"
set "INSTALL_DIR=%INSTALL_DIR:~0,-1%"
set PHP_CMD=php
if exist "%~dp0php\php.exe" set "PHP_CMD=%~dp0php\php.exe"

:: Create scheduled task to run at user logon
schtasks /Create /TN "TBMS" /TR "cmd /c \"cd /d \"%INSTALL_DIR%\" && \"%PHP_CMD%\" artisan serve --host=0.0.0.0 --port=8000\"" /SC ONLOGON /F /RL HIGHEST 2>nul

if %ERRORLEVEL% equ 0 (
  echo TBMS has been added to Windows startup.
  echo The server will start when you log in.
) else (
  echo Could not create startup task. Run this script as Administrator.
)
pause
