@extends('checkout.layout')

@section('title', 'Payment Method')

@php
    $currentStep = 2;
@endphp

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Payment Method</h2>
    
    <form action="{{ route('checkout.validate-payment') }}" method="POST" id="payment-form">
        @csrf
        
        <!-- Payment Loading Indicator -->
        <div id="payment-loading" class="hidden bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-700 mr-2"></div>
                Processing payment...
            </div>
        </div>
        
        <!-- Payment Method Selection -->
        <div class="space-y-4 mb-8">
            <!-- Cash on Delivery -->
            <div class="border border-gray-200 rounded-lg p-4 {{ !$codEligible ? 'opacity-50' : '' }}">
                <label class="flex items-center {{ !$codEligible ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                    <input type="radio" 
                           name="payment_method" 
                           value="cod" 
                           class="h-4 w-4 text-[#8b7355] focus:ring-[#8b7355] border-gray-300"
                           {{ old('payment_method') == 'cod' ? 'checked' : '' }}
                           {{ !$codEligible ? 'disabled' : '' }}>
                    <div class="ml-3">
                        <div class="flex items-center">
                            <i data-lucide="banknote" class="w-5 h-5 text-gray-600 mr-2"></i>
                            <span class="text-lg font-medium {{ !$codEligible ? 'text-gray-500' : 'text-gray-900' }}">Cash on Delivery</span>
                        </div>
                        <p class="text-sm {{ !$codEligible ? 'text-gray-500' : 'text-gray-600' }} mt-1">
                            Pay when your order arrives
                            @if(!$codEligible)
                                <span class="text-red-600 font-medium">(Not available for orders over ₱3,000)</span>
                            @endif
                        </p>
                    </div>
                </label>
            </div>
            
            <!-- Saved Payment Methods -->
            @if($paymentMethods->count() > 0)
                @foreach($paymentMethods as $paymentMethod)
                <div class="border border-gray-200 rounded-lg p-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" 
                               name="payment_method" 
                               value="existing" 
                               data-payment-method-id="{{ $paymentMethod->id }}"
                               class="h-4 w-4 text-[#8b7355] focus:ring-[#8b7355] border-gray-300"
                               {{ old('payment_method') == 'existing' && old('payment_method_id') == $paymentMethod->id ? 'checked' : '' }}>
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if($paymentMethod->isCard())
                                        <i data-lucide="credit-card" class="w-5 h-5 text-gray-600 mr-2"></i>
                                    @else
                                        <i data-lucide="smartphone" class="w-5 h-5 text-gray-600 mr-2"></i>
                                    @endif
                                    <span class="text-lg font-medium text-gray-900">{{ $paymentMethod->getDisplayName() }}</span>
                                    @if($paymentMethod->is_default)
                                        <span class="ml-2 px-2 py-1 text-xs font-medium bg-[#8b7355] text-white rounded">Default</span>
                                    @endif
                                </div>
                                @if($paymentMethod->isCard() && $paymentMethod->isExpired())
                                    <span class="text-red-600 text-sm font-medium">Expired</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($paymentMethod->isCard())
                                    {{ $paymentMethod->getMaskedNumber() }} • Expires {{ $paymentMethod->getFormattedExpiry() }}
                                @else
                                    {{ $paymentMethod->gcash_name }}
                                @endif
                            </p>
                        </div>
                    </label>
                </div>
                @endforeach
            @endif
            
            <!-- Add New Payment Method -->
            <div class="border border-gray-200 rounded-lg p-4">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" 
                           name="payment_method" 
                           value="new" 
                           class="h-4 w-4 text-[#8b7355] focus:ring-[#8b7355] border-gray-300"
                           {{ old('payment_method') == 'new' ? 'checked' : '' }}>
                    <div class="ml-3">
                        <div class="flex items-center">
                            <i data-lucide="plus" class="w-5 h-5 text-gray-600 mr-2"></i>
                            <span class="text-lg font-medium text-gray-900">Add New Payment Method</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Add a new credit card or GCash account</p>
                    </div>
                </label>
            </div>
        </div>
        
        <!-- Hidden field for payment method ID -->
        <input type="hidden" name="payment_method_id" id="payment_method_id" value="{{ old('payment_method_id') }}">
        
        <!-- New Payment Method Form -->
        <div id="new-payment-form" class="hidden">
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h3>
                
                <!-- Payment Type Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Payment Type</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="new_payment_type" 
                                   value="card" 
                                   class="h-4 w-4 text-[#8b7355] focus:ring-[#8b7355] border-gray-300"
                                   {{ old('new_payment_type') == 'card' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Credit/Debit Card</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="new_payment_type" 
                                   value="gcash" 
                                   class="h-4 w-4 text-[#8b7355] focus:ring-[#8b7355] border-gray-300"
                                   {{ old('new_payment_type') == 'gcash' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">GCash</span>
                        </label>
                    </div>
                </div>
                
                <!-- Card Form -->
                <div id="card-form" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">Card Number *</label>
                            <input type="text" 
                                   id="card_number" 
                                   name="card_number" 
                                   value="{{ old('card_number') }}"
                                   placeholder="1234 5678 9012 3456"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('card_number') border-red-500 @enderror">
                            @error('card_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="card_holder_name" class="block text-sm font-medium text-gray-700 mb-2">Cardholder Name *</label>
                            <input type="text" 
                                   id="card_holder_name" 
                                   name="card_holder_name" 
                                   value="{{ old('card_holder_name') }}"
                                   placeholder="John Doe"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('card_holder_name') border-red-500 @enderror">
                            @error('card_holder_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="card_expiry_month" class="block text-sm font-medium text-gray-700 mb-2">Expiry Month *</label>
                            <select id="card_expiry_month" 
                                    name="card_expiry_month" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('card_expiry_month') border-red-500 @enderror">
                                <option value="">Month</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('card_expiry_month') == $i ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                @endfor
                            </select>
                            @error('card_expiry_month')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="card_expiry_year" class="block text-sm font-medium text-gray-700 mb-2">Expiry Year *</label>
                            <select id="card_expiry_year" 
                                    name="card_expiry_year" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('card_expiry_year') border-red-500 @enderror">
                                <option value="">Year</option>
                                @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                    <option value="{{ $i }}" {{ old('card_expiry_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('card_expiry_year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="card_cvv" class="block text-sm font-medium text-gray-700 mb-2">CVV *</label>
                            <input type="text" 
                                   id="card_cvv" 
                                   name="card_cvv" 
                                   value="{{ old('card_cvv') }}"
                                   placeholder="123"
                                   maxlength="4"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('card_cvv') border-red-500 @enderror">
                            @error('card_cvv')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Billing Address -->
                    <div class="mt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Billing Address</h4>
                        <div class="space-y-6">
                            <div>
                                <label for="billing_address_line_1" class="block text-sm font-medium text-gray-700 mb-2">Address Line 1 *</label>
                                <input type="text" 
                                       id="billing_address_line_1" 
                                       name="billing_address[address_line_1]" 
                                       value="{{ old('billing_address.address_line_1') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('billing_address.address_line_1') border-red-500 @enderror">
                                @error('billing_address.address_line_1')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="billing_region" class="block text-sm font-medium text-gray-700 mb-2">Region *</label>
                                    <select id="billing_region" 
                                            name="billing_address[region]" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('billing_address.region') border-red-500 @enderror">
                                        <option value="">Select Region</option>
                                    </select>
                                    @error('billing_address.region')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="billing_province" class="block text-sm font-medium text-gray-700 mb-2">Province *</label>
                                    <select id="billing_province" 
                                            name="billing_address[province]" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('billing_address.province') border-red-500 @enderror"
                                            disabled>
                                        <option value="">Select Province</option>
                                    </select>
                                    @error('billing_address.province')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-2">City/Municipality *</label>
                                    <select id="billing_city" 
                                            name="billing_address[city]" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('billing_address.city') border-red-500 @enderror"
                                            disabled>
                                        <option value="">Select City/Municipality</option>
                                    </select>
                                    @error('billing_address.city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="billing_barangay" class="block text-sm font-medium text-gray-700 mb-2">Barangay</label>
                                    <select id="billing_barangay" 
                                            name="billing_address[barangay]" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('billing_address.barangay') border-red-500 @enderror"
                                            disabled>
                                        <option value="">Select Barangay</option>
                                    </select>
                                    @error('billing_address.barangay')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="billing_zip_code" class="block text-sm font-medium text-gray-700 mb-2">ZIP Code *</label>
                                <input type="text" 
                                       id="billing_zip_code" 
                                       name="billing_address[zip_code]" 
                                       value="{{ old('billing_address.zip_code') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('billing_address.zip_code') border-red-500 @enderror">
                                @error('billing_address.zip_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- GCash Form -->
                <div id="gcash-form" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="gcash_number" class="block text-sm font-medium text-gray-700 mb-2">GCash Mobile Number *</label>
                            <input type="tel" 
                                   id="gcash_number" 
                                   name="gcash_number" 
                                   value="{{ old('gcash_number') }}"
                                   placeholder="09123456789"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('gcash_number') border-red-500 @enderror">
                            @error('gcash_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="gcash_name" class="block text-sm font-medium text-gray-700 mb-2">Account Name *</label>
                            <input type="text" 
                                   id="gcash_name" 
                                   name="gcash_name" 
                                   value="{{ old('gcash_name') }}"
                                   placeholder="John Doe"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('gcash_name') border-red-500 @enderror">
                            @error('gcash_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- COD Form -->
        <div id="cod-form" class="hidden">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600 mr-2"></i>
                    <p class="text-sm text-gray-700">
                        You will pay when your order arrives. Please have the exact amount ready.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Existing Payment Form -->
        <div id="existing-payment-form" class="hidden">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i data-lucide="credit-card" class="w-5 h-5 text-green-600 mr-2"></i>
                    <p class="text-sm text-gray-700">
                        Using your saved payment method for this purchase.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('checkout.index') }}" 
               class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                Back to Shipping
            </a>
            <button type="submit" 
                    class="bg-[#8b7355] text-white px-8 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold">
                Continue to Review
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    const newPaymentForm = document.getElementById('new-payment-form');
    const cardForm = document.getElementById('card-form');
    const gcashForm = document.getElementById('gcash-form');
    const paymentTypeRadios = document.querySelectorAll('input[name="new_payment_type"]');
    const paymentMethodIdField = document.getElementById('payment_method_id');
    
    // Handle payment method selection
    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'new') {
                newPaymentForm.classList.remove('hidden');
            } else {
                newPaymentForm.classList.add('hidden');
            }
            
            if (this.value === 'existing') {
                const paymentMethodId = this.getAttribute('data-payment-method-id');
                paymentMethodIdField.value = paymentMethodId || '';
            } else {
                paymentMethodIdField.value = '';
            }
        });
    });
    
    // Handle payment type selection
    paymentTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'card') {
                cardForm.classList.remove('hidden');
                gcashForm.classList.add('hidden');
                // Initialize billing address PSGC when card form is shown
                if (billingRegionsData.length === 0) {
                    loadBillingRegions().catch(error => {
                        console.error('Failed to load billing regions:', error);
                    });
                }
            } else if (this.value === 'gcash') {
                cardForm.classList.add('hidden');
                gcashForm.classList.remove('hidden');
            }
        });
    });
    
    // Initialize forms based on old values
    const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
    if (selectedPaymentMethod) {
        selectedPaymentMethod.dispatchEvent(new Event('change'));
    }
    
    const selectedPaymentType = document.querySelector('input[name="new_payment_type"]:checked');
    if (selectedPaymentType) {
        selectedPaymentType.dispatchEvent(new Event('change'));
    }
    
    // Card number formatting
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function() {
            let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            this.value = formattedValue;
        });
    }
    
    // CVV formatting
    const cvvInput = document.getElementById('card_cvv');
    if (cvvInput) {
        cvvInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
    // GCash number formatting
    const gcashNumberInput = document.getElementById('gcash_number');
    if (gcashNumberInput) {
        gcashNumberInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
    // Set up billing address PSGC event listeners
    function setupBillingAddressEventListeners() {
        // Billing region select event listener
        const billingRegionSelect = document.getElementById('billing_region');
        if (billingRegionSelect) {
            billingRegionSelect.removeEventListener('change', handleBillingRegionChange);
            billingRegionSelect.addEventListener('change', handleBillingRegionChange);
        }

        // Billing province select event listener
        const billingProvinceSelect = document.getElementById('billing_province');
        if (billingProvinceSelect) {
            billingProvinceSelect.removeEventListener('change', handleBillingProvinceChange);
            billingProvinceSelect.addEventListener('change', handleBillingProvinceChange);
        }

        // Billing city select event listener
        const billingCitySelect = document.getElementById('billing_city');
        if (billingCitySelect) {
            billingCitySelect.removeEventListener('change', handleBillingCityChange);
            billingCitySelect.addEventListener('change', handleBillingCityChange);
        }
    }

    // Event handlers for billing address form
    async function handleBillingRegionChange(event) {
        const selectedRegion = event.target.value;
        if (selectedRegion) {
            // Reset dependent selects
            const billingProvinceSelect = document.getElementById('billing_province');
            const billingCitySelect = document.getElementById('billing_city');
            const billingBarangaySelect = document.getElementById('billing_barangay');
            
            if (billingProvinceSelect) {
                billingProvinceSelect.innerHTML = '<option value="">Select Province</option>';
                billingProvinceSelect.disabled = true;
            }
            if (billingCitySelect) {
                billingCitySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                billingCitySelect.disabled = true;
            }
            if (billingBarangaySelect) {
                billingBarangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                billingBarangaySelect.disabled = true;
            }
            
            // Load provinces for selected region
            await loadBillingProvinces(selectedRegion);
        }
    }

    async function handleBillingProvinceChange(event) {
        const selectedProvince = event.target.value;
        if (selectedProvince) {
            // Reset dependent selects
            const billingCitySelect = document.getElementById('billing_city');
            const billingBarangaySelect = document.getElementById('billing_barangay');
            
            if (billingCitySelect) {
                billingCitySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                billingCitySelect.disabled = true;
            }
            if (billingBarangaySelect) {
                billingBarangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                billingBarangaySelect.disabled = true;
            }
            
            // Load cities for selected province
            await loadBillingCities(selectedProvince);
        }
    }

    async function handleBillingCityChange(event) {
        const selectedCity = event.target.value;
        if (selectedCity) {
            // Reset dependent selects
            const billingBarangaySelect = document.getElementById('billing_barangay');
            
            if (billingBarangaySelect) {
                billingBarangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                billingBarangaySelect.disabled = true;
            }
            
            // Load barangays for selected city
            await loadBillingBarangays(selectedCity);
        }
    }
    
    // Initialize billing address event listeners when page loads
    setupBillingAddressEventListeners();
});
</script>
@endpush
