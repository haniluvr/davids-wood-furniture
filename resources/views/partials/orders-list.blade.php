<style>
/* DaisyUI Steps Component Recreation */
.steps {
    display: flex;
    width: 100%;
    counter-reset: step;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center;
    justify-content: space-between;
}

.steps-horizontal {
    flex-direction: row;
    align-items: center;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
    position: relative;
    color: #a3a3a3; /* Default gray text */
    min-height: 4rem;
}

.step + .step:before {
    content: "";
    position: absolute;
    top: 1rem;
    left: -50%;
    right: 50%;
    height: 2px;
    background-color: #e5e7eb; /* Default gray line */
    z-index: 0;
}

/* Custom icon styling */
.step .step-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background-color: #e5e7eb;
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 600;
    position: relative;
    z-index: 2;
    margin-bottom: 0.5rem;
}

/* Step text */
.step > span {
    font-size: 0.75rem;
    font-weight: 500;
    margin-top: 0.25rem;
}

/* Active/Primary step styling */
.step-primary {
    color: #374151 !important;
}

.step-primary + .step-primary:before {
    background-color: #8b7355 !important;
}

.step-primary .step-icon {
    background-color: #8b7355 !important;
    color: white !important;
}

/* Responsive */
@media (max-width: 640px) {
    .step .step-icon {
        width: 1.5rem;
        height: 1.5rem;
        font-size: 0.75rem;
    }
    
    .step + .step:before {
        top: 0.75rem;
    }
}
</style>

