#!/bin/bash

# MAM Tours - Production Deployment Script
# This script automates the deployment process

echo "🚀 Starting MAM Tours Deployment..."

# Exit on any error
set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Check if .env file exists
if [ ! -f .env ]; then
    print_error ".env file not found!"
    print_info "Copy .env.production to .env and configure it first"
    exit 1
fi

print_success ".env file found"

# Put application in maintenance mode
print_info "Putting application in maintenance mode..."
php artisan down || true
print_success "Application in maintenance mode"

# Pull latest code from git
print_info "Pulling latest code from repository..."
git pull origin main
print_success "Code updated"

# Install/Update Composer dependencies
print_info "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader
print_success "Composer dependencies installed"

# Install/Update NPM dependencies
print_info "Installing NPM dependencies..."
npm ci --production
print_success "NPM dependencies installed"

# Build frontend assets
print_info "Building frontend assets..."
npm run build
print_success "Frontend assets built"

# Clear all caches
print_info "Clearing application caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
print_success "Caches cleared"

# Run database migrations
print_info "Running database migrations..."
php artisan migrate --force
print_success "Database migrations completed"

# Optimize application
print_info "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Application optimized"

# Create storage link if not exists
print_info "Creating storage symlink..."
php artisan storage:link || true
print_success "Storage symlink created"

# Set proper permissions
print_info "Setting file permissions..."
chmod -R 755 storage bootstrap/cache
print_success "Permissions set"

# Restart queue workers (if using supervisor)
print_info "Restarting queue workers..."
php artisan queue:restart || true
print_success "Queue workers restarted"

# Bring application back online
print_info "Bringing application back online..."
php artisan up
print_success "Application is now live!"

echo ""
print_success "🎉 Deployment completed successfully!"
echo ""
print_info "Next steps:"
echo "  1. Test the application at your domain"
echo "  2. Check logs for any errors: tail -f storage/logs/laravel.log"
echo "  3. Monitor queue workers: php artisan queue:work"
echo ""
