@extends('layouts.app')

@section('title', 'My Account | David\'s Wood Furniture - Handcrafteded furniture with timeless design.')

@push('styles')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f3efe7;
    }
    .account-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    .account-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    .account-section {
        padding: 24px;
        border-bottom: 1px solid #f0f0f0;
    }
    .account-section:last-child {
        border-bottom: none;
    }
    .section-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #f4f1eb 0%, #e8e0d3 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c2c2c;
        margin-bottom: 8px;
    }
    .section-description {
        font-size: 14px;
        color: #666;
        margin-bottom: 16px;
    }
    .section-action {
        color: #8b7355;
        font-weight: 500;
        text-decoration: none;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .section-action:hover {
        color: #6b5b47;
    }
    .account-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-top: 32px;
    }
    .account-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .account-title {
        font-size: 32px;
        font-weight: 700;
        color: #2c2c2c;
        margin-bottom: 8px;
    }
    .account-subtitle {
        font-size: 16px;
        color: #666;
    }
    .sidebar-link.active {
        color: #8b7355;
        font-weight: 600;
    }
    .form-input {
        border: 1px solid #CED4DA;
        border-radius: 6px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.2s ease;
        background-color: white;
    }
    .form-input:focus {
        outline: none;
        border-color: #8b7355;
        box-shadow: 0 0 0 2px rgba(139, 115, 85, 0.1);
    }
    .form-input:disabled {
        background-color: #f5f5f5;
        color: #999;
        cursor: not-allowed;
    }
    select.form-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M10.293 3.293L6 7.586 1.707 3.293A1 1 0 00.293 4.707l5 5a1 1 0 001.414 0l5-5a1 1 0 10-1.414-1.414z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 40px;
    }
    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #333333;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .save-button {
        background-color: #8b7355;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    .save-button:hover {
        background-color: #6b5b47;
    }
    
    /* Custom Toggle Switch - Override any conflicts */
    .custom-toggle {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }
    
    .custom-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e5e7eb;
        transition: 0.3s;
        border-radius: 24px;
        overflow: hidden;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        top: 2px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }
    
    .custom-toggle input:checked + .toggle-slider {
        background-color: #8b7355;
    }
    
    .custom-toggle input:checked + .toggle-slider:before {
        transform: translateX(20px);
    }
    
    .custom-toggle input:focus + .toggle-slider {
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.2);
    }
</style>
@endpush

