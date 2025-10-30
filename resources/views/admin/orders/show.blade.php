@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Order Details - {{ $order->order_number }}</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">View and manage order information</p>
</div>
            </div>
            <a href="{{ admin_route('orders.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Orders
            </a>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="xl:col-span-2 space-y-8">
            <!-- Order Status & Actions -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                            <i data-lucide="activity" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Status & Actions</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Current status and available actions for this order</p>
                </div>
                <div class="p-8 space-y-6">
                    <!-- Current Status Display -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                            <h4 class="text-sm font-medium text-stone-700 dark:text-stone-300">Current Status</h4>
                        <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-stone-900 dark:text-white">Status:</span>
                            <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium
                                @switch($order->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @break
                                    @case('processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @break
                                    @case('shipped') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300 @break
                                    @case('delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 @break
                                    @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @break
                                    @case('returned') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @break
                                    @default bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                @endswitch
                            ">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-stone-900 dark:text-white">Payment:</span>
                            <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium
                                @switch($order->payment_status)
                                    @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @break
                                    @case('paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 @break
                                    @case('refunded') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @break
                                    @case('failed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @break
                                    @default bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                @endswitch
                            ">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Status Update Form -->
                    <div class="space-y-4">
                            <h4 class="text-sm font-medium text-stone-700 dark:text-stone-300">Update Status</h4>
                        <form action="{{ admin_route('orders.update-status', $order) }}" method="POST" class="flex items-end gap-3">
                            @csrf
                            @method('PATCH')
                            <div class="flex-1">
                                    <select name="status" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="returned" {{ $order->status === 'returned' ? 'selected' : '' }}>Returned</option>
                                </select>
                            </div>
                                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-blue-600 px-6 py-3 text-center font-medium text-white hover:from-emerald-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>

                    <!-- Fulfillment Progress Bar -->
                    @if($order->status !== 'cancelled')
                        <div class="space-y-4">
                            <h4 class="text-sm font-medium text-stone-700 dark:text-stone-300">Fulfillment Progress</h4>
                            <div class="space-y-3">
                                <!-- Progress Bar -->
                                <div class="w-full bg-stone-200 rounded-full h-3 dark:bg-stone-700">
                                    @php
                                        $progress = 0;
                                        $steps = ['pending', 'processing', 'shipped', 'delivered'];
                                        $currentStep = array_search($order->status, $steps);
                                        if ($currentStep !== false) {
                                            $progress = (($currentStep + 1) / count($steps)) * 100;
                                        }
                                    @endphp
                                    <div class="bg-gradient-to-r from-emerald-500 to-blue-500 h-3 rounded-full transition-all duration-500" 
                                         style="width: {{ $progress }}%"></div>
                                </div>
                                
                                <!-- Progress Steps -->
                                <div class="flex justify-between text-xs">
                                    <div class="flex flex-col items-center space-y-1">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $order->status === 'pending' ? 'bg-emerald-500 text-white' : ($order->status === 'processing' || $order->status === 'shipped' || $order->status === 'delivered' ? 'bg-emerald-500 text-white' : 'bg-stone-300 text-stone-600') }}">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-stone-600 dark:text-stone-400 {{ $order->status === 'pending' ? 'font-medium text-emerald-600' : '' }}">Order Placed</span>
                                    </div>
                                    <div class="flex flex-col items-center space-y-1">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $order->status === 'processing' ? 'bg-emerald-500 text-white' : ($order->status === 'shipped' || $order->status === 'delivered' ? 'bg-emerald-500 text-white' : 'bg-stone-300 text-stone-600') }}">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-stone-600 dark:text-stone-400 {{ $order->status === 'processing' ? 'font-medium text-emerald-600' : '' }}">Processing</span>
                                    </div>
                                    <div class="flex flex-col items-center space-y-1">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $order->status === 'shipped' ? 'bg-emerald-500 text-white' : ($order->status === 'delivered' ? 'bg-emerald-500 text-white' : 'bg-stone-300 text-stone-600') }}">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-stone-600 dark:text-stone-400 {{ $order->status === 'shipped' ? 'font-medium text-emerald-600' : '' }}">Shipped</span>
                                    </div>
                                    <div class="flex flex-col items-center space-y-1">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $order->status === 'delivered' ? 'bg-emerald-500 text-white' : 'bg-stone-300 text-stone-600' }}">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-stone-600 dark:text-stone-400 {{ $order->status === 'delivered' ? 'font-medium text-emerald-600' : '' }}">Delivered</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                <!-- Action Buttons -->
                    <div class="border-t border-stone-200 pt-6 dark:border-strokedark">
                        <h4 class="text-sm font-medium text-stone-700 dark:text-stone-300 mb-4">Actions</h4>
                    <div class="flex flex-wrap gap-3">
                            <a href="{{ admin_route('orders.edit', $order) }}" class="inline-flex items-center justify-center rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-center font-medium text-emerald-700 hover:bg-emerald-100 transition-all duration-200 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400">
                            <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                            Edit Order
                        </a>
                        
                            <a href="{{ admin_route('orders.download-invoice', $order) }}" class="inline-flex items-center justify-center rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-center font-medium text-stone-700 hover:bg-stone-50 transition-all duration-200 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                            <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                            Download Invoice
                        </a>
                        
                        @if($order->payment_status === 'paid' && in_array($order->status, ['delivered', 'cancelled']))
                            <button onclick="openRefundModal()" class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-center font-medium text-red-600 hover:bg-red-100 transition-all duration-200 dark:border-red-600 dark:bg-red-900/20 dark:text-red-400">
                            <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i>
                            Process Refund
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl">
                            <i data-lucide="package" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Items</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Products included in this order</p>
            </div>
                <div class="p-8">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                                <tr class="bg-stone-50 text-left dark:bg-meta-4">
                                    <th class="min-w-[220px] px-4 py-4 font-medium text-stone-900 dark:text-white xl:pl-11">
                                    Product
                                </th>
                                    <th class="min-w-[150px] px-4 py-4 font-medium text-stone-900 dark:text-white">
                                    SKU
                                </th>
                                    <th class="min-w-[120px] px-4 py-4 font-medium text-stone-900 dark:text-white">
                                    Quantity
                                </th>
                                    <th class="min-w-[120px] px-4 py-4 font-medium text-stone-900 dark:text-white">
                                    Unit Price
                                </th>
                                    <th class="px-4 py-4 font-medium text-stone-900 dark:text-white">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr class="border-b border-stone-200 dark:border-strokedark hover:bg-stone-50 dark:hover:bg-meta-4/50 transition-colors duration-200">
                                    <td class="px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                                    <div class="flex items-center gap-3">
                                        @if($item->product && $item->product->image)
                                            <div class="h-12 w-12 rounded-xl overflow-hidden shadow-sm">
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                                        </div>
                                        @else
                                            <div class="h-12 w-12 rounded-xl bg-stone-200 dark:bg-stone-700 flex items-center justify-center">
                                                <i data-lucide="package" class="w-6 h-6 text-stone-400"></i>
                                        </div>
                                        @endif
                                        <div>
                                                <h5 class="font-medium text-stone-900 dark:text-white">
                                                {{ $item->product_name }}
                                            </h5>
                                            @if($item->product_data && isset($item->product_data['category']))
                                                <p class="text-sm text-stone-500">{{ $item->product_data['category'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                    <td class="px-4 py-5 dark:border-strokedark">
                                        <p class="text-stone-900 dark:text-white">{{ $item->product_sku ?: 'N/A' }}</p>
                                </td>
                                    <td class="px-4 py-5 dark:border-strokedark">
                                        <p class="text-stone-900 dark:text-white">{{ $item->quantity }}</p>
                                </td>
                                    <td class="px-4 py-5 dark:border-strokedark">
                                        <p class="text-stone-900 dark:text-white">₱{{ number_format($item->unit_price, 2) }}</p>
                                </td>
                                    <td class="px-4 py-5 dark:border-strokedark">
                                        <p class="text-stone-900 dark:text-white font-medium">₱{{ number_format($item->total_price, 2) }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Timeline -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-cyan-50 to-teal-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl">
                            <i data-lucide="clock" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order Timeline</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Track the order's journey from creation to delivery</p>
            </div>
                <div class="p-8">
                <div class="relative">
                        <div class="absolute left-4 top-0 h-full w-0.5 bg-stone-200 dark:bg-stone-700"></div>
                    
                    <!-- Order Created -->
                    <div class="relative flex items-center gap-4 pb-6">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 shadow-lg">
                            <i data-lucide="shopping-cart" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                                <h4 class="font-medium text-stone-900 dark:text-white">Order Created</h4>
                                <p class="text-sm text-stone-500">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    @if($order->status !== 'pending')
                    <!-- Processing -->
                    <div class="relative flex items-center gap-4 pb-6">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 shadow-lg">
                            <i data-lucide="loader" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                                <h4 class="font-medium text-stone-900 dark:text-white">Order Processing</h4>
                                <p class="text-sm text-stone-500">Order is being processed</p>
                        </div>
                    </div>
                    @endif

                    @if($order->shipped_at)
                    <!-- Shipped -->
                    <div class="relative flex items-center gap-4 pb-6">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-500 shadow-lg">
                            <i data-lucide="truck" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                                <h4 class="font-medium text-stone-900 dark:text-white">Order Shipped</h4>
                                <p class="text-sm text-stone-500">{{ $order->shipped_at->format('M d, Y \a\t g:i A') }}</p>
                            @if($order->tracking_number)
                                <p class="text-sm text-emerald-600 dark:text-emerald-400">Tracking: {{ $order->tracking_number }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($order->delivered_at)
                    <!-- Delivered -->
                    <div class="relative flex items-center gap-4">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 shadow-lg">
                            <i data-lucide="check-circle" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                                <h4 class="font-medium text-stone-900 dark:text-white">Order Delivered</h4>
                                <p class="text-sm text-stone-500">{{ $order->delivered_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($order->status === 'cancelled')
                    <!-- Cancelled -->
                    <div class="relative flex items-center gap-4">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 shadow-lg">
                            <i data-lucide="x-circle" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                                <h4 class="font-medium text-stone-900 dark:text-white">Order Cancelled</h4>
                                <p class="text-sm text-stone-500">Order has been cancelled</p>
                            </div>
                        </div>
                    @endif
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
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Customer details and contact information</p>
            </div>
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center shadow-lg">
                            <span class="text-white font-medium text-sm">
                            @if($order->user)
                                {{ substr($order->user->first_name, 0, 1) }}{{ substr($order->user->last_name, 0, 1) }}
                            @else
                                GU
                            @endif
                        </span>
                    </div>
                    <div>
                            <h4 class="font-medium text-stone-900 dark:text-white">
                            @if($order->user)
                                {{ $order->user->first_name }} {{ $order->user->last_name }}
                            @else
                                Guest User
                            @endif
                        </h4>
                            <p class="text-sm text-stone-500">
                            @if($order->user)
                                {{ $order->user->email }}
                            @else
                                No email available
                            @endif
                        </p>
                        @if($order->user && $order->user->phone)
                            <p class="text-sm text-stone-500">
                            Phone: {{ $order->user->phone }}
                        </p>
                        @endif
                    </div>
                </div>
                
                @if($order->user)
                        <a href="{{ admin_route('users.show', $order->user) }}" class="w-full inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 px-4 py-3 text-center font-medium text-white hover:from-amber-600 hover:to-yellow-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                        View Customer
                    </a>
                @else
                        <div class="w-full inline-flex items-center justify-center rounded-xl border border-stone-300 px-4 py-3 text-center font-medium text-stone-500 cursor-not-allowed">
                        <i data-lucide="user-x" class="w-4 h-4 mr-2"></i>
                        Guest Order
                    </div>
                @endif
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
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Financial breakdown and order details</p>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                    <div class="flex justify-between">
                            <span class="text-stone-900 dark:text-white">Subtotal:</span>
                            <span class="text-stone-900 dark:text-white">₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                        <div class="flex justify-between">
                            <span class="text-stone-900 dark:text-white">Tax:</span>
                            <span class="text-stone-900 dark:text-white">₱{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-stone-900 dark:text-white">Shipping:</span>
                            <span class="text-stone-900 dark:text-white">₱{{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                        </div>
                        <div class="border-t border-stone-200 pt-4 dark:border-strokedark">
                            <div class="flex justify-between">
                                <span class="font-semibold text-stone-900 dark:text-white">Total:</span>
                                <span class="font-semibold text-stone-900 dark:text-white">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                    <div class="mt-6 pt-6 border-t border-stone-200 dark:border-strokedark">
                        <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                                <span class="text-stone-500">Payment Method:</span>
                                <span class="text-stone-900 dark:text-white">{{ ucfirst($order->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                                <span class="text-stone-500">Order Date:</span>
                                <span class="text-stone-900 dark:text-white">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($order->tracking_number)
                        <div class="flex justify-between text-sm">
                                <span class="text-stone-500">Tracking:</span>
                                <span class="text-stone-900 dark:text-white">{{ $order->tracking_number }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Addresses -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-lime-50 to-green-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-lime-500 to-green-600 rounded-xl">
                            <i data-lucide="map-pin" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Addresses</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Billing and shipping addresses</p>
            </div>
                <div class="p-8">
                <!-- Billing Address -->
                <div class="mb-6">
                        <h4 class="font-medium text-stone-900 dark:text-white mb-3">Billing Address</h4>
                        <div class="text-sm text-stone-600 dark:text-stone-400 bg-stone-50 dark:bg-stone-800 rounded-xl p-4">
                        @if($order->billing_address && !empty($order->billing_address))
                            <p>{{ $order->billing_address['name'] ?? '' }}</p>
                            <p>{{ $order->billing_address['address_line_1'] ?? '' }}</p>
                            @if(isset($order->billing_address['address_line_2']) && $order->billing_address['address_line_2'])
                            <p>{{ $order->billing_address['address_line_2'] }}</p>
                            @endif
                            <p>{{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['postal_code'] ?? '' }}</p>
                            <p>{{ $order->billing_address['country'] ?? '' }}</p>
                        @elseif($order->user)
                            @php
                                // Philippine address format: street, barangay, city, province, region, zip_code
                                $addressParts = [];
                                if($order->user->street) $addressParts[] = $order->user->street;
                                if($order->user->barangay) $addressParts[] = $order->user->barangay;
                                if($order->user->city) $addressParts[] = $order->user->city;
                                if($order->user->province) $addressParts[] = $order->user->province;
                                if($order->user->region) $addressParts[] = $order->user->region;
                                if($order->user->zip_code) $addressParts[] = $order->user->zip_code;
                                $fullAddress = implode(', ', $addressParts);
                            @endphp
                            <p><strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong></p>
                            <p>{{ $fullAddress ?: 'N/A' }}</p>
                            @if($order->user->phone)
                            <p>Phone: {{ $order->user->phone }}</p>
                            @endif
                        @else
                            <p>N/A</p>
                        @endif
                    </div>
                </div>

                <!-- Shipping Address -->
                <div>
                        <h4 class="font-medium text-stone-900 dark:text-white mb-3">Shipping Address</h4>
                        <div class="text-sm text-stone-600 dark:text-stone-400 bg-stone-50 dark:bg-stone-800 rounded-xl p-4">
                        @if($order->shipping_address && !empty($order->shipping_address))
                            <p>{{ $order->shipping_address['name'] ?? '' }}</p>
                            <p>{{ $order->shipping_address['street'] ?? '' }}</p>
                                @if(isset($order->shipping_address['barangay']) && $order->shipping_address['barangay'])
                            <p>{{ $order->shipping_address['barangay'] }}</p>
                            @endif
                            <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['province'] ?? '' }}</p>
                            <p>{{ $order->shipping_address['region'] ?? '' }} {{ $order->shipping_address['zip_code'] ?? '' }}</p>
                        @elseif($order->user)
                            @php
                                // Philippine address format: street, barangay, city, province, region, zip_code
                                $addressParts = [];
                                if($order->user->street) $addressParts[] = $order->user->street;
                                if($order->user->barangay) $addressParts[] = $order->user->barangay;
                                if($order->user->city) $addressParts[] = $order->user->city;
                                if($order->user->province) $addressParts[] = $order->user->province;
                                if($order->user->region) $addressParts[] = $order->user->region;
                                if($order->user->zip_code) $addressParts[] = $order->user->zip_code;
                                $fullAddress = implode(', ', $addressParts);
                            @endphp
                            <p><strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong></p>
                            <p>{{ $fullAddress ?: 'N/A' }}</p>
                            @if($order->user->phone)
                            <p>Phone: {{ $order->user->phone }}</p>
                            @endif
                        @else
                            <p>N/A</p>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 z-99999 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 dark:bg-boxdark shadow-2xl">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl">
                <i data-lucide="credit-card" class="w-5 h-5 text-white"></i>
            </div>
            <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Process Refund</h3>
        </div>
        
        <form action="{{ admin_route('orders.process-refund', $order) }}" method="POST" class="space-y-6">
            @csrf
            <div class="space-y-2">
                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                    Refund Amount
                </label>
                <input type="number" step="0.01" max="{{ $order->total_amount }}" name="refund_amount" value="{{ $order->total_amount }}" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                    Refund Reason
                </label>
                <textarea name="refund_reason" rows="3" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white" placeholder="Enter refund reason..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-red-500 to-pink-600 px-4 py-3 text-white hover:from-red-600 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Process Refund
                </button>
                <button type="button" onclick="closeRefundModal()" class="flex-1 rounded-xl border border-stone-200 px-4 py-3 text-stone-700 hover:bg-stone-50 transition-all duration-200 dark:border-strokedark dark:text-white dark:hover:bg-gray-800">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openRefundModal() {
        document.getElementById('refundModal').classList.remove('hidden');
        document.getElementById('refundModal').classList.add('flex');
    }
    
    function closeRefundModal() {
        document.getElementById('refundModal').classList.add('hidden');
        document.getElementById('refundModal').classList.remove('flex');
    }
    
    lucide.createIcons();
</script>
@endpush