@extends('admin.layouts.app')

@section('title', 'Inventory Management')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Inventory Management
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li class="font-medium text-primary">Inventory</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards Start -->
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 mb-6">
    <!-- Total Products -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <i data-lucide="package" class="w-6 h-6 text-primary dark:text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['total_products']) }}
                </h4>
                <span class="text-sm font-medium">Total Products</span>
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-6 dark:bg-meta-4">
            <i data-lucide="alert-triangle" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['low_stock_products']) }}
                </h4>
                <span class="text-sm font-medium">Low Stock</span>
            </div>
            @if($stats['low_stock_products'] > 0)
            <a href="{{ admin_route('inventory.low-stock') }}" class="text-meta-6 hover:text-meta-6/80">
                <i data-lucide="external-link" class="w-4 h-4"></i>
            </a>
            @endif
        </div>
    </div>

    <!-- Out of Stock Products -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-1 dark:bg-meta-4">
            <i data-lucide="x-circle" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['out_of_stock_products']) }}
                </h4>
                <span class="text-sm font-medium">Out of Stock</span>
            </div>
        </div>
    </div>

    <!-- Total Stock Value -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-3 dark:bg-meta-4">
            <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    ${{ number_format($stats['total_stock_value'], 2) }}
                </h4>
                <span class="text-sm font-medium">Stock Value</span>
            </div>
        </div>
    </div>
</div>
<!-- Stats Cards End -->

