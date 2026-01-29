# Building Windows .exe Manually on Mac

## ⚠️ Technical Limitation

**You cannot build a Windows `.exe` installer directly on macOS.** Electron Builder requires Windows-specific tools (NSIS installer, Windows code signing tools) that only run on Windows.

However, here are manual options you can control from your Mac:

---

## Option 1: Windows VM on Mac (Most Manual Control)

### Setup Windows VM

1. **Install Virtualization Software:**
   - **Parallels Desktop** (paid, best performance) - [parallels.com](https://www.parallels.com/)
   - **VMware Fusion** (paid) - [vmware.com](https://www.vmware.com/products/fusion.html)
   - **UTM** (free, open source) - [mac.getutm.app](https://mac.getutm.app/)
   - **VirtualBox** (free) - [virtualbox.org](https://www.virtualbox.org/)

2. **Install Windows 10/11 in VM:**
   - Download Windows ISO from Microsoft
   - Create VM with 4GB+ RAM, 50GB+ disk
   - Install Windows (can use evaluation version for testing)

3. **Build in VM:**
   ```cmd
   # In Windows VM
   cd desktop\electron
   npm install
   npm run build:win
   ```

4. **Copy .exe back to Mac:**
   - Use shared folder between VM and Mac
   - Or copy via network/USB

**Pros:** Full manual control, works offline  
**Cons:** Requires Windows license, uses disk space/RAM

---

## Option 2: Remote Windows PC/VPS (Manual via SSH/RDP)

### Using a Remote Windows Machine

1. **Get Windows Access:**
   - Use a Windows PC you have access to
   - Rent a Windows VPS (Azure, AWS, DigitalOcean, etc.)
   - Use a friend's Windows PC

2. **Connect from Mac:**
   ```bash
   # Via RDP (Remote Desktop)
   # Use Microsoft Remote Desktop app (free from App Store)
   
   # Or via SSH (if Windows has OpenSSH)
   ssh user@windows-ip-address
   ```

3. **Build on Remote Windows:**
   ```cmd
   # Copy files to Windows (via SCP, shared folder, or git)
   cd desktop\electron
   npm install
   npm run build:win
   ```

4. **Download .exe:**
   - Copy via SCP, shared folder, or download link

**Pros:** No VM overhead, can use cloud Windows  
**Cons:** Requires Windows access, may cost money

---

## Option 3: GitHub Actions (Manual Trigger)

This is "manual" because you trigger it yourself, but it runs on GitHub's Windows servers.

### Setup

1. **Push code to GitHub** (if not already):
   ```bash
   git add .
   git commit -m "Ready to build"
   git push
   ```

2. **Manually trigger workflow:**
   - Go to GitHub repo → **Actions** tab
   - Click **"Build Windows Installer"**
   - Click **"Run workflow"** button
   - Click **"Run workflow"** (green button)
   - Wait 2-5 minutes

3. **Download .exe:**
   - Click on the completed workflow run
   - Scroll to **Artifacts** section
   - Download `windows-installer.zip`
   - Extract to get `.exe` file

**Pros:** Free, no Windows needed, works from Mac  
**Cons:** Requires GitHub account, needs internet

---

## Option 4: Docker with Windows Container (Advanced)

This is complex and requires Windows Server license, but technically possible:

```bash
# Requires Docker Desktop with Windows containers
# Not recommended - complex setup
```

---

## Recommended: Option 3 (GitHub Actions)

**Why:** It's the easiest "manual" option:
- ✅ You control when it runs (manual trigger)
- ✅ No Windows license needed
- ✅ No VM setup
- ✅ Free
- ✅ Works from Mac
- ✅ Just push code and click "Run workflow"

### Quick Setup:

1. **The workflow file is already created:** `.github/workflows/build-windows.yml`

2. **Push to GitHub:**
   ```bash
   git add .github/workflows/build-windows.yml
   git commit -m "Add Windows build workflow"
   git push
   ```

3. **Trigger build:**
   - GitHub → Actions → "Build Windows Installer" → "Run workflow"

4. **Download .exe** from artifacts

---

## Comparison

| Method | Manual Control | Cost | Setup Time | Best For |
|--------|---------------|------|------------|----------|
| **Windows VM** | ✅ Full | Windows license | 1-2 hours | Offline work |
| **Remote Windows** | ✅ Full | VPS cost | 30 min | One-time builds |
| **GitHub Actions** | ✅ Manual trigger | Free | 5 min | Regular builds |
| **Direct on Mac** | ❌ Impossible | - | - | Not possible |

---

## My Recommendation

**Use GitHub Actions** - It's the easiest "manual" solution:
- You manually trigger it when you want
- No Windows setup needed
- Free
- Works perfectly from Mac

The workflow file is already created at `.github/workflows/build-windows.yml`. Just push to GitHub and use it!

---

## If You Really Need Full Control

Use **Windows VM** (Option 1) with Parallels or VMware Fusion. You'll have complete manual control, but it requires:
- Windows license (~$100-200)
- VM software (~$50-100)
- 50GB+ disk space
- 8GB+ RAM recommended

---

## Quick Start (GitHub Actions)

```bash
# 1. Make sure workflow file exists
ls .github/workflows/build-windows.yml

# 2. Push to GitHub
git add .
git commit -m "Add Windows build"
git push

# 3. Go to GitHub → Actions → Run workflow manually
# 4. Download .exe from artifacts
```

That's the easiest way to build Windows .exe "manually" from Mac!
