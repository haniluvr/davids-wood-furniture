# Changelog

All notable changes to David's Wood Furniture e-commerce platform.

## [1.0.3] - October 13, 2025

### Added
- **Product Review & Rating System**: Complete review functionality for customers
  - 5-star rating system with interactive UI
  - Review submission modal with brand-colored gradient header
  - Only verified purchasers can submit reviews
  - One review per product per order constraint
  - Optional review title (max 255 characters)
  - Required review text (10-1000 characters)
  - Admin moderation with `is_approved` flag
  - Helpful count tracking for future features
  
- **New Database Table**: `product_reviews`
  - Fields: id, product_id, user_id, order_id, rating, title, review, is_verified_purchase, is_approved, helpful_count, timestamps
  - Unique constraint on (user_id, product_id, order_id)
  - Foreign keys with cascade delete
  - Migration: `database/migrations/2025_10_13_164551_create_product_reviews_table.php`

- **New Model**: `app/Models/ProductReview.php`
  - Relationships to Product, User, and Order models
  - Type casting for rating, booleans, and counts
  - Fillable fields for mass assignment

- **New Controller**: `app/Http/Controllers/ProductReviewController.php`
  - `store()` - Submit review with validation and verification
  - `index()` - Get paginated reviews for a product
  - Purchase verification logic
  - Duplicate review prevention

- **API Routes**: Review submission and retrieval
  - `POST /api/reviews/submit` - Submit review (authenticated users only)
  - `GET /api/reviews/{productId}` - Get product reviews (public)
  - Routes added to `routes/web.php`

- **User Interface Components**:
  - Review submission modal in account page
  - "Write Review" button for delivered order items
  - "Reviewed" badge for already-reviewed items
  - Interactive star rating selector with hover effects
  - Real-time character count validation
  - AJAX form submission without page reload
  - Success/error notification messages

- **JavaScript Functions**: Review handling
  - `openReviewModal(productId, productName, orderId)` - Opens modal with product info
  - `closeReviewModal()` - Closes modal and resets form
  - `setRating(rating)` - Interactive star selection
  - `submitReview()` - AJAX submission with validation

- **Sample Data Seeder**: `database/seeders/ProductReviewSeeder.php`
  - Creates 2 sample reviews for testing
  - Various ratings (3-5 stars) and review content
  - Auto-approved for demo purposes

- **Comprehensive Documentation**:
  - `REVIEW_SYSTEM_DOCUMENTATION.md` - Full technical documentation
  - `REVIEW_SYSTEM_QUICK_START.md` - User guide with visual flow
  - `REVIEW_SYSTEM_SUMMARY.md` - Implementation overview

### Changed
- **Product Model**: Extended with review relationships
  - Added `reviews()` relationship - All reviews
  - Added `approvedReviews()` relationship - Only approved reviews
  - Added `average_rating` accessor - Calculated average rating
  - Added `reviews_count` accessor - Total approved reviews count
  - Modified `app/Models/Product.php`

- **Account Page**: Enhanced with review functionality
  - Added review modal HTML and styling
  - Added JavaScript for review submission
  - Modified `resources/views/account.blade.php`

- **Orders List Partial**: Added review buttons
  - "Write Review" button for eligible items
  - "Reviewed" badge for completed reviews
  - Only shows for delivered orders
  - Smart logic to check existing reviews
  - Modified `resources/views/partials/orders-list.blade.php`

### Security
- **Review Validation**: Multi-layer validation
  - Backend validation of all fields
  - User authentication requirement
  - Purchase verification (user must have bought the product)
  - Order ownership verification
  - Duplicate review prevention
  - SQL injection prevention via Eloquent ORM
  - XSS protection via Laravel's escaping
  - CSRF token validation

### Files Modified
```
Modified:
- app/Models/Product.php
- routes/web.php
- resources/views/account.blade.php
- resources/views/partials/orders-list.blade.php

Created:
- database/migrations/2025_10_13_164551_create_product_reviews_table.php
- app/Models/ProductReview.php
- app/Http/Controllers/ProductReviewController.php
- database/seeders/ProductReviewSeeder.php
- REVIEW_SYSTEM_DOCUMENTATION.md
- REVIEW_SYSTEM_QUICK_START.md
- REVIEW_SYSTEM_SUMMARY.md
```

