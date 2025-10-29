<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.4;
            color: #333;
            background: #fff;
            font-size: 11px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 15px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3C50E0;
        }
        
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #3C50E0;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h1 {
            font-size: 24px;
            color: #3C50E0;
            margin-bottom: 3px;
        }
        
        .invoice-title p {
            color: #666;
            font-size: 12px;
        }
        
        .billing-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
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
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .info-section p {
            margin-bottom: 3px;
            color: #666;
            font-size: 12px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th,
        .items-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
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
            margin-bottom: 20px;
        }
        
        .totals-table {
            width: 280px;
        }
        
        .totals-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }
        
        .totals-table .total-row {
            font-weight: bold;
            background-color: #3C50E0;
            color: white;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-family: 'DejaVu Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-processing { background-color: #d1ecf1; color: #0c5460; }
        .status-shipped { background-color: #d4edda; color: #155724; }
        .status-delivered { background-color: #d1ecf1; color: #0c5460; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        
        /* Page break control */
        .container {
            page-break-inside: avoid;
        }
        
        .items-table {
            page-break-inside: avoid;
        }
        
        .totals {
            page-break-inside: avoid;
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
                @if($order->billing_address)
                    <p><strong>{{ $order->billing_address['name'] ?? ($order->user ? $order->user->first_name . ' ' . $order->user->last_name : 'Guest User') }}</strong></p>
                    @if(isset($order->billing_address['company']) && $order->billing_address['company'])
                        <p>{{ $order->billing_address['company'] }}</p>
                    @endif
                    <p>{{ $order->billing_address['address_line_1'] ?? '' }}</p>
                    @if(isset($order->billing_address['address_line_2']) && $order->billing_address['address_line_2'])
                        <p>{{ $order->billing_address['address_line_2'] }}</p>
                    @endif
                    <p>{{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['postal_code'] ?? '' }}</p>
                    <p>{{ $order->billing_address['country'] ?? '' }}</p>
                    @if(isset($order->billing_address['phone']) && $order->billing_address['phone'])
                        <p>Phone: {{ $order->billing_address['phone'] }}</p>
                    @endif
                @else
                    <p><strong>{{ $order->user ? $order->user->first_name . ' ' . $order->user->last_name : 'Guest User' }}</strong></p>
                    @if($order->user)
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
                        <p>{{ $fullAddress ?: 'N/A' }}</p>
                        <p>Phone: {{ $order->user->phone ?: 'N/A' }}</p>
                    @else
                        <p>N/A</p>
                        <p>Phone: N/A</p>
                    @endif
                @endif
            </div>
            
            <div class="info-section">
                <h3>Order Information:</h3>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
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
                    <th>SKU</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                    </td>
                    <td>{{ $item->product_sku ?: 'N/A' }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">₱{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="totals">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">₱{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                @if($order->tax_amount > 0)
                <tr>
                    <td>Tax:</td>
                    <td class="text-right">₱{{ number_format($order->tax_amount, 2) }}</td>
                </tr>
                @endif
                @if(($order->shipping_cost ?? 0) > 0)
                <tr>
                    <td>Shipping:</td>
                    <td class="text-right">₱{{ number_format($order->shipping_cost, 2) }}</td>
                </tr>
                @endif
                @if(($order->discount_amount ?? 0) > 0)
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">-₱{{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Total:</td>
                    <td class="text-right">₱{{ number_format($order->total_amount, 2) }}</td>
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
