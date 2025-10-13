# HTTPS Configuration for Port 8443

Since IIS is using port 443, we'll configure Apache to use port **8443** for HTTPS connections.

## 🔒 SSL/HTTPS Setup Steps

### Step 1: Verify SSL Certificates Exist

The SSL certificates were already created. Verify they exist at:
- Certificate: `C:\xampp\apache\conf\ssl.crt\davidswood\davidswood.test.crt`
- Private Key: `C:\xampp\apache\conf\ssl.key\davidswood\davidswood.test.key`

### Step 2: Configure Apache to Listen on Port 8443

#### Edit `C:\xampp\apache\conf\httpd.conf`

Find the Listen directive (around line 60) and add:
```apache
Listen 8080
Listen 8443
```

#### Edit `C:\xampp\apache\conf\extra\httpd-ssl.conf`

Find and comment out the duplicate Listen directive (around line 36):
```apache
# Listen 8443 is now defined in httpd.conf
#Listen 8443
```

**Important**: Only define `Listen 8443` once in `httpd.conf`, not in both files!

Make sure this line is uncommented:
```apache
LoadModule ssl_module modules/mod_ssl.so
```

Also ensure this line is uncommented:
```apache
LoadModule socache_shmcb_module modules/mod_socache_shmcb.so
```

### Step 3: Update SSL Virtual Host Configuration

#### Edit `C:\xampp\apache\conf\extra\httpd-davidswood-ssl.conf`

Replace the entire file with:

```apache
# SSL Configuration for davidswood.test on port 8443
<VirtualHost *:8443>
    DocumentRoot "C:/xampp/htdocs/davids-wood-furniture/public"
    ServerName davidswood.test:8443
    ServerAlias www.davidswood.test:8443
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile "conf/ssl.crt/davidswood/davidswood.test.crt"
    SSLCertificateKeyFile "conf/ssl.key/davidswood/davidswood.test.key"
    
    # Modern SSL Configuration
    SSLProtocol all -SSLv3 -TLSv1 -TLSv1.1
    SSLCipherSuite HIGH:!aNULL:!MD5
    SSLHonorCipherOrder on
    
    # Logging
    ErrorLog "C:/xampp/apache/logs/davidswood_ssl_error.log"
    TransferLog "C:/xampp/apache/logs/davidswood_ssl_access.log"
    
    # Directory configuration
    <Directory "C:/xampp/htdocs/davids-wood-furniture/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        DirectoryIndex index.php index.html
    </Directory>
</VirtualHost>

# SSL Configuration for admin.davidswood.test on port 8443
<VirtualHost *:8443>
    DocumentRoot "C:/xampp/htdocs/davids-wood-furniture/public"
    ServerName admin.davidswood.test:8443
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile "conf/ssl.crt/davidswood/davidswood.test.crt"
    SSLCertificateKeyFile "conf/ssl.key/davidswood/davidswood.test.key"
    
    # Modern SSL Configuration
    SSLProtocol all -SSLv3 -TLSv1 -TLSv1.1
    SSLCipherSuite HIGH:!aNULL:!MD5
    SSLHonorCipherOrder on
    
    # Logging
    ErrorLog "C:/xampp/apache/logs/admin_davidswood_ssl_error.log"
    TransferLog "C:/xampp/apache/logs/admin_davidswood_ssl_access.log"
    
    # Directory configuration
    <Directory "C:/xampp/htdocs/davids-wood-furniture/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        DirectoryIndex index.php index.html
    </Directory>
</VirtualHost>
```

### Step 4: Update HTTP Virtual Host for Redirects

#### Edit `C:\xampp\apache\conf\extra\httpd-davidswood.conf`

Replace with:

```apache
# HTTP to HTTPS redirect for davidswood.test on port 8080
<VirtualHost *:8080>
    ServerName davidswood.test
    ServerAlias www.davidswood.test
    
    # Optional: Redirect to HTTPS
    # Uncomment the line below if you want automatic HTTPS redirect
    # Redirect permanent / https://davidswood.test:8443/
    
    # OR keep serving on HTTP (current behavior)
    DocumentRoot "C:/xampp/htdocs/davids-wood-furniture/public"
    
    <Directory "C:/xampp/htdocs/davids-wood-furniture/public">
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/davidswood-error.log"
    CustomLog "logs/davidswood-access.log" common
</VirtualHost>

# HTTP to HTTPS redirect for admin.davidswood.test on port 8080
<VirtualHost *:8080>
    ServerName admin.davidswood.test
    
    # Optional: Redirect to HTTPS
    # Uncomment the line below if you want automatic HTTPS redirect
    # Redirect permanent / https://admin.davidswood.test:8443/
    
    # OR keep serving on HTTP (current behavior)
    DocumentRoot "C:/xampp/htdocs/davids-wood-furniture/public"
    
    <Directory "C:/xampp/htdocs/davids-wood-furniture/public">
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/admin-davidswood-error.log"
    CustomLog "logs/admin-davidswood-access.log" common
</VirtualHost>
```

