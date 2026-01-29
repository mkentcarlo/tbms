# Building TBMS Desktop App on macOS

## ⚠️ Important: Windows .exe Cannot Be Built on Mac

**You cannot build a Windows `.exe` installer on macOS.** Electron Builder requires Windows to create Windows installers.

---

## Your Options

### Option 1: Build macOS App (Recommended for Mac Users)

Build a macOS `.dmg` installer instead:

```bash
cd desktop/electron
npm install
npm run build:mac
```

**Output:** `desktop/electron/dist/TBMS-1.0.0.dmg`

This creates a macOS app that Mac users can install.

---

### Option 2: Build Windows .exe Using Remote Windows Machine

#### Option 2A: Use a Windows PC/Virtual Machine

1. Copy the `desktop/electron` folder to a Windows PC
2. On Windows, run:
   ```cmd
   cd desktop\electron
   npm install
   npm run build:win
   ```
3. Get the `.exe` from `dist/` folder

#### Option 2B: Use GitHub Actions (Free CI/CD)

Create `.github/workflows/build-windows.yml`:

```yaml
name: Build Windows Installer

on:
  workflow_dispatch:

jobs:
  build:
    runs-on: windows-latest
    steps:
      - uses: actions/checkout@v3
      - uses: actions/setup-node@v3
        with:
          node-version: '18'
      - run: cd desktop/electron && npm install
      - run: cd desktop/electron && npm run build:win
      - uses: actions/upload-artifact@v3
        with:
          name: windows-installer
          path: desktop/electron/dist/*.exe
```

Then:
1. Push to GitHub
2. Go to Actions tab
3. Run the workflow
4. Download the `.exe` from artifacts

#### Option 2C: Use Remote Windows Service

Services like:
- **GitHub Actions** (free for public repos)
- **AppVeyor** (free tier)
- **CircleCI** (free tier)
- **Azure Pipelines** (free tier)

---

### Option 3: Build Universal App (Works on Mac, Package for Windows Later)

Build a macOS app now, and package for Windows when you have access to Windows:

```bash
cd desktop/electron
npm install
npm run build:mac
```

This creates a working macOS app. When you have Windows access, run `npm run build:win` to create the Windows version.

---

## Quick Commands for Mac

### Build macOS App
```bash
cd desktop/electron
npm install
npm run build:mac
```

### Test Electron App (Development)
```bash
cd desktop/electron
npm start
```

**Note:** For testing, you need:
- PHP server running: `php artisan serve --host=127.0.0.1 --port=8000`
- MySQL running
- `.env` configured

---

## What You Can Build on Mac

| Platform | Command | Output |
|----------|---------|--------|
| **macOS** | `npm run build:mac` | `TBMS-1.0.0.dmg` ✅ |
| **Linux** | `npm run build:linux` | `.AppImage` or `.deb` ✅ |
| **Windows** | `npm run build:win` | ❌ Requires Windows |

---

## Recommended Workflow

1. **On Mac:** Build and test macOS app
   ```bash
   npm run build:mac
   ```

2. **For Windows .exe:** Use one of these:
   - **GitHub Actions** (easiest, free)
   - **Windows VM** (Parallels, VMware, VirtualBox)
   - **Remote Windows PC** (friend, colleague, cloud VM)

---

## GitHub Actions Setup (Easiest for Windows .exe)

1. **Create file:** `.github/workflows/build-windows.yml`

2. **Push to GitHub**

3. **Run workflow:**
   - Go to GitHub repo → Actions tab
   - Click "Build Windows Installer"
   - Click "Run workflow"
   - Wait for build to complete
   - Download `.exe` from artifacts

4. **Done!** You have your Windows `.exe` without needing Windows.

---

## Summary

**On Mac, you can:**
- ✅ Build macOS `.dmg` installer
- ✅ Build Linux `.AppImage` or `.deb`
- ✅ Test Electron app in development
- ❌ **Cannot** build Windows `.exe` (needs Windows)

**To get Windows .exe:**
- Use GitHub Actions (recommended)
- Use Windows VM
- Use remote Windows PC
- Use cloud CI/CD service

---

## Next Steps

1. **Try building macOS app:**
   ```bash
   cd desktop/electron
   npm run build:mac
   ```

2. **Set up GitHub Actions** for Windows builds (see above)

3. **Or** use a Windows VM/PC when you need the `.exe`
