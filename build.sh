#!/usr/bin/env bash
# exit on error
set -o errexit

composer install --no-dev --optimize-autoloader

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

# Create storage link
php artisan storage:link

# Seed database if needed (optional - comment out if not needed)
# php artisan db:seed --force
