#!/usr/bin/env bash

# Clear and optimize
php artisan config:clear
php artisan cache:clear

# Run database migrations
php artisan migrate --force --no-interaction

# Run seeders (ignore errors if user already exists)
php artisan db:seed --class=UserSeeder --force || true

# Create storage link
php artisan storage:link || true

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
