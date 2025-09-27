<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// API routes for frontend JavaScript

// Get products for homepage
Route::get('/products', function (Request $request) {
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
                // Use sort_order for popularity, fallback to created_at
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

// Get single product by slug
Route::get('/products/{slug}', function ($slug) {
    try {
        $product = \App\Models\Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category'])
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching product',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Categories API route
Route::get('/categories', function () {
    try {
        $categories = \App\Models\Category::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->select(['id', 'name', 'slug'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching categories',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Debug route to test API connectivity 
Route::get('/test-cart', function() {
    return response()->json([
        'success' => true,
        'message' => 'Cart API is accessible',
        'timestamp' => now()
    ]);
});

// Cart GET route - use controller
Route::get('/cart', [CartController::class, 'index']);

// Cart API routes
Route::post('/cart/add', [CartController::class, 'addToCart']);
Route::put('/cart/update', [CartController::class, 'updateCartItem']);
Route::delete('/cart/remove', [CartController::class, 'removeFromCart']);
Route::delete('/cart/clear', [CartController::class, 'clearCart']);
