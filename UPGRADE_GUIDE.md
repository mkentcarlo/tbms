# Laravel 11 & Breeze Upgrade Guide

This document outlines the changes made to upgrade your Laravel project from version 8.0 to 11.x and migrate from CoreUI/Laravel UI to Laravel Breeze.

## ✅ Completed Changes

### 1. Composer Dependencies Updated
- Updated `composer.json`:
  - PHP requirement: `^7.2.5` → `^8.2` (required for Laravel 11)
  - Laravel Framework: `^8.0` → `^11.0`
  - Removed `fideloper/proxy` (no longer needed)
  - Removed `fruitcake/laravel-cors` (now part of framework)
  - Updated `spatie/laravel-medialibrary`: `^8.7.2` → `^11.0`
  - Updated `spatie/laravel-permission`: `^3.17` → `^6.0`
  - Removed `laravel/ui` (replaced with Breeze)
  - Updated dev dependencies (PHPUnit, Collision, etc.)

### 2. Frontend Dependencies Updated
- Updated `package.json`:
  - Replaced Laravel Mix with Vite
  - Removed CoreUI dependencies
  - Added Tailwind CSS and related packages
  - Added Laravel Vite plugin

### 3. Configuration Files Created
- Created `vite.config.js` for Vite build system
- Created `tailwind.config.js` for Tailwind CSS
- Created `postcss.config.js` for PostCSS processing
- Created `resources/css/app.css` with Tailwind directives
- Updated `resources/js/app.js` and `bootstrap.js` for ES6 modules

### 4. Middleware Updated
- Updated `CheckForMaintenanceMode` → `PreventRequestsDuringMaintenance`
- Updated `TrustProxies` to use Laravel's built-in middleware
- Updated `VerifyCsrfToken` (removed deprecated `addHttpCookie`)
- Created `ValidateSignature` middleware (required in Laravel 11)
- Updated `Kernel.php`:
  - Changed `routeMiddleware` → `middlewareAliases`
  - Updated API middleware group structure
  - Removed deprecated `middlewarePriority`

## 🔄 Next Steps (Manual Actions Required)

### Step 1: Install Composer Dependencies
```bash
composer update
```

**Note:** You may encounter dependency conflicts. If so, you may need to:
- Update other packages that depend on older Laravel versions
- Check for breaking changes in Spatie packages

### Step 2: Install Laravel Breeze
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
```

This will:
- Install Breeze authentication scaffolding
- Create new authentication views
- Update routes
- Create layout files

### Step 3: Install NPM Dependencies
```bash
npm install
```

### Step 4: Build Assets
```bash
npm run dev
# or for production
npm run build
```

### Step 5: Update Routes
After installing Breeze, you'll need to:
1. Remove `Auth::routes()` from `routes/web.php` (Breeze creates its own routes)
2. Update any custom authentication routes to match Breeze's structure
3. Update route references in your controllers

### Step 6: Update Blade Layouts
The main layout file (`resources/views/dashboard/base.blade.php`) still uses CoreUI. You have two options:

**Option A: Gradual Migration**
- Keep CoreUI for existing dashboard pages
- Use Breeze layout for new pages
- Gradually migrate pages one by one

**Option B: Full Migration**
- Replace `dashboard/base.blade.php` with Breeze's layout
- Update all dashboard views to use Tailwind CSS classes
- This is a significant undertaking but provides a modern, consistent UI

### Step 7: Update Controllers
- Check `LoginController` and `RegisterController` - Breeze will create new versions
- Update any controllers that reference old authentication methods
- Update redirect paths if needed

### Step 8: Database Migrations
Run migrations to ensure database is up to date:
```bash
php artisan migrate
```

### Step 9: Update Environment Variables
Check your `.env` file for any deprecated variables. Laravel 11 may have changed some defaults.

### Step 10: Test Everything
- Test authentication (login, register, password reset)
- Test all dashboard functionality
- Check for any broken routes or views
- Verify middleware is working correctly

## ⚠️ Breaking Changes to Watch For

1. **Route Model Binding**: Laravel 11 uses implicit route model binding by default
2. **Middleware**: Some middleware syntax has changed
3. **Service Providers**: Some providers may need updates
4. **Config Files**: Some config files have been removed or consolidated
5. **Eloquent**: Some Eloquent methods have changed

## 📝 Additional Notes

- The old `webpack.mix.js` file is still present but can be removed after confirming Vite works
- CoreUI assets in `public/` can be removed after full migration
- The `build/` directory contains old CoreUI build scripts that can be removed

## 🆘 Troubleshooting

If you encounter issues:

1. **Composer conflicts**: Use `composer why-not laravel/framework 11.0` to identify blockers
2. **Missing classes**: Run `composer dump-autoload`
3. **Asset issues**: Clear cache with `php artisan cache:clear` and `php artisan config:clear`
4. **Route issues**: Run `php artisan route:clear`

## 📚 Resources

- [Laravel 11 Upgrade Guide](https://laravel.com/docs/11.x/upgrade)
- [Laravel Breeze Documentation](https://laravel.com/docs/11.x/starter-kits#laravel-breeze)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

