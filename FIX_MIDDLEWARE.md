# Fix: "Target class [get.menu] does not exist"

This error occurs when Laravel can't resolve the middleware alias. Here's how to fix it:

## Quick Fix (Run These Commands)

### If running locally (not Docker):
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Regenerate autoloader
composer dump-autoload

# Clear optimization cache (Laravel 11)
php artisan optimize:clear
```

### If running in Docker:
```bash
# Clear all caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Regenerate autoloader
docker-compose exec app composer dump-autoload

# Clear optimization cache
docker-compose exec app php artisan optimize:clear
```

## Alternative: Use the Fix Script

```bash
# Local
./fix-middleware.sh

# Docker
docker-compose exec app bash -c "./fix-middleware.sh"
```

## Verify Middleware Registration

The middleware should be registered in `app/Http/Kernel.php`:

```php
protected $middlewareAliases = [
    // ... other middleware
    'get.menu' => \App\Http\Middleware\GetMenu::class,
    // ... other middleware
];
```

## If Still Not Working

1. **Check if the middleware file exists:**
   ```bash
   ls -la app/Http/Middleware/GetMenu.php
   ```

2. **Verify the namespace is correct:**
   The file should have: `namespace App\Http\Middleware;`

3. **Check if composer autoload is working:**
   ```bash
   composer dump-autoload -v
   ```

4. **Restart the web server:**
   - If using PHP built-in server: Stop and restart
   - If using Docker: `docker-compose restart`
   - If using Nginx/Apache: Restart the service

5. **Check for syntax errors:**
   ```bash
   php -l app/Http/Middleware/GetMenu.php
   ```

## Common Causes

1. **Cached configuration** - Fixed by `php artisan config:clear`
2. **Stale autoloader** - Fixed by `composer dump-autoload`
3. **Optimized cache** - Fixed by `php artisan optimize:clear`
4. **Web server needs restart** - Restart your web server

## Still Having Issues?

If the problem persists after clearing caches:

1. Check that `app/Http/Middleware/GetMenu.php` exists and has correct namespace
2. Verify `app/Http/Kernel.php` has the middleware alias registered
3. Make sure you've run `composer install` after the Laravel 11 upgrade
4. Check Laravel version: `php artisan --version` (should be 11.x)

