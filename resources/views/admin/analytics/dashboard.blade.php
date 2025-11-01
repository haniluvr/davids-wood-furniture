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
        <!-- Time Filters -->
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ admin_route('analytics.index') }}" class="flex items-center gap-2">
                <select name="date_range" id="date-range" onchange="this.form.submit()" class="rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
                    <option value="7" {{ $dateRange == '7' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ $dateRange == '30' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ $dateRange == '90' ? 'selected' : '' }}>Last 90 days</option>
                    <option value="365" {{ $dateRange == '365' ? 'selected' : '' }}>Last year</option>
                    <option value="custom" {{ request('start_date') && request('end_date') ? 'selected' : '' }}>Custom</option>
                </select>
                
                @if(request('start_date') || request('end_date'))
                <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
                <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
                @endif
            </form>
        </div>
        
        <a href="{{ admin_route('analytics.export', ['type' => 'all', 'date_range' => $dateRange, 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="download" class="w-4 h-4"></i>
            Export All Data
        </a>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Key Metrics Cards -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4 mb-8">
    <!-- Total Orders -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 dark:from-blue-900/20 dark:to-blue-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($salesData['total_orders'] ?? 0) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Orders</p>
                </div>
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
                        ₱{{ number_format($salesData['total_revenue'] ?? 0, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Revenue</p>
                </div>
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
                        ₱{{ number_format($salesData['average_order_value'] ?? 0, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Avg Order Value</p>
                </div>
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
                        {{ number_format($salesData['conversion_rate'] ?? 0, 2) }}%
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Conversion Rate</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-12 gap-6 mb-8">
    <!-- Revenue Over Time (Line Chart) -->
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

    <!-- Traffic Sources (Pie Chart) -->
    <div class="col-span-12 xl:col-span-4">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
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
</div>

<!-- Top Products by Sales (Bar Chart) -->
<div class="grid grid-cols-12 gap-6 mb-8">
    <div class="col-span-12">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Top Products by Sales</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Best performing products this period</p>
                </div>
            </div>

            <div>
                <div id="topProductsChart" class="h-[400px] w-full"></div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-stone-900 dark:text-white">Quick Actions</h3>
            <p class="text-sm text-stone-600 dark:text-gray-400">Navigate to detailed reports</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ admin_route('analytics.sales') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-medium text-white shadow-lg shadow-blue-600/25 hover:bg-blue-700 transition-all duration-200">
            <i data-lucide="chart-bar" class="w-4 h-4"></i>
            Sales Reports
        </a>
        <a href="{{ admin_route('analytics.customers') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="users" class="w-4 h-4"></i>
            Customer Insights
        </a>
        <a href="{{ admin_route('analytics.products') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-4 py-3 text-sm font-medium text-white shadow-lg shadow-amber-600/25 hover:bg-amber-700 transition-all duration-200">
            <i data-lucide="package" class="w-4 h-4"></i>
            Product Reports
        </a>
        <a href="{{ admin_route('analytics.revenue') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-purple-600 px-4 py-3 text-sm font-medium text-white shadow-lg shadow-purple-600/25 hover:bg-purple-700 transition-all duration-200">
            <i data-lucide="dollar-sign" class="w-4 h-4"></i>
            Revenue Reports
        </a>
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
const topProducts = @json(($topProducts ?? [])->take(10)->map(function($p) { return ['name' => $p->name, 'total_revenue' => $p->total_revenue ?? 0]; })->values()->toArray());
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
    colors: ['#3B82F6'],
    plotOptions: {
        bar: {
            borderRadius: 8,
            horizontal: false,
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
