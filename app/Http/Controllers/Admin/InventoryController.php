<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryMovement;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display inventory overview.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'inventoryMovements' => function($q) {
            $q->latest()->take(5);
        }])->where('is_active', true);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        // Category filter
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Stock status filter
        if ($request->has('stock_status') && $request->stock_status !== 'all') {
            switch ($request->stock_status) {
                case 'low_stock':
                    $query->lowStock();
                    break;
                case 'out_of_stock':
                    $query->outOfStock();
                    break;
                case 'in_stock':
                    $query->inStock()->where('stock_quantity', '>', 10);
                    break;
            }
        }

        $products = $query->orderBy('stock_quantity', 'asc')->paginate(15);
        $categories = Category::whereNull('parent_id')->get();

        // Get inventory statistics
        $stats = [
            'total_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => Product::lowStock()->count(),
            'out_of_stock_products' => Product::outOfStock()->count(),
            'total_stock_value' => Product::where('is_active', true)->sum(DB::raw('stock_quantity * price')),
            'recent_movements' => InventoryMovement::with('product')->latest()->take(10)->get(),
        ];

        return view('admin.inventory.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Show inventory movements for a specific product.
     */
    public function show(Product $product)
    {
        $movements = $product->inventoryMovements()
            ->with('createdBy')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.inventory.show', compact('product', 'movements'));
    }

    /**
     * Show form for stock adjustment.
     */
    public function adjust(Product $product)
    {
        return view('admin.inventory.adjust', compact('product'));
    }

    /**
     * Process stock adjustment.
     */
    public function processAdjustment(Request $request, Product $product)
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:set,add,subtract',
            'quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $currentStock = $product->stock_quantity;
        $newStock = $currentStock;

        switch ($validated['adjustment_type']) {
            case 'set':
                $newStock = $validated['quantity'];
                break;
            case 'add':
                $newStock = $currentStock + $validated['quantity'];
                break;
            case 'subtract':
                $newStock = max(0, $currentStock - $validated['quantity']);
                break;
        }

        // Record the adjustment
        InventoryMovement::recordStockAdjustment(
            $product->id,
            $newStock,
            $validated['reason'],
            $validated['notes'],
            Auth::guard('admin')->id()
        );

        return redirect()->route('admin.inventory.show', $product)
            ->with('success', 'Stock adjustment completed successfully.');
    }

    /**
     * Add stock to a product.
     */
    public function addStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        InventoryMovement::recordStockIn(
            $product->id,
            $validated['quantity'],
            $validated['reason'],
            $validated['notes'],
            null,
            null,
            Auth::guard('admin')->id()
        );

        return back()->with('success', 'Stock added successfully.');
    }

    /**
     * Remove stock from a product.
     */
    public function removeStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock_quantity,
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        InventoryMovement::recordStockOut(
            $product->id,
            $validated['quantity'],
            $validated['reason'],
            $validated['notes'],
            null,
            null,
            Auth::guard('admin')->id()
        );

        return back()->with('success', 'Stock removed successfully.');
    }

    /**
     * Show low stock alerts.
     */
    public function lowStockAlerts(Request $request)
    {
        $threshold = $request->get('threshold', 10);
        
        $products = Product::with('category')
            ->lowStock($threshold)
            ->where('is_active', true)
            ->orderBy('stock_quantity', 'asc')
            ->paginate(20);

        return view('admin.inventory.low-stock', compact('products', 'threshold'));
    }

    /**
     * Show inventory movements report.
     */
    public function movements(Request $request)
    {
        $query = InventoryMovement::with(['product', 'createdBy'])
            ->orderBy('created_at', 'desc');

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Movement type filter
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Product filter
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        $movements = $query->paginate(20);
        $products = Product::where('is_active', true)->orderBy('name')->get();

        // Movement statistics
        $stats = [
            'total_movements' => InventoryMovement::count(),
            'stock_in_movements' => InventoryMovement::where('type', 'in')->count(),
            'stock_out_movements' => InventoryMovement::where('type', 'out')->count(),
            'adjustments' => InventoryMovement::where('type', 'adjustment')->count(),
        ];

        return view('admin.inventory.movements', compact('movements', 'products', 'stats'));
    }

    /**
     * Export inventory report.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $filename = 'inventory-report-' . now()->format('Y-m-d-H-i-s');

        if ($format === 'csv') {
            return $this->exportCsv($products, $filename);
        }

        // Add other export formats as needed
        return back()->with('error', 'Export format not supported.');
    }

    /**
     * Export inventory as CSV.
     */
    private function exportCsv($products, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Product Name',
                'SKU',
                'Category',
                'Current Stock',
                'Stock Status',
                'Price',
                'Stock Value',
                'Last Updated'
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->name,
                    $product->sku,
                    $product->category->name ?? 'N/A',
                    $product->stock_quantity,
                    ucfirst(str_replace('_', ' ', $product->stock_status)),
                    '$' . number_format($product->price, 2),
                    '$' . number_format($product->stock_quantity * $product->price, 2),
                    $product->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk stock update.
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.stock_quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['products'] as $productData) {
                $product = Product::findOrFail($productData['id']);
                $newStock = $productData['stock_quantity'];
                
                if ($product->stock_quantity != $newStock) {
                    InventoryMovement::recordStockAdjustment(
                        $product->id,
                        $newStock,
                        $validated['reason'],
                        $validated['notes'],
                        Auth::guard('admin')->id()
                    );
                }
            }

            DB::commit();
            return back()->with('success', 'Bulk stock update completed successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Bulk update failed: ' . $e->getMessage()]);
        }
    }
}