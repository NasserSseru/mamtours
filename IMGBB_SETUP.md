# ImgBB Image Storage Setup

This application uses ImgBB for persistent image storage. ImgBB is a free image hosting service that provides:

- **Unlimited storage** (free tier)
- **32MB per image** limit
- **Permanent image hosting** (images don't get deleted)
- **Fast CDN delivery**

## Getting Your ImgBB API Key

1. Go to [https://api.imgbb.com/](https://api.imgbb.com/)
2. Click "Get API Key" or "Sign Up"
3. Create a free account (you can use your email or sign in with Google/Facebook)
4. Once logged in, you'll see your API key on the dashboard
5. Copy your API key

## Configuration

### For Local Development

Add to your `.env` file:
```
IMGBB_API_KEY=your_api_key_here
```

### For Production (Render.com)

1. Go to your Render dashboard
2. Select your web service (mam-tours)
3. Go to "Environment" tab
4. Add a new environment variable:
   - **Key**: `IMGBB_API_KEY`
   - **Value**: Your ImgBB API key
5. Click "Save Changes"
6. Your service will automatically redeploy

## How It Works

- When you upload a car image, the system first tries to upload it to ImgBB
- If ImgBB is configured (API key is set), images are stored permanently on ImgBB's servers
- If ImgBB is not configured or fails, images fall back to local storage (will be lost on Render restarts)
- The system automatically detects whether an image URL is from ImgBB or local storage

## Benefits

- **Persistent Storage**: Images remain available even after app restarts on Render
- **No Storage Limits**: Render's free tier has limited storage, but ImgBB provides unlimited storage
- **Fast Loading**: ImgBB uses a CDN for fast image delivery worldwide
- **Free Forever**: ImgBB's free tier has no expiration

## Testing

After adding your API key:

1. Go to the admin dashboard
2. Add a new vehicle with an image
3. The image should upload successfully
4. Check the browser console - you should see: "Image uploaded to ImgBB successfully: [URL]"
5. The image URL will start with `https://i.ibb.co/`

## Troubleshooting

If images aren't uploading:

1. Check that your API key is correct in the environment variables
2. Check the Laravel logs: `storage/logs/laravel.log`
3. Make sure your image is under 32MB
4. Verify your ImgBB account is active

## Fallback Behavior

If ImgBB upload fails for any reason:
- The system automatically falls back to local storage
- You'll see a log message: "Image uploaded to local storage"
- Images will work on localhost but may be lost on Render restarts
