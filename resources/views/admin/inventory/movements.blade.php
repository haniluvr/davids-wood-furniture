@extends('admin.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Inventory Movements')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="mx-auto max-w-screen-2xl">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-stone-900">Inventory Movements</h1>
                    <p class="mt-1 text-sm text-stone-600">Track all stock in, stock out, and adjustment transactions</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ admin_route('inventory.movements.export', request()->all()) }}" class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Export
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-screen-2xl pt-6">

    <!-- Stats Cards -->
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Movements</p>
                    <p class="text-2xl font-bold text-black dark:text-white">{{ number_format($stats['total_movements'] ?? 0) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                    <i data-lucide="activity" class="h-6 w-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Stock In (30 days)</p>
                    <p class="text-2xl font-bold text-green-600">+{{ number_format($stats['stock_in_30_days'] ?? 0) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                    <i data-lucide="trending-up" class="h-6 w-6 text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Stock Out (30 days)</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['stock_out_30_days'] ?? 0) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                    <i data-lucide="trending-down" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Net Change</p>
                    @php
                        $netChange = $stats['net_change_30_days'] ?? 0;
                    @endphp
                    <p class="text-2xl font-bold {{ $netChange >= 0 ? 'text-blue-600' : 'text-red-600' }}">{{ $netChange >= 0 ? '+' : '' }}{{ number_format($netChange) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                    <i data-lucide="bar-chart-3" class="h-6 w-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Filters</h3>
        <form method="GET" class="flex flex-wrap items-end gap-4 justify-between">
            <div class="flex-1 min-w-[200px] relative">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Product</label>
                <div class="relative">
                    <input type="text" 
                           id="product-search" 
                           name="product_search"
                           value="{{ request('product_id') ? \App\Models\Product::find(request('product_id'))?->name : '' }}"
                           placeholder="Search products by name or SKU..."
                           autocomplete="off"
                           class="w-full rounded-lg border border-stroke px-3 py-2 pl-10 text-sm dark:border-strokedark dark:bg-form-input">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="hidden" name="product_id" id="product_id" value="{{ request('product_id') }}">
                    <div id="product-results" class="hidden absolute z-50 w-full mt-1 border border-stroke rounded-lg max-h-60 overflow-y-auto bg-white dark:border-strokedark dark:bg-boxdark shadow-lg"></div>
                </div>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Movement Type</label>
                <select name="type" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    <option value="">All Types</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-opacity-90 whitespace-nowrap">
                    Apply Filters
                </button>
                <a href="{{ admin_route('inventory.movements') }}" class="inline-flex items-center justify-center rounded-lg border border-stroke px-4 py-2.5 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Movements Table -->
    <div class="rounded-xl border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center justify-between border-b border-stroke p-6 dark:border-strokedark">
            <h3 class="text-lg font-semibold text-black dark:text-white">Movement History</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-stroke dark:border-strokedark">
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Date</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Product</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Type</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Quantity</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Reason</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">User</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Reference</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        <tr class="border-b border-stroke dark:border-strokedark hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">
                                {{ $movement->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    @if($movement->product && $movement->product->images && count($movement->product->images) > 0)
                                        <img src="{{ Storage::url($movement->product->images[0]) }}" alt="{{ $movement->product->name }}" class="h-8 w-8 rounded object-cover">
                                    @else
                                        <div class="h-8 w-8 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <i data-lucide="package" class="h-4 w-4 text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-black dark:text-white">{{ $movement->product->name ?? 'Product Deleted' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $movement->product->sku ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $movement->type_badge_color }}">
                                    {{ ucfirst($movement->type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm font-medium {{ $movement->type === 'in' || $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->formatted_quantity }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $movement->reason ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $movement->createdBy ? $movement->createdBy->name : 'System' }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                @if($movement->reference_type && $movement->reference_id)
                                    @php
                                        $reference = $movement->reference;
                                        $referenceDisplay = $movement->reference_id;
                                        if ($reference) {
                                            if (method_exists($reference, 'order_number')) {
                                                $referenceDisplay = $reference->order_number;
                                            } elseif (method_exists($reference, 'name')) {
                                                $referenceDisplay = $reference->name;
                                            }
                                        }
                                    @endphp
                                    {{ $referenceDisplay }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $movement->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="package-x" class="h-12 w-12 text-gray-400 mb-3"></i>
                                    <p class="text-gray-500 dark:text-gray-400">No inventory movements found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($movements->hasPages())
            @include('admin.partials.pagination', ['paginator' => $movements])
        @endif
    </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('product-search');
    const productIdInput = document.getElementById('product_id');
    const resultsDiv = document.getElementById('product-results');
    let searchTimeout;

    // Close results when clicking outside
    document.addEventListener('click', function(e) {
        const productSearchContainer = searchInput.closest('.relative');
        if (!productSearchContainer.contains(e.target)) {
            resultsDiv.classList.add('hidden');
        }
    });

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            resultsDiv.classList.add('hidden');
            productIdInput.value = '';
            return;
        }

        searchTimeout = setTimeout(() => {
            searchProducts(query);
        }, 300);
    });

    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            searchProducts(this.value.trim());
        }
    });

    function searchProducts(query) {
        fetch(`/api/search?q=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                displayProductResults(data.data);
            } else {
                resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500">No products found</div>';
                resultsDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error searching products:', error);
            resultsDiv.innerHTML = '<div class="p-4 text-center text-red-500">Error searching products</div>';
            resultsDiv.classList.remove('hidden');
        });
    }

    function displayProductResults(products) {
        if (products.length === 0) {
            resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500 dark:text-gray-400">No products found</div>';
        } else {
            resultsDiv.innerHTML = products.map(product => `
                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer border-b border-stroke last:border-b-0 dark:border-strokedark" 
                     onclick="selectProduct(${product.id}, '${product.name.replace(/'/g, "\\'")}')">
                    <div class="font-medium text-black dark:text-white">${product.name}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">SKU: ${product.sku || 'N/A'}</div>
                </div>
            `).join('');
        }
        resultsDiv.classList.remove('hidden');
        
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    window.selectProduct = function(productId, productName) {
        searchInput.value = productName;
        productIdInput.value = productId;
        resultsDiv.classList.add('hidden');
        
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    };

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endpush
@endsection
