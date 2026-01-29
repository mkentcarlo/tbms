# Building the TBMS Windows .exe Installer

This document explains how to build **TBMS-Setup-1.0.exe** so you can distribute TBMS as a single Windows installer.

---

## Prerequisites

1. **Windows PC** (10 or 11)
2. **Inno Setup 6** – [Download](https://jrsoftware.org/isdl.php) and install
3. **PHP 8.2+** on PATH (e.g. XAMPP, Laragon, or standalone PHP)
4. **Composer** – [getcomposer.org](https://getcomposer.org/)
5. **Node.js 18+** and npm (for building frontend assets)
6. **MySQL** running (for migrations; can be XAMPP/Laragon)

---

## Steps to Build the .exe

### 1. Prepare the project

From the **project root** (where `artisan` and `composer.json` are):

```cmd
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

- `composer install --no-dev` – installs production PHP dependencies into `vendor/`
- `npm run build` – builds frontend assets into `public/build/`

The installer **includes** `vendor/` and `public/build/`, so end users do not need Composer or Node.js.

### 2. (Optional) Create .env.example for Windows

Ensure `.env.example` exists and has Windows-friendly defaults. The repo has `.env.xample`; the installer copies it as `.env.example`. For Windows deployments you may want:

```env
APP_URL=http://localhost:8000
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tbms
DB_USERNAME=root
DB_PASSWORD=
```

End users can edit `.env` after install (or use the first-time setup).

### 3. Build the installer with Inno Setup

1. Open **Inno Setup Compiler**
2. **File → Open** and choose:
   ```
   <project-root>\installer\TBMS-Setup.iss
   ```
3. **Build → Compile** (or press Ctrl+F9)

Or from a **Command Prompt** (with Inno Setup in PATH):

```cmd
cd <project-root>
"C:\Program Files (x86)\Inno Setup 6\ISCC.exe" installer\TBMS-Setup.iss
```

### 4. Output

The compiled installer is:

```
<project-root>\installer\Output\TBMS-Setup-1.0.exe
```

Use this single `.exe` for distribution. End users run it to install TBMS to `C:\TBMS` (or a folder they choose).

---

## What the installer does

- Copies the Laravel app (including `vendor/` and `public/build/`) to the chosen folder (default `C:\TBMS`)
- Copies `run-server.bat`, `install.bat`, `install-autostart.bat`, `uninstall-autostart.bat`
- Offers **“Start TBMS when Windows starts”** – if checked, adds a Task Scheduler task so the server runs at logon
- Offers **“Create desktop shortcut”** – shortcut to open http://localhost:8000
- Creates Start Menu shortcuts:
  - **TBMS - Open in browser** – opens http://localhost:8000
  - **TBMS - Start server** – runs the server (for testing or if autostart is off)
  - **TBMS - Add to Windows startup** / **Remove from startup**
- Runs first-time setup: creates `.env` from `.env.example`, `php artisan key:generate`, `php artisan migrate`
- On uninstall, removes the “TBMS” startup task

---

## End-user requirements

- **Windows 10 or 11**
- **PHP 8.2+** on system PATH, or a `php` folder next to the install (e.g. `C:\TBMS\php\` with `php.exe` inside)
- **MySQL** (or MariaDB) installed and running; `.env` must have correct `DB_*` values
- **Run installer as Administrator** so it can install to `C:\TBMS` and create the startup task

---

## Optional: Bundle PHP in the installer

To avoid requiring users to install PHP:

1. Download a **thread-safe** PHP 8.2 Windows zip from [windows.php.net](https://windows.php.net/download/)
2. Unzip it into `<project-root>\installer\php\` so that `<project-root>\installer\php\php.exe` exists
3. Add to `TBMS-Setup.iss` in the `[Files]` section:

   ```iss
   Source: "php\*"; DestDir: "{app}\php"; Flags: ignoreversion recursesubdirs createallsubdirs; Excludes: "*.pdb"
   ```

4. Rebuild the installer. The batch scripts already look for `php\php.exe` in the install folder and use it if present.

---

## Troubleshooting

| Issue | Fix |
|------|-----|
| “vendor folder not found” when compiling | Run `composer install` in the project root before building. |
| “public/build missing” | Run `npm run build` before building the installer. |
| ISCC not found | Use the full path to `ISCC.exe` or add Inno Setup’s directory to PATH. |
| Installer runs but migrations fail on user PC | User must have MySQL running and correct `DB_*` in `.env`. They can run `install.bat` again after fixing `.env`. |
| Server doesn’t start at login | User can run “TBMS - Add to Windows startup” as Administrator, or check Task Scheduler for a “TBMS” task. |

---

## Version number

To change the version in the installer and filename, edit `installer\TBMS-Setup.iss`:

```iss
#define MyAppVersion "1.0"
```

Then rebuild. The output file will be `TBMS-Setup-1.0.exe` (or whatever you set).
