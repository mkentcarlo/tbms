#!/bin/bash

# Fix Middleware Issues Script

echo "🔧 Fixing middleware registration issues..."

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear 2>/dev/null || echo "Config clear skipped"
php artisan cache:clear 2>/dev/null || echo "Cache clear skipped"
php artisan route:clear 2>/dev/null || echo "Route clear skipped"
php artisan view:clear 2>/dev/null || echo "View clear skipped"

# Regenerate autoloader
echo "🔄 Regenerating autoloader..."
composer dump-autoload 2>/dev/null || echo "Autoloader regeneration skipped"

# Optimize (if available)
php artisan optimize:clear 2>/dev/null || echo "Optimize clear skipped"

echo "✅ Done! Try accessing your application again."

