@extends('admin.layouts.app')

@section('title', 'Fulfillment')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex justify-between items-center py-6">
    <div>
                <h1 class="text-2xl font-bold text-stone-900">Order Fulfillment</h1>
                <p class="mt-1 text-sm text-stone-600">Manage packing and shipping workflow for orders</p>
    </div>
            <div class="flex gap-3">
                <button id="bulk-ship-btn" class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" disabled>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
            Bulk Mark Shipped
        </button>
    </div>
</div>
    </div>

    <!-- Statistics Cards -->
    <div class="pt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Pending Packing -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Pending Packing</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['pending_packing'] ?? 0) }}</p>
                </div>
            </div>
    </div>

    <!-- Packed -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Packed</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['packed'] ?? 0) }}</p>
                </div>
            </div>
    </div>

            <!-- Shipped -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                </div>
            </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Shipped</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['shipped'] ?? 0) }}</p>
                </div>
            </div>
        </div>

            <!-- Delivered -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
    </div>
</div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Delivered</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['delivered'] ?? 0) }}</p>
        </div>
    </div>
            </div>
            </div>
        </div>

    <!-- Filters -->
    <div class="pb-6">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
            <form method="GET" action="{{ admin_route('orders.fulfillment') }}" class="flex flex-wrap items-end gap-4 justify-between">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-stone-700 mb-2">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Search orders..."
                           class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-stone-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Status</option>
                        <option value="pending_packing" {{ request('status') === 'pending_packing' ? 'selected' : '' }}>Pending Packing</option>
                        <option value="packed" {{ request('status') === 'packed' ? 'selected' : '' }}>Packed</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 whitespace-nowrap">
                        Filter
                    </button>
                    <a href="{{ admin_route('orders.fulfillment') }}" class="inline-flex items-center justify-center rounded-lg border border-stone-300 px-4 py-2.5 text-sm hover:bg-stone-50">
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Tracking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Due Date</th>
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-stone-900 dark:text-white capitalize">
                                            {{ str_replace('_', ' ', $order->fulfillment_status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        {{ $order->tracking_number ?? 'Not assigned' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        {{ $order->fulfillment_due_date ? $order->fulfillment_due_date->format('M d, Y') : 'Not set' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            @if($order->fulfillment_status === 'pending_packing')
                                                <button onclick="markAsPacked({{ $order->id }})" class="text-blue-600 hover:text-blue-900 transition-colors duration-150" title="Mark as Packed">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            @elseif($order->fulfillment_status === 'packed')
                                                <button onclick="markAsShipped({{ $order->id }})" class="text-purple-600 hover:text-purple-900 transition-colors duration-150" title="Mark as Shipped">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                    </svg>
                                                </button>
                                            @endif
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
                    <div class="px-6 py-3 border-t border-stone-200">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <p class="text-stone-500">No orders found</p>
    </div>
    @endif
        </div>
    </div>
</div>

<!-- Success Notification -->
<div id="success-notification" class="fixed top-4 right-4 z-[10000] hidden">
    <div class="bg-emerald-50 border border-emerald-200 rounded-lg shadow-lg p-4 max-w-sm">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <p id="success-message" class="text-sm font-medium text-emerald-900"></p>
            </div>
            <button onclick="closeSuccessNotification()" class="flex-shrink-0 text-emerald-400 hover:text-emerald-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Error Notification -->
<div id="error-notification" class="fixed top-4 right-4 z-[10000] hidden">
    <div class="bg-red-50 border border-red-200 rounded-lg shadow-lg p-4 max-w-sm">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <p id="error-message" class="text-sm font-medium text-red-900"></p>
            </div>
            <button onclick="closeErrorNotification()" class="flex-shrink-0 text-red-400 hover:text-red-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Bulk Ship Confirmation Modal -->
<div id="bulk-ship-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-stone-900">Mark Orders as Shipped</h3>
                </div>
                <p id="bulk-ship-message" class="text-sm text-stone-600"></p>
            </div>
            <div class="flex gap-3">
                <button id="bulk-ship-cancel" class="flex-1 rounded-lg border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50">
                    Cancel
                </button>
                <button id="bulk-ship-confirm" class="flex-1 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                    Confirm
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
    const bulkShipBtn = document.getElementById('bulk-ship-btn');

    // Select all functionality
    if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkShipButton();
    });
    }

    // Individual checkbox change
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkShipButton();
            updateSelectAllState();
        });
    });

    // Bulk ship button
    bulkShipBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        if (checkedBoxes.length > 0) {
            showBulkShipModal(checkedBoxes.length);
        }
    });

    function updateBulkShipButton() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        bulkShipBtn.disabled = checkedBoxes.length === 0;
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

