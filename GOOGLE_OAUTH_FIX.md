# Google OAuth HTTPS Fix for Localhost

## Problem
You're getting `ERR_SSL_PROTOCOL_ERROR` because Google OAuth redirected you back to `https://localhost:8080`, but you don't have SSL configured for localhost.

## Solution 1: Use HTTP with Localhost (Quick Fix) ⚡

Google **does allow** HTTP for localhost in most cases.

### Steps:

1. **Update your `.env` file:**
   ```env
   APP_URL=http://localhost:8080
   GOOGLE_CLIENT_ID=your-actual-client-id
   GOOGLE_CLIENT_SECRET=your-actual-secret
   GOOGLE_REDIRECT_URL=http://localhost:8080/auth/google/callback
   FORCE_HTTPS=false
   ```

2. **In Google Cloud Console** (https://console.cloud.google.com/):
   - Go to **APIs & Services** → **Credentials**
   - Click on your OAuth 2.0 Client ID
   - Under **Authorized redirect URIs**, add:
     ```
     http://localhost:8080/auth/google/callback
     ```
   - Click **Save**

3. **Clear Laravel cache** (already done):
   ```bash
   php artisan config:clear
   php artisan route:clear
   ```

4. **Access your site at:**
   - Main: http://localhost:8080
   - Admin: http://admin.localhost:8080

5. **Test Google Sign-In** again

---

## Solution 2: Use mkcert for Trusted HTTPS (Better for Long-term) 🔒

If you want proper HTTPS on localhost, use `mkcert` to create trusted local certificates.

### Step 1: Install mkcert

**Option A: Using Chocolatey (Recommended)**

If you don't have Chocolatey, install it first:
```powershell
# Run PowerShell as Administrator
Set-ExecutionPolicy Bypass -Scope Process -Force
[System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072
iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
```

Then install mkcert:
```powershell
choco install mkcert -y
```

**Option B: Manual Download**

1. Download from: https://github.com/FiloSottile/mkcert/releases
2. Download `mkcert-v1.4.4-windows-amd64.exe`
3. Rename to `mkcert.exe`
4. Move to a folder in your PATH (e.g., `C:\Windows\System32`)

### Step 2: Install Local Certificate Authority

```powershell
# Run as Administrator
mkcert -install
```

### Step 3: Generate Certificates for Localhost

```powershell
# Navigate to your project
cd C:\xampp\htdocs\davids-wood-furniture

# Create SSL directory
mkdir -p public\ssl

# Generate certificates
mkcert -key-file public\ssl\localhost-key.pem -cert-file public\ssl\localhost-cert.pem localhost admin.localhost 127.0.0.1 ::1
```

### Step 4: Configure Laravel Valet OR Update Apache

**Option A: Use Laravel Valet (Easiest)**
```powershell
composer global require laravel/valet
valet install
cd C:\xampp\htdocs\davids-wood-furniture
valet link davidswood
valet secure davidswood
```

Then access at: `https://davidswood.test`

**Option B: Configure Apache for XAMPP**

Create `C:\xampp\apache\conf\extra\httpd-localhost-ssl.conf`:

```apache
Listen 8443

<VirtualHost *:8443>
    DocumentRoot "C:/xampp/htdocs/davids-wood-furniture/public"
    ServerName localhost:8443
    
    SSLEngine on
    SSLCertificateFile "C:/xampp/htdocs/davids-wood-furniture/public/ssl/localhost-cert.pem"
    SSLCertificateKeyFile "C:/xampp/htdocs/davids-wood-furniture/public/ssl/localhost-key.pem"
    
    <Directory "C:/xampp/htdocs/davids-wood-furniture/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:8443>
    DocumentRoot "C:/xampp/htdocs/davids-wood-furniture/public"
    ServerName admin.localhost:8443
    
    SSLEngine on
    SSLCertificateFile "C:/xampp/htdocs/davids-wood-furniture/public/ssl/localhost-cert.pem"
    SSLCertificateKeyFile "C:/xampp/htdocs/davids-wood-furniture/public/ssl/localhost-key.pem"
    
    <Directory "C:/xampp/htdocs/davids-wood-furniture/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Edit `C:\xampp\apache\conf\httpd.conf` and add:
```apache
Include conf/extra/httpd-localhost-ssl.conf
```

Restart Apache.

### Step 5: Update .env

```env
APP_URL=https://localhost:8443
GOOGLE_REDIRECT_URL=https://localhost:8443/auth/google/callback
```

### Step 6: Update Google Cloud Console

Add authorized redirect URI:
```
https://localhost:8443/auth/google/callback
```

---

## Recommended Approach

🎯 **For quick testing**: Use **Solution 1** (HTTP with localhost)

🎯 **For production-like development**: Use **Solution 2** (mkcert with HTTPS)

---

## Current Setup Summary

After my changes, your application now:

✅ Admin subdomain routes are **dynamic** (works with any APP_URL)
✅ Google OAuth redirect is **dynamic** (based on APP_URL)
✅ Automatically adapts to localhost or .test domains

### URL Mapping Examples:

| APP_URL | Main Site | Admin Site | OAuth Callback |
|---------|-----------|------------|----------------|
| `http://localhost:8080` | http://localhost:8080 | http://admin.localhost:8080 | http://localhost:8080/auth/google/callback |
| `https://localhost:8443` | https://localhost:8443 | https://admin.localhost:8443 | https://localhost:8443/auth/google/callback |
| `http://davidswood.test:8080` | http://davidswood.test:8080 | http://admin.davidswood.test:8080 | ❌ Won't work with Google (`.test` not allowed) |

---

## Quick Troubleshooting

**If Google still shows an error:**
1. Make sure the redirect URI in Google Console **exactly matches** your `.env` setting
2. Clear browser cookies and cache
3. Try in incognito/private mode
4. Verify your GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET are correct

**If you get "This site can't be reached":**
1. Make sure Apache is running
2. Verify you're accessing the correct port (8080 for HTTP or 8443 for HTTPS)
3. Check Windows hosts file has localhost entry

**If admin subdomain doesn't work:**
1. Clear Laravel caches: `php artisan config:clear && php artisan route:clear`
2. Restart Apache
3. Try accessing http://localhost:8080 first to verify basic setup works

