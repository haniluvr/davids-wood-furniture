@extends('admin.layouts.app')

@section('title', 'Fulfillment')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">
            Order Fulfillment
        </h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Manage packing and shipping workflow for orders.
        </p>
    </div>

    <div class="flex items-center gap-3">
        <button id="bulk-ship-btn" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200" disabled>
            <i data-lucide="truck" class="w-4 h-4"></i>
            Bulk Mark Shipped
        </button>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards Start -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4 mb-8">
    <!-- Pending Packing -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-yellow-50 to-yellow-100/50 p-6 shadow-lg shadow-yellow-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-yellow-500/20 dark:from-yellow-900/20 dark:to-yellow-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-500 shadow-lg">
                    <i data-lucide="package" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['pending_packing']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Pending Packing</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-yellow-500/10"></div>
    </div>

    <!-- Packed -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 dark:from-blue-900/20 dark:to-blue-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['packed']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Packed</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10"></div>
    </div>

    <!-- Ready to Ship -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100/50 p-6 shadow-lg shadow-purple-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 dark:from-purple-900/20 dark:to-purple-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 shadow-lg">
                    <i data-lucide="truck" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['ready_to_ship']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Ready to Ship</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
    </div>

    <!-- Shipped Today -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-lg shadow-emerald-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/20 dark:from-emerald-900/20 dark:to-emerald-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg">
                    <i data-lucide="send" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['shipped_today']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Shipped Today</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10"></div>
    </div>
</div>
<!-- Stats Cards End -->

<!-- Orders Ready to Ship -->
<div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-stone-900 dark:text-white">Orders Ready to Ship</h3>
            <p class="text-sm text-stone-600 dark:text-gray-400">Orders that need to be packed and shipped</p>
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
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Items</h5>
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Status</h5>
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Actions</h5>
            </div>
        </div>

        @forelse($ordersReadyToShip as $order)
        <div class="grid grid-cols-6 border-b border-stone-200/50 dark:border-strokedark/50 transition-colors duration-200 hover:bg-stone-50/50 dark:hover:bg-stone-800/20">
            <div class="flex items-center p-4">
                <input type="checkbox" class="order-checkbox rounded border-stone-300 text-primary focus:ring-primary" value="{{ $order->id }}">
            </div>
            <div class="flex items-center gap-3 p-4">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg">
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
                    <p class="font-semibold text-stone-900 dark:text-white">{{ $order->orderItems->count() }} items</p>
                    <p class="text-xs text-stone-500 dark:text-gray-400">₱{{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <div class="flex items-center p-4">
                @php
                    $fulfillment = $order->fulfillment;
                    $progress = 0;
                    if ($fulfillment) {
                        $steps = 0;
                        if ($fulfillment->items_packed) $steps++;
                        if ($fulfillment->label_printed) $steps++;
                        if ($fulfillment->shipped) $steps++;
                        $progress = round(($steps / 3) * 100);
                    }
                @endphp
                <div class="w-full">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-medium text-stone-600 dark:text-gray-400">Progress</span>
                        <span class="text-xs font-medium text-stone-600 dark:text-gray-400">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-stone-200 rounded-full h-2 dark:bg-stone-700">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 p-4">
                <a href="{{ route('admin.orders.fulfillment.show', $order) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-emerald-100 hover:text-emerald-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-400" title="View Details">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </a>
                <a href="{{ route('admin.orders.fulfillment.print-label', $order) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-blue-100 hover:text-blue-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-blue-900/20 dark:hover:text-blue-400" title="Print Label">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="p-8 text-center">
            <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4 dark:bg-stone-800">
                <i data-lucide="package" class="w-6 h-6 text-stone-400"></i>
            </div>
            <p class="text-stone-500 dark:text-gray-400">No orders ready to ship</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($ordersReadyToShip->hasPages())
    <div class="mt-6">
        {{ $ordersReadyToShip->links() }}
    </div>
    @endif
</div>

<!-- Bulk Ship Modal -->
<div id="bulk-ship-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-boxdark">
            <form id="bulk-ship-form">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-boxdark">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10 dark:bg-emerald-900/30">
                            <i data-lucide="truck" class="h-6 w-6 text-emerald-600 dark:text-emerald-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Bulk Mark as Shipped
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="carrier" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Carrier</label>
                                    <select id="carrier" name="carrier" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white">
                                        <option value="">Select Carrier</option>
                                        <option value="LBC">LBC</option>
                                        <option value="J&T Express">J&T Express</option>
                                        <option value="2GO">2GO</option>
                                        <option value="Grab Express">Grab Express</option>
                                        <option value="Lalamove">Lalamove</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div id="tracking-numbers-container">
                                    <!-- Tracking numbers will be added dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-stone-800">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Mark as Shipped
                    </button>
                    <button type="button" id="cancel-bulk-ship" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-stone-700 dark:border-stone-600 dark:text-white dark:hover:bg-stone-600">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkShipBtn = document.getElementById('bulk-ship-btn');
    const bulkShipModal = document.getElementById('bulk-ship-modal');
    const cancelBulkShip = document.getElementById('cancel-bulk-ship');
    const bulkShipForm = document.getElementById('bulk-ship-form');
    const trackingNumbersContainer = document.getElementById('tracking-numbers-container');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkShipButton();
    });

    // Individual checkbox change
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkShipButton();
            updateSelectAllState();
        });
    });

    function updateBulkShipButton() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        bulkShipBtn.disabled = checkedBoxes.length === 0;
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        const totalBoxes = orderCheckboxes.length;
        selectAllCheckbox.checked = checkedBoxes.length === totalBoxes;
        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
    }

    // Bulk ship modal
    bulkShipBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
        
        // Clear and populate tracking numbers container
        trackingNumbersContainer.innerHTML = '';
        orderIds.forEach((orderId, index) => {
            const div = document.createElement('div');
            div.innerHTML = `
                <label for="tracking_${orderId}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Order #${orderId} - Tracking Number</label>
                <input type="text" id="tracking_${orderId}" name="tracking_numbers[${index}]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-stone-800 dark:border-stone-600 dark:text-white" required>
            `;
            trackingNumbersContainer.appendChild(div);
        });

        // Add hidden inputs for order IDs
        const existingOrderIds = document.querySelectorAll('input[name="order_ids[]"]');
        existingOrderIds.forEach(input => input.remove());
        
        orderIds.forEach(orderId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'order_ids[]';
            input.value = orderId;
            bulkShipForm.appendChild(input);
        });

        bulkShipModal.classList.remove('hidden');
    });

    cancelBulkShip.addEventListener('click', function() {
        bulkShipModal.classList.add('hidden');
    });

    // Close modal when clicking outside
    bulkShipModal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Form submission
    bulkShipForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("admin.orders.fulfillment.bulk-ship") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
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
});
});
</script>
@endpush
@endsection
