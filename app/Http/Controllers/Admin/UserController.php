<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::withCount(['orders', 'wishlistItems'])
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            } elseif ($request->status === 'suspended') {
                $query->where('is_suspended', true);
            }
        }

        // Registration method filter
        if ($request->has('registration_method') && $request->registration_method !== 'all') {
            if ($request->registration_method === 'email') {
                $query->whereNull('google_id');
            } elseif ($request->registration_method === 'google') {
                $query->whereNotNull('google_id');
            }
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $all_customers = $query->paginate(15);

        // Get statistics with lifetime value calculations
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::whereNotNull('email_verified_at')->count(),
            'inactive_users' => User::whereNull('email_verified_at')->count(),
            'suspended_users' => User::where('is_suspended', true)->count(),
            'google_users' => User::whereNotNull('google_id')->count(),
            'recent_registrations' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'total_customer_value' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'average_order_value' => Order::where('payment_status', 'paid')->avg('total_amount'),
            'repeat_customers' => User::whereHas('orders', function ($q) {
                $q->where('payment_status', 'paid');
            }, '>=', 2)->count(),
        ];

        // Get customer groups for filtering
        $customerGroups = [
            'new_customers' => 'New Customers (0-1 orders)',
            'regular_customers' => 'Regular Customers (2-5 orders)',
            'loyal_customers' => 'Loyal Customers (6-15 orders)',
            'vip_customers' => 'VIP Customers (15+ orders)',
        ];

        return view('admin.users.index', compact('all_customers', 'stats', 'customerGroups'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'region' => 'nullable|string|max:255',
            'newsletter_subscribed' => 'boolean',
            'newsletter_product_updates' => 'boolean',
            'newsletter_special_offers' => 'boolean',
            'marketing_emails' => 'boolean',
            'send_welcome_email' => 'boolean',
        ]);

        $all_customerData = $validated;
        $all_customerData['is_suspended'] = false; // Default to active
        $all_customerData['email_verified_at'] = null; // Will be verified via magic link
        $all_customerData['password'] = Hash::make('temp_password_'.time()); // Temporary password

        // Set default values for newsletter preferences
        $all_customerData['newsletter_subscribed'] = $validated['newsletter_subscribed'] ?? false;
        $all_customerData['newsletter_product_updates'] = $validated['newsletter_product_updates'] ?? true;
        $all_customerData['newsletter_special_offers'] = $validated['newsletter_special_offers'] ?? false;
        $all_customerData['marketing_emails'] = $validated['marketing_emails'] ?? false;

        // Remove fields that are not in the database
        unset($all_customerData['send_welcome_email']);

        $all_customer = User::create($all_customerData);

        // Log customer creation
        AuditLog::log('customer.created', Auth::guard('admin')->user(), $all_customer, [], [], "Created customer {$all_customer->first_name} {$all_customer->last_name} ({$all_customer->email})");

        // Send welcome email with magic link if requested
        if ($request->has('send_welcome_email') && $request->send_welcome_email) {
            try {
                // Generate magic link for password setup
                $magicLinkService = new \App\Services\MagicLinkService;
                $magicLink = $magicLinkService->generateMagicLink($all_customer, 'password-setup');

                // Send welcome email with magic link
                Mail::to($all_customer->email)->send(new WelcomeMail($all_customer, $magicLink));
            } catch (\Exception $e) {
                // Log the error but don't fail the user creation
                \Log::error('Failed to send welcome email to user '.$all_customer->id.': '.$e->getMessage());
            }
        }

        return redirect()->to(admin_route('users.show', ['all_customer' => $all_customer]))
            ->with('success', 'Customer created successfully. '.($request->has('send_welcome_email') && $request->send_welcome_email ? 'Welcome email sent with password setup link.' : ''));
    }

    /**
     * Display the specified user.
     */
    public function show(User $all_customer)
    {
        $all_customer->load(['orders.orderItems', 'wishlists']);

        // Get user statistics
        $stats = [
            'total_orders' => $all_customer->orders->count(),
            'total_spent' => $all_customer->orders->where('payment_status', 'paid')->sum('total_amount'),
            'average_order_value' => $all_customer->orders->where('payment_status', 'paid')->avg('total_amount') ?? 0,
            'wishlist_items' => $all_customer->wishlists->count(),
            'last_order' => $all_customer->orders->sortByDesc('created_at')->first(),
            'registration_method' => $all_customer->google_id ? 'Google' : 'Email',
        ];

        // Recent orders
        $recentOrders = $all_customer->orders()
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.users.show', compact('all_customer', 'stats', 'recentOrders'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $all_customer)
    {
        return view('admin.users.edit', compact('all_customer'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $all_customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($all_customer->id)],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'newsletter_subscribed' => 'boolean',
            'marketing_emails' => 'boolean',
            'is_suspended' => 'boolean',
        ]);

        $oldValues = $all_customer->only(['first_name', 'last_name', 'email', 'phone', 'is_suspended']);
        $all_customer->update($validated);

        // Log customer update
        AuditLog::log('customer.updated', Auth::guard('admin')->user(), $all_customer, $oldValues, $all_customer->only(['first_name', 'last_name', 'email', 'phone', 'is_suspended']), "Updated customer {$all_customer->first_name} {$all_customer->last_name}");

        return redirect()->to(admin_route('users.show', $all_customer))
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $all_customer)
    {
        // Check if user has orders
        if ($all_customer->orders()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete user with existing orders. Consider suspending the account instead.']);
        }

        // Delete user's wishlist items
        $all_customer->wishlists()->delete();

        $userData = $all_customer->toArray();
        // Delete the user
        $all_customer->delete();

        // Log customer deletion
        AuditLog::log('customer.deleted', Auth::guard('admin')->user(), null, $userData, [], "Deleted customer {$userData['first_name']} {$userData['last_name']} ({$userData['email']})");

        return redirect()->to(admin_route('users.index'))
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Suspend a user account.
     */
    public function suspend(User $all_customer)
    {
        $all_customer->update(['is_suspended' => true]);

        // Log suspension
        AuditLog::log('customer.suspended', Auth::guard('admin')->user(), $all_customer, ['is_suspended' => false], ['is_suspended' => true], "Suspended customer {$all_customer->first_name} {$all_customer->last_name} ({$all_customer->email})");

        return back()->with('success', 'User account suspended successfully.');
    }

    /**
     * Unsuspend a user account.
     */
    public function unsuspend(User $all_customer)
    {
        $all_customer->update(['is_suspended' => false]);

        // Log unsuspension
        AuditLog::log('customer.unsuspended', Auth::guard('admin')->user(), $all_customer, ['is_suspended' => true], ['is_suspended' => false], "Unsuspended customer {$all_customer->first_name} {$all_customer->last_name} ({$all_customer->email})");

        return back()->with('success', 'User account unsuspended successfully.');
    }

    /**
     * Verify a user's email.
     */
    public function verifyEmail(User $all_customer)
    {
        $all_customer->update(['email_verified_at' => now()]);

        // Log email verification
        AuditLog::log('customer.email_verified', Auth::guard('admin')->user(), $all_customer, ['email_verified_at' => null], ['email_verified_at' => now()], "Verified email for customer {$all_customer->first_name} {$all_customer->last_name} ({$all_customer->email})");

        return back()->with('success', 'User email verified successfully.');
    }

    /**
     * Unverify a user's email.
     */
    public function unverifyEmail(User $all_customer)
    {
        $oldVerifiedAt = $all_customer->email_verified_at;
        $all_customer->update(['email_verified_at' => null]);

        // Log email unverification
        AuditLog::log('customer.email_unverified', Auth::guard('admin')->user(), $all_customer, ['email_verified_at' => $oldVerifiedAt], ['email_verified_at' => null], "Removed email verification for customer {$all_customer->first_name} {$all_customer->last_name} ({$all_customer->email})");

        return back()->with('success', 'User email verification removed.');
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $all_customer)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $all_customer->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Log password reset (don't log the actual password)
        AuditLog::log('customer.password_reset', Auth::guard('admin')->user(), $all_customer, [], [], "Reset password for customer {$all_customer->first_name} {$all_customer->last_name} ({$all_customer->email})");

        return back()->with('success', 'User password reset successfully.');
    }

    /**
     * Show admin users management.
     */
    public function admins(Request $request)
    {
        $query = Admin::orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        // Role filter
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        $admins = $query->paginate(15);

        return view('admin.users.admins', compact('admins'));
    }

    /**
     * Create new admin user.
     */
    public function createAdmin()
    {
        return view('admin.users.create-admin');
    }

    /**
     * Store new admin user.
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,manager,staff',
        ]);

        $adminData = $validated;
        $adminData['password'] = Hash::make($validated['password']);
        $adminData['email_verified_at'] = now();

        $admin = Admin::create($adminData);

        // Log admin user creation
        AuditLog::log('admin_user.created', Auth::guard('admin')->user(), $admin, [], [], "Created admin user {$admin->first_name} {$admin->last_name} ({$admin->email}) with role {$admin->role}");

        return redirect()->to(admin_route('users.admins'))
            ->with('success', 'Admin user created successfully.');
    }

    /**
     * Delete admin user.
     */
    public function destroyAdmin(Admin $admin)
    {
        // Prevent deleting the current admin
        if ($admin->id === Auth::guard('admin')->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        // Prevent deleting the last super admin
        if ($admin->role === 'super_admin' && Admin::where('role', 'super_admin')->count() <= 1) {
            return back()->withErrors(['error' => 'Cannot delete the last super admin account.']);
        }

        $adminData = $admin->toArray();
        $admin->delete();

        // Log admin deletion
        AuditLog::log('admin_user.deleted', Auth::guard('admin')->user(), null, $adminData, [], "Deleted admin user {$adminData['first_name']} {$adminData['last_name']} ({$adminData['email']}) with role {$adminData['role']}");

        return back()->with('success', 'Admin user deleted successfully.');
    }

    /**
     * Export users data.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');

        $all_customers = User::with('orders')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'users-export-'.now()->format('Y-m-d-H-i-s');

        if ($format === 'csv') {
            return $this->exportCsv($all_customers, $filename);
        }

        return back()->with('error', 'Export format not supported.');
    }

    /**
     * Export users as CSV.
     */
    private function exportCsv($all_customers, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'.csv"',
        ];

        $callback = function () use ($all_customers) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'First Name',
                'Last Name',
                'Email',
                'Phone',
                'Registration Method',
                'Email Verified',
                'Status',
                'Total Orders',
                'Total Spent',
                'Registered Date',
                'Last Login',
            ]);

            foreach ($all_customers as $all_customer) {
                fputcsv($file, [
                    $all_customer->id,
                    $all_customer->first_name,
                    $all_customer->last_name,
                    $all_customer->email,
                    $all_customer->phone ?? 'N/A',
                    $all_customer->google_id ? 'Google' : 'Email',
                    $all_customer->email_verified_at ? 'Yes' : 'No',
                    $all_customer->is_suspended ? 'Suspended' : 'Active',
                    $all_customer->orders->count(),
                    '$'.number_format($all_customer->orders->where('payment_status', 'paid')->sum('total_amount'), 2),
                    $all_customer->created_at->format('Y-m-d H:i:s'),
                    $all_customer->last_login_at ? $all_customer->last_login_at->format('Y-m-d H:i:s') : 'Never',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Add tags to customer.
     */
    public function addTags(Request $request, User $all_customer)
    {
        $request->validate([
            'tags' => 'required|array',
            'tags.*' => 'string|max:50',
        ]);

        $currentTags = $all_customer->tags ?? [];
        $newTags = array_unique(array_merge($currentTags, $request->tags));

        $all_customer->update(['tags' => $newTags]);

        return response()->json([
            'success' => true,
            'message' => 'Tags added successfully',
        ]);
    }

    /**
     * Remove tag from customer.
     */
    public function removeTag(Request $request, User $all_customer)
    {
        $request->validate([
            'tag' => 'required|string',
        ]);

        $currentTags = $all_customer->tags ?? [];
        $newTags = array_values(array_filter($currentTags, function ($tag) use ($request) {
            return $tag !== $request->tag;
        }));

        $all_customer->update(['tags' => $newTags]);

        return response()->json([
            'success' => true,
            'message' => 'Tag removed successfully',
        ]);
    }

    /**
     * Update customer notes.
     */
    public function updateNotes(Request $request, User $all_customer)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $all_customer->update(['admin_notes' => $request->admin_notes]);

        return response()->json([
            'success' => true,
            'message' => 'Notes updated successfully',
        ]);
    }

    /**
     * Get customer lifetime value and analytics.
     */
    public function getCustomerAnalytics(User $all_customer)
    {
        $orders = $all_customer->orders()->where('payment_status', 'paid')->get();

        $analytics = [
            'total_orders' => $orders->count(),
            'total_spent' => $orders->sum('total_amount'),
            'average_order_value' => $orders->avg('total_amount'),
            'first_order_date' => $orders->min('created_at'),
            'last_order_date' => $orders->max('created_at'),
            'days_since_last_order' => $orders->max('created_at') ? now()->diffInDays($orders->max('created_at')) : null,
            'customer_lifetime_days' => $all_customer->created_at ? now()->diffInDays($all_customer->created_at) : 0,
            'order_frequency' => $orders->count() > 0 && $all_customer->created_at ?
                $orders->count() / max(1, now()->diffInDays($all_customer->created_at) / 30) : 0,
            'customer_group' => $this->getCustomerGroup($orders->count()),
        ];

        return response()->json([
            'success' => true,
            'analytics' => $analytics,
        ]);
    }

    /**
     * Determine customer group based on order count.
     */
    private function getCustomerGroup($orderCount)
    {
        if ($orderCount == 0) {
            return 'Prospect';
        }
        if ($orderCount <= 1) {
            return 'New Customer';
        }
        if ($orderCount <= 5) {
            return 'Regular Customer';
        }
        if ($orderCount <= 15) {
            return 'Loyal Customer';
        }

        return 'VIP Customer';
    }

    /**
     * Bulk update customer tags.
     */
    public function bulkUpdateTags(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'tags' => 'required|array',
            'tags.*' => 'string|max:50',
            'action' => 'required|in:add,remove,replace',
        ]);

        $all_customerIds = $request->user_ids;
        $newTags = $request->tags;
        $action = $request->action;

        $all_customers = User::whereIn('id', $all_customerIds)->get();

        foreach ($all_customers as $all_customer) {
            $currentTags = $all_customer->tags ?? [];

            switch ($action) {
                case 'add':
                    $updatedTags = array_unique(array_merge($currentTags, $newTags));

                    break;
                case 'remove':
                    $updatedTags = array_values(array_filter($currentTags, function ($tag) use ($newTags) {
                        return ! in_array($tag, $newTags);
                    }));

                    break;
                case 'replace':
                    $updatedTags = $newTags;

                    break;
            }

            $all_customer->update(['tags' => $updatedTags]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tags updated for '.count($all_customerIds).' customers',
        ]);
    }

    /**
     * Get customers by group.
     */
    public function getByGroup($group)
    {
        $query = User::withCount(['orders' => function ($q) {
            $q->where('payment_status', 'paid');
        }]);

        switch ($group) {
            case 'new_customers':
                $query->having('orders_count', '<=', 1);

                break;
            case 'regular_customers':
                $query->having('orders_count', '>=', 2)->having('orders_count', '<=', 5);

                break;
            case 'loyal_customers':
                $query->having('orders_count', '>=', 6)->having('orders_count', '<=', 15);

                break;
            case 'vip_customers':
                $query->having('orders_count', '>=', 16);

                break;
        }

        $all_customers = $query->paginate(20);

        return response()->json([
            'success' => true,
            'users' => $all_customers->items(),
            'pagination' => [
                'current_page' => $all_customers->currentPage(),
                'last_page' => $all_customers->lastPage(),
                'per_page' => $all_customers->perPage(),
                'total' => $all_customers->total(),
            ],
        ]);
    }
}
