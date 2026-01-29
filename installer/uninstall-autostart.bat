@echo off
title TBMS - Remove from Windows Startup
schtasks /Delete /TN "TBMS" /F 2>nul
if %ERRORLEVEL% equ 0 (
  echo TBMS has been removed from Windows startup.
) else (
  echo No TBMS startup task found, or run as Administrator to remove.
)
pause
