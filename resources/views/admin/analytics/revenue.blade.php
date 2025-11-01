@extends('admin.layouts.app')

@section('title', 'Revenue Analytics')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Revenue Analytics</h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Track revenue growth and financial performance
        </p>
    </div>

    <div class="flex items-center gap-3">
        <!-- Date Range Filter -->
        <form method="GET" action="{{ admin_route('analytics.revenue') }}" class="flex items-center gap-2">
            <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
            <span class="text-sm text-stone-600 dark:text-gray-400">to</span>
            <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
            <button type="submit" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm font-medium text-stone-900 shadow-sm hover:bg-stone-50 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white dark:hover:bg-stone-700">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </button>
            @if(request('start_date') || request('end_date'))
            <a href="{{ admin_route('analytics.revenue') }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm font-medium text-stone-900 shadow-sm hover:bg-stone-50 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white dark:hover:bg-stone-700" title="Clear date filter">
                <i data-lucide="x" class="w-4 h-4"></i>
            </a>
            @endif
        </form>
        
        <a href="{{ admin_route('analytics.export', ['type' => 'revenue', 'start_date' => request('start_date', $startDate->format('Y-m-d')), 'end_date' => request('end_date', $endDate->format('Y-m-d'))]) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="download" class="w-4 h-4"></i>
            Export
        </a>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Revenue Overview Cards -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4 mb-8">
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

    <!-- Revenue Growth -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 dark:from-blue-900/20 dark:to-blue-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                    <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        +{{ number_format($revenueGrowth, 1) }}%
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Growth Rate</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['growth_rate'] ?? 0;
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

    <!-- Average Order Value -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100/50 p-6 shadow-lg shadow-amber-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/20 dark:from-amber-900/20 dark:to-amber-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500 shadow-lg">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
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

    <!-- Revenue per Customer -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100/50 p-6 shadow-lg shadow-purple-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 dark:from-purple-900/20 dark:to-purple-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 shadow-lg">
                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        ₱{{ number_format($revenuePerCustomer, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Revenue per Customer</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['revenue_per_customer'] ?? 0;
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
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-8 items-stretch">
    <!-- Revenue Trend Chart -->
    <div class="lg:col-span-2">
        <div class="flex flex-col rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full" style="min-height: 550px;">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Revenue Trend</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Daily revenue performance over time</p>
                </div>
                <div class="flex items-center gap-2">
                    <button id="revenue-period-prev" class="revenue-period-nav inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Previous period">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <div class="inline-flex items-center rounded-xl bg-stone-100 p-1 dark:bg-stone-800">
                        <button id="revenue-period-day" class="revenue-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($revenueCurrentPeriod ?? 'month') == 'day' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Day
                        </button>
                        <button id="revenue-period-week" class="revenue-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($revenueCurrentPeriod ?? 'month') == 'week' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Week
                        </button>
                        <button id="revenue-period-month" class="revenue-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($revenueCurrentPeriod ?? 'month') == 'month' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Month
                        </button>
                    </div>
                    <button id="revenue-period-next" class="revenue-period-nav inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Next period" disabled>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                    @if(request('revenue_period_offset') || request('revenue_current_period'))
                    <button id="revenue-period-clear" class="revenue-period-clear inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Clear period filter">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                    @endif
                </div>
            </div>

            <div class="flex-1 flex flex-col">
                <div id="revenueTrendChart" class="w-full flex-1"></div>
            </div>
        </div>
    </div>

    <!-- Revenue Sources -->
    <div>
        <div class="flex flex-col rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full" style="min-height: 550px;">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Revenue Sources</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Revenue breakdown by category</p>
                </div>
            </div>

            <div class="mb-6">
                <div id="revenueSourcesChart" class="mx-auto flex justify-center"></div>
            </div>

            <div class="space-y-3">
                @foreach($revenueByCategory as $category)
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

<!-- Gross vs Net Revenue -->
<div class="mb-8">
    <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-stone-900 dark:text-white">Gross vs Net Revenue</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Revenue breakdown after discounts and refunds</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-6 rounded-xl bg-emerald-50 dark:bg-emerald-900/20">
                <div class="mb-2 flex items-center gap-2">
                    <i data-lucide="dollar-sign" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Gross Revenue</p>
                </div>
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($grossNetRevenue['gross_revenue'] ?? 0, 2) }}</p>
            </div>
            
            <div class="p-6 rounded-xl bg-red-50 dark:bg-red-900/20">
                <div class="mb-2 flex items-center gap-2">
                    <i data-lucide="tag" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Discounts</p>
                </div>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">-₱{{ number_format($grossNetRevenue['discounts'] ?? 0, 2) }}</p>
            </div>
            
            <div class="p-6 rounded-xl bg-orange-50 dark:bg-orange-900/20">
                <div class="mb-2 flex items-center gap-2">
                    <i data-lucide="refresh-ccw" class="w-5 h-5 text-orange-600 dark:text-orange-400"></i>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Refunds</p>
                </div>
                <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">-₱{{ number_format($grossNetRevenue['refunds'] ?? 0, 2) }}</p>
            </div>
            
            <div class="p-6 rounded-xl bg-blue-50 dark:bg-blue-900/20">
                <div class="mb-2 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Net Revenue</p>
                </div>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">₱{{ number_format($grossNetRevenue['net_revenue'] ?? 0, 2) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Revenue by Channel & Payment Methods -->
<div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
    <!-- Revenue by Channel -->
    <div>
        <div class="h-full rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Revenue by Channel</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Revenue breakdown by sales channel</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-stone-600 dark:text-gray-400">Online</span>
                        <span class="text-sm font-bold text-stone-900 dark:text-white">{{ number_format($revenueByChannel['online_percentage'] ?? 0, 1) }}%</span>
                    </div>
                    <p class="text-xl font-bold text-blue-600 dark:text-blue-400">₱{{ number_format($revenueByChannel['online'] ?? 0, 2) }}</p>
                </div>
                
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-stone-600 dark:text-gray-400">Phone</span>
                        <span class="text-sm font-bold text-stone-900 dark:text-white">{{ number_format($revenueByChannel['phone_percentage'] ?? 0, 1) }}%</span>
                    </div>
                    <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($revenueByChannel['phone'] ?? 0, 2) }}</p>
                </div>
                
                <div class="p-4 rounded-xl bg-purple-50 dark:bg-purple-900/20">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-stone-600 dark:text-gray-400">Wholesale</span>
                        <span class="text-sm font-bold text-stone-900 dark:text-white">{{ number_format($revenueByChannel['wholesale_percentage'] ?? 0, 1) }}%</span>
                    </div>
                    <p class="text-xl font-bold text-purple-600 dark:text-purple-400">₱{{ number_format($revenueByChannel['wholesale'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Distribution -->
    <div>
        <div class="h-full rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Payment Method Distribution</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Revenue by payment type</p>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6 items-center lg:items-start">
                <!-- Chart on Left -->
                <div class="flex-shrink-0">
                    <div id="paymentMethodChart" class="flex justify-center"></div>
                </div>
                
                <!-- Legend on Right -->
                <div class="flex-1 space-y-3 w-full lg:w-auto">
                    @php
                        // Extended color palette to ensure all payment methods get unique colors
                        $paymentColors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'];
                        
                        // Assign colors based on payment method name to ensure consistency
                        $paymentMethodColorMap = [
                            'bank_transfer' => '#3B82F6',      // Blue
                            'cod' => '#10B981',                // Green
                            'cash_on_delivery' => '#10B981',   // Green (alternative name)
                            'debit_card' => '#F59E0B',         // Amber
                            'direct_debit' => '#EF4444',        // Red
                            'gcash' => '#8B5CF6',               // Purple
                            'qr_code' => '#EC4899',             // Pink
                            'retail_outlet' => '#06B6D4',       // Cyan (different from bank_transfer blue)
                        ];
                    @endphp
                    @foreach($revenueByPaymentMethod as $index => $method)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @php
                                $methodKey = strtolower($method->payment_method ?? 'unknown');
                                $color = $paymentMethodColorMap[$methodKey] ?? $paymentColors[$index % count($paymentColors)];
                            @endphp
                            <div class="h-3 w-3 rounded-full" style="background-color: {{ $color }}"></div>
                            <span class="text-sm font-medium text-stone-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $method->payment_method ?? 'Unknown')) }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-stone-900 dark:text-white">₱{{ number_format($method->revenue ?? 0, 2) }}</span>
                            <p class="text-xs text-stone-500 dark:text-gray-400">
                                @if($totalRevenue > 0)
                                    {{ number_format(($method->revenue / $totalRevenue) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tax Collected & Profitability -->
<div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-3">
    <!-- Tax Collected -->
    <div class="lg:col-span-1">
        <div class="h-full flex flex-col rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Tax Collected</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Total tax revenue for the period</p>
                </div>
            </div>

            <div class="flex-1 flex flex-col justify-center p-6 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-blue-500 mx-auto mb-4">
                    <i data-lucide="receipt" class="w-8 h-8 text-white"></i>
                </div>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">₱{{ number_format($taxCollected ?? 0, 2) }}</p>
                <p class="text-sm text-stone-600 dark:text-gray-400">
                    @if($totalRevenue > 0)
                        {{ number_format(($taxCollected / $totalRevenue) * 100, 2) }}% of total revenue
                    @else
                        0% of total revenue
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Profitability -->
    <div class="lg:col-span-2">
        <div class="h-full rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Profitability Analysis</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Revenue, costs, and profit margins</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-stone-200/50 bg-white dark:bg-stone-800/50 p-5 shadow-md transition-all duration-200 hover:shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Revenue</span>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30">
                            <i data-lucide="dollar-sign" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($profitability['total_revenue'] ?? 0, 2) }}</p>
                </div>
                
                <div class="rounded-xl border border-stone-200/50 bg-white dark:bg-stone-800/50 p-5 shadow-md transition-all duration-200 hover:shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Cost</span>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/30">
                            <i data-lucide="trending-down" class="w-4 h-4 text-red-600 dark:text-red-400"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">₱{{ number_format($profitability['total_cost'] ?? 0, 2) }}</p>
                </div>
                
                <div class="rounded-xl border border-stone-200/50 bg-white dark:bg-stone-800/50 p-5 shadow-md transition-all duration-200 hover:shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-stone-600 dark:text-gray-400">Net Profit</span>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                            <i data-lucide="trending-up" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">₱{{ number_format($profitability['total_profit'] ?? 0, 2) }}</p>
                </div>
                
                <div class="rounded-xl border border-stone-200/50 bg-white dark:bg-stone-800/50 p-5 shadow-md transition-all duration-200 hover:shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-stone-600 dark:text-gray-400">Profit Margin</span>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ ($profitability['profit_margin'] ?? 0) >= 20 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ number_format($profitability['profit_margin'] ?? 0, 1) }}%
                        </span>
                    </div>
                    <p class="text-lg font-bold text-amber-600 dark:text-amber-400 mb-1">{{ number_format($profitability['profit_margin'] ?? 0, 1) }}%</p>
                    <p class="text-xs text-stone-500 dark:text-gray-400">Percentage of revenue retained as profit</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Comparison -->
<div class="mb-8">
    <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-stone-900 dark:text-white">Revenue Comparison</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Compare current period with previous period</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Current Period -->
            <div class="text-center p-6 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 transition-all duration-200 hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 mx-auto mb-4">
                    <i data-lucide="calendar" class="w-6 h-6 text-white"></i>
                </div>
                <h4 class="text-lg font-bold text-stone-900 dark:text-white mb-2">Current Period</h4>
                <p class="text-2xl font-bold text-emerald-600 mb-1">₱{{ number_format($currentPeriodRevenue, 2) }}</p>
                <p class="text-sm text-stone-600 dark:text-gray-400">{{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}</p>
            </div>

            <!-- Previous Period -->
            <div class="text-center p-6 rounded-xl bg-stone-50 dark:bg-stone-800/50 transition-all duration-200 hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-stone-500 mx-auto mb-4">
                    <i data-lucide="calendar-days" class="w-6 h-6 text-white"></i>
                </div>
                <h4 class="text-lg font-bold text-stone-900 dark:text-white mb-2">Previous Period</h4>
                <p class="text-2xl font-bold text-stone-600 dark:text-gray-400 mb-1">₱{{ number_format($previousPeriodRevenue, 2) }}</p>
                <p class="text-sm text-stone-600 dark:text-gray-400">{{ $previousStartDate->format('M d') }} - {{ $previousEndDate->format('M d, Y') }}</p>
            </div>

            <!-- Growth -->
            <div class="text-center p-6 rounded-xl {{ $revenueGrowth >= 0 ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }} transition-all duration-200 hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $revenueGrowth >= 0 ? 'bg-green-500' : 'bg-red-500' }} mx-auto mb-4">
                    <i data-lucide="{{ $revenueGrowth >= 0 ? 'trending-up' : 'trending-down' }}" class="w-6 h-6 text-white"></i>
                </div>
                <h4 class="text-lg font-bold text-stone-900 dark:text-white mb-2">Growth</h4>
                <p class="text-2xl font-bold {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mb-1">
                    {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}%
                </p>
                <p class="text-sm text-stone-600 dark:text-gray-400">
                    {{ $revenueGrowth >= 0 ? 'Increase' : 'Decrease' }} from previous period
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Prepare period data for Revenue Trend chart
const revenuePeriodData = {
    day: {
        labels: @json($dailyLabels ?? []),
        revenue: @json($dailyRevenue ?? [])
    },
    week: {
        labels: @json($weeklyLabels ?? []),
        revenue: @json($weeklyRevenue ?? [])
    },
    month: {
        labels: @json($monthlyLabels ?? []),
        revenue: @json($monthlyRevenue ?? [])
    }
};

// Set default to month view
let currentRevenuePeriod = '{{ $revenueCurrentPeriod ?? 'month' }}';
let revenuePeriodOffset = {{ $revenuePeriodOffset ?? 0 }};
const maxRevenuePeriodOffset = 0; // Cannot go forward past today

// Revenue Trend Chart - Area Chart
const revenueTrendOptions = {
    series: [{
        name: 'Revenue',
        data: revenuePeriodData[currentRevenuePeriod]?.revenue || []
    }],
    chart: {
        type: 'area',
        height: '100%',
        fontFamily: 'Inter, sans-serif',
        toolbar: {
            show: false
        }
    },
    colors: ['#10B981'],
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
        categories: revenuePeriodData[currentRevenuePeriod]?.labels || [],
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

const revenueTrendChart = new ApexCharts(document.querySelector('#revenueTrendChart'), revenueTrendOptions);

// Function to resize chart to fill container
function resizeRevenueTrendChart() {
    const container = document.querySelector('#revenueTrendChart').parentElement;
    const height = container.offsetHeight;
    if (height > 0) {
        revenueTrendChart.updateOptions({ chart: { height: height } });
    }
}

revenueTrendChart.render();
setTimeout(resizeRevenueTrendChart, 100);
setTimeout(resizeRevenueTrendChart, 500);
window.addEventListener('resize', resizeRevenueTrendChart);

// Update navigation buttons state
function updateRevenuePeriodNavigation() {
    const prevBtn = document.getElementById('revenue-period-prev');
    const nextBtn = document.getElementById('revenue-period-next');
    
    // Can always go back
    if (prevBtn) prevBtn.disabled = false;
    
    // Cannot go forward past today (offset must be >= 0)
    if (nextBtn) nextBtn.disabled = revenuePeriodOffset <= maxRevenuePeriodOffset;
}

// Navigate to previous period
// Logic: 
// - Month backward: Show past year (12 months back, so offset += 12)
// - Week backward: Show past month's 4 previous weeks (4 weeks back, so offset += 4)
// - Day backward: Show last week's per day (7 days back, so offset += 7)
const revenuePrevBtn = document.getElementById('revenue-period-prev');
if (revenuePrevBtn) {
    revenuePrevBtn.addEventListener('click', function() {
        let offsetIncrement = 1; // Default increment
        
        if (currentRevenuePeriod === 'month') {
            offsetIncrement = 12; // Go back 12 months (a full year)
        } else if (currentRevenuePeriod === 'week') {
            offsetIncrement = 4; // Go back 4 weeks (a full month of weeks)
        } else if (currentRevenuePeriod === 'day') {
            offsetIncrement = 7; // Go back 7 days (a full week)
        }
        
        revenuePeriodOffset += offsetIncrement;
        updateRevenuePeriodNavigation();
        loadRevenuePeriodData();
    });
}

// Navigate to next period
// Logic: Reverse of backward navigation
const revenueNextBtn = document.getElementById('revenue-period-next');
if (revenueNextBtn) {
    revenueNextBtn.addEventListener('click', function() {
        let offsetDecrement = 1; // Default decrement
        
        if (currentRevenuePeriod === 'month') {
            offsetDecrement = 12; // Go forward 12 months (a full year)
        } else if (currentRevenuePeriod === 'week') {
            offsetDecrement = 4; // Go forward 4 weeks (a full month of weeks)
        } else if (currentRevenuePeriod === 'day') {
            offsetDecrement = 7; // Go forward 7 days (a full week)
        }
        
        if (revenuePeriodOffset >= offsetDecrement) {
            revenuePeriodOffset -= offsetDecrement;
            updateRevenuePeriodNavigation();
            loadRevenuePeriodData();
        }
    });
}

// Load period data from server
function loadRevenuePeriodData() {
    // Get current date filter values
    const urlParams = new URLSearchParams(window.location.search);
    const startDate = urlParams.get('start_date') || '';
    const endDate = urlParams.get('end_date') || '';
    
    // Build URL with period parameters
    const params = new URLSearchParams({
        revenue_period_offset: revenuePeriodOffset,
        revenue_current_period: currentRevenuePeriod,
        start_date: startDate,
        end_date: endDate
    });
    
    // Reload page with new period offset
    window.location.href = '{{ admin_route('analytics.revenue') }}?' + params.toString();
}

// Period toggle handler for Revenue Trend chart
// Wait for DOM to be ready
(function() {
    function initRevenuePeriodToggles() {
        const buttons = document.querySelectorAll('.revenue-period-toggle');
        if (buttons.length === 0) {
            // Buttons not ready yet, try again
            setTimeout(initRevenuePeriodToggles, 100);
            return;
        }
        
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const period = this.id.replace('revenue-period-', '');
                currentRevenuePeriod = period;
                
                // Reset offset to 0 when switching period types and reload to get fresh data
                revenuePeriodOffset = 0;
                loadRevenuePeriodData();
            });
        });
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRevenuePeriodToggles);
    } else {
        initRevenuePeriodToggles();
    }
})();

