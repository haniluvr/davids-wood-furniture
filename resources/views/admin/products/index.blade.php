@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex justify-between items-center py-6">
        <div>
                <h1 class="text-2xl font-bold text-stone-900">Products</h1>
                <p class="mt-1 text-sm text-stone-600">Manage your product inventory and catalog</p>
        </div>
    <div class="flex items-center gap-3">
                <button id="bulk-actions-btn" class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" disabled>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                    </svg>
            Bulk Actions
        </button>
                <a href="{{ admin_route('products.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
            Add Product
        </a>
    </div>
</div>
    </div>

    <!-- Stats Cards -->
    <div class="py-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-5">
    <!-- Total Products -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Total Products</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['total_products']) }}</p>
                </div>
                </div>
    </div>

    <!-- Active Products -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Active</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['active_products']) }}</p>
                </div>
                </div>
    </div>

    <!-- Low Stock -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Low Stock</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['low_stock_products']) }}</p>
                </div>
                </div>
    </div>

    <!-- Out of Stock -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Out of Stock</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['out_of_stock_products']) }}</p>
                </div>
                </div>
    </div>

    <!-- Inventory Value -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Inventory Value</p>
                        <p class="text-2xl font-semibold text-stone-900">₱{{ number_format($stats['total_inventory_value'], 2) }}</p>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="pb-6">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
            <form method="GET" action="{{ admin_route('products.index') }}" class="flex flex-wrap items-end gap-4 justify-between">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-stone-700 mb-2">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Search products..."
                           class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="category" class="block text-sm font-medium text-stone-700 mb-2">Category</label>
                    <select id="category" name="category" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-stone-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 whitespace-nowrap">
                        Filter
                    </button>
                    <a href="{{ admin_route('products.index') }}" class="inline-flex items-center justify-center rounded-lg border border-stone-300 px-4 py-2.5 text-sm hover:bg-stone-50">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products List -->
    <div class="pb-8">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
    <!-- Select All Checkbox -->
            <div class="mb-6 flex items-center gap-3">
        <label class="flex items-center gap-2">
                    <input type="checkbox" id="selectAll" class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="text-sm font-medium text-stone-700">Select All Products</span>
        </label>
    </div>

            @if($products->count() > 0)
    <!-- Products Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $product)
                        <div class="group relative overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm transition-all duration-300 hover:shadow-md">
                        <!-- Product Image -->
            <div class="relative h-48 overflow-hidden">
                @if($product->images && count($product->images) > 0)
                    <img src="{{ Storage::url($product->images[0]) }}?v={{ time() }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                                    <div class="flex h-full w-full items-center justify-center bg-stone-100">
                                        <svg class="w-12 h-12 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                </div>
                            @endif
                
                <!-- Status Badge -->
                <div class="absolute top-3 left-3">
                    @if($product->stock_quantity == 0)
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                            Out of Stock
                        </span>
                    @elseif($product->stock_quantity <= $product->low_stock_threshold)
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                            Low Stock
                        </span>
                    @elseif($product->is_active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-medium text-stone-800">
                                            Inactive
                                        </span>
                                    @endif
                                </div>

                <!-- Checkbox for bulk actions -->
                <div class="absolute top-3 right-3">
                                    <input type="checkbox" class="product-checkbox rounded border-stone-300 text-emerald-600 focus:ring-emerald-500" value="{{ $product->id }}">
                                </div>
                            </div>

            <!-- Product Info -->
            <div class="p-4">
                <div class="mb-2">
                                    <h3 class="text-lg font-semibold text-stone-900 truncate">{{ $product->name }}</h3>
                                    <p class="text-sm text-stone-500">{{ $product->sku }}</p>
                            </div>

                <div class="mb-3">
                                    <p class="text-2xl font-bold text-stone-900">₱{{ number_format($product->price, 2) }}</p>
                                    <p class="text-sm text-stone-600">Stock: {{ $product->stock_quantity }}</p>
                                </div>

                @if($product->category)
                <div class="mb-3">
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                        {{ $product->category->name }}
                                    </span>
                </div>
                                @endif

                            <!-- Actions -->
                <div class="flex items-center gap-2">
                                    <a href="{{ admin_route('products.show', $product) }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    View
                                </a>
                                    <a href="{{ admin_route('products.edit', $product) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 hover:bg-emerald-100 hover:text-emerald-600 transition-colors duration-200" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button onclick="restockProduct({{ $product->id }})" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 hover:bg-blue-100 hover:text-blue-600 transition-colors duration-200" title="Restock">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
    @if($products->hasPages())
                <div class="mt-6">
                    {{ $products->links() }}
    </div>
    @endif
            @else
                <div class="p-8 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <p class="text-stone-500">No products found</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div id="bulk-actions-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900">Bulk Actions</h3>
                <p class="text-sm text-stone-600">Select an action to apply to selected products</p>
            </div>
            <div class="space-y-3">
                <button onclick="bulkUpdateStatus('active')" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Activate Selected
                </button>
                <button onclick="showBulkDeactivateModal(document.querySelectorAll('.product-checkbox:checked').length)" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-600 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Deactivate Selected
                </button>
                <button onclick="showBulkRestockModal(document.querySelectorAll('.product-checkbox:checked').length)" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Restock Selected
                </button>
            </div>
            <div class="mt-4 flex gap-3">
                <button onclick="closeBulkActionsModal()" class="flex-1 rounded-lg border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50">
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
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900">Restock Product</h3>
                <p class="text-sm text-stone-600">Add inventory to the selected product</p>
            </div>
            <form id="restock-form">
                <div class="mb-4">
                    <label for="restock-quantity" class="block text-sm font-medium text-stone-700 mb-2">Quantity to Add</label>
                    <input type="number" id="restock-quantity" min="1" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Enter quantity">
                </div>
                <div class="mb-4">
                    <label for="restock-notes" class="block text-sm font-medium text-stone-700 mb-2">Notes (Optional)</label>
                    <textarea id="restock-notes" rows="3" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Add notes about this restock..."></textarea>
                    </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeRestockModal()" class="flex-1 rounded-lg border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
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
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900">Confirm Action</h3>
                <p id="confirmation-message" class="text-sm text-stone-600"></p>
            </div>
            <div class="flex gap-3">
                <button id="confirmation-cancel" class="flex-1 rounded-lg border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50">
                    Cancel
                </button>
                <button id="confirmation-confirm" class="flex-1 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
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
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900">Notification</h3>
                <p id="alert-message" class="text-sm text-stone-600"></p>
            </div>
            <div class="flex justify-end">
                <button id="alert-ok" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
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
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-stone-900">Deactivate Products</h3>
                </div>
                <p id="bulk-deactivate-message" class="text-sm text-stone-600"></p>
            </div>
            <div class="flex gap-3">
                <button id="bulk-deactivate-cancel" class="flex-1 rounded-lg border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50">
                    Cancel
                </button>
                <button id="bulk-deactivate-confirm" class="flex-1 rounded-lg bg-yellow-600 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-700">
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
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-stone-900">Bulk Restock</h3>
                </div>
                <p id="bulk-restock-message" class="text-sm text-stone-600 mb-4"></p>
                <div class="mb-4">
                    <label for="bulk-restock-quantity" class="block text-sm font-medium text-stone-700 mb-2">Quantity to Add</label>
                    <input type="number" id="bulk-restock-quantity" min="1" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Enter quantity">
                </div>
                <div class="mb-4">
                    <label for="bulk-restock-notes" class="block text-sm font-medium text-stone-700 mb-2">Notes (Optional)</label>
                    <textarea id="bulk-restock-notes" rows="3" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Add notes about this bulk restock..."></textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button id="bulk-restock-cancel" class="flex-1 rounded-lg border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50">
                    Cancel
                </button>
                <button id="bulk-restock-confirm" class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Restock
                </button>
            </div>
        </div>
    </div>
</div>

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
    const selectAllCheckbox = document.getElementById('selectAll');
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

function deleteProduct(productId) {
    showConfirmationModal('Are you sure you want to delete this product? This action cannot be undone.', function() {
        // Implement delete functionality here
        console.log('Delete product:', productId);
    });
}

function restockProductSubmit(productId, quantity, notes) {
    fetch(`{{ admin_route('products.restock', ['product' => ':product']) }}`.replace(':product', productId), {
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
        showAlertModal('An error occurred while processing the request.');
    });
}

function bulkUpdateStatus(status) {
    const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
    const productIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    showConfirmationModal(`Are you sure you want to ${status} ${productIds.length} products?`, function() {
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
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeBulkActionsModal();
            location.reload();
        } else {
            showAlertModal('Error: ' + data.message);
        }
    })
    .catch(error => {
        showAlertModal('An error occurred while processing the request: ' + error.message);
    });
    });
}

function bulkRestockWithQuantity(quantity, notes = 'Bulk restock') {
    const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
    const productIds = Array.from(checkedBoxes).map(cb => cb.value);
    
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
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showAlertModal('Error: ' + data.message);
        }
    })
    .catch(error => {
        showAlertModal('An error occurred while processing the request: ' + error.message);
    });
}
</script>
@endpush
@endsection