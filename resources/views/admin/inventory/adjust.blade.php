@extends('admin.layouts.app')

@section('title', 'Adjust Stock')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="edit" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Adjust Stock - {{ $product->name }}</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update product stock levels and inventory</p>
                </div>
            </div>
            <a href="{{ admin_route('inventory.show', $product) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Product
            </a>
        </div>
    </div>

    <form action="{{ admin_route('inventory.adjust', $product) }}" method="POST" class="space-y-8">
        @csrf

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Product Information -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl">
                                <i data-lucide="package" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Product Information</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Product details and current stock information</p>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="flex items-center gap-4">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" class="h-16 w-16 rounded-lg object-cover">
                            @else
                                <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                                    <i data-lucide="package" class="h-8 w-8 text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="text-lg font-semibold text-black dark:text-white">{{ $product->name }}</h4>
                                <p class="text-gray-600 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Current Stock</p>
                                <p class="text-2xl font-bold text-black dark:text-white">{{ $product->stock_quantity }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Low Stock Threshold</p>
                                <p class="text-2xl font-bold text-black dark:text-white">{{ $product->low_stock_threshold }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Stock Status</p>
                                <p class="text-lg font-bold {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $product->stock_quantity <= $product->low_stock_threshold ? 'Low Stock' : 'In Stock' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Adjustment -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl">
                                <i data-lucide="edit" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Stock Adjustment</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update stock quantity and adjustment details</p>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Adjustment Type <span class="text-red-500">*</span>
                                </label>
                                <select
                                    name="adjustment_type"
                                    id="adjustment-type"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white"
                                    required
                                >
                                    <option value="">Select Type</option>
                                    <option value="add">Add Stock</option>
                                    <option value="remove">Remove Stock</option>
                                    <option value="set">Set Stock Level</option>
                                </select>
                                @error('adjustment_type')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Quantity <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    min="0"
                                    step="1"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white"
                                    required
                                >
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Reason <span class="text-red-500">*</span>
                                </label>
                                <select
                                    name="reason"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white"
                                    required
                                >
                                    <option value="">Select Reason</option>
                                    <option value="inventory_count">Inventory Count</option>
                                    <option value="damaged_goods">Damaged Goods</option>
                                    <option value="theft_loss">Theft/Loss</option>
                                    <option value="return_restock">Return/Restock</option>
                                    <option value="supplier_delivery">Supplier Delivery</option>
                                    <option value="production_completion">Production Completion</option>
                                    <option value="quality_control">Quality Control</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('reason')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Reference Number
                                </label>
                                <input
                                    type="text"
                                    name="reference_number"
                                    placeholder="e.g., PO-12345, Invoice-67890"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white"
                                >
                                @error('reference_number')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Notes
                            </label>
                            <textarea
                                name="notes"
                                rows="4"
                                placeholder="Additional details about this stock adjustment..."
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white"
                            >{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Adjustment Preview -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl">
                                <i data-lucide="eye" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Adjustment Preview</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Preview stock changes</p>
                    </div>
                    <div class="p-8">
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-stone-900 dark:text-white">Current Stock:</span>
                                <span class="text-stone-900 dark:text-white font-medium" id="current-stock">{{ $product->stock_quantity }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-stone-900 dark:text-white">Adjustment:</span>
                                <span class="text-stone-900 dark:text-white font-medium" id="adjustment-preview">0</span>
                            </div>
                            <div class="border-t border-stone-200 pt-4 dark:border-strokedark">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-stone-900 dark:text-white">New Stock:</span>
                                    <span class="font-semibold text-stone-900 dark:text-white" id="new-stock">{{ $product->stock_quantity }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20">
                            <div class="flex items-start">
                                <i data-lucide="alert-triangle" class="h-5 w-5 text-yellow-600 dark:text-yellow-400 mt-0.5 mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Important</h4>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                        Stock adjustments are permanent and will be logged in the inventory history.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Adjustments -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                                <i data-lucide="history" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Recent Adjustments</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Latest stock adjustment history</p>
                    </div>
                    <div class="p-8">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                                <div>
                                    <p class="text-sm font-medium text-black dark:text-white">+5 units</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Inventory Count - 2 days ago</p>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Admin User</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                                <div>
                                    <p class="text-sm font-medium text-black dark:text-white">-2 units</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Damaged Goods - 1 week ago</p>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Admin User</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                                <div>
                                    <p class="text-sm font-medium text-black dark:text-white">+10 units</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Supplier Delivery - 2 weeks ago</p>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Admin User</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-slate-50 to-gray-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-slate-500 to-gray-600 rounded-xl">
                                <i data-lucide="settings" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Actions</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Save or cancel changes</p>
                    </div>
                    <div class="p-8">
                        <div class="space-y-4">
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-blue-600 px-6 py-3 text-center font-medium text-white hover:from-emerald-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                Apply Adjustment
                            </button>
                            
                            <a href="{{ admin_route('inventory.show', $product) }}" class="w-full inline-flex items-center justify-center rounded-xl border border-stone-200 px-6 py-3 text-center font-medium text-stone-700 hover:bg-stone-50 transition-all duration-200 dark:border-strokedark dark:text-white dark:hover:bg-gray-800">
                                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const adjustmentType = document.getElementById('adjustment-type');
    const quantity = document.getElementById('quantity');
    const currentStock = {{ $product->stock_quantity }};
    
    function updatePreview() {
        const type = adjustmentType.value;
        const qty = parseInt(quantity.value) || 0;
        let adjustment = 0;
        let newStock = currentStock;
        
        if (type && qty > 0) {
            switch(type) {
                case 'add':
                    adjustment = qty;
                    newStock = currentStock + qty;
                    break;
                case 'remove':
                    adjustment = -qty;
                    newStock = Math.max(0, currentStock - qty);
                    break;
                case 'set':
                    adjustment = qty - currentStock;
                    newStock = qty;
                    break;
            }
        }
        
        document.getElementById('adjustment-preview').textContent = 
            adjustment > 0 ? `+${adjustment}` : adjustment.toString();
        document.getElementById('new-stock').textContent = newStock;
        
        // Update color based on new stock level
        const newStockElement = document.getElementById('new-stock');
        const lowStockThreshold = {{ $product->low_stock_threshold }};
        
        if (newStock <= lowStockThreshold) {
            newStockElement.className = 'text-lg font-semibold text-red-600';
        } else {
            newStockElement.className = 'text-lg font-semibold text-black dark:text-white';
        }
    }
    
    adjustmentType.addEventListener('change', updatePreview);
    quantity.addEventListener('input', updatePreview);
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const type = adjustmentType.value;
        const qty = parseInt(quantity.value) || 0;
        
        if (!type) {
            e.preventDefault();
            alert('Please select an adjustment type.');
            return;
        }
        
        if (qty <= 0) {
            e.preventDefault();
            alert('Please enter a valid quantity.');
            return;
        }
        
        if (type === 'remove' && qty > currentStock) {
            e.preventDefault();
            alert('Cannot remove more stock than currently available.');
            return;
        }
        
        if (type === 'set' && qty < 0) {
            e.preventDefault();
            alert('Stock level cannot be negative.');
            return;
        }
    });
});
</script>
@endpush
@endsection
