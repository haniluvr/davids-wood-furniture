<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_number }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/assets/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
        
        @page {
            size: A4;
            margin: 20mm;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-8">
        <!-- Action Buttons (Don't print) -->
        <div class="no-print mb-6 flex gap-4 justify-end">
            <button onclick="window.print()" class="bg-[#8b7355] text-white px-6 py-2 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Download/Print Receipt
            </button>
            <button onclick="window.close()" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                Close
            </button>
        </div>

        <!-- Receipt Container -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-[#8b7355] text-white p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">RECEIPT</h1>
                        <p class="text-lg opacity-90">David's Wood Furniture</p>
                        <p class="text-sm opacity-75">Handcrafted Furniture with Timeless Design</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white text-[#8b7355] px-4 py-2 rounded-lg inline-block">
                            <p class="text-xs font-semibold">PAID</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="p-8 border-b border-gray-200">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-3">ORDER DETAILS</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-600">Order Number:</span>
                                <p class="font-bold text-lg">{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Order Date:</span>
                                <p class="font-semibold">{{ $order->created_at->format('F j, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Payment Date:</span>
                                <p class="font-semibold">{{ $order->updated_at->format('F j, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Payment Method:</span>
                                <p class="font-semibold capitalize">{{ $order->payment_method ?? 'N/A' }}</p>
                            </div>
                            @if($order->tracking_number)
                            <div>
                                <span class="text-sm text-gray-600">Tracking Number:</span>
                                <p class="font-semibold">{{ $order->tracking_number }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-3">CUSTOMER DETAILS</h3>
                        <div class="space-y-2">
                            <div>
                                <p class="font-semibold">{{ $user->first_name }} {{ $user->last_name }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Email:</span>
                                <p class="font-semibold">{{ $user->email }}</p>
                            </div>
                            @if($user->phone)
                            <div>
                                <span class="text-sm text-gray-600">Phone:</span>
                                <p class="font-semibold">{{ $user->phone }}</p>
                            </div>
                            @endif
                            <div class="mt-3">
                                <span class="text-sm text-gray-600">Delivery Address:</span>
                                @php
                                    $addressParts = array_filter([
                                        $user->street,
                                        $user->barangay,
                                        $user->city,
                                        $user->province,
                                        $user->zip_code
                                    ]);
                                @endphp
                                <p class="font-semibold">{{ implode(', ', $addressParts) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="p-8">
                <h3 class="text-sm font-semibold text-gray-600 mb-4">ORDER ITEMS</h3>
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $item->product_sku }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-center">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right">
                                    ₱{{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 text-right">
                                    ₱{{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="mt-8 flex justify-end">
                    <div class="w-80">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold">₱{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Tax (12%):</span>
                                <span class="font-semibold">₱{{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Shipping:</span>
                                <span class="font-semibold">₱{{ number_format($order->shipping_amount, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="flex justify-between items-center text-green-600">
                                <span>Discount:</span>
                                <span class="font-semibold">-₱{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="border-t-2 border-gray-300 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">TOTAL PAID:</span>
                                    <span class="text-2xl font-bold text-[#8b7355]">₱{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 p-8 border-t border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">Thank you for your purchase!</p>
                    <p class="text-xs text-gray-500">This is an official receipt for your order. For any questions or concerns, please contact our customer service.</p>
                    <p class="text-xs text-gray-500 mt-2">David's Wood Furniture | Phone: (123) 456-7890 | Email: info@davidswood.com</p>
                </div>
            </div>
        </div>

        <!-- Additional Print Instructions (Don't print) -->
        <div class="no-print mt-6 text-center text-sm text-gray-600">
            <p>Click "Download/Print Receipt" to save or print this receipt.</p>
            <p>Use your browser's print function (Ctrl+P / Cmd+P) to save as PDF.</p>
        </div>
    </div>

    <script>
        // Auto-focus print dialog option
        // You can uncomment this to auto-trigger print dialog on page load
        // window.onload = function() { window.print(); };
    </script>
</body>
</html>
