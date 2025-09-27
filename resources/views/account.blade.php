@extends('layouts.app')

@section('title', 'Profile Dashboard | David\'s Wood Furniture - Handcrafteded furniture with timeless design.')

@push('styles')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #faf7f2;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .order-progress {
        height: 8px;
    }
    .progress-step {
        transition: all 0.5s ease;
    }
    .receipt-item {
        border-left: 3px solid #c79f6c;
    }
    .sidebar-link.active {
        color: #c79f6c;
        font-weight: 600;
    }
</style>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    beige: {
                        50: '#faf7f2',
                        100: '#f4ede6',
                        200: '#e9d9c7',
                        300: '#dec6a9',
                        400: '#d2b28a',
                        500: '#c79f6c',
                        600: '#b38f61',
                        700: '#997a53',
                        800: '#7f6545',
                        900: '#655037',
                    },
                    neutral: {
                        50: '#f8f8f8',
                        100: '#f0f0f0',
                        200: '#e0e0e0',
                        300: '#d0d0d0',
                        400: '#b0b0b0',
                        500: '#909090',
                        600: '#707070',
                        700: '#505050',
                        800: '#303030',
                        900: '#101010',
                    }
                }
            }
        }
    }
</script>
@endpush

@section('content')
<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8 mt-5 pt-5">
        <!-- Sidebar -->
        <div class="w-full md:w-1/4 sticky top-8 self-start">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6 card">
                <div class="flex items-center mb-6">
                    <div class="bg-beige-200 rounded-full w-16 h-16 flex items-center justify-center mr-4">
                        <i data-lucide="user" class="text-beige-700 w-8 h-8"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-beige-900">Alex Morgan</h2>
                        <p class="text-beige-600">alex@example.com</p>
                    </div>
                </div>
                <ul class="space-y-3">
                    <li>
                        <a href="#" class="flex items-center text-beige-700 hover:text-beige-900 font-medium sidebar-link" data-target="profile-section">
                            <i data-lucide="user" class="mr-3 w-5 h-5"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-beige-700 hover:text-beige-900 font-medium sidebar-link" data-target="wishlist-section">
                            <i data-lucide="heart" class="mr-3 w-5 h-5"></i> Wishlist
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-beige-700 hover:text-beige-900 font-medium sidebar-link" data-target="cart-section">
                            <i data-lucide="shopping-cart" class="mr-3 w-5 h-5"></i> Cart
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-beige-700 hover:text-beige-900 font-medium sidebar-link" data-target="orders-section">
                            <i data-lucide="package" class="mr-3 w-5 h-5"></i> My Orders
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-beige-700 hover:text-beige-900 font-medium sidebar-link" data-target="tracking-section">
                            <i data-lucide="map-pin" class="mr-3 w-5 h-5"></i> Tracking
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-beige-700 hover:text-beige-900 font-medium sidebar-link" data-target="receipts-section">
                            <i data-lucide="file-text" class="mr-3 w-5 h-5"></i> Receipts
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 card">
                <h3 class="font-semibold text-beige-900 mb-4">Account Settings</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="#" class="flex items-center text-beige-700 hover:text-beige-900 sidebar-link" data-target="edit-profile-section">
                            <i data-lucide="edit" class="mr-3 w-5 h-5"></i> Edit Profile
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-beige-700 hover:text-beige-900 sidebar-link" data-target="security-section">
                            <i data-lucide="lock" class="mr-3 w-5 h-5"></i> Security
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-beige-700 hover:text-beige-900 sidebar-link" data-target="payment-section">
                            <i data-lucide="credit-card" class="mr-3 w-5 h-5"></i> Payment Methods
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-beige-700 hover:text-beige-900 sidebar-link" data-target="logout-section">
                            <i data-lucide="log-out" class="mr-3 w-5 h-5"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="w-full md:w-3/4">
            <!-- Customer Information -->
            <div id="profile-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 card content-section" data-aos="fade-up">
                <h2 class="text-2xl font-bold text-beige-900 mb-6">Customer Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-beige-800 mb-2">Personal Details</h3>
                        <ul class="space-y-2">
                            <li class="flex justify-between border-b border-beige-100 pb-2">
                                <span class="text-beige-600">Name:</span>
                                <span class="text-beige-900">Alex Morgan</span>
                            </li>
                            <li class="flex justify-between border-b border-beige-100 pb-2">
                                <span class="text-beige-600">Email:</span>
                                <span class="text-beige-900">alex@example.com</span>
                            </li>
                            <li class="flex justify-between border-b border-beige-100 pb-2">
                                <span class="text-beige-600">Phone:</span>
                                <span class="text-beige-900">+1 (555) 123-4567</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-beige-800 mb-2">Address</h3>
                        <ul class="space-y-2">
                            <li class="flex justify-between border-b border-beige-100 pb-2">
                                <span class="text-beige-600">Street:</span>
                                <span class="text-beige-900">123 Main Street</span>
                            </li>
                            <li class="flex justify-between border-b border-beige-100 pb-2">
                                <span class="text-beige-600">City:</span>
                                <span class="text-beige-900">New York</span>
                            </li>
                            <li class="flex justify-between border-b border-beige-100 pb-2">
                                <span class="text-beige-600">Country:</span>
                                <span class="text-beige-900">United States</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Wishlist -->
            <div id="wishlist-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 card content-section" data-aos="fade-up">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-beige-900">Wishlist</h2>
                    <a href="#" class="text-beige-600 hover:text-beige-800 flex items-center">
                        View All <i data-lucide="chevron-right" class="ml-1 w-4 h-4"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="border border-beige-200 rounded-lg p-4 flex items-center">
                        <div class="bg-beige-100 rounded-lg w-16 h-16 flex items-center justify-center mr-4">
                            <i data-lucide="heart" class="text-beige-700"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-beige-900">Minimal Watch</h3>
                            <p class="text-beige-600">$129.99</p>
                        </div>
                    </div>
                    <div class="border border-beige-200 rounded-lg p-4 flex items-center">
                        <div class="bg-beige-100 rounded-lg w-16 h-16 flex items-center justify-center mr-4">
                            <i data-lucide="heart" class="text-beige-700"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-beige-900">Leather Wallet</h3>
                            <p class="text-beige-600">$89.99</p>
                        </div>
                    </div>
                    <div class="border border-beige-200 rounded-lg p-4 flex items-center">
                        <div class="bg-beige-100 rounded-lg w-16 h-16 flex items-center justify-center mr-4">
                            <i data-lucide="heart" class="text-beige-700"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-beige-900">Ceramic Mug</h3>
                            <p class="text-beige-600">$24.99</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart -->
            <div id="cart-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 card content-section" data-aos="fade-up">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-beige-900">Shopping Cart</h2>
                    <a href="#" class="text-beige-600 hover:text-beige-800 flex items-center">
                        View Cart <i data-lucide="chevron-right" class="ml-1 w-4 h-4"></i>
                    </a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center border-b border-beige-100 pb-4">
                        <div class="bg-beige-100 rounded-lg w-16 h-16 flex items-center justify-center mr-4">
                            <i data-lucide="shopping-bag" class="text-beige-700"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-beige-900">Minimal Backpack</h3>
                            <p class="text-beige-600">Quantity: 1</p>
                        </div>
                        <div class="font-medium text-beige-900">$79.99</div>
                    </div>
                    <div class="flex items-center border-b border-beige-100 pb-4">
                        <div class="bg-beige-100 rounded-lg w-16 h-16 flex items-center justify-center mr-4">
                            <i data-lucide="shopping-bag" class="text-beige-700"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-beige-900">Wireless Headphones</h3>
                            <p class="text-beige-600">Quantity: 1</p>
                        </div>
                        <div class="font-medium text-beige-900">$149.99</div>
                    </div>
                    <div class="flex justify-between pt-4">
                        <span class="text-beige-700">Subtotal</span>
                        <span class="font-medium text-beige-900">$229.98</span>
                    </div>
                </div>
            </div>

            <!-- My Orders -->
            <div id="orders-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 card content-section" data-aos="fade-up">
                <h2 class="text-2xl font-bold text-beige-900 mb-6">My Orders</h2>
                <div class="space-y-6">
                    <div class="border border-beige-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <h3 class="font-medium text-beige-900">Order #ORD-7842</h3>
                                <p class="text-beige-600">Placed on May 12, 2023</p>
                            </div>
                            <span class="bg-beige-100 text-beige-800 px-3 py-1 rounded-full text-sm">Processing</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-beige-100 rounded-lg w-12 h-12 flex items-center justify-center mr-3">
                                    <i data-lucide="package" class="text-beige-700"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-beige-900">Minimal Desk Set</p>
                                    <p class="text-beige-600">2 items</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-beige-900">$129.99</p>
                                <a href="#" class="text-beige-600 hover:text-beige-800 text-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="border border-beige-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <h3 class="font-medium text-beige-900">Order #ORD-7839</h3>
                                <p class="text-beige-600">Placed on May 5, 2023</p>
                            </div>
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Delivered</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-beige-100 rounded-lg w-12 h-12 flex items-center justify-center mr-3">
                                    <i data-lucide="package" class="text-beige-700"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-beige-900">Ceramic Planters Set</p>
                                    <p class="text-beige-600">3 items</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-beige-900">$89.99</p>
                                <a href="#" class="text-beige-600 hover:text-beige-800 text-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Tracking -->
            <div id="tracking-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 card content-section" data-aos="fade-up">
                <h2 class="text-2xl font-bold text-beige-900 mb-6">Order Tracking</h2>
                <div class="border border-beige-200 rounded-lg p-6">
                    <div class="flex justify-between mb-8">
                        <div class="text-center">
                            <div class="w-12 h-12 rounded-full bg-beige-100 flex items-center justify-center mx-auto mb-2">
                                <i data-lucide="clipboard" class="text-beige-700"></i>
                            </div>
                            <p class="text-beige-900 font-medium">Order Placed</p>
                            <p class="text-beige-600 text-sm">May 12, 2023</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 rounded-full bg-beige-100 flex items-center justify-center mx-auto mb-2">
                                <i data-lucide="package" class="text-beige-700"></i>
                            </div>
                            <p class="text-beige-900 font-medium">Processing</p>
                            <p class="text-beige-600 text-sm">May 13, 2023</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 rounded-full bg-beige-700 flex items-center justify-center mx-auto mb-2">
                                <i data-lucide="truck" class="text-white"></i>
                            </div>
                            <p class="text-beige-900 font-medium">Shipped</p>
                            <p class="text-beige-600 text-sm">May 15, 2023</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 rounded-full bg-beige-100 flex items-center justify-center mx-auto mb-2">
                                <i data-lucide="home" class="text-beige-700"></i>
                            </div>
                            <p class="text-beige-900 font-medium">Delivered</p>
                            <p class="text-beige-600 text-sm">Est. May 18</p>
                        </div>
                    </div>
                    <div class="mb-6">
                        <div class="flex justify-between mb-2">
                            <span class="text-beige-700">Tracking Number:</span>
                            <span class="font-medium text-beige-900">SH-7842-1928</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-beige-700">Carrier:</span>
                            <span class="font-medium text-beige-900">FedEx Express</span>
                        </div>
                    </div>
                    <div class="bg-beige-50 rounded-lg p-4">
                        <h3 class="font-medium text-beige-900 mb-2">Delivery Information</h3>
                        <p class="text-beige-700">123 Main Street, New York, NY 10001</p>
                        <p class="text-beige-700">Estimated Delivery: May 18, 2023 by 5:00 PM</p>
                    </div>
                </div>
            </div>

            <!-- Receipts -->
            <div id="receipts-section" class="bg-white rounded-xl shadow-sm p-6 card content-section" data-aos="fade-up">
            <h2 class="text-2xl font-bold text-beige-900 mb-6">Receipts</h2>
                <div class="space-y-6">
                    <div class="receipt-item pl-4 border-beige-200">
                        <div class="flex justify-between mb-2">
                            <h3 class="font-medium text-beige-900">Order #ORD-7842</h3>
                            <span class="text-beige-700">May 12, 2023</span>
                        </div>
                        <div class="flex justify-between">
                            <p class="text-beige-600">Minimal Desk Set</p>
                            <span class="font-medium text-beige-900">$129.99</span>
                        </div>
                        <div class="flex justify-end mt-1">
                            <a href="#" class="text-beige-600 hover:text-beige-800 text-sm flex items-center">
                                View Receipt <i data-lucide="chevron-right" class="ml-1 w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                    <div class="receipt-item pl-4 border-beige-200">
                        <div class="flex justify-between mb-2">
                            <h3 class="font-medium text-beige-900">Order #ORD-7839</h3>
                            <span class="text-beige-700">May 5, 2023</span>
                        </div>
                        <div class="flex justify-between">
                            <p class="text-beige-600">Ceramic Planters Set</p>
                            <span class="font-medium text-beige-900">$89.99</span>
                        </div>
                        <div class="flex justify-end mt-1">
                            <a href="#" class="text-beige-600 hover:text-beige-800 text-sm flex items-center">
                                View Receipt <i data-lucide="chevron-right" class="ml-1 w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                    <div class="receipt-item pl-4 border-beige-200">
                        <div class="flex justify-between mb-2">
                            <h3 class="font-medium text-beige-900">Order #ORD-7821</h3>
                            <span class="text-beige-700">April 28, 2023</span>
                        </div>
                        <div class="flex justify-between">
                            <p class="text-beige-600">Minimal Watch</p>
                            <span class="font-medium text-beige-900">$129.99</span>
                        </div>
                        <div class="flex justify-end mt-1">
                            <a href="#" class="text-beige-600 hover:text-beige-800 text-sm flex items-center">
                                View Receipt <i data-lucide="chevron-right" class="ml-1 w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile -->
            <div id="edit-profile-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 card content-section" data-aos="fade-up">
                <h2 class="text-2xl font-bold text-beige-900 mb-6">Edit Profile</h2>
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-beige-700 mb-2">First Name</label>
                            <input type="text" class="w-full px-4 py-2 border border-beige-200 rounded-lg focus:ring-beige-500 focus:border-beige-500" value="Alex">
                        </div>
                        <div>
                            <label class="block text-beige-700 mb-2">Last Name</label>
                            <input type="text" class="w-full px-4 py-2 border border-beige-200 rounded-lg focus:ring-beige-500 focus:border-beige-500" value="Morgan">
                        </div>
                    </div>
                    <div>
                        <label class="block text-beige-700 mb-2">Email</label>
                        <input type="email" class="w-full px-4 py-2 border border-beige-200 rounded-lg focus:ring-beige-500 focus:border-beige-500" value="alex@example.com">
                    </div>
                    <div>
                        <label class="block text-beige-700 mb-2">Phone</label>
                        <input type="tel" class="w-full px-4 py-2 border border-beige-200 rounded-lg focus:ring-beige-500 focus:border-beige-500" value="+1 (555) 123-4567">
                    </div>
                    <div>
                        <label class="block text-beige-700 mb-2">Address</label>
                        <textarea class="w-full px-4 py-2 border border-beige-200 rounded-lg focus:ring-beige-500 focus:border-beige-500" rows="3">123 Main Street, New York, NY 10001</textarea>
                    </div>
                    <button type="submit" class="bg-beige-700 text-white px-6 py-2 rounded-lg hover:bg-beige-800 transition-colors">Save Changes</button>
                </form>
            </div>

            <!-- Security -->
            <div id="security-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 card content-section" data-aos="fade-up">
                <h2 class="text-2xl font-bold text-beige-900 mb-6">Security Settings</h2>
                <div class="space-y-6">
                    <div class="border border-beige-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-beige-900">Password</h3>
                                <p class="text-beige-600">Last changed 3 months ago</p>
                            </div>
                            <button class="text-beige-700 hover:text-beige-900 font-medium">Change Password</button>
                        </div>
                    </div>
                    <div class="border border-beige-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-beige-900">Two-Factor Authentication</h3>
                                <p class="text-beige-600">Add an extra layer of security</p>
                            </div>
                            <button class="text-beige-700 hover:text-beige-900 font-medium">Enable</button>
                        </div>
                    </div>
                    <div class="border border-beige-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-beige-900">Login Activity</h3>
                                <p class="text-beige-600">View recent login attempts</p>
                            </div>
                            <button class="text-beige-700 hover:text-beige-900 font-medium">View Logs</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div id="payment-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 card content-section" data-aos="fade-up">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-beige-900">Payment Methods</h2>
                    <button class="text-beige-700 hover:text-beige-900 font-medium flex items-center">
                        <i data-lucide="plus" class="mr-1 w-4 h-4"></i> Add New
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="border border-beige-200 rounded-lg p-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-beige-100 rounded-lg w-12 h-12 flex items-center justify-center mr-4">
                                <i data-lucide="credit-card" class="text-beige-700"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-beige-900">Visa ending in 4242</h3>
                                <p class="text-beige-600">Expires 05/2025</p>
                            </div>
                        </div>
                        <button class="text-red-600 hover:text-red-800">Remove</button>
                    </div>
                    <div class="border border-beige-200 rounded-lg p-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-beige-100 rounded-lg w-12 h-12 flex items-center justify-center mr-4">
                                <i data-lucide="credit-card" class="text-beige-700"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-beige-900">Mastercard ending in 5555</h3>
                                <p class="text-beige-600">Expires 11/2024</p>
                            </div>
                        </div>
                        <button class="text-red-600 hover:text-red-800">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Logout -->
            <div id="logout-section" class="bg-white rounded-xl shadow-sm p-6 mb-8 card content-section" data-aos="fade-up">
                <h2 class="text-2xl font-bold text-beige-900 mb-6">Logout</h2>
                <div class="text-center py-8">
                    <div class="w-24 h-24 rounded-full bg-beige-100 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="log-out" class="text-beige-700 w-10 h-10"></i>
                    </div>
                    <h3 class="text-xl font-medium text-beige-900 mb-2">Ready to leave?</h3>
                    <p class="text-beige-600 mb-6">You can always log back in at any time.</p>
                    <button class="bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 transition-colors">Logout</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({
        duration: 800,
        once: true
    });
    feather.replace();
    
    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Hide all content sections initially
        const contentSections = document.querySelectorAll('.content-section');
        contentSections.forEach(section => {
            section.style.display = 'none';
        });
        
        // Show first section by default
        const firstSection = document.getElementById('profile-section');
        if (firstSection) {
            firstSection.style.display = 'block';
        }
        
        // Add active class to first link
        const firstLink = document.querySelector('[data-target="profile-section"]');
        if (firstLink) {
            firstLink.classList.add('active');
        }
        
        // Tab click functionality
        const tabLinks = document.querySelectorAll('.sidebar-link');
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all links
                tabLinks.forEach(l => l.classList.remove('active'));
                
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
                    // --- ADD THIS LINE ---
                    AOS.refreshHard(); // Force AOS to recalculate animations for newly visible elements
                }
            });
        });
    });
</script>
@endpush
