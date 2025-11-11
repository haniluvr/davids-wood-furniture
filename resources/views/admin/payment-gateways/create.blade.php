@extends('admin.layouts.app')

@section('title', 'Add Payment Gateway')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Add Payment Gateway
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ admin_route('payment-gateways.index') }}">Payment Gateways /</a>
            </li>
            <li class="font-medium text-primary">Add New</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="max-w-4xl mx-auto">
    <form action="{{ admin_route('payment-gateways.store') }}" method="POST" class="space-y-6">
        @csrf

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
                        value="{{ old('name') }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('name') border-red-500 @enderror"
                        required
                        placeholder="e.g., Stripe, PayPal"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gateway Key -->
                <div>
                    <label for="gateway_key" class="mb-2.5 block text-black dark:text-white">
                        Gateway Key <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="gateway_key"
                        name="gateway_key"
                        value="{{ old('gateway_key') }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('gateway_key') border-red-500 @enderror"
                        required
                        placeholder="e.g., stripe, paypal"
                    />
                    <p class="mt-1 text-xs text-gray-500">Unique identifier (lowercase, no spaces)</p>
                    @error('gateway_key')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Display Name -->
                <div class="md:col-span-2">
                    <label for="display_name" class="mb-2.5 block text-black dark:text-white">
                        Display Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="display_name"
                        name="display_name"
                        value="{{ old('display_name') }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('display_name') border-red-500 @enderror"
                        required
                        placeholder="e.g., Stripe Payment Gateway"
                    />
                    @error('display_name')
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
                    >{{ old('description') }}</textarea>
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
                    <label for="config_api_key" class="mb-2.5 block text-black dark:text-white">
                        API Key
                    </label>
                    <input
                        type="password"
                        id="config_api_key"
                        name="config[api_key]"
                        value="{{ old('config.api_key') }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('config.api_key') border-red-500 @enderror"
                        placeholder="Enter API key"
                    />
                    @error('config.api_key')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Secret Key -->
                <div>
                    <label for="config_secret_key" class="mb-2.5 block text-black dark:text-white">
                        Secret Key
                    </label>
                    <input
                        type="password"
                        id="config_secret_key"
                        name="config[secret_key]"
                        value="{{ old('config.secret_key') }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('config.secret_key') border-red-500 @enderror"
                        placeholder="Enter secret key"
                    />
                    @error('config.secret_key')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Webhook URL -->
                <div>
                    <label for="config_webhook_url" class="mb-2.5 block text-black dark:text-white">
                        Webhook URL
                    </label>
                    <input
                        type="url"
                        id="config_webhook_url"
                        name="config[webhook_url]"
                        value="{{ old('config.webhook_url') }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('config.webhook_url') border-red-500 @enderror"
                        placeholder="https://example.com/webhook"
                    />
                    @error('config.webhook_url')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Webhook Secret -->
                <div>
                    <label for="config_webhook_secret" class="mb-2.5 block text-black dark:text-white">
                        Webhook Secret
                    </label>
                    <input
                        type="password"
                        id="config_webhook_secret"
                        name="config[webhook_secret]"
                        value="{{ old('config.webhook_secret') }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('config.webhook_secret') border-red-500 @enderror"
                        placeholder="Enter webhook secret"
                    />
                    @error('config.webhook_secret')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Fee Configuration -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Fee Configuration</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Transaction Fee Percentage -->
                <div>
                    <label for="transaction_fee_percentage" class="mb-2.5 block text-black dark:text-white">
                        Transaction Fee Percentage
                    </label>
                    <div class="relative">
                        <input
                            type="number"
                            id="transaction_fee_percentage"
                            name="transaction_fee_percentage"
                            value="{{ old('transaction_fee_percentage', 0) }}"
                            step="0.01"
                            min="0"
                            max="100"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent pr-8 pl-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('transaction_fee_percentage') border-red-500 @enderror"
                            placeholder="0.00"
                        />
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                    </div>
                    @error('transaction_fee_percentage')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transaction Fee Fixed -->
                <div>
                    <label for="transaction_fee_fixed" class="mb-2.5 block text-black dark:text-white">
                        Transaction Fee Fixed
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                        <input
                            type="number"
                            id="transaction_fee_fixed"
                            name="transaction_fee_fixed"
                            value="{{ old('transaction_fee_fixed', 0) }}"
                            step="0.01"
                            min="0"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent pl-8 pr-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('transaction_fee_fixed') border-red-500 @enderror"
                            placeholder="0.00"
                        />
                    </div>
                    @error('transaction_fee_fixed')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Supported Currencies and Countries -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Supported Currencies & Countries</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Supported Currencies -->
                <div>
                    <label for="supported_currencies" class="mb-2.5 block text-black dark:text-white">
                        Supported Currencies
                    </label>
                    <select
                        id="supported_currencies"
                        name="supported_currencies[]"
                        multiple
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('supported_currencies') border-red-500 @enderror"
                        size="5"
                    >
                        <option value="PHP" {{ in_array('PHP', old('supported_currencies', [])) ? 'selected' : '' }}>PHP - Philippine Peso</option>
                        <option value="USD" {{ in_array('USD', old('supported_currencies', [])) ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ in_array('EUR', old('supported_currencies', [])) ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="GBP" {{ in_array('GBP', old('supported_currencies', [])) ? 'selected' : '' }}>GBP - British Pound</option>
                        <option value="CAD" {{ in_array('CAD', old('supported_currencies', [])) ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                        <option value="AUD" {{ in_array('AUD', old('supported_currencies', [])) ? 'selected' : '' }}>AUD - Australian Dollar</option>
                        <option value="JPY" {{ in_array('JPY', old('supported_currencies', [])) ? 'selected' : '' }}>JPY - Japanese Yen</option>
                        <option value="CNY" {{ in_array('CNY', old('supported_currencies', [])) ? 'selected' : '' }}>CNY - Chinese Yuan</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple currencies</p>
                    @error('supported_currencies')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Supported Countries -->
                <div>
                    <label for="supported_countries" class="mb-2.5 block text-black dark:text-white">
                        Supported Countries
                    </label>
                    <select
                        id="supported_countries"
                        name="supported_countries[]"
                        multiple
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('supported_countries') border-red-500 @enderror"
                        size="5"
                    >
                        <option value="PH" {{ in_array('PH', old('supported_countries', [])) ? 'selected' : '' }}>PH - Philippines</option>
                        <option value="US" {{ in_array('US', old('supported_countries', [])) ? 'selected' : '' }}>US - United States</option>
                        <option value="GB" {{ in_array('GB', old('supported_countries', [])) ? 'selected' : '' }}>GB - United Kingdom</option>
                        <option value="CA" {{ in_array('CA', old('supported_countries', [])) ? 'selected' : '' }}>CA - Canada</option>
                        <option value="AU" {{ in_array('AU', old('supported_countries', [])) ? 'selected' : '' }}>AU - Australia</option>
                        <option value="JP" {{ in_array('JP', old('supported_countries', [])) ? 'selected' : '' }}>JP - Japan</option>
                        <option value="CN" {{ in_array('CN', old('supported_countries', [])) ? 'selected' : '' }}>CN - China</option>
                        <option value="SG" {{ in_array('SG', old('supported_countries', [])) ? 'selected' : '' }}>SG - Singapore</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple countries</p>
                    @error('supported_countries')
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
                        value="{{ old('sort_order', 0) }}"
                        min="0"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('sort_order') border-red-500 @enderror"
                        placeholder="0"
                    />
                    <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
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
                        name="is_active"
                        value="1"
                        {{ old('is_active', false) ? 'checked' : '' }}
                        class="mr-2 rounded border-stroke dark:border-strokedark"
                    />
                    <span class="text-black dark:text-white">Active</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        name="is_test_mode"
                        value="1"
                        {{ old('is_test_mode', true) ? 'checked' : '' }}
                        class="mr-2 rounded border-stroke dark:border-strokedark"
                    />
                    <span class="text-black dark:text-white">Test Mode</span>
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
                Create Payment Gateway
            </button>
        </div>
    </form>
</div>
@endsection

