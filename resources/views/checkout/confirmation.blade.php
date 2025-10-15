<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - David's Wood Furniture</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/assets/favicon.png') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('frontend/style.css') }}">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-[#8b7355]">
                        David's Wood Furniture
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('account') }}" class="text-[#8b7355] hover:text-[#6b5b47] font-medium">
                        My Account
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success Message -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <i data-lucide="check" class="h-8 w-8 text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
            <p class="text-lg text-gray-600">Thank you for your order. We'll send you a confirmation email shortly.</p>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Order Details</h2>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Order Number</p>
                    <p class="text-lg font-bold text-[#8b7355]">{{ $order->order_number }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Shipping Address</h3>
                    <div class="text-sm text-gray-600">
                        <p class="font-medium text-gray-900">{{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}</p>
                        <p>{{ $order->shipping_address['address_line_1'] }}</p>
                        @if($order->shipping_address['address_line_2'])
                            <p>{{ $order->shipping_address['address_line_2'] }}</p>
                        @endif
                        <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['province'] }} {{ $order->shipping_address['zip_code'] }}</p>
                        <p>{{ $order->shipping_address['region'] }}</p>
                        <p class="mt-2">{{ $order->shipping_address['phone'] }}</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Payment Method</h3>
                    <div class="text-sm text-gray-600">
                        <p class="font-medium text-gray-900">{{ $order->payment_method }}</p>
                        <p class="mt-2">Status: <span class="font-medium text-orange-600">{{ ucfirst($order->payment_status) }}</span></p>
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            @if($item->product && $item->product->images)
                                @php
                                    $images = is_string($item->product->images) ? json_decode($item->product->images, true) : $item->product->images;
                                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                                @endphp
                                @if($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i data-lucide="package" class="w-8 h-8 text-gray-400"></i>
                                @endif
                            @else
                                <i data-lucide="package" class="w-8 h-8 text-gray-400"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-medium text-gray-900">{{ $item->product_name }}</h4>
                            <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                            <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-gray-900">₱{{ number_format($item->total_price, 2) }}</p>
                            <p class="text-sm text-gray-600">₱{{ number_format($item->unit_price, 2) }} each</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="border-t pt-6 mt-6">
                <div class="flex justify-end">
                    <div class="w-full max-w-sm">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900">₱{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="text-gray-900">
                                    @if($order->shipping_cost == 0)
                                        <span class="text-green-600">Free</span>
                                    @else
                                        ₱{{ number_format($order->shipping_cost, 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">VAT (12%)</span>
                                <span class="text-gray-900">₱{{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold border-t pt-2">
                                <span class="text-gray-900">Total</span>
                                <span class="text-[#8b7355]">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-[#8b7355] text-white">
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-900">Order Placed</span>
                </div>
                <div class="flex-1 h-px bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500">
                        <i data-lucide="package" class="w-4 h-4"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Processing</span>
                </div>
                <div class="flex-1 h-px bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500">
                        <i data-lucide="truck" class="w-4 h-4"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Shipped</span>
                </div>
                <div class="flex-1 h-px bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500">
                        <i data-lucide="home" class="w-4 h-4"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Delivered</span>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">What's Next?</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start">
                    <i data-lucide="mail" class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>You'll receive an order confirmation email shortly</span>
                </li>
                <li class="flex items-start">
                    <i data-lucide="clock" class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>We'll process your order within 1-2 business days</span>
                </li>
                <li class="flex items-start">
                    <i data-lucide="truck" class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>You'll receive tracking information once your order ships</span>
                </li>
                <li class="flex items-start">
                    <i data-lucide="phone" class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>Contact us if you have any questions about your order</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('account.receipt', $order->order_number) }}" 
               class="bg-[#8b7355] text-white px-6 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold text-center">
                View Receipt
            </a>
            <a href="{{ route('products') }}" 
               class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold text-center">
                Continue Shopping
            </a>
            <a href="{{ route('account') }}" 
               class="border border-[#8b7355] text-[#8b7355] px-6 py-3 rounded-lg hover:bg-[#8b7355] hover:text-white transition-colors font-semibold text-center">
                My Orders
            </a>
        </div>
    </main>

    <!-- Scripts -->
    <script src="{{ asset('frontend/js/app.js') }}"></script>
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
