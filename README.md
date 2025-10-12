# David's Wood Furniture - E-Commerce Platform

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12.0">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/TailwindCSS-3.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
</p>

A modern, full-featured e-commerce platform for a wood furniture business, built with Laravel 12. The platform features a beautiful customer-facing storefront and a powerful admin dashboard accessed via subdomain with comprehensive product management, order tracking, inventory control, and analytics.

---

## 📋 Table of Contents

- [Features](#-features)
- [Demo](#-demo)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
  - [Basic Setup](#1-basic-setup)
  - [Subdomain Configuration](#2-subdomain-configuration)
  - [SSL/HTTPS Setup](#3-sslhttps-setup)
  - [Database Setup](#4-database-setup)
  - [Final Configuration](#5-final-configuration)
- [Usage](#-usage)
- [Project Structure](#-project-structure)
- [Technologies Used](#-technologies-used)
- [Contributing](#-contributing)
- [Testing](#-testing)
- [License](#-license)
- [Authors & Acknowledgements](#-authors--acknowledgements)
- [Contact](#-contact)
- [Roadmap](#-roadmap)
- [Troubleshooting](#-troubleshooting)

---

## ✨ Features

### Customer Portal
- 🛍️ **Product Catalog** - Browse furniture by categories, rooms, and subcategories
- 🔍 **Advanced Search** - Filter products by price, category, availability
- 🛒 **Shopping Cart** - Add/remove items, update quantities, real-time total calculation
- ❤️ **Wishlist** - Save favorite items (Redis/Database/Session storage options)
- 👤 **User Authentication** - Register, login, profile management
- 📦 **Order Management** - Place orders, track status, view order history
- 📱 **Responsive Design** - Mobile-first, optimized for all devices
- 💳 **Secure Checkout** - Protected payment processing
- 📄 **CMS Pages** - Dynamic content pages (About, Contact, Privacy, etc.)

### Admin Dashboard (Subdomain)
- 📊 **Real-time Dashboard** - Statistics, charts, recent activity
- 📦 **Product Management** - Full CRUD operations with image uploads
- 🏷️ **Category Management** - Hierarchical category structure
- 📈 **Inventory Tracking** - Stock levels, low stock alerts, movement history
- 👥 **Customer Management** - View and manage customer accounts
- 🛍️ **Order Management** - Process orders, update status, generate reports
- 📊 **Analytics** - Sales trends, revenue reports, customer insights
- 🔔 **Notifications** - Admin alerts and activity monitoring
- 📝 **Audit Logs** - Complete activity tracking for security
- 👨‍💼 **Employee Management** - Role-based access control
- ⚙️ **Settings** - Configure site settings, appearance, and behavior

### Security Features
- 🔐 **Role-based Access Control** - Admin middleware protection
- 🛡️ **HTTPS/SSL Support** - Secure data transmission
- 🔑 **Password Encryption** - Bcrypt hashing
- 🚫 **CSRF Protection** - Built-in Laravel security
- 📋 **Audit Trail** - Complete action logging
- 🌐 **Subdomain Isolation** - Admin panel separated from public site

---

## 🎥 Demo

**Public Site**: `https://davidswood.test:8443`  
**Admin Panel**: `https://admin.davidswood.test:8443`

> **Note**: The site runs on custom ports (8080 for HTTP, 8443 for HTTPS) to avoid conflicts with other services.

### Default Admin Credentials
```
Super Admin:
Email: admin@davidswood.com
Password: password123

Manager:
Email: manager@davidswood.com
Password: password123

Staff:
Email: staff@davidswood.com
Password: password123
```

---

## 🔧 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x and npm
- **XAMPP/WAMP/MAMP** (or any Apache web server)
- **MySQL** or **SQLite** (included in Laravel)
- **Git** (for version control)
- **OpenSSL** (for SSL certificate generation)

### Recommended System Requirements
- **RAM**: 4GB minimum, 8GB recommended
- **Disk Space**: 500MB for application + dependencies
- **OS**: Windows 10/11, macOS 10.15+, Linux (Ubuntu 20.04+)

---

## 📥 Installation

### 1. Basic Setup

#### Clone the Repository
```bash
git clone https://github.com/yourusername/davids-wood-furniture.git
cd davids-wood-furniture
```

#### Install PHP Dependencies
```bash
composer install
```

#### Install Node Dependencies
```bash
npm install
```

#### Create Environment File
```bash
# Windows (PowerShell)
copy .env.example .env

# macOS/Linux
cp .env.example .env
```

#### Generate Application Key
```bash
php artisan key:generate
```

---

### 2. Subdomain Configuration

The admin panel is accessed via a subdomain (`admin.davidswood.test`). Follow these steps to configure it:

#### Windows (XAMPP)

**Step 1: Update Hosts File** (Run as Administrator)
```
File Location: C:\Windows\System32\drivers\etc\hosts

Add these lines:
127.0.0.1    davidswood.test
127.0.0.1    admin.davidswood.test
```

**Step 2: Configure Apache Virtual Hosts**
```apache
File Location: C:\xampp\apache\conf\extra\httpd-vhosts.conf

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

**Step 3: Enable Required Apache Modules**
```apache
File Location: C:\xampp\apache\conf\httpd.conf

Ensure these modules are uncommented:
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule vhost_alias_module modules/mod_vhost_alias.so
```

**Step 4: Restart Apache**
- Open XAMPP Control Panel
- Stop Apache
- Start Apache

#### macOS/Linux (Laravel Valet)
```bash
cd davids-wood-furniture
valet link davidswood

# Admin panel will be available at:
# http://admin.davidswood.test
```

#### Laravel Homestead
```yaml
# Edit Homestead.yaml
sites:
    - map: davidswood.test
      to: /home/vagrant/code/davids-wood-furniture/public

# Edit hosts file
192.168.56.56    davidswood.test
192.168.56.56    admin.davidswood.test

# Provision
vagrant reload --provision
```

---

### 3. SSL/HTTPS Setup

For secure local development with HTTPS on port 8443:

#### Generate Self-Signed SSL Certificates with SAN

**Create Certificate Directories** (Windows - XAMPP)
```powershell
mkdir C:\xampp\apache\conf\ssl.crt\davidswood
```

**Create OpenSSL Configuration File**

Create `C:\xampp\apache\conf\ssl.crt\davidswood\req-v2.conf`:
```ini
[req]
default_bits = 2048
prompt = no
default_md = sha256
distinguished_name = dn
req_extensions = v3_req

[dn]
C = US
ST = State
L = City
O = Organization
OU = Organizational Unit
CN = davidswood.test

[v3_req]
subjectAltName = @alt_names
basicConstraints = CA:FALSE
keyUsage = nonRepudiation, digitalSignature, keyEncipherment
extendedKeyUsage = serverAuth

[alt_names]
DNS.1 = davidswood.test
DNS.2 = *.davidswood.test
DNS.3 = admin.davidswood.test
```

**Generate Certificate with Proper Extensions**
```powershell
# Navigate to OpenSSL directory
cd C:\xampp\apache\bin

# Generate certificate (valid for 365 days) with SAN
.\openssl.exe req -new -x509 -nodes -days 365 `
  -keyout C:\xampp\apache\conf\ssl.crt\davidswood\davidswood-v2.key `
  -out C:\xampp\apache\conf\ssl.crt\davidswood\davidswood-v2.crt `
  -config C:\xampp\apache\conf\ssl.crt\davidswood\req-v2.conf `
  -extensions v3_req
```

> **Important**: Modern browsers (Chrome, Edge) require Subject Alternative Names (SAN) in SSL certificates. The configuration above includes proper SAN extensions for both main domain and subdomain.

#### Configure Apache for HTTPS on Port 8443

**Create HTTPS Virtual Hosts**

Create `C:\xampp\apache\conf\extra\httpd-davidswood-ssl.conf`:
```apache
# SSL Configuration for davidswood.test on port 8443
<VirtualHost *:8443>
    DocumentRoot "C:/xampp/htdocs/davids-wood-furniture/public"
    ServerName davidswood.test:8443
    ServerAlias www.davidswood.test:8443
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile "conf/ssl.crt/davidswood/davidswood-v2.crt"
    SSLCertificateKeyFile "conf/ssl.crt/davidswood/davidswood-v2.key"
    
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
    SSLCertificateFile "conf/ssl.crt/davidswood/davidswood-v2.crt"
    SSLCertificateKeyFile "conf/ssl.crt/davidswood/davidswood-v2.key"
    
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

**Create HTTP to HTTPS Redirects**

Create `C:\xampp\apache\conf\extra\httpd-davidswood.conf`:
```apache
# HTTP Virtual Host for davidswood.test (Port 8080)
<VirtualHost davidswood.test:80>
    ServerName davidswood.test
    Redirect permanent / https://davidswood.test:8443/
</VirtualHost>

# HTTP Virtual Host for admin.davidswood.test (Port 8080)
<VirtualHost admin.davidswood.test:80>
    ServerName admin.davidswood.test
    Redirect permanent / https://admin.davidswood.test:8443/
</VirtualHost>
```

**Update Apache Main Configuration**

Edit `C:\xampp\apache\conf\httpd.conf`:
```apache
# Add Listen directive for port 8443
Listen 8443

# Include SSL configuration at the end of the file
Include conf/extra/httpd-davidswood-ssl.conf
Include conf/extra/httpd-davidswood.conf
```

**Verify Apache Configuration**
```powershell
# Test configuration syntax
C:\xampp\apache\bin\httpd.exe -t

# Should output: Syntax OK
```

**Update .env for HTTPS**
```env
APP_URL=https://davidswood.test:8443
FORCE_HTTPS=false  # Set to true in production
```

**Install Certificate to Trust Store (Windows)**

Run PowerShell as Administrator:
```powershell
# Install the certificate to Trusted Root Certification Authorities
certutil -addstore -f "ROOT" "C:\xampp\apache\conf\ssl.crt\davidswood\davidswood-v2.crt"

# Verify installation
certutil -store "ROOT" | findstr -i "davidswood"
```

**Trust Certificate in Browser**
1. Close all browser windows completely
2. Open browser and visit `https://davidswood.test:8443`
3. You should see the green lock 🔒
4. If still showing "Not secure":
   - Press `Ctrl+Shift+Delete` → Clear cached images and cookies
   - Restart browser
   - Visit the site again

> **Note**: For detailed certificate trust instructions, see `TRUST_CERTIFICATE_GUIDE.md`

**Restart Apache**
1. Open XAMPP Control Panel
2. Stop Apache
3. Start Apache

---

### 4. Database Setup

#### Configure Database

Edit your `.env` file:

**MySQL (Recommended)**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=davids_wood
DB_USERNAME=root
DB_PASSWORD=
```

**Create MySQL Database**

Open phpMyAdmin (http://localhost/phpmyadmin) or MySQL CLI:
```sql
CREATE DATABASE davids_wood CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Or using MySQL CLI:
```bash
# Windows (XAMPP)
C:\xampp\mysql\bin\mysql.exe -u root -p

# Then run:
CREATE DATABASE davids_wood CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**Alternative: SQLite (Simple Setup)**
```env
DB_CONNECTION=sqlite
# DB_DATABASE will use database/database.sqlite
```

Create SQLite file:
```powershell
# Windows (PowerShell)
New-Item -ItemType File -Path database/database.sqlite

# macOS/Linux
touch database/database.sqlite
```

#### Run Migrations
```bash
# Run migrations
php artisan migrate

# If you encounter migration order issues, use --force
php artisan migrate --force

# Clear caches after migration
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

#### Seed Database with Sample Data
```bash
# Seed all data (recommended for first setup)
php artisan db:seed

# Or seed specific seeders:
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=ProductSeeder
php artisan db:seed --class=WoodProductsSeeder
```

> **Note**: If you encounter "Table already exists" errors during migration, you may need to manually mark migrations as completed in the `migrations` table or drop and recreate the database.

---

### 5. Final Configuration

#### Build Frontend Assets
```bash
# Development
npm run dev

# Production
npm run build
```

#### Set Permissions (Linux/macOS)
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

#### Clear All Caches
```bash
php artisan optimize:clear
```

#### Configure Additional Settings

Update `.env` with your settings:
```env
APP_NAME="David's Wood Furniture"
APP_ENV=local
APP_DEBUG=true
APP_URL=https://davidswood.test:8443

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=file

# Queue (optional)
QUEUE_CONNECTION=sync

# Mail (configure for production)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@davidswood.test"
MAIL_FROM_NAME="${APP_NAME}"
```

#### Verify Installation
```bash
# List all routes
php artisan route:list

# Check for any issues
php artisan about
```

---

## 🚀 Usage

### Starting the Development Server

```bash
# Start Laravel development server
php artisan serve

# In another terminal, start Vite for asset compilation
npm run dev
```

Access the application:
- **Public Site**: `https://davidswood.test:8443`
- **Admin Panel**: `https://admin.davidswood.test:8443`
- **HTTP Access**: `http://davidswood.test:8080` (redirects to HTTPS)

### Using the Admin Panel

1. Navigate to `https://admin.davidswood.test:8443/login`
2. Login with admin credentials (see [Demo](#-demo) section)
3. Access available features:
   - **Dashboard**: View statistics and recent activity
   - **Products**: Manage product catalog
   - **Orders**: Process and track orders
   - **Customers**: Manage customer accounts
   - **Analytics**: View reports and insights
   - **Settings**: Configure application settings

### Managing Products

```php
// Create a new product via Admin Panel
1. Go to Products → Add New Product
2. Fill in product details:
   - Name, Description, Price
   - Category, Subcategory
   - SKU, Stock Quantity
   - Images
3. Set product status (Active/Inactive)
4. Save

// Or via Artisan Console
php artisan tinker
>>> $product = new App\Models\Product();
>>> $product->name = "Oak Dining Table";
>>> $product->price = 599.99;
>>> $product->save();
```

### Managing Orders

```bash
# View orders
php artisan tinker
>>> App\Models\Order::with('items', 'user')->get();

# Update order status
>>> $order = App\Models\Order::find(1);
>>> $order->status = 'shipped';
>>> $order->save();
```

---

## 📁 Project Structure

```
davids-wood-furniture/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin panel controllers
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── ProductController.php
│   │   │   ├── CartController.php
│   │   │   ├── OrderController.php
│   │   │   └── ProductController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php  # Admin authentication
│   │       └── ForceHttps.php       # HTTPS enforcement
│   ├── Models/
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── Cart.php
│   │   ├── User.php
│   │   └── Admin.php
│   └── Services/
│       ├── DatabaseWishlistService.php
│       ├── RedisWishlistService.php
│       └── SessionWishlistService.php
├── database/
│   ├── migrations/                  # Database schema
│   └── seeders/                     # Sample data
├── public/
│   ├── admin/                       # Admin panel assets
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── frontend/                    # Public site assets
│       ├── js/
│       ├── style.css
│       └── assets/
├── resources/
│   ├── views/
│   │   ├── admin/                   # Admin panel views
│   │   │   ├── dashboard/
│   │   │   ├── products/
│   │   │   ├── orders/
│   │   │   └── layouts/
│   │   ├── layouts/                 # Public site layouts
│   │   ├── partials/                # Reusable components
│   │   └── welcome.blade.php
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                      # Web routes (with subdomain)
│   ├── api.php                      # API routes
│   └── console.php                  # Artisan commands
├── .env                             # Environment configuration
├── composer.json                    # PHP dependencies
├── package.json                     # Node dependencies
└── README.md
```

---

## 🛠 Technologies Used

### Backend
- **Laravel 12** - PHP Framework
- **PHP 8.2+** - Programming Language
- **MySQL** - Primary database (SQLite alternative available)
- **Eloquent ORM** - Database abstraction
- **Laravel Sanctum** - API authentication (optional)
- **Laravel Socialite** - Social authentication
- **Subdomain Routing** - Admin panel isolation

### Frontend
- **Blade Templates** - Server-side rendering
- **Tailwind CSS 3** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Vite** - Frontend build tool
- **JavaScript ES6+** - Client-side scripting

### Development Tools
- **Composer** - PHP dependency manager
- **npm** - Node package manager
- **Laravel Pint** - Code style fixer
- **PHPUnit** - Testing framework
- **Laravel Pail** - Log viewer

### Infrastructure
- **Apache/Nginx** - Web server
- **OpenSSL** - SSL certificates
- **Redis** (optional) - Caching and sessions
- **Git** - Version control

---

## 🤝 Contributing

We welcome contributions from the community! Here's how you can help:

### Getting Started

1. **Fork the repository**
2. **Clone your fork**
   ```bash
   git clone https://github.com/yourusername/davids-wood-furniture.git
   ```
3. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```
4. **Make your changes**
5. **Commit your changes**
   ```bash
   git commit -m 'Add some amazing feature'
   ```
6. **Push to the branch**
   ```bash
   git push origin feature/amazing-feature
   ```
7. **Open a Pull Request**

### Coding Standards

- Follow **PSR-12** coding standards
- Run Laravel Pint before committing:
  ```bash
  ./vendor/bin/pint
  ```
- Write meaningful commit messages
- Add comments for complex logic
- Update documentation for new features

### Pull Request Guidelines

- Describe what your PR does
- Reference any related issues
- Include screenshots for UI changes
- Ensure all tests pass
- Keep PRs focused and small

---

## 🧪 Testing

### Run Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/ProductTest.php

# Run specific test method
php artisan test --filter testUserCanViewProducts
```

### Writing Tests

Tests are located in the `tests/` directory:
- `tests/Feature/` - Feature tests (HTTP tests, etc.)
- `tests/Unit/` - Unit tests (individual classes)

Example test:
```php
// tests/Feature/ProductTest.php
public function test_user_can_view_products()
{
    $response = $this->get('/products');
    $response->assertStatus(200);
    $response->assertViewIs('products');
}
```

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

```
MIT License

Copyright (c) 2025 David's Wood Furniture

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 👥 Authors & Acknowledgements

### Authors
- **Primary Developer** - Initial work and ongoing development
- **Contributors** - See [CONTRIBUTORS.md](CONTRIBUTORS.md) for a list of contributors

### Acknowledgements
- **Laravel Team** - For the amazing framework
- **Taylor Otwell** - Creator of Laravel
- **Tailwind CSS Team** - For the utility-first CSS framework
- **Alpine.js Team** - For the lightweight JavaScript framework
- **Open Source Community** - For countless packages and inspiration

### Special Thanks
- Stack Overflow community for troubleshooting help
- Laravel documentation and community
- All contributors who have helped improve this project

### Resources & Inspiration
- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev)

---

## 📞 Contact

### Project Links
- **Repository**: [https://github.com/yourusername/davids-wood-furniture](https://github.com/yourusername/davids-wood-furniture)
- **Issue Tracker**: [https://github.com/yourusername/davids-wood-furniture/issues](https://github.com/yourusername/davids-wood-furniture/issues)
- **Documentation**: [https://github.com/yourusername/davids-wood-furniture/wiki](https://github.com/yourusername/davids-wood-furniture/wiki)

### Get In Touch
- **Email**: support@davidswood.com
- **Website**: https://davidswood.test
- **Twitter**: [@davidswoodfurn](https://twitter.com/davidswoodfurn)
- **Discord**: [Join our community](https://discord.gg/davidswoodfurniture)

### Support
For support, please:
1. Check the [Troubleshooting](#-troubleshooting) section
2. Search [existing issues](https://github.com/yourusername/davids-wood-furniture/issues)
3. Create a [new issue](https://github.com/yourusername/davids-wood-furniture/issues/new) if needed
4. Join our [Discord community](https://discord.gg/davidswoodfurniture) for real-time help

---

## 🗺 Roadmap

### Version 1.0 (Current) ✅
- [x] Core e-commerce functionality
- [x] Product catalog with categories
- [x] Shopping cart and wishlist
- [x] User authentication
- [x] Admin dashboard with subdomain
- [x] Order management
- [x] Inventory tracking
- [x] SSL/HTTPS support

### Version 1.1 (In Progress) 🚧
- [ ] Payment gateway integration (Stripe, PayPal)
- [ ] Email notifications for orders
- [ ] Product reviews and ratings
- [ ] Advanced search with filters
- [ ] Product comparison feature
- [ ] Customer order tracking portal
- [ ] Export orders to CSV/PDF
- [ ] Bulk product import/export

### Version 1.2 (Planned) 📋
- [ ] Multi-currency support
- [ ] Multi-language support (i18n)
- [ ] Advanced analytics dashboard
- [ ] Promotional codes and discounts
- [ ] Gift cards functionality
- [ ] Customer loyalty program
- [ ] Live chat support
- [ ] Product recommendations engine

### Version 2.0 (Future) 🔮
- [ ] Mobile app (iOS/Android)
- [ ] Progressive Web App (PWA)
- [ ] Real-time inventory sync
- [ ] Marketplace integration (Amazon, eBay)
- [ ] Social media integration
- [ ] Advanced SEO tools
- [ ] A/B testing framework
- [ ] Marketing automation

### Feature Requests
Have an idea? [Submit a feature request](https://github.com/yourusername/davids-wood-furniture/issues/new?labels=enhancement)

---

## 🔧 Troubleshooting

### Common Issues

#### Issue: "This site can't be reached"
**Solution:**
1. Check hosts file entries are correct
2. Restart your web server (Apache/Nginx)
3. Flush DNS cache:
   ```bash
   # Windows
   ipconfig /flushdns
   
   # macOS
   sudo dscacheutil -flushcache
   
   # Linux
   sudo systemd-resolve --flush-caches
   ```
4. Try accessing with IP: `http://127.0.0.1`

#### Issue: "MissingAppKeyException"
**Solution:**
```bash
php artisan key:generate
php artisan config:clear
```

#### Issue: Apache won't start
**Solution:**
1. Check port 80/443 is not in use:
   ```bash
   # Windows
   netstat -ano | findstr :80
   netstat -ano | findstr :443
   
   # macOS/Linux
   lsof -i :80
   lsof -i :443
   ```
2. Check Apache error logs:
   - Windows: `C:\xampp\apache\logs\error.log`
   - Linux: `/var/log/apache2/error.log`
3. Verify virtual host syntax:
   ```bash
   # Test Apache configuration
   apache2ctl configtest  # Linux
   httpd -t              # Windows/XAMPP
   ```

#### Issue: 404 Not Found on routes
**Solution:**
```bash
# Clear route cache
php artisan route:clear

# Clear config cache
php artisan config:clear

# Verify .htaccess exists in public/
ls public/.htaccess

# Ensure mod_rewrite is enabled (Apache)
```

#### Issue: CSS/JS not loading
**Solution:**
```bash
# Rebuild assets
npm run build

# Clear view cache
php artisan view:clear

# Check asset paths in blade files
```

#### Issue: Database connection failed
**Solution:**
1. Verify database credentials in `.env`
2. For SQLite, ensure file exists:
   ```bash
   touch database/database.sqlite
   ```
3. For MySQL, create database:
   ```sql
   CREATE DATABASE davidswood_furniture;
   ```
4. Test connection:
   ```bash
   php artisan migrate:status
   ```

#### Issue: Permission denied (Linux/macOS)
**Solution:**
```bash
# Fix permissions
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### Issue: SSL certificate errors / "Not secure" warning
**Solution:**

**Step 1: Verify certificate has SAN (Subject Alternative Names)**
```powershell
cd C:\xampp\apache\bin
.\openssl.exe x509 -in C:\xampp\apache\conf\ssl.crt\davidswood\davidswood-v2.crt -text -noout | Select-String -Pattern "DNS:"
```
Should show: `DNS:davidswood.test, DNS:*.davidswood.test, DNS:admin.davidswood.test`

**Step 2: Install certificate to Windows Trust Store** (Run PowerShell as Administrator)
```powershell
# Remove old certificate (if exists)
certutil -delstore "ROOT" "davidswood.test"

# Install new certificate
certutil -addstore -f "ROOT" "C:\xampp\apache\conf\ssl.crt\davidswood\davidswood-v2.crt"

# Verify installation
certutil -store "ROOT" | findstr -i "davidswood"
```

**Step 3: Clear browser cache and SSL state**
1. Close ALL browser windows
2. Press `Ctrl+Shift+Delete`
3. Clear "Cached images and files" and "Cookies"
4. Restart browser
5. Visit `https://davidswood.test:8443`

**Step 4: If still not working, check Apache is using correct certificate**
```apache
# In C:\xampp\apache\conf\extra\httpd-davidswood-ssl.conf
SSLCertificateFile "conf/ssl.crt/davidswood/davidswood-v2.crt"
SSLCertificateKeyFile "conf/ssl.crt/davidswood/davidswood-v2.key"
```

**Step 5: Restart Apache**
- XAMPP Control Panel → Stop Apache → Start Apache

> **Note**: See `TRUST_CERTIFICATE_GUIDE.md` for detailed step-by-step instructions with screenshots.

#### Issue: Admin subdomain not working
**Solution:**
1. Verify hosts file includes `admin.davidswood.test`
2. Check virtual host configuration
3. Ensure subdomain routes are defined in `routes/web.php`
4. Clear route cache: `php artisan route:clear`

### Getting Help

If you still have issues:

1. **Check Laravel Logs**: `storage/logs/laravel.log`
2. **Enable Debug Mode**: Set `APP_DEBUG=true` in `.env`
3. **Run Diagnostics**:
   ```bash
   php artisan about
   php artisan route:list
   php artisan config:show
   ```
4. **Search Issues**: [GitHub Issues](https://github.com/yourusername/davids-wood-furniture/issues)
5. **Ask for Help**: [Create an issue](https://github.com/yourusername/davids-wood-furniture/issues/new)

### Useful Commands

```bash
# Clear all caches
php artisan optimize:clear

# View application information
php artisan about

# List all routes
php artisan route:list

# Check database connection
php artisan db:show

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Create admin user
php artisan tinker
>>> User::create(['first_name' => 'Admin', 'last_name' => 'User', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'is_admin' => true])
```

---

## 📝 Recent Updates & Changes

### Version 1.0.1 (October 2025)

#### Domain & Routing Updates
- ✅ **Migrated to custom domain**: Changed from localhost to `davidswood.test`
- ✅ **Subdomain implementation**: Admin panel now accessible at `admin.davidswood.test`
- ✅ **Dynamic URL configuration**: Updated all frontend JavaScript files to use dynamic API endpoints
- ✅ **Route fixes**: Corrected admin navigation routes (`admin.products.index`, `admin.users.index`, `admin.orders.index`)

#### Security Enhancements
- ✅ **HTTPS/SSL implementation**: Full SSL certificate setup with proper Subject Alternative Names (SAN)
- ✅ **Custom port configuration**: Running on port 8080 (HTTP) and 8443 (HTTPS) to avoid conflicts
- ✅ **HTTP to HTTPS redirects**: Automatic redirection from HTTP to HTTPS
- ✅ **ForceHttps middleware**: Created middleware for HTTPS enforcement (configurable via `.env`)
- ✅ **Admin authentication fix**: Updated AdminMiddleware to use correct guard (`admin`)
- ✅ **Modern SSL protocols**: Disabled SSLv3, TLSv1, TLSv1.1; using TLSv1.2+ only

#### Database Changes
- ✅ **Migrated to MySQL**: Switched from SQLite to MySQL for better performance
- ✅ **Database name**: Using `davids_wood` database
- ✅ **Migration fixes**: Resolved migration order issues with subcategory columns
- ✅ **Session table**: Configured database-driven sessions

#### Configuration Files Updated
- ✅ **Apache virtual hosts**: Created custom configurations for HTTP and HTTPS
- ✅ **SSL certificates**: Generated proper certificates with SAN extensions for modern browsers
- ✅ **Environment variables**: Updated `.env` with correct URLs, database settings, and security options
- ✅ **Middleware registration**: Registered ForceHttps middleware in `bootstrap/app.php`

#### JavaScript & Frontend
- ✅ **Dynamic API URLs**: Updated `config.js` and `api.js` to use `window.location.origin`
- ✅ **Removed hardcoded URLs**: Eliminated all hardcoded localhost references
- ✅ **Cross-domain support**: API calls now work seamlessly across subdomains

#### Documentation
- ✅ **SSL Setup Guide**: Created `SSL_SETUP_INSTRUCTIONS.md`
- ✅ **HTTPS Port Guide**: Created `HTTPS_PORT_8443_SETUP.md`
- ✅ **Certificate Trust Guide**: Created `TRUST_CERTIFICATE_GUIDE.md`
- ✅ **Updated README**: Comprehensive documentation of all changes

#### Files Modified
```
Modified:
- .env (APP_URL, DB_*, FORCE_HTTPS)
- routes/web.php (subdomain routing)
- app/Http/Middleware/AdminAuth.php (guard fix)
- public/frontend/js/config.js (dynamic URLs)
- public/frontend/js/api.js (dynamic URLs)
- resources/views/admin/partials/tailadmin-sidebar.blade.php (route names)
- bootstrap/app.php (middleware registration)
- config/app.php (force_https config)

Created:
- app/Http/Middleware/ForceHttps.php
- C:\xampp\apache\conf\extra\httpd-davidswood-ssl.conf
- C:\xampp\apache\conf\extra\httpd-davidswood.conf
- C:\xampp\apache\conf\ssl.crt\davidswood\req-v2.conf
- C:\xampp\apache\conf\ssl.crt\davidswood\davidswood-v2.crt
- C:\xampp\apache\conf\ssl.crt\davidswood\davidswood-v2.key
- SSL_SETUP_INSTRUCTIONS.md
- HTTPS_PORT_8443_SETUP.md
- TRUST_CERTIFICATE_GUIDE.md

Renamed:
- database/migrations/2025_01_27_* → 2025_09_25_* (migration order fix)
```

---

## 🎉 Quick Start Summary

```bash
# 1. Clone and install
git clone https://github.com/yourusername/davids-wood-furniture.git
cd davids-wood-furniture
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Setup MySQL database
# Create database in phpMyAdmin or MySQL CLI:
# CREATE DATABASE davids_wood;

# Update .env with database credentials
# DB_CONNECTION=mysql
# DB_DATABASE=davids_wood
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Run migrations
php artisan migrate
php artisan db:seed

# 5. Configure hosts (Windows - as Administrator)
# Add to C:\Windows\System32\drivers\etc\hosts:
# 127.0.0.1    davidswood.test
# 127.0.0.1    admin.davidswood.test

# 6. Setup Apache virtual hosts and SSL (see Installation section)
# - Configure httpd-davidswood-ssl.conf (port 8443)
# - Configure httpd-davidswood.conf (port 8080 redirects)
# - Generate SSL certificates with SAN
# - Install certificate to trust store

# 7. Build assets
npm run build

# 8. Start Apache via XAMPP Control Panel

# 9. Access the application
# Public: https://davidswood.test:8443
# Admin: https://admin.davidswood.test:8443
```

---

<p align="center">Made with ❤️ by David's Wood Furniture Team</p>
<p align="center">© 2025 David's Wood Furniture. All rights reserved.</p>
