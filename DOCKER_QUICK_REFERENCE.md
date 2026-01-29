# Docker Quick Reference

## 🚀 Quick Start

```bash
# Start everything (recommended first time)
./docker-start.sh

# Or manually:
docker-compose up -d --build
```

## 📍 Access Points

- **Application**: http://localhost:8085
- **phpMyAdmin**: http://localhost:8081
- **MySQL**: localhost:3306
- **Vite Dev Server**: http://localhost:5173 (when running)

## 🔧 Common Commands

### Start/Stop
```bash
docker-compose up -d          # Start in background
docker-compose stop            # Stop containers
docker-compose down            # Stop and remove containers
docker-compose restart         # Restart containers
```

### Artisan Commands
```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan config:clear
```

### Composer
```bash
docker-compose exec app composer install
docker-compose exec app composer update
docker-compose exec app composer require package/name
```

### NPM
```bash
docker-compose exec app npm install
docker-compose exec app npm run dev      # Development
docker-compose exec app npm run build    # Production
```

### Shell Access
```bash
docker-compose exec app bash
docker-compose exec mysql bash
docker-compose exec nginx sh
```

### Logs
```bash
docker-compose logs -f          # All services
docker-compose logs -f app      # PHP app only
docker-compose logs -f nginx    # Nginx only
docker-compose logs -f mysql    # MySQL only
```

### Database
```bash
# Access MySQL CLI
docker-compose exec mysql mysql -u user -ppassword laravel

# Backup database
docker-compose exec mysql mysqldump -u user -ppassword laravel > backup.sql

# Restore database
docker-compose exec -T mysql mysql -u user -ppassword laravel < backup.sql
```

## 🐛 Troubleshooting

### Rebuild Everything
```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

### Check Status
```bash
docker-compose ps
docker-compose logs
```

### Fix Permissions
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Clear Everything
```bash
docker-compose down -v
docker system prune -a
```

## 📝 Environment Variables

Default database credentials in `docker-compose.yml`:
- **Host**: mysql
- **Database**: laravel
- **Username**: user
- **Password**: password
- **Root Password**: rootpassword

Update `.env` file:
```env
DB_HOST=mysql
DB_DATABASE=laravel
DB_USERNAME=user
DB_PASSWORD=password
```

## 🎯 Development Workflow

1. **Start containers**: `docker-compose up -d`
2. **Install dependencies**: `docker-compose exec app composer install && npm install`
3. **Setup environment**: Copy `.env.example` to `.env` and configure
4. **Generate key**: `docker-compose exec app php artisan key:generate`
5. **Run migrations**: `docker-compose exec app php artisan migrate`
6. **Build assets**: `docker-compose exec app npm run dev`
7. **Access app**: http://localhost:8085

## 🔥 Vite Hot Reload (Development)

```bash
# Start Vite dev server
docker-compose --profile dev up vite

# Or start everything with Vite
docker-compose --profile dev up
```

Then your app will have hot module replacement enabled!

