@extends('admin.layouts.app')

@section('title', 'Customer Analytics')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Customer Analytics</h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Understand your customer behavior and demographics
        </p>
    </div>

    <div class="flex items-center gap-3">
        <!-- Date Range Filter -->
        <form method="GET" action="{{ admin_route('analytics.customers') }}" class="flex items-center gap-2">
            <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
            <span class="text-sm text-stone-600 dark:text-gray-400">to</span>
            <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
            <button type="submit" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm font-medium text-stone-900 shadow-sm hover:bg-stone-50 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white dark:hover:bg-stone-700">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </button>
        </form>
        
        @if(request('start_date') || request('end_date') || request('acquisition_period_offset') || request('acquisition_current_period'))
        <a href="{{ admin_route('analytics.customers') }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm font-medium text-stone-900 shadow-sm hover:bg-stone-50 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white dark:hover:bg-stone-700" title="Clear all filters">
            <i data-lucide="x-circle" class="w-4 h-4"></i>
            Clear All Filters
        </a>
        @endif
        
        <a href="{{ admin_route('analytics.export', ['type' => 'customers', 'start_date' => request('start_date', $startDate->format('Y-m-d')), 'end_date' => request('end_date', $endDate->format('Y-m-d'))]) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="download" class="w-4 h-4"></i>
            Export
        </a>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Customer Overview Cards -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4 mb-8">
    <!-- Total Customers -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 dark:from-blue-900/20 dark:to-blue-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($totalCustomers) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Customers</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['total_customers'] ?? 0;
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

    <!-- New Customers -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-lg shadow-emerald-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/20 dark:from-emerald-900/20 dark:to-emerald-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg">
                    <i data-lucide="user-plus" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($newCustomersCount) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">New Customers</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['new_customers'] ?? 0;
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

    <!-- Customer Lifetime Value -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100/50 p-6 shadow-lg shadow-amber-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/20 dark:from-amber-900/20 dark:to-amber-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500 shadow-lg">
                    <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        ₱{{ number_format($averageLifetimeValue, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Avg Lifetime Value</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['avg_lifetime_value'] ?? 0;
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

    <!-- Repeat Purchase Rate -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100/50 p-6 shadow-lg shadow-purple-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 dark:from-purple-900/20 dark:to-purple-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 shadow-lg">
                    <i data-lucide="repeat" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($repeatPurchaseRate, 1) }}%
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Repeat Purchase Rate</p>
                </div>
            </div>
            <div class="text-right">
                @php
                    $change = $percentageChanges['repeat_purchase_rate'] ?? 0;
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
    <!-- Customer Acquisition Chart -->
    <div class="col-span-12 xl:col-span-8">
        <div class="flex flex-col rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full" style="min-height: 550px;">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Customer Acquisition</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">New customer registrations over time</p>
                </div>
                <div class="flex items-center gap-2">
                    <button id="acquisition-period-prev" class="acquisition-period-nav inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Previous period">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <div class="inline-flex items-center rounded-xl bg-stone-100 p-1 dark:bg-stone-800">
                        <button id="acquisition-period-day" class="acquisition-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($acquisitionCurrentPeriod ?? 'month') == 'day' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Day
                        </button>
                        <button id="acquisition-period-week" class="acquisition-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($acquisitionCurrentPeriod ?? 'month') == 'week' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Week
                        </button>
                        <button id="acquisition-period-month" class="acquisition-period-toggle rounded-lg px-3 py-1.5 text-xs font-medium {{ ($acquisitionCurrentPeriod ?? 'month') == 'month' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Month
                        </button>
                    </div>
                    <button id="acquisition-period-next" class="acquisition-period-nav inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Next period" disabled>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 flex flex-col">
                <div id="customerAcquisitionChart" class="w-full flex-1"></div>
            </div>
        </div>
    </div>

    <!-- Customer Segments -->
    <div class="col-span-12 xl:col-span-4">
        <div class="flex flex-col rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full" style="min-height: 550px;">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Customer Segments</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Customer distribution by value</p>
                </div>
            </div>

            <div class="mb-6 flex-1 flex items-center justify-center">
                <div id="customerSegmentsChart" class="mx-auto flex justify-center w-full"></div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
                        <span class="text-sm font-medium text-stone-900 dark:text-white">High Value</span>
                    </div>
                    <span class="text-sm font-bold text-stone-900 dark:text-white">{{ number_format($customerSegmentsCalculated['high'] ?? 0, 1) }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                        <span class="text-sm font-medium text-stone-900 dark:text-white">Medium Value</span>
                    </div>
                    <span class="text-sm font-bold text-stone-900 dark:text-white">{{ number_format($customerSegmentsCalculated['medium'] ?? 0, 1) }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full bg-amber-500"></div>
                        <span class="text-sm font-medium text-stone-900 dark:text-white">Low Value</span>
                    </div>
                    <span class="text-sm font-bold text-stone-900 dark:text-white">{{ number_format($customerSegmentsCalculated['low'] ?? 0, 1) }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New vs Returning Customers and Cohort Analysis -->
<div class="grid grid-cols-12 gap-6 mb-8">
    <!-- Left Column: New vs Returning Customers + Cohort Analysis -->
    <div class="col-span-12 xl:col-span-6 flex flex-col gap-6 overflow-hidden" id="left-customer-containers">
        <!-- New vs Returning Customers -->
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">New vs Returning Customers</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Customer acquisition breakdown</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- New Customers Card -->
                <div class="rounded-xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-md dark:border-emerald-800/50 dark:from-emerald-900/20 dark:to-emerald-800/10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg">
                            <i data-lucide="user-plus" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-1 rounded-full">{{ number_format($newVsReturning['new_percentage'] ?? 0, 1) }}%</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-stone-900 dark:text-white mb-1">{{ number_format($newVsReturning['new'] ?? 0) }}</h3>
                        <p class="text-sm font-medium text-stone-600 dark:text-gray-400">New Customers</p>
                    </div>
                </div>

                <!-- Returning Customers Card -->
                <div class="rounded-xl border border-blue-200 bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-md dark:border-blue-800/50 dark:from-blue-900/20 dark:to-blue-800/10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                            <i data-lucide="repeat" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 rounded-full">{{ number_format($newVsReturning['returning_percentage'] ?? 0, 1) }}%</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-stone-900 dark:text-white mb-1">{{ number_format($newVsReturning['returning'] ?? 0) }}</h3>
                        <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Returning Customers</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cohort Analysis -->
        @if(isset($cohortAnalysis) && !empty($cohortAnalysis['cohorts']))
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 flex-1 flex flex-col overflow-hidden">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between flex-shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Cohort Analysis</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Customer retention by acquisition month</p>
                </div>
            </div>

            <div class="flex-1 flex flex-col overflow-hidden" style="min-height: 0;">
                <div id="cohortAnalysisChart" class="flex-1" style="min-height: 0;"></div>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column: Top Customers -->
    <div class="col-span-12 xl:col-span-6">
        <div class="flex flex-col rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80" id="top-customers-container">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between flex-shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Top Customers</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Highest spending customers</p>
                </div>
            </div>

            <div class="flex-1">
                <div class="space-y-3">
                    @forelse($clvData as $index => $customer)
                    <div class="flex items-center gap-3 p-3 rounded-xl border border-stone-200/50 transition-all duration-200 hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-strokedark/50 dark:hover:border-emerald-800/50 dark:hover:bg-emerald-900/10">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 font-bold text-sm dark:bg-emerald-900/30 dark:text-emerald-400">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-stone-900 dark:text-white">{{ $customer['name'] }}</p>
                            <p class="text-xs text-stone-500 dark:text-gray-400">{{ $customer['order_count'] }} orders</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-stone-900 dark:text-white">₱{{ number_format($customer['total_spent'], 2) }}</p>
                            <p class="text-xs text-stone-500 dark:text-gray-400">CLV</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4 dark:bg-stone-800">
                            <i data-lucide="users" class="w-6 h-6 text-stone-400"></i>
                        </div>
                        <p class="text-sm text-stone-500 dark:text-gray-400">No customer data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Geographic Heatmap -->
@if(!empty($geographicData) && isset($geographicData['cities']) && isset($geographicData['regions']))
<div class="mb-8">
    <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-stone-900 dark:text-white">Geographic Distribution</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Orders by location (from shipping addresses)</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Map Container - 2 columns -->
            <div class="lg:col-span-2">
                <div id="geographicMap" style="height: 600px; width: 100%; border-radius: 12px; overflow: hidden; background: #f3f4f6;"></div>
            </div>
            
            <!-- Locations List by Region - 1 column -->
            <div class="lg:col-span-1">
                <div class="rounded-xl border border-stone-200/50 bg-stone-50/50 dark:border-strokedark/50 dark:bg-stone-800/30 p-4 h-full flex flex-col" style="height: 600px;">
                    <!-- Search Bar -->
                    <div class="mb-4 flex-shrink-0">
                        <input 
                            type="text" 
                            id="regionSearch" 
                            placeholder="Search region..." 
                            class="w-full px-3 py-2 text-sm rounded-lg border border-stone-300 bg-white dark:bg-stone-700 dark:border-stone-600 text-stone-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        />
                    </div>
                    
                    <!-- Scrollable Region List -->
                    <div class="flex-1 overflow-y-auto pr-2" id="regionsList" style="overflow-y: auto; max-height: 100%;">
                        @foreach($geographicData['regions'] as $regionData)
                        <div class="mb-4 region-item" data-region="{{ strtolower($regionData['region']) }}">
                            <!-- Region Header -->
                            <div class="flex items-center justify-between p-3 rounded-lg bg-white dark:bg-stone-700 border border-stone-200 dark:border-stone-600 mb-2 cursor-pointer hover:bg-stone-50 dark:hover:bg-stone-600 transition-colors" onclick="toggleRegion('{{ md5($regionData['region']) }}')">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-stone-500 region-arrow transition-transform" id="arrow-{{ md5($regionData['region']) }}"></i>
                                    <span class="font-semibold text-stone-900 dark:text-white">{{ $regionData['region'] }}</span>
                                </div>
                                <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($regionData['count']) }}</span>
                            </div>
                            
                            <!-- Cities in Region (Collapsible) -->
                            <div class="ml-6 region-cities hidden" id="cities-{{ md5($regionData['region']) }}">
                                @foreach($regionData['cities'] as $cityData)
                                <div class="flex items-center justify-between p-2 rounded-lg hover:bg-stone-50 dark:hover:bg-stone-800/50 mb-1 transition-colors">
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-stone-900 dark:text-white">{{ $cityData['city'] }}</span>
                                        @if($cityData['province'])
                                        <p class="text-xs text-stone-500 dark:text-gray-400">{{ $cityData['province'] }}</p>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400 ml-2">{{ number_format($cityData['count']) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Leaflet Heat Plugin -->
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Prepare period data for Customer Acquisition chart
const acquisitionPeriodData = {
    day: {
        labels: @json($dailyLabels ?? []),
        data: @json($dailyNewCustomers ?? [])
    },
    week: {
        labels: @json($weeklyLabels ?? []),
        data: @json($weeklyNewCustomers ?? [])
    },
    month: {
        labels: @json($monthlyLabels ?? []),
        data: @json($monthlyNewCustomers ?? [])
    }
};

// Set default to month view
let currentAcquisitionPeriod = '{{ $acquisitionCurrentPeriod ?? 'month' }}';
let acquisitionPeriodOffset = {{ $acquisitionPeriodOffset ?? 0 }};
const maxAcquisitionPeriodOffset = 0; // Cannot go forward past today

// Customer Acquisition Chart - Bar Chart with Zoom
const customerAcquisitionOptions = {
    series: [{
        name: 'New Customers',
        data: acquisitionPeriodData[currentAcquisitionPeriod].data
    }],
    chart: {
        type: 'bar',
        height: '100%',
        fontFamily: 'Inter, sans-serif',
        toolbar: {
            show: true,
            tools: {
                download: false,
                selection: true,
                zoom: true,
                zoomin: true,
                zoomout: true,
                pan: true,
                reset: true
            }
        },
        zoom: {
            enabled: true,
            type: 'x',
            autoScaleYaxis: true
        }
    },
    colors: ['#3B82F6'],
    plotOptions: {
        bar: {
            borderRadius: 8,
            horizontal: false,
            columnWidth: '60%'
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
    xaxis: {
        categories: acquisitionPeriodData[currentAcquisitionPeriod].labels,
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
                return val.toLocaleString()
            }
        }
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return val.toLocaleString() + ' customers'
            }
        }
    },
    legend: {
        show: false
    },
    grid: {
        borderColor: '#f1f5f9',
        strokeDashArray: 4
    },
    dataLabels: {
        enabled: false
    }
};

