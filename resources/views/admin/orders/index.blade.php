@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Orders Management
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li class="font-medium text-primary">Orders</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards Start -->
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 mb-6">
    <!-- Total Orders -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <i data-lucide="shopping-bag" class="w-6 h-6 text-primary dark:text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['total_orders']) }}
                </h4>
                <span class="text-sm font-medium">Total Orders</span>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-6 dark:bg-meta-4">
            <i data-lucide="clock" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['pending_orders']) }}
                </h4>
                <span class="text-sm font-medium">Pending Orders</span>
            </div>
        </div>
    </div>

    <!-- Processing Orders -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-5 dark:bg-meta-4">
            <i data-lucide="loader" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['processing_orders']) }}
                </h4>
                <span class="text-sm font-medium">Processing</span>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-3 dark:bg-meta-4">
            <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    ₱{{ number_format($stats['total_revenue'], 2) }}
                </h4>
                <span class="text-sm font-medium">Total Revenue</span>
            </div>
        </div>
    </div>
</div>
<!-- Stats Cards End -->

<!-- Orders Table -->
<div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
    <div class="px-4 py-6 md:px-6 xl:px-7.5">
        <!-- Header with filters and create button in one row -->
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <h4 class="text-xl font-semibold text-black dark:text-white">
                All Orders
            </h4>
            
            <!-- Filters and Create Order Button -->
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:gap-4">
                <form method="GET" class="flex flex-col gap-4 lg:flex-row lg:items-center lg:gap-4">
                    <div class="w-full lg:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..." class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                    </div>
                    
                    <div class="w-full lg:w-40">
                        <select name="status" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                            <option value="all">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                    </div>
                    
                    <div class="w-full lg:w-48">
                        <select name="payment_status" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                            <option value="all">All Payment Status</option>
                            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-3 text-center font-medium text-white hover:bg-opacity-90">
                            <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                            Filter
                        </button>
                        <a href="{{ admin_route('orders.index') }}" class="inline-flex items-center justify-center rounded-md border border-stroke px-4 py-3 text-center font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4">
                            <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                            Clear
                        </a>
                    </div>
                </form>
                
                <a href="{{ admin_route('orders.create') }}" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white hover:bg-opacity-90 whitespace-nowrap">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Create Order
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-7 border-t border-stroke px-4 py-4.5 dark:border-strokedark md:px-6 2xl:px-7.5">
        <div class="col-span-1 flex items-center">
            <p class="font-medium">Order</p>
        </div>
        <div class="col-span-1 hidden items-center sm:flex">
            <p class="font-medium">Customer</p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="font-medium">Status</p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="font-medium">Payment</p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="font-medium">Total</p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="font-medium">Date</p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="font-medium">Actions</p>
        </div>
    </div>

    @forelse($orders as $order)
    <div class="grid grid-cols-7 border-t border-stroke px-4 py-4.5 dark:border-strokedark md:px-6 2xl:px-7.5">
        <div class="col-span-1 flex items-center">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center">
                <p class="text-sm text-black dark:text-white font-medium">
                    {{ $order->order_number }}
                </p>
            </div>
        </div>
        <div class="col-span-1 hidden items-center sm:flex">
            <p class="text-sm text-black dark:text-white">
                @if($order->user)
                    {{ $order->user->first_name }} {{ $order->user->last_name }}
                @else
                    <span class="text-gray-500 dark:text-gray-400">Guest User</span>
                @endif
            </p>
        </div>
        <div class="col-span-1 flex items-center">
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
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
        <div class="col-span-1 flex items-center">
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
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
        <div class="col-span-1 flex items-center">
            <p class="text-sm text-black dark:text-white font-medium">
                ₱{{ number_format($order->total_amount, 2) }}
            </p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="text-sm text-black dark:text-white">
                {{ $order->created_at->format('M d, Y') }}
            </p>
        </div>
        <div class="col-span-1 flex items-center">
            <div class="relative flex items-center justify-center" x-data="{ dropdownOpen: false }">
                <button @click="dropdownOpen = !dropdownOpen" class="hover:text-primary">
                    <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                </button>
                
                <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" class="absolute left-full top-1/2 transform -translate-y-1/2 ml-2 z-40 w-40 space-y-1 rounded-sm border border-stroke bg-white p-1.5 shadow-default dark:border-strokedark dark:bg-boxdark" x-cloak>
                    <a href="{{ admin_route('orders.show', $order) }}" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        View
                    </a>
                    <a href="{{ admin_route('orders.edit', $order) }}" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Edit
                    </a>
                    @if($order->status !== 'cancelled')
                    <form action="{{ admin_route('orders.update-status', $order) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4 text-red-600" onclick="return confirm('Are you sure you want to cancel this order?')">
                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                            Cancel
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="px-4 py-8 text-center">
        <i data-lucide="shopping-bag" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
        <p class="text-gray-500 dark:text-gray-400">No orders found.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($orders->hasPages())
<div class="mt-6">
    {{ $orders->links() }}
</div>
@endif

@endsection

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
