# Admin Dashboard Setup Guide

This guide will help you set up the Laravel admin dashboard with subdomain access (e.g., `admin.myshop.test`).

## 🚀 Quick Start

### 1. Database Setup
The admin functionality requires the `is_admin` column in the users table. This has been added via migration.

```bash
# Run migrations (if not already done)
php artisan migrate

# Create admin users
php artisan db:seed --class=AdminUserSeeder
```

### 2. Admin User Credentials
After running the seeder, you can login with these accounts:

**Primary Admin:**
- Email: `admin@dwatelier.com`
- Password: `password123`

**Demo Admin (Nerissa):**
- Email: `nerissa@example.com`
- Password: `password123`

## 🌐 Subdomain Configuration

### Option 1: XAMPP (Windows)

1. **Edit hosts file** (Run as Administrator):
   ```
   # File: C:\Windows\System32\drivers\etc\hosts
   127.0.0.1    myshop.test
   127.0.0.1    admin.myshop.test
   ```

2. **Configure Apache Virtual Host**:
   ```apache
   # File: C:\xampp\apache\conf\extra\httpd-vhosts.conf
   
   <VirtualHost *:80>
       ServerName myshop.test
       ServerAlias admin.myshop.test
       DocumentRoot "C:/xampp/htdocs/davids-wood-furniture/public"
       <Directory "C:/xampp/htdocs/davids-wood-furniture/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

3. **Restart Apache** in XAMPP Control Panel

4. **Update .env file**:
   ```env
   APP_URL=http://myshop.test
   ```

### Option 2: Laravel Valet (macOS/Linux)

```bash
# Navigate to your project directory
cd /path/to/davids-wood-furniture

# Link the site
valet link myshop

# The admin panel will be available at:
# http://admin.myshop.test
```

### Option 3: Laravel Homestead

1. **Edit Homestead.yaml**:
   ```yaml
   sites:
       - map: myshop.test
         to: /home/vagrant/code/davids-wood-furniture/public
   ```

2. **Edit hosts file**:
   ```
   # Add to /etc/hosts (macOS/Linux) or C:\Windows\System32\drivers\etc\hosts (Windows)
   192.168.56.56    myshop.test
   192.168.56.56    admin.myshop.test
   ```

3. **Provision Homestead**:
   ```bash
   vagrant reload --provision
   ```

### Option 4: Docker/Sail

1. **Update docker-compose.yml** (if using Laravel Sail):
   ```yaml
   services:
       laravel.test:
           # ... existing config
           labels:
               - "traefik.http.routers.laravel.rule=Host(\`myshop.test\`) || Host(\`admin.myshop.test\`)"
   ```

2. **Edit hosts file**:
   ```
   127.0.0.1    myshop.test
   127.0.0.1    admin.myshop.test
   ```

## 🎨 Admin Dashboard Features

### Current Pages
- **Dashboard** (`/`) - Overview with stats and recent activity
- **Products** (`/products`) - Product management with grid layout
- **Customers** (`/customers`) - Customer management (placeholder)
- **Orders** (`/orders`) - Order management (placeholder)
- **Analytics** (`/analytics`) - Analytics dashboard (placeholder)

### Design Features
- ✅ Responsive sidebar with collapsible mobile menu
- ✅ Clean, modern UI matching the provided design
- ✅ Tailwind CSS for styling
- ✅ Alpine.js for interactive components
- ✅ Product grid with search and filters
- ✅ User authentication and admin middleware
- ✅ Logout functionality

### Navigation Structure
```
📊 Dashboard
📦 Products (active highlighting)
🛒 Purchases
👥 Customers
📈 Analytics
   └── By Category (sub-menu)
📄 Orders
↩️ Returns
⭐ Reviews
🎁 Promotions
⚙️ Settings
↪️ Logout
```

## 🔐 Security Features

### Admin Middleware
- Checks user authentication
- Verifies `is_admin = true`
- Redirects unauthorized users
- Located: `app/Http/Middleware/AdminMiddleware.php`

### Route Protection
All admin routes are protected by the `admin` middleware:
```php
Route::domain('admin.{domain}')->middleware(['admin'])->group(function () {
    // Admin routes
});
```

## 🛠 Development

### Adding New Admin Pages

1. **Create Controller Method**:
   ```php
   // app/Http/Controllers/Admin/DashboardController.php
   public function newPage()
   {
       return view('admin.new-page.index');
   }
   ```

2. **Add Route**:
   ```php
   // routes/web.php (in admin subdomain group)
   Route::get('/new-page', [DashboardController::class, 'newPage'])->name('new-page');
   ```

3. **Create View**:
   ```php
   // resources/views/admin/new-page/index.blade.php
   @extends('admin.layouts.app')
   @section('title', 'New Page')
   @section('content')
       <!-- Page content -->
   @endsection
   ```

4. **Update Sidebar**:
   ```php
   // resources/views/admin/partials/sidebar.blade.php
   <a href="{{ route('admin.new-page') }}" class="...">New Page</a>
   ```

### Customizing Styles
The admin panel uses Tailwind CSS via CDN. To customize:

1. Install Tailwind locally:
   ```bash
   npm install -D tailwindcss
   npx tailwindcss init
   ```

2. Create custom CSS file and compile with Vite

### Database Integration
To use real data instead of dummy data:

1. **Update Controller**:
   ```php
   public function products()
   {
       $products = Product::with('category')->paginate(6);
       return view('admin.products.index', compact('products'));
   }
   ```

2. **Update View** to use model attributes

## 🚨 Troubleshooting

### Common Issues

**1. Subdomain not working:**
- Check hosts file entries
- Restart web server
- Clear browser cache
- Verify virtual host configuration

**2. 403 Access Denied:**
- Ensure user has `is_admin = true`
- Check middleware registration
- Verify user is logged in

**3. Styles not loading:**
- Check Tailwind CDN connection
- Verify internet connection
- Check browser console for errors

**4. Routes not found:**
- Run `php artisan route:list` to verify routes
- Check route caching: `php artisan route:clear`

### Useful Commands

```bash
# Clear all caches
php artisan optimize:clear

# List all routes
php artisan route:list --name=admin

# Create admin user manually
php artisan tinker
>>> User::create(['first_name' => 'Admin', 'last_name' => 'User', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'is_admin' => true])
```

## 📱 Mobile Responsiveness

The admin dashboard is fully responsive:
- **Desktop**: Full sidebar visible
- **Tablet**: Collapsible sidebar
- **Mobile**: Overlay sidebar with backdrop

## 🎯 Next Steps

1. **Implement CRUD operations** for products, customers, orders
2. **Add real analytics** with charts and graphs
3. **Implement file uploads** for product images
4. **Add user roles and permissions** beyond simple admin flag
5. **Integrate with payment systems** for order management
6. **Add email notifications** for admin actions

## 📞 Support

If you encounter any issues:
1. Check the troubleshooting section above
2. Verify all setup steps were completed
3. Check Laravel logs: `storage/logs/laravel.log`
4. Ensure all dependencies are installed: `composer install`

---

**Access your admin dashboard at:** `http://admin.myshop.test` (or your configured domain)

