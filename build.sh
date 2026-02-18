#!/usr/bin/env bash
# exit on error
set -o errexit

composer install --no-dev --optimize-autoloader --no-interaction

# Clear and cache config
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force --no-interaction

# Run seeders (ignore errors if user already exists)
php artisan db:seed --class=UserSeeder --force || true

# Create storage link (ignore errors if already exists)
php artisan storage:link || true
