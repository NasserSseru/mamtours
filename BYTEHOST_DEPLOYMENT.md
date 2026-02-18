Bytehost Laravel Deployment Guide

This guide walks you through deploying your Laravel application to Bytehost free hosting.

What You Need Before Starting

1. Bytehost account (already created)
2. Your project files ready to upload
3. Database credentials from Bytehost cPanel

Step 1: Prepare Your Files for Upload

Before uploading, you need to create a ZIP file of your project. Here's what to include:

Include These Folders and Files:
- app/
- bootstrap/
- config/
- database/
- public/
- resources/
- routes/
- storage/
- vendor/ (if you already ran composer install locally)
- artisan
- composer.json
- composer.lock
- .htaccess (from public folder)

DO NOT Include:
- .git/
- .github/
- node_modules/
- tests/
- .env (you'll create this on the server)
- *.md files (documentation)
- .kiro/
- .vscode/

Step 2: Create ZIP File

On Windows:
1. Select all the folders and files listed above
2. Right-click and choose "Send to" > "Compressed (zipped) folder"
3. Name it something like "mamtours.zip"

Step 3: Upload to Bytehost

1. Log into your Bytehost cPanel
2. Find "File Manager" and click it
3. Navigate to "public_html" folder
4. Click "Upload" button at the top
5. Select your mamtours.zip file
6. Wait for upload to complete (this may take several minutes)
7. Once uploaded, right-click the ZIP file and select "Extract"
8. After extraction, you can delete the ZIP file

Step 4: Restructure Files for Laravel

Laravel needs special folder structure for cPanel hosting:

1. In File Manager, open the extracted folder (probably named "mamtours")
2. Open the "public" folder inside it
3. Select ALL files inside the public folder (index.php, .htaccess, css/, js/, images/, etc.)
4. Click "Move" at the top
5. Move them to "/public_html" (the root)
6. Go back to public_html
7. Now move all the OTHER folders (app, bootstrap, config, database, resources, routes, storage, vendor) UP one level to public_html
8. Delete the now-empty "mamtours" and "public" folders

Your public_html should now look like:
- public_html/
  - app/
  - bootstrap/
  - config/
  - database/
  - resources/
  - routes/
  - storage/
  - vendor/
  - index.php
  - .htaccess
  - css/
  - js/
  - images/
  - etc.

Step 5: Edit index.php

This is critical! You need to fix the paths in index.php:

1. In File Manager, find "index.php" in public_html
2. Right-click and select "Edit"
3. Find this line (around line 17):
   require __DIR__.'/../vendor/autoload.php';
   
   Change it to:
   require __DIR__.'/vendor/autoload.php';

4. Find this line (around line 31):
   $app = require_once __DIR__.'/../bootstrap/app.php';
   
   Change it to:
   $app = require_once __DIR__.'/bootstrap/app.php';

5. Click "Save Changes"

Step 6: Create Database

1. In cPanel, find "MySQL Databases"
2. Create a new database (example: "bythost_mamtours")
3. Create a new database user with a strong password
4. Add the user to the database with ALL PRIVILEGES
5. Write down these details:
   - Database name
   - Database username
   - Database password
   - Database host (usually "localhost")

Step 7: Create .env File

1. In File Manager, go to public_html
2. Click "File" > "New File"
3. Name it ".env" (with the dot at the start)
4. Right-click the new .env file and select "Edit"
5. Copy this template and paste it:

APP_NAME="MAM Tours"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://your-bytehost-domain.byethost.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=wilberofficial2001@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

6. Replace "your_database_name", "your_database_username", and "your_database_password" with the values from Step 6
7. Replace "your-bytehost-domain.byethost.com" with your actual Bytehost domain
8. Save the file

Step 8: Set Folder Permissions

1. In File Manager, right-click the "storage" folder
2. Select "Change Permissions"
3. Set to 755 (or check: Owner Read/Write/Execute, Group Read/Execute, World Read/Execute)
4. Check "Recurse into subdirectories"
5. Click "Change Permissions"

6. Do the same for "bootstrap/cache" folder

Step 9: Generate Application Key

You need to run this command. Bytehost might have SSH access or you can use a web-based terminal:

Option A - If SSH is available:
1. Connect via SSH
2. Run: cd public_html
3. Run: php artisan key:generate

Option B - If no SSH (create a temporary PHP file):
1. Create a file called "generate-key.php" in public_html
2. Add this code:

<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('key:generate');
echo "Key generated! Check your .env file.";
?>

3. Visit: http://your-domain.byethost.com/generate-key.php
4. Delete the generate-key.php file after running it

Step 10: Run Database Migrations

Similar to Step 9, you need to run migrations:

Option A - SSH:
php artisan migrate --force

Option B - Create migrate.php file:
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('migrate', ['--force' => true]);
echo "Migrations completed!";
?>

Visit the file, then delete it.

Step 11: Seed the Database

Create seed.php:
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('db:seed', ['--force' => true]);
echo "Database seeded!";
?>

Visit it, then delete it.

Step 12: Test Your Site

Visit your Bytehost domain. You should see your MAM Tours homepage!

Common Issues and Fixes

Issue: "500 Internal Server Error"
Fix: Check .htaccess file exists in public_html and has correct Laravel rules

Issue: "No application encryption key has been specified"
Fix: Run the key:generate step again

Issue: "Database connection error"
Fix: Double-check your .env database credentials

Issue: "Blank page"
Fix: Set APP_DEBUG=true temporarily in .env to see the actual error

Issue: "CSS/JS not loading"
Fix: Make sure all files from public/ folder are in public_html root

Important Notes

- Bytehost free hosting has limitations (bandwidth, storage, CPU)
- No SSH access on free plan means you'll use the PHP file method for artisan commands
- Free hosting may have ads injected into your pages
- Database size is limited on free plans
- Consider upgrading to paid hosting for production use

Need Help?

If you encounter errors, check:
1. Laravel log files in storage/logs/
2. Enable APP_DEBUG=true temporarily to see detailed errors
3. Verify all file permissions are correct
4. Make sure .env file has correct database credentials

Your site should now be live on Bytehost!
