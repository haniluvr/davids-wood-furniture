@extends('admin.layouts.app')

@section('title', 'Sales Analytics')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Sales Analytics</h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Track your sales performance and identify trends
        </p>
    </div>

    <div class="flex items-center gap-3">
        <!-- Date Range Filter -->
        <form method="GET" action="{{ admin_route('analytics.sales') }}" class="flex items-center gap-2">
            <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
            <span class="text-sm text-stone-600 dark:text-gray-400">to</span>
            <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
            <button type="submit" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm font-medium text-stone-900 shadow-sm hover:bg-stone-50 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white dark:hover:bg-stone-700">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </button>
            @if(request('start_date') || request('end_date'))
            <a href="{{ admin_route('analytics.sales') }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm font-medium text-stone-900 shadow-sm hover:bg-stone-50 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white dark:hover:bg-stone-700" title="Clear date filter">
                <i data-lucide="x" class="w-4 h-4"></i>
            </a>
            @endif
        </form>
        
        <a href="{{ admin_route('analytics.export', ['type' => 'sales', 'start_date' => request('start_date', $startDate->format('Y-m-d')), 'end_date' => request('end_date', $endDate->format('Y-m-d'))]) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="download" class="w-4 h-4"></i>
            Export
        </a>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Sales Overview Cards -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4 mb-8">
    <!-- Total Sales -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 dark:from-blue-900/20 dark:to-blue-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($totalSales) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Sales</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['total_sales'] ?? 0;
                    $isPositive = $change >= 0;
                    $bgColor = $isPositive ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30';
                    $textColor = $isPositive ? 'text-green-800 dark:text-green-400' : 'text-red-800 dark:text-red-400';
                    $icon = $isPositive ? 'trending-up' : 'trending-down';
                @endphp
                <span class="inline-flex items-center gap-1 rounded-full {{ $bgColor }} px-2.5 py-1 text-xs font-medium {{ $textColor }}">
                    <i data-lucide="{{ $icon }}" class="w-3 h-3"></i>
                    {{ $isPositive ? '+' : '' }}{{ number_format($change, 1) }}%
                </span>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10"></div>
    </div>

    <!-- Total Revenue -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-lg shadow-emerald-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/20 dark:from-emerald-900/20 dark:to-emerald-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        ₱{{ number_format($totalRevenue, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Revenue</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['total_revenue'] ?? 0;
                    $isPositive = $change >= 0;
                    $bgColor = $isPositive ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30';
                    $textColor = $isPositive ? 'text-green-800 dark:text-green-400' : 'text-red-800 dark:text-red-400';
                    $icon = $isPositive ? 'trending-up' : 'trending-down';
                @endphp
                <span class="inline-flex items-center gap-1 rounded-full {{ $bgColor }} px-2.5 py-1 text-xs font-medium {{ $textColor }}">
                    <i data-lucide="{{ $icon }}" class="w-3 h-3"></i>
                    {{ $isPositive ? '+' : '' }}{{ number_format($change, 1) }}%
                </span>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10"></div>
    </div>

    <!-- Average Order Value -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100/50 p-6 shadow-lg shadow-amber-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/20 dark:from-amber-900/20 dark:to-amber-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500 shadow-lg">
                    <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        ₱{{ number_format($averageOrderValue, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Avg Order Value</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['avg_order_value'] ?? 0;
                    $isPositive = $change >= 0;
                    $bgColor = $isPositive ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30';
                    $textColor = $isPositive ? 'text-green-800 dark:text-green-400' : 'text-red-800 dark:text-red-400';
                    $icon = $isPositive ? 'trending-up' : 'trending-down';
                @endphp
                <span class="inline-flex items-center gap-1 rounded-full {{ $bgColor }} px-2.5 py-1 text-xs font-medium {{ $textColor }}">
                    <i data-lucide="{{ $icon }}" class="w-3 h-3"></i>
                    {{ $isPositive ? '+' : '' }}{{ number_format($change, 1) }}%
                </span>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-500/10"></div>
    </div>

    <!-- Conversion Rate -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100/50 p-6 shadow-lg shadow-purple-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 dark:from-purple-900/20 dark:to-purple-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 shadow-lg">
                    <i data-lucide="target" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($conversionRate, 1) }}%
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Conversion Rate</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['conversion_rate'] ?? 0;
                    $isPositive = $change >= 0;
                    $bgColor = $isPositive ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30';
                    $textColor = $isPositive ? 'text-green-800 dark:text-green-400' : 'text-red-800 dark:text-red-400';
                    $icon = $isPositive ? 'trending-up' : 'trending-down';
                @endphp
                <span class="inline-flex items-center gap-1 rounded-full {{ $bgColor }} px-2.5 py-1 text-xs font-medium {{ $textColor }}">
                    <i data-lucide="{{ $icon }}" class="w-3 h-3"></i>
                    {{ $isPositive ? '+' : '' }}{{ number_format($change, 1) }}%
                </span>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-12 gap-6 mb-8">
    <!-- Sales Trend Chart -->
    <div class="col-span-12 xl:col-span-8">
        <div id="salesTrendContainer" class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Sales Trend</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Daily sales performance over time</p>
                </div>
                <div class="flex items-center gap-2">
                    <button id="sales-period-prev" class="sales-period-nav inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Previous period">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <div class="inline-flex items-center rounded-xl bg-stone-100 p-1 dark:bg-stone-800">
                        <button id="sales-period-day" class="sales-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($salesCurrentPeriod ?? 'month') == 'day' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Day
                        </button>
                        <button id="sales-period-week" class="sales-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($salesCurrentPeriod ?? 'month') == 'week' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Week
                        </button>
                        <button id="sales-period-month" class="sales-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($salesCurrentPeriod ?? 'month') == 'month' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Month
                        </button>
                    </div>
                    <button id="sales-period-next" class="sales-period-nav inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Next period" disabled>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                    @if(request('sales_period_offset') || request('sales_current_period'))
                    <button id="sales-period-clear" class="sales-period-clear inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Clear period filter">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                    @endif
                </div>
            </div>
            
            <div class="mb-6 flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                    <div>
                        <p class="text-sm font-medium text-stone-900 dark:text-white">Revenue</p>
                        <p class="text-lg font-bold text-blue-600">₱{{ number_format($totalRevenue, 2) }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-3 w-3 rounded-full bg-red-500"></div>
                    <div>
                        <p class="text-sm font-medium text-stone-900 dark:text-white">Expenses</p>
                        <p class="text-lg font-bold text-red-600">₱{{ number_format($totalExpenses ?? 0, 2) }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
                    <div>
                        <p class="text-sm font-medium text-stone-900 dark:text-white">Profit</p>
                        <p class="text-lg font-bold text-emerald-600">₱{{ number_format($totalRevenue - $totalExpenses, 2) }}</p>
                    </div>
                </div>
            </div>

            <div>
                <div id="salesTrendChart" class="h-[400px] w-full"></div>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-span-12 xl:col-span-4">
        <div id="topProductsContainer" class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Top Products</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Best selling products this period</p>
                </div>
            </div>

            <div class="space-y-4 overflow-y-auto pr-2" id="topProductsList">
                @forelse($topProducts as $index => $product)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-stone-200/50 transition-all duration-200 hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-strokedark/50 dark:hover:border-emerald-800/50 dark:hover:bg-emerald-900/10">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 font-bold text-sm dark:bg-emerald-900/30 dark:text-emerald-400">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-stone-900 dark:text-white">{{ $product->name }}</p>
                        <p class="text-xs text-stone-500 dark:text-gray-400">{{ $product->total_sold }} sold</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-stone-900 dark:text-white">₱{{ number_format($product->total_revenue, 2) }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4 dark:bg-stone-800">
                        <i data-lucide="package" class="w-6 h-6 text-stone-400"></i>
                    </div>
                    <p class="text-sm text-stone-500 dark:text-gray-400">No sales data available</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Additional Charts Section -->
<div class="grid grid-cols-12 gap-6 mb-8 items-stretch">
    <!-- Sales by Category -->
    <div class="col-span-12 xl:col-span-6">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full flex flex-col">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Sales by Product Category</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Revenue breakdown by category</p>
                </div>
            </div>

            <div class="flex-1 flex items-center gap-6">
                <!-- Pie Chart on Left -->
                <div class="flex-shrink-0">
                    <div id="categorySalesChart" class="w-[300px] h-[300px]"></div>
                </div>

                <!-- Legend on Right -->
                <div class="flex-1 space-y-3 overflow-y-auto pr-2">
                    @foreach($salesByCategory as $category)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-3 w-3 rounded-full" style="background-color: {{ $category->color ?? '#3B82F6' }}"></div>
                            <span class="text-sm font-medium text-stone-900 dark:text-white">{{ $category->name }}</span>
                        </div>
                        <span class="text-sm font-bold text-stone-900 dark:text-white">₱{{ number_format($category->total_revenue ?? 0, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Discount Usage -->
    <div class="col-span-12 xl:col-span-6">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full flex flex-col">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Discount Usage</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Promotion and discount analysis</p>
                </div>
            </div>

            <div class="flex-1 flex flex-col">
                <div class="grid grid-cols-2 gap-4 flex-1">
                    <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex flex-col justify-center">
                        <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Discounts</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">₱{{ number_format($discountUsage['total_discounts'] ?? 0, 2) }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex flex-col justify-center">
                        <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Orders with Discount</p>
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($discountUsage['orders_with_discount'] ?? 0) }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex flex-col justify-center">
                        <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Average Discount</p>
                        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">₱{{ number_format($discountUsage['average_discount'] ?? 0, 2) }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex flex-col justify-center">
                        <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Discount Rate</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                            @if(($discountUsage['total_orders'] ?? 0) > 0)
                                {{ number_format((($discountUsage['orders_with_discount'] ?? 0) / $discountUsage['total_orders']) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Period Comparison -->
<div class="mb-8">
    <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-stone-900 dark:text-white">Period Comparison</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Compare current period with previous periods</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- Month over Month -->
            <div class="rounded-xl border border-stone-200/50 bg-stone-50/50 p-6 dark:border-strokedark/50 dark:bg-stone-800/30">
                <div class="mb-4 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5 text-stone-600 dark:text-gray-400"></i>
                    <h4 class="text-lg font-bold text-stone-900 dark:text-white">Month over Month</h4>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-stone-600 dark:text-gray-400">Revenue</span>
                            <span class="text-sm font-bold {{ ($periodComparison['mom']['revenue']['change'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ ($periodComparison['mom']['revenue']['change'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($periodComparison['mom']['revenue']['change'] ?? 0, 1) }}%
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-stone-500 dark:text-gray-500">Current: ₱{{ number_format($periodComparison['mom']['revenue']['current'] ?? 0, 2) }}</span>
                            <span class="text-xs text-stone-500 dark:text-gray-500">Previous: ₱{{ number_format($periodComparison['mom']['revenue']['previous'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-stone-600 dark:text-gray-400">Orders</span>
                            <span class="text-sm font-bold {{ ($periodComparison['mom']['orders']['change'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ ($periodComparison['mom']['orders']['change'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($periodComparison['mom']['orders']['change'] ?? 0, 1) }}%
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-stone-500 dark:text-gray-500">Current: {{ number_format($periodComparison['mom']['orders']['current'] ?? 0) }}</span>
                            <span class="text-xs text-stone-500 dark:text-gray-500">Previous: {{ number_format($periodComparison['mom']['orders']['previous'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year over Year -->
            <div class="rounded-xl border border-stone-200/50 bg-stone-50/50 p-6 dark:border-strokedark/50 dark:bg-stone-800/30">
                <div class="mb-4 flex items-center gap-2">
                    <i data-lucide="calendar-days" class="w-5 h-5 text-stone-600 dark:text-gray-400"></i>
                    <h4 class="text-lg font-bold text-stone-900 dark:text-white">Year over Year</h4>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-stone-600 dark:text-gray-400">Revenue</span>
                            <span class="text-sm font-bold {{ ($periodComparison['yoy']['revenue']['change'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ ($periodComparison['yoy']['revenue']['change'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($periodComparison['yoy']['revenue']['change'] ?? 0, 1) }}%
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-stone-500 dark:text-gray-500">Current: ₱{{ number_format($periodComparison['yoy']['revenue']['current'] ?? 0, 2) }}</span>
                            <span class="text-xs text-stone-500 dark:text-gray-500">Previous: ₱{{ number_format($periodComparison['yoy']['revenue']['previous'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-stone-600 dark:text-gray-400">Orders</span>
                            <span class="text-sm font-bold {{ ($periodComparison['yoy']['orders']['change'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ ($periodComparison['yoy']['orders']['change'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($periodComparison['yoy']['orders']['change'] ?? 0, 1) }}%
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-stone-500 dark:text-gray-500">Current: {{ number_format($periodComparison['yoy']['orders']['current'] ?? 0) }}</span>
                            <span class="text-xs text-stone-500 dark:text-gray-500">Previous: {{ number_format($periodComparison['yoy']['orders']['previous'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Prepare period data for Sales Trend chart
const salesPeriodData = {
    day: {
        labels: @json($dailyLabels ?? []),
        revenue: @json($dailyRevenue ?? []),
        expenses: @json($dailyExpenses ?? []),
        profit: @json($dailyProfit ?? []),
        orders: @json($dailyOrders ?? [])
    },
    week: {
        labels: @json($weeklyLabels ?? []),
        revenue: @json($weeklyRevenue ?? []),
        expenses: @json($weeklyExpenses ?? []),
        profit: @json($weeklyProfit ?? []),
        orders: @json($weeklyOrders ?? [])
    },
    month: {
        labels: @json($monthlyLabels ?? []),
        revenue: @json($monthlyRevenue ?? []),
        expenses: @json($monthlyExpenses ?? []),
        profit: @json($monthlyProfit ?? []),
        orders: @json($monthlyOrders ?? [])
    }
};

// Set default to month view
let currentSalesPeriod = '{{ $salesCurrentPeriod ?? 'month' }}';
let salesPeriodOffset = {{ $salesPeriodOffset ?? 0 }};
const maxSalesPeriodOffset = 0; // Cannot go forward past today

// Sales Trend Chart - Area Chart
const salesTrendOptions = {
    series: [{
        name: 'Revenue',
        data: salesPeriodData[currentSalesPeriod]?.revenue || []
    }, {
        name: 'Expenses',
        data: salesPeriodData[currentSalesPeriod]?.expenses || []
    }, {
        name: 'Profit',
        data: salesPeriodData[currentSalesPeriod]?.profit || []
    }],
    chart: {
        type: 'area',
        height: 400,
        fontFamily: 'Inter, sans-serif',
        toolbar: {
            show: false
        }
    },
    colors: ['#3B82F6', '#EF4444', '#10B981'], // Blue for Revenue, Red for Expenses, Green for Profit
    stroke: {
        curve: 'smooth',
        width: 2
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.3,
            stops: [0, 100]
        }
    },
    xaxis: {
        categories: salesPeriodData[currentSalesPeriod]?.labels || [],
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false
        }
    },
    yaxis: {
        labels: {
            formatter: function (val) {
                return '₱' + val.toLocaleString()
            }
        }
    },
    tooltip: {
        shared: true,
        intersect: false,
        custom: function({ series, seriesIndex, dataPointIndex, w }) {
            const periodData = salesPeriodData[currentSalesPeriod];
            const orders = periodData?.orders || [];
            const orderCount = orders[dataPointIndex] || 0;
            
            const revenue = series[0] ? series[0][dataPointIndex] : 0;
            const expenses = series[1] ? series[1][dataPointIndex] : 0;
            const profit = series[2] ? series[2][dataPointIndex] : 0;
            
            return '<div class="p-3 bg-white dark:bg-stone-800 rounded-lg shadow-lg border border-stone-200 dark:border-stone-700">' +
                '<div class="text-xs font-medium text-stone-500 dark:text-gray-400 mb-2">' + (w.globals.categoryLabels[dataPointIndex] || '') + '</div>' +
                '<div class="space-y-1">' +
                '<div class="flex items-center justify-between gap-4">' +
                '<div class="flex items-center gap-2">' +
                '<div class="w-2 h-2 rounded-full bg-blue-500"></div>' +
                '<span class="text-xs font-medium text-stone-600 dark:text-gray-400">Revenue</span>' +
                '</div>' +
                '<span class="text-xs font-bold text-stone-900 dark:text-white">₱' + (revenue || 0).toLocaleString() + '</span>' +
                '</div>' +
                '<div class="flex items-center justify-between gap-4">' +
                '<div class="flex items-center gap-2">' +
                '<div class="w-2 h-2 rounded-full bg-red-500"></div>' +
                '<span class="text-xs font-medium text-stone-600 dark:text-gray-400">Expenses</span>' +
                '</div>' +
                '<span class="text-xs font-bold text-stone-900 dark:text-white">₱' + (expenses || 0).toLocaleString() + '</span>' +
                '</div>' +
                '<div class="flex items-center justify-between gap-4">' +
                '<div class="flex items-center gap-2">' +
                '<div class="w-2 h-2 rounded-full bg-green-500"></div>' +
                '<span class="text-xs font-medium text-stone-600 dark:text-gray-400">Profit</span>' +
                '</div>' +
                '<span class="text-xs font-bold text-stone-900 dark:text-white">₱' + (profit || 0).toLocaleString() + '</span>' +
                '</div>' +
                '<div class="flex items-center justify-between gap-4 mt-2 pt-2 border-t border-stone-200 dark:border-stone-700">' +
                '<div class="flex items-center gap-2">' +
                '<div class="w-2 h-2 rounded-full bg-purple-500"></div>' +
                '<span class="text-xs font-medium text-stone-600 dark:text-gray-400">Orders</span>' +
                '</div>' +
                '<span class="text-xs font-bold text-stone-900 dark:text-white">' + orderCount + '</span>' +
                '</div>' +
                '</div>' +
                '</div>';
        }
    },
    legend: {
        show: false
    },
    grid: {
        borderColor: '#f1f5f9',
        strokeDashArray: 4
    }
};

const salesTrendChart = new ApexCharts(document.querySelector('#salesTrendChart'), salesTrendOptions);
salesTrendChart.render();

// Match Top Products container height to Sales Trend container
function matchContainerHeights() {
    const salesTrendContainer = document.getElementById('salesTrendContainer');
    const topProductsContainer = document.getElementById('topProductsContainer');
    const topProductsList = document.getElementById('topProductsList');
    
    if (salesTrendContainer && topProductsContainer) {
        const salesTrendHeight = salesTrendContainer.offsetHeight;
        topProductsContainer.style.height = salesTrendHeight + 'px';
        
        // Calculate available height for the list (container height - header - padding)
        if (topProductsList) {
            const headerHeight = topProductsContainer.querySelector('.mb-6')?.offsetHeight || 80;
            const padding = 48; // 24px top + 24px bottom (p-6 = 1.5rem = 24px)
            const availableHeight = salesTrendHeight - headerHeight - padding;
            topProductsList.style.maxHeight = availableHeight + 'px';
        }
    }
}

// Match heights on load and resize
window.addEventListener('resize', matchContainerHeights);
setTimeout(matchContainerHeights, 100);
setTimeout(matchContainerHeights, 500);

// Update navigation buttons state
function updateSalesPeriodNavigation() {
    const prevBtn = document.getElementById('sales-period-prev');
    const nextBtn = document.getElementById('sales-period-next');
    
    // Can always go back
    if (prevBtn) prevBtn.disabled = false;
    
    // Cannot go forward past today (offset must be >= 0)
    if (nextBtn) nextBtn.disabled = salesPeriodOffset <= maxSalesPeriodOffset;
}

// Navigate to previous period
// Logic: 
// - Month backward: Show past year (12 months back, so offset += 12)
// - Week backward: Show past month's 4 previous weeks (4 weeks back, so offset += 4)
// - Day backward: Show last week's per day (7 days back, so offset += 7)
const salesPrevBtn = document.getElementById('sales-period-prev');
if (salesPrevBtn) {
    salesPrevBtn.addEventListener('click', function() {
        let offsetIncrement = 1; // Default increment
        
        if (currentSalesPeriod === 'month') {
            offsetIncrement = 12; // Go back 12 months (a full year)
        } else if (currentSalesPeriod === 'week') {
            offsetIncrement = 4; // Go back 4 weeks (a full month of weeks)
        } else if (currentSalesPeriod === 'day') {
            offsetIncrement = 7; // Go back 7 days (a full week)
        }
        
        salesPeriodOffset += offsetIncrement;
        updateSalesPeriodNavigation();
        loadSalesPeriodData();
    });
}

// Navigate to next period
// Logic: Reverse of backward navigation
const salesNextBtn = document.getElementById('sales-period-next');
if (salesNextBtn) {
    salesNextBtn.addEventListener('click', function() {
        let offsetDecrement = 1; // Default decrement
        
        if (currentSalesPeriod === 'month') {
            offsetDecrement = 12; // Go forward 12 months (a full year)
        } else if (currentSalesPeriod === 'week') {
            offsetDecrement = 4; // Go forward 4 weeks (a full month of weeks)
        } else if (currentSalesPeriod === 'day') {
            offsetDecrement = 7; // Go forward 7 days (a full week)
        }
        
        if (salesPeriodOffset >= offsetDecrement) {
            salesPeriodOffset -= offsetDecrement;
            updateSalesPeriodNavigation();
            loadSalesPeriodData();
        }
    });
}

// Load period data from server
function loadSalesPeriodData() {
    // Get current date filter values
    const urlParams = new URLSearchParams(window.location.search);
    const startDate = urlParams.get('start_date') || '';
    const endDate = urlParams.get('end_date') || '';
    
    // Build URL with period parameters
    const params = new URLSearchParams({
        sales_period_offset: salesPeriodOffset,
        sales_current_period: currentSalesPeriod,
        start_date: startDate,
        end_date: endDate
    });
    
    // Reload page with new period offset
    window.location.href = '{{ admin_route('analytics.sales') }}?' + params.toString();
}

// Period toggle handler for Sales Trend chart
// Wait for DOM to be ready
(function() {
    function initSalesPeriodToggles() {
        const buttons = document.querySelectorAll('.sales-period-toggle');
        if (buttons.length === 0) {
            // Buttons not ready yet, try again
            setTimeout(initSalesPeriodToggles, 100);
            return;
        }
        
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const period = this.id.replace('sales-period-', '');
                currentSalesPeriod = period;
                
                // Reset offset to 0 when switching period types and reload to get fresh data
                salesPeriodOffset = 0;
                loadSalesPeriodData();
            });
        });
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSalesPeriodToggles);
    } else {
        initSalesPeriodToggles();
    }
})();

// Initialize navigation state
updateSalesPeriodNavigation();

// Clear period filter button handler
const salesClearBtn = document.getElementById('sales-period-clear');
if (salesClearBtn) {
    salesClearBtn.addEventListener('click', function(e) {
        e.preventDefault();
        // Get current date filter values
        const urlParams = new URLSearchParams(window.location.search);
        const startDate = urlParams.get('start_date') || '';
        const endDate = urlParams.get('end_date') || '';
        
        // Build URL without period parameters
        const params = new URLSearchParams();
        if (startDate) params.set('start_date', startDate);
        if (endDate) params.set('end_date', endDate);
        
        // Reload page without period filter
        const url = '{{ admin_route('analytics.sales') }}' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    });
}

// Category Sales Chart
const categorySalesOptions = {
    series: @json($salesByCategory->pluck('total_revenue')->map(function($val) { return (float) ($val ?? 0); })->toArray()),
    chart: {
        type: 'donut',
        width: 300,
        height: 300,
        fontFamily: 'Inter, sans-serif',
    },
    colors: @json($salesByCategory->pluck('color')->map(function($color) { return $color ?? '#3B82F6'; })->toArray()),
    labels: @json($salesByCategory->pluck('name')->toArray()),
    legend: {
        show: false,
    },
    plotOptions: {
        pie: {
            donut: {
                size: '70%',
                background: 'transparent',
            },
        },
    },
    dataLabels: {
        enabled: false,
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return '₱' + val.toLocaleString()
            }
        }
    },
};

const categorySalesChart = new ApexCharts(document.querySelector('#categorySalesChart'), categorySalesOptions);
categorySalesChart.render();
</script>
@endpush
