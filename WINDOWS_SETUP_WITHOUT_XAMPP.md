# Running TBMS on Windows Without XAMPP

This guide shows you how to run TBMS Laravel app on Windows without XAMPP, using standalone PHP and MySQL.

---

## Option 1: Laragon (Recommended - Easiest)

**Laragon** is a modern Windows development environment specifically designed for Laravel.

### Installation

1. **Download Laragon:**
   - Visit: https://laragon.org/download/
   - Download Laragon Full (includes PHP, MySQL, Nginx, etc.)

2. **Install Laragon:**
   - Run the installer
   - Choose installation directory (default: `C:\laragon`)
   - Install

3. **Setup TBMS:**
   ```cmd
   # Copy TBMS to Laragon's www folder
   # Or create a symlink
   ```

4. **Start Laragon:**
   - Open Laragon
   - Click "Start All"
   - Your app runs at: `http://tbms.test` (or `http://localhost`)

**Pros:** ✅ Easy setup, auto-configured, great for Laravel  
**Cons:** None really, it's perfect for this use case

---

## Option 2: Standalone PHP + MySQL

### Step 1: Install PHP

1. **Download PHP 8.2:**
   - Visit: https://windows.php.net/download/
   - Download PHP 8.2 Thread Safe (ZIP)
   - Extract to `C:\php`

2. **Configure PHP:**
   - Copy `php.ini-development` to `php.ini`
   - Edit `php.ini` and uncomment:
     ```ini
     extension=mysqli
     extension=pdo_mysql
     extension=mbstring
     extension=openssl
     extension=curl
     extension=fileinfo
     extension=gd
     extension=intl
     extension=zip
     ```

3. **Add PHP to PATH:**
   - Add `C:\php` to Windows PATH environment variable

### Step 2: Install MySQL

1. **Download MySQL:**
   - Visit: https://dev.mysql.com/downloads/installer/
   - Download MySQL Installer for Windows
   - Install MySQL Server 8.0+

2. **Configure MySQL:**
   - Set root password
   - Create database: `tbms`
   - Create user: `tbms_user` with password

### Step 3: Install Composer

1. **Download Composer:**
   - Visit: https://getcomposer.org/download/
   - Download `Composer-Setup.exe`
   - Install (it will detect PHP automatically)

### Step 4: Setup TBMS

```cmd
# Navigate to TBMS folder
cd C:\path\to\tbms

# Copy .env file
copy .env.example .env

# Edit .env with your database settings:
# DB_HOST=127.0.0.1
# DB_DATABASE=tbms
# DB_USERNAME=tbms_user
# DB_PASSWORD=your_password

# Install dependencies
composer install

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Build frontend assets
npm install
npm run build
```

### Step 5: Run Laravel

```cmd
php artisan serve
```

App runs at: `http://localhost:8000`

---

## Option 3: Docker (If You Have Docker Desktop)

### Quick Start

1. **Install Docker Desktop for Windows:**
   - Download from: https://www.docker.com/products/docker-desktop

2. **Run TBMS:**
   ```cmd
   cd C:\path\to\tbms
   docker-compose up -d
   ```

3. **Access app:**
   - App: http://localhost:8085
   - phpMyAdmin: http://localhost:8081

**Note:** Docker setup files already exist in your project!

---

## Option 4: PHP Built-in Server + MySQL (Simplest)

If you only need PHP and MySQL (no Apache/Nginx):

### Setup

1. **Install PHP** (see Option 2, Step 1)

2. **Install MySQL** (see Option 2, Step 2)

3. **Run TBMS:**
   ```cmd
   cd C:\path\to\tbms
   php artisan serve --host=0.0.0.0 --port=8000
   ```

4. **Access:** http://localhost:8000

**Pros:** ✅ Simple, no web server config needed  
**Cons:** ⚠️ PHP built-in server is for development only

---

## Recommended: Laragon

**For Windows Laravel development, Laragon is the best choice:**

- ✅ One-click install
- ✅ Auto-configured for Laravel
- ✅ Includes PHP, MySQL, Redis, etc.
- ✅ Auto virtual hosts (`http://tbms.test`)
- ✅ Easy database management
- ✅ No manual configuration needed

---

## Quick Setup Scripts

I've created helper scripts in `windows/` folder to automate setup.

---

## Comparison

| Method | Difficulty | Setup Time | Best For |
|--------|-----------|------------|----------|
| **Laragon** | ⭐ Easy | 10 min | Most users |
| **Standalone PHP+MySQL** | ⭐⭐ Medium | 30 min | Learning |
| **Docker** | ⭐⭐ Medium | 15 min | Consistency |
| **PHP Built-in Server** | ⭐ Easy | 20 min | Quick testing |

---

## Next Steps

1. **Choose a method** (Laragon recommended)
2. **Follow setup steps** above
3. **Run TBMS** and access at the provided URL

---

## Troubleshooting

**PHP not found:**
- Add PHP to PATH: `C:\php`

**MySQL connection error:**
- Check MySQL is running: `net start mysql`
- Verify credentials in `.env`

**Composer not found:**
- Reinstall Composer or add to PATH

**Port 8000 already in use:**
- Use different port: `php artisan serve --port=8001`
