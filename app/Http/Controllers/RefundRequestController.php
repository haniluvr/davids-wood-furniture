<?php

namespace App\Http\Controllers;

use App\Events\NewRefundRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRepair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RefundRequestController extends Controller
{
    /**
     * Store a new refund request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to submit a refund request',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'order_item_id' => 'required|exists:order_items,id',
            'reason' => 'required|string|in:defective,item_not_as_described,item_does_not_fit,quality_issues,customer_dissatisfaction,wrong_item,other',
            'description' => 'required|string|min:10|max:1000',
            'customer_notes' => 'nullable|string|max:1000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verify that the order belongs to the user
        $order = Order::where('id', $request->order_id)
            ->where('user_id', $user->id)
            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or does not belong to you',
            ], 404);
        }

        // Verify order status allows refund requests
        if (! in_array($order->status, ['delivered', 'shipped', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Refund requests can only be submitted for delivered, shipped, or processing orders',
            ], 403);
        }

        // Verify that the order item belongs to the order
        $orderItem = OrderItem::where('id', $request->order_item_id)
            ->where('order_id', $order->id)
            ->first();

        if (! $orderItem) {
            return response()->json([
                'success' => false,
                'message' => 'Order item not found or does not belong to this order',
            ], 404);
        }

        // Check if a refund request already exists for this order item
        $existingRequest = ReturnRepair::where('order_id', $order->id)
            ->where('user_id', $user->id)
            ->whereJsonContains('products', [['product_id' => $orderItem->product_id]])
            ->whereIn('status', ['requested', 'approved', 'received', 'processing'])
            ->first();

        if ($existingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'A refund request already exists for this item',
            ], 409);
        }

        try {
            DB::beginTransaction();

            // Handle photo uploads using dynamic storage (S3 for production, local for local)
            $photoPaths = [];
            if ($request->hasFile('photos')) {
                $disk = Storage::getDynamicDisk();
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('returns-photos', $disk);
                    $photoPaths[] = $path;
                }
            }

            // Create refund request
            $returnRepair = ReturnRepair::create([
                'rma_number' => ReturnRepair::generateRmaNumber(),
                'order_id' => $order->id,
                'user_id' => $user->id,
                'type' => 'return',
                'status' => 'requested',
                'reason' => $request->reason,
                'description' => $request->description,
                'products' => [
                    [
                        'product_id' => $orderItem->product_id,
                        'quantity' => $orderItem->quantity,
                    ],
                ],
                'customer_notes' => $request->customer_notes,
                'photos' => ! empty($photoPaths) ? $photoPaths : null,
            ]);

            // Update order return status
            $order->update(['return_status' => 'requested']);

            // Load order relationship for event
            $returnRepair->load('order.user');

            // Fire new refund request event to notify admins
            event(new NewRefundRequest($returnRepair));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund request submitted successfully. Your request is being reviewed.',
                'rma_number' => $returnRepair->rma_number,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Failed to create refund request', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your refund request. Please try again.',
            ], 500);
        }
    }
}
