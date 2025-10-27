<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category']);

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('sku', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'low_stock') {
                $query->whereRaw('stock_quantity <= low_stock_threshold');
            } elseif ($request->status === 'out_of_stock') {
                $query->where('stock_quantity', 0);
            }
        }

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Material filter
        if ($request->filled('material') && $request->material !== 'all') {
            $query->where('material', $request->material);
        }

        // Stock level filter
        if ($request->filled('stock_level')) {
            switch ($request->stock_level) {
                case 'in_stock':
                    $query->where('stock_quantity', '>', 0);
                    break;
                case 'low_stock':
                    $query->whereRaw('stock_quantity <= low_stock_threshold AND stock_quantity > 0');
                    break;
                case 'out_of_stock':
                    $query->where('stock_quantity', 0);
                    break;
            }
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        // Get statistics
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => Product::whereRaw('stock_quantity <= low_stock_threshold')->count(),
            'out_of_stock_products' => Product::where('stock_quantity', 0)->count(),
            'total_inventory_value' => Product::sum(DB::raw('price * stock_quantity')),
        ];

        // Get materials for filter
        $materials = Product::select('material')
            ->whereNotNull('material')
            ->where('material', '!=', '')
            ->distinct()
            ->orderBy('material')
            ->pluck('material');

        return view('admin.products.index', compact('products', 'categories', 'stats', 'materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku',
            'barcode' => 'nullable|string|unique:products,barcode',
            'category_id' => 'required|exists:categories,id',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
            'tax_class' => 'nullable|string',
            'manage_stock' => 'boolean',
            'is_active' => 'boolean',
            'featured' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);

        // Set created_by
        $validated['created_by'] = Auth::guard('admin')->id();

        // Handle images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
            $validated['gallery'] = $images;
        }

        $product = Product::create($validated);

        // Log the action
        AuditLog::logCreate(Auth::guard('admin')->user(), $product);

        return redirect()->to(admin_route('products.index'))
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category']);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $oldValues = $product->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,'.$product->id,
            'barcode' => 'nullable|string|unique:products,barcode,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
            'tax_class' => 'nullable|string',
            'manage_stock' => 'boolean',
            'is_active' => 'boolean',
            'featured' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate slug if name changed
        if ($validated['name'] !== $product->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Set updated_by
        $validated['updated_by'] = Auth::guard('admin')->id();

        // Handle images
        if ($request->hasFile('images')) {
            // Delete old images
            if ($product->images) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
            $validated['gallery'] = $images;
        }

        $product->update($validated);

        // Log the action
        AuditLog::logUpdate(Auth::guard('admin')->user(), $product, $oldValues);

        return redirect()->to(admin_route('products.index'))
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete images
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // Log the action before deletion
        AuditLog::logDelete(Auth::guard('admin')->user(), $product);

        $product->delete();

        return redirect()->to(admin_route('products.index'))
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(Product $product)
    {
        $oldValues = $product->toArray();

        $product->update([
            'is_active' => ! $product->is_active,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        // Log the action
        AuditLog::logUpdate(Auth::guard('admin')->user(), $product, $oldValues);

        $status = $product->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Product {$status} successfully.");
    }

    /**
     * Bulk update product status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'status' => 'required|in:active,inactive',
        ]);

        $productIds = $request->product_ids;
        $isActive = $request->status === 'active';

        Product::whereIn('id', $productIds)->update([
            'is_active' => $isActive,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        $status = $isActive ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => count($productIds)." products {$status} successfully",
        ]);
    }

    /**
     * Bulk update product prices
     */
    public function bulkUpdatePrices(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'price_adjustment' => 'required|in:increase,decrease',
            'price_amount' => 'required|numeric|min:0',
        ]);

        $productIds = $request->product_ids;
        $adjustment = $request->price_adjustment;
        $amount = $request->price_amount;

        $products = Product::whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            $oldPrice = $product->price;
            $newPrice = $adjustment === 'increase'
                ? $oldPrice + $amount
                : max(0, $oldPrice - $amount);

            $product->update([
                'price' => $newPrice,
                'updated_by' => Auth::guard('admin')->id(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Prices updated for '.count($productIds).' products',
        ]);
    }

    /**
     * Restock product
     */
    public function restock(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldQuantity = $product->stock_quantity;
        $newQuantity = $oldQuantity + $request->quantity;

        $product->update([
            'stock_quantity' => $newQuantity,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        // Log the restock action
        AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'restock',
            'model_type' => Product::class,
            'model_id' => $product->id,
            'old_values' => ['stock_quantity' => $oldQuantity],
            'new_values' => ['stock_quantity' => $newQuantity],
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Product restocked with {$request->quantity} units. New stock: {$newQuantity}",
            'new_stock' => $newQuantity,
        ]);
    }

    /**
     * Bulk restock products
     */
    public function bulkRestock(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $productIds = $request->product_ids;
        $quantity = $request->quantity;

        $products = Product::whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            $oldQuantity = $product->stock_quantity;
            $newQuantity = $oldQuantity + $quantity;

            $product->update([
                'stock_quantity' => $newQuantity,
                'updated_by' => Auth::guard('admin')->id(),
            ]);

            // Log the restock action
            AuditLog::log(
                'bulk_restock',
                Auth::guard('admin')->user(),
                $product,
                ['stock_quantity' => $oldQuantity],
                ['stock_quantity' => $newQuantity],
                $request->notes
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Restocked {$quantity} units for ".count($productIds).' products',
        ]);
    }

    /**
     * Export products to CSV
     */
    public function export(Request $request)
    {
        $query = Product::with(['category']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('sku', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'low_stock') {
                $query->whereRaw('stock_quantity <= low_stock_threshold');
            }
        }

        $products = $query->get();

        $filename = 'products_export_'.now()->format('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'SKU',
                'Name',
                'Category',
                'Price (PHP)',
                'Stock Quantity',
                'Low Stock Threshold',
                'Material',
                'Status',
                'Created At',
            ]);

            // CSV data
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->sku,
                    $product->name,
                    $product->category->name ?? 'N/A',
                    $product->price,
                    $product->stock_quantity,
                    $product->low_stock_threshold,
                    $product->material ?? 'N/A',
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
