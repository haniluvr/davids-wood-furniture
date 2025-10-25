<?php

namespace App\Http\Controllers;

use App\Models\ArchivedUser;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
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

        // Get user's orders with pagination in chronological order (most recent first)
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

        // Debug logging
        \Log::info('Profile update request', [
            'user_id' => $user->id,
            'request_data' => $request->all(),
            'current_user' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ]);

        // Determine which fields have actually changed
        $changedFields = [];
        $validationRules = [];

        // Check first name
        if ($request->has('first_name') && $request->first_name !== $user->first_name) {
            $changedFields['first_name'] = $request->first_name;
            $validationRules['first_name'] = 'required|string|max:255';
        }

        // Check last name
        if ($request->has('last_name') && $request->last_name !== $user->last_name) {
            $changedFields['last_name'] = $request->last_name;
            $validationRules['last_name'] = 'required|string|max:255';
        }

        // Check username
        \Log::info('Username check', [
            'has_username' => $request->has('username'),
            'request_username' => $request->username,
            'current_username' => $user->username,
            'is_different' => $request->has('username') && $request->username !== $user->username,
        ]);

        if ($request->has('username') && $request->username !== $user->username) {
            $changedFields['username'] = $request->username;
            $validationRules['username'] = 'required|string|min:3|max:20|regex:/^[a-zA-Z0-9_-]+$/|unique:users,username,'.$user->id;
            \Log::info('Username will be updated', ['new_username' => $request->username]);
        }

        // Check email
        if ($request->has('email') && $request->email !== $user->email) {
            $changedFields['email'] = $request->email;
            $validationRules['email'] = 'required|email|unique:users,email,'.$user->id;
            $validationRules['password'] = 'required'; // Always require password for email changes
        }

        // Check phone
        if ($request->has('phone')) {
            $phoneValue = trim($request->phone);
            $currentPhone = $user->phone ?? '';

            // Only validate if phone is actually changing
            if ($phoneValue !== $currentPhone) {
                // If user had a phone number before and is trying to clear it, show error
                if (! empty($user->phone) && empty($phoneValue)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Phone number cannot be removed once added. You can only update it.',
                    ], 422);
                }

                // If providing a phone number, validate Philippine format
                if (! empty($phoneValue)) {
                    $changedFields['phone'] = $phoneValue;
                    $validationRules['phone'] = [
                        'required',
                        'string',
                        'min:10',
                        'max:11',
                        'regex:/^[09]\d{9,10}$/', // Starts with 0 or 9, followed by 9-10 more digits
                    ];
                }
            }
        }

        // If no fields have changed, return success without doing anything
        if (empty($changedFields)) {
            return response()->json([
                'success' => true,
                'message' => 'No changes detected',
                'user' => $user,
            ]);
        }

        // Validate only the changed fields
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // If email is being changed, verify password
        if (isset($changedFields['email'])) {
            if (! $request->has('password') || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                ], 400);
            }
        }

        try {
            // Auto-format phone if it's being updated
            if (isset($changedFields['phone'])) {
                $phoneValue = $changedFields['phone'];
                // Auto-format: Add "0" prefix if starts with 9
                if (! empty($phoneValue) && strlen($phoneValue) >= 10 && $phoneValue[0] === '9') {
                    $changedFields['phone'] = '0'.$phoneValue;
                }
            }

            // Update only the changed fields
            $user->update($changedFields);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $user,
                'changed_fields' => array_keys($changedFields), // For debugging
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: '.$e->getMessage(),
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // Build validation rules based on whether user has a password
        $validationRules = [
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
        ];

        // Only require current password if user already has a password
        if ($user->hasPassword()) {
            $validationRules['current_password'] = 'required';
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check current password only if user has a password
        if ($user->hasPassword()) {
            if (! Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                ], 400);
            }
        }

        try {
            $hadPassword = $user->hasPassword();

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            $message = $hadPassword ? 'Password changed successfully' : 'Password added successfully';

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password: '.$e->getMessage(),
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
                'success' => true,
            ],
            [
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'location' => 'New York, NY',
                'timestamp' => now()->subDays(1),
                'success' => true,
            ],
            [
                'ip_address' => '203.0.113.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'location' => 'San Francisco, CA',
                'timestamp' => now()->subDays(3),
                'success' => false,
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $loginActivity,
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
                'is_default' => true,
            ],
            [
                'id' => 2,
                'type' => 'mastercard',
                'last_four' => '5555',
                'expiry_month' => '11',
                'expiry_year' => '2024',
                'is_default' => false,
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $paymentMethods,
        ]);
    }

    public function removePaymentMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // In a real application, you would delete from payment_methods table
        // For now, just return success
        return response()->json([
            'success' => true,
            'message' => 'Payment method removed successfully',
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
                'errors' => $validator->errors(),
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

            if (! empty($updateData)) {
                $user->update($updateData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Newsletter preferences updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update newsletter preferences: '.$e->getMessage(),
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
                'errors' => $validator->errors(),
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
                'message' => 'Address added successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add address: '.$e->getMessage(),
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
                'errors' => $validator->errors(),
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
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address: '.$e->getMessage(),
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
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verify password
        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password',
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

            if (! $deleted) {
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
                'redirect' => route('home'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account: '.$e->getMessage(),
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
            'redirect' => route('home'),
        ]);
    }

    public function getOrders(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $page = $request->get('page', 1);
        $status = $request->get('status', null);

        // Build query for user's orders
        $query = Order::where('user_id', $user->id)
            ->with('orderItems.product');

        // Apply status filter if provided
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Get paginated orders in chronological order (most recent first)
        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);

        $html = view('partials.orders-list', compact('orders'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }

    public function viewReceipt($orderNumber)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in to view receipt.');
        }

        // Get the order with items
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->with('orderItems.product')
            ->first();

        if (! $order) {
            abort(404, 'Order not found');
        }

        // Only allow viewing receipt for paid orders
        if ($order->payment_status !== 'paid') {
            abort(403, 'Receipt is only available for paid orders');
        }

        return view('receipt', compact('order', 'user'));
    }

    public function orders()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in to view your orders.');
        }

        // Get user's orders with pagination
        $orders = Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('account', compact('user', 'orders'));
    }

    public function wishlist()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in to view your wishlist.');
        }

        // Get user's wishlist items
        $wishlistItems = WishlistItem::forUser($user->id)
            ->with('product')
            ->get();

        return view('account', compact('user', 'wishlistItems'));
    }

    public function profile()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in to view your profile.');
        }

        return view('account', compact('user'));
    }

    /**
     * Enable two-factor authentication for the user
     */
    public function enableTwoFactor(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in to enable two-factor authentication.',
            ], 401);
        }

        // Validate password
        $request->validate([
            'password' => 'required|string',
        ]);

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password.',
            ], 422);
        }

        // Enable 2FA
        $user->update([
            'two_factor_enabled' => true,
        ]);

        // Send confirmation email
        Mail::to($user->email)->send(new \App\Mail\TwoFactorEnabledMail($user));

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication has been enabled. You will receive a confirmation email shortly.',
        ]);
    }

    /**
     * Disable two-factor authentication for the user
     */
    public function disableTwoFactor(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in to disable two-factor authentication.',
            ], 401);
        }

        // Validate password
        $request->validate([
            'password' => 'required|string',
        ]);

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password.',
            ], 422);
        }

        // Disable 2FA
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_verified_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication has been disabled.',
        ]);
    }

    /**
     * Get the current 2FA status for the user
     */
    public function getTwoFactorStatus()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in to view your security settings.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'two_factor_enabled' => $user->two_factor_enabled,
            'two_factor_verified_at' => $user->two_factor_verified_at,
        ]);
    }
}
