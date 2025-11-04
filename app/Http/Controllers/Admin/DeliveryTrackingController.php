<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryTrackingController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::whereIn('status', ['shipped', 'delivered'])
            ->whereNotNull('tracking_number')
            ->with(['user', 'orderItems.product']);

        // Search by order number, tracking number, or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('tracking_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by carrier
        if ($request->filled('carrier')) {
            $query->where('carrier', $request->carrier);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('shipped_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('shipped_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('shipped_at', 'desc')->paginate(15);

        // Get unique carriers for filter
        $carriers = Order::whereNotNull('carrier')
            ->whereIn('status', ['shipped', 'delivered'])
            ->distinct()
            ->pluck('carrier')
            ->filter()
            ->sort()
            ->values();

        // Calculate statistics
        $stats = [
            'total_shipped' => Order::where('status', 'shipped')->whereNotNull('tracking_number')->count(),
            'total_delivered' => Order::where('status', 'delivered')->whereNotNull('tracking_number')->count(),
            'in_transit' => Order::where('status', 'shipped')
                ->whereNotNull('tracking_number')
                ->whereNull('delivered_at')
                ->count(),
        ];

        return view('admin.delivery-tracking.index', compact('orders', 'carriers', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'fulfillment']);

        return view('admin.delivery-tracking.show', compact('order'));
    }

    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:255',
            'carrier' => 'nullable|string|max:255',
            'status' => 'required|in:shipped,delivered',
        ]);

        $updateData = [
            'tracking_number' => $request->tracking_number ?? $order->tracking_number,
            'carrier' => $request->carrier ?? $order->carrier,
        ];

        if ($request->status === 'shipped' && $order->status !== 'shipped') {
            $updateData['status'] = 'shipped';
            $updateData['fulfillment_status'] = 'shipped';
            $updateData['shipped_at'] = now();
        } elseif ($request->status === 'delivered' && $order->status !== 'delivered') {
            $updateData['status'] = 'delivered';
            $updateData['fulfillment_status'] = 'delivered';
            $updateData['delivered_at'] = now();
        }

        $order->update($updateData);

        return back()->with('success', 'Tracking information updated successfully.');
    }
}
