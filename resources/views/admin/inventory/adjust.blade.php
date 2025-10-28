@extends('admin.layouts.app')

@section('title', 'Adjust Stock')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            Adjust Stock
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a href="{{ admin_route('dashboard') }}" class="font-medium">Dashboard</a></li>
                <li class="font-medium text-primary">/</li>
                <li><a href="{{ admin_route('inventory.index') }}" class="font-medium">Inventory</a></li>
                <li class="font-medium text-primary">/</li>
                <li><a href="{{ admin_route('inventory.show', $product) }}" class="font-medium">{{ $product->name }}</a></li>
                <li class="font-medium text-primary">/</li>
                <li class="font-medium text-primary">Adjust</li>
            </ol>
        </nav>
    </div>

    <form action="{{ admin_route('inventory.adjust', $product) }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Information -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Product Information</h3>
                    
                    <div class="flex items-center gap-4 mb-6">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="h-16 w-16 rounded-lg object-cover">
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

                <!-- Stock Adjustment -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Stock Adjustment</h3>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Adjustment Type <span class="text-meta-1">*</span>
                            </label>
                            <select
                                name="adjustment_type"
                                id="adjustment-type"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                required
                            >
                                <option value="">Select Type</option>
                                <option value="add">Add Stock</option>
                                <option value="remove">Remove Stock</option>
                                <option value="set">Set Stock Level</option>
                            </select>
                            @error('adjustment_type')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Quantity <span class="text-meta-1">*</span>
                            </label>
                            <input
                                type="number"
                                name="quantity"
                                id="quantity"
                                min="0"
                                step="1"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                required
                            >
                            @error('quantity')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Reason <span class="text-meta-1">*</span>
                            </label>
                            <select
                                name="reason"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
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
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Reference Number
                            </label>
                            <input
                                type="text"
                                name="reference_number"
                                placeholder="e.g., PO-12345, Invoice-67890"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                            @error('reference_number')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="mb-2.5 block text-black dark:text-white">
                            Notes
                        </label>
                        <textarea
                            name="notes"
                            rows="4"
                            placeholder="Additional details about this stock adjustment..."
                            class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        >{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Adjustment Preview -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Adjustment Preview</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Current Stock:</span>
                            <span class="font-medium text-black dark:text-white" id="current-stock">{{ $product->stock_quantity }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Adjustment:</span>
                            <span class="font-medium text-black dark:text-white" id="adjustment-preview">0</span>
                        </div>
                        <div class="border-t border-stroke dark:border-strokedark pt-4">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">New Stock:</span>
                                <span class="text-lg font-semibold text-black dark:text-white" id="new-stock">{{ $product->stock_quantity }}</span>
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

                <!-- Recent Adjustments -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Recent Adjustments</h3>
                    
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

                <!-- Actions -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <div class="space-y-3">
                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-lg bg-primary p-3 font-medium text-white hover:bg-opacity-90"
                        >
                            Apply Adjustment
                        </button>
                        
                        <a
                            href="{{ admin_route('inventory.show', $product) }}"
                            class="flex w-full justify-center rounded-lg border border-stroke bg-white p-3 font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-boxdark-2"
                        >
                            Cancel
                        </a>
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
