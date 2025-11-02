@extends('admin.layouts.app')

@section('title', 'Pending Approval')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex justify-between items-center py-6">
    <div>
                <h1 class="text-2xl font-bold text-stone-900">Pending Approval</h1>
                <p class="mt-1 text-sm text-stone-600">Review orders that require manual approval before processing</p>
    </div>
            <div class="flex gap-3">
                <button id="bulk-approve-btn" class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" disabled>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
            Bulk Approve
        </button>
    </div>
</div>
    </div>

    <!-- Statistics Cards -->
    <div class="pt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <!-- Pending Approval -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Pending Approval</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['pending_approval'] ?? 0) }}</p>
                </div>
            </div>
    </div>

    <!-- Approved Today -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                </div>
            </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Approved Today</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['approved_today'] ?? 0) }}</p>
                </div>
            </div>
        </div>

            <!-- Rejected Today -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
    </div>
</div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Rejected Today</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['rejected_today'] ?? 0) }}</p>
        </div>
    </div>
            </div>
            </div>
        </div>

    <!-- Filters -->
    <div class="pb-6">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
            <form method="GET" action="{{ admin_route('orders.pending-approval') }}" class="flex flex-wrap items-end gap-4 justify-between">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-stone-700 mb-2">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Search orders..."
                           class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="priority" class="block text-sm font-medium text-stone-700 mb-2">Priority</label>
                    <select id="priority" name="priority" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Priorities</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 whitespace-nowrap">
                        Filter
                    </button>
                    <a href="{{ admin_route('orders.pending-approval') }}" class="inline-flex items-center justify-center rounded-lg border border-stone-300 px-4 py-2.5 text-sm hover:bg-stone-50">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders List -->
    <div class="pb-8">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAll" class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stone-200">
                            @foreach($orders as $order)
                                <tr class="hover:bg-stone-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="selected_orders[]" value="{{ $order->id }}" class="order-checkbox rounded border-stone-300 text-emerald-600 focus:ring-emerald-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-stone-900">#{{ $order->order_number }}</div>
                                        <div class="text-sm text-stone-500">{{ $order->items_count }} items</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-stone-900">{{ $order->user->first_name }} {{ $order->user->last_name }}</div>
                                        <div class="text-sm text-stone-500">{{ $order->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        ₱{{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $priority = $order->total_amount >= 10000 ? 'high' : ($order->total_amount >= 5000 ? 'medium' : 'low');
                                            $priorityColors = [
                                                'high' => 'bg-red-100 text-red-800',
                                                'medium' => 'bg-yellow-100 text-yellow-800',
                                                'low' => 'bg-green-100 text-green-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$priority] }} capitalize">
                                            {{ $priority }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        {{ $order->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="approveOrder({{ $order->id }}, '{{ $order->order_number }}')" class="text-green-600 hover:text-green-900 transition-colors duration-150" title="Approve">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                </button>
                                            <button onclick="rejectOrder({{ $order->id }}, '{{ $order->order_number }}')" class="text-red-600 hover:text-red-900 transition-colors duration-150" title="Reject">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                </button>
                                            <a href="{{ admin_route('orders.show', $order) }}" class="text-emerald-600 hover:text-emerald-900 transition-colors duration-150" title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
            </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        @include('admin.partials.pagination', ['paginator' => $orders])
    @endif
            @else
                <div class="p-8 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-stone-500">No orders pending approval</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Approve Modal -->
<div id="bulk-approve-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeBulkApproveModal()"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900">Bulk Approve Orders</h3>
                <p class="text-sm text-stone-600">Approve selected orders and move them to processing</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-stone-700" id="selected-orders-count"></p>
                <label for="bulk-approve-notes" class="block text-sm font-medium text-stone-700 mt-4 mb-2">Admin Notes (Optional)</label>
                <textarea id="bulk-approve-notes" rows="3" placeholder="Add any notes about this approval..." class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
            </div>
            <div class="flex gap-3">
                <button id="confirm-bulk-approve-btn" onclick="confirmBulkApprove(this)" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve Selected
                </button>
                <button onclick="closeBulkApproveModal()" class="flex-1 rounded-lg border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Single Order Approve Modal -->
<div id="approve-order-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeApproveOrderModal()"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900">Approve Order</h3>
                <p class="text-sm text-stone-600">Are you sure you want to approve this order? It will be moved to processing.</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-stone-700 mb-4" id="approve-order-number"></p>
                <label for="approve-order-notes" class="block text-sm font-medium text-stone-700 mb-2">Admin Notes (Optional)</label>
                <textarea id="approve-order-notes" rows="3" placeholder="Add any notes about this approval..." class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
            </div>
            <div class="flex gap-3">
                <button id="confirm-approve-order-btn" onclick="confirmApproveOrder(this)" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve Order
                </button>
                <button onclick="closeApproveOrderModal()" class="flex-1 rounded-lg border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Single Order Reject Modal -->
<div id="reject-order-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeRejectOrderModal()"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900">Reject Order</h3>
                <p class="text-sm text-stone-600">This order will be cancelled and stock will be restored.</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-stone-700 mb-4" id="reject-order-number"></p>
                <label for="reject-order-notes" class="block text-sm font-medium text-stone-700 mb-2">
                    Rejection Reason <span class="text-red-600">*</span>
                </label>
                <textarea id="reject-order-notes" rows="4" placeholder="Please provide a reason for rejection..." required class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"></textarea>
                <p class="text-xs text-stone-500 mt-1">This field is required</p>
            </div>
            <div class="flex gap-3">
                <button id="confirm-reject-order-btn" onclick="confirmRejectOrder(this)" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reject Order
                </button>
                <button onclick="closeRejectOrderModal()" class="flex-1 rounded-lg border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkApproveBtn = document.getElementById('bulk-approve-btn');

    // Select all functionality
    if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkApproveButton();
    });
    }

    // Individual checkbox change
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkApproveButton();
            updateSelectAllState();
        });
    });

    // Bulk approve button
    bulkApproveBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        if (checkedBoxes.length > 0) {
            document.getElementById('selected-orders-count').textContent = `You are about to approve ${checkedBoxes.length} order(s).`;
            document.getElementById('bulk-approve-modal').classList.remove('hidden');
            document.body.classList.add('modal-open');
        }
    });

    function updateBulkApproveButton() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        bulkApproveBtn.disabled = checkedBoxes.length === 0;
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        const totalBoxes = orderCheckboxes.length;
        if (selectAllCheckbox) {
        selectAllCheckbox.checked = checkedBoxes.length === totalBoxes;
        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
    }
    }
});

