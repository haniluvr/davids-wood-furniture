@extends('admin.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Low Stock Alerts')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="mx-auto max-w-screen-2xl">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-stone-900">Low Stock Alerts</h1>
                    <p class="mt-1 text-sm text-stone-600">Monitor products that are running low on inventory</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ admin_route('inventory.low-stock.export', request()->all()) }}" class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Export
                    </a>
                    <button onclick="openBulkReorderModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Bulk Reorder
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-screen-2xl pt-6">

    <!-- Alert Summary -->
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Critical Stock</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['critical_stock'] ?? 0 }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                    <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Low Stock</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['low_stock'] ?? 0 }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <i data-lucide="alert-circle" class="h-6 w-6 text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Out of Stock</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['out_of_stock'] ?? 0 }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                    <i data-lucide="x-circle" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Value at Risk</p>
                    <p class="text-2xl font-bold text-black dark:text-white">₱{{ number_format($stats['total_value_at_risk'] ?? 0, 2) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                    <i data-lucide="dollar-sign" class="h-6 w-6 text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Filters</h3>
        <form method="GET" class="flex flex-wrap items-end gap-4 justify-between">
            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Alert Level</label>
                <select name="level" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    <option value="">All Levels</option>
                    <option value="critical" {{ request('level') == 'critical' ? 'selected' : '' }}>Critical (0-5 units)</option>
                    <option value="low" {{ request('level') == 'low' ? 'selected' : '' }}>Low (6-20 units)</option>
                    <option value="out" {{ request('level') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <select name="category_id" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    <option value="">All Categories</option>
                    @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort By</label>
                <select name="sort" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stock (Low to High)</option>
                    <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stock (High to Low)</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A to Z)</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z to A)</option>
                    <option value="value_desc" {{ request('sort') == 'value_desc' ? 'selected' : '' }}>Value (High to Low)</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-opacity-90 whitespace-nowrap">
                    Apply Filters
                </button>
                <a href="{{ admin_route('inventory.low-stock') }}" class="inline-flex items-center justify-center rounded-lg border border-stroke px-4 py-2.5 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Low Stock Products -->
    <div class="rounded-xl border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center justify-between border-b border-stroke p-6 dark:border-strokedark">
            <h3 class="text-lg font-semibold text-black dark:text-white">Low Stock Products</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-stroke dark:border-strokedark">
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">
                            <input type="checkbox" id="select-all" class="rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input">
                        </th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Product</th>
                        <th class="py-3 px-4 text-center text-sm font-medium text-gray-600 dark:text-gray-400">Current Stock</th>
                        <th class="py-3 px-4 text-center text-sm font-medium text-gray-600 dark:text-gray-400">Threshold</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Alert Level</th>
                        <th class="py-3 px-4 text-center text-sm font-medium text-gray-600 dark:text-gray-400">Days Until Stockout</th>
                        <th class="py-3 px-4 text-center text-sm font-medium text-gray-600 dark:text-gray-400">Suggested Reorder</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            // Calculate days until stockout based on average daily sales (last 30 days)
                            $dailySales = $product->orderItems()
                                ->whereHas('order', function($q) { 
                                    $q->where('created_at', '>=', now()->subDays(30))
                                      ->where('status', '!=', 'cancelled'); 
                                })
                                ->sum('quantity') / 30;
                            $daysUntilStockout = $dailySales > 0 ? floor($product->stock_quantity / $dailySales) : 'N/A';
                            
                            // Suggested reorder quantity (threshold * 2, minimum 10)
                            $suggestedReorder = max($product->low_stock_threshold * 2, 10);
                            
                            // Determine alert level
                            $alertLevel = 'Low Stock';
                            $alertBadgeClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                            $textColor = 'text-yellow-600';
                            $hoverColor = 'hover:bg-yellow-50 dark:hover:bg-yellow-900/10';
                            
                            if ($product->stock_quantity == 0) {
                                $alertLevel = 'Out of Stock';
                                $alertBadgeClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                $textColor = 'text-red-600';
                                $hoverColor = 'hover:bg-red-50 dark:hover:bg-red-900/10';
                            } elseif ($product->stock_quantity <= ($product->low_stock_threshold * 0.5)) {
                                $alertLevel = 'Critical';
                                $alertBadgeClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                $textColor = 'text-red-600';
                                $hoverColor = 'hover:bg-red-50 dark:hover:bg-red-900/10';
                            }
                        @endphp
                        <tr class="border-b border-stroke dark:border-strokedark {{ $hoverColor }}">
                            <td class="py-3 px-4">
                                <input type="checkbox" class="rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input" name="product_ids[]" value="{{ $product->id }}">
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    @if($product->images && count($product->images) > 0)
                                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <i data-lucide="package" class="h-5 w-5 text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-black dark:text-white">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="text-lg font-bold {{ $textColor }}">{{ $product->stock_quantity }}</span>
                            </td>
                            <td class="py-3 px-4 text-center text-sm text-gray-600 dark:text-gray-400">{{ $product->low_stock_threshold }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center rounded-full {{ $alertBadgeClasses }} px-2 py-1 text-xs font-medium">
                                    {{ $alertLevel }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center text-sm {{ $textColor }} font-medium">
                                {{ is_numeric($daysUntilStockout) ? $daysUntilStockout . ' days' : $daysUntilStockout }}
                            </td>
                            <td class="py-3 px-4 text-center text-sm text-gray-600 dark:text-gray-400">{{ $suggestedReorder }} units</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ admin_route('inventory.adjust', $product) }}" class="text-primary hover:text-primary/80" title="Adjust Stock">
                                        <i data-lucide="edit" class="h-4 w-4"></i>
                                    </a>
                                    <a href="{{ admin_route('products.show', $product) }}" class="text-green-600 hover:text-green-700" title="View Product">
                                        <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="package-x" class="h-12 w-12 text-gray-400 mb-3"></i>
                                    <p class="text-gray-500 dark:text-gray-400">No low stock products found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            @include('admin.partials.pagination', ['paginator' => $products])
        @endif
    </div>
    </div>
</div>

<!-- Bulk Reorder Modal -->
<div id="bulkReorderModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl max-w-md w-full mx-4 z-[10000]" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-stone-200 dark:border-strokedark">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Bulk Reorder Products</h3>
                <button onclick="closeBulkReorderModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
        </div>
        <form id="bulkReorderForm" method="POST" action="{{ admin_route('inventory.bulk-restock') }}" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Selected Products: <span id="selectedCount" class="text-primary font-semibold">0</span>
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400">Add the same quantity to all selected products</p>
            </div>
            
            <div class="mb-4">
                <label for="quantity" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity to Add</label>
                <input type="number" id="quantity" name="quantity" min="1" required class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input" placeholder="Enter quantity">
            </div>
            
            <div class="mb-4">
                <label for="notes" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input" placeholder="Add notes about this bulk reorder"></textarea>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeBulkReorderModal()" class="flex-1 rounded-lg border border-stroke px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-strokedark dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="submit" class="flex-1 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-opacity-90">
                    Confirm Reorder
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endpush

@push('scripts')
<script>
// Select All Checkbox Functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const productCheckboxes = document.querySelectorAll('input[name="product_ids[]"]');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }
    
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            // Update select all checkbox state
            if (selectAllCheckbox) {
                const allChecked = Array.from(productCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(productCheckboxes).some(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
        });
    });
    
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('input[name="product_ids[]"]:checked').length;
        const countElement = document.getElementById('selectedCount');
        if (countElement) {
            countElement.textContent = selectedCount;
        }
    }
    
    updateSelectedCount();
});

