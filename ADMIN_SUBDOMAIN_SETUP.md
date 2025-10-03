# Admin Subdomain Setup Guide

This guide will help you set up the admin panel to work with the subdomain `admin.davidswood.test`.

## 🔧 Setup Instructions

### 1. Update Windows Hosts File

Add the subdomain to your hosts file:

**File Location**: `C:\Windows\System32\drivers\etc\hosts`

**Add this line**:
```
127.0.0.1    admin.davidswood.test
```

### 2. Configure Apache Virtual Host (XAMPP)

Update your Apache configuration to handle the subdomain:

**File Location**: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

**Add this virtual host configuration**:
```apache
# Main domain
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/davids-wood-furniture/public"
    ServerName davidswood.test
    ServerAlias www.davidswood.test
    
    <Directory "C:/xampp/htdocs/davids-wood-furniture/public">
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/davidswood-error.log"
    CustomLog "logs/davidswood-access.log" common
</VirtualHost>

# Admin subdomain
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/davids-wood-furniture/public"
    ServerName admin.davidswood.test
    
    <Directory "C:/xampp/htdocs/davids-wood-furniture/public">
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/admin-davidswood-error.log"
    CustomLog "logs/admin-davidswood-access.log" common
</VirtualHost>
```

### 3. Enable Apache Modules

Make sure these modules are enabled in `C:\xampp\apache\conf\httpd.conf`:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule vhost_alias_module modules/mod_vhost_alias.so
```

### 4. Restart Apache

Restart Apache through XAMPP Control Panel or command line:
```bash
# Stop Apache
net stop apache2.4

# Start Apache
net start apache2.4
```

### 5. Update Laravel Environment (Optional)

If you want to set specific configurations for the admin subdomain, you can update your `.env` file:

```env
APP_URL=http://davidswood.test
ADMIN_URL=http://admin.davidswood.test
```

### 6. Test the Setup

1. **Main Site**: Visit `http://davidswood.test`
2. **Admin Login**: Visit `http://admin.davidswood.test/login`
3. **Admin Dashboard**: Visit `http://admin.davidswood.test` (after login)

## 🔐 Admin Login Credentials

Use these credentials to access the admin panel:

- **Super Admin**:
  - Email: `admin@davidswood.com`
  - Password: `password123`

- **Regular Admin**:
  - Email: `manager@davidswood.com`
  - Password: `password123`

- **Staff Member**:
  - Email: `staff@davidswood.com`
  - Password: `password123`

## 🚀 Admin Panel Features

Once logged in at `admin.davidswood.test`, you'll have access to:

### ✅ **Dashboard**
- Real-time statistics (customers, products, orders, revenue)
- Interactive charts (monthly orders and revenue)
- Recent activity and low stock alerts
- Audit trail of admin actions

### ✅ **Product Management**
- View all products with search and filters
- Add new products with images and detailed information
- Edit existing products
- Manage inventory and stock levels
- Set low stock thresholds
- Activate/deactivate products

### ✅ **Navigation Menu**
- **Dashboard**: Overview and statistics
- **Products**: Complete product management
- **Orders**: Order management (framework ready)
- **Customers**: User management (framework ready)
- **Analytics**: Reports and insights (framework ready)
- **Settings**: System configuration (framework ready)

## 🔧 Troubleshooting

### Issue: "This site can't be reached"
**Solution**: 
1. Check that the hosts file entry is correct
2. Restart your browser
3. Clear DNS cache: `ipconfig /flushdns`

### Issue: Apache won't start
**Solution**:
1. Check Apache error logs in `C:\xampp\apache\logs\error.log`
2. Ensure no other services are using port 80
3. Verify virtual host configuration syntax

### Issue: 404 Not Found on admin routes
**Solution**:
1. Run `php artisan route:clear`
2. Run `php artisan config:clear`
3. Check that the subdomain is properly configured in Apache

### Issue: Admin login redirects to main site
**Solution**:
1. Clear browser cookies for both domains
2. Ensure the subdomain routes are defined before main routes in `routes/web.php`

## 📁 File Structure

The admin system uses these key files:

```
app/
├── Http/
│   ├── Controllers/Admin/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   └── ProductController.php
│   └── Middleware/
│       └── AdminAuth.php
├── Models/
│   ├── Admin.php
│   ├── AuditLog.php
│   └── ...
resources/views/admin/
├── layouts/
│   └── app.blade.php
├── partials/
│   ├── sidebar.blade.php
│   ├── header.blade.php
│   └── alerts.blade.php
├── auth/
│   └── login.blade.php
├── dashboard/
│   └── index.blade.php
└── products/
    ├── index.blade.php
    └── create.blade.php
```

## 🎯 Next Steps

The admin system is now fully functional with subdomain access! You can:

1. **Access the admin panel**: `http://admin.davidswood.test/login`
2. **Manage products**: Add, edit, and organize your product catalog
3. **Monitor activity**: View dashboard statistics and audit logs
4. **Extend functionality**: Add more modules like order management, user management, etc.

The foundation is complete and ready for additional features! 🚀
