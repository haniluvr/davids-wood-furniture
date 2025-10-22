<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Slip - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .logo-section {
            flex: 1;
        }
        
        .logo {
            width: 50px;
            height: 50px;
            background: #10b981;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 8px;
        }
        
        .company-info h1 {
            color: #1f2937;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 2px;
        }
        
        .order-info {
            text-align: right;
        }
        
        .packing-title {
            font-size: 28px;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 8px;
        }
        
        .order-number {
            font-size: 16px;
            color: #374151;
            margin-bottom: 5px;
        }
        
        .order-date {
            color: #6b7280;
            font-size: 13px;
        }
        
        .shipping-info {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .shipping-info h3 {
            color: #374151;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .shipping-address {
            display: flex;
            gap: 40px;
        }
        
        .address-block {
            flex: 1;
        }
        
        .address-block h4 {
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .address-block p {
            color: #1f2937;
            font-size: 14px;
            margin-bottom: 3px;
            font-weight: 500;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th {
            background: #f3f4f6;
            color: #374151;
            font-weight: 600;
            text-align: left;
            padding: 12px 10px;
            border-bottom: 2px solid #d1d5db;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .product-name {
            font-weight: 500;
            color: #1f2937;
        }
        
        .product-sku {
            color: #6b7280;
            font-size: 12px;
            margin-top: 2px;
        }
        
        .quantity-box {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            text-align: center;
            line-height: 26px;
            font-weight: 600;
            color: #374151;
            background: #fff;
        }
        
        .packing-notes {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 30px;
        }
        
        .packing-notes h3 {
            color: #92400e;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .packing-notes p {
            color: #92400e;
            font-size: 13px;
        }
        
        .barcode-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
        }
        
        .barcode-section h3 {
            color: #374151;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .barcode-placeholder {
            width: 200px;
            height: 60px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 12px;
        }
        
        .order-number-barcode {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            letter-spacing: 2px;
        }
        
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 11px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-processing {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-shipped {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-delivered {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .shipping-method {
            background: #e0f2fe;
            border: 1px solid #0ea5e9;
            border-radius: 6px;
            padding: 10px;
            margin-top: 15px;
        }
        
        .shipping-method h4 {
            color: #0c4a6e;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .shipping-method p {
            color: #0c4a6e;
            font-size: 13px;
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .container {
                padding: 15px;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">DW</div>
                <div class="company-info">
                    <h1>David's Wood Furniture</h1>
                    <p>123 Craftsmanship Lane</p>
                    <p>Portland, OR 97201</p>
                    <p>Phone: (555) 123-4567</p>
                </div>
            </div>
            <div class="order-info">
                <div class="packing-title">PACKING SLIP</div>
                <div class="order-number">{{ $order->order_number }}</div>
                <div class="order-date">{{ $order->created_at->format('M d, Y') }}</div>
                <div style="margin-top: 8px;">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="shipping-info">
            <h3>Shipping Information</h3>
            <div class="shipping-address">
                <div class="address-block">
                    <h4>Ship To</h4>
                    @if($order->shipping_address)
                        <p><strong>{{ $order->user ? $order->user->first_name . ' ' . $order->user->last_name : 'Guest User' }}</strong></p>
                        @if($order->shipping_address['address_line_1'])
                            <p>{{ $order->shipping_address['address_line_1'] }}</p>
                        @endif
                        @if($order->shipping_address['address_line_2'])
                            <p>{{ $order->shipping_address['address_line_2'] }}</p>
                        @endif
                        <p>
                            {{ $order->shipping_address['city'] }}, 
                            {{ $order->shipping_address['state'] }} 
                            {{ $order->shipping_address['zip_code'] }}
                        </p>
                        <p>{{ $order->shipping_address['country'] }}</p>
                    @else
                        <p><strong>{{ $order->user ? $order->user->first_name . ' ' . $order->user->last_name : 'Guest User' }}</strong></p>
                        <p>{{ $order->user ? $order->user->email : 'No email available' }}</p>
                    @endif
                </div>
                <div class="address-block">
                    <h4>Contact</h4>
                    <p>{{ $order->user ? $order->user->email : 'No email available' }}</p>
                    @if($order->user && $order->user->phone)
                        <p>{{ $order->user->phone }}</p>
                    @endif
                </div>
            </div>
            
            @if($order->shipping_method)
                <div class="shipping-method">
                    <h4>Shipping Method</h4>
                    <p>{{ ucwords(str_replace('_', ' ', $order->shipping_method)) }}</p>
                    @if($order->tracking_number)
                        <p><strong>Tracking:</strong> {{ $order->tracking_number }}</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Item Description</th>
                    <th style="width: 15%;" class="text-center">SKU</th>
                    <th style="width: 15%;" class="text-center">Qty</th>
                    <th style="width: 20%;" class="text-right">Unit Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>
                            <div class="product-name">{{ $item->product->name }}</div>
                            @if($item->product->short_description)
                                <div class="product-sku">{{ Str::limit($item->product->short_description, 60) }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->product->sku }}</td>
                        <td class="text-center">
                            <div class="quantity-box">{{ $item->quantity }}</div>
                        </td>
                        <td class="text-right">${{ number_format($item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Packing Notes -->
        <div class="packing-notes">
            <h3>Packing Instructions</h3>
            <p>• Handle with care - these are handcrafted wooden items</p>
            <p>• Use appropriate padding and protection materials</p>
            <p>• Ensure all items are securely packaged</p>
            <p>• Double-check quantities against this packing slip</p>
        </div>

        @if($order->notes)
            <div class="packing-notes">
                <h3>Special Instructions</h3>
                <p>{{ $order->notes }}</p>
            </div>
        @endif

        <!-- Barcode Section -->
        <div class="barcode-section">
            <h3>Order Barcode</h3>
            <div class="barcode-placeholder">
                [BARCODE]
            </div>
            <div class="order-number-barcode">{{ $order->order_number }}</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>David's Wood Furniture</strong> - Handcrafted Excellence Since 2003</p>
            <p>Questions? Contact us at orders@davidswood.com or (555) 123-4567</p>
        </div>
    </div>
</body>
</html>
