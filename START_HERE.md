# 🚀 Start Here - Docker Setup

## Quick Start (3 Steps)

### Step 1: Start Docker Desktop
Make sure Docker Desktop is running on your machine.

### Step 2: Run the Setup Script
```bash
./setup-docker.sh
```

This script will:
- ✅ Build and start all Docker containers
- ✅ Install PHP and Node dependencies
- ✅ Create and configure `.env` file
- ✅ Generate application key
- ✅ Set proper permissions
- ✅ Clear caches

### Step 3: Run Migrations & Build Assets
```bash
# Run database migrations
docker-compose exec app php artisan migrate

# Build frontend assets
docker-compose exec app npm run dev
```

## 🎉 You're Done!

Access your application at:
- **Web App**: http://localhost:8085
- **phpMyAdmin**: http://localhost:8081

## 📚 Need Help?

- **Detailed Setup**: See `DOCKER_SETUP.md`
- **Quick Reference**: See `DOCKER_QUICK_REFERENCE.md`
- **Laravel Upgrade**: See `QUICK_START.md` and `UPGRADE_GUIDE.md`

## 🔧 Manual Setup (Alternative)

If you prefer to set up manually:

```bash
# 1. Start containers
docker-compose up -d --build

# 2. Install dependencies
docker-compose exec app composer install
docker-compose exec app npm install

# 3. Setup environment
cp .env.docker.example .env
docker-compose exec app php artisan key:generate

# 4. Set permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache

# 5. Run migrations
docker-compose exec app php artisan migrate

# 6. Build assets
docker-compose exec app npm run dev
```

## ⚠️ Troubleshooting

**Docker not running?**
- Start Docker Desktop
- Wait for it to fully start
- Try again

**Port already in use?**
- Edit `docker-compose.yml` and change ports:
  - `8085:80` → `8086:80` (or another port)
  - `3306:3306` → `3307:3306` (or another port)

**Permission errors?**
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

**Need to rebuild?**
```bash
docker-compose down
docker-compose up -d --build
```

