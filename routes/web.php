<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Admin subdomain routes (MUST BE FIRST!)
// Define admin routes function to avoid duplication
$adminRoutes = function () {
    // Guest routes (login, forgot password, etc.)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
        Route::get('/forgot-password', [App\Http\Controllers\Admin\AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
        Route::post('/forgot-password', [App\Http\Controllers\Admin\AuthController::class, 'sendResetLink']);
    });
    
    // Root admin route - redirect to login if not authenticated, dashboard if authenticated
    Route::get('/', function () {
        if (auth()->guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    })->name('index');
    
    // Protected admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
        
        // Product Management
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        
        // Order Management
        Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
        Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('orders/{order}/refund', [App\Http\Controllers\Admin\OrderController::class, 'processRefund'])->name('orders.process-refund');
        Route::get('orders/{order}/invoice', [App\Http\Controllers\Admin\OrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
        Route::get('orders/{order}/packing-slip', [App\Http\Controllers\Admin\OrderController::class, 'downloadPackingSlip'])->name('orders.download-packing-slip');
        
        // User Management
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::post('users/{user}/suspend', [App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('users.suspend');
        Route::post('users/{user}/unsuspend', [App\Http\Controllers\Admin\UserController::class, 'unsuspend'])->name('users.unsuspend');
        Route::post('users/{user}/verify-email', [App\Http\Controllers\Admin\UserController::class, 'verifyEmail'])->name('users.verify-email');
        Route::post('users/{user}/unverify-email', [App\Http\Controllers\Admin\UserController::class, 'unverifyEmail'])->name('users.unverify-email');
        Route::post('users/{user}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::get('users-export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
        
        // Admin User Management
        Route::get('admins', [App\Http\Controllers\Admin\UserController::class, 'admins'])->name('users.admins');
        Route::get('admins/create', [App\Http\Controllers\Admin\UserController::class, 'createAdmin'])->name('users.create-admin');
        Route::post('admins', [App\Http\Controllers\Admin\UserController::class, 'storeAdmin'])->name('users.store-admin');
        Route::delete('admins/{admin}', [App\Http\Controllers\Admin\UserController::class, 'destroyAdmin'])->name('users.destroy-admin');
        
        // Inventory Management
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
        
        // Settings
        Route::resource('settings', App\Http\Controllers\Admin\SettingController::class)->only(['index']);
        Route::post('settings/general', [App\Http\Controllers\Admin\SettingController::class, 'updateGeneral'])->name('settings.update-general');
        Route::post('settings/email', [App\Http\Controllers\Admin\SettingController::class, 'updateEmail'])->name('settings.update-email');
        Route::post('settings/payment-gateway/{paymentGateway}', [App\Http\Controllers\Admin\SettingController::class, 'updatePaymentGateway'])->name('settings.update-payment-gateway');
        Route::post('settings/shipping-method', [App\Http\Controllers\Admin\SettingController::class, 'createShippingMethod'])->name('settings.create-shipping-method');
        Route::put('settings/shipping-method/{shippingMethod}', [App\Http\Controllers\Admin\SettingController::class, 'updateShippingMethod'])->name('settings.update-shipping-method');
        Route::delete('settings/shipping-method/{shippingMethod}', [App\Http\Controllers\Admin\SettingController::class, 'deleteShippingMethod'])->name('settings.delete-shipping-method');
        Route::post('settings/test-email', [App\Http\Controllers\Admin\SettingController::class, 'testEmail'])->name('settings.test-email');
        Route::post('settings/clear-cache', [App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('settings.clear-cache');
        
        // Coupons/Promotions
        Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
        Route::post('coupons/{coupon}/toggle-status', [App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
        Route::post('coupons/generate-code', [App\Http\Controllers\Admin\CouponController::class, 'generateCode'])->name('coupons.generate-code');
        Route::post('coupons/{coupon}/duplicate', [App\Http\Controllers\Admin\CouponController::class, 'duplicate'])->name('coupons.duplicate');
        
            // Shipping Methods
            Route::resource('shipping-methods', App\Http\Controllers\Admin\ShippingMethodController::class);
            Route::post('shipping-methods/{shippingMethod}/toggle-status', [App\Http\Controllers\Admin\ShippingMethodController::class, 'toggleStatus'])->name('shipping-methods.toggle-status');
            Route::post('shipping-methods/reorder', [App\Http\Controllers\Admin\ShippingMethodController::class, 'reorder'])->name('shipping-methods.reorder');
            
            // Payment Gateways
            Route::resource('payment-gateways', App\Http\Controllers\Admin\PaymentGatewayController::class);
            Route::post('payment-gateways/{paymentGateway}/toggle-status', [App\Http\Controllers\Admin\PaymentGatewayController::class, 'toggleStatus'])->name('payment-gateways.toggle-status');
            Route::post('payment-gateways/{paymentGateway}/toggle-mode', [App\Http\Controllers\Admin\PaymentGatewayController::class, 'toggleMode'])->name('payment-gateways.toggle-mode');
            Route::post('payment-gateways/{paymentGateway}/test-connection', [App\Http\Controllers\Admin\PaymentGatewayController::class, 'testConnection'])->name('payment-gateways.test-connection');
            Route::post('payment-gateways/reorder', [App\Http\Controllers\Admin\PaymentGatewayController::class, 'reorder'])->name('payment-gateways.reorder');
            
            // CMS Pages
            Route::resource('cms-pages', App\Http\Controllers\Admin\CmsPageController::class);
            Route::post('cms-pages/{cmsPage}/toggle-status', [App\Http\Controllers\Admin\CmsPageController::class, 'toggleStatus'])->name('cms-pages.toggle-status');
            Route::post('cms-pages/{cmsPage}/duplicate', [App\Http\Controllers\Admin\CmsPageController::class, 'duplicate'])->name('cms-pages.duplicate');
            Route::get('cms-pages/{cmsPage}/preview', [App\Http\Controllers\Admin\CmsPageController::class, 'preview'])->name('cms-pages.preview');
            Route::post('cms-pages/generate-slug', [App\Http\Controllers\Admin\CmsPageController::class, 'generateSlug'])->name('cms-pages.generate-slug');
        
        // Analytics
        Route::get('analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('analytics/sales', [App\Http\Controllers\Admin\AnalyticsController::class, 'sales'])->name('analytics.sales');
        Route::get('analytics/customers', [App\Http\Controllers\Admin\AnalyticsController::class, 'customers'])->name('analytics.customers');
        Route::get('analytics/products', [App\Http\Controllers\Admin\AnalyticsController::class, 'products'])->name('analytics.products');
        Route::get('analytics/revenue', [App\Http\Controllers\Admin\AnalyticsController::class, 'revenue'])->name('analytics.revenue');
        Route::get('analytics/export', [App\Http\Controllers\Admin\AnalyticsController::class, 'export'])->name('analytics.export');
        
        // Reviews Management
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
            
            // Permissions Management
            Route::get('permissions', [App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('permissions.index');
            Route::post('permissions', [App\Http\Controllers\Admin\PermissionController::class, 'update'])->name('permissions.update');
            Route::post('permissions/reset', [App\Http\Controllers\Admin\PermissionController::class, 'resetToDefaults'])->name('permissions.reset');
        
        // Contact Messages
        Route::get('contact-messages', [App\Http\Controllers\ContactController::class, 'index'])->name('contact-messages.index');
        Route::get('contact-messages/{contactMessage}', [App\Http\Controllers\ContactController::class, 'show'])->name('contact-messages.show');
        Route::patch('contact-messages/{contactMessage}', [App\Http\Controllers\ContactController::class, 'update'])->name('contact-messages.update');
        Route::delete('contact-messages/{contactMessage}', [App\Http\Controllers\ContactController::class, 'destroy'])->name('contact-messages.destroy');
    });
};

// Register admin routes for both domains
Route::domain('admin.davidswood.test')->name('admin.')->group($adminRoutes);
Route::domain('admin.localhost')->name('admin.')->group($adminRoutes);

// Public routes - but check for admin subdomain first
Route::get('/', function () {
    $host = request()->getHost();
    
    // If this is an admin subdomain, redirect to admin login
    if ($host === 'admin.localhost' || $host === 'admin.davidswood.test') {
        if (auth()->guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    }
    
    // Otherwise, show the normal homepage
    return app(HomeController::class)->index();
})->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Contact form routes
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Login page route (for admin redirects) - redirect to home with login modal
Route::get('/login', function () {
    return redirect()->route('home')->with('show_login_modal', true);
})->name('login.form');

// Authentication routes (using api.session middleware for guest session capture)
Route::middleware(['api.session'])->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    Route::get('/api/check-username/{username}', [AuthController::class, 'checkUsername'])->name('check.username');
    Route::post('/api/store-intended-url', [AuthController::class, 'storeIntendedUrl'])->name('store.intended.url');
});

// Cart routes (using web middleware for proper session handling)
Route::middleware(['web'])->group(function () {
    Route::get('/api/cart', [App\Http\Controllers\CartController::class, 'index']);
    Route::post('/api/cart/add', [App\Http\Controllers\CartController::class, 'addToCart']);
    Route::put('/api/cart/update', [App\Http\Controllers\CartController::class, 'updateCartItem']);
    Route::delete('/api/cart/remove', [App\Http\Controllers\CartController::class, 'removeFromCart']);
    Route::delete('/api/cart/clear', [App\Http\Controllers\CartController::class, 'clearCart']);
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
            $query->whereHas('category', function($q) use ($request) {
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
                        ->where('is_approved', true)
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
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching products',
            'error' => $e->getMessage()
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
    
    // Payment Methods API routes
    Route::get('/api/payment-methods', [App\Http\Controllers\PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('/api/payment-methods', [App\Http\Controllers\PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::put('/api/payment-methods/{id}', [App\Http\Controllers\PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/api/payment-methods/{id}', [App\Http\Controllers\PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    Route::post('/api/payment-methods/{id}/set-default', [App\Http\Controllers\PaymentMethodController::class, 'setDefault'])->name('payment-methods.set-default');
});

// Public review routes
Route::get('/api/reviews/{productId}', [App\Http\Controllers\ProductReviewController::class, 'index'])->name('reviews.index');

// Public API routes
Route::get('/api/user/check', function () {
    \Log::info('Auth check called', [
        'session_id' => session()->getId(),
        'auth_check' => Auth::check(),
        'user_id' => Auth::id(),
        'url' => request()->url(),
        'referer' => request()->header('referer'),
        'user_agent' => request()->header('user-agent')
    ]);
    
    if (Auth::check()) {
        $user = Auth::user();
        \Log::info('Auth check - user authenticated', [
            'user_id' => $user->id,
            'username' => $user->username
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
                'provider' => $user->provider
            ]
        ]);
    }
    
    \Log::info('Auth check - user not authenticated', [
        'session_id' => session()->getId(),
        'session_data' => session()->all()
    ]);
    
    return response()->json([
        'authenticated' => false,
        'user_id' => null,
        'user' => null
    ]);
});

// Test username availability endpoint
Route::get('/test-username-check/{username}', function ($username) {
    $exists = \App\Models\User::where('username', $username)->exists();
    return response()->json([
        'username' => $username,
        'exists' => $exists,
        'available' => !$exists,
        'message' => $exists ? 'Username is already taken' : 'Username is available'
    ]);
});

// Test registration endpoint
Route::get('/test-register-form', function () {
    return view('test-register');
});

// Debug routes for testing
Route::get('/debug-profile-update', function () {
    $user = \App\Models\User::first();
    if (!$user) {
        return response()->json(['error' => 'No users found']);
    }
    
    return response()->json([
        'user_id' => $user->id,
        'current_name' => $user->first_name . ' ' . $user->last_name,
        'current_email' => $user->email,
        'current_street' => $user->street,
        'current_city' => $user->city,
        'database_connected' => true
    ]);
});

Route::get('/debug-wishlist', function () {
    $sessionId = 'hEfLeGvKEGL6kyywstnX7ndrlRuGMIfBMPigi4gz';
    $items = \App\Models\WishlistItem::where('session_id', $sessionId)->get();
    return response()->json([
        'session_id' => $sessionId,
        'count' => $items->count(),
        'items' => $items->toArray()
    ]);
});

Route::get('/debug-all-wishlist', function () {
    $allItems = \App\Models\WishlistItem::all();
    return response()->json([
        'total_count' => $allItems->count(),
        'items' => $allItems->toArray()
    ]);
});

Route::get('/debug-wishlist-session/{sessionId}', function ($sessionId) {
    $items = \App\Models\WishlistItem::where('session_id', $sessionId)->get();
    return response()->json([
        'session_id' => $sessionId,
        'count' => $items->count(),
        'items' => $items->toArray()
    ]);
});

Route::get('/debug-guest-session/{sessionId}', function ($sessionId) {
    $guestSession = \App\Models\GuestSession::find($sessionId);
    return response()->json([
        'session_id' => $sessionId,
        'guest_session_exists' => $guestSession ? true : false,
        'guest_session' => $guestSession ? $guestSession->toArray() : null
    ]);
});

// Debug products count and pagination
Route::get('/debug-products-count', function () {
    $totalProducts = \App\Models\Product::where('is_active', true)->count();
    $productsPage1 = \App\Models\Product::where('is_active', true)->paginate(28);
    
    return response()->json([
        'total_active_products' => $totalProducts,
        'per_page' => 28,
        'total_pages' => ceil($totalProducts / 28),
        'meta' => [
            'current_page' => $productsPage1->currentPage(),
            'total' => $productsPage1->total(),
            'per_page' => $productsPage1->perPage(),
            'last_page' => $productsPage1->lastPage(),
        ],
        'message' => $totalProducts > 28 ? 'Pagination should work' : 'Not enough products for pagination (need more than 28)'
    ]);
});

Route::get('/debug-wishlist-migration/{userId}/{sessionId}', function ($userId, $sessionId) {
    $wishlistController = new \App\Http\Controllers\WishlistController();
    try {
        $wishlistController->migrateWishlistToUser($userId, $sessionId);
        return response()->json([
            'success' => true,
            'message' => 'Migration completed',
            'user_id' => $userId,
            'session_id' => $sessionId
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Migration failed',
            'error' => $e->getMessage()
        ]);
    }
});

Route::get('/debug-preserve-wishlist/{sessionId}', function ($sessionId) {
    $wishlistController = new \App\Http\Controllers\WishlistController();
    try {
        $preservedData = $wishlistController->preserveGuestWishlistData($sessionId);
        return response()->json([
            'success' => true,
            'message' => 'Data preserved',
            'session_id' => $sessionId,
            'preserved_count' => count($preservedData),
            'preserved_data' => $preservedData
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Preservation failed',
            'error' => $e->getMessage()
        ]);
    }
});

Route::get('/test-logout', function () {
    return response()->json([
        'message' => 'Test logout route works',
        'user_authenticated' => \Auth::check(),
        'user_id' => \Auth::id()
    ]);
});