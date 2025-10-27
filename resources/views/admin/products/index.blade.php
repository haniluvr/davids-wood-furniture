@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">
            Products
        </h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Manage your product inventory and catalog.
        </p>
        </div>

    <div class="flex items-center gap-3">
        <button id="bulk-actions-btn" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800" disabled>
            <i data-lucide="settings" class="w-4 h-4"></i>
            Bulk Actions
        </button>
        <a href="{{ admin_route('products.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Product
        </a>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards Start -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-5 mb-8">
    <!-- Total Products -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 dark:from-blue-900/20 dark:to-blue-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                    <i data-lucide="package" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['total_products']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Products</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10"></div>
    </div>

    <!-- Active Products -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-lg shadow-emerald-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/20 dark:from-emerald-900/20 dark:to-emerald-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['active_products']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Active Products</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10"></div>
    </div>

    <!-- Low Stock -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-yellow-50 to-yellow-100/50 p-6 shadow-lg shadow-yellow-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-yellow-500/20 dark:from-yellow-900/20 dark:to-yellow-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-500 shadow-lg">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['low_stock_products']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Low Stock</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-yellow-500/10"></div>
    </div>

    <!-- Out of Stock -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-50 to-red-100/50 p-6 shadow-lg shadow-red-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-red-500/20 dark:from-red-900/20 dark:to-red-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-500 shadow-lg">
                    <i data-lucide="x-circle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['out_of_stock_products']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Out of Stock</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-red-500/10"></div>
    </div>

    <!-- Inventory Value -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100/50 p-6 shadow-lg shadow-purple-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 dark:from-purple-900/20 dark:to-purple-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 shadow-lg">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        ₱{{ number_format($stats['total_inventory_value'], 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Inventory Value</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
    </div>
</div>
<!-- Stats Cards End -->

<!-- Products Management -->
<div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-stone-900 dark:text-white">Product Catalog</h3>
            <p class="text-sm text-stone-600 dark:text-gray-400">Manage your product inventory and settings</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ admin_route('products.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="mb-6 rounded-xl border border-stone-200/50 bg-stone-50/50 p-4 dark:border-strokedark/50 dark:bg-stone-800/20">
            <form method="GET" action="{{ admin_route('products.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
                    <!-- Search -->
                    <div>
                    <label for="search" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400">
                    </div>

                    <!-- Category Filter -->
                    <div>
                    <label for="category" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Category</label>
                    <select name="category" id="category" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                    <label for="status" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Status</label>
                    <select name="status" id="status" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>

                <!-- Material Filter -->
                    <div>
                    <label for="material" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Material</label>
                    <select name="material" id="material" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                        <option value="all">All Materials</option>
                        @foreach($materials as $material)
                            <option value="{{ $material }}" {{ request('material') == $material ? 'selected' : '' }}>
                                {{ $material }}
                            </option>
                        @endforeach
                        </select>
                    </div>

                <!-- Price Range -->
                <div>
                    <label for="price_min" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Min Price (₱)</label>
                    <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}" placeholder="0" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400">
                </div>

                <div>
                    <label for="price_max" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Max Price (₱)</label>
                    <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}" placeholder="10000" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-primary/90">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Apply Filters
                </button>
                <a href="{{ admin_route('products.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    <i data-lucide="x" class="w-4 h-4"></i>
                        Clear
                    </a>
                </div>
            </form>
    </div>

    <!-- Select All Checkbox -->
    <div class="mb-4 flex items-center gap-3">
        <label class="flex items-center gap-2">
            <input type="checkbox" id="select-all-products" class="rounded border-stone-300 text-primary focus:ring-primary">
            <span class="text-sm font-medium text-stone-700 dark:text-stone-300">Select All Products</span>
        </label>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($products as $product)
        <div class="group relative overflow-hidden rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm shadow-lg shadow-stone-500/5 transition-all duration-300 hover:shadow-xl hover:shadow-stone-500/10 dark:border-strokedark/50 dark:bg-boxdark/80">
                        <!-- Product Image -->
            <div class="relative h-48 overflow-hidden">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                    <div class="flex h-full w-full items-center justify-center bg-stone-100 dark:bg-stone-800">
                        <i data-lucide="image" class="w-12 h-12 text-stone-400"></i>
                                </div>
                            @endif
                
                <!-- Status Badge -->
                <div class="absolute top-3 left-3">
                    @if($product->stock_quantity == 0)
                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-400">
                            Out of Stock
                        </span>
                    @elseif($product->stock_quantity <= $product->low_stock_threshold)
                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                            Low Stock
                        </span>
                    @elseif($product->is_active)
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Active
                                        </span>
                                    @else
                        <span class="inline-flex items-center rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-medium text-stone-800 dark:bg-stone-700 dark:text-stone-200">
                                            Inactive
                                        </span>
                                    @endif
                                </div>

                <!-- Checkbox for bulk actions -->
                <div class="absolute top-3 right-3">
                    <input type="checkbox" class="product-checkbox rounded border-stone-300 text-primary focus:ring-primary" value="{{ $product->id }}">
                                </div>
                            </div>

            <!-- Product Info -->
            <div class="p-4">
                <div class="mb-2">
                    <h3 class="text-lg font-semibold text-stone-900 dark:text-white truncate">{{ $product->name }}</h3>
                    <p class="text-sm text-stone-600 dark:text-gray-400">{{ $product->sku }}</p>
                            </div>

                <div class="mb-3">
                    <p class="text-2xl font-bold text-stone-900 dark:text-white">₱{{ number_format($product->price, 2) }}</p>
                    <p class="text-sm text-stone-600 dark:text-gray-400">Stock: {{ $product->stock_quantity }}</p>
                                </div>

                @if($product->category)
                <div class="mb-3">
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                        {{ $product->category->name }}
                                    </span>
                </div>
                                @endif

                            <!-- Actions -->
                <div class="flex items-center gap-2">
                    <a href="{{ admin_route('products.show', $product) }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-3 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-primary/90">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    View
                                </a>
                    <a href="{{ admin_route('products.edit', $product) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-emerald-100 hover:text-emerald-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-400" title="Edit">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                    </a>
                    <button onclick="restockProduct({{ $product->id }})" class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-blue-100 hover:text-blue-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-blue-900/20 dark:hover:text-blue-400" title="Restock">
                        <i data-lucide="package-plus" class="w-4 h-4"></i>
                                    </button>
                            </div>
                        </div>
                    </div>
        @empty
        <div class="col-span-full p-8 text-center">
            <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4 dark:bg-stone-800">
                <i data-lucide="package" class="w-6 h-6 text-stone-400"></i>
            </div>
            <p class="text-stone-500 dark:text-gray-400">No products found</p>
        </div>
        @endforelse
                </div>

                <!-- Pagination -->
    @if($products->hasPages())
                <div class="mt-6">
                    {{ $products->links() }}
    </div>
    @endif
