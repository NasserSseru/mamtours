#!/usr/bin/env bash

# Run database migrations
php artisan migrate --force

# Start the Laravel server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
