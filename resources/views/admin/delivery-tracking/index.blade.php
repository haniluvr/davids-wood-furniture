@extends('admin.layouts.app')

@section('title', 'Delivery Tracking')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-stone-900">Delivery Tracking</h1>
                <p class="mt-1 text-sm text-stone-600">Track and manage order deliveries</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="pt-6 pb-3">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            <!-- Total Shipped -->
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
                        <p class="text-sm font-medium text-stone-500">Total Shipped</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['total_shipped'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- In Transit -->
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
                        <p class="text-sm font-medium text-stone-500">In Transit</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['in_transit'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Delivered -->
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
                        <p class="text-sm font-medium text-stone-500">Total Delivered</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['total_delivered'] ?? 0) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="pt-3 pb-6">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
            <form method="GET" action="{{ admin_route('delivery-tracking.index') }}" class="flex flex-wrap items-end gap-4 justify-between">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-stone-700 mb-2">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Order #, Tracking #, Customer..."
                           class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-stone-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Status</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="carrier" class="block text-sm font-medium text-stone-700 mb-2">Carrier</label>
                    <select id="carrier" name="carrier" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Carriers</option>
                        @foreach($carriers as $carrier)
                            <option value="{{ $carrier }}" {{ request('carrier') == $carrier ? 'selected' : '' }}>{{ $carrier }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date_from" class="block text-sm font-medium text-stone-700 mb-2">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 whitespace-nowrap">
                        Filter
                    </button>
                    <a href="{{ admin_route('delivery-tracking.index') }}" class="inline-flex items-center justify-center rounded-lg border border-stone-300 px-4 py-2.5 text-sm hover:bg-stone-50">
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Tracking Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Carrier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Shipped Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stone-200">
                            @foreach($orders as $order)
                                <tr class="hover:bg-stone-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-stone-900">#{{ $order->order_number }}</div>
                                        <div class="text-sm text-stone-500">{{ $order->orderItems->count() }} items</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-stone-900">{{ $order->user->first_name }} {{ $order->user->last_name }}</div>
                                        <div class="text-sm text-stone-500">{{ $order->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <code class="px-2 py-1 bg-stone-100 rounded text-sm font-mono text-stone-900">
                                                {{ $order->tracking_number }}
                                            </code>
                                            <button onclick="copyTrackingNumber('{{ $order->tracking_number }}')" 
                                                    class="text-stone-500 hover:text-stone-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($order->carrier)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $order->carrier }}
                                            </span>
                                        @else
                                            <span class="text-sm text-stone-400">Not specified</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($order->status === 'delivered')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Delivered
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                In Transit
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        @if($order->shipped_at)
                                            {{ $order->shipped_at->format('M d, Y') }}
                                        @else
                                            <span class="text-stone-400">Not shipped</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ admin_route('delivery-tracking.show', $order) }}" class="text-emerald-600 hover:text-emerald-900 transition-colors duration-150" title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ admin_route('orders.show', $order) }}" class="text-stone-600 hover:text-stone-900 transition-colors duration-150" title="View Order">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-stone-500">No tracked orders found</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyTrackingNumber(trackingNumber) {
    navigator.clipboard.writeText(trackingNumber).then(() => {
        // Show notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-xl text-white z-50 shadow-lg bg-green-500';
        notification.textContent = 'Tracking number copied!';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
</script>
@endpush
@endsection