const customerAcquisitionChart = new ApexCharts(document.querySelector('#customerAcquisitionChart'), customerAcquisitionOptions);

// Function to resize chart to fill container
function resizeCustomerAcquisitionChart() {
    const container = document.querySelector('#customerAcquisitionChart').parentElement;
    const height = container.offsetHeight;
    if (height > 0) {
        customerAcquisitionChart.updateOptions({ chart: { height: height } });
    }
}

customerAcquisitionChart.render();
setTimeout(resizeCustomerAcquisitionChart, 100);
setTimeout(resizeCustomerAcquisitionChart, 500);
window.addEventListener('resize', resizeCustomerAcquisitionChart);

// Update navigation buttons state
function updateAcquisitionPeriodNavigation() {
    const prevBtn = document.getElementById('acquisition-period-prev');
    const nextBtn = document.getElementById('acquisition-period-next');
    
    // Can always go back
    prevBtn.disabled = false;
    
    // Cannot go forward past today (offset must be >= 0)
    nextBtn.disabled = acquisitionPeriodOffset <= maxAcquisitionPeriodOffset;
}

// Navigate to previous period
// Logic: 
// - Month backward: Show past year (12 months back, so offset += 12)
// - Week backward: Show past month's 4 previous weeks (4 weeks back, so offset += 4)
// - Day backward: Show last week's per day (7 days back, so offset += 7)
document.getElementById('acquisition-period-prev').addEventListener('click', function() {
    let offsetIncrement = 1; // Default increment
    
    if (currentAcquisitionPeriod === 'month') {
        offsetIncrement = 12; // Go back 12 months (a full year)
    } else if (currentAcquisitionPeriod === 'week') {
        offsetIncrement = 4; // Go back 4 weeks (a full month of weeks)
    } else if (currentAcquisitionPeriod === 'day') {
        offsetIncrement = 7; // Go back 7 days (a full week)
    }
    
    acquisitionPeriodOffset += offsetIncrement;
    updateAcquisitionPeriodNavigation();
    loadAcquisitionPeriodData();
});

