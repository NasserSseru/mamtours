# Application Modernization Guide

This document outlines the modernization enhancements added to the MAM Tours application.

## New Dependencies Installed

### Backend (PHP/Laravel)
- **predis/predis** (^2.0) - Redis client for PHP
- **sentry/sentry-laravel** (^3.0) - Error tracking and performance monitoring
- **laravel/horizon** (^5.0) - Queue monitoring dashboard (optional)

### Frontend (JavaScript/TypeScript)
- **typescript** (^5.0.0) - Type-safe JavaScript
- **pinia** (^2.1.0) - State management for Vue.js
- **@googlemaps/js-api-loader** (^1.16.0) - Google Maps integration
- **@googlemaps/markerclusterer** (^2.5.0) - Map marker clustering
- **@types/google.maps** (^3.53.0) - TypeScript definitions for Google Maps

## Installation Steps

### 1. Install PHP Dependencies

```bash
composer install
```

### 2. Install Node Dependencies

```bash
npm install
```

### 3. Configure Environment Variables

Copy the new variables from `.env.example` to your `.env` file:

```bash
# Sentry Configuration
SENTRY_LARAVEL_DSN=your_sentry_dsn_here
SENTRY_TRACES_SAMPLE_RATE=0.2
APP_VERSION=1.0.0

# Google Maps API
VITE_GOOGLE_MAPS_API_KEY=your_google_maps_api_key

# Webhook Secret
WEBHOOK_SECRET=your_random_secret_key

# Queue Configuration (for production)
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 4. Set Up Redis

#### Local Development (XAMPP/Windows)
1. Download Redis for Windows from https://github.com/microsoftarchive/redis/releases
2. Install and start Redis service
3. Verify connection: `redis-cli ping` (should return PONG)

#### Railway Deployment
1. Add Redis plugin from Railway dashboard
2. Railway will automatically set REDIS_URL environment variable
3. Update your `.env` on Railway with the Redis connection details

### 5. Run Database Migrations

```bash
php artisan migrate
```

### 6. Compile Frontend Assets

```bash
npm run build
```

## New Features

### 1. Redis Caching
- Faster page loads with cached car listings
- Session storage in Redis for better performance
- Automatic cache invalidation on data updates

### 2. Queue System
- Asynchronous email sending
- Background PDF generation
- SMS notifications processed in background
- Retry logic for failed jobs

### 3. Error Tracking (Sentry)
- Real-time error notifications
- Performance monitoring
- Request tracing
- Privacy-compliant error logging

### 4. TypeScript Support
- Type-safe frontend code
- Better IDE autocomplete
- Compile-time error detection
- Improved code maintainability

### 5. Google Maps Integration
- Interactive location selection
- Route planning and directions
- Distance calculations
- Nearby pickup points display

### 6. Pinia State Management
- Centralized application state
- Reactive data updates
- Persistent state across page reloads
- Better component communication

## Development Workflow

### Running Queue Workers

For local development:
```bash
php artisan queue:work
```

For production (with Horizon):
```bash
php artisan horizon
```

### Monitoring Queues

Access Horizon dashboard at: `http://your-app-url/horizon`

### Viewing Logs

Structured logs are stored in `storage/logs/structured.log` in JSON format.

### Health Checks

Check application health at: `http://your-app-url/api/health`

## Testing

Run the full test suite:
```bash
php artisan test
```

Run specific test suites:
```bash
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
php artisan test --testsuite=Integration
```

## Production Deployment

### Railway Deployment Checklist

1. ✅ Add Redis plugin
2. ✅ Set environment variables (Sentry DSN, Google Maps API key)
3. ✅ Update `CACHE_DRIVER=redis` and `QUEUE_CONNECTION=redis`
4. ✅ Run migrations: `php artisan migrate --force`
5. ✅ Start queue workers (add to Procfile)
6. ✅ Monitor with Sentry dashboard

### Procfile Updates

Add queue worker to your `Procfile`:
```
web: vendor/bin/heroku-php-apache2 public/
worker: php artisan queue:work --tries=3
```

## Troubleshooting

### Redis Connection Issues
- Verify Redis is running: `redis-cli ping`
- Check REDIS_HOST and REDIS_PORT in `.env`
- Ensure firewall allows Redis port (6379)

### Queue Jobs Not Processing
- Verify queue worker is running: `php artisan queue:work`
- Check failed jobs: `php artisan queue:failed`
- Retry failed jobs: `php artisan queue:retry all`

### TypeScript Compilation Errors
- Run type checking: `npm run type-check`
- Check `tsconfig.json` configuration
- Ensure all type definitions are installed

### Google Maps Not Loading
- Verify API key is set in `.env`
- Check API key has Maps JavaScript API enabled
- Ensure billing is enabled on Google Cloud project

## Performance Optimization

### Cache Warming
Warm up the cache after deployment:
```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Optimization
Indexes have been added for common queries. Monitor slow queries in logs.

### Frontend Optimization
- Assets are minified and bundled with Vite
- Map markers use clustering for performance
- Images are lazy-loaded

## Security Considerations

- Sentry scrubs sensitive data before sending
- Webhook signatures are verified
- API rate limiting is enforced
- CSRF protection on all forms
- Security headers middleware active

## Support

For issues or questions:
1. Check application logs in `storage/logs/`
2. Review Sentry error dashboard
3. Check health endpoint: `/api/health`
4. Review this guide and `.env.example`

## Next Steps

1. Configure Sentry project and get DSN
2. Set up Google Maps API key with billing
3. Test queue workers locally
4. Deploy to Railway with Redis plugin
5. Monitor performance with Sentry
6. Set up automated backups

---

Last Updated: February 2026
Version: 1.0.0
