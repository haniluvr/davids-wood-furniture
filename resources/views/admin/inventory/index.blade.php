@extends('admin.layouts.app')

@section('title', 'Inventory Management')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="mx-auto max-w-screen-2xl">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-stone-900">Inventory Management</h1>
                    <p class="mt-1 text-sm text-stone-600">Monitor and manage your product stock levels and inventory</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="openExportModal()" class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export
                    </button>
                    <a href="{{ admin_route('inventory.movements') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        View Movements
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-screen-2xl py-6">

    <!-- Stats Cards -->
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Total Products -->
        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Products</p>
                    <p class="text-2xl font-bold text-black dark:text-white">{{ number_format($stats['total_products']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                    <i data-lucide="package" class="h-6 w-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Low Stock</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['low_stock_products']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <i data-lucide="alert-triangle" class="h-6 w-6 text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
            @if($stats['low_stock_products'] > 0)
            <div class="mt-3">
                <a href="{{ admin_route('inventory.low-stock') }}" class="text-xs font-medium text-yellow-600 hover:text-yellow-700 dark:text-yellow-400">
                    View Details →
                </a>
            </div>
            @endif
        </div>

        <!-- Out of Stock Products -->
        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Out of Stock</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['out_of_stock_products']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                    <i data-lucide="x-circle" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <!-- Total Stock Value -->
        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Stock Value</p>
                    <p class="text-2xl font-bold text-black dark:text-white">₱{{ number_format($stats['total_stock_value'], 2) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                    <i data-lucide="dollar-sign" class="h-6 w-6 text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Filters</h3>
        <form method="GET" class="flex flex-wrap items-end gap-4 justify-between">
            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
            </div>
            
            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <select name="category" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    <option value="all">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Stock Status</label>
                <select name="stock_status" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    <option value="all">All Stock Status</option>
                    <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-opacity-90 whitespace-nowrap">
                    Apply Filters
                </button>
                <a href="{{ admin_route('inventory.index') }}" class="inline-flex items-center justify-center rounded-lg border border-stroke px-4 py-2.5 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <!-- Inventory Table -->
        <div class="xl:col-span-2">
            <div class="rounded-xl border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
                <div class="flex items-center justify-between border-b border-stroke p-6 dark:border-strokedark">
                    <h3 class="text-lg font-semibold text-black dark:text-white">Product Inventory</h3>
                    <div class="flex items-center gap-3">
                        <a href="{{ admin_route('inventory.movements') }}" class="inline-flex items-center gap-2 rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">
                            <i data-lucide="activity" class="h-4 w-4"></i>
                            View Movements
                        </a>
                        <button onclick="openExportModal()" class="inline-flex items-center gap-2 rounded-lg bg-primary px-3 py-2 text-sm text-white hover:bg-opacity-90">
                            <i data-lucide="download" class="h-4 w-4"></i>
                            Export
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-stroke dark:border-strokedark">
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Product</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">SKU</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Category</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Stock</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Status</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Value</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr class="border-b border-stroke dark:border-strokedark hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($product->image)
                                        <div class="h-10 w-10 rounded overflow-hidden">
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                        </div>
                                        @else
                                        <div class="h-10 w-10 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <i data-lucide="package" class="w-5 h-5 text-gray-400"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-black dark:text-white">{{ $product->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $product->sku ?: 'N/A' }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $product->category->name ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-sm font-medium text-black dark:text-white">{{ $product->stock_quantity }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $product->stock_status_badge_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm font-medium text-black dark:text-white">
                                    ₱{{ number_format($product->stock_quantity * $product->price, 2) }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2" x-data="{ dropdownOpen: false }">
                                        <button @click="dropdownOpen = !dropdownOpen" class="text-gray-600 hover:text-primary dark:text-gray-400">
                                            <i data-lucide="more-horizontal" class="h-4 w-4"></i>
                                        </button>
                                        
                                        <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" class="absolute right-0 mt-2 z-40 w-40 space-y-1 rounded-lg border border-stroke bg-white p-1.5 shadow-lg dark:border-strokedark dark:bg-boxdark" x-cloak>
                                            <a href="{{ admin_route('inventory.show', $product) }}" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                                View History
                                            </a>
                                            <a href="{{ admin_route('inventory.adjust', $product) }}" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                                Adjust Stock
                                            </a>
                                            <button onclick="openQuickAdjustModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock_quantity }})" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4">
                                                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                                Quick Add
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center">
                                    <i data-lucide="package" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                                    <p class="text-gray-500 dark:text-gray-400">No products found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="flex items-center justify-between border-t border-stroke p-6 dark:border-strokedark">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Movements Sidebar -->
        <div class="xl:col-span-1">
            <div class="rounded-xl border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
                <div class="border-b border-stroke p-6 dark:border-strokedark">
                    <h3 class="text-lg font-semibold text-black dark:text-white">
                        Recent Movements
                    </h3>
                </div>
                <div class="p-6">
                    @forelse($stats['recent_movements'] as $movement)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-stroke dark:border-strokedark' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $movement->type === 'in' ? 'bg-green-100 dark:bg-green-900' : ($movement->type === 'out' ? 'bg-red-100 dark:bg-red-900' : 'bg-blue-100 dark:bg-blue-900') }}">
                                @if($movement->type === 'in')
                                <i data-lucide="plus" class="w-4 h-4 text-green-600 dark:text-green-300"></i>
                                @elseif($movement->type === 'out')
                                <i data-lucide="minus" class="w-4 h-4 text-red-600 dark:text-red-300"></i>
                                @else
                                <i data-lucide="edit" class="w-4 h-4 text-blue-600 dark:text-blue-300"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-black dark:text-white">{{ $movement->product->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $movement->reason }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium {{ $movement->type_color }}">
                                {{ $movement->formatted_quantity }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $movement->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center">
                        <i data-lucide="activity" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">No recent movements</p>
                    </div>
                    @endforelse
                    
                    @if($stats['recent_movements']->count() > 0)
                    <div class="mt-4 pt-4 border-t border-stroke dark:border-strokedark">
                        <a href="{{ admin_route('inventory.movements') }}" class="inline-flex items-center justify-center rounded-lg border border-primary px-4 py-2 text-center text-sm font-medium text-primary hover:bg-opacity-90 w-full">
                            <i data-lucide="activity" class="w-4 h-4 mr-2"></i>
                            View All Movements
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Adjust Modal -->
    <div id="quickAdjustModal" class="fixed inset-0 z-99999 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md rounded-xl border border-stroke bg-white p-6 shadow-lg dark:border-strokedark dark:bg-boxdark">
            <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Quick Stock Adjustment</h3>
            
            <form id="quickAdjustForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Product</label>
                    <p id="productName" class="font-medium text-black dark:text-white"></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Current Stock: <span id="currentStock"></span></p>
                </div>
                
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity to Add</label>
                    <input type="number" name="quantity" min="1" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                </div>
                
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Reason</label>
                    <select name="reason" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                        <option value="purchase">Purchase/Restock</option>
                        <option value="return">Customer Return</option>
                        <option value="found">Found Inventory</option>
                        <option value="correction">Stock Correction</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                    <textarea name="notes" rows="2" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input"></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-opacity-90">
                        Add Stock
                    </button>
                    <button type="button" onclick="closeQuickAdjustModal()" class="flex-1 rounded-lg border border-stroke px-4 py-2 text-sm font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-gray-800">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Export Modal -->
    <div id="exportModal" class="fixed inset-0 z-99999 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md rounded-xl border border-stroke bg-white p-6 shadow-lg dark:border-strokedark dark:bg-boxdark">
            <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Export Inventory</h3>
            
            <form action="{{ admin_route('inventory.export') }}" method="GET">
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Export Format</label>
                    <select name="format" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                        <option value="csv">CSV</option>
                        <option value="excel" disabled>Excel (Coming Soon)</option>
                        <option value="pdf" disabled>PDF (Coming Soon)</option>
                    </select>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-opacity-90">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Export
                    </button>
                    <button type="button" onclick="closeExportModal()" class="flex-1 rounded-lg border border-stroke px-4 py-2 text-sm font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-gray-800">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openQuickAdjustModal(productId, productName, currentStock) {
        document.getElementById('productName').textContent = productName;
        document.getElementById('currentStock').textContent = currentStock;
        document.getElementById('quickAdjustForm').action = `/admin/products/${productId}/add-stock`;
        document.getElementById('quickAdjustModal').classList.remove('hidden');
        document.getElementById('quickAdjustModal').classList.add('flex');
    }
    
    function closeQuickAdjustModal() {
        document.getElementById('quickAdjustModal').classList.add('hidden');
        document.getElementById('quickAdjustModal').classList.remove('flex');
    }
    
    function openExportModal() {
        document.getElementById('exportModal').classList.remove('hidden');
        document.getElementById('exportModal').classList.add('flex');
    }
    
    function closeExportModal() {
        document.getElementById('exportModal').classList.add('hidden');
        document.getElementById('exportModal').classList.remove('flex');
    }
    
    lucide.createIcons();
</script>
@endpush