@if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-semibold text-gray-900 text-lg mb-1">Order #{{ $order->order_number }}</h3>
                    <p class="text-[#8b7355] text-sm font-medium">Placed on {{ $order->created_at->format('M j, Y') }}</p>
                </div>
                <div class="text-right">
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-blue-100 text-blue-800', 
                            'shipped' => 'bg-purple-100 text-purple-800',
                            'delivered' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800'
                        ];
                    @endphp
                    <span class="inline-block px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }} capitalize">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center text-gray-600">
                    <div class="w-10 h-10 bg-[#f4f1eb] rounded-lg flex items-center justify-center mr-4">
                        <i data-lucide="package" class="text-[#8b7355] w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">
                            @if($order->orderItems->count() == 1)
                                {{ $order->orderItems->first()->product_name }}
                            @else
                                {{ $order->orderItems->first()->product_name }}
                            @endif
                        </p>
                        <p class="text-sm text-gray-500">{{ $order->orderItems->count() }} {{ $order->orderItems->count() == 1 ? 'item' : 'items' }}</p>
                    </div>
                </div>
                            <div class="text-right">
                                <p class="font-bold text-lg text-gray-900">₱{{ number_format($order->total_amount, 2) }}</p>
                                <button onclick="toggleOrderDetails('order-{{ $order->id }}')" class="text-[#8b7355] hover:text-[#6b5b47] font-medium text-sm transition-colors">
                                    <span class="view-details-text">View Details</span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 inline-block ml-1 transition-transform duration-200 chevron-icon"></i>
                                </button>
                            </div>
            </div>
            
            <!-- Order Details Accordion -->
            <div id="order-{{ $order->id }}-details" class="hidden mt-4 pt-4 border-t border-gray-200">
                <!-- Order Tracking Steps - Only show for non-cancelled orders -->
                @if($order->status !== 'cancelled')
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Order Status</h4>
                        <ul class="steps steps-horizontal w-full">
                            @php
                                $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                                $currentStatusIndex = array_search($order->status, $statuses);
                            @endphp
                            
                            @foreach($statuses as $index => $status)
                                @php
                                    $stepClass = 'step';
                                    
                                    // Color the current step and all previous steps using DaisyUI's step-primary class
                                    if ($currentStatusIndex !== false && $index <= $currentStatusIndex) {
                                        $stepClass .= ' step-primary';
                                    }
                                @endphp
                                <li class="{{ $stepClass }}">
                                    <div class="step-icon">
                                        @if($status === 'pending')
                                            <i data-lucide="clock" class="w-4 h-4"></i>
                                        @elseif($status === 'processing')
                                            <i data-lucide="package" class="w-4 h-4"></i>
                                        @elseif($status === 'shipped')
                                            <i data-lucide="truck" class="w-4 h-4"></i>
                                        @elseif($status === 'delivered')
                                            <i data-lucide="home" class="w-4 h-4"></i>
                                        @endif
                                    </div>
                                    <span class="text-xs font-medium capitalize">{{ $status }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Tracking Information -->
                @if(in_array($order->status, ['shipped', 'delivered']))
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-2">Tracking Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Tracking Number:</span>
                                <span class="font-medium ml-2">{{ $order->tracking_number ?? 'SH-' . $order->order_number . '-' . rand(1000, 9999) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Carrier:</span>
                                <span class="font-medium ml-2">{{ $order->shipping_method ?? 'FedEx Express' }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5 class="font-medium text-gray-900 mb-2">Delivery Information</h5>
                            @php
                                $shippingAddress = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                            @endphp
                            <p class="text-sm text-gray-600">
                                {{ $shippingAddress['street'] ?? '' }}, {{ $shippingAddress['city'] ?? '' }}, {{ $shippingAddress['province'] ?? '' }} {{ $shippingAddress['zip_code'] ?? '' }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Estimated Delivery: 
                                @if($order->status === 'delivered' && $order->delivered_at)
                                    <span class="text-green-600 font-medium">Delivered on {{ $order->delivered_at->format('M j, Y') }}</span>
                                @elseif($order->shipped_at)
                                    <span class="font-medium">{{ $order->shipped_at->addDays(3)->format('M j, Y') }} by 5:00 PM</span>
                                @else
                                    <span class="font-medium">{{ now()->addDays(5)->format('M j, Y') }} by 5:00 PM</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Order Items -->
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-900 mb-4">Order Items</h4>
                    <div class="space-y-3">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-3">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="w-10 h-10 object-cover rounded">
                                        @else
                                            <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                        <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">₱{{ number_format($item->total_price, 2) }}</p>
                                    <p class="text-sm text-gray-600">₱{{ number_format($item->unit_price, 2) }} each</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="border-t pt-4">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax:</span>
                            <span class="font-medium">₱{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping:</span>
                            <span class="font-medium">₱{{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-2 font-bold text-lg">
                            <span>Total:</span>
                            <span>₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="mt-8 flex justify-center">
            <nav class="flex items-center space-x-1" id="orders-pagination">
                {{-- Previous Page Link --}}
                @if ($orders->onFirstPage())
                    <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </span>
                @else
                    <button onclick="loadOrdersPage({{ $orders->currentPage() - 1 }})" class="px-3 py-2 text-sm text-gray-600 hover:text-[#8b7355] hover:bg-gray-50 rounded-md transition-colors">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if ($page == $orders->currentPage())
                        <span class="px-3 py-2 text-sm bg-[#8b7355] text-white rounded-md font-medium">{{ $page }}</span>
                    @else
                        <button onclick="loadOrdersPage({{ $page }})" class="px-3 py-2 text-sm text-gray-600 hover:text-[#8b7355] hover:bg-gray-50 rounded-md transition-colors">{{ $page }}</button>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($orders->hasMorePages())
                    <button onclick="loadOrdersPage({{ $orders->currentPage() + 1 }})" class="px-3 py-2 text-sm text-gray-600 hover:text-[#8b7355] hover:bg-gray-50 rounded-md transition-colors">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                @else
                    <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </span>
                @endif
            </nav>
        </div>
    @endif
@else
    <div class="text-center py-12">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="package" class="text-gray-400 w-8 h-8"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
        <p class="text-gray-600 mb-4">Start shopping to see your orders here</p>
        <a href="{{ route('products') }}" class="bg-[#8b7355] text-white px-6 py-2 rounded-lg hover:bg-[#6b5b47] transition-colors">
            Browse Products
        </a>
    </div>
@endif
