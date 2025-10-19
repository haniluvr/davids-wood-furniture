<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRepair;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReturnsRepairsController extends Controller
{
    public function index()
    {
        $returnsRepairs = ReturnRepair::with(['order.user', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get statistics
        $stats = [
            'requested' => ReturnRepair::where('status', 'requested')->count(),
            'approved' => ReturnRepair::where('status', 'approved')->count(),
            'received' => ReturnRepair::where('status', 'received')->count(),
            'completed' => ReturnRepair::where('status', 'completed')->count(),
            'total_refunded' => ReturnRepair::where('status', 'refunded')->sum('refund_amount'),
        ];

        return view('admin.orders.returns-repairs', compact('returnsRepairs', 'stats'));
    }

    public function show(ReturnRepair $returnRepair)
    {
        $returnRepair->load(['order.user', 'order.orderItems.product', 'processedBy']);
        
        return view('admin.orders.returns-repairs-detail', compact('returnRepair'));
    }

    public function create()
    {
        $orders = Order::with('user')->where('status', 'delivered')->orderBy('created_at', 'desc')->get();
        
        return view('admin.orders.returns-repairs-create', compact('orders'));
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
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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
        });

        return redirect()->route('admin.orders.returns-repairs.index')
            ->with('success', 'Return/Repair request created successfully.');
    }

    public function approve(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $returnRepair->update([
            'status' => 'approved',
            'approved_at' => now(),
            'processed_by' => auth('admin')->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        $returnRepair->order->update(['return_status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Return/Repair request approved successfully'
        ]);
    }

    public function reject(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $returnRepair->update([
            'status' => 'rejected',
            'processed_by' => auth('admin')->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        $returnRepair->order->update(['return_status' => 'none']);

        return response()->json([
            'success' => true,
            'message' => 'Return/Repair request rejected'
        ]);
    }

    public function markReceived(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $returnRepair->update([
            'status' => 'received',
            'received_at' => now(),
            'processed_by' => auth('admin')->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        $returnRepair->order->update(['return_status' => 'received']);

        return response()->json([
            'success' => true,
            'message' => 'Return/Repair marked as received'
        ]);
    }

    public function processRefund(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0',
            'refund_method' => 'required|string|max:100',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $returnRepair->update([
            'status' => 'refunded',
            'refund_amount' => $request->refund_amount,
            'refund_method' => $request->refund_method,
            'completed_at' => now(),
            'processed_by' => auth('admin')->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        $returnRepair->order->update(['return_status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Refund processed successfully'
        ]);
    }

    public function markCompleted(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $returnRepair->update([
            'status' => 'completed',
            'completed_at' => now(),
            'processed_by' => auth('admin')->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        $returnRepair->order->update(['return_status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Return/Repair marked as completed'
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
            'message' => 'Notes updated successfully'
        ]);
    }

    public function uploadPhotos(Request $request, ReturnRepair $returnRepair)
    {
        $request->validate([
            'photos' => 'required|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'photos' => $photoPaths
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
            'message' => 'Photo deleted successfully'
        ]);
    }
}
