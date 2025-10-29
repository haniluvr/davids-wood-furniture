@extends('admin.layouts.app')

@section('title', 'Edit Order')

@push('styles')
<style>
    .psgc-disabled {
        background-color: #f3f4f6 !important;
        color: #6b7280 !important;
        cursor: not-allowed !important;
    }
    
    .psgc-disabled option {
        color: #6b7280 !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="edit" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Edit Order - {{ $order->order_number }}</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update order information and details</p>
                </div>
            </div>
            <a href="{{ admin_route('orders.show', $order) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Order
            </a>
        </div>
</div>

    <form action="{{ admin_route('orders.update', $order) }}" method="POST" class="space-y-8">
    @csrf
    @method('PUT')
    
        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="xl:col-span-2 space-y-8">
            <!-- Order Status -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                <i data-lucide="activity" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Status</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update order and payment status</p>
                </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Order Status <span class="text-red-500">*</span>
                            </label>
                                <select name="status" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="returned" {{ $order->status === 'returned' ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>
                        
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Payment Status <span class="text-red-500">*</span>
                            </label>
                                <select name="payment_status" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Shipping Method
                            </label>
                                <input type="text" 
                                       name="shipping_method" 
                                       value="{{ $order->shipping_method }}" 
                                       placeholder="e.g., Standard, Express, Free"
                                       class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                        </div>
                        
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Tracking Number
                            </label>
                                <input type="text" 
                                       name="tracking_number" 
                                       value="{{ $order->tracking_number }}" 
                                       placeholder="Enter tracking number"
                                       class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Address -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl">
                                <i data-lucide="credit-card" class="w-5 h-5 text-white"></i>
                </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Billing Address</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Customer billing information</p>
                    </div>
                    <div class="p-8 space-y-6">
                            <div class="grid grid-cols-1 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="billing_address[name]" 
                                           value="{{ $order->billing_address['name'] ?? '' }}"
                                           class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                        Street Address <span class="text-red-500">*</span>
                            </label>
                                    <input type="text" 
                                           name="billing_address[street]" 
                                           value="{{ $order->billing_address['street'] ?? '' }}"
                                           placeholder="Enter street address"
                                           class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                        </div>
                        
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                            Region <span class="text-red-500">*</span>
                            </label>
                                        <select name="billing_address[region]" 
                                                id="billing_region"
                                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                            <option value="">Select Region</option>
                                        </select>
                        </div>
                        
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                            Province <span class="text-red-500">*</span>
                            </label>
                                        <select name="billing_address[province]" 
                                                id="billing_province"
                                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                            <option value="">Select Province</option>
                                        </select>
                        </div>
                        
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                            City <span class="text-red-500">*</span>
                            </label>
                                        <select name="billing_address[city]" 
                                                id="billing_city"
                                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                            <option value="">Select City</option>
                                        </select>
                        </div>
                        
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                            Barangay <span class="text-red-500">*</span>
                            </label>
                                        <select name="billing_address[barangay]" 
                                                id="billing_barangay"
                                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                            <option value="">Select Barangay</option>
                                        </select>
                        </div>
                        
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                            Zip Code <span class="text-red-500">*</span>
                            </label>
                                        <input type="text" 
                                               name="billing_address[zip_code]" 
                                               id="billing_zip_code"
                                               value="{{ $order->billing_address['zip_code'] ?? '' }}"
                                               placeholder="Enter zip code"
                                               class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                        </div>
                        
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                            Country
                            </label>
                                        <input type="text" 
                                               name="billing_address[country]" 
                                               value="Philippines"
                                               readonly
                                               class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-500 dark:border-strokedark dark:bg-stone-800 dark:text-stone-400">
                                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-cyan-50 to-teal-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl">
                                <i data-lucide="truck" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Shipping Address</h3>
                </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Delivery information</p>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="sameAsBilling" 
                                   class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="sameAsBilling" class="ml-2 text-sm font-medium text-stone-700 dark:text-stone-300">
                                Same as billing address
                            </label>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="shipping_address[name]" 
                                       value="{{ $order->shipping_address['name'] ?? '' }}"
                                       class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Street Address <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="shipping_address[street]" 
                                       value="{{ $order->shipping_address['street'] ?? '' }}"
                                       placeholder="Enter street address"
                                       class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                            </div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                        Region <span class="text-red-500">*</span>
                            </label>
                                    <select name="shipping_address[region]" 
                                            id="shipping_region"
                                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                        <option value="">Select Region</option>
                                    </select>
                        </div>
                        
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                        Province <span class="text-red-500">*</span>
                            </label>
                                    <select name="shipping_address[province]" 
                                            id="shipping_province"
                                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                        <option value="">Select Province</option>
                                    </select>
                        </div>
                        
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                        City <span class="text-red-500">*</span>
                            </label>
                                    <select name="shipping_address[city]" 
                                            id="shipping_city"
                                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                        <option value="">Select City</option>
                                    </select>
                        </div>
                        
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                        Barangay <span class="text-red-500">*</span>
                            </label>
                                    <select name="shipping_address[barangay]" 
                                            id="shipping_barangay"
                                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                        <option value="">Select Barangay</option>
                                    </select>
                        </div>
                        
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                        Zip Code <span class="text-red-500">*</span>
                            </label>
                                    <input type="text" 
                                           name="shipping_address[zip_code]" 
                                           id="shipping_zip_code"
                                           value="{{ $order->shipping_address['zip_code'] ?? '' }}"
                                           placeholder="Enter zip code"
                                           class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                        </div>
                        
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                        Country
                            </label>
                                    <input type="text" 
                                           name="shipping_address[country]" 
                                           value="Philippines"
                                           readonly
                                           class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-500 dark:border-strokedark dark:bg-stone-800 dark:text-stone-400">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="xl:col-span-1 space-y-8">
            <!-- Order Summary -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-violet-50 to-fuchsia-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-violet-500 to-fuchsia-600 rounded-xl">
                                <i data-lucide="receipt" class="w-5 h-5 text-white"></i>
                </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Summary</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Financial breakdown and order details</p>
                    </div>
                    <div class="p-8">
                        <div class="space-y-4">
                        <div class="flex justify-between">
                                <span class="text-stone-900 dark:text-white">Order Number:</span>
                                <span class="text-stone-900 dark:text-white font-medium">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                                <span class="text-stone-900 dark:text-white">Subtotal:</span>
                                <span class="text-stone-900 dark:text-white">₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                                <span class="text-stone-900 dark:text-white">Tax:</span>
                                <span class="text-stone-900 dark:text-white">₱{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                            <div class="flex justify-between">
                                <span class="text-stone-900 dark:text-white">Shipping:</span>
                                <span class="text-stone-900 dark:text-white">₱{{ number_format($order->shipping_amount, 2) }}</span>
                            </div>
                            <div class="border-t border-stone-200 pt-4 dark:border-strokedark">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-stone-900 dark:text-white">Total:</span>
                                    <span class="font-semibold text-stone-900 dark:text-white">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl">
                                <i data-lucide="user" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Customer</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Customer information</p>
                </div>
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center shadow-lg">
                                <span class="text-white font-medium text-sm">
                                @if($order->user)
                                    {{ substr($order->user->first_name, 0, 1) }}{{ substr($order->user->last_name, 0, 1) }}
                                @else
                                    GU
                                @endif
                            </span>
                        </div>
                        <div>
                                <h4 class="font-medium text-stone-900 dark:text-white">
                                @if($order->user)
                                    {{ $order->user->first_name }} {{ $order->user->last_name }}
                                @else
                                    Guest User
                                @endif
                            </h4>
                                <p class="text-sm text-stone-500">
                                @if($order->user)
                                    {{ $order->user->email }}
                                @else
                                    No email available
                                @endif
                            </p>
                        </div>
                    </div>
                        
                        @if($order->user)
                            <a href="{{ admin_route('users.show', $order->user) }}" class="w-full inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 px-4 py-3 text-center font-medium text-white hover:from-amber-600 hover:to-yellow-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                                View Customer
                            </a>
                        @else
                            <div class="w-full inline-flex items-center justify-center rounded-xl border border-stone-300 px-4 py-3 text-center font-medium text-stone-500 cursor-not-allowed">
                                <i data-lucide="user-x" class="w-4 h-4 mr-2"></i>
                                Guest Order
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-lime-50 to-green-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-lime-500 to-green-600 rounded-xl">
                                <i data-lucide="sticky-note" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Notes</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Additional notes and comments</p>
                    </div>
                    <div class="p-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Notes
                            </label>
                            <textarea name="notes" 
                                      rows="4" 
                                      placeholder="Add any notes about this order..."
                                      class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">{{ $order->notes }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-slate-50 to-gray-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-slate-500 to-gray-600 rounded-xl">
                                <i data-lucide="settings" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Actions</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Save or cancel changes</p>
                    </div>
                    <div class="p-8">
                        <div class="space-y-4">
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-blue-600 px-6 py-3 text-center font-medium text-white hover:from-emerald-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                            Update Order
                        </button>
                        
                            <a href="{{ admin_route('orders.show', $order) }}" class="w-full inline-flex items-center justify-center rounded-xl border border-stone-200 px-6 py-3 text-center font-medium text-stone-700 hover:bg-stone-50 transition-all duration-200 dark:border-strokedark dark:text-white dark:hover:bg-gray-800">
                            <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>

@endsection

@push('scripts')
<script>
    // PSGC API Integration
    const PSGC_BASE_URL = 'https://psgc.cloud/api';
    
    // Load regions
    async function loadRegions() {
        try {
            const response = await fetch(`${PSGC_BASE_URL}/regions`);
            const regions = await response.json();
            
            const billingRegion = document.getElementById('billing_region');
            const shippingRegion = document.getElementById('shipping_region');
            
            regions.forEach(region => {
                const option = document.createElement('option');
                option.value = region.name;
                option.textContent = region.name;
                billingRegion.appendChild(option.cloneNode(true));
                shippingRegion.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading regions:', error);
        }
    }
    
    // Load provinces for a region
    async function loadProvinces(regionName, provinceSelectId) {
        try {
            const response = await fetch(`${PSGC_BASE_URL}/regions/${encodeURIComponent(regionName)}/provinces`);
            const provinces = await response.json();
            
            const provinceSelect = document.getElementById(provinceSelectId);
            provinceSelect.innerHTML = '<option value="">Select Province</option>';
            
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.name;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading provinces:', error);
        }
    }
    
    // Load cities for a province
    async function loadCities(provinceName, citySelectId) {
        try {
            const response = await fetch(`${PSGC_BASE_URL}/provinces/${encodeURIComponent(provinceName)}/cities`);
            const cities = await response.json();
            
            const citySelect = document.getElementById(citySelectId);
            citySelect.innerHTML = '<option value="">Select City</option>';
            
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.name;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading cities:', error);
        }
    }
    
    // Load cities for NCR (no province)
    async function loadCitiesForNCR(citySelectId) {
        try {
            const response = await fetch(`${PSGC_BASE_URL}/regions/National Capital Region/cities`);
            const cities = await response.json();
            
            const citySelect = document.getElementById(citySelectId);
            citySelect.innerHTML = '<option value="">Select City</option>';
            
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.name;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading NCR cities:', error);
        }
    }
    
    // Load barangays for a city
    async function loadBarangays(cityName, barangaySelectId) {
        try {
            const response = await fetch(`${PSGC_BASE_URL}/cities/${encodeURIComponent(cityName)}/barangays`);
            const barangays = await response.json();
            
            const barangaySelect = document.getElementById(barangaySelectId);
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            
            barangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay.name;
                option.textContent = barangay.name;
                barangaySelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading barangays:', error);
        }
    }
    
    // Initialize PSGC functionality
    function initializePSGC() {
        // Load regions on page load
        loadRegions();
        
        // Billing address event listeners
        document.getElementById('billing_region').addEventListener('change', function() {
            const regionName = this.value;
            const provinceSelect = document.getElementById('billing_province');
            const citySelect = document.getElementById('billing_city');
            const barangaySelect = document.getElementById('billing_barangay');
            
            // Reset dependent selects
            provinceSelect.innerHTML = '<option value="">Select Province</option>';
            citySelect.innerHTML = '<option value="">Select City</option>';
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            
            if (regionName) {
                // Check if NCR
                if (regionName.toLowerCase().includes('ncr') || regionName.toLowerCase().includes('national capital region')) {
                    provinceSelect.disabled = true;
                    provinceSelect.classList.add('psgc-disabled');
                    provinceSelect.innerHTML = '<option value="N/A (NCR)">N/A (NCR)</option>';
                    loadCitiesForNCR('billing_city');
                } else {
                    provinceSelect.disabled = false;
                    provinceSelect.classList.remove('psgc-disabled');
                    loadProvinces(regionName, 'billing_province');
                }
            }
        });
        
        document.getElementById('billing_province').addEventListener('change', function() {
            const provinceName = this.value;
            const citySelect = document.getElementById('billing_city');
            const barangaySelect = document.getElementById('billing_barangay');
            
            // Reset dependent selects
            citySelect.innerHTML = '<option value="">Select City</option>';
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            
            if (provinceName && provinceName !== 'N/A (NCR)') {
                loadCities(provinceName, 'billing_city');
            }
        });
        
        document.getElementById('billing_city').addEventListener('change', function() {
            const cityName = this.value;
            const barangaySelect = document.getElementById('billing_barangay');
            
            // Reset dependent select
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            
            if (cityName) {
                loadBarangays(cityName, 'billing_barangay');
            }
        });
        
        // Shipping address event listeners
        document.getElementById('shipping_region').addEventListener('change', function() {
            const regionName = this.value;
            const provinceSelect = document.getElementById('shipping_province');
            const citySelect = document.getElementById('shipping_city');
            const barangaySelect = document.getElementById('shipping_barangay');
            
            // Reset dependent selects
            provinceSelect.innerHTML = '<option value="">Select Province</option>';
            citySelect.innerHTML = '<option value="">Select City</option>';
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            
            if (regionName) {
                // Check if NCR
                if (regionName.toLowerCase().includes('ncr') || regionName.toLowerCase().includes('national capital region')) {
                    provinceSelect.disabled = true;
                    provinceSelect.classList.add('psgc-disabled');
                    provinceSelect.innerHTML = '<option value="N/A (NCR)">N/A (NCR)</option>';
                    loadCitiesForNCR('shipping_city');
                } else {
                    provinceSelect.disabled = false;
                    provinceSelect.classList.remove('psgc-disabled');
                    loadProvinces(regionName, 'shipping_province');
                }
            }
        });
        
        document.getElementById('shipping_province').addEventListener('change', function() {
            const provinceName = this.value;
            const citySelect = document.getElementById('shipping_city');
            const barangaySelect = document.getElementById('shipping_barangay');
            
            // Reset dependent selects
            citySelect.innerHTML = '<option value="">Select City</option>';
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            
            if (provinceName && provinceName !== 'N/A (NCR)') {
                loadCities(provinceName, 'shipping_city');
            }
        });
        
        document.getElementById('shipping_city').addEventListener('change', function() {
            const cityName = this.value;
            const barangaySelect = document.getElementById('shipping_barangay');
            
            // Reset dependent select
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            
            if (cityName) {
                loadBarangays(cityName, 'shipping_barangay');
            }
        });
    }
    
    // Same as billing address functionality
    document.getElementById('sameAsBilling').addEventListener('change', function() {
        const billingInputs = document.querySelectorAll('input[name^="billing_address"], select[name^="billing_address"]');
        const shippingInputs = document.querySelectorAll('input[name^="shipping_address"], select[name^="shipping_address"]');
        
        if (this.checked) {
            billingInputs.forEach((input, index) => {
                if (shippingInputs[index]) {
                    if (input.tagName === 'SELECT') {
                        // Copy select value and trigger change event
                        shippingInputs[index].value = input.value;
                        shippingInputs[index].dispatchEvent(new Event('change'));
                    } else {
                    shippingInputs[index].value = input.value;
                    }
                }
            });
        }
    });
    
    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializePSGC();
    lucide.createIcons();
    });
</script>
@endpush