@extends('admin.layouts.app')

@section('title', 'Product Analytics')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Product Analytics</h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Analyze product performance and identify top sellers
        </p>
    </div>

    <div class="flex items-center gap-3">
        <!-- Date Range Filter -->
        <form method="GET" action="{{ admin_route('analytics.products') }}" class="flex items-center gap-2">
            <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
            <span class="text-sm text-stone-600 dark:text-gray-400">to</span>
            <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
            <button type="submit" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm font-medium text-stone-900 shadow-sm hover:bg-stone-50 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white dark:hover:bg-stone-700">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </button>
        </form>
        
        @if(request('start_date') || request('end_date') || request('category') || request('sort_by') || request('period_offset') || request('current_period'))
        <a href="{{ admin_route('analytics.products') }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm font-medium text-stone-900 shadow-sm hover:bg-stone-50 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white dark:hover:bg-stone-700" title="Clear all filters">
            <i data-lucide="x-circle" class="w-4 h-4"></i>
            Clear All Filters
        </a>
        @endif
        
        <a href="{{ admin_route('analytics.export', ['type' => 'products', 'start_date' => request('start_date', $startDate->format('Y-m-d')), 'end_date' => request('end_date', $endDate->format('Y-m-d'))]) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="download" class="w-4 h-4"></i>
            Export
        </a>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Product Overview Cards -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4 mb-8">
    <!-- Total Products -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 dark:from-blue-900/20 dark:to-blue-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                    <i data-lucide="package" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($totalProducts) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Products</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['total_products'] ?? 0;
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

    <!-- Products Sold -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-lg shadow-emerald-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/20 dark:from-emerald-900/20 dark:to-emerald-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($totalUnitsSold) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Units Sold</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['units_sold'] ?? 0;
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

    <!-- Product Revenue -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100/50 p-6 shadow-lg shadow-amber-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/20 dark:from-amber-900/20 dark:to-amber-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500 shadow-lg">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        ₱{{ number_format($totalProductRevenue, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Product Revenue</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['product_revenue'] ?? 0;
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

    <!-- Average Price -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100/50 p-6 shadow-lg shadow-purple-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 dark:from-purple-900/20 dark:to-purple-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 shadow-lg">
                    <i data-lucide="tag" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        ₱{{ number_format($averageProductPrice, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Average Price</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['avg_product_price'] ?? 0;
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
<div class="grid grid-cols-12 gap-6 items-stretch mb-8">
    <!-- Product Performance Chart -->
    <div class="col-span-12 xl:col-span-8">
        <div class="flex flex-col rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full" style="min-height: 550px;">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Product Performance</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Top performing products by revenue</p>
                </div>
                <div class="flex items-center gap-2">
                    <button id="product-period-prev" class="product-period-nav inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Previous period">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <div class="inline-flex items-center rounded-xl bg-stone-100 p-1 dark:bg-stone-800">
                        <button id="product-period-day" class="product-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($currentPeriod ?? 'week') == 'day' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Day
                        </button>
                        <button id="product-period-week" class="product-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($currentPeriod ?? 'week') == 'week' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Week
                        </button>
                        <button id="product-period-month" class="product-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($currentPeriod ?? 'week') == 'month' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Month
                        </button>
                    </div>
                    <button id="product-period-next" class="product-period-nav inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Next period" disabled>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 flex flex-col">
                <div id="productPerformanceChart" class="w-full flex-1"></div>
            </div>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="col-span-12 xl:col-span-4">
        <div class="flex flex-col rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full" style="min-height: 550px;">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Category Breakdown</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Sales by product category</p>
                </div>
            </div>

            <div class="mb-6 flex-1 flex items-center justify-center">
                <div id="categoryBreakdownChart" class="mx-auto flex justify-center w-full"></div>
            </div>

            <div class="space-y-3">
                @foreach($categorySales as $category)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full" style="background-color: {{ $category->color ?? '#3B82F6' }}"></div>
                        <span class="text-sm font-medium text-stone-900 dark:text-white">{{ $category->name }}</span>
                    </div>
                    <span class="text-sm font-bold text-stone-900 dark:text-white">₱{{ number_format($category->total_revenue, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Comprehensive Product Performance Table -->
<div class="mb-8" id="product-performance-report">
    <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-stone-900 dark:text-white">Product Performance Report</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Comprehensive product analytics with all key metrics</p>
            </div>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ admin_route('analytics.products') }}" id="productReportFilterForm" class="flex items-center gap-2">
                    <input type="hidden" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                    <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
                    <select name="category" onchange="sessionStorage.setItem('scrollToProductReport', 'true'); this.form.action = this.form.action.split('#')[0] + '#product-performance-report'; this.form.submit();" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
                        <option value="all" {{ ($categoryFilter ?? 'all') == 'all' ? 'selected' : '' }}>All Categories</option>
                        @foreach($mainCategories as $category)
                        <option value="{{ $category->id }}" {{ ($categoryFilter ?? 'all') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </form>
                @if(($categoryFilter && $categoryFilter !== 'all') || ($sortBy != 'units_sold' || $sortOrder != 'desc'))
                <a href="{{ admin_route('analytics.products', ['start_date' => request('start_date', $startDate->format('Y-m-d')), 'end_date' => request('end_date', $endDate->format('Y-m-d'))]) }}#product-performance-report" onclick="sessionStorage.setItem('scrollToProductReport', 'true');" class="inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm font-medium text-stone-900 shadow-sm hover:bg-stone-50 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white dark:hover:bg-stone-700" title="Clear filters and sort">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Clear
                </a>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <div class="overflow-y-auto" style="max-height: 600px;">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-stone-50 dark:bg-stone-800/50 z-10">
                        <tr class="border-b border-stone-200 dark:border-strokedark">
                            <th class="text-left p-4 font-semibold text-stone-700 dark:text-stone-300">Product</th>
                            <th class="text-center p-4 font-semibold text-stone-700 dark:text-stone-300">SKU</th>
                            <th class="text-center p-4 font-semibold text-stone-700 dark:text-stone-300">
                                <div class="flex items-center justify-center gap-2">
                                    Units Sold
                                    <div class="flex flex-col">
                                        <a href="{{ admin_route('analytics.products', array_merge(request()->all(), ['sort_by' => 'units_sold', 'sort_order' => $sortBy == 'units_sold' && $sortOrder == 'asc' ? 'desc' : 'asc'])) }}#product-performance-report" onclick="sessionStorage.setItem('scrollToProductReport', 'true');" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 {{ $sortBy == 'units_sold' && $sortOrder == 'asc' ? 'text-emerald-600 dark:text-emerald-400' : '' }}">
                                            <i data-lucide="chevron-up" class="w-3 h-3"></i>
                                        </a>
                                        <a href="{{ admin_route('analytics.products', array_merge(request()->all(), ['sort_by' => 'units_sold', 'sort_order' => $sortBy == 'units_sold' && $sortOrder == 'desc' ? 'asc' : 'desc'])) }}#product-performance-report" onclick="sessionStorage.setItem('scrollToProductReport', 'true');" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 {{ $sortBy == 'units_sold' && $sortOrder == 'desc' ? 'text-emerald-600 dark:text-emerald-400' : '' }}">
                                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <th class="text-center p-4 font-semibold text-stone-700 dark:text-stone-300">
                                <div class="flex items-center justify-center gap-2">
                                    Revenue
                                    <div class="flex flex-col">
                                        <a href="{{ admin_route('analytics.products', array_merge(request()->all(), ['sort_by' => 'revenue', 'sort_order' => $sortBy == 'revenue' && $sortOrder == 'asc' ? 'desc' : 'asc'])) }}#product-performance-report" onclick="sessionStorage.setItem('scrollToProductReport', 'true');" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 {{ $sortBy == 'revenue' && $sortOrder == 'asc' ? 'text-emerald-600 dark:text-emerald-400' : '' }}">
                                            <i data-lucide="chevron-up" class="w-3 h-3"></i>
                                        </a>
                                        <a href="{{ admin_route('analytics.products', array_merge(request()->all(), ['sort_by' => 'revenue', 'sort_order' => $sortBy == 'revenue' && $sortOrder == 'desc' ? 'asc' : 'desc'])) }}#product-performance-report" onclick="sessionStorage.setItem('scrollToProductReport', 'true');" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 {{ $sortBy == 'revenue' && $sortOrder == 'desc' ? 'text-emerald-600 dark:text-emerald-400' : '' }}">
                                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                                        </a>
                                    </div>
                </div>
                            </th>
                            <th class="text-center p-4 font-semibold text-stone-700 dark:text-stone-300">
                                <div class="flex items-center justify-center gap-2">
                                    Profit Margin
                                    <div class="flex flex-col">
                                        <a href="{{ admin_route('analytics.products', array_merge(request()->all(), ['sort_by' => 'profit_margin', 'sort_order' => $sortBy == 'profit_margin' && $sortOrder == 'asc' ? 'desc' : 'asc'])) }}#product-performance-report" onclick="sessionStorage.setItem('scrollToProductReport', 'true');" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 {{ $sortBy == 'profit_margin' && $sortOrder == 'asc' ? 'text-emerald-600 dark:text-emerald-400' : '' }}">
                                            <i data-lucide="chevron-up" class="w-3 h-3"></i>
                                        </a>
                                        <a href="{{ admin_route('analytics.products', array_merge(request()->all(), ['sort_by' => 'profit_margin', 'sort_order' => $sortBy == 'profit_margin' && $sortOrder == 'desc' ? 'asc' : 'desc'])) }}#product-performance-report" onclick="sessionStorage.setItem('scrollToProductReport', 'true');" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 {{ $sortBy == 'profit_margin' && $sortOrder == 'desc' ? 'text-emerald-600 dark:text-emerald-400' : '' }}">
                                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                                        </a>
                </div>
                </div>
                            </th>
                            <th class="text-center p-4 font-semibold text-stone-700 dark:text-stone-300">
                                <div class="flex items-center justify-center gap-2">
                                    Views
                                    <div class="flex flex-col">
                                        <a href="{{ admin_route('analytics.products', array_merge(request()->all(), ['sort_by' => 'views', 'sort_order' => $sortBy == 'views' && $sortOrder == 'asc' ? 'desc' : 'asc'])) }}#product-performance-report" onclick="sessionStorage.setItem('scrollToProductReport', 'true');" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 {{ $sortBy == 'views' && $sortOrder == 'asc' ? 'text-emerald-600 dark:text-emerald-400' : '' }}">
                                            <i data-lucide="chevron-up" class="w-3 h-3"></i>
                                        </a>
                                        <a href="{{ admin_route('analytics.products', array_merge(request()->all(), ['sort_by' => 'views', 'sort_order' => $sortBy == 'views' && $sortOrder == 'desc' ? 'asc' : 'desc'])) }}#product-performance-report" onclick="sessionStorage.setItem('scrollToProductReport', 'true');" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 {{ $sortBy == 'views' && $sortOrder == 'desc' ? 'text-emerald-600 dark:text-emerald-400' : '' }}">
                                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                                        </a>
                </div>
                </div>
                            </th>
                            <th class="text-center p-4 font-semibold text-stone-700 dark:text-stone-300">
                                <div class="flex items-center justify-center gap-2">
                                    Conversion
                                    <div class="flex flex-col">
                                        <a href="{{ admin_route('analytics.products', array_merge(request()->all(), ['sort_by' => 'conversion_rate', 'sort_order' => $sortBy == 'conversion_rate' && $sortOrder == 'asc' ? 'desc' : 'asc'])) }}#product-performance-report" onclick="sessionStorage.setItem('scrollToProductReport', 'true');" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 {{ $sortBy == 'conversion_rate' && $sortOrder == 'asc' ? 'text-emerald-600 dark:text-emerald-400' : '' }}">
                                            <i data-lucide="chevron-up" class="w-3 h-3"></i>
                                        </a>
                                        <a href="{{ admin_route('analytics.products', array_merge(request()->all(), ['sort_by' => 'conversion_rate', 'sort_order' => $sortBy == 'conversion_rate' && $sortOrder == 'desc' ? 'asc' : 'desc'])) }}#product-performance-report" onclick="sessionStorage.setItem('scrollToProductReport', 'true');" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 {{ $sortBy == 'conversion_rate' && $sortOrder == 'desc' ? 'text-emerald-600 dark:text-emerald-400' : '' }}">
                                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                                        </a>
                </div>
            </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productPerformanceData as $product)
                        <tr class="border-b border-stone-100 dark:border-strokedark/50 transition-colors duration-200 hover:bg-stone-50/50 dark:hover:bg-stone-800/20">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-stone-100 flex items-center justify-center dark:bg-stone-800">
                            <i data-lucide="package" class="w-4 h-4 text-stone-400"></i>
                    </div>
                    <div>
                                        <p class="font-semibold text-stone-900 dark:text-white">{{ $product['name'] }}</p>
                                        <p class="text-xs text-stone-500 dark:text-gray-400">{{ $product['category'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center text-stone-900 dark:text-white">{{ $product['sku'] }}</td>
                            <td class="p-4 text-center font-bold text-stone-900 dark:text-white">{{ number_format($product['units_sold']) }}</td>
                            <td class="p-4 text-center font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($product['revenue'], 2) }}</td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $product['profit_margin'] >= 30 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : ($product['profit_margin'] >= 20 ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                                    {{ number_format($product['profit_margin'], 1) }}%
                                </span>
                            </td>
                            <td class="p-4 text-center text-stone-600 dark:text-gray-400">{{ number_format($product['views']) }}</td>
                            <td class="p-4 text-center text-stone-600 dark:text-gray-400">{{ number_format($product['conversion_rate'], 2) }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center">
                                <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4 dark:bg-stone-800">
                                    <i data-lucide="package" class="w-6 h-6 text-stone-400"></i>
                                </div>
                                <p class="text-stone-500 dark:text-gray-400">No product data available</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
                    </div>
                </div>

<!-- Rankings Section -->
<div class="grid grid-cols-12 gap-6 mb-8">
    <!-- Best Sellers -->
    <div class="col-span-12 md:col-span-4">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Best Sellers</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Top performing products</p>
                </div>
                </div>

            <div class="space-y-3">
                @forelse($bestSellers->take(5) as $index => $product)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-stone-200/50 transition-all duration-200 hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-strokedark/50 dark:hover:border-emerald-800/50 dark:hover:bg-emerald-900/10">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 font-bold text-sm dark:bg-emerald-900/30 dark:text-emerald-400">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-stone-900 dark:text-white text-sm">{{ Str::limit($product->name, 30) }}</p>
                        <p class="text-xs text-stone-500 dark:text-gray-400">{{ $product->total_sold ?? 0 }} sold</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-stone-900 dark:text-white text-sm">₱{{ number_format($product->total_revenue ?? 0, 2) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-stone-500 dark:text-gray-400 text-center py-4">No data available</p>
                @endforelse
            </div>
        </div>
                </div>

    <!-- Worst Performers -->
    <div class="col-span-12 md:col-span-4">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Worst Performers</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Products needing attention</p>
                </div>
                </div>

            <div class="space-y-3">
                @forelse($worstPerformers->take(5) as $index => $product)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-stone-200/50 transition-all duration-200 hover:border-red-200 hover:bg-red-50/50 dark:border-strokedark/50 dark:hover:border-red-800/50 dark:hover:bg-red-900/10">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 text-red-600 font-bold text-sm dark:bg-red-900/30 dark:text-red-400">
                        {{ $index + 1 }}
                        </div>
                    <div class="flex-1">
                        <p class="font-semibold text-stone-900 dark:text-white text-sm">{{ Str::limit($product->name, 30) }}</p>
                        <p class="text-xs text-stone-500 dark:text-gray-400">{{ $product->total_sold ?? 0 }} sold</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-stone-900 dark:text-white text-sm">₱{{ number_format($product->total_revenue ?? 0, 2) }}</p>
                </div>
            </div>
            @empty
                <p class="text-sm text-stone-500 dark:text-gray-400 text-center py-4">No data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Most Viewed -->
    <div class="col-span-12 md:col-span-4">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Most Viewed</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Highest interest products</p>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($mostViewed->take(5) as $index => $product)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-stone-200/50 transition-all duration-200 hover:border-blue-200 hover:bg-blue-50/50 dark:border-strokedark/50 dark:hover:border-blue-800/50 dark:hover:bg-blue-900/10">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-600 font-bold text-sm dark:bg-blue-900/30 dark:text-blue-400">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-stone-900 dark:text-white text-sm">{{ Str::limit($product->name, 30) }}</p>
                        <p class="text-xs text-stone-500 dark:text-gray-400">{{ $product->view_count ?? 0 }} views</p>
                </div>
            </div>
                @empty
                <p class="text-sm text-stone-500 dark:text-gray-400 text-center py-4">No data available</p>
            @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Stock Turnover Rate -->
<div class="mb-8">
    <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-stone-900 dark:text-white">Stock Turnover Rate</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Inventory efficiency metrics</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-xl bg-blue-50 dark:bg-blue-900/20">
                <div class="mb-2 flex items-center gap-2">
                    <i data-lucide="dollar-sign" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Cost of Goods Sold</p>
                </div>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">₱{{ number_format($stockTurnoverRate['cogs'] ?? 0, 2) }}</p>
            </div>
            
            <div class="p-6 rounded-xl bg-emerald-50 dark:bg-emerald-900/20">
                <div class="mb-2 flex items-center gap-2">
                    <i data-lucide="package" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Average Inventory</p>
                </div>
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($stockTurnoverRate['average_inventory'] ?? 0, 2) }}</p>
            </div>
            
            <div class="p-6 rounded-xl bg-amber-50 dark:bg-amber-900/20">
                <div class="mb-2 flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Turnover Rate</p>
                </div>
                <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($stockTurnoverRate['turnover_rate'] ?? 0, 2) }}x</p>
                <p class="text-xs text-stone-500 dark:text-gray-400 mt-1">Times inventory turned over</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Prepare period data - get top products for each period
const productPeriodData = {
    day: {
        labels: @json($topProductsDaily->pluck('name')->toArray() ?? []),
        revenue: @json($topProductsDaily->pluck('total_revenue')->map(function($val) { return (float) ($val ?? 0); })->toArray())
    },
    week: {
        labels: @json($topProductsWeekly->pluck('name')->toArray() ?? []),
        revenue: @json($topProductsWeekly->pluck('total_revenue')->map(function($val) { return (float) ($val ?? 0); })->toArray())
    },
    month: {
        labels: @json($topProductsMonthly->pluck('name')->toArray() ?? []),
        revenue: @json($topProductsMonthly->pluck('total_revenue')->map(function($val) { return (float) ($val ?? 0); })->toArray())
    }
};

// Set default to week view
let currentProductPeriod = '{{ $currentPeriod ?? 'week' }}';
let periodOffset = {{ $periodOffset ?? 0 }};
const maxPeriodOffset = 0; // Cannot go forward past today

// Product Performance Chart
const productPerformanceOptions = {
    series: [{
        name: 'Revenue',
        data: productPeriodData[currentProductPeriod].revenue
    }],
    chart: {
        type: 'bar',
        height: '100%',
        fontFamily: 'Inter, sans-serif',
        toolbar: {
            show: false
        }
    },
    colors: ['#3B82F6'],
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
            gradientToColors: ['#60A5FA'],
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
            colors: ['#3B82F6']
        },
        formatter: function (val) {
            return '₱' + parseFloat(val).toLocaleString();
        }
    },
    xaxis: {
        categories: productPeriodData[currentProductPeriod].labels,
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

const productPerformanceChart = new ApexCharts(document.querySelector('#productPerformanceChart'), productPerformanceOptions);

// Function to resize chart to fill container
function resizeProductPerformanceChart() {
    const container = document.querySelector('#productPerformanceChart').parentElement;
    const height = container.offsetHeight;
    if (height > 0) {
        productPerformanceChart.updateOptions({ chart: { height: height } });
    }
}

productPerformanceChart.render();
setTimeout(resizeProductPerformanceChart, 100);
setTimeout(resizeProductPerformanceChart, 500);
window.addEventListener('resize', resizeProductPerformanceChart);

// Update navigation buttons state
function updatePeriodNavigation() {
    const prevBtn = document.getElementById('product-period-prev');
    const nextBtn = document.getElementById('product-period-next');
    
    // Can always go back
    prevBtn.disabled = false;
    
    // Cannot go forward past today (offset must be >= 0)
    nextBtn.disabled = periodOffset <= maxPeriodOffset;
}

// Navigate to previous period
document.getElementById('product-period-prev').addEventListener('click', function() {
    periodOffset += 1;
    updatePeriodNavigation();
    loadPeriodData();
});

// Navigate to next period
document.getElementById('product-period-next').addEventListener('click', function() {
    if (periodOffset > maxPeriodOffset) {
        periodOffset -= 1;
        updatePeriodNavigation();
        loadPeriodData();
    }
});

// Load period data from server
function loadPeriodData() {
    // Get current date filter values
    const urlParams = new URLSearchParams(window.location.search);
    const startDate = urlParams.get('start_date') || '';
    const endDate = urlParams.get('end_date') || '';
    
    // Build URL with period parameters
    const params = new URLSearchParams({
        period_offset: periodOffset,
        current_period: currentProductPeriod,
        start_date: startDate,
        end_date: endDate,
        category: urlParams.get('category') || '',
        sort_by: urlParams.get('sort_by') || '',
        sort_order: urlParams.get('sort_order') || ''
    });
    
    // Reload page with new period offset
    window.location.href = '{{ admin_route('analytics.products') }}?' + params.toString();
}

// Period toggle handler for Product Performance chart
document.querySelectorAll('.product-period-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const period = this.id.replace('product-period-', '');
        currentProductPeriod = period;
        
        // Reset offset to 0 when switching period types and reload to get fresh data
        periodOffset = 0;
        loadPeriodData();
    });
});

// Initialize navigation state
updatePeriodNavigation();

// Category Breakdown Chart
const categoryBreakdownOptions = {
    series: @json($categorySales->pluck('total_revenue')->toArray()),
    chart: {
        type: 'donut',
        height: '100%',
        fontFamily: 'Inter, sans-serif',
    },
    colors: @json($categorySales->pluck('color')->map(function($color) { return $color ?? '#3B82F6'; })->toArray()),
    labels: @json($categorySales->pluck('name')->toArray()),
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

const categoryBreakdownChart = new ApexCharts(document.querySelector('#categoryBreakdownChart'), categoryBreakdownOptions);

// Function to resize category chart to fill container
function resizeCategoryBreakdownChart() {
    const container = document.querySelector('#categoryBreakdownChart').parentElement;
    const height = container.offsetHeight;
    if (height > 0) {
        const size = Math.min(height * 0.8, 300);
        categoryBreakdownChart.updateOptions({ chart: { height: size } });
    }
}

categoryBreakdownChart.render();
setTimeout(resizeCategoryBreakdownChart, 100);
setTimeout(resizeCategoryBreakdownChart, 500);
window.addEventListener('resize', resizeCategoryBreakdownChart);

// Scroll to product performance report when product performance filters/sort are changed (NOT date filter)
(function() {
    // Only scroll if the flag was set by Product Performance Report actions (category filter or sort)
    // The date filter does NOT set this flag, so scrolling won't happen when only the date changes
    const shouldScroll = sessionStorage.getItem('scrollToProductReport') === 'true';
    
    if (shouldScroll) {
        // Wait for page to fully load and render
        setTimeout(function() {
            const element = document.getElementById('product-performance-report');
            if (element) {
                const offset = 100; // Offset from top
                const elementPosition = element.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - offset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Clear the flag
                sessionStorage.removeItem('scrollToProductReport');
            }
        }, 300);
    }
})();
</script>
@endpush
