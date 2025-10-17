@extends('admin.layouts.app')

@section('title', 'Create Order')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            Create Order
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('admin.dashboard') }}" class="font-medium">Dashboard</a></li>
                <li class="font-medium text-primary">/</li>
                <li><a href="{{ route('admin.orders.index') }}" class="font-medium">Orders</a></li>
                <li class="font-medium text-primary">/</li>
                <li class="font-medium text-primary">Create</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.orders.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Selection -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Customer Information</h3>
                    
                    <div class="mb-4">
                        <label class="mb-2.5 block text-black dark:text-white">
                            Select Customer <span class="text-meta-1">*</span>
                        </label>
                        <select
                            name="user_id"
                            id="customer-select"
                            class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            required
                        >
                            <option value="">Search and select customer...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" data-email="{{ $user->email }}" data-phone="{{ $user->phone }}">
                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Customer Details Display -->
                    <div id="customer-details" class="hidden rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</label>
                                <p id="customer-email" class="text-black dark:text-white"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Phone</label>
                                <p id="customer-phone" class="text-black dark:text-white"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-black dark:text-white">Order Items</h3>
                        <button type="button" id="add-item-btn" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-white hover:bg-opacity-90">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                            Add Item
                        </button>
                    </div>

                    <div id="order-items" class="space-y-4">
                        <!-- Items will be added dynamically -->
                    </div>

                    <!-- Order Summary -->
                    <div class="mt-6 rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                <span id="subtotal" class="font-medium text-black dark:text-white">$0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tax:</span>
                                <span id="tax-amount" class="font-medium text-black dark:text-white">$0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Shipping:</span>
                                <span id="shipping-cost" class="font-medium text-black dark:text-white">$0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Discount:</span>
                                <span id="discount-amount" class="font-medium text-black dark:text-white">$0.00</span>
                            </div>
                            <div class="col-span-2 border-t border-gray-300 dark:border-gray-600 pt-2">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Total:</span>
                                    <span id="total-amount" class="text-lg font-semibold text-black dark:text-white">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Details -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Order Details</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Payment Method <span class="text-meta-1">*</span>
                            </label>
                            <select
                                name="payment_method"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                required
                            >
                                <option value="">Select Payment Method</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cash_on_delivery">Cash on Delivery</option>
                                <option value="check">Check</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Tax Rate (%)
                            </label>
                            <input
                                type="number"
                                id="tax-rate"
                                step="0.01"
                                min="0"
                                max="100"
                                value="8.25"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Shipping Cost ($)
                            </label>
                            <input
                                type="number"
                                id="shipping-cost-input"
                                step="0.01"
                                min="0"
                                value="0"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Discount Amount ($)
                            </label>
                            <input
                                type="number"
                                id="discount-amount-input"
                                step="0.01"
                                min="0"
                                value="0"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Order Notes
                            </label>
                            <textarea
                                name="notes"
                                rows="4"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                placeholder="Any special instructions or notes..."
                            >{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Billing Address</h3>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">First Name</label>
                                <input
                                    type="text"
                                    name="billing_address[first_name]"
                                    value="{{ old('billing_address.first_name') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">Last Name</label>
                                <input
                                    type="text"
                                    name="billing_address[last_name]"
                                    value="{{ old('billing_address.last_name') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">Address Line 1</label>
                            <input
                                type="text"
                                name="billing_address[address_line_1]"
                                value="{{ old('billing_address.address_line_1') }}"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">Address Line 2</label>
                            <input
                                type="text"
                                name="billing_address[address_line_2]"
                                value="{{ old('billing_address.address_line_2') }}"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">City</label>
                                <input
                                    type="text"
                                    name="billing_address[city]"
                                    value="{{ old('billing_address.city') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">State</label>
                                <input
                                    type="text"
                                    name="billing_address[state]"
                                    value="{{ old('billing_address.state') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">ZIP Code</label>
                                <input
                                    type="text"
                                    name="billing_address[zip_code]"
                                    value="{{ old('billing_address.zip_code') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">Country</label>
                                <input
                                    type="text"
                                    name="billing_address[country]"
                                    value="{{ old('billing_address.country', 'United States') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-black dark:text-white">Shipping Address</h3>
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                id="same-as-billing"
                                class="h-4 w-4 rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input"
                            >
                            <span class="ml-2 text-sm text-black dark:text-white">Same as billing</span>
                        </label>
                    </div>
                    
                    <div class="space-y-4" id="shipping-address-fields">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">First Name</label>
                                <input
                                    type="text"
                                    name="shipping_address[first_name]"
                                    value="{{ old('shipping_address.first_name') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">Last Name</label>
                                <input
                                    type="text"
                                    name="shipping_address[last_name]"
                                    value="{{ old('shipping_address.last_name') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">Address Line 1</label>
                            <input
                                type="text"
                                name="shipping_address[address_line_1]"
                                value="{{ old('shipping_address.address_line_1') }}"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">Address Line 2</label>
                            <input
                                type="text"
                                name="shipping_address[address_line_2]"
                                value="{{ old('shipping_address.address_line_2') }}"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">City</label>
                                <input
                                    type="text"
                                    name="shipping_address[city]"
                                    value="{{ old('shipping_address.city') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">State</label>
                                <input
                                    type="text"
                                    name="shipping_address[state]"
                                    value="{{ old('shipping_address.state') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">ZIP Code</label>
                                <input
                                    type="text"
                                    name="shipping_address[zip_code]"
                                    value="{{ old('shipping_address.zip_code') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                            <div>
                                <label class="mb-2.5 block text-black dark:text-white">Country</label>
                                <input
                                    type="text"
                                    name="shipping_address[country]"
                                    value="{{ old('shipping_address.country', 'United States') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <div class="space-y-3">
                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-lg bg-primary p-3 font-medium text-white hover:bg-opacity-90"
                        >
                            Create Order
                        </button>
                        
                        <a
                            href="{{ route('admin.orders.index') }}"
                            class="flex w-full justify-center rounded-lg border border-stroke bg-white p-3 font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-boxdark-2"
                        >
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Product Selection Modal -->
<div id="product-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="relative max-w-4xl max-h-full w-full mx-4">
        <div class="rounded-xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-xl font-semibold text-black dark:text-white">Select Product</h3>
                <button onclick="closeProductModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <div class="mb-4">
                <input
                    type="text"
                    id="product-search"
                    placeholder="Search products..."
                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                >
            </div>

            <div id="product-list" class="max-h-96 overflow-y-auto space-y-2">
                <!-- Products will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let itemCounter = 0;
let products = @json(\App\Models\Product::where('is_active', true)->get());

document.addEventListener('DOMContentLoaded', function() {
    // Customer selection
    document.getElementById('customer-select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const customerDetails = document.getElementById('customer-details');
        
        if (this.value) {
            document.getElementById('customer-email').textContent = selectedOption.dataset.email;
            document.getElementById('customer-phone').textContent = selectedOption.dataset.phone || 'N/A';
            customerDetails.classList.remove('hidden');
        } else {
            customerDetails.classList.add('hidden');
        }
    });

    // Same as billing checkbox
    document.getElementById('same-as-billing').addEventListener('change', function() {
        const shippingFields = document.getElementById('shipping-address-fields');
        if (this.checked) {
            shippingFields.style.display = 'none';
        } else {
            shippingFields.style.display = 'block';
        }
    });

    // Add item button
    document.getElementById('add-item-btn').addEventListener('click', openProductModal);

    // Tax and shipping calculations
    document.getElementById('tax-rate').addEventListener('input', calculateTotals);
    document.getElementById('shipping-cost-input').addEventListener('input', calculateTotals);
    document.getElementById('discount-amount-input').addEventListener('input', calculateTotals);

    // Product search
    document.getElementById('product-search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const productList = document.getElementById('product-list');
        
        productList.innerHTML = '';
        
        products.filter(product => 
            product.name.toLowerCase().includes(searchTerm) ||
            product.sku.toLowerCase().includes(searchTerm)
        ).forEach(product => {
            const productItem = document.createElement('div');
            productItem.className = 'flex items-center justify-between p-3 border border-stroke rounded-lg hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800 cursor-pointer';
            productItem.innerHTML = `
                <div>
                    <h4 class="font-medium text-black dark:text-white">${product.name}</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">SKU: ${product.sku} | Stock: ${product.stock_quantity} | Price: $${parseFloat(product.price).toFixed(2)}</p>
                </div>
                <button type="button" onclick="selectProduct(${product.id})" class="rounded-lg bg-primary px-3 py-1 text-white text-sm hover:bg-opacity-90">
                    Select
                </button>
            `;
            productList.appendChild(productItem);
        });
    });

    // Load initial products
    document.getElementById('product-search').dispatchEvent(new Event('input'));
});

