#!/usr/bin/env bash
# exit on error
set -o errexit

# Install PHP dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies
npm ci

# Build frontend assets
npm run build

# Cache Laravel config
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Build completed successfully!"
