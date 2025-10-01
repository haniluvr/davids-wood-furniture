<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\CartItem;
use App\Models\WishlistItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to access your account.');
        }

        // Get user's cart items
        $cartItems = CartItem::forUser($user->id)
            ->with('product')
            ->get();

        // Get user's wishlist items
        $wishlistItems = WishlistItem::forUser($user->id)
            ->with('product')
            ->get();

        // Get user's orders
        $orders = Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate cart totals
        $cartTotal = $cartItems->sum('total_price');
        $cartItemCount = $cartItems->sum('quantity');

        return view('account', compact(
            'user',
            'cartItems',
            'wishlistItems',
            'orders',
            'cartTotal',
            'cartItemCount'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'street' => $request->street,
                'barangay' => $request->barangay,
                'city' => $request->city,
                'province' => $request->province,
                'zip_code' => $request->zip_code,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to change password: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getLoginActivity()
    {
        // This would typically come from a login_attempts table
        // For now, return dummy data
        $loginActivity = [
            [
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'location' => 'New York, NY',
                'timestamp' => now()->subHours(2),
                'success' => true
            ],
            [
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'location' => 'New York, NY',
                'timestamp' => now()->subDays(1),
                'success' => true
            ],
            [
                'ip_address' => '203.0.113.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'location' => 'San Francisco, CA',
                'timestamp' => now()->subDays(3),
                'success' => false
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $loginActivity
        ]);
    }

    public function getPaymentMethods()
    {
        // This would typically come from a payment_methods table
        // For now, return dummy data
        $paymentMethods = [
            [
                'id' => 1,
                'type' => 'visa',
                'last_four' => '4242',
                'expiry_month' => '05',
                'expiry_year' => '2025',
                'is_default' => true
            ],
            [
                'id' => 2,
                'type' => 'mastercard',
                'last_four' => '5555',
                'expiry_month' => '11',
                'expiry_year' => '2024',
                'is_default' => false
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $paymentMethods
        ]);
    }

    public function removePaymentMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // In a real application, you would delete from payment_methods table
        // For now, just return success
        return response()->json([
            'success' => true,
            'message' => 'Payment method removed successfully'
        ]);
    }

    public function updateNewsletterPreferences(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'product_updates' => 'boolean',
            'special_offers' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update newsletter preferences
            $user->update([
                'newsletter_product_updates' => $request->boolean('product_updates'),
                'newsletter_special_offers' => $request->boolean('special_offers'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Newsletter preferences updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update newsletter preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addAddress(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // If this is set as default, update user's main address
            if ($request->boolean('is_default')) {
                $user->update([
                    'street' => $request->street,
                    'city' => $request->city,
                    'province' => $request->province,
                    'barangay' => $request->barangay,
                    'zip_code' => $request->zip_code,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAddress(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update([
                'street' => $request->street,
                'city' => $request->city,
                'province' => $request->province,
                'barangay' => $request->barangay,
                'zip_code' => $request->zip_code,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
            'redirect' => route('home')
        ]);
    }
}