@extends('checkout.layout')

@section('title', 'Shipping Information')

@php
    $currentStep = 1;
@endphp

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Shipping Information</h2>
    
    <form action="{{ route('checkout.validate-shipping') }}" method="POST" id="shipping-form">
        @csrf
        <input type="hidden" name="address_option" id="address_option" value="{{ old('address_option', $isDefaultAddressComplete ? 'default' : 'custom') }}">
        
    <!-- Address Selection -->
        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-3">Choose Delivery Address</label>
        <div class="space-y-3">
            <!-- Default Address Option -->
                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer {{ old('address_option', $isDefaultAddressComplete ? 'default' : 'custom') === 'default' && $isDefaultAddressComplete ? 'border-[#8b7355] bg-[#8b7355]/5' : 'border-gray-200' }} {{ !$isDefaultAddressComplete ? 'opacity-50' : '' }}" style="transition: none !important;">
                    <input type="radio" 
                           name="address_option_radio" 
                           value="default" 
                           class="mt-1 mr-3"
                           {{ old('address_option', $isDefaultAddressComplete ? 'default' : 'custom') === 'default' && $isDefaultAddressComplete ? 'checked' : '' }}
                           {{ !$isDefaultAddressComplete ? 'disabled' : '' }}
                           onchange="document.getElementById('address_option').value='default'; toggleAddressForms();">
                <div class="flex-1">
                        <div class="font-medium text-gray-900">Use Default Saved Address</div>
                    <div class="text-sm text-gray-600 mt-1">
                            @if($isDefaultAddressComplete && ($user->street || $user->city || $user->province))
                            {{ $user->street ?? '' }}<br>
                            {{ $user->barangay ?? '' }}{{ $user->barangay ? ', ' : '' }}{{ $user->city ?? '' }}<br>
                            @if($user->province)
                                {{ $user->province }}{{ $user->region ? ', ' : '' }}
                            @endif
                            {{ $user->region ?? '' }} {{ $user->zip_code ?? '' }}
                        @else
                                <span class="text-red-600 font-medium">No default address set</span>
                        @endif
                    </div>
                    @if(!$isDefaultAddressComplete)
                        <div class="mt-2 p-2 bg-red-100 border border-red-200 rounded text-sm text-red-700">
                            <i data-lucide="alert-triangle" class="w-4 h-4 inline mr-1"></i>
                                Please enter a new address below or 
                            <a href="{{ route('account') }}" class="underline hover:no-underline font-medium">update your profile</a>.
                        </div>
                    @endif
                </div>
            </label>
            
                <!-- New Address Option -->
                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer {{ old('address_option', $isDefaultAddressComplete ? 'default' : 'custom') === 'custom' ? 'border-[#8b7355] bg-[#8b7355]/5' : 'border-gray-200' }}" style="transition: none !important;">
                    <input type="radio" 
                           name="address_option_radio" 
                           value="custom" 
                           class="mt-1 mr-3"
                           {{ old('address_option', $isDefaultAddressComplete ? 'default' : 'custom') === 'custom' || !$isDefaultAddressComplete ? 'checked' : '' }}
                           onchange="document.getElementById('address_option').value='custom'; toggleAddressForms();">
                <div class="flex-1">
                        <div class="font-medium text-gray-900">Enter a New Address</div>
                        <div class="text-sm text-gray-600">Enter a different shipping address for this order</div>
                </div>
            </label>
        </div>
    </div>
    
        <!-- New Address Form -->
        <div id="new-address-form" class="space-y-6 mt-4 {{ old('address_option', $isDefaultAddressComplete ? 'default' : 'custom') === 'default' ? 'hidden' : '' }}">
            <!-- Street | Barangay -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-2">Street *</label>
                    <input type="text" 
                           id="address_line_1" 
                           name="address_line_1" 
                           value="{{ old('address_line_1', $user->street) }}"
                           placeholder="Street address"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('address_line_1') border-red-500 @enderror"
                           required>
                    @error('address_line_1')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="barangay" class="block text-sm font-medium text-gray-700 mb-2">Barangay *</label>
                    <select id="barangay" 
                            name="barangay" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('barangay') border-red-500 @enderror"
                            data-required="true">
                        <option value="">Select Barangay</option>
                    </select>
                    @error('barangay')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- City | ZIP Code -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City/Municipality *</label>
                    <select id="city" 
                            name="city" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('city') border-red-500 @enderror"
                            data-required="true">
                        <option value="">Select City/Municipality</option>
                    </select>
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">ZIP Code *</label>
                    <input type="text" 
                           id="zip_code" 
                           name="zip_code" 
                           value="{{ old('zip_code', $user->zip_code) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('zip_code') border-red-500 @enderror"
                           required>
                    @error('zip_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Province | Region -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                <label for="province" class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                <select id="province" 
                        name="province" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('province') border-red-500 @enderror"
                        disabled>
                    <option value="">Select Province (Optional)</option>
                </select>
                @error('province')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
                <div>
                    <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Region *</label>
                    <select id="region" 
                            name="region" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('region') border-red-500 @enderror"
                            data-required="true">
                        <option value="">Select Region</option>
                </select>
                    @error('region')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                </div>
            </div>
            
            <!-- Save as Default Checkbox - Only show if default address is empty -->
            @if(!$isDefaultAddressComplete)
            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="save_as_default" 
                           value="1"
                           class="h-4 w-4 text-[#8b7355] focus:ring-[#8b7355] border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Save this address as my default address</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">You won't need to enter this address again for future orders</p>
            </div>
            @endif
        </div>
        
        <!-- Shipping Method Selection -->
        <div class="mb-8 mt-8 border-t pt-8">
            <label class="block text-sm font-medium text-gray-700 mb-3">Select Shipping Method</label>
            
            <!-- Auto-detected Free Shipping -->
            @if($freeShippingMethod)
                <div class="mb-4 p-4 border-2 border-green-500 rounded-lg bg-green-50 shipping-method-option" data-method-id="{{ $freeShippingMethod->id }}" style="transition: none !important;">
                    <div class="flex items-start">
                        <input type="radio" 
                               name="shipping_method_radio" 
                               value="free" 
                               class="mt-1 mr-3" 
                               checked
                               onchange="updateShippingMethod('free', {{ $freeShippingMethod->id }}, 0);" 
                               onclick="updateShippingMethodBorders(this);">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $freeShippingMethod->name }}</div>
                                    <div class="text-sm text-gray-600 mt-1">{{ $freeShippingMethod->description }}</div>
                                    <div class="text-sm text-gray-600 mt-1">Estimated delivery: {{ $freeShippingMethod->getEstimatedDeliveryDays() }}</div>
                                </div>
                                <div class="text-lg font-bold text-green-600">FREE</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Auto-detected Weight-Based Shipping -->
            @if($weightBasedMethod && $totalWeight > 0 && !$freeShippingMethod)
                <div class="mb-4 p-4 border-2 border-blue-500 rounded-lg bg-blue-50 shipping-method-option" data-method-id="{{ $weightBasedMethod->id }}" style="transition: none !important;">
                    <div class="flex items-start">
                        <input type="radio" 
                               name="shipping_method_radio" 
                               value="weight" 
                               class="mt-1 mr-3" 
                               checked
                               onchange="updateShippingMethod('weight', {{ $weightBasedMethod->id }}, {{ $weightBasedMethod->calculateCost($subtotal, $totalWeight) }});" 
                               onclick="updateShippingMethodBorders(this);">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $weightBasedMethod->name }}</div>
                                    <div class="text-sm text-gray-600 mt-1">{{ $weightBasedMethod->description }}</div>
                                    <div class="text-sm text-gray-600 mt-1">Total weight: {{ number_format($totalWeight, 2) }} kg</div>
                                    <div class="text-sm text-gray-600 mt-1">Estimated delivery: {{ $weightBasedMethod->getEstimatedDeliveryDays() }}</div>
                                </div>
                                <div class="text-lg font-bold text-blue-600">₱{{ number_format($weightBasedMethod->calculateCost($subtotal, $totalWeight), 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Flat Rate Options (only if no free/weight-based) -->
            @if(!$freeShippingMethod && (!$weightBasedMethod || $totalWeight == 0))
                <div class="space-y-3">
                    @forelse($availableShippingMethods as $method)
                        <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer shipping-method-option {{ $loop->first ? 'border-[#8b7355] bg-[#8b7355]/5' : 'border-gray-200' }}" data-method-id="{{ $method['id'] }}" style="transition: none !important;">
                            <input type="radio" 
                                   name="shipping_method_radio" 
                                   value="flat_{{ $method['id'] }}" 
                                   class="mt-1 mr-3"
                                   {{ $loop->first ? 'checked' : '' }}
                                   onchange="updateShippingMethod('flat', {{ $method['id'] }}, {{ $method['cost'] }});" 
                                   onclick="updateShippingMethodBorders(this);">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $method['name'] }}</div>
                                        <div class="text-sm text-gray-600 mt-1">{{ $method['description'] }}</div>
                                        <div class="text-sm text-gray-600 mt-1">Estimated delivery: {{ $method['estimated_days'] }}</div>
                                    </div>
                                    <div class="text-lg font-bold text-gray-900">₱{{ number_format($method['cost'], 2) }}</div>
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="p-4 border border-red-300 rounded-lg bg-red-50">
                            <p class="text-sm text-red-700">No shipping methods available. Please contact support.</p>
                        </div>
                    @endforelse
                </div>
            @endif
            
            <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="{{ $defaultShippingMethod ? $defaultShippingMethod->id : ($availableShippingMethods->first()['id'] ?? '') }}">
            </div>
            
            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8">
                <a href="{{ route('home') }}" 
                   class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                    Back to Home
                </a>
                <button type="submit" 
                    id="continue-to-payment-btn"
                        class="bg-[#8b7355] text-white px-8 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold">
                    Continue to Payment
                </button>
            </div>
        </form>
</div>
@endsection

@push('scripts')
<script>
// PSGC functions are loaded from checkout/layout.blade.php
// We use the loadRegions() function from there

function toggleAddressForms() {
    const addressOptionRadio = document.querySelector('input[name="address_option_radio"]:checked');
    const addressOption = addressOptionRadio ? addressOptionRadio.value : 'default';
    const newAddressForm = document.getElementById('new-address-form');
    const defaultAddressOption = document.querySelector('input[name="address_option_radio"][value="default"]')?.closest('label');
    const newAddressOptionLabel = document.querySelector('input[name="address_option_radio"][value="custom"]')?.closest('label');
    
    // Remove all transitions for instant updates
    if (newAddressForm) {
        newAddressForm.style.transition = 'none';
        newAddressForm.style.transitionDuration = '0s';
    }
    if (defaultAddressOption) {
        defaultAddressOption.style.transition = 'none';
        defaultAddressOption.style.transitionDuration = '0s';
    }
    if (newAddressOptionLabel) {
        newAddressOptionLabel.style.transition = 'none';
        newAddressOptionLabel.style.transitionDuration = '0s';
    }
    
    if (addressOption === 'custom') {
        newAddressForm.classList.remove('hidden');
        // No border on the form itself
        
        // Set required attributes on custom address fields when form is visible
        const customFields = newAddressForm.querySelectorAll('[data-required="true"]');
        customFields.forEach(field => {
            if (!field.disabled) {
                field.setAttribute('required', 'required');
            }
        });
        
        // Update hidden input
        const hiddenInput = document.getElementById('address_option');
        if (hiddenInput) hiddenInput.value = 'custom';
        
        // Remove border from default address option, add to new
        if (defaultAddressOption) {
            defaultAddressOption.classList.remove('border-[#8b7355]', 'bg-[#8b7355]/5');
            defaultAddressOption.classList.add('border-gray-200');
            defaultAddressOption.style.borderColor = 'rgb(229, 231, 235)';
        }
        if (newAddressOptionLabel) {
            newAddressOptionLabel.classList.add('border-[#8b7355]', 'bg-[#8b7355]/5');
            newAddressOptionLabel.classList.remove('border-gray-200');
            newAddressOptionLabel.style.borderColor = 'rgb(139, 115, 85)';
        }
    } else {
        newAddressForm.classList.add('hidden');
        // No border on the form itself
        
        // Remove required attributes from custom address fields when form is hidden
        const customFields = newAddressForm.querySelectorAll('[data-required="true"]');
        customFields.forEach(field => {
            field.removeAttribute('required');
        });
        
        // Update hidden input
        const hiddenInput = document.getElementById('address_option');
        if (hiddenInput) hiddenInput.value = 'default';
        
        // Add border to default address option, remove from new
        if (defaultAddressOption) {
            defaultAddressOption.classList.add('border-[#8b7355]', 'bg-[#8b7355]/5');
            defaultAddressOption.classList.remove('border-gray-200');
            defaultAddressOption.style.borderColor = 'rgb(139, 115, 85)';
        }
        if (newAddressOptionLabel) {
            newAddressOptionLabel.classList.remove('border-[#8b7355]', 'bg-[#8b7355]/5');
            newAddressOptionLabel.classList.add('border-gray-200');
            newAddressOptionLabel.style.borderColor = 'rgb(229, 231, 235)';
        }
    }
}

function updateShippingMethod(type, methodId, cost) {
    document.getElementById('shipping_method_id').value = methodId;
    
    // Update right sidebar Order Summary
    const shippingCostDisplay = document.querySelector('.order-summary-sidebar .shipping-cost-display');
    const totalDisplay = document.querySelector('.order-summary-sidebar .total-display');
    
    if (shippingCostDisplay && totalDisplay) {
        const subtotal = {{ $subtotal }};
        const taxAmount = {{ $taxAmount }};
        const total = subtotal + cost + taxAmount;
        
        if (cost == 0) {
            shippingCostDisplay.innerHTML = '<span class="text-green-600">Free</span>';
        } else {
            shippingCostDisplay.textContent = '₱' + cost.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
        
        totalDisplay.textContent = '₱' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
}

function updateShippingMethodBorders(selectedRadio) {
    if (!selectedRadio) return;
    
    // Update borders instantly - no transitions, no hover effects, no delays
    document.querySelectorAll('.shipping-method-option').forEach(option => {
        // Force remove all transitions and inline styles immediately
        option.style.setProperty('transition', 'none', 'important');
        option.style.setProperty('transition-duration', '0s', 'important');
        option.style.borderColor = ''; // Clear any inline border color
        
        // Get the radio button within this option
        const radioInOption = option.querySelector('input[type="radio"][name="shipping_method_radio"]');
        const isSelected = radioInOption && (radioInOption === selectedRadio || radioInOption.checked);
        
        // Remove all border classes first
        option.classList.remove('border-[#8b7355]', 'bg-[#8b7355]/5', 'border-green-500', 'border-blue-500', 'border-gray-200');
        
        if (isSelected) {
            // Selected: always use brown border (#8b7355)
            option.classList.add('border-[#8b7355]', 'bg-[#8b7355]/5');
        } else {
            // Not selected: use default gray border for flat rate, keep original for special methods
            if (option.classList.contains('bg-green-50')) {
                option.classList.add('border-green-500');
            } else if (option.classList.contains('bg-blue-50')) {
                option.classList.add('border-blue-500');
            } else {
                option.classList.add('border-gray-200');
            }
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load regions using PSGC API from layout
    if (typeof loadRegions === 'function') {
        loadRegions();
    } else {
        // Fallback: Try calling after a short delay to ensure layout script is loaded
        setTimeout(() => {
            if (typeof loadRegions === 'function') {
                loadRegions();
            }
        }, 100);
    }
    // Initialize address form visibility
    toggleAddressForms();
    
    // Initialize shipping method borders on page load
    const selectedShippingMethod = document.querySelector('input[name="shipping_method_radio"]:checked');
    if (selectedShippingMethod) {
        updateShippingMethodBorders(selectedShippingMethod);
    }
    
    // Add event listeners to all shipping method radios - update border immediately on click
    document.querySelectorAll('input[name="shipping_method_radio"]').forEach(radio => {
        radio.addEventListener('click', function() {
            // Update border immediately on click (before change event)
            updateShippingMethodBorders(this);
        });
        radio.addEventListener('change', function() {
            // Also update on change as backup
            updateShippingMethodBorders(this);
        });
    });
    
    // Update address option borders when changed
    document.querySelectorAll('input[name="address_option_radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            toggleAddressForms();
        });
    });
    
    // Ensure form submits correctly
    const shippingForm = document.getElementById('shipping-form');
    const continueBtn = document.getElementById('continue-to-payment-btn');
    
    if (shippingForm && continueBtn) {
        // Add click handler to button to ensure form submits
        continueBtn.addEventListener('click', function(e) {
            // Check if shipping method is selected
            const shippingMethodId = document.getElementById('shipping_method_id').value;
            if (!shippingMethodId) {
                e.preventDefault();
                alert('Please select a shipping method.');
                return false;
            }
            
            // Check if address is selected/entered
            const addressOption = document.getElementById('address_option');
            if (addressOption && addressOption.value === 'custom') {
                // Check required fields for new address
                const requiredFields = shippingForm.querySelectorAll('#new-address-form [required]');
                let isValid = true;
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                    alert('Please fill in all required address fields.');
                    return false;
                }
            }
            
            // Allow form to submit
            return true;
        });
        
        // Also handle form submission - but don't prevent if validation passes
        shippingForm.addEventListener('submit', function(e) {
            const shippingMethodId = document.getElementById('shipping_method_id').value;
            if (!shippingMethodId) {
                e.preventDefault();
                e.stopPropagation();
                alert('Please select a shipping method.');
                return false;
            }
            
            // Check if custom address is selected and form is visible
            const addressOption = document.getElementById('address_option');
            const newAddressForm = document.getElementById('new-address-form');
            const isCustomAddressVisible = newAddressForm && !newAddressForm.classList.contains('hidden');
            
            if (addressOption && addressOption.value === 'custom' && isCustomAddressVisible) {
                // Make sure region is selected (required)
                const regionSelect = document.getElementById('region');
                if (!regionSelect || !regionSelect.value || !regionSelect.value.trim()) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('Please select a region.');
                    return false;
                }
                
                // Check if province is required (only if not NCR and not disabled)
                const provinceSelect = document.getElementById('province');
                const citySelect = document.getElementById('city');
                
                // If province is disabled, that means NCR - check city instead
                if (provinceSelect && !provinceSelect.disabled && !provinceSelect.value) {
                    // Province is enabled but not selected - this might be okay depending on validation
                }
                
                // Check city is selected (only if enabled)
                if (citySelect && !citySelect.disabled && (!citySelect.value || !citySelect.value.trim())) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('Please select a city/municipality.');
                    return false;
                }
                
                // Check barangay (only if enabled and has value)
                const barangaySelect = document.getElementById('barangay');
                if (barangaySelect && !barangaySelect.disabled && (!barangaySelect.value || barangaySelect.value === '')) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('Please select a barangay.');
                    return false;
                }
                
                // Check required text fields
                const streetInput = document.getElementById('address_line_1');
                const zipInput = document.getElementById('zip_code');
                
                if (!streetInput || !streetInput.value.trim()) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('Please enter a street address.');
                    return false;
                }
                
                if (!zipInput || !zipInput.value.trim()) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('Please enter a ZIP code.');
                    return false;
                }
            }
            
            // Allow form to submit
            return true;
        });
    }
});
</script>
@endpush
