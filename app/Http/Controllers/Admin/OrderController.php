<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])
            ->orderByStatusPriority();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', '%'.$search.'%')
                            ->orWhere('last_name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Payment status filter
        if ($request->has('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(15);

        // Get statistics for the view
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $users = User::orderBy('first_name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.orders.create', compact('users', 'products'));
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'billing_address' => 'required|array',
            'shipping_address' => 'required|array',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $unitPrice = $product->price;
                $totalPrice = $unitPrice * $quantity;

                $subtotal += $totalPrice;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'product_data' => [
                        'name' => $product->name,
                        'image' => $product->image,
                        'category' => $product->category->name ?? null,
                    ],
                ];
            }

            $taxAmount = $subtotal * 0.1; // 10% tax
            $shippingCost = $subtotal > 5000 ? 0 : 150; // Free shipping over ₱5000
            $discountAmount = 0; // No discounts for now
            $totalAmount = $subtotal + $taxAmount + $shippingCost - $discountAmount;

            // Create order
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'order_number' => 'ORD-'.strtoupper(uniqid()),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => 'PHP',
                'billing_address' => $validated['billing_address'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'shipping_method' => $shippingCost > 0 ? 'standard' : 'free',
                'notes' => $validated['notes'],
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->orderItems()->create($item);
            }

            // Update product stock
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $product->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit();

            // Fire order created event
            event(new OrderCreated($order));

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order created successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => 'Failed to create order: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        $users = User::orderBy('first_name')->get();

        return view('admin.orders.edit', compact('order', 'users'));
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,returned',
            'payment_status' => 'required|in:pending,paid,refunded,failed',
            'shipping_method' => 'nullable|string',
            'tracking_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'billing_address' => 'required|array',
            'shipping_address' => 'required|array',
        ]);

        // Handle status changes
        if ($validated['status'] === 'shipped' && $order->status !== 'shipped') {
            $validated['shipped_at'] = now();
        }

        if ($validated['status'] === 'delivered' && $order->status !== 'delivered') {
            $validated['delivered_at'] = now();
        }

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order)
    {
        // Only allow deletion of cancelled orders
        if ($order->status !== 'cancelled') {
            return back()->withErrors(['error' => 'Only cancelled orders can be deleted.']);
        }

        DB::beginTransaction();
        try {
            // Restore product stock if order was not delivered
            if ($order->status !== 'delivered') {
                foreach ($order->orderItems as $item) {
                    if ($item->product) {
                        $item->product->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            $order->orderItems()->delete();
            $order->delete();

            DB::commit();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Order deleted successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => 'Failed to delete order: '.$e->getMessage()]);
        }
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,returned',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Handle status-specific logic
        $updateData = ['status' => $newStatus];

        if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
            $updateData['shipped_at'] = now();
        }

        if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            $updateData['delivered_at'] = now();
        }

        $order->update($updateData);

        // Fire order status changed event
        event(new OrderStatusChanged($order, $oldStatus, $newStatus));

        return back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Generate and download invoice PDF.
     */
    public function downloadInvoice(Order $order)
    {
        $order->load(['user', 'items.product']);

        $pdf = Pdf::loadView('admin.orders.pdf.invoice-pdf', compact('order'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
            ]);

        return $pdf->download('invoice-'.$order->order_number.'.pdf');
    }

    /**
     * Generate and download packing slip PDF.
     */
    public function downloadPackingSlip(Order $order)
    {
        $order->load(['user', 'items.product']);

        $pdf = Pdf::loadView('admin.orders.pdf.packing-slip-pdf', compact('order'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
            ]);

        return $pdf->download('packing-slip-'.$order->order_number.'.pdf');
    }

    /**
     * Process refund for an order.
     */
    public function processRefund(Request $request, Order $order)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0|max:'.$order->total_amount,
            'refund_reason' => 'required|string|max:500',
        ]);

        // Here you would integrate with your payment processor
        // For now, we'll just update the order status

        $order->update([
            'payment_status' => 'refunded',
            'status' => 'returned',
            'notes' => ($order->notes ? $order->notes."\n\n" : '').
                      'Refund processed: ₱'.$request->refund_amount.
                      "\nReason: ".$request->refund_reason.
                      "\nProcessed at: ".now()->format('Y-m-d H:i:s'),
        ]);

        return back()->with('success', 'Refund processed successfully.');
    }

    /**
     * Display orders pending approval.
     */
    public function pendingApproval(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product', 'approvedBy'])
            ->where('requires_approval', true)
            ->whereNull('approved_at')
            ->orderBy('created_at', 'asc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', '%'.$search.'%')
                            ->orWhere('last_name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        $orders = $query->paginate(15);

        // Get statistics
        $stats = [
            'pending_approval' => Order::where('requires_approval', true)->whereNull('approved_at')->count(),
            'approved_today' => Order::where('requires_approval', true)->whereDate('approved_at', today())->count(),
            'total_requiring_approval' => Order::where('requires_approval', true)->count(),
        ];

        return view('admin.orders.pending-approval', compact('orders', 'stats'));
    }

    /**
     * Approve an order.
     */
    public function approveOrder(Request $request, Order $order)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $order->update([
            'requires_approval' => false,
            'approved_at' => now(),
            'approved_by' => auth('admin')->id(),
            'status' => 'processing',
            'admin_notes' => $request->admin_notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order approved successfully',
        ]);
    }

    /**
     * Reject an order.
     */
    public function rejectOrder(Request $request, Order $order)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $order->update([
            'requires_approval' => false,
            'status' => 'cancelled',
            'admin_notes' => $request->admin_notes,
        ]);

        // Restore product stock
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Order rejected successfully',
        ]);
    }

    /**
     * Bulk update order statuses.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $orderIds = $request->order_ids;
        $newStatus = $request->status;

        DB::transaction(function () use ($orderIds, $newStatus) {
            foreach ($orderIds as $orderId) {
                $order = Order::findOrFail($orderId);
                $oldStatus = $order->status;

                $updateData = ['status' => $newStatus];

                if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
                    $updateData['shipped_at'] = now();
                }

                if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
                    $updateData['delivered_at'] = now();
                }

                $order->update($updateData);
                event(new OrderStatusChanged($order, $oldStatus, $newStatus));
            }
        });

        return response()->json([
            'success' => true,
            'message' => count($orderIds).' orders updated successfully',
        ]);
    }

    /**
     * Export orders to CSV.
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Apply same filters as index
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', '%'.$search.'%')
                            ->orWhere('last_name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $filename = 'orders_export_'.now()->format('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Status',
                'Payment Status',
                'Total Amount',
                'Currency',
                'Created At',
                'Items Count',
            ]);

            // CSV data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user ? $order->user->first_name.' '.$order->user->last_name : 'Guest',
                    $order->user ? $order->user->email : 'N/A',
                    $order->status,
                    $order->payment_status,
                    $order->total_amount,
                    $order->currency,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->orderItems->count(),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
