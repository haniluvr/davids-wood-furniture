@extends('admin.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Track Order')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="package-search" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Track Order</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Order #{{ $order->order_number }}</p>
                </div>
            </div>
            <a href="{{ admin_route('delivery-tracking.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Tracking
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Tracking Information -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-emerald-50 to-blue-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl">
                            <i data-lucide="map-pin" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Tracking Information</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Package delivery status and location</p>
                </div>
                <div class="p-8">
                    @if($order->tracking_number)
                        <div class="space-y-6">
                            <!-- Tracking Number -->
                            <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-stone-500 dark:text-gray-400">Tracking Number</p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <code class="text-lg font-mono text-stone-900 dark:text-white">{{ $order->tracking_number }}</code>
                                        <button onclick="copyTrackingNumber('{{ $order->tracking_number }}')" 
                                                class="text-stone-500 hover:text-stone-700 dark:text-gray-400 dark:hover:text-gray-300">
                                            <i data-lucide="copy" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Carrier -->
                            @if($order->carrier)
                                <div>
                                    <p class="text-sm font-medium text-stone-500 dark:text-gray-400 mb-2">Carrier</p>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $order->carrier }}
                                    </span>
                                </div>
                            @endif

                            <!-- Tracking Timeline -->
                            <div>
                                <p class="text-sm font-medium text-stone-700 dark:text-stone-300 mb-4">Delivery Timeline</p>
                                <div class="space-y-4">
                                    @if($order->delivered_at)
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-stone-900 dark:text-white">Delivered</p>
                                                <p class="text-xs text-stone-500 dark:text-gray-400">{{ $order->delivered_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($order->shipped_at)
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                    <i data-lucide="truck" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-stone-900 dark:text-white">Shipped</p>
                                                <p class="text-xs text-stone-500 dark:text-gray-400">{{ $order->shipped_at->format('M d, Y h:i A') }}</p>
                                                @if($order->shipping_address)
                                                    <p class="text-xs text-stone-500 dark:text-gray-400 mt-1">
                                                        Shipping to: {{ is_array($order->shipping_address) ? ($order->shipping_address['city'] ?? 'N/A') : 'N/A' }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-stone-100 dark:bg-gray-800 flex items-center justify-center">
                                                <i data-lucide="package" class="w-5 h-5 text-stone-600 dark:text-gray-400"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-stone-900 dark:text-white">Order Placed</p>
                                            <p class="text-xs text-stone-500 dark:text-gray-400">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-stone-100 dark:bg-gray-800 mb-4">
                                <i data-lucide="package-x" class="w-8 h-8 text-stone-400"></i>
                            </div>
                            <p class="text-sm text-stone-500 dark:text-gray-400">No tracking number assigned yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                            <i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Items</h3>
                    </div>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center space-x-4 p-4 border border-stone-200 dark:border-strokedark rounded-xl">
                                @if($item->product && $item->product->images && count($item->product->images) > 0)
                                    <img src="{{ Storage::url($item->product->images[0]) }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded-lg object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-stone-100 dark:bg-gray-800 flex items-center justify-center">
                                        <i data-lucide="package" class="w-8 h-8 text-stone-400"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-stone-900 dark:text-white">{{ $item->product_name }}</p>
                                    <p class="text-xs text-stone-500 dark:text-gray-400">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-stone-900 dark:text-white">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Order Summary -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl">
                            <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Summary</h3>
                    </div>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-stone-600 dark:text-gray-400">Subtotal:</span>
                            <span class="text-sm font-medium text-stone-900 dark:text-white">₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-stone-600 dark:text-gray-400">Shipping:</span>
                            <span class="text-sm font-medium text-stone-900 dark:text-white">₱{{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-stone-600 dark:text-gray-400">Tax:</span>
                            <span class="text-sm font-medium text-stone-900 dark:text-white">₱{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-stone-600 dark:text-gray-400">Discount:</span>
                                <span class="text-sm font-medium text-red-600 dark:text-red-400">-₱{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="border-t border-stone-200 dark:border-strokedark pt-4">
                            <div class="flex justify-between">
                                <span class="text-base font-semibold text-stone-900 dark:text-white">Total:</span>
                                <span class="text-base font-semibold text-stone-900 dark:text-white">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-green-50 to-blue-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                            <i data-lucide="user" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Customer</h3>
                    </div>
                </div>
                <div class="p-8">
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-stone-900 dark:text-white">{{ $order->user->first_name }} {{ $order->user->last_name }}</p>
                            <p class="text-xs text-stone-500 dark:text-gray-400">{{ $order->user->email }}</p>
                        </div>
                        @if($order->shipping_address && is_array($order->shipping_address))
                            <div class="pt-3 border-t border-stone-200 dark:border-strokedark">
                                <p class="text-xs font-medium text-stone-500 dark:text-gray-400 mb-2">Shipping Address</p>
                                <p class="text-sm text-stone-900 dark:text-white">
                                    {{ $order->shipping_address['street'] ?? '' }}<br>
                                    {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['province'] ?? '' }}<br>
                                    {{ $order->shipping_address['zip_code'] ?? '' }}
                                </p>
                            </div>
                        @endif
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
                </div>
                <div class="p-8">
                    <div class="space-y-3">
                        <a href="{{ admin_route('orders.show', $order) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            View Order Details
                        </a>
                        @if($order->tracking_number)
                            <a href="https://tracking.com/search?q={{ $order->tracking_number }}" target="_blank" 
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-blue-600 text-sm font-medium text-white shadow-lg transition-all duration-200 hover:from-emerald-700 hover:to-blue-700 hover:shadow-xl">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                                Track on Carrier Site
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyTrackingNumber(trackingNumber) {
    navigator.clipboard.writeText(trackingNumber).then(() => {
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