</div>

<!-- Bulk Actions Modal -->
<div id="bulk-actions-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Bulk Actions</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Select an action to apply to selected products</p>
            </div>
            <div class="space-y-3">
                <button onclick="bulkUpdateStatus('active')" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 hover:bg-emerald-700">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    Activate Selected
                </button>
                <button onclick="showBulkDeactivateModal(document.querySelectorAll('.product-checkbox:checked').length)" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-yellow-600 px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 hover:bg-yellow-700">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Deactivate Selected
                </button>
                <button onclick="showBulkRestockModal(document.querySelectorAll('.product-checkbox:checked').length)" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 hover:bg-blue-700">
                    <i data-lucide="package-plus" class="w-4 h-4"></i>
                    Restock Selected
                </button>
            </div>
            <div class="mt-4 flex gap-3">
                <button onclick="closeBulkActionsModal()" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Restock Modal -->
<div id="restock-modal" class="fixed inset-0 z-[9998] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Restock Product</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Add inventory to the selected product</p>
            </div>
            <form id="restock-form">
                <div class="mb-4">
                    <label for="restock-quantity" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Quantity to Add</label>
                    <input type="number" id="restock-quantity" min="1" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400" placeholder="Enter quantity">
                </div>
                <div class="mb-4">
                    <label for="restock-notes" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Notes (Optional)</label>
                    <textarea id="restock-notes" rows="3" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400" placeholder="Add notes about this restock..."></textarea>
                    </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeRestockModal()" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-primary/90">
                        Restock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 z-[10000] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Confirm Action</h3>
                <p id="confirmation-message" class="text-sm text-stone-600 dark:text-gray-400"></p>
            </div>
            <div class="flex gap-3">
                <button id="confirmation-cancel" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button id="confirmation-confirm" class="flex-1 rounded-xl bg-red-600 px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-red-700">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Modal -->
<div id="alert-modal" class="fixed inset-0 z-[10001] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Notification</h3>
                <p id="alert-message" class="text-sm text-stone-600 dark:text-gray-400"></p>
            </div>
            <div class="flex justify-end">
                <button id="alert-ok" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-blue-700">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Deactivate Confirmation Modal -->
