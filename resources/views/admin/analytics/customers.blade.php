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
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +12.5%
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
                        {{ number_format($newCustomers) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">New Customers</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +8.2%
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
                        ${{ number_format($averageLifetimeValue, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Avg Lifetime Value</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +5.1%
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
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +2.3%
                </span>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-12 gap-6">
    <!-- Customer Acquisition Chart -->
    <div class="col-span-12 xl:col-span-8">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Customer Acquisition</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">New customer registrations over time</p>
                </div>
            </div>

            <div>
                <div id="customerAcquisitionChart" class="h-[400px] w-full"></div>
            </div>
        </div>
    </div>

    <!-- Customer Segments -->
    <div class="col-span-12 xl:col-span-4">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Customer Segments</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Customer distribution by value</p>
                </div>
            </div>

            <div class="mb-6">
                <div id="customerSegmentsChart" class="mx-auto flex justify-center"></div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
                        <span class="text-sm font-medium text-stone-900 dark:text-white">High Value</span>
                    </div>
                    <span class="text-sm font-bold text-stone-900 dark:text-white">{{ $highValueCustomers }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                        <span class="text-sm font-medium text-stone-900 dark:text-white">Medium Value</span>
                    </div>
                    <span class="text-sm font-bold text-stone-900 dark:text-white">{{ $mediumValueCustomers }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full bg-amber-500"></div>
                        <span class="text-sm font-medium text-stone-900 dark:text-white">Low Value</span>
                    </div>
                    <span class="text-sm font-bold text-stone-900 dark:text-white">{{ $lowValueCustomers }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Customer Acquisition Chart
const customerAcquisitionOptions = {
    series: [{
        name: 'New Customers',
        data: @json($dailyNewCustomers)
    }],
    chart: {
        type: 'area',
        height: 400,
        fontFamily: 'Inter, sans-serif',
        toolbar: {
            show: false
        }
    },
    colors: ['#3B82F6'],
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
    }
};

const customerAcquisitionChart = new ApexCharts(document.querySelector('#customerAcquisitionChart'), customerAcquisitionOptions);
customerAcquisitionChart.render();

// Customer Segments Chart
const customerSegmentsOptions = {
    series: [{{ $highValueCustomers }}, {{ $mediumValueCustomers }}, {{ $lowValueCustomers }}],
    chart: {
        type: 'donut',
        width: 300,
        height: 300,
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
customerSegmentsChart.render();
</script>
@endpush