// Navigate to next period
// Logic: Reverse of backward navigation
document.getElementById('acquisition-period-next').addEventListener('click', function() {
    let offsetDecrement = 1; // Default decrement
    
    if (currentAcquisitionPeriod === 'month') {
        offsetDecrement = 12; // Go forward 12 months (a full year)
    } else if (currentAcquisitionPeriod === 'week') {
        offsetDecrement = 4; // Go forward 4 weeks (a full month of weeks)
    } else if (currentAcquisitionPeriod === 'day') {
        offsetDecrement = 7; // Go forward 7 days (a full week)
    }
    
    if (acquisitionPeriodOffset >= offsetDecrement) {
        acquisitionPeriodOffset -= offsetDecrement;
        updateAcquisitionPeriodNavigation();
        loadAcquisitionPeriodData();
    }
});

// Load period data from server
function loadAcquisitionPeriodData() {
    // Get current date filter values
    const urlParams = new URLSearchParams(window.location.search);
    const startDate = urlParams.get('start_date') || '';
    const endDate = urlParams.get('end_date') || '';
    
    // Build URL with period parameters
    const params = new URLSearchParams({
        acquisition_period_offset: acquisitionPeriodOffset,
        acquisition_current_period: currentAcquisitionPeriod,
        start_date: startDate,
        end_date: endDate
    });
    
    // Reload page with new period offset
    window.location.href = '{{ admin_route('analytics.customers') }}?' + params.toString();
}

