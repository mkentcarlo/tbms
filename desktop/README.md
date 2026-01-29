# TBMS Desktop Application

Convert TBMS into a native Windows desktop application.

## Quick Start (Electron - Recommended)

### Step 1: Install Node.js
Download and install Node.js 18+ from [nodejs.org](https://nodejs.org/)

### Step 2: Build Desktop App

```cmd
cd desktop\electron
npm install
npm run build:win
```

### Step 3: Run
The built installer is at: `desktop\electron\dist\TBMS Setup 1.0.0.exe`

Install it, and TBMS will run as a desktop application!

---

## What You Get

✅ **Native desktop window** - No browser needed  
✅ **System tray icon** - Minimize to tray  
✅ **Auto-start option** - Starts with Windows  
✅ **Single .exe installer** - Easy distribution  
✅ **Offline** - Runs completely offline  

---

## How It Works

1. **Electron** wraps your Laravel app in a Chromium window
2. **PHP server** runs in the background (hidden)
3. **Desktop window** displays your app at `http://127.0.0.1:8000`
4. **System tray** icon for quick access

---

## Files

- `electron/main.js` - Electron main process (starts server, opens window)
- `electron/package.json` - Electron dependencies
- `start-tbms.bat` - Alternative launcher (if not using Electron)
- `settings.json` - PHP Desktop config (alternative approach)

---

## Integration with Main Installer

After building the Electron app, you can include it in the main Inno Setup installer:

1. Build Electron app: `npm run build:win` in `desktop\electron`
2. Copy `TBMS Setup 1.0.0.exe` to `installer\` folder
3. Update `installer\TBMS-Setup.iss` to include it
4. Rebuild main installer

Or distribute the Electron installer separately as "TBMS Desktop".

---

## Troubleshooting

**"npm not found"**  
→ Install Node.js from nodejs.org

**"electron-builder not found"**  
→ Run `npm install` in `desktop\electron`

**App window is blank**  
→ Wait a few seconds for PHP server to start, or check `http://127.0.0.1:8000` in browser

**Server won't start**  
→ Ensure PHP is installed and in PATH, or place `php.exe` in `C:\TBMS\php\`
