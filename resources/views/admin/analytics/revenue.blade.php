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
        <div class="text-right">
            <p class="text-sm text-stone-500 dark:text-gray-400">Report Period</p>
            <p class="text-sm font-medium text-stone-900 dark:text-white">{{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}</p>
        </div>
        <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="download" class="w-4 h-4"></i>
            Export
        </button>
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
                        ${{ number_format($totalRevenue, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Revenue</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +15.3%
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
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +2.1%
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
                        ${{ number_format($averageOrderValue, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Avg Order Value</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +5.7%
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
                        ${{ number_format($revenuePerCustomer, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Revenue per Customer</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +8.4%
                </span>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-12 gap-6">
    <!-- Revenue Trend Chart -->
    <div class="col-span-12 xl:col-span-8">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Revenue Trend</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Daily revenue performance over time</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="inline-flex items-center rounded-xl bg-stone-100 p-1 dark:bg-stone-800">
                        <button class="rounded-lg bg-white px-3 py-1.5 text-xs font-medium text-stone-900 shadow-sm transition-all duration-200 hover:shadow-md dark:bg-stone-700 dark:text-white">
                            Day
                        </button>
                        <button class="rounded-lg px-3 py-1.5 text-xs font-medium text-stone-600 transition-colors duration-200 hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">
                            Week
                        </button>
                        <button class="rounded-lg px-3 py-1.5 text-xs font-medium text-stone-600 transition-colors duration-200 hover:text-stone-900 dark:text-stone-400 dark:hover:text-white">
                            Month
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <div id="revenueTrendChart" class="h-[400px] w-full"></div>
            </div>
        </div>
    </div>

    <!-- Revenue Sources -->
    <div class="col-span-12 xl:col-span-4">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
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
                    <span class="text-sm font-bold text-stone-900 dark:text-white">${{ number_format($category->total_revenue, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Revenue Comparison -->
<div class="mt-8">
    <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-stone-900 dark:text-white">Revenue Comparison</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Compare current period with previous period</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Current Period -->
            <div class="text-center p-6 rounded-xl bg-emerald-50 dark:bg-emerald-900/20">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 mx-auto mb-4">
                    <i data-lucide="calendar" class="w-6 h-6 text-white"></i>
                </div>
                <h4 class="text-lg font-bold text-stone-900 dark:text-white mb-2">Current Period</h4>
                <p class="text-2xl font-bold text-emerald-600 mb-1">${{ number_format($currentPeriodRevenue, 2) }}</p>
                <p class="text-sm text-stone-600 dark:text-gray-400">{{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}</p>
            </div>

            <!-- Previous Period -->
            <div class="text-center p-6 rounded-xl bg-stone-50 dark:bg-stone-800/50">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-stone-500 mx-auto mb-4">
                    <i data-lucide="calendar-days" class="w-6 h-6 text-white"></i>
                </div>
                <h4 class="text-lg font-bold text-stone-900 dark:text-white mb-2">Previous Period</h4>
                <p class="text-2xl font-bold text-stone-600 dark:text-gray-400 mb-1">${{ number_format($previousPeriodRevenue, 2) }}</p>
                <p class="text-sm text-stone-600 dark:text-gray-400">{{ $previousStartDate->format('M d') }} - {{ $previousEndDate->format('M d, Y') }}</p>
            </div>

            <!-- Growth -->
            <div class="text-center p-6 rounded-xl {{ $revenueGrowth >= 0 ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }}">
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
// Revenue Trend Chart
const revenueTrendOptions = {
    series: [{
        name: 'Revenue',
        data: @json($dailyRevenue)
    }],
    chart: {
        type: 'area',
        height: 400,
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
        categories: @json($chartLabels),
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
                return '$' + val.toLocaleString()
            }
        }
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return '$' + val.toLocaleString()
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
revenueTrendChart.render();

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
</script>
@endpush
