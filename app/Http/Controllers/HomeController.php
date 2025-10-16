<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->with('category')
            ->addSelect([
                'avg_rating' => \App\Models\ProductReview::selectRaw('COALESCE(AVG(rating), 0)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('is_approved', true)
            ])
            ->orderBy('avg_rating', 'desc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // If no featured products, get all active products
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::where('is_active', true)
                ->with('category')
                ->addSelect([
                    'avg_rating' => \App\Models\ProductReview::selectRaw('COALESCE(AVG(rating), 0)')
                        ->whereColumn('product_id', 'products.id')
                        ->where('is_approved', true)
                ])
                ->orderBy('avg_rating', 'desc')
                ->orderBy('sort_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        }

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
