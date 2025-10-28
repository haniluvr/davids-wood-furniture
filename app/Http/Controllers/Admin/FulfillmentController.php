<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FulfillmentController extends Controller
{
    public function index()
    {
        // Get orders ready to ship (status: processing or pending)
        $orders = Order::with(['user', 'orderItems.product', 'fulfillment'])
            ->whereIn('status', ['processing', 'pending'])
            ->where('fulfillment_status', '!=', 'shipped')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

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
            'carrier' => 'required|string|max:100',
            'tracking_numbers' => 'required|array',
            'tracking_numbers.*' => 'string|max:100',
        ]);

        $orderIds = $request->order_ids;
        $carrier = $request->carrier;
        $trackingNumbers = $request->tracking_numbers;

        DB::transaction(function () use ($orderIds, $carrier, $trackingNumbers) {
            foreach ($orderIds as $index => $orderId) {
                $order = Order::findOrFail($orderId);
                $trackingNumber = $trackingNumbers[$index] ?? null;

                if ($trackingNumber) {
                    $fulfillment = $order->fulfillment()->firstOrCreate([]);

                    $fulfillment->update([
                        'shipped' => true,
                        'shipped_at' => now(),
                        'shipped_by' => auth('admin')->id(),
                        'carrier' => $carrier,
                        'tracking_number' => $trackingNumber,
                    ]);

                    $order->update([
                        'status' => 'shipped',
                        'fulfillment_status' => 'shipped',
                        'shipped_at' => now(),
                        'carrier' => $carrier,
                        'tracking_number' => $trackingNumber,
                    ]);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => count($orderIds).' orders marked as shipped successfully',
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