function approveOrder(orderId, orderNumber) {
    document.getElementById('approve-order-number').textContent = `Order: #${orderNumber}`;
    document.getElementById('approve-order-modal').dataset.orderId = orderId;
    document.getElementById('approve-order-notes').value = '';
    document.getElementById('approve-order-modal').classList.remove('hidden');
    document.body.classList.add('modal-open');
}

function rejectOrder(orderId, orderNumber) {
    document.getElementById('reject-order-number').textContent = `Order: #${orderNumber}`;
    document.getElementById('reject-order-modal').dataset.orderId = orderId;
    document.getElementById('reject-order-notes').value = '';
    document.getElementById('reject-order-modal').classList.remove('hidden');
    document.body.classList.add('modal-open');
}

function closeApproveOrderModal() {
    document.getElementById('approve-order-modal').classList.add('hidden');
    document.getElementById('approve-order-notes').value = '';
    document.getElementById('approve-order-modal').dataset.orderId = '';
    document.body.classList.remove('modal-open');
}

function closeRejectOrderModal() {
    document.getElementById('reject-order-modal').classList.add('hidden');
    document.getElementById('reject-order-notes').value = '';
    document.getElementById('reject-order-modal').dataset.orderId = '';
    document.body.classList.remove('modal-open');
}

function confirmApproveOrder(button) {
    const orderId = document.getElementById('approve-order-modal').dataset.orderId;
    const adminNotes = document.getElementById('approve-order-notes').value;

    if (!orderId) {
        alert('Order ID not found');
        return;
    }

    // Disable button to prevent double submission
    const confirmBtn = button || document.getElementById('confirm-approve-order-btn');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Processing...';

    // Build the URL - routes are /orders/{order}/approve on admin subdomain
    const approveUrl = `{{ url('/') }}/orders/${orderId}/approve`;
    fetch(approveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            admin_notes: adminNotes
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeApproveOrderModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to approve order'));
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while approving the order: ' + error.message);
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = originalText;
    });
}

function confirmRejectOrder(button) {
    const orderId = document.getElementById('reject-order-modal').dataset.orderId;
    const adminNotes = document.getElementById('reject-order-notes').value.trim();

    if (!orderId) {
        alert('Order ID not found');
        return;
    }

    if (!adminNotes) {
        alert('Rejection reason is required');
        document.getElementById('reject-order-notes').focus();
        return;
    }

    // Disable button to prevent double submission
    const confirmBtn = button || document.getElementById('confirm-reject-order-btn');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Processing...';

    // Build the URL - routes are /orders/{order}/reject on admin subdomain
    const rejectUrl = `{{ url('/') }}/orders/${orderId}/reject`;
    fetch(rejectUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            admin_notes: adminNotes
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeRejectOrderModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to reject order'));
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while rejecting the order: ' + error.message);
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = originalText;
    });
}

function closeBulkApproveModal() {
    document.getElementById('bulk-approve-modal').classList.add('hidden');
    document.getElementById('bulk-approve-notes').value = '';
    document.body.classList.remove('modal-open');
}

function confirmBulkApprove(button) {
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
    const adminNotes = document.getElementById('bulk-approve-notes').value;

    if (orderIds.length === 0) {
        alert('No orders selected');
        return;
    }

    // Disable button to prevent double submission
    const confirmBtn = button || document.getElementById('confirm-bulk-approve-btn');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Processing...';

    fetch('{{ admin_route("orders.bulk-approve") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            order_ids: orderIds,
            admin_notes: adminNotes
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeBulkApproveModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'An error occurred'));
            const confirmBtn = document.getElementById('confirm-bulk-approve-btn');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Approve Selected';
        }
    })
    .catch(error => {
        alert('An error occurred while processing the request: ' + error.message);
        const confirmBtn = document.getElementById('confirm-bulk-approve-btn');
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Approve Selected';
    });
}

function bulkApproveOrders(orderIds) {
    // This function is kept for backward compatibility but uses the modal now
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    if (checkedBoxes.length > 0) {
        document.getElementById('selected-orders-count').textContent = `You are about to approve ${checkedBoxes.length} order(s).`;
        document.getElementById('bulk-approve-modal').classList.remove('hidden');
        document.body.classList.add('modal-open');
    }
}
</script>
@endpush
@endsection
