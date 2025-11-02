@extends('admin.layouts.app')

@section('title', 'Create Return/Repair Request')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="refresh-cw" class="w-6 h-6 text-white"></i>
                </div>
    <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Create Return/Repair Request</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Create a new RMA (Return Merchandise Authorization) request for a customer</p>
                </div>
    </div>
            <a href="{{ admin_route('orders.returns-repairs.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Returns & Repairs
        </a>
    </div>
</div>

    <form action="{{ admin_route('orders.returns-repairs.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Order Selection -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-visible">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                <i data-lucide="shopping-cart" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Selection</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Select the order for this return/repair request</p>
                    </div>
                    <div class="p-8 overflow-visible">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Select Order <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="order-search" 
                                       name="order_search"
                                       placeholder="Type order number or customer name..."
                                       autocomplete="off"
                                       class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                <input type="hidden" name="order_id" id="selected-order-id" value="{{ old('order_id') }}" required>
                                <div id="order-results" class="hidden absolute z-[9999] w-full mt-1 bg-white dark:bg-boxdark rounded-xl shadow-xl border border-stone-200 dark:border-strokedark max-h-60 overflow-y-auto"></div>
                            </div>
                            @error('order_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="selected-order-display" class="hidden mt-2 p-3 bg-stone-50 dark:bg-stone-800 rounded-lg border border-stone-200 dark:border-strokedark">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-stone-900 dark:text-white" id="selected-order-text"></p>
                                    </div>
                                    <button type="button" onclick="clearOrderSelection()" class="text-stone-500 hover:text-stone-700 dark:text-stone-400 dark:hover:text-stone-200">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Request Details -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl">
                                <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Request Details</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Provide details about the return/repair request</p>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Request Type <span class="text-red-500">*</span>
                    </label>
                                <select name="type" 
                                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white"
                                        required>
                        <option value="">Select request type...</option>
                                    <option value="return" {{ old('type') === 'return' ? 'selected' : '' }}>Return</option>
                                    <option value="exchange" {{ old('type') === 'exchange' ? 'selected' : '' }}>Exchange</option>
                                    <option value="repair" {{ old('type') === 'repair' ? 'selected' : '' }}>Repair</option>
                    </select>
                    @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Reason <span class="text-red-500">*</span>
                    </label>
                                <select name="reason" 
                                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white"
                                        required>
                        <option value="">Select reason...</option>
                                    <option value="defective" {{ old('reason') === 'defective' ? 'selected' : '' }}>Defective Product</option>
                                    <option value="wrong_item" {{ old('reason') === 'wrong_item' ? 'selected' : '' }}>Wrong Item Received</option>
                                    <option value="damaged_shipping" {{ old('reason') === 'damaged_shipping' ? 'selected' : '' }}>Damaged in Shipping</option>
                                    <option value="not_as_described" {{ old('reason') === 'not_as_described' ? 'selected' : '' }}>Not as Described</option>
                                    <option value="changed_mind" {{ old('reason') === 'changed_mind' ? 'selected' : '' }}>Changed Mind</option>
                                    <option value="size_issue" {{ old('reason') === 'size_issue' ? 'selected' : '' }}>Size Issue</option>
                                    <option value="quality_issue" {{ old('reason') === 'quality_issue' ? 'selected' : '' }}>Quality Issue</option>
                                    <option value="other" {{ old('reason') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                            </div>
                </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Description <span class="text-red-500">*</span>
                    </label>
                            <textarea name="description" 
                        rows="4"
                        placeholder="Please provide a detailed description of the issue..."
                                      class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white"
                                      required>{{ old('description') }}</textarea>
                    @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                    </div>
                </div>

                <!-- Refund Information -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-cyan-50 to-teal-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl">
                                <i data-lucide="credit-card" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Refund Information</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Configure refund details for this request</p>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Refund Amount (₱)
                            </label>
                                <input type="number" 
                                name="refund_amount"
                                step="0.01"
                                min="0"
                                placeholder="0.00"
                                value="{{ old('refund_amount') }}"
                                       class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                            @error('refund_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Refund Method
                            </label>
                                <select name="refund_method" 
                                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                <option value="">Select refund method...</option>
                                    <option value="original_payment" {{ old('refund_method') === 'original_payment' ? 'selected' : '' }}>Original Payment Method</option>
                                    <option value="store_credit" {{ old('refund_method') === 'store_credit' ? 'selected' : '' }}>Store Credit</option>
                                    <option value="bank_transfer" {{ old('refund_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="check" {{ old('refund_method') === 'check' ? 'selected' : '' }}>Check</option>
                            </select>
                            @error('refund_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="xl:col-span-1 space-y-8">
                <!-- Customer Information -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl">
                                <i data-lucide="user" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Customer Information</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Customer details from selected order</p>
                    </div>
                    <div class="p-8">
                        <div id="customer-info" class="space-y-4">
                            <div class="text-center py-8">
                                <i data-lucide="shopping-cart" class="w-12 h-12 text-stone-400 mx-auto mb-4"></i>
                                <p class="text-stone-600 dark:text-stone-400">Select an order to view customer details</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-violet-50 to-fuchsia-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-violet-500 to-fuchsia-600 rounded-xl">
                                <i data-lucide="receipt" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Summary</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Order details and information</p>
                    </div>
                    <div class="p-8">
                        <div id="order-summary" class="space-y-4">
                            <div class="text-center py-8">
                                <i data-lucide="file-text" class="w-12 h-12 text-stone-400 mx-auto mb-4"></i>
                                <p class="text-stone-600 dark:text-stone-400">Select an order to view details</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Notes -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-lime-50 to-green-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-lime-500 to-green-600 rounded-xl">
                                <i data-lucide="sticky-note" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Admin Notes</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Internal notes and comments</p>
                    </div>
                    <div class="p-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Notes
                    </label>
                            <textarea name="admin_notes" 
                                      rows="4" 
                        placeholder="Internal notes (not visible to customer)..."
                                      class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Create or cancel the request</p>
                    </div>
                    <div class="p-8">
                        <div class="space-y-4">
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-blue-600 px-6 py-3 text-center font-medium text-white hover:from-emerald-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                Create RMA Request
                            </button>
                            
                            <a href="{{ admin_route('orders.returns-repairs.index') }}" class="w-full inline-flex items-center justify-center rounded-xl border border-stone-200 px-6 py-3 text-center font-medium text-stone-700 hover:bg-stone-50 transition-all duration-200 dark:border-strokedark dark:text-white dark:hover:bg-gray-800">
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
document.addEventListener('DOMContentLoaded', function() {
    const orderSearch = document.getElementById('order-search');
    const orderResults = document.getElementById('order-results');
    const selectedOrderId = document.getElementById('selected-order-id');
    const selectedOrderDisplay = document.getElementById('selected-order-display');
    const selectedOrderText = document.getElementById('selected-order-text');
    const customerInfo = document.getElementById('customer-info');
    const orderSummary = document.getElementById('order-summary');

    let searchTimeout;
    let selectedOrder = null;

    // Order search functionality
    orderSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            orderResults.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(() => {
            searchOrders(query);
        }, 300);
    });

    // Close results when clicking outside
    document.addEventListener('click', function(e) {
        if (!orderSearch.contains(e.target) && !orderResults.contains(e.target)) {
            orderResults.classList.add('hidden');
        }
    });

    function searchOrders(query) {
        const url = `{{ admin_route('orders.returns-repairs.search-orders') }}?q=${encodeURIComponent(query)}`;
        
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
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            displayOrderResults(data);
        })
        .catch(error => {
            console.error('Error searching orders:', error);
            orderResults.innerHTML = '<div class="p-4 text-center text-red-500">Error searching orders</div>';
            orderResults.classList.remove('hidden');
        });
    }

    function displayOrderResults(orders) {
        if (orders.length === 0) {
            orderResults.innerHTML = '<div class="p-4 text-center text-stone-600 dark:text-stone-400">No orders found</div>';
            orderResults.classList.remove('hidden');
            return;
        }

        let html = '';
        orders.forEach(order => {
            const escapedCustomerName = order.customer_name.replace(/'/g, "&#39;").replace(/"/g, "&quot;");
            const escapedCustomerEmail = order.customer_email.replace(/'/g, "&#39;").replace(/"/g, "&quot;");
            html += `
                <div class="p-3 hover:bg-stone-50 dark:hover:bg-stone-800 cursor-pointer border-b border-stone-200 dark:border-strokedark last:border-b-0" 
                     data-order-id="${order.id}"
                     data-order-number="${order.order_number}"
                     data-customer-name="${escapedCustomerName}"
                     data-customer-email="${escapedCustomerEmail}"
                     data-order-date="${order.order_date}"
                     data-order-total="${order.order_total}">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-stone-900 dark:text-white">Order #${order.order_number}</p>
                            <p class="text-xs text-stone-600 dark:text-stone-400">${order.customer_name} • ${order.order_date}</p>
                        </div>
                        <p class="text-sm font-semibold text-stone-900 dark:text-white">₱${order.order_total}</p>
                    </div>
                </div>
            `;
        });

        orderResults.innerHTML = html;
        
        // Add click handlers after rendering
        orderResults.querySelectorAll('[data-order-id]').forEach(item => {
            item.addEventListener('click', function() {
                selectOrder(
                    parseInt(this.dataset.orderId),
                    this.dataset.orderNumber,
                    this.dataset.customerName,
                    this.dataset.customerEmail,
                    this.dataset.orderDate,
                    '₱' + this.dataset.orderTotal
                );
            });
        });

        orderResults.classList.remove('hidden');
    }

    window.selectOrder = function(id, orderNumber, customerName, customerEmail, orderDate, orderTotal) {
        selectedOrderId.value = id;
        selectedOrder = {
            id: id,
            orderNumber: orderNumber,
            customerName: customerName,
            customerEmail: customerEmail,
            orderDate: orderDate,
            orderTotal: orderTotal
        };

        // Update display
        orderSearch.value = `Order #${orderNumber} - ${customerName}`;
        selectedOrderText.textContent = `Order #${orderNumber} - ${customerName} (${orderDate})`;
        selectedOrderDisplay.classList.remove('hidden');
        orderResults.classList.add('hidden');

        // Update customer information
        customerInfo.innerHTML = `
            <div class="flex items-center gap-3 mb-6">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center shadow-lg">
                    <span class="text-white font-medium text-sm">
                        ${customerName.split(' ').map(n => n[0]).join('').toUpperCase()}
                    </span>
                </div>
                <div>
                    <h4 class="font-medium text-stone-900 dark:text-white">${customerName}</h4>
                    <p class="text-sm text-stone-500">${customerEmail}</p>
                </div>
            </div>
        `;
        
        // Update order summary
        orderSummary.innerHTML = `
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-stone-600 dark:text-stone-400">Order Number:</span>
                    <span class="text-stone-900 dark:text-white font-medium">${orderNumber}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-600 dark:text-stone-400">Order Date:</span>
                    <span class="text-stone-900 dark:text-white">${orderDate}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-600 dark:text-stone-400">Total Amount:</span>
                    <span class="text-stone-900 dark:text-white font-semibold">${orderTotal}</span>
                </div>
            </div>
        `;

        lucide.createIcons();
    };

    window.clearOrderSelection = function() {
        selectedOrderId.value = '';
        selectedOrder = null;
        orderSearch.value = '';
        selectedOrderDisplay.classList.add('hidden');

        // Reset customer info and order summary
        customerInfo.innerHTML = `
            <div class="text-center py-8">
                <i data-lucide="shopping-cart" class="w-12 h-12 text-stone-400 mx-auto mb-4"></i>
                <p class="text-stone-600 dark:text-stone-400">Select an order to view customer details</p>
            </div>
        `;
        
        orderSummary.innerHTML = `
            <div class="text-center py-8">
                <i data-lucide="file-text" class="w-12 h-12 text-stone-400 mx-auto mb-4"></i>
                <p class="text-stone-600 dark:text-stone-400">Select an order to view details</p>
            </div>
        `;

        lucide.createIcons();
    };
    
    lucide.createIcons();
});
</script>
@endpush