---

## [1.0.2] - October 13, 2025

### Added
- **Order Receipt Generation**: Professional receipt view for customer orders
  - File: `resources/views/receipt.blade.php`
  - Print/download functionality with branded design
  - A4 print-optimized layout
  - Displays order details, customer information, and itemized products
  - Includes payment information and tracking numbers

- **Order Tracking Enhancements**: Improved order tracking in customer account
  - Visual progress indicators with step-by-step status
  - Status steps: pending → processing → shipped → delivered
  - Expandable order details sections
  - Display of tracking numbers when available
  - Updated `resources/views/partials/orders-list.blade.php`

- **Database Schema Updates**: Extended orders table
  - Added `tracking_number` VARCHAR field for shipment tracking
  - Added `shipped_at` TIMESTAMP for shipment date
  - Added `delivered_at` TIMESTAMP for delivery date
  - Added `admin_notes` TEXT field for internal notes
  - Updated migration: `database/migrations/2025_09_25_212128_create_orders_table.php`

- **Console Command**: Tracking number generation placeholder
  - Created `app/Console/Commands/GenerateTrackingNumbers.php`
  - Prepared for automated tracking number assignment

### Changed
- **Order Model**: Enhanced with additional tracking fields
  - Added `tracking_number`, `shipped_at`, `delivered_at`, `admin_notes` to fillable array
  - Updated casts for datetime fields
  - Modified `app/Models/Order.php`

- **Account Controller**: Added receipt viewing functionality
  - Support for displaying order receipts
  - Modified `app/Http/Controllers/AccountController.php`

- **Order Seeders**: Updated test data with tracking information
  - Added tracking numbers to sample orders
  - Modified `database/seeders/TestOrdersSeeder.php`
  - Modified `database/seeders/MoreTestOrdersSeeder.php`

### Fixed
- Improved order status display consistency
- Enhanced mobile responsiveness for order details

### Files Modified
```
Modified:
- app/Http/Controllers/AccountController.php
- app/Models/Order.php
- database/migrations/2025_09_25_212128_create_orders_table.php
- database/seeders/TestOrdersSeeder.php
- database/seeders/MoreTestOrdersSeeder.php
- resources/views/account.blade.php
- resources/views/partials/orders-list.blade.php
- routes/web.php

Created:
- resources/views/receipt.blade.php
- app/Console/Commands/GenerateTrackingNumbers.php
```

---

## [1.0.1] - October 12, 2025

### Added
- **SSL/HTTPS Support**: Full HTTPS implementation with self-signed certificates
  - Created SSL certificate configuration with Subject Alternative Names (SAN)
  - Generated certificates for `davidswood.test` and `admin.davidswood.test`
  - Configured Apache to run HTTPS on port 8443
  - Automatic HTTP to HTTPS redirects on port 8080
  
- **ForceHttps Middleware**: New middleware for enforcing HTTPS connections
  - File: `app/Http/Middleware/ForceHttps.php`
  - Configurable via `FORCE_HTTPS` environment variable
  - Registered in `bootstrap/app.php`

- **Documentation**:
  - `SSL_SETUP_INSTRUCTIONS.md` - Complete SSL setup guide
  - `HTTPS_PORT_8443_SETUP.md` - Port configuration guide
  - `TRUST_CERTIFICATE_GUIDE.md` - Certificate trust instructions
  - `CHANGELOG.md` - This file

- **Apache Configuration Files**:
  - `C:\xampp\apache\conf\extra\httpd-davidswood-ssl.conf` - HTTPS virtual hosts
  - `C:\xampp\apache\conf\extra\httpd-davidswood.conf` - HTTP redirects
  - `C:\xampp\apache\conf\ssl.crt\davidswood\req-v2.conf` - Certificate config

