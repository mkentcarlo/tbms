# Building TBMS Desktop App on Windows

## ✅ Dependencies Installed

The Electron dependencies have been installed. You're ready to build the Windows desktop app!

---

## Build Steps (Run on Windows PC)

### Prerequisites
- **Windows 10/11** PC
- **Node.js 18+** installed ([download](https://nodejs.org/))
- **PHP 8.2+** installed (for running the Laravel server)
- **MySQL** installed and running

### Step 1: Navigate to Electron Folder

Open **Command Prompt** or **PowerShell** and navigate to:

```cmd
cd C:\path\to\tbms\desktop\electron
```

Or if you're in the project root:

```cmd
cd desktop\electron
```

### Step 2: Build Windows Installer

```cmd
npm run build:win
```

This will:
- Package Electron app
- Create Windows installer (NSIS)
- Output: `dist\TBMS Setup 1.0.0.exe`

### Step 3: Test the Installer

1. Run `dist\TBMS Setup 1.0.0.exe`
2. Install to default location (`C:\TBMS` or chosen folder)
3. Launch "TBMS Desktop" shortcut
4. Verify the app opens as a desktop window

---

## Output Location

After building, the installer will be at:

```
desktop\electron\dist\TBMS Setup 1.0.0.exe
```

---

## Troubleshooting

**"npm is not recognized"**  
→ Install Node.js from nodejs.org

**"electron-builder not found"**  
→ Run `npm install` in `desktop\electron` (already done)

**Build fails**  
→ Ensure you're on Windows (can't build Windows .exe on Mac/Linux)

**App window is blank**  
→ Wait 3-5 seconds for PHP server to start, or check PHP is installed

**Server won't start**  
→ Ensure PHP is in PATH or place `php.exe` in `C:\TBMS\php\`

---

## Alternative: Development Mode

To test the Electron app without building:

```cmd
npm start
```

This runs Electron in development mode. Make sure:
1. PHP server is running: `php artisan serve --host=127.0.0.1 --port=8000`
2. MySQL is running
3. `.env` is configured

---

## Next Steps

1. **Build on Windows:** Run `npm run build:win` on a Windows PC
2. **Test installer:** Install and verify it works
3. **Distribute:** Give `TBMS Setup 1.0.0.exe` to users

---

**Note:** The Windows .exe installer can only be built on Windows. The dependencies are already installed, so you just need to run the build command on a Windows machine.
