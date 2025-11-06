<?php

namespace App\Http\Controllers\Admin;

use App\Events\LowStockAlert;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
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
        $query = Product::with(['category', 'inventoryMovements' => function ($q) {
            $q->latest()->take(5);
        }])->where('is_active', true);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('sku', 'like', '%'.$search.'%');
            });
        }

        // Category filter
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
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
            'products_needing_reorder' => Product::where('is_active', true)
                ->whereRaw('stock_quantity <= low_stock_threshold')
                ->count(),
            'average_stock_level' => Product::where('is_active', true)->avg('stock_quantity'),
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

        // Get stock level trend data for the last 6 months
        $stockTrendData = $this->getStockLevelTrend($product);

        // Get sales trend data for the last 30 days (4 weeks)
        $salesTrendData = $this->getSalesTrend($product);

        return view('admin.inventory.show', compact('product', 'movements', 'stockTrendData', 'salesTrendData'));
    }

    /**
     * Get stock level trend data for the last 6 months.
     */
    private function getStockLevelTrend(Product $product)
    {
        $months = [];
        $stockLevels = [];

        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');

            // Get the stock level at the end of this month
            $endOfMonth = $date->copy()->endOfMonth();

            if ($endOfMonth->isFuture()) {
                // Future month, use current stock
                $stockLevels[] = $product->stock_quantity;
            } else {
                // Get the latest movement up to the end of this month
                $latestMovement = $product->inventoryMovements()
                    ->where('created_at', '<=', $endOfMonth)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($latestMovement) {
                    $stockLevels[] = $latestMovement->new_stock;
                } else {
                    // No movements before this date
                    // If product was created after this month, use 0 or previous value
                    if ($product->created_at > $endOfMonth) {
                        // Product didn't exist yet, use 0 or previous month's value
                        $stockLevels[] = ! empty($stockLevels) ? $stockLevels[count($stockLevels) - 1] : 0;
                    } else {
                        // Product existed but no movements - use estimated initial stock
                        // Try to get the first movement's previous_stock, or use current stock
                        $firstMovement = $product->inventoryMovements()->orderBy('created_at', 'asc')->first();
                        $initialStock = $firstMovement ? $firstMovement->previous_stock : $product->stock_quantity;
                        $stockLevels[] = $initialStock;
                    }
                }
            }
        }

        return [
            'labels' => $months,
            'data' => $stockLevels,
        ];
    }

    /**
     * Get sales trend data for the last 30 days (4 weeks).
     */
    private function getSalesTrend(Product $product)
    {
        $weeks = [];
        $salesData = [];

        $today = now();
        $startDate = $today->copy()->subDays(30)->startOfWeek();

        // Get 4 weeks of data
        for ($i = 0; $i < 4; $i++) {
            $weekStart = $startDate->copy()->addWeeks($i);
            $weekEnd = $weekStart->copy()->endOfWeek();

            $weeks[] = 'Week '.($i + 1);

            // Get sales for this week (only count up to today if week is not complete)
            $countEndDate = $weekEnd->gt($today) ? $today : $weekEnd;

            $weeklySales = $product->orderItems()
                ->whereHas('order', function ($q) use ($weekStart, $countEndDate) {
                    $q->whereBetween('created_at', [
                        $weekStart->startOfDay(),
                        $countEndDate->endOfDay(),
                    ])
                        ->where('status', '!=', 'cancelled');
                })
                ->sum('quantity');

            $salesData[] = (int) $weeklySales;
        }

        return [
            'labels' => $weeks,
            'data' => $salesData,
        ];
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

        // Refresh product to get updated stock
        $product->refresh();

        // Log inventory adjustment
        AuditLog::log('inventory.adjusted', Auth::guard('admin')->user(), $product, ['stock_quantity' => $currentStock], ['stock_quantity' => $newStock], "Adjusted stock for {$product->name} (SKU: {$product->sku}) from {$currentStock} to {$newStock}. Type: {$validated['adjustment_type']}, Reason: {$validated['reason']}");

        // Check for low stock alert
        if ($newStock <= 10) { // Low stock threshold
            event(new LowStockAlert($product, $newStock, 10));
        }

        return redirect()->to(admin_route('inventory.show', $product))
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

        $oldStock = $product->stock_quantity;

        InventoryMovement::recordStockIn(
            $product->id,
            $validated['quantity'],
            $validated['reason'],
            $validated['notes'],
            null,
            null,
            Auth::guard('admin')->id()
        );

        $product->refresh();

        // Log stock addition
        AuditLog::log('inventory.added', Auth::guard('admin')->user(), $product, ['stock_quantity' => $oldStock], ['stock_quantity' => $product->stock_quantity], "Added {$validated['quantity']} units to {$product->name} (SKU: {$product->sku}). Reason: {$validated['reason']}");

        return back()->with('success', 'Stock added successfully.');
    }

    /**
     * Remove stock from a product.
     */
    public function removeStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:'.$product->stock_quantity,
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $oldStock = $product->stock_quantity;

        InventoryMovement::recordStockOut(
            $product->id,
            $validated['quantity'],
            $validated['reason'],
            $validated['notes'],
            null,
            null,
            Auth::guard('admin')->id()
        );

        $product->refresh();

        // Log stock removal
        AuditLog::log('inventory.removed', Auth::guard('admin')->user(), $product, ['stock_quantity' => $oldStock], ['stock_quantity' => $product->stock_quantity], "Removed {$validated['quantity']} units from {$product->name} (SKU: {$product->sku}). Reason: {$validated['reason']}");

        return back()->with('success', 'Stock removed successfully.');
    }

    /**
     * Show low stock alerts.
     */
    public function lowStockAlerts(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true)
            ->whereRaw('stock_quantity <= low_stock_threshold');

        // Filter by alert level
        if ($request->has('level') && $request->level) {
            switch ($request->level) {
                case 'critical':
                    $query->whereRaw('stock_quantity <= (low_stock_threshold * 0.5)');

                    break;
                case 'low':
                    $query->whereRaw('stock_quantity > (low_stock_threshold * 0.5)')
                        ->where('stock_quantity', '>', 0);

                    break;
                case 'out':
                    $query->where('stock_quantity', 0);

                    break;
            }
        }

        // Filter by category (main category - includes products in subcategories)
        if ($request->has('category_id') && $request->category_id) {
            $mainCategoryId = $request->category_id;
            // Get subcategory IDs for this main category
            $subcategoryIds = Category::where('parent_id', $mainCategoryId)->pluck('id');

            $query->where(function ($q) use ($mainCategoryId, $subcategoryIds) {
                // Products directly assigned to main category
                $q->where('category_id', $mainCategoryId)
                  // Or products assigned to subcategories of this main category
                    ->orWhereIn('subcategory_id', $subcategoryIds);
            });
        }

        // Sort
        if ($request->has('sort') && $request->sort) {
            switch ($request->sort) {
                case 'stock_asc':
                    $query->orderBy('stock_quantity', 'asc');

                    break;
                case 'stock_desc':
                    $query->orderBy('stock_quantity', 'desc');

                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');

                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');

                    break;
                case 'value_desc':
                    $query->orderByRaw('(stock_quantity * price) DESC');

                    break;
                default:
                    $query->orderBy('stock_quantity', 'asc');
            }
        } else {
            $query->orderBy('stock_quantity', 'asc');
        }

        $products = $query->paginate(20);

        // Get statistics
        $stats = [
            'critical_stock' => Product::where('is_active', true)
                ->whereRaw('stock_quantity <= (low_stock_threshold * 0.5)')
                ->whereRaw('stock_quantity <= low_stock_threshold')
                ->count(),
            'low_stock' => Product::where('is_active', true)
                ->whereRaw('stock_quantity > (low_stock_threshold * 0.5)')
                ->whereRaw('stock_quantity <= low_stock_threshold')
                ->where('stock_quantity', '>', 0)
                ->count(),
            'out_of_stock' => Product::where('is_active', true)
                ->whereRaw('stock_quantity <= low_stock_threshold')
                ->where('stock_quantity', 0)
                ->count(),
            'total_value_at_risk' => Product::where('is_active', true)
                ->whereRaw('stock_quantity <= low_stock_threshold')
                ->sum(DB::raw('stock_quantity * price')),
        ];

        return view('admin.inventory.low-stock', compact('products', 'stats'));
    }

    /**
     * Export low stock products to CSV.
     */
    public function exportLowStock(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true)
            ->whereRaw('stock_quantity <= low_stock_threshold');

        // Apply same filters as lowStockAlerts
        if ($request->has('level') && $request->level) {
            switch ($request->level) {
                case 'critical':
                    $query->whereRaw('stock_quantity <= (low_stock_threshold * 0.5)');

                    break;
                case 'low':
                    $query->whereRaw('stock_quantity > (low_stock_threshold * 0.5)')
                        ->where('stock_quantity', '>', 0);

                    break;
                case 'out':
                    $query->where('stock_quantity', 0);

                    break;
            }
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('stock_quantity', 'asc')->get();

        $filename = 'low-stock-products-'.now()->format('Y-m-d-H-i-s');

        return $this->exportLowStockCsv($products, $filename);
    }

    /**
     * Export low stock products as CSV.
     */
    private function exportLowStockCsv($products, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'.csv"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Product Name',
                'SKU',
                'Category',
                'Current Stock',
                'Threshold',
                'Alert Level',
                'Days Until Stockout',
                'Suggested Reorder',
                'Price',
                'Stock Value',
            ]);

            foreach ($products as $product) {
                // Calculate days until stockout
                $dailySales = $product->orderItems()
                    ->whereHas('order', function ($q) {
                        $q->where('created_at', '>=', now()->subDays(30))
                            ->where('status', '!=', 'cancelled');
                    })
                    ->sum('quantity') / 30;
                $daysUntilStockout = $dailySales > 0 ? floor($product->stock_quantity / $dailySales) : 'N/A';

                // Suggested reorder
                $suggestedReorder = max($product->low_stock_threshold * 2, 10);

                // Alert level
                $alertLevel = 'Low Stock';
                if ($product->stock_quantity == 0) {
                    $alertLevel = 'Out of Stock';
                } elseif ($product->stock_quantity <= ($product->low_stock_threshold * 0.5)) {
                    $alertLevel = 'Critical';
                }

                $stockValue = $product->stock_quantity * $product->price;

                fputcsv($file, [
                    $product->name,
                    $product->sku ?? 'N/A',
                    $product->category->name ?? 'N/A',
                    $product->stock_quantity,
                    $product->low_stock_threshold,
                    $alertLevel,
                    is_numeric($daysUntilStockout) ? $daysUntilStockout : $daysUntilStockout,
                    $suggestedReorder,
                    $product->price,
                    $stockValue,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
        $thirtyDaysAgo = now()->subDays(30);
        $stats = [
            'total_movements' => InventoryMovement::count(),
            'stock_in_movements' => InventoryMovement::where('type', 'in')->count(),
            'stock_out_movements' => InventoryMovement::where('type', 'out')->count(),
            'adjustments' => InventoryMovement::where('type', 'adjustment')->count(),
            'stock_in_30_days' => InventoryMovement::where('type', 'in')->where('created_at', '>=', $thirtyDaysAgo)->sum('quantity'),
            // Stock out quantities are stored as negative, so we need to get the absolute value
            'stock_out_30_days' => abs(InventoryMovement::where('type', 'out')->where('created_at', '>=', $thirtyDaysAgo)->sum('quantity') ?? 0),
            'net_change_30_days' => InventoryMovement::where('created_at', '>=', $thirtyDaysAgo)
                ->selectRaw('SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END) as net')
                ->value('net') ?? 0,
        ];

        return view('admin.inventory.movements', compact('movements', 'products', 'stats'));
    }

    /**
     * Export inventory movements to CSV.
     */
    public function exportMovements(Request $request)
    {
        $query = InventoryMovement::with(['product.category', 'createdBy'])
            ->orderBy('created_at', 'desc');

        // Apply the same filters as the movements page
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        $movements = $query->get();

        $filename = 'inventory-movements-'.now()->format('Y-m-d-H-i-s');

        return $this->exportMovementsCsv($movements, $filename);
    }

    /**
     * Export movements as CSV.
     */
    private function exportMovementsCsv($movements, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'.csv"',
        ];

        $callback = function () use ($movements) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Date',
                'Product Name',
                'SKU',
                'Category',
                'Type',
                'Quantity',
                'Reason',
                'User',
                'Reference',
                'Notes',
            ]);

            foreach ($movements as $movement) {
                // Get reference display
                $referenceDisplay = '-';
                if ($movement->reference_type && $movement->reference_id) {
                    $reference = $movement->reference;
                    if ($reference) {
                        if (method_exists($reference, 'order_number')) {
                            $referenceDisplay = $reference->order_number;
                        } elseif (method_exists($reference, 'name')) {
                            $referenceDisplay = $reference->name;
                        } else {
                            $referenceDisplay = $movement->reference_id;
                        }
                    }
                }

                fputcsv($file, [
                    $movement->created_at->format('Y-m-d H:i:s'),
                    $movement->product->name ?? 'Product Deleted',
                    $movement->product->sku ?? 'N/A',
                    $movement->product->category->name ?? 'N/A',
                    ucfirst($movement->type),
                    $movement->quantity,
                    $movement->reason ?? 'N/A',
                    $movement->createdBy ? $movement->createdBy->name : 'System',
                    $referenceDisplay,
                    $movement->notes ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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

        $filename = 'inventory-report-'.now()->format('Y-m-d-H-i-s');

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
            'Content-Disposition' => 'attachment; filename="'.$filename.'.csv"',
        ];

        $callback = function () use ($products) {
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
                'Last Updated',
            ]);

            foreach ($products as $product) {
                // Format numbers as pure numeric values (no currency symbols) for Excel CSV compatibility
                // Excel will handle these as numbers and display them correctly
                $price = $product->price;
                $stockValue = $product->stock_quantity * $product->price;

                fputcsv($file, [
                    $product->name,
                    $product->sku ?? 'N/A',
                    $product->category->name ?? 'N/A',
                    $product->stock_quantity,
                    ucfirst(str_replace('_', ' ', $product->stock_status)),
                    $price, // Pure number for Excel compatibility
                    $stockValue, // Pure number for Excel compatibility
                    $product->updated_at->format('Y-m-d H:i:s'), // Format compatible with Excel
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
            $updatedCount = 0;
            foreach ($validated['products'] as $productData) {
                $product = Product::findOrFail($productData['id']);
                $oldStock = $product->stock_quantity;
                $newStock = $productData['stock_quantity'];

                if ($product->stock_quantity != $newStock) {
                    InventoryMovement::recordStockAdjustment(
                        $product->id,
                        $newStock,
                        $validated['reason'],
                        $validated['notes'],
                        Auth::guard('admin')->id()
                    );

                    $product->refresh();

                    // Log bulk inventory adjustment
                    AuditLog::log('inventory.adjusted', Auth::guard('admin')->user(), $product, ['stock_quantity' => $oldStock], ['stock_quantity' => $newStock], "Bulk adjusted stock for {$product->name} (SKU: {$product->sku}) from {$oldStock} to {$newStock}. Reason: {$validated['reason']}");
                    $updatedCount++;
                }
            }

            DB::commit();

            return back()->with('success', "Bulk stock update completed successfully. {$updatedCount} product(s) updated.");
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => 'Bulk update failed: '.$e->getMessage()]);
        }
    }

    /**
     * Display low stock alerts.
     */
    public function lowStock(Request $request)
    {
        $query = Product::with(['category'])
            ->where('is_active', true)
            ->whereRaw('stock_quantity <= low_stock_threshold')
            ->orderBy('stock_quantity', 'asc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('sku', 'like', '%'.$search.'%');
            });
        }

        $products = $query->paginate(20);

        // Get statistics
        $stats = [
            'total_low_stock' => Product::where('is_active', true)
                ->whereRaw('stock_quantity <= low_stock_threshold')
                ->count(),
            'critical_stock' => Product::where('is_active', true)
                ->whereRaw('stock_quantity <= (low_stock_threshold * 0.5)')
                ->count(),
            'out_of_stock' => Product::where('is_active', true)
                ->where('stock_quantity', 0)
                ->count(),
        ];

        return view('admin.inventory.low-stock', compact('products', 'stats'));
    }

    /**
     * Bulk restock low stock products.
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
        $notes = $request->notes;

        DB::beginTransaction();

        try {
            $products = Product::whereIn('id', $productIds)->get();

            foreach ($products as $product) {
                $oldQuantity = $product->stock_quantity;
                $newQuantity = $oldQuantity + $quantity;

                $product->update(['stock_quantity' => $newQuantity]);

                // Log the movement
                InventoryMovement::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $quantity,
                    'previous_stock' => $oldQuantity,
                    'new_stock' => $newQuantity,
                    'reason' => 'Bulk Restock',
                    'notes' => $notes ?: 'Bulk restock from low stock alerts',
                    'created_by' => Auth::guard('admin')->id(),
                ]);

                // Log bulk restock
                AuditLog::log('inventory.added', Auth::guard('admin')->user(), $product, ['stock_quantity' => $oldQuantity], ['stock_quantity' => $newQuantity], "Bulk restocked {$quantity} units to {$product->name} (SKU: {$product->sku}). Reason: Bulk Restock");
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Restocked {$quantity} units for ".count($productIds).' products',
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Bulk restock failed: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update reorder point for a product.
     */
    public function updateReorderPoint(Request $request, Product $product)
    {
        $request->validate([
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        $oldThreshold = $product->low_stock_threshold;
        $product->update(['low_stock_threshold' => $request->low_stock_threshold]);

        // Log the change
        InventoryMovement::create([
            'product_id' => $product->id,
            'type' => 'reorder_point_update',
            'quantity' => 0,
            'previous_quantity' => $oldThreshold,
            'new_quantity' => $request->low_stock_threshold,
            'notes' => 'Reorder point updated',
            'created_by' => Auth::guard('admin')->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reorder point updated successfully',
        ]);
    }
}
