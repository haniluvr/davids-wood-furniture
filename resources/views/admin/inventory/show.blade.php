@extends('admin.layouts.app')

@section('title', 'Inventory Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="warehouse" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Inventory Details - {{ $product->name }}</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">View and manage product inventory information</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ admin_route('inventory.adjust', $product) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-blue-600 text-white hover:from-emerald-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl text-sm font-medium">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Adjust Stock
                </a>
                <a href="{{ admin_route('inventory.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to Inventory
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Current Stock</p>
                        <p class="text-3xl font-bold {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-red-600 dark:text-red-400' : 'text-black dark:text-white' }} mt-2">
                            {{ $product->stock_quantity }}
                        </p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br {{ $product->stock_quantity <= $product->low_stock_threshold ? 'from-red-500 to-red-600' : 'from-blue-500 to-blue-600' }} shadow-lg">
                        <i data-lucide="package" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Low Stock Threshold</p>
                        <p class="text-3xl font-bold text-black dark:text-white mt-2">{{ $product->low_stock_threshold }}</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 shadow-lg">
                        <i data-lucide="alert-triangle" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Units Sold (30 days)</p>
                        <p class="text-3xl font-bold text-black dark:text-white mt-2">
                            {{ $product->orderItems()->whereHas('order', function($q) { $q->where('created_at', '>=', now()->subDays(30)); })->sum('quantity') }}
                        </p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-green-600 shadow-lg">
                        <i data-lucide="trending-up" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Stock Value</p>
                        <p class="text-3xl font-bold text-black dark:text-white mt-2">₱{{ number_format($product->stock_quantity * $product->price, 2) }}</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 shadow-lg">
                        <i data-lucide="dollar-sign" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content with Tabs -->
    <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
        <!-- Tab Navigation -->
        <div class="border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-stone-50 to-gray-50 dark:from-gray-800 dark:to-gray-700">
            <nav class="flex space-x-8 px-8" aria-label="Tabs">
                <button
                    onclick="switchTab('overview')"
                    class="tab-button active border-b-2 border-primary py-6 px-1 text-sm font-semibold text-primary transition-all duration-200"
                    data-tab="overview"
                >
                    Overview
                </button>
                <button
                    onclick="switchTab('movements')"
                    class="tab-button border-b-2 border-transparent py-6 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200"
                    data-tab="movements"
                >
                    Stock Movements
                </button>
                <button
                    onclick="switchTab('analytics')"
                    class="tab-button border-b-2 border-transparent py-6 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200"
                    data-tab="analytics"
                >
                    Analytics
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-8">
            <!-- Overview Tab -->
            <div id="overview-tab" class="tab-content">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <!-- Product Information Card -->
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                    <i data-lucide="info" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Product Information</h3>
                            </div>
                            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Basic product details and specifications</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Name:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">SKU:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->sku }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Category:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->category->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Weight:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->weight ? $product->weight . ' lbs' : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Dimensions:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->dimensions ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Settings Card -->
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl">
                                    <i data-lucide="settings" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Stock Settings</h3>
                            </div>
                            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Inventory management configuration</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Manage Stock:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->manage_stock ? 'Yes' : 'No' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Stock Status:</span>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $product->stock_quantity <= $product->low_stock_threshold ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                    {{ $product->stock_quantity <= $product->low_stock_threshold ? 'Low Stock' : 'In Stock' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Days Until Stockout:</span>
                                <span class="font-semibold text-black dark:text-white">
                                    @php
                                        $dailySales = $product->orderItems()->whereHas('order', function($q) { $q->where('created_at', '>=', now()->subDays(30)); })->sum('quantity') / 30;
                                        $daysUntilStockout = $dailySales > 0 ? floor($product->stock_quantity / $dailySales) : 'N/A';
                                    @endphp
                                    {{ $daysUntilStockout }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Level Trend Card -->
                    <div class="bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden xl:col-span-2">
                        <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl">
                                    <i data-lucide="trending-down" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Stock Level Trend</h3>
                            </div>
                            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Visual representation of stock changes over time</p>
                        </div>
                        <div class="p-6">
                            <div class="h-80 w-full rounded-lg bg-white dark:bg-gray-800 p-4">
                                <canvas id="stockChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Movements Tab -->
            <div id="movements-tab" class="tab-content hidden">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold text-black dark:text-white">Stock Movement History</h3>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Track all stock adjustments and movements</p>
                    </div>
                    <a href="{{ admin_route('inventory.adjust', $product) }}" 
                       class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-blue-600 px-4 py-2.5 text-white hover:from-emerald-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Adjust Stock
                    </a>
                </div>

                <div class="bg-white dark:bg-boxdark rounded-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-stone-50 to-gray-50 dark:from-gray-800 dark:to-gray-700">
                                <tr>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Date</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Type</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Quantity</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Previous Stock</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">New Stock</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Reason</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">User</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-200 dark:divide-strokedark">
                                @forelse($movements as $movement)
                                    <tr class="hover:bg-stone-50 dark:hover:bg-gray-800 transition-colors">
                                        <td class="py-4 px-6 text-sm text-gray-900 dark:text-white">
                                            {{ $movement->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $movement->type_badge_color }}">
                                                {{ ucfirst($movement->type) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-sm font-semibold {{ $movement->quantity > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $movement->formatted_quantity }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-900 dark:text-white">
                                            {{ $movement->previous_stock }}
                                        </td>
                                        <td class="py-4 px-6 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $movement->new_stock }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $movement->reason ?? 'N/A' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $movement->createdBy ? $movement->createdBy->name : 'System' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $movement->notes ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <i data-lucide="package-x" class="h-12 w-12 text-gray-400 mb-3"></i>
                                                <p class="text-gray-500 dark:text-gray-400">No stock movements recorded yet</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($movements->hasPages())
                    <div class="mt-6">
                        @include('admin.partials.pagination', ['paginator' => $movements])
                    </div>
                @endif
            </div>

            <!-- Analytics Tab -->
            <div id="analytics-tab" class="tab-content hidden">
                <div class="mb-6">
                    <h3 class="text-2xl font-semibold text-black dark:text-white">Inventory Analytics</h3>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Sales performance and inventory insights</p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Sales Performance Card -->
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                    <i data-lucide="trending-up" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Sales Performance</h3>
                            </div>
                            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Product sales metrics and revenue</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Total Units Sold:</span>
                                <span class="font-bold text-black dark:text-white text-lg">{{ $product->orderItems()->sum('quantity') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Last 30 Days:</span>
                                <span class="font-bold text-black dark:text-white text-lg">{{ $product->orderItems()->whereHas('order', function($q) { $q->where('created_at', '>=', now()->subDays(30)); })->sum('quantity') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Average Daily Sales:</span>
                                <span class="font-bold text-black dark:text-white text-lg">
                                    @php
                                        $dailySales = $product->orderItems()->whereHas('order', function($q) { $q->where('created_at', '>=', now()->subDays(30)); })->sum('quantity') / 30;
                                    @endphp
                                    {{ number_format($dailySales, 1) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Total Revenue:</span>
                                <span class="font-bold text-green-600 dark:text-green-400 text-lg">₱{{ number_format($product->orderItems()->sum(DB::raw('quantity * unit_price')), 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Analysis Card -->
                    <div class="bg-gradient-to-br from-amber-50 to-yellow-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl">
                                    <i data-lucide="warehouse" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Inventory Analysis</h3>
                            </div>
                            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Stock metrics and reorder recommendations</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Stock Turnover Rate:</span>
                                <span class="font-bold text-black dark:text-white text-lg">
                                    @php
                                        $turnoverRate = $product->orderItems()->sum('quantity') / max($product->stock_quantity, 1);
                                    @endphp
                                    {{ number_format($turnoverRate, 2) }}x
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Stock Value:</span>
                                <span class="font-bold text-black dark:text-white text-lg">₱{{ number_format($product->stock_quantity * $product->price, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Reorder Point:</span>
                                <span class="font-bold text-black dark:text-white text-lg">{{ $product->low_stock_threshold }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Suggested Reorder Qty:</span>
                                <span class="font-bold text-black dark:text-white text-lg">
                                    @php
                                        $suggestedReorder = max($product->low_stock_threshold * 2, 10);
                                    @endphp
                                    {{ $suggestedReorder }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="bg-gradient-to-br from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl">
                                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Sales Trend (Last 30 Days)</h3>
                            </div>
                            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Visual representation of sales over time</p>
                        </div>
                        <div class="p-6">
                            <div class="h-80 w-full rounded-lg bg-white dark:bg-gray-800 p-4">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-primary', 'text-primary', 'font-semibold');
        button.classList.add('border-transparent', 'text-gray-500', 'font-medium');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
    if (activeButton) {
        activeButton.classList.add('active', 'border-primary', 'text-primary', 'font-semibold');
        activeButton.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
    }
    
    // Refresh Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Stock Chart - Line Chart with TailAdmin Styling
const stockCtx = document.getElementById('stockChart');
if (stockCtx) {
    const gradient = stockCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.05)');
    
    new Chart(stockCtx.getContext('2d'), {
        type: 'line',
        data: {
            labels: @json($stockTrendData['labels']),
            datasets: [{
                label: 'Stock Level',
                data: @json($stockTrendData['data']),
                borderColor: '#10B981',
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#10B981',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: '#10B981',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13
                    },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Stock: ' + context.parsed.y + ' units';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        },
                        color: '#6B7280'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        },
                        color: '#6B7280',
                        callback: function(value) {
                            return value;
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// Sales Chart - Bar Chart with TailAdmin Styling
const salesCtx = document.getElementById('salesChart');
if (salesCtx) {
    new Chart(salesCtx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($salesTrendData['labels']),
            datasets: [{
                label: 'Units Sold',
                data: @json($salesTrendData['data']),
                backgroundColor: '#10B981',
                borderRadius: 8,
                borderSkipped: false,
                barThickness: 50,
                maxBarThickness: 60
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13
                    },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Units Sold: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        },
                        color: '#6B7280'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        },
                        color: '#6B7280',
                        stepSize: 1,
                        precision: 0
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
@endpush
@endsection