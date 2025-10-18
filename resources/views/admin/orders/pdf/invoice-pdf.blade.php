<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
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
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h1 {
            font-size: 28px;
            color: #3C50E0;
            margin-bottom: 5px;
        }
        
        .invoice-title p {
            color: #666;
            font-size: 14px;
        }
        
        .billing-info {
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
        
        .totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        
        .totals-table {
            width: 300px;
        }
        
        .totals-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }
        
        .totals-table .total-row {
            font-weight: bold;
            background-color: #3C50E0;
            color: white;
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                David's Wood Furniture
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p>Invoice #{{ $order->order_number }}</p>
                <p>Date: {{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </div>
        
        <!-- Billing Information -->
        <div class="billing-info">
            <div class="info-section">
                <h3>Bill To:</h3>
                <p><strong>{{ $order->billing_address['first_name'] }} {{ $order->billing_address['last_name'] }}</strong></p>
                @if($order->billing_address['company'])
                    <p>{{ $order->billing_address['company'] }}</p>
                @endif
                <p>{{ $order->billing_address['address_line_1'] }}</p>
                @if($order->billing_address['address_line_2'])
                    <p>{{ $order->billing_address['address_line_2'] }}</p>
                @endif
                <p>{{ $order->billing_address['city'] }}, {{ $order->billing_address['state'] }} {{ $order->billing_address['postal_code'] }}</p>
                <p>{{ $order->billing_address['country'] }}</p>
                @if($order->billing_address['phone'])
                    <p>Phone: {{ $order->billing_address['phone'] }}</p>
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
                @if($order->payment_status)
                    <p><strong>Payment Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</p>
                @endif
                @if($order->tracking_number)
                    <p><strong>Tracking:</strong> {{ $order->tracking_number }}</p>
                @endif
            </div>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
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
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                    <td class="text-right">${{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="totals">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">${{ number_format($order->subtotal, 2) }}</td>
                </tr>
                @if($order->tax_amount > 0)
                <tr>
                    <td>Tax:</td>
                    <td class="text-right">${{ number_format($order->tax_amount, 2) }}</td>
                </tr>
                @endif
                @if($order->shipping_amount > 0)
                <tr>
                    <td>Shipping:</td>
                    <td class="text-right">${{ number_format($order->shipping_amount, 2) }}</td>
                </tr>
                @endif
                @if($order->discount_amount > 0)
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">-${{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Total:</td>
                    <td class="text-right">${{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>David's Wood Furniture - Quality furniture for your home</p>
            <p>For questions about this invoice, please contact our customer service team.</p>
        </div>
    </div>
</body>
</html>
