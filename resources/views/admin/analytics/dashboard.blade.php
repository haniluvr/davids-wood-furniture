@extends('admin.layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Analytics Dashboard</h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Deep business intelligence and performance overview
        </p>
            </div>

    <div class="flex items-center gap-3">
        <!-- Date Range Filter -->
        <form method="GET" action="{{ admin_route('analytics.index') }}" class="flex items-center gap-2">
            <select name="date_range" id="date-range" onchange="this.form.submit()" class="rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
                <option value="7" {{ $dateRange == '7' ? 'selected' : '' }}>Last 7 days</option>
                <option value="30" {{ $dateRange == '30' ? 'selected' : '' }}>Last 30 days</option>
                <option value="90" {{ $dateRange == '90' ? 'selected' : '' }}>Last 90 days</option>
                <option value="365" {{ $dateRange == '365' ? 'selected' : '' }}>Last year</option>
                <option value="custom" {{ ($dateRange == 'custom' || (request('start_date') && request('end_date'))) ? 'selected' : '' }}>Custom</option>
            </select>
            
            @if($dateRange == 'custom' || request('start_date') || request('end_date'))
            <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
            <span class="text-sm text-stone-600 dark:text-gray-400">to</span>
            <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
            <button type="submit" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm font-medium text-stone-900 shadow-sm hover:bg-stone-50 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white dark:hover:bg-stone-700">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </button>
            @endif
            
            @if($dateRange == 'custom' || request('start_date') || request('end_date') || $dateRange != '30')
            <a href="{{ admin_route('analytics.index') }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm font-medium text-stone-900 shadow-sm hover:bg-stone-50 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white dark:hover:bg-stone-700" title="Clear date filter">
                <i data-lucide="x" class="w-4 h-4"></i>
            </a>
            @endif
        </form>
            </div>
</div>
<!-- Breadcrumb End -->
    <!-- Quick Actions -->
    <div class="mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ admin_route('analytics.sales') }}" class="group relative overflow-hidden rounded-xl border border-stone-200/50 bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 hover:scale-[1.02] dark:border-strokedark/50 dark:from-blue-900/20 dark:to-blue-800/10">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="chart-bar" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-stone-900 dark:text-white">Sales Reports</h3>
                        <p class="text-xs text-stone-600 dark:text-gray-400 mt-0.5">Revenue & orders</p>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-stone-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all duration-300"></i>
                </div>
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10"></div>
            </a>

            <a href="{{ admin_route('analytics.customers') }}" class="group relative overflow-hidden rounded-xl border border-stone-200/50 bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-lg shadow-emerald-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/20 hover:scale-[1.02] dark:border-strokedark/50 dark:from-emerald-900/20 dark:to-emerald-800/10">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="users" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-stone-900 dark:text-white">Customer Insights</h3>
                        <p class="text-xs text-stone-600 dark:text-gray-400 mt-0.5">Behavior & segments</p>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-stone-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all duration-300"></i>
                </div>
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10"></div>
            </a>

            <a href="{{ admin_route('analytics.products') }}" class="group relative overflow-hidden rounded-xl border border-stone-200/50 bg-gradient-to-br from-amber-50 to-amber-100/50 p-6 shadow-lg shadow-amber-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/20 hover:scale-[1.02] dark:border-strokedark/50 dark:from-amber-900/20 dark:to-amber-800/10">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="package" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-stone-900 dark:text-white">Product Reports</h3>
                        <p class="text-xs text-stone-600 dark:text-gray-400 mt-0.5">Performance & sales</p>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-stone-400 group-hover:text-amber-600 group-hover:translate-x-1 transition-all duration-300"></i>
                </div>
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-500/10"></div>
            </a>

            <a href="{{ admin_route('analytics.revenue') }}" class="group relative overflow-hidden rounded-xl border border-stone-200/50 bg-gradient-to-br from-purple-50 to-purple-100/50 p-6 shadow-lg shadow-purple-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 hover:scale-[1.02] dark:border-strokedark/50 dark:from-purple-900/20 dark:to-purple-800/10">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-stone-900 dark:text-white">Revenue Reports</h3>
                        <p class="text-xs text-stone-600 dark:text-gray-400 mt-0.5">Financial overview</p>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-stone-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all duration-300"></i>
                </div>
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
            </a>
        </div>
    </div>

    <!-- Revenue Over Time and Key Metrics Cards -->
    <div class="grid grid-cols-12 gap-6 mb-8">
        <!-- Revenue Over Time -->
        <div class="col-span-12 xl:col-span-8">
            <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-stone-900 dark:text-white">Revenue Over Time</h3>
                        <p class="text-sm text-stone-600 dark:text-gray-400">Daily revenue performance</p>
                    </div>
                    </div>
                <div>
                    <div id="revenueChart" class="h-[400px] w-full"></div>
                    </div>
                </div>
        </div>

        <!-- Key Metrics Cards (2x2 Grid) -->
        <div class="col-span-12 xl:col-span-4">
            <div class="grid grid-cols-2 gap-6 h-full">
                <!-- Total Orders -->
                <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 relative">
                    <div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                            <i data-lucide="shopping-cart" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                                {{ number_format($salesData['total_orders'] ?? 0) }}
                            </h3>
                            <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Orders</p>
                        </div>
                    </div>
                    <div class="absolute bottom-6 right-6">
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

        <!-- Total Revenue -->
                <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 relative">
                    <div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30">
                            <i data-lucide="dollar-sign" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                                ₱{{ number_format($salesData['total_revenue'] ?? 0, 2) }}
                            </h3>
                            <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Revenue</p>
                    </div>
                </div>
                    <div class="absolute bottom-6 right-6">
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

        <!-- Average Order Value -->
                <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 relative">
                    <div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                            <i data-lucide="trending-up" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
                    </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                                ₱{{ number_format($salesData['average_order_value'] ?? 0, 2) }}
                            </h3>
                            <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Avg Order Value</p>
                    </div>
                </div>
                    <div class="absolute bottom-6 right-6">
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

        <!-- Conversion Rate -->
                <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 relative">
                    <div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30">
                            <i data-lucide="target" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                                {{ number_format($salesData['conversion_rate'] ?? 0, 2) }}%
                            </h3>
                            <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Conversion Rate</p>
                        </div>
                    </div>
                    <div class="absolute bottom-6 right-6">
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
            </div>
        </div>
    </div>

<!-- Traffic Sources and Top Products -->
<div class="grid grid-cols-12 gap-6 mb-8 items-stretch">
    <!-- Traffic Sources (Pie Chart) -->
    <div class="col-span-12 xl:col-span-4">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full flex flex-col">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Traffic Sources</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Visitor acquisition channels</p>
                </div>
            </div>
            <div class="mb-6">
                <div id="trafficSourcesChart" class="mx-auto flex justify-center"></div>
            </div>
            <div class="space-y-3">
                @foreach($trafficSources as $source => $percentage)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-3 w-3 rounded-full" style="background-color: {{ ['organic_search' => '#3B82F6', 'direct_traffic' => '#10B981', 'social_media' => '#F59E0B', 'email_marketing' => '#EF4444', 'paid_search' => '#8B5CF6'][$source] ?? '#6B7280' }}"></div>
                            <span class="text-sm font-medium text-stone-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $source)) }}</span>
                        </div>
                            <span class="text-sm font-bold text-stone-900 dark:text-white">{{ number_format($percentage, 1) }}%</span>
                    </div>
                @endforeach
                </div>
            </div>
        </div>

        <!-- Top Products by Sales (Bar Chart) -->
        <div class="col-span-12 xl:col-span-8">
            <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full flex flex-col">
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-stone-900 dark:text-white">Top Products by Sales</h3>
                        <p class="text-sm text-stone-600 dark:text-gray-400">Best performing products this period</p>
                    </div>
                </div>
                <div class="flex-1 flex flex-col min-h-0">
                    <div id="topProductsChart" class="flex-1 w-full min-h-0"></div>
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
// Revenue Over Time Line Chart
@php
    $revenueByDate = $revenueData['by_date'] ?? collect();
    $revenueDates = $revenueByDate->keys()->map(function($date) { return \Carbon\Carbon::parse($date)->format('M d'); })->toArray();
    $revenueValues = $revenueByDate->values()->map(function($val) { return (float) $val; })->toArray();
@endphp
const revenueChartOptions = {
    series: [{
        name: 'Revenue',
        data: @json($revenueValues)
    }],
    chart: {
        type: 'line',
        height: 400,
        fontFamily: 'Inter, sans-serif',
        toolbar: {
            show: false
        }
    },
    colors: ['#a855f7'],
    stroke: {
        curve: 'smooth',
        width: 3
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
        categories: @json($revenueDates),
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
        y: {
            formatter: function (val) {
                return '₱' + val.toLocaleString()
            }
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

const revenueChart = new ApexCharts(document.querySelector('#revenueChart'), revenueChartOptions);
revenueChart.render();

// Top Products Bar Chart
@php
    $topProductsArray = is_array($topProducts ?? []) ? collect($topProducts)->take(10) : ($topProducts ?? collect())->take(10);
@endphp
const topProducts = @json($topProductsArray->map(function($p) { return ['name' => $p->name ?? '', 'total_revenue' => $p->total_revenue ?? 0]; })->values()->toArray());
const topProductsChartOptions = {
    series: [{
        name: 'Revenue',
        data: topProducts.map(p => parseFloat(p.total_revenue ?? 0))
    }],
    chart: {
        type: 'bar',
        height: 400,
        fontFamily: 'Inter, sans-serif',
        toolbar: {
            show: false
        }
    },
    colors: ['#8B5CF6'],
    plotOptions: {
        bar: {
            borderRadius: 8,
            horizontal: false,
            columnWidth: '60%',
            dataLabels: {
                position: 'top'
            }
        }
    },
    fill: {
        type: 'gradient',
        gradient: {
            shade: 'dark',
            type: 'vertical',
            shadeIntensity: 0.5,
            gradientToColors: ['#A855F7'],
            inverseColors: false,
            opacityFrom: 1,
            opacityTo: 0.8,
            stops: [0, 100]
        }
    },
    dataLabels: {
        enabled: true,
        offsetY: -20,
        style: {
            fontSize: '12px',
            fontWeight: 600,
            colors: ['#8B5CF6']
        },
        formatter: function (val) {
            return '₱' + parseFloat(val).toLocaleString();
        }
    },
    xaxis: {
        categories: topProducts.map(p => (p.name ?? '').length > 20 ? (p.name ?? '').substring(0, 20) + '...' : (p.name ?? '')),
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false
        },
        labels: {
            rotate: -45,
            rotateAlways: true
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
        y: {
            formatter: function (val) {
                return '₱' + val.toLocaleString()
            }
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

const topProductsChart = new ApexCharts(document.querySelector('#topProductsChart'), topProductsChartOptions);
topProductsChart.render();

// Resize Top Products chart to fill container
function resizeTopProductsChart() {
    const chartContainer = document.querySelector('#topProductsChart');
    if (chartContainer && topProductsChart) {
        // Get the parent flex container height
        const parentFlex = chartContainer.closest('.flex.flex-col');
        if (parentFlex) {
            const availableHeight = parentFlex.offsetHeight;
            if (availableHeight > 0) {
                topProductsChart.updateOptions({
                    chart: {
                        height: availableHeight
                    }
                });
            }
        }
    }
}

// Resize on load and window resize
setTimeout(resizeTopProductsChart, 100);
setTimeout(resizeTopProductsChart, 500);
setTimeout(resizeTopProductsChart, 1000);
window.addEventListener('resize', resizeTopProductsChart);

// Traffic Sources Pie Chart
const trafficSources = @json($trafficSources ?? []);
const trafficSourcesChartOptions = {
    series: Object.values(trafficSources),
    chart: {
        type: 'donut',
        width: 300,
        height: 300,
    },
    colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
    labels: Object.keys(trafficSources).map(key => ucfirst(key.replace('_', ' '))),
    legend: {
        show: false,
    },
    plotOptions: {
        pie: {
            donut: {
                size: '65%',
                background: 'transparent',
            },
        },
    },
    dataLabels: {
        enabled: false,
    },
};

function ucfirst(str) {
    return str.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
}

const trafficSourcesChart = new ApexCharts(document.querySelector('#trafficSourcesChart'), trafficSourcesChartOptions);
trafficSourcesChart.render();
</script>
@endpush
