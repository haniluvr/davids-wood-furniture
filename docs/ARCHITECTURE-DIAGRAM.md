# David's Wood E-Commerce System - Architecture Diagram

## High-Level System Architecture

This document provides an accurate representation of the David's Wood e-commerce platform architecture, showing the monolithic Laravel application structure with logical modules and external integrations.

---

## System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         DAVID'S WOOD E-COMMERCE SYSTEM                      │
│                    (Monolithic Laravel 12 Application)                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    PRESENTATION LAYER                                │   │
│  │                    (Laravel Blade Templates)                        │   │
│  ├─────────────────────────────────────────────────────────────────────┤   │
│  │                                                                     │   │
│  │  ┌──────────────────────────────┐  ┌─────────────────────────────┐ │   │
│  │  │   Customer Portal            │  │   Admin Dashboard           │ │   │
│  │  │   (davidswood.shop)          │  │   (admin.davidswood.shop)   │ │   │
│  │  ├──────────────────────────────┤  ├─────────────────────────────┤ │   │
│  │  │ • Product Catalog            │  │ • Dashboard Analytics      │ │   │
│  │  │ • Shopping Cart              │  │ • Product Management        │ │   │
│  │  │ • Checkout & Payment         │  │ • Order Management          │ │   │
│  │  │ • User Profile               │  │ • Inventory Management      │ │   │
│  │  │ • Order History              │  │ • Customer Management       │ │   │
│  │  │ • Product Reviews            │  │ • Analytics & Reports       │ │   │
│  │  │ • Wishlist                   │  │ • Returns & Repairs        │ │   │
│  │  │ • Contact Form               │  │ • Message Management        │ │   │
│  │  │ • CMS Pages                  │  │ • Review Moderation        │ │   │
│  │  └──────────────────────────────┘  └─────────────────────────────┘ │   │
│  │                                                                     │   │
│  │  Technologies: Tailwind CSS 3, Alpine.js, Vite                    │   │
│  │                                                                     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    ↕                                        │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    APPLICATION LAYER                                 │   │
│  │              (Controllers, Services, Middleware)                     │   │
│  ├─────────────────────────────────────────────────────────────────────┤   │
│  │                                                                     │   │
│  │  ┌──────────────────────────────────────────────────────────────┐  │   │
│  │  │              CUSTOMER-FACING CONTROLLERS                       │  │   │
│  │  ├──────────────────────────────────────────────────────────────┤  │   │
│  │  │ • HomeController           - Landing page                    │  │   │
│  │  │ • ProductController        - Product catalog & search        │  │   │
│  │  │ • CartController           - Shopping cart management        │  │   │
│  │  │ • WishlistController      - Wishlist operations             │  │   │
│  │  │ • CheckoutController      - Checkout process                │  │   │
│  │  │ • OrderController         - Order management                │  │   │
│  │  │ • AuthController          - User authentication              │  │   │
│  │  │ • AccountController       - User profile management         │  │   │
│  │  │ • ProductReviewController - Review submission               │  │   │
│  │  │ • ContactController       - Contact form handling           │  │   │
│  │  │ • PaymentMethodController - Payment method selection        │  │   │
│  │  └──────────────────────────────────────────────────────────────┘  │   │
│  │                                                                     │   │
│  │  ┌──────────────────────────────────────────────────────────────┐  │   │
│  │  │              ADMIN-FACING CONTROLLERS                        │  │   │
│  │  ├──────────────────────────────────────────────────────────────┤  │   │
│  │  │ • Admin\AuthController         - Admin authentication (2FA) │  │   │
│  │  │ • Admin\DashboardController    - Admin dashboard & KPIs      │  │   │
│  │  │ • Admin\ProductController      - Product CRUD operations     │  │   │
│  │  │ • Admin\OrderController        - Order processing            │  │   │
│  │  │ • Admin\FulfillmentController  - Order fulfillment workflow  │  │   │
│  │  │ • Admin\InventoryController     - Inventory tracking          │  │   │
│  │  │ • Admin\UserController         - Customer management         │  │   │
│  │  │ • Admin\AnalyticsController    - Sales analytics & reports   │  │   │
│  │  │ • Admin\ReturnsRepairsController - RMA management            │  │   │
│  │  │ • Admin\MessageController      - Contact message management  │  │   │
│  │  │ • Admin\ReviewController       - Review moderation           │  │   │
│  │  │ • Admin\CategoryController     - Category management        │  │   │
│  │  └──────────────────────────────────────────────────────────────┘  │   │
│  │                                                                     │   │
│  │  ┌──────────────────────────────────────────────────────────────┐  │   │
│  │  │                    SERVICE CLASSES                            │  │   │
│  │  ├──────────────────────────────────────────────────────────────┤  │   │
│  │  │ • MagicLinkService        - Magic link authentication        │  │   │
│  │  │ • DatabaseWishlistService - Database-based wishlist          │  │   │
│  │  │ • RedisWishlistService    - Redis-based wishlist (optional)  │  │   │
│  │  │ • SessionWishlistService  - Session-based wishlist           │  │   │
│  │  └──────────────────────────────────────────────────────────────┘  │   │
│  │                                                                     │   │
│  │  ┌──────────────────────────────────────────────────────────────┐  │   │
│  │  │                    API ROUTES (routes/api.php)                │  │   │
│  │  ├──────────────────────────────────────────────────────────────┤  │   │
│  │  │ • GET  /api/products          - Product catalog (AJAX)       │  │   │
│  │  │ • GET  /api/product/{id}      - Product details (AJAX)       │  │   │
│  │  │ • GET  /api/categories        - Category listing (AJAX)      │  │   │
│  │  │ • POST /api/search            - Product search (AJAX)        │  │   │
│  │  │ • POST /api/cart/*            - Cart operations (AJAX)       │  │   │
│  │  │ • POST /api/wishlist/*        - Wishlist operations (AJAX)  │  │   │
│  │  └──────────────────────────────────────────────────────────────┘  │   │
│  │                                                                     │   │
│  │  ┌──────────────────────────────────────────────────────────────┐  │   │
│  │  │                    MIDDLEWARE                                 │  │   │
│  │  ├──────────────────────────────────────────────────────────────┤  │   │
│  │  │ • AdminMiddleware            - Admin authentication guard     │  │   │
│  │  │ • RequireEmailVerification   - Email verification check      │  │   │
│  │  │ • ForceHttps                 - HTTPS enforcement             │  │   │
│  │  │ • StoreIntendedUrl           - Remember intended URL         │  │   │
│  │  │ • CSRF Protection             - Laravel CSRF token validation  │  │   │
│  │  └──────────────────────────────────────────────────────────────┘  │   │
│  │                                                                     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    ↕                                        │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    DATA LAYER                                       │   │
│  │              (Eloquent ORM + Database)                               │   │
│  ├─────────────────────────────────────────────────────────────────────┤   │
│  │                                                                     │   │
│  │  ┌──────────────────────────────────────────────────────────────┐  │   │
│  │  │              MySQL DATABASE (Single Database)                 │  │   │
│  │  ├──────────────────────────────────────────────────────────────┤  │   │
│  │  │                                                               │  │   │
│  │  │  Core Tables:                                                 │  │   │
│  │  │  • users                    • products                       │  │   │
│  │  │  • admins                   • categories                     │  │   │
│  │  │  • orders                   • order_items                   │  │   │
│  │  │  • order_fulfillment        • cart_items                    │  │   │
│  │  │  • returns_repairs         • product_reviews               │  │   │
│  │  │  • contact_messages         • product_popularity             │  │   │
│  │  │  • inventory_movements      • admin_permissions             │  │   │
│  │  │  • audit_logs               • notifications                 │  │   │
│  │  │  • payment_methods          • payment_gateways              │  │   │
│  │  │  • magic_link_tokens         • employees                    │  │   │
│  │  │                                                               │  │   │
│  │  └──────────────────────────────────────────────────────────────┘  │   │
│  │                                                                     │   │
│  │  ┌──────────────────────────────────────────────────────────────┐  │   │
│  │  │              CACHE & SESSION STORAGE (Optional)               │  │   │
│  │  ├──────────────────────────────────────────────────────────────┤  │   │
│  │  │ • Redis (optional) - Caching, wishlist storage               │  │   │
│  │  │ • File/Database Sessions - Laravel session storage           │  │   │
│  │  └──────────────────────────────────────────────────────────────┘  │   │
│  │                                                                     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘

                                    ↕ External Integrations ↕

┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐
│  XENDIT PAYMENT  │  │    AWS S3        │  │   GOOGLE OAUTH   │  │  EMAIL SERVICE  │
│     GATEWAY      │  │    STORAGE       │  │   (Social Login) │  │  (Laravel Mail)  │
├──────────────────┤  ├──────────────────┤  ├──────────────────┤  ├──────────────────┤
│ • Credit/Debit   │  │ • Product Images │  │ • OAuth 2.0       │  │ • Email          │
│   Cards          │  │ • File Storage   │  │ • User            │  │   Verification   │
│ • E-Wallets:     │  │ • Dynamic        │  │   Authentication │  │ • Magic Links    │
│   - GCash        │  │   Switching      │  │ • Profile Data    │  │ • Password Reset │
│   - PayMaya      │  │   (Local/S3)     │  │                   │  │ • Order          │
│   - GrabPay      │  │                   │  │                   │  │   Confirmations │
│ • Cash on        │  │                   │  │                   │  │ • Admin          │
│   Delivery       │  │                   │  │                   │  │   Notifications │
│ • Webhook        │  │                   │  │                   │  │                  │
│   Callbacks      │  │                   │  │                   │  │                  │
└──────────────────┘  └──────────────────┘  └──────────────────┘  └──────────────────┘
```

---

## Architecture Components Details

### 1. Presentation Layer

**Technology Stack:**
- **Laravel Blade Templates**: Server-side rendering for both customer and admin interfaces
- **Tailwind CSS 3**: Utility-first CSS framework for styling
- **Alpine.js**: Lightweight JavaScript framework for frontend interactivity
- **Vite**: Frontend build tool and asset bundler

**Access Points:**
- **Customer Portal**: `https://davidswood.shop` - Main e-commerce storefront
- **Admin Dashboard**: `https://admin.davidswood.shop` - Administrative interface (subdomain routing)

### 2. Application Layer

**Architecture Pattern:** Monolithic Laravel application with service-oriented design

**Key Components:**

#### Controllers (MVC Pattern)
- **Customer Controllers**: Handle all customer-facing operations
- **Admin Controllers**: Manage administrative functions with role-based access control
- **Payment Controllers**: Process payment transactions via Xendit integration

#### Services (Business Logic)
- Encapsulate reusable business logic
- Support multiple storage backends (Database, Redis, Session) for features like wishlist
- Handle authentication logic (Magic Links, OAuth)

#### API Routes
- RESTful endpoints primarily for AJAX calls from Blade templates
- Session-based authentication (not separate API gateway)
- CSRF protection with exceptions for webhooks

#### Middleware
- **Authentication**: Admin guards, email verification requirements
- **Security**: CSRF protection, HTTPS enforcement
- **Session Management**: Store intended URLs for post-login redirects

### 3. Data Layer

**Primary Database:**
- **MySQL/MariaDB**: Single relational database containing all application data
- **Eloquent ORM**: Laravel's ORM for database interactions
- **Migrations & Seeders**: Schema management and test data generation

**Optional Cache Layer:**
- **Redis**: Optional caching and wishlist storage (can fall back to database or session)
- **File/Database Sessions**: Laravel's session storage

### 4. External Integrations

#### Xendit Payment Gateway
- **Purpose**: Process online payments (credit/debit cards, e-wallets, COD)
- **Integration**: RESTful API calls from `Payments\XenditPaymentController`
- **Webhooks**: Receive payment status updates via webhook endpoints
- **Security**: Secure token-based authentication

#### AWS S3 Storage
- **Purpose**: Cloud storage for product images and files
- **Integration**: Dynamic switching via `StorageServiceProvider`
- **Behavior**: Automatically switches between local storage (development) and S3 (production)
- **Access**: Via Laravel's Filesystem abstraction

#### Google OAuth 2.0
- **Purpose**: Social login for customer authentication
- **Integration**: Laravel Socialite package
- **Flow**: OAuth 2.0 authorization code flow
- **User Data**: Retrieves profile information for account creation/login

#### Email Service (Laravel Mail)
- **Purpose**: Transactional emails (verification, notifications, confirmations)
- **Integration**: Laravel Mail classes (`EmailVerificationMail`, `MagicLinkMail`, etc.)
- **Transport**: SMTP configuration (can be configured for various mail services)
- **Templates**: Blade-based email templates

---

## Data Flow Examples

### Customer Order Flow

```
1. Customer (Browser)
   ↓
2. Customer Portal (Blade Template)
   ↓
3. ProductController → Product Model → MySQL Database
   ↓
4. CartController → Cart Model → MySQL Database
   ↓
5. CheckoutController → Order Model → MySQL Database
   ↓
6. XenditPaymentController → Xendit Payment Gateway API
   ↓
7. Webhook Callback → OrderController → Update Order Status
   ↓
8. Email Service → Send Order Confirmation
   ↓
9. Customer (Email Notification)
```

### Admin Product Management Flow

```
1. Admin (Browser - admin.davidswood.shop)
   ↓
2. Admin Dashboard (Blade Template)
   ↓
3. Admin\ProductController → Product Model → MySQL Database
   ↓
4. Image Upload → StorageServiceProvider → AWS S3 (or Local)
   ↓
5. Product Model → Update Database
   ↓
6. Audit Log → Log Activity
   ↓
7. Admin Dashboard (Updated Product List)
```

### Authentication Flow (Magic Link)

```
1. User Requests Login
   ↓
2. AuthController → MagicLinkService
   ↓
3. Generate Secure Token → Store in Database
   ↓
4. Email Service → Send Magic Link Email
   ↓
5. User Clicks Link → Verify Token
   ↓
6. AuthController → Create Session
   ↓
7. Redirect to Intended URL
```

---

## Security Architecture

### Authentication & Authorization

- **Customer Authentication:**
  - Session-based authentication (Laravel's default)
  - Email verification required for new accounts
  - Magic link passwordless login
  - Google OAuth social login
  - Password reset via secure email links

- **Admin Authentication:**
  - Separate admin guard with session-based authentication
  - Magic link 2FA (two-factor authentication)
  - Role-based access control (RBAC) via `AdminPermission` model
  - Password reset with email verification

### Security Measures

- **CSRF Protection**: Laravel's built-in CSRF token validation (with exceptions for API routes and webhooks)
- **HTTPS Enforcement**: Force HTTPS middleware for production
- **Password Hashing**: Bcrypt encryption for all passwords
- **SQL Injection Protection**: Eloquent ORM parameterized queries
- **XSS Protection**: Blade template escaping
- **Session Security**: Secure session configuration

---

## Deployment Architecture

### Production Environment

```
┌─────────────────────────────────────────────────────────┐
│                    AWS EC2 Instance                     │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  ┌──────────────────────────────────────────────────┐  │
│  │  Web Server (Nginx/Apache)                      │  │
│  │  - Serves static assets                         │  │
│  │  - SSL/TLS termination                          │  │
│  │  - Subdomain routing                            │  │
│  └──────────────────────────────────────────────────┘  │
│                        ↕                                │
│  ┌──────────────────────────────────────────────────┐  │
│  │  PHP-FPM                                        │  │
│  │  - Laravel Application                          │  │
│  │  - Process manager                              │  │
│  └──────────────────────────────────────────────────┘  │
│                        ↕                                │
│  ┌──────────────────────────────────────────────────┐  │
│  │  MySQL Database                                  │  │
│  │  - Application data                              │  │
│  └──────────────────────────────────────────────────┘  │
│                                                         │
└─────────────────────────────────────────────────────────┘
                        ↕
┌─────────────────────────────────────────────────────────┐
│  External Services:                                     │
│  • AWS S3 (File Storage)                                │
│  • Xendit Payment Gateway                               │
│  • Google OAuth                                         │
│  • Email SMTP Server                                    │
└─────────────────────────────────────────────────────────┘
```

### CI/CD Pipeline

```
┌─────────────────────────────────────────────────────────┐
│  GitHub Repository                                       │
│  - Code commits trigger workflow                         │
└─────────────────────────────────────────────────────────┘
                        ↕
┌─────────────────────────────────────────────────────────┐
│  GitHub Actions CI/CD                                    │
│  - Run PHPUnit tests                                     │
│  - Code quality checks                                   │
│  - Security scanning                                     │
│  - Build assets (Vite)                                   │
└─────────────────────────────────────────────────────────┘
                        ↕
┌─────────────────────────────────────────────────────────┐
│  AWS EC2 Deployment                                      │
│  - Automated deployment via SSH                          │
│  - Zero-downtime deployment                             │
│  - Health checks                                         │
│  - Automatic rollback on failure                        │
└─────────────────────────────────────────────────────────┘
```

---

## Key Architectural Decisions

### 1. Monolithic Architecture
- **Why**: Simplicity for a junior developer team, easier debugging, single deployment
- **Trade-off**: Less scalability than microservices, but sufficient for current needs

### 2. Service-Oriented Design Within Monolith
- **Why**: Maintainability, code reusability, separation of concerns
- **Benefit**: Can refactor into microservices later if needed

### 3. Blade Templates (Not React/SPA)
- **Why**: Server-side rendering is simpler, SEO-friendly, no separate frontend build
- **Trade-off**: Less dynamic interactivity than SPA, but adequate for e-commerce

### 4. Single Database
- **Why**: Simplicity, ACID transactions, easier data consistency
- **Trade-off**: Less scalable than distributed databases, but sufficient for current scale

### 5. Subdomain Routing for Admin
- **Why**: Logical separation, easy access control, different styling/UX
- **Implementation**: Laravel's subdomain routing with middleware

### 6. Dynamic Storage Switching
- **Why**: Local development ease + production cloud storage
- **Implementation**: Service provider that switches based on environment

---

## Technology Stack Summary

| Layer | Technology | Version |
|-------|-----------|---------|
| **Backend Framework** | Laravel | 12.0 |
| **Programming Language** | PHP | 8.2+ |
| **Database** | MySQL/MariaDB | Latest |
| **Frontend Framework** | Blade Templates | Laravel 12 |
| **CSS Framework** | Tailwind CSS | 3.0 |
| **JavaScript Framework** | Alpine.js | Latest |
| **Build Tool** | Vite | 7.0+ |
| **Payment Gateway** | Xendit | API v2 |
| **Cloud Storage** | AWS S3 | v3 SDK |
| **OAuth Provider** | Google OAuth | 2.0 |
| **Email** | Laravel Mail | SMTP |
| **Caching** | Redis (Optional) | Latest |
| **CI/CD** | GitHub Actions | - |
| **Deployment** | AWS EC2 | - |

---

## Notes

- **This is a monolithic Laravel application**, not a microservices architecture
- All modules run within the same Laravel process
- Controllers, Services, and Models are logical modules, not separate services
- API routes are part of the same application, not a separate API gateway
- Customer and Admin interfaces are different views, but same application
- Database is centralized, not distributed
- External integrations are via HTTP APIs and SDKs

---

*Last Updated: Based on current codebase analysis*
*Document Version: 1.0*