// Period toggle handler for Customer Acquisition chart
document.querySelectorAll('.acquisition-period-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const period = this.id.replace('acquisition-period-', '');
        currentAcquisitionPeriod = period;
        
        // Reset offset to 0 when switching period types and reload to get fresh data
        acquisitionPeriodOffset = 0;
        loadAcquisitionPeriodData();
    });
});

// Initialize navigation state
updateAcquisitionPeriodNavigation();

// Match left column containers height to Top Customers container
(function() {
    function matchContainerHeight() {
        const leftColumn = document.getElementById('left-customer-containers');
        const topCustomersContainer = document.getElementById('top-customers-container');
        
        if (leftColumn && topCustomersContainer) {
            // Get the actual rendered height of the Top Customers container
            const topCustomersHeight = topCustomersContainer.offsetHeight;
            
            // Set the left column to match exactly - force it
            if (topCustomersHeight > 0) {
                leftColumn.style.height = topCustomersHeight + 'px';
                leftColumn.style.maxHeight = topCustomersHeight + 'px';
                leftColumn.style.minHeight = topCustomersHeight + 'px';
                
                // Also ensure child containers respect the height
                const cohortContainer = leftColumn.querySelector('#cohortAnalysisChart')?.closest('.rounded-2xl');
                if (cohortContainer) {
                    cohortContainer.style.maxHeight = '100%';
                }
            }
        }
    }
    
    // Wait for everything to render
    function runMultipleTimes() {
        matchContainerHeight();
        setTimeout(matchContainerHeight, 100);
        setTimeout(matchContainerHeight, 300);
        setTimeout(matchContainerHeight, 500);
        setTimeout(matchContainerHeight, 1000);
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runMultipleTimes);
    } else {
        runMultipleTimes();
    }
    
    // Match on resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(matchContainerHeight, 100);
    });
    
    // Use ResizeObserver for more accurate tracking
    if (typeof ResizeObserver !== 'undefined') {
        const topCustomersContainer = document.getElementById('top-customers-container');
        if (topCustomersContainer) {
            const resizeObserver = new ResizeObserver(function(entries) {
                matchContainerHeight();
            });
            resizeObserver.observe(topCustomersContainer);
        }
    }
})();

