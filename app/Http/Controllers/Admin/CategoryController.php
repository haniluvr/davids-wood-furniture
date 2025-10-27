<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Get subcategories for a given category.
     */
    public function getSubcategories(Category $category)
    {
        $subcategories = $category->children()
            ->where('is_active', true)
            ->orderBy('category_order')
            ->get(['id', 'name']);

        return response()->json($subcategories);
    }
}