@section('content')
<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8 mt-5 pt-16">
        <!-- Sidebar -->
        <div class="w-full md:w-1/4 sticky top-24 self-start z-10">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6 account-card">
                <div class="flex items-center mb-6">
                    <div class="bg-[#f4f1eb] rounded-full w-16 h-16 flex items-center justify-center mr-4">
                        <i data-lucide="user" class="text-[#8b7355] w-8 h-8"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
                <ul class="space-y-3">
                    <li>
                        <a href="#" class="flex items-center text-gray-700 hover:text-[#8b7355] font-medium sidebar-link" data-target="my-details-section">
                            <i data-lucide="user" class="mr-3 w-5 h-5"></i> My Details
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-gray-700 hover:text-[#8b7355] font-medium sidebar-link" data-target="address-book-section">
                            <i data-lucide="map-pin" class="mr-3 w-5 h-5"></i> My Address Book
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-gray-700 hover:text-[#8b7355] font-medium sidebar-link" data-target="my-orders-section">
                            <i data-lucide="package" class="mr-3 w-5 h-5"></i> My Orders
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-gray-700 hover:text-[#8b7355] font-medium sidebar-link" data-target="my-wishlist-section">
                            <i data-lucide="heart" class="mr-3 w-5 h-5"></i> My Wishlist
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-gray-700 hover:text-[#8b7355] font-medium sidebar-link" data-target="my-cart-section">
                            <i data-lucide="shopping-cart" class="mr-3 w-5 h-5"></i> My Cart
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-gray-700 hover:text-[#8b7355] font-medium sidebar-link" data-target="newsletter-section">
                            <i data-lucide="mail" class="mr-3 w-5 h-5"></i> Newsletter Preferences
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-gray-700 hover:text-[#8b7355] font-medium sidebar-link" data-target="account-settings-section">
                            <i data-lucide="settings" class="mr-3 w-5 h-5"></i> Account Settings
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="w-full md:w-3/4">
            <!-- My Details Section -->
            <div id="my-details-section" class="bg-white rounded-xl shadow-sm p-8 mb-8 account-card content-section">
                
                <!-- Personal Information Section -->
                <div class="border-gray-200 pb-8 mb-8">
                    <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">Personal Information</h3>
                    <form id="personal-info-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <p class="text-gray-600 mb-6">Update your personal details and contact information.</p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="form-label block">FIRST NAME</label>
                                <input type="text" name="first_name" value="{{ $user->first_name }}" class="w-full form-input" required>
                            </div>
                            
                            <div>
                                <label class="form-label block">PHONE NUMBER</label>
                                <input type="tel" name="phone" id="phone-input" value="{{ $user->phone ?? '' }}" class="w-full form-input" data-original-phone="{{ $user->phone ?? '' }}" placeholder="09xxxxxxxxx" maxlength="11">
                                <p id="phone-error" class="text-sm text-red-600 mt-1 hidden">Phone number cannot be removed once added. You can only update it.</p>
                                <p id="phone-format-error" class="text-sm text-red-600 mt-1 hidden">Please enter a valid phone number.</p>
                            </div>
                            <button type="submit" class="save-button">
                                SAVE
                            </button>
                        </div>
                        <div>
                            <div>
                                <label class="form-label block">LAST NAME</label>
                                <input type="text" name="last_name" value="{{ $user->last_name }}" class="w-full form-input" required>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- E-mail Address Section -->
                <div class="border-gray-200 pb-8 mb-8">
                    <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">E-mail address</h3>
                    <form id="email-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <p class="text-gray-600 mb-6">Update your email address and manage your account settings.</p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="form-label block">E-MAIL ADDRESS</label>
                                <input type="email" name="email" value="{{ $user->email }}" class="w-full form-input" required>
                            </div>
                            <div>
                                <label class="form-label block">PASSWORD (REQUIRED TO CHANGE EMAIL)</label>
                                <input type="password" name="password" id="email-password" class="w-full form-input" required>
                                <p class="text-sm text-gray-500 mt-1">Enter your current password to confirm email change.</p>
                                <p id="email-password-error" class="text-sm text-red-600 mt-1 hidden">Current password is incorrect.</p>
                            </div>
                            <button type="submit" class="save-button">
                                SAVE
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Section -->
                <div>
                    <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">Password</h3>
                    <form id="password-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <p class="text-gray-600 mb-6">Change your account password for better security.</p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="form-label block">CURRENT PASSWORD</label>
                                <input type="password" name="current_password" id="current-password" class="w-full form-input" required>
                                <p id="current-password-error" class="text-sm text-red-600 mt-1 hidden">Current password is incorrect.</p>
                            </div>
                            <div>
                                <label class="form-label block">NEW PASSWORD</label>
                                <div class="relative">
                                    <input type="password" name="new_password" id="new-password" class="w-full form-input pr-12" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i data-lucide="eye" class="text-gray-400 w-5 h-5 cursor-pointer hover:text-gray-600 transition-colors"></i>
                                    </div>
                                </div>
                                <div id="password-requirements" class="mt-2 space-y-1 hidden">
                                    <p id="req-length" class="text-sm text-gray-400 flex items-center gap-2">
                                        <i data-lucide="x" class="req-icon w-4 h-4"></i> At least 8 characters
                                    </p>
                                    <p id="req-lowercase" class="text-sm text-gray-400 flex items-center gap-2">
                                        <i data-lucide="x" class="req-icon w-4 h-4"></i> One lowercase letter
                                    </p>
                                    <p id="req-uppercase" class="text-sm text-gray-400 flex items-center gap-2">
                                        <i data-lucide="x" class="req-icon w-4 h-4"></i> One uppercase letter
                                    </p>
                                    <p id="req-number" class="text-sm text-gray-400 flex items-center gap-2">
                                        <i data-lucide="x" class="req-icon w-4 h-4"></i> One number
                                    </p>
                                    <p id="req-special" class="text-sm text-gray-400 flex items-center gap-2">
                                        <i data-lucide="x" class="req-icon w-4 h-4"></i> One special character
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label class="form-label block">CONFIRM PASSWORD</label>
                                <div class="relative">
                                    <input type="password" name="new_password_confirmation" id="confirm-password" class="w-full form-input pr-12" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i data-lucide="eye" class="text-gray-400 w-5 h-5 cursor-pointer hover:text-gray-600 transition-colors"></i>
                                    </div>
                                </div>
                                <p id="confirm-password-error" class="text-sm text-red-600 mt-1 hidden">Passwords do not match.</p>
                            </div>
                            <button type="submit" class="save-button" id="password-submit-btn">
                                SAVE
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Address Book Section -->
            <div id="address-book-section" class="bg-white rounded-xl shadow-sm p-8 mb-8 account-card content-section" style="display: none;">
                
                <!-- Saved Addresses Display -->
                <div class="border-gray-200">
                    <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">My Address Book</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <p class="text-gray-600 mb-6">Manage your delivery addresses and contact information.</p>
                        </div>
                        <div class="lg:col-span-2">
                            <div class="space-y-4">
                                <!-- Default Address Display -->
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900 mb-2">Default Address</h4>
                                            @if($user->street || $user->city || $user->province)
                                                <p class="text-gray-600">{{ $user->street ?? '' }}</p>
                                                <p class="text-gray-600">{{ $user->barangay ?? '' }}{{ $user->barangay ? ', ' : '' }}{{ $user->city ?? '' }}</p>
                                                <p class="text-gray-600">
                                                    @if($user->province)
                                                        {{ $user->province }}{{ $user->region ? ', ' : '' }}
                                                    @endif
                                                    {{ $user->region ?? '' }}
                                                </p>
                                                <p class="text-gray-600">{{ $user->zip_code ?? '' }}</p>
                                            @else
                                                <p class="text-gray-500 italic">No address provided</p>
                                            @endif
                                        </div>
                                        <button class="text-[#8b7355] hover:text-[#6b5b47] font-medium" onclick="showEditAddressForm()">Edit</button>
                                    </div>
                                </div>
                                
                                <!-- Add New Address Button -->
                                <button class="w-full border-2 border-dashed border-gray-300 rounded-lg p-4 text-gray-600 hover:border-[#8b7355] hover:text-[#8b7355] transition-colors" onclick="showAddAddressForm()">
                                    + Add New Address
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Edit Address Form (Hidden by default) -->
                <div id="edit-address-form" class="border-gray-200 pb-8 mb-8" style="display: none;">
                    <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">Edit Address</h3>
                    <form id="update-address-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <p class="text-gray-600 mb-6">Update your default delivery address.</p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="form-label block">STREET ADDRESS</label>
                                <input type="text" name="street" value="{{ $user->street ?? '' }}" class="w-full form-input" required>
                            </div>
                            <div>
                                <label class="form-label block">CITY/MUNICIPALITY</label>
                                <select name="city" id="city-select" class="w-full form-input" required disabled>
                                    <option value="">Select City/Municipality</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label block">PROVINCE</label>
                                <select name="province" id="province-select" class="w-full form-input" disabled>
                                    <option value="">Select Province</option>
                                </select>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="save-button">
                                    SAVE
                                </button>
                                <button type="button" onclick="hideEditAddressForm()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors font-semibold text-sm">
                                    CANCEL
                                </button>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="form-label block">BARANGAY</label>
                                <select name="barangay" id="barangay-select" class="w-full form-input" required disabled>
                                    <option value="">Select Barangay</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label block">ZIP CODE</label>
                                <input type="text" name="zip_code" value="{{ $user->zip_code ?? '' }}" class="w-full form-input" maxlength="4" placeholder="Enter ZIP code" required>
                            </div>
                            <div>
                                <label class="form-label block">REGION</label>
                                <select name="region" id="region-select" class="w-full form-input" required>
                                    <option value="">Select Region</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- My Orders Section -->
            <div id="my-orders-section" class="bg-white rounded-xl shadow-sm p-8 mb-8 account-card content-section" style="display: none;">
                
                <!-- My Orders -->
                <div class="border-gray-200">
                <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">My Orders</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
                        <div class="lg:col-span-2" id="orders-container">
                            @include('partials.orders-list', ['orders' => $orders])
                        </div>
                    </div>
                </div>
                    </div>

            <!-- My Wishlist Section -->
            <div id="my-wishlist-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 account-card content-section" style="display: none;">
                <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">My Wishlist</h3>
                @if($wishlistItems->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        @foreach($wishlistItems as $item)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="aspect-square bg-gray-100 rounded-lg mb-3 flex items-center justify-center">
                                @if($item->product && $item->product->image)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i data-lucide="heart" class="text-gray-400 w-8 h-8"></i>
                                @endif
                        </div>
                            <h3 class="font-medium text-gray-900">{{ $item->product->name ?? 'Product' }}</h3>
                            <p class="text-gray-600">₱{{ number_format($item->product->price ?? 0, 2) }}</p>
                        </div>
                        @endforeach
                        </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="heart" class="text-gray-400 w-8 h-8"></i>
                    </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
                        <p class="text-gray-600 mb-4">Start adding items you love to your wishlist</p>
                        <a href="{{ route('products') }}" class="bg-[#8b7355] text-white px-6 py-2 rounded-lg hover:bg-[#6b5b47] transition-colors">
                            Browse Products
                        </a>
                </div>
                @endif
            </div>

            <!-- My Cart Section -->
            <div id="my-cart-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 account-card content-section" style="display: none;">
                <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">My Cart</h3>
                @if($cartItems->count() > 0)
                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                        <div class="cart-item flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[#8b7355] transition-colors" data-product-id="{{ $item->product_id }}">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                @if($item->product && $item->product->image)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i data-lucide="package" class="text-gray-400 w-6 h-6"></i>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ $item->product->name ?? 'Product' }}</h3>
                                <p class="text-xs text-gray-500 mb-1">₱{{ number_format($item->product->price ?? 0, 2) }} each</p>
                                <p class="text-base font-semibold text-[#8b7355] item-total-price">₱{{ number_format($item->total_price ?? 0, 2) }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <!-- Quantity Controls -->
                                <div class="flex items-center border border-gray-300 rounded">
                                    <button class="decrease-qty px-2 py-1 hover:bg-gray-100 transition-colors" data-product-id="{{ $item->product_id }}">
                                        <i data-lucide="minus" class="w-4 h-4"></i>
                                    </button>
                                    <span class="px-3 py-1 text-sm font-medium border-x border-gray-300 min-w-[40px] text-center">{{ $item->quantity }}</span>
                                    <button class="increase-qty px-2 py-1 hover:bg-gray-100 transition-colors" data-product-id="{{ $item->product_id }}">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <!-- Delete Button -->
                                <button class="remove-cart-item text-red-600 hover:text-red-700 transition-colors p-1" data-product-id="{{ $item->product_id }}">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Cart Summary -->
                        <div class="border-t pt-4 mt-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-semibold text-gray-900">Subtotal (<span id="cart-total-qty">{{ $cartItems->sum('quantity') }}</span> items)</span>
                                <span class="text-2xl font-bold text-[#8b7355]" id="cart-total-price">₱{{ number_format($cartTotal ?? 0, 2) }}</span>
                            </div>
                            <div class="flex gap-3">
                                <button onclick="window.location.href='{{ route('products') }}'" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                                    Continue Shopping
                                </button>
                                <button onclick="window.location.href='/checkout'" class="flex-1 bg-[#8b7355] text-white px-6 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold">
                                    Proceed to Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="shopping-cart" class="text-gray-400 w-8 h-8"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                        <p class="text-gray-600 mb-4">Add items to your cart to see them here</p>
                        <a href="{{ route('products') }}" class="bg-[#8b7355] text-white px-6 py-2 rounded-lg hover:bg-[#6b5b47] transition-colors inline-block">
                            Browse Products
                        </a>
                    </div>
                @endif
            </div>

            <!-- Newsletter Section -->
            <div id="newsletter-section" class="bg-white rounded-xl shadow-sm p-8 mb-8 account-card content-section" style="display: none;">
                
                <!-- Newsletter Preferences Section -->
                <div class="border-gray-200">
                    <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">Newsletter Preferences</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <p class="text-gray-600">Manage your newsletter preferences and stay updated with the latest news.</p>
                        </div>
                        <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-900">Product Updates</h3>
                            <p class="text-gray-600">Get notified about new products and collections</p>
                        </div>
                                <label class="custom-toggle">
                                    <input type="checkbox" {{ $user->newsletter_product_updates ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                        </label>
                        </div>
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                            <h3 class="font-medium text-gray-900">Special Offers</h3>
                            <p class="text-gray-600">Receive exclusive discounts and promotions</p>
                    </div>
                                <label class="custom-toggle">
                                    <input type="checkbox" {{ $user->newsletter_special_offers ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                        </label>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>

            <!-- Account Settings Section -->
            <div id="account-settings-section" class="bg-white rounded-xl shadow-sm p-8 mb-8 account-card content-section" style="display: none;">
                
                <!-- Account Settings -->
                <div class="border-gray-200">
                    <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">Account Settings</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <p class="text-gray-600">Manage your account security and preferences.</p>
                        </div>
                        <div class="lg:col-span-2 space-y-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-gray-900">Change Password</h3>
                                <p class="text-gray-600">Update your account password</p>
                            </div>
                                    <button onclick="goToPasswordSection()" class="text-[#8b7355] hover:text-[#6b5b47] font-medium">Change</button>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-gray-900">Two-Factor Authentication</h3>
                                <p class="text-gray-600">Add an extra layer of security</p>
                            </div>
                            <button class="text-[#8b7355] hover:text-[#6b5b47] font-medium">Enable</button>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-gray-900">Delete Account</h3>
                                <p class="text-gray-600">Permanently delete your account</p>
                            </div>
                                    <button onclick="showDeleteAccountModal()" class="text-red-600 hover:text-red-700 font-medium">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="delete-account-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="alert-triangle" class="w-8 h-8 text-red-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Delete Account</h3>
            <p class="text-gray-600">Are you sure you want to delete your account? This action cannot be undone.</p>
        </div>
        
        <form id="delete-account-form">
            <div class="mb-4">
                <label class="form-label block">CONFIRM PASSWORD</label>
                <input type="password" id="delete-account-password" class="w-full form-input" required placeholder="Enter your password">
                <p id="delete-password-error" class="text-sm text-red-600 mt-1 hidden">Incorrect password</p>
            </div>
            
            <div class="mb-6">
                <label class="form-label block">REASON (OPTIONAL)</label>
                <textarea id="delete-account-reason" class="w-full form-input" rows="3" placeholder="Tell us why you're leaving (optional)"></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="hideDeleteAccountModal()" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors font-semibold">
                    Delete Account
                </button>
            </div>
        </form>
                            </div>
                            </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Handle sidebar navigation
        const sectionLinks = document.querySelectorAll('.sidebar-link');
        const contentSections = document.querySelectorAll('.content-section');
        
        // Show first section by default
        const firstSection = document.getElementById('my-details-section');
        if (firstSection) {
            firstSection.style.display = 'block';
        }
        
        // Add active class to first link
        const firstLink = document.querySelector('[data-target="my-details-section"]');
        if (firstLink) {
            firstLink.classList.add('active');
        }
        
        sectionLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all links
                sectionLinks.forEach(l => l.classList.remove('active'));
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // Hide all content sections
                contentSections.forEach(section => {
                    section.style.display = 'none';
                });
                
                // Show the corresponding content section
                const targetId = this.getAttribute('data-target');
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.style.display = 'block';
                }
            });
        });

        // Phone validation and auto-formatting
        const phoneInput = document.getElementById('phone-input');
        const phoneError = document.getElementById('phone-error');
        const phoneFormatError = document.getElementById('phone-format-error');
        
        if (phoneInput) {
            // Auto-format phone number (add 0 if starts with 9)
            phoneInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, ''); // Remove non-digits
                
                // If user starts typing with 9 and it's the first character, add 0
                if (value.length > 0 && value[0] === '9') {
                    value = '0' + value;
                    this.value = value;
                }
                
                // Only allow digits
                this.value = value;
                
                const originalPhone = this.getAttribute('data-original-phone');
                const currentValue = this.value.trim();
                
                // Hide format error initially
                if (phoneFormatError) {
                    phoneFormatError.classList.add('hidden');
                }
                
                // Show error if user had a phone and is trying to clear it
                if (originalPhone && !currentValue) {
                    if (phoneError) {
                        phoneError.classList.remove('hidden');
                    }
                    this.classList.add('border-red-500');
                } else {
                    if (phoneError) {
                        phoneError.classList.add('hidden');
                    }
                    
                    // Validate Philippine phone format (10-11 digits, starts with 0 or 9)
                    if (currentValue.length > 0) {
                        const isValidLength = currentValue.length >= 10 && currentValue.length <= 11;
                        const startsCorrectly = currentValue[0] === '0' || currentValue[0] === '9';
                        
                        if (!isValidLength || !startsCorrectly) {
                            if (phoneFormatError) {
                                phoneFormatError.classList.remove('hidden');
                            }
                            this.classList.add('border-red-500');
                        } else {
                            if (phoneFormatError) {
                                phoneFormatError.classList.add('hidden');
                            }
                            this.classList.remove('border-red-500');
                        }
                    } else {
                        this.classList.remove('border-red-500');
                    }
                }
            });
            
            // Prevent non-numeric input
            phoneInput.addEventListener('keypress', function(e) {
                if (e.key && !/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') {
                    e.preventDefault();
                }
            });
        }

        // Handle personal information form submission
        const personalInfoForm = document.getElementById('personal-info-form');
        if (personalInfoForm) {
            personalInfoForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Check phone validation before submitting
                const phoneInput = document.getElementById('phone-input');
                if (phoneInput) {
                    const originalPhone = phoneInput.getAttribute('data-original-phone');
                    const currentValue = phoneInput.value.trim();
                    
                    if (originalPhone && !currentValue) {
                        showNotification('Phone number cannot be removed once added', 'error');
                        return;
                    }
                    
                    // Validate Philippine phone format if phone is provided
                    if (currentValue.length > 0) {
                        const isValidLength = currentValue.length >= 10 && currentValue.length <= 11;
                        const startsCorrectly = currentValue[0] === '0' || currentValue[0] === '9';
                        
                        if (!isValidLength || !startsCorrectly) {
                            showNotification('Please enter a valid Philippine phone number (10-11 digits)', 'error');
                            return;
                        }
                    }
                }
                
                const formData = new FormData(this);
                const data = Object.fromEntries(formData);
                
                try {
                    const response = await fetch('/api/account/profile/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification('Personal information updated successfully!', 'success');
                        // Update the original phone value after successful save
                        if (phoneInput && data.phone) {
                            phoneInput.setAttribute('data-original-phone', data.phone);
                        }
                    } else {
                        showNotification(result.message || 'Failed to update personal information', 'error');
                    }
                } catch (error) {
                    showNotification('An error occurred while updating personal information', 'error');
                }
            });
        }

        // Clear email password error when user starts typing
        const emailPasswordInput = document.getElementById('email-password');
        const emailPasswordError = document.getElementById('email-password-error');
        
        if (emailPasswordInput && emailPasswordError) {
            emailPasswordInput.addEventListener('input', function() {
                emailPasswordError.classList.add('hidden');
                this.classList.remove('border-red-500');
            });
        }

        // Handle email form submission
        const emailForm = document.getElementById('email-form');
        if (emailForm) {
            emailForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const data = Object.fromEntries(formData);
                
                // Hide any previous errors
                const emailPasswordError = document.getElementById('email-password-error');
                const emailPasswordInput = document.getElementById('email-password');
                if (emailPasswordError) {
                    emailPasswordError.classList.add('hidden');
                }
                if (emailPasswordInput) {
                    emailPasswordInput.classList.remove('border-red-500');
                }
                
                // Check if password is provided
                if (!data.password || data.password.trim() === '') {
                    if (emailPasswordError) {
                        emailPasswordError.textContent = 'Password is required to change email.';
                        emailPasswordError.classList.remove('hidden');
                    }
                    if (emailPasswordInput) {
                        emailPasswordInput.classList.add('border-red-500');
                    }
                    return;
                }
                
                try {
                    const response = await fetch('/api/account/profile/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification('Email updated successfully!', 'success');
                        // Clear password field
                        if (emailPasswordInput) {
                            emailPasswordInput.value = '';
                        }
                    } else {
                        // Check if error is related to incorrect password
                        if (result.message && result.message.toLowerCase().includes('password')) {
                            if (emailPasswordError) {
                                emailPasswordError.textContent = 'Current password is incorrect.';
                                emailPasswordError.classList.remove('hidden');
                            }
                            if (emailPasswordInput) {
                                emailPasswordInput.classList.add('border-red-500');
                            }
                        } else {
                            showNotification(result.message || 'Failed to update email', 'error');
                        }
                    }
                } catch (error) {
                    showNotification('An error occurred while updating email', 'error');
                }
            });
        }

        // Password validation
        const newPasswordInput = document.getElementById('new-password');
        const confirmPasswordInput = document.getElementById('confirm-password');
        const passwordRequirements = document.getElementById('password-requirements');
        const confirmPasswordError = document.getElementById('confirm-password-error');
        
        // Show password requirements when user starts typing
        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                
                // Show requirements box
                if (password.length > 0) {
                    passwordRequirements.classList.remove('hidden');
                } else {
                    passwordRequirements.classList.add('hidden');
                }
                
                // Validate each requirement
                validatePasswordRequirement('req-length', password.length >= 8);
                validatePasswordRequirement('req-lowercase', /[a-z]/.test(password));
                validatePasswordRequirement('req-uppercase', /[A-Z]/.test(password));
                validatePasswordRequirement('req-number', /[0-9]/.test(password));
                validatePasswordRequirement('req-special', /[!@#$%^&*(),.?":{}|<>]/.test(password));
                
                // Check if confirm password matches
                if (confirmPasswordInput.value) {
                    validatePasswordMatch();
                }
            });
        }
        
        // Validate password match when user types in confirm password
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', validatePasswordMatch);
        }
        
        function validatePasswordRequirement(reqId, isValid) {
            const reqElement = document.getElementById(reqId);
            if (reqElement) {
                const icon = reqElement.querySelector('.req-icon');
                if (isValid) {
                    reqElement.classList.remove('text-gray-400');
                    reqElement.classList.add('text-green-600');
                    icon.setAttribute('data-lucide', 'check');
                } else {
                    reqElement.classList.remove('text-green-600');
                    reqElement.classList.add('text-gray-400');
                    icon.setAttribute('data-lucide', 'x');
                }
                // Reinitialize the icon
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        }
        
        function validatePasswordMatch() {
            const newPassword = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (confirmPassword && newPassword !== confirmPassword) {
                confirmPasswordError.classList.remove('hidden');
                confirmPasswordInput.classList.add('border-red-500');
            } else {
                confirmPasswordError.classList.add('hidden');
                confirmPasswordInput.classList.remove('border-red-500');
            }
        }
        
        function isPasswordValid(password) {
            return password.length >= 8 &&
                   /[a-z]/.test(password) &&
                   /[A-Z]/.test(password) &&
                   /[0-9]/.test(password) &&
                   /[!@#$%^&*(),.?":{}|<>]/.test(password);
        }

        // Handle password form submission
        const passwordForm = document.getElementById('password-form');
        if (passwordForm) {
            passwordForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const data = Object.fromEntries(formData);
                
                // Validate new password requirements
                if (!isPasswordValid(data.new_password)) {
                    showNotification('Password does not meet the requirements', 'error');
                    return;
                }
                
                // Validate password confirmation
                if (data.new_password !== data.new_password_confirmation) {
                    showNotification('Passwords do not match', 'error');
                    confirmPasswordError.classList.remove('hidden');
                    return;
                }
                
                try {
                    const response = await fetch('/api/account/password/change', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification('Password changed successfully!', 'success');
                        passwordForm.reset();
                        passwordRequirements.classList.add('hidden');
                        confirmPasswordError.classList.add('hidden');
                        // Reset all requirement icons
                        ['req-length', 'req-lowercase', 'req-uppercase', 'req-number', 'req-special'].forEach(reqId => {
                            validatePasswordRequirement(reqId, false);
                        });
                    } else {
                        // Check if error is related to current password
                        if (result.message && result.message.toLowerCase().includes('current password')) {
                            document.getElementById('current-password-error').classList.remove('hidden');
                            document.getElementById('current-password').classList.add('border-red-500');
                            // Don't show notification for password errors - inline error is enough
                        } else {
                            // Show notification for other errors
                            showNotification(result.message || 'Failed to change password', 'error');
                        }
                    }
                } catch (error) {
                    showNotification('An error occurred while changing password', 'error');
                }
            });
        }
        
        // Clear current password error when user starts typing
        const currentPasswordInput = document.getElementById('current-password');
        if (currentPasswordInput) {
            currentPasswordInput.addEventListener('input', function() {
                document.getElementById('current-password-error').classList.add('hidden');
                this.classList.remove('border-red-500');
            });
        }

        // Password visibility toggle functionality
        function initPasswordToggle(inputId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
            const toggleButton = input.parentElement.querySelector('[data-lucide="eye"], [data-lucide="eye-off"]');
            if (!toggleButton) return;
            
            toggleButton.addEventListener('click', function() {
                const type = input.getAttribute('type');
                
                if (type === 'password') {
                    input.setAttribute('type', 'text');
                    this.setAttribute('data-lucide', 'eye-off');
                } else {
                    input.setAttribute('type', 'password');
                    this.setAttribute('data-lucide', 'eye');
                }
                
                // Reinitialize Lucide icons to update the icon
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        }
        
        // Initialize password toggles for new and confirm password fields
        initPasswordToggle('new-password');
        initPasswordToggle('confirm-password');

        // Newsletter toggle functionality
        const newsletterToggles = document.querySelectorAll('#newsletter-section input[type="checkbox"]');
        newsletterToggles.forEach(toggle => {
            toggle.addEventListener('change', async function() {
                const isEnabled = this.checked;
                const type = this.closest('.flex').querySelector('h3').textContent.toLowerCase().includes('product') ? 'product_updates' : 'special_offers';
                
                try {
                    const response = await fetch('/api/account/newsletter/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                            [type]: isEnabled
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                        showNotification('Newsletter preferences updated successfully!', 'success');
            } else {
                        showNotification(result.message || 'Failed to update newsletter preferences', 'error');
                        // Revert the toggle if the request failed
                        this.checked = !isEnabled;
            }
        } catch (error) {
                    showNotification('An error occurred while updating newsletter preferences', 'error');
                    // Revert the toggle if the request failed
                    this.checked = !isEnabled;
                }
            });
        });

        // Address form functionality
        const updateAddressForm = document.getElementById('update-address-form');
        if (updateAddressForm) {
            updateAddressForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Get form data manually to include disabled fields
                const regionSelect = document.getElementById('region-select');
                const provinceSelect = document.getElementById('province-select');
                const citySelect = document.getElementById('city-select');
                const barangaySelect = document.getElementById('barangay-select');
                
                const data = {
                    street: this.querySelector('input[name="street"]').value,
                    region: regionSelect ? regionSelect.value : '',
                    province: provinceSelect ? provinceSelect.value : '', // Include even if disabled/empty
                    city: citySelect ? citySelect.value : '',
                    barangay: barangaySelect ? barangaySelect.value : '',
                    zip_code: this.querySelector('input[name="zip_code"]').value
                };
                
                console.log('Submitting address data:', data);
                
                try {
                    const response = await fetch('/api/account/address/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification('Address updated successfully!', 'success');
                        hideEditAddressForm();
                        // Refresh the page to show updated address
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showNotification(result.message || 'Failed to update address', 'error');
                    }
                } catch (error) {
                    console.error('Error updating address:', error);
                    showNotification('An error occurred while updating address', 'error');
                }
            });
        }
    });

    // Philippine Address API Integration (PSGC Cloud v2)
    const PSGC_API = 'https://psgc.cloud/api/v2';
    let regionsData = [];
    let currentRegionCode = '';
    let currentProvinceCode = '';
    
    // Load all regions
    async function loadRegions() {
        try {
            const response = await fetch(`${PSGC_API}/regions`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();
            console.log('API Response:', data);
            console.log('API Response Type:', typeof data);
            console.log('Is Array?', Array.isArray(data));
            if (typeof data === 'object') {
                console.log('Object keys:', Object.keys(data));
            }
            
            // Extract array from response
            if (Array.isArray(data)) {
                regionsData = data;
            } else if (data.data && Array.isArray(data.data)) {
                regionsData = data.data;
            } else if (typeof data === 'object') {
                // Try to find the array in the object
                const possibleArrays = Object.values(data).filter(v => Array.isArray(v));
                if (possibleArrays.length > 0) {
                    regionsData = possibleArrays[0];
                } else {
                    throw new Error('No array found in API response');
                }
            } else {
                throw new Error('API response is not in expected format');
            }
            
            if (!Array.isArray(regionsData)) {
                throw new Error('API response is not in expected format');
            }
            
            const regionSelect = document.getElementById('region-select');
            if (regionSelect) {
                regionSelect.innerHTML = '<option value="">Select Region</option>';
                regionsData.forEach(region => {
                    const option = document.createElement('option');
                    option.value = region.name;
                    option.setAttribute('data-code', region.code);
                    option.textContent = region.name;
                    regionSelect.appendChild(option);
                });
            }
            
            console.log('✅ Regions loaded:', regionsData.length);
        } catch (error) {
            console.error('❌ Error loading regions:', error);
            showNotification('Failed to load regions. Please refresh the page.', 'error');
        }
    }
    
    // Load provinces for selected region using v2 nested endpoint
    async function loadProvinces(regionCodeOrName) {
        try {
            currentRegionCode = regionCodeOrName;
            const url = `${PSGC_API}/regions/${encodeURIComponent(regionCodeOrName)}/provinces`;
            console.log('🔍 Loading provinces from:', url);
            
            const response = await fetch(url);
            console.log('📡 Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('❌ Response error:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('📦 Province data received:', data);
            
            // Extract provinces array from response (v2 API wraps in {data: [...]})
            const provinces = data.data || data;
            
            console.log('📦 Province count:', provinces.length);
            
            // Special case: Some regions (like NCR) have no provinces, go directly to cities
            if (!Array.isArray(provinces) || provinces.length === 0) {
                console.log('⚠️ No provinces in this region, loading cities directly...');
                
                // Skip province selection and load cities directly
                const provinceSelect = document.getElementById('province-select');
                if (provinceSelect) {
                    provinceSelect.innerHTML = '<option value="">No provinces (loading cities...)</option>';
                    provinceSelect.disabled = true;
                }
                
                // Load cities directly for this region
                await loadCitiesDirectly(regionCodeOrName);
                return;
            }
            
            const provinceSelect = document.getElementById('province-select');
            if (provinceSelect) {
                provinceSelect.innerHTML = '<option value="">Select Province</option>';
                provinceSelect.disabled = false;
                
                provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name;
                    option.setAttribute('data-code', province.code);
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
            }
            
            console.log('✅ Provinces loaded:', provinces.length);
        } catch (error) {
            console.error('❌ Error loading provinces:', error);
            showNotification('Failed to load provinces. Please try again.', 'error');
        }
    }
    
    // Load cities/municipalities directly for a region (for regions without provinces like NCR)
    async function loadCitiesDirectly(regionCodeOrName) {
        try {
            const url = `${PSGC_API}/regions/${encodeURIComponent(regionCodeOrName)}/cities-municipalities`;
            console.log('🔍 Loading cities directly from:', url);
            
            const response = await fetch(url);
            console.log('📡 Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('❌ Response error:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('📦 City data received:', data);
            
            // Extract cities array from response (v2 API wraps in {data: [...]})
            const cities = data.data || data;
            
            const citySelect = document.getElementById('city-select');
            if (citySelect) {
                citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                citySelect.disabled = false;
                
                if (Array.isArray(cities)) {
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.name;
                        option.setAttribute('data-code', city.code);
                        option.textContent = `${city.name} (${city.type})`;
                        citySelect.appendChild(option);
                    });
                }
            }
            
            console.log('✅ Cities/Municipalities loaded:', cities.length);
        } catch (error) {
            console.error('❌ Error loading cities:', error);
            showNotification('Failed to load cities. Please try again.', 'error');
        }
    }
    
    // Load cities/municipalities for selected province using v2 nested endpoint
    async function loadCities(provinceCodeOrName) {
        try {
            currentProvinceCode = provinceCodeOrName;
            const url = `${PSGC_API}/regions/${encodeURIComponent(currentRegionCode)}/provinces/${encodeURIComponent(provinceCodeOrName)}/cities-municipalities`;
            console.log('🔍 Loading cities from:', url);
            
            const response = await fetch(url);
            console.log('📡 Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('❌ Response error:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('📦 City data received:', data);
            
            // Extract cities array from response (v2 API wraps in {data: [...]})
            const cities = data.data || data;
            
            const citySelect = document.getElementById('city-select');
            if (citySelect) {
                citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                citySelect.disabled = false;
                
                if (Array.isArray(cities)) {
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.name;
                        option.setAttribute('data-code', city.code);
                        option.textContent = `${city.name} (${city.type})`;
                        citySelect.appendChild(option);
                    });
                }
            }
            
            console.log('✅ Cities/Municipalities loaded:', cities.length);
        } catch (error) {
            console.error('❌ Error loading cities:', error);
            showNotification('Failed to load cities. Please try again.', 'error');
        }
    }
    
    // Load barangays for selected city using v2 endpoint
    async function loadBarangays(cityCodeOrName) {
        try {
            // Use the direct cities-municipalities endpoint to get barangays
            const url = `${PSGC_API}/cities-municipalities/${encodeURIComponent(cityCodeOrName)}/barangays`;
            console.log('🔍 Loading barangays from:', url);
            
            const response = await fetch(url);
            console.log('📡 Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('❌ Response error:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('📦 Barangay data received:', data);
            
            // Extract barangays array from response (v2 API wraps in {data: [...]})
            const barangays = data.data || data;
            
            const barangaySelect = document.getElementById('barangay-select');
            if (barangaySelect) {
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                barangaySelect.disabled = false;
                
                if (Array.isArray(barangays)) {
                    barangays.forEach(barangay => {
                        const option = document.createElement('option');
                        option.value = barangay.name;
                        option.textContent = barangay.name;
                        barangaySelect.appendChild(option);
                    });
                }
            }
            
            console.log('✅ Barangays loaded:', barangays.length);
        } catch (error) {
            console.error('❌ Error loading barangays:', error);
            showNotification('Failed to load barangays. Please try again.', 'error');
        }
    }
    
    // Set up cascading dropdowns
    const regionSelect = document.getElementById('region-select');
    const provinceSelect = document.getElementById('province-select');
    const citySelect = document.getElementById('city-select');
    const barangaySelect = document.getElementById('barangay-select');
    
    if (regionSelect) {
        regionSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const regionName = selectedOption.value;
            const regionCode = selectedOption.getAttribute('data-code');
            
            console.log('🌏 Region selected:', { name: regionName, code: regionCode });
            
            // Reset province code (important for regions without provinces)
            currentProvinceCode = '';
            
            // Reset province, city and barangay
            if (provinceSelect) {
                provinceSelect.innerHTML = '<option value="">Select Province</option>';
                provinceSelect.disabled = true;
            }
            if (citySelect) {
                citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                citySelect.disabled = true;
            }
            if (barangaySelect) {
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                barangaySelect.disabled = true;
            }
            
            // Clear zip code
            const zipCodeInput = document.querySelector('input[name="zip_code"]');
            if (zipCodeInput) {
                zipCodeInput.value = '';
            }
            
            if (regionName) {
                loadProvinces(regionName);
            }
        });
    }
    
    if (provinceSelect) {
        provinceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const provinceName = selectedOption.value;
            const provinceCode = selectedOption.getAttribute('data-code');
            
            console.log('🏛️ Province selected:', { name: provinceName, code: provinceCode });
            
            // Reset city and barangay
            if (citySelect) {
                citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                citySelect.disabled = true;
            }
            if (barangaySelect) {
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                barangaySelect.disabled = true;
            }
            
            // Clear zip code
            const zipCodeInput = document.querySelector('input[name="zip_code"]');
            if (zipCodeInput) {
                zipCodeInput.value = '';
            }
            
            if (provinceName) {
                loadCities(provinceName);
            }
        });
    }
    
    if (citySelect) {
        citySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const cityName = selectedOption.value;
            const cityCode = selectedOption.getAttribute('data-code');
            
            console.log('🏙️ City selected:', { name: cityName, code: cityCode });
            
            // Reset barangay
            if (barangaySelect) {
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                barangaySelect.disabled = true;
            }
            
            if (cityName) {
                loadBarangays(cityName);
            }
        });
    }

    // Address form helper functions
    async function showEditAddressForm() {
        document.getElementById('edit-address-form').style.display = 'block';
        
        // Load regions if not already loaded
        if (regionsData.length === 0) {
            await loadRegions();
        }
        
        // Scroll to the edit form
        document.getElementById('edit-address-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function hideEditAddressForm() {
        document.getElementById('edit-address-form').style.display = 'none';
    }

    function showAddAddressForm() {
        showNotification('Add new address functionality coming soon!', 'info');
    }

    // Helper function for notifications
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Navigate to password section in My Details
    function goToPasswordSection() {
        // Hide all content sections
        const contentSections = document.querySelectorAll('.content-section');
        contentSections.forEach(section => {
            section.style.display = 'none';
        });
        
        // Show My Details section
        const myDetailsSection = document.getElementById('my-details-section');
        if (myDetailsSection) {
            myDetailsSection.style.display = 'block';
        }
        
        // Remove active class from all sidebar links
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => link.classList.remove('active'));
        
        // Add active class to My Details link
        const myDetailsLink = document.querySelector('[data-target="my-details-section"]');
        if (myDetailsLink) {
            myDetailsLink.classList.add('active');
        }
        
        // Scroll to password form
        setTimeout(() => {
            const passwordForm = document.getElementById('password-form');
            if (passwordForm) {
                passwordForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
                // Focus on the current password field after scrolling
                setTimeout(() => {
                    const currentPasswordInput = document.getElementById('current-password');
                    if (currentPasswordInput) {
                        currentPasswordInput.focus();
                    }
                }, 500);
            }
        }, 100);
    }

    // Show delete account modal
    function showDeleteAccountModal() {
        const modal = document.getElementById('delete-account-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Reinitialize Lucide icons for the modal
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    }

    // Hide delete account modal
    function hideDeleteAccountModal() {
        const modal = document.getElementById('delete-account-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            
            // Clear form
            document.getElementById('delete-account-password').value = '';
            document.getElementById('delete-account-reason').value = '';
            document.getElementById('delete-password-error').classList.add('hidden');
        }
    }

    // Handle delete account form submission
    const deleteAccountForm = document.getElementById('delete-account-form');
    if (deleteAccountForm) {
        deleteAccountForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const password = document.getElementById('delete-account-password').value;
            const reason = document.getElementById('delete-account-reason').value;
            const errorElement = document.getElementById('delete-password-error');
            
            // Hide previous errors
            if (errorElement) {
                errorElement.classList.add('hidden');
            }
            
            try {
                const response = await fetch('/api/account/archive', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        password: password,
                        reason: reason
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Account deleted successfully. Redirecting...', 'success');
                    
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = result.redirect || '/';
                    }, 1500);
                } else {
                    if (result.message && result.message.toLowerCase().includes('password')) {
                        if (errorElement) {
                            errorElement.textContent = result.message;
                            errorElement.classList.remove('hidden');
                        }
                    } else {
                        showNotification(result.message || 'Failed to delete account', 'error');
                    }
                }
            } catch (error) {
                console.error('Error deleting account:', error);
                showNotification('An error occurred while deleting your account', 'error');
            }
        });
    }

    // Close modal when clicking outside
    document.getElementById('delete-account-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            hideDeleteAccountModal();
        }
    });

    // Cart quantity controls
    document.addEventListener('click', async function(e) {
        // Increase quantity
        if (e.target.closest('.increase-qty')) {
            const button = e.target.closest('.increase-qty');
            const productId = button.getAttribute('data-product-id');
            const quantitySpan = button.previousElementSibling;
            const currentQty = parseInt(quantitySpan.textContent);
            
            try {
                const response = await fetch('/api/cart/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        product_id: productId, 
                        quantity: currentQty + 1 
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    // Update quantity display
                    quantitySpan.textContent = currentQty + 1;
                    updateCartSummary();
                    showNotification('Cart updated', 'success');
                } else {
                    showNotification(result.message || 'Failed to update quantity', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        }
        
        // Decrease quantity
        if (e.target.closest('.decrease-qty')) {
            const button = e.target.closest('.decrease-qty');
            const productId = button.getAttribute('data-product-id');
            const quantitySpan = button.nextElementSibling;
            const currentQty = parseInt(quantitySpan.textContent);
            
            if (currentQty <= 1) {
                showNotification('Quantity cannot be less than 1', 'error');
                return;
            }
            
            try {
                const response = await fetch('/api/cart/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        product_id: productId, 
                        quantity: currentQty - 1 
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    // Update quantity display
                    quantitySpan.textContent = currentQty - 1;
                    updateCartSummary();
                    showNotification('Cart updated', 'success');
                } else {
                    showNotification(result.message || 'Failed to update quantity', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        }
        
        // Remove item
        if (e.target.closest('.remove-cart-item')) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }
            
            const button = e.target.closest('.remove-cart-item');
            const productId = button.getAttribute('data-product-id');
            const cartItem = button.closest('.cart-item');
            
            try {
                const response = await fetch('/api/cart/remove', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                
                const result = await response.json();
                if (result.success) {
                    // Remove item from DOM
                    cartItem.remove();
                    updateCartSummary();
                    showNotification('Item removed from cart', 'success');
                    
                    // Check if cart is empty
                    const remainingItems = document.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        location.reload(); // Reload to show empty state
                    }
                } else {
                    showNotification(result.message || 'Failed to remove item', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        }
    });

    // Function to update cart summary
    async function updateCartSummary() {
        try {
            const response = await fetch('/api/cart');
            const result = await response.json();
            
            if (result.success) {
                const items = result.data?.cart_items || result.items || [];
                const totalQuantity = items.reduce((sum, item) => sum + item.quantity, 0); // Sum of all quantities
                const totalPrice = items.reduce((sum, item) => sum + item.total_price, 0);
                
                // Update each individual item's displayed price
                items.forEach(item => {
                    const cartItem = document.querySelector(`.cart-item[data-product-id="${item.product_id}"]`);
                    if (cartItem) {
                        const priceElement = cartItem.querySelector('.item-total-price');
                        if (priceElement) {
                            priceElement.textContent = `₱${parseFloat(item.total_price).toFixed(2)}`;
                        }
                    }
                });
                
                // Update subtotal display
                const totalQtyElement = document.getElementById('cart-total-qty');
                const totalPriceElement = document.getElementById('cart-total-price');
                
                if (totalQtyElement) totalQtyElement.textContent = totalQuantity;
                if (totalPriceElement) totalPriceElement.textContent = `₱${totalPrice.toFixed(2)}`;
            }
        } catch (error) {
            console.error('Error updating cart summary:', error);
        }
    }

    // Toggle Order Details Accordion
    function toggleOrderDetails(orderId) {
        const detailsElement = document.getElementById(orderId + '-details');
        const button = document.querySelector(`[onclick="toggleOrderDetails('${orderId}')"]`);
        const chevronIcon = button.querySelector('.chevron-icon');
        const viewDetailsText = button.querySelector('.view-details-text');
        
        if (detailsElement.classList.contains('hidden')) {
            // Show details
            detailsElement.classList.remove('hidden');
            detailsElement.classList.add('block');
            chevronIcon.style.transform = 'rotate(180deg)';
            viewDetailsText.textContent = 'Hide Details';
            
            // Reinitialize Lucide icons for the new content
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        } else {
            // Hide details
            detailsElement.classList.add('hidden');
            detailsElement.classList.remove('block');
            chevronIcon.style.transform = 'rotate(0deg)';
            viewDetailsText.textContent = 'View Details';
        }
    }

    // AJAX Pagination for Orders
    async function loadOrdersPage(page) {
        try {
            // Show loading state
            const ordersContainer = document.getElementById('orders-container');
            if (ordersContainer) {
                ordersContainer.style.opacity = '0.5';
                ordersContainer.style.pointerEvents = 'none';
            }

            const response = await fetch(`/api/account/orders?page=${page}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'include'
            });

            const result = await response.json();

            if (result.success) {
                // Update the orders container with new content
                if (ordersContainer) {
                    ordersContainer.innerHTML = result.html;
                    ordersContainer.style.opacity = '1';
                    ordersContainer.style.pointerEvents = 'auto';
                    
                    // Reinitialize Lucide icons for the new content
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            } else {
                console.error('Failed to load orders:', result.message);
                showNotification('Failed to load orders', 'error');
            }
        } catch (error) {
            console.error('Error loading orders:', error);
            showNotification('An error occurred while loading orders', 'error');
        } finally {
            // Remove loading state
            const ordersContainer = document.getElementById('orders-container');
            if (ordersContainer) {
                ordersContainer.style.opacity = '1';
                ordersContainer.style.pointerEvents = 'auto';
            }
        }
    }
</script>
@endpush