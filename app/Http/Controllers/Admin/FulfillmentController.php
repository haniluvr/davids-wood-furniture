<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FulfillmentController extends Controller
{
    public function index(Request $request)
    {
        // Get all orders with fulfillment information
        $query = Order::with(['user', 'orderItems.product', 'fulfillment'])
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && ! empty($request->search)) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', '%'.$search.'%')
                            ->orWhere('last_name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        // Status filter - handle both 'pending' and 'pending_packing' for compatibility
        if ($request->has('status') && ! empty($request->status)) {
            $status = $request->status;
            // Map 'pending_packing' to 'pending' since the DB uses 'pending'
            if ($status === 'pending_packing') {
                $status = 'pending';
            }
            $query->where('fulfillment_status', $status);
        }

        $orders = $query->paginate(20)->withQueryString();

        // Get fulfillment statistics
        $stats = [
            'pending_packing' => Order::where('fulfillment_status', 'pending')->count(),
            'packed' => Order::where('fulfillment_status', 'packed')->count(),
            'shipped' => Order::where('fulfillment_status', 'shipped')->count(),
            'delivered' => Order::where('fulfillment_status', 'delivered')->count(),
        ];

        return view('admin.orders.fulfillment', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'fulfillment']);

        // Get or create fulfillment record
        $fulfillment = $order->fulfillment()->firstOrCreate([]);

        return view('admin.orders.fulfillment-detail', compact('order', 'fulfillment'));
    }

    public function updatePackingStatus(Request $request, Order $order)
    {
        $request->validate([
            'items_packed' => 'required|boolean',
            'packing_notes' => 'nullable|string|max:1000',
        ]);

        $fulfillment = $order->fulfillment()->firstOrCreate([]);

        $fulfillment->update([
            'items_packed' => $request->items_packed,
            'packing_notes' => $request->packing_notes,
            'packed_at' => $request->items_packed ? now() : null,
            'packed_by' => $request->items_packed ? auth('admin')->id() : null,
        ]);

        // Update order fulfillment status
        if ($request->items_packed) {
            $order->update(['fulfillment_status' => 'packed']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Packing status updated successfully',
            'fulfillment' => $fulfillment->fresh(),
        ]);
    }

    public function updateShippingStatus(Request $request, Order $order)
    {
        $request->validate([
            'label_printed' => 'required|boolean',
            'shipped' => 'required|boolean',
            'carrier' => 'required_if:shipped,true|string|max:100',
            'tracking_number' => 'required_if:shipped,true|string|max:100',
            'shipping_notes' => 'nullable|string|max:1000',
        ]);

        $fulfillment = $order->fulfillment()->firstOrCreate([]);

        $updateData = [
            'label_printed' => $request->label_printed,
            'shipped' => $request->shipped,
            'shipping_notes' => $request->shipping_notes,
        ];

        if ($request->shipped) {
            $updateData['shipped_at'] = now();
            $updateData['shipped_by'] = auth('admin')->id();
            $updateData['carrier'] = $request->carrier;
            $updateData['tracking_number'] = $request->tracking_number;

            // Update order status and fulfillment status
            $order->update([
                'status' => 'shipped',
                'fulfillment_status' => 'shipped',
                'shipped_at' => now(),
                'carrier' => $request->carrier,
                'tracking_number' => $request->tracking_number,
            ]);
        }

        $fulfillment->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Shipping status updated successfully',
            'fulfillment' => $fulfillment->fresh(),
        ]);
    }

    public function bulkMarkShipped(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'carrier' => 'nullable|string|max:100',
            'tracking_numbers' => 'nullable|array',
            'tracking_numbers.*' => 'nullable|string|max:100',
        ]);

        $orderIds = $request->order_ids;
        $carrier = $request->carrier ?? 'Not specified';
        $trackingNumbers = $request->tracking_numbers ?? [];

        $updatedCount = 0;

        DB::transaction(function () use ($orderIds, $carrier, $trackingNumbers, &$updatedCount) {
            foreach ($orderIds as $index => $orderId) {
                $order = Order::findOrFail($orderId);

                // Skip if already shipped
                if ($order->fulfillment_status === 'shipped') {
                    continue;
                }

                $trackingNumber = $trackingNumbers[$index] ?? null;

                $fulfillment = $order->fulfillment()->firstOrCreate([]);

                $fulfillmentData = [
                    'shipped' => true,
                    'shipped_at' => now(),
                    'shipped_by' => auth('admin')->id(),
                    'carrier' => $carrier,
                ];

                if ($trackingNumber) {
                    $fulfillmentData['tracking_number'] = $trackingNumber;
                }

                $fulfillment->update($fulfillmentData);

                $orderData = [
                    'status' => 'shipped',
                    'fulfillment_status' => 'shipped',
                    'shipped_at' => now(),
                    'carrier' => $carrier,
                ];

                if ($trackingNumber) {
                    $orderData['tracking_number'] = $trackingNumber;
                }

                $order->update($orderData);
                $updatedCount++;
            }
        });

        return response()->json([
            'success' => true,
            'message' => $updatedCount.' order'.($updatedCount !== 1 ? 's' : '').' marked as shipped successfully',
        ]);
    }

    public function printLabel(Order $order)
    {
        $order->load(['user', 'orderItems.product']);

        // Generate tracking number if not exists
        if (! $order->tracking_number) {
            $order->update(['tracking_number' => $order->generateTrackingNumber()]);
        }

        return view('admin.orders.shipping-label', compact('order'));
    }
}