// Initialize navigation state
updateRevenuePeriodNavigation();

// Clear period filter button handler
const revenueClearBtn = document.getElementById('revenue-period-clear');
if (revenueClearBtn) {
    revenueClearBtn.addEventListener('click', function(e) {
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
        const url = '{{ admin_route('analytics.revenue') }}' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    });
}

// Revenue Sources Chart
const revenueSourcesOptions = {
    series: @json($revenueByCategory->pluck('total_revenue')->toArray()),
    chart: {
        type: 'donut',
        width: 300,
        height: 300,
    },
    colors: @json($revenueByCategory->pluck('color')->map(function($color) { return $color ?? '#3B82F6'; })->toArray()),
    labels: @json($revenueByCategory->pluck('name')->toArray()),
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

const revenueSourcesChart = new ApexCharts(document.querySelector('#revenueSourcesChart'), revenueSourcesOptions);
revenueSourcesChart.render();

// Payment Method Distribution Chart
@php
    $paymentMethodColorMap = [
        'bank_transfer' => '#3B82F6',
        'cod' => '#10B981',
        'cash_on_delivery' => '#10B981',
        'debit_card' => '#F59E0B',
        'direct_debit' => '#EF4444',
        'gcash' => '#8B5CF6',
        'qr_code' => '#EC4899',
        'retail_outlet' => '#06B6D4',
    ];
    $paymentMethodColors = $revenueByPaymentMethod->pluck('payment_method')->map(function($method) use ($paymentMethodColorMap) {
        $methodKey = strtolower($method ?? 'unknown');
        return $paymentMethodColorMap[$methodKey] ?? '#6366F1';
    })->toArray();
@endphp
const paymentMethodOptions = {
    series: @json($revenueByPaymentMethod->pluck('revenue')->map(function($val) { return (float) ($val ?? 0); })->toArray()),
    chart: {
        type: 'donut',
        width: 300,
        height: 300,
    },
    colors: @json($paymentMethodColors),
    labels: @json($revenueByPaymentMethod->pluck('payment_method')->map(function($method) { return ucfirst(str_replace('_', ' ', $method ?? 'Unknown')); })->toArray()),
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
    tooltip: {
        y: {
            formatter: function (val) {
                return '₱' + val.toLocaleString()
            }
        }
    },
};

function ucfirst(str) {
    return str.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
}

const paymentMethodChart = new ApexCharts(document.querySelector('#paymentMethodChart'), paymentMethodOptions);
paymentMethodChart.render();
</script>
@endpush
