@extends('admin.layouts.app')

@section('title', 'Inventory Details')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            Inventory Details
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a href="{{ admin_route('dashboard') }}" class="font-medium">Dashboard</a></li>
                <li class="font-medium text-primary">/</li>
                <li><a href="{{ admin_route('inventory.index') }}" class="font-medium">Inventory</a></li>
                <li class="font-medium text-primary">/</li>
                <li class="font-medium text-primary">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>

    <!-- Product Header -->
    <div class="mb-6 rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
                @if($product->images && count($product->images) > 0)
                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="h-16 w-16 rounded-lg object-cover">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                        <i data-lucide="package" class="h-8 w-8 text-gray-400"></i>
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-black dark:text-white">{{ $product->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $product->stock_quantity <= $product->low_stock_threshold ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                    {{ $product->stock_quantity <= $product->low_stock_threshold ? 'Low Stock' : 'In Stock' }}
                </span>
                <a
                    href="{{ admin_route('inventory.adjust', $product) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-white hover:bg-opacity-90"
                >
                    <i data-lucide="edit" class="h-4 w-4"></i>
                    Adjust Stock
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Current Stock</p>
                    <p class="text-2xl font-bold {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-red-600' : 'text-black dark:text-white' }}">
                        {{ $product->stock_quantity }}
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $product->stock_quantity <= $product->low_stock_threshold ? 'bg-red-100 dark:bg-red-900' : 'bg-blue-100 dark:bg-blue-900' }}">
                    <i data-lucide="package" class="h-6 w-6 {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400' }}"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Low Stock Threshold</p>
                    <p class="text-2xl font-bold text-black dark:text-white">{{ $product->low_stock_threshold }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <i data-lucide="alert-triangle" class="h-6 w-6 text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Units Sold (30 days)</p>
                    <p class="text-2xl font-bold text-black dark:text-white">{{ $product->orderItems()->whereHas('order', function($q) { $q->where('created_at', '>=', now()->subDays(30)); })->sum('quantity') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                    <i data-lucide="trending-up" class="h-6 w-6 text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Value</p>
                    <p class="text-2xl font-bold text-black dark:text-white">₱{{ number_format($product->stock_quantity * $product->price, 2) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                    <i data-lucide="dollar-sign" class="h-6 w-6 text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="rounded-xl border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
        <!-- Tab Navigation -->
        <div class="border-b border-stroke dark:border-strokedark">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button
                    onclick="switchTab('overview')"
                    class="tab-button active border-b-2 border-primary py-4 px-1 text-sm font-medium text-primary"
                    data-tab="overview"
                >
                    Overview
                </button>
                <button
                    onclick="switchTab('movements')"
                    class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                    data-tab="movements"
                >
                    Stock Movements
                </button>
                <button
                    onclick="switchTab('analytics')"
                    class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                    data-tab="analytics"
                >
                    Analytics
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Overview Tab -->
            <div id="overview-tab" class="tab-content">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Product Information -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Product Information</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Name:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">SKU:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->sku }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Category:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->category->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Weight:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->weight ? $product->weight . ' lbs' : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Dimensions:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->dimensions ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Stock Settings</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Manage Stock:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->manage_stock ? 'Yes' : 'No' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Stock Status:</span>
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $product->stock_quantity <= $product->low_stock_threshold ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                        {{ $product->stock_quantity <= $product->low_stock_threshold ? 'Low Stock' : 'In Stock' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Days Until Stockout:</span>
                                    <span class="font-medium text-black dark:text-white">
                                        @php
                                            $dailySales = $product->orderItems()->whereHas('order', function($q) { $q->where('created_at', '>=', now()->subDays(30)); })->sum('quantity') / 30;
                                            $daysUntilStockout = $dailySales > 0 ? floor($product->stock_quantity / $dailySales) : 'N/A';
                                        @endphp
                                        {{ $daysUntilStockout }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Chart -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Stock Level Trend</h3>
                        <div class="h-64 rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                            <canvas id="stockChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Movements Tab -->
            <div id="movements-tab" class="tab-content hidden">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black dark:text-white">Stock Movement History</h3>
                    <div class="flex items-center gap-3">
                        <select class="rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                            <option value="all">All Types</option>
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                            <option value="adjustment">Adjustment</option>
                        </select>
                        <input type="date" class="rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-stroke dark:border-strokedark">
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Date</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Type</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Quantity</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Reason</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">User</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-stroke dark:border-strokedark">
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $product->created_at->format('M d, Y H:i') }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Initial Stock
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">+{{ $product->stock_quantity }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Product created</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">System</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">-</td>
                            </tr>
                            <!-- Sample movement entries -->
                            <tr class="border-b border-stroke dark:border-strokedark">
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ now()->subDays(5)->format('M d, Y H:i') }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                        Sale
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">-2</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Order #ORD-12345</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">System</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">-</td>
                            </tr>
                            <tr class="border-b border-stroke dark:border-strokedark">
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ now()->subDays(10)->format('M d, Y H:i') }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        Adjustment
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">+5</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Stock correction</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Admin User</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Found additional inventory</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Analytics Tab -->
            <div id="analytics-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="rounded-lg border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-gray-800">
                        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Sales Performance</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Units Sold:</span>
                                <span class="font-medium text-black dark:text-white">{{ $product->orderItems()->sum('quantity') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Last 30 Days:</span>
                                <span class="font-medium text-black dark:text-white">{{ $product->orderItems()->whereHas('order', function($q) { $q->where('created_at', '>=', now()->subDays(30)); })->sum('quantity') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Average Daily Sales:</span>
                                <span class="font-medium text-black dark:text-white">
                                    @php
                                        $dailySales = $product->orderItems()->whereHas('order', function($q) { $q->where('created_at', '>=', now()->subDays(30)); })->sum('quantity') / 30;
                                    @endphp
                                    {{ number_format($dailySales, 1) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Revenue:</span>
                                <span class="font-medium text-black dark:text-white">₱{{ number_format($product->orderItems()->sum(DB::raw('quantity * unit_price')), 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-gray-800">
                        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Inventory Analysis</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Stock Turnover Rate:</span>
                                <span class="font-medium text-black dark:text-white">
                                    @php
                                        $turnoverRate = $product->orderItems()->sum('quantity') / max($product->stock_quantity, 1);
                                    @endphp
                                    {{ number_format($turnoverRate, 2) }}x
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Stock Value:</span>
                                <span class="font-medium text-black dark:text-white">₱{{ number_format($product->stock_quantity * $product->price, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Reorder Point:</span>
                                <span class="font-medium text-black dark:text-white">{{ $product->low_stock_threshold }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Suggested Reorder Qty:</span>
                                <span class="font-medium text-black dark:text-white">
                                    @php
                                        $suggestedReorder = max($product->low_stock_threshold * 2, 10);
                                    @endphp
                                    {{ $suggestedReorder }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Sales Trend (Last 30 Days)</h3>
                    <div class="h-64 rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                        <canvas id="salesChart"></canvas>
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
        button.classList.remove('active', 'border-primary', 'text-primary');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
    activeButton.classList.add('active', 'border-primary', 'text-primary');
    activeButton.classList.remove('border-transparent', 'text-gray-500');
}

// Stock Chart
const stockCtx = document.getElementById('stockChart').getContext('2d');
new Chart(stockCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Stock Level',
            data: [50, 45, 40, 35, 30, {{ $product->stock_quantity }}],
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Sales Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'bar',
    data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
            label: 'Units Sold',
            data: [3, 5, 2, 4],
            backgroundColor: '#10b981',
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
@endsection
