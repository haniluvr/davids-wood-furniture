@extends('admin.layouts.app')

@section('title', 'Edit Payment Gateway')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Edit Payment Gateway
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ admin_route('payment-gateways.index') }}">Payment Gateways /</a>
            </li>
            <li class="font-medium text-primary">{{ $paymentGateway->name }}</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="max-w-4xl mx-auto">
    <form action="{{ admin_route('payment-gateways.update', $paymentGateway) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Basic Information</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Name -->
                <div>
                    <label for="name" class="mb-2.5 block text-black dark:text-white">
                        Gateway Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $paymentGateway->name) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('name') border-red-500 @enderror"
                        required
                        placeholder="e.g., Stripe"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="mb-2.5 block text-black dark:text-white">
                        Gateway Code <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code', $paymentGateway->code) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('code') border-red-500 @enderror"
                        required
                        placeholder="e.g., stripe"
                    />
                    @error('code')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="mb-2.5 block text-black dark:text-white">
                        Payment Type
                    </label>
                    <select
                        id="type"
                        name="type"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('type') border-red-500 @enderror"
                    >
                        <option value="credit_card" {{ old('type', $paymentGateway->type) === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="debit_card" {{ old('type', $paymentGateway->type) === 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                        <option value="bank_transfer" {{ old('type', $paymentGateway->type) === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="digital_wallet" {{ old('type', $paymentGateway->type) === 'digital_wallet' ? 'selected' : '' }}>Digital Wallet</option>
                        <option value="cryptocurrency" {{ old('type', $paymentGateway->type) === 'cryptocurrency' ? 'selected' : '' }}>Cryptocurrency</option>
                        <option value="other" {{ old('type', $paymentGateway->type) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

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
                        <option value="active" {{ old('status', $paymentGateway->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $paymentGateway->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="maintenance" {{ old('status', $paymentGateway->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
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
                        placeholder="Brief description of the payment gateway"
                    >{{ old('description', $paymentGateway->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Configuration Settings -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Configuration Settings</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- API Key -->
                <div>
                    <label for="api_key" class="mb-2.5 block text-black dark:text-white">
                        API Key
                    </label>
                    <input
                        type="password"
                        id="api_key"
                        name="api_key"
                        value="{{ old('api_key', $paymentGateway->api_key) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('api_key') border-red-500 @enderror"
                        placeholder="Enter API key"
                    />
                    @error('api_key')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Secret Key -->
                <div>
                    <label for="secret_key" class="mb-2.5 block text-black dark:text-white">
                        Secret Key
                    </label>
                    <input
                        type="password"
                        id="secret_key"
                        name="secret_key"
                        value="{{ old('secret_key', $paymentGateway->secret_key) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('secret_key') border-red-500 @enderror"
                        placeholder="Enter secret key"
                    />
                    @error('secret_key')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Webhook URL -->
                <div>
                    <label for="webhook_url" class="mb-2.5 block text-black dark:text-white">
                        Webhook URL
                    </label>
                    <input
                        type="url"
                        id="webhook_url"
                        name="webhook_url"
                        value="{{ old('webhook_url', $paymentGateway->webhook_url) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('webhook_url') border-red-500 @enderror"
                        placeholder="https://example.com/webhook"
                    />
                    @error('webhook_url')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Environment -->
                <div>
                    <label for="environment" class="mb-2.5 block text-black dark:text-white">
                        Environment
                    </label>
                    <select
                        id="environment"
                        name="environment"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('environment') border-red-500 @enderror"
                    >
                        <option value="sandbox" {{ old('environment', $paymentGateway->environment) === 'sandbox' ? 'selected' : '' }}>Sandbox/Test</option>
                        <option value="production" {{ old('environment', $paymentGateway->environment) === 'production' ? 'selected' : '' }}>Production</option>
                    </select>
                    @error('environment')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Fee Configuration -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Fee Configuration</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Processing Fee -->
                <div>
                    <label for="processing_fee" class="mb-2.5 block text-black dark:text-white">
                        Processing Fee
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input
                            type="number"
                            id="processing_fee"
                            name="processing_fee"
                            value="{{ old('processing_fee', $paymentGateway->processing_fee) }}"
                            step="0.01"
                            min="0"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent pl-8 pr-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('processing_fee') border-red-500 @enderror"
                            placeholder="0.00"
                        />
                    </div>
                    @error('processing_fee')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Processing Fee Type -->
                <div>
                    <label for="processing_fee_type" class="mb-2.5 block text-black dark:text-white">
                        Fee Type
                    </label>
                    <select
                        id="processing_fee_type"
                        name="processing_fee_type"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('processing_fee_type') border-red-500 @enderror"
                    >
                        <option value="fixed" {{ old('processing_fee_type', $paymentGateway->processing_fee_type) === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="percentage" {{ old('processing_fee_type', $paymentGateway->processing_fee_type) === 'percentage' ? 'selected' : '' }}>Percentage</option>
                    </select>
                    @error('processing_fee_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Minimum Amount -->
                <div>
                    <label for="minimum_amount" class="mb-2.5 block text-black dark:text-white">
                        Minimum Transaction Amount
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input
                            type="number"
                            id="minimum_amount"
                            name="minimum_amount"
                            value="{{ old('minimum_amount', $paymentGateway->minimum_amount) }}"
                            step="0.01"
                            min="0"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent pl-8 pr-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('minimum_amount') border-red-500 @enderror"
                            placeholder="0.00"
                        />
                    </div>
                    @error('minimum_amount')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Maximum Amount -->
                <div>
                    <label for="maximum_amount" class="mb-2.5 block text-black dark:text-white">
                        Maximum Transaction Amount
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input
                            type="number"
                            id="maximum_amount"
                            name="maximum_amount"
                            value="{{ old('maximum_amount', $paymentGateway->maximum_amount) }}"
                            step="0.01"
                            min="0"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent pl-8 pr-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('maximum_amount') border-red-500 @enderror"
                            placeholder="0.00"
                        />
                    </div>
                    @error('maximum_amount')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Settings -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Additional Settings</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="mb-2.5 block text-black dark:text-white">
                        Sort Order
                    </label>
                    <input
                        type="number"
                        id="sort_order"
                        name="sort_order"
                        value="{{ old('sort_order', $paymentGateway->sort_order) }}"
                        min="0"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('sort_order') border-red-500 @enderror"
                        placeholder="0"
                    />
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Currency -->
                <div>
                    <label for="currency" class="mb-2.5 block text-black dark:text-white">
                        Supported Currency
                    </label>
                    <select
                        id="currency"
                        name="currency"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('currency') border-red-500 @enderror"
                    >
                        <option value="USD" {{ old('currency', $paymentGateway->currency) === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ old('currency', $paymentGateway->currency) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="GBP" {{ old('currency', $paymentGateway->currency) === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                        <option value="CAD" {{ old('currency', $paymentGateway->currency) === 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                        <option value="AUD" {{ old('currency', $paymentGateway->currency) === 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                    </select>
                    @error('currency')
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
                        {{ old('is_default', $paymentGateway->is_default) ? 'checked' : '' }}
                        class="mr-2 rounded border-stroke dark:border-strokedark"
                    />
                    <span class="text-black dark:text-white">Default Payment Method</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        name="supports_refunds"
                        value="1"
                        {{ old('supports_refunds', $paymentGateway->supports_refunds) ? 'checked' : '' }}
                        class="mr-2 rounded border-stroke dark:border-strokedark"
                    />
                    <span class="text-black dark:text-white">Supports Refunds</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        name="supports_recurring"
                        value="1"
                        {{ old('supports_recurring', $paymentGateway->supports_recurring) ? 'checked' : '' }}
                        class="mr-2 rounded border-stroke dark:border-strokedark"
                    />
                    <span class="text-black dark:text-white">Supports Recurring Payments</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        name="requires_3d_secure"
                        value="1"
                        {{ old('requires_3d_secure', $paymentGateway->requires_3d_secure) ? 'checked' : '' }}
                        class="mr-2 rounded border-stroke dark:border-strokedark"
                    />
                    <span class="text-black dark:text-white">Requires 3D Secure</span>
                </label>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ admin_route('payment-gateways.index') }}" class="flex items-center gap-2 rounded-lg border border-stroke bg-white px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-300 dark:hover:bg-gray-800">
                <i data-lucide="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-6 py-3 text-white hover:bg-primary/90 transition-colors duration-200">
                <i data-lucide="save" class="w-4 h-4"></i>
                Update Payment Gateway
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const processingFeeTypeSelect = document.getElementById('processing_fee_type');
    const processingFeeInput = document.getElementById('processing_fee');
    const processingFeeLabel = processingFeeInput.previousElementSibling;

    // Update fee input placeholder and label based on type
    function updateFeeInput() {
        const feeType = processingFeeTypeSelect.value;
        
        if (feeType === 'percentage') {
            processingFeeInput.placeholder = '0.00';
            processingFeeInput.step = '0.01';
            processingFeeInput.max = '100';
            processingFeeLabel.textContent = 'Processing Fee (%)';
        } else {
            processingFeeInput.placeholder = '0.00';
            processingFeeInput.step = '0.01';
            processingFeeInput.max = '';
            processingFeeLabel.textContent = 'Processing Fee ($)';
        }
    }

    processingFeeTypeSelect.addEventListener('change', updateFeeInput);
    
    // Initialize on page load
    updateFeeInput();
});
</script>
@endsection
