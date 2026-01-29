@echo off
title TBMS Server
cd /d "%~dp0"
set PHP_CMD=php
if exist "%~dp0php\php.exe" set PHP_CMD=%~dp0php\php.exe
echo Starting TBMS at http://localhost:8000
echo Press Ctrl+C to stop.
"%PHP_CMD%" artisan serve --host=0.0.0.0 --port=8000
pause
