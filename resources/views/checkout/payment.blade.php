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
            <div class="payment-method-option border-2 rounded-lg p-4 {{ old('payment_method') == 'cod' ? 'border-[#8b7355] bg-[#8b7355]/5' : 'border-gray-200' }} {{ !$codEligible ? 'opacity-50' : '' }}" style="transition: none !important;">
                <label class="flex items-center {{ !$codEligible ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                    <input type="radio" 
                           name="payment_method" 
                           value="cod" 
                           class="h-4 w-4 text-[#8b7355] focus:ring-[#8b7355] border-gray-300"
                           {{ old('payment_method') == 'cod' ? 'checked' : '' }}
                           {{ !$codEligible ? 'disabled' : '' }}
                           onclick="updatePaymentMethodBorders(this);"
                           onchange="updatePaymentMethodBorders(this);">
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
                <div class="payment-method-option border-2 rounded-lg p-4 {{ old('payment_method') == 'existing' && old('payment_method_id') == $paymentMethod->id ? 'border-[#8b7355] bg-[#8b7355]/5' : 'border-gray-200' }}" style="transition: none !important;">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" 
                               name="payment_method" 
                               value="existing" 
                               data-payment-method-id="{{ $paymentMethod->id }}"
                               class="h-4 w-4 text-[#8b7355] focus:ring-[#8b7355] border-gray-300"
                               {{ old('payment_method') == 'existing' && old('payment_method_id') == $paymentMethod->id ? 'checked' : '' }}
                               onclick="updatePaymentMethodBorders(this);"
                               onchange="updatePaymentMethodBorders(this);">
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
            
            <!-- Online Payment via Xendit -->
            <div class="payment-method-option border-2 rounded-lg p-4 {{ old('payment_method') == 'xendit' || old('payment_method') == 'new' || !old('payment_method') ? 'border-[#8b7355] bg-[#8b7355]/5' : 'border-gray-200' }}" style="transition: none !important;">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" 
                           name="payment_method" 
                           value="xendit" 
                           class="h-4 w-4 text-[#8b7355] focus:ring-[#8b7355] border-gray-300"
                           {{ old('payment_method') == 'xendit' || old('payment_method') == 'new' || !old('payment_method') ? 'checked' : '' }}
                           onclick="updatePaymentMethodBorders(this);"
                           onchange="updatePaymentMethodBorders(this);">
                    <div class="ml-3 flex-1">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i data-lucide="credit-card" class="w-5 h-5 text-gray-600 mr-2"></i>
                                <span class="text-lg font-medium text-gray-900">Online Payment</span>
                            </div>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Powered by Xendit</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            Pay securely with credit/debit card, GCash, PayMaya, or other payment methods
                        </p>
                        <div class="mt-2 flex items-center gap-2 flex-wrap">
                            <span class="text-xs text-gray-500">Accepts:</span>
                            <span class="text-xs font-medium text-gray-700">VISA</span>
                            <span class="text-xs font-medium text-gray-700">Mastercard</span>
                            <span class="text-xs font-medium text-gray-700">GCash</span>
                            <span class="text-xs font-medium text-gray-700">PayMaya</span>
                            <span class="text-xs text-gray-500">and more</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>
        
        <!-- Hidden field for payment method ID -->
        <input type="hidden" name="payment_method_id" id="payment_method_id" value="">
        
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
        
        <!-- Xendit Payment Info -->
        <div id="xendit-form" class="hidden">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5"></i>
                    <div class="text-sm text-gray-700">
                        <p class="font-medium text-gray-900 mb-1">Secure Payment Processing</p>
                        <p>You will be redirected to Xendit's secure payment page to complete your payment. Xendit supports multiple payment methods including credit/debit cards, GCash, PayMaya, and more.</p>
                    </div>
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
                    id="continue-to-review-btn"
                    class="bg-[#8b7355] text-white px-8 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold">
                Continue to Review
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function updatePaymentMethodBorders(selectedRadio) {
    if (!selectedRadio) return;
    
    // Update borders instantly - no transitions, no hover effects, no delays
    document.querySelectorAll('.payment-method-option').forEach(option => {
        // Force remove all transitions and inline styles immediately
        option.style.setProperty('transition', 'none', 'important');
        option.style.setProperty('transition-duration', '0s', 'important');
        option.style.borderColor = ''; // Clear any inline border color
        
        // Get the radio button within this option
        const radioInOption = option.querySelector('input[type="radio"][name="payment_method"]');
        const isSelected = radioInOption && (radioInOption === selectedRadio || radioInOption.checked);
        
        // Remove all border classes first
        option.classList.remove('border-[#8b7355]', 'bg-[#8b7355]/5', 'border-gray-200');
        
        if (isSelected) {
            // Selected: always use brown border (#8b7355)
            option.classList.add('border-[#8b7355]', 'bg-[#8b7355]/5');
        } else {
            // Not selected: use default gray border
            option.classList.add('border-gray-200');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Get payment method radios and forms
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    const codForm = document.getElementById('cod-form');
    const existingPaymentForm = document.getElementById('existing-payment-form');
    const xenditForm = document.getElementById('xendit-form');
    const paymentMethodIdField = document.getElementById('payment_method_id');
    
    // Initialize borders for selected payment method
    const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
    if (selectedMethod) {
        updatePaymentMethodBorders(selectedMethod);
    }
    
    // Handle payment method selection
    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            updatePaymentMethodBorders(this);
            
            // Hide all forms
            if (codForm) codForm.classList.add('hidden');
            if (existingPaymentForm) existingPaymentForm.classList.add('hidden');
            if (xenditForm) xenditForm.classList.add('hidden');
            
            // Clear payment_method_id
            if (paymentMethodIdField) {
                paymentMethodIdField.value = '';
            }
            
            // Show appropriate form
            if (this.value === 'cod') {
                if (codForm) codForm.classList.remove('hidden');
            } else if (this.value === 'existing') {
                const selectedRadio = document.querySelector('input[name="payment_method"][value="existing"]:checked');
                if (selectedRadio && paymentMethodIdField) {
                    const paymentMethodId = selectedRadio.getAttribute('data-payment-method-id');
                    if (paymentMethodId) {
                        paymentMethodIdField.value = paymentMethodId;
                    }
                }
                if (existingPaymentForm) existingPaymentForm.classList.remove('hidden');
            } else if (this.value === 'xendit') {
                if (xenditForm) xenditForm.classList.remove('hidden');
            }
        });
    });
    
    // Initialize form visibility based on selected method
    if (selectedMethod) {
        selectedMethod.dispatchEvent(new Event('change'));
    }
    
    // Form submission handler
    const paymentForm = document.getElementById('payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
            
            if (!selectedMethod) {
                e.preventDefault();
                alert('Please select a payment method.');
                return false;
            }
            
            // For COD, ensure payment_method_id is cleared
            if (selectedMethod.value === 'cod') {
                if (paymentMethodIdField) {
                    paymentMethodIdField.value = '';
                }
            }
            
            // For xendit, also clear payment_method_id
            if (selectedMethod.value === 'xendit') {
                if (paymentMethodIdField) {
                    paymentMethodIdField.value = '';
                }
            }
            
            // Disable button to prevent double submission
            const continueBtn = document.getElementById('continue-to-review-btn');
            if (continueBtn) {
                continueBtn.disabled = true;
                continueBtn.textContent = 'Processing...';
            }
        });
    }
});
</script>
@endpush
