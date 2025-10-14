@extends('checkout.layout')

@section('title', 'Shipping Information')

@php
    $currentStep = 1;
@endphp

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Shipping Information</h2>
    
    <!-- Address Selection -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-3">Choose Address</label>
        <div class="space-y-3">
            <!-- Default Address Option -->
            <label class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-[#8b7355] transition-colors">
                <input type="radio" name="address_option" value="default" class="mt-1 mr-3" checked>
                <div class="flex-1">
                    <div class="font-medium text-gray-900">Default Address</div>
                    <div class="text-sm text-gray-600 mt-1">
                        @if($user->street || $user->city || $user->province)
                            {{ $user->street ?? '' }}<br>
                            {{ $user->barangay ?? '' }}{{ $user->barangay ? ', ' : '' }}{{ $user->city ?? '' }}<br>
                            @if($user->province)
                                {{ $user->province }}{{ $user->region ? ', ' : '' }}
                            @endif
                            {{ $user->region ?? '' }} {{ $user->zip_code ?? '' }}
                        @else
                            <span class="text-gray-500 italic">No address provided</span>
                        @endif
                    </div>
                </div>
            </label>
            
            <!-- Custom Address Option -->
            <label class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-[#8b7355] transition-colors">
                <input type="radio" name="address_option" value="custom" class="mt-1 mr-3">
                <div class="flex-1">
                    <div class="font-medium text-gray-900">Use Different Address</div>
                    <div class="text-sm text-gray-600">Enter a different shipping address</div>
                </div>
            </label>
        </div>
    </div>
    
    <!-- Custom Address Form (Hidden by default) -->
    <div id="custom-address-form" class="space-y-6" style="display: none;">
        <form action="{{ route('checkout.validate-shipping') }}" method="POST" id="shipping-form">
            @csrf
            
            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           value="{{ old('first_name', $user->first_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('first_name') border-red-500 @enderror"
                           required>
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           value="{{ old('last_name', $user->last_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('last_name') border-red-500 @enderror"
                           required>
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('email') border-red-500 @enderror"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $user->phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('phone') border-red-500 @enderror"
                           required>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Address Line 1 -->
            <div class="mt-8">
                <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-2">Address Line 1 *</label>
                <input type="text" 
                       id="address_line_1" 
                       name="address_line_1" 
                       value="{{ old('address_line_1', $user->address_line_1) }}"
                       placeholder="Street address, P.O. box, company name, c/o"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('address_line_1') border-red-500 @enderror"
                       required>
                @error('address_line_1')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Address Line 2 -->
            <div class="mt-6">
                <label for="address_line_2" class="block text-sm font-medium text-gray-700 mb-2">Address Line 2</label>
                <input type="text" 
                       id="address_line_2" 
                       name="address_line_2" 
                       value="{{ old('address_line_2', $user->address_line_2) }}"
                       placeholder="Apartment, suite, unit, building, floor, etc."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('address_line_2') border-red-500 @enderror">
                @error('address_line_2')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Region -->
            <div class="mt-6">
                <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Region *</label>
                <select id="region" 
                        name="region" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('region') border-red-500 @enderror"
                        required>
                    <option value="">Select Region</option>
                </select>
                @error('region')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Province -->
            <div class="mt-6">
                <label for="province" class="block text-sm font-medium text-gray-700 mb-2">Province *</label>
                <select id="province" 
                        name="province" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('province') border-red-500 @enderror"
                        required disabled>
                    <option value="">Select Province</option>
                </select>
                @error('province')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- City -->
            <div class="mt-6">
                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City/Municipality *</label>
                <select id="city" 
                        name="city" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('city') border-red-500 @enderror"
                        required disabled>
                    <option value="">Select City/Municipality</option>
                </select>
                @error('city')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Barangay -->
            <div class="mt-6">
                <label for="barangay" class="block text-sm font-medium text-gray-700 mb-2">Barangay</label>
                <select id="barangay" 
                        name="barangay" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent @error('barangay') border-red-500 @enderror"
                        disabled>
                    <option value="">Select Barangay</option>
                </select>
                @error('barangay')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- ZIP Code -->
            <div class="mt-6">
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
            
            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8">
                <a href="{{ route('home') }}" 
                   class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                    Back to Home
                </a>
                <button type="submit" 
                        class="bg-[#8b7355] text-white px-8 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold">
                    Continue to Payment
                </button>
            </div>
        </form>
    </div>
    
    <!-- Default Address Continue Button -->
    <div id="default-address-continue" class="flex justify-between mt-8">
        <a href="{{ route('home') }}" 
           class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
            Back to Home
        </a>
        <form action="{{ route('checkout.validate-shipping') }}" method="POST">
            @csrf
            <input type="hidden" name="address_option" value="default">
            <button type="submit" 
                    class="bg-[#8b7355] text-white px-8 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold">
                Continue to Payment
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Address option toggle
    const addressOptions = document.querySelectorAll('input[name="address_option"]');
    const customForm = document.getElementById('custom-address-form');
    const defaultContinue = document.getElementById('default-address-continue');
    
    addressOptions.forEach(option => {
        option.addEventListener('change', function() {
            if (this.value === 'custom') {
                customForm.style.display = 'block';
                defaultContinue.style.display = 'none';
            } else {
                customForm.style.display = 'none';
                defaultContinue.style.display = 'flex';
            }
        });
    });
    
    // Initialize the correct display state
    const defaultOption = document.querySelector('input[name="address_option"][value="default"]');
    if (defaultOption && defaultOption.checked) {
        customForm.style.display = 'none';
        defaultContinue.style.display = 'flex';
    }
    
    // Form validation for custom address
    const form = document.getElementById('shipping-form');
    if (form) {
        const requiredFields = form.querySelectorAll('[required]');
        
        form.addEventListener('submit', function(e) {
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
                alert('Please fill in all required fields.');
            }
        });
        
        // Real-time validation
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('border-red-500');
                } else {
                    this.classList.remove('border-red-500');
                }
            });
        });
    }
});
</script>
@endpush