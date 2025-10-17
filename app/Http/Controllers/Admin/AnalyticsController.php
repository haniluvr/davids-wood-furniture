<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductReview;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();

        // Sales Analytics
        $salesData = $this->getSalesData($startDate, $endDate);
        $revenueData = $this->getRevenueData($startDate, $endDate);
        $orderStats = $this->getOrderStats($startDate, $endDate);
        $productStats = $this->getProductStats();
        $customerStats = $this->getCustomerStats($startDate, $endDate);

        return view('admin.analytics.dashboard', compact(
            'salesData', 'revenueData', 'orderStats', 'productStats', 'customerStats', 'dateRange'
        ));
    }

    /**
     * Sales reports page.
     */
    public function sales(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();

        // Get all required data
        $salesData = $this->getSalesData($startDate, $endDate);
        $revenueData = $this->getRevenueData($startDate, $endDate);
        $topProducts = $this->getTopProducts($startDate, $endDate);
        $salesByCategory = $this->getSalesByCategory($startDate, $endDate);
        $orderStats = $this->getOrderStats($startDate, $endDate);
        
        // Calculate metrics for the view
        $totalSales = $orderStats['total_orders'] ?? 0;
        $totalRevenue = $revenueData['total_revenue'] ?? 0;
        $averageOrderValue = $totalSales > 0 ? $totalRevenue / $totalSales : 0;
        $conversionRate = $this->calculateConversionRate($startDate, $endDate);
        
        // Generate chart data
        $dailyRevenue = $this->generateDailyRevenueData($startDate, $endDate);
        $dailyOrders = $this->generateDailyOrdersData($startDate, $endDate);
        $chartLabels = $this->generateChartLabels($startDate, $endDate);

        return view('admin.analytics.sales', compact(
            'salesData', 'revenueData', 'topProducts', 'salesByCategory', 'dateRange', 'startDate', 'endDate',
            'totalSales', 'totalRevenue', 'averageOrderValue', 'conversionRate',
            'dailyRevenue', 'dailyOrders', 'chartLabels'
        ));
    }

    /**
     * Customer analytics page.
     */
    public function customers(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();

        $customerStats = $this->getCustomerStats($startDate, $endDate);
        $newCustomers = $this->getNewCustomers($startDate, $endDate);
        $topCustomers = $this->getTopCustomers($startDate, $endDate);
        $customerSegments = $this->getCustomerSegments();

        // Calculate metrics for the view
        $totalCustomers = User::count();
        $newCustomersCount = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $averageLifetimeValue = $this->calculateAverageLifetimeValue();
        $repeatPurchaseRate = $this->calculateRepeatPurchaseRate($startDate, $endDate);
        
        // Generate chart data
        $dailyNewCustomers = $this->generateDailyNewCustomersData($startDate, $endDate);
        $chartLabels = $this->generateChartLabels($startDate, $endDate);
        
        // Customer segments
        $highValueCustomers = 25; // Placeholder
        $mediumValueCustomers = 45; // Placeholder
        $lowValueCustomers = 30; // Placeholder

        return view('admin.analytics.customers', compact(
            'customerStats', 'newCustomers', 'topCustomers', 'customerSegments', 'dateRange', 'startDate', 'endDate',
            'totalCustomers', 'newCustomersCount', 'averageLifetimeValue', 'repeatPurchaseRate',
            'dailyNewCustomers', 'chartLabels', 'highValueCustomers', 'mediumValueCustomers', 'lowValueCustomers'
        ));
    }

    /**
     * Product performance page.
     */
    public function products(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();

        $productStats = $this->getProductStats();
        $topProducts = $this->getTopProducts($startDate, $endDate);
        $lowStockProducts = $this->getLowStockProducts();
        $productReviews = $this->getProductReviews($startDate, $endDate);

        // Calculate metrics for the view
        $totalProducts = Product::count();
        $totalUnitsSold = OrderItem::whereHas('order', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', '!=', 'cancelled');
        })->sum('quantity');
        
        $totalProductRevenue = OrderItem::whereHas('order', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', '!=', 'cancelled');
        })->sum('total_price');
        
        $averageProductPrice = Product::avg('price');
        
        // Category sales data
        $categorySales = $this->getSalesByCategory($startDate, $endDate);

        return view('admin.analytics.products', compact(
            'productStats', 'topProducts', 'lowStockProducts', 'productReviews', 'dateRange', 'startDate', 'endDate',
            'totalProducts', 'totalUnitsSold', 'totalProductRevenue', 'averageProductPrice', 'categorySales'
        ));
    }

    /**
     * Revenue analysis page.
     */
    public function revenue(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();

        $revenueData = $this->getRevenueData($startDate, $endDate);
        $revenueByMonth = $this->getRevenueByMonth();
        $revenueByPaymentMethod = $this->getRevenueByPaymentMethod($startDate, $endDate);
        $averageOrderValue = $this->getAverageOrderValue($startDate, $endDate);

        // Calculate metrics for the view
        $totalRevenue = $revenueData['total_revenue'] ?? 0;
        $revenueGrowth = $this->calculateRevenueGrowth($startDate, $endDate);
        $revenuePerCustomer = $this->calculateRevenuePerCustomer($startDate, $endDate);
        
        // Generate chart data
        $dailyRevenue = $this->generateDailyRevenueData($startDate, $endDate);
        $chartLabels = $this->generateChartLabels($startDate, $endDate);
        
        // Revenue by category
        $revenueByCategory = $this->getSalesByCategory($startDate, $endDate);
        
        // Previous period comparison
        $previousStartDate = $startDate->copy()->subDays($dateRange);
        $previousEndDate = $startDate->copy();
        $previousPeriodRevenue = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $currentPeriodRevenue = $totalRevenue;

        return view('admin.analytics.revenue', compact(
            'revenueData', 'revenueByMonth', 'revenueByPaymentMethod', 'averageOrderValue', 'dateRange', 'startDate', 'endDate',
            'totalRevenue', 'revenueGrowth', 'revenuePerCustomer', 'dailyRevenue', 'chartLabels', 'revenueByCategory',
            'previousStartDate', 'previousEndDate', 'previousPeriodRevenue', 'currentPeriodRevenue'
        ));
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'sales');
        $dateRange = $request->get('date_range', '30');
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();

        $filename = $type . '-analytics-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($type, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            switch ($type) {
                case 'sales':
                    $this->exportSalesData($file, $startDate, $endDate);
                    break;
                case 'customers':
                    $this->exportCustomerData($file, $startDate, $endDate);
                    break;
                case 'products':
                    $this->exportProductData($file, $startDate, $endDate);
                    break;
                case 'revenue':
                    $this->exportRevenueData($file, $startDate, $endDate);
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Private helper methods

    private function getSalesData($startDate, $endDate)
    {
        return [
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount'),
            'average_order_value' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->avg('total_amount'),
            'conversion_rate' => $this->calculateConversionRate($startDate, $endDate),
        ];
    }

    private function getRevenueData($startDate, $endDate)
    {
        $revenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $revenue->pluck('revenue', 'date');
    }

    private function getOrderStats($startDate, $endDate)
    {
        return [
            'pending' => Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'pending')->count(),
            'processing' => Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'processing')->count(),
            'shipped' => Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'shipped')->count(),
            'delivered' => Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'delivered')->count(),
            'cancelled' => Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'cancelled')->count(),
        ];
    }

    private function getProductStats()
    {
        return [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => Product::where('stock_quantity', '<=', 10)->count(),
            'out_of_stock_products' => Product::where('stock_quantity', 0)->count(),
        ];
    }

    private function getCustomerStats($startDate, $endDate)
    {
        return [
            'total_customers' => User::count(),
            'new_customers' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'active_customers' => User::whereHas('orders', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count(),
            'average_customer_value' => User::with('orders')->get()->map(function($user) {
                return $user->orders->sum('total_amount');
            })->avg(),
        ];
    }

    private function getTopProducts($startDate, $endDate)
    {
        return Product::withCount(['orderItems as total_sold' => function($query) use ($startDate, $endDate) {
            $query->whereHas('order', function($orderQuery) use ($startDate, $endDate) {
                $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                          ->where('status', '!=', 'cancelled');
            });
        }])
        ->withSum(['orderItems as total_revenue' => function($query) use ($startDate, $endDate) {
            $query->whereHas('order', function($orderQuery) use ($startDate, $endDate) {
                $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                          ->where('status', '!=', 'cancelled');
            });
        }], 'total_price')
        ->orderBy('total_sold', 'desc')
        ->take(10)
        ->get();
    }

    private function getSalesByCategory($startDate, $endDate)
    {
        return Category::withCount(['products as total_sold' => function($query) use ($startDate, $endDate) {
            $query->whereHas('orderItems.order', function($orderQuery) use ($startDate, $endDate) {
                $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                          ->where('status', '!=', 'cancelled');
            });
        }])
        ->orderBy('total_sold', 'desc')
        ->get();
    }

    private function getNewCustomers($startDate, $endDate)
    {
        return User::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    private function getTopCustomers($startDate, $endDate)
    {
        return User::withSum(['orders as total_spent' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', '!=', 'cancelled');
        }], 'total_amount')
        ->withCount(['orders as total_orders' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', '!=', 'cancelled');
        }])
        ->orderBy('total_spent', 'desc')
        ->take(10)
        ->get();
    }

    private function getCustomerSegments()
    {
        return [
            'new_customers' => User::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
            'returning_customers' => User::whereHas('orders', function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            })->count(),
            'vip_customers' => User::whereHas('orders', function($query) {
                $query->where('total_amount', '>=', 1000);
            })->count(),
        ];
    }

    private function getLowStockProducts()
    {
        return Product::where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get();
    }

    private function getProductReviews($startDate, $endDate)
    {
        return ProductReview::whereBetween('created_at', [$startDate, $endDate])
            ->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    private function getRevenueByMonth()
    {
        return Order::where('status', '!=', 'cancelled')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();
    }

    private function getRevenueByPaymentMethod($startDate, $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('payment_method, SUM(total_amount) as revenue')
            ->groupBy('payment_method')
            ->get();
    }

    private function getAverageOrderValue($startDate, $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->avg('total_amount');
    }

    private function calculateConversionRate($startDate, $endDate)
    {
        $totalVisitors = 1000; // This would come from analytics service
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        
        return $totalVisitors > 0 ? ($totalOrders / $totalVisitors) * 100 : 0;
    }

    private function exportSalesData($file, $startDate, $endDate)
    {
        fputcsv($file, ['Date', 'Orders', 'Revenue', 'Average Order Value']);
        
        $salesData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue, AVG(total_amount) as avg_order_value')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($salesData as $data) {
            fputcsv($file, [
                $data->date,
                $data->orders,
                $data->revenue,
                $data->avg_order_value,
            ]);
        }
    }

    private function exportCustomerData($file, $startDate, $endDate)
    {
        fputcsv($file, ['Customer ID', 'Name', 'Email', 'Total Orders', 'Total Spent', 'Last Order Date']);
        
        $customers = User::withSum('orders as total_spent', 'total_amount')
            ->withCount('orders as total_orders')
            ->withMax('orders as last_order_date', 'created_at')
            ->get();

        foreach ($customers as $customer) {
            fputcsv($file, [
                $customer->id,
                $customer->first_name . ' ' . $customer->last_name,
                $customer->email,
                $customer->total_orders,
                $customer->total_spent,
                $customer->last_order_date,
            ]);
        }
    }

    private function exportProductData($file, $startDate, $endDate)
    {
        fputcsv($file, ['Product ID', 'Name', 'SKU', 'Price', 'Stock', 'Total Sold', 'Revenue']);
        
        $products = Product::withCount(['orderItems as total_sold' => function($query) use ($startDate, $endDate) {
            $query->whereHas('order', function($orderQuery) use ($startDate, $endDate) {
                $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                          ->where('status', '!=', 'cancelled');
            });
        }])
        ->withSum(['orderItems as total_revenue' => function($query) use ($startDate, $endDate) {
            $query->whereHas('order', function($orderQuery) use ($startDate, $endDate) {
                $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                          ->where('status', '!=', 'cancelled');
            });
        }], 'total_price')
        ->get();

        foreach ($products as $product) {
            fputcsv($file, [
                $product->id,
                $product->name,
                $product->sku,
                $product->price,
                $product->stock_quantity,
                $product->total_sold,
                $product->total_revenue,
            ]);
        }
    }

    private function exportRevenueData($file, $startDate, $endDate)
    {
        fputcsv($file, ['Date', 'Revenue', 'Orders', 'Average Order Value']);
        
        $revenueData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as orders, AVG(total_amount) as avg_order_value')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($revenueData as $data) {
            fputcsv($file, [
                $data->date,
                $data->revenue,
                $data->orders,
                $data->avg_order_value,
            ]);
        }
    }

    /**
     * Generate daily revenue data for charts
     */
    private function generateDailyRevenueData($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy();
        
        while ($current->lte($endDate)) {
            $dayRevenue = Order::whereDate('created_at', $current->format('Y-m-d'))
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');
            
            $data[] = (float) $dayRevenue;
            $current->addDay();
        }
        
        return $data;
    }

    /**
     * Generate daily orders data for charts
     */
    private function generateDailyOrdersData($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy();
        
        while ($current->lte($endDate)) {
            $dayOrders = Order::whereDate('created_at', $current->format('Y-m-d'))
                ->where('status', '!=', 'cancelled')
                ->count();
            
            $data[] = $dayOrders;
            $current->addDay();
        }
        
        return $data;
    }

    /**
     * Generate chart labels for date range
     */
    private function generateChartLabels($startDate, $endDate)
    {
        $labels = [];
        $current = $startDate->copy();
        
        while ($current->lte($endDate)) {
            $labels[] = $current->format('M d');
            $current->addDay();
        }
        
        return $labels;
    }

    /**
     * Generate daily new customers data for charts
     */
    private function generateDailyNewCustomersData($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy();
        
        while ($current->lte($endDate)) {
            $dayNewCustomers = User::whereDate('created_at', $current->format('Y-m-d'))->count();
            $data[] = $dayNewCustomers;
            $current->addDay();
        }
        
        return $data;
    }

    /**
     * Calculate average lifetime value
     */
    private function calculateAverageLifetimeValue()
    {
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalCustomers = User::count();
        
        return $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;
    }

    /**
     * Calculate repeat purchase rate
     */
    private function calculateRepeatPurchaseRate($startDate, $endDate)
    {
        $totalCustomers = User::count();
        $repeatCustomers = User::has('orders', '>', 1)->count();
        
        return $totalCustomers > 0 ? ($repeatCustomers / $totalCustomers) * 100 : 0;
    }

    /**
     * Calculate revenue growth
     */
    private function calculateRevenueGrowth($startDate, $endDate)
    {
        $currentRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        
        $previousStartDate = $startDate->copy()->subDays($startDate->diffInDays($endDate));
        $previousEndDate = $startDate->copy();
        
        $previousRevenue = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        
        if ($previousRevenue == 0) {
            return $currentRevenue > 0 ? 100 : 0;
        }
        
        return (($currentRevenue - $previousRevenue) / $previousRevenue) * 100;
    }

    /**
     * Calculate revenue per customer
     */
    private function calculateRevenuePerCustomer($startDate, $endDate)
    {
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        
        $uniqueCustomers = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->distinct('user_id')
            ->count('user_id');
        
        return $uniqueCustomers > 0 ? $totalRevenue / $uniqueCustomers : 0;
    }
}
