<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Slip #{{ $order->order_number }}</title>
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
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3C50E0;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3C50E0;
        }
        
        .packing-title {
            text-align: right;
        }
        
        .packing-title h1 {
            font-size: 28px;
            color: #3C50E0;
            margin-bottom: 5px;
        }
        
        .packing-title p {
            color: #666;
            font-size: 14px;
        }
        
        .shipping-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .info-section {
            flex: 1;
            margin-right: 20px;
        }
        
        .info-section:last-child {
            margin-right: 0;
        }
        
        .info-section h3 {
            color: #3C50E0;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .info-section p {
            margin-bottom: 5px;
            color: #666;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .items-table th {
            background-color: #f8f9fa;
            color: #3C50E0;
            font-weight: 600;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .barcode-section {
            margin-top: 40px;
            text-align: center;
            padding: 20px;
            border: 2px dashed #ddd;
            border-radius: 8px;
        }
        
        .barcode-placeholder {
            width: 200px;
            height: 80px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 12px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-processing { background-color: #d1ecf1; color: #0c5460; }
        .status-shipped { background-color: #d4edda; color: #155724; }
        .status-delivered { background-color: #d1ecf1; color: #0c5460; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        
        .shipping-instructions {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .shipping-instructions h4 {
            color: #3C50E0;
            margin-bottom: 10px;
        }
        
        .shipping-instructions p {
            margin-bottom: 5px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                David's Wood Furniture
            </div>
            <div class="packing-title">
                <h1>PACKING SLIP</h1>
                <p>Order #{{ $order->order_number }}</p>
                <p>Date: {{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </div>
        
        <!-- Shipping Information -->
        <div class="shipping-info">
            <div class="info-section">
                <h3>Ship To:</h3>
                <p><strong>{{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}</strong></p>
                @if($order->shipping_address['company'])
                    <p>{{ $order->shipping_address['company'] }}</p>
                @endif
                <p>{{ $order->shipping_address['address_line_1'] }}</p>
                @if($order->shipping_address['address_line_2'])
                    <p>{{ $order->shipping_address['address_line_2'] }}</p>
                @endif
                <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['postal_code'] }}</p>
                <p>{{ $order->shipping_address['country'] }}</p>
                @if($order->shipping_address['phone'])
                    <p>Phone: {{ $order->shipping_address['phone'] }}</p>
                @endif
            </div>
            
            <div class="info-section">
                <h3>Order Information:</h3>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                <p><strong>Status:</strong> 
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                @if($order->shipping_method)
                    <p><strong>Shipping Method:</strong> {{ $order->shipping_method }}</p>
                @endif
                @if($order->tracking_number)
                    <p><strong>Tracking Number:</strong> {{ $order->tracking_number }}</p>
                @endif
            </div>
        </div>
        
        <!-- Shipping Instructions -->
        @if($order->notes)
        <div class="shipping-instructions">
            <h4>Special Instructions:</h4>
            <p>{{ $order->notes }}</p>
        </div>
        @endif
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Packed</th>
                    <th class="text-center">Weight</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product->name }}</strong>
                        @if($item->product->sku)
                            <br><small>SKU: {{ $item->product->sku }}</small>
                        @endif
                    </td>
                    <td>{{ Str::limit($item->product->description, 100) }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-center">
                        <div style="width: 20px; height: 20px; border: 1px solid #ddd; margin: 0 auto;"></div>
                    </td>
                    <td class="text-center">
                        @if($item->product->weight)
                            {{ $item->product->weight }} lbs
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Packing Checklist -->
        <div style="margin-top: 30px;">
            <h3 style="color: #3C50E0; margin-bottom: 15px;">Packing Checklist:</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: flex; align-items: center; margin-bottom: 8px;">
                        <input type="checkbox" style="margin-right: 8px;"> All items included
                    </label>
                    <label style="display: flex; align-items: center; margin-bottom: 8px;">
                        <input type="checkbox" style="margin-right: 8px;"> Items properly wrapped
                    </label>
                    <label style="display: flex; align-items: center; margin-bottom: 8px;">
                        <input type="checkbox" style="margin-right: 8px;"> Fragile items marked
                    </label>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: flex; align-items: center; margin-bottom: 8px;">
                        <input type="checkbox" style="margin-right: 8px;"> Invoice included
                    </label>
                    <label style="display: flex; align-items: center; margin-bottom: 8px;">
                        <input type="checkbox" style="margin-right: 8px;"> Return policy included
                    </label>
                    <label style="display: flex; align-items: center; margin-bottom: 8px;">
                        <input type="checkbox" style="margin-right: 8px;"> Package sealed properly
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Barcode Section -->
        <div class="barcode-section">
            <h4 style="color: #3C50E0; margin-bottom: 15px;">Shipping Label</h4>
            <div class="barcode-placeholder">
                [BARCODE PLACEHOLDER]
            </div>
            <p><strong>Tracking Number:</strong> {{ $order->tracking_number ?: 'To be assigned' }}</p>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>Packed by:</strong> _________________ <strong>Date:</strong> _________________</p>
            <p><strong>Verified by:</strong> _________________ <strong>Date:</strong> _________________</p>
            <p style="margin-top: 20px;">Thank you for choosing David's Wood Furniture!</p>
        </div>
    </div>
</body>
</html>
