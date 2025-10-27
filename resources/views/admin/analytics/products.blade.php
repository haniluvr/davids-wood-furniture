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
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +5.2%
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
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +12.8%
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
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +8.5%
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
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +3.2%
                </span>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-12 gap-6">
    <!-- Product Performance Chart -->
    <div class="col-span-12 xl:col-span-8">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Product Performance</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Top performing products by revenue</p>
                </div>
            </div>

            <div>
                <div id="productPerformanceChart" class="h-[400px] w-full"></div>
            </div>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="col-span-12 xl:col-span-4">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Category Breakdown</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Sales by product category</p>
                </div>
            </div>

            <div class="mb-6">
                <div id="categoryBreakdownChart" class="mx-auto flex justify-center"></div>
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

<!-- Top Products Table -->
<div class="mt-8">
    <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-stone-900 dark:text-white">Top Selling Products</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Best performing products this period</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-stone-200/50 dark:border-strokedark/50">
            <div class="grid grid-cols-6 rounded-t-xl bg-stone-50 dark:bg-stone-800/50">
                <div class="p-4">
                    <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Product</h5>
                </div>
                <div class="p-4 text-center">
                    <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">SKU</h5>
                </div>
                <div class="p-4 text-center">
                    <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Price</h5>
                </div>
                <div class="p-4 text-center">
                    <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Units Sold</h5>
                </div>
                <div class="p-4 text-center">
                    <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Revenue</h5>
                </div>
                <div class="p-4 text-center">
                    <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Performance</h5>
                </div>
            </div>

            @forelse($topProducts as $product)
            <div class="grid grid-cols-6 border-b border-stone-200/50 dark:border-strokedark/50 transition-colors duration-200 hover:bg-stone-50/50 dark:hover:bg-stone-800/20">
                <div class="flex items-center gap-3 p-4">
                    <div class="h-10 w-10 rounded-lg bg-stone-100 flex items-center justify-center dark:bg-stone-800">
                        @if($product->images && count($product->images) > 0)
                            <img class="h-8 w-8 object-cover rounded" src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" />
                        @else
                            <i data-lucide="package" class="w-4 h-4 text-stone-400"></i>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-stone-900 dark:text-white">{{ $product->name }}</p>
                        <p class="text-xs text-stone-500 dark:text-gray-400">{{ $product->category->name ?? 'Uncategorized' }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-center p-4">
                    <p class="text-sm font-medium text-stone-900 dark:text-white">{{ $product->sku }}</p>
                </div>

                <div class="flex items-center justify-center p-4">
                    <p class="font-bold text-stone-900 dark:text-white">₱{{ number_format($product->price, 2) }}</p>
                </div>

                <div class="flex items-center justify-center p-4">
                    <p class="font-bold text-stone-900 dark:text-white">{{ number_format($product->total_sold) }}</p>
                </div>

                <div class="flex items-center justify-center p-4">
                    <p class="font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($product->total_revenue, 2) }}</p>
                </div>

                <div class="flex items-center justify-center p-4">
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-16 bg-stone-200 rounded-full dark:bg-stone-700">
                            <div class="h-2 bg-emerald-500 rounded-full" style="width: {{ min(100, ($product->total_revenue / $topProducts->first()->total_revenue) * 100) }}%"></div>
                        </div>
                        <span class="text-xs text-stone-500 dark:text-gray-400">{{ number_format(($product->total_revenue / $topProducts->first()->total_revenue) * 100, 1) }}%</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4 dark:bg-stone-800">
                    <i data-lucide="package" class="w-6 h-6 text-stone-400"></i>
                </div>
                <p class="text-stone-500 dark:text-gray-400">No product data available</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Product Performance Chart
const productPerformanceOptions = {
    series: [{
        name: 'Revenue',
        data: @json($topProducts->pluck('total_revenue')->toArray())
    }],
    chart: {
        type: 'bar',
        height: 400,
        fontFamily: 'Inter, sans-serif',
        toolbar: {
            show: false
        }
    },
    colors: ['#10B981'],
    plotOptions: {
        bar: {
            borderRadius: 8,
            horizontal: false,
        }
    },
    xaxis: {
        categories: @json($topProducts->pluck('name')->toArray()),
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

const productPerformanceChart = new ApexCharts(document.querySelector('#productPerformanceChart'), productPerformanceOptions);
productPerformanceChart.render();

// Category Breakdown Chart
const categoryBreakdownOptions = {
    series: @json($categorySales->pluck('total_revenue')->toArray()),
    chart: {
        type: 'donut',
        width: 300,
        height: 300,
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
categoryBreakdownChart.render();
</script>
@endpush
