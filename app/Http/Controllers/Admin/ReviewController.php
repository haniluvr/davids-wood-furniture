<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductReview::with(['product', 'user', 'order']);

        // Calculate statistics
        $totalReviews = ProductReview::count();
        $averageRating = ProductReview::avg('rating') ?? 0;
        $pendingCount = ProductReview::where('is_approved', false)->count();
        $approvedCount = ProductReview::where('is_approved', true)->count();

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('review', 'like', '%'.$request->search.'%')
                    ->orWhereHas('product', function ($productQuery) use ($request) {
                        $productQuery->where('name', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('first_name', 'like', '%'.$request->search.'%')
                            ->orWhere('last_name', 'like', '%'.$request->search.'%')
                            ->orWhere('email', 'like', '%'.$request->search.'%');
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->where('is_approved', false);

                    break;
                case 'approved':
                    $query->where('is_approved', true);

                    break;
                case 'verified':
                    $query->where('is_verified_purchase', true);

                    break;
                case 'unverified':
                    $query->where('is_verified_purchase', false);

                    break;
            }
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Product filter
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $reviews = $query->paginate(15)->withQueryString();

        // Get products for filter dropdown
        $products = \App\Models\Product::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('admin.reviews.index', compact('reviews', 'products', 'totalReviews', 'averageRating', 'pendingCount', 'approvedCount'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductReview $review)
    {
        $review->load(['product', 'user', 'order', 'respondedBy']);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve a review.
     */
    public function approve(ProductReview $review)
    {
        $oldValues = $review->toArray();

        $review->update([
            'is_approved' => true,
        ]);

        // Log the action
        AuditLog::logUpdate(Auth::guard('admin')->user(), $review, $oldValues);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Review approved successfully.']);
        }

        return redirect()->back()
            ->with('success', 'Review approved successfully.');
    }

    /**
     * Reject a review.
     */
    public function reject(ProductReview $review)
    {
        $oldValues = $review->toArray();

        $review->update([
            'is_approved' => false,
        ]);

        // Log the action
        AuditLog::logUpdate(Auth::guard('admin')->user(), $review, $oldValues);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Review rejected successfully.']);
        }

        return redirect()->back()
            ->with('success', 'Review rejected successfully.');
    }

    /**
     * Add admin response to a review.
     */
    public function respond(Request $request, ProductReview $review)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string|max:1000',
        ]);

        $oldValues = $review->toArray();

        $review->update([
            'admin_response' => $validated['admin_response'],
            'responded_by' => Auth::guard('admin')->id(),
            'responded_at' => now(),
        ]);

        // Log the action
        AuditLog::logUpdate(Auth::guard('admin')->user(), $review, $oldValues);

        return redirect()->back()
            ->with('success', 'Response added successfully.');
    }

    /**
     * Update admin response.
     */
    public function updateResponse(Request $request, ProductReview $review)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string|max:1000',
        ]);

        $oldValues = $review->toArray();

        $review->update([
            'admin_response' => $validated['admin_response'],
            'responded_by' => Auth::guard('admin')->id(),
            'responded_at' => now(),
        ]);

        // Log the action
        AuditLog::logUpdate(Auth::guard('admin')->user(), $review, $oldValues);

        return redirect()->back()
            ->with('success', 'Response updated successfully.');
    }

    /**
     * Remove admin response.
     */
    public function removeResponse(ProductReview $review)
    {
        $oldValues = $review->toArray();

        $review->update([
            'admin_response' => null,
            'responded_by' => null,
            'responded_at' => null,
        ]);

        // Log the action
        AuditLog::logUpdate(Auth::guard('admin')->user(), $review, $oldValues);

        return redirect()->back()
            ->with('success', 'Response removed successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReview $review)
    {
        // Log the action before deletion
        AuditLog::logDelete(Auth::guard('admin')->user(), $review);

        $review->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Review deleted successfully.']);
        }

        return redirect()->to(admin_route('reviews.index'))
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Bulk approve reviews.
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array|min:1',
            'review_ids.*' => 'exists:product_reviews,id',
        ]);

        $reviews = ProductReview::whereIn('id', $validated['review_ids'])->get();

        foreach ($reviews as $review) {
            $oldValues = $review->toArray();
            $review->update(['is_approved' => true]);
            AuditLog::logUpdate(Auth::guard('admin')->user(), $review, $oldValues);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => count($reviews).' reviews approved successfully.']);
        }

        return redirect()->back()
            ->with('success', count($reviews).' reviews approved successfully.');
    }

    /**
     * Bulk reject reviews.
     */
    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array|min:1',
            'review_ids.*' => 'exists:product_reviews,id',
        ]);

        $reviews = ProductReview::whereIn('id', $validated['review_ids'])->get();

        foreach ($reviews as $review) {
            $oldValues = $review->toArray();
            $review->update(['is_approved' => false]);
            AuditLog::logUpdate(Auth::guard('admin')->user(), $review, $oldValues);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => count($reviews).' reviews rejected successfully.']);
        }

        return redirect()->back()
            ->with('success', count($reviews).' reviews rejected successfully.');
    }

    /**
     * Bulk delete reviews.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array|min:1',
            'review_ids.*' => 'exists:product_reviews,id',
        ]);

        $reviews = ProductReview::whereIn('id', $validated['review_ids'])->get();

        foreach ($reviews as $review) {
            AuditLog::logDelete(Auth::guard('admin')->user(), $review);
            $review->delete();
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => count($reviews).' reviews deleted successfully.']);
        }

        return redirect()->back()
            ->with('success', count($reviews).' reviews deleted successfully.');
    }

    /**
     * Export reviews.
     */
    public function export(Request $request)
    {
        $query = ProductReview::with(['product', 'user']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('review', 'like', '%'.$request->search.'%')
                    ->orWhereHas('product', function ($productQuery) use ($request) {
                        $productQuery->where('name', 'like', '%'.$request->search.'%');
                    });
            });
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->where('is_approved', false);

                    break;
                case 'approved':
                    $query->where('is_approved', true);

                    break;
            }
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->get();

        $filename = 'reviews-export-'.now()->format('Y-m-d-H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($reviews) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID', 'Product', 'Customer', 'Rating', 'Title', 'Review',
                'Verified Purchase', 'Approved', 'Helpful Count', 'Admin Response',
                'Created At', 'Updated At',
            ]);

            // CSV data
            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->id,
                    $review->product ? $review->product->name : 'Deleted Product',
                    $review->user ? ($review->user->first_name.' '.$review->user->last_name) : 'Deleted User',
                    $review->rating,
                    $review->title,
                    $review->review,
                    $review->is_verified_purchase ? 'Yes' : 'No',
                    $review->is_approved ? 'Yes' : 'No',
                    $review->helpful_count,
                    $review->admin_response,
                    $review->created_at->format('Y-m-d H:i:s'),
                    $review->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