// Note: New vs Returning Customers now uses cards instead of pie chart

// Cohort Analysis Chart
@if(isset($cohortAnalysis) && !empty($cohortAnalysis['cohorts']))
(function() {
    const cohortData = @json(array_slice($cohortAnalysis['cohorts'] ?? [], -12));
    const labels = cohortData.map(c => c.month);
    const customerCounts = cohortData.map(c => c.customers);
    
    // Calculate returning customers (customers who made repeat purchases)
    const returningCustomers = cohortData.map(c => {
        let returning = 0;
        if (c.repeat_customers && typeof c.repeat_customers === 'object') {
            returning = Object.values(c.repeat_customers).reduce((sum, val) => sum + (typeof val === 'number' ? val : 0), 0);
        }
        return returning;
    });
    
    const cohortOptions = {
        series: [
            {
                name: 'New Customers',
                data: customerCounts
            },
            {
                name: 'Returning Customers',
                data: returningCustomers
            }
        ],
        chart: {
            type: 'area',
            height: '100%',
            fontFamily: 'Inter, sans-serif',
            toolbar: {
                show: false
            },
            zoom: {
                enabled: true,
                type: 'x',
                autoScaleYaxis: true
            },
            parentHeightOffset: 0
        },
        colors: ['#3B82F6', '#10B981'],
        dataLabels: {
            enabled: false
        },
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
            categories: labels,
            labels: {
                style: {
                    colors: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280',
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif'
                }
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280',
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif'
                }
            }
        },
        grid: {
            borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#F3F4F6',
            strokeDashArray: 3,
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '14px',
            fontFamily: 'Inter, sans-serif',
            labels: {
                colors: document.documentElement.classList.contains('dark') ? '#D1D5DB' : '#374151'
            },
            markers: {
                width: 12,
                height: 12,
                radius: 12
            }
        },
        tooltip: {
            theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
            x: {
                format: 'MMM yyyy'
            }
        }
    };
    
    // Initialize chart when DOM is ready
    let cohortChart = null;
    function initCohortChart() {
        const chartElement = document.getElementById('cohortAnalysisChart');
        if (chartElement && typeof ApexCharts !== 'undefined') {
            cohortChart = new ApexCharts(chartElement, cohortOptions);
            cohortChart.render();
            
            // Resize chart when container resizes
            function resizeCohortChart() {
                if (cohortChart && chartElement) {
                    const container = chartElement.parentElement;
                    if (container && container.offsetHeight > 0) {
                        cohortChart.updateOptions({ chart: { height: container.offsetHeight } });
                    }
                }
            }
            
            // Resize on window resize
            window.addEventListener('resize', resizeCohortChart);
            
            // Also resize when height matching happens
            setTimeout(resizeCohortChart, 500);
            setTimeout(resizeCohortChart, 1000);
        } else if (chartElement) {
            // Wait for ApexCharts to load
            setTimeout(initCohortChart, 100);
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCohortChart);
    } else {
        initCohortChart();
    }
})();
@endif

