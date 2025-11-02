@extends('admin.layouts.app')

@section('title', 'Edit Return/Repair Request')

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
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Edit Return/Repair Request</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Edit RMA #{{ $returnRepair->rma_number }}</p>
                </div>
    </div>
            <a href="{{ admin_route('orders.returns-repairs.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Returns & Repairs
        </a>
    </div>
</div>

    <form action="{{ admin_route('orders.returns-repairs.update', $returnRepair) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Order Selection -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                <i data-lucide="shopping-cart" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Selection</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Select the order for this return/repair request</p>
                    </div>
                    <div class="p-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Select Order <span class="text-red-500">*</span>
                    </label>
                            <select name="order_id" 
                        id="order-select"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white"
                                    required>
                        <option value="">Select an order...</option>
                        @foreach($orders as $order)
                                    <option value="{{ $order->id }}" 
                                            {{ old('order_id', $returnRepair->order_id) == $order->id ? 'selected' : '' }}
                                            data-customer="{{ $order->user->first_name ?? 'Guest' }} {{ $order->user->last_name ?? '' }}" 
                                            data-email="{{ $order->user->email ?? 'No email' }}"
                                            data-order-number="{{ $order->order_number }}"
                                            data-order-date="{{ $order->created_at->format('M d, Y') }}"
                                            data-order-total="₱{{ number_format($order->total_amount, 2) }}">
                                Order #{{ $order->order_number }} - {{ $order->user->first_name ?? 'Guest' }} {{ $order->user->last_name ?? '' }} ({{ $order->created_at->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                                    <option value="return" {{ old('type', $returnRepair->type) === 'return' ? 'selected' : '' }}>Return</option>
                                    <option value="exchange" {{ old('type', $returnRepair->type) === 'exchange' ? 'selected' : '' }}>Exchange</option>
                                    <option value="repair" {{ old('type', $returnRepair->type) === 'repair' ? 'selected' : '' }}>Repair</option>
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
                                    <option value="defective" {{ old('reason', $returnRepair->reason) === 'defective' ? 'selected' : '' }}>Defective Product</option>
                                    <option value="wrong_item" {{ old('reason', $returnRepair->reason) === 'wrong_item' ? 'selected' : '' }}>Wrong Item Received</option>
                                    <option value="damaged_shipping" {{ old('reason', $returnRepair->reason) === 'damaged_shipping' ? 'selected' : '' }}>Damaged in Shipping</option>
                                    <option value="not_as_described" {{ old('reason', $returnRepair->reason) === 'not_as_described' ? 'selected' : '' }}>Not as Described</option>
                                    <option value="changed_mind" {{ old('reason', $returnRepair->reason) === 'changed_mind' ? 'selected' : '' }}>Changed Mind</option>
                                    <option value="size_issue" {{ old('reason', $returnRepair->reason) === 'size_issue' ? 'selected' : '' }}>Size Issue</option>
                                    <option value="quality_issue" {{ old('reason', $returnRepair->reason) === 'quality_issue' ? 'selected' : '' }}>Quality Issue</option>
                                    <option value="other" {{ old('reason', $returnRepair->reason) === 'other' ? 'selected' : '' }}>Other</option>
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
                                      required>{{ old('description', $returnRepair->description) }}</textarea>
                    @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Customer Notes
                    </label>
                            <textarea name="customer_notes" 
                        rows="3"
                        placeholder="Customer notes..."
                                      class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">{{ old('customer_notes', $returnRepair->customer_notes) }}</textarea>
                    @error('customer_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                        <!-- Products - Hidden field to preserve existing products -->
                        @if($returnRepair->products)
                            @foreach($returnRepair->products as $index => $product)
                                <input type="hidden" name="products[{{ $index }}][product_id]" value="{{ $product['product_id'] ?? $product['id'] ?? '' }}">
                                <input type="hidden" name="products[{{ $index }}][quantity]" value="{{ $product['quantity'] ?? 1 }}">
                            @endforeach
                        @endif
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
                                value="{{ old('refund_amount', $returnRepair->refund_amount) }}"
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
                                    <option value="original_payment" {{ old('refund_method', $returnRepair->refund_method) === 'original_payment' ? 'selected' : '' }}>Original Payment Method</option>
                                    <option value="store_credit" {{ old('refund_method', $returnRepair->refund_method) === 'store_credit' ? 'selected' : '' }}>Store Credit</option>
                                    <option value="bank_transfer" {{ old('refund_method', $returnRepair->refund_method) === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="check" {{ old('refund_method', $returnRepair->refund_method) === 'check' ? 'selected' : '' }}>Check</option>
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
                            @if($returnRepair->order && $returnRepair->order->user)
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center shadow-lg">
                                        <span class="text-white font-medium text-sm">
                                            {{ strtoupper(substr($returnRepair->order->user->first_name ?? '', 0, 1) . substr($returnRepair->order->user->last_name ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-stone-900 dark:text-white">{{ $returnRepair->order->user->first_name ?? 'Guest' }} {{ $returnRepair->order->user->last_name ?? '' }}</h4>
                                        <p class="text-sm text-stone-500">{{ $returnRepair->order->user->email ?? 'No email' }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i data-lucide="shopping-cart" class="w-12 h-12 text-stone-400 mx-auto mb-4"></i>
                                    <p class="text-stone-600 dark:text-stone-400">Select an order to view customer details</p>
                                </div>
                            @endif
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
                            @if($returnRepair->order)
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-stone-600 dark:text-stone-400">Order Number:</span>
                                        <span class="text-stone-900 dark:text-white font-medium">#{{ $returnRepair->order->order_number }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-stone-600 dark:text-stone-400">Order Date:</span>
                                        <span class="text-stone-900 dark:text-white">{{ $returnRepair->order->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-stone-600 dark:text-stone-400">Total Amount:</span>
                                        <span class="text-stone-900 dark:text-white font-semibold">₱{{ number_format($returnRepair->order->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i data-lucide="file-text" class="w-12 h-12 text-stone-400 mx-auto mb-4"></i>
                                    <p class="text-stone-600 dark:text-stone-400">Select an order to view details</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- RMA Information -->
                <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                    <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl">
                                <i data-lucide="hash" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">RMA Information</h3>
                        </div>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Request details</p>
                    </div>
                    <div class="p-8">
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-stone-600 dark:text-stone-400">RMA Number:</span>
                                <span class="text-stone-900 dark:text-white font-medium">{{ $returnRepair->rma_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-stone-600 dark:text-stone-400">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($returnRepair->status === 'requested') bg-yellow-100 text-yellow-800
                                    @elseif($returnRepair->status === 'approved') bg-blue-100 text-blue-800
                                    @elseif($returnRepair->status === 'completed') bg-green-100 text-green-800
                                    @elseif($returnRepair->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $returnRepair->status)) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-stone-600 dark:text-stone-400">Created:</span>
                                <span class="text-stone-900 dark:text-white">{{ $returnRepair->created_at->format('M d, Y') }}</span>
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
                                      class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">{{ old('admin_notes', $returnRepair->admin_notes) }}</textarea>
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
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Save or cancel changes</p>
                    </div>
                    <div class="p-8">
                        <div class="space-y-4">
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-blue-600 px-6 py-3 text-center font-medium text-white hover:from-emerald-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                Update RMA Request
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
    const orderSelect = document.getElementById('order-select');
    const customerInfo = document.getElementById('customer-info');
    const orderSummary = document.getElementById('order-summary');

    orderSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const customerName = selectedOption.dataset.customer;
            const customerEmail = selectedOption.dataset.email;
            const orderNumber = selectedOption.dataset.orderNumber;
            const orderDate = selectedOption.dataset.orderDate;
            const orderTotal = selectedOption.dataset.orderTotal;
            
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
        } else {
            // Reset to default state
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
        }
        
        // Recreate icons
        lucide.createIcons();
    });
    
    lucide.createIcons();
});
</script>
@endpush

