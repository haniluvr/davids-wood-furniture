<!-- Payment Method Card Component -->
<div class="payment-method-card border border-gray-200 rounded-lg p-4" data-payment-method-id="{{ $paymentMethod->id }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                @if($paymentMethod->isCard())
                    <i data-lucide="credit-card" class="w-5 h-5 text-gray-600 mr-3"></i>
                @else
                    <i data-lucide="smartphone" class="w-5 h-5 text-gray-600 mr-3"></i>
                @endif
                <div>
                    <h4 class="font-medium text-gray-900">{{ $paymentMethod->getDisplayName() }}</h4>
                    <p class="text-sm text-gray-600">
                        @if($paymentMethod->isCard())
                            {{ $paymentMethod->getMaskedNumber() }} • Expires {{ $paymentMethod->getFormattedExpiry() }}
                            @if($paymentMethod->isExpired())
                                <span class="text-red-600 font-medium ml-2">(Expired)</span>
                            @endif
                        @else
                            {{ $paymentMethod->gcash_name }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-2">
            @if($paymentMethod->is_default)
                <span class="px-2 py-1 text-xs font-medium bg-[#8b7355] text-white rounded">Default</span>
            @else
                <button onclick="setDefaultPaymentMethod({{ $paymentMethod->id }})" 
                        class="text-sm text-[#8b7355] hover:text-[#6b5b47] font-medium">
                    Set as Default
                </button>
            @endif
            
            <button onclick="editPaymentMethod({{ $paymentMethod->id }})" 
                    class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                Edit
            </button>
            
            <button onclick="deletePaymentMethod({{ $paymentMethod->id }})" 
                    class="text-sm text-red-600 hover:text-red-800 font-medium">
                Delete
            </button>
        </div>
    </div>
</div>
