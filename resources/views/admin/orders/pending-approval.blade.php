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
    <div class="py-6">
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
    <div class="py-6">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
            <form method="GET" action="{{ admin_route('orders.pending-approval') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-stone-700 mb-2">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Search orders..."
                           class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-stone-700 mb-2">Priority</label>
                    <select id="priority" name="priority" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Priorities</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                    </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ admin_route('orders.pending-approval') }}" class="inline-flex justify-center items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Clear
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
                                        <span class="text-sm text-stone-900 dark:text-white capitalize">
                                            {{ $order->priority }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        {{ $order->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="approveOrder({{ $order->id }})" class="text-green-600 hover:text-green-900 transition-colors duration-150" title="Approve">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                </button>
                                            <button onclick="rejectOrder({{ $order->id }})" class="text-red-600 hover:text-red-900 transition-colors duration-150" title="Reject">
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
                    <div class="px-6 py-3 border-t border-stone-200">
        {{ $orders->links() }}
    </div>
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
            if (confirm(`Are you sure you want to approve ${checkedBoxes.length} orders?`)) {
                bulkApproveOrders(Array.from(checkedBoxes).map(cb => cb.value));
            }
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

function approveOrder(orderId) {
    if (confirm('Are you sure you want to approve this order?')) {
        // Implement approve order functionality
        console.log('Approve order:', orderId);
    }
}

function rejectOrder(orderId) {
    if (confirm('Are you sure you want to reject this order?')) {
        // Implement reject order functionality
        console.log('Reject order:', orderId);
    }
}

function bulkApproveOrders(orderIds) {
    // Implement bulk approve functionality
    console.log('Bulk approve orders:', orderIds);
}
</script>
@endpush
@endsection
