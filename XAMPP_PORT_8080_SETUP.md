# XAMPP Port 8080 Setup Guide
## Running XAMPP alongside IIS

This guide will help you configure XAMPP to run on port 8080 so it doesn't conflict with IIS running on port 80.

---

## 🎯 Quick Solution

**Access phpMyAdmin at:** `http://localhost:8080/phpmyadmin`

---

## 📝 Step-by-Step Configuration

### Step 1: Configure Apache to Use Port 8080

1. **Open XAMPP Control Panel** as Administrator

2. **Stop Apache** if it's running (click "Stop" button)

3. **Edit Apache Configuration**
   - Click "Config" button next to Apache
   - Select "httpd.conf"
   
4. **Find and Change Port 80 to 8080**
   
   Find this line (around line 60):
   ```apache
   Listen 80
   ```
   
   Change it to:
   ```apache
   Listen 8080
   ```

5. **Change ServerName Port** (optional but recommended)
   
   Find this line (around line 227):
   ```apache
   ServerName localhost:80
   ```
   
   Change it to:
   ```apache
   ServerName localhost:8080
   ```

6. **Save and close** the file

### Step 2: Configure Virtual Hosts (if enabled)

1. **Edit Virtual Hosts Configuration**
   - In XAMPP Control Panel, click "Config" next to Apache
   - Select "httpd-vhosts.conf"

2. **Update All VirtualHost Entries**
   
   Find all instances of:
   ```apache
   <VirtualHost *:80>
   ```
   
   Change them to:
   ```apache
   <VirtualHost *:8080>
   ```

3. **Save and close** the file

### Step 3: Restart Apache

1. In XAMPP Control Panel, click **"Start"** next to Apache
2. Apache should now start successfully on port 8080

### Step 4: Access phpMyAdmin

Open your browser and go to:
```
http://localhost:8080/phpmyadmin
```

---

## ✅ Verify Everything Works

After configuration, test the following URLs:

| Service | URL | Expected Result |
|---------|-----|-----------------|
| XAMPP Dashboard | `http://localhost:8080/` | XAMPP welcome page |
| phpMyAdmin | `http://localhost:8080/phpmyadmin` | phpMyAdmin login |
| Your Laravel App | `http://localhost:8080/davids-wood-furniture/public` | Your application |
| IIS (unchanged) | `http://localhost/` | IIS default page |

---

## 🚀 For Your Laravel Application

Your David's Wood Furniture application is already configured for port 8080!

### Access URLs:
- **Main Site**: `http://davidswood.test:8080`
- **Admin Panel**: `http://admin.davidswood.test:8080`

### Requirements:
1. Configure Apache Virtual Hosts (see README.md section on Subdomain Configuration)
2. Update your hosts file:
   ```
   127.0.0.1    davidswood.test
   127.0.0.1    admin.davidswood.test
   ```
3. Ensure `.env` file has:
   ```
   APP_URL=http://davidswood.test:8080
   ```

---

## 🔧 Troubleshooting

### Apache Won't Start
1. **Check if port 8080 is already in use:**
   ```cmd
   netstat -ano | findstr :8080
   ```
   
2. **Kill the process if needed:**
   ```cmd
   taskkill /PID [process_id] /F
   ```

### phpMyAdmin Shows 404
- Make sure you're using `localhost:8080` not just `localhost`
- Clear your browser cache
- Restart Apache

### Can't Access Virtual Hosts
1. Verify hosts file entries (requires admin privileges)
2. Check that httpd-vhosts.conf has port 8080
3. Ensure virtual hosts are enabled in httpd.conf

### IIS and XAMPP Both Need to Run
This configuration allows both to run simultaneously:
- **IIS**: Port 80 (`http://localhost/`)
- **XAMPP**: Port 8080 (`http://localhost:8080/`)

---

## 📋 Quick Diagnostic Script

Run this to check your configuration:
```cmd
php check-port-config.php
```

This script will verify:
- ✓ Apache configuration files
- ✓ Port settings
- ✓ Virtual host configuration
- ✓ Hosts file entries
- ✓ Network connectivity

---

## 🎯 Summary

1. Change `Listen 80` to `Listen 8080` in `C:\xampp\apache\conf\httpd.conf`
2. Change `<VirtualHost *:80>` to `<VirtualHost *:8080>` in `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
3. Restart Apache in XAMPP Control Panel
4. Access phpMyAdmin at `http://localhost:8080/phpmyadmin`

---

## 📞 Need More Help?

- Check the main README.md for detailed Laravel setup
- Review PORT_8080_SETUP.md if it exists
- Run `php check-port-config.php` for diagnostics

**Both IIS and XAMPP can run simultaneously on different ports!** 🎉

