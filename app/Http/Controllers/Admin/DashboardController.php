<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get period parameters for chart navigation
        $periodOffset = request()->get('period_offset', 0);
        $currentPeriod = request()->get('current_period', 'month'); // Default to month

        // Get basic statistics
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_amount') ?? 0;

        // Enhanced KPIs
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        // Revenue by period
        $revenueToday = Order::whereDate('created_at', $today)->sum('total_amount') ?? 0;
        $revenueThisWeek = Order::where('created_at', '>=', $thisWeek)->sum('total_amount') ?? 0;
        $revenueThisMonth = Order::where('created_at', '>=', $thisMonth)->sum('total_amount') ?? 0;

        // Orders by status
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')->count();

        // New customers
        $newCustomersToday = User::whereDate('created_at', $today)->count();
        $newCustomersThisWeek = User::where('created_at', '>=', $thisWeek)->count();
        $newCustomersThisMonth = User::where('created_at', '>=', $thisMonth)->count();

        // Unread messages
        $unreadMessages = \App\Models\ContactMessage::where('status', 'new')->count();

        // Low stock alerts
        $lowStockCount = Product::where('stock_quantity', '<=', DB::raw('low_stock_threshold'))->count();

        // Get recent activity
        $recentProducts = Product::orderBy('created_at', 'desc')->take(5)->get();
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(20)->get();
        $lowStockProducts = Product::where('stock_quantity', '<=', DB::raw('low_stock_threshold'))->take(5)->get();

        // Recent activity feed
        $recentActivity = collect();

        // Add recent orders to activity
        $recentOrdersActivity = Order::with('user')->orderBy('created_at', 'desc')->take(10)->get()->map(function ($order) {
            $customerName = $order->user ? ($order->user->first_name ?? 'Guest') : 'Guest';

            return [
                'type' => 'order',
                'title' => 'New Order',
                'message' => "Order #{$order->order_number} from {$customerName}",
                'timestamp' => $order->created_at,
                'url' => admin_route('orders.show', $order),
            ];
        });

        // Add recent messages to activity
        $recentMessagesActivity = \App\Models\ContactMessage::with('user')->orderBy('created_at', 'desc')->take(10)->get()->map(function ($message) {
            return [
                'type' => 'message',
                'title' => 'New Message',
                'message' => "Message from {$message->name}",
                'timestamp' => $message->created_at,
                'url' => admin_route('messages.show', $message),
            ];
        });

        // Add low stock alerts to activity
        $lowStockActivity = Product::where('stock_quantity', '<=', DB::raw('low_stock_threshold'))
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($product) {
                return [
                    'type' => 'inventory',
                    'title' => 'Low Stock Alert',
                    'message' => "{$product->name} is running low ({$product->stock_quantity} left)",
                    'timestamp' => $product->updated_at ?? $product->created_at ?? now(),
                    'url' => admin_route('inventory.show', $product),
                ];
            });

        $recentActivity = $recentOrdersActivity->concat($recentMessagesActivity)->concat($lowStockActivity)
            ->sortByDesc('timestamp')
            ->take(20);

        // Get monthly data for charts
        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        // Order status distribution for pie chart
        $orderStatusDistribution = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Fill missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            if (! isset($monthlyOrders[$i])) {
                $monthlyOrders[$i] = 0;
            }
            if (! isset($monthlyRevenue[$i])) {
                $monthlyRevenue[$i] = 0;
            }
        }

        ksort($monthlyOrders);
        ksort($monthlyRevenue);

        // Generate period-based data for chart filters (Day/Week/Month)
        $periodRanges = $this->calculatePeriodRanges(Carbon::now(), $periodOffset);

        // Daily revenue data (last 7 days)
        $dailyRevenue = $this->generateDailyRevenueData($periodRanges['day']['start'], $periodRanges['day']['end']);
        $dailyLabels = $this->generateDailyLabels($periodRanges['day']['start'], $periodRanges['day']['end']);

        // Weekly revenue data (last 4 weeks)
        $weeklyRevenue = $this->generateWeeklyRevenueDataForPeriod($periodRanges['week']['start'], $periodRanges['week']['end']);
        $weeklyLabels = $this->generateWeeklyLabels($periodRanges['week']['start'], $periodRanges['week']['end']);

        // Monthly revenue data (last 12 months)
        $monthlyRevenueChart = $this->generateMonthlyRevenueDataForPeriod($periodRanges['month']['start'], $periodRanges['month']['end']);
        $monthlyLabels = $this->generateMonthlyLabels($periodRanges['month']['start'], $periodRanges['month']['end']);

        // Calculate period-specific totals for dynamic display
        $periodRevenue = 0;
        $periodOrders = 0;

        if ($currentPeriod === 'day') {
            $periodRevenue = array_sum($dailyRevenue);
            $periodOrders = Order::whereBetween('created_at', [$periodRanges['day']['start'], $periodRanges['day']['end']])
                ->where('status', '!=', 'cancelled')
                ->count();
        } elseif ($currentPeriod === 'week') {
            $periodRevenue = array_sum($weeklyRevenue);
            $periodOrders = Order::whereBetween('created_at', [$periodRanges['week']['start'], $periodRanges['week']['end']])
                ->where('status', '!=', 'cancelled')
                ->count();
        } else { // month
            $periodRevenue = array_sum($monthlyRevenueChart);
            $periodOrders = Order::whereBetween('created_at', [$periodRanges['month']['start'], $periodRanges['month']['end']])
                ->where('status', '!=', 'cancelled')
                ->count();
        }

        // Get top selling products (last 30 days)
        $topProducts = OrderItem::whereHas('order', function ($query) {
            $query->where('created_at', '>=', now()->subDays(30))
                ->where('status', '!=', 'cancelled');
        })
            ->select('product_id', 'product_name')
            ->selectRaw('SUM(quantity) as total_sold')
            ->selectRaw('SUM(total_price) as total_revenue')
            ->groupBy('product_id', 'product_name')
            ->orderBy('total_sold', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);

                return (object) [
                    'id' => $item->product_id,
                    'name' => $item->product_name ?? ($product->name ?? 'Unknown Product'),
                    'total_sold' => (int) $item->total_sold,
                    'total_revenue' => (float) $item->total_revenue,
                    'product' => $product,
                ];
            });

        return view('admin.dashboard.index', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'revenueToday',
            'revenueThisWeek',
            'revenueThisMonth',
            'pendingOrders',
            'completedOrders',
            'newCustomersToday',
            'newCustomersThisWeek',
            'newCustomersThisMonth',
            'unreadMessages',
            'lowStockCount',
            'recentProducts',
            'recentOrders',
            'lowStockProducts',
            'recentActivity',
            'monthlyOrders',
            'monthlyRevenue',
            'orderStatusDistribution',
            'dailyRevenue',
            'dailyLabels',
            'weeklyRevenue',
            'weeklyLabels',
            'monthlyRevenueChart',
            'monthlyLabels',
            'periodOffset',
            'currentPeriod',
            'periodRevenue',
            'periodOrders',
            'topProducts'
        ));
    }

    /**
     * Calculate period ranges for Day/Week/Month filters.
     */
    private function calculatePeriodRanges($today, $offset = 0)
    {
        // Day filter: Show the week containing the selected day (7 days)
        $dayDate = $today->copy()->subDays($offset);
        $weekStart = $dayDate->copy()->startOfWeek();
        $weekEnd = $dayDate->copy()->endOfWeek();

        // Week filter: Show the month containing the selected week (4 weeks)
        $weekDate = $today->copy()->subWeeks($offset);
        $monthStart = $weekDate->copy()->startOfMonth();
        $firstWeekStart = $monthStart->copy()->startOfWeek();
        $fourthWeekEnd = $firstWeekStart->copy()->addWeeks(3)->endOfWeek();
        $monthEnd = $monthStart->copy()->endOfMonth();
        $monthEndForWeeks = $fourthWeekEnd->lt($monthEnd) ? $fourthWeekEnd : $monthEnd;

        // Month filter: Show the year containing the selected month (12 months)
        $monthDate = $today->copy()->subMonths($offset);
        $yearStart = $monthDate->copy()->startOfYear();
        $yearEnd = $yearStart->copy()->endOfYear();
        if ($yearEnd->gt($today)) {
            $yearEnd = $today->copy()->endOfDay();
        }

        return [
            'day' => ['start' => $weekStart, 'end' => $weekEnd],
            'week' => ['start' => $monthStart, 'end' => $monthEndForWeeks],
            'month' => ['start' => $yearStart, 'end' => $yearEnd],
        ];
    }

    /**
     * Generate daily revenue data for charts.
     */
    private function generateDailyRevenueData($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy();
        $today = Carbon::now();

        while ($current->lte($endDate)) {
            if ($current->gt($today)) {
                $dayRevenue = 0;
            } else {
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
     * Generate weekly revenue data for period-based view (4 weeks).
     */
    private function generateWeeklyRevenueDataForPeriod($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfWeek();
        $today = Carbon::now();

        $weekCount = 0;
        while ($weekCount < 4) {
            $weekEnd = $current->copy()->endOfWeek();

            if ($current->gt($today)) {
                $weekRevenue = 0;
            } else {
                $countEndDate = $weekEnd->gt($today) ? $today : $weekEnd;

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
            $current->addWeek();
            $weekCount++;
        }

        return $data;
    }

    /**
     * Generate monthly revenue data for period-based view (12 months).
     */
    private function generateMonthlyRevenueDataForPeriod($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy()->startOfMonth();
        $today = Carbon::now();

        $monthCount = 0;
        while ($current->lte($endDate) && $monthCount < 12) {
            $monthEnd = $current->copy()->endOfMonth();

            if ($current->gt($today)) {
                $monthRevenue = 0;
            } else {
                $countEndDate = $monthEnd->gt($today) ? $today : $monthEnd;

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
            $current->addMonth();
            $monthCount++;
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
     * Generate weekly labels for period-based view (4 weeks).
     */
    private function generateWeeklyLabels($startDate, $endDate)
    {
        $labels = [];
        $current = $startDate->copy()->startOfWeek();
        $weekCount = 0;

        while ($weekCount < 4) {
            $weekEnd = $current->copy()->endOfWeek();
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
