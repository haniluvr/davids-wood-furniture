<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\GuestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Get or create guest session
     */
    private function getOrCreateGuestSession(string $sessionId): GuestSession
    {
        return GuestSession::findOrCreateSession($sessionId);
    }

    /**
     * Get cart items for current user or guest
     */
    public function index(Request $request)
    {
        try {
            // Force session start
            if (!session()->isStarted()) {
                session()->start();
            }
            
            $sessionId = session()->getId();
            
            
            // Use Laravel's built-in authentication
            $userId = null;
            if (\Auth::check()) {
                $userId = \Auth::id();
            }
            
            if ($userId) {
                // User is logged in - get their cart items
                $cartItems = CartItem::forUser($userId)
                    ->with('product')
                    ->get();
            } else {
                // Guest user - get session cart items
                $guestSession = $this->getOrCreateGuestSession($sessionId);
                $cartItems = CartItem::forGuest($sessionId)
                    ->with('product')
                    ->get();
                
            }
            
            $subtotal = $cartItems->sum('total_price');
            $totalItems = $cartItems->count(); // Count number of items, not quantity
            
            return response()->json([
                'success' => true,
                'data' => [
                    'cart_items' => $cartItems,
                    'subtotal' => $subtotal,
                    'total_items' => $totalItems
                ],
                'session_id' => $sessionId,
                'debug' => [
                    'session_id' => $sessionId,
                    'user_id' => $userId,
                    'items_count' => $cartItems->count(),
                    'request_cookies' => $request->cookies->all(),
                    'session_cookie' => $request->cookie('laravel_session')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'integer|min:1'
            ]);

            // Force session start
            if (!session()->isStarted()) {
                session()->start();
            }
            
            $sessionId = session()->getId();
            $productId = $request->product_id;
            $quantity = $request->quantity ?? 1;
            
            
            // Use Laravel's built-in authentication
            $userId = null;
            if (\Auth::check()) {
                $userId = \Auth::id();
            }

            // Get the product
            $product = Product::findOrFail($productId);
            
            if ($userId) {
                // User is logged in
                $existingItem = CartItem::forUser($userId)
                    ->where('product_id', $productId)
                    ->first();

                if ($existingItem) {
                    // Update existing quantity
                    $existingItem->quantity += $quantity;
                    $existingItem->total_price = $existingItem->quantity * $existingItem->unit_price;
                    $existingItem->save();
                } else {
                    // Create new item
                    CartItem::create([
                        'user_id' => $userId,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                        'total_price' => $product->price * $quantity,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'product_data' => [
                            'image' => is_array($product->images) && !empty($product->images) ? $product->images[0] : null,
                            'slug' => $product->slug,
                            'description' => $product->short_description
                        ]
                    ]);
                }
            } else {
                // Guest user
                $this->getOrCreateGuestSession($sessionId);
                
                $existingItem = CartItem::forGuest($sessionId)
                    ->where('product_id', $productId)
                    ->first();

                if ($existingItem) {
                    // Update existing quantity
                    $existingItem->quantity += $quantity;
                    $existingItem->total_price = $existingItem->quantity * $existingItem->unit_price;
                    $existingItem->save();
                } else {
                    // Create new item
                    CartItem::create([
                        'session_id' => $sessionId,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                        'total_price' => $product->price * $quantity,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'product_data' => [
                            'image' => is_array($product->images) && !empty($product->images) ? $product->images[0] : null,
                            'slug' => $product->slug,
                            'description' => $product->short_description
                        ]
                    ]);
                }
                
            }
            
            // Force session save to ensure cookie is set
            session()->save();

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'session_id' => $sessionId,
                'debug' => [
                    'session_id' => $sessionId,
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'request_cookies' => $request->cookies->all(),
                    'session_cookie' => $request->cookie('laravel_session')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $sessionId = session()->getId();
        
        if ($userId) {
            $cartItem = CartItem::forUser($userId)
                ->where('product_id', $request->product_id)
                ->first();
        } else {
            $cartItem = CartItem::forGuest($sessionId)
            ->where('product_id', $request->product_id)
            ->first();
        }

        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->total_price = $cartItem->quantity * $cartItem->unit_price;
            $cartItem->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully'
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $userId = Auth::id();
        $sessionId = session()->getId();
        
        if ($userId) {
            CartItem::forUser($userId)
                ->where('product_id', $request->product_id)
                ->delete();
        } else {
            CartItem::forGuest($sessionId)
            ->where('product_id', $request->product_id)
            ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ]);
    }

    /**
     * Clear cart
     */
    public function clearCart(Request $request)
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        if ($userId) {
            CartItem::forUser($userId)->delete();
        } else {
            CartItem::forGuest($sessionId)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    /**
     * Migrate guest cart items to user account when they log in
     */
    public function migrateCartToUser($userId, $sessionId)
    {
        try {
            DB::transaction(function () use ($userId, $sessionId) {
                // Get all guest cart items
                $guestCartItems = CartItem::forGuest($sessionId)->get();
                
                foreach ($guestCartItems as $guestItem) {
                    // Check if user already has this product in their cart
                    $existingUserItem = CartItem::forUser($userId)
                        ->where('product_id', $guestItem->product_id)
                        ->first();

                    if ($existingUserItem) {
                        // Merge quantities
                        $existingUserItem->quantity += $guestItem->quantity;
                        $existingUserItem->total_price = $existingUserItem->quantity * $existingUserItem->unit_price;
                        $existingUserItem->save();
                    } else {
                        // Transfer item to user
                        $guestItem->update([
                            'user_id' => $userId,
                            'session_id' => null,
                        ]);
                    }
                }

                // Delete any remaining guest items (duplicates that were merged)
                CartItem::forGuest($sessionId)->delete();
                
                // Clean up guest session
                GuestSession::where('session_id', $sessionId)->delete();
            });


        } catch (\Exception $e) {
            \Log::error('Cart migration failed', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'error' => $e->getMessage()
        ]);
    }
}

    /**
     * Clear user cart (called when user logs out)
     */
    public function clearUserCart($userId)
    {
        try {
            CartItem::forUser($userId)->delete();
        } catch (\Exception $e) {
            \Log::error('Failed to clear user cart', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
        }
    }
}