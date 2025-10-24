@extends('admin.layouts.app')

@section('title', 'Edit Order')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Edit Order - {{ $order->order_number }}
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ admin_route('orders.index') }}">Orders /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ admin_route('orders.show', $order) }}">{{ $order->order_number }} /</a>
            </li>
            <li class="font-medium text-primary">Edit</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<form action="{{ admin_route('orders.update', $order) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Order Status -->
            <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark mb-6">
                <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                    <h3 class="font-medium text-black dark:text-white">
                        Order Status
                    </h3>
                </div>
                <div class="p-6.5">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Order Status <span class="text-meta-1">*</span>
                            </label>
                            <select name="status" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="returned" {{ $order->status === 'returned' ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Payment Status <span class="text-meta-1">*</span>
                            </label>
                            <select name="payment_status" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Shipping Method
                            </label>
                            <input type="text" name="shipping_method" value="{{ $order->shipping_method }}" placeholder="e.g., Standard, Express, Free" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Tracking Number
                            </label>
                            <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Enter tracking number" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Address -->
            <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark mb-6">
                <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                    <h3 class="font-medium text-black dark:text-white">
                        Billing Address
                    </h3>
                </div>
                <div class="p-6.5">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="mb-2.5 block text-black dark:text-white">
                                Full Name <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="billing_address[name]" value="{{ $order->billing_address['name'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="mb-2.5 block text-black dark:text-white">
                                Address Line 1 <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="billing_address[address_line_1]" value="{{ $order->billing_address['address_line_1'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="mb-2.5 block text-black dark:text-white">
                                Address Line 2
                            </label>
                            <input type="text" name="billing_address[address_line_2]" value="{{ $order->billing_address['address_line_2'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                City <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="billing_address[city]" value="{{ $order->billing_address['city'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                State/Province <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="billing_address[state]" value="{{ $order->billing_address['state'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Postal Code <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="billing_address[postal_code]" value="{{ $order->billing_address['postal_code'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Country <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="billing_address[country]" value="{{ $order->billing_address['country'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark mb-6">
                <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                    <h3 class="font-medium text-black dark:text-white">
                        Shipping Address
                    </h3>
                </div>
                <div class="p-6.5">
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="sameAsBilling" class="mr-2">
                            <span class="text-black dark:text-white">Same as billing address</span>
                        </label>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="mb-2.5 block text-black dark:text-white">
                                Full Name <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="shipping_address[name]" value="{{ $order->shipping_address['name'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="mb-2.5 block text-black dark:text-white">
                                Address Line 1 <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="shipping_address[address_line_1]" value="{{ $order->shipping_address['address_line_1'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="mb-2.5 block text-black dark:text-white">
                                Address Line 2
                            </label>
                            <input type="text" name="shipping_address[address_line_2]" value="{{ $order->shipping_address['address_line_2'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                City <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="shipping_address[city]" value="{{ $order->shipping_address['city'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                State/Province <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="shipping_address[state]" value="{{ $order->shipping_address['state'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Postal Code <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="shipping_address[postal_code]" value="{{ $order->shipping_address['postal_code'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Country <span class="text-meta-1">*</span>
                            </label>
                            <input type="text" name="shipping_address[country]" value="{{ $order->shipping_address['country'] ?? '' }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                    <h3 class="font-medium text-black dark:text-white">
                        Order Notes
                    </h3>
                </div>
                <div class="p-6.5">
                    <textarea name="notes" rows="4" placeholder="Add any notes about this order..." class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">{{ $order->notes }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
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
                            <span class="text-black dark:text-white">Order Number:</span>
                            <span class="text-black dark:text-white font-medium">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-black dark:text-white">Subtotal:</span>
                            <span class="text-black dark:text-white">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-black dark:text-white">Tax:</span>
                            <span class="text-black dark:text-white">${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-black dark:text-white">Shipping:</span>
                            <span class="text-black dark:text-white">${{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                        <div class="border-t border-stroke pt-3 dark:border-strokedark">
                            <div class="flex justify-between">
                                <span class="font-medium text-black dark:text-white">Total:</span>
                                <span class="font-medium text-black dark:text-white">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark mb-6">
                <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                    <h3 class="font-medium text-black dark:text-white">
                        Customer
                    </h3>
                </div>
                <div class="p-6.5">
                    <div class="flex items-center gap-3">
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="p-6.5">
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white hover:bg-opacity-90">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                            Update Order
                        </button>
                        
                        <a href="{{ admin_route('orders.show', $order) }}" class="inline-flex items-center justify-center rounded-md border border-stroke px-4 py-2 text-center font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4">
                            <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    // Same as billing address functionality
    document.getElementById('sameAsBilling').addEventListener('change', function() {
        const billingInputs = document.querySelectorAll('input[name^="billing_address"]');
        const shippingInputs = document.querySelectorAll('input[name^="shipping_address"]');
        
        if (this.checked) {
            billingInputs.forEach((input, index) => {
                if (shippingInputs[index]) {
                    shippingInputs[index].value = input.value;
                }
            });
        }
    });
    
    lucide.createIcons();
</script>
@endpush
