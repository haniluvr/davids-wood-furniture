<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin subdomain routes (MUST BE FIRST!)
// Define admin routes function to avoid duplication
$adminRoutes = function () {
    // Guest routes (login, forgot password, etc.)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
        Route::get('/forgot-password', [App\Http\Controllers\Admin\AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
        Route::post('/forgot-password', [App\Http\Controllers\Admin\AuthController::class, 'sendResetLink'])->name('forgot-password.post');
        Route::get('/verify-magic-link/{token}', [App\Http\Controllers\Admin\AuthController::class, 'verifyMagicLink'])->name('verify-magic-link');
        Route::get('/check-email', [App\Http\Controllers\Admin\AuthController::class, 'checkEmail'])->name('check-email');
        Route::get('/verify-otp', [App\Http\Controllers\Admin\AuthController::class, 'showOtpVerification'])->name('verify-otp');
        Route::post('/verify-otp', [App\Http\Controllers\Admin\AuthController::class, 'verifyOtp'])->name('verify-otp.post');
        Route::post('/resend-otp', [App\Http\Controllers\Admin\AuthController::class, 'resendOtp'])->name('resend-otp');
    });

    // Root admin route - redirect to login if not authenticated, dashboard if authenticated
    Route::get('/', function () {
        if (auth()->guard('admin')->check()) {
            return redirect()->to(admin_route('dashboard'));
        }

        return redirect('/login');
    })->name('index');

    // Admin logout routes (outside middleware to handle CSRF expiration)
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout.get');

    // Fallback route for CSRF token expiration
    Route::get('/logout-fallback', function () {
        Auth::guard('admin')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to(admin_route('login'))
            ->with('success', 'You have been logged out successfully.');
    })->name('logout.fallback');

    // Protected admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Product Management
        Route::middleware('admin.permission:products.view')->group(function () {
            Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
            Route::patch('products/{product}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggle-status');
            Route::post('products/{product}/restock', [App\Http\Controllers\Admin\ProductController::class, 'restock'])->name('products.restock');
            Route::post('products/bulk-update-status', [App\Http\Controllers\Admin\ProductController::class, 'bulkUpdateStatus'])->name('products.bulk-update-status');
            Route::post('products/bulk-update-prices', [App\Http\Controllers\Admin\ProductController::class, 'bulkUpdatePrices'])->name('products.bulk-update-prices');
            Route::post('products/bulk-restock', [App\Http\Controllers\Admin\ProductController::class, 'bulkRestock'])->name('products.bulk-restock');
            Route::get('products/export', [App\Http\Controllers\Admin\ProductController::class, 'export'])->name('products.export');
            Route::post('products/export-download', [App\Http\Controllers\Admin\ProductController::class, 'exportDownload'])->name('products.export-download');
            Route::get('categories/{category}/subcategories', [App\Http\Controllers\Admin\CategoryController::class, 'getSubcategories'])->name('categories.subcategories');
        });

        // Order Management
        // Route::middleware('admin.permission:orders.view')->group(function () {
        // IMPORTANT: Custom routes must come BEFORE resource routes to avoid conflicts

        // Pending Approval
        Route::get('orders/pending-approval', [App\Http\Controllers\Admin\OrderController::class, 'pendingApproval'])->name('orders.pending-approval');

        // Fulfillment
        Route::get('orders/fulfillment', [App\Http\Controllers\Admin\FulfillmentController::class, 'index'])->name('orders.fulfillment');
        Route::get('orders/fulfillment/{order}', [App\Http\Controllers\Admin\FulfillmentController::class, 'show'])->name('orders.fulfillment.show');
        Route::post('orders/fulfillment/{order}/packing', [App\Http\Controllers\Admin\FulfillmentController::class, 'updatePackingStatus'])->name('orders.fulfillment.packing');
        Route::post('orders/fulfillment/{order}/shipping', [App\Http\Controllers\Admin\FulfillmentController::class, 'updateShippingStatus'])->name('orders.fulfillment.shipping');
        Route::post('orders/fulfillment/bulk-ship', [App\Http\Controllers\Admin\FulfillmentController::class, 'bulkMarkShipped'])->name('orders.fulfillment.bulk-ship');
        Route::get('orders/fulfillment/{order}/print-label', [App\Http\Controllers\Admin\FulfillmentController::class, 'printLabel'])->name('orders.fulfillment.print-label');

        // Returns & Repairs
        Route::get('orders/returns-repairs', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'index'])->name('orders.returns-repairs.index');
        Route::get('orders/returns-repairs/create', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'create'])->name('orders.returns-repairs.create');
        Route::post('orders/returns-repairs', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'store'])->name('orders.returns-repairs.store');
        Route::get('orders/returns-repairs/{returnRepair}', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'show'])->name('orders.returns-repairs.show');
        Route::get('orders/returns-repairs/{returnRepair}/edit', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'edit'])->name('orders.returns-repairs.edit');
        Route::put('orders/returns-repairs/{returnRepair}', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'update'])->name('orders.returns-repairs.update');
        Route::post('orders/returns-repairs/{returnRepair}/approve', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'approve'])->name('orders.returns-repairs.approve');
        Route::post('orders/returns-repairs/{returnRepair}/reject', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'reject'])->name('orders.returns-repairs.reject');
        Route::post('orders/returns-repairs/{returnRepair}/received', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'markReceived'])->name('orders.returns-repairs.received');
        Route::post('orders/returns-repairs/{returnRepair}/refund', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'processRefund'])->name('orders.returns-repairs.refund');
        Route::post('orders/returns-repairs/{returnRepair}/complete', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'markCompleted'])->name('orders.returns-repairs.complete');
        Route::post('orders/returns-repairs/{returnRepair}/notes', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'updateNotes'])->name('orders.returns-repairs.notes');
        Route::post('orders/returns-repairs/{returnRepair}/photos', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'uploadPhotos'])->name('orders.returns-repairs.photos');
        Route::delete('orders/returns-repairs/{returnRepair}/photos', [App\Http\Controllers\Admin\ReturnsRepairsController::class, 'deletePhoto'])->name('orders.returns-repairs.delete-photo');

        // Search routes for order creation
        Route::get('customers/search', [App\Http\Controllers\Admin\OrderController::class, 'searchCustomers'])->name('customers.search');
        Route::post('customers/quick-create', [App\Http\Controllers\Admin\OrderController::class, 'quickCreateCustomer'])->name('customers.quick-create');

        // Bulk Actions & Export (before resource routes)
        Route::post('orders/bulk-update-status', [App\Http\Controllers\Admin\OrderController::class, 'bulkUpdateStatus'])->name('orders.bulk-update-status');
        Route::get('orders/export', [App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');

        // Resource routes (must come AFTER custom routes)
        Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
        Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('orders/{order}/refund', [App\Http\Controllers\Admin\OrderController::class, 'processRefund'])->name('orders.process-refund');
        Route::get('orders/{order}/invoice', [App\Http\Controllers\Admin\OrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
        Route::get('orders/{order}/packing-slip', [App\Http\Controllers\Admin\OrderController::class, 'downloadPackingSlip'])->name('orders.download-packing-slip');
        Route::post('orders/{order}/approve', [App\Http\Controllers\Admin\OrderController::class, 'approveOrder'])->name('orders.approve');
        Route::post('orders/{order}/reject', [App\Http\Controllers\Admin\OrderController::class, 'rejectOrder'])->name('orders.reject');
        // });

        // User Management
        Route::middleware('admin.permission:users.view')->group(function () {
            Route::resource('all-customers', App\Http\Controllers\Admin\UserController::class)->names([
                'index' => 'users.index',
                'create' => 'users.create',
                'store' => 'users.store',
                'show' => 'users.show',
                'edit' => 'users.edit',
                'update' => 'users.update',
                'destroy' => 'users.destroy',
            ]);
            Route::post('all-customers/{all_customer}/suspend', [App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('users.suspend');
            Route::post('all-customers/{all_customer}/unsuspend', [App\Http\Controllers\Admin\UserController::class, 'unsuspend'])->name('users.unsuspend');
            Route::post('all-customers/{all_customer}/verify-email', [App\Http\Controllers\Admin\UserController::class, 'verifyEmail'])->name('users.verify-email');
            Route::post('all-customers/{all_customer}/unverify-email', [App\Http\Controllers\Admin\UserController::class, 'unverifyEmail'])->name('users.unverify-email');
            Route::post('all-customers/{all_customer}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
            Route::get('all-customers-export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
            Route::post('all-customers/{all_customer}/tags', [App\Http\Controllers\Admin\UserController::class, 'addTags'])->name('users.add-tags');
            Route::post('all-customers/{all_customer}/remove-tag', [App\Http\Controllers\Admin\UserController::class, 'removeTag'])->name('users.remove-tag');
            Route::post('all-customers/{all_customer}/notes', [App\Http\Controllers\Admin\UserController::class, 'updateNotes'])->name('users.update-notes');
            Route::get('all-customers/{all_customer}/analytics', [App\Http\Controllers\Admin\UserController::class, 'getCustomerAnalytics'])->name('users.analytics');
            Route::post('all-customers/bulk-update-tags', [App\Http\Controllers\Admin\UserController::class, 'bulkUpdateTags'])->name('users.bulk-update-tags');
            Route::get('all-customers/group/{group}', [App\Http\Controllers\Admin\UserController::class, 'getByGroup'])->name('users.by-group');
        });

        // Admin User Management
        Route::middleware('admin.permission:admins.view')->group(function () {
            Route::get('admins', [App\Http\Controllers\Admin\UserController::class, 'admins'])->name('users.admins');
            Route::get('admins/create', [App\Http\Controllers\Admin\UserController::class, 'createAdmin'])->name('users.create-admin');
            Route::post('admins', [App\Http\Controllers\Admin\UserController::class, 'storeAdmin'])->name('users.store-admin');
            Route::delete('admins/{admin}', [App\Http\Controllers\Admin\UserController::class, 'destroyAdmin'])->name('users.destroy-admin');
        });

        // Inventory Management
        Route::middleware('admin.permission:inventory.view')->group(function () {
            Route::get('inventory', [App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
            Route::get('inventory/low-stock', [App\Http\Controllers\Admin\InventoryController::class, 'lowStockAlerts'])->name('inventory.low-stock');
            Route::get('inventory/movements', [App\Http\Controllers\Admin\InventoryController::class, 'movements'])->name('inventory.movements');
            Route::get('inventory/export', [App\Http\Controllers\Admin\InventoryController::class, 'export'])->name('inventory.export');
            Route::get('inventory/{product}', [App\Http\Controllers\Admin\InventoryController::class, 'show'])->name('inventory.show');
            Route::get('inventory/{product}/adjust', [App\Http\Controllers\Admin\InventoryController::class, 'adjust'])->name('inventory.adjust');
            Route::post('inventory/{product}/adjust', [App\Http\Controllers\Admin\InventoryController::class, 'processAdjustment'])->name('inventory.process-adjustment');
            Route::post('products/{product}/add-stock', [App\Http\Controllers\Admin\InventoryController::class, 'addStock'])->name('inventory.add-stock');
            Route::post('products/{product}/remove-stock', [App\Http\Controllers\Admin\InventoryController::class, 'removeStock'])->name('inventory.remove-stock');
            Route::post('inventory/bulk-update', [App\Http\Controllers\Admin\InventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');
            Route::post('inventory/bulk-restock', [App\Http\Controllers\Admin\InventoryController::class, 'bulkRestock'])->name('inventory.bulk-restock');
            Route::post('inventory/{product}/update-reorder-point', [App\Http\Controllers\Admin\InventoryController::class, 'updateReorderPoint'])->name('inventory.update-reorder-point');
        });

        // Settings
        Route::middleware('admin.permission:settings.view')->group(function () {
            Route::resource('settings', App\Http\Controllers\Admin\SettingController::class)->only(['index']);
            Route::post('settings/general', [App\Http\Controllers\Admin\SettingController::class, 'updateGeneral'])->name('settings.update-general');
            Route::post('settings/email', [App\Http\Controllers\Admin\SettingController::class, 'updateEmail'])->name('settings.update-email');
            Route::post('settings/payment-gateway/{paymentGateway}', [App\Http\Controllers\Admin\SettingController::class, 'updatePaymentGateway'])->name('settings.update-payment-gateway');
            Route::post('settings/shipping-method', [App\Http\Controllers\Admin\SettingController::class, 'createShippingMethod'])->name('settings.create-shipping-method');
            Route::put('settings/shipping-method/{shippingMethod}', [App\Http\Controllers\Admin\SettingController::class, 'updateShippingMethod'])->name('settings.update-shipping-method');
            Route::delete('settings/shipping-method/{shippingMethod}', [App\Http\Controllers\Admin\SettingController::class, 'deleteShippingMethod'])->name('settings.delete-shipping-method');
            Route::post('settings/test-email', [App\Http\Controllers\Admin\SettingController::class, 'testEmail'])->name('settings.test-email');
            Route::post('settings/clear-cache', [App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('settings.clear-cache');

            // Sustainability Settings
            Route::get('settings/sustainability', [App\Http\Controllers\Admin\SustainabilityController::class, 'index'])->name('settings.sustainability');
            Route::post('settings/sustainability', [App\Http\Controllers\Admin\SustainabilityController::class, 'update'])->name('settings.sustainability.update');

            // Integrations
            Route::get('integrations', [App\Http\Controllers\Admin\IntegrationController::class, 'index'])->name('integrations.index');
            Route::get('integrations/{integration}', [App\Http\Controllers\Admin\IntegrationController::class, 'edit'])->name('integrations.edit');
            Route::post('integrations/{integration}', [App\Http\Controllers\Admin\IntegrationController::class, 'update'])->name('integrations.update');
            Route::post('integrations/{integration}/test', [App\Http\Controllers\Admin\IntegrationController::class, 'testConnection'])->name('integrations.test');

            // Email preview routes
            Route::get('emails/preview', [App\Http\Controllers\Admin\EmailPreviewController::class, 'index'])->name('emails.preview');
            Route::get('emails/preview/{type}', [App\Http\Controllers\Admin\EmailPreviewController::class, 'preview'])->name('emails.preview.type');
        });

        // Shipping Methods
        Route::middleware('admin.permission:shipping.view')->group(function () {
            Route::resource('shipping-methods', App\Http\Controllers\Admin\ShippingMethodController::class);
            Route::post('shipping-methods/{shippingMethod}/toggle-status', [App\Http\Controllers\Admin\ShippingMethodController::class, 'toggleStatus'])->name('shipping-methods.toggle-status');
            Route::post('shipping-methods/reorder', [App\Http\Controllers\Admin\ShippingMethodController::class, 'reorder'])->name('shipping-methods.reorder');
        });

        // Payment Gateways
        Route::middleware('admin.permission:payment_gateways.view')->group(function () {
            Route::resource('payment-gateways', App\Http\Controllers\Admin\PaymentGatewayController::class);
            Route::post('payment-gateways/{paymentGateway}/toggle-status', [App\Http\Controllers\Admin\PaymentGatewayController::class, 'toggleStatus'])->name('payment-gateways.toggle-status');
            Route::post('payment-gateways/{paymentGateway}/toggle-mode', [App\Http\Controllers\Admin\PaymentGatewayController::class, 'toggleMode'])->name('payment-gateways.toggle-mode');
            Route::post('payment-gateways/{paymentGateway}/test-connection', [App\Http\Controllers\Admin\PaymentGatewayController::class, 'testConnection'])->name('payment-gateways.test-connection');
            Route::post('payment-gateways/reorder', [App\Http\Controllers\Admin\PaymentGatewayController::class, 'reorder'])->name('payment-gateways.reorder');
        });

        // CMS Pages
        Route::middleware('admin.permission:cms.view')->group(function () {
            Route::resource('cms-pages', App\Http\Controllers\Admin\CmsPageController::class);
            Route::post('cms-pages/{cmsPage}/toggle-status', [App\Http\Controllers\Admin\CmsPageController::class, 'toggleStatus'])->name('cms-pages.toggle-status');
            Route::post('cms-pages/{cmsPage}/duplicate', [App\Http\Controllers\Admin\CmsPageController::class, 'duplicate'])->name('cms-pages.duplicate');
            Route::get('cms-pages/{cmsPage}/preview', [App\Http\Controllers\Admin\CmsPageController::class, 'preview'])->name('cms-pages.preview');
            Route::post('cms-pages/generate-slug', [App\Http\Controllers\Admin\CmsPageController::class, 'generateSlug'])->name('cms-pages.generate-slug');
        });

        // Blogs
        Route::middleware('admin.permission:cms.view')->group(function () {
            Route::get('blogs', [App\Http\Controllers\Admin\CmsPageController::class, 'blogs'])->name('blogs.index');
        });

        // Analytics
        Route::middleware('admin.permission:analytics.view')->group(function () {
            Route::get('analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
            Route::get('analytics/sales', [App\Http\Controllers\Admin\AnalyticsController::class, 'sales'])->name('analytics.sales');
            Route::get('analytics/customers', [App\Http\Controllers\Admin\AnalyticsController::class, 'customers'])->name('analytics.customers');
            Route::get('analytics/products', [App\Http\Controllers\Admin\AnalyticsController::class, 'products'])->name('analytics.products');
            Route::get('analytics/revenue', [App\Http\Controllers\Admin\AnalyticsController::class, 'revenue'])->name('analytics.revenue');
            Route::get('analytics/export', [App\Http\Controllers\Admin\AnalyticsController::class, 'export'])->name('analytics.export');
            Route::get('analytics/customer-lifetime-value', [App\Http\Controllers\Admin\AnalyticsController::class, 'customerLifetimeValue'])->name('analytics.customer-lifetime-value');
            Route::get('analytics/product-performance', [App\Http\Controllers\Admin\AnalyticsController::class, 'productPerformance'])->name('analytics.product-performance');
        });

        // Reviews Management
        Route::middleware('admin.permission:reviews.view')->group(function () {
            Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'show', 'destroy']);
            Route::post('reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
            Route::post('reviews/{review}/reject', [App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
            Route::post('reviews/{review}/respond', [App\Http\Controllers\Admin\ReviewController::class, 'respond'])->name('reviews.respond');
            Route::put('reviews/{review}/response', [App\Http\Controllers\Admin\ReviewController::class, 'updateResponse'])->name('reviews.update-response');
            Route::delete('reviews/{review}/response', [App\Http\Controllers\Admin\ReviewController::class, 'removeResponse'])->name('reviews.remove-response');
            Route::post('reviews/bulk-approve', [App\Http\Controllers\Admin\ReviewController::class, 'bulkApprove'])->name('reviews.bulk-approve');
            Route::post('reviews/bulk-reject', [App\Http\Controllers\Admin\ReviewController::class, 'bulkReject'])->name('reviews.bulk-reject');
            Route::post('reviews/bulk-delete', [App\Http\Controllers\Admin\ReviewController::class, 'bulkDelete'])->name('reviews.bulk-delete');
            Route::get('reviews/export', [App\Http\Controllers\Admin\ReviewController::class, 'export'])->name('reviews.export');
        });

        // Permissions Management (Super Admin only)
        Route::middleware('admin.permission:admins.edit')->group(function () {
            Route::get('permissions', [App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('permissions.index');
            Route::post('permissions', [App\Http\Controllers\Admin\PermissionController::class, 'update'])->name('permissions.update');
            Route::post('permissions/reset', [App\Http\Controllers\Admin\PermissionController::class, 'resetToDefaults'])->name('permissions.reset');
        });

        // Audit Trail
        Route::middleware('admin.permission:audit_logs.view')->group(function () {
            Route::get('audit', [App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audit.index');
            Route::get('audit/export', [App\Http\Controllers\Admin\AuditController::class, 'export'])->name('audit.export');
        });

        // Notifications Management
        Route::middleware('admin.permission:notifications.view')->group(function () {
            Route::get('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
            Route::get('notifications/templates', [App\Http\Controllers\Admin\NotificationController::class, 'templates'])->name('notifications.templates');
            Route::post('notifications/templates/{template}', [App\Http\Controllers\Admin\NotificationController::class, 'updateTemplate'])->name('notifications.update-template');
            Route::post('notifications/send', [App\Http\Controllers\Admin\NotificationController::class, 'send'])->name('notifications.send');
            Route::post('notifications/test', [App\Http\Controllers\Admin\NotificationController::class, 'test'])->name('notifications.test');
        });

        // Bulk Actions
        Route::middleware('admin.permission:products.bulk_actions')->group(function () {
            Route::post('bulk/products', [App\Http\Controllers\Admin\BulkActionController::class, 'products'])->name('bulk.products');
            Route::post('bulk/orders', [App\Http\Controllers\Admin\BulkActionController::class, 'orders'])->name('bulk.orders');
            Route::post('bulk/users', [App\Http\Controllers\Admin\BulkActionController::class, 'users'])->name('bulk.users');
            Route::post('bulk/reviews', [App\Http\Controllers\Admin\BulkActionController::class, 'reviews'])->name('bulk.reviews');
        });

        // Image Upload
        Route::middleware('admin.permission:products.edit')->group(function () {
            Route::get('images/upload', function () {
                return view('admin.images.upload');
            })->name('images.upload-page');
            Route::post('images/upload', [App\Http\Controllers\Admin\ImageUploadController::class, 'upload'])->name('images.upload');
            Route::delete('images/{image}', [App\Http\Controllers\Admin\ImageUploadController::class, 'delete'])->name('images.delete');
            Route::post('images/reorder', [App\Http\Controllers\Admin\ImageUploadController::class, 'reorder'])->name('images.reorder');
        });

        // CMS Media Library
        Route::middleware('admin.permission:cms.view')->group(function () {
            Route::get('media-library', function () {
                return view('admin.cms-pages.media-library');
            })->name('media-library');
            Route::get('api/cms-images', [App\Http\Controllers\Admin\ImageUploadController::class, 'getCmsImages'])->name('api.cms-images');
        });

        // API Routes for image upload
        Route::get('api/products', function () {
            return \App\Models\Product::select('id', 'name')->get();
        })->name('api.products');

        // Messages (reorganized from contact-messages)
        Route::get('messages', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [App\Http\Controllers\Admin\MessageController::class, 'show'])->name('messages.show');
        Route::patch('messages/{message}', [App\Http\Controllers\Admin\MessageController::class, 'update'])->name('messages.update');
        Route::delete('messages/{message}', [App\Http\Controllers\Admin\MessageController::class, 'destroy'])->name('messages.destroy');
        Route::post('messages/{message}/respond', [App\Http\Controllers\Admin\MessageController::class, 'markAsResponded'])->name('messages.respond');
        Route::post('messages/{message}/assign', [App\Http\Controllers\Admin\MessageController::class, 'assign'])->name('messages.assign');
        Route::post('messages/{message}/tags', [App\Http\Controllers\Admin\MessageController::class, 'addTags'])->name('messages.add-tags');
        Route::post('messages/{message}/remove-tag', [App\Http\Controllers\Admin\MessageController::class, 'removeTag'])->name('messages.remove-tag');
        Route::post('messages/bulk-update-status', [App\Http\Controllers\Admin\MessageController::class, 'bulkUpdateStatus'])->name('messages.bulk-update-status');
        Route::get('messages/status/{status}', [App\Http\Controllers\Admin\MessageController::class, 'getByStatus'])->name('messages.by-status');
    });
};

// Register admin routes for both domains
Route::domain('admin.davidswood.test')->name('admin.test.')->group($adminRoutes);
Route::domain('admin.localhost')->name('admin.local.')->group($adminRoutes);
Route::domain('admin.davidswood.shop')->name('admin.')->group($adminRoutes);

// Fallback admin routes for any admin subdomain (handles ports)
Route::group(['middleware' => 'admin.subdomain'], function () use ($adminRoutes) {
    $adminRoutes();
})->name('admin.fallback.');

// Public routes - but check for admin subdomain first
Route::get('/', function () {
    $host = request()->getHost();

    // If this is an admin subdomain, redirect to admin login
    if ($host === 'admin.localhost' || $host === 'admin.davidswood.test' || $host === 'admin.davidswood.shop') {
        if (auth()->guard('admin')->check()) {
            return redirect()->to(admin_route('dashboard'));
        }

        return redirect('/login');
    }

    // Otherwise, show the normal homepage
    return app(HomeController::class)->index();
})->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// NOTE: The CMS catch-all route must come AFTER specific public/auth routes

// Contact form routes
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Login page route (for admin redirects) - redirect to home with login modal
Route::get('/login', function () {
    return redirect()->route('home')->with('show_login_modal', true);
})->name('user.login.form');

// Authentication routes (using api.session middleware for guest session capture)
Route::middleware(['api.session'])->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    Route::get('/verify-email-sent', [AuthController::class, 'verifyEmailSent'])->name('auth.verify-email-sent');
    Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('auth.verify-email');
    Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('auth.resend-verification');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot-password');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('auth.reset-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password.post');
    Route::get('/api/check-username/{username}', [AuthController::class, 'checkUsername'])->name('check.username');
    Route::get('/api/check-email/{email}', [AuthController::class, 'checkEmail'])->name('check.email');
    Route::post('/api/store-intended-url', [AuthController::class, 'storeIntendedUrl'])->name('store.intended.url');
});

// CMS Pages public routes (catch-all for valid slugs, but exclude specific routes)
// Keep this BELOW specific routes like auth, products, etc., to avoid shadowing
Route::get('/{slug}', [App\Http\Controllers\CmsPageController::class, 'show'])
    ->name('cms.show')
    ->where('slug', '^(?!test-route$|health$|login$|verify-email-sent$|verify-email$|reset-password$|auth$)[a-zA-Z0-9\-]+$');

// Cart routes (using web middleware for proper session handling)
Route::middleware(['web'])->group(function () {
    Route::get('/api/cart', [App\Http\Controllers\CartController::class, 'index']);
    Route::post('/api/cart/add', [App\Http\Controllers\CartController::class, 'addToCart']);
    Route::put('/api/cart/update', [App\Http\Controllers\CartController::class, 'updateCartItem']);
    Route::delete('/api/cart/remove', [App\Http\Controllers\CartController::class, 'removeFromCart']);
    Route::delete('/api/cart/clear', [App\Http\Controllers\CartController::class, 'clearCart']);
    Route::post('/api/store-selected-cart-items', [App\Http\Controllers\CartController::class, 'storeSelectedItems']);
});

// Wishlist routes (using api.session middleware for proper session handling and guest session capture)
Route::middleware(['api.session'])->group(function () {
    Route::get('/api/wishlist', [App\Http\Controllers\WishlistController::class, 'index']);
    Route::post('/api/wishlist/add', [App\Http\Controllers\WishlistController::class, 'add']);
    Route::delete('/api/wishlist/remove', [App\Http\Controllers\WishlistController::class, 'remove']);
    Route::get('/api/wishlist/check/{productId}', [App\Http\Controllers\WishlistController::class, 'check']);
    Route::post('/api/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle']);
    Route::delete('/api/wishlist/clear', [App\Http\Controllers\WishlistController::class, 'clear']);
    Route::post('/api/wishlist/migrate', [App\Http\Controllers\WishlistController::class, 'migrate']);
});

// API Integration routes for additional fetch
Route::get('/api/weather', [ApiController::class, 'weather'])->name('api.weather');

// Add products API explicitly to web routes
Route::get('/api/products', function (Illuminate\Http\Request $request) {
    try {
        $query = \App\Models\Product::where('is_active', true)
            ->with(['category', 'approvedReviews']);

        // Handle filtering
        if ($request->has('category') && $request->get('category') !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->get('category'));
            });
        }

        // Handle sorting
        switch ($request->get('sort')) {
            case 'price-low':
                $query->orderBy('price', 'asc');

                break;
            case 'price-high':
                $query->orderBy('price', 'desc');

                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');

                break;
            case 'popularity':
            default:
                // Order by average rating (5 stars first, then 4, 3, 2, 1, 0)
                $query->addSelect([
                    'avg_rating' => \App\Models\ProductReview::selectRaw('COALESCE(AVG(rating), 0)')
                        ->whereColumn('product_id', 'products.id')
                        ->where('is_approved', true),
                ])
                    ->orderBy('avg_rating', 'desc')
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('created_at', 'desc');

                break;
        }

        // Get pagination
        $perPage = $request->get('per_page', 8);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching products',
            'error' => $e->getMessage(),
        ], 500);
    }
});

// Protected routes (require authentication)
Route::middleware(['auth', 'store.intended'])->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/wishlist', [AccountController::class, 'wishlist'])->name('account.wishlist');
    Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');

    // Account API routes
    Route::post('/api/account/profile/update', [AccountController::class, 'updateProfile']);
    Route::post('/api/account/password/change', [AccountController::class, 'changePassword']);
    Route::get('/api/account/login-activity', [AccountController::class, 'getLoginActivity']);
    Route::get('/api/account/payment-methods', [AccountController::class, 'getPaymentMethods']);
    Route::delete('/api/account/payment-methods/{id}', [AccountController::class, 'removePaymentMethod']);
    Route::post('/api/account/newsletter/update', [AccountController::class, 'updateNewsletterPreferences']);
    Route::post('/api/account/address/add', [AccountController::class, 'addAddress']);
    Route::post('/api/account/address/update', [AccountController::class, 'updateAddress']);
    Route::delete('/api/account/archive', [AccountController::class, 'archiveAccount']);
    Route::post('/api/account/logout', [AccountController::class, 'logout']);
    Route::get('/api/account/orders', [AccountController::class, 'getOrders']);
    Route::get('/account/receipt/{orderNumber}', [AccountController::class, 'viewReceipt'])->name('account.receipt');

    // Notification routes
    Route::get('/api/notifications', [NotificationController::class, 'getUserNotifications']);
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);
    Route::post('/api/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/api/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/api/notifications/{id}', [NotificationController::class, 'deleteNotification']);
    Route::delete('/api/notifications/clear-all', [NotificationController::class, 'clearAll']);

    // Product Reviews
    Route::post('/api/reviews/submit', [App\Http\Controllers\ProductReviewController::class, 'store'])->name('reviews.store');

    // Checkout routes
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/validate-shipping', [App\Http\Controllers\CheckoutController::class, 'validateShipping'])->name('checkout.validate-shipping');
    Route::get('/checkout/payment', [App\Http\Controllers\CheckoutController::class, 'showPayment'])->name('checkout.payment');
    Route::post('/checkout/validate-payment', [App\Http\Controllers\CheckoutController::class, 'validatePayment'])->name('checkout.validate-payment');
    Route::get('/checkout/review', [App\Http\Controllers\CheckoutController::class, 'showReview'])->name('checkout.review');
    Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'processOrder'])->name('checkout.process');
    Route::get('/checkout/confirmation/{order}', [App\Http\Controllers\CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

    // Xendit payment routes (customer-facing redirects)
    Route::get('/payments/xendit/pay/{order}', [App\Http\Controllers\Payments\XenditPaymentController::class, 'pay'])
        ->name('payments.xendit.pay');
    Route::get('/payments/xendit/return/success', [App\Http\Controllers\Payments\XenditPaymentController::class, 'returnSuccess'])
        ->name('payments.xendit.return.success');
    Route::get('/payments/xendit/return/failed', [App\Http\Controllers\Payments\XenditPaymentController::class, 'returnFailed'])
        ->name('payments.xendit.return.failed');

    // Payment Methods API routes
    Route::get('/api/payment-methods', [App\Http\Controllers\PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('/api/payment-methods', [App\Http\Controllers\PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::put('/api/payment-methods/{id}', [App\Http\Controllers\PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/api/payment-methods/{id}', [App\Http\Controllers\PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    Route::post('/api/payment-methods/{id}/set-default', [App\Http\Controllers\PaymentMethodController::class, 'setDefault'])->name('payment-methods.set-default');
});

// Public review routes
Route::get('/api/reviews/{productId}', [App\Http\Controllers\ProductReviewController::class, 'index'])->name('api.reviews.index');

// Simple test route
Route::get('/test-route', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Laravel route is working',
        'timestamp' => now(),
        'app_name' => config('app.name'),
    ]);
});

// Enhanced health check endpoint for Docker Compose
Route::get('/health', function () {
    try {
        $checks = [
            'app' => true,
            'database' => false,
            'redis' => false,
            'storage' => false,
        ];

        $errors = [];

        // Check database connection
        try {
            \DB::connection()->getPdo();
            $checks['database'] = true;
        } catch (\Exception $e) {
            $errors[] = 'Database connection failed: '.$e->getMessage();
        }

        // Check Redis connection
        try {
            \Illuminate\Support\Facades\Redis::ping();
            $checks['redis'] = true;
        } catch (\Exception $e) {
            $errors[] = 'Redis connection failed: '.$e->getMessage();
        }

        // Check storage directories
        try {
            $storageWritable = is_writable(storage_path());
            $bootstrapWritable = is_writable(base_path('bootstrap/cache'));
            $checks['storage'] = $storageWritable && $bootstrapWritable;

            if (! $storageWritable) {
                $errors[] = 'Storage directory not writable';
            }
            if (! $bootstrapWritable) {
                $errors[] = 'Bootstrap cache directory not writable';
            }
        } catch (\Exception $e) {
            $errors[] = 'Storage check failed: '.$e->getMessage();
        }

        // In testing environment, Redis is optional
        $requiredChecks = ['app', 'database', 'storage'];
        if (app()->environment() !== 'testing') {
            $requiredChecks[] = 'redis';
        }

        $allHealthy = true;
        foreach ($requiredChecks as $check) {
            if (! ($checks[$check] ?? false)) {
                $allHealthy = false;

                break;
            }
        }

        return response()->json([
            'status' => $allHealthy ? 'healthy' : 'unhealthy',
            'timestamp' => date('Y-m-d H:i:s'),
            'service' => 'davids-wood-furniture',
            'php_version' => PHP_VERSION,
            'checks' => $checks,
            'errors' => $errors,
            'environment' => app()->environment(),
        ], $allHealthy ? 200 : 503);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => date('Y-m-d H:i:s'),
        ], 500);
    }
});

// Xendit webhook (no auth)
Route::post('/webhooks/xendit', [App\Http\Controllers\Payments\XenditPaymentController::class, 'webhook'])
    ->name('webhooks.xendit');

// Public API routes
Route::get('/api/user/check', function () {
    \Log::info('Auth check called', [
        'session_id' => session()->getId(),
        'auth_check' => Auth::check(),
        'user_id' => Auth::id(),
        'url' => request()->url(),
        'referer' => request()->header('referer'),
        'user_agent' => request()->header('user-agent'),
    ]);

    if (Auth::check()) {
        $user = Auth::user();
        \Log::info('Auth check - user authenticated', [
            'user_id' => $user->id,
            'username' => $user->username,
        ]);

        return response()->json([
            'authenticated' => true,
            'user_id' => $user->id,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'name' => $user->name,
                'provider' => $user->provider,
            ],
        ]);
    }

    \Log::info('Auth check - user not authenticated', [
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
    ]);

    return response()->json([
        'authenticated' => false,
        'user_id' => null,
        'user' => null,
    ]);
});

// Test username availability endpoint
Route::get('/test-username-check/{username}', function ($username) {
    $exists = \App\Models\User::where('username', $username)->exists();

    return response()->json([
        'username' => $username,
        'exists' => $exists,
        'available' => ! $exists,
        'message' => $exists ? 'Username is already taken' : 'Username is available',
    ]);
});

Route::get('/test-logout', function () {
    return response()->json([
        'message' => 'Test logout route works',
        'user_authenticated' => \Auth::check(),
        'user_id' => \Auth::id(),
    ]);
});
