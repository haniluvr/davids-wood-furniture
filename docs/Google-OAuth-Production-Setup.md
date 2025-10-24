# Google OAuth Production Setup Guide

This guide will help you configure Google OAuth for your production environment.

## Prerequisites

- Access to Google Cloud Console
- Your production domain (e.g., `https://yourdomain.com`)
- Admin access to your production environment

## Step 1: Google Cloud Console Configuration

### 1.1 Create or Select a Project
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Make sure the project is selected in the top dropdown

### 1.2 Enable Google+ API
1. Go to **APIs & Services** → **Library**
2. Search for "Google+ API" or "Google People API"
3. Click **Enable**

### 1.3 Create OAuth 2.0 Credentials
1. Go to **APIs & Services** → **Credentials**
2. Click **Create Credentials** → **OAuth 2.0 Client ID**
3. If prompted, configure the OAuth consent screen first:
   - Choose **External** user type
   - Fill in required fields (App name, User support email, Developer contact)
   - Add your production domain to authorized domains
4. Select **Web application** as the application type
5. Configure the following:

#### Authorized JavaScript origins:
```
https://yourdomain.com
```

#### Authorized redirect URIs:
```
https://yourdomain.com/auth/google/callback
```

6. Click **Create**
7. **Copy your Client ID and Client Secret** - you'll need these for your environment variables

## Step 2: Production Environment Configuration

### 2.1 Add Environment Variables
Add these variables to your production `.env` file or deployment secrets:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your-actual-client-id-from-google-console
GOOGLE_CLIENT_SECRET=your-actual-client-secret-from-google-console
GOOGLE_REDIRECT_URL=https://yourdomain.com/auth/google/callback
```

### 2.2 Update GitHub Secrets (if using GitHub Actions)
If you're using GitHub Actions for deployment, add these secrets in your repository:

1. Go to your repository → **Settings** → **Secrets and variables** → **Actions**
2. Add these repository secrets:
   - `GOOGLE_CLIENT_ID`: Your Google Client ID
   - `GOOGLE_CLIENT_SECRET`: Your Google Client Secret
   - `GOOGLE_REDIRECT_URL`: `https://yourdomain.com/auth/google/callback`

### 2.3 Clear Configuration Cache
After updating environment variables, clear the configuration cache:

```bash
php artisan config:clear
php artisan config:cache
```

## Step 3: Verify Configuration

### 3.1 Check Environment Variables
Verify that your environment variables are loaded correctly:

```bash
php artisan tinker
>>> config('services.google.client_id')
>>> config('services.google.client_secret')
>>> config('services.google.redirect')
```

### 3.2 Test Google OAuth
1. Visit your production site
2. Try to sign in with Google
3. Check the browser console for any errors
4. Verify that the redirect URL matches your Google Console configuration

## Step 4: Troubleshooting

### Common Issues:

#### 1. "Missing required parameter: client_id"
- **Cause**: `GOOGLE_CLIENT_ID` is not set in production environment
- **Solution**: Ensure the environment variable is properly set and cached

#### 2. "Error 400: invalid_request"
- **Cause**: Mismatch between configured redirect URI and actual redirect URI
- **Solution**: Verify that `GOOGLE_REDIRECT_URL` matches exactly what's configured in Google Console

#### 3. "Access blocked: Authorization Error"
- **Cause**: OAuth consent screen not properly configured
- **Solution**: Complete the OAuth consent screen configuration in Google Console

#### 4. "redirect_uri_mismatch"
- **Cause**: The redirect URI in the request doesn't match the authorized redirect URIs
- **Solution**: Ensure the redirect URI in Google Console exactly matches your production domain

### Debug Steps:

1. **Check Laravel logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Verify configuration**:
   ```bash
   php artisan config:show services.google
   ```

3. **Test OAuth flow**:
   - Check if the redirect to Google works
   - Verify the callback URL is correct
   - Check for any JavaScript errors in browser console

## Step 5: Security Considerations

### 5.1 OAuth Consent Screen
- Complete all required fields in the OAuth consent screen
- Add your production domain to authorized domains
- Set up proper privacy policy and terms of service URLs

### 5.2 Environment Security
- Never commit OAuth credentials to version control
- Use environment variables or secure secret management
- Regularly rotate OAuth credentials

### 5.3 Domain Verification
- Ensure your production domain is verified in Google Console
- Use HTTPS for all OAuth redirects
- Avoid using localhost or test domains in production

## Additional Resources

- [Google OAuth 2.0 Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- [Google Cloud Console](https://console.cloud.google.com/)

## Support

If you encounter issues:
1. Check the Laravel logs for detailed error messages
2. Verify your Google Console configuration
3. Ensure all environment variables are properly set
4. Test with a fresh browser session (clear cookies/cache)
