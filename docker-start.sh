#!/bin/bash

# Docker Startup Script for Laravel 11

echo "🚀 Starting Laravel 11 Docker Environment..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker Desktop first."
    exit 1
fi

# Build and start containers
echo "📦 Building and starting containers..."
docker-compose up -d --build

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
sleep 10

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    docker-compose exec -T app cp .env.example .env 2>/dev/null || echo "⚠️  .env.example not found, please create .env manually"
fi

# Install dependencies if vendor doesn't exist
if [ ! -d "vendor" ]; then
    echo "📥 Installing PHP dependencies..."
    docker-compose exec app composer install
fi

# Install Node dependencies if node_modules doesn't exist
if [ ! -d "node_modules" ]; then
    echo "📥 Installing Node dependencies..."
    docker-compose exec app npm install
fi

# Generate app key if not set
echo "🔑 Checking application key..."
docker-compose exec app php artisan key:generate --force 2>/dev/null || echo "⚠️  Key already exists or .env not configured"

# Set permissions
echo "🔐 Setting permissions..."
docker-compose exec app chmod -R 775 storage bootstrap/cache 2>/dev/null || true
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

echo ""
echo "✅ Docker environment is ready!"
echo ""
echo "📍 Access your application at:"
echo "   - Web App: http://localhost:8085"
echo "   - phpMyAdmin: http://localhost:8081"
echo ""
echo "📋 Next steps:"
echo "   1. Update .env file with database credentials:"
echo "      DB_HOST=mysql"
echo "      DB_DATABASE=laravel"
echo "      DB_USERNAME=user"
echo "      DB_PASSWORD=password"
echo ""
echo "   2. Run migrations:"
echo "      docker-compose exec app php artisan migrate"
echo ""
echo "   3. Build frontend assets:"
echo "      docker-compose exec app npm run dev"
echo ""
echo "   4. Install Breeze (if not done):"
echo "      docker-compose exec app composer require laravel/breeze --dev"
echo "      docker-compose exec app php artisan breeze:install blade"
echo ""
echo "📚 For more information, see DOCKER_SETUP.md"

