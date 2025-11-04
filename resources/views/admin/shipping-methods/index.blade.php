@extends('admin.layouts.app')

@section('title', 'Shipping Methods')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-stone-900">Shipping Methods</h1>
                <p class="mt-1 text-sm text-stone-600">Manage shipping options and delivery methods</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="reorderShippingMethods()" 
                        class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 hover:bg-stone-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reorder
                </button>
                <a href="{{ admin_route('shipping-methods.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Shipping Method
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="py-6">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
            <form method="GET" action="{{ admin_route('shipping-methods.index') }}" class="flex flex-wrap items-end gap-4 justify-between">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-stone-700 mb-2">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Search by name or description..."
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
                    <label for="type" class="block text-sm font-medium text-stone-700 mb-2">Type</label>
                    <select id="type" name="type" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Types</option>
                        <option value="flat_rate" {{ request('type') == 'flat_rate' ? 'selected' : '' }}>Flat Rate</option>
                        <option value="free_shipping" {{ request('type') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                        <option value="weight_based" {{ request('type') == 'weight_based' ? 'selected' : '' }}>Weight Based</option>
                        <option value="price_based" {{ request('type') == 'price_based' ? 'selected' : '' }}>Price Based</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 whitespace-nowrap">
                        Filter
                    </button>
                    <a href="{{ admin_route('shipping-methods.index') }}" class="inline-flex items-center justify-center rounded-lg border border-stone-300 px-4 py-2.5 text-sm hover:bg-stone-50">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Shipping Methods List -->
    <div class="pb-8">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200">
            @if($shippingMethods->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Delivery</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stone-200" id="sortable-shipping-methods">
                            @foreach($shippingMethods as $method)
                                <tr class="hover:bg-stone-50 transition-colors duration-150" data-id="{{ $method->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-stone-900">{{ $method->name }}</div>
                                                @if($method->description)
                                                    <div class="text-sm text-stone-500">{{ Str::limit($method->description, 50) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $method->type === 'free_shipping' ? 'bg-green-100 text-green-800' : 
                                               ($method->type === 'flat_rate' ? 'bg-blue-100 text-blue-800' : 
                                               ($method->type === 'weight_based' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800')) }}">
                                            {{ ucwords(str_replace('_', ' ', $method->type)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        @if($method->type === 'free_shipping')
                                            <span class="text-green-600 font-medium">Free</span>
                                        @else
                                            ₱{{ number_format($method->cost, 2) }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        @if($method->estimated_days_min && $method->estimated_days_max)
                                            {{ $method->estimated_days_min }}-{{ $method->estimated_days_max }} days
                                        @elseif($method->estimated_days_min)
                                            {{ $method->estimated_days_min }}+ days
                                        @else
                                            <span class="text-stone-400">Not specified</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="toggleStatus({{ $method->id }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-150
                                            {{ $method->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ admin_route('shipping-methods.show', $method) }}" class="text-emerald-600 hover:text-emerald-900 transition-colors duration-150" title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ admin_route('shipping-methods.edit', $method) }}" class="text-stone-600 hover:text-stone-900 transition-colors duration-150" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <button onclick="deleteShippingMethod({{ $method->id }})" class="text-red-600 hover:text-red-900 transition-colors duration-150" title="Delete">
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
                @if($shippingMethods->hasPages())
                    @include('admin.partials.pagination', ['paginator' => $shippingMethods])
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-stone-900">No shipping methods</h3>
                    <p class="mt-1 text-sm text-stone-500">Get started by creating your first shipping method.</p>
                    <div class="mt-6">
                        <a href="{{ admin_route('shipping-methods.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Shipping Method
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
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-4">Delete Shipping Method</h3>
            <div class="mt-3 px-4 py-3">
                <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this shipping method? This action cannot be undone and will permanently remove the method from your system.</p>
            </div>
            <div class="flex gap-3 px-4 py-4">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 border border-stone-200 dark:border-strokedark bg-white dark:bg-boxdark text-stone-700 dark:text-white text-sm font-medium rounded-xl hover:bg-stone-50 dark:hover:bg-gray-800 transition-colors duration-200">
                    Cancel
                </button>
                <button id="confirmDelete" class="flex-1 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                    Delete Method
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
let deleteMethodId = null;

// Initialize sortable
document.addEventListener('DOMContentLoaded', function() {
    const sortableContainer = document.getElementById('sortable-shipping-methods');
    if (sortableContainer) {
        const sortable = Sortable.create(sortableContainer, {
            handle: 'tr',
            animation: 150,
            onEnd: function(evt) {
                const items = Array.from(document.querySelectorAll('#sortable-shipping-methods tr[data-id]'));
                const shippingMethods = items.map((item, index) => ({
                    id: item.dataset.id,
                    sort_order: index
                }));
                
                fetch('{{ admin_route("shipping-methods.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ shipping_methods: shippingMethods })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Shipping methods reordered successfully', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error reordering shipping methods', 'error');
                });
            }
        });
    }
});

function toggleStatus(methodId) {
    fetch(`/admin/shipping-methods/${methodId}/toggle-status`, {
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

function deleteShippingMethod(methodId) {
    deleteMethodId = methodId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteMethodId = null;
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (deleteMethodId) {
        fetch(`/admin/shipping-methods/${deleteMethodId}`, {
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
            showNotification('Error deleting shipping method', 'error');
        });
    }
    closeDeleteModal();
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl text-white z-50 shadow-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function reorderShippingMethods() {
    // Toggle drag mode or show instructions
    alert('Drag and drop rows to reorder shipping methods');
}
</script>
@endpush
