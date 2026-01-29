# Windows Setup Scripts

Helper scripts to set up and run TBMS on Windows without XAMPP.

---

## Scripts

### 1. `setup-laragon.bat` - Laragon Setup (Recommended)

Sets up TBMS with Laragon (easiest method).

**Usage:**
```cmd
cd windows
setup-laragon.bat
```

**What it does:**
- Checks if Laragon is installed
- Sets up TBMS in Laragon's www folder
- Creates .env file
- Installs dependencies
- Runs migrations
- Builds assets

**Requirements:**
- Laragon installed (https://laragon.org/download/)

---

### 2. `setup-standalone.bat` - Standalone PHP Setup

Sets up TBMS with standalone PHP and MySQL.

**Usage:**
```cmd
cd windows
setup-standalone.bat
```

**What it does:**
- Checks PHP, Composer, MySQL
- Creates .env file
- Installs dependencies
- Sets up Laravel
- Runs migrations
- Builds assets

**Requirements:**
- PHP 8.2+ installed and in PATH
- Composer installed
- MySQL installed and running

---

### 3. `start-server.bat` - Start Laravel Server

Starts the Laravel development server.

**Usage:**
```cmd
cd windows
start-server.bat
```

Or double-click the file.

**What it does:**
- Starts `php artisan serve`
- Server runs at http://localhost:8000

---

## Quick Start

### Option A: Using Laragon (Easiest)

1. **Install Laragon:**
   - Download: https://laragon.org/download/
   - Install Laragon

2. **Run setup:**
   ```cmd
   cd windows
   setup-laragon.bat
   ```

3. **Start Laragon:**
   - Open Laragon
   - Click "Start All"
   - Access: http://tbms.test

---

### Option B: Standalone PHP

1. **Install PHP:**
   - Download PHP 8.2 from https://windows.php.net/download/
   - Extract to `C:\php`
   - Add to PATH

2. **Install Composer:**
   - Download from https://getcomposer.org/download/
   - Install

3. **Install MySQL:**
   - Download from https://dev.mysql.com/downloads/installer/
   - Install MySQL Server

4. **Run setup:**
   ```cmd
   cd windows
   setup-standalone.bat
   ```

5. **Start server:**
   ```cmd
   start-server.bat
   ```

6. **Access:** http://localhost:8000

---

## Troubleshooting

**"PHP is not recognized"**
- Install PHP and add to PATH
- Or use Laragon (includes PHP)

**"Composer is not recognized"**
- Install Composer from getcomposer.org

**"MySQL connection error"**
- Ensure MySQL is running: `net start mysql`
- Check .env database credentials

**"Port 8000 already in use"**
- Use different port: `php artisan serve --port=8001`

---

## Recommended: Laragon

For Windows Laravel development, **Laragon is the best choice:**
- ✅ One-click install
- ✅ Auto-configured
- ✅ Includes everything (PHP, MySQL, Redis, etc.)
- ✅ Easy virtual hosts
- ✅ No manual configuration

---

## Next Steps

1. Choose setup method (Laragon recommended)
2. Run appropriate setup script
3. Start server
4. Access TBMS in browser
