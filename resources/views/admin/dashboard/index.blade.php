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
        <button id="refreshBtn" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            Refresh
        </button>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Quick Action Cards Start -->
<div class="mb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ admin_route('orders.index') }}" class="group relative overflow-hidden rounded-xl border border-stone-200/50 bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 hover:scale-[1.02] dark:border-strokedark/50 dark:from-blue-900/20 dark:to-blue-800/10">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-stone-900 dark:text-white">Orders</h3>
                    <p class="text-xs text-stone-600 dark:text-gray-400 mt-0.5">Manage orders</p>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-stone-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all duration-300"></i>
            </div>
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10"></div>
        </a>

        <a href="{{ admin_route('products.index') }}" class="group relative overflow-hidden rounded-xl border border-stone-200/50 bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-lg shadow-emerald-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/20 hover:scale-[1.02] dark:border-strokedark/50 dark:from-emerald-900/20 dark:to-emerald-800/10">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="package" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-stone-900 dark:text-white">Products</h3>
                    <p class="text-xs text-stone-600 dark:text-gray-400 mt-0.5">Manage products</p>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-stone-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all duration-300"></i>
            </div>
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10"></div>
        </a>

        <a href="{{ admin_route('users.index') }}" class="group relative overflow-hidden rounded-xl border border-stone-200/50 bg-gradient-to-br from-amber-50 to-amber-100/50 p-6 shadow-lg shadow-amber-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/20 hover:scale-[1.02] dark:border-strokedark/50 dark:from-amber-900/20 dark:to-amber-800/10">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-stone-900 dark:text-white">Customers</h3>
                    <p class="text-xs text-stone-600 dark:text-gray-400 mt-0.5">Manage customers</p>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-stone-400 group-hover:text-amber-600 group-hover:translate-x-1 transition-all duration-300"></i>
            </div>
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-500/10"></div>
        </a>

        <a href="{{ admin_route('inventory.index') }}" class="group relative overflow-hidden rounded-xl border border-stone-200/50 bg-gradient-to-br from-purple-50 to-purple-100/50 p-6 shadow-lg shadow-purple-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 hover:scale-[1.02] dark:border-strokedark/50 dark:from-purple-900/20 dark:to-purple-800/10">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="warehouse" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-stone-900 dark:text-white">Inventory</h3>
                    <p class="text-xs text-stone-600 dark:text-gray-400 mt-0.5">Manage inventory</p>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-stone-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all duration-300"></i>
            </div>
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
        </a>
    </div>
</div>
<!-- Quick Action Cards End -->