// Customer Segments Chart
const customerSegmentsOptions = {
    series: [{{ number_format($customerSegmentsCalculated['high'] ?? 0, 1) }}, {{ number_format($customerSegmentsCalculated['medium'] ?? 0, 1) }}, {{ number_format($customerSegmentsCalculated['low'] ?? 0, 1) }}],
    chart: {
        type: 'donut',
        height: '100%',
        fontFamily: 'Inter, sans-serif',
    },
    colors: ['#10B981', '#3B82F6', '#F59E0B'],
    labels: ['High Value', 'Medium Value', 'Low Value'],
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

const customerSegmentsChart = new ApexCharts(document.querySelector('#customerSegmentsChart'), customerSegmentsOptions);

// Function to resize chart to fill container
function resizeCustomerSegmentsChart() {
    const container = document.querySelector('#customerSegmentsChart').parentElement;
    const height = container.offsetHeight;
    const width = container.offsetWidth;
    if (height > 0 && width > 0) {
        const size = Math.min(height, width);
        customerSegmentsChart.updateOptions({ 
            chart: { 
                height: size,
                width: size 
            } 
        });
    }
}

customerSegmentsChart.render();
setTimeout(resizeCustomerSegmentsChart, 100);
setTimeout(resizeCustomerSegmentsChart, 500);
window.addEventListener('resize', resizeCustomerSegmentsChart);

// Geographic Heatmap using Leaflet
@if(!empty($geographicData) && isset($geographicData['cities']) && isset($geographicData['regions']))
(function() {
    const geographicData = @json($geographicData);
    const citiesData = geographicData.cities || {};
    
    // Wait for map container to be ready
    function initGeographicMap() {
        const mapElement = document.getElementById('geographicMap');
        if (!mapElement || typeof L === 'undefined') {
            setTimeout(initGeographicMap, 100);
            return;
        }
        
        // Initialize map centered on Philippines
        const map = L.map('geographicMap').setView([12.8797, 121.7740], 6);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Prepare heatmap data (array of [lat, lng, intensity])
        const heatmapData = [];
        const markers = [];
        let maxCount = 0;
        
        // Find max count for normalization
        Object.values(citiesData).forEach(function(cityData) {
            if (cityData.count > maxCount) {
                maxCount = cityData.count;
            }
        });
        
        Object.values(citiesData).forEach(function(cityData) {
            if (cityData.coordinates && cityData.coordinates.length === 2) {
                const [lat, lng] = cityData.coordinates;
                
                // Add to heatmap (intensity based on order count)
                heatmapData.push([lat, lng, cityData.count]);
                
                // Add marker for cities with orders
                if (cityData.count >= 1) {
                    const radius = Math.min(Math.max(cityData.count / 2, 5), 20);
                    const marker = L.circleMarker([lat, lng], {
                        radius: radius,
                        fillColor: cityData.count >= 10 ? '#EF4444' : (cityData.count >= 5 ? '#F59E0B' : '#10B981'),
                        color: cityData.count >= 10 ? '#DC2626' : (cityData.count >= 5 ? '#D97706' : '#059669'),
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.7
                    }).addTo(map);
                    
                    marker.bindPopup(`
                        <div class="text-sm font-medium">
                            <strong class="text-stone-900 dark:text-white">${cityData.city}</strong><br>
                            <span class="text-emerald-600 dark:text-emerald-400">${cityData.count.toLocaleString()} orders</span>
                            ${cityData.province ? '<br><span class="text-xs text-stone-500">' + cityData.province + '</span>' : ''}
                            ${cityData.region ? '<br><span class="text-xs text-stone-500">' + cityData.region + '</span>' : ''}
                        </div>
                    `);
                    
                    markers.push(marker);
                }
            }
        });
        
        // Add heatmap layer
        if (heatmapData.length > 0 && typeof L !== 'undefined' && L.heatLayer) {
            L.heatLayer(heatmapData, {
                radius: 30,
                blur: 20,
                maxZoom: 17,
                gradient: {
                    0.0: 'blue',
                    0.3: 'cyan',
                    0.6: 'yellow',
                    1.0: 'red'
                },
                max: maxCount || 1
            }).addTo(map);
        }
        
        // Adjust map bounds to fit all markers
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.15));
        }
    }
    
    // Initialize when DOM is ready and Leaflet is loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGeographicMap);
    } else {
        initGeographicMap();
    }
})();

