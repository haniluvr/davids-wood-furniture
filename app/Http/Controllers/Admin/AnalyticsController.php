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

        // Handle custom date range
        if ($dateRange === 'custom') {
            $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays(30);
            $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();
        } else {
            $days = (int) $dateRange;
            $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays($days);
            $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();
        }

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

        // Calculate percentage changes for dashboard badges (using sales percentage changes)
        $percentageChanges = $this->getSalesPercentageChanges($startDate, $endDate);

        // Period comparison data
        $periodComparison = $this->getPeriodComparison($startDate, $endDate);

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
            'endDate',
            'percentageChanges',
            'periodComparison'
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

        // Calculate total expenses for the period (for display in view)
        $totalExpenses = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled');
        })
            ->with('product')
            ->get()
            ->sum(function ($item) {
                $costPrice = $item->product && $item->product->cost_price
                    ? $item->product->cost_price
                    : 0;

                return $costPrice * $item->quantity;
            });

        // Calculate percentage changes for badges
        $percentageChanges = $this->getSalesPercentageChanges($startDate, $endDate);

        // Support period offset for Sales Trend chart navigation
        $salesPeriodOffset = $request->get('sales_period_offset', 0);
        $salesCurrentPeriod = $request->get('sales_current_period', 'month'); // Default to month

        // Calculate date ranges based on period and offset for Sales Trend
        // Same logic as Customer Acquisition and Revenue Trend:
        // - Day filter: Show the 7 days in the week containing the selected day
        // - Week filter: Show the 4 weeks of the month containing the selected week
        // - Month filter: Show the 12 months of the year containing the selected month
        $today = Carbon::now();
        $salesPeriodRanges = $this->calculateAcquisitionPeriodRanges($today, $salesPeriodOffset);

        // Generate period-based sales trend data
        // Day filter: Show 7 days in the week (daily data)
        // Week filter: Show 4 weeks in the month (weekly data)
        // Month filter: Show 12 months in the year (monthly data)
        $dailyRevenue = $this->generateDailyRevenueData($salesPeriodRanges['day']['start'], $salesPeriodRanges['day']['end']);
        $weeklyRevenue = $this->generateWeeklyRevenueDataForPeriod($salesPeriodRanges['week']['start'], $salesPeriodRanges['week']['end']);
        $monthlyRevenue = $this->generateMonthlyRevenueDataForPeriod($salesPeriodRanges['month']['start'], $salesPeriodRanges['month']['end']);
        $dailyExpenses = $this->generateDailyExpensesData($salesPeriodRanges['day']['start'], $salesPeriodRanges['day']['end']);
        $weeklyExpenses = $this->generateWeeklyExpensesDataForPeriod($salesPeriodRanges['week']['start'], $salesPeriodRanges['week']['end']);
        $monthlyExpenses = $this->generateMonthlyExpensesDataForPeriod($salesPeriodRanges['month']['start'], $salesPeriodRanges['month']['end']);
        $dailyProfit = $this->generateDailyProfitData($dailyRevenue, $dailyExpenses);
        $weeklyProfit = $this->generateWeeklyProfitData($weeklyRevenue, $weeklyExpenses);
        $monthlyProfit = $this->generateMonthlyProfitData($monthlyRevenue, $monthlyExpenses);
        $dailyOrders = $this->generateDailyOrdersData($salesPeriodRanges['day']['start'], $salesPeriodRanges['day']['end']);
        $weeklyOrders = $this->generateWeeklyOrdersDataForPeriod($salesPeriodRanges['week']['start'], $salesPeriodRanges['week']['end']);
        $monthlyOrders = $this->generateMonthlyOrdersDataForPeriod($salesPeriodRanges['month']['start'], $salesPeriodRanges['month']['end']);

        // Generate labels for each period
        $dailyLabels = $this->generateDailyLabels($salesPeriodRanges['day']['start'], $salesPeriodRanges['day']['end']);
        $weeklyLabels = $this->generateWeeklyLabels($salesPeriodRanges['week']['start'], $salesPeriodRanges['week']['end']);
        $monthlyLabels = $this->generateMonthlyLabels($salesPeriodRanges['month']['start'], $salesPeriodRanges['month']['end']);

        // Keep original chart labels for other charts (not period-based)
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
            'dailyExpenses',
            'weeklyExpenses',
            'monthlyExpenses',
            'dailyProfit',
            'weeklyProfit',
            'monthlyProfit',
            'dailyOrders',
            'weeklyOrders',
            'monthlyOrders',
            'dailyLabels',
            'weeklyLabels',
            'monthlyLabels',
            'percentageChanges',
            'chartLabels',
            'salesPeriodOffset',
            'salesCurrentPeriod',
            'periodComparison',
            'discountUsage',
            'totalExpenses'
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

        // Calculate percentage changes for badges
        $percentageChanges = $this->getCustomerPercentageChanges($startDate, $endDate);

        // Support period offset for Customer Acquisition chart navigation
        $acquisitionPeriodOffset = $request->get('acquisition_period_offset', 0);
        $acquisitionCurrentPeriod = $request->get('acquisition_current_period', 'month'); // Default to month

        // Calculate date ranges based on period and offset for Customer Acquisition
        // Revised logic:
        // - Day filter: Show the 7 days in the week containing the selected day
        // - Week filter: Show the 4 weeks of the month containing the selected week
        // - Month filter: Show the 12 months of the year containing the selected month
        $today = Carbon::now();
        $acquisitionPeriodRanges = $this->calculateAcquisitionPeriodRanges($today, $acquisitionPeriodOffset);

        // Generate period-based customer acquisition data
        // Day filter: Show 7 days in the week (daily data)
        // Week filter: Show 4 weeks in the month (weekly data)
        // Month filter: Show 12 months in the year (monthly data)
        $dailyNewCustomers = $this->generateDailyNewCustomersData($acquisitionPeriodRanges['day']['start'], $acquisitionPeriodRanges['day']['end']);
        $weeklyNewCustomers = $this->generateWeeklyNewCustomersData($acquisitionPeriodRanges['week']['start'], $acquisitionPeriodRanges['week']['end']);
        $monthlyNewCustomers = $this->generateMonthlyNewCustomersData($acquisitionPeriodRanges['month']['start'], $acquisitionPeriodRanges['month']['end']);

        // Generate labels for each period
        $dailyLabels = $this->generateDailyLabels($acquisitionPeriodRanges['day']['start'], $acquisitionPeriodRanges['day']['end']);
        $weeklyLabels = $this->generateWeeklyLabels($acquisitionPeriodRanges['week']['start'], $acquisitionPeriodRanges['week']['end']);
        $monthlyLabels = $this->generateMonthlyLabels($acquisitionPeriodRanges['month']['start'], $acquisitionPeriodRanges['month']['end']);

        // Also keep original chart labels for other charts (not period-based)
        $chartLabels = $this->generateChartLabels($startDate, $endDate);

        // Customer segments - calculate from actual data
        $customerSegmentsCalculated = $this->calculateCustomerSegments();

        return view('admin.analytics.customers', compact(
            'percentageChanges',
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
            'weeklyNewCustomers',
            'monthlyNewCustomers',
            'dailyLabels',
            'weeklyLabels',
            'monthlyLabels',
            'chartLabels',
            'newVsReturning',
            'clvData',
            'geographicData',
            'cohortAnalysis',
            'customerSegmentsCalculated',
            'acquisitionPeriodOffset',
            'acquisitionCurrentPeriod'
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

        // Calculate percentage changes for badges
        $percentageChanges = $this->getProductPercentageChanges($startDate, $endDate);

        // Category sales data
        $categorySales = $this->getSalesByCategory($startDate, $endDate);

        // Get main categories for filter
        $mainCategories = Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get();

        // Get filter and sort parameters
        $categoryFilter = $request->get('category');
        $sortBy = $request->get('sort_by', 'units_sold');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validate sortBy parameter
        $validSortFields = ['units_sold', 'revenue', 'profit_margin', 'views', 'conversion_rate'];
        if (empty($sortBy) || ! in_array($sortBy, $validSortFields)) {
            $sortBy = 'units_sold';
        }

        // Generate period-based product data for chart
        // Each period shows top 10 products for that specific period
        // Support period offset for navigation (0 = current, 1 = previous, etc.)
        $periodOffset = $request->get('period_offset', 0);
        $currentPeriod = $request->get('current_period', 'week'); // Default to week

        // Calculate date ranges based on period and offset
        $today = Carbon::now();
        $periodRanges = $this->calculatePeriodRanges($today, $periodOffset);

        $topProductsDaily = $this->getTopProductsByPeriod($periodRanges['day']['start'], $periodRanges['day']['end'], 'day');
        $topProductsWeekly = $this->getTopProductsByPeriod($periodRanges['week']['start'], $periodRanges['week']['end'], 'week');
        $topProductsMonthly = $this->getTopProductsByPeriod($periodRanges['month']['start'], $periodRanges['month']['end'], 'month');

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
            'sortOrder',
            'topProductsDaily',
            'percentageChanges',
            'topProductsWeekly',
            'topProductsMonthly',
            'periodOffset',
            'currentPeriod',
            'periodRanges'
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

        // Calculate percentage changes for badges
        $percentageChanges = $this->getRevenuePercentageChanges($startDate, $endDate);

        // Support period offset for Revenue Trend chart navigation
        $revenuePeriodOffset = $request->get('revenue_period_offset', 0);
        $revenueCurrentPeriod = $request->get('revenue_current_period', 'month'); // Default to month

        // Calculate date ranges based on period and offset for Revenue Trend
        // Same logic as Customer Acquisition:
        // - Day filter: Show the 7 days in the week containing the selected day
        // - Week filter: Show the 4 weeks of the month containing the selected week
        // - Month filter: Show the 12 months of the year containing the selected month
        $today = Carbon::now();
        $revenuePeriodRanges = $this->calculateAcquisitionPeriodRanges($today, $revenuePeriodOffset);

        // Generate period-based revenue trend data
        // Day filter: Show 7 days in the week (daily data)
        // Week filter: Show 4 weeks in the month (weekly data)
        // Month filter: Show 12 months in the year (monthly data)
        $dailyRevenue = $this->generateDailyRevenueData($revenuePeriodRanges['day']['start'], $revenuePeriodRanges['day']['end']);
        $weeklyRevenue = $this->generateWeeklyRevenueDataForPeriod($revenuePeriodRanges['week']['start'], $revenuePeriodRanges['week']['end']);
        $monthlyRevenue = $this->generateMonthlyRevenueDataForPeriod($revenuePeriodRanges['month']['start'], $revenuePeriodRanges['month']['end']);

        // Generate labels for each period
        $dailyLabels = $this->generateDailyLabels($revenuePeriodRanges['day']['start'], $revenuePeriodRanges['day']['end']);
        $weeklyLabels = $this->generateWeeklyLabels($revenuePeriodRanges['week']['start'], $revenuePeriodRanges['week']['end']);
        $monthlyLabels = $this->generateMonthlyLabels($revenuePeriodRanges['month']['start'], $revenuePeriodRanges['month']['end']);

        // Keep original chart labels for other charts (not period-based)
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
            'percentageChanges',
            'revenueGrowth',
            'revenuePerCustomer',
            'dailyRevenue',
            'weeklyRevenue',
            'monthlyRevenue',
            'dailyLabels',
            'weeklyLabels',
            'monthlyLabels',
            'chartLabels',
            'revenueByCategory',
            'previousStartDate',
            'previousEndDate',
            'previousPeriodRevenue',
            'currentPeriodRevenue',
            'grossNetRevenue',
            'revenueByChannel',
            'taxCollected',
            'profitability',
            'revenuePeriodOffset',
            'revenueCurrentPeriod'
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
                    $this->exportAllSalesData($file, $startDate, $endDate);

                    break;
                case 'customers':
                    $this->exportCustomerData($file, $startDate, $endDate);

                    break;
                case 'products':
                    $this->exportAllProductData($file, $startDate, $endDate);

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

    /**
     * Export all comprehensive sales analytics data.
     */
    private function exportAllSalesData($file, $startDate, $endDate)
    {
        // Summary Section
        fputcsv($file, ['SALES ANALYTICS SUMMARY']);
        fputcsv($file, ['Period', $startDate->format('m-d-Y').' to '.$endDate->format('m-d-Y')]);
        fputcsv($file, []);

        $salesData = $this->getSalesData($startDate, $endDate);
        $orderStats = $this->getOrderStats($startDate, $endDate);
        $conversionRate = $this->calculateConversionRate($startDate, $endDate);

        // Calculate total expenses and profit
        $totalExpenses = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled');
        })
            ->with('product')
            ->get()
            ->sum(function ($item) {
                $costPrice = $item->product && $item->product->cost_price
                    ? $item->product->cost_price
                    : 0;

                return $costPrice * $item->quantity;
            });

        fputcsv($file, ['OVERALL STATISTICS']);
        fputcsv($file, ['Total Sales (Orders)', $orderStats['total_orders'] ?? 0]);
        fputcsv($file, ['Total Revenue', round($salesData['total_revenue'] ?? 0, 2)]);
        fputcsv($file, ['Total Expenses', round($totalExpenses, 2)]);
        fputcsv($file, ['Total Profit', round(($salesData['total_revenue'] ?? 0) - $totalExpenses, 2)]);
        fputcsv($file, ['Average Order Value', round($salesData['average_order_value'] ?? 0, 2)]);
        fputcsv($file, ['Conversion Rate', round($conversionRate, 2).'%']);
        fputcsv($file, []);

        // Discount Usage
        $discountUsage = $this->getDiscountUsage($startDate, $endDate);
        $totalOrders = $orderStats['total_orders'] ?? 0;
        $discountRate = $totalOrders > 0 ? (($discountUsage['orders_with_discount'] ?? 0) / $totalOrders) * 100 : 0;

        fputcsv($file, ['DISCOUNT USAGE']);
        fputcsv($file, ['Total Discounts', round($discountUsage['total_discounts'] ?? 0, 2)]);
        fputcsv($file, ['Orders with Discount', $discountUsage['orders_with_discount'] ?? 0]);
        fputcsv($file, ['Average Discount', round($discountUsage['average_discount'] ?? 0, 2)]);
        fputcsv($file, ['Discount Rate', round($discountRate, 1).'%']);
        fputcsv($file, []);

        // Period Comparison
        $periodComparison = $this->getPeriodComparison($startDate, $endDate);

        fputcsv($file, ['PERIOD COMPARISON - MONTH OVER MONTH']);
        fputcsv($file, ['Metric', 'Current', 'Previous', 'Change %']);
        fputcsv($file, [
            'Revenue',
            round($periodComparison['mom']['revenue']['current'] ?? 0, 2),
            round($periodComparison['mom']['revenue']['previous'] ?? 0, 2),
            round($periodComparison['mom']['revenue']['change'] ?? 0, 1).'%',
        ]);
        fputcsv($file, [
            'Orders',
            $periodComparison['mom']['orders']['current'] ?? 0,
            $periodComparison['mom']['orders']['previous'] ?? 0,
            round($periodComparison['mom']['orders']['change'] ?? 0, 1).'%',
        ]);
        fputcsv($file, []);

        fputcsv($file, ['PERIOD COMPARISON - YEAR OVER YEAR']);
        fputcsv($file, ['Metric', 'Current', 'Previous', 'Change %']);
        fputcsv($file, [
            'Revenue',
            round($periodComparison['yoy']['revenue']['current'] ?? 0, 2),
            round($periodComparison['yoy']['revenue']['previous'] ?? 0, 2),
            round($periodComparison['yoy']['revenue']['change'] ?? 0, 1).'%',
        ]);
        fputcsv($file, [
            'Orders',
            $periodComparison['yoy']['orders']['current'] ?? 0,
            $periodComparison['yoy']['orders']['previous'] ?? 0,
            round($periodComparison['yoy']['orders']['change'] ?? 0, 1).'%',
        ]);
        fputcsv($file, []);

        // Sales by Product Category
        fputcsv($file, ['SALES BY PRODUCT CATEGORY']);
        fputcsv($file, ['Category', 'Revenue', 'Units Sold']);

        $salesByCategory = $this->getSalesByCategory($startDate, $endDate);
        foreach ($salesByCategory as $category) {
            fputcsv($file, [
                $category->name,
                round($category->total_revenue ?? 0, 2),
                $category->total_sold ?? 0,
            ]);
        }
        fputcsv($file, []);

        // Top Products
        fputcsv($file, ['TOP PRODUCTS']);
        fputcsv($file, ['Rank', 'Product Name', 'Units Sold', 'Revenue']);

        $topProducts = $this->getTopProducts($startDate, $endDate);
        $rank = 1;
        foreach ($topProducts->take(20) as $product) {
            fputcsv($file, [
                $rank++,
                $product->name,
                $product->total_sold ?? 0,
                round($product->total_revenue ?? 0, 2),
            ]);
        }
        fputcsv($file, []);

        // Daily Sales Breakdown (Sales Trend)
        fputcsv($file, ['DAILY SALES BREAKDOWN']);
        fputcsv($file, ['Date', 'Revenue', 'Expenses', 'Profit', 'Orders', 'Average Order Value']);

        $dailySales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue, AVG(total_amount) as avg_order_value')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($dailySales as $data) {
            // Calculate daily expenses
            $dailyExpenses = OrderItem::whereHas('order', function ($query) use ($data) {
                $query->whereDate('created_at', $data->date)
                    ->where('status', '!=', 'cancelled');
            })
                ->with('product')
                ->get()
                ->sum(function ($item) {
                    $costPrice = $item->product && $item->product->cost_price
                        ? $item->product->cost_price
                        : 0;

                    return $costPrice * $item->quantity;
                });

            $dailyProfit = ($data->revenue ?? 0) - $dailyExpenses;

            // Format date as mm-dd-yyyy for Excel compatibility
            $formattedDate = Carbon::parse($data->date)->format('m-d-Y');

            fputcsv($file, [
                $formattedDate,
                round($data->revenue ?? 0, 2),
                round($dailyExpenses, 2),
                round($dailyProfit, 2),
                $data->orders ?? 0,
                round($data->avg_order_value ?? 0, 2),
            ]);
        }
    }

    private function exportCustomerData($file, $startDate, $endDate)
    {
        // Summary Section
        fputcsv($file, ['CUSTOMER ANALYTICS SUMMARY']);
        fputcsv($file, ['Period', $startDate->format('m-d-Y').' to '.$endDate->format('m-d-Y')]);
        fputcsv($file, []);

        $customerStats = $this->getCustomerStats($startDate, $endDate);
        $newVsReturning = $this->getNewVsReturningCustomers($startDate, $endDate);
        $clvData = $this->getCustomerLifetimeValueData();
        $geographicData = $this->getGeographicData($startDate, $endDate);
        $cohortAnalysis = $this->getCohortAnalysis();
        $customerSegments = $this->getCustomerSegments();
        $customerSegmentsCalculated = $this->calculateCustomerSegments();

        // Overall Statistics
        fputcsv($file, ['OVERALL STATISTICS']);
        fputcsv($file, ['Total Customers', User::count()]);
        fputcsv($file, ['New Customers (Period)', $customerStats['new_customers'] ?? 0]);
        fputcsv($file, ['Returning Customers (Period)', $customerStats['returning_customers'] ?? 0]);
        fputcsv($file, ['Average Lifetime Value', round($this->calculateAverageLifetimeValue(), 2)]);
        fputcsv($file, ['Repeat Purchase Rate', round($this->calculateRepeatPurchaseRate($startDate, $endDate), 2).'%']);
        fputcsv($file, []);

        // New vs Returning Customers
        fputcsv($file, ['NEW VS RETURNING CUSTOMERS']);
        fputcsv($file, ['Category', 'Count', 'Percentage']);
        fputcsv($file, ['New Customers', $newVsReturning['new'] ?? 0, round($newVsReturning['new_percentage'] ?? 0, 2).'%']);
        fputcsv($file, ['Returning Customers', $newVsReturning['returning'] ?? 0, round($newVsReturning['returning_percentage'] ?? 0, 2).'%']);
        fputcsv($file, []);

        // Top Customers by CLV
        fputcsv($file, ['TOP CUSTOMERS BY LIFETIME VALUE']);
        fputcsv($file, ['Rank', 'Name', 'Email', 'Total Orders', 'Total Spent (CLV)', 'Average Order Value']);
        foreach ($clvData as $index => $customer) {
            fputcsv($file, [
                $index + 1,
                $customer['name'],
                $customer['email'],
                $customer['order_count'],
                round($customer['total_spent'], 2),
                round($customer['avg_order_value'], 2),
            ]);
        }
        fputcsv($file, []);

        // Customer Segments
        fputcsv($file, ['CUSTOMER SEGMENTS']);
        fputcsv($file, ['Segment', 'Count', 'Percentage']);
        fputcsv($file, ['High Value', $customerSegmentsCalculated['high'] ?? 0, round((($customerSegmentsCalculated['high'] ?? 0) / max(User::count(), 1)) * 100, 2).'%']);
        fputcsv($file, ['Medium Value', $customerSegmentsCalculated['medium'] ?? 0, round((($customerSegmentsCalculated['medium'] ?? 0) / max(User::count(), 1)) * 100, 2).'%']);
        fputcsv($file, ['Low Value', $customerSegmentsCalculated['low'] ?? 0, round((($customerSegmentsCalculated['low'] ?? 0) / max(User::count(), 1)) * 100, 2).'%']);
        fputcsv($file, []);

        // Cohort Analysis
        if (isset($cohortAnalysis['cohorts']) && ! empty($cohortAnalysis['cohorts'])) {
            fputcsv($file, ['COHORT ANALYSIS']);
            fputcsv($file, ['Cohort Month', 'New Customers', 'Returning Customers']);
            foreach (array_slice($cohortAnalysis['cohorts'], -12) as $cohort) {
                $returning = 0;
                if (isset($cohort['repeat_customers']) && is_array($cohort['repeat_customers'])) {
                    $returning = array_sum($cohort['repeat_customers']);
                }
                fputcsv($file, [
                    $cohort['month'],
                    $cohort['customers'],
                    $returning,
                ]);
            }
            fputcsv($file, []);
        }

        // Geographic Distribution by Region
        if (isset($geographicData['regions']) && ! empty($geographicData['regions'])) {
            fputcsv($file, ['GEOGRAPHIC DISTRIBUTION BY REGION']);
            fputcsv($file, ['Region', 'Total Orders', 'Number of Cities']);
            foreach ($geographicData['regions'] as $regionData) {
                fputcsv($file, [
                    $regionData['region'],
                    $regionData['count'],
                    count($regionData['cities']),
                ]);
            }
            fputcsv($file, []);

            // Cities within Regions
            fputcsv($file, ['GEOGRAPHIC DISTRIBUTION BY CITY']);
            fputcsv($file, ['Region', 'City', 'Province', 'Order Count']);
            foreach ($geographicData['regions'] as $regionData) {
                foreach ($regionData['cities'] as $cityData) {
                    fputcsv($file, [
                        $regionData['region'],
                        $cityData['city'],
                        $cityData['province'] ?? 'N/A',
                        $cityData['count'],
                    ]);
                }
            }
            fputcsv($file, []);
        }

        // All Customers Detailed List
        fputcsv($file, ['ALL CUSTOMERS DETAILED LIST']);
        fputcsv($file, ['Customer ID', 'Name', 'Email', 'Phone', 'Total Orders', 'Total Spent', 'Average Order Value', 'Last Order Date', 'Registration Date']);

        $allCustomers = User::withSum('orders as total_spent', 'total_amount')
            ->withCount(['orders as total_orders' => function ($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->withMax('orders as last_order_date', 'created_at')
            ->get();

        foreach ($allCustomers as $customer) {
            $avgOrderValue = $customer->total_orders > 0 ? ($customer->total_spent ?? 0) / $customer->total_orders : 0;

            fputcsv($file, [
                $customer->id,
                trim($customer->first_name.' '.$customer->last_name),
                $customer->email,
                $customer->phone ?? 'N/A',
                $customer->total_orders,
                round($customer->total_spent ?? 0, 2),
                round($avgOrderValue, 2),
                $customer->last_order_date ? Carbon::parse($customer->last_order_date)->format('m-d-Y') : 'Never',
                $customer->created_at->format('m-d-Y'),
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

    /**
     * Export all comprehensive product analytics data.
     */
    private function exportAllProductData($file, $startDate, $endDate)
    {
        // Summary Section
        fputcsv($file, ['PRODUCT ANALYTICS SUMMARY']);
        fputcsv($file, ['Period', $startDate->format('m-d-Y').' to '.$endDate->format('m-d-Y')]);
        fputcsv($file, []);

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

        fputcsv($file, ['Total Products', $totalProducts]);
        fputcsv($file, ['Total Units Sold', round($totalUnitsSold, 0)]);
        fputcsv($file, ['Total Product Revenue', round($totalProductRevenue, 2)]);
        fputcsv($file, ['Average Product Price', round($averageProductPrice, 2)]);
        fputcsv($file, []);

        // Revenue by Category
        fputcsv($file, ['REVENUE BY CATEGORY']);
        fputcsv($file, ['Category', 'Revenue', 'Units Sold']);
        $categorySales = $this->getSalesByCategory($startDate, $endDate);
        foreach ($categorySales as $category) {
            fputcsv($file, [
                $category->name,
                round($category->total_revenue ?? 0, 2),
                $category->total_sold ?? 0,
            ]);
        }
        fputcsv($file, []);

        // Top Products (Best Sellers)
        fputcsv($file, ['TOP PRODUCTS (BEST SELLERS)']);
        fputcsv($file, ['Product', 'SKU', 'Category', 'Units Sold', 'Revenue', 'Profit Margin', 'Views', 'Conversion Rate']);
        $bestSellers = $this->getBestSellers($startDate, $endDate);
        foreach ($bestSellers as $product) {
            $profitMargin = 0;
            if ($product->cost_price && $product->price > 0) {
                $profitMargin = (($product->price - $product->cost_price) / $product->price) * 100;
            } elseif ($product->price > 0) {
                $profitMargin = 40;
            }

            $views = $product->view_count ?? 0;
            $unitsSold = $product->total_sold ?? 0;
            $conversionRate = $views > 0 ? (($unitsSold / $views) * 100) : 0;

            fputcsv($file, [
                $product->name,
                $product->sku,
                $product->category->name ?? 'Uncategorized',
                round($unitsSold, 0),
                round($product->total_revenue ?? 0, 2),
                round($profitMargin, 1).'%',
                round($views, 0),
                round($conversionRate, 2).'%',
            ]);
        }
        fputcsv($file, []);

        // Worst Performers
        fputcsv($file, ['WORST PERFORMERS']);
        fputcsv($file, ['Product', 'SKU', 'Category', 'Units Sold', 'Revenue']);
        $worstPerformers = $this->getWorstPerformers($startDate, $endDate);
        foreach ($worstPerformers->take(10) as $product) {
            fputcsv($file, [
                $product->name,
                $product->sku,
                $product->category->name ?? 'Uncategorized',
                round($product->total_sold ?? 0, 0),
                round($product->total_revenue ?? 0, 2),
            ]);
        }
        fputcsv($file, []);

        // Most Viewed Products
        fputcsv($file, ['MOST VIEWED PRODUCTS']);
        fputcsv($file, ['Product', 'SKU', 'Category', 'Views']);
        $mostViewed = $this->getMostViewedProducts($startDate, $endDate);
        foreach ($mostViewed->take(10) as $product) {
            fputcsv($file, [
                $product->name,
                $product->sku,
                $product->category->name ?? 'Uncategorized',
                round($product->view_count ?? 0, 0),
            ]);
        }
        fputcsv($file, []);

        // Stock Turnover Rate
        fputcsv($file, ['STOCK TURNOVER RATE']);
        $stockTurnoverRate = $this->calculateStockTurnoverRate($startDate, $endDate);
        fputcsv($file, ['Cost of Goods Sold', round($stockTurnoverRate['cogs'] ?? 0, 2)]);
        fputcsv($file, ['Average Inventory', round($stockTurnoverRate['average_inventory'] ?? 0, 2)]);
        fputcsv($file, ['Turnover Rate', round($stockTurnoverRate['turnover_rate'] ?? 0, 2).'x']);
        fputcsv($file, []);

        // Comprehensive Product Performance Table
        fputcsv($file, ['COMPREHENSIVE PRODUCT PERFORMANCE']);
        fputcsv($file, ['Product', 'SKU', 'Category', 'Units Sold', 'Revenue', 'Profit Margin', 'Views', 'Conversion Rate']);
        $productPerformanceData = $this->getProductPerformanceData($startDate, $endDate);
        foreach ($productPerformanceData as $product) {
            fputcsv($file, [
                $product['name'],
                $product['sku'],
                $product['category'],
                round($product['units_sold'], 0),
                round($product['revenue'], 2),
                round($product['profit_margin'], 1).'%',
                round($product['views'], 0),
                round($product['conversion_rate'], 2).'%',
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
     * Revenue = Sum of (Price * Quantity) for all order items
     * Formula: Total Revenue = (Price 1 * Quantity 1) + (Price 2 * Quantity 2) + ...
     */
    private function generateDailyRevenueData($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy();
        $today = Carbon::now();

        while ($current->lte($endDate)) {
            // If the date is in the future, show 0
            if ($current->gt($today)) {
                $dayRevenue = 0;
            } else {
                // Calculate revenue from order items: Sum of (unit_price * quantity) = Sum of total_price
                $dayRevenue = OrderItem::whereHas('order', function ($query) use ($current) {
                    $query->whereDate('created_at', $current->format('Y-m-d'))
                        ->where('status', '!=', 'cancelled');
                })
                    ->sum('total_price');
            }

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
        $today = Carbon::now();

        while ($current->lte($endDate)) {
            // If the date is in the future, show 0
            if ($current->gt($today)) {
                $dayOrders = 0;
            } else {
                $dayOrders = Order::whereDate('created_at', $current->format('Y-m-d'))
                    ->where('status', '!=', 'cancelled')
                    ->count();
            }

            $data[] = (int) $dayOrders;
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
        $today = Carbon::now();

        while ($current->lte($endDate)) {
            // If the date is in the future, show 0
            if ($current->gt($today)) {
                $dayNewCustomers = 0;
            } else {
                $dayNewCustomers = User::whereDate('created_at', $current->format('Y-m-d'))->count();
            }
            $data[] = $dayNewCustomers;
            $current->addDay();
        }

        return $data;
    }

    /**
     * Generate daily labels for a date range.
     */
    private function generateDailyLabels($startDate, $endDate)
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
        // Query orders from database with shipping address
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('shipping_address')
            ->where('status', '!=', 'cancelled') // Exclude cancelled orders
            ->get();

        $geographicData = [];
        $regionsData = [];

        foreach ($orders as $order) {
            // Get shipping address (automatically cast from JSON to array by Eloquent)
            $address = $order->shipping_address;

            // Handle both array and JSON string formats
            if (is_string($address)) {
                $address = json_decode($address, true);
            }

            // Check if city exists in address
            if (is_array($address) && isset($address['city']) && ! empty($address['city']) && $address['city'] !== 'N/A') {
                $city = trim($address['city']);
                $region = ! empty($address['region']) && $address['region'] !== 'N/A' ? trim($address['region']) : 'Unknown Region';
                $province = ! empty($address['province']) && $address['province'] !== 'N/A' ? trim($address['province']) : null;

                // City-level data
                if (! isset($geographicData[$city])) {
                    $geographicData[$city] = [
                        'count' => 0,
                        'city' => $city,
                        'province' => $province,
                        'region' => $region,
                        'coordinates' => $this->getCityCoordinates($city, $province),
                    ];
                }
                $geographicData[$city]['count']++;

                // Region-level data
                if (! isset($regionsData[$region])) {
                    $regionsData[$region] = [
                        'count' => 0,
                        'region' => $region,
                        'cities' => [],
                    ];
                }
                $regionsData[$region]['count']++;

                // City within region
                if (! isset($regionsData[$region]['cities'][$city])) {
                    $regionsData[$region]['cities'][$city] = [
                        'count' => 0,
                        'city' => $city,
                        'province' => $province,
                        'coordinates' => $this->getCityCoordinates($city, $province),
                    ];
                }
                $regionsData[$region]['cities'][$city]['count']++;
            }
        }

        // Sort regions by count
        uasort($regionsData, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        // Sort cities within each region
        foreach ($regionsData as &$regionData) {
            uasort($regionData['cities'], function ($a, $b) {
                return $b['count'] - $a['count'];
            });
        }
        unset($regionData);

        // Sort all cities by count for map markers
        uasort($geographicData, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        return [
            'cities' => array_slice($geographicData, 0, 50, true), // Get top 50 cities for map
            'regions' => $regionsData,
        ];
    }

    /**
     * Get coordinates for a city in Philippines
     * Returns [lat, lng] or null if not found.
     */
    private function getCityCoordinates($city, $province = null)
    {
        // Common Philippine cities coordinates
        $philippinesCities = [
            // Metro Manila / NCR
            'Manila' => [14.5995, 120.9842],
            'Quezon City' => [14.6760, 121.0437],
            'Caloocan' => [14.6576, 120.9679],
            'Makati' => [14.5547, 121.0244],
            'Pasig' => [14.5764, 121.0851],
            'Taguig' => [14.5176, 121.0509],
            'Mandaluyong' => [14.5794, 121.0359],
            'San Juan' => [14.6019, 121.0255],
            'Muntinlupa' => [14.4106, 121.0453],
            'Paranaque' => [14.4793, 121.0198],
            'Las Pinas' => [14.4506, 120.9828],
            'Valenzuela' => [14.7000, 120.9833],
            'Malabon' => [14.6600, 120.9569],
            'Navotas' => [14.6500, 120.9500],
            'Marikina' => [14.6507, 121.1029],
            'Pasay' => [14.5378, 121.0014],
            'Pateros' => [14.5407, 121.0689],

            // Other major cities
            'Cebu City' => [10.3157, 123.8854],
            'Davao City' => [7.1907, 125.4553],
            'Iloilo City' => [10.7202, 122.5621],
            'Bacolod' => [10.6407, 122.9688],
            'Calamba' => [14.2227, 121.1657],
            'Laguna' => [14.2595, 121.2946],
            'Koronadal' => [6.5000, 124.8500],
            'Panabo' => [7.3081, 125.6839],
            'Tagum' => [7.4475, 125.8047],
            'Butuan' => [8.9492, 125.5436],
            'Cagayan de Oro' => [8.4542, 124.6319],
            'Zamboanga City' => [6.9214, 122.0790],
            'Baguio' => [16.4023, 120.5960],
            'Dagupan' => [16.0439, 120.3411],
            'Pampanga' => [15.0794, 120.6199],
            'Angeles' => [15.1450, 120.5846],
            'Batangas' => [13.7565, 121.0583],
            'Lucena' => [13.9314, 121.6172],
            'Naga' => [13.6192, 123.1814],
            'Legazpi' => [13.1392, 123.7338],
            'Puerto Princesa' => [9.8349, 118.7384],
            'Tacloban' => [11.2444, 125.0039],
            'Iligan' => [8.2280, 124.2452],
            'General Santos' => [6.1164, 125.1716],
            'Olongapo' => [14.8292, 120.2828],
            'San Fernando' => [15.0327, 120.6869],
            'Dumaguete' => [9.3077, 123.3056],
            'Bacoor' => [14.4594, 120.9530],
            'Imus' => [14.4297, 120.9371],
            'Dasmarinas' => [14.3294, 120.9375],
            'Antipolo' => [14.5887, 121.1764],
            'Cainta' => [14.5785, 121.1222],
            'Taytay' => [14.5690, 121.1311],
            'Santa Rosa' => [14.3167, 121.1111],
            'Biñan' => [14.3361, 121.0861],
            'Cabuyao' => [14.2775, 121.1269],
            'San Pedro' => [14.3572, 121.0556],
            'Los Baños' => [14.1667, 121.2500],
            'San Pablo' => [14.0694, 121.3278],
            'Tanauan' => [14.0833, 121.1500],
            'Lipa' => [13.9411, 121.1631],
            'Tagaytay' => [14.1000, 120.9333],
            'Malolos' => [14.8444, 120.8111],
            'Baliuag' => [14.9547, 120.8972],
            'San Jose del Monte' => [14.8139, 121.0458],
            'Tarlac City' => [15.4869, 120.5897],
            'Angeles City' => [15.1450, 120.5846],
            'Olongapo City' => [14.8292, 120.2828],
            'Dagupan City' => [16.0439, 120.3411],
            'Urdaneta' => [15.9761, 120.5711],
            'San Carlos' => [15.9283, 120.3483],
            'Alaminos' => [16.1561, 119.9808],
            'Laoag' => [18.1961, 120.5925],
            'Vigan' => [17.5747, 120.3869],
            'Ilagan' => [17.1481, 121.8892],
            'Tuguegarao' => [17.6133, 121.7272],
            'Cabanatuan' => [15.4907, 120.9664],
            'Palayan' => [15.5419, 121.0833],
            'Gapan' => [15.3072, 120.9469],
            'San Jose' => [15.7878, 121.0003],
            'Muñoz' => [15.7167, 120.9167],
            'Balanga' => [14.6761, 120.5361],
            'Malabon City' => [14.6600, 120.9569],
            'Navotas City' => [14.6500, 120.9500],
            'Valenzuela City' => [14.7000, 120.9833],
            'Muntinlupa City' => [14.4106, 121.0453],
            'Las Piñas City' => [14.4506, 120.9828],
            'Parañaque City' => [14.4793, 121.0198],
            'Pasay City' => [14.5378, 121.0014],
            'San Juan City' => [14.6019, 121.0255],
            'Mandaluyong City' => [14.5794, 121.0359],
            'Taguig City' => [14.5176, 121.0509],
            'Pasig City' => [14.5764, 121.0851],
            'Marikina City' => [14.6507, 121.1029],
            'Pateros' => [14.5407, 121.0689],
        ];

        // Try exact match first
        if (isset($philippinesCities[$city])) {
            return $philippinesCities[$city];
        }

        // Try partial match (case insensitive)
        $cityLower = strtolower($city);
        foreach ($philippinesCities as $key => $coords) {
            if (stripos($key, $city) !== false || stripos($city, $key) !== false) {
                return $coords;
            }
        }

        // Try with "City" suffix
        if (stripos($city, 'city') === false) {
            $cityWithSuffix = $city.' City';
            if (isset($philippinesCities[$cityWithSuffix])) {
                return $philippinesCities[$cityWithSuffix];
            }
        }

        // If not found, return approximate center of Philippines
        return [12.8797, 121.7740];
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
     * Generate weekly revenue data for period-based view (4 weeks).
     * Always shows 4 weeks, even if some extend into the future.
     */
    private function generateWeeklyRevenueDataForPeriod($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfWeek(); // Start from the first week
        $today = Carbon::now();

        $weekCount = 0;
        while ($weekCount < 4) {
            $weekEnd = $current->copy()->endOfWeek();

            // If the week is entirely in the future, show 0
            if ($current->gt($today)) {
                $weekRevenue = 0;
            } else {
                // Only count revenue up to today, but use full week range for labels
                $countEndDate = $weekEnd->gt($today) ? $today : $weekEnd;

                // Calculate revenue from order items: Sum of (unit_price * quantity) = Sum of total_price
                $weekRevenue = OrderItem::whereHas('order', function ($query) use ($current, $countEndDate) {
                    $query->whereBetween('created_at', [
                        $current->copy()->startOfDay(),
                        $countEndDate->copy()->endOfDay(),
                    ])
                        ->where('status', '!=', 'cancelled');
                })
                    ->sum('total_price');
            }

            $data[] = (float) $weekRevenue;
            $current->addWeek(); // Move to next week
            $weekCount++;
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
     * Generate monthly revenue data for period-based view (12 months).
     * Always shows 12 months, even if some extend into the future.
     */
    private function generateMonthlyRevenueDataForPeriod($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfMonth(); // Start from the first month
        $today = Carbon::now();

        $monthCount = 0;
        while ($current->lte($endDate) && $monthCount < 12) {
            $monthEnd = $current->copy()->endOfMonth();

            // If the month is entirely in the future, show 0
            if ($current->gt($today)) {
                $monthRevenue = 0;
            } else {
                // Only count revenue up to today
                $countEndDate = $monthEnd->gt($today) ? $today : $monthEnd;

                // Calculate revenue from order items: Sum of (unit_price * quantity) = Sum of total_price
                $monthRevenue = OrderItem::whereHas('order', function ($query) use ($current, $countEndDate) {
                    $query->whereBetween('created_at', [
                        $current->copy()->startOfDay(),
                        $countEndDate->copy()->endOfDay(),
                    ])
                        ->where('status', '!=', 'cancelled');
                })
                    ->sum('total_price');
            }

            $data[] = (float) $monthRevenue;
            $current->addMonth(); // Move to next month
            $monthCount++;
        }

        return $data;
    }

    /**
     * Generate weekly orders data for period-based view (4 weeks).
     * Always shows 4 weeks, even if some extend into the future.
     */
    private function generateWeeklyOrdersDataForPeriod($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfWeek(); // Start from the first week
        $today = Carbon::now();

        $weekCount = 0;
        while ($weekCount < 4) {
            $weekEnd = $current->copy()->endOfWeek();

            // If the week is entirely in the future, show 0
            if ($current->gt($today)) {
                $weekOrders = 0;
            } else {
                // Only count orders up to today, but use full week range for labels
                $countEndDate = $weekEnd->gt($today) ? $today : $weekEnd;

                $weekOrders = Order::whereBetween('created_at', [
                    $current->copy()->startOfDay(),
                    $countEndDate->copy()->endOfDay(),
                ])
                    ->where('status', '!=', 'cancelled')
                    ->count();
            }

            $data[] = (int) $weekOrders;
            $current->addWeek(); // Move to next week
            $weekCount++;
        }

        return $data;
    }

    /**
     * Generate monthly orders data for period-based view (12 months).
     * Always shows 12 months, even if some extend into the future.
     */
    private function generateMonthlyOrdersDataForPeriod($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfMonth(); // Start from the first month
        $today = Carbon::now();

        $monthCount = 0;
        while ($current->lte($endDate) && $monthCount < 12) {
            $monthEnd = $current->copy()->endOfMonth();

            // If the month is entirely in the future, show 0
            if ($current->gt($today)) {
                $monthOrders = 0;
            } else {
                // Only count orders up to today
                $countEndDate = $monthEnd->gt($today) ? $today : $monthEnd;

                $monthOrders = Order::whereBetween('created_at', [
                    $current->copy()->startOfDay(),
                    $countEndDate->copy()->endOfDay(),
                ])
                    ->where('status', '!=', 'cancelled')
                    ->count();
            }

            $data[] = (int) $monthOrders;
            $current->addMonth(); // Move to next month
            $monthCount++;
        }

        return $data;
    }

    /**
     * Generate daily expenses data.
     * Expenses = Sum of (cost_price * quantity) for all order items.
     */
    private function generateDailyExpensesData($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy();
        $today = Carbon::now();

        while ($current->lte($endDate)) {
            // If the date is in the future, show 0
            if ($current->gt($today)) {
                $dayExpenses = 0;
            } else {
                // Get all order items for this day with their products to access cost_price
                $orderItems = OrderItem::whereHas('order', function ($query) use ($current) {
                    $query->whereDate('created_at', $current->format('Y-m-d'))
                        ->where('status', '!=', 'cancelled');
                })
                    ->with('product')
                    ->get();

                $dayExpenses = $orderItems->sum(function ($item) {
                    // Use cost_price from product if available, otherwise use 0
                    $costPrice = $item->product && $item->product->cost_price
                        ? $item->product->cost_price
                        : 0;

                    return $costPrice * $item->quantity;
                });
            }

            $data[] = (float) $dayExpenses;
            $current->addDay();
        }

        return $data;
    }

    /**
     * Generate weekly expenses data for period-based view (4 weeks).
     */
    private function generateWeeklyExpensesDataForPeriod($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfWeek();
        $today = Carbon::now();

        $weekCount = 0;
        while ($weekCount < 4) {
            $weekEnd = $current->copy()->endOfWeek();

            if ($current->gt($today)) {
                $weekExpenses = 0;
            } else {
                $countEndDate = $weekEnd->gt($today) ? $today : $weekEnd;

                $orderItems = OrderItem::whereHas('order', function ($query) use ($current, $countEndDate) {
                    $query->whereBetween('created_at', [
                        $current->copy()->startOfDay(),
                        $countEndDate->copy()->endOfDay(),
                    ])
                        ->where('status', '!=', 'cancelled');
                })
                    ->with('product')
                    ->get();

                $weekExpenses = $orderItems->sum(function ($item) {
                    $costPrice = $item->product && $item->product->cost_price
                        ? $item->product->cost_price
                        : 0;

                    return $costPrice * $item->quantity;
                });
            }

            $data[] = (float) $weekExpenses;
            $current->addWeek();
            $weekCount++;
        }

        return $data;
    }

    /**
     * Generate monthly expenses data for period-based view (12 months).
     */
    private function generateMonthlyExpensesDataForPeriod($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfMonth();
        $today = Carbon::now();

        $monthCount = 0;
        while ($current->lte($endDate) && $monthCount < 12) {
            $monthEnd = $current->copy()->endOfMonth();

            if ($current->gt($today)) {
                $monthExpenses = 0;
            } else {
                $countEndDate = $monthEnd->gt($today) ? $today : $monthEnd;

                $orderItems = OrderItem::whereHas('order', function ($query) use ($current, $countEndDate) {
                    $query->whereBetween('created_at', [
                        $current->copy()->startOfDay(),
                        $countEndDate->copy()->endOfDay(),
                    ])
                        ->where('status', '!=', 'cancelled');
                })
                    ->with('product')
                    ->get();

                $monthExpenses = $orderItems->sum(function ($item) {
                    $costPrice = $item->product && $item->product->cost_price
                        ? $item->product->cost_price
                        : 0;

                    return $costPrice * $item->quantity;
                });
            }

            $data[] = (float) $monthExpenses;
            $current->addMonth();
            $monthCount++;
        }

        return $data;
    }

    /**
     * Generate daily profit data.
     * Profit = Revenue - Expenses.
     */
    private function generateDailyProfitData($revenue, $expenses)
    {
        $data = [];
        $count = min(count($revenue), count($expenses));
        for ($i = 0; $i < $count; $i++) {
            $data[] = (float) ($revenue[$i] - $expenses[$i]);
        }

        return $data;
    }

    /**
     * Generate weekly profit data.
     */
    private function generateWeeklyProfitData($revenue, $expenses)
    {
        $data = [];
        $count = min(count($revenue), count($expenses));
        for ($i = 0; $i < $count; $i++) {
            $data[] = (float) ($revenue[$i] - $expenses[$i]);
        }

        return $data;
    }

    /**
     * Generate monthly profit data.
     */
    private function generateMonthlyProfitData($revenue, $expenses)
    {
        $data = [];
        $count = min(count($revenue), count($expenses));
        for ($i = 0; $i < $count; $i++) {
            $data[] = (float) ($revenue[$i] - $expenses[$i]);
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
        // Validate sortBy to ensure it exists in the array
        $validSortFields = ['units_sold', 'revenue', 'profit_margin', 'views', 'conversion_rate'];
        if (! in_array($sortBy, $validSortFields)) {
            $sortBy = 'units_sold';
        }

        $mappedProducts = $mappedProducts->sortBy(function ($product) use ($sortBy) {
            return $product[$sortBy] ?? 0; // Provide default value if key doesn't exist
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
     * Calculate period ranges based on offset from current date.
     * For week and month, show full periods even if they extend into the future.
     */
    private function calculatePeriodRanges($today, $offset = 0)
    {
        // Day range: offset days back from today
        $dayDate = $today->copy()->subDays($offset);
        $dayStart = $dayDate->copy()->startOfDay();
        $dayEndCandidate = $dayDate->copy()->endOfDay();
        $dayEnd = $dayEndCandidate->gt($today) ? $today->copy()->endOfDay() : $dayEndCandidate; // Don't go past today

        // Week range: offset weeks back from current week - show full week (all 7 days)
        $weekDate = $today->copy()->subWeeks($offset);
        $weekStart = $weekDate->copy()->startOfWeek();
        $weekEnd = $weekDate->copy()->endOfWeek(); // Show full week, even if extends into future

        // Month range: offset months back from current month - show full month (all days)
        $monthDate = $today->copy()->subMonths($offset);
        $monthStart = $monthDate->copy()->startOfMonth();
        $monthEnd = $monthDate->copy()->endOfMonth(); // Show full month, even if extends into future

        return [
            'day' => ['start' => $dayStart, 'end' => $dayEnd],
            'week' => ['start' => $weekStart, 'end' => $weekEnd],
            'month' => ['start' => $monthStart, 'end' => $monthEnd],
        ];
    }

    /**
     * Get top products by period (day, week, or month aggregation).
     */
    private function getTopProductsByPeriod($startDate, $endDate, $period = 'day')
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
            ->get()
            ->filter(function ($product) {
                return ($product->total_revenue ?? 0) > 0; // Only products with sales in the period
            })
            ->sortByDesc('total_revenue')
            ->take(10)
            ->values();
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

    /**
     * Calculate percentage change between two periods.
     */
    private function calculatePercentageChange($currentValue, $previousValue)
    {
        if ($previousValue == 0) {
            return $currentValue > 0 ? 100 : 0;
        }

        return (($currentValue - $previousValue) / $previousValue) * 100;
    }

    /**
     * Get percentage change metrics for customer analytics cards.
     */
    private function getCustomerPercentageChanges($startDate, $endDate)
    {
        $periodDays = $startDate->diffInDays($endDate);
        $previousStartDate = $startDate->copy()->subDays($periodDays + 1);
        $previousEndDate = $startDate->copy()->subDay();

        // Total Customers - compare total customers at end of current period vs end of previous period
        // This is a cumulative metric, so we compare snapshots at equivalent points in time
        $currentTotal = User::where('created_at', '<=', $endDate)->count();
        $previousTotal = User::where('created_at', '<=', $previousEndDate)->count();
        $totalCustomersChange = $this->calculatePercentageChange($currentTotal, $previousTotal);

        // New Customers
        $currentNew = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $previousNew = User::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();
        $newCustomersChange = $this->calculatePercentageChange($currentNew, $previousNew);

        // Average Lifetime Value - compare overall average LTV at end of periods
        // Get all-time average LTV at end of current period vs end of previous period
        $currentAvgLTV = $this->calculateAverageLifetimeValue();
        // For previous period, calculate all-time average up to that point
        $previousCustomers = User::where('created_at', '<=', $previousEndDate)
            ->whereHas('orders', function ($query) use ($previousEndDate) {
                $query->where('created_at', '<=', $previousEndDate)
                    ->where('status', '!=', 'cancelled');
            })
            ->withSum(['orders as total_spent' => function ($query) use ($previousEndDate) {
                $query->where('created_at', '<=', $previousEndDate)
                    ->where('status', '!=', 'cancelled');
            }], 'total_amount')
            ->get();
        $previousAvgLTV = $previousCustomers->count() > 0 ? ($previousCustomers->avg('total_spent') ?? 0) : 0;
        $avgLTVChange = $this->calculatePercentageChange($currentAvgLTV, $previousAvgLTV);

        // Repeat Purchase Rate
        $currentRepeatRate = $this->calculateRepeatPurchaseRate($startDate, $endDate);
        $previousRepeatRate = $this->calculateRepeatPurchaseRate($previousStartDate, $previousEndDate);
        $repeatRateChange = $this->calculatePercentageChange($currentRepeatRate, $previousRepeatRate);

        return [
            'total_customers' => $totalCustomersChange,
            'new_customers' => $newCustomersChange,
            'avg_lifetime_value' => $avgLTVChange,
            'repeat_purchase_rate' => $repeatRateChange,
        ];
    }

    /**
     * Calculate average lifetime value for a specific period.
     */
    private function calculateAverageLifetimeValueForPeriod($startDate, $endDate)
    {
        $customers = User::whereHas('orders', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled');
        })
            ->withSum(['orders as total_spent' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', '!=', 'cancelled');
            }], 'total_amount')
            ->get();

        if ($customers->count() == 0) {
            return 0;
        }

        return $customers->avg('total_spent') ?? 0;
    }

    /**
     * Get percentage change metrics for sales analytics cards.
     */
    private function getSalesPercentageChanges($startDate, $endDate)
    {
        $periodDays = $startDate->diffInDays($endDate);
        $previousStartDate = $startDate->copy()->subDays($periodDays + 1);
        $previousEndDate = $startDate->copy()->subDay();

        // Total Sales (Orders)
        $currentSales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->count();
        $previousSales = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', '!=', 'cancelled')
            ->count();
        $totalSalesChange = $this->calculatePercentageChange($currentSales, $previousSales);

        // Total Revenue
        $currentRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $previousRevenue = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $totalRevenueChange = $this->calculatePercentageChange($currentRevenue, $previousRevenue);

        // Average Order Value
        $currentAOV = $currentSales > 0 ? ($currentRevenue / $currentSales) : 0;
        $previousAOV = $previousSales > 0 ? ($previousRevenue / $previousSales) : 0;
        $aovChange = $this->calculatePercentageChange($currentAOV, $previousAOV);

        // Conversion Rate
        $currentConversion = $this->calculateConversionRate($startDate, $endDate);
        $previousConversion = $this->calculateConversionRate($previousStartDate, $previousEndDate);
        $conversionChange = $this->calculatePercentageChange($currentConversion, $previousConversion);

        return [
            'total_sales' => $totalSalesChange,
            'total_revenue' => $totalRevenueChange,
            'avg_order_value' => $aovChange,
            'conversion_rate' => $conversionChange,
        ];
    }

    /**
     * Get percentage change metrics for product analytics cards.
     */
    private function getProductPercentageChanges($startDate, $endDate)
    {
        $periodDays = $startDate->diffInDays($endDate);
        $previousStartDate = $startDate->copy()->subDays($periodDays + 1);
        $previousEndDate = $startDate->copy()->subDay();

        // Total Products - compare total products at end of current period vs end of previous period
        // This is a cumulative metric, so we compare snapshots at equivalent points in time
        $currentTotal = Product::where('created_at', '<=', $endDate)->count();
        $previousTotal = Product::where('created_at', '<=', $previousEndDate)->count();
        $totalProductsChange = $this->calculatePercentageChange($currentTotal, $previousTotal);

        // Units Sold
        $currentUnits = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled');
        })->sum('quantity');
        $previousUnits = OrderItem::whereHas('order', function ($query) use ($previousStartDate, $previousEndDate) {
            $query->whereBetween('created_at', [$previousStartDate, $previousEndDate])
                ->where('status', '!=', 'cancelled');
        })->sum('quantity');
        $unitsSoldChange = $this->calculatePercentageChange($currentUnits, $previousUnits);

        // Product Revenue
        $currentRevenue = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled');
        })->sum('total_price');
        $previousRevenue = OrderItem::whereHas('order', function ($query) use ($previousStartDate, $previousEndDate) {
            $query->whereBetween('created_at', [$previousStartDate, $previousEndDate])
                ->where('status', '!=', 'cancelled');
        })->sum('total_price');
        $productRevenueChange = $this->calculatePercentageChange($currentRevenue, $previousRevenue);

        // Average Product Price - compare average price of all products at end of periods
        $currentAvgPrice = Product::where('created_at', '<=', $endDate)->avg('price');
        $previousAvgPrice = Product::where('created_at', '<=', $previousEndDate)->avg('price');
        $avgPriceChange = $this->calculatePercentageChange($currentAvgPrice ?? 0, $previousAvgPrice ?? 0);

        return [
            'total_products' => $totalProductsChange,
            'units_sold' => $unitsSoldChange,
            'product_revenue' => $productRevenueChange,
            'avg_product_price' => $avgPriceChange,
        ];
    }

    /**
     * Get percentage change metrics for revenue analytics cards.
     */
    private function getRevenuePercentageChanges($startDate, $endDate)
    {
        $periodDays = $startDate->diffInDays($endDate);
        $previousStartDate = $startDate->copy()->subDays($periodDays + 1);
        $previousEndDate = $startDate->copy()->subDay();

        // Total Revenue
        $currentRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $previousRevenue = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $totalRevenueChange = $this->calculatePercentageChange($currentRevenue, $previousRevenue);

        // Revenue Growth - The growth rate card already shows the growth rate itself
        // For the badge, we should show the change in the growth rate, but this doesn't make much sense.
        // Instead, we'll just show the growth rate itself (which is already calculated above as totalRevenueChange)
        // Actually, the growth rate IS the percentage change, so the badge should match the growth rate calculation
        // Let's just use the same calculation as totalRevenueChange since growth rate = percentage change of revenue
        $growthChange = $totalRevenueChange; // Growth rate badge should match revenue growth

        // Average Order Value
        $currentOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->count();
        $previousOrders = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', '!=', 'cancelled')
            ->count();
        $currentAOV = $currentOrders > 0 ? ($currentRevenue / $currentOrders) : 0;
        $previousAOV = $previousOrders > 0 ? ($previousRevenue / $previousOrders) : 0;
        $aovChange = $this->calculatePercentageChange($currentAOV, $previousAOV);

        // Revenue per Customer
        $currentRevPerCust = $this->calculateRevenuePerCustomer($startDate, $endDate);
        $previousRevPerCust = $this->calculateRevenuePerCustomer($previousStartDate, $previousEndDate);
        $revPerCustChange = $this->calculatePercentageChange($currentRevPerCust, $previousRevPerCust);

        return [
            'total_revenue' => $totalRevenueChange,
            'growth_rate' => $growthChange,
            'avg_order_value' => $aovChange,
            'revenue_per_customer' => $revPerCustChange,
        ];
    }

    /**
     * Calculate period ranges for Customer Acquisition chart.
     * - Day filter: Show the 7 days in the week containing the selected day
     * - Week filter: Show the 4 weeks of the month containing the selected week
     * - Month filter: Show the 12 months of the year containing the selected month.
     */
    private function calculateAcquisitionPeriodRanges($today, $offset = 0)
    {
        // Day filter: Show the week containing the selected day (7 days)
        // Always show full 7 days even if extending into the future
        $dayDate = $today->copy()->subDays($offset);
        $weekStart = $dayDate->copy()->startOfWeek(); // Start of week containing this day
        $weekEnd = $dayDate->copy()->endOfWeek(); // End of week (all 7 days, even if in future)

        // Week filter: Show the month containing the selected week (4 weeks)
        // Always show 4 weeks even if some extend into the future
        $weekDate = $today->copy()->subWeeks($offset);
        $monthStart = $weekDate->copy()->startOfMonth(); // Start of month containing this week
        // Calculate the end of the 4th week from the month start
        $firstWeekStart = $monthStart->copy()->startOfWeek();
        $fourthWeekEnd = $firstWeekStart->copy()->addWeeks(3)->endOfWeek(); // End of 4th week
        // Don't go beyond the month end, but allow future dates
        $monthEnd = $monthStart->copy()->endOfMonth();
        $monthEndForWeeks = $fourthWeekEnd->lt($monthEnd) ? $fourthWeekEnd : $monthEnd;

        // Month filter: Show the year containing the selected month (12 months)
        $monthDate = $today->copy()->subMonths($offset);
        $yearStart = $monthDate->copy()->startOfYear(); // Start of year containing this month
        $yearEnd = $yearStart->copy()->endOfYear(); // End of year (12 months)
        // Ensure we don't go past today
        if ($yearEnd->gt($today)) {
            $yearEnd = $today->copy()->endOfDay();
        }

        return [
            'day' => ['start' => $weekStart, 'end' => $weekEnd], // Week containing the day
            'week' => ['start' => $monthStart, 'end' => $monthEndForWeeks], // 4 weeks in the month
            'month' => ['start' => $yearStart, 'end' => $yearEnd], // 12 months in the year
        ];
    }

    /**
     * Generate weekly customer acquisition data (for week filter showing 4 weeks).
     * Always shows 4 weeks, even if some extend into the future.
     */
    private function generateWeeklyNewCustomersData($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfWeek(); // Start from the first week
        $today = Carbon::now();

        $weekCount = 0;
        while ($weekCount < 4) {
            $weekEnd = $current->copy()->endOfWeek();

            // If the week is entirely in the future, show 0
            if ($current->gt($today)) {
                $weekNewCustomers = 0;
            } else {
                // Only count customers up to today, but use full week range for labels
                $countEndDate = $weekEnd->gt($today) ? $today : $weekEnd;

                $weekNewCustomers = User::whereBetween('created_at', [
                    $current->copy()->startOfDay(),
                    $countEndDate->copy()->endOfDay(),
                ])->count();
            }

            $data[] = $weekNewCustomers;
            $current->addWeek(); // Move to next week
            $weekCount++;
        }

        return $data;
    }

    /**
     * Generate monthly customer acquisition data (for month filter showing 12 months).
     */
    private function generateMonthlyNewCustomersData($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfMonth(); // Start from the first month

        $monthCount = 0;
        while ($current->lte($endDate) && $monthCount < 12) {
            $monthEnd = $current->copy()->endOfMonth();
            // Don't count beyond today
            if ($monthEnd->gt(Carbon::now())) {
                $monthEnd = Carbon::now();
            }

            $monthNewCustomers = User::whereBetween('created_at', [
                $current->copy()->startOfDay(),
                $monthEnd->copy()->endOfDay(),
            ])->count();

            $data[] = $monthNewCustomers;
            $current->addMonth(); // Move to next month
            $monthCount++;
        }

        return $data;
    }

    /**
     * Generate weekly labels (for week filter showing 4 weeks).
     * Each label shows the full week range (e.g., "Oct 01 - Oct 07").
     */
    private function generateWeeklyLabels($startDate, $endDate)
    {
        $labels = [];
        $current = $startDate->copy()->startOfWeek();
        $weekCount = 0;

        while ($weekCount < 4) {
            $weekEnd = $current->copy()->endOfWeek();
            // Always show full week range even if in future
            $labels[] = $current->format('M d').' - '.$weekEnd->format('M d');
            $current->addWeek();
            $weekCount++;
        }

        return $labels;
    }

    /**
     * Generate monthly labels (for month filter showing 12 months).
     */
    private function generateMonthlyLabels($startDate, $endDate)
    {
        $labels = [];
        $current = $startDate->copy()->startOfMonth();
        $monthCount = 0;

        while ($current->lte($endDate) && $monthCount < 12) {
            $labels[] = $current->format('M Y');
            $current->addMonth();
            $monthCount++;
        }

        return $labels;
    }
}
