@extends('admin.layouts.app')

@section('title', 'Pending Approval')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">
            Pending Approval
        </h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Review orders that require manual approval before processing.
        </p>
    </div>

    <div class="flex items-center gap-3">
        <button id="bulk-approve-btn" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200" disabled>
            <i data-lucide="check" class="w-4 h-4"></i>
            Bulk Approve
        </button>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards Start -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-3 mb-8">
    <!-- Pending Approval -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-yellow-50 to-yellow-100/50 p-6 shadow-lg shadow-yellow-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-yellow-500/20 dark:from-yellow-900/20 dark:to-yellow-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-500 shadow-lg">
                    <i data-lucide="clock" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['pending_approval']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Pending Approval</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-yellow-500/10"></div>
    </div>

    <!-- Approved Today -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-lg shadow-emerald-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/20 dark:from-emerald-900/20 dark:to-emerald-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['approved_today']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Approved Today</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10"></div>
    </div>

    <!-- Total Requiring Approval -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 dark:from-blue-900/20 dark:to-blue-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['total_requiring_approval']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Requiring Approval</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10"></div>
    </div>
</div>
<!-- Stats Cards End -->

<!-- Orders Pending Approval -->
<div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-stone-900 dark:text-white">Orders Pending Approval</h3>
            <p class="text-sm text-stone-600 dark:text-gray-400">Orders that need manual review before processing</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-stone-200/50 dark:border-strokedark/50">
        <div class="grid grid-cols-6 rounded-t-xl bg-stone-50 dark:bg-stone-800/50">
            <div class="p-4">
                <input type="checkbox" id="select-all" class="rounded border-stone-300 text-primary focus:ring-primary">
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Order</h5>
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Customer</h5>
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Reason</h5>
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Amount</h5>
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Actions</h5>
            </div>
        </div>

        @forelse($orders as $order)
        <div class="grid grid-cols-6 border-b border-stone-200/50 dark:border-strokedark/50 transition-colors duration-200 hover:bg-stone-50/50 dark:hover:bg-stone-800/20">
            <div class="flex items-center p-4">
                <input type="checkbox" class="order-checkbox rounded border-stone-300 text-primary focus:ring-primary" value="{{ $order->id }}">
            </div>
            <div class="flex items-center gap-3 p-4">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg">
                        <span class="text-white font-semibold text-sm">#</span>
                    </div>
                </div>
                <div class="flex flex-col">
                    <p class="font-semibold text-stone-900 dark:text-white">{{ $order->order_number }}</p>
                    <p class="text-xs text-stone-500 dark:text-gray-400">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="flex items-center p-4">
                <div class="flex flex-col">
                    <p class="font-semibold text-stone-900 dark:text-white">{{ $order->user->first_name ?? 'Guest' }} {{ $order->user->last_name ?? '' }}</p>
                    <p class="text-xs text-stone-500 dark:text-gray-400">{{ $order->user->email ?? 'No email' }}</p>
                </div>
            </div>

            <div class="flex items-center p-4">
                <div class="flex flex-col">
                    <p class="font-semibold text-stone-900 dark:text-white">{{ $order->approval_reason ?? 'Custom Request' }}</p>
                    <p class="text-xs text-stone-500 dark:text-gray-400">{{ $order->orderItems->count() }} items</p>
                </div>
            </div>

            <div class="flex items-center p-4">
                <div class="flex flex-col">
                    <p class="font-bold text-stone-900 dark:text-white">₱{{ number_format($order->total_amount, 2) }}</p>
                    <p class="text-xs text-stone-500 dark:text-gray-400">{{ $order->payment_status }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 p-4">
                <a href="{{ admin_route('orders.show', $order) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-emerald-100 hover:text-emerald-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-400" title="View Details">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </a>
                <button onclick="approveOrder({{ $order->id }})" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-green-100 hover:text-green-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-green-900/20 dark:hover:text-green-400" title="Approve">
                    <i data-lucide="check" class="w-4 h-4"></i>
                </button>
                <button onclick="rejectOrder({{ $order->id }})" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-red-100 hover:text-red-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-red-900/20 dark:hover:text-red-400" title="Reject">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="p-8 text-center">
            <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4 dark:bg-stone-800">
                <i data-lucide="check-circle" class="w-6 h-6 text-stone-400"></i>
            </div>
            <p class="text-stone-500 dark:text-gray-400">No orders pending approval</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkApproveBtn = document.getElementById('bulk-approve-btn');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkApproveButton();
    });

    // Individual checkbox change
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkApproveButton();
            updateSelectAllState();
        });
    });

    function updateBulkApproveButton() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        bulkApproveBtn.disabled = checkedBoxes.length === 0;
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        const totalBoxes = orderCheckboxes.length;
        selectAllCheckbox.checked = checkedBoxes.length === totalBoxes;
        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
    }

    // Bulk approve functionality
    bulkApproveBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (confirm(`Are you sure you want to approve ${orderIds.length} orders?`)) {
            // Process bulk approval
            orderIds.forEach(orderId => {
                approveOrder(orderId, true);
            });
        }
    });
});

function approveOrder(orderId, silent = false) {
    if (!silent && !confirm('Are you sure you want to approve this order?')) {
        return;
    }

    fetch(`/admin/orders/${orderId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            admin_notes: ''
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (!silent) {
                location.reload();
            }
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the request.');
    });
}

function rejectOrder(orderId) {
    const notes = prompt('Please provide a reason for rejection:');
    if (notes !== null) {
        fetch(`/admin/orders/${orderId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                admin_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the request.');
        });
    }
}
</script>
@endpush
@endsection
