@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">
            Welcome back, {{ auth('admin')->user()->first_name }}! 👋
        </h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Here's what's happening with your business today.
        </p>
    </div>

    <div class="flex items-center gap-3">
        <div class="text-right">
            <p class="text-sm text-stone-500 dark:text-gray-400">Last updated</p>
            <p class="text-sm font-medium text-stone-900 dark:text-white">{{ now()->format('M d, Y \a\t g:i A') }}</p>
        </div>
        <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            Refresh
        </button>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards Start -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
    <!-- Revenue Today Card -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-lg shadow-emerald-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/20 dark:from-emerald-900/20 dark:to-emerald-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        ₱{{ number_format($revenueToday, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Revenue Today</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    Today
                </span>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10"></div>
    </div>

    <!-- Orders Status Card -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 dark:from-blue-900/20 dark:to-blue-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($pendingOrders) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Pending Orders</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                    {{ number_format($completedOrders) }} completed
                </span>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10"></div>
    </div>

    <!-- New Customers Card -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100/50 p-6 shadow-lg shadow-purple-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 dark:from-purple-900/20 dark:to-purple-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 shadow-lg">
                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($newCustomersToday) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">New Customers Today</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="user-plus" class="w-3 h-3"></i>
                    {{ number_format($newCustomersThisWeek) }} this week
                </span>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
    </div>

    <!-- Messages & Alerts Card -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100/50 p-6 shadow-lg shadow-amber-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/20 dark:from-amber-900/20 dark:to-amber-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500 shadow-lg">
                    <i data-lucide="bell" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($unreadMessages) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Unread Messages</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-400">
                    <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                    {{ number_format($lowStockCount) }} low stock
                </span>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-500/10"></div>
    </div>
</div>
<!-- Stats Cards End -->

<!-- Charts and Tables -->
<div class="mt-8 grid grid-cols-12 gap-6">
    <!-- Revenue Chart -->
    <div class="col-span-12 rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 xl:col-span-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-stone-900 dark:text-white">Revenue Analytics</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Track your sales performance over time</p>
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
        
        <div class="mb-6 flex items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
                <div>
                    <p class="text-sm font-medium text-stone-900 dark:text-white">Revenue</p>
                    <p class="text-lg font-bold text-emerald-600">₱{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                <div>
                    <p class="text-sm font-medium text-stone-900 dark:text-white">Orders</p>
                    <p class="text-lg font-bold text-blue-600">{{ number_format($totalOrders) }}</p>
                </div>
            </div>
        </div>

        <div>
            <div id="chartOne" class="-ml-5 h-[355px] w-[105%]"></div>
        </div>
    </div>
    <!-- Chart One -->

    <!-- Visitors Analytics Chart -->
    <div class="col-span-12 rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 xl:col-span-4">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-stone-900 dark:text-white">Traffic Sources</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Where your visitors come from</p>
            </div>
            <div>
                <div class="relative z-20 inline-block">
                    <select name="" id="" class="relative z-20 inline-flex appearance-none rounded-lg border border-stone-200 bg-white py-2 pl-3 pr-8 text-sm font-medium text-stone-900 outline-none transition-colors duration-200 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white">
                        <option value="">Monthly</option>
                        <option value="">Yearly</option>
                    </select>
                    <span class="absolute right-3 top-1/2 z-10 -translate-y-1/2">
                        <i data-lucide="chevron-down" class="w-4 h-4 text-stone-500"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="mb-2">
            <div id="chartThree" class="mx-auto flex justify-center"></div>
        </div>

        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
                    <span class="text-sm font-medium text-stone-900 dark:text-white">Desktop</span>
                </div>
                <span class="text-sm font-bold text-stone-900 dark:text-white">65%</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                    <span class="text-sm font-medium text-stone-900 dark:text-white">Tablet</span>
                </div>
                <span class="text-sm font-bold text-stone-900 dark:text-white">34%</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-3 w-3 rounded-full bg-amber-500"></div>
                    <span class="text-sm font-medium text-stone-900 dark:text-white">Mobile</span>
                </div>
                <span class="text-sm font-bold text-stone-900 dark:text-white">45%</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-3 w-3 rounded-full bg-purple-500"></div>
                    <span class="text-sm font-medium text-stone-900 dark:text-white">Unknown</span>
                </div>
                <span class="text-sm font-bold text-stone-900 dark:text-white">12%</span>
            </div>
        </div>
    </div>
    <!-- Chart Three -->

    <!-- Recent Orders Table -->
    <div class="col-span-12 xl:col-span-8">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Recent Orders</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Latest customer orders and their status</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        Filter
                    </button>
                    <a href="{{ admin_route('orders.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 transition-all duration-200 hover:bg-emerald-700">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        View All
                    </a>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-stone-200/50 dark:border-strokedark/50">
                <div class="grid grid-cols-3 rounded-t-xl bg-stone-50 dark:bg-stone-800/50 sm:grid-cols-5">
                    <div class="p-4">
                        <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">
                            Customer
                        </h5>
                    </div>
                    <div class="p-4 text-center">
                        <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">
                            Date
                        </h5>
                    </div>
                    <div class="p-4 text-center">
                        <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">
                            Amount
                        </h5>
                    </div>
                    <div class="hidden p-4 text-center sm:block">
                        <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">
                            Status
                        </h5>
                    </div>
                    <div class="hidden p-4 text-center sm:block">
                        <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">
                            Actions
                        </h5>
                    </div>
                </div>

                @forelse($recentOrders as $order)
                <div class="grid grid-cols-3 border-b border-stone-200/50 dark:border-strokedark/50 transition-colors duration-200 hover:bg-stone-50/50 dark:hover:bg-stone-800/20 sm:grid-cols-5">
                    <div class="flex items-center gap-3 p-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg">
                                <span class="text-white font-semibold text-sm">
                                    {{ substr($order->user->first_name ?? 'G', 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <p class="font-semibold text-stone-900 dark:text-white">{{ $order->user->first_name ?? 'Guest' }} {{ $order->user->last_name ?? '' }}</p>
                            <p class="text-xs text-stone-500 dark:text-gray-400">Order #{{ $order->order_number ?? $order->id }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-center p-4">
                        <p class="text-sm font-medium text-stone-900 dark:text-white">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>

                    <div class="flex items-center justify-center p-4">
                        <p class="font-bold text-stone-900 dark:text-white">₱{{ number_format($order->total_amount, 2) }}</p>
                    </div>

                    <div class="hidden items-center justify-center p-4 sm:flex">
                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            {{ ucfirst($order->status ?? 'pending') }}
                        </span>
                    </div>

                    <div class="hidden items-center justify-center p-4 sm:flex">
                        <a href="{{ admin_route('orders.show', $order) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-emerald-100 hover:text-emerald-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-400">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4 dark:bg-stone-800">
                        <i data-lucide="shopping-cart" class="w-6 h-6 text-stone-400"></i>
                    </div>
                    <p class="text-stone-500 dark:text-gray-400">No recent orders found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity Feed -->
    <div class="col-span-12 xl:col-span-4">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Recent Activity</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Latest business activities</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                        Live
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($recentActivity as $activity)
                <div class="flex items-start gap-3 p-3 rounded-xl border border-stone-200/50 hover:bg-stone-50/50 dark:border-strokedark/50 dark:hover:bg-stone-800/20 transition-all duration-200">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full flex-shrink-0"
                         :class="{
                             'bg-green-100 dark:bg-green-900/30': '{{ $activity['type'] }}' === 'order',
                             'bg-blue-100 dark:bg-blue-900/30': '{{ $activity['type'] }}' === 'message',
                             'bg-yellow-100 dark:bg-yellow-900/30': '{{ $activity['type'] }}' === 'inventory',
                             'bg-purple-100 dark:bg-purple-900/30': '{{ $activity['type'] }}' === 'review'
                         }">
                        <i data-lucide="shopping-cart" class="h-4 w-4" 
                           :class="{
                               'text-green-600 dark:text-green-400': '{{ $activity['type'] }}' === 'order',
                               'text-blue-600 dark:text-blue-400': '{{ $activity['type'] }}' === 'message',
                               'text-yellow-600 dark:text-yellow-400': '{{ $activity['type'] }}' === 'inventory',
                               'text-purple-600 dark:text-purple-400': '{{ $activity['type'] }}' === 'review'
                           }"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-stone-900 dark:text-white">{{ $activity['title'] }}</p>
                        <p class="text-xs text-stone-600 dark:text-gray-400 mt-1">{{ $activity['message'] }}</p>
                        <p class="text-xs text-stone-500 dark:text-gray-500 mt-2">{{ $activity['timestamp']->diffForHumans() }}</p>
                    </div>
                    @if(isset($activity['url']))
                    <a href="{{ $activity['url'] }}" class="flex-shrink-0 text-stone-400 hover:text-primary transition-colors duration-200">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    @endif
            </div>
            @empty
            <div class="text-center py-8">
                    <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4 dark:bg-stone-800">
                        <i data-lucide="activity" class="w-6 h-6 text-stone-400"></i>
                    </div>
                    <p class="text-sm text-stone-500 dark:text-gray-400">No recent activity</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Chart One - Revenue Chart
const chartOneOptions = {
    series: [
        {
            name: 'Revenue',
            data: @json(array_values($monthlyRevenue)),
        },
        {
            name: 'Orders',
            data: @json(array_values($monthlyOrders)),
        },
    ],
    legend: {
        show: false,
        position: 'top',
        horizontalAlign: 'left',
    },
    colors: ['#10B981', '#3B82F6'],
    chart: {
        fontFamily: 'Satoshi, sans-serif',
        height: 335,
        type: 'area',
        dropShadow: {
            enabled: true,
            color: '#623CEA14',
            top: 10,
            blur: 4,
            left: 0,
            opacity: 0.1,
        },
        toolbar: {
            show: false,
        },
    },
    responsive: [
        {
            breakpoint: 1024,
            options: {
                chart: {
                    height: 300,
                },
            },
        },
        {
            breakpoint: 1366,
            options: {
                chart: {
                    height: 350,
                },
            },
        },
    ],
    stroke: {
        width: [2, 2],
        curve: 'straight',
    },
    grid: {
        xaxis: {
            lines: {
                show: true,
            },
        },
        yaxis: {
            lines: {
                show: true,
            },
        },
    },
    dataLabels: {
        enabled: false,
    },
    markers: {
        size: 4,
        colors: '#fff',
        strokeColors: ['#3056D3', '#80CAEE'],
        strokeWidth: 3,
        strokeOpacity: 0.9,
        strokeDashArray: 0,
        fillOpacity: 1,
        discrete: [],
        hover: {
            size: undefined,
            sizeOffset: 5,
        },
    },
    xaxis: {
        type: 'category',
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
    },
    yaxis: {
        title: {
            style: {
                fontSize: '0px',
            },
        },
        min: 0,
        max: Math.max(...@json(array_values($monthlyRevenue))) * 1.1,
    },
};

const chartOne = new ApexCharts(document.querySelector('#chartOne'), chartOneOptions);
chartOne.render();

// Chart Three - Donut Chart
const chartThreeOptions = {
    series: [65, 34, 45, 12],
    chart: {
        type: 'donut',
        width: 380,
        height: 335,
    },
    colors: ['#10B981', '#3B82F6', '#F59E0B', '#8B5CF6'],
    labels: ['Desktop', 'Tablet', 'Mobile', 'Unknown'],
    legend: {
        show: false,
        position: 'bottom',
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
    responsive: [
        {
            breakpoint: 2600,
            options: {
                chart: {
                    width: 380,
                },
            },
        },
        {
            breakpoint: 640,
            options: {
                chart: {
                    width: 200,
                },
            },
        },
    ],
};

const chartThree = new ApexCharts(document.querySelector('#chartThree'), chartThreeOptions);
chartThree.render();
</script>
@endpush