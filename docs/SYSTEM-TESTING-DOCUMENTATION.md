# SYSTEM TESTING DOCUMENTATION

## David's Wood E-Commerce Platform

---

## Table of Contents

1. [Test Plan Overview](#test-plan-overview)
2. [Test Environment](#test-environment)
3. [Customer Portal Testing](#customer-portal-testing)
4. [Admin Dashboard Testing](#admin-dashboard-testing)
5. [Integration Testing](#integration-testing)
6. [Security Testing](#security-testing)
7. [Performance Testing](#performance-testing)
8. [User Acceptance Testing](#user-acceptance-testing)
9. [Test Execution Summary](#test-execution-summary)
10. [Defect Tracking](#defect-tracking)

---

## 1. Test Plan Overview

### 1.1 Purpose

This document outlines the system testing approach for the David's Wood E-Commerce Platform to ensure functionality, reliability, security, and performance meet the specified requirements.

### 1.2 Scope

The testing scope includes:

- **Customer-facing Features**: Product catalog, shopping cart, checkout, authentication, order management, reviews
- **Admin Dashboard Features**: Product management, order processing, inventory management, analytics
- **External Integrations**: Xendit payment gateway, AWS S3 storage, Google OAuth
- **Security Features**: Authentication, authorization, data protection
- **Performance and Scalability**: Load testing, response times, concurrent users

### 1.3 Testing Approach

- **Functional Testing**: Validating all features work as specified
- **Integration Testing**: Testing interactions between components
- **Security Testing**: Authentication, authorization, data protection
- **Performance Testing**: Load testing and response time validation
- **User Acceptance Testing**: End-user validation of system functionality

### 1.4 Test Deliverables

- Test cases and execution results
- Defect reports and tracking
- Test summary report
- Performance test results

---

## 2. Test Environment

### 2.1 Test Environment Configuration

| Component | Configuration |
|-----------|--------------|
| **Application URL** | `https://davidswood.shop` (Customer) |
| **Admin URL** | `https://admin.davidswood.shop` (Admin) |
| **Database** | MySQL (Test Database: `davids_wood_test`) |
| **PHP Version** | PHP 8.2+ |
| **Framework** | Laravel 12 |
| **Browser Testing** | Chrome, Firefox, Safari, Edge (Latest Versions) |
| **Mobile Testing** | iOS Safari, Android Chrome |
| **Test Data** | Realistic test data from seeders |

### 2.2 Test Data Setup

**Test Users:**

- Customer Test Account: `customer@test.com` / `password123`
- Admin Test Account: `admin@davidswood.com` / `password123`
- Manager Test Account: `manager@davidswood.com` / `password123`
- Staff Test Account: `staff@davidswood.com` / `password123`

**Test Products:**

- Multiple products across different categories
- Products with varying stock levels
- Products with and without reviews

**Test Orders:**

- Orders in various statuses (pending, processing, shipped, delivered)
- Orders with different payment methods
- Orders with fulfillment tracking

**Setup Commands:**

```bash
# Seed test database
php artisan migrate:fresh
php artisan db:seed

# Or seed specific data
php artisan db:seed --class=RealisticDataSeeder
```

---

## 3. Customer Portal Testing

### 3.1 Home & Discovery Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-CUST-001 | Homepage Load | 1. Navigate to `https://davidswood.shop`<br>2. Verify page loads successfully | Homepage displays with featured products, navigation, and footer | - [ ] |
| TC-CUST-002 | Featured Products Display | 1. Open homepage<br>2. Check featured products section | 8 featured products displayed with images, names, and prices | - [ ] |
| TC-CUST-003 | Product Search | 1. Click search icon<br>2. Enter product name<br>3. Click search | Search results show matching products | - [ ] |
| TC-CUST-004 | Category Navigation | 1. Click on category from navigation<br>2. Verify products filtered | Products filtered by selected category | - [ ] |
| TC-CUST-005 | Product Filtering | 1. Go to products page<br>2. Apply price filter<br>3. Apply category filter | Products filtered according to selected criteria | - [ ] |
| TC-CUST-006 | Product Sorting | 1. Go to products page<br>2. Select sort option (price, newest, popularity)<br>3. Verify products sorted | Products sorted according to selected option | - [ ] |
| TC-CUST-007 | Product Pagination | 1. Go to products page<br>2. Navigate through pages | Products paginated correctly (28 per page) | - [ ] |

### 3.2 Product Catalog Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-CUST-008 | Product Detail Page | 1. Click on product from catalog<br>2. Verify product detail page | Product details displayed (images, description, price, stock, reviews) | - [ ] |
| TC-CUST-009 | Product Image Gallery | 1. Open product detail page<br>2. Click on product images | Image gallery displays and allows image navigation | - [ ] |
| TC-CUST-010 | Product Reviews Display | 1. Open product detail page<br>2. Scroll to reviews section | Approved product reviews displayed with ratings and comments | - [ ] |
| TC-CUST-011 | Quick View Modal | 1. Click quick view on product card<br>2. Verify modal displays | Quick view modal shows product details without page reload | - [ ] |
| TC-CUST-012 | Add to Cart from Product Page | 1. Open product detail page<br>2. Select quantity<br>3. Click "Add to Cart" | Product added to cart, cart count updates, success message displayed | - [ ] |
| TC-CUST-013 | Add to Wishlist from Product Page | 1. Open product detail page<br>2. Click "Add to Wishlist" | Product added to wishlist, wishlist icon updates | - [ ] |

### 3.3 Shopping Cart Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-CUST-014 | Add Product to Cart | 1. Browse products<br>2. Click "Add to Cart" on product | Product added to cart, cart icon shows item count | - [ ] |
| TC-CUST-015 | View Cart | 1. Click cart icon<br>2. Verify cart offcanvas opens | Cart displays all items with quantities and prices | - [ ] |
| TC-CUST-016 | Update Cart Item Quantity | 1. Open cart<br>2. Change quantity of item<br>3. Verify total updates | Cart total recalculated correctly, quantity updated | - [ ] |
| TC-CUST-017 | Remove Item from Cart | 1. Open cart<br>2. Click remove on item<br>3. Confirm removal | Item removed from cart, cart total updated | - [ ] |
| TC-CUST-018 | Clear Cart | 1. Add multiple items to cart<br>2. Click clear cart<br>3. Confirm | All items removed from cart, cart empty | - [ ] |
| TC-CUST-019 | Guest Cart Persistence | 1. Add items to cart as guest<br>2. Close browser<br>3. Reopen and check cart | Cart items persist in session | - [ ] |
| TC-CUST-020 | Cart Migration After Login | 1. Add items to cart as guest<br>2. Login to account<br>3. Verify cart | Guest cart items migrated to user account | - [ ] |
| TC-CUST-021 | Cart Selection for Checkout | 1. Add multiple items to cart<br>2. Select specific items<br>3. Proceed to checkout | Only selected items proceed to checkout | - [ ] |

### 3.4 Wishlist Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-CUST-022 | Add Product to Wishlist | 1. Browse products<br>2. Click wishlist icon | Product added to wishlist, icon updates | - [ ] |
| TC-CUST-023 | View Wishlist | 1. Click wishlist icon<br>2. Verify wishlist offcanvas opens | Wishlist displays all saved products | - [ ] |
| TC-CUST-024 | Remove from Wishlist | 1. Open wishlist<br>2. Click remove on item | Item removed from wishlist | - [ ] |
| TC-CUST-025 | Add to Cart from Wishlist | 1. Open wishlist<br>2. Click "Add to Cart" on item | Item added to cart from wishlist | - [ ] |
| TC-CUST-026 | Wishlist Migration After Login | 1. Add items to wishlist as guest<br>2. Login to account<br>3. Verify wishlist | Guest wishlist items migrated to user account | - [ ] |

### 3.5 User Authentication Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-CUST-027 | User Registration | 1. Click "Sign Up"<br>2. Fill registration form<br>3. Submit | Account created, email verification sent | - [ ] |
| TC-CUST-028 | Email Verification | 1. Register new account<br>2. Check email<br>3. Click verification link | Email verified, user logged in automatically | - [ ] |
| TC-CUST-029 | Resend Verification Email | 1. Login with unverified account<br>2. Click "Resend Verification" | Verification email resent successfully | - [ ] |
| TC-CUST-030 | User Login | 1. Enter email/username and password<br>2. Click "Login" | User logged in successfully, redirected to intended page | - [ ] |
| TC-CUST-031 | Login with Invalid Credentials | 1. Enter incorrect credentials<br>2. Click "Login" | Error message displayed, user not logged in | - [ ] |
| TC-CUST-032 | Google OAuth Login | 1. Click "Sign in with Google"<br>2. Authenticate with Google<br>3. Verify redirect | User logged in with Google account, account created/updated | - [ ] |
| TC-CUST-033 | Password Reset Request | 1. Click "Forgot Password"<br>2. Enter email<br>3. Submit | Password reset email sent with magic link | - [ ] |
| TC-CUST-034 | Password Reset via Magic Link | 1. Request password reset<br>2. Click magic link in email<br>3. Enter new password<br>4. Submit | Password reset successfully, user can login | - [ ] |
| TC-CUST-035 | Logout | 1. Login to account<br>2. Click logout | User logged out, redirected to homepage | - [ ] |

### 3.6 User Profile Management Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-CUST-036 | View Profile | 1. Login to account<br>2. Navigate to "My Account" | Profile information displayed correctly | - [ ] |
| TC-CUST-037 | Update Profile Information | 1. Go to account profile<br>2. Update personal information<br>3. Save | Profile updated successfully, changes saved | - [ ] |
| TC-CUST-038 | Change Password | 1. Go to account settings<br>2. Enter current and new password<br>3. Submit | Password changed successfully | - [ ] |
| TC-CUST-039 | Add Payment Method | 1. Go to account → Payment Methods<br>2. Add new payment method<br>3. Save | Payment method added successfully | - [ ] |
| TC-CUST-040 | Set Default Payment Method | 1. Go to payment methods<br>2. Set default payment method | Default payment method set successfully | - [ ] |
| TC-CUST-041 | Add Shipping Address | 1. Go to account → Addresses<br>2. Add new address<br>3. Save | Shipping address added successfully | - [ ] |
| TC-CUST-042 | Update Newsletter Preferences | 1. Go to account settings<br>2. Update newsletter preferences<br>3. Save | Newsletter preferences updated | - [ ] |

### 3.7 Checkout and Payment Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-CUST-043 | Initiate Checkout | 1. Add items to cart<br>2. Click "Checkout" | Checkout page loads with cart items | - [ ] |
| TC-CUST-044 | Shipping Information Entry | 1. Go to checkout<br>2. Enter shipping address<br>3. Continue | Shipping information validated and saved | - [ ] |
| TC-CUST-045 | Payment Method Selection | 1. Continue from shipping<br>2. Select payment method<br>3. Continue | Payment method selected, proceed to review | - [ ] |
| TC-CUST-046 | Order Review | 1. Continue from payment<br>2. Review order details | Order summary displayed with all details | - [ ] |
| TC-CUST-047 | Xendit Payment Processing | 1. Select Xendit payment<br>2. Complete checkout<br>3. Redirect to Xendit | Redirected to Xendit payment page | - [ ] |
| TC-CUST-048 | Cash on Delivery Order | 1. Select COD payment<br>2. Complete checkout | Order created with COD payment status | - [ ] |
| TC-CUST-049 | Payment Success | 1. Complete payment on Xendit<br>2. Return to success page | Order confirmation displayed, order created | - [ ] |
| TC-CUST-050 | Payment Failure | 1. Fail payment on Xendit<br>2. Return to failure page | Payment failure message displayed, order status updated | - [ ] |
| TC-CUST-051 | Order Confirmation | 1. Complete successful order<br>2. Verify confirmation page | Order confirmation displayed with order number | - [ ] |

### 3.8 Order Management Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-CUST-052 | View Order History | 1. Login to account<br>2. Navigate to "My Orders" | All orders displayed with status and dates | - [ ] |
| TC-CUST-053 | View Order Details | 1. Go to order history<br>2. Click on order | Order details displayed with items and tracking | - [ ] |
| TC-CUST-054 | Order Tracking | 1. View order details<br>2. Check tracking information | Order status and tracking displayed correctly | - [ ] |
| TC-CUST-055 | Download Order Receipt | 1. View delivered order<br>2. Click "Download Receipt" | Receipt PDF downloaded successfully | - [ ] |
| TC-CUST-056 | Print Order Receipt | 1. View delivered order<br>2. Click "Print Receipt" | Receipt prints correctly formatted | - [ ] |
| TC-CUST-057 | Order Status Updates | 1. Place order<br>2. Monitor order status changes | Order status updates reflected in order history | - [ ] |

### 3.9 Product Reviews Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-CUST-058 | Submit Product Review | 1. View delivered order<br>2. Click "Write Review"<br>3. Enter rating and review<br>4. Submit | Review submitted successfully, pending approval message | - [ ] |
| TC-CUST-059 | Review Validation | 1. Try to submit review without rating<br>2. Submit | Validation error displayed, review not submitted | - [ ] |
| TC-CUST-060 | Verified Purchase Review | 1. Try to review product not purchased<br>2. Verify restriction | Review option only available for purchased products | - [ ] |
| TC-CUST-061 | View Approved Reviews | 1. Go to product detail page<br>2. Check reviews section | Approved reviews displayed with ratings | - [ ] |
| TC-CUST-062 | Duplicate Review Prevention | 1. Submit review for product<br>2. Try to submit another review for same product | Duplicate review prevented, message displayed | - [ ] |

### 3.10 Contact Form Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-CUST-063 | Submit Contact Form | 1. Scroll to footer<br>2. Fill contact form<br>3. Submit | Contact form submitted, confirmation message displayed | - [ ] |
| TC-CUST-064 | Contact Form Validation | 1. Try to submit empty form<br>2. Submit | Validation errors displayed, form not submitted | - [ ] |
| TC-CUST-065 | Auto-fill for Logged-in Users | 1. Login to account<br>2. Open contact form | Name and email auto-filled from account | - [ ] |

### 3.11 CMS Pages Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-CUST-066 | View About Page | 1. Navigate to About page | About page content displayed correctly | - [ ] |
| TC-CUST-067 | View Privacy Policy | 1. Navigate to Privacy Policy page | Privacy policy content displayed | - [ ] |
| TC-CUST-068 | View Terms of Service | 1. Navigate to Terms of Service page | Terms of service content displayed | - [ ] |

---

## 4. Admin Dashboard Testing

### 4.1 Admin Authentication Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-001 | Admin Login | 1. Navigate to `admin.davidswood.shop`<br>2. Enter admin credentials<br>3. Login | Admin logged in, 2FA email sent | - [ ] |
| TC-ADMIN-002 | Admin 2FA Verification | 1. Complete admin login<br>2. Check email for magic link<br>3. Click magic link | 2FA verified, admin logged in successfully | - [ ] |
| TC-ADMIN-003 | Admin Login with Invalid Credentials | 1. Enter incorrect credentials<br>2. Submit | Error message displayed, login failed | - [ ] |
| TC-ADMIN-004 | Admin Password Reset | 1. Click "Forgot Password"<br>2. Enter admin email<br>3. Submit | Password reset email sent | - [ ] |
| TC-ADMIN-005 | Admin Logout | 1. Login as admin<br>2. Click logout | Admin logged out, redirected to login page | - [ ] |

### 4.2 Dashboard Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-006 | Dashboard Load | 1. Login as admin<br>2. Navigate to dashboard | Dashboard displays with KPIs and statistics | - [ ] |
| TC-ADMIN-007 | Dashboard KPIs | 1. View dashboard<br>2. Verify KPI cards | Revenue, orders, customers, products KPIs displayed correctly | - [ ] |
| TC-ADMIN-008 | Dashboard Charts | 1. View dashboard<br>2. Check charts | Revenue charts, product charts displayed correctly | - [ ] |
| TC-ADMIN-009 | Recent Activity Feed | 1. View dashboard<br>2. Check activity feed | Recent orders, messages, inventory updates displayed | - [ ] |
| TC-ADMIN-010 | Low Stock Alerts | 1. View dashboard<br>2. Check low stock alerts | Products with low stock displayed with alerts | - [ ] |

### 4.3 Product Management Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-011 | View Products List | 1. Navigate to Products<br>2. View product list | All products displayed with details | - [ ] |
| TC-ADMIN-012 | Create New Product | 1. Click "Add Product"<br>2. Fill product form<br>3. Upload images<br>4. Save | Product created successfully | - [ ] |
| TC-ADMIN-013 | Edit Product | 1. Click on product<br>2. Click edit<br>3. Update information<br>4. Save | Product updated successfully | - [ ] |
| TC-ADMIN-014 | Delete Product | 1. Select product<br>2. Click delete<br>3. Confirm | Product deleted successfully | - [ ] |
| TC-ADMIN-015 | Product Image Upload | 1. Create/edit product<br>2. Upload multiple images<br>3. Save | Images uploaded and displayed correctly | - [ ] |
| TC-ADMIN-016 | Product Status Toggle | 1. View product list<br>2. Toggle product status | Product status updated (active/inactive) | - [ ] |
| TC-ADMIN-017 | Bulk Product Operations | 1. Select multiple products<br>2. Perform bulk action (activate/deactivate)<br>3. Confirm | Bulk operation completed successfully | - [ ] |
| TC-ADMIN-018 | Product Export | 1. Navigate to products<br>2. Click export<br>3. Download CSV | Product data exported to CSV file | - [ ] |
| TC-ADMIN-019 | Product SKU Generation | 1. Create new product<br>2. Verify SKU auto-generation | SKU generated in 5-digit format | - [ ] |

### 4.4 Category Management Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-020 | View Categories | 1. Navigate to Categories<br>2. View category list | Categories displayed with hierarchy | - [ ] |
| TC-ADMIN-021 | Create Category | 1. Click "Add Category"<br>2. Fill category form<br>3. Save | Category created successfully | - [ ] |
| TC-ADMIN-022 | Create Subcategory | 1. Select parent category<br>2. Create subcategory<br>3. Save | Subcategory created under parent category | - [ ] |
| TC-ADMIN-023 | Edit Category | 1. Select category<br>2. Click edit<br>3. Update information<br>4. Save | Category updated successfully | - [ ] |
| TC-ADMIN-024 | Delete Category | 1. Select category<br>2. Click delete<br>3. Confirm | Category deleted (with products handling) | - [ ] |

### 4.5 Order Management Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-025 | View Orders List | 1. Navigate to Orders<br>2. View order list | All orders displayed with status and details | - [ ] |
| TC-ADMIN-026 | View Order Details | 1. Click on order<br>2. View order details | Order details displayed with customer and items | - [ ] |
| TC-ADMIN-027 | Update Order Status | 1. View order<br>2. Update status<br>3. Save | Order status updated, notification sent | - [ ] |
| TC-ADMIN-028 | Approve Order | 1. View pending approval order<br>2. Click approve<br>3. Confirm | Order approved, status updated | - [ ] |
| TC-ADMIN-029 | Reject Order | 1. View pending approval order<br>2. Click reject<br>3. Enter reason<br>4. Confirm | Order rejected, customer notified | - [ ] |
| TC-ADMIN-030 | Process Refund | 1. View order<br>2. Click process refund<br>3. Enter refund details<br>4. Submit | Refund processed successfully | - [ ] |
| TC-ADMIN-031 | Generate Invoice | 1. View order<br>2. Click download invoice | Invoice PDF generated and downloaded | - [ ] |
| TC-ADMIN-032 | Generate Packing Slip | 1. View order<br>2. Click download packing slip | Packing slip PDF generated | - [ ] |
| TC-ADMIN-033 | Bulk Order Operations | 1. Select multiple orders<br>2. Perform bulk action<br>3. Confirm | Bulk operation completed | - [ ] |
| TC-ADMIN-034 | Order Export | 1. Navigate to orders<br>2. Click export<br>3. Download | Orders exported to CSV | - [ ] |

### 4.6 Order Fulfillment Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-035 | View Fulfillment List | 1. Navigate to Fulfillment<br>2. View fulfillment list | Orders with fulfillment status displayed | - [ ] |
| TC-ADMIN-036 | Mark Items Packed | 1. View order fulfillment<br>2. Mark items as packed<br>3. Add packing notes<br>4. Save | Packing status updated, notes saved | - [ ] |
| TC-ADMIN-037 | Mark Order Shipped | 1. View packed order<br>2. Enter tracking number<br>3. Select carrier<br>4. Mark shipped | Order marked as shipped, tracking updated | - [ ] |
| TC-ADMIN-038 | Print Shipping Label | 1. View order fulfillment<br>2. Click print label | Shipping label generated and printed | - [ ] |
| TC-ADMIN-039 | Bulk Shipping | 1. Select multiple orders<br>2. Click bulk ship<br>3. Enter tracking details<br>4. Confirm | Multiple orders marked as shipped | - [ ] |

### 4.7 Returns & Repairs Management Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-040 | View Returns/Repairs List | 1. Navigate to Returns & Repairs<br>2. View list | All RMA requests displayed | - [ ] |
| TC-ADMIN-041 | Create RMA Request | 1. Click create RMA<br>2. Select order<br>3. Fill RMA form<br>4. Submit | RMA created with unique RMA number | - [ ] |
| TC-ADMIN-042 | Approve RMA Request | 1. View RMA request<br>2. Click approve<br>3. Confirm | RMA approved, customer notified | - [ ] |
| TC-ADMIN-043 | Reject RMA Request | 1. View RMA request<br>2. Click reject<br>3. Enter reason<br>4. Confirm | RMA rejected, customer notified | - [ ] |
| TC-ADMIN-044 | Mark RMA as Received | 1. View approved RMA<br>2. Mark as received<br>3. Upload photos<br>4. Save | RMA status updated to received | - [ ] |
| TC-ADMIN-045 | Process RMA Refund | 1. View received RMA<br>2. Process refund<br>3. Enter refund details<br>4. Submit | Refund processed successfully | - [ ] |
| TC-ADMIN-046 | Complete RMA | 1. View processed RMA<br>2. Mark as completed<br>3. Confirm | RMA status updated to completed | - [ ] |

### 4.8 Inventory Management Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-047 | View Inventory List | 1. Navigate to Inventory<br>2. View inventory list | All products with stock levels displayed | - [ ] |
| TC-ADMIN-048 | View Low Stock Alerts | 1. Navigate to Low Stock<br>2. View alerts | Products below threshold displayed | - [ ] |
| TC-ADMIN-049 | Adjust Inventory | 1. Select product<br>2. Click adjust inventory<br>3. Enter adjustment<br>4. Save | Inventory adjusted, movement recorded | - [ ] |
| TC-ADMIN-050 | Add Stock | 1. Select product<br>2. Add stock quantity<br>3. Save | Stock quantity increased, movement recorded | - [ ] |
| TC-ADMIN-051 | Remove Stock | 1. Select product<br>2. Remove stock quantity<br>3. Save | Stock quantity decreased, movement recorded | - [ ] |
| TC-ADMIN-052 | View Inventory Movements | 1. Navigate to Inventory Movements<br>2. View history | All inventory movements displayed with details | - [ ] |
| TC-ADMIN-053 | Bulk Inventory Update | 1. Select multiple products<br>2. Perform bulk restock<br>3. Confirm | Bulk inventory update completed | - [ ] |
| TC-ADMIN-054 | Export Inventory | 1. Navigate to inventory<br>2. Click export<br>3. Download | Inventory data exported to CSV | - [ ] |

### 4.9 Customer Management Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-055 | View Customers List | 1. Navigate to Customers<br>2. View customer list | All customers displayed with details | - [ ] |
| TC-ADMIN-056 | View Customer Details | 1. Click on customer<br>2. View customer profile | Customer details, orders, and history displayed | - [ ] |
| TC-ADMIN-057 | Suspend Customer | 1. View customer<br>2. Click suspend<br>3. Confirm | Customer account suspended | - [ ] |
| TC-ADMIN-058 | Activate Customer | 1. View suspended customer<br>2. Click activate<br>3. Confirm | Customer account activated | - [ ] |
| TC-ADMIN-059 | Verify Customer Email | 1. View customer<br>2. Click verify email<br>3. Confirm | Customer email verified | - [ ] |
| TC-ADMIN-060 | Reset Customer Password | 1. View customer<br>2. Click reset password<br>3. Confirm | Password reset email sent to customer | - [ ] |
| TC-ADMIN-061 | Export Customers | 1. Navigate to customers<br>2. Click export<br>3. Download | Customer data exported to CSV | - [ ] |

### 4.10 Review Moderation Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-062 | View Reviews List | 1. Navigate to Reviews<br>2. View review list | All reviews displayed with status | - [ ] |
| TC-ADMIN-063 | Approve Review | 1. View pending review<br>2. Click approve<br>3. Confirm | Review approved, displayed on product page | - [ ] |
| TC-ADMIN-064 | Reject Review | 1. View pending review<br>2. Click reject<br>3. Confirm | Review rejected, hidden from product page | - [ ] |
| TC-ADMIN-065 | Add Review Response | 1. View approved review<br>2. Add admin response<br>3. Save | Admin response added to review | - [ ] |
| TC-ADMIN-066 | Bulk Review Operations | 1. Select multiple reviews<br>2. Perform bulk approve/reject<br>3. Confirm | Bulk operation completed | - [ ] |

### 4.11 Message Management Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-067 | View Messages List | 1. Navigate to Messages<br>2. View message list | All contact messages displayed | - [ ] |
| TC-ADMIN-068 | View Message Details | 1. Click on message<br>2. View details | Message details displayed with customer info | - [ ] |
| TC-ADMIN-069 | Update Message Status | 1. View message<br>2. Update status<br>3. Save | Message status updated | - [ ] |
| TC-ADMIN-070 | Assign Message to Admin | 1. View message<br>2. Assign to admin<br>3. Save | Message assigned to admin | - [ ] |
| TC-ADMIN-071 | Add Admin Notes | 1. View message<br>2. Add admin notes<br>3. Save | Notes added to message | - [ ] |
| TC-ADMIN-072 | Reply to Message | 1. View message<br>2. Click reply via email<br>3. Send email | Reply email sent to customer | - [ ] |

### 4.12 Analytics & Reports Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-073 | View Analytics Dashboard | 1. Navigate to Analytics<br>2. View dashboard | Analytics charts and metrics displayed | - [ ] |
| TC-ADMIN-074 | Revenue Over Time Chart | 1. View analytics<br>2. Check revenue chart | Revenue trend chart displayed correctly | - [ ] |
| TC-ADMIN-075 | Top Products Chart | 1. View analytics<br>2. Check top products | Top products by sales chart displayed | - [ ] |
| TC-ADMIN-076 | Traffic Sources Chart | 1. View analytics<br>2. Check traffic sources | Traffic sources donut chart displayed | - [ ] |
| TC-ADMIN-077 | Date Range Filtering | 1. View analytics<br>2. Select date range<br>3. Apply filter | Analytics data filtered by date range | - [ ] |
| TC-ADMIN-078 | Export Analytics Data | 1. View analytics<br>2. Click export<br>3. Download | Analytics data exported | - [ ] |

### 4.13 Settings Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-079 | View Settings | 1. Navigate to Settings<br>2. View settings page | All settings displayed | - [ ] |
| TC-ADMIN-080 | Update General Settings | 1. View settings<br>2. Update general settings<br>3. Save | Settings updated successfully | - [ ] |
| TC-ADMIN-081 | Update Email Settings | 1. View settings<br>2. Update email configuration<br>3. Save | Email settings updated | - [ ] |
| TC-ADMIN-082 | Test Email Configuration | 1. View email settings<br>2. Click test email<br>3. Verify | Test email sent successfully | - [ ] |
| TC-ADMIN-083 | Clear Cache | 1. View settings<br>2. Click clear cache<br>3. Confirm | Cache cleared successfully | - [ ] |

### 4.14 Payment Gateway Configuration Module

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-ADMIN-084 | View Payment Gateway Settings | 1. Navigate to Integrations → Xendit<br>2. View configuration | Xendit settings displayed | - [ ] |
| TC-ADMIN-085 | Configure Xendit | 1. View Xendit settings<br>2. Enter API keys<br>3. Select environment<br>4. Save | Xendit configuration saved | - [ ] |
| TC-ADMIN-086 | Test Payment Gateway Connection | 1. Configure Xendit<br>2. Click test connection<br>3. Verify | Connection test successful | - [ ] |
| TC-ADMIN-087 | Toggle Payment Gateway Status | 1. View payment gateway<br>2. Toggle enabled/disabled<br>3. Save | Payment gateway status updated | - [ ] |

---

## 5. Integration Testing

### 5.1 Payment Gateway Integration

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-INT-001 | Xendit Invoice Creation | 1. Complete checkout<br>2. Select Xendit payment<br>3. Verify redirect | Redirected to Xendit payment page | - [ ] |
| TC-INT-002 | Xendit Payment Success | 1. Complete payment on Xendit<br>2. Verify return to success URL | Order status updated to paid, confirmation displayed | - [ ] |
| TC-INT-003 | Xendit Payment Failure | 1. Fail payment on Xendit<br>2. Verify return to failure URL | Order status remains pending, failure message displayed | - [ ] |
| TC-INT-004 | Xendit Webhook Processing | 1. Complete payment on Xendit<br>2. Verify webhook received<br>3. Check order status | Webhook processed, order status synchronized | - [ ] |
| TC-INT-005 | Webhook Security Verification | 1. Send webhook with invalid token<br>2. Verify rejection | Webhook rejected, order not updated | - [ ] |

### 5.2 AWS S3 Storage Integration

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-INT-006 | Product Image Upload to S3 | 1. Create product in production<br>2. Upload image<br>3. Verify | Image uploaded to S3, URL generated correctly | - [ ] |
| TC-INT-007 | Image Retrieval from S3 | 1. View product with S3 image<br>2. Verify image loads | Image loads from S3 URL | - [ ] |
| TC-INT-008 | Dynamic Storage Switching | 1. Test in local environment<br>2. Test in production<br>3. Verify storage | Local uses local storage, production uses S3 | - [ ] |

### 5.3 Google OAuth Integration

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-INT-009 | Google OAuth Redirect | 1. Click "Sign in with Google"<br>2. Verify redirect | Redirected to Google authentication page | - [ ] |
| TC-INT-010 | Google OAuth Callback | 1. Authenticate with Google<br>2. Verify callback | User account created/updated, logged in | - [ ] |
| TC-INT-011 | Google OAuth Error Handling | 1. Deny Google OAuth permissions<br>2. Verify error handling | Error message displayed, user not logged in | - [ ] |

### 5.4 Email Service Integration

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-INT-012 | Email Verification Send | 1. Register new user<br>2. Verify email sent | Email verification email sent via SMTP | - [ ] |
| TC-INT-013 | Password Reset Email | 1. Request password reset<br>2. Verify email sent | Password reset email sent | - [ ] |
| TC-INT-014 | Order Confirmation Email | 1. Complete order<br>2. Verify email sent | Order confirmation email sent to customer | - [ ] |
| TC-INT-015 | Admin 2FA Email | 1. Admin login<br>2. Verify 2FA email sent | 2FA magic link email sent to admin | - [ ] |

---

## 6. Security Testing

### 6.1 Authentication Security

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-SEC-001 | SQL Injection Prevention | 1. Attempt SQL injection in search<br>2. Verify protection | SQL injection prevented, input sanitized | - [ ] |
| TC-SEC-002 | XSS Prevention | 1. Enter script tags in form fields<br>2. Submit form<br>3. Verify | XSS prevented, script tags sanitized | - [ ] |
| TC-SEC-003 | CSRF Protection | 1. Attempt POST without CSRF token<br>2. Verify rejection | Request rejected due to missing CSRF token | - [ ] |
| TC-SEC-004 | Password Encryption | 1. Check database for user password<br>2. Verify encryption | Password stored as bcrypt hash | - [ ] |
| TC-SEC-005 | Session Security | 1. Login to account<br>2. Verify session management | Session secured, invalidated on logout | - [ ] |
| TC-SEC-006 | Magic Link Token Expiration | 1. Use expired magic link<br>2. Verify rejection | Expired token rejected, new token required | - [ ] |
| TC-SEC-007 | Magic Link Single Use | 1. Use magic link<br>2. Try to use same link again | Token marked as used, second use rejected | - [ ] |
| TC-SEC-008 | HTTPS Enforcement | 1. Attempt HTTP access<br>2. Verify redirect | HTTP requests redirected to HTTPS | - [ ] |

### 6.2 Authorization Security

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-SEC-009 | Unauthorized Admin Access | 1. Attempt to access admin without login<br>2. Verify redirect | Redirected to admin login page | - [ ] |
| TC-SEC-010 | Role-Based Access Control | 1. Login as staff<br>2. Attempt restricted action<br>3. Verify | Access denied, permission error displayed | - [ ] |
| TC-SEC-011 | Customer Order Access | 1. Login as customer<br>2. Attempt to access another customer's order<br>3. Verify | Access denied, can only view own orders | - [ ] |
| TC-SEC-012 | Admin Subdomain Protection | 1. Attempt to access admin routes from main domain<br>2. Verify | Admin routes only accessible from admin subdomain | - [ ] |

### 6.3 Data Protection

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-SEC-013 | Sensitive Data Encryption | 1. Check database for sensitive data<br>2. Verify encryption | Sensitive data encrypted in transit and at rest | - [ ] |
| TC-SEC-014 | Payment Data Security | 1. Complete payment<br>2. Verify payment data | Payment data not stored, only transaction IDs | - [ ] |
| TC-SEC-015 | File Upload Security | 1. Attempt to upload malicious file<br>2. Verify rejection | Malicious files rejected, only allowed types accepted | - [ ] |

---

## 7. Performance Testing

### 7.1 Page Load Performance

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-PERF-001 | Homepage Load Time | 1. Measure homepage load time<br>2. Verify | Homepage loads in < 3 seconds | - [ ] |
| TC-PERF-002 | Product Page Load Time | 1. Measure product page load time<br>2. Verify | Product page loads in < 2 seconds | - [ ] |
| TC-PERF-003 | Admin Dashboard Load Time | 1. Measure admin dashboard load time<br>2. Verify | Dashboard loads in < 3 seconds | - [ ] |
| TC-PERF-004 | Database Query Performance | 1. Monitor database queries<br>2. Verify optimization | Queries optimized, no N+1 problems | - [ ] |

### 7.2 Concurrent User Performance

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-PERF-005 | Concurrent User Load | 1. Simulate 50 concurrent users<br>2. Monitor performance | System handles 50+ concurrent users | - [ ] |
| TC-PERF-006 | Database Connection Pooling | 1. Monitor database connections<br>2. Verify pooling | Connections pooled efficiently | - [ ] |

### 7.3 Image Loading Performance

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-PERF-007 | Image Lazy Loading | 1. Load product page<br>2. Verify lazy loading | Images load lazily, page loads faster | - [ ] |
| TC-PERF-008 | S3 Image Retrieval | 1. Load product with S3 images<br>2. Measure load time | S3 images load efficiently | - [ ] |

---

## 8. User Acceptance Testing

### 8.1 Customer User Acceptance

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-UAT-001 | Complete Purchase Flow | 1. Browse products<br>2. Add to cart<br>3. Checkout<br>4. Complete payment | Complete purchase flow works smoothly | - [ ] |
| TC-UAT-002 | User Registration Experience | 1. Register new account<br>2. Verify email<br>3. Complete profile | Registration process intuitive and smooth | - [ ] |
| TC-UAT-003 | Mobile Responsiveness | 1. Access site on mobile device<br>2. Test all features | All features work correctly on mobile | - [ ] |

### 8.2 Admin User Acceptance

| Test Case ID | Test Case Description | Test Steps | Expected Result | Status |
|--------------|----------------------|------------|-----------------|--------|
| TC-UAT-004 | Order Processing Workflow | 1. Receive order<br>2. Process order<br>3. Update fulfillment<br>4. Complete | Order processing workflow efficient | - [ ] |
| TC-UAT-005 | Product Management Workflow | 1. Add new product<br>2. Update inventory<br>3. Manage images | Product management workflow smooth | - [ ] |
| TC-UAT-006 | Analytics Dashboard Usability | 1. View analytics<br>2. Filter data<br>3. Export reports | Analytics dashboard user-friendly and informative | - [ ] |

---

## 9. Test Execution Summary

### 9.1 Test Execution Template

| Test Suite | Total Test Cases | Passed | Failed | Blocked | Not Executed | Pass Rate |
|------------|------------------|--------|--------|---------|--------------|-----------|
| Customer Portal | 68 | - [ ] | - [ ] | - [ ] | - [ ] | - [ ]% |
| Admin Dashboard | 87 | - [ ] | - [ ] | - [ ] | - [ ] | - [ ]% |
| Integration Testing | 15 | - [ ] | - [ ] | - [ ] | - [ ] | - [ ]% |
| Security Testing | 15 | - [ ] | - [ ] | - [ ] | - [ ] | - [ ]% |
| Performance Testing | 8 | - [ ] | - [ ] | - [ ] | - [ ] | - [ ]% |
| User Acceptance Testing | 6 | - [ ] | - [ ] | - [ ] | - [ ] | - [ ]% |
| **TOTAL** | **199** | - [ ] | - [ ] | - [ ] | - [ ] | - [ ]% |

### 9.2 Test Execution Schedule

| Phase | Duration | Test Cases | Status |
|-------|----------|------------|--------|
| Phase 1: Customer Portal Testing | Week 1 | 68 cases | - [ ] |
| Phase 2: Admin Dashboard Testing | Week 2 | 87 cases | - [ ] |
| Phase 3: Integration Testing | Week 3 | 15 cases | - [ ] |
| Phase 4: Security Testing | Week 3 | 15 cases | - [ ] |
| Phase 5: Performance Testing | Week 4 | 8 cases | - [ ] |
| Phase 6: User Acceptance Testing | Week 4 | 6 cases | - [ ] |

### 9.3 Test Execution Instructions

**For Testers:**

1. **Test Environment Setup:**
   ```bash
   # Clone repository
   git clone [repository-url]
   cd davids-wood-furniture
   
   # Install dependencies
   composer install
   npm install
   
   # Setup test database
   php artisan migrate --database=mysql
   php artisan db:seed
   ```

2. **Running Automated Tests:**
   ```bash
   # Run all tests
   php artisan test
   
   # Run specific test suite
   php artisan test --testsuite=Feature
   php artisan test --testsuite=Unit
   
   # Run with coverage
   php artisan test --coverage
   ```

3. **Manual Testing:**
   - Use test cases provided in this document
   - Document results in the Status column (✅ Pass, ❌ Fail, ⚠️ Blocked)
   - Report defects using the defect tracking template

4. **Test Data Management:**
   ```bash
   # Reset test data
   php artisan migrate:fresh --seed
   
   # Seed realistic data
   php artisan db:seed --class=RealisticDataSeeder
   ```

---

## 10. Defect Tracking

### 10.1 Defect Report Template

| Defect ID | Test Case ID | Severity | Priority | Description | Steps to Reproduce | Expected Result | Actual Result | Status | Assigned To | Fixed Date |
|-----------|--------------|----------|----------|-------------|-------------------|-----------------|--------------|--------|-------------|------------|
| DEF-001 | - [ ] | - [ ] | - [ ] | - [ ] | - [ ] | - [ ] | - [ ] | - [ ] | - [ ] | - [ ] |

**Severity Levels:**

- **Critical**: System crash, data loss, security breach
- **High**: Major functionality broken, cannot proceed
- **Medium**: Feature partially working, workaround available
- **Low**: Minor issue, cosmetic problem

**Priority Levels:**

- **P1**: Fix immediately
- **P2**: Fix within 24 hours
- **P3**: Fix within 1 week
- **P4**: Fix in next release

**Status Values:**

- **New**: Defect reported
- **Assigned**: Assigned to developer
- **In Progress**: Being fixed
- **Fixed**: Fixed, pending verification
- **Verified**: Fixed and verified
- **Rejected**: Not a defect
- **Deferred**: Postponed to later release

### 10.2 Defect Summary

| Severity | Total Defects | Open | Fixed | Rejected | Deferred |
|----------|---------------|------|-------|----------|----------|
| Critical | - [ ] | - [ ] | - [ ] | - [ ] | - [ ] |
| High | - [ ] | - [ ] | - [ ] | - [ ] | - [ ] |
| Medium | - [ ] | - [ ] | - [ ] | - [ ] | - [ ] |
| Low | - [ ] | - [ ] | - [ ] | - [ ] | - [ ] |
| **TOTAL** | **- [ ]** | **- [ ]** | **- [ ]** | **- [ ]** | **- [ ]** |

### 10.3 Defect Reporting Guidelines

1. **Report defects immediately** when discovered
2. **Include screenshots** for visual defects
3. **Provide detailed steps** to reproduce
4. **Specify environment** (browser, OS, device)
5. **Attach logs** if available
6. **Update defect status** as it progresses

---

## 11. Test Tools and Resources

### 11.1 Testing Tools

- **PHPUnit**: Unit and feature testing framework
- **Browser DevTools**: Frontend debugging and performance analysis
- **Postman/Thunder Client**: API endpoint testing
- **Database Tools**: MySQL Workbench for data verification
- **Performance Tools**: Browser DevTools Performance tab, Lighthouse
- **Security Tools**: OWASP ZAP (optional for security scanning)

### 11.2 Test Data

**Test User Accounts:**
- Created via seeders: `AdminSeeder`, `RealisticDataSeeder`
- Default admin credentials: `admin@davidswood.com` / `password123`

**Test Products:**
- Sample product data from `ProductSeeder`
- Categories from `CategorySeeder`
- Realistic data from `RealisticDataSeeder`

**Test Orders:**
- Orders in various states from `OrderSeeder`
- Fulfillment data from seeders
- Returns/repairs data from `ReturnsRepairsSeeder`

### 11.3 Test Execution Tools

**Browser Testing:**
- Chrome (Latest)
- Firefox (Latest)
- Safari (Latest)
- Edge (Latest)

**Mobile Testing:**
- iOS Safari
- Android Chrome
- Responsive design testing

**API Testing:**
- Postman collection
- Thunder Client
- cURL commands

---

## 12. Test Sign-Off

### 12.1 Approval Signatures

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Test Lead | - [ ] | - [ ] | - [ ] |
| Project Manager | - [ ] | - [ ] | - [ ] |
| Development Lead | - [ ] | - [ ] | - [ ] |
| Quality Assurance | - [ ] | - [ ] | - [ ] |
| Business Owner | - [ ] | - [ ] | - [ ] |

### 12.2 Test Completion Criteria

- [ ] All critical test cases executed
- [ ] All high-priority defects fixed and verified
- [ ] Pass rate ≥ 95% for all test suites
- [ ] No critical defects remaining
- [ ] Performance benchmarks met (< 3 seconds page load)
- [ ] Security testing completed with no critical vulnerabilities
- [ ] User acceptance testing completed with stakeholder approval
- [ ] Documentation updated with test results
- [ ] Production deployment checklist completed

### 12.3 Test Summary Report Template

**Test Summary Report:**

```
Project: David's Wood E-Commerce Platform
Test Period: [Start Date] to [End Date]
Test Environment: [Environment Details]

Executive Summary:
- Total Test Cases: 199
- Test Cases Executed: - [ ]
- Test Cases Passed: - [ ]
- Test Cases Failed: - [ ]
- Test Cases Blocked: - [ ]
- Overall Pass Rate: - [ ]%

Key Findings:
- [List key findings]

Critical Issues:
- [List critical issues]

Recommendations:
- [List recommendations]

Sign-Off:
- Test Lead: - [ ]
- Project Manager: - [ ]
- Development Lead: - [ ]
```

---

## 13. Test Maintenance

### 13.1 Test Case Updates

Test cases should be updated when:
- New features are added
- Existing features are modified
- Defects reveal missing test coverage
- Business requirements change

### 13.2 Test Data Refresh

Test data should be refreshed:
- Before each test cycle
- After major database changes
- When test data becomes stale

### 13.3 Continuous Improvement

- Review test coverage regularly
- Update test cases based on lessons learned
- Improve test automation where possible
- Document test best practices

---

**Document Version:** 1.0  
**Last Updated:** [Date]  
**Prepared By:** [Name]  
**Reviewed By:** [Name]  
**Approved By:** [Name]

---

## Appendix A: Test Case Execution Log

### Sample Execution Log Format

| Date | Test Case ID | Tester | Status | Notes | Defect ID |
|------|--------------|--------|--------|-------|-----------|
| - [ ] | TC-CUST-001 | - [ ] | - [ ] | - [ ] | - [ ] |

---

## Appendix B: Performance Benchmarks

### Target Performance Metrics

| Metric | Target | Actual | Status |
|--------|--------|--------|--------|
| Homepage Load Time | < 3s | - [ ] | - [ ] |
| Product Page Load Time | < 2s | - [ ] | - [ ] |
| Admin Dashboard Load | < 3s | - [ ] | - [ ] |
| Database Query Time | < 500ms | - [ ] | - [ ] |
| API Response Time | < 1s | - [ ] | - [ ] |
| Concurrent Users | 50+ | - [ ] | - [ ] |

---

## Appendix C: Test Environment Configuration

### Production-Like Test Environment

```env
APP_ENV=testing
APP_DEBUG=false
DB_CONNECTION=mysql
DB_DATABASE=davids_wood_test
MAIL_MAILER=log
SESSION_DRIVER=database
CACHE_STORE=file
```

---

## Appendix D: Common Test Scenarios

### End-to-End Test Scenarios

**Scenario 1: New Customer Purchase**
1. Customer browses products
2. Adds items to cart
3. Registers new account
4. Verifies email
5. Completes checkout
6. Makes payment via Xendit
7. Receives order confirmation
8. Tracks order status
9. Receives order
10. Writes product review

**Scenario 2: Admin Order Processing**
1. Admin receives new order notification
2. Reviews order details
3. Approves order (if needed)
4. Marks items as packed
5. Updates shipping information
6. Marks order as shipped
7. Customer receives tracking update
8. Order delivered
9. Admin processes completion

**Scenario 3: Returns/Repairs Workflow**
1. Customer requests return
2. Admin creates RMA
3. Admin approves RMA
4. Customer ships item
5. Admin marks as received
6. Admin processes refund
7. RMA completed

---

**End of Document**