// Success/Error Message Functions
function showSuccessMessage(message) {
    // Remove existing messages
    const existingMessage = document.getElementById('success-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create success message element
    const messageDiv = document.createElement('div');
    messageDiv.id = 'success-message';
    messageDiv.className = 'fixed top-4 right-4 z-[10001] bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-fade-in';
    messageDiv.innerHTML = `
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(messageDiv);
    
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Remove message after 3 seconds
    setTimeout(() => {
        messageDiv.style.opacity = '0';
        messageDiv.style.transition = 'opacity 0.3s ease-out';
        setTimeout(() => {
            messageDiv.remove();
        }, 300);
    }, 3000);
}

function showErrorMessage(message) {
    // Remove existing messages
    const existingMessage = document.getElementById('error-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create error message element
    const messageDiv = document.createElement('div');
    messageDiv.id = 'error-message';
    messageDiv.className = 'fixed top-4 right-4 z-[10001] bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-fade-in';
    messageDiv.innerHTML = `
        <i data-lucide="alert-circle" class="w-5 h-5"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(messageDiv);
    
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Remove message after 5 seconds
    setTimeout(() => {
        messageDiv.style.opacity = '0';
        messageDiv.style.transition = 'opacity 0.3s ease-out';
        setTimeout(() => {
            messageDiv.remove();
        }, 300);
    }, 5000);
}

// Bulk Reorder Modal Functions
function openBulkReorderModal() {
    const selectedCheckboxes = document.querySelectorAll('input[name="product_ids[]"]:checked');
    
    if (selectedCheckboxes.length === 0) {
        showErrorMessage('Please select at least one product to reorder.');
        return;
    }
    
    // Clear previous form data
    document.getElementById('bulkReorderForm').reset();
    
    // Update selected count
    document.getElementById('selectedCount').textContent = selectedCheckboxes.length;
    
    // Show modal
    const modal = document.getElementById('bulkReorderModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        // Close modal when clicking outside (one-time setup)
        if (!modal.hasAttribute('data-click-handler-set')) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeBulkReorderModal();
                }
            });
            modal.setAttribute('data-click-handler-set', 'true');
        }
    }
    
    // Refresh Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function closeBulkReorderModal() {
    const modal = document.getElementById('bulkReorderModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        // Restore body scroll
        document.body.style.overflow = '';
    }
}

// Handle bulk reorder form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bulkReorderForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const selectedCheckboxes = document.querySelectorAll('input[name="product_ids[]"]:checked');
            const productIds = Array.from(selectedCheckboxes).map(cb => cb.value);
            
            if (productIds.length === 0) {
                alert('Please select at least one product.');
                return;
            }
            
            // Add product_ids to form
            productIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            // Submit form via AJAX
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_ids: productIds,
                    quantity: document.getElementById('quantity').value,
                    notes: document.getElementById('notes').value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    closeBulkReorderModal();
                    
                    // Show success message
                    showSuccessMessage(data.message);
                    
                    // Reload page after a short delay
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showErrorMessage('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('An error occurred. Please try again.');
            });
        });
    }
});

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
@endpush
@endsection
