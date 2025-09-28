<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Authentication routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Cart routes (using web middleware for proper session handling)
Route::middleware(['web'])->group(function () {
    Route::get('/api/cart', [App\Http\Controllers\CartController::class, 'index']);
    Route::post('/api/cart/add', [App\Http\Controllers\CartController::class, 'addToCart']);
    Route::put('/api/cart/update', [App\Http\Controllers\CartController::class, 'updateCartItem']);
    Route::delete('/api/cart/remove', [App\Http\Controllers\CartController::class, 'removeFromCart']);
    Route::delete('/api/cart/clear', [App\Http\Controllers\CartController::class, 'clearCart']);
});

// Wishlist routes (using web middleware for proper session handling)
Route::middleware(['web'])->group(function () {
    Route::get('/api/wishlist', [App\Http\Controllers\WishlistController::class, 'index']);
    Route::post('/api/wishlist/add', [App\Http\Controllers\WishlistController::class, 'add']);
    Route::delete('/api/wishlist/remove', [App\Http\Controllers\WishlistController::class, 'remove']);
    Route::get('/api/wishlist/check/{productId}', [App\Http\Controllers\WishlistController::class, 'check']);
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
});
