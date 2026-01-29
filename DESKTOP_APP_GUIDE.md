# TBMS Desktop Application Guide

This guide explains how to convert TBMS into a desktop application that runs as a native Windows app without requiring a browser.

---

## Overview

TBMS can run as a desktop application using one of two approaches:

1. **Electron** (Recommended) - Modern, widely used, easier to package
2. **PHP Desktop** - Specifically designed for PHP apps, lighter weight

Both approaches:
- ✅ Run the Laravel server in the background
- ✅ Display the app in an embedded browser window (no external browser needed)
- ✅ Provide system tray icon
- ✅ Auto-start with Windows
- ✅ Single .exe installer

---

## Option 1: Electron (Recommended)

### What is Electron?

Electron wraps your web app in a Chromium-based desktop window. It's used by VS Code, Slack, Discord, and many other apps.

### Setup

1. **Install Node.js** (if not already installed)
   - Download from [nodejs.org](https://nodejs.org/)
   - Version 18+ recommended

2. **Install Electron dependencies**

   ```cmd
   cd desktop\electron
   npm install
   ```

3. **Build the desktop app**

   ```cmd
   npm run build:win
   ```

   This creates `desktop\electron\dist\TBMS Setup 1.0.0.exe`

### How it works

- **main.js** - Electron main process that:
  - Starts the PHP server (`php artisan serve`)
  - Opens a Chromium window pointing to `http://127.0.0.1:8000`
  - Manages system tray icon
  - Handles app lifecycle

- The app runs completely offline (no internet needed)
- Server runs in the background (hidden)
- Window looks like a native desktop app

### Integration with Installer

The Inno Setup installer can include the Electron-built `.exe` as the main launcher, or you can distribute it separately.

---

## Option 2: PHP Desktop

### What is PHP Desktop?

PHP Desktop is a lightweight wrapper specifically designed for PHP applications. It embeds Chromium and can run PHP internally.

### Setup

1. **Download PHP Desktop**
   - Visit [phpdesktop.org](https://phpdesktop.org/)
   - Download the Windows version (e.g., `phpdesktop-chrome-120.0-windows.zip`)
   - Extract to `desktop\phpdesktop\`

2. **Configure**

   - Edit `desktop\settings.json` (already created)
   - Point `url` to `http://127.0.0.1:8000`
   - Set `document_root` to your Laravel `public` folder

3. **Copy files**

   ```
   desktop\
   ├── phpdesktop\
   │   ├── phpdesktop.exe
   │   ├── settings.json (copy from desktop\settings.json)
   │   └── www\ (symlink or copy Laravel public folder)
   └── start-tbms.bat (launches phpdesktop.exe)
   ```

### How it works

- **start-tbms.bat** - Starts the PHP server, then launches `phpdesktop.exe`
- **phpdesktop.exe** - Opens a Chromium window with your app
- **settings.json** - Configuration for window size, behavior, etc.

---

## Recommended: Electron Approach

For TBMS, **Electron is recommended** because:

1. ✅ More standard and well-documented
2. ✅ Easier to package as a single .exe
3. ✅ Better Windows integration (notifications, system tray, etc.)
4. ✅ Active community and updates
5. ✅ Can be built on any OS (cross-platform)

### Building the Electron Desktop App

1. **From project root:**

   ```cmd
   cd desktop\electron
   npm install
   npm run build:win
   ```

2. **Output:** `desktop\electron\dist\TBMS Setup 1.0.0.exe`

3. **Distribute:** Give users this single `.exe` file. They install it, and TBMS runs as a desktop app.

### Updating the Main Installer

You can integrate Electron into the main Inno Setup installer:

1. Build Electron app first (creates `TBMS Setup 1.0.0.exe`)
2. In `installer\TBMS-Setup.iss`, add:

   ```iss
   Source: "desktop\electron\dist\TBMS Setup 1.0.0.exe"; DestDir: "{app}"; DestName: "TBMS.exe"; Flags: ignoreversion
   ```

3. Update shortcuts to point to `TBMS.exe` instead of `run-server.bat`

---

## Desktop App Features

Both approaches provide:

| Feature | Description |
|---------|-------------|
| **Native Window** | Looks like a desktop app, not a browser |
| **System Tray** | Minimize to tray, right-click menu |
| **Auto-start** | Can be added to Windows startup |
| **Offline** | Runs completely offline (no internet) |
| **Background Server** | PHP server runs hidden in background |
| **Single .exe** | One installer file for distribution |

---

## User Experience

**For end users:**

1. Install `TBMS-Setup-1.0.exe` (or `TBMS Setup 1.0.0.exe` for Electron-only)
2. TBMS installs to `C:\TBMS` (or chosen folder)
3. Desktop shortcut "TBMS" appears
4. Double-click → TBMS opens as a desktop window
5. No browser needed, no URL to remember
6. App runs in background (system tray icon)
7. Can minimize to tray, close window (stays in tray)

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Electron app won't start | Ensure Node.js is installed, run `npm install` in `desktop\electron` |
| Server not starting | Check PHP is on PATH or in `C:\TBMS\php\` |
| Window is blank | Wait a few seconds for server to start, or check `http://127.0.0.1:8000` in browser |
| Can't build Electron | Run `npm install` first, ensure Node.js 18+ is installed |

---

## Next Steps

1. **Choose approach** (Electron recommended)
2. **Build the desktop app** (follow steps above)
3. **Test** - Run the built `.exe` and verify it works
4. **Update installer** - Include desktop app in main installer
5. **Distribute** - Give users the single `.exe` installer

---

## Files Created

- `desktop/electron/main.js` - Electron main process
- `desktop/electron/package.json` - Electron dependencies
- `desktop/settings.json` - PHP Desktop config (if using PHP Desktop)
- `desktop/start-tbms.bat` - PHP Desktop launcher
- `desktop/start-server.bat` - Server starter
- `desktop/stop-server.bat` - Server stopper