// Region search and toggle functionality
(function() {
    function toggleRegion(regionHash) {
        const citiesDiv = document.getElementById('cities-' + regionHash);
        const arrow = document.getElementById('arrow-' + regionHash);
        
        if (citiesDiv && arrow) {
            if (citiesDiv.classList.contains('hidden')) {
                citiesDiv.classList.remove('hidden');
                arrow.style.transform = 'rotate(90deg)';
            } else {
                citiesDiv.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
            
            // Reinitialize icons after toggle
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    }
    
    // Make toggleRegion available globally
    window.toggleRegion = toggleRegion;
    
    // Region search functionality
    const regionSearchInput = document.getElementById('regionSearch');
    if (regionSearchInput) {
        regionSearchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const regionItems = document.querySelectorAll('.region-item');
            
            regionItems.forEach(function(item) {
                const regionName = item.getAttribute('data-region') || '';
                const citiesDiv = item.querySelector('.region-cities');
                
                if (regionName.includes(searchTerm)) {
                    item.style.display = 'block';
                    // Auto-expand matching regions
                    if (citiesDiv && searchTerm) {
                        const regionHash = citiesDiv.id.replace('cities-', '');
                        if (citiesDiv.classList.contains('hidden')) {
                            toggleRegion(regionHash);
                        }
                    }
                } else {
                    item.style.display = searchTerm ? 'none' : 'block';
                }
            });
        });
    }
    
    // Initialize icons after page load
    setTimeout(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }, 300);
})();
@endif
</script>
@endpush
