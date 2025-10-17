<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Coupon::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->active();
                    break;
                case 'expired':
                    $query->where('expires_at', '<', now());
                    break;
                case 'upcoming':
                    $query->where('starts_at', '>', now());
                    break;
                case 'used_up':
                    $query->whereRaw('used_count >= maximum_uses');
                    break;
            }
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $coupons = $query->paginate(15)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        return view('admin.coupons.create', compact('products', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'required|numeric|min:0',
            'maximum_uses' => 'nullable|integer|min:1',
            'maximum_uses_per_customer' => 'required|integer|min:1',
            'starts_at' => 'required|date|after_or_equal:today',
            'expires_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'applicable_products' => 'nullable|array',
            'applicable_products.*' => 'exists:products,id',
            'applicable_categories' => 'nullable|array',
            'applicable_categories.*' => 'exists:categories,id',
            'excluded_products' => 'nullable|array',
            'excluded_products.*' => 'exists:products,id',
            'excluded_categories' => 'nullable|array',
            'excluded_categories.*' => 'exists:categories,id',
        ]);

        // Validate percentage value
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage value cannot exceed 100%']);
        }

        $coupon = Coupon::create($validated);

        // Log the action
        AuditLog::logCreate(Auth::guard('admin')->user(), $coupon);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        $coupon->load(['orders' => function($query) {
            $query->latest()->take(10);
        }]);

        $usageStats = [
            'total_uses' => $coupon->used_count,
            'remaining_uses' => $coupon->maximum_uses ? $coupon->maximum_uses - $coupon->used_count : 'Unlimited',
            'total_discount_given' => $coupon->orders()->sum('pivot.discount_amount'),
            'average_discount' => $coupon->orders()->avg('pivot.discount_amount'),
        ];

        return view('admin.coupons.show', compact('coupon', 'usageStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        return view('admin.coupons.edit', compact('coupon', 'products', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $oldValues = $coupon->toArray();

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'required|numeric|min:0',
            'maximum_uses' => 'nullable|integer|min:1',
            'maximum_uses_per_customer' => 'required|integer|min:1',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'applicable_products' => 'nullable|array',
            'applicable_products.*' => 'exists:products,id',
            'applicable_categories' => 'nullable|array',
            'applicable_categories.*' => 'exists:categories,id',
            'excluded_products' => 'nullable|array',
            'excluded_products.*' => 'exists:products,id',
            'excluded_categories' => 'nullable|array',
            'excluded_categories.*' => 'exists:categories,id',
        ]);

        // Validate percentage value
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage value cannot exceed 100%']);
        }

        $coupon->update($validated);

        // Log the action
        AuditLog::logUpdate(Auth::guard('admin')->user(), $coupon, $oldValues);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        // Check if coupon has been used
        if ($coupon->used_count > 0) {
            return back()->withErrors(['error' => 'Cannot delete coupon that has been used.']);
        }

        // Log the action before deletion
        AuditLog::logDelete(Auth::guard('admin')->user(), $coupon);
        
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    /**
     * Toggle coupon status
     */
    public function toggleStatus(Coupon $coupon)
    {
        $oldValues = $coupon->toArray();
        
        $coupon->update([
            'is_active' => !$coupon->is_active,
        ]);
        
        // Log the action
        AuditLog::logUpdate(Auth::guard('admin')->user(), $coupon, $oldValues);
        
        $status = $coupon->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Coupon {$status} successfully.");
    }

    /**
     * Generate a unique coupon code
     */
    public function generateCode(Request $request)
    {
        $request->validate([
            'prefix' => 'nullable|string|max:10',
            'length' => 'required|integer|min:4|max:20',
        ]);

        $prefix = $request->prefix ? strtoupper($request->prefix) . '-' : '';
        $length = $request->length;
        $maxAttempts = 100;

        for ($i = 0; $i < $maxAttempts; $i++) {
            $code = $prefix . strtoupper(Str::random($length));
            
            if (!Coupon::where('code', $code)->exists()) {
                return response()->json(['code' => $code]);
            }
        }

        return response()->json(['error' => 'Unable to generate unique code'], 500);
    }

    /**
     * Duplicate a coupon
     */
    public function duplicate(Coupon $coupon)
    {
        $newCoupon = $coupon->replicate();
        $newCoupon->code = $coupon->code . '-COPY-' . time();
        $newCoupon->name = $coupon->name . ' (Copy)';
        $newCoupon->used_count = 0;
        $newCoupon->starts_at = now();
        $newCoupon->expires_at = now()->addMonth();
        $newCoupon->save();

        // Log the action
        AuditLog::logCreate(Auth::guard('admin')->user(), $newCoupon);

        return redirect()->route('admin.coupons.edit', $newCoupon)
            ->with('success', 'Coupon duplicated successfully.');
    }
}
