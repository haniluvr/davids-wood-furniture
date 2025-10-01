@extends('layouts.app')

@section('title', 'My Account | David\'s Wood Furniture - Handcrafteded furniture with timeless design.')

@push('styles')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #F8F8F8;
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
    }
    .form-input:focus {
        outline: none;
        border-color: #8b7355;
        box-shadow: 0 0 0 2px rgba(139, 115, 85, 0.1);
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
</style>
@endpush

@section('content')
<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8 mt-5 pt-5">
        <!-- Sidebar -->
        <div class="w-full md:w-1/4 sticky top-8 self-start">
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
                        <a href="#" class="flex items-center text-gray-700 hover:text-[#8b7355] font-medium sidebar-link" data-target="newsletter-section">
                            <i data-lucide="mail" class="mr-3 w-5 h-5"></i> My Newsletter
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
                <h2 class="text-3xl font-bold text-gray-900 mb-8">My Details</h2>
                
                <!-- Personal Information Section -->
                <div class="border-b border-gray-200 pb-8 mb-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Personal Information</h3>
                            <p class="text-gray-600 mb-6">Assertively utilize adaptive customer service for future-proof platforms. Completely drive optimal markets.</p>
                    </div>
                        <div class="space-y-4">
                    <div>
                                <label class="form-label block">FIRST NAME</label>
                                <input type="text" name="first_name" value="{{ $user->first_name }}" class="w-full form-input">
                    </div>
                            <div>
                                <label class="form-label block">SECOND NAME</label>
                                <input type="text" name="last_name" value="{{ $user->last_name }}" class="w-full form-input">
                </div>
                            <div>
                                <label class="form-label block">BIRTH DATE</label>
                                <div class="relative">
                                    <input type="text" placeholder="dd/mm/yy" class="w-full form-input">
                                    <i data-lucide="calendar" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
            </div>
                        </div>
                        <div>
                                <label class="form-label block">PHONE NUMBER</label>
                                <input type="tel" name="phone" value="{{ $user->phone ?? '123456789' }}" class="w-full form-input">
                                <p class="text-sm text-gray-500 mt-1">Keep 9-digit format with no spaces and dashes.</p>
                        </div>
                            <button type="submit" class="save-button">
                                SAVE
                            </button>
                    </div>
                        </div>
            </div>

                <!-- E-mail Address Section -->
                <div class="border-b border-gray-200 pb-8 mb-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">E-mail address</h3>
                            <p class="text-gray-600 mb-6">Assertively utilize adaptive customer service for future-proof platforms. Completely drive optimal markets.</p>
                </div>
                <div class="space-y-4">
                            <div>
                                <label class="form-label block">E-MAIL ADDRESS</label>
                                <input type="email" name="email" value="{{ $user->email }}" class="w-full form-input">
                        </div>
                            <div>
                                <label class="form-label block">PASSWORD</label>
                                <input type="password" value="************" class="w-full form-input">
                        </div>
                            <button type="submit" class="save-button">
                                SAVE
                            </button>
                    </div>
                    </div>
            </div>

                <!-- Password Section -->
                            <div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Password</h3>
                            <p class="text-gray-600 mb-6">Assertively utilize adaptive customer service for future-proof platforms. Completely drive optimal markets.</p>
                            </div>
                        <div class="space-y-4">
                            <div>
                                <label class="form-label block">CURRENT PASSWORD</label>
                                <input type="text" value="{{ $user->email }}" class="w-full form-input">
                            </div>
                            <div>
                                <label class="form-label block">NEW PASSWORD</label>
                                <div class="relative">
                                    <input type="password" value="************" class="w-full form-input">
                                    <i data-lucide="eye" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5 cursor-pointer"></i>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Make sure your password is 8 characters long and contains letters and numbers.</p>
                            </div>
                            <div>
                                <label class="form-label block">CONFIRM PASSWORD</label>
                                <div class="relative">
                                    <input type="password" value="************" class="w-full form-input">
                                    <i data-lucide="eye" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5 cursor-pointer"></i>
                                </div>
                            </div>
                            <button type="submit" class="save-button">
                                SAVE
                            </button>
                        </div>
                            </div>
                        </div>
            </div>

            <!-- Address Book Section -->
            <div id="address-book-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 account-card content-section" style="display: none;">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">My Address Book</h2>
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900">Default Address</h3>
                                <p class="text-gray-600">{{ $user->street ?? 'No address provided' }}</p>
                                <p class="text-gray-600">{{ $user->city ?? '' }}, {{ $user->province ?? '' }}</p>
                            </div>
                            <button class="text-[#8b7355] hover:text-[#6b5b47]" onclick="showEditAddressForm()">Edit</button>
                        </div>
                            </div>
                    <button class="w-full border-2 border-dashed border-gray-300 rounded-lg p-4 text-gray-600 hover:border-[#8b7355] hover:text-[#8b7355] transition-colors" onclick="showAddAddressForm()">
                        + Add New Address
                    </button>
                        </div>
                
                <!-- Edit Address Form (Hidden by default) -->
                <div id="edit-address-form" class="mt-6 p-4 border border-gray-200 rounded-lg" style="display: none;">
                    <h3 class="font-medium text-gray-900 mb-4">Edit Address</h3>
                    <form id="update-address-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                            <input type="text" name="street" value="{{ $user->street ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-[#8b7355]" required>
                            </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input type="text" name="city" value="{{ $user->city ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-[#8b7355]" required>
                        </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                                <input type="text" name="province" value="{{ $user->province ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-[#8b7355]" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Barangay</label>
                                <input type="text" name="barangay" value="{{ $user->barangay ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-[#8b7355]">
                    </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                                <input type="text" name="zip_code" value="{{ $user->zip_code ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b7355] focus:border-[#8b7355]">
                        </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="bg-[#8b7355] text-white px-6 py-2 rounded-lg hover:bg-[#6b5b47] transition-colors">
                                Update Address
                            </button>
                            <button type="button" onclick="hideEditAddressForm()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                                Cancel
                            </button>
                    </div>
                    </form>
                </div>
            </div>

            <!-- My Orders Section -->
            <div id="my-orders-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 account-card content-section" style="display: none;">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">My Orders</h2>
                @if($orders->count() > 0)
                    <div class="space-y-4">
                        @foreach($orders as $order)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium text-gray-900">Order #{{ $order->order_number }}</h3>
                                    <p class="text-gray-600">Placed on {{ $order->created_at->format('M j, Y') }}</p>
                                    <p class="text-gray-600">{{ $order->orderItems->count() }} items</p>
                        </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 capitalize">{{ $order->status }}</span>
                        </div>
                        </div>
                    </div>
                        @endforeach
                        </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="package" class="text-gray-400 w-8 h-8"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
                        <p class="text-gray-600 mb-4">Start shopping to see your orders here</p>
                        <a href="{{ route('products') }}" class="bg-[#8b7355] text-white px-6 py-2 rounded-lg hover:bg-[#6b5b47] transition-colors">
                            Browse Products
                            </a>
                        </div>
                @endif
                    </div>

            <!-- My Wishlist Section -->
            <div id="my-wishlist-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 account-card content-section" style="display: none;">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">My Wishlist</h2>
                @if($wishlistItems->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
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
                            <p class="text-gray-600">${{ number_format($item->product->price ?? 0, 2) }}</p>
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

            <!-- Newsletter Section -->
            <div id="newsletter-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 account-card content-section" style="display: none;">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">My Newsletter</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-900">Product Updates</h3>
                            <p class="text-gray-600">Get notified about new products and collections</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" {{ $user->newsletter_product_updates ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#8b7355]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8b7355]"></div>
                        </label>
                        </div>
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                            <h3 class="font-medium text-gray-900">Special Offers</h3>
                            <p class="text-gray-600">Receive exclusive discounts and promotions</p>
                    </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" {{ $user->newsletter_special_offers ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#8b7355]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8b7355]"></div>
                        </label>
                    </div>
                    </div>
            </div>

            <!-- Account Settings Section -->
            <div id="account-settings-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 account-card content-section" style="display: none;">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Account Settings</h2>
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-gray-900">Change Password</h3>
                                <p class="text-gray-600">Update your account password</p>
                            </div>
                            <button class="text-[#8b7355] hover:text-[#6b5b47] font-medium">Change</button>
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
                            <button class="text-red-600 hover:text-red-700 font-medium">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
                </div>
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

        // Handle form submissions
        const myDetailsForm = document.getElementById('my-details-form');
        if (myDetailsForm) {
            myDetailsForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
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
                    showNotification('Profile updated successfully!', 'success');
                } else {
                    showNotification(result.message || 'Failed to update profile', 'error');
                }
            } catch (error) {
                showNotification('An error occurred while updating profile', 'error');
            }
            });
        }

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
                
                const formData = new FormData(this);
                const data = Object.fromEntries(formData);
                
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
                    showNotification('An error occurred while updating address', 'error');
                }
            });
        }
    });

    // Address form helper functions
    function showEditAddressForm() {
        document.getElementById('edit-address-form').style.display = 'block';
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
</script>
@endpush