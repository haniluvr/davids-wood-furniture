<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index(Request $request)
    {
        // Enhanced time filtering
        $dateRange = $request->get('date_range', '30');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays($dateRange);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();

        // Ensure end date is not in the future
        if ($endDate->isFuture()) {
            $endDate = Carbon::now();
        }

        // Deep BI Analytics
        $salesData = $this->getSalesData($startDate, $endDate);
        $revenueData = $this->getRevenueData($startDate, $endDate);
        $orderStats = $this->getOrderStats($startDate, $endDate);
        $productStats = $this->getProductStats($startDate, $endDate);
        $customerStats = $this->getCustomerStats($startDate, $endDate);
        $topProducts = $this->getTopProducts($startDate, $endDate);

        // Enhanced metrics
        $conversionMetrics = $this->getConversionMetrics($startDate, $endDate);
        $trafficSources = $this->getTrafficSources($startDate, $endDate);
        $geographicData = $this->getGeographicData($startDate, $endDate);
        $seasonalTrends = $this->getSeasonalTrends($startDate, $endDate);
        $profitabilityAnalysis = $this->getProfitabilityAnalysis($startDate, $endDate);

        return view('admin.analytics.dashboard', compact(
            'salesData',
            'revenueData',
            'orderStats',
            'productStats',
            'customerStats',
            'topProducts',
            'conversionMetrics',
            'trafficSources',
            'geographicData',
            'seasonalTrends',
            'profitabilityAnalysis',
            'dateRange',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Sales reports page.
     */
    public function sales(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays($dateRange);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();

        if ($endDate->isFuture()) {
            $endDate = Carbon::now();
        }

        // Get all required data
        $salesData = $this->getSalesData($startDate, $endDate);
        $revenueData = $this->getRevenueData($startDate, $endDate);
        $topProducts = $this->getTopProducts($startDate, $endDate);
        $salesByCategory = $this->getSalesByCategory($startDate, $endDate);
        $orderStats = $this->getOrderStats($startDate, $endDate);

        // Calculate metrics for the view
        $totalSales = $orderStats['total_orders'] ?? 0;
        $totalRevenue = $salesData['total_revenue'] ?? 0;
        $averageOrderValue = $salesData['average_order_value'] ?? 0;
        $conversionRate = $this->calculateConversionRate($startDate, $endDate);

        // Period comparison
        $periodComparison = $this->getPeriodComparison($startDate, $endDate);
        $discountUsage = $this->getDiscountUsage($startDate, $endDate);

        // Generate chart data
        $dailyRevenue = $this->generateDailyRevenueData($startDate, $endDate);
        $weeklyRevenue = $this->generateWeeklyRevenueData($startDate, $endDate);
        $monthlyRevenue = $this->generateMonthlyRevenueData($startDate, $endDate);
        $dailyOrders = $this->generateDailyOrdersData($startDate, $endDate);
        $chartLabels = $this->generateChartLabels($startDate, $endDate);

        return view('admin.analytics.sales', compact(
            'salesData',
            'revenueData',
            'topProducts',
            'salesByCategory',
            'dateRange',
            'startDate',
            'endDate',
            'totalSales',
            'totalRevenue',
            'averageOrderValue',
            'conversionRate',
            'dailyRevenue',
            'weeklyRevenue',
            'monthlyRevenue',
            'dailyOrders',
            'chartLabels',
            'periodComparison',
            'discountUsage'
        ));
    }

    /**
     * Customer analytics page.
     */
    public function customers(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays($dateRange);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();

        if ($endDate->isFuture()) {
            $endDate = Carbon::now();
        }

        $customerStats = $this->getCustomerStats($startDate, $endDate);
        $newCustomers = $this->getNewCustomers($startDate, $endDate);
        $topCustomers = $this->getTopCustomers($startDate, $endDate);
        $customerSegments = $this->getCustomerSegments();
        $newVsReturning = $this->getNewVsReturningCustomers($startDate, $endDate);
        $clvData = $this->getCustomerLifetimeValueData();
        $geographicData = $this->getGeographicData($startDate, $endDate);
        $cohortAnalysis = $this->getCohortAnalysis();

        // Calculate metrics for the view
        $totalCustomers = User::count();
        $newCustomersCount = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $averageLifetimeValue = $this->calculateAverageLifetimeValue();
        $repeatPurchaseRate = $this->calculateRepeatPurchaseRate($startDate, $endDate);

        // Generate chart data
        $dailyNewCustomers = $this->generateDailyNewCustomersData($startDate, $endDate);
        $chartLabels = $this->generateChartLabels($startDate, $endDate);

        // Customer segments - calculate from actual data
        $customerSegmentsCalculated = $this->calculateCustomerSegments();

        return view('admin.analytics.customers', compact(
            'customerStats',
            'newCustomers',
            'topCustomers',
            'customerSegments',
            'dateRange',
            'startDate',
            'endDate',
            'totalCustomers',
            'newCustomersCount',
            'averageLifetimeValue',
            'repeatPurchaseRate',
            'dailyNewCustomers',
            'chartLabels',
            'newVsReturning',
            'clvData',
            'geographicData',
            'cohortAnalysis',
            'customerSegmentsCalculated'
        ));
    }

    /**
     * Product performance page.
     */
    public function products(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays($dateRange);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();

        if ($endDate->isFuture()) {
            $endDate = Carbon::now();
        }

        $productStats = $this->getProductStats();
        $topProducts = $this->getTopProducts($startDate, $endDate);
        $lowStockProducts = $this->getLowStockProducts();
        $productReviews = $this->getProductReviews($startDate, $endDate);

        // Calculate metrics for the view
        $totalProducts = Product::count();
        $totalUnitsSold = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled');
        })->sum('quantity');

        $totalProductRevenue = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled');
        })->sum('total_price');

        $averageProductPrice = Product::avg('price');

        // Category sales data
        $categorySales = $this->getSalesByCategory($startDate, $endDate);

        // Get main categories for filter
        $mainCategories = Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get();

        // Get filter and sort parameters
        $categoryFilter = $request->get('category');
        $sortBy = $request->get('sort_by', 'units_sold');
        $sortOrder = $request->get('sort_order', 'desc');

        // Enhanced product data with all metrics
        $productPerformanceData = $this->getProductPerformanceData($startDate, $endDate, $categoryFilter, $sortBy, $sortOrder);
        $bestSellers = $this->getBestSellers($startDate, $endDate);
        $worstPerformers = $this->getWorstPerformers($startDate, $endDate);
        $mostViewed = $this->getMostViewedProducts($startDate, $endDate);
        $stockTurnoverRate = $this->calculateStockTurnoverRate($startDate, $endDate);

        return view('admin.analytics.products', compact(
            'productStats',
            'topProducts',
            'lowStockProducts',
            'productReviews',
            'dateRange',
            'startDate',
            'endDate',
            'totalProducts',
            'totalUnitsSold',
            'totalProductRevenue',
            'averageProductPrice',
            'categorySales',
            'productPerformanceData',
            'bestSellers',
            'worstPerformers',
            'mostViewed',
            'stockTurnoverRate',
            'mainCategories',
            'categoryFilter',
            'sortBy',
            'sortOrder'
        ));
    }

    /**
     * Revenue analysis page.
     */
    public function revenue(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays($dateRange);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();

        if ($endDate->isFuture()) {
            $endDate = Carbon::now();
        }

        $revenueData = $this->getRevenueData($startDate, $endDate);
        $revenueByMonth = $this->getRevenueByMonth();
        $revenueByPaymentMethod = $this->getRevenueByPaymentMethod($startDate, $endDate);
        $averageOrderValue = $this->getAverageOrderValue($startDate, $endDate);

        // Calculate metrics for the view
        $grossNetRevenue = $this->getGrossNetRevenue($startDate, $endDate);
        $totalRevenue = $grossNetRevenue['net_revenue'] ?? 0;
        $revenueGrowth = $this->calculateRevenueGrowth($startDate, $endDate);
        $revenuePerCustomer = $this->calculateRevenuePerCustomer($startDate, $endDate);
        $revenueByChannel = $this->getRevenueByChannel($startDate, $endDate);
        $taxCollected = $this->getTaxCollected($startDate, $endDate);
        $profitability = $this->getProfitabilityAnalysis($startDate, $endDate);

        // Generate chart data
        $dailyRevenue = $this->generateDailyRevenueData($startDate, $endDate);
        $weeklyRevenue = $this->generateWeeklyRevenueData($startDate, $endDate);
        $monthlyRevenue = $this->generateMonthlyRevenueData($startDate, $endDate);
        $chartLabels = $this->generateChartLabels($startDate, $endDate);

        // Revenue by category
        $revenueByCategory = $this->getSalesByCategory($startDate, $endDate);

        // Previous period comparison
        $previousStartDate = $startDate->copy()->subDays($startDate->diffInDays($endDate));
        $previousEndDate = $startDate->copy();
        $previousPeriodRevenue = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $currentPeriodRevenue = $totalRevenue;

        return view('admin.analytics.revenue', compact(
            'revenueData',
            'revenueByMonth',
            'revenueByPaymentMethod',
            'averageOrderValue',
            'dateRange',
            'startDate',
            'endDate',
            'totalRevenue',
            'revenueGrowth',
            'revenuePerCustomer',
            'dailyRevenue',
            'weeklyRevenue',
            'monthlyRevenue',
            'chartLabels',
            'revenueByCategory',
            'previousStartDate',
            'previousEndDate',
            'previousPeriodRevenue',
            'currentPeriodRevenue',
            'grossNetRevenue',
            'revenueByChannel',
            'taxCollected',
            'profitability'
        ));
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'sales');

        // Handle custom date range from date picker
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->get('start_date'));
            $endDate = Carbon::parse($request->get('end_date'));
        } else {
            $dateRange = $request->get('date_range', '30');
            $startDate = Carbon::now()->subDays($dateRange);
            $endDate = Carbon::now();
        }

        // Ensure end date is not in the future
        if ($endDate->isFuture()) {
            $endDate = Carbon::now();
        }

        $filename = $type.'-analytics-'.now()->format('Y-m-d-H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($type, $startDate, $endDate) {
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
                    $this->exportAllRevenueData($file, $startDate, $endDate);

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
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $revenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total_revenue' => $totalRevenue,
            'by_date' => $revenue->pluck('revenue', 'date'),
        ];
    }

    private function getOrderStats($startDate, $endDate)
    {
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        return [
            'total_orders' => $totalOrders,
            'pending' => Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'pending')->count(),
            'processing' => Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'processing')->count(),
            'shipped' => Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'shipped')->count(),
            'delivered' => Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'delivered')->count(),
            'cancelled' => Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'cancelled')->count(),
        ];
    }

    private function getProductStats($startDate = null, $endDate = null)
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
            'active_customers' => User::whereHas('orders', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count(),
            'average_customer_value' => User::with('orders')->get()->map(function ($user) {
                return $user->orders->sum('total_amount');
            })->avg(),
        ];
    }

    private function getTopProducts($startDate, $endDate)
    {
        return Product::withCount(['orderItems as total_sold' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', '!=', 'cancelled');
            });
        }])
            ->withSum(['orderItems as total_revenue' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
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
        // Only get main categories (parent_id is null)
        $categories = Category::whereNull('parent_id')
            ->with(['products' => function ($query) use ($startDate, $endDate) {
                $query->withCount(['orderItems as total_sold' => function ($q) use ($startDate, $endDate) {
                    $q->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                        $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                            ->where('status', '!=', 'cancelled');
                    });
                }])
                    ->withSum(['orderItems as total_revenue' => function ($q) use ($startDate, $endDate) {
                        $q->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                            $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                                ->where('status', '!=', 'cancelled');
                        });
                    }], 'total_price');
            }])->get();

        return $categories->map(function ($category) {
            $totalSold = $category->products->sum('total_sold');
            $totalRevenue = $category->products->sum('total_revenue');

            return (object) [
                'id' => $category->id,
                'name' => $category->name,
                'total_sold' => $totalSold,
                'total_revenue' => $totalRevenue ?? 0,
                'color' => $this->getCategoryColor($category->id),
            ];
        })->sortByDesc('total_revenue')->take(6)->values();
    }

    private function getCategoryColor($categoryId)
    {
        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16'];

        return $colors[$categoryId % count($colors)];
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
        return User::withSum(['orders as total_spent' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled');
        }], 'total_amount')
            ->withCount(['orders as total_orders' => function ($query) use ($startDate, $endDate) {
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
            'returning_customers' => User::whereHas('orders', function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            })->count(),
            'vip_customers' => User::whereHas('orders', function ($query) {
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
                $customer->first_name.' '.$customer->last_name,
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

        $products = Product::withCount(['orderItems as total_sold' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', '!=', 'cancelled');
            });
        }])
            ->withSum(['orderItems as total_revenue' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
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
            // Format date as mm-dd-yyyy string for Excel compatibility
            $dateValue = $data->date;
            if ($dateValue instanceof \Carbon\Carbon) {
                $formattedDate = $dateValue->format('m-d-Y');
            } elseif (is_string($dateValue)) {
                // Parse and format as m-d-Y for Excel compatibility
                $formattedDate = Carbon::parse($dateValue)->format('m-d-Y');
            } else {
                $formattedDate = Carbon::parse($dateValue)->format('m-d-Y');
            }

            fputcsv($file, [
                $formattedDate,
                round($data->revenue ?? 0, 2),
                $data->orders ?? 0,
                round($data->avg_order_value ?? 0, 2),
            ]);
        }
    }

    /**
     * Export all comprehensive revenue data for the period.
     */
    private function exportAllRevenueData($file, $startDate, $endDate)
    {
        // Summary Section
        fputcsv($file, ['REVENUE ANALYTICS SUMMARY']);
        fputcsv($file, ['Period', $startDate->format('Y-m-d').' to '.$endDate->format('Y-m-d')]);
        fputcsv($file, []);

        $grossNetRevenue = $this->getGrossNetRevenue($startDate, $endDate);
        $revenueByChannel = $this->getRevenueByChannel($startDate, $endDate);
        $taxCollected = $this->getTaxCollected($startDate, $endDate);
        $profitability = $this->getProfitabilityAnalysis($startDate, $endDate);

        fputcsv($file, ['Gross Revenue', round($grossNetRevenue['gross_revenue'] ?? 0, 2)]);
        fputcsv($file, ['Discounts', round($grossNetRevenue['discounts'] ?? 0, 2)]);
        fputcsv($file, ['Refunds', round($grossNetRevenue['refunds'] ?? 0, 2)]);
        fputcsv($file, ['Net Revenue', round($grossNetRevenue['net_revenue'] ?? 0, 2)]);
        fputcsv($file, ['Tax Collected', round($taxCollected ?? 0, 2)]);
        fputcsv($file, ['Total Cost', round($profitability['total_cost'] ?? 0, 2)]);
        fputcsv($file, ['Net Profit', round($profitability['total_profit'] ?? 0, 2)]);
        fputcsv($file, ['Profit Margin', round($profitability['profit_margin'] ?? 0, 2).'%']);
        fputcsv($file, []);

        // Revenue by Channel
        fputcsv($file, ['REVENUE BY CHANNEL']);
        fputcsv($file, ['Channel', 'Revenue', 'Percentage']);
        fputcsv($file, ['Online', round($revenueByChannel['online'] ?? 0, 2), round($revenueByChannel['online_percentage'] ?? 0, 2).'%']);
        fputcsv($file, ['Phone', round($revenueByChannel['phone'] ?? 0, 2), round($revenueByChannel['phone_percentage'] ?? 0, 2).'%']);
        fputcsv($file, ['Wholesale', round($revenueByChannel['wholesale'] ?? 0, 2), round($revenueByChannel['wholesale_percentage'] ?? 0, 2).'%']);
        fputcsv($file, []);

        // Revenue by Payment Method
        fputcsv($file, ['REVENUE BY PAYMENT METHOD']);
        fputcsv($file, ['Payment Method', 'Revenue']);
        $revenueByPaymentMethod = $this->getRevenueByPaymentMethod($startDate, $endDate);
        foreach ($revenueByPaymentMethod as $method) {
            fputcsv($file, [
                ucfirst(str_replace('_', ' ', $method->payment_method ?? 'Unknown')),
                round($method->revenue ?? 0, 2),
            ]);
        }
        fputcsv($file, []);

        // Revenue by Category
        fputcsv($file, ['REVENUE BY CATEGORY']);
        fputcsv($file, ['Category', 'Revenue', 'Units Sold']);
        $revenueByCategory = $this->getSalesByCategory($startDate, $endDate);
        foreach ($revenueByCategory as $category) {
            fputcsv($file, [
                $category->name,
                round($category->total_revenue ?? 0, 2),
                $category->total_sold ?? 0,
            ]);
        }
        fputcsv($file, []);

        // Daily Revenue Breakdown
        fputcsv($file, ['DAILY REVENUE BREAKDOWN']);
        $this->exportRevenueData($file, $startDate, $endDate);
    }

    /**
     * Generate daily revenue data for charts.
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
     * Generate daily orders data for charts.
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
     * Generate chart labels for date range.
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
     * Generate daily new customers data for charts.
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
     * Calculate average lifetime value.
     */
    private function calculateAverageLifetimeValue()
    {
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalCustomers = User::count();

        return $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;
    }

    /**
     * Calculate repeat purchase rate.
     */
    private function calculateRepeatPurchaseRate($startDate, $endDate)
    {
        $totalCustomers = User::count();
        $repeatCustomers = User::has('orders', '>', 1)->count();

        return $totalCustomers > 0 ? ($repeatCustomers / $totalCustomers) * 100 : 0;
    }

    /**
     * Calculate revenue growth.
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
     * Calculate revenue per customer.
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

    /**
     * Get conversion metrics.
     */
    private function getConversionMetrics($startDate, $endDate)
    {
        $totalVisitors = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $paidOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')->count();

        return [
            'visitor_to_customer_rate' => $totalVisitors > 0 ? ($totalOrders / $totalVisitors) * 100 : 0,
            'order_conversion_rate' => $totalOrders > 0 ? ($paidOrders / $totalOrders) * 100 : 0,
            'cart_abandonment_rate' => $totalOrders > 0 ? (($totalOrders - $paidOrders) / $totalOrders) * 100 : 0,
            'average_session_duration' => $this->getAverageSessionDuration($startDate, $endDate),
        ];
    }

    /**
     * Get traffic sources (inferred from order patterns and user registration).
     */
    private function getTrafficSources($startDate, $endDate)
    {
        // Infer from user registration patterns and order metadata
        $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        // Direct traffic: users who registered and placed order same day
        $directUsers = User::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('orders', function ($query) {
                $query->whereRaw('DATE(users.created_at) = DATE(orders.created_at)');
            })
            ->count();

        // Social media: users with specific patterns (can be enhanced with tracking)
        $socialUsers = User::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('avatar')
            ->count();

        // Email marketing: users with newsletter subscribed
        $emailUsers = User::whereBetween('created_at', [$startDate, $endDate])
            ->where('newsletter_subscribed', true)
            ->count();

        // Organic search: remainder
        $organicUsers = max(0, $totalUsers - $directUsers - $socialUsers - $emailUsers);

        if ($totalUsers > 0) {
            return [
                'organic_search' => ($organicUsers / $totalUsers) * 100,
                'direct_traffic' => ($directUsers / $totalUsers) * 100,
                'social_media' => ($socialUsers / $totalUsers) * 100,
                'email_marketing' => ($emailUsers / $totalUsers) * 100,
                'paid_search' => max(0, 100 - (($organicUsers + $directUsers + $socialUsers + $emailUsers) / $totalUsers) * 100),
            ];
        }

        // Default fallback
        return [
            'organic_search' => 45.2,
            'direct_traffic' => 28.7,
            'social_media' => 12.3,
            'email_marketing' => 8.1,
            'paid_search' => 5.7,
        ];
    }

    /**
     * Get geographic data.
     */
    private function getGeographicData($startDate, $endDate)
    {
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('shipping_address')
            ->get();

        $geographicData = [];
        foreach ($orders as $order) {
            $address = $order->shipping_address;
            if (isset($address['city'])) {
                $city = $address['city'];
                if (! isset($geographicData[$city])) {
                    $geographicData[$city] = 0;
                }
                $geographicData[$city]++;
            }
        }

        arsort($geographicData);

        return array_slice($geographicData, 0, 10, true);
    }

    /**
     * Get seasonal trends.
     */
    private function getSeasonalTrends($startDate, $endDate)
    {
        $monthlyData = [];
        $current = $startDate->copy()->startOfMonth();

        while ($current->lte($endDate)) {
            $monthStart = $current->copy();
            $monthEnd = $current->copy()->endOfMonth();

            $revenue = Order::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('payment_status', 'paid')
                ->sum('total_amount');

            $orders = Order::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('payment_status', 'paid')
                ->count();

            $monthlyData[] = [
                'month' => $current->format('M Y'),
                'revenue' => $revenue,
                'orders' => $orders,
            ];

            $current->addMonth();
        }

        return $monthlyData;
    }

    /**
     * Get profitability analysis.
     */
    private function getProfitabilityAnalysis($startDate, $endDate)
    {
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->with('orderItems.product')
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalCost = 0;
        $totalProfit = 0;

        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    // Assuming cost is 60% of price (this would come from actual cost data)
                    $cost = $item->total_price * 0.6;
                    $totalCost += $cost;
                    $totalProfit += ($item->total_price - $cost);
                }
            }
        }

        return [
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'total_profit' => $totalProfit,
            'profit_margin' => $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0,
            'cost_ratio' => $totalRevenue > 0 ? ($totalCost / $totalRevenue) * 100 : 0,
        ];
    }

    /**
     * Get average session duration (simulated).
     */
    private function getAverageSessionDuration($startDate, $endDate)
    {
        // This would typically come from analytics service
        return 4.5; // minutes
    }

    /**
     * Get customer lifetime value analysis.
     */
    public function customerLifetimeValue(Request $request)
    {
        $dateRange = $request->get('date_range', '365');
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();

        $customers = User::whereHas('orders', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid');
        })->with(['orders' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid');
        }])->get();

        $clvData = [];
        foreach ($customers as $customer) {
            $totalSpent = $customer->orders->sum('total_amount');
            $orderCount = $customer->orders->count();
            $avgOrderValue = $orderCount > 0 ? $totalSpent / $orderCount : 0;
            $customerLifetime = $customer->created_at ? now()->diffInDays($customer->created_at) : 0;

            $clvData[] = [
                'customer_id' => $customer->id,
                'name' => $customer->first_name.' '.$customer->last_name,
                'email' => $customer->email,
                'total_spent' => $totalSpent,
                'order_count' => $orderCount,
                'avg_order_value' => $avgOrderValue,
                'customer_lifetime_days' => $customerLifetime,
                'clv' => $totalSpent,
            ];
        }

        // Sort by CLV
        usort($clvData, function ($a, $b) {
            return $b['clv'] <=> $a['clv'];
        });

        return response()->json([
            'success' => true,
            'data' => array_slice($clvData, 0, 50), // Top 50 customers
            'summary' => [
                'total_customers' => count($clvData),
                'avg_clv' => count($clvData) > 0 ? array_sum(array_column($clvData, 'clv')) / count($clvData) : 0,
                'total_clv' => array_sum(array_column($clvData, 'clv')),
            ],
        ]);
    }

    /**
     * Get product performance metrics.
     */
    public function productPerformance(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();

        $products = Product::withCount(['orderItems' => function ($q) use ($startDate, $endDate) {
            $q->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                    ->where('payment_status', 'paid');
            });
        }])->withSum(['orderItems as total_revenue' => function ($q) use ($startDate, $endDate) {
            $q->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                    ->where('payment_status', 'paid');
            });
        }], 'total_price')->orderBy('total_revenue', 'desc')->take(20)->get();

        return response()->json([
            'success' => true,
            'data' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'units_sold' => $product->order_items_count,
                    'revenue' => $product->total_revenue ?? 0,
                    'profit_margin' => $product->price > 0 ? (($product->price - ($product->price * 0.6)) / $product->price) * 100 : 0,
                ];
            }),
        ]);
    }

    /**
     * Get period comparison data (MoM, YoY).
     */
    private function getPeriodComparison($startDate, $endDate)
    {
        $currentRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $currentOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->count();

        $periodDays = $startDate->diffInDays($endDate);

        // Month over Month
        $previousMonthStart = $startDate->copy()->subMonth();
        $previousMonthEnd = $endDate->copy()->subMonth();
        $previousMonthRevenue = Order::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $previousMonthOrders = Order::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->where('status', '!=', 'cancelled')
            ->count();

        // Year over Year
        $previousYearStart = $startDate->copy()->subYear();
        $previousYearEnd = $endDate->copy()->subYear();
        $previousYearRevenue = Order::whereBetween('created_at', [$previousYearStart, $previousYearEnd])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $previousYearOrders = Order::whereBetween('created_at', [$previousYearStart, $previousYearEnd])
            ->where('status', '!=', 'cancelled')
            ->count();

        return [
            'mom' => [
                'revenue' => [
                    'current' => $currentRevenue,
                    'previous' => $previousMonthRevenue,
                    'change' => $previousMonthRevenue > 0 ? (($currentRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 : 0,
                ],
                'orders' => [
                    'current' => $currentOrders,
                    'previous' => $previousMonthOrders,
                    'change' => $previousMonthOrders > 0 ? (($currentOrders - $previousMonthOrders) / $previousMonthOrders) * 100 : 0,
                ],
            ],
            'yoy' => [
                'revenue' => [
                    'current' => $currentRevenue,
                    'previous' => $previousYearRevenue,
                    'change' => $previousYearRevenue > 0 ? (($currentRevenue - $previousYearRevenue) / $previousYearRevenue) * 100 : 0,
                ],
                'orders' => [
                    'current' => $currentOrders,
                    'previous' => $previousYearOrders,
                    'change' => $previousYearOrders > 0 ? (($currentOrders - $previousYearOrders) / $previousYearOrders) * 100 : 0,
                ],
            ],
        ];
    }

    /**
     * Get discount usage statistics.
     */
    private function getDiscountUsage($startDate, $endDate)
    {
        $ordersWithDiscount = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->where('discount_amount', '>', 0)
            ->get();

        return [
            'total_discounts' => $ordersWithDiscount->sum('discount_amount'),
            'orders_with_discount' => $ordersWithDiscount->count(),
            'average_discount' => $ordersWithDiscount->count() > 0 ? $ordersWithDiscount->avg('discount_amount') : 0,
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->count(),
        ];
    }

    /**
     * Generate weekly revenue data.
     */
    private function generateWeeklyRevenueData($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfWeek();

        while ($current->lte($endDate)) {
            $weekEnd = $current->copy()->endOfWeek();
            if ($weekEnd->gt($endDate)) {
                $weekEnd = $endDate->copy();
            }

            $weekRevenue = Order::whereBetween('created_at', [$current, $weekEnd])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

            $data[] = (float) $weekRevenue;
            $current->addWeek();
        }

        return $data;
    }

    /**
     * Generate monthly revenue data.
     */
    private function generateMonthlyRevenueData($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfMonth();

        while ($current->lte($endDate)) {
            $monthEnd = $current->copy()->endOfMonth();
            if ($monthEnd->gt($endDate)) {
                $monthEnd = $endDate->copy();
            }

            $monthRevenue = Order::whereBetween('created_at', [$current, $monthEnd])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

            $data[] = (float) $monthRevenue;
            $current->addMonth();
        }

        return $data;
    }

    /**
     * Get new vs returning customers.
     */
    private function getNewVsReturningCustomers($startDate, $endDate)
    {
        $newCustomers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        $returningCustomers = User::whereHas('orders', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled');
        })->where('created_at', '<', $startDate)->count();

        $totalActiveCustomers = $newCustomers + $returningCustomers;

        return [
            'new' => $newCustomers,
            'returning' => $returningCustomers,
            'total' => $totalActiveCustomers,
            'new_percentage' => $totalActiveCustomers > 0 ? ($newCustomers / $totalActiveCustomers) * 100 : 0,
            'returning_percentage' => $totalActiveCustomers > 0 ? ($returningCustomers / $totalActiveCustomers) * 100 : 0,
        ];
    }

    /**
     * Get customer lifetime value data.
     */
    private function getCustomerLifetimeValueData()
    {
        $customers = User::withSum('orders as total_spent', 'total_amount')
            ->withCount('orders')
            ->whereHas('orders', function ($query) {
                $query->where('status', '!=', 'cancelled');
            })
            ->get();

        $clvData = $customers->map(function ($customer) {
            $lifetimeDays = $customer->created_at ? now()->diffInDays($customer->created_at) : 1;

            return [
                'customer_id' => $customer->id,
                'name' => trim($customer->first_name.' '.$customer->last_name),
                'email' => $customer->email,
                'total_spent' => $customer->total_spent ?? 0,
                'order_count' => $customer->orders_count,
                'avg_order_value' => $customer->orders_count > 0 ? ($customer->total_spent ?? 0) / $customer->orders_count : 0,
                'lifetime_days' => $lifetimeDays,
                'clv' => $customer->total_spent ?? 0,
            ];
        })->sortByDesc('clv')->take(10)->values();

        return $clvData;
    }

    /**
     * Get cohort analysis.
     */
    private function getCohortAnalysis()
    {
        $cohorts = [];
        $months = [];

        for ($i = 11; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            $months[] = $monthStart->format('M Y');

            $cohortCustomers = User::whereBetween('created_at', [$monthStart, $monthEnd])->get();

            $cohortData = [];
            foreach ($cohortCustomers as $customer) {
                $customerOrders = $customer->orders()
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('created_at')
                    ->get();

                if ($customerOrders->count() > 0) {
                    $firstOrderDate = $customerOrders->first()->created_at;
                    $cohortMonth = $firstOrderDate->format('M Y');

                    if (! isset($cohortData[$cohortMonth])) {
                        $cohortData[$cohortMonth] = 0;
                    }
                    $cohortData[$cohortMonth]++;
                }
            }

            $cohorts[] = [
                'month' => $monthStart->format('M Y'),
                'customers' => $cohortCustomers->count(),
                'repeat_customers' => $cohortData,
            ];
        }

        return [
            'months' => $months,
            'cohorts' => $cohorts,
        ];
    }

    /**
     * Calculate customer segments.
     */
    private function calculateCustomerSegments()
    {
        $customers = User::withSum('orders as total_spent', 'total_amount')
            ->whereHas('orders', function ($query) {
                $query->where('status', '!=', 'cancelled');
            })
            ->get();

        $totalSpent = $customers->sum('total_spent');
        $avgSpent = $customers->count() > 0 ? $totalSpent / $customers->count() : 0;

        $highValue = $customers->filter(function ($customer) use ($avgSpent) {
            return ($customer->total_spent ?? 0) >= $avgSpent * 2;
        })->count();

        $mediumValue = $customers->filter(function ($customer) use ($avgSpent) {
            return ($customer->total_spent ?? 0) >= $avgSpent && ($customer->total_spent ?? 0) < $avgSpent * 2;
        })->count();

        $lowValue = $customers->filter(function ($customer) use ($avgSpent) {
            return ($customer->total_spent ?? 0) < $avgSpent;
        })->count();

        $total = $highValue + $mediumValue + $lowValue;

        return [
            'high' => $total > 0 ? ($highValue / $total) * 100 : 0,
            'medium' => $total > 0 ? ($mediumValue / $total) * 100 : 0,
            'low' => $total > 0 ? ($lowValue / $total) * 100 : 0,
        ];
    }

    /**
     * Get product performance data with all metrics.
     */
    private function getProductPerformanceData($startDate, $endDate, $categoryFilter = null, $sortBy = 'units_sold', $sortOrder = 'desc')
    {
        $query = Product::select('products.*') // Explicitly select all product columns including view_count
            ->withCount(['orderItems as units_sold' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                    $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', '!=', 'cancelled');
                });
            }])
            ->withCount(['orderItems as order_count' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                    $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', '!=', 'cancelled');
                })->distinct('order_id');
            }])
            ->withSum(['orderItems as revenue' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                    $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', '!=', 'cancelled');
                });
            }], 'total_price')
            ->with(['category']);

        // Filter by category if provided
        if ($categoryFilter && $categoryFilter !== 'all') {
            $query->whereHas('category', function ($q) use ($categoryFilter) {
                $q->where('id', $categoryFilter)
                    ->orWhere('parent_id', $categoryFilter);
            });
        }

        $products = $query->get();

        $mappedProducts = $products->map(function ($product) {
            $profitMargin = 0;
            if ($product->cost_price && $product->price > 0) {
                $profitMargin = (($product->price - $product->cost_price) / $product->price) * 100;
            } elseif ($product->price > 0) {
                // Default 40% margin if no cost data
                $profitMargin = 40;
            }

            // Use actual view count from database (always use it if it exists, even if 0)
            // Only fall back to estimate if view_count is truly null (not set yet)
            $actualViews = $product->view_count;
            $orderCount = $product->order_count ?? 0;
            $estimatedViews = $orderCount * 10; // Estimate 10 views per order as fallback
            // Use actual views if it's not null, otherwise use estimate
            $views = $actualViews !== null ? $actualViews : $estimatedViews;

            // Conversion rate: units sold / views
            $conversionRate = $views > 0 ? (($product->units_sold ?? 0) / $views) * 100 : 0;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->price,
                'units_sold' => $product->units_sold ?? 0,
                'revenue' => $product->revenue ?? 0,
                'profit_margin' => round($profitMargin, 2),
                'views' => $views,
                'conversion_rate' => round($conversionRate, 2),
                'category' => $product->category->name ?? 'Uncategorized',
                'category_id' => $product->category_id ?? null,
            ];
        });

        // Sort the collection
        $mappedProducts = $mappedProducts->sortBy(function ($product) use ($sortBy) {
            return $product[$sortBy];
        }, SORT_REGULAR, $sortOrder === 'desc');

        return $mappedProducts->values();
    }

    /**
     * Get best sellers.
     */
    private function getBestSellers($startDate, $endDate)
    {
        return $this->getTopProducts($startDate, $endDate);
    }

    /**
     * Get worst performers.
     */
    private function getWorstPerformers($startDate, $endDate)
    {
        return Product::withCount(['orderItems as total_sold' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', '!=', 'cancelled');
            });
        }])
            ->withSum(['orderItems as total_revenue' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                    $orderQuery->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', '!=', 'cancelled');
                });
            }], 'total_price')
            ->orderBy('total_sold', 'asc')
            ->take(10)
            ->get();
    }

    /**
     * Get most viewed products (using order frequency as proxy).
     */
    private function getMostViewedProducts($startDate, $endDate)
    {
        // Use actual view_count from database column, sorted by view_count descending
        return Product::where('is_active', true)
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Calculate stock turnover rate.
     */
    private function calculateStockTurnoverRate($startDate, $endDate)
    {
        // Cost of Goods Sold
        $cogs = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled');
        })
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return ($item->product && $item->product->cost_price)
                    ? $item->product->cost_price * $item->quantity
                    : ($item->unit_price * $item->quantity * 0.6); // Default 60% cost
            });

        // Average Inventory (using current stock value)
        $averageInventory = Product::where('manage_stock', true)
            ->get()
            ->sum(function ($product) {
                $cost = $product->cost_price ?? ($product->price * 0.6);

                return $cost * $product->stock_quantity;
            });

        $turnoverRate = $averageInventory > 0 ? $cogs / $averageInventory : 0;

        return [
            'cogs' => $cogs,
            'average_inventory' => $averageInventory,
            'turnover_rate' => round($turnoverRate, 2),
        ];
    }

    /**
     * Get gross vs net revenue.
     */
    private function getGrossNetRevenue($startDate, $endDate)
    {
        $grossRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $totalDiscounts = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('discount_amount');

        // Refunds: orders with return_status
        $refundedOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('return_status', ['requested', 'approved', 'received', 'completed'])
            ->sum('total_amount');

        $netRevenue = $grossRevenue - $totalDiscounts - ($refundedOrders * 0.5); // Assume 50% of returned orders are refunded

        return [
            'gross_revenue' => $grossRevenue,
            'discounts' => $totalDiscounts,
            'refunds' => $refundedOrders * 0.5,
            'net_revenue' => max(0, $netRevenue),
        ];
    }

    /**
     * Get revenue by channel.
     */
    private function getRevenueByChannel($startDate, $endDate)
    {
        // Infer channels from payment method or order source
        $onlineRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->whereIn('payment_method', ['card', 'online', 'paypal', 'gcash', 'paymaya', 'bank_transfer'])
            ->sum('total_amount');

        $phoneRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->where('payment_method', 'cash_on_delivery')
            ->sum('total_amount');

        $wholesaleRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->where('payment_method', 'wholesale')
            ->sum('total_amount');

        // If no wholesale, assume it's mostly online
        if ($wholesaleRevenue == 0) {
            $wholesaleRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->whereNotNull('notes')
                ->where('notes', 'like', '%wholesale%')
                ->sum('total_amount');
        }

        $totalRevenue = $onlineRevenue + $phoneRevenue + $wholesaleRevenue;

        return [
            'online' => $onlineRevenue,
            'phone' => $phoneRevenue,
            'wholesale' => $wholesaleRevenue,
            'total' => $totalRevenue,
            'online_percentage' => $totalRevenue > 0 ? ($onlineRevenue / $totalRevenue) * 100 : 0,
            'phone_percentage' => $totalRevenue > 0 ? ($phoneRevenue / $totalRevenue) * 100 : 0,
            'wholesale_percentage' => $totalRevenue > 0 ? ($wholesaleRevenue / $totalRevenue) * 100 : 0,
        ];
    }

    /**
     * Get tax collected.
     */
    private function getTaxCollected($startDate, $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('tax_amount');
    }

    /**
     * Enhanced conversion rate calculation.
     */
    private function calculateConversionRate($startDate, $endDate)
    {
        // Use registered users who made purchases vs total registered users
        $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $usersWithOrders = User::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('orders', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', '!=', 'cancelled');
            })
            ->count();

        // If we have visitor data, use it, otherwise use user registration as proxy
        if ($totalUsers > 0) {
            return ($usersWithOrders / $totalUsers) * 100;
        }

        // Fallback: assume 1000 visitors and calculate from orders
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->distinct('user_id')
            ->count('user_id');

        $estimatedVisitors = max(100, $totalOrders * 20); // Assume 5% conversion rate

        return $estimatedVisitors > 0 ? ($totalOrders / $estimatedVisitors) * 100 : 0;
    }
}
