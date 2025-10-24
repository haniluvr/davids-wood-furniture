@extends('admin.layouts.app')

@section('title', 'Create Shipping Method')

@section('content')
<div class="min-h-screen bg-stone-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-4">
                            <li>
                                <a href="{{ admin_route('shipping-methods.index') }}" class="text-stone-400 hover:text-stone-500">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-stone-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ admin_route('shipping-methods.index') }}" class="ml-4 text-sm font-medium text-stone-500 hover:text-stone-700">Shipping Methods</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-stone-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-4 text-sm font-medium text-stone-500">Create</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="mt-2 text-2xl font-bold text-stone-900">Create Shipping Method</h1>
                    <p class="mt-1 text-sm text-stone-600">Add a new shipping method to your store</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="POST" action="{{ admin_route('shipping-methods.store') }}" class="space-y-8">
            @csrf
            
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <h2 class="text-lg font-semibold text-stone-900 mb-6">Basic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-stone-700 mb-2">Method Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-300 @enderror"
                               placeholder="e.g., Standard Shipping">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-stone-700 mb-2">Shipping Type *</label>
                        <select id="type" name="type" required onchange="toggleTypeFields()"
                                class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('type') border-red-300 @enderror">
                            <option value="">Select Type</option>
                            <option value="flat_rate" {{ old('type') == 'flat_rate' ? 'selected' : '' }}>Flat Rate</option>
                            <option value="free_shipping" {{ old('type') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                            <option value="weight_based" {{ old('type') == 'weight_based' ? 'selected' : '' }}>Weight Based</option>
                            <option value="price_based" {{ old('type') == 'price_based' ? 'selected' : '' }}>Price Based</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-stone-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('description') border-red-300 @enderror"
                              placeholder="Brief description of this shipping method">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Cost Configuration -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <h2 class="text-lg font-semibold text-stone-900 mb-6">Cost Configuration</h2>
                
                <div id="cost-fields" class="space-y-6">
                    <div>
                        <label for="cost" class="block text-sm font-medium text-stone-700 mb-2">Base Cost *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-stone-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="cost" name="cost" value="{{ old('cost') }}" step="0.01" min="0" required
                                   class="w-full pl-7 pr-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('cost') border-red-300 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div id="free-shipping-threshold" class="hidden">
                        <label for="free_shipping_threshold" class="block text-sm font-medium text-stone-700 mb-2">Free Shipping Threshold</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-stone-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="free_shipping_threshold" name="free_shipping_threshold" value="{{ old('free_shipping_threshold') }}" step="0.01" min="0"
                                   class="w-full pl-7 pr-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('free_shipping_threshold') border-red-300 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('free_shipping_threshold')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="minimum_order_amount" class="block text-sm font-medium text-stone-700 mb-2">Minimum Order Amount</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-stone-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="minimum_order_amount" name="minimum_order_amount" value="{{ old('minimum_order_amount') }}" step="0.01" min="0"
                                   class="w-full pl-7 pr-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('minimum_order_amount') border-red-300 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('minimum_order_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="maximum_order_amount" class="block text-sm font-medium text-stone-700 mb-2">Maximum Order Amount</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-stone-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="maximum_order_amount" name="maximum_order_amount" value="{{ old('maximum_order_amount') }}" step="0.01" min="0"
                                   class="w-full pl-7 pr-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('maximum_order_amount') border-red-300 @enderror"
                                   placeholder="No limit">
                        </div>
                        @error('maximum_order_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <h2 class="text-lg font-semibold text-stone-900 mb-6">Delivery Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="estimated_days_min" class="block text-sm font-medium text-stone-700 mb-2">Minimum Delivery Days</label>
                        <input type="number" id="estimated_days_min" name="estimated_days_min" value="{{ old('estimated_days_min') }}" min="1"
                               class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('estimated_days_min') border-red-300 @enderror"
                               placeholder="1">
                        @error('estimated_days_min')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="estimated_days_max" class="block text-sm font-medium text-stone-700 mb-2">Maximum Delivery Days</label>
                        <input type="number" id="estimated_days_max" name="estimated_days_max" value="{{ old('estimated_days_max') }}" min="1"
                               class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('estimated_days_max') border-red-300 @enderror"
                               placeholder="7">
                        @error('estimated_days_max')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <h2 class="text-lg font-semibold text-stone-900 mb-6">Settings</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-stone-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-stone-900">Active</label>
                    </div>
                    
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-stone-700 mb-2">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('sort_order') border-red-300 @enderror"
                               placeholder="0">
                        <p class="mt-1 text-sm text-stone-500">Lower numbers appear first in the list</p>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ admin_route('shipping-methods.index') }}" 
                   class="px-6 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Create Shipping Method
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleTypeFields() {
    const type = document.getElementById('type').value;
    const costFields = document.getElementById('cost-fields');
    const freeShippingThreshold = document.getElementById('free-shipping-threshold');
    const costInput = document.getElementById('cost');
    
    if (type === 'free_shipping') {
        costFields.style.display = 'none';
        freeShippingThreshold.classList.remove('hidden');
        costInput.required = false;
    } else {
        costFields.style.display = 'block';
        freeShippingThreshold.classList.add('hidden');
        costInput.required = true;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleTypeFields();
});
</script>
@endpush
