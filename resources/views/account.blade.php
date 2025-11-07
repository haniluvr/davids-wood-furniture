@extends('layouts.app')

@section('title', 'My Account | David\'s Wood Furniture - Handcrafteded furniture with timeless design.')

@push('styles')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f3efe7;
    }
    
    /* Account Page Select All Button Styles */
    .account-select-all-btn {
        background: transparent;
        border: 1px solid #8b7355;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        color: #8b7355;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
        white-space: nowrap;
        min-width: fit-content;
    }

    .account-select-all-btn:hover {
        background-color: #8b7355;
        color: white;
        transform: translateY(-1px);
    }

    .account-select-all-btn:active {
        transform: translateY(0);
    }

    /* Account Page Item Selection Checkbox Styles */
    .account-item-selection {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .account-item-checkbox {
        width: 1rem;
        height: 1rem;
        cursor: pointer;
        border: 2px solid #8b7355;
        border-radius: 4px;
        background-color: white;
        position: relative;
        transition: all 0.2s ease;
    }

    .account-item-checkbox:hover {
        border-color: #6b5b47;
        box-shadow: 0 0 0 2px rgba(139, 115, 85, 0.1);
    }

    .account-item-checkbox:checked {
        background-color: #8b7355;
        border-color: #8b7355;
    }

    .account-item-checkbox:checked::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
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
    
    /* Order Filter Tabs */
    .order-filter-tab {
        color: #6b7280;
        border-bottom-color: transparent;
    }
    .order-filter-tab:hover {
        color: #374151;
        border-bottom-color: #e5e7eb;
    }
    .order-filter-tab.active {
        color: #8b7355;
        border-bottom-color: #8b7355;
        font-weight: 600;
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
                        <a href="#" class="flex items-center text-gray-700 hover:text-[#8b7355] font-medium sidebar-link" data-target="payment-methods-section">
                            <i data-lucide="credit-card" class="mr-3 w-5 h-5"></i> Payment Methods
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
                                <label class="form-label block">USERNAME</label>
                                <input type="text" name="username" value="{{ $user->username }}" class="w-full form-input" required>
                                <p id="username-error" class="text-sm text-red-600 mt-1 hidden">Username is already taken or invalid.</p>
                                <p class="text-sm text-gray-500 mt-1">Choose a unique username for your account.</p>
                            </div>
                            <button type="submit" class="save-button">
                                SAVE
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="form-label block">LAST NAME</label>
                                <input type="text" name="last_name" value="{{ $user->last_name }}" class="w-full form-input" required>
                            </div>
                            <div>
                                <label class="form-label block">PHONE NUMBER</label>
                                <input type="tel" name="phone" id="phone-input" value="{{ $user->phone ?? '' }}" class="w-full form-input" data-original-phone="{{ $user->phone ?? '' }}" placeholder="09xxxxxxxxx" maxlength="11">
                                <p id="phone-error" class="text-sm text-red-600 mt-1 hidden">Phone number cannot be removed once added. You can only update it.</p>
                                <p id="phone-format-error" class="text-sm text-red-600 mt-1 hidden">Please enter a valid phone number.</p>
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
                    <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">
                        @if($user->hasPassword())
                            Password
                        @else
                            Add Password
                        @endif
                    </h3>
                    <form id="password-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            @if($user->hasPassword())
                                <p class="text-gray-600 mb-6">Change your account password for better security.</p>
                            @else
                                <p class="text-gray-600 mb-6">Add a password to your account for additional security and easier login.</p>
                            @endif
                        </div>
                        <div class="space-y-4">
                            @if($user->hasPassword())
                                <div>
                                    <label class="form-label block">CURRENT PASSWORD</label>
                                    <input type="password" name="current_password" id="current-password" class="w-full form-input" required>
                                    <p id="current-password-error" class="text-sm text-red-600 mt-1 hidden">Current password is incorrect.</p>
                                </div>
                            @endif
                            <div>
                                <label class="form-label block">
                                    @if($user->hasPassword())
                                        NEW PASSWORD
                                    @else
                                        PASSWORD
                                    @endif
                                </label>
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
                                        <button class="text-[#8b7355] hover:text-[#6b5b47] font-medium" onclick="showEditAddressForm()">
                                            @if($user->street || $user->city || $user->province)
                                                Edit
                                            @else
                                                Add
                                            @endif
                                        </button>
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
                    <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">
                        @if($user->street || $user->city || $user->province)
                            Edit Address
                        @else
                            Add Address
                        @endif
                    </h3>
                    <form id="update-address-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <p class="text-gray-600 mb-6">
                                @if($user->street || $user->city || $user->province)
                                    Update your default delivery address.
                                @else
                                    Add your default delivery address.
                                @endif
                            </p>
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
                    <h3 class="text-xl font-bold text-gray-900 mb-6">My Orders</h3>
                    
                    <!-- Order Status Filter Tabs -->
                    <div class="mb-6 border-b border-gray-200">
                        <nav class="flex gap-6 overflow-x-auto" id="order-filter-tabs">
                            <button onclick="filterOrders('all')" class="order-filter-tab active pb-3 px-1 text-sm font-medium border-b-2 transition-colors whitespace-nowrap" data-status="all">
                                All Orders
                            </button>
                            <button onclick="filterOrders('pending')" class="order-filter-tab pb-3 px-1 text-sm font-medium border-b-2 transition-colors whitespace-nowrap" data-status="pending">
                                Pending
                            </button>
                            <button onclick="filterOrders('processing')" class="order-filter-tab pb-3 px-1 text-sm font-medium border-b-2 transition-colors whitespace-nowrap" data-status="processing">
                                Processing
                            </button>
                            <button onclick="filterOrders('shipped')" class="order-filter-tab pb-3 px-1 text-sm font-medium border-b-2 transition-colors whitespace-nowrap" data-status="shipped">
                                Shipped
                            </button>
                            <button onclick="filterOrders('delivered')" class="order-filter-tab pb-3 px-1 text-sm font-medium border-b-2 transition-colors whitespace-nowrap" data-status="delivered">
                                Delivered
                            </button>
                            <button onclick="filterOrders('cancelled')" class="order-filter-tab pb-3 px-1 text-sm font-medium border-b-2 transition-colors whitespace-nowrap" data-status="cancelled">
                                Cancelled
                            </button>
                        </nav>
                    </div>
                    
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
                            <div class="aspect-square bg-gray-100 rounded-lg mb-3 flex items-center justify-center cursor-pointer hover:opacity-90 transition-opacity" 
                                 onclick="openQuickView({{ $item->product->id ?? 'null' }}, '{{ $item->product->slug ?? '' }}')">
                                @if($item->product && $item->product->image)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i data-lucide="heart" class="text-gray-400 w-8 h-8"></i>
                                @endif
                        </div>
                            <h3 class="font-medium text-gray-900 cursor-pointer hover:text-[#8b7355] transition-colors" 
                                data-product-id="{{ $item->product->id ?? '' }}" 
                                data-product-slug="{{ $item->product->slug ?? '' }}"
                                onclick="openQuickView({{ $item->product->id ?? 'null' }}, '{{ $item->product->slug ?? '' }}')">
                                {{ $item->product->name ?? 'Product' }}
                            </h3>
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
                <div class="flex items-center justify-between border-b pb-3 mb-8">
                    <h3 class="text-xl font-bold text-gray-900">My Cart</h3>
                    <button type="button" class="account-select-all-btn" id="account-select-all-cart-items">
                        Select All
                    </button>
                </div>
                @if($cartItems->count() > 0)
                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                        <div class="cart-item flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[#8b7355] transition-colors" data-product-id="{{ $item->product_id }}">
                            <!-- Selection Checkbox -->
                            <div class="account-item-selection">
                                <input type="checkbox" 
                                       class="account-item-checkbox w-4 h-4 text-[#8b7355] border-gray-300 rounded focus:ring-[#8b7355]" 
                                       data-product-id="{{ $item->product_id }}"
                                       data-item-total="{{ $item->total_price }}"
                                       checked>
                            </div>
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
                                <button onclick="window.open('{{ route('checkout.index') }}', '_blank')" class="flex-1 bg-[#8b7355] text-white px-6 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold">
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

            <!-- Payment Methods Section -->
            <div id="payment-methods-section" class="bg-white rounded-xl shadow-sm p-8 mb-8 account-card content-section" style="display: none;">
                <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">Payment Methods</h3>
                
                <div class="space-y-6">
                <!-- Add New Payment Method Button -->
                <div class="flex justify-end">
                    <button id="add-payment-method-btn" class="w-full border-2 border-dashed border-gray-300 rounded-lg p-4 text-gray-600 hover:border-[#8b7355] hover:text-[#8b7355] transition-colors" onclick="showAddPaymentMethodForm()">
                        + Add New Payment Method
                    </button>
                </div>
                    
                    <!-- Payment Methods List -->
                    <div id="payment-methods-list" class="space-y-4">
                        <!-- Payment methods will be loaded here -->
                    </div>
                    
                    <!-- Empty State -->
                    <div id="payment-methods-empty" class="text-center py-8 hidden">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="credit-card" class="text-gray-400 w-8 h-8"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No payment methods</h3>
                        <p class="text-gray-600 mb-4">Add a payment method to make checkout faster</p>
                    </div>
                </div>
                
                <!-- Add/Edit Payment Method Form (Hidden by default) -->
                <div id="add-payment-method-form" class="border-gray-200 pb-8 mb-8" style="display: none;">
                    <h3 class="border-b text-xl font-bold text-gray-900 mb-8 pb-3">Add Payment Method</h3>
                    <form id="payment-method-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <p class="text-gray-600 mb-6">Add a new payment method for faster checkout.</p>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Payment Type Selection -->
                            <div>
                                <label class="form-label block mb-3">PAYMENT TYPE</label>
                                <div class="flex space-x-8">
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_type" value="card" class="mr-3" checked>
                                        <span>Credit/Debit Card</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_type" value="gcash" class="mr-3">
                                        <span>GCash</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Card Fields -->
                            <div id="card-fields" class="space-y-6">
                                <div>
                                    <label class="form-label block mb-2">CARD NUMBER</label>
                                    <input type="text" name="card_number" id="card-number" class="w-full form-input" placeholder="1234 5678 9012 3456" maxlength="19">
                                </div>
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label class="form-label block mb-2">EXPIRY DATE</label>
                                        <input type="text" name="card_expiry" id="card-expiry" class="w-full form-input" placeholder="MM/YY" maxlength="5">
                                    </div>
                                    <div>
                                        <label class="form-label block mb-2">CVV</label>
                                        <input type="text" name="card_cvv" id="card-cvv" class="w-full form-input" placeholder="123" maxlength="4">
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label block mb-2">CARDHOLDER NAME</label>
                                    <input type="text" name="card_holder_name" class="w-full form-input" placeholder="John Doe">
                                </div>
                            </div>
                            
                            <!-- GCash Fields -->
                            <div id="gcash-fields" class="space-y-6" style="display: none;">
                                <div>
                                    <label class="form-label block mb-2">GCASH MOBILE NUMBER</label>
                                    <input type="text" name="gcash_number" id="gcash-number" class="w-full form-input" placeholder="09123456789" maxlength="11">
                                </div>
                                <div>
                                    <label class="form-label block mb-2">ACCOUNT NAME</label>
                                    <input type="text" name="gcash_name" class="w-full form-input" placeholder="John Doe">
                                </div>
                            </div>
                            
                            <!-- Billing Address -->
                            <div class="space-y-4">
                                <label class="form-label block mb-2">BILLING ADDRESS</label>
                                
                                <!-- Address Line 1 -->
                                <div>
                                    <label class="form-label block mb-2">Address Line 1 *</label>
                                    <input type="text" name="billing_address_line_1" class="w-full form-input" placeholder="Street address, P.O. box, company name, c/o">
                                </div>
                                
                                <!-- Address Line 2 -->
                                <div>
                                    <label class="form-label block mb-2">Address Line 2</label>
                                    <input type="text" name="billing_address_line_2" class="w-full form-input" placeholder="Apartment, suite, unit, building, floor, etc.">
                                </div>
                                
                                <!-- Region -->
                                <div>
                                    <label class="form-label block mb-2">Region *</label>
                                    <select name="billing_region" id="billing-region" class="w-full form-input" required>
                                        <option value="">Select Region</option>
                                    </select>
                                </div>
                                
                                <!-- Province -->
                                <div>
                                    <label class="form-label block mb-2">Province *</label>
                                    <select name="billing_province" id="billing-province" class="w-full form-input" required disabled>
                                        <option value="">Select Province</option>
                                    </select>
                                </div>
                                
                                <!-- City -->
                                <div>
                                    <label class="form-label block mb-2">City/Municipality *</label>
                                    <select name="billing_city" id="billing-city" class="w-full form-input" required disabled>
                                        <option value="">Select City/Municipality</option>
                                    </select>
                                </div>
                                
                                <!-- Barangay -->
                                <div>
                                    <label class="form-label block mb-2">Barangay</label>
                                    <select name="billing_barangay" id="billing-barangay" class="w-full form-input" disabled>
                                        <option value="">Select Barangay</option>
                                    </select>
                                </div>
                                
                                <!-- ZIP Code -->
                                <div>
                                    <label class="form-label block mb-2">ZIP Code *</label>
                                    <input type="text" name="billing_zip_code" class="w-full form-input" placeholder="ZIP Code">
                                </div>
                            </div>
                            
                            <!-- Set as Default -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_default" class="mr-3">
                                    <span>Set as default payment method</span>
                                </label>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="flex space-x-4 pt-6">
                                <button type="submit" class="bg-[#8b7355] text-white px-8 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold">
                                    Add Payment Method
                                </button>
                                <button type="button" onclick="hideAddPaymentMethodForm()" class="bg-gray-300 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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
                                <div id="two-factor-status" class="mt-2">
                                    <span id="two-factor-status-text" class="text-sm text-gray-500">Loading...</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button id="two-factor-toggle" class="text-[#8b7355] hover:text-[#6b5b47] font-medium" onclick="toggleTwoFactor()">Loading...</button>
                            </div>
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
                @if($user->isSsoUser() && !$user->hasPassword())
                    <label class="form-label block">CONFIRM EMAIL</label>
                    <input type="email" id="delete-account-confirmation" class="w-full form-input" required placeholder="Enter your email address">
                @else
                    <label class="form-label block">CONFIRM PASSWORD</label>
                    <input type="password" id="delete-account-confirmation" class="w-full form-input" required placeholder="Enter your password">
                @endif
                <p id="delete-confirmation-error" class="text-sm text-red-600 mt-1 hidden"></p>
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

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-[#8b7355] px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Write a Review</h3>
            <button onclick="closeReviewModal()" class="text-white hover:text-gray-200 transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="mb-4">
                <p class="text-sm text-gray-600">Product:</p>
                <p id="reviewProductName" class="text-lg font-semibold text-gray-900"></p>
            </div>
            
            <form id="reviewForm" onsubmit="submitReview(event)">
                <input type="hidden" id="reviewProductId" name="product_id">
                <input type="hidden" id="reviewOrderId" name="order_id">
                <input type="hidden" id="reviewRatingValue" name="rating" value="0">
                
                <!-- Star Rating -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                    <div class="flex gap-2">
                        <button type="button" onclick="setRating(1)" class="hover:scale-110 transition-transform">
                            <i id="star-1" data-lucide="star" class="w-8 h-8 text-gray-300 fill-current"></i>
                        </button>
                        <button type="button" onclick="setRating(2)" class="hover:scale-110 transition-transform">
                            <i id="star-2" data-lucide="star" class="w-8 h-8 text-gray-300 fill-current"></i>
                        </button>
                        <button type="button" onclick="setRating(3)" class="hover:scale-110 transition-transform">
                            <i id="star-3" data-lucide="star" class="w-8 h-8 text-gray-300 fill-current"></i>
                        </button>
                        <button type="button" onclick="setRating(4)" class="hover:scale-110 transition-transform">
                            <i id="star-4" data-lucide="star" class="w-8 h-8 text-gray-300 fill-current"></i>
                        </button>
                        <button type="button" onclick="setRating(5)" class="hover:scale-110 transition-transform">
                            <i id="star-5" data-lucide="star" class="w-8 h-8 text-gray-300 fill-current"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Review Title -->
                <div class="mb-6">
                    <label for="reviewTitle" class="block text-sm font-medium text-gray-700 mb-2">Review Title (Optional)</label>
                    <input 
                        type="text" 
                        id="reviewTitle" 
                        name="title" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent"
                        placeholder="e.g., Great quality furniture!"
                        maxlength="255"
                    >
                </div>
                
                <!-- Review Text -->
                <div class="mb-6">
                    <label for="reviewText" class="block text-sm font-medium text-gray-700 mb-2">Your Review *</label>
                    <textarea 
                        id="reviewText" 
                        name="review" 
                        rows="5" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-transparent"
                        placeholder="Share your thoughts about this product... (minimum 10 characters)"
                        required
                        minlength="10"
                        maxlength="1000"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">Minimum 10 characters, maximum 1000 characters</p>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="closeReviewModal()" 
                        class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 bg-[#8b7355] text-white px-6 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold"
                    >
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Refund Request Modal -->
<div id="refundModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 overflow-hidden max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-[#8b7355] px-6 py-4 flex justify-between items-center sticky top-0 z-10">
            <h3 class="text-xl font-bold text-white">Request a Refund</h3>
            <button onclick="closeRefundModal()" class="text-white hover:text-gray-200 transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="mb-4">
                <p class="text-sm text-gray-600">Product:</p>
                <p id="refundProductName" class="text-lg font-semibold text-gray-900"></p>
            </div>
            
            <form id="refundForm" onsubmit="submitRefundRequest(event)">
                <input type="hidden" id="refundProductId" name="product_id">
                <input type="hidden" id="refundOrderId" name="order_id">
                <input type="hidden" id="refundOrderItemId" name="order_item_id">
                
                <!-- Reason -->
                <div class="mb-6">
                    <label for="refundReason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Refund *</label>
                    <select id="refundReason" name="reason" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#8b7355] focus:outline-none focus:ring-2 focus:ring-[#8b7355]">
                        <option value="">Select a reason...</option>
                        <option value="defective">Defective Item</option>
                        <option value="item_not_as_described">Item Not as Described</option>
                        <option value="item_does_not_fit">Item Does Not Fit</option>
                        <option value="quality_issues">Quality Issues</option>
                        <option value="customer_dissatisfaction">Customer Dissatisfaction</option>
                        <option value="wrong_item">Wrong Item Received</option>
                        <option value="other">Other</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Please select the primary reason for your refund request</p>
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <label for="refundDescription" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea 
                        id="refundDescription" 
                        name="description" 
                        required 
                        minlength="10"
                        maxlength="1000"
                        rows="4"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#8b7355] focus:outline-none focus:ring-2 focus:ring-[#8b7355] resize-none"
                        placeholder="Please provide a detailed description of why you're requesting a refund (minimum 10 characters)..."
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">
                        <span id="refundDescriptionCount">0</span>/1000 characters
                    </p>
                </div>
                
                <!-- Customer Notes -->
                <div class="mb-6">
                    <label for="refundCustomerNotes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea 
                        id="refundCustomerNotes" 
                        name="customer_notes" 
                        maxlength="1000"
                        rows="3"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#8b7355] focus:outline-none focus:ring-2 focus:ring-[#8b7355] resize-none"
                        placeholder="Any additional information that might help us process your request..."
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">
                        <span id="refundNotesCount">0</span>/1000 characters
                    </p>
                </div>
                
                <!-- Photos -->
                <div class="mb-6">
                    <label for="refundPhotos" class="block text-sm font-medium text-gray-700 mb-2">Photos (Optional)</label>
                    <p class="text-xs text-gray-500 mb-2">Upload up to 5 photos to support your refund request (max 2MB each)</p>
                    <input 
                        type="file" 
                        id="refundPhotos" 
                        name="photos[]" 
                        multiple 
                        accept="image/jpeg,image/png,image/gif,image/webp,image/avif"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#8b7355] focus:outline-none focus:ring-2 focus:ring-[#8b7355]"
                    >
                    <div id="refundPhotoPreview" class="mt-3 grid grid-cols-5 gap-2 hidden"></div>
                </div>
                
                <!-- Error Message -->
                <div id="refundErrorMessage" class="mb-4 hidden">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <p id="refundErrorText"></p>
                    </div>
                </div>
                
                <!-- Success Message -->
                <div id="refundSuccessMessage" class="mb-4 hidden">
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                        <p id="refundSuccessText"></p>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="closeRefundModal()" 
                        class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        id="refundSubmitBtn"
                        class="flex-1 bg-[#8b7355] text-white px-6 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span id="refundSubmitText">Submit Request</span>
                        <span id="refundSubmitLoading" class="hidden">Submitting...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('frontend/js/payment-methods.js') }}"></script>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Initialize account cart selection functionality
        initializeAccountCartSelection();

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
                    
                    // Initialize PSGC functions when address book section is accessed
                    if (targetId === 'address-book-section' && regionsData.length === 0) {
                        loadRegions().catch(error => {
                            console.error('Failed to load regions:', error);
                        });
                    }
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

        // Username input validation and error clearing
        const usernameInput = document.querySelector('input[name="username"]');
        const usernameError = document.getElementById('username-error');
        
        if (usernameInput && usernameError) {
            // Clear any existing errors on page load
            usernameError.classList.add('hidden');
            usernameInput.classList.remove('border-red-500');
            
            usernameInput.addEventListener('input', function() {
                // Clear error when user starts typing
                usernameError.classList.add('hidden');
                this.classList.remove('border-red-500');
            });
        }

        // Handle personal information form submission
        const personalInfoForm = document.getElementById('personal-info-form');
        if (personalInfoForm) {
            personalInfoForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Get current user data for comparison
                const currentUser = {
                    first_name: '{{ $user->first_name }}',
                    last_name: '{{ $user->last_name }}',
                    username: '{{ $user->username }}',
                    email: '{{ $user->email }}',
                    phone: '{{ $user->phone ?? "" }}'
                };
                
                // Only validate fields that have actually changed
                const formData = new FormData(this);
                const allData = Object.fromEntries(formData);
                
                // Validate username only if it has changed
                const usernameInputs = document.querySelectorAll('input[name="username"]');
                
                // Find the username input within the personal info form, not the login modal
                const personalInfoForm = document.getElementById('personal-info-form');
                const usernameInput = personalInfoForm ? personalInfoForm.querySelector('input[name="username"]') : null;
                const usernameError = document.getElementById('username-error');
                
                
                if (usernameInput && usernameError) {
                    
                    const username = usernameInput.value.trim();
                    const hasChanged = username !== currentUser.username;
                    
                    
                    if (hasChanged) {
                        // Clear any existing errors
                        usernameError.classList.add('hidden');
                        usernameInput.classList.remove('border-red-500');
                        
                        // Basic username validation
                        if (!username || username.length === 0) {
                            usernameError.textContent = 'Username is required.';
                            usernameError.classList.remove('hidden');
                            usernameInput.classList.add('border-red-500');
                            return;
                        }
                        
                        // Username format validation (alphanumeric, underscore, hyphen, 3-20 characters)
                        const usernameRegex = /^[a-zA-Z0-9_-]{3,20}$/;
                        if (!usernameRegex.test(username)) {
                            usernameError.textContent = 'Username must be 3-20 characters long and contain only letters, numbers, underscores, and hyphens.';
                            usernameError.classList.remove('hidden');
                            usernameInput.classList.add('border-red-500');
                            return;
                        }
                    } else {
                        // Username hasn't changed, make sure error is hidden
                        usernameError.classList.add('hidden');
                        usernameInput.classList.remove('border-red-500');
                    }
                }
                
                // Validate phone only if it has changed
                if (allData.phone !== undefined && allData.phone !== currentUser.phone) {
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
                }
                
                // Check each field and only include if it has changed
                const data = {};
                if (allData.first_name && allData.first_name !== currentUser.first_name) {
                    data.first_name = allData.first_name;
                }
                if (allData.last_name && allData.last_name !== currentUser.last_name) {
                    data.last_name = allData.last_name;
                }
                if (usernameInput && usernameInput.value.trim() !== currentUser.username) {
                    data.username = usernameInput.value.trim();
                }
                if (allData.email && allData.email !== currentUser.email) {
                    data.email = allData.email;
                    data.password = allData.password; // Include password for email changes
                }
                if (allData.phone !== undefined && allData.phone !== currentUser.phone) {
                    data.phone = allData.phone;
                }
                
                
                // If no fields have changed, show message and return
                if (Object.keys(data).length === 0) {
                    showNotification('No changes detected', 'info');
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
                        showNotification('Personal information updated successfully!', 'success');
                        // Update the original phone value after successful save
                        if (phoneInput && data.phone) {
                            phoneInput.setAttribute('data-original-phone', data.phone);
                        }
                        // Force refresh the page
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Check if error is related to username
                        if (result.message && (result.message.toLowerCase().includes('username') || result.message.toLowerCase().includes('taken'))) {
                            if (usernameError) {
                                usernameError.textContent = result.message;
                                usernameError.classList.remove('hidden');
                            }
                            if (usernameInput) {
                                usernameInput.classList.add('border-red-500');
                            }
                        } else {
                            showNotification(result.message || 'Failed to update personal information', 'error');
                        }
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
                        // Force refresh the page
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
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
                        // Force refresh the page
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
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
    let billingRegionsData = [];
    let billingCurrentRegionCode = '';
    let billingCurrentProvinceCode = '';
    
    // Load all regions
    async function loadRegions() {
        try {
            const response = await fetch(`${PSGC_API}/regions`);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('❌ API Error Response:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
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
            
        } catch (error) {
            console.error('❌ Error loading regions:', error);
            
            // Fallback to static regions data
            regionsData = [
                { name: 'National Capital Region (NCR)', code: 'NCR' },
                { name: 'Cordillera Administrative Region (CAR)', code: 'CAR' },
                { name: 'Region I (Ilocos Region)', code: '01' },
                { name: 'Region II (Cagayan Valley)', code: '02' },
                { name: 'Region III (Central Luzon)', code: '03' },
                { name: 'Region IV-A (CALABARZON)', code: '04A' },
                { name: 'Region IV-B (MIMAROPA)', code: '04B' },
                { name: 'Region V (Bicol Region)', code: '05' },
                { name: 'Region VI (Western Visayas)', code: '06' },
                { name: 'Region VII (Central Visayas)', code: '07' },
                { name: 'Region VIII (Eastern Visayas)', code: '08' },
                { name: 'Region IX (Zamboanga Peninsula)', code: '09' },
                { name: 'Region X (Northern Mindanao)', code: '10' },
                { name: 'Region XI (Davao Region)', code: '11' },
                { name: 'Region XII (SOCCSKSARGEN)', code: '12' },
                { name: 'Region XIII (Caraga)', code: '13' },
                { name: 'Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)', code: 'BARMM' }
            ];
            
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
            
            showNotification('Using offline regions data. Some features may be limited.', 'warning');
        }
    }
    
    // Load provinces for selected region using v2 nested endpoint
    async function loadProvinces(regionCodeOrName) {
        try {
            currentRegionCode = regionCodeOrName;
            const url = `${PSGC_API}/regions/${encodeURIComponent(regionCodeOrName)}/provinces`;
            
            const response = await fetch(url);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('❌ Response error:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            // Extract provinces array from response (v2 API wraps in {data: [...]})
            const provinces = data.data || data;
            
            
            // Special case: Some regions (like NCR) have no provinces, go directly to cities
            if (!Array.isArray(provinces) || provinces.length === 0) {
                
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
            
        } catch (error) {
            console.error('❌ Error loading provinces:', error);
            
            // Fallback for NCR (no provinces, load cities directly)
            if (regionCodeOrName === 'National Capital Region (NCR)' || regionCodeOrName === 'NCR') {
                await loadCitiesDirectly(regionCodeOrName);
                return;
            }
            
            showNotification('Failed to load provinces. Please try again.', 'error');
        }
    }
    
    // Load cities/municipalities directly for a region (for regions without provinces like NCR)
    async function loadCitiesDirectly(regionCodeOrName) {
        try {
            const url = `${PSGC_API}/regions/${encodeURIComponent(regionCodeOrName)}/cities-municipalities`;
            
            const response = await fetch(url);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('❌ Response error:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
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
            
        } catch (error) {
            console.error('❌ Error loading cities:', error);
            
            // Fallback for NCR cities
            if (regionCodeOrName === 'National Capital Region (NCR)' || regionCodeOrName === 'NCR') {
                const ncrCities = [
                    { name: 'Caloocan', type: 'City' },
                    { name: 'Las Piñas', type: 'City' },
                    { name: 'Makati', type: 'City' },
                    { name: 'Malabon', type: 'City' },
                    { name: 'Mandaluyong', type: 'City' },
                    { name: 'Manila', type: 'City' },
                    { name: 'Marikina', type: 'City' },
                    { name: 'Muntinlupa', type: 'City' },
                    { name: 'Navotas', type: 'City' },
                    { name: 'Parañaque', type: 'City' },
                    { name: 'Pasay', type: 'City' },
                    { name: 'Pasig', type: 'City' },
                    { name: 'Pateros', type: 'Municipality' },
                    { name: 'Quezon City', type: 'City' },
                    { name: 'San Juan', type: 'City' },
                    { name: 'Taguig', type: 'City' },
                    { name: 'Valenzuela', type: 'City' }
                ];
                
                const citySelect = document.getElementById('city-select');
                if (citySelect) {
                    citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                    citySelect.disabled = false;
                    
                    ncrCities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.name;
                        option.textContent = `${city.name} (${city.type})`;
                        citySelect.appendChild(option);
                    });
                }
                
                showNotification('Using offline NCR cities data.', 'warning');
                return;
            }
            
            showNotification('Failed to load cities. Please try again.', 'error');
        }
    }
    
    // Load cities/municipalities for selected province using v2 nested endpoint
    async function loadCities(provinceCodeOrName) {
        try {
            currentProvinceCode = provinceCodeOrName;
            const url = `${PSGC_API}/regions/${encodeURIComponent(currentRegionCode)}/provinces/${encodeURIComponent(provinceCodeOrName)}/cities-municipalities`;
            
            const response = await fetch(url);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('❌ Response error:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
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
            
            const response = await fetch(url);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('❌ Response error:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
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
            
        } catch (error) {
            console.error('❌ Error loading barangays:', error);
            showNotification('Failed to load barangays. Please try again.', 'error');
        }
    }
    
    // Billing Address PSGC Functions
    async function loadBillingRegions() {
        try {
            const response = await fetch(`${PSGC_API}/regions`);
            const data = await response.json();
            billingRegionsData = data.data || data;
            
            const regionSelect = document.getElementById('billing-region');
            if (regionSelect) {
                regionSelect.innerHTML = '<option value="">Select Region</option>';
                
                if (Array.isArray(billingRegionsData)) {
                    billingRegionsData.forEach(region => {
                        const option = document.createElement('option');
                        option.value = region.name;
                        option.setAttribute('data-code', region.code);
                        option.textContent = region.name;
                        regionSelect.appendChild(option);
                    });
                }
            }
            
        } catch (error) {
            console.error('❌ Error loading billing regions:', error);
        }
    }
    
    async function loadBillingProvinces(regionCodeOrName) {
        try {
            billingCurrentRegionCode = regionCodeOrName;
            const url = `${PSGC_API}/regions/${encodeURIComponent(regionCodeOrName)}/provinces`;
            
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            const provinces = data.data || data;
            
            // Special case: Some regions (like NCR) have no provinces, go directly to cities
            if (!Array.isArray(provinces) || provinces.length === 0) {
                const provinceSelect = document.getElementById('billing-province');
                if (provinceSelect) {
                    provinceSelect.innerHTML = '<option value="">No provinces (loading cities...)</option>';
                    provinceSelect.disabled = true;
                }
                
                // Load cities directly for this region
                await loadBillingCitiesDirectly(regionCodeOrName);
                return;
            }
            
            const provinceSelect = document.getElementById('billing-province');
            if (provinceSelect) {
                provinceSelect.innerHTML = '<option value="">Select Province</option>';
                provinceSelect.disabled = false;
                
                if (Array.isArray(provinces)) {
                    provinces.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.name;
                        option.setAttribute('data-code', province.code);
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });
                }
            }
            
        } catch (error) {
            console.error('❌ Error loading billing provinces:', error);
        }
    }
    
    async function loadBillingCitiesDirectly(regionCodeOrName) {
        try {
            const url = `${PSGC_API}/regions/${encodeURIComponent(regionCodeOrName)}/cities-municipalities`;
            
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            const cities = data.data || data;
            
            const citySelect = document.getElementById('billing-city');
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
            
        } catch (error) {
            console.error('❌ Error loading billing cities:', error);
        }
    }
    
    async function loadBillingCities(provinceCodeOrName) {
        try {
            billingCurrentProvinceCode = provinceCodeOrName;
            const url = `${PSGC_API}/regions/${encodeURIComponent(billingCurrentRegionCode)}/provinces/${encodeURIComponent(provinceCodeOrName)}/cities-municipalities`;
            
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            const cities = data.data || data;
            
            const citySelect = document.getElementById('billing-city');
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
            
        } catch (error) {
            console.error('❌ Error loading billing cities:', error);
        }
    }
    
    async function loadBillingBarangays(cityCodeOrName) {
        try {
            const url = `${PSGC_API}/cities-municipalities/${encodeURIComponent(cityCodeOrName)}/barangays`;
            
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            const barangays = data.data || data;
            
            const barangaySelect = document.getElementById('billing-barangay');
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
            
        } catch (error) {
            console.error('❌ Error loading billing barangays:', error);
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

    // Set up billing address cascading dropdowns
    const billingRegionSelect = document.getElementById('billing-region');
    const billingProvinceSelect = document.getElementById('billing-province');
    const billingCitySelect = document.getElementById('billing-city');
    const billingBarangaySelect = document.getElementById('billing-barangay');
    
    if (billingRegionSelect) {
        billingRegionSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const regionName = selectedOption.value;
            const regionCode = selectedOption.getAttribute('data-code');
            
            
            // Reset province code (important for regions without provinces)
            billingCurrentProvinceCode = '';
            
            // Reset province, city and barangay
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
            
            if (regionName) {
                loadBillingProvinces(regionName);
            }
        });
    }
    
    if (billingProvinceSelect) {
        billingProvinceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const provinceName = selectedOption.value;
            const provinceCode = selectedOption.getAttribute('data-code');
            
            
            // Reset city and barangay
            if (billingCitySelect) {
                billingCitySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                billingCitySelect.disabled = true;
            }
            if (billingBarangaySelect) {
                billingBarangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                billingBarangaySelect.disabled = true;
            }
            
            if (provinceName) {
                loadBillingCities(provinceName);
            }
        });
    }
    
    if (billingCitySelect) {
        billingCitySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const cityName = selectedOption.value;
            const cityCode = selectedOption.getAttribute('data-code');
            
            
            // Reset barangay
            if (billingBarangaySelect) {
                billingBarangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                billingBarangaySelect.disabled = true;
            }
            
            if (cityName) {
                loadBillingBarangays(cityName);
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
        
        // Set up event listeners for address form elements
        setupAddressFormEventListeners();
        
        // Scroll to the edit form
        document.getElementById('edit-address-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function hideEditAddressForm() {
        document.getElementById('edit-address-form').style.display = 'none';
    }

    function showAddAddressForm() {
        showNotification('Add new address functionality coming soon!', 'info');
    }

    // Set up event listeners for address form elements
    function setupAddressFormEventListeners() {
        // Region select event listener
        const regionSelect = document.getElementById('region-select');
        if (regionSelect) {
            // Remove existing event listeners to prevent duplicates
            regionSelect.removeEventListener('change', handleRegionChange);
            regionSelect.addEventListener('change', handleRegionChange);
        }

        // Province select event listener
        const provinceSelect = document.getElementById('province-select');
        if (provinceSelect) {
            provinceSelect.removeEventListener('change', handleProvinceChange);
            provinceSelect.addEventListener('change', handleProvinceChange);
        }

        // City select event listener
        const citySelect = document.getElementById('city-select');
        if (citySelect) {
            citySelect.removeEventListener('change', handleCityChange);
            citySelect.addEventListener('change', handleCityChange);
        }
    }

    // Event handlers for address form
    async function handleRegionChange(event) {
        const selectedRegion = event.target.value;
        if (selectedRegion) {
            // Reset dependent selects
            const provinceSelect = document.getElementById('province-select');
            const citySelect = document.getElementById('city-select');
            const barangaySelect = document.getElementById('barangay-select');
            
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
            
            // Load provinces for selected region
            await loadProvinces(selectedRegion);
        }
    }

    async function handleProvinceChange(event) {
        const selectedProvince = event.target.value;
        if (selectedProvince) {
            // Reset dependent selects
            const citySelect = document.getElementById('city-select');
            const barangaySelect = document.getElementById('barangay-select');
            
            if (citySelect) {
                citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                citySelect.disabled = true;
            }
            if (barangaySelect) {
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                barangaySelect.disabled = true;
            }
            
            // Load cities for selected province
            await loadCities(selectedProvince);
        }
    }

    async function handleCityChange(event) {
        const selectedCity = event.target.value;
        if (selectedCity) {
            // Reset dependent selects
            const barangaySelect = document.getElementById('barangay-select');
            
            if (barangaySelect) {
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                barangaySelect.disabled = true;
            }
            
            // Load barangays for selected city
            await loadBarangays(selectedCity);
        }
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
            document.getElementById('delete-account-confirmation').value = '';
            document.getElementById('delete-account-reason').value = '';
            document.getElementById('delete-confirmation-error').classList.add('hidden');
        }
    }

    // Handle delete account form submission
    const deleteAccountForm = document.getElementById('delete-account-form');
    if (deleteAccountForm) {
        deleteAccountForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const isGoogleSso = {{ $user->isSsoUser() && !$user->hasPassword() ? 'true' : 'false' }};
            const confirmationValue = document.getElementById('delete-account-confirmation').value;
            const reason = document.getElementById('delete-account-reason').value;
            const errorElement = document.getElementById('delete-confirmation-error');
            
            // Hide previous errors
            if (errorElement) {
                errorElement.classList.add('hidden');
            }
            
            try {
                const requestBody = {
                    reason: reason
                };
                
                // Add password or email based on user type
                if (isGoogleSso) {
                    requestBody.email = confirmationValue;
                } else {
                    requestBody.password = confirmationValue;
                }
                
                const response = await fetch('/api/account/archive', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(requestBody)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Account deleted successfully. Redirecting...', 'success');
                    
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = result.redirect || '/';
                    }, 1500);
                } else {
                    if (result.message && (result.message.toLowerCase().includes('password') || result.message.toLowerCase().includes('email'))) {
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

    // Toggle Order Details Accordion (only one open at a time)
    function toggleOrderDetails(orderId) {
        const detailsElement = document.getElementById(orderId + '-details');
        const button = document.querySelector(`[onclick="toggleOrderDetails('${orderId}')"]`);
        const chevronIcon = button.querySelector('.chevron-icon');
        const viewDetailsText = button.querySelector('.view-details-text');
        
        // Check if this accordion is currently open
        const isCurrentlyOpen = !detailsElement.classList.contains('hidden');
        
        // Close all other open accordions first
        closeAllOrderDetails();
        
        // If this accordion wasn't open, open it
        if (!isCurrentlyOpen) {
            // Show details
            detailsElement.classList.remove('hidden');
            detailsElement.classList.add('block');
            chevronIcon.style.transform = 'rotate(180deg)';
            viewDetailsText.textContent = 'Hide Details';
            
            // Reinitialize Lucide icons for the new content
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    }

    // Close all order details accordions
    function closeAllOrderDetails() {
        // Find all order details elements
        const allDetailsElements = document.querySelectorAll('[id$="-details"]');
        
        allDetailsElements.forEach(detailsElement => {
            if (!detailsElement.classList.contains('hidden')) {
                // Get the order ID from the details element ID
                const orderId = detailsElement.id.replace('-details', '');
                const button = document.querySelector(`[onclick="toggleOrderDetails('${orderId}')"]`);
                
                if (button) {
                    const chevronIcon = button.querySelector('.chevron-icon');
                    const viewDetailsText = button.querySelector('.view-details-text');
                    
                    // Hide details
                    detailsElement.classList.add('hidden');
                    detailsElement.classList.remove('block');
                    
                    if (chevronIcon) {
                        chevronIcon.style.transform = 'rotate(0deg)';
                    }
                    if (viewDetailsText) {
                        viewDetailsText.textContent = 'View Details';
                    }
                }
            }
        });
    }

    // View Receipt function
    function viewReceipt(orderNumber) {
        window.open(`/account/receipt/${orderNumber}`, '_blank');
    }

    // Open Review Modal
    function openReviewModal(productId, orderId, productName) {
        const modal = document.getElementById('reviewModal');
        const modalProductName = document.getElementById('reviewProductName');
        const reviewForm = document.getElementById('reviewForm');
        
        // Set product name
        modalProductName.textContent = productName;
        
        // Set hidden form values
        document.getElementById('reviewProductId').value = productId;
        document.getElementById('reviewOrderId').value = orderId;
        
        // Reset form
        reviewForm.reset();
        setRating(0);
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Close Review Modal
    function closeReviewModal() {
        const modal = document.getElementById('reviewModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Set Rating
    let selectedRating = 0;
    function setRating(rating) {
        selectedRating = rating;
        document.getElementById('reviewRatingValue').value = rating;
        
        // Update star display
        for (let i = 1; i <= 5; i++) {
            const star = document.getElementById(`star-${i}`);
            if (i <= rating) {
                star.classList.add('text-yellow-400');
                star.classList.remove('text-gray-300');
            } else {
                star.classList.add('text-gray-300');
                star.classList.remove('text-yellow-400');
            }
        }
        
        // Reinitialize Lucide icons to apply color changes
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Submit Review
    async function submitReview(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        
        // Validate rating
        if (selectedRating === 0) {
            showNotification('Please select a rating', 'error');
            return;
        }
        
        // Validate review text
        const reviewText = formData.get('review');
        if (!reviewText || reviewText.trim().length < 10) {
            showNotification('Review must be at least 10 characters', 'error');
            return;
        }
        
        try {
            const response = await fetch('/api/reviews/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'include',
                body: JSON.stringify({
                    product_id: formData.get('product_id'),
                    order_id: formData.get('order_id'),
                    rating: formData.get('rating'),
                    title: formData.get('title'),
                    review: reviewText
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification(result.message, 'success');
                closeReviewModal();
                
                // Reload orders to update the "Write Review" button (preserving current filter)
                loadOrdersPage(1, currentOrderStatus);
            } else {
                showNotification(result.message || 'Failed to submit review', 'error');
            }
        } catch (error) {
            console.error('Error submitting review:', error);
            showNotification('An error occurred while submitting your review', 'error');
        }
    }

    // Open Refund Modal
    function openRefundModal(productId, orderId, orderItemId, productName) {
        const modal = document.getElementById('refundModal');
        const modalProductName = document.getElementById('refundProductName');
        const refundForm = document.getElementById('refundForm');
        
        // Set product name
        modalProductName.textContent = productName;
        
        // Set hidden form values
        document.getElementById('refundProductId').value = productId;
        document.getElementById('refundOrderId').value = orderId;
        document.getElementById('refundOrderItemId').value = orderItemId;
        
        // Reset form
        refundForm.reset();
        document.getElementById('refundDescriptionCount').textContent = '0';
        document.getElementById('refundNotesCount').textContent = '0';
        document.getElementById('refundPhotoPreview').classList.add('hidden');
        document.getElementById('refundPhotoPreview').innerHTML = '';
        document.getElementById('refundErrorMessage').classList.add('hidden');
        document.getElementById('refundSuccessMessage').classList.add('hidden');
        document.getElementById('refundSubmitBtn').disabled = false;
        document.getElementById('refundSubmitText').classList.remove('hidden');
        document.getElementById('refundSubmitLoading').classList.add('hidden');
        
        // Add character counters
        const descriptionField = document.getElementById('refundDescription');
        const notesField = document.getElementById('refundCustomerNotes');
        
        descriptionField.addEventListener('input', function() {
            document.getElementById('refundDescriptionCount').textContent = this.value.length;
        });
        
        notesField.addEventListener('input', function() {
            document.getElementById('refundNotesCount').textContent = this.value.length;
        });
        
        // Add photo preview
        const photoInput = document.getElementById('refundPhotos');
        photoInput.addEventListener('change', function(e) {
            const preview = document.getElementById('refundPhotoPreview');
            preview.innerHTML = '';
            
            if (e.target.files.length > 0) {
                preview.classList.remove('hidden');
                
                Array.from(e.target.files).slice(0, 5).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-20 object-cover rounded-lg border border-gray-300';
                        img.alt = `Preview ${index + 1}`;
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                preview.classList.add('hidden');
            }
        });
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Reinitialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Close Refund Modal
    function closeRefundModal() {
        const modal = document.getElementById('refundModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Submit Refund Request
    async function submitRefundRequest(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        
        // Hide previous messages
        document.getElementById('refundErrorMessage').classList.add('hidden');
        document.getElementById('refundSuccessMessage').classList.add('hidden');
        
        // Validate description
        const description = formData.get('description');
        if (!description || description.trim().length < 10) {
            document.getElementById('refundErrorText').textContent = 'Description must be at least 10 characters';
            document.getElementById('refundErrorMessage').classList.remove('hidden');
            return;
        }
        
        // Validate reason
        const reason = formData.get('reason');
        if (!reason) {
            document.getElementById('refundErrorText').textContent = 'Please select a reason for the refund';
            document.getElementById('refundErrorMessage').classList.remove('hidden');
            return;
        }
        
        // Disable submit button
        const submitBtn = document.getElementById('refundSubmitBtn');
        submitBtn.disabled = true;
        document.getElementById('refundSubmitText').classList.add('hidden');
        document.getElementById('refundSubmitLoading').classList.remove('hidden');
        
        try {
            const response = await fetch('{{ route("account.refund-request.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'include',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                document.getElementById('refundSuccessText').textContent = result.message || 'Refund request submitted successfully!';
                document.getElementById('refundSuccessMessage').classList.remove('hidden');
                
                // Reload orders after a short delay
                setTimeout(() => {
                    closeRefundModal();
                    // Reload orders to update the refund request status (preserving current filter)
                    if (typeof loadOrdersPage === 'function') {
                        loadOrdersPage(1, currentOrderStatus);
                    } else {
                        location.reload();
                    }
                }, 2000);
            } else {
                // Show error message
                const errorMsg = result.message || (result.errors ? Object.values(result.errors).flat().join(', ') : 'Failed to submit refund request');
                document.getElementById('refundErrorText').textContent = errorMsg;
                document.getElementById('refundErrorMessage').classList.remove('hidden');
                
                // Re-enable submit button
                submitBtn.disabled = false;
                document.getElementById('refundSubmitText').classList.remove('hidden');
                document.getElementById('refundSubmitLoading').classList.add('hidden');
            }
        } catch (error) {
            console.error('Error submitting refund request:', error);
            document.getElementById('refundErrorText').textContent = 'An error occurred while submitting your refund request. Please try again.';
            document.getElementById('refundErrorMessage').classList.remove('hidden');
            
            // Re-enable submit button
            submitBtn.disabled = false;
            document.getElementById('refundSubmitText').classList.remove('hidden');
            document.getElementById('refundSubmitLoading').classList.add('hidden');
        }
    }

    // Order Filtering
    let currentOrderStatus = 'all';
    
    async function filterOrders(status) {
        currentOrderStatus = status;
        
        // Update active tab
        document.querySelectorAll('.order-filter-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelector(`[data-status="${status}"]`).classList.add('active');
        
        // Load filtered orders
        await loadOrdersPage(1, status);
    }

    // AJAX Pagination for Orders
    async function loadOrdersPage(page, status = null) {
        try {
            // Use current status if not provided
            if (status === null) {
                status = currentOrderStatus;
            }
            
            // Show loading state
            const ordersContainer = document.getElementById('orders-container');
            if (ordersContainer) {
                ordersContainer.style.opacity = '0.5';
                ordersContainer.style.pointerEvents = 'none';
            }

            // Build URL with status filter
            let url = `/api/account/orders?page=${page}`;
            if (status && status !== 'all') {
                url += `&status=${status}`;
            }

            const response = await fetch(url, {
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

    // Function to open quick view modal for wishlist items
    async function openQuickView(productId, productSlug) {
        if (!productId) {
            console.error('Product ID is required');
            return;
        }

        try {
            // Fetch product data
            const response = await fetch(`/api/products/id/${productId}`);
            if (!response.ok) {
                throw new Error('Failed to fetch product data');
            }
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Failed to fetch product data');
            }
            
            const product = result.data;
            
            // Fill modal with product information
            await fillQuickViewModal(product);
            
            // Show modal
            if (typeof window.showmodalQuickView === 'function') {
                window.showmodalQuickView();
            } else {
                // Fallback method
                const modal = document.getElementById('modalQuickView');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            }
            
            // Re-init icons after modal opens
            setTimeout(() => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }, 100);
            
        } catch (error) {
            console.error('Error opening quick view:', error);
            showNotification('Failed to load product details', 'error');
        }
    }
    
    // ── Account Cart Selection Functions ──
    
    // Initialize account cart selection functionality
    function initializeAccountCartSelection() {
        
        // Remove existing event listeners to prevent duplicates
        const itemCheckboxes = document.querySelectorAll('.account-item-checkbox');
        
        itemCheckboxes.forEach((checkbox, index) => {
            // Remove existing event listeners by cloning the element
            const newCheckbox = checkbox.cloneNode(true);
            checkbox.parentNode.replaceChild(newCheckbox, checkbox);
            
            // Add fresh event listener
            newCheckbox.addEventListener('change', function() {
                updateAccountCartSubtotal();
                updateAccountSelectAllButton();
            });
        });
        
        // Add event listener to select all button
        const selectAllBtn = document.getElementById('account-select-all-cart-items');
        if (selectAllBtn) {
            
            // Remove existing event listeners
            const newSelectAllBtn = selectAllBtn.cloneNode(true);
            selectAllBtn.parentNode.replaceChild(newSelectAllBtn, selectAllBtn);
            
            newSelectAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleAccountSelectAll();
            });
        } else {
            console.warn('Account select all button not found');
        }
        
        // Initialize the select all button text based on current state
        updateAccountSelectAllButton();
        updateAccountCartSubtotal();
    }
    
    // Toggle account select all functionality
    function toggleAccountSelectAll() {
        const itemCheckboxes = document.querySelectorAll('.account-item-checkbox');
        const selectAllBtn = document.getElementById('account-select-all-cart-items');
        
        
        if (!itemCheckboxes.length || !selectAllBtn) {
            console.warn('Missing elements for toggleAccountSelectAll');
            return;
        }
        
        // Check if all items are selected
        const allSelected = Array.from(itemCheckboxes).every(checkbox => checkbox.checked);
        
        if (allSelected) {
            // Deselect all
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        } else {
            // Select all
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        }
        
        // Update UI after toggling
        updateAccountCartSubtotal();
        updateAccountSelectAllButton();
    }
    
    // Update account select all button text based on current selection
    function updateAccountSelectAllButton() {
        const itemCheckboxes = document.querySelectorAll('.account-item-checkbox');
        const selectAllBtn = document.getElementById('account-select-all-cart-items');
        
        
        if (!selectAllBtn || itemCheckboxes.length === 0) {
            console.warn('Missing elements for updateAccountSelectAllButton');
            return;
        }
        
        const selectedCount = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked).length;
        const totalCount = itemCheckboxes.length;
        
        
        if (selectedCount === 0) {
            selectAllBtn.textContent = 'Select All';
        } else if (selectedCount === totalCount) {
            selectAllBtn.textContent = 'Deselect All';
        } else {
            // When some items are selected but not all, show "Select All" to select remaining items
            selectAllBtn.textContent = 'Select All';
        }
    }
    
    // Update account cart subtotal based on selected items
    function updateAccountCartSubtotal() {
        const cartTotalPrice = document.getElementById('cart-total-price');
        const cartTotalQty = document.getElementById('cart-total-qty');
        
        if (!cartTotalPrice) return;
        
        const selectedCheckboxes = document.querySelectorAll('.account-item-checkbox:checked');
        let selectedTotal = 0;
        let selectedQty = 0;
        
        selectedCheckboxes.forEach(checkbox => {
            const itemTotal = parseFloat(checkbox.dataset.itemTotal) || 0;
            selectedTotal += itemTotal;
            selectedQty += 1; // Each checkbox represents one item
        });
        
        if (cartTotalPrice) {
            cartTotalPrice.textContent = `₱${selectedTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        }
        
        if (cartTotalQty) {
            cartTotalQty.textContent = selectedQty;
        }
    }

    // Two-Factor Authentication functionality
    let twoFactorEnabled = false;

    // Load 2FA status on page load
    async function loadTwoFactorStatus() {
        try {
            const response = await fetch('/api/account/two-factor/status', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            });

            const data = await response.json();
            
            if (data.success) {
                twoFactorEnabled = data.two_factor_enabled;
                updateTwoFactorUI();
            }
        } catch (error) {
            console.error('Error loading 2FA status:', error);
            document.getElementById('two-factor-status-text').textContent = 'Error loading status';
            document.getElementById('two-factor-toggle').textContent = 'Error';
        }
    }

    // Update the UI based on 2FA status
    function updateTwoFactorUI() {
        const statusText = document.getElementById('two-factor-status-text');
        const toggleButton = document.getElementById('two-factor-toggle');

        if (twoFactorEnabled) {
            statusText.textContent = 'Two-factor authentication is enabled';
            statusText.className = 'text-sm text-green-600';
            toggleButton.textContent = 'Disable';
            toggleButton.className = 'text-red-600 hover:text-red-700 font-medium';
        } else {
            statusText.textContent = 'Two-factor authentication is disabled';
            statusText.className = 'text-sm text-gray-500';
            toggleButton.textContent = 'Enable';
            toggleButton.className = 'text-[#8b7355] hover:text-[#6b5b47] font-medium';
        }
    }

    // Toggle 2FA functionality
    async function toggleTwoFactor() {
        const password = prompt(twoFactorEnabled ? 'Enter your password to disable two-factor authentication:' : 'Enter your password to enable two-factor authentication:');
        
        if (!password) {
            return;
        }

        const toggleButton = document.getElementById('two-factor-toggle');
        const originalText = toggleButton.textContent;
        toggleButton.textContent = 'Processing...';
        toggleButton.disabled = true;

        try {
            const endpoint = twoFactorEnabled ? '/api/account/two-factor/disable' : '/api/account/two-factor/enable';
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ password: password }),
            });

            const data = await response.json();

            if (data.success) {
                twoFactorEnabled = !twoFactorEnabled;
                updateTwoFactorUI();
                alert(data.message);
            } else {
                alert(data.message || 'An error occurred');
            }
        } catch (error) {
            console.error('Error toggling 2FA:', error);
            alert('An error occurred while updating two-factor authentication');
        } finally {
            toggleButton.textContent = originalText;
            toggleButton.disabled = false;
        }
    }

    // Load 2FA status when the page loads
    loadTwoFactorStatus();
</script>
@endpush