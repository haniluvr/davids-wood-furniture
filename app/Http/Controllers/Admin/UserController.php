<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::withCount(['orders', 'wishlists'])
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
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

        $users = $query->paginate(15);

        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::whereNotNull('email_verified_at')->count(),
            'inactive_users' => User::whereNull('email_verified_at')->count(),
            'suspended_users' => User::where('is_suspended', true)->count(),
            'google_users' => User::whereNotNull('google_id')->count(),
            'recent_registrations' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
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
            'password' => 'required|string|min:8|confirmed',
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
            'email_verified' => 'boolean',
        ]);

        $userData = $validated;
        $userData['password'] = Hash::make($validated['password']);
        $userData['email_verified_at'] = $validated['email_verified'] ? now() : null;
        
        // Remove the checkbox field that's not in the database
        unset($userData['email_verified']);

        $user = User::create($userData);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['orders.orderItems', 'wishlists']);
        
        // Get user statistics
        $stats = [
            'total_orders' => $user->orders->count(),
            'total_spent' => $user->orders->where('payment_status', 'paid')->sum('total_amount'),
            'average_order_value' => $user->orders->where('payment_status', 'paid')->avg('total_amount') ?? 0,
            'wishlist_items' => $user->wishlists->count(),
            'last_order' => $user->orders->sortByDesc('created_at')->first(),
            'registration_method' => $user->google_id ? 'Google' : 'Email',
        ];

        // Recent orders
        $recentOrders = $user->orders()
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.users.show', compact('user', 'stats', 'recentOrders'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
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

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Check if user has orders
        if ($user->orders()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete user with existing orders. Consider suspending the account instead.']);
        }

        // Delete user's wishlist items
        $user->wishlists()->delete();
        
        // Delete the user
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Suspend a user account.
     */
    public function suspend(User $user)
    {
        $user->update(['is_suspended' => true]);
        
        return back()->with('success', 'User account suspended successfully.');
    }

    /**
     * Unsuspend a user account.
     */
    public function unsuspend(User $user)
    {
        $user->update(['is_suspended' => false]);
        
        return back()->with('success', 'User account unsuspended successfully.');
    }

    /**
     * Verify a user's email.
     */
    public function verifyEmail(User $user)
    {
        $user->update(['email_verified_at' => now()]);
        
        return back()->with('success', 'User email verified successfully.');
    }

    /**
     * Unverify a user's email.
     */
    public function unverifyEmail(User $user)
    {
        $user->update(['email_verified_at' => null]);
        
        return back()->with('success', 'User email verification removed.');
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

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
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
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

        return redirect()->route('admin.users.admins')
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

        $admin->delete();

        return back()->with('success', 'Admin user deleted successfully.');
    }

    /**
     * Export users data.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        $users = User::with('orders')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'users-export-' . now()->format('Y-m-d-H-i-s');

        if ($format === 'csv') {
            return $this->exportCsv($users, $filename);
        }

        return back()->with('error', 'Export format not supported.');
    }

    /**
     * Export users as CSV.
     */
    private function exportCsv($users, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        $callback = function() use ($users) {
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
                'Last Login'
            ]);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->first_name,
                    $user->last_name,
                    $user->email,
                    $user->phone ?? 'N/A',
                    $user->google_id ? 'Google' : 'Email',
                    $user->email_verified_at ? 'Yes' : 'No',
                    $user->is_suspended ? 'Suspended' : 'Active',
                    $user->orders->count(),
                    '$' . number_format($user->orders->where('payment_status', 'paid')->sum('total_amount'), 2),
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}