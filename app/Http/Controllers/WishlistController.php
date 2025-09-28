<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use App\Models\Product;
use App\Models\GuestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    /**
     * Get or create guest session
     */
    private function getOrCreateGuestSession(string $sessionId): GuestSession
    {
        return GuestSession::findOrCreateSession($sessionId);
    }

    /**
     * Get wishlist items for current user or guest
     */
    public function index(Request $request)
    {
        try {
            $sessionId = session()->getId();
            
            // Use Laravel's built-in authentication
            $userId = null;
            if (\Auth::check()) {
                $userId = \Auth::id();
            }
            
            if ($userId) {
                // User is logged in - get their wishlist items
                $wishlistItems = WishlistItem::forUser($userId)
                    ->with('product')
                    ->get();
            } else {
                // Guest user - get session wishlist items
                $this->getOrCreateGuestSession($sessionId);
                $wishlistItems = WishlistItem::forGuest($sessionId)
                    ->with('product')
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => $wishlistItems
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching wishlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add product to wishlist
     */
    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id'
            ]);

            // Use Laravel's built-in authentication
            $userId = null;
            if (\Auth::check()) {
                $userId = \Auth::id();
            }
            
            $sessionId = session()->getId();
            $productId = $request->product_id;

            // Check if product exists
            $product = Product::findOrFail($productId);

            if ($userId) {
                // User is logged in
                $wishlistItem = WishlistItem::firstOrCreate([
                    'user_id' => $userId,
                    'product_id' => $productId
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Product added to wishlist',
                    'data' => $wishlistItem->load('product')
                ]);
            } else {
                // Guest user
                $this->getOrCreateGuestSession($sessionId);
                
                $wishlistItem = WishlistItem::firstOrCreate([
                    'session_id' => $sessionId,
                    'product_id' => $productId
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Product added to wishlist',
                    'data' => $wishlistItem->load('product')
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding to wishlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove product from wishlist
     */
    public function remove(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id'
            ]);

            // Manual user detection - check session data directly
            $userId = null;
            
            // Check if user is logged in by looking at session data
            $sessionData = session()->all();
            
            // Look for user ID in session data
            foreach ($sessionData as $key => $value) {
                if (str_contains($key, 'login_web') && is_numeric($value)) {
                    $userId = $value;
                    break;
                }
            }
            
            // Alternative: Check if there's a user_id in session
            if (!$userId && isset($sessionData['user_id'])) {
                $userId = $sessionData['user_id'];
            }
            
            // Fallback: Check if there's an authenticated user in the database
            if (!$userId) {
                $user = \App\Models\User::where('remember_token', '!=', null)
                    ->where('updated_at', '>=', now()->subMinutes(10))
                    ->first();
                if ($user) {
                    $userId = $user->id;
                }
            }
            $sessionId = session()->getId();
            $productId = $request->product_id;

            if ($userId) {
                // User is logged in
                WishlistItem::forUser($userId)
                    ->where('product_id', $productId)
                    ->delete();
            } else {
                // Guest user
                WishlistItem::forGuest($sessionId)
                    ->where('product_id', $productId)
                    ->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing from wishlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if product is in wishlist
     */
    public function check($productId)
    {
        try {
            // Manual user detection - check session data directly
            $userId = null;
            
            // Check if user is logged in by looking at session data
            $sessionData = session()->all();
            
            // Look for user ID in session data
            foreach ($sessionData as $key => $value) {
                if (str_contains($key, 'login_web') && is_numeric($value)) {
                    $userId = $value;
                    break;
                }
            }
            
            // Alternative: Check if there's a user_id in session
            if (!$userId && isset($sessionData['user_id'])) {
                $userId = $sessionData['user_id'];
            }
            
            // Fallback: Check if there's an authenticated user in the database
            if (!$userId) {
                $user = \App\Models\User::where('remember_token', '!=', null)
                    ->where('updated_at', '>=', now()->subMinutes(10))
                    ->first();
                if ($user) {
                    $userId = $user->id;
                }
            }
            $sessionId = session()->getId();
            
            if ($userId) {
                // User is logged in
                $inWishlist = WishlistItem::forUser($userId)
                    ->where('product_id', $productId)
                    ->exists();
            } else {
                // Guest user
                $inWishlist = WishlistItem::forGuest($sessionId)
                    ->where('product_id', $productId)
                    ->exists();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'in_wishlist' => $inWishlist
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking wishlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Migrate guest wishlist to authenticated user
     */
    public function migrate(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }
            
            $request->validate([
                'guest_wishlist' => 'required|array',
                'guest_wishlist.*' => 'integer|exists:products,id'
            ]);
            
            $guestWishlist = $request->guest_wishlist;
            $migratedCount = 0;
            
            foreach ($guestWishlist as $productId) {
                $wishlistItem = WishlistItem::firstOrCreate([
                    'user_id' => $user->id,
                    'product_id' => $productId
                ]);
                
                if ($wishlistItem->wasRecentlyCreated) {
                    $migratedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Wishlist migrated successfully',
                'migrated_count' => $migratedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error migrating wishlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Migrate guest wishlist items to user account when they log in
     */
    public function migrateWishlistToUser($userId, $sessionId)
    {
        try {
            DB::transaction(function () use ($userId, $sessionId) {
                // Get all guest wishlist items
                $guestWishlistItems = WishlistItem::forGuest($sessionId)->get();
                
                foreach ($guestWishlistItems as $guestItem) {
                    // Transfer item to user (firstOrCreate handles duplicates)
                    WishlistItem::firstOrCreate([
                        'user_id' => $userId,
                        'product_id' => $guestItem->product_id
                    ]);
                }

                // Delete guest wishlist items
                WishlistItem::forGuest($sessionId)->delete();
                
                // Clean up guest session
                GuestSession::where('session_id', $sessionId)->delete();
            });

            \Log::info('Wishlist migrated successfully', [
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);

        } catch (\Exception $e) {
            \Log::error('Wishlist migration failed', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'error' => $e->getMessage()
            ]);
        }
    }
}