### Step 5: Update .env File

```env
APP_URL=https://davidswood.test:8443
FORCE_HTTPS=false
```

**Note**: Set `FORCE_HTTPS=false` because we want to support both HTTP (8080) and HTTPS (8443).

### Step 6: Restart Apache

1. Open XAMPP Control Panel
2. Stop Apache
3. Start Apache
4. Check for errors in the logs if Apache won't start

### Step 7: Test Configuration

Test if Apache is listening on both ports:
```bash
netstat -ano | findstr ":8080"
netstat -ano | findstr ":8443"
```

### Step 8: Clear Laravel Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 🌐 Access URLs

After configuration, you can access your application at:

- **HTTP Main Site**: http://davidswood.test:8080
- **HTTPS Main Site**: https://davidswood.test:8443
- **HTTP Admin Panel**: http://admin.davidswood.test:8080/login
- **HTTPS Admin Panel**: https://admin.davidswood.test:8443/login

## 🔒 Trust the SSL Certificate

Since this is a self-signed certificate, your browser will show a security warning:

### For Chrome/Edge:
1. Visit https://davidswood.test:8443
2. Click "Advanced"
3. Click "Proceed to davidswood.test (unsafe)"

### To Permanently Trust (Windows):
1. Visit https://davidswood.test:8443
2. Click the lock icon → "Certificate is not valid"
3. Go to "Details" tab → "Copy to File"
4. Save as `davidswood.crt`
5. Double-click the file
6. Click "Install Certificate"
7. Choose "Local Machine"
8. Select "Place all certificates in the following store"
9. Browse and select "Trusted Root Certification Authorities"
10. Complete the wizard
11. Restart your browser

## ⚙️ Optional: Force HTTPS Redirect

If you want to automatically redirect all HTTP traffic to HTTPS:

1. **Uncomment the redirect lines** in `httpd-davidswood.conf`:
   ```apache
   Redirect permanent / https://davidswood.test:8443/
   ```

2. **Update .env**:
   ```env
   FORCE_HTTPS=true
   ```

3. **Restart Apache** and **Clear caches**

## 🐛 Troubleshooting

### Issue: Apache won't start

**Check error logs**:
```
C:\xampp\apache\logs\error.log
```

**Common issues**:
1. Port 8443 already in use - check with: `netstat -ano | findstr :8443`
2. SSL certificate file not found - verify paths
3. SSL module not loaded - check `httpd.conf`

### Issue: Certificate errors in browser

**Solutions**:
1. Clear browser cache and SSL state
2. Trust the certificate (see steps above)
3. Try accessing in incognito/private mode first

### Issue: Mixed content warnings

**Solution**: Make sure all assets use relative URLs or HTTPS

### Issue: "This site can't provide a secure connection"

**Solutions**:
1. Verify Apache is listening on 8443: `netstat -ano | findstr :8443`
2. Check SSL certificate files exist
3. Verify SSLEngine is on in the virtual host
4. Check Apache error logs

## 📝 Quick Commands Reference

```bash
# Check if ports are listening
netstat -ano | findstr ":8080"
netstat -ano | findstr ":8443"

# Clear all Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Test Apache configuration
C:\xampp\apache\bin\httpd.exe -t

# View Apache error log
type C:\xampp\apache\logs\error.log
```

## ✅ Success Checklist

- [ ] Apache listening on port 8080 (HTTP)
- [ ] Apache listening on port 8443 (HTTPS)
- [ ] SSL certificates exist and are configured
- [ ] Can access http://davidswood.test:8080
- [ ] Can access https://davidswood.test:8443 (even with certificate warning)
- [ ] Can access http://admin.davidswood.test:8080
- [ ] Can access https://admin.davidswood.test:8443
- [ ] Browser shows lock icon (after trusting certificate)
- [ ] Laravel application loads correctly on HTTPS
- [ ] All caches cleared

## 🎯 Final Result

You'll have:
- ✅ **HTTP** on port **8080** (http://davidswood.test:8080)
- ✅ **HTTPS** on port **8443** (https://davidswood.test:8443)
- ✅ Both ports working simultaneously
- ✅ Secure HTTPS connection with SSL certificate
- ✅ Admin subdomain working on both HTTP and HTTPS

---

**Note**: You can use both HTTP and HTTPS versions. HTTPS on port 8443 will show as "secure" in the browser once you trust the certificate!
