@extends('admin.layouts.app')

@section('title', 'Edit Shipping Method')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Edit Shipping Method
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ route('admin.dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ route('admin.shipping-methods.index') }}">Shipping Methods /</a>
            </li>
            <li class="font-medium text-primary">{{ $shippingMethod->name }}</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.shipping-methods.update', $shippingMethod) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Basic Information</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Name -->
                <div>
                    <label for="name" class="mb-2.5 block text-black dark:text-white">
                        Shipping Method Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $shippingMethod->name) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('name') border-red-500 @enderror"
                        required
                        placeholder="e.g., Standard Shipping"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="mb-2.5 block text-black dark:text-white">
                        Method Code <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code', $shippingMethod->code) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('code') border-red-500 @enderror"
                        required
                        placeholder="e.g., standard"
                    />
                    @error('code')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="mb-2.5 block text-black dark:text-white">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('description') border-red-500 @enderror"
                        placeholder="Brief description of the shipping method"
                    >{{ old('description', $shippingMethod->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pricing Configuration -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Pricing Configuration</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Base Rate -->
                <div>
                    <label for="base_rate" class="mb-2.5 block text-black dark:text-white">
                        Base Rate <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input
                            type="number"
                            id="base_rate"
                            name="base_rate"
                            value="{{ old('base_rate', $shippingMethod->base_rate) }}"
                            step="0.01"
                            min="0"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent pl-8 pr-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('base_rate') border-red-500 @enderror"
                            required
                            placeholder="0.00"
                        />
                    </div>
                    @error('base_rate')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rate Type -->
                <div>
                    <label for="rate_type" class="mb-2.5 block text-black dark:text-white">
                        Rate Type
                    </label>
                    <select
                        id="rate_type"
                        name="rate_type"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('rate_type') border-red-500 @enderror"
                    >
                        <option value="flat" {{ old('rate_type', $shippingMethod->rate_type) === 'flat' ? 'selected' : '' }}>Flat Rate</option>
                        <option value="weight" {{ old('rate_type', $shippingMethod->rate_type) === 'weight' ? 'selected' : '' }}>Weight Based</option>
                        <option value="price" {{ old('rate_type', $shippingMethod->rate_type) === 'price' ? 'selected' : '' }}>Price Based</option>
                        <option value="free" {{ old('rate_type', $shippingMethod->rate_type) === 'free' ? 'selected' : '' }}>Free Shipping</option>
                    </select>
                    @error('rate_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Free Shipping Threshold -->
                <div id="free_shipping_threshold" class="hidden">
                    <label for="free_shipping_threshold" class="mb-2.5 block text-black dark:text-white">
                        Free Shipping Threshold
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input
                            type="number"
                            id="free_shipping_threshold"
                            name="free_shipping_threshold"
                            value="{{ old('free_shipping_threshold', $shippingMethod->free_shipping_threshold) }}"
                            step="0.01"
                            min="0"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent pl-8 pr-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('free_shipping_threshold') border-red-500 @enderror"
                            placeholder="0.00"
                        />
                    </div>
                    @error('free_shipping_threshold')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Weight Rate -->
                <div id="weight_rate" class="hidden">
                    <label for="weight_rate" class="mb-2.5 block text-black dark:text-white">
                        Rate Per Weight Unit
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input
                            type="number"
                            id="weight_rate"
                            name="weight_rate"
                            value="{{ old('weight_rate', $shippingMethod->weight_rate) }}"
                            step="0.01"
                            min="0"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent pl-8 pr-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('weight_rate') border-red-500 @enderror"
                            placeholder="0.00"
                        />
                    </div>
                    @error('weight_rate')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Delivery Settings -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Delivery Settings</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Estimated Days -->
                <div>
                    <label for="estimated_days" class="mb-2.5 block text-black dark:text-white">
                        Estimated Delivery Days
                    </label>
                    <input
                        type="number"
                        id="estimated_days"
                        name="estimated_days"
                        value="{{ old('estimated_days', $shippingMethod->estimated_days) }}"
                        min="1"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('estimated_days') border-red-500 @enderror"
                        placeholder="e.g., 3-5"
                    />
                    @error('estimated_days')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Weight -->
                <div>
                    <label for="max_weight" class="mb-2.5 block text-black dark:text-white">
                        Maximum Weight (lbs)
                    </label>
                    <input
                        type="number"
                        id="max_weight"
                        name="max_weight"
                        value="{{ old('max_weight', $shippingMethod->max_weight) }}"
                        step="0.1"
                        min="0"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('max_weight') border-red-500 @enderror"
                        placeholder="0.0"
                    />
                    @error('max_weight')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Dimensions -->
                <div>
                    <label for="max_dimensions" class="mb-2.5 block text-black dark:text-white">
                        Maximum Dimensions (inches)
                    </label>
                    <input
                        type="text"
                        id="max_dimensions"
                        name="max_dimensions"
                        value="{{ old('max_dimensions', $shippingMethod->max_dimensions) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('max_dimensions') border-red-500 @enderror"
                        placeholder="e.g., 24x18x12"
                    />
                    @error('max_dimensions')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tracking Available -->
                <div>
                    <label class="mb-2.5 block text-black dark:text-white">
                        Tracking Available
                    </label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input
                                type="radio"
                                name="tracking_available"
                                value="1"
                                {{ old('tracking_available', $shippingMethod->tracking_available) ? 'checked' : '' }}
                                class="mr-2"
                            />
                            <span class="text-black dark:text-white">Yes</span>
                        </label>
                        <label class="flex items-center">
                            <input
                                type="radio"
                                name="tracking_available"
                                value="0"
                                {{ !old('tracking_available', $shippingMethod->tracking_available) ? 'checked' : '' }}
                                class="mr-2"
                            />
                            <span class="text-black dark:text-white">No</span>
                        </label>
                    </div>
                    @error('tracking_available')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status & Availability -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Status & Availability</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Status -->
                <div>
                    <label for="status" class="mb-2.5 block text-black dark:text-white">
                        Status
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('status') border-red-500 @enderror"
                    >
                        <option value="active" {{ old('status', $shippingMethod->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $shippingMethod->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="mb-2.5 block text-black dark:text-white">
                        Sort Order
                    </label>
                    <input
                        type="number"
                        id="sort_order"
                        name="sort_order"
                        value="{{ old('sort_order', $shippingMethod->sort_order) }}"
                        min="0"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('sort_order') border-red-500 @enderror"
                        placeholder="0"
                    />
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Checkboxes -->
            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        name="is_default"
                        value="1"
                        {{ old('is_default', $shippingMethod->is_default) ? 'checked' : '' }}
                        class="mr-2 rounded border-stroke dark:border-strokedark"
                    />
                    <span class="text-black dark:text-white">Default Shipping Method</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        name="requires_signature"
                        value="1"
                        {{ old('requires_signature', $shippingMethod->requires_signature) ? 'checked' : '' }}
                        class="mr-2 rounded border-stroke dark:border-strokedark"
                    />
                    <span class="text-black dark:text-white">Requires Signature</span>
                </label>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.shipping-methods.index') }}" class="flex items-center gap-2 rounded-lg border border-stroke bg-white px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-300 dark:hover:bg-gray-800">
                <i data-lucide="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-6 py-3 text-white hover:bg-primary/90 transition-colors duration-200">
                <i data-lucide="save" class="w-4 h-4"></i>
                Update Shipping Method
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rateTypeSelect = document.getElementById('rate_type');
    const freeShippingThreshold = document.getElementById('free_shipping_threshold');
    const weightRate = document.getElementById('weight_rate');

    // Show/hide fields based on rate type
    function toggleRateFields() {
        const rateType = rateTypeSelect.value;
        
        // Hide all conditional fields
        freeShippingThreshold.style.display = 'none';
        weightRate.style.display = 'none';
        
        // Show relevant fields based on rate type
        if (rateType === 'free') {
            freeShippingThreshold.style.display = 'block';
        } else if (rateType === 'weight') {
            weightRate.style.display = 'block';
        }
    }

    rateTypeSelect.addEventListener('change', toggleRateFields);
    
    // Initialize on page load
    toggleRateFields();
});
</script>
@endsection
