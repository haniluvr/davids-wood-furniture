# SSL Setup Instructions for David's Wood Furniture

## ✅ What I've Done:

1. **Created SSL Certificates**: Generated self-signed certificates for `davidswood.test` and `admin.davidswood.test`
2. **Configured Apache**: Added virtual host configurations for HTTPS
3. **Updated Laravel**: Configured the application to use HTTPS
4. **Added HTTPS Redirect**: HTTP requests will automatically redirect to HTTPS

## 🔧 Next Steps:

### 1. Fix Laravel Configuration (IMPORTANT!)
If you get a "MissingAppKeyException" error, run these commands in your project directory:

```bash
# Generate application key
php artisan key:generate --force

# Clear configuration cache
php artisan config:clear

# Run database migrations
php artisan migrate --force

# Seed initial data
php artisan db:seed --force
```

### 2. Restart Apache
- Open XAMPP Control Panel
- Stop Apache (click "Stop" button)
- Start Apache (click "Start" button)

### 3. Trust the Certificate (Important!)
Since this is a self-signed certificate, browsers will show a security warning. You need to trust it:

1. **Visit the site**: Go to `https://davidswood.test`
2. **Accept the warning**: Click "Advanced" → "Proceed to davidswood.test (unsafe)"
3. **For Chrome**: Click the lock icon → "Certificate is not valid" → "Details" → "Copy to File" → Save as .crt file
4. **Install the certificate**:
   - Double-click the .crt file
   - Click "Install Certificate"
   - Choose "Local Machine"
   - Place in "Trusted Root Certification Authorities"
   - Complete the wizard

### 4. Test the Sites:
- **Main Site**: https://davidswood.test
- **Admin Panel**: https://admin.davidswood.test

## 📁 Files Created/Modified:

### SSL Certificates:
- `C:\xampp\apache\conf\ssl.crt\davidswood\davidswood.test.crt`
- `C:\xampp\apache\conf\ssl.key\davidswood\davidswood.test.key`

### Apache Configuration:
- `C:\xampp\apache\conf\extra\httpd-davidswood.conf` (HTTP to HTTPS redirect)
- `C:\xampp\apache\conf\extra\httpd-davidswood-ssl.conf` (HTTPS virtual hosts)
- `C:\xampp\apache\conf\httpd.conf` (includes the new configs)

### Laravel Configuration:
- `.env` (updated with HTTPS settings)
- `app/Http/Middleware/ForceHttps.php` (forces HTTPS)
- `bootstrap/app.php` (registers HTTPS middleware)
- `config/app.php` (adds force_https setting)

## 🔒 Security Features:

1. **Automatic HTTPS Redirect**: All HTTP requests redirect to HTTPS
2. **Secure Cookies**: Session cookies are secure when using HTTPS
3. **HSTS Ready**: Can be enabled for production
4. **Mixed Content Protection**: All assets served over HTTPS

## 🚨 Troubleshooting:

### If Apache won't start:
1. Check `C:\xampp\apache\logs\error.log` for errors
2. Make sure ports 80 and 443 are not in use by other applications
3. Verify the certificate files exist in the correct locations

### If you get certificate errors:
1. Make sure you've trusted the certificate in your browser
2. Clear browser cache and cookies
3. Try accessing the site in an incognito/private window

### If redirects don't work:
1. Clear Laravel cache: `php artisan config:clear`
2. Check that `FORCE_HTTPS=true` in `.env` file
3. Verify Apache configuration syntax

## 📝 Notes:

- This setup is for local development only
- For production, use a proper SSL certificate from a trusted CA
- The certificate is valid for 365 days
- Both `davidswood.test` and `admin.davidswood.test` are covered by the same certificate

## 🎉 Success Indicators:

✅ Browser shows green lock icon  
✅ URL shows `https://davidswood.test`  
✅ No "Not secure" warnings  
✅ All pages load without certificate errors  
✅ Admin panel accessible at `https://admin.davidswood.test`  

---

**Ready to go!** After restarting Apache and trusting the certificate, your site will be fully secured with HTTPS.
