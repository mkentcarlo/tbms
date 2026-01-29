@echo off
echo ========================================
echo TBMS Desktop App - Windows Builder
echo ========================================
echo.

echo Step 1: Checking Node.js...
node --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERROR: Node.js is not installed!
    echo Please install Node.js from https://nodejs.org/
    pause
    exit /b 1
)
node --version
echo.

echo Step 2: Installing dependencies...
call npm install
if %ERRORLEVEL% neq 0 (
    echo ERROR: Failed to install dependencies
    pause
    exit /b 1
)
echo.

echo Step 3: Verifying package.json...
if not exist package.json (
    echo ERROR: package.json not found! Make sure you're in desktop\electron folder
    pause
    exit /b 1
)

echo Step 4: Building Windows installer...
echo This may take 2-5 minutes (first time downloads Chromium)...
call npm run build:win
if %ERRORLEVEL% neq 0 (
    echo.
    echo Build failed. Trying alternative method...
    call npx electron-builder --win
    if %ERRORLEVEL% neq 0 (
        echo ERROR: Build failed
        pause
        exit /b 1
    )
)
echo.

echo ========================================
echo Build Complete!
echo ========================================
echo.
echo Your installer is ready at:
echo   dist\TBMS Setup 1.0.0.exe
echo.
echo You can now distribute this file to users.
echo.
pause
