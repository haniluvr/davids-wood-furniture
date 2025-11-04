@extends('admin.layouts.app')

@section('title', 'Payment Gateways')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-stone-900">Payment Gateways</h1>
                <p class="mt-1 text-sm text-stone-600">Manage payment processing methods and configurations</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="reorderPaymentGateways()" 
                        class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 hover:bg-stone-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reorder
                </button>
                <a href="{{ admin_route('payment-gateways.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Payment Gateway
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="py-6">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
            <form method="GET" action="{{ admin_route('payment-gateways.index') }}" class="flex flex-wrap items-end gap-4 justify-between">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-stone-700 mb-2">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Search by name or gateway..."
                           class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-stone-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="mode" class="block text-sm font-medium text-stone-700 mb-2">Mode</label>
                    <select id="mode" name="mode" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Modes</option>
                        <option value="live" {{ request('mode') == 'live' ? 'selected' : '' }}>Live</option>
                        <option value="test" {{ request('mode') == 'test' ? 'selected' : '' }}>Test</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 whitespace-nowrap">
                        Filter
                    </button>
                    <a href="{{ admin_route('payment-gateways.index') }}" class="inline-flex items-center justify-center rounded-lg border border-stone-300 px-4 py-2.5 text-sm hover:bg-stone-50">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Gateways List -->
    <div class="pb-8">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200">
            @if($paymentGateways->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Gateway</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Fees</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Mode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stone-200" id="sortable-payment-gateways">
                            @foreach($paymentGateways as $gateway)
                                <tr class="hover:bg-stone-50 transition-colors duration-150" data-id="{{ $gateway->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-stone-900">{{ $gateway->display_name }}</div>
                                                <div class="text-sm text-stone-500">{{ $gateway->gateway_key }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucwords(str_replace('_', ' ', $gateway->gateway_key)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        @if($gateway->transaction_fee_percentage > 0 || $gateway->transaction_fee_fixed > 0)
                                            @if($gateway->transaction_fee_percentage > 0)
                                                {{ number_format($gateway->transaction_fee_percentage, 2) }}%
                                            @endif
                                            @if($gateway->transaction_fee_percentage > 0 && $gateway->transaction_fee_fixed > 0)
                                                +
                                            @endif
                                            @if($gateway->transaction_fee_fixed > 0)
                                                ₱{{ number_format($gateway->transaction_fee_fixed, 2) }}
                                            @endif
                                        @else
                                            <span class="text-stone-400">No fees</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="toggleMode({{ $gateway->id }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-150
                                            {{ $gateway->is_test_mode ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                                            {{ $gateway->is_test_mode ? 'Test' : 'Live' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="toggleStatus({{ $gateway->id }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-150
                                            {{ $gateway->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                            {{ $gateway->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="testConnection({{ $gateway->id }})" class="text-blue-600 hover:text-blue-900 transition-colors duration-150" title="Test Connection">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                            <a href="{{ admin_route('payment-gateways.show', $gateway) }}" class="text-emerald-600 hover:text-emerald-900 transition-colors duration-150" title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ admin_route('payment-gateways.edit', $gateway) }}" class="text-stone-600 hover:text-stone-900 transition-colors duration-150" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <button onclick="deletePaymentGateway({{ $gateway->id }})" class="text-red-600 hover:text-red-900 transition-colors duration-150" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($paymentGateways->hasPages())
                    @include('admin.partials.pagination', ['paginator' => $paymentGateways])
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-stone-900">No payment gateways</h3>
                    <p class="mt-1 text-sm text-stone-500">Get started by adding a payment gateway.</p>
                    <div class="mt-6">
                        <a href="{{ admin_route('payment-gateways.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Payment Gateway
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-[9999]">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-boxdark text-left align-middle shadow-2xl transition-all">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/30">
                <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-4">Delete Payment Gateway</h3>
            <div class="mt-3 px-4 py-3">
                <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this payment gateway? This action cannot be undone and will permanently remove the gateway from your system.</p>
            </div>
            <div class="flex gap-3 px-4 py-4">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 border border-stone-200 dark:border-strokedark bg-white dark:bg-boxdark text-stone-700 dark:text-white text-sm font-medium rounded-xl hover:bg-stone-50 dark:hover:bg-gray-800 transition-colors duration-200">
                    Cancel
                </button>
                <button id="confirmDelete" class="flex-1 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                    Delete Gateway
                </button>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let deleteGatewayId = null;

// Initialize sortable
document.addEventListener('DOMContentLoaded', function() {
    const sortableContainer = document.getElementById('sortable-payment-gateways');
    if (sortableContainer) {
        const sortable = Sortable.create(sortableContainer, {
            handle: 'tr',
            animation: 150,
            onEnd: function(evt) {
                const items = Array.from(document.querySelectorAll('#sortable-payment-gateways tr[data-id]'));
                const paymentGateways = items.map((item, index) => ({
                    id: item.dataset.id,
                    sort_order: index
                }));
                
                fetch('{{ admin_route("payment-gateways.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ payment_gateways: paymentGateways })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Payment gateways reordered successfully', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error reordering payment gateways', 'error');
                });
            }
        });
    }
});

function toggleStatus(gatewayId) {
    fetch(`/admin/payment-gateways/${gatewayId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating status', 'error');
    });
}

function toggleMode(gatewayId) {
    fetch(`/admin/payment-gateways/${gatewayId}/toggle-mode`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating mode', 'error');
    });
}

function testConnection(gatewayId) {
    showNotification('Testing connection...', 'info');
    
    fetch(`/admin/payment-gateways/${gatewayId}/test-connection`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error testing connection', 'error');
    });
}

function deletePaymentGateway(gatewayId) {
    deleteGatewayId = gatewayId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteGatewayId = null;
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (deleteGatewayId) {
        fetch(`/admin/payment-gateways/${deleteGatewayId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error deleting payment gateway', 'error');
        });
    }
    closeDeleteModal();
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl text-white z-50 shadow-lg ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function reorderPaymentGateways() {
    alert('Drag and drop rows to reorder payment gateways');
}
</script>
@endpush
