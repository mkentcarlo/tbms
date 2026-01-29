# Quick Guide: Build Windows .exe from Mac

## ⚠️ Important

**You cannot build Windows .exe directly on Mac.** But you can build it manually using one of these methods:

---

## Method 1: GitHub Actions (Easiest - Recommended)

### Step 1: Run the helper script

```bash
cd desktop
./build-windows.sh
```

This script will:
- Check your git setup
- Push code to GitHub if needed
- Give you instructions

### Step 2: Build on GitHub

1. Go to your GitHub repo → **Actions** tab
2. Click **"Build Windows Installer"**
3. Click **"Run workflow"** → **"Run workflow"**
4. Wait 2-5 minutes
5. Download `.exe` from **Artifacts**

**That's it!** You get your Windows `.exe` file.

---

## Method 2: Windows VM (Full Manual Control)

### Setup:

1. **Install VM software:**
   - **UTM** (free): https://mac.getutm.app/
   - **Parallels** (paid, best): https://www.parallels.com/
   - **VMware Fusion** (paid): https://www.vmware.com/

2. **Install Windows 10/11 in VM**

3. **Build in VM:**
   ```cmd
   cd desktop\electron
   npm install
   npm run build:win
   ```

4. **Copy .exe to Mac** (via shared folder)

---

## Method 3: Remote Windows PC

1. **Access Windows PC** (RDP, SSH, or physical access)

2. **Copy `desktop/electron` folder to Windows**

3. **Build on Windows:**
   ```cmd
   cd desktop\electron
   npm install
   npm run build:win
   ```

4. **Copy `.exe` back to Mac**

---

## Which Method Should I Use?

| Method | Difficulty | Cost | Time | Best For |
|--------|-----------|------|------|----------|
| **GitHub Actions** | ⭐ Easy | Free | 5 min | Most users |
| **Windows VM** | ⭐⭐⭐ Hard | $100-200 | 1-2 hrs | Offline work |
| **Remote Windows** | ⭐⭐ Medium | Varies | 30 min | One-time |

**Recommendation:** Use **GitHub Actions** (Method 1). It's free, easy, and works perfectly from Mac.

---

## Quick Start (GitHub Actions)

```bash
# 1. Make sure you're in the project root
cd /path/to/tbms

# 2. Run helper script
cd desktop
./build-windows.sh

# 3. Follow the instructions it gives you
# 4. Go to GitHub Actions and run the workflow
# 5. Download your .exe!
```

---

## Troubleshooting

**"build-windows.sh: Permission denied"**
```bash
chmod +x desktop/build-windows.sh
```

**"Not a git repository"**
```bash
git init
git add .
git commit -m "Initial commit"
```

**"No GitHub remote"**
```bash
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
git push -u origin main
```

---

## Summary

**To build Windows .exe manually from Mac:**

1. ✅ Use GitHub Actions (easiest)
2. ✅ Or use Windows VM (full control)
3. ✅ Or use remote Windows PC

**The GitHub Actions workflow is already set up!** Just push to GitHub and run it.
