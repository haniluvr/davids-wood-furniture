@extends('admin.layouts.app')

@section('title', 'Payment Settings')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Payment Settings
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ route('admin.dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ route('admin.settings.index') }}">Settings /</a>
            </li>
            <li class="font-medium text-primary">Payment</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.settings.payment.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Payment Methods -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Payment Methods</h4>
            
            <div class="space-y-4">
                <!-- Credit Card -->
                <div class="flex items-center justify-between p-4 border border-stroke dark:border-strokedark rounded-lg">
                    <div class="flex items-center gap-3">
                        <i data-lucide="credit-card" class="w-6 h-6 text-primary"></i>
                        <div>
                            <h5 class="font-medium text-black dark:text-white">Credit Card</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Accept Visa, Mastercard, American Express</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="payment_credit_card" value="1" {{ old('payment_credit_card', setting('payment_credit_card', true)) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                    </label>
                </div>

                <!-- PayPal -->
                <div class="flex items-center justify-between p-4 border border-stroke dark:border-strokedark rounded-lg">
                    <div class="flex items-center gap-3">
                        <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>
                        <div>
                            <h5 class="font-medium text-black dark:text-white">PayPal</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">PayPal Express Checkout</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="payment_paypal" value="1" {{ old('payment_paypal', setting('payment_paypal', false)) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                    </label>
                </div>

                <!-- Bank Transfer -->
                <div class="flex items-center justify-between p-4 border border-stroke dark:border-strokedark rounded-lg">
                    <div class="flex items-center gap-3">
                        <i data-lucide="building-2" class="w-6 h-6 text-green-600"></i>
                        <div>
                            <h5 class="font-medium text-black dark:text-white">Bank Transfer</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Direct bank transfer payments</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="payment_bank_transfer" value="1" {{ old('payment_bank_transfer', setting('payment_bank_transfer', false)) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                    </label>
                </div>

                <!-- Cash on Delivery -->
                <div class="flex items-center justify-between p-4 border border-stroke dark:border-strokedark rounded-lg">
                    <div class="flex items-center gap-3">
                        <i data-lucide="banknote" class="w-6 h-6 text-yellow-600"></i>
                        <div>
                            <h5 class="font-medium text-black dark:text-white">Cash on Delivery</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pay when the order is delivered</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="payment_cod" value="1" {{ old('payment_cod', setting('payment_cod', false)) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Payment Processing -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Payment Processing</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Auto Capture -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="auto_capture"
                            value="1"
                            {{ old('auto_capture', setting('auto_capture', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Auto Capture Payments</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Automatically capture payments when orders are placed</p>
                </div>

                <!-- Manual Capture -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="manual_capture"
                            value="1"
                            {{ old('manual_capture', setting('manual_capture', false)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Manual Capture</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manually capture payments after order verification</p>
                </div>

                <!-- Payment Timeout -->
                <div>
                    <label for="payment_timeout" class="mb-2.5 block text-black dark:text-white">
                        Payment Timeout (minutes)
                    </label>
                    <input
                        type="number"
                        id="payment_timeout"
                        name="payment_timeout"
                        value="{{ old('payment_timeout', setting('payment_timeout', 30)) }}"
                        min="5"
                        max="120"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('payment_timeout') border-red-500 @enderror"
                    />
                    @error('payment_timeout')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Failed Payment Retry -->
                <div>
                    <label for="payment_retry_attempts" class="mb-2.5 block text-black dark:text-white">
                        Failed Payment Retry Attempts
                    </label>
                    <input
                        type="number"
                        id="payment_retry_attempts"
                        name="payment_retry_attempts"
                        value="{{ old('payment_retry_attempts', setting('payment_retry_attempts', 3)) }}"
                        min="0"
                        max="10"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('payment_retry_attempts') border-red-500 @enderror"
                    />
                    @error('payment_retry_attempts')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Refund Settings -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Refund Settings</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Auto Refund -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="auto_refund"
                            value="1"
                            {{ old('auto_refund', setting('auto_refund', false)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Auto Process Refunds</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Automatically process refunds for cancelled orders</p>
                </div>

                <!-- Refund Processing Time -->
                <div>
                    <label for="refund_processing_days" class="mb-2.5 block text-black dark:text-white">
                        Refund Processing Time (days)
                    </label>
                    <input
                        type="number"
                        id="refund_processing_days"
                        name="refund_processing_days"
                        value="{{ old('refund_processing_days', setting('refund_processing_days', 3)) }}"
                        min="1"
                        max="30"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('refund_processing_days') border-red-500 @enderror"
                    />
                    @error('refund_processing_days')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Partial Refunds -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="allow_partial_refunds"
                            value="1"
                            {{ old('allow_partial_refunds', setting('allow_partial_refunds', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Allow Partial Refunds</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Allow refunding partial amounts</p>
                </div>

                <!-- Refund Reason Required -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="refund_reason_required"
                            value="1"
                            {{ old('refund_reason_required', setting('refund_reason_required', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Require Refund Reason</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Require a reason for all refunds</p>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Security Settings</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- 3D Secure -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="require_3d_secure"
                            value="1"
                            {{ old('require_3d_secure', setting('require_3d_secure', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Require 3D Secure</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Enable 3D Secure authentication for card payments</p>
                </div>

                <!-- CVV Required -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="require_cvv"
                            value="1"
                            {{ old('require_cvv', setting('require_cvv', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Require CVV</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Require CVV code for card payments</p>
                </div>

                <!-- PCI Compliance -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="pci_compliant"
                            value="1"
                            {{ old('pci_compliant', setting('pci_compliant', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">PCI DSS Compliant</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Ensure PCI DSS compliance for card data</p>
                </div>

                <!-- Fraud Detection -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="fraud_detection"
                            value="1"
                            {{ old('fraud_detection', setting('fraud_detection', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Enable Fraud Detection</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Use fraud detection services</p>
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
