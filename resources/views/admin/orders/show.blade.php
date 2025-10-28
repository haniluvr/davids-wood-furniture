@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Order Details - {{ $order->order_number }}
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ admin_route('orders.index') }}">Orders /</a>
            </li>
            <li class="font-medium text-primary">{{ $order->order_number }}</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Order Information -->
    <div class="lg:col-span-2">
        <!-- Order Status Card -->
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark mb-6">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">
                    Order Status & Actions
                </h3>
            </div>
            <div class="p-6.5">
                <!-- Status Display and Update Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Current Status Display -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Current Status</h4>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-black dark:text-white">Status:</span>
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
                            <span class="text-sm font-medium text-black dark:text-white">Payment:</span>
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
                        
                        <!-- Fulfillment Progress Bar -->
                        @if($order->status !== 'cancelled')
                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Fulfillment Progress</h4>
                                <div class="space-y-3">
                                    <!-- Progress Bar -->
                                    <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
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
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $order->status === 'pending' ? 'bg-emerald-500 text-white' : ($order->status === 'processing' || $order->status === 'shipped' || $order->status === 'delivered' ? 'bg-emerald-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-600 dark:text-gray-400 {{ $order->status === 'pending' ? 'font-medium text-emerald-600' : '' }}">Order Placed</span>
                                        </div>
                                        <div class="flex flex-col items-center space-y-1">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $order->status === 'processing' ? 'bg-emerald-500 text-white' : ($order->status === 'shipped' || $order->status === 'delivered' ? 'bg-emerald-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-600 dark:text-gray-400 {{ $order->status === 'processing' ? 'font-medium text-emerald-600' : '' }}">Processing</span>
                                        </div>
                                        <div class="flex flex-col items-center space-y-1">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $order->status === 'shipped' ? 'bg-emerald-500 text-white' : ($order->status === 'delivered' ? 'bg-emerald-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-600 dark:text-gray-400 {{ $order->status === 'shipped' ? 'font-medium text-emerald-600' : '' }}">Shipped</span>
                                        </div>
                                        <div class="flex flex-col items-center space-y-1">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $order->status === 'delivered' ? 'bg-emerald-500 text-white' : 'bg-gray-300 text-gray-600' }}">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-600 dark:text-gray-400 {{ $order->status === 'delivered' ? 'font-medium text-emerald-600' : '' }}">Delivered</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Status Update Form -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Update Status</h4>
                        <form action="{{ admin_route('orders.update-status', $order) }}" method="POST" class="flex items-end gap-3">
                            @csrf
                            @method('PATCH')
                            <div class="flex-1">
                                <select name="status" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-4 py-2.5 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="returned" {{ $order->status === 'returned' ? 'selected' : '' }}>Returned</option>
                                </select>
                            </div>
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2.5 text-center font-medium text-white hover:bg-opacity-90 whitespace-nowrap">
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t border-stroke pt-6 dark:border-strokedark">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Actions</h4>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ admin_route('orders.edit', $order) }}" class="inline-flex items-center justify-center rounded-md border border-primary px-4 py-2 text-center font-medium text-primary hover:bg-opacity-90">
                            <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                            Edit Order
                        </a>
                        
                        <a href="{{ admin_route('orders.download-invoice', $order) }}" class="inline-flex items-center justify-center rounded-md border border-stroke px-4 py-2 text-center font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4">
                            <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                            Download Invoice
                        </a>
                        
                        @if($order->payment_status === 'paid' && in_array($order->status, ['delivered', 'cancelled']))
                        <button onclick="openRefundModal()" class="inline-flex items-center justify-center rounded-md border border-red-300 px-4 py-2 text-center font-medium text-red-600 hover:bg-red-50 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                            <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i>
                            Process Refund
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark mb-6">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">
                    Order Items
                </h3>
            </div>
            <div class="p-6.5">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                <th class="min-w-[220px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11">
                                    Product
                                </th>
                                <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">
                                    SKU
                                </th>
                                <th class="min-w-[120px] px-4 py-4 font-medium text-black dark:text-white">
                                    Quantity
                                </th>
                                <th class="min-w-[120px] px-4 py-4 font-medium text-black dark:text-white">
                                    Unit Price
                                </th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                                    <div class="flex items-center gap-3">
                                        @if($item->product && $item->product->image)
                                        <div class="h-12 w-12 rounded-md overflow-hidden">
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                                        </div>
                                        @else
                                        <div class="h-12 w-12 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <h5 class="font-medium text-black dark:text-white">
                                                {{ $item->product_name }}
                                            </h5>
                                            @if($item->product_data && isset($item->product_data['category']))
                                            <p class="text-sm text-gray-500">{{ $item->product_data['category'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ $item->product_sku ?: 'N/A' }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ $item->quantity }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">₱{{ number_format($item->unit_price, 2) }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white font-medium">₱{{ number_format($item->total_price, 2) }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">
                    Order Timeline
                </h3>
            </div>
            <div class="p-6.5">
                <div class="relative">
                    <div class="absolute left-4 top-0 h-full w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                    
                    <!-- Order Created -->
                    <div class="relative flex items-center gap-4 pb-6">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary">
                            <i data-lucide="shopping-cart" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-black dark:text-white">Order Created</h4>
                            <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    @if($order->status !== 'pending')
                    <!-- Processing -->
                    <div class="relative flex items-center gap-4 pb-6">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500">
                            <i data-lucide="loader" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-black dark:text-white">Order Processing</h4>
                            <p class="text-sm text-gray-500">Order is being processed</p>
                        </div>
                    </div>
                    @endif

                    @if($order->shipped_at)
                    <!-- Shipped -->
                    <div class="relative flex items-center gap-4 pb-6">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-500">
                            <i data-lucide="truck" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-black dark:text-white">Order Shipped</h4>
                            <p class="text-sm text-gray-500">{{ $order->shipped_at->format('M d, Y \a\t g:i A') }}</p>
                            @if($order->tracking_number)
                            <p class="text-sm text-primary">Tracking: {{ $order->tracking_number }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($order->delivered_at)
                    <!-- Delivered -->
                    <div class="relative flex items-center gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500">
                            <i data-lucide="check-circle" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-black dark:text-white">Order Delivered</h4>
                            <p class="text-sm text-gray-500">{{ $order->delivered_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($order->status === 'cancelled')
                    <!-- Cancelled -->
                    <div class="relative flex items-center gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500">
                            <i data-lucide="x-circle" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-black dark:text-white">Order Cancelled</h4>
                            <p class="text-sm text-gray-500">Order has been cancelled</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Customer Information -->
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark mb-6">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">
                    Customer Information
                </h3>
            </div>
            <div class="p-6.5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-12 w-12 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">
                            @if($order->user)
                                {{ substr($order->user->first_name, 0, 1) }}{{ substr($order->user->last_name, 0, 1) }}
                            @else
                                GU
                            @endif
                        </span>
                    </div>
                    <div>
                        <h4 class="font-medium text-black dark:text-white">
                            @if($order->user)
                                {{ $order->user->first_name }} {{ $order->user->last_name }}
                            @else
                                Guest User
                            @endif
                        </h4>
                        <p class="text-sm text-gray-500">
                            @if($order->user)
                                {{ $order->user->email }}
                            @else
                                No email available
                            @endif
                        </p>
                        @if($order->user && $order->user->phone)
                        <p class="text-sm text-gray-500">
                            Phone: {{ $order->user->phone }}
                        </p>
                        @endif
                    </div>
                </div>
                
                @if($order->user)
                    <a href="{{ admin_route('users.show', $order->user) }}" class="inline-flex items-center justify-center rounded-md border border-primary px-4 py-2 text-center font-medium text-primary hover:bg-opacity-90 w-full">
                        <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                        View Customer
                    </a>
                @else
                    <div class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-center font-medium text-gray-500 w-full cursor-not-allowed">
                        <i data-lucide="user-x" class="w-4 h-4 mr-2"></i>
                        Guest Order
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Summary -->
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark mb-6">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">
                    Order Summary
                </h3>
            </div>
            <div class="p-6.5">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-black dark:text-white">Subtotal:</span>
                        <span class="text-black dark:text-white">₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-black dark:text-white">Tax:</span>
                        <span class="text-black dark:text-white">₱{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                        <div class="flex justify-between">
                            <span class="text-black dark:text-white">Shipping:</span>
                            <span class="text-black dark:text-white">₱{{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                        </div>
                    <div class="border-t border-stroke pt-3 dark:border-strokedark">
                        <div class="flex justify-between">
                            <span class="font-medium text-black dark:text-white">Total:</span>
                            <span class="font-medium text-black dark:text-white">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-stroke dark:border-strokedark">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Payment Method:</span>
                            <span class="text-black dark:text-white">{{ ucfirst($order->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Order Date:</span>
                            <span class="text-black dark:text-white">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($order->tracking_number)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Tracking:</span>
                            <span class="text-black dark:text-white">{{ $order->tracking_number }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">
                    Addresses
                </h3>
            </div>
            <div class="p-6.5">
                <!-- Billing Address -->
                <div class="mb-6">
                    <h4 class="font-medium text-black dark:text-white mb-2">Billing Address</h4>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
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
                    <h4 class="font-medium text-black dark:text-white mb-2">Shipping Address</h4>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        @if($order->shipping_address && !empty($order->shipping_address))
                            <p>{{ $order->shipping_address['name'] ?? '' }}</p>
                            <p>{{ $order->shipping_address['street'] ?? '' }}</p>
                            @if($order->shipping_address['barangay'])
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

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 z-99999 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="w-full max-w-md rounded-lg bg-white p-6 dark:bg-boxdark">
        <h3 class="mb-4 text-lg font-medium text-black dark:text-white">Process Refund</h3>
        
        <form action="{{ admin_route('orders.process-refund', $order) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="mb-2.5 block text-black dark:text-white">
                    Refund Amount
                </label>
                <input type="number" step="0.01" max="{{ $order->total_amount }}" name="refund_amount" value="{{ $order->total_amount }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
            </div>
            
            <div class="mb-4">
                <label class="mb-2.5 block text-black dark:text-white">
                    Refund Reason
                </label>
                <textarea name="refund_reason" rows="3" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" placeholder="Enter refund reason..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 rounded bg-primary px-4 py-2 text-white hover:bg-opacity-90">
                    Process Refund
                </button>
                <button type="button" onclick="closeRefundModal()" class="flex-1 rounded border border-stroke px-4 py-2 text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4">
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
