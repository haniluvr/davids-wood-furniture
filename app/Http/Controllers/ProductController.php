<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('category');

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%')
                    ->orWhere('sku', 'like', '%'.$request->search.'%');
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'popularity');
        switch ($sortBy) {
            case 'price-low':
                $query->orderBy('price', 'asc');

                break;
            case 'price-high':
                $query->orderBy('price', 'desc');

                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');

                break;
            case 'popularity':
            default:
                // Order by average rating (5 stars first, then 4, 3, 2, 1, 0)
                $query->addSelect([
                    'avg_rating' => \App\Models\ProductReview::selectRaw('COALESCE(AVG(rating), 0)')
                        ->whereColumn('product_id', 'products.id')
                        ->where('is_approved', true),
                ])
                    ->orderBy('avg_rating', 'desc')
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('created_at', 'desc');

                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('products', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $sessionIdAtStart = session()->getId();

        \Log::info('Product show method called', [
            'product_id' => $product->id,
            'product_slug' => $product->slug,
            'session_id_at_start' => $sessionIdAtStart,
            'auth_check' => \Auth::check(),
            'user_id' => \Auth::id(),
            'url' => request()->url(),
            'referer' => request()->header('referer'),
            'route_parameters' => request()->route()->parameters(),
        ]);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->addSelect([
                'avg_rating' => \App\Models\ProductReview::selectRaw('COALESCE(AVG(rating), 0)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('is_approved', true),
            ])
            ->orderBy('avg_rating', 'desc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Load approved reviews with user information
        $reviews = $product->approvedReviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Calculate rating distribution
        $ratingDistribution = [
            5 => $product->approvedReviews()->where('rating', 5)->count(),
            4 => $product->approvedReviews()->where('rating', 4)->count(),
            3 => $product->approvedReviews()->where('rating', 3)->count(),
            2 => $product->approvedReviews()->where('rating', 2)->count(),
            1 => $product->approvedReviews()->where('rating', 1)->count(),
        ];

        $sessionIdAtEnd = session()->getId();

        \Log::info('Product show method completed', [
            'product_id' => $product->id,
            'session_id_at_start' => $sessionIdAtStart,
            'session_id_at_end' => $sessionIdAtEnd,
            'session_changed' => $sessionIdAtStart !== $sessionIdAtEnd,
            'auth_check' => \Auth::check(),
            'user_id' => \Auth::id(),
        ]);

        return view('product.show', compact('product', 'relatedProducts', 'reviews', 'ratingDistribution'));
    }
}