<div id="bulk-deactivate-modal" class="fixed inset-0 z-[10002] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30">
                        <i data-lucide="alert-triangle" class="h-5 w-5 text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Deactivate Products</h3>
                </div>
                <p id="bulk-deactivate-message" class="text-sm text-stone-600 dark:text-gray-400"></p>
            </div>
            <div class="flex gap-3">
                <button id="bulk-deactivate-cancel" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button id="bulk-deactivate-confirm" class="flex-1 rounded-xl bg-yellow-600 px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-yellow-700">
                    Deactivate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Restock Modal -->
<div id="bulk-restock-modal" class="fixed inset-0 z-[10003] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                        <i data-lucide="package-plus" class="h-5 w-5 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Bulk Restock</h3>
                </div>
                <p id="bulk-restock-message" class="text-sm text-stone-600 dark:text-gray-400 mb-4"></p>
                <div class="mb-4">
                    <label for="bulk-restock-quantity" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Quantity to Add</label>
                    <input type="number" id="bulk-restock-quantity" min="1" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400" placeholder="Enter quantity">
                </div>
                <div class="mb-4">
                    <label for="bulk-restock-notes" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Notes (Optional)</label>
                    <textarea id="bulk-restock-notes" rows="3" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400" placeholder="Add notes about this bulk restock..."></textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button id="bulk-restock-cancel" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button id="bulk-restock-confirm" class="flex-1 rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-blue-700">
                    Restock
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Modal backdrop and body scroll prevention */
.modal-open {
    overflow: hidden;
}

/* Ensure modals are always on top */
#confirmation-modal,
#alert-modal,
#bulk-actions-modal,
#restock-modal,
#bulk-deactivate-modal,
#bulk-restock-modal {
    backdrop-filter: blur(4px);
}

/* Smooth transitions for modals */
.modal-transition {
    transition: all 0.3s ease-in-out;
}
</style>
@endpush

@push('scripts')
<script>
// Custom modal functions
function showConfirmationModal(message, onConfirm) {
    document.getElementById('confirmation-message').textContent = message;
    document.getElementById('confirmation-modal').classList.remove('hidden');
    document.body.classList.add('modal-open');
    
    // Remove existing event listeners
    const confirmBtn = document.getElementById('confirmation-confirm');
    const cancelBtn = document.getElementById('confirmation-cancel');
    
    // Clone and replace to remove event listeners
    const newConfirmBtn = confirmBtn.cloneNode(true);
    const newCancelBtn = cancelBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
    
    // Add new event listeners
    newConfirmBtn.addEventListener('click', function() {
        document.getElementById('confirmation-modal').classList.add('hidden');
        document.body.classList.remove('modal-open');
        if (onConfirm) onConfirm();
    });
    
    newCancelBtn.addEventListener('click', function() {
        document.getElementById('confirmation-modal').classList.add('hidden');
        document.body.classList.remove('modal-open');
    });
}

function showAlertModal(message) {
    document.getElementById('alert-message').textContent = message;
    document.getElementById('alert-modal').classList.remove('hidden');
    document.body.classList.add('modal-open');
    
    // Remove existing event listeners
    const okBtn = document.getElementById('alert-ok');
    const newOkBtn = okBtn.cloneNode(true);
    okBtn.parentNode.replaceChild(newOkBtn, okBtn);
    
    // Add new event listener
    newOkBtn.addEventListener('click', function() {
        document.getElementById('alert-modal').classList.add('hidden');
        document.body.classList.remove('modal-open');
    });
}

// Bulk deactivate modal functions
function showBulkDeactivateModal(productCount) {
    if (productCount === 0) {
        showAlertModal('Please select at least one product to deactivate.');
        return;
    }
    
    document.getElementById('bulk-deactivate-message').textContent = `Are you sure you want to deactivate ${productCount} products? This will make them unavailable for purchase.`;
    document.getElementById('bulk-deactivate-modal').classList.remove('hidden');
    document.body.classList.add('modal-open');
    
    // Remove existing event listeners
    const confirmBtn = document.getElementById('bulk-deactivate-confirm');
    const cancelBtn = document.getElementById('bulk-deactivate-cancel');
    
    const newConfirmBtn = confirmBtn.cloneNode(true);
    const newCancelBtn = cancelBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
    
    // Add new event listeners
    newConfirmBtn.addEventListener('click', function() {
        document.getElementById('bulk-deactivate-modal').classList.add('hidden');
        document.body.classList.remove('modal-open');
        closeBulkActionsModal();
        bulkUpdateStatus('inactive');
    });
    
    newCancelBtn.addEventListener('click', function() {
        document.getElementById('bulk-deactivate-modal').classList.add('hidden');
        document.body.classList.remove('modal-open');
    });
}

