@extends('admin.layouts.app')

@section('title', 'Shipping Settings')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Shipping Settings
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ admin_route('settings.index') }}">Settings /</a>
            </li>
            <li class="font-medium text-primary">Shipping</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="max-w-4xl mx-auto">
    <form action="{{ admin_route('settings.shipping.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Shipping Configuration -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Shipping Configuration</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Default Shipping Method -->
                <div>
                    <label for="default_shipping_method" class="mb-2.5 block text-black dark:text-white">
                        Default Shipping Method
                    </label>
                    <select
                        id="default_shipping_method"
                        name="default_shipping_method"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('default_shipping_method') border-red-500 @enderror"
                    >
                        <option value="standard" {{ old('default_shipping_method', setting('default_shipping_method', 'standard')) === 'standard' ? 'selected' : '' }}>Standard Shipping</option>
                        <option value="express" {{ old('default_shipping_method', setting('default_shipping_method', 'standard')) === 'express' ? 'selected' : '' }}>Express Shipping</option>
                        <option value="overnight" {{ old('default_shipping_method', setting('default_shipping_method', 'standard')) === 'overnight' ? 'selected' : '' }}>Overnight Shipping</option>
                        <option value="pickup" {{ old('default_shipping_method', setting('default_shipping_method', 'standard')) === 'pickup' ? 'selected' : '' }}>Store Pickup</option>
                    </select>
                    @error('default_shipping_method')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Shipping Origin -->
                <div>
                    <label for="shipping_origin" class="mb-2.5 block text-black dark:text-white">
                        Shipping Origin
                    </label>
                    <select
                        id="shipping_origin"
                        name="shipping_origin"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('shipping_origin') border-red-500 @enderror"
                    >
                        <option value="warehouse" {{ old('shipping_origin', setting('shipping_origin', 'warehouse')) === 'warehouse' ? 'selected' : '' }}>Main Warehouse</option>
                        <option value="store" {{ old('shipping_origin', setting('shipping_origin', 'warehouse')) === 'store' ? 'selected' : '' }}>Store Location</option>
                        <option value="manufacturer" {{ old('shipping_origin', setting('shipping_origin', 'warehouse')) === 'manufacturer' ? 'selected' : '' }}>Manufacturer Direct</option>
                    </select>
                    @error('shipping_origin')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Processing Time -->
                <div>
                    <label for="processing_time_days" class="mb-2.5 block text-black dark:text-white">
                        Order Processing Time (days)
                    </label>
                    <input
                        type="number"
                        id="processing_time_days"
                        name="processing_time_days"
                        value="{{ old('processing_time_days', setting('processing_time_days', 1)) }}"
                        min="0"
                        max="14"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('processing_time_days') border-red-500 @enderror"
                    />
                    @error('processing_time_days')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Free Shipping Threshold -->
                <div>
                    <label for="free_shipping_threshold" class="mb-2.5 block text-black dark:text-white">
                        Free Shipping Threshold
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input
                            type="number"
                            id="free_shipping_threshold"
                            name="free_shipping_threshold"
                            value="{{ old('free_shipping_threshold', setting('free_shipping_threshold', 100)) }}"
                            step="0.01"
                            min="0"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent pl-8 pr-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('free_shipping_threshold') border-red-500 @enderror"
                        />
                    </div>
                    @error('free_shipping_threshold')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Shipping Zones -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Shipping Zones</h4>
            
            <div class="space-y-4">
                <!-- Domestic Shipping -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i>
                            <div>
                                <h5 class="font-medium text-black dark:text-white">Domestic Shipping</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">United States</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="shipping_domestic" value="1" {{ old('shipping_domestic', setting('shipping_domestic', true)) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Standard Rate</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="domestic_standard_rate" value="{{ old('domestic_standard_rate', setting('domestic_standard_rate', 9.99)) }}" step="0.01" class="w-full pl-8 pr-3 py-2 border border-stroke dark:border-strokedark rounded text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Express Rate</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="domestic_express_rate" value="{{ old('domestic_express_rate', setting('domestic_express_rate', 19.99)) }}" step="0.01" class="w-full pl-8 pr-3 py-2 border border-stroke dark:border-strokedark rounded text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Overnight Rate</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="domestic_overnight_rate" value="{{ old('domestic_overnight_rate', setting('domestic_overnight_rate', 39.99)) }}" step="0.01" class="w-full pl-8 pr-3 py-2 border border-stroke dark:border-strokedark rounded text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- International Shipping -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <i data-lucide="globe" class="w-5 h-5 text-blue-600"></i>
                            <div>
                                <h5 class="font-medium text-black dark:text-white">International Shipping</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Worldwide</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="shipping_international" value="1" {{ old('shipping_international', setting('shipping_international', false)) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Standard Rate</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="international_standard_rate" value="{{ old('international_standard_rate', setting('international_standard_rate', 29.99)) }}" step="0.01" class="w-full pl-8 pr-3 py-2 border border-stroke dark:border-strokedark rounded text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Express Rate</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="international_express_rate" value="{{ old('international_express_rate', setting('international_express_rate', 59.99)) }}" step="0.01" class="w-full pl-8 pr-3 py-2 border border-stroke dark:border-strokedark rounded text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Priority Rate</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="international_priority_rate" value="{{ old('international_priority_rate', setting('international_priority_rate', 99.99)) }}" step="0.01" class="w-full pl-8 pr-3 py-2 border border-stroke dark:border-strokedark rounded text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Options -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Delivery Options</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Signature Required -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="signature_required"
                            value="1"
                            {{ old('signature_required', setting('signature_required', false)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Require Signature for Delivery</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Require recipient signature for all deliveries</p>
                </div>

                <!-- Insurance -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="shipping_insurance"
                            value="1"
                            {{ old('shipping_insurance', setting('shipping_insurance', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Include Shipping Insurance</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Automatically include insurance for high-value items</p>
                </div>

                <!-- Tracking -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="tracking_enabled"
                            value="1"
                            {{ old('tracking_enabled', setting('tracking_enabled', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Enable Package Tracking</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Provide tracking numbers for all shipments</p>
                </div>

                <!-- Delivery Confirmation -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="delivery_confirmation"
                            value="1"
                            {{ old('delivery_confirmation', setting('delivery_confirmation', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Delivery Confirmation</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send delivery confirmation emails</p>
                </div>
            </div>
        </div>

        <!-- Packaging Settings -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Packaging Settings</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Package Weight Limit -->
                <div>
                    <label for="package_weight_limit" class="mb-2.5 block text-black dark:text-white">
                        Package Weight Limit (lbs)
                    </label>
                    <input
                        type="number"
                        id="package_weight_limit"
                        name="package_weight_limit"
                        value="{{ old('package_weight_limit', setting('package_weight_limit', 70)) }}"
                        step="0.1"
                        min="1"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('package_weight_limit') border-red-500 @enderror"
                    />
                    @error('package_weight_limit')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Package Dimensions Limit -->
                <div>
                    <label for="package_dimensions_limit" class="mb-2.5 block text-black dark:text-white">
                        Package Dimensions Limit (inches)
                    </label>
                    <input
                        type="text"
                        id="package_dimensions_limit"
                        name="package_dimensions_limit"
                        value="{{ old('package_dimensions_limit', setting('package_dimensions_limit', '108x108x108')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('package_dimensions_limit') border-red-500 @enderror"
                        placeholder="LxWxH"
                    />
                    @error('package_dimensions_limit')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fragile Item Handling -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="fragile_handling"
                            value="1"
                            {{ old('fragile_handling', setting('fragile_handling', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Special Fragile Item Handling</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Apply special handling for fragile items</p>
                </div>

                <!-- Eco-Friendly Packaging -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="eco_friendly_packaging"
                            value="1"
                            {{ old('eco_friendly_packaging', setting('eco_friendly_packaging', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Use Eco-Friendly Packaging</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Use sustainable and recyclable packaging materials</p>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <button type="button" class="flex items-center gap-2 rounded-lg border border-gray-500 bg-gray-500 px-6 py-3 text-white hover:bg-gray-600 transition-colors duration-200">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                Reset to Defaults
            </button>
            <button type="submit" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-6 py-3 text-white hover:bg-primary/90 transition-colors duration-200">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
