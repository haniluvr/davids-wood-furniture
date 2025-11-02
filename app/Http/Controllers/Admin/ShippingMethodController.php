<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShippingMethodController extends Controller
{
    public function index(Request $request)
    {
        $query = ShippingMethod::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $shippingMethods = $query->ordered()->paginate(15);

        return view('admin.shipping-methods.index', compact('shippingMethods'));
    }

    public function create()
    {
        return view('admin.shipping-methods.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:flat_rate,free_shipping,weight_based,price_based',
            'cost' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_order_amount' => 'nullable|numeric|min:0',
            'estimated_days_min' => 'nullable|integer|min:1',
            'estimated_days_max' => 'nullable|integer|min:1|gte:estimated_days_min',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'zones' => 'nullable|array',
            'weight_rates' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shippingMethod = ShippingMethod::create($request->all());

        // Log the action
        AuditLog::log('shipping_method.created', Auth::guard('admin')->user(), $shippingMethod, [], [], "Created shipping method: {$shippingMethod->name}");

        return redirect()->to(admin_route('shipping-methods.index'))
            ->with('success', 'Shipping method created successfully.');
    }

    public function show(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping-methods.show', compact('shippingMethod'));
    }

    public function edit(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping-methods.edit', compact('shippingMethod'));
    }

    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:flat_rate,free_shipping,weight_based,price_based',
            'cost' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_order_amount' => 'nullable|numeric|min:0',
            'estimated_days_min' => 'nullable|integer|min:1',
            'estimated_days_max' => 'nullable|integer|min:1|gte:estimated_days_min',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'zones' => 'nullable|array',
            'weight_rates' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldValues = $shippingMethod->only(['name', 'cost', 'type', 'is_active']);
        $shippingMethod->update($request->all());

        // Log the action
        AuditLog::log('shipping_method.updated', Auth::guard('admin')->user(), $shippingMethod, $oldValues, $shippingMethod->only(['name', 'cost', 'type', 'is_active']), "Updated shipping method: {$shippingMethod->name}");

        return redirect()->to(admin_route('shipping-methods.index'))
            ->with('success', 'Shipping method updated successfully.');
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethodData = $shippingMethod->toArray();
        $shippingMethod->delete();

        // Log the action
        AuditLog::log('shipping_method.deleted', Auth::guard('admin')->user(), null, $shippingMethodData, [], "Deleted shipping method: {$shippingMethodData['name']}");

        return redirect()->to(admin_route('shipping-methods.index'))
            ->with('success', 'Shipping method deleted successfully.');
    }

    public function toggleStatus(ShippingMethod $shippingMethod)
    {
        $oldStatus = $shippingMethod->is_active;
        $shippingMethod->update(['is_active' => ! $shippingMethod->is_active]);

        // Log the action
        AuditLog::log('shipping_method.status_toggled', Auth::guard('admin')->user(), $shippingMethod, ['is_active' => $oldStatus], ['is_active' => $shippingMethod->is_active], "Toggled shipping method status for {$shippingMethod->name} from ".($oldStatus ? 'active' : 'inactive').' to '.($shippingMethod->is_active ? 'active' : 'inactive'));

        return response()->json([
            'success' => true,
            'message' => 'Shipping method status updated successfully.',
            'is_active' => $shippingMethod->is_active,
        ]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'shipping_methods' => 'required|array',
            'shipping_methods.*.id' => 'required|exists:shipping_methods,id',
            'shipping_methods.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->shipping_methods as $item) {
            ShippingMethod::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        // Log the action
        AuditLog::log('shipping_method.reordered', Auth::guard('admin')->user(), null, [], $request->shipping_methods, 'Reordered shipping methods');

        return response()->json([
            'success' => true,
            'message' => 'Shipping methods reordered successfully.',
        ]);
    }
}
