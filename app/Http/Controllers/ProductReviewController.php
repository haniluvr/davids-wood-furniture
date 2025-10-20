<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to submit a review',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verify that the user actually purchased this product in this order
        $order = Order::where('id', $request->order_id)
            ->where('user_id', $user->id)
            ->whereHas('orderItems', function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'You can only review products you have purchased',
            ], 403);
        }

        // Check if review already exists
        $existingReview = ProductReview::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->where('order_id', $request->order_id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product for this order',
            ], 409);
        }

        // Create the review
        $review = ProductReview::create([
            'product_id' => $request->product_id,
            'user_id' => $user->id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'review' => $request->review,
            'is_verified_purchase' => true,
            'is_approved' => false, // Requires approval
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your review has been submitted and is pending approval.',
            'review' => $review,
        ]);
    }

    /**
     * Get reviews for a product
     */
    public function index($productId)
    {
        $product = Product::findOrFail($productId);

        $reviews = ProductReview::where('product_id', $productId)
            ->where('is_approved', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
            'average_rating' => $product->average_rating,
            'total_reviews' => $product->reviews_count,
        ]);
    }
}
