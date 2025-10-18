<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\ProductReview;
use Illuminate\Support\Facades\DB;

class BulkActionController extends Controller
{
    public function products(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,update_category,export',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $productIds = $request->product_ids;
        $action = $request->action;

        try {
            DB::beginTransaction();

            switch ($action) {
                case 'delete':
                    Product::whereIn('id', $productIds)->delete();
                    $message = count($productIds) . ' products deleted successfully.';
                    break;

                case 'activate':
                    Product::whereIn('id', $productIds)->update(['is_active' => true]);
                    $message = count($productIds) . ' products activated successfully.';
                    break;

                case 'deactivate':
                    Product::whereIn('id', $productIds)->update(['is_active' => false]);
                    $message = count($productIds) . ' products deactivated successfully.';
                    break;

                case 'update_category':
                    $request->validate(['category_id' => 'required|exists:categories,id']);
                    Product::whereIn('id', $productIds)->update(['category_id' => $request->category_id]);
                    $message = count($productIds) . ' products updated with new category.';
                    break;

                case 'export':
                    return $this->exportProducts($productIds);
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }

    public function orders(Request $request)
    {
        $request->validate([
            'action' => 'required|in:update_status,export,delete',
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $orderIds = $request->order_ids;
        $action = $request->action;

        try {
            DB::beginTransaction();

            switch ($action) {
                case 'update_status':
                    $request->validate(['status' => 'required|in:pending,processing,shipped,delivered,cancelled']);
                    Order::whereIn('id', $orderIds)->update(['status' => $request->status]);
                    $message = count($orderIds) . ' orders updated to ' . $request->status . ' status.';
                    break;

                case 'export':
                    return $this->exportOrders($orderIds);

                case 'delete':
                    Order::whereIn('id', $orderIds)->delete();
                    $message = count($orderIds) . ' orders deleted successfully.';
                    break;
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }

    public function users(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,export,send_email',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $request->user_ids;
        $action = $request->action;

        try {
            DB::beginTransaction();

            switch ($action) {
                case 'activate':
                    User::whereIn('id', $userIds)->update(['is_active' => true]);
                    $message = count($userIds) . ' users activated successfully.';
                    break;

                case 'deactivate':
                    User::whereIn('id', $userIds)->update(['is_active' => false]);
                    $message = count($userIds) . ' users deactivated successfully.';
                    break;

                case 'delete':
                    User::whereIn('id', $userIds)->delete();
                    $message = count($userIds) . ' users deleted successfully.';
                    break;

                case 'export':
                    return $this->exportUsers($userIds);

                case 'send_email':
                    $request->validate([
                        'email_subject' => 'required|string|max:255',
                        'email_body' => 'required|string'
                    ]);
                    
                    $users = User::whereIn('id', $userIds)->get();
                    foreach ($users as $user) {
                        // Send email notification
                        $user->notify(new \App\Notifications\CustomNotification(
                            $request->email_subject,
                            $request->email_body
                        ));
                    }
                    $message = 'Email sent to ' . count($userIds) . ' users successfully.';
                    break;
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }

    public function reviews(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete,export',
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:product_reviews,id'
        ]);

        $reviewIds = $request->review_ids;
        $action = $request->action;

        try {
            DB::beginTransaction();

            switch ($action) {
                case 'approve':
                    ProductReview::whereIn('id', $reviewIds)->update(['is_approved' => true]);
                    $message = count($reviewIds) . ' reviews approved successfully.';
                    break;

                case 'reject':
                    ProductReview::whereIn('id', $reviewIds)->update(['is_approved' => false]);
                    $message = count($reviewIds) . ' reviews rejected successfully.';
                    break;

                case 'delete':
                    ProductReview::whereIn('id', $reviewIds)->delete();
                    $message = count($reviewIds) . ' reviews deleted successfully.';
                    break;

                case 'export':
                    return $this->exportReviews($reviewIds);
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }

    private function exportProducts($productIds)
    {
        $products = Product::with(['category'])->whereIn('id', $productIds)->get();
        
        $filename = 'products_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'Name', 'SKU', 'Price', 'Stock', 'Category', 'Status', 'Created At'
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->price,
                    $product->stock_quantity,
                    $product->category ? $product->category->name : 'N/A',
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportOrders($orderIds)
    {
        $orders = Order::with(['user', 'items.product'])->whereIn('id', $orderIds)->get();
        
        $filename = 'orders_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Order ID', 'Customer', 'Status', 'Total', 'Items', 'Created At'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user ? $order->user->name : 'Guest',
                    $order->status,
                    $order->total_amount,
                    $order->items->count(),
                    $order->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportUsers($userIds)
    {
        $users = User::whereIn('id', $userIds)->get();
        
        $filename = 'users_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'Username', 'Email', 'Name', 'Status', 'Created At'
            ]);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->username,
                    $user->email,
                    $user->name,
                    $user->is_active ? 'Active' : 'Inactive',
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportReviews($reviewIds)
    {
        $reviews = ProductReview::with(['user', 'product'])->whereIn('id', $reviewIds)->get();
        
        $filename = 'reviews_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($reviews) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'Product', 'User', 'Rating', 'Review', 'Status', 'Created At'
            ]);

            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->id,
                    $review->product ? $review->product->name : 'N/A',
                    $review->user ? $review->user->name : 'Anonymous',
                    $review->rating,
                    $review->review,
                    $review->is_approved ? 'Approved' : 'Pending',
                    $review->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
