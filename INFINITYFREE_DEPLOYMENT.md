InfinityFree Deployment Guide

Simple, traditional hosting that actually works for Laravel.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

STEP 1: SIGN UP FOR INFINITYFREE

1. Go to https://infinityfree.net
2. Click "Sign Up Now"
3. Create account (no credit card needed)
4. Create a new hosting account
5. Choose a subdomain (e.g., mamtours.infinityfreeapp.com)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

STEP 2: PREPARE YOUR FILES LOCALLY

Run these commands in your project folder:

  composer install --no-dev --optimize-autoloader
  npm run build
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

STEP 3: CREATE DATABASE

In InfinityFree control panel:

1. Go to "MySQL Databases"
2. Click "Create Database"
3. Database name: mam_tours
4. Create a database user
5. Save these credentials:
   - Database name
   - Database username
   - Database password
   - Database host (usually sql###.infinityfreeapp.com)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

STEP 4: UPDATE .ENV FILE

Create a new .env file with these settings:

  APP_NAME="MAM Tours"
  APP_ENV=production
  APP_KEY=base64:KKmN0zpxMoDU1DkQa8ByHyZ/UD0b3+EoeKrJ3uRaktg=
  APP_DEBUG=false
  APP_URL=https://your-subdomain.infinityfreeapp.com
  
  DB_CONNECTION=mysql
  DB_HOST=sql###.infinityfreeapp.com
  DB_PORT=3306
  DB_DATABASE=your_database_name
  DB_USERNAME=your_database_username
  DB_PASSWORD=your_database_password
  
  SESSION_DRIVER=file
  CACHE_DRIVER=file
  QUEUE_CONNECTION=sync
  
  MAIL_FROM_ADDRESS=noreply@mamtours.com
  MAIL_ADMIN_EMAIL=wilberofficial2001@gmail.com

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

STEP 5: UPLOAD FILES

Option A: Using File Manager (Easier)

1. In control panel, go to "File Manager"
2. Navigate to htdocs folder
3. Delete everything in htdocs
4. Upload all your project files to htdocs
5. Make sure .env file is uploaded

Option B: Using FTP

1. Get FTP credentials from control panel
2. Use FileZilla or any FTP client
3. Connect to your account
4. Upload all files to htdocs folder

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

STEP 6: FIX PUBLIC FOLDER

Laravel needs the public folder to be the web root.

1. In File Manager, go to htdocs
2. Move everything from public/ folder to htdocs/
3. Update index.php:

Change this line:
  require __DIR__.'/../vendor/autoload.php';
To:
  require __DIR__.'/vendor/autoload.php';

Change this line:
  $app = require_once __DIR__.'/../bootstrap/app.php';
To:
  $app = require_once __DIR__.'/bootstrap/app.php';

4. Delete the now-empty public folder

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

STEP 7: SET PERMISSIONS

In File Manager:

1. Right-click storage folder → Permissions → 755
2. Right-click bootstrap/cache → Permissions → 755

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

STEP 8: RUN MIGRATIONS

You'll need to run migrations manually. Two options:

Option A: Create a temporary migration script

Create migrate.php in htdocs:

  <?php
  require __DIR__.'/vendor/autoload.php';
  $app = require_once __DIR__.'/bootstrap/app.php';
  $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
  $kernel->call('migrate', ['--force' => true]);
  echo "Migrations completed!";
  ?>

Visit: https://your-subdomain.infinityfreeapp.com/migrate.php
Then DELETE migrate.php immediately for security!

Option B: Use phpMyAdmin

1. Go to phpMyAdmin in control panel
2. Import the SQL file from your local database
3. Or manually run migrations from your local machine

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

STEP 9: CREATE ADMIN ACCOUNT

Create admin.php in htdocs:

  <?php
  require __DIR__.'/vendor/autoload.php';
  $app = require_once __DIR__.'/bootstrap/app.php';
  $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
  $kernel->call('admin:create', [
      'name' => 'Admin',
      'email' => 'wilberofficial2001@gmail.com',
      'password' => 'your_password'
  ]);
  echo "Admin created!";
  ?>

Visit: https://your-subdomain.infinityfreeapp.com/admin.php
Then DELETE admin.php immediately!

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

STEP 10: TEST YOUR SITE

Visit: https://your-subdomain.infinityfreeapp.com

You should see your MAM Tours homepage!

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

TROUBLESHOOTING

500 Error:
- Check storage and bootstrap/cache permissions (755)
- Verify .env file exists and has correct database credentials
- Check error logs in control panel

Database Connection Error:
- Verify database credentials in .env
- Make sure database user has all privileges
- Check DB_HOST is correct

Images Not Loading:
- Make sure all files from public/ are in htdocs/
- Check image paths in your code

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

LIMITATIONS

Free tier includes:
- 5GB disk space
- Unlimited bandwidth
- MySQL database
- PHP 8.x support
- Free SSL certificate

Limitations:
- Ads on free plan (can upgrade to remove)
- No SSH access
- Limited CPU/memory
- Some functions disabled for security

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

This is the simplest way to get your Laravel app online for free!