function openProductModal() {
    document.getElementById('product-modal').classList.remove('hidden');
    document.getElementById('product-modal').classList.add('flex');
}

function closeProductModal() {
    document.getElementById('product-modal').classList.add('hidden');
    document.getElementById('product-modal').classList.remove('flex');
}

function selectProduct(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    const orderItems = document.getElementById('order-items');
    const itemDiv = document.createElement('div');
    itemDiv.className = 'flex items-center gap-4 p-4 border border-stroke rounded-lg dark:border-strokedark';
    itemDiv.innerHTML = `
        <div class="flex-1">
            <h4 class="font-medium text-black dark:text-white">${product.name}</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">SKU: ${product.sku} | Stock: ${product.stock_quantity}</p>
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600 dark:text-gray-400">Qty:</label>
            <input type="number" name="items[${itemCounter}][quantity]" value="1" min="1" max="${product.stock_quantity}" class="w-20 rounded border border-stroke px-2 py-1 text-center dark:border-strokedark dark:bg-form-input" onchange="calculateTotals()">
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600 dark:text-gray-400">Price:</label>
            <input type="number" name="items[${itemCounter}][price]" value="${product.price}" step="0.01" class="w-24 rounded border border-stroke px-2 py-1 text-center dark:border-strokedark dark:bg-form-input" onchange="calculateTotals()">
        </div>
        <div class="text-right">
            <p class="font-medium text-black dark:text-white">$<span class="item-total">${parseFloat(product.price).toFixed(2)}</span></p>
        </div>
        <button type="button" onclick="removeItem(this)" class="text-red-500 hover:text-red-700">
            <i data-lucide="trash-2" class="h-4 w-4"></i>
        </button>
        <input type="hidden" name="items[${itemCounter}][product_id]" value="${product.id}">
    `;
    
    orderItems.appendChild(itemDiv);
    itemCounter++;
    
    closeProductModal();
    calculateTotals();
}

function removeItem(button) {
    button.closest('div').remove();
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    
    document.querySelectorAll('#order-items > div').forEach(item => {
        const quantity = parseFloat(item.querySelector('input[name*="[quantity]"]').value) || 0;
        const price = parseFloat(item.querySelector('input[name*="[price]"]').value) || 0;
        const itemTotal = quantity * price;
        subtotal += itemTotal;
        
        item.querySelector('.item-total').textContent = itemTotal.toFixed(2);
    });
    
    const taxRate = parseFloat(document.getElementById('tax-rate').value) || 0;
    const shippingCost = parseFloat(document.getElementById('shipping-cost-input').value) || 0;
    const discountAmount = parseFloat(document.getElementById('discount-amount-input').value) || 0;
    
    const taxAmount = (subtotal * taxRate) / 100;
    const total = subtotal + taxAmount + shippingCost - discountAmount;
    
    document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('tax-amount').textContent = '$' + taxAmount.toFixed(2);
    document.getElementById('shipping-cost').textContent = '$' + shippingCost.toFixed(2);
    document.getElementById('discount-amount').textContent = '$' + discountAmount.toFixed(2);
    document.getElementById('total-amount').textContent = '$' + total.toFixed(2);
}
</script>
@endpush
@endsection