### Changed
- **Domain Migration**: Changed from `localhost` to `davidswood.test`
  - Updated all URLs in `.env` file
  - Updated `APP_URL` to `https://davidswood.test:8443`
  
- **Database Migration**: Switched from SQLite to MySQL
  - Database name: `davids_wood`
  - Updated `.env` with MySQL credentials
  - Configured for better performance and scalability

- **Subdomain Implementation**: Admin panel now on subdomain
  - Main site: `https://davidswood.test:8443`
  - Admin panel: `https://admin.davidswood.test:8443`
  - Updated subdomain routing in `routes/web.php`

- **Dynamic API URLs**: Removed hardcoded URLs
  - `public/frontend/js/config.js` - Now uses `window.location.origin`
  - `public/frontend/js/api.js` - Dynamic API base URL
  - Supports seamless cross-subdomain API calls

- **Admin Authentication**: Fixed authentication guard
  - `app/Http/Middleware/AdminAuth.php` - Now uses `auth()->guard('admin')`
  - Corrected redirect to `admin.login` route

- **Admin Navigation Routes**: Fixed route names
  - `resources/views/admin/partials/tailadmin-sidebar.blade.php`
  - Updated to use correct route names:
    - `admin.products.index`
    - `admin.users.index`
    - `admin.orders.index`

- **SSL Configuration**: Enhanced security
  - Disabled SSLv3, TLSv1, TLSv1.1
  - Using only TLSv1.2 and TLSv1.3
  - Modern cipher suite configuration
  - Proper Subject Alternative Names (SAN) for browser compatibility

- **Port Configuration**: Custom ports to avoid conflicts
  - HTTP: Port 8080
  - HTTPS: Port 8443
  - Updated Apache `httpd.conf` with `Listen 8443`

### Fixed
- **Migration Order Issues**: Resolved migration execution order
  - Renamed migrations from `2025_01_27_*` to `2025_09_25_*`
  - Fixed subcategory column migrations to run after products table creation
  - Files affected:
    - `2025_09_25_212130_add_subcategory_id_to_products_table.php`
    - `2025_09_25_212131_update_products_room_category_structure.php`
    - `2025_09_25_212132_reorder_subcategory_id_column.php`
    - `2025_09_25_212133_fix_subcategory_id_position.php`

- **Apache Configuration Errors**: Fixed duplicate Listen directives
  - Removed duplicate `Listen 8443` from `httpd-ssl.conf`
  - Centralized Listen directive in `httpd.conf`

- **SSL Certificate Browser Compatibility**: Added SAN extensions
  - Modern browsers (Chrome, Edge) require SAN
  - Certificate now includes:
    - `DNS.1 = davidswood.test`
    - `DNS.2 = *.davidswood.test`
    - `DNS.3 = admin.davidswood.test`

- **Environment Configuration**: Fixed missing/incorrect values
  - Generated `APP_KEY` using Laravel key generation
  - Updated database connection settings
  - Added `FORCE_HTTPS` configuration option

### Security
- **HTTPS Enforcement**: All traffic can be forced to HTTPS
- **Certificate Trust**: Proper certificate installation to Windows Trust Store
- **Admin Guard**: Correct authentication guard for admin routes
- **Modern SSL Protocols**: Disabled insecure SSL/TLS versions
- **Subdomain Isolation**: Admin panel separated from public site

### Technical Details

#### Modified Files
```
.env
routes/web.php
app/Http/Middleware/AdminAuth.php
public/frontend/js/config.js
public/frontend/js/api.js
resources/views/admin/partials/tailadmin-sidebar.blade.php
bootstrap/app.php
config/app.php
C:\xampp\apache\conf\httpd.conf
C:\xampp\apache\conf\extra\httpd-ssl.conf
```

#### Created Files
```
app/Http/Middleware/ForceHttps.php
C:\xampp\apache\conf\extra\httpd-davidswood-ssl.conf
C:\xampp\apache\conf\extra\httpd-davidswood.conf
C:\xampp\apache\conf\ssl.crt\davidswood\req-v2.conf
C:\xampp\apache\conf\ssl.crt\davidswood\davidswood-v2.crt
C:\xampp\apache\conf\ssl.crt\davidswood\davidswood-v2.key
SSL_SETUP_INSTRUCTIONS.md
HTTPS_PORT_8443_SETUP.md
TRUST_CERTIFICATE_GUIDE.md
CHANGELOG.md
```

