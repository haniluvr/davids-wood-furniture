@extends('admin.layouts.app')

@section('title', 'Edit Payment Gateway')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="credit-card" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Edit Payment Gateway</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Edit "{{ $paymentGateway->display_name }}"</p>
                </div>
            </div>
            <a href="{{ admin_route('payment-gateways.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Payment Gateways
            </a>
        </div>
    </div>

    <form action="{{ admin_route('payment-gateways.update', $paymentGateway) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                        <i data-lucide="info" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Basic Information</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update the essential details about the payment gateway</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Gateway Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $paymentGateway->name) }}"
                            required
                            placeholder="e.g., Stripe, PayPal"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('name') border-red-300 @enderror"
                        />
                        @error('name')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="gateway_key" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Gateway Key <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="gateway_key"
                            name="gateway_key"
                            value="{{ old('gateway_key', $paymentGateway->gateway_key) }}"
                            required
                            placeholder="e.g., stripe, paypal"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('gateway_key') border-red-300 @enderror"
                        />
                        <p class="mt-1 text-xs text-stone-500 dark:text-gray-400">Unique identifier (lowercase, no spaces)</p>
                        @error('gateway_key')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="config_type" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Payment Type
                        </label>
                        <select
                            id="config_type"
                            name="config[type]"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('config.type') border-red-300 @enderror"
                        >
                            <option value="">Select Payment Type</option>
                            @php
                                $currentType = old('config.type', $paymentGateway->getConfigValue('type'));
                                // Handle backward compatibility: if types array exists and contains 'multiple', use 'multiple'
                                $typesArray = $paymentGateway->getConfigValue('types', []);
                                if (is_array($typesArray) && in_array('multiple', $typesArray)) {
                                    $currentType = 'multiple';
                                }
                            @endphp
                            <option value="credit_card" {{ $currentType === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="debit_card" {{ $currentType === 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                            <option value="bank_transfer" {{ $currentType === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="e_wallet" {{ $currentType === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="retail_outlet" {{ $currentType === 'retail_outlet' ? 'selected' : '' }}>Retail Outlet</option>
                            <option value="qr_code" {{ $currentType === 'qr_code' ? 'selected' : '' }}>QR Code</option>
                            <option value="direct_debit" {{ $currentType === 'direct_debit' ? 'selected' : '' }}>Direct Debit</option>
                            <option value="multiple" {{ $currentType === 'multiple' ? 'selected' : '' }}>Multiple</option>
                            <option value="cryptocurrency" {{ $currentType === 'cryptocurrency' ? 'selected' : '' }}>Cryptocurrency</option>
                            <option value="other" {{ $currentType === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('config.type')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="config_status" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Status
                        </label>
                        <select
                            id="config_status"
                            name="config[status]"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('config.status') border-red-300 @enderror"
                        >
                            @php
                                $currentStatus = old('config.status', $paymentGateway->getConfigValue('status'));
                                if (!$currentStatus) {
                                    $currentStatus = $paymentGateway->is_active ? 'active' : 'inactive';
                                }
                            @endphp
                            <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="maintenance" {{ $currentStatus === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('config.status')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="display_name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Display Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="display_name"
                        name="display_name"
                        value="{{ old('display_name', $paymentGateway->display_name) }}"
                        required
                        placeholder="e.g., Stripe Payment Gateway"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('display_name') border-red-300 @enderror"
                    />
                    @error('display_name')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="description" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Brief description of the payment gateway"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('description') border-red-300 @enderror"
                    >{{ old('description', $paymentGateway->description) }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Payment Methods (Conditional - shown when "Multiple" is selected) -->
        <div id="payment-methods-section" class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden" style="display: none;">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                        <i data-lucide="credit-card" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Payment Methods</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Enable the payment methods you want to support for this gateway</p>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $existingMethods = old('config.enabled_methods', $paymentGateway->getConfigValue('enabled_methods', []));
                        if (!is_array($existingMethods)) {
                            $existingMethods = [];
                        }
                        // Map to Xendit payment method codes
                        $xenditMethods = [
                            'CREDIT_CARD' => 'Credit Card',
                            'DEBIT_CARD' => 'Debit Card',
                            'EWALLET' => 'E-Wallet',
                            'BANK_TRANSFER' => 'Bank Transfer',
                            'RETAIL_OUTLET' => 'Retail Outlet',
                            'QR_CODE' => 'QR Code',
                            'DIRECT_DEBIT' => 'Direct Debit',
                        ];
                    @endphp
                    @foreach($xenditMethods as $key => $label)
                        <label class="flex items-center p-4 bg-stone-50 dark:bg-gray-800 rounded-xl hover:bg-stone-100 dark:hover:bg-gray-700 cursor-pointer transition-colors border border-stone-200 dark:border-strokedark">
                            <input
                                type="checkbox"
                                name="config[enabled_methods][]"
                                value="{{ $key }}"
                                {{ in_array($key, $existingMethods) ? 'checked' : '' }}
                                class="h-5 w-5 text-primary focus:ring-primary border-stone-300 rounded"
                            />
                            <span class="ml-3 text-sm font-medium text-stone-700 dark:text-stone-300">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('config.enabled_methods')
                    <p class="mt-4 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Configuration Settings -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-green-50 to-blue-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                        <i data-lucide="settings" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Configuration Settings</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Configure API keys and webhook settings</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="config_api_key" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            API Key
                        </label>
                        <input
                            type="password"
                            id="config_api_key"
                            name="config[api_key]"
                            value="{{ old('config.api_key') }}"
                            placeholder="{{ $paymentGateway->getConfigValue('api_key') ? '•••••••• (leave blank to keep current)' : 'Enter API key' }}"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('config.api_key') border-red-300 @enderror"
                        />
                        <p class="mt-1 text-xs text-stone-500 dark:text-gray-400">Leave blank to keep current value</p>
                        @error('config.api_key')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="config_secret_key" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Secret Key
                        </label>
                        <input
                            type="password"
                            id="config_secret_key"
                            name="config[secret_key]"
                            value="{{ old('config.secret_key') }}"
                            placeholder="{{ $paymentGateway->getConfigValue('secret_key') ? '•••••••• (leave blank to keep current)' : 'Enter secret key' }}"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('config.secret_key') border-red-300 @enderror"
                        />
                        <p class="mt-1 text-xs text-stone-500 dark:text-gray-400">Leave blank to keep current value</p>
                        @error('config.secret_key')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="config_webhook_url" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Webhook URL
                        </label>
                        <input
                            type="url"
                            id="config_webhook_url"
                            name="config[webhook_url]"
                            value="{{ old('config.webhook_url', $paymentGateway->getConfigValue('webhook_url', '')) }}"
                            placeholder="https://example.com/webhook"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('config.webhook_url') border-red-300 @enderror"
                        />
                        @error('config.webhook_url')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="config_webhook_secret" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Webhook Secret
                        </label>
                        <input
                            type="password"
                            id="config_webhook_secret"
                            name="config[webhook_secret]"
                            value="{{ old('config.webhook_secret') }}"
                            placeholder="{{ $paymentGateway->getConfigValue('webhook_secret') ? '•••••••• (leave blank to keep current)' : 'Enter webhook secret' }}"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('config.webhook_secret') border-red-300 @enderror"
                        />
                        <p class="mt-1 text-xs text-stone-500 dark:text-gray-400">Leave blank to keep current value</p>
                        @error('config.webhook_secret')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="config_environment" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Environment
                        </label>
                        <select
                            id="config_environment"
                            name="config[environment]"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('config.environment') border-red-300 @enderror"
                        >
                            @php
                                $currentEnvironment = old('config.environment', $paymentGateway->getConfigValue('environment'));
                                if (!$currentEnvironment) {
                                    $currentEnvironment = $paymentGateway->is_test_mode ? 'sandbox' : 'production';
                                }
                            @endphp
                            <option value="sandbox" {{ $currentEnvironment === 'sandbox' ? 'selected' : '' }}>Sandbox/Test</option>
                            <option value="production" {{ $currentEnvironment === 'production' ? 'selected' : '' }}>Production</option>
                        </select>
                        @error('config.environment')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee Configuration -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl">
                        <i data-lucide="dollar-sign" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Fee Configuration</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Set transaction fees for this payment gateway</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="transaction_fee_percentage" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Transaction Fee Percentage
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                id="transaction_fee_percentage"
                                name="transaction_fee_percentage"
                                value="{{ old('transaction_fee_percentage', $paymentGateway->transaction_fee_percentage) }}"
                                step="0.01"
                                min="0"
                                max="100"
                                placeholder="0.00"
                                class="w-full rounded-xl border border-stone-200 bg-white pr-8 pl-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('transaction_fee_percentage') border-red-300 @enderror"
                            />
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-500 text-sm">%</span>
                        </div>
                        @error('transaction_fee_percentage')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="transaction_fee_fixed" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Transaction Fee Fixed
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-500 text-sm">₱</span>
                            <input
                                type="number"
                                id="transaction_fee_fixed"
                                name="transaction_fee_fixed"
                                value="{{ old('transaction_fee_fixed', $paymentGateway->transaction_fee_fixed) }}"
                                step="0.01"
                                min="0"
                                placeholder="0.00"
                                class="w-full rounded-xl border border-stone-200 bg-white pl-8 pr-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('transaction_fee_fixed') border-red-300 @enderror"
                            />
                        </div>
                        @error('transaction_fee_fixed')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Supported Currencies and Countries -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-indigo-50 to-cyan-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-cyan-600 rounded-xl">
                        <i data-lucide="globe" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Supported Currencies & Countries</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Select supported currencies and countries for this gateway</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Supported Currencies
                        </label>
                        <p class="text-xs text-stone-500 dark:text-gray-400 mb-3">Select all currencies supported by this gateway</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @php
                                $selectedCurrencies = old('supported_currencies', $paymentGateway->supported_currencies ?? []);
                                if (!is_array($selectedCurrencies)) {
                                    $selectedCurrencies = [];
                                }
                                $currencies = [
                                    'PHP' => 'PHP - Philippine Peso',
                                    'USD' => 'USD - US Dollar',
                                    'EUR' => 'EUR - Euro',
                                    'GBP' => 'GBP - British Pound',
                                    'CAD' => 'CAD - Canadian Dollar',
                                    'AUD' => 'AUD - Australian Dollar',
                                    'JPY' => 'JPY - Japanese Yen',
                                    'CNY' => 'CNY - Chinese Yuan',
                                ];
                            @endphp
                            @foreach($currencies as $code => $label)
                                <label class="flex items-center p-3 bg-stone-50 dark:bg-gray-800 rounded-lg hover:bg-stone-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input
                                        type="checkbox"
                                        name="supported_currencies[]"
                                        value="{{ $code }}"
                                        {{ in_array($code, $selectedCurrencies) ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary focus:ring-primary border-stone-300 rounded"
                                    />
                                    <span class="ml-2 text-sm text-stone-700 dark:text-stone-300">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('supported_currencies')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Supported Countries
                        </label>
                        <p class="text-xs text-stone-500 dark:text-gray-400 mb-3">Select all countries supported by this gateway</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @php
                                $selectedCountries = old('supported_countries', $paymentGateway->supported_countries ?? []);
                                if (!is_array($selectedCountries)) {
                                    $selectedCountries = [];
                                }
                                $countries = [
                                    'PH' => 'PH - Philippines',
                                    'US' => 'US - United States',
                                    'GB' => 'GB - United Kingdom',
                                    'CA' => 'CA - Canada',
                                    'AU' => 'AU - Australia',
                                    'JP' => 'JP - Japan',
                                    'CN' => 'CN - China',
                                    'SG' => 'SG - Singapore',
                                ];
                            @endphp
                            @foreach($countries as $code => $label)
                                <label class="flex items-center p-3 bg-stone-50 dark:bg-gray-800 rounded-lg hover:bg-stone-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input
                                        type="checkbox"
                                        name="supported_countries[]"
                                        value="{{ $code }}"
                                        {{ in_array($code, $selectedCountries) ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary focus:ring-primary border-stone-300 rounded"
                                    />
                                    <span class="ml-2 text-sm text-stone-700 dark:text-stone-300">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('supported_countries')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Settings -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl">
                        <i data-lucide="sliders" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Additional Settings</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Configure gateway status and display order</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="space-y-2">
                    <label for="sort_order" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Sort Order
                    </label>
                    <input
                        type="number"
                        id="sort_order"
                        name="sort_order"
                        value="{{ old('sort_order', $paymentGateway->sort_order) }}"
                        min="0"
                        placeholder="0"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('sort_order') border-red-300 @enderror"
                    />
                    <p class="mt-1 text-xs text-stone-500 dark:text-gray-400">Lower numbers appear first</p>
                    @error('sort_order')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="flex items-center p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                        <input
                            type="checkbox"
                            name="is_active"
                            id="is_active"
                            value="1"
                            {{ old('is_active', $paymentGateway->is_active) ? 'checked' : '' }}
                            class="h-5 w-5 text-primary focus:ring-primary border-stone-300 rounded"
                        />
                        <label for="is_active" class="ml-3 block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Active
                        </label>
                    </div>

                    <div class="flex items-center p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                        <input
                            type="checkbox"
                            name="is_test_mode"
                            id="is_test_mode"
                            value="1"
                            {{ old('is_test_mode', $paymentGateway->is_test_mode) ? 'checked' : '' }}
                            class="h-5 w-5 text-primary focus:ring-primary border-stone-300 rounded"
                        />
                        <label for="is_test_mode" class="ml-3 block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Test Mode
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ admin_route('payment-gateways.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 border border-stone-200 bg-white text-sm font-medium text-stone-700 rounded-xl transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-blue-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-emerald-700 hover:to-blue-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                <i data-lucide="save" class="w-4 h-4"></i>
                Update Payment Gateway
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethodsSection = document.getElementById('payment-methods-section');
        const paymentTypeSelect = document.getElementById('config_type');

        function togglePaymentMethodsSection() {
            // Check if "Multiple" is selected
            if (paymentTypeSelect && paymentTypeSelect.value === 'multiple') {
                paymentMethodsSection.style.display = 'block';
            } else {
                paymentMethodsSection.style.display = 'none';
            }
        }

        // Check on page load
        togglePaymentMethodsSection();

        // Check on select change
        if (paymentTypeSelect) {
            paymentTypeSelect.addEventListener('change', togglePaymentMethodsSection);
        }
    });
</script>
@endpush
@endsection