function markAsPacked(orderId) {
    if (confirm('Are you sure you want to mark this order as packed?')) {
        // Implement mark as packed functionality
        console.log('Mark as packed:', orderId);
    }
}

function markAsShipped(orderId) {
    if (confirm('Are you sure you want to mark this order as shipped?')) {
        // Implement mark as shipped functionality
        console.log('Mark as shipped:', orderId);
    }
}

function showBulkShipModal(orderCount) {
    if (orderCount === 0) {
        return;
    }
    
    const message = orderCount === 1 
        ? `Are you sure you want to mark ${orderCount} order as shipped?`
        : `Are you sure you want to mark ${orderCount} orders as shipped?`;
    
    document.getElementById('bulk-ship-message').textContent = message;
    document.getElementById('bulk-ship-modal').classList.remove('hidden');
    document.body.classList.add('modal-open');
    
    // Remove existing event listeners
    const confirmBtn = document.getElementById('bulk-ship-confirm');
    const cancelBtn = document.getElementById('bulk-ship-cancel');
    
    const newConfirmBtn = confirmBtn.cloneNode(true);
    const newCancelBtn = cancelBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
    
    // Add new event listeners
    newConfirmBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
        bulkShipOrders(orderIds, newConfirmBtn);
    });
    
    newCancelBtn.addEventListener('click', function() {
        document.getElementById('bulk-ship-modal').classList.add('hidden');
        document.body.classList.remove('modal-open');
    });
    
    // Close modal when clicking outside on the overlay
    const modalContainer = document.getElementById('bulk-ship-modal');
    const overlayDiv = modalContainer.querySelector('.fixed.inset-0.bg-black');
    
    if (overlayDiv) {
        const newOverlay = overlayDiv.cloneNode(true);
        overlayDiv.parentNode.replaceChild(newOverlay, overlayDiv);
        newOverlay.addEventListener('click', function() {
            modalContainer.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });
    }
}

function bulkShipOrders(orderIds, confirmButton) {
    if (!orderIds || orderIds.length === 0) {
        showErrorNotification('No orders selected');
        return;
    }

    // Close modal first
    const modal = document.getElementById('bulk-ship-modal');
    modal.classList.add('hidden');
    document.body.classList.remove('modal-open');

    // Show loading state on button if provided
    let originalText = '';
    if (confirmButton) {
        originalText = confirmButton.textContent;
        confirmButton.disabled = true;
        confirmButton.textContent = 'Processing...';
    }

    // Make API request
    fetch('{{ admin_route("orders.fulfillment.bulk-ship") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            order_ids: orderIds,
            carrier: null, // Optional - can be enhanced later with a form
            tracking_numbers: null // Optional - can be enhanced later with a form
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Failed to mark orders as shipped');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showSuccessNotification(data.message || 'Orders marked as shipped successfully');
            // Reload after a short delay to let user see the message
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to mark orders as shipped');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorNotification(error.message || 'Failed to mark orders as shipped');
        if (confirmButton && originalText) {
            confirmButton.disabled = false;
            confirmButton.textContent = originalText;
        }
        // Re-open modal if there was an error
        modal.classList.remove('hidden');
        document.body.classList.add('modal-open');
    });
}

function showSuccessNotification(message) {
    const notification = document.getElementById('success-notification');
    const messageElement = document.getElementById('success-message');
    messageElement.textContent = message;
    notification.classList.remove('hidden');
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        closeSuccessNotification();
    }, 3000);
}

function closeSuccessNotification() {
    document.getElementById('success-notification').classList.add('hidden');
}

function showErrorNotification(message) {
    const notification = document.getElementById('error-notification');
    const messageElement = document.getElementById('error-message');
    messageElement.textContent = message;
    notification.classList.remove('hidden');
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        closeErrorNotification();
    }, 5000);
}

function closeErrorNotification() {
    document.getElementById('error-notification').classList.add('hidden');
}
</script>
@endpush
@endsection
