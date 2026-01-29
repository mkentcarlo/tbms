# Fix: "Cannot create symbolic link - A required privilege is not held"

## Problem

When running `setup-laragon.bat`, you get an error about creating a symbolic link requiring privileges.

## Solutions

### Solution 1: Run as Administrator (Recommended for Symlink)

**Symlinks are faster** but require admin privileges:

1. **Right-click** `setup-laragon-admin.bat`
2. Select **"Run as administrator"**
3. Click **"Yes"** when prompted
4. The script will run and create a symlink

**OR** manually run as admin:

1. Open **Command Prompt as Administrator**
   - Press `Win + X`
   - Select "Command Prompt (Admin)" or "Windows PowerShell (Admin)"
2. Navigate to the windows folder:
   ```cmd
   cd C:\path\to\tbms\windows
   ```
3. Run:
   ```cmd
   setup-laragon.bat
   ```

---

### Solution 2: Use Regular Script (Copies Files Instead)

The regular `setup-laragon.bat` script will **automatically fall back to copying files** if symlink fails:

1. Just run `setup-laragon.bat` normally
2. It will detect symlink failure
3. Automatically copy files instead (slower but works)

**Note:** Copying takes longer but doesn't require admin privileges.

---

### Solution 3: Manual Setup

If both methods fail, set up manually:

1. **Copy TBMS folder:**
   ```cmd
   xcopy C:\path\to\tbms\* C:\laragon\www\tbms\ /E /I /H /Y
   ```

2. **Or use Windows Explorer:**
   - Copy the entire `tbms` folder
   - Paste into `C:\laragon\www\`
   - Rename to `tbms` if needed

3. **Then continue setup:**
   ```cmd
   cd C:\laragon\www\tbms
   copy .env.example .env
   composer install
   php artisan key:generate
   php artisan migrate
   npm install
   npm run build
   ```

---

## What's the Difference?

| Method | Speed | Requires Admin | Best For |
|--------|-------|---------------|----------|
| **Symlink** | ⚡ Fast | ✅ Yes | Development (one-time setup) |
| **Copy Files** | 🐌 Slower | ❌ No | Quick setup without admin |
| **Manual Copy** | 🐌 Slowest | ❌ No | When scripts don't work |

---

## Quick Fix

**Easiest solution:** Just run `setup-laragon.bat` normally. It will automatically copy files if symlink fails. You don't need admin privileges - it just takes a bit longer.

---

## Updated Scripts

I've updated the scripts to:
- ✅ Automatically fall back to copying if symlink fails
- ✅ Provide clear error messages
- ✅ Created `setup-laragon-admin.bat` for admin mode

**Just run `setup-laragon.bat` - it will work either way!**
