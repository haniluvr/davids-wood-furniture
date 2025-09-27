<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getCart($userId = null, $sessionId = null)
    {
        try {
            // Try to find existing cart based on user or session
            $cart = Cart::where('user_id', $userId)
                ->orWhere('session_id', $sessionId)
                ->where('expires_at', '>', now())
                ->first();

            // If no cart found, create one
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'expires_at' => now()->addDays(7) // Cart expires in 7 days
                ]);
            }

            return $cart;
        } catch (\Exception $e) {
            // Create a fallback cart even if something goes wrong
            return Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId ?? 'temp',
                'expires_at' => now()->addDays(7)
            ]);
        }
    }

    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            $sessionId = session()->getId();
            
            $cart = $this->getCart($userId, $sessionId);
            
            $cart->load(['cartItems.product']);
            
            $subtotal = $cart->cartItems->sum('total_price');
            
            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => $cart,
                    'cart_items' => $cart->cartItems,
                    'subtotal' => $subtotal,
                    'total_items' => $cart->cartItems->sum('quantity')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'integer|min:1'
            ]);

            $userId = Auth::id();
            $sessionId = session()->getId();
            $productId = $request->product_id;
            $quantity = $request->quantity ?? 1;

            // Get the product
            $product = Product::findOrFail($productId);
            
            // Get or create cart
            $cart = $this->getCart($userId, $sessionId);
            
            // Check if item already exists in cart
            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->first();

            if ($existingItem) {
                // Update existing quantity
                $existingItem->quantity += $quantity;
                $existingItem->total_price = $existingItem->quantity * $existingItem->unit_price;
                $existingItem->save();
            } else {
                // Add new item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'total_price' => $product->price * $quantity,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_data' => [
                        'image' => $product->images[0] ?? null,
                        'slug' => $product->slug,
                        'description' => $product->short_description
                    ]
                ]);
            }

            // Update cart expiry
            $cart->expires_at = now()->addDays(7);
            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateCartItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $sessionId = session()->getId();
        
        $cart = $this->getCart($userId, $sessionId);
        
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

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

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $userId = Auth::id();
        $sessionId = session()->getId();
        
        $cart = $this->getCart($userId, $sessionId);
        
        CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ]);
    }

    public function clearCart(Request $request)
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        $cart = $this->getCart($userId, $sessionId);
        
        CartItem::where('cart_id', $cart->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }
}
