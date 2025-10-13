<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ArchivedUser;
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

        // Get user's orders with pagination
        $orders = Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate cart totals
        $cartTotal = $cartItems->sum('total_price');
        $cartItemCount = $cartItems->count(); // Count number of items, not quantity

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
        
        // Build validation rules
        $rules = [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
        ];
        
        // Phone validation: if user already has a phone number, don't allow them to remove it
        if ($request->has('phone')) {
            $phoneValue = trim($request->phone);
            
            // If user had a phone number before and is trying to clear it, show error
            if (!empty($user->phone) && empty($phoneValue)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone number cannot be removed once added. You can only update it.'
                ], 422);
            }
            
            // If providing a phone number, validate Philippine format (10-11 digits, starts with 0 or 9)
            if (!empty($phoneValue)) {
                $rules['phone'] = [
                    'string',
                    'min:10',
                    'max:11',
                    'regex:/^[09]\d{9,10}$/' // Starts with 0 or 9, followed by 9-10 more digits
                ];
            }
        }
        
        // If email is being updated, require password
        if ($request->has('email') && $request->email !== $user->email) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
            $rules['password'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // If email is being changed, verify password
        if ($request->has('email') && $request->email !== $user->email) {
            if (!$request->has('password') || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 400);
            }
        }

        try {
            $updateData = [];
            
            // Update only provided fields
            if ($request->has('first_name')) $updateData['first_name'] = $request->first_name;
            if ($request->has('last_name')) $updateData['last_name'] = $request->last_name;
            if ($request->has('email') && $request->email !== $user->email) $updateData['email'] = $request->email;
            
            // Handle phone update
            if ($request->has('phone')) {
                $phoneValue = trim($request->phone);
                
                // Auto-format: Add "0" prefix if starts with 9
                if (!empty($phoneValue) && strlen($phoneValue) >= 10 && $phoneValue[0] === '9') {
                    $phoneValue = '0' . $phoneValue;
                }
                
                // Only update if not trying to clear an existing phone number
                if (!empty($user->phone) || !empty($phoneValue)) {
                    $updateData['phone'] = $phoneValue;
                }
            }
            
            if ($request->has('street')) $updateData['street'] = $request->street;
            if ($request->has('barangay')) $updateData['barangay'] = $request->barangay;
            if ($request->has('city')) $updateData['city'] = $request->city;
            if ($request->has('province')) $updateData['province'] = $request->province;
            if ($request->has('zip_code')) $updateData['zip_code'] = $request->zip_code;
            
            $user->update($updateData);

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
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // must contain a special character
            ],
            'new_password_confirmation' => 'required|same:new_password',
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
            'product_updates' => 'nullable|boolean',
            'special_offers' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update only the newsletter preferences that are provided
            $updateData = [];
            
            if ($request->has('product_updates')) {
                $updateData['newsletter_product_updates'] = $request->boolean('product_updates');
            }
            
            if ($request->has('special_offers')) {
                $updateData['newsletter_special_offers'] = $request->boolean('special_offers');
            }
            
            if (!empty($updateData)) {
                $user->update($updateData);
            }

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
            'region' => 'required|string|max:255',
            'province' => 'nullable|string|max:255', // Nullable for regions without provinces (e.g., NCR)
            'city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
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
                'region' => $request->region,
                'province' => $request->province ?? '', // Store empty string if no province
                'city' => $request->city,
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

    public function archiveAccount(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Archive the user data using the ArchivedUser model
            ArchivedUser::create([
                'original_user_id' => $user->id,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->phone,
                'street' => $user->street,
                'barangay' => $user->barangay,
                'city' => $user->city,
                'province' => $user->province,
                'region' => $user->region,
                'zip_code' => $user->zip_code,
                'newsletter_product_updates' => $user->newsletter_product_updates,
                'newsletter_special_offers' => $user->newsletter_special_offers,
                'google_id' => $user->google_id,
                'avatar' => $user->avatar,
                'archived_at' => now(),
                'archive_reason' => $request->reason ?? 'User requested account deletion',
            ]);

            // Delete user's cart items
            CartItem::where('user_id', $user->id)->delete();

            // Delete user's wishlist items
            WishlistItem::where('user_id', $user->id)->delete();

            // Anonymize orders (keep for record-keeping but remove user association)
            Order::where('user_id', $user->id)->update(['user_id' => null]);
            
            // Delete the user account (this makes email and username available again)
            $deleted = $user->delete();
            
            if (!$deleted) {
                throw new \Exception('Failed to delete user account');
            }

            DB::commit();

            // Logout the user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Your account has been successfully deleted.',
                'redirect' => route('home')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account: ' . $e->getMessage()
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

    public function getOrders(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $page = $request->get('page', 1);
        
        // Get user's orders with pagination
        $orders = Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);

        $html = view('partials.orders-list', compact('orders'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}