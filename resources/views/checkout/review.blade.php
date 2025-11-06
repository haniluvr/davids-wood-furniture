@extends('checkout.layout')

@section('title', 'Order Review')

@php
    $currentStep = 3;
@endphp

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Review Your Order</h2>
    
    <form action="{{ route('checkout.process') }}" method="POST" id="review-form">
        @csrf
        
        <!-- Shipping Information -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Shipping Information</h3>
                <a href="{{ route('checkout.index') }}" class="text-[#8b7355] hover:text-[#6b5b47] text-sm font-medium">
                    Edit
                </a>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-medium text-gray-900">{{ $shippingInfo['first_name'] }} {{ $shippingInfo['last_name'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ $shippingInfo['email'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="font-medium text-gray-900">{{ $shippingInfo['phone'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Region</p>
                        <p class="font-medium text-gray-900">{{ $shippingInfo['region'] }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-sm text-gray-600">Address</p>
                    <p class="font-medium text-gray-900">
                        {{ $shippingInfo['address_line_1'] }}
                        @if(isset($shippingInfo['address_line_2']) && $shippingInfo['address_line_2'])
                            <br>{{ $shippingInfo['address_line_2'] }}
                        @endif
                        <br>{{ $shippingInfo['city'] }}, {{ $shippingInfo['province'] ?? 'N/A' }} {{ $shippingInfo['zip_code'] }}
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Payment Method -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Payment Method</h3>
                <a href="{{ route('checkout.payment') }}" class="text-[#8b7355] hover:text-[#6b5b47] text-sm font-medium">
                    Edit
                </a>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                @if($paymentInfo['payment_method'] === 'cod')
                    <div class="flex items-center">
                        <i data-lucide="banknote" class="w-5 h-5 text-gray-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">Cash on Delivery</p>
                            <p class="text-sm text-gray-600">Pay when your order arrives</p>
                        </div>
                    </div>
                @elseif($paymentInfo['payment_method'] === 'existing' && $paymentMethod)
                    <div class="flex items-center">
                        @if($paymentMethod->isCard())
                            <i data-lucide="credit-card" class="w-5 h-5 text-gray-600 mr-3"></i>
                        @else
                            <i data-lucide="smartphone" class="w-5 h-5 text-gray-600 mr-3"></i>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900">{{ $paymentMethod->getDisplayName() }}</p>
                            <p class="text-sm text-gray-600">
                                @if($paymentMethod->isCard())
                                    {{ $paymentMethod->getMaskedNumber() }} • Expires {{ $paymentMethod->getFormattedExpiry() }}
                                @else
                                    {{ $paymentMethod->gcash_name }}
                                @endif
                            </p>
                        </div>
                    </div>
                @elseif($paymentInfo['payment_method'] === 'xendit')
                    <div class="flex items-center">
                        <i data-lucide="credit-card" class="w-5 h-5 text-gray-600 mr-3"></i>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">Online Payment</p>
                                    <p class="text-sm text-gray-600">Pay securely via Xendit (Credit/Debit Card, GCash, PayMaya, etc.)</p>
                                </div>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Powered by Xendit</span>
                            </div>
                        </div>
                    </div>
                @elseif($paymentInfo['payment_method'] === 'new')
                    <div class="flex items-center">
                        @if($paymentInfo['new_payment_type'] === 'card')
                            <i data-lucide="credit-card" class="w-5 h-5 text-gray-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">Credit/Debit Card</p>
                                <p class="text-sm text-gray-600">{{ $paymentInfo['card_holder_name'] }}</p>
                            </div>
                        @else
                            <i data-lucide="smartphone" class="w-5 h-5 text-gray-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">GCash</p>
                                <p class="text-sm text-gray-600">{{ $paymentInfo['gcash_name'] }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
            <div class="space-y-4">
                @foreach($cartItems as $item)
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        @if($item->product && $item->product->images)
                            @php
                                $images = is_string($item->product->images) ? json_decode($item->product->images, true) : $item->product->images;
                                $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                            @endphp
                            @if($firstImage)
                                <img src="{{ Storage::url($firstImage) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <i data-lucide="package" class="w-8 h-8 text-gray-400"></i>
                            @endif
                        @else
                            <i data-lucide="package" class="w-8 h-8 text-gray-400"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-lg font-medium text-gray-900">{{ $item->product_name }}</h4>
                        <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                        <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-semibold text-gray-900">₱{{ number_format($item->total_price, 2) }}</p>
                        <p class="text-sm text-gray-600">₱{{ number_format($item->unit_price, 2) }} each</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Order Notes -->
        <div class="mb-8">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Order Notes (Optional)</label>
            <textarea id="notes" 
                      name="notes" 
                      rows="3" 
                      placeholder="Any special instructions for your order..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent"></textarea>
        </div>
        
        <!-- Terms and Conditions -->
        <div class="mb-8">
            <label class="flex items-start">
                <input type="checkbox" 
                       id="terms_accepted" 
                       name="terms_accepted" 
                       class="mt-1 h-4 w-4 text-[#8b7355] focus:ring-[#8b7355] border-gray-300 rounded"
                       required>
                <span class="ml-2 text-sm text-gray-700">
                    I agree to the <a href="#" class="text-[#8b7355] hover:text-[#6b5b47] underline">Terms and Conditions</a> 
                    and <a href="#" class="text-[#8b7355] hover:text-[#6b5b47] underline">Privacy Policy</a>
                </span>
            </label>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="flex justify-between">
            <a href="{{ route('checkout.payment') }}" 
               class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                Back to Payment
            </a>
            <button type="submit" 
                    id="place-order-btn"
                    class="bg-[#8b7355] text-white px-8 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                Place Order
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('review-form');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const termsCheckbox = document.getElementById('terms_accepted');
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Please accept the Terms and Conditions to continue.');
            return;
        }
        
        // Disable button to prevent double submission
        placeOrderBtn.disabled = true;
        placeOrderBtn.textContent = 'Processing...';
    });
    
    // Enable/disable place order button based on terms acceptance
    termsCheckbox.addEventListener('change', function() {
        placeOrderBtn.disabled = !this.checked;
    });
    
    // Initialize button state
    placeOrderBtn.disabled = !termsCheckbox.checked;
});
</script>
@endpush
