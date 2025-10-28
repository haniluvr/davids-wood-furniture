@extends('admin.layouts.app')

@section('title', 'Create Order')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Create New Order</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Create a new order for a customer</p>
                </div>
            </div>
            <a href="{{ admin_route('orders.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Orders
            </a>
        </div>
    </div>

    <form action="{{ admin_route('orders.store') }}" method="POST" class="space-y-8">
        @csrf

                <!-- Customer Selection -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                        <i data-lucide="user" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Customer Selection</h3>
                            </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Select an existing customer or create a new one</p>
                            </div>
            <div class="p-8 space-y-6">
                <!-- Customer Search -->
                <div class="space-y-2">
                    <label for="customer-search" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Search Customer <span class="text-red-500">*</span>
                        </label>
                            <div class="relative">
                                <input type="text" 
                                       id="customer-search" 
                                       placeholder="Search by first name, last name, email, or phone..."
                                       class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 pl-10 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('user_id') border-red-300 @enderror">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                    <input type="hidden" name="user_id" id="selected-customer-id" required>
                        @error('user_id')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                <!-- Search Results -->
                <div id="customer-results" class="hidden border border-stone-200 rounded-xl max-h-60 overflow-y-auto dark:border-strokedark">
                    <!-- Results will be populated here -->
                    </div>

                <!-- Selected Customer Display -->
                <div id="selected-customer" class="hidden rounded-xl bg-stone-50 p-4 dark:bg-stone-800">
                    <div class="flex items-center justify-between">
                            <div>
                            <h4 class="font-medium text-stone-900 dark:text-white" id="customer-name"></h4>
                            <p class="text-sm text-stone-500 dark:text-stone-400" id="customer-email"></p>
                            <p class="text-sm text-stone-500 dark:text-stone-400" id="customer-phone"></p>
                        </div>
                        <button type="button" id="clear-customer" class="text-stone-400 hover:text-stone-600 dark:text-stone-500 dark:hover:text-stone-300">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                </div>
            </div>

                <!-- New Customer Form -->
                <div id="new-customer-form" class="hidden space-y-4 p-4 border border-stone-200 rounded-xl dark:border-strokedark">
                    <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span class="font-medium">Create New Customer</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="new-first-name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="new-first-name" 
                                   class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label for="new-last-name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="new-last-name" 
                                   class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                            </div>
                        <div class="space-y-2 md:col-span-2">
                            <label for="new-email" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="new-email" 
                                   class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                            </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" id="create-customer-btn" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors duration-200">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            Create Customer
                        </button>
                        <button type="button" id="cancel-new-customer" class="inline-flex items-center gap-2 px-4 py-2 border border-stone-300 text-stone-700 rounded-lg hover:bg-stone-50 transition-colors duration-200 dark:border-strokedark dark:text-white dark:hover:bg-gray-800">
                            Cancel
                        </button>
                    </div>
                        </div>

                <!-- New Customer Button -->
                <div class="text-center">
                    <button type="button" id="show-new-customer" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm transition-colors duration-200">
                        <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i>
                        Can't find the customer? Create a new one
                    </button>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-green-50 to-blue-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                            <i data-lucide="package" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Items</h3>
                    </div>
                    <button type="button" id="add-item-btn" class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-white hover:bg-opacity-90 transition-all duration-200">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                            Add Item
                        </button>
                    </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Add products to this order</p>
            </div>
            <div class="p-8">
                    <div id="order-items" class="space-y-4">
                        <!-- Items will be added dynamically -->
                    </div>

                    <!-- Order Summary -->
                <div class="mt-6 rounded-xl bg-stone-50 p-6 dark:bg-stone-800">
                    <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-4">Order Summary</h4>
                    <div class="space-y-3">
                            <div class="flex justify-between">
                            <span class="text-stone-600 dark:text-stone-400">Subtotal:</span>
                            <span id="subtotal" class="font-medium text-stone-900 dark:text-white">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                            <span class="text-stone-600 dark:text-stone-400">Tax:</span>
                            <span id="tax-amount" class="font-medium text-stone-900 dark:text-white">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                            <span class="text-stone-600 dark:text-stone-400">Shipping:</span>
                            <span id="shipping-cost" class="font-medium text-stone-900 dark:text-white">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                            <span class="text-stone-600 dark:text-stone-400">Discount:</span>
                            <span id="discount-amount" class="font-medium text-stone-900 dark:text-white">-₱0.00</span>
                            </div>
                        <div class="border-t border-stone-200 dark:border-stone-700 pt-3">
                                <div class="flex justify-between">
                                <span class="text-lg font-semibold text-stone-900 dark:text-white">Total:</span>
                                <span id="total-amount" class="text-lg font-bold text-stone-900 dark:text-white">₱0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Shipping Address -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl">
                        <i data-lucide="map-pin" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Shipping Address</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Enter the delivery address for this order</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="shipping_street" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Street Address <span class="text-red-500">*</span>
                            </label>
                        <input type="text" 
                               name="shipping_street" 
                               id="shipping_street"
                               value="{{ old('shipping_street') }}"
                               class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('shipping_street') border-red-300 @enderror" 
                               placeholder="Enter street address"
                               required>
                        @error('shipping_street')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    <div class="space-y-2">
                        <label for="shipping_barangay" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Barangay <span class="text-red-500">*</span>
                            </label>
                        <select name="shipping_barangay" 
                                id="shipping_barangay"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('shipping_barangay') border-red-300 @enderror" 
                                required>
                            <option value="">Select Barangay</option>
                        </select>
                        @error('shipping_barangay')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        </div>
                        </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="shipping_city" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            City <span class="text-red-500">*</span>
                            </label>
                        <select name="shipping_city" 
                                id="shipping_city"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('shipping_city') border-red-300 @enderror" 
                                required>
                            <option value="">Select City</option>
                        </select>
                        @error('shipping_city')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        </div>

                    <div class="space-y-2">
                        <label for="shipping_zip_code" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            ZIP Code <span class="text-red-500">*</span>
                            </label>
                        <input type="text" 
                               name="shipping_zip_code" 
                               id="shipping_zip_code"
                               value="{{ old('shipping_zip_code') }}"
                               class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('shipping_zip_code') border-red-300 @enderror" 
                               placeholder="Enter ZIP code"
                               required>
                        @error('shipping_zip_code')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="shipping_province" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Province <span class="text-red-500">*</span>
                        </label>
                        <select name="shipping_province" 
                                id="shipping_province"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('shipping_province') border-red-300 @enderror" 
                                required>
                            <option value="">Select Province</option>
                        </select>
                        @error('shipping_province')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        </div>

                    <div class="space-y-2">
                        <label for="shipping_region" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Region <span class="text-red-500">*</span>
                        </label>
                        <select name="shipping_region" 
                                id="shipping_region"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('shipping_region') border-red-300 @enderror" 
                                required>
                            <option value="">Select Region</option>
                        </select>
                        @error('shipping_region')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Order Details -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                        <i data-lucide="settings" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Details</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Configure order settings and payment information</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Order Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('status') border-red-300 @enderror" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label for="payment_status" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Payment Status <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_status" id="payment_status" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('payment_status') border-red-300 @enderror" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                        @error('payment_status')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                            </div>
                        </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="shipping_method" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Shipping Method</label>
                        <select name="shipping_method" id="shipping_method" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                            <option value="">Select shipping method</option>
                            <option value="standard">Standard Shipping</option>
                            <option value="express">Express Shipping</option>
                            <option value="overnight">Overnight Shipping</option>
                        </select>
                        </div>

                    <div class="space-y-2">
                        <label for="payment_method" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                            <option value="">Select payment method</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash_on_delivery">Cash on Delivery</option>
                        </select>
                            </div>
                        </div>

                <div class="space-y-2">
                    <label for="notes" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Order Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400" placeholder="Add any special notes for this order..."></textarea>
                        </div>
                    </div>
                </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ admin_route('orders.index') }}" class="flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-6 py-3 text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit" class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 text-white font-medium shadow-lg shadow-blue-600/25 transition-all duration-200 hover:shadow-xl hover:shadow-blue-600/30 hover:scale-105">
                <i data-lucide="check" class="w-4 h-4"></i>
                            Create Order
                        </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSearch = document.getElementById('customer-search');
    const customerResults = document.getElementById('customer-results');
    const selectedCustomer = document.getElementById('selected-customer');
    const selectedCustomerId = document.getElementById('selected-customer-id');
    const newCustomerForm = document.getElementById('new-customer-form');
    const showNewCustomer = document.getElementById('show-new-customer');
    const createCustomerBtn = document.getElementById('create-customer-btn');
    const cancelNewCustomer = document.getElementById('cancel-new-customer');
    const addItemBtn = document.getElementById('add-item-btn');
    const orderItems = document.getElementById('order-items');
    const subtotalEl = document.getElementById('subtotal');
    const taxAmountEl = document.getElementById('tax-amount');
    const shippingCostEl = document.getElementById('shipping-cost');
    const discountAmountEl = document.getElementById('discount-amount');
    const totalAmountEl = document.getElementById('total-amount');

    let itemCount = 0;
    let searchTimeout;

    // Customer search functionality
    customerSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            customerResults.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(() => {
            console.log('Searching customers with query:', query);
            searchCustomers(query);
        }, 300);
    });

    // Close customer results when clicking outside
    document.addEventListener('click', function(e) {
        if (!customerSearch.contains(e.target) && !customerResults.contains(e.target)) {
            customerResults.classList.add('hidden');
        }
    });

    function searchCustomers(query) {
        const url = `{{ admin_route('customers.search') }}?q=${encodeURIComponent(query)}`;
        console.log('Fetching URL:', url);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Customer search results:', data);
                displayCustomerResults(data);
            })
            .catch(error => {
                console.error('Error searching customers:', error);
                customerResults.innerHTML = '<div class="p-4 text-center text-red-500">Error searching customers: ' + error.message + '</div>';
                customerResults.classList.remove('hidden');
            });
    }

    function displayCustomerResults(customers) {
        if (customers.length === 0) {
            customerResults.innerHTML = '<div class="p-4 text-center text-stone-500">No customers found</div>';
        } else {
            customerResults.innerHTML = customers.map(customer => `
                <div class="p-3 hover:bg-stone-50 cursor-pointer border-b border-stone-100 last:border-b-0 dark:hover:bg-stone-700 dark:border-strokedark" 
                     onclick="selectCustomer(${customer.id}, '${customer.first_name}', '${customer.last_name}', '${customer.email}', '${customer.phone || ''}')">
                    <div class="font-medium text-stone-900 dark:text-white">${customer.first_name} ${customer.last_name}</div>
                    <div class="text-sm text-stone-500 dark:text-stone-400">${customer.email}</div>
                    ${customer.phone ? `<div class="text-sm text-stone-500 dark:text-stone-400">${customer.phone}</div>` : ''}
                </div>
            `).join('');
        }
        customerResults.classList.remove('hidden');
    }

    window.selectCustomer = function(id, firstName, lastName, email, phone) {
        selectedCustomerId.value = id;
        document.getElementById('customer-name').textContent = `${firstName} ${lastName}`;
        document.getElementById('customer-email').textContent = email;
        document.getElementById('customer-phone').textContent = phone || 'No phone number';
        
        customerSearch.value = '';
        customerResults.classList.add('hidden');
        selectedCustomer.classList.remove('hidden');
        newCustomerForm.classList.add('hidden');
    };

    // Clear customer selection
    document.getElementById('clear-customer').addEventListener('click', function() {
        selectedCustomerId.value = '';
        selectedCustomer.classList.add('hidden');
        customerSearch.value = '';
    });

    // Show new customer form
    showNewCustomer.addEventListener('click', function() {
        newCustomerForm.classList.remove('hidden');
        this.style.display = 'none';
    });

    // Cancel new customer form
    cancelNewCustomer.addEventListener('click', function() {
        newCustomerForm.classList.add('hidden');
        showNewCustomer.style.display = 'block';
        document.getElementById('new-first-name').value = '';
        document.getElementById('new-last-name').value = '';
        document.getElementById('new-email').value = '';
    });

    // Create new customer
    createCustomerBtn.addEventListener('click', function() {
        const firstName = document.getElementById('new-first-name').value.trim();
        const lastName = document.getElementById('new-last-name').value.trim();
        const email = document.getElementById('new-email').value.trim();

        if (!firstName || !lastName || !email) {
            alert('Please fill in all required fields');
            return;
        }

        // Generate username: first letter of first name + last name
        const username = (firstName.charAt(0) + lastName).toLowerCase();

                fetch('{{ admin_route('customers.quick-create') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                first_name: firstName,
                last_name: lastName,
                email: email,
                username: username
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectCustomer(data.user.id, data.user.first_name, data.user.last_name, data.user.email, '');
                newCustomerForm.classList.add('hidden');
                showNewCustomer.style.display = 'block';
                document.getElementById('new-first-name').value = '';
                document.getElementById('new-last-name').value = '';
                document.getElementById('new-email').value = '';
            } else {
                alert(data.message || 'Error creating customer');
            }
        })
        .catch(error => {
            console.error('Error creating customer:', error);
            alert('Error creating customer');
        });
    });

    // Add item functionality
    addItemBtn.addEventListener('click', function() {
        addOrderItem();
    });

    function addOrderItem() {
        itemCount++;
        const itemHtml = `
            <div class="order-item border border-stone-200 rounded-xl p-4 dark:border-strokedark" data-item="${itemCount}">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6 space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">Product</label>
                        <div class="relative">
                            <input type="text" 
                                   id="product-search-${itemCount}" 
                                   placeholder="Search products..."
                                   class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 pl-10 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
        </div>
                        <div id="product-results-${itemCount}" class="hidden border border-stone-200 rounded-xl max-h-40 overflow-y-auto dark:border-strokedark"></div>
                        <input type="hidden" name="items[${itemCount}][product_id]" class="product-id-input" required>
        </div>
                    <div class="col-span-2 space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">Quantity</label>
                        <input type="number" name="items[${itemCount}][quantity]" min="1" value="1" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white" required>
        </div>
                    <div class="col-span-2 space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">Price</label>
                        <input type="number" name="items[${itemCount}][price]" step="0.01" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white" required>
        </div>
                    <div class="col-span-2 space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">Actions</label>
                        <button type="button" onclick="removeOrderItem(${itemCount})" class="w-full rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-600 hover:bg-red-100 transition-colors duration-200 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
                            <i data-lucide="trash-2" class="w-4 h-4 mx-auto"></i>
        </button>
                    </div>
                </div>
            </div>
        `;
        orderItems.insertAdjacentHTML('beforeend', itemHtml);
        updateOrderSummary();
        setupProductSearch(itemCount);
    }

    function setupProductSearch(itemId) {
        const searchInput = document.getElementById(`product-search-${itemId}`);
        const resultsDiv = document.getElementById(`product-results-${itemId}`);
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                resultsDiv.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                searchProducts(query, itemId);
            }, 300);
        });
    }

    function searchProducts(query, itemId) {
        const url = `{{ admin_route('products.search') }}?q=${encodeURIComponent(query)}`;
        console.log('Fetching products URL:', url);
        console.log('Generated route:', '{{ admin_route('products.search') }}');
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                console.log('Product response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Product search results:', data);
                displayProductResults(data, itemId);
            })
            .catch(error => {
                console.error('Error searching products:', error);
                const resultsDiv = document.getElementById(`product-results-${itemId}`);
                resultsDiv.innerHTML = '<div class="p-4 text-center text-red-500">Error searching products: ' + error.message + '</div>';
                resultsDiv.classList.remove('hidden');
            });
    }

    function displayProductResults(products, itemId) {
        const resultsDiv = document.getElementById(`product-results-${itemId}`);
        
        if (products.length === 0) {
            resultsDiv.innerHTML = '<div class="p-4 text-center text-stone-500">No products found</div>';
        } else {
            resultsDiv.innerHTML = products.map(product => `
                <div class="p-3 hover:bg-stone-50 cursor-pointer border-b border-stone-100 last:border-b-0 dark:hover:bg-stone-700 dark:border-strokedark" 
                     onclick="selectProduct(${itemId}, ${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.price})">
                    <div class="font-medium text-stone-900 dark:text-white">${product.name}</div>
                    <div class="text-sm text-stone-500 dark:text-stone-400">SKU: ${product.sku || 'N/A'}</div>
                    <div class="text-sm text-stone-500 dark:text-stone-400">₱${parseFloat(product.price).toFixed(2)}</div>
                    ${product.meta_description ? `<div class="text-xs text-stone-400 dark:text-stone-500 mt-1">${product.meta_description.substring(0, 100)}${product.meta_description.length > 100 ? '...' : ''}</div>` : ''}
                </div>
            `).join('');
        }
        resultsDiv.classList.remove('hidden');
    }

    window.selectProduct = function(itemId, productId, productName, price) {
        const searchInput = document.getElementById(`product-search-${itemId}`);
        const resultsDiv = document.getElementById(`product-results-${itemId}`);
        const productIdInput = document.querySelector(`[data-item="${itemId}"] .product-id-input`);
        const priceInput = document.querySelector(`[data-item="${itemId}"] input[name*="[price]"]`);

        searchInput.value = productName;
        productIdInput.value = productId;
        priceInput.value = price;
        
        resultsDiv.classList.add('hidden');
        updateOrderSummary();
    };

    // Remove item function
    window.removeOrderItem = function(itemId) {
        const item = document.querySelector(`[data-item="${itemId}"]`);
        if (item) {
            item.remove();
            updateOrderSummary();
        }
    };

    // Update order summary
    function updateOrderSummary() {
    let subtotal = 0;
        const items = document.querySelectorAll('.order-item');
    
        items.forEach(item => {
        const quantity = parseFloat(item.querySelector('input[name*="[quantity]"]').value) || 0;
        const price = parseFloat(item.querySelector('input[name*="[price]"]').value) || 0;
            subtotal += quantity * price;
        });

        const tax = subtotal * 0.12; // 12% tax
        const shipping = 100; // Fixed shipping cost
        const discount = 0; // No discount for now
        const total = subtotal + tax + shipping - discount;

        subtotalEl.textContent = `₱${subtotal.toFixed(2)}`;
        taxAmountEl.textContent = `₱${tax.toFixed(2)}`;
        shippingCostEl.textContent = `₱${shipping.toFixed(2)}`;
        discountAmountEl.textContent = `-₱${discount.toFixed(2)}`;
        totalAmountEl.textContent = `₱${total.toFixed(2)}`;
    }

    // Add event listeners for dynamic updates
    orderItems.addEventListener('input', function(e) {
        if (e.target.matches('input[name*="[quantity]"], input[name*="[price]"]')) {
            updateOrderSummary();
        }
    });

    // PSGC Cloud API for Shipping Address
    const PSGC_API_BASE = 'https://psgc.cloud/api';
    const shippingRegion = document.getElementById('shipping_region');
    const shippingProvince = document.getElementById('shipping_province');
    const shippingCity = document.getElementById('shipping_city');
    const shippingBarangay = document.getElementById('shipping_barangay');

    // Add CSS for disabled province field
    const style = document.createElement('style');
    style.textContent = `
        #shipping_province:disabled {
            background-color: #f9fafb !important;
            color: #6b7280 !important;
            cursor: not-allowed;
        }
    `;
    document.head.appendChild(style);

    // Load regions
    loadRegions();

    async function loadRegions() {
        try {
            const response = await fetch(`${PSGC_API_BASE}/regions`);
            const regions = await response.json();
            
            shippingRegion.innerHTML = '<option value="">Select Region</option>';
            regions.forEach(region => {
                const option = document.createElement('option');
                option.value = region.name;
                option.textContent = region.name;
                shippingRegion.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading regions:', error);
        }
    }

    // Region change handler
    shippingRegion.addEventListener('change', async function() {
        const regionName = this.value;
        
        // Clear existing options
        shippingProvince.innerHTML = '<option value="">Select Province</option>';
        shippingCity.innerHTML = '<option value="">Select City</option>';
        shippingBarangay.innerHTML = '<option value="">Select Barangay</option>';
        
        if (!regionName) {
            shippingProvince.disabled = false;
            return;
        }
        
        // Special handling for NCR - it doesn't have provinces
        if (regionName.toLowerCase().includes('ncr') || regionName.toLowerCase().includes('national capital region')) {
            // For NCR, load cities directly
            try {
                const response = await fetch(`${PSGC_API_BASE}/regions/${encodeURIComponent(regionName)}/cities`);
                const cities = await response.json();
                
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.name;
                    option.textContent = city.name;
                    shippingCity.appendChild(option);
                });
                
                // Disable province field for NCR
                shippingProvince.disabled = true;
                shippingProvince.innerHTML = '<option value="">N/A (NCR)</option>';
                
                // Clear barangay field
                shippingBarangay.innerHTML = '<option value="">Select Barangay</option>';
            } catch (error) {
                console.error('Error loading NCR cities:', error);
            }
        } else {
            // For other regions, load provinces normally
            try {
                const response = await fetch(`${PSGC_API_BASE}/regions/${encodeURIComponent(regionName)}/provinces`);
                const provinces = await response.json();
                
                provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name;
                    option.textContent = province.name;
                    shippingProvince.appendChild(option);
                });
                
                // Enable province field for non-NCR regions
                shippingProvince.disabled = false;
            } catch (error) {
                console.error('Error loading provinces:', error);
            }
        }
    });

    // Province change handler
    shippingProvince.addEventListener('change', async function() {
        // Skip if province field is disabled (for NCR)
        if (this.disabled) return;
        
        const provinceName = this.value;
        
        // Clear existing options
        shippingCity.innerHTML = '<option value="">Select City</option>';
        shippingBarangay.innerHTML = '<option value="">Select Barangay</option>';
        
        if (!provinceName) return;
        
        try {
            const response = await fetch(`${PSGC_API_BASE}/provinces/${encodeURIComponent(provinceName)}/cities`);
            const cities = await response.json();
            
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.name;
                option.textContent = city.name;
                shippingCity.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading cities:', error);
        }
    });

    // City change handler
    shippingCity.addEventListener('change', async function() {
        const cityName = this.value;
        
        // Clear existing options
        shippingBarangay.innerHTML = '<option value="">Select Barangay</option>';
        
        if (!cityName) return;
        
        try {
            const response = await fetch(`${PSGC_API_BASE}/cities/${encodeURIComponent(cityName)}/barangays`);
            const barangays = await response.json();
            
            barangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay.name;
                option.textContent = barangay.name;
                shippingBarangay.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading barangays:', error);
        }
    });
});
</script>
@endpush
@endsection

