@extends('admin.layouts.app')

@section('title', 'Create Return/Repair Request')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">
            Create Return/Repair Request
        </h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Create a new RMA (Return Merchandise Authorization) request for a customer.
        </p>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.returns-repairs.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Returns & Repairs
        </a>
    </div>
</div>
<!-- Breadcrumb End -->

<div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
    <form action="{{ route('admin.orders.returns-repairs.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Order Selection -->
                <div>
                    <label class="mb-2.5 block text-black dark:text-white">
                        Select Order <span class="text-meta-1">*</span>
                    </label>
                    <select
                        name="order_id"
                        id="order-select"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        required
                    >
                        <option value="">Select an order...</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}" data-customer="{{ $order->user->first_name ?? 'Guest' }} {{ $order->user->last_name ?? '' }}" data-email="{{ $order->user->email ?? 'No email' }}">
                                Order #{{ $order->order_number }} - {{ $order->user->first_name ?? 'Guest' }} {{ $order->user->last_name ?? '' }} ({{ $order->created_at->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')
                        <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Request Type -->
                <div>
                    <label class="mb-2.5 block text-black dark:text-white">
                        Request Type <span class="text-meta-1">*</span>
                    </label>
                    <select
                        name="type"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        required
                    >
                        <option value="">Select request type...</option>
                        <option value="return">Return</option>
                        <option value="exchange">Exchange</option>
                        <option value="repair">Repair</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reason -->
                <div>
                    <label class="mb-2.5 block text-black dark:text-white">
                        Reason <span class="text-meta-1">*</span>
                    </label>
                    <select
                        name="reason"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        required
                    >
                        <option value="">Select reason...</option>
                        <option value="defective">Defective Product</option>
                        <option value="wrong_item">Wrong Item Received</option>
                        <option value="damaged_shipping">Damaged in Shipping</option>
                        <option value="not_as_described">Not as Described</option>
                        <option value="changed_mind">Changed Mind</option>
                        <option value="size_issue">Size Issue</option>
                        <option value="quality_issue">Quality Issue</option>
                        <option value="other">Other</option>
                    </select>
                    @error('reason')
                        <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="mb-2.5 block text-black dark:text-white">
                        Description <span class="text-meta-1">*</span>
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        placeholder="Please provide a detailed description of the issue..."
                        required
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Customer Information -->
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Customer Information</h3>
                    <div id="customer-info" class="space-y-2 text-sm">
                        <p class="text-gray-600 dark:text-gray-400">Select an order to view customer details</p>
                    </div>
                </div>

                <!-- Refund Information -->
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Refund Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Refund Amount (₱)
                            </label>
                            <input
                                type="number"
                                name="refund_amount"
                                step="0.01"
                                min="0"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                placeholder="0.00"
                                value="{{ old('refund_amount') }}"
                            >
                            @error('refund_amount')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Refund Method
                            </label>
                            <select
                                name="refund_method"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                                <option value="">Select refund method...</option>
                                <option value="original_payment">Original Payment Method</option>
                                <option value="store_credit">Store Credit</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="check">Check</option>
                            </select>
                            @error('refund_method')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Admin Notes -->
                <div>
                    <label class="mb-2.5 block text-black dark:text-white">
                        Admin Notes
                    </label>
                    <textarea
                        name="admin_notes"
                        rows="3"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        placeholder="Internal notes (not visible to customer)..."
                    >{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-stone-200 dark:border-strokedark">
            <a href="{{ route('admin.orders.returns-repairs.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-6 py-3 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Create RMA Request
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderSelect = document.getElementById('order-select');
    const customerInfo = document.getElementById('customer-info');

    orderSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const customerName = selectedOption.dataset.customer;
            const customerEmail = selectedOption.dataset.email;
            
            customerInfo.innerHTML = `
                <div class="space-y-2">
                    <p><span class="font-medium text-black dark:text-white">Name:</span> ${customerName}</p>
                    <p><span class="font-medium text-black dark:text-white">Email:</span> ${customerEmail}</p>
                </div>
            `;
        } else {
            customerInfo.innerHTML = '<p class="text-gray-600 dark:text-gray-400">Select an order to view customer details</p>';
        }
    });
});
</script>
@endpush
@endsection
