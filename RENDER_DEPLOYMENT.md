# Deploy MAM Tours to Render.com

## Prerequisites
1. GitHub account
2. Render.com account (sign up at https://render.com)
3. Your code pushed to GitHub

## Step 1: Push Code to GitHub

```bash
# Initialize git if not already done
git init

# Add all files
git add .

# Commit
git commit -m "Prepare for Render deployment"

# Create a new repository on GitHub, then:
git remote add origin https://github.com/YOUR_USERNAME/mam-tours.git
git branch -M main
git push -u origin main
```

## Step 2: Create Render Account
1. Go to https://render.com
2. Sign up with GitHub
3. Authorize Render to access your repositories

## Step 3: Create PostgreSQL Database
1. Click "New +" → "PostgreSQL"
2. Name: `mam-tours-db`
3. Database: `mam_tours`
4. User: `mam_tours_user`
5. Region: Choose closest to your users
6. Plan: **Free**
7. Click "Create Database"
8. **Save the connection details** (you'll need them)

## Step 4: Create Web Service
1. Click "New +" → "Web Service"
2. Connect your GitHub repository
3. Select your `mam-tours` repository
4. Configure:
   - **Name:** mam-tours
   - **Region:** Same as database
   - **Branch:** main
   - **Runtime:** PHP
   - **Build Command:** `./build.sh`
   - **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`

## Step 5: Add Environment Variables
Click "Environment" and add these variables:

```
APP_NAME=MAM Tours
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_URL=https://your-app-name.onrender.com

LOG_CHANNEL=stack

DB_CONNECTION=pgsql
DB_HOST=<from database connection info>
DB_PORT=5432
DB_DATABASE=mam_tours
DB_USERNAME=<from database connection info>
DB_PASSWORD=<from database connection info>

SESSION_DRIVER=file
QUEUE_CONNECTION=sync
CACHE_DRIVER=file

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Step 6: Generate APP_KEY
Run locally:
```bash
php artisan key:generate --show
```
Copy the output and paste it as `APP_KEY` in Render environment variables.

## Step 7: Deploy
1. Click "Create Web Service"
2. Wait for deployment (5-10 minutes)
3. Your site will be live at: `https://your-app-name.onrender.com`

## Step 8: Create Admin User
After deployment, go to Render dashboard:
1. Click on your web service
2. Go to "Shell" tab
3. Run:
```bash
php artisan tinker
```
Then:
```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@mamtours.com',
    'password' => bcrypt('your-secure-password'),
    'role' => 'admin',
    'email_verified_at' => now()
]);
exit
```

## Important Notes

### Free Tier Limitations
- Service spins down after 15 minutes of inactivity
- First request after spin-down takes 30-60 seconds
- 750 hours/month free (enough for one service)

### File Storage
- Uploaded files are NOT persistent on free tier
- Use Cloudinary or AWS S3 for file uploads
- Or upgrade to paid plan ($7/month)

### Database Backups
- Free PostgreSQL includes automatic backups
- Download backups from Render dashboard

### Custom Domain (Optional)
1. Go to your web service settings
2. Click "Custom Domains"
3. Add your domain
4. Update DNS records as instructed

## Troubleshooting

### Build Fails
- Check build logs in Render dashboard
- Ensure `composer.json` has all dependencies
- Verify PHP version in `composer.json`

### Database Connection Error
- Verify all DB environment variables
- Check database is in same region
- Ensure database is running

### 500 Error
- Check logs in Render dashboard
- Verify APP_KEY is set
- Run migrations: `php artisan migrate --force`

### Storage Link Error
- This is normal on first deploy
- Files will work after first upload

## Monitoring
- View logs: Render Dashboard → Your Service → Logs
- Check metrics: Dashboard → Metrics tab
- Set up alerts: Dashboard → Notifications

## Updating Your Site
```bash
# Make changes locally
git add .
git commit -m "Your changes"
git push origin main
```
Render will automatically redeploy!

## Support
- Render Docs: https://render.com/docs
- Community: https://community.render.com