<div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
    <!-- Inventory Table -->
    <div class="xl:col-span-2">
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="px-4 py-6 md:px-6 xl:px-7.5">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-xl font-semibold text-black dark:text-white">
                        Product Inventory
                    </h4>
                    <div class="flex gap-2">
                        <a href="{{ admin_route('inventory.movements') }}" class="inline-flex items-center justify-center rounded-md border border-primary px-4 py-2 text-center font-medium text-primary hover:bg-opacity-90">
                            <i data-lucide="activity" class="w-4 h-4 mr-2"></i>
                            View Movements
                        </a>
                        <button onclick="openExportModal()" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white hover:bg-opacity-90">
                            <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                            Export
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3 mb-6">
                    <form method="GET" class="contents">
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div>
                            <select name="category" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                                <option value="all">All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <select name="stock_status" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                                <option value="all">All Stock Status</option>
                                <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-3 flex gap-2">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-3 text-center font-medium text-white hover:bg-opacity-90">
                                <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                                Filter
                            </button>
                            <a href="{{ admin_route('inventory.index') }}" class="inline-flex items-center justify-center rounded-md border border-stroke px-4 py-3 text-center font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4">
                                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-6 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
                <div class="col-span-2 flex items-center">
                    <p class="font-medium">Product</p>
                </div>
                <div class="col-span-1 hidden items-center sm:flex">
                    <p class="font-medium">SKU</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="font-medium">Category</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="font-medium">Stock</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="font-medium">Status</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="font-medium">Value</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="font-medium">Actions</p>
                </div>
            </div>

            @forelse($products as $product)
            <div class="grid grid-cols-6 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
                <div class="col-span-2 flex items-center">
                    <div class="flex items-center gap-3">
                        @if($product->image)
                        <div class="h-12 w-12 rounded-md overflow-hidden">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        </div>
                        @else
                        <div class="h-12 w-12 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm text-black dark:text-white font-medium">{{ $product->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-span-1 hidden items-center sm:flex">
                    <p class="text-sm text-black dark:text-white">{{ $product->sku ?: 'N/A' }}</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="text-sm text-black dark:text-white">{{ $product->category->name ?? 'N/A' }}</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="text-sm text-black dark:text-white font-medium">{{ $product->stock_quantity }}</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $product->stock_status_badge_color }}">
                        {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                    </span>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="text-sm text-black dark:text-white font-medium">
                        ${{ number_format($product->stock_quantity * $product->price, 2) }}
                    </p>
                </div>
                <div class="col-span-1 flex items-center">
                    <div class="flex items-center space-x-3.5" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen" class="hover:text-primary">
                            <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                        </button>
                        
                        <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" class="absolute right-0 top-full z-40 w-40 space-y-1 rounded-sm border border-stroke bg-white p-1.5 shadow-default dark:border-strokedark dark:bg-boxdark" x-cloak>
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
                </div>
            </div>
            @empty
            <div class="px-4 py-8 text-center">
                <i data-lucide="package" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400">No products found.</p>
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

    <!-- Recent Movements Sidebar -->
    <div class="xl:col-span-1">
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">
                    Recent Movements
                </h3>
            </div>
            <div class="p-6.5">
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
                            <p class="text-xs text-gray-500">{{ $movement->reason }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium {{ $movement->type_color }}">
                            {{ $movement->formatted_quantity }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $movement->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 dark:text-gray-400 py-4">No recent movements</p>
                @endforelse
                
                @if($stats['recent_movements']->count() > 0)
                <div class="mt-4 pt-4 border-t border-stroke dark:border-strokedark">
                    <a href="{{ admin_route('inventory.movements') }}" class="inline-flex items-center justify-center rounded-md border border-primary px-4 py-2 text-center font-medium text-primary hover:bg-opacity-90 w-full">
                        <i data-lucide="activity" class="w-4 h-4 mr-2"></i>
                        View All Movements
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Adjust Modal -->
<div id="quickAdjustModal" class="fixed inset-0 z-99999 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="w-full max-w-md rounded-lg bg-white p-6 dark:bg-boxdark">
        <h3 class="mb-4 text-lg font-medium text-black dark:text-white">Quick Stock Adjustment</h3>
        
        <form id="quickAdjustForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="mb-2.5 block text-black dark:text-white">Product</label>
                <p id="productName" class="font-medium text-black dark:text-white"></p>
                <p class="text-sm text-gray-500">Current Stock: <span id="currentStock"></span></p>
            </div>
            
            <div class="mb-4">
                <label class="mb-2.5 block text-black dark:text-white">Quantity to Add</label>
                <input type="number" name="quantity" min="1" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
            </div>
            
            <div class="mb-4">
                <label class="mb-2.5 block text-black dark:text-white">Reason</label>
                <select name="reason" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                    <option value="purchase">Purchase/Restock</option>
                    <option value="return">Customer Return</option>
                    <option value="found">Found Inventory</option>
                    <option value="correction">Stock Correction</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="mb-2.5 block text-black dark:text-white">Notes (Optional)</label>
                <textarea name="notes" rows="2" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 rounded bg-primary px-4 py-2 text-white hover:bg-opacity-90">
                    Add Stock
                </button>
                <button type="button" onclick="closeQuickAdjustModal()" class="flex-1 rounded border border-stroke px-4 py-2 text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="fixed inset-0 z-99999 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="w-full max-w-md rounded-lg bg-white p-6 dark:bg-boxdark">
        <h3 class="mb-4 text-lg font-medium text-black dark:text-white">Export Inventory</h3>
        
        <form action="{{ admin_route('inventory.export') }}" method="GET">
            <div class="mb-4">
                <label class="mb-2.5 block text-black dark:text-white">Export Format</label>
                <select name="format" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                    <option value="csv">CSV</option>
                    <option value="excel" disabled>Excel (Coming Soon)</option>
                    <option value="pdf" disabled>PDF (Coming Soon)</option>
                </select>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 rounded bg-primary px-4 py-2 text-white hover:bg-opacity-90">
                    <i data-lucide="download" class="w-4 h-4 mr-2 inline"></i>
                    Export
                </button>
                <button type="button" onclick="closeExportModal()" class="flex-1 rounded border border-stroke px-4 py-2 text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4">
                    Cancel
                </button>
            </div>
        </form>
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
