<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\ReturnRepair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReturnsRepairsController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnRepair::with(['order.user', 'processedBy']);

        // Search filter (RMA number)
        if ($request->filled('search')) {
            $search = trim($request->search);
            // Remove common prefixes/symbols for more flexible searching
            $search = str_replace(['#', 'RMA-', 'rma-'], '', $search);
            $query->where(function ($q) use ($search) {
                $q->where('rma_number', 'like', '%'.$search.'%')
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_number', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('order.user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', '%'.$search.'%')
                            ->orWhere('last_name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $rmas = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Get statistics
        $stats = [
            'requested' => ReturnRepair::where('status', 'requested')->count(),
            'approved' => ReturnRepair::where('status', 'approved')->count(),
            'in_progress' => ReturnRepair::where('status', 'in_progress')->count(),
            'completed' => ReturnRepair::where('status', 'completed')->count(),
            'rejected' => ReturnRepair::where('status', 'rejected')->count(),
        ];

        return view('admin.orders.returns-repairs', compact('rmas', 'stats'));
    }

    public function show(ReturnRepair $returnRepair)
    {
        $returnRepair->load(['order.user', 'order.orderItems.product', 'processedBy']);

        return view('admin.orders.returns-repairs-detail', compact('returnRepair'));
    }

    public function create()
    {
        return view('admin.orders.returns-repairs-create');
    }

    public function searchOrders(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $orders = Order::with('user')
            ->where(function ($q) use ($query) {
                $q->where('order_number', 'like', '%'.$query.'%')
                    ->orWhereHas('user', function ($userQuery) use ($query) {
                        $userQuery->where('first_name', 'like', '%'.$query.'%')
                            ->orWhere('last_name', 'like', '%'.$query.'%')
                            ->orWhere('email', 'like', '%'.$query.'%');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => ($order->user->first_name ?? 'Guest').' '.($order->user->last_name ?? ''),
                    'customer_email' => $order->user->email ?? 'No email',
                    'order_date' => $order->created_at->format('M d, Y'),
                    'order_total' => number_format($order->total_amount, 2),
                ];
            });

        return response()->json($orders);
    }

    public function edit(ReturnRepair $returnRepair)
    {
        $returnRepair->load(['order.user', 'order.orderItems.product']);
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.orders.returns-repairs-edit', compact('returnRepair', 'orders'));
    }

    public function update(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'type' => 'required|in:return,repair,exchange',
            'reason' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
            'products' => 'required|array',
            'products.*' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'customer_notes' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ]);

        DB::transaction(function () use ($request, $returnRepair) {
            $updateData = [
                'order_id' => $request->order_id,
                'user_id' => Order::find($request->order_id)->user_id,
                'type' => $request->type,
                'reason' => $request->reason,
                'description' => $request->description,
                'products' => $request->products,
                'customer_notes' => $request->customer_notes,
                'admin_notes' => $request->admin_notes,
            ];

            // Handle photo uploads if new photos are provided
            if ($request->hasFile('photos')) {
                $photoPaths = $returnRepair->photos ?? [];
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('returns-photos', 'public');
                    $photoPaths[] = $path;
                }
                $updateData['photos'] = $photoPaths;
            }

            $returnRepair->update($updateData);
        });

        return redirect()->to(admin_route('orders.returns-repairs.index'))
            ->with('success', 'Return/Repair request updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'type' => 'required|in:return,repair,exchange',
            'reason' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
            'products' => 'required|array',
            'products.*' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'customer_notes' => 'nullable|string|max:1000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $returnRepair = ReturnRepair::create([
                'rma_number' => ReturnRepair::generateRmaNumber(),
                'order_id' => $request->order_id,
                'user_id' => Order::find($request->order_id)->user_id,
                'type' => $request->type,
                'status' => 'requested',
                'reason' => $request->reason,
                'description' => $request->description,
                'products' => $request->products,
                'customer_notes' => $request->customer_notes,
            ]);

            // Handle photo uploads
            if ($request->hasFile('photos')) {
                $photoPaths = [];
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('returns-photos', 'public');
                    $photoPaths[] = $path;
                }
                $returnRepair->update(['photos' => $photoPaths]);
            }

            // Update order return status
            Order::find($request->order_id)->update(['return_status' => 'requested']);

            // Log return/repair creation
            AuditLog::log('return_repair.created', Auth::guard('admin')->user(), $returnRepair, [], [], "Created {$returnRepair->type} request {$returnRepair->rma_number} for order {$returnRepair->order->order_number}");
        });

        return redirect()->to(admin_route('orders.returns-repairs.index'))
            ->with('success', 'Return/Repair request created successfully.');
    }

    public function approve(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $returnRepair->status;
        $returnRepair->update([
            'status' => 'approved',
            'approved_at' => now(),
            'processed_by' => auth('admin')->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        $returnRepair->order->update(['return_status' => 'approved']);

        // Log approval
        AuditLog::log('return_repair.approved', Auth::guard('admin')->user(), $returnRepair, ['status' => $oldStatus], ['status' => 'approved'], "Approved {$returnRepair->type} request {$returnRepair->rma_number}");

        return response()->json([
            'success' => true,
            'message' => 'Return/Repair request approved successfully',
        ]);
    }

    public function reject(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $oldStatus = $returnRepair->status;
        $returnRepair->update([
            'status' => 'rejected',
            'processed_by' => auth('admin')->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        $returnRepair->order->update(['return_status' => 'none']);

        // Log rejection
        AuditLog::log('return_repair.rejected', Auth::guard('admin')->user(), $returnRepair, ['status' => $oldStatus], ['status' => 'rejected'], "Rejected {$returnRepair->type} request {$returnRepair->rma_number}. Notes: {$request->admin_notes}");

        return response()->json([
            'success' => true,
            'message' => 'Return/Repair request rejected',
        ]);
    }

    public function markReceived(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $returnRepair->status;
        $returnRepair->update([
            'status' => 'received',
            'received_at' => now(),
            'processed_by' => auth('admin')->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        $returnRepair->order->update(['return_status' => 'received']);

        // Log received status
        AuditLog::log('return_repair.received', Auth::guard('admin')->user(), $returnRepair, ['status' => $oldStatus], ['status' => 'received'], "Marked {$returnRepair->type} request {$returnRepair->rma_number} as received");

        return response()->json([
            'success' => true,
            'message' => 'Return/Repair marked as received',
        ]);
    }

    public function processRefund(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0',
            'refund_method' => 'required|string|max:100',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $returnRepair->status;
        $returnRepair->update([
            'status' => 'refunded',
            'refund_amount' => $request->refund_amount,
            'refund_method' => $request->refund_method,
            'completed_at' => now(),
            'processed_by' => auth('admin')->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        $returnRepair->order->update(['return_status' => 'completed']);

        // Log refund processing
        AuditLog::log('return_repair.refund_processed', Auth::guard('admin')->user(), $returnRepair, ['status' => $oldStatus], ['status' => 'refunded', 'refund_amount' => $request->refund_amount], "Processed refund of ₱{$request->refund_amount} for {$returnRepair->type} request {$returnRepair->rma_number}");

        return response()->json([
            'success' => true,
            'message' => 'Refund processed successfully',
        ]);
    }

    public function markCompleted(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $returnRepair->status;
        $returnRepair->update([
            'status' => 'completed',
            'completed_at' => now(),
            'processed_by' => auth('admin')->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        $returnRepair->order->update(['return_status' => 'completed']);

        // Log completion
        AuditLog::log('return_repair.completed', Auth::guard('admin')->user(), $returnRepair, ['status' => $oldStatus], ['status' => 'completed'], "Marked {$returnRepair->type} request {$returnRepair->rma_number} as completed");

        return response()->json([
            'success' => true,
            'message' => 'Return/Repair marked as completed',
        ]);
    }

    public function updateNotes(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $returnRepair->update([
            'admin_notes' => $request->admin_notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notes updated successfully',
        ]);
    }

    public function uploadPhotos(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'photos' => 'required|array|max:5',
            'photos.*' => 'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ]);

        $photoPaths = $returnRepair->photos ?? [];

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('returns-photos', 'public');
            $photoPaths[] = $path;
        }

        $returnRepair->update(['photos' => $photoPaths]);

        return response()->json([
            'success' => true,
            'message' => 'Photos uploaded successfully',
            'photos' => $photoPaths,
        ]);
    }

    public function deletePhoto(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'photo_path' => 'required|string',
        ]);

        $photos = $returnRepair->photos ?? [];
        $photoIndex = array_search($request->photo_path, $photos);

        if ($photoIndex !== false) {
            // Delete file from storage
            Storage::disk('public')->delete($request->photo_path);

            // Remove from array
            unset($photos[$photoIndex]);
            $photos = array_values($photos); // Re-index array

            $returnRepair->update(['photos' => $photos]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Photo deleted successfully',
        ]);
    }
}
