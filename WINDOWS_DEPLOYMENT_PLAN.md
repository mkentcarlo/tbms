# TBMS Windows Deployment Plan

**Goals:**
1. **Easy install on any Windows PC** – One installer or one folder; minimal manual steps.
2. **Runs in the background** – No need to keep a browser or terminal open; server runs as a background process or Windows Service.
3. **Auto-start when PC boots** – Server starts automatically when Windows starts; no user action required.

---

## Overview

Target: **Windows 10/11** local deployment. Users get TBMS running by:
- Running an installer (or unpacking a portable package),
- Optionally running a one-time setup (DB creation, migrations),
- Thereafter the app runs in the background and starts automatically on boot.

Users open a browser only when they want to use TBMS (e.g. http://localhost:8000). The server does not depend on the browser being open.

---

## Phase 1: Define the Windows Stack

### 1.1 Components to Ship or Require

| Component | Option A (Recommended) | Option B |
|-----------|-------------------------|----------|
| **PHP** | Portable PHP 8.2+ (zip) inside app folder | User installs XAMPP/Laragon; we only ship app |
| **MySQL** | MariaDB/MySQL portable or “installer included” | User installs XAMPP/Laragon (includes MySQL) |
| **Web server** | `php artisan serve` (simplest) | Nginx/Apache via Laragon/XAMPP |
| **Auto-start** | Windows Service (NSSM) or Task Scheduler | Laragon “Run at Windows startup” |

**Recommendation:** **Option A** – ship a **self-contained package**:

- **PHP** – Embed a portable PHP 8.2 build (e.g. from windows.php.net or a known portable build) in a `php/` subfolder, or use a fixed path like `C:\tbms\php\`.
- **MySQL** – Use one of:
  - **MariaDB portable** (zip, unzip to `mysql/` or `C:\tbms\mysql\`), or  
  - **MySQL installer** run by our installer, or  
  - A **MySQL Community MSI** that our installer calls (e.g. silent install).
- **App** – Your Laravel app lives in e.g. `C:\tbms\app\` (or `C:\Program Files\TBMS\app\` if you use an installer).
- **Server** – `php artisan serve --host=0.0.0.0 --port=8000` so it’s reachable as `http://localhost:8000` from the same machine (and optionally from the network if needed later).

This way:
- No dependency on “user must install XAMPP/Laragon.”
- One folder (or one installer) delivers PHP + MySQL + app.
- “Easy install on any Windows PC” = copy folder + run setup, or run installer.

### 1.2 Optional: Laragon-Based Variant

If you prefer **Option B**:

- Document “Install Laragon, then run our Laragon installer.”
- Laragon provides PHP, MySQL, and “start at Windows boot.”
- We provide a **Laragon “quick app”** or script that:
  - Copies the app into Laragon’s `www/tbms`,
  - Creates a Laragon “link”/vhost so it’s `http://tbms.test` (or similar),
  - Runs migrations once,
  - Uses Laragon’s “Auto start” so MySQL + server start on boot.

This is easier to build but requires every user to install Laragon first. Phase 2–4 below assume **Option A** (self-contained); you can add a small “Laragon mode” subsection later.

---

## Phase 2: Folder Layout and Scripts (Self-Contained)

### 2.1 Proposed layout (e.g. `C:\tbms\` or install dir)

```
C:\tbms\
├── php\              # Portable PHP 8.2 (or symlink/installer-selected path)
│   └── php.exe, …
├── mysql\            # Portable MariaDB/MySQL (optional if you use system MySQL)
│   └── bin\mysqld.exe, …
├── app\              # Laravel app (your TBMS codebase)
│   ├── artisan
│   ├── public\
│   ├── ...
├── run-server.bat    # Starts "php artisan serve" (used by Task/Service)
├── start-mysql.bat   # If using portable MySQL: start mysql
├── stop-mysql.bat    # If using portable MySQL: stop mysql
├── install.bat       # One-time: create DB, .env, migrate, build assets
├── uninstall-service.bat   # Remove Windows Service / Task (if we add one)
└── (optional) nssm\  # NSSM executable for “install as service”
```

- **run-server.bat**  
  - Sets `PATH` to `php\` (and optionally `mysql\bin`)  
  - `cd app`  
  - `php artisan serve --host=0.0.0.0 --port=8000`  
  - Used both for manual “Start server” and by Task Scheduler / NSSM.

- **start-mysql.bat / stop-mysql.bat**  
  - Only needed if you ship portable MySQL.  
  - Start: run `mysql\bin\mysqld.exe` (or `mysqld --defaults-file=...`) in background.  
  - Stop: `mysql\bin\mysqladmin -u root shutdown` or equivalent.

- **install.bat**  
  - One-time setup: create `.env` from `.env.example`, run migrations, `npm run build` (or use pre-built `public/build`), optionally create DB and user if portable MySQL.

### 2.2 “Runs in background” and “No need to sit on browser”

- The **server** is started by either:
  - **Task Scheduler** – Run `run-server.bat` at user logon (or at startup), “Run whether user is logged on or not” if you want it before login (requires admin).
  - **Windows Service (NSSM)** – Run `php.exe artisan serve ...` as a service; it then runs in the background and does not need any window or browser.
- The **browser** is only used when someone wants to use TBMS. Users open `http://localhost:8000` when needed; they can close the browser and the server keeps running.

So:
- **“Not need to sit on browser”** = server runs in the background; the browser is only the client.
- **“Runs in background”** = achieved by Task Scheduler or NSSM, not by “keeping a terminal open.”

### 2.3 Auto-start when Windows is on

Two practical options:

| Method | Ease | Reliability | User sees |
|--------|------|-------------|-----------|
| **Task Scheduler** – “At log on” run `run-server.bat` | Easy, no admin needed for “at log on” | Good | Can be hidden (no window) |
| **NSSM – Windows Service** | Medium (one-time “install service” step) | Best (starts at OS boot, restarts on failure) | Nothing |

**Recommended path:**

1. **First release:** Use **Task Scheduler**:
   - **install.bat** (or a separate **install-autostart.bat**) creates a task:
     - Trigger: “At log on” (or “At startup” if you run as admin).
     - Action: Run `run-server.bat` (full path).
     - “Run with highest privileges” only if needed; otherwise run as current user.
   - Optional: “Do not show window” so no CMD flash.

2. **Later:** Add **NSSM-based service** for “start before user logs in” and more robustness:
   - **install-service.bat** calls NSSM to create a service:
     - Application: `C:\tbms\php\php.exe`
     - Arguments: `artisan serve --host=0.0.0.0 --port=8000`
     - Start directory: `C:\tbms\app`
   - Startup type: Automatic.  
   - MySQL must already be running (either as another service or started earlier by another task/service).

---

## Phase 3: Easy Install Experience

### 3.1 “Portable” (ZIP) style

1. **Build an output zip** (e.g. `tbms-windows-portable.zip`) containing:
   - Pre-downloaded portable PHP 8.2 (or a script that downloads it on first run).
   - Pre-downloaded portable MariaDB/MySQL (or instructions + installer link).
   - Your Laravel app (with `vendor/` and `node_modules` pre-built, or a one-step build in **install.bat**).
   - `install.bat`, `run-server.bat`, “add to Task Scheduler” logic.

2. **User flow:**
   - Unzip to e.g. `C:\tbms\`.
   - (If MySQL not included) Install MySQL/MariaDB once, or run a small “MySQL installer” from the package.
   - Run **install.bat** once: create `.env`, create DB (if needed), `php artisan migrate`, `npm run build`, optionally “add autostart task.”
   - Optionally run **“Add autostart”** (or have **install.bat** ask “Add to Windows startup? Y/N”).
   - Thereafter: server starts at boot (if autostart was added); user opens `http://localhost:8000` when they want to use TBMS.

### 3.2 “Installer” (e.g. Inno Setup) style

1. **Inno Setup** (or similar) installer that:
   - Lets user choose install path (e.g. `C:\tbms\`).
   - Copies:
     - Portable PHP (or installs PHP via a known path),
     - App files (excluding dev-only),
     - Optional: portable MySQL or runs MySQL installer.
   - Runs a “post-install” step that does the same as **install.bat** (env, migrate, build).
   - Optional: “Add TBMS to Windows startup” checkbox → creates the Task Scheduler task (or installs NSSM service).
   - Shortcuts: “TBMS – Open in browser” (http://localhost:8000), “TBMS – Start server” (for manual run), “TBMS – Uninstall autostart.”

2. **User flow:**
   - Run `TBMS-Setup-1.0.exe` → choose path → Next → Finish.
   - If “Add to startup” was checked, no further action; after reboot, server is running.  
   - Open “TBMS – Open in browser” or type `http://localhost:8000` when needed.

### 3.3 Checklist for “easy install on any Windows PC”

- [ ] One download: ZIP or one EXE installer.
- [ ] One-time setup: single script or installer step (env, DB, migrate, build).
- [ ] No need to “leave browser open” – server runs in background (Task or Service).
- [ ] Auto-start: one checkbox or one script (“Add to startup”) that creates Task or Service.
- [ ] Clear doc: “After install, open http://localhost:8000 when you want to use TBMS.”

---

## Phase 4: App and Config Adjustments for Windows

### 4.1 Paths and .env

- **APP_URL** – e.g. `http://localhost:8000` for local use.
- **DB_*** – Use `127.0.0.1`, port `3306`, and a dedicated DB/user created by **install.bat** or the installer.
- **Paths in app** – Replace any hardcoded Windows path (e.g. backup) with config.

### 4.2 Backup route (existing hardcoded path)

Current pattern in `routes/web.php`:

```php
$command = "C:/xampp/mysql/bin/mysqldump --user=...";
```

- Add config (e.g. in `.env`):
  - `MYSQLDUMP_PATH=C:\tbms\mysql\bin\mysqldump` (or `C:\xampp\mysql\bin\mysqldump` for XAMPP).
- In code, use `env('MYSQLDUMP_PATH', 'mysqldump')` so:
  - Windows install uses `C:\tbms\mysql\bin\mysqldump` (or whatever the installer set).
  - Dev/local can use `mysqldump` if it’s on PATH, or set `MYSQLDUMP_PATH` in `.env`.

Also ensure **BACKUP_DIR** is set and writable on Windows (e.g. `storage/app/backups`).

### 4.3 Build assets

- For “easy install,” avoid requiring Node on the target PC:
  - Run `npm run build` during **build/packaging** and ship `public/build/` in the zip or installer.
  - **install.bat** / installer then only need PHP + MySQL; no Node install on user machine.

### 4.4 PHP requirements

- Ensure embedded/portable PHP has extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, etc.
- Document in the plan or in **install.bat** comments which extensions are required so whoever prepares the PHP zip includes them.

---

## Phase 5: Delivered Artifacts (What You’ll Create)

| Artifact | Purpose |
|----------|----------|
| **run-server.bat** | Start Laravel with `php artisan serve`; used by user and by Task/Service. |
| **install.bat** | One-time: .env, DB, migrate, (optionally) build, “Add to startup?”. |
| **start-mysql.bat** / **stop-mysql.bat** | Only if you ship portable MySQL. |
| **install-autostart.bat** | Creates Task Scheduler task “At log on” → run-server.bat. |
| **uninstall-autostart.bat** | Removes that task (or the NSSM service). |
| **.env.windows.example** | Example .env for Windows (DB_HOST=127.0.0.1, APP_URL=http://localhost:8000, MYSQLDUMP_PATH=…). |
| **Optional: NSSM** | Bundle NSSM and **install-service.bat** / **remove-service.bat** for Service-based autostart. |
| **Optional: Inno Setup script** | `.iss` script to build `TBMS-Setup-x.x.exe`. |
| **Optional: Laragon “quick add”** | Small pack for “install via Laragon” users. |

---

## Phase 6: Implementation Order

1. **Path and config (Phase 4)**  
   - Add `MYSQLDUMP_PATH` (and BACKUP_DIR) to `.env.example` and use it in the backup route so Windows paths are configurable.

2. **Scripts (Phase 2 + 5)**  
   - Implement **run-server.bat** and **install.bat** for a fixed layout (e.g. `C:\tbms\php\`, `C:\tbms\app\`).  
   - Assume PHP and MySQL are already installed (e.g. XAMPP or manual) so you can test “run in background” and “autostart” quickly.

3. **Autostart (Phase 2.3)**  
   - Implement **install-autostart.bat** (Task Scheduler “At log on” → run-server.bat) and **uninstall-autostart.bat**.  
   - Test: reboot, confirm server is up without opening browser.

4. **Portable package (Phase 3.1)**  
   - Document or script “drop PHP zip here,” “drop MySQL zip here,” then run **install.bat**.  
   - Optional: single ZIP that includes PHP (+ optionally MySQL) and the app.

5. **Installer (Phase 3.2)**  
   - Add Inno Setup script and build `TBMS-Setup-x.x.exe` that copies files, runs setup, and optionally adds autostart.

6. **NSSM service (Phase 2.3, optional)**  
   - Add **install-service.bat** and **remove-service.bat** for users who want “start before logon” and service-style restarts.

---

## Summary

| Goal | How it’s achieved |
|------|-------------------|
| **Easy install on any Windows PC** | One ZIP or one EXE installer; one-time **install.bat** (or installer) does .env, DB, migrate, build. |
| **Not needed to sit on browser** | Server runs in background via Task Scheduler or NSSM; user opens browser only when using TBMS. |
| **Auto-start when PC is on** | Task Scheduler “At log on” runs **run-server.bat**, or NSSM installs a Windows Service that starts at boot. |

Next implementation steps: add **MYSQLDUMP_PATH** and BACKUP_DIR to config, then implement **run-server.bat** and **install.bat** for your chosen folder layout (e.g. `C:\tbms\` with PHP from XAMPP/Laragon first, then add portable PHP later).
