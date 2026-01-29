#!/bin/bash

# Comprehensive Docker Setup Script for Laravel 11

set -e

echo "🚀 Laravel 11 Docker Setup Script"
echo "=================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if Docker is running
echo "📋 Checking Docker..."
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}❌ Docker is not running!${NC}"
    echo "Please start Docker Desktop and try again."
    exit 1
fi
echo -e "${GREEN}✅ Docker is running${NC}"
echo ""

# Check if docker-compose is available
if ! command -v docker-compose &> /dev/null; then
    echo -e "${RED}❌ docker-compose not found!${NC}"
    exit 1
fi
echo -e "${GREEN}✅ docker-compose is available${NC}"
echo ""

# Step 1: Build and start containers
echo "📦 Step 1: Building and starting containers..."
docker-compose up -d --build
echo -e "${GREEN}✅ Containers started${NC}"
echo ""

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready (30 seconds)..."
sleep 30
echo ""

# Step 2: Check/Create .env file
echo "📝 Step 2: Setting up environment file..."
if [ ! -f .env ]; then
    if [ -f .env.docker.example ]; then
        cp .env.docker.example .env
        echo -e "${GREEN}✅ Created .env from .env.docker.example${NC}"
    else
        echo -e "${YELLOW}⚠️  .env.docker.example not found, creating basic .env${NC}"
        cat > .env << EOF
APP_NAME=TBMS
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8085

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=user
DB_PASSWORD=password
EOF
    fi
else
    echo -e "${YELLOW}⚠️  .env file already exists, skipping...${NC}"
fi
echo ""

# Step 3: Install PHP dependencies
echo "📥 Step 3: Installing PHP dependencies..."
if [ ! -d "vendor" ]; then
    docker-compose exec -T app composer install --no-interaction
    echo -e "${GREEN}✅ PHP dependencies installed${NC}"
else
    echo -e "${YELLOW}⚠️  vendor directory exists, skipping composer install${NC}"
    echo "   Run 'docker-compose exec app composer install' if needed"
fi
echo ""

# Step 4: Install Node dependencies
echo "📥 Step 4: Installing Node dependencies..."
if [ ! -d "node_modules" ]; then
    docker-compose exec -T app npm install
    echo -e "${GREEN}✅ Node dependencies installed${NC}"
else
    echo -e "${YELLOW}⚠️  node_modules exists, skipping npm install${NC}"
    echo "   Run 'docker-compose exec app npm install' if needed"
fi
echo ""

# Step 5: Generate application key
echo "🔑 Step 5: Generating application key..."
docker-compose exec -T app php artisan key:generate --force || echo -e "${YELLOW}⚠️  Key generation skipped (may already exist)${NC}"
echo ""

# Step 6: Set permissions
echo "🔐 Step 6: Setting permissions..."
docker-compose exec -T app chmod -R 775 storage bootstrap/cache 2>/dev/null || true
docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
echo -e "${GREEN}✅ Permissions set${NC}"
echo ""

# Step 7: Clear caches
echo "🧹 Step 7: Clearing caches..."
docker-compose exec -T app php artisan config:clear 2>/dev/null || true
docker-compose exec -T app php artisan cache:clear 2>/dev/null || true
docker-compose exec -T app php artisan route:clear 2>/dev/null || true
docker-compose exec -T app php artisan view:clear 2>/dev/null || true
echo -e "${GREEN}✅ Caches cleared${NC}"
echo ""

# Summary
echo "=================================="
echo -e "${GREEN}✅ Setup Complete!${NC}"
echo "=================================="
echo ""
echo "📍 Access your application:"
echo "   🌐 Web App: http://localhost:8085"
echo "   🗄️  phpMyAdmin: http://localhost:8081"
echo ""
echo "📋 Next steps:"
echo "   1. Update .env file if needed (database credentials are already set)"
echo "   2. Run migrations:"
echo "      docker-compose exec app php artisan migrate"
echo ""
echo "   3. Build frontend assets:"
echo "      docker-compose exec app npm run dev"
echo ""
echo "   4. Install Laravel Breeze (optional):"
echo "      docker-compose exec app composer require laravel/breeze --dev"
echo "      docker-compose exec app php artisan breeze:install blade"
echo ""
echo "📚 Useful commands:"
echo "   View logs: docker-compose logs -f"
echo "   Stop: docker-compose stop"
echo "   Restart: docker-compose restart"
echo "   Shell access: docker-compose exec app bash"
echo ""
echo "📖 See DOCKER_SETUP.md for detailed documentation"

