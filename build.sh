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
php artisan db:seed --class=CarSeeder --force || true

# Insert sample cars if none exist
php artisan tinker --execute="
if (\App\Models\Car::count() === 0) {
    \App\Models\Car::create(['brand' => 'Toyota', 'model' => 'Hilux', 'numberPlate' => 'UAA001A', 'dailyRate' => 200000, 'seats' => 5, 'category' => 'SUV', 'isAvailable' => true]);
    \App\Models\Car::create(['brand' => 'Toyota', 'model' => 'Land Cruiser', 'numberPlate' => 'UAB002B', 'dailyRate' => 350000, 'seats' => 7, 'category' => 'SUV', 'isAvailable' => true]);
    \App\Models\Car::create(['brand' => 'Toyota', 'model' => 'RAV4', 'numberPlate' => 'UAC003C', 'dailyRate' => 150000, 'seats' => 5, 'category' => 'SUV', 'isAvailable' => true]);
    echo 'Sample cars created';
}
" || true

# Create storage link and ensure directories exist
php artisan storage:link || true
mkdir -p storage/app/public/cars
chmod -R 775 storage
