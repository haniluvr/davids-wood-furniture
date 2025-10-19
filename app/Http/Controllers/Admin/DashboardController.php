<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
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
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $lowStockProducts = Product::where('stock_quantity', '<=', DB::raw('low_stock_threshold'))->take(5)->get();

        // Recent activity feed
        $recentActivity = collect();
        
        // Add recent orders to activity
        $recentOrdersActivity = Order::with('user')->orderBy('created_at', 'desc')->take(3)->get()->map(function($order) {
            $customerName = $order->user ? ($order->user->first_name ?? 'Guest') : 'Guest';
            return [
                'type' => 'order',
                'title' => 'New Order',
                'message' => "Order #{$order->order_number} from {$customerName}",
                'timestamp' => $order->created_at,
                'url' => route('admin.orders.show', $order)
            ];
        });

        // Add recent messages to activity
        $recentMessagesActivity = \App\Models\ContactMessage::with('user')->orderBy('created_at', 'desc')->take(3)->get()->map(function($message) {
            return [
                'type' => 'message',
                'title' => 'New Message',
                'message' => "Message from {$message->name}",
                'timestamp' => $message->created_at,
                'url' => route('admin.contact-messages.show', $message)
            ];
        });

        // Add low stock alerts to activity
        $lowStockActivity = Product::where('stock_quantity', '<=', DB::raw('low_stock_threshold'))->take(3)->get()->map(function($product) {
            return [
                'type' => 'inventory',
                'title' => 'Low Stock Alert',
                'message' => "{$product->name} is running low ({$product->stock_quantity} left)",
                'timestamp' => now(),
                'url' => route('admin.inventory.show', $product)
            ];
        });

        $recentActivity = $recentOrdersActivity->concat($recentMessagesActivity)->concat($lowStockActivity)
            ->sortByDesc('timestamp')->take(10);

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
            if (!isset($monthlyOrders[$i])) {
                $monthlyOrders[$i] = 0;
            }
            if (!isset($monthlyRevenue[$i])) {
                $monthlyRevenue[$i] = 0;
            }
        }

        ksort($monthlyOrders);
        ksort($monthlyRevenue);

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
            'orderStatusDistribution'
        ));
    }
}