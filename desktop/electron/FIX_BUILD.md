# Fix: Missing script "build:win" Error

## Problem
You're getting: `npm error Missing script: "build:win"`

## Solution

### Step 1: Make sure you're in the correct directory

You MUST be in the `desktop\electron` folder:

```cmd
cd desktop\electron
```

**Verify you're in the right place:**
```cmd
dir
```

You should see:
- `main.js`
- `package.json`
- `node_modules\` (folder)
- `package-lock.json`

### Step 2: Verify package.json has the script

Check that `package.json` exists and has the script:

```cmd
type package.json
```

Look for this section:
```json
"scripts": {
  "start": "electron .",
  "build": "electron-builder",
  "build:win": "electron-builder --win",
  "dist": "electron-builder --publish=never"
}
```

### Step 3: Reinstall if needed

If the script is missing, reinstall dependencies:

```cmd
npm install
```

### Step 4: Try building again

```cmd
npm run build:win
```

---

## Alternative: Use the build script directly

If `npm run build:win` still doesn't work, you can run electron-builder directly:

```cmd
npx electron-builder --win
```

Or if electron-builder is installed globally:

```cmd
electron-builder --win
```

---

## Common Issues

### Issue 1: Wrong directory
**Error:** `npm error Missing script: "build:win"`  
**Solution:** Make sure you're in `desktop\electron` folder

### Issue 2: package.json corrupted
**Solution:** The package.json should have the scripts section. If it's missing, copy it from the project.

### Issue 3: node_modules not installed
**Solution:** Run `npm install` first

---

## Quick Fix Commands

Run these commands in order:

```cmd
cd desktop\electron
npm install
npm run build:win
```

If that doesn't work:

```cmd
cd desktop\electron
npx electron-builder --win
```

---

## Verify Your Setup

Run this to check everything:

```cmd
cd desktop\electron
echo Current directory:
cd
echo.
echo Checking package.json:
type package.json | findstr "build:win"
echo.
echo Checking node_modules:
dir node_modules 2>nul && echo node_modules exists || echo node_modules missing - run npm install
```

---

## Still Having Issues?

1. **Delete node_modules and reinstall:**
   ```cmd
   cd desktop\electron
   rmdir /s /q node_modules
   del package-lock.json
   npm install
   npm run build:win
   ```

2. **Check Node.js version:**
   ```cmd
   node --version
   ```
   Should be 18 or higher

3. **Verify electron-builder is installed:**
   ```cmd
   npm list electron-builder
   ```