#### Renamed Files
```
database/migrations/2025_01_27_000000_add_subcategory_id_to_products_table.php
  → database/migrations/2025_09_25_212130_add_subcategory_id_to_products_table.php

database/migrations/2025_01_27_000001_update_products_room_category_structure.php
  → database/migrations/2025_09_25_212131_update_products_room_category_structure.php

database/migrations/2025_01_27_000002_reorder_subcategory_id_column.php
  → database/migrations/2025_09_25_212132_reorder_subcategory_id_column.php

database/migrations/2025_01_27_000003_fix_subcategory_id_position.php
  → database/migrations/2025_09_25_212133_fix_subcategory_id_position.php
```

### Deployment Notes

#### For Fresh Installation
1. Clone repository
2. Run `composer install` and `npm install`
3. Copy `.env.example` to `.env`
4. Generate application key: `php artisan key:generate`
5. Create MySQL database: `davids_wood`
6. Update `.env` with database credentials
7. Run migrations: `php artisan migrate`
8. Seed database: `php artisan db:seed`
9. Configure hosts file (Windows):
   ```
   127.0.0.1    davidswood.test
   127.0.0.1    admin.davidswood.test
   ```
10. Setup Apache virtual hosts (see `HTTPS_PORT_8443_SETUP.md`)
11. Generate SSL certificates (see `SSL_SETUP_INSTRUCTIONS.md`)
12. Install certificate to trust store (see `TRUST_CERTIFICATE_GUIDE.md`)
13. Build assets: `npm run build`
14. Start Apache via XAMPP Control Panel
15. Access site at `https://davidswood.test:8443`

#### For Existing Installation
1. Pull latest changes
2. Update `.env`:
   ```env
   APP_URL=https://davidswood.test:8443
   DB_CONNECTION=mysql
   DB_DATABASE=davids_wood
   DB_USERNAME=root
   DB_PASSWORD=
   FORCE_HTTPS=false
   ```
3. Generate application key if missing: `php artisan key:generate`
4. Create MySQL database and migrate
5. Follow SSL setup instructions
6. Clear all caches: `php artisan optimize:clear`
7. Restart Apache

### Breaking Changes
- **URL Changes**: All URLs now use `davidswood.test:8443` instead of `localhost`
- **Database**: Switched from SQLite to MySQL (migration required)
- **Ports**: Now using 8080 (HTTP) and 8443 (HTTPS) instead of default 80/443
- **Admin Access**: Admin panel moved to subdomain `admin.davidswood.test:8443`

### Known Issues
- Self-signed certificates require manual trust installation
- Browser may show "Not secure" until certificate is trusted
- Port 8080/8443 must be available (not used by other services)

### Migration Guide

#### From SQLite to MySQL
```bash
# Export SQLite data (if needed)
php artisan db:seed  # Re-seed in MySQL

# Update .env
DB_CONNECTION=mysql
DB_DATABASE=davids_wood

# Create database
CREATE DATABASE davids_wood;

# Run migrations
php artisan migrate:fresh --seed
```

#### From localhost to davidswood.test
1. Update hosts file
2. Update `.env` APP_URL
3. Clear caches: `php artisan optimize:clear`
4. Restart web server

### Performance Improvements
- MySQL provides better performance than SQLite for production
- Database-driven sessions for better scalability
- Optimized SSL configuration for modern browsers

### Contributors
- Primary Developer - Domain migration, SSL setup, database migration

---

## [1.0.0] - Initial Release

### Added
- Initial e-commerce platform setup
- Product catalog with categories
- Shopping cart functionality
- User authentication
- Admin dashboard
- Order management
- Inventory tracking
- Wishlist feature
- CMS pages
- Audit logging
- Employee management

---

**Note**: For detailed setup instructions, see README.md

