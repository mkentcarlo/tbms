# Quick Desktop App Setup

Convert TBMS to a desktop application in 3 steps.

## Step 1: Install Node.js

Download and install Node.js 18+ from [nodejs.org](https://nodejs.org/)

## Step 2: Build Desktop App

Open Command Prompt in the project root and run:

```cmd
cd desktop\electron
npm install
npm run build:win
```

## Step 3: Install & Run

The installer is created at:
```
desktop\electron\dist\TBMS Setup 1.0.0.exe
```

**Double-click it to install.** TBMS will run as a desktop application!

---

## What You Get

- ✅ Native desktop window (no browser needed)
- ✅ System tray icon
- ✅ Auto-start option
- ✅ Single .exe installer
- ✅ Runs completely offline

---

## Alternative: Include in Main Installer

After building the Electron app, you can integrate it into the main TBMS installer:

1. Build Electron app (Step 2 above)
2. Copy `TBMS Setup 1.0.0.exe` to `installer\` folder
3. Update `installer\TBMS-Setup.iss` to include it
4. Rebuild main installer with Inno Setup

Then users get one installer that includes both web and desktop versions.

---

## Troubleshooting

**"npm is not recognized"**  
→ Install Node.js from nodejs.org

**Build fails**  
→ Run `npm install` first in `desktop\electron`

**App window is blank**  
→ Wait 3-5 seconds for PHP server to start

**Server won't start**  
→ Ensure PHP is installed and in PATH, or place `php.exe` in `C:\TBMS\php\`

---

## Files Created

- `desktop/electron/main.js` - Electron app (starts server, opens window)
- `desktop/electron/package.json` - Dependencies
- `desktop/TBMS-Desktop.bat` - Alternative launcher
- `desktop/README.md` - Detailed guide
- `DESKTOP_APP_GUIDE.md` - Full documentation