<!-- Main Content Grid -->
<div class="grid grid-cols-12 gap-6 mb-8 items-stretch">
    <!-- Charts Section (Left - 7 cols) -->
    <div class="col-span-12 xl:col-span-7 space-y-6">
        <!-- Revenue Analytics Chart -->
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Revenue Analytics</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Track your sales performance over time</p>
                </div>
                <div class="flex items-center gap-2">
                    <button id="period-prev" class="period-nav inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Previous period">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <div class="inline-flex items-center rounded-xl bg-stone-100 p-1 dark:bg-stone-800">
                        <button id="periodDay" class="period-btn rounded-lg px-3 py-1.5 text-xs font-medium {{ ($currentPeriod ?? 'month') == 'day' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Day
                        </button>
                        <button id="periodWeek" class="period-btn rounded-lg px-3 py-1.5 text-xs font-medium {{ ($currentPeriod ?? 'month') == 'week' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Week
                        </button>
                        <button id="periodMonth" class="period-btn rounded-lg px-3 py-1.5 text-xs font-medium {{ ($currentPeriod ?? 'month') == 'month' ? 'bg-white text-stone-900 shadow-sm dark:bg-stone-700 dark:text-white' : 'text-stone-600 dark:text-stone-400' }} transition-colors duration-200 hover:text-stone-900 dark:hover:text-white">
                            Month
                        </button>
                    </div>
                    <button id="period-next" class="period-nav inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Next period" {{ ($periodOffset ?? 0) <= 0 ? 'disabled' : '' }}>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                    @if(request('period_offset') || request('current_period'))
                    <button id="period-clear" class="period-clear inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-xs font-medium text-stone-700 transition-colors duration-200 hover:bg-stone-50 dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700" title="Clear period filter">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                    @endif
                </div>
            </div>
            
            <div class="mb-6 flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
                    <div>
                        <p class="text-sm font-medium text-stone-900 dark:text-white">Revenue</p>
                        <p id="periodRevenue" class="text-lg font-bold text-emerald-600">₱{{ number_format($periodRevenue ?? $totalRevenue, 2) }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-3 w-3 rounded-full bg-blue-500"></div>
            <div>
                        <p class="text-sm font-medium text-stone-900 dark:text-white">Orders</p>
                        <p id="periodOrders" class="text-lg font-bold text-blue-600">{{ number_format($periodOrders ?? $totalOrders) }}</p>
                    </div>
                </div>
            </div>

            <div>
                <div id="revenueChart" class="h-[355px] w-full"></div>
            </div>
        </div>
    </div>

    <!-- Summary Cards Section (Right - 5 cols) -->
    <div class="col-span-12 xl:col-span-5">
        <div class="grid grid-cols-2 gap-6 h-full">
            <!-- Revenue Today Card -->
            <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 relative flex flex-col">
                <div class="pr-20">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30">
                        <i data-lucide="dollar-sign" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white whitespace-nowrap">
                        ₱{{ number_format($revenueToday, 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Revenue Today</p>
                </div>
            </div>
                <div class="absolute top-6 right-6">
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    Today
                </span>
            </div>
    </div>

            <!-- Pending Orders Card -->
            <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 relative flex flex-col">
                <div class="pr-20">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                        <i data-lucide="shopping-cart" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white whitespace-nowrap">
                        {{ number_format($pendingOrders) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Pending Orders</p>
                </div>
            </div>
                <div class="absolute top-6 right-6">
                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                    {{ number_format($completedOrders) }} completed
                </span>
            </div>
    </div>

            <!-- New Customers Today Card -->
            <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 relative flex flex-col">
                <div class="pr-20">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30">
                        <i data-lucide="users" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white whitespace-nowrap">
                        {{ number_format($newCustomersToday) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">New Customers Today</p>
                </div>
            </div>
                <div class="absolute top-6 right-6">
                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="user-plus" class="w-3 h-3"></i>
                    {{ number_format($newCustomersThisWeek) }} this week
                </span>
            </div>
    </div>

            <!-- Unread Messages Card -->
            <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 relative flex flex-col">
                <div class="pr-20">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                        <i data-lucide="bell" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white whitespace-nowrap">
                        {{ number_format($unreadMessages) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Unread Messages</p>
                </div>
            </div>
                <div class="absolute top-6 right-6">
                    <a href="{{ admin_route('messages.index') }}" class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-800 hover:bg-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:hover:bg-amber-900/50 transition-colors">
                        <i data-lucide="mail" class="w-3 h-3"></i>
                        View
                    </a>
                </div>
            </div>

            <!-- Low Stock Alerts Card -->
            <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 relative flex flex-col">
                <div class="pr-20">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/30">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
        </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-stone-900 dark:text-white whitespace-nowrap">
                            {{ number_format($lowStockCount) }}
                        </h3>
                        <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Low Stock Alerts</p>
    </div>
</div>
                <div class="absolute top-6 right-6">
                    <a href="{{ admin_route('inventory.low-stock') }}" class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-800 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 transition-colors">
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        View
                    </a>
            </div>
        </div>
        
            <!-- Revenue This Month Card -->
            <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 relative flex flex-col">
                <div class="pr-20">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-100 dark:bg-teal-900/30">
                        <i data-lucide="calendar" class="w-5 h-5 text-teal-600 dark:text-teal-400"></i>
                </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-stone-900 dark:text-white whitespace-nowrap">
                            ₱{{ number_format($revenueThisMonth, 2) }}
                        </h3>
                        <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Revenue This Month</p>
            </div>
                </div>
                <div class="absolute top-6 right-6">
                    <span class="inline-flex items-center gap-1 rounded-full bg-teal-100 px-2.5 py-1 text-xs font-medium text-teal-800 dark:bg-teal-900/30 dark:text-teal-400">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        This Month
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Section: Recent Orders and Activity -->
<div class="grid grid-cols-12 gap-6 items-stretch">
    <!-- Recent Orders Table -->
    <div class="col-span-12 xl:col-span-8 flex flex-col">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 flex-1 flex flex-col">
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

            <div class="overflow-hidden rounded-xl border border-stone-200/50 dark:border-strokedark/50 flex-1 flex flex-col">
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

                <div class="overflow-y-auto" style="height: 600px;">
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

        <!-- Top Selling Products and Order Status Distribution -->
        <div class="grid grid-cols-12 gap-6 mt-6 flex-1">
            <!-- Top Selling Products -->
            <div class="col-span-12 md:col-span-6">
                <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full flex flex-col">
                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-stone-900 dark:text-white">Top Selling Products</h3>
                            <p class="text-sm text-stone-600 dark:text-gray-400">Best performing products (Last 30 days)</p>
                        </div>
                        <a href="{{ admin_route('analytics.products') }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-3 py-1.5 text-xs font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            View All
                        </a>
                    </div>

                    <div class="space-y-3 overflow-y-auto" style="height: 450px;">
                        @forelse($topProducts as $index => $product)
                        <div class="flex items-center gap-3 p-3 rounded-xl border border-stone-200/50 transition-all duration-200 hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-strokedark/50 dark:hover:border-emerald-800/50 dark:hover:bg-emerald-900/10">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 font-bold text-sm dark:bg-emerald-900/30 dark:text-emerald-400 flex-shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-stone-900 dark:text-white text-sm truncate">{{ $product->name }}</p>
                                <p class="text-xs text-stone-500 dark:text-gray-400">{{ number_format($product->total_sold ?? 0) }} sold</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="font-bold text-stone-900 dark:text-white text-sm">₱{{ number_format($product->total_revenue ?? 0, 2) }}</p>
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

            <!-- Order Status Distribution -->
            <div class="col-span-12 md:col-span-6">
                <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 h-full flex flex-col">
                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-stone-900 dark:text-white">Order Status Distribution</h3>
                            <p class="text-sm text-stone-600 dark:text-gray-400">Breakdown of orders by status</p>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col items-center justify-center">
                        <div class="mb-6">
                            <div id="orderStatusChart" class="mx-auto flex justify-center"></div>
                        </div>

                        <div class="space-y-3 w-full">
                            @php
                                $statusColors = [
                                    'pending' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-600'],
                                    'processing' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600'],
                                    'shipped' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600'],
                                    'delivered' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600'],
                                    'cancelled' => ['bg' => 'bg-red-500', 'text' => 'text-red-600'],
                                ];
                                $totalOrdersForStatus = array_sum($orderStatusDistribution);
                            @endphp
                            @foreach($orderStatusDistribution as $status => $count)
                                @php
                                    $percentage = $totalOrdersForStatus > 0 ? ($count / $totalOrdersForStatus) * 100 : 0;
                                    $color = $statusColors[$status] ?? ['bg' => 'bg-gray-500', 'text' => 'text-gray-600'];
                                @endphp
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="h-3 w-3 rounded-full {{ $color['bg'] }}"></div>
                                        <span class="text-sm font-medium text-stone-900 dark:text-white capitalize">{{ str_replace('_', ' ', $status) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-bold text-stone-900 dark:text-white">{{ number_format($count) }}</span>
                                        <span class="text-xs text-stone-500 dark:text-gray-400 ml-2">({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Feed -->
    <div class="col-span-12 xl:col-span-4 flex flex-col">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80 flex-1 flex flex-col">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between flex-shrink-0">
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

            <div class="space-y-4 overflow-y-auto" style="height: 1220px;">
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
                        <p class="text-xs text-stone-500 dark:text-gray-500 mt-2 activity-timestamp" data-timestamp="{{ $activity['timestamp']->toIso8601String() }}">{{ $activity['timestamp']->diffForHumans() }}</p>
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
// Prepare period data for Revenue Chart
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
        revenue: @json($monthlyRevenueChart ?? [])
    }
};

// Period navigation state
let periodOffset = {{ $periodOffset ?? 0 }};
let currentRevenuePeriod = '{{ $currentPeriod ?? 'month' }}';
const maxPeriodOffset = 0; // Cannot go forward past today

// Revenue Chart - Smooth Line Chart
const revenueChartOptions = {
    series: [{
        name: 'Revenue',
        data: revenuePeriodData[currentRevenuePeriod]?.revenue || []
    }],
    chart: {
        type: 'line',
        height: 355,
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

const revenueChart = new ApexCharts(document.querySelector('#revenueChart'), revenueChartOptions);
revenueChart.render();

// Update navigation buttons state
function updatePeriodNavigation() {
    const prevBtn = document.getElementById('period-prev');
    const nextBtn = document.getElementById('period-next');
    
    // Can always go back
    if (prevBtn) prevBtn.disabled = false;
    
    // Cannot go forward past today (offset must be >= 0)
    if (nextBtn) nextBtn.disabled = periodOffset <= maxPeriodOffset;
}

// Navigate to previous period
const periodPrevBtn = document.getElementById('period-prev');
if (periodPrevBtn) {
    periodPrevBtn.addEventListener('click', function() {
        let offsetIncrement = 1;
        
        if (currentRevenuePeriod === 'month') {
            offsetIncrement = 12; // Go back 12 months (a full year)
        } else if (currentRevenuePeriod === 'week') {
            offsetIncrement = 4; // Go back 4 weeks (a full month of weeks)
        } else if (currentRevenuePeriod === 'day') {
            offsetIncrement = 7; // Go back 7 days (a full week)
        }
        
        periodOffset += offsetIncrement;
        updatePeriodNavigation();
        loadPeriodData();
    });
}

// Navigate to next period
const periodNextBtn = document.getElementById('period-next');
if (periodNextBtn) {
    periodNextBtn.addEventListener('click', function() {
        let offsetDecrement = 1;
        
        if (currentRevenuePeriod === 'month') {
            offsetDecrement = 12; // Go forward 12 months (a full year)
        } else if (currentRevenuePeriod === 'week') {
            offsetDecrement = 4; // Go forward 4 weeks (a full month of weeks)
        } else if (currentRevenuePeriod === 'day') {
            offsetDecrement = 7; // Go forward 7 days (a full week)
        }
        
        if (periodOffset >= offsetDecrement) {
            periodOffset -= offsetDecrement;
            updatePeriodNavigation();
            loadPeriodData();
        }
    });
}

// Period filter buttons
document.querySelectorAll('.period-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Determine period
        let period = 'month';
        if (this.id === 'periodDay') period = 'day';
        else if (this.id === 'periodWeek') period = 'week';
        else if (this.id === 'periodMonth') period = 'month';
        
        // Update period and reload
        currentRevenuePeriod = period;
        periodOffset = 0; // Reset offset when changing period type
        loadPeriodData();
    });
});

// Clear period filter
const periodClearBtn = document.getElementById('period-clear');
if (periodClearBtn) {
    periodClearBtn.addEventListener('click', function() {
        window.location.href = '{{ admin_route('dashboard') }}';
    });
}

// Load period data from server
function loadPeriodData() {
    // Build URL with period parameters
    const params = new URLSearchParams({
        period_offset: periodOffset,
        current_period: currentRevenuePeriod
    });
    
    // Reload page with new period offset
    window.location.href = '{{ admin_route('dashboard') }}?' + params.toString();
}

// Initialize navigation state
updatePeriodNavigation();

// Refresh button functionality
document.getElementById('refreshBtn').addEventListener('click', function() {
    this.classList.add('animate-spin');
    location.reload();
});

// Order Status Distribution Chart
const orderStatusData = @json($orderStatusDistribution ?? []);
const orderStatusChartOptions = {
    series: Object.values(orderStatusData),
    chart: {
        type: 'donut',
        height: 250,
        fontFamily: 'Inter, sans-serif',
        toolbar: {
            show: false
        }
    },
    labels: Object.keys(orderStatusData).map(status => status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ')),
    colors: ['#EAB308', '#3B82F6', '#A855F7', '#10B981', '#EF4444'],
    legend: {
        show: false
    },
    dataLabels: {
        enabled: false
    },
    plotOptions: {
        pie: {
            donut: {
                size: '70%',
                labels: {
                    show: true,
                    total: {
                        show: true,
                        label: 'Total Orders',
                        formatter: function() {
                            return Object.values(orderStatusData).reduce((a, b) => a + b, 0);
                        }
                    }
                }
            }
        }
    },
    tooltip: {
        y: {
            formatter: function(val, opts) {
                const total = Object.values(orderStatusData).reduce((a, b) => a + b, 0);
                const percentage = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                return val + ' (' + percentage + '%)';
            }
        }
    }
};

const orderStatusChartElement = document.querySelector('#orderStatusChart');
if (orderStatusChartElement) {
    const orderStatusChart = new ApexCharts(orderStatusChartElement, orderStatusChartOptions);
    orderStatusChart.render();
}

// Real-time timestamp updates for Recent Activity
function updateActivityTimestamps() {
    const timestampElements = document.querySelectorAll('.activity-timestamp');
    const now = new Date();
    
    timestampElements.forEach(element => {
        const timestampStr = element.getAttribute('data-timestamp');
        if (!timestampStr) return;
        
        const timestamp = new Date(timestampStr);
        const diffInSeconds = Math.floor((now - timestamp) / 1000);
        
        let timeAgo;
        if (diffInSeconds < 60) {
            timeAgo = 'Just now';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            timeAgo = `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            timeAgo = `${hours} hour${hours !== 1 ? 's' : ''} ago`;
        } else if (diffInSeconds < 604800) {
            const days = Math.floor(diffInSeconds / 86400);
            timeAgo = `${days} day${days !== 1 ? 's' : ''} ago`;
        } else if (diffInSeconds < 2592000) {
            const weeks = Math.floor(diffInSeconds / 604800);
            timeAgo = `${weeks} week${weeks !== 1 ? 's' : ''} ago`;
        } else {
            const months = Math.floor(diffInSeconds / 2592000);
            timeAgo = `${months} month${months !== 1 ? 's' : ''} ago`;
        }
        
        element.textContent = timeAgo;
    });
}

// Update timestamps immediately and then every 30 seconds
updateActivityTimestamps();
setInterval(updateActivityTimestamps, 30000);
</script>
@endpush
