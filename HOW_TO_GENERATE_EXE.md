# How to Generate TBMS Desktop .exe File

## ⚠️ Important: Windows Required

**You must build the .exe on a Windows PC.** The Electron builder creates Windows installers only on Windows.

---

## Step-by-Step Instructions

### Step 1: Ensure You're on Windows

- **Windows 10 or 11** is required
- Cannot build Windows .exe on macOS or Linux

### Step 2: Install Node.js (if not already installed)

1. Download Node.js 18+ from [nodejs.org](https://nodejs.org/)
2. Install it (use default settings)
3. Verify installation:
   ```cmd
   node --version
   npm --version
   ```

### Step 3: Navigate to Electron Folder

Open **Command Prompt** or **PowerShell** and navigate to your project:

```cmd
cd C:\path\to\tbms\desktop\electron
```

**Example:**
```cmd
cd C:\Users\YourName\Desktop\webhometech\tbms\desktop\electron
```

### Step 4: Install Dependencies (if not done)

```cmd
npm install
```

This installs Electron and electron-builder. **Note:** Dependencies are already installed if you ran this before.

### Step 5: Build the .exe Installer

```cmd
npm run build:win
```

This command will:
- Package the Electron app
- Create a Windows installer (NSIS)
- Take 2-5 minutes (downloads Chromium if needed)

### Step 6: Find Your .exe File

After building, your installer will be at:

```
desktop\electron\dist\TBMS Setup 1.0.0.exe
```

**Full path example:**
```
C:\Users\YourName\Desktop\webhometech\tbms\desktop\electron\dist\TBMS Setup 1.0.0.exe
```

---

## What You'll Get

After building, you'll have:

```
desktop/electron/dist/
├── TBMS Setup 1.0.0.exe    ← This is your installer!
└── win-unpacked/           ← Unpacked app (for testing)
    ├── TBMS.exe
    └── ...
```

---

## Quick Command Summary

```cmd
# 1. Navigate to electron folder
cd desktop\electron

# 2. Install dependencies (if needed)
npm install

# 3. Build Windows installer
npm run build:win

# 4. Your .exe is ready!
# Location: desktop\electron\dist\TBMS Setup 1.0.0.exe
```

---

## Troubleshooting

### "npm is not recognized"
**Solution:** Install Node.js from [nodejs.org](https://nodejs.org/)

### "electron-builder not found"
**Solution:** Run `npm install` in the `desktop\electron` folder

### Build fails with "Cannot find module"
**Solution:** 
```cmd
cd desktop\electron
npm install
npm run build:win
```

### Build is slow
**Normal!** First build downloads Chromium (~100MB) and takes 2-5 minutes. Subsequent builds are faster.

### "Cannot build on this platform"
**Solution:** You're not on Windows. You must build on Windows 10/11.

### Build succeeds but .exe doesn't work
**Check:**
1. PHP is installed and in PATH
2. MySQL is installed and running
3. `.env` file exists with correct database settings

---

## Testing the .exe

1. **Double-click** `TBMS Setup 1.0.0.exe`
2. Follow the installer wizard
3. Choose installation directory (default: `C:\TBMS`)
4. Check "Start TBMS when Windows starts" (optional)
5. Click "Install"
6. After installation, launch "TBMS Desktop" from Start Menu or Desktop
7. The app should open as a desktop window

---

## Alternative: Build from Project Root

If you're in the project root (`tbms` folder), you can also run:

```cmd
cd desktop\electron && npm run build:win
```

Or use PowerShell:

```powershell
Set-Location desktop\electron
npm run build:win
```

---

## File Size

The generated `.exe` installer will be approximately:
- **100-150 MB** (includes Chromium browser)
- This is normal for Electron apps

---

## Distribution

Once you have `TBMS Setup 1.0.0.exe`:

1. **Test it** on a clean Windows PC first
2. **Distribute** the single `.exe` file to users
3. Users just double-click to install
4. No additional files needed - everything is bundled

---

## Summary

**To generate the .exe:**

1. ✅ Be on Windows PC
2. ✅ Navigate: `cd desktop\electron`
3. ✅ Run: `npm run build:win`
4. ✅ Get: `dist\TBMS Setup 1.0.0.exe`

**That's it!** The installer is ready to distribute.