// Bulk restock modal functions
function showBulkRestockModal(productCount) {
    if (productCount === 0) {
        showAlertModal('Please select at least one product to restock.');
        return;
    }
    
    document.getElementById('bulk-restock-message').textContent = `Add inventory to ${productCount} selected products.`;
    document.getElementById('bulk-restock-modal').classList.remove('hidden');
    document.body.classList.add('modal-open');
    
    // Clear previous values
    document.getElementById('bulk-restock-quantity').value = '';
    document.getElementById('bulk-restock-notes').value = '';
    
    // Focus on quantity input
    setTimeout(() => {
        document.getElementById('bulk-restock-quantity').focus();
    }, 100);
    
    // Remove existing event listeners
    const confirmBtn = document.getElementById('bulk-restock-confirm');
    const cancelBtn = document.getElementById('bulk-restock-cancel');
    
    const newConfirmBtn = confirmBtn.cloneNode(true);
    const newCancelBtn = cancelBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
    
    // Add new event listeners
    newConfirmBtn.addEventListener('click', function() {
        const quantity = document.getElementById('bulk-restock-quantity').value;
        const notes = document.getElementById('bulk-restock-notes').value;
        
        if (!quantity || quantity <= 0) {
            showAlertModal('Please enter a valid quantity.');
            return;
        }
        
        document.getElementById('bulk-restock-modal').classList.add('hidden');
        document.body.classList.remove('modal-open');
        closeBulkActionsModal();
        bulkRestockWithQuantity(quantity, notes);
    });
    
    newCancelBtn.addEventListener('click', function() {
        document.getElementById('bulk-restock-modal').classList.add('hidden');
        document.body.classList.remove('modal-open');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-products');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkActionsBtn = document.getElementById('bulk-actions-btn');

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionsButton();
        });
    }

    // Individual checkbox change
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActionsButton();
            updateSelectAllState();
        });
    });

    // Bulk actions button
    bulkActionsBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        if (checkedBoxes.length > 0) {
            document.getElementById('bulk-actions-modal').classList.remove('hidden');
            document.body.classList.add('modal-open');
        }
    });

    // Restock form
    document.getElementById('restock-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const quantity = document.getElementById('restock-quantity').value;
        const notes = document.getElementById('restock-notes').value;
        const productId = this.dataset.productId;
        
        restockProductSubmit(productId, quantity, notes);
    });

    function updateBulkActionsButton() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        bulkActionsBtn.disabled = checkedBoxes.length === 0;
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const totalBoxes = productCheckboxes.length;
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedBoxes.length === totalBoxes;
            selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
        }
    }
});

function restockProduct(productId) {
    document.getElementById('restock-form').dataset.productId = productId;
    document.getElementById('restock-modal').classList.remove('hidden');
    document.body.classList.add('modal-open');
}

function closeRestockModal() {
    document.getElementById('restock-modal').classList.add('hidden');
    document.getElementById('restock-form').reset();
    document.body.classList.remove('modal-open');
}

function closeBulkActionsModal() {
    document.getElementById('bulk-actions-modal').classList.add('hidden');
    document.body.classList.remove('modal-open');
}

function restockProductSubmit(productId, quantity, notes) {
    fetch(`{{ admin_route('products.restock', ':product') }}`.replace(':product', productId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: quantity,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeRestockModal();
            location.reload();
        } else {
            showAlertModal('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlertModal('An error occurred while processing the request.');
    });
}

function bulkUpdateStatus(status) {
    const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
    const productIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    showConfirmationModal(`Are you sure you want to ${status} ${productIds.length} products?`, function() {
    console.log('Sending bulk update request:', {
        product_ids: productIds,
        status: status,
        csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    });
    
    fetch('{{ admin_route("products.bulk-update-status") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_ids: productIds,
            status: status
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            closeBulkActionsModal();
            location.reload();
        } else {
            showAlertModal('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Detailed error:', error);
        showAlertModal('An error occurred while processing the request: ' + error.message);
    });
    });
}

function bulkRestock() {
    // This function is now replaced by showBulkRestockModal
    const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
    const productCount = checkedBoxes.length;
    showBulkRestockModal(productCount);
}

function bulkRestockWithQuantity(quantity, notes = 'Bulk restock') {
    const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
    const productIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    console.log('Sending bulk restock request:', {
        product_ids: productIds,
        quantity: quantity,
        notes: notes,
        csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    });
    
    fetch('{{ admin_route("products.bulk-restock") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_ids: productIds,
            quantity: quantity,
            notes: notes
        })
    })
    .then(response => {
        console.log('Restock response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Restock response data:', data);
        if (data.success) {
            location.reload();
        } else {
            showAlertModal('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Restock detailed error:', error);
        showAlertModal('An error occurred while processing the request: ' + error.message);
    });
}
</script>
@endpush
@endsection