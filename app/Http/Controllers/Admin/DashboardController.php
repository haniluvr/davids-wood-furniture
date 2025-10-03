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

        // Get recent activity
        $recentProducts = Product::orderBy('created_at', 'desc')->take(5)->get();
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $lowStockProducts = Product::where('stock_quantity', '<=', DB::raw('low_stock_threshold'))->take(5)->get();

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

        // Get recent audit logs (simplified for now)
        $recentActivity = collect(); // Empty collection for now

        return view('admin.dashboard.index', compact(
            'totalUsers',
            'totalProducts', 
            'totalOrders',
            'totalRevenue',
            'recentProducts',
            'recentOrders',
            'lowStockProducts',
            'monthlyOrders',
            'monthlyRevenue',
            'recentActivity'
        ));
    }
}