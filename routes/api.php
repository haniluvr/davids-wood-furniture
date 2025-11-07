<?php

use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API routes for frontend JavaScript

// Get products for homepage
Route::get('/products', function (Request $request) {
    try {
        $query = \App\Models\Product::where('is_active', true)
            ->with(['category']);

        // Handle filtering
        if ($request->has('category') && $request->get('category') !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->get('category'));
            });
        }

        // Handle room filtering
        if ($request->has('room') && $request->get('room') !== 'all') {
            $roomValue = $request->get('room');
            // Only filter if room is specified and not 'all'
            // whereJsonContains works for JSON columns - checks if the JSON array contains the value
            $query->whereNotNull('room_category')
                ->where('room_category', '!=', '[]')
                ->whereJsonContains('room_category', $roomValue);
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

// Get single product by ID
Route::get('/product/{id}', function ($id) {
    try {
        $product = \App\Models\Product::where('id', $id)
            ->where('is_active', true)
            ->with(['category'])
            ->first();

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching product',
            'error' => $e->getMessage(),
        ], 500);
    }
});

// Get single product by ID for quick view
Route::get('/products/id/{id}', function ($id) {
    try {
        $product = \App\Models\Product::where('id', $id)
            ->where('is_active', true)
            ->with(['category', 'approvedReviews'])
            ->first();

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        // Calculate average rating
        $averageRating = $product->approvedReviews->avg('rating') ?? 0;
        $reviewCount = $product->approvedReviews->count();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'image' => $product->image,
                'primary_image' => $product->primary_image,
                'images' => $product->images,
                'sku' => $product->sku,
                'stock_quantity' => $product->stock_quantity,
                'category' => $product->category,
                'average_rating' => round($averageRating, 1),
                'review_count' => $reviewCount,
                'rating' => round($averageRating, 1), // For compatibility
                'review_rating' => round($averageRating, 1), // For compatibility
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching product',
            'error' => $e->getMessage(),
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

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching product',
            'error' => $e->getMessage(),
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
            'data' => $categories,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching categories',
            'error' => $e->getMessage(),
        ], 500);
    }
});

// Search products API route
Route::get('/search', function (Request $request) {
    try {
        $query = \App\Models\Product::where('is_active', true)
            ->with(['category']);

        // Search functionality
        if ($request->has('q') && ! empty(trim($request->get('q')))) {
            $searchTerm = trim($request->get('q'));

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('description', 'like', '%'.$searchTerm.'%')
                    ->orWhere('short_description', 'like', '%'.$searchTerm.'%')
                    ->orWhere('material', 'like', '%'.$searchTerm.'%')
                    ->orWhere('sku', 'like', '%'.$searchTerm.'%');
            });
        }

        // Limit results for search modal (show max 10 results)
        $products = $query->orderBy('name', 'asc')->limit(10)->get();

        return response()->json([
            'success' => true,
            'data' => $products,
            'total' => $products->count(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error searching products',
            'error' => $e->getMessage(),
        ], 500);
    }
});

// Debug route to test API connectivity
Route::get('/test-cart', function () {
    return response()->json([
        'success' => true,
        'message' => 'Cart API is accessible',
        'timestamp' => now(),
    ]);
});

// Cart routes moved to web.php for proper authentication

// Cart migration endpoint for testing
Route::post('/cart/migrate', function (Request $request) {
    try {
        $userId = auth()->id();
        $sessionId = session()->getId();

        if (! $userId) {
            return response()->json([
                'success' => false,
                'message' => 'User must be authenticated to migrate cart',
            ], 401);
        }

        $cartController = new \App\Http\Controllers\CartController;
        $cartController->migrateCartToUser($userId, $sessionId);

        return response()->json([
            'success' => true,
            'message' => 'Cart migration completed',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Cart migration failed: '.$e->getMessage(),
        ], 500);
    }
})->middleware('auth');

// Wishlist routes moved to web.php for proper authentication

// Wishlist migration endpoint
Route::post('/wishlist/migrate', [WishlistController::class, 'migrate'])->middleware('auth');

// Track product view (for quick view modal)
Route::post('/products/{id}/track-view', function ($id) {
    try {
        $product = \App\Models\Product::where('id', $id)
            ->where('is_active', true)
            ->first();

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        // Increment view count
        $product->increment('view_count');

        return response()->json([
            'success' => true,
            'message' => 'View tracked successfully',
            'view_count' => $product->view_count,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error tracking view',
            'error' => $e->getMessage(),
        ], 500);
    }
});

// Cleanup old guest carts (for testing/debugging)
Route::post('/cart/cleanup', function (Request $request) {
    try {
        // Clear all guest carts (carts with user_id = null)
        $deletedCarts = \App\Models\Cart::whereNull('user_id')->delete();

        // Clear all cart items from deleted carts
        $deletedItems = \App\Models\CartItem::whereNotExists(function ($query) {
            $query->select(\DB::raw(1))
                ->from('carts')
                ->whereRaw('carts.id = cart_items.cart_id');
        })->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cleanup completed',
            'deleted_carts' => $deletedCarts,
            'deleted_items' => $deletedItems,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Cleanup failed',
            'error' => $e->getMessage(),
        ], 500);
    }
});
