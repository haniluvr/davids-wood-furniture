<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

// Admin subdomain routes (MUST BE FIRST!)
Route::domain('admin.davidswood.test')->name('admin.')->group(function () {
    // Guest routes (login, forgot password, etc.)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
        Route::get('/forgot-password', [App\Http\Controllers\Admin\AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
        Route::post('/forgot-password', [App\Http\Controllers\Admin\AuthController::class, 'sendResetLink']);
    });
    
    // Protected admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
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
        Route::resource('settings', App\Http\Controllers\Admin\SettingController::class)->only(['index', 'update']);
        
        // Analytics
        Route::get('/analytics', function () {
            return view('admin.analytics.index');
        })->name('analytics');
    });
});

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Login page route (for admin redirects)
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

// Authentication routes (using api.session middleware for guest session capture)
Route::middleware(['api.session'])->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
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
            ->with(['category']);
            
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
                $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
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
Route::middleware('auth')->group(function () {
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
    Route::post('/api/account/logout', [AccountController::class, 'logout']);
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