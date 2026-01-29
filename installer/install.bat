@echo off
title TBMS First-Time Setup
cd /d "%~dp0"
set PHP_CMD=php
if exist "%~dp0php\php.exe" set PHP_CMD=%~dp0php\php.exe

echo TBMS Setup
echo ----------

if not exist ".env" (
  if exist ".env.example" (
    copy ".env.example" ".env"
    echo Created .env from .env.example
  ) else (
    echo .env.example not found. Create .env with your database settings.
    pause
    exit /b 1
  )
) else (
  echo .env already exists, skipping.
)

echo.
echo Generating application key...
"%PHP_CMD%" artisan key:generate --force 2>nul || echo Key may already exist.

echo.
echo Running database migrations...
"%PHP_CMD%" artisan migrate --force 2>nul || echo Make sure MySQL is running and .env has correct DB_* settings.

echo.
echo Setting storage permissions...
if exist "storage" (
  icacls "storage" /grant Everyone:F /T >nul 2>&1
  icacls "bootstrap\cache" /grant Everyone:F /T >nul 2>&1
)

echo.
echo Setup complete.
echo Open http://localhost:8000 in your browser when the server is running.
echo Use "TBMS - Start server" or run-server.bat to start the server.
pause
