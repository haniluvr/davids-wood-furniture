@extends('admin.layouts.app')

@section('title', 'Create Shipping Method')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="truck" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Create Shipping Method</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Add a new shipping method to your store</p>
                </div>
            </div>
            <a href="{{ admin_route('shipping-methods.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Shipping Methods
            </a>
        </div>
    </div>

    <form method="POST" action="{{ admin_route('shipping-methods.store') }}" class="space-y-8" id="shippingForm">
        @csrf
        
        <!-- Main Content Area -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <!-- Tab Navigation -->
            <div class="border-b border-stone-200 dark:border-strokedark">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button type="button" onclick="switchTab('basic')" id="basic-tab" class="tab-button active py-4 px-1 border-b-2 font-medium text-sm border-emerald-500 text-emerald-600 dark:text-emerald-400">
                        <i data-lucide="info" class="w-5 h-5 mr-2 inline"></i>
                        Basic Information
                    </button>
                    <button type="button" onclick="switchTab('pricing')" id="pricing-tab" class="tab-button py-4 px-1 border-b-2 font-medium text-sm border-transparent text-stone-500 hover:text-stone-700 hover:border-stone-300 dark:text-gray-400 dark:hover:text-gray-300">
                        <i data-lucide="dollar-sign" class="w-5 h-5 mr-2 inline"></i>
                        Cost & Pricing
                    </button>
                    <button type="button" onclick="switchTab('settings')" id="settings-tab" class="tab-button py-4 px-1 border-b-2 font-medium text-sm border-transparent text-stone-500 hover:text-stone-700 hover:border-stone-300 dark:text-gray-400 dark:hover:text-gray-300">
                        <i data-lucide="settings" class="w-5 h-5 mr-2 inline"></i>
                        Settings
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-8">
                <!-- Basic Information Tab -->
                <div id="basic-panel" class="tab-panel">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Method Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                       class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('name') border-red-300 @enderror"
                                       placeholder="e.g., Standard Shipping">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label for="type" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Shipping Type <span class="text-red-500">*</span>
                                </label>
                                <select id="type" name="type" required onchange="toggleTypeFields()"
                                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('type') border-red-300 @enderror">
                                    <option value="">Select Type</option>
                                    <option value="flat_rate" {{ old('type') == 'flat_rate' ? 'selected' : '' }}>Flat Rate</option>
                                    <option value="free_shipping" {{ old('type') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                                    <option value="weight_based" {{ old('type') == 'weight_based' ? 'selected' : '' }}>Weight Based</option>
                                    <option value="price_based" {{ old('type') == 'price_based' ? 'selected' : '' }}>Price Based</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Description</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('description') border-red-300 @enderror"
                                      placeholder="Brief description of this shipping method">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Cost & Pricing Tab -->
                <div id="pricing-panel" class="tab-panel hidden">
                    <div class="space-y-6">
                        <div id="cost-fields" class="space-y-6">
                            <div class="space-y-2">
                                <label for="cost" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Base Cost <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-stone-500 dark:text-gray-400 sm:text-sm">₱</span>
                                    </div>
                                    <input type="number" id="cost" name="cost" value="{{ old('cost') }}" step="0.01" min="0" required
                                           class="w-full pl-8 pr-3 py-3 rounded-xl border border-stone-200 bg-white px-4 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('cost') border-red-300 @enderror"
                                           placeholder="0.00">
                                </div>
                                @error('cost')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div id="free-shipping-threshold" class="hidden space-y-2">
                                <label for="free_shipping_threshold" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Free Shipping Threshold</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-stone-500 dark:text-gray-400 sm:text-sm">₱</span>
                                    </div>
                                    <input type="number" id="free_shipping_threshold" name="free_shipping_threshold" value="{{ old('free_shipping_threshold') }}" step="0.01" min="0"
                                           class="w-full pl-8 pr-3 py-3 rounded-xl border border-stone-200 bg-white px-4 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('free_shipping_threshold') border-red-300 @enderror"
                                           placeholder="0.00">
                                </div>
                                @error('free_shipping_threshold')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="minimum_order_amount" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Minimum Order Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-stone-500 dark:text-gray-400 sm:text-sm">₱</span>
                                    </div>
                                    <input type="number" id="minimum_order_amount" name="minimum_order_amount" value="{{ old('minimum_order_amount') }}" step="0.01" min="0"
                                           class="w-full pl-8 pr-3 py-3 rounded-xl border border-stone-200 bg-white px-4 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('minimum_order_amount') border-red-300 @enderror"
                                           placeholder="0.00">
                                </div>
                                @error('minimum_order_amount')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label for="maximum_order_amount" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Maximum Order Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-stone-500 dark:text-gray-400 sm:text-sm">₱</span>
                                    </div>
                                    <input type="number" id="maximum_order_amount" name="maximum_order_amount" value="{{ old('maximum_order_amount') }}" step="0.01" min="0"
                                           class="w-full pl-8 pr-3 py-3 rounded-xl border border-stone-200 bg-white px-4 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('maximum_order_amount') border-red-300 @enderror"
                                           placeholder="No limit">
                                </div>
                                @error('maximum_order_amount')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="estimated_days_min" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Minimum Delivery Days</label>
                                <input type="number" id="estimated_days_min" name="estimated_days_min" value="{{ old('estimated_days_min') }}" min="1"
                                       class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('estimated_days_min') border-red-300 @enderror"
                                       placeholder="1">
                                @error('estimated_days_min')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label for="estimated_days_max" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Maximum Delivery Days</label>
                                <input type="number" id="estimated_days_max" name="estimated_days_max" value="{{ old('estimated_days_max') }}" min="1"
                                       class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('estimated_days_max') border-red-300 @enderror"
                                       placeholder="7">
                                @error('estimated_days_max')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div id="settings-panel" class="tab-panel hidden">
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500 dark:border-strokedark dark:bg-boxdark">
                            <label for="is_active" class="ml-2 block text-sm text-stone-700 dark:text-stone-300">Active</label>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="sort_order" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                                   class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('sort_order') border-red-300 @enderror"
                                   placeholder="0">
                            <p class="mt-1 text-xs text-stone-500 dark:text-gray-400">Lower numbers appear first in the list</p>
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ admin_route('shipping-methods.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 border border-stone-200 bg-white text-sm font-medium text-stone-700 rounded-xl transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-blue-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-emerald-700 hover:to-blue-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                <i data-lucide="check" class="w-4 h-4"></i>
                Create Shipping Method
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Tab switching functionality
function switchTab(tabName) {
    // Hide all panels
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(tab => {
        tab.classList.remove('active', 'border-emerald-500', 'text-emerald-600', 'dark:text-emerald-400');
        tab.classList.add('border-transparent', 'text-stone-500', 'dark:text-gray-400');
    });
    
    // Show selected panel
    document.getElementById(tabName + '-panel').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-emerald-500', 'text-emerald-600', 'dark:text-emerald-400');
    activeTab.classList.remove('border-transparent', 'text-stone-500', 'dark:text-gray-400');
}

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
@endsection
