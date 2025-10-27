<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
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
            padding: 40px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .logo-section {
            flex: 1;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            background: #10b981;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .company-info h1 {
            color: #1f2937;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 2px;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-title {
            font-size: 32px;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 10px;
        }
        
        .invoice-number {
            font-size: 18px;
            color: #374151;
            margin-bottom: 5px;
        }
        
        .invoice-date {
            color: #6b7280;
            font-size: 14px;
        }
        
        .addresses {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        
        .address {
            flex: 1;
            margin-right: 40px;
        }
        
        .address:last-child {
            margin-right: 0;
        }
        
        .address h3 {
            color: #374151;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .address p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 3px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th {
            background: #f9fafb;
            color: #374151;
            font-weight: 600;
            text-align: left;
            padding: 15px 12px;
            border-bottom: 2px solid #e5e7eb;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .items-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        
        .items-table tbody tr:hover {
            background: #f9fafb;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .items-table .text-center {
            text-align: center;
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
        
        .totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }
        
        .totals-table {
            width: 300px;
        }
        
        .totals-table tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        .totals-table td {
            padding: 8px 12px;
            font-size: 14px;
        }
        
        .totals-table .label {
            color: #6b7280;
            text-align: right;
        }
        
        .totals-table .amount {
            color: #1f2937;
            font-weight: 500;
            text-align: right;
        }
        
        .totals-table .total-row {
            background: #f9fafb;
            border-top: 2px solid #e5e7eb;
        }
        
        .totals-table .total-row .label {
            font-weight: 600;
            color: #1f2937;
        }
        
        .totals-table .total-row .amount {
            font-weight: 700;
            font-size: 16px;
            color: #10b981;
        }
        
        .payment-info {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .payment-info h3 {
            color: #374151;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .payment-info p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .footer {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
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
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .container {
                padding: 20px;
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
                    <p>United States</p>
                    <p>Phone: (555) 123-4567</p>
                    <p>Email: orders@davidswood.com</p>
                </div>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">{{ $order->order_number }}</div>
                <div class="invoice-date">{{ $order->created_at->format('F d, Y') }}</div>
                <div style="margin-top: 10px;">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="addresses">
            <div class="address">
                <h3>Bill To</h3>
                @if($order->billing_address)
                    <p><strong>{{ $order->user ? $order->user->first_name . ' ' . $order->user->last_name : 'Guest User' }}</strong></p>
                    @if($order->billing_address['address_line_1'])
                        <p>{{ $order->billing_address['address_line_1'] }}</p>
                    @endif
                    @if($order->billing_address['address_line_2'])
                        <p>{{ $order->billing_address['address_line_2'] }}</p>
                    @endif
                    <p>
                        {{ $order->billing_address['city'] }}, 
                        {{ $order->billing_address['state'] }} 
                        {{ $order->billing_address['zip_code'] }}
                    </p>
                    <p>{{ $order->billing_address['country'] }}</p>
                @else
                    <p>{{ $order->user ? $order->user->first_name . ' ' . $order->user->last_name : 'Guest User' }}</p>
                    <p>{{ $order->user ? $order->user->email : 'No email available' }}</p>
                @endif
            </div>
            <div class="address">
                <h3>Ship To</h3>
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
                    <p>Same as billing address</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Item</th>
                    <th style="width: 15%;" class="text-center">SKU</th>
                    <th style="width: 15%;" class="text-center">Quantity</th>
                    <th style="width: 15%;" class="text-right">Unit Price</th>
                    <th style="width: 15%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>
                            <div class="product-name">{{ $item->product->name }}</div>
                            @if($item->product->short_description)
                                <div class="product-sku">{{ Str::limit($item->product->short_description, 50) }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->product->sku }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">₱{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                @if($order->tax_amount > 0)
                    <tr>
                        <td class="label">Tax:</td>
                        <td class="amount">₱{{ number_format($order->tax_amount, 2) }}</td>
                    </tr>
                @endif
                @if($order->shipping_cost > 0)
                    <tr>
                        <td class="label">Shipping:</td>
                        <td class="amount">₱{{ number_format($order->shipping_cost, 2) }}</td>
                    </tr>
                @endif
                @if($order->discount_amount > 0)
                    <tr>
                        <td class="label">Discount:</td>
                        <td class="amount">-₱{{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td class="label">Total:</td>
                    <td class="amount">₱{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Payment Information -->
        <div class="payment-info">
            <h3>Payment Information</h3>
            <p><strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
            <p><strong>Payment Status:</strong> 
                <span class="status-badge status-{{ $order->payment_status }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </p>
            @if($order->shipping_method)
                <p><strong>Shipping Method:</strong> {{ ucwords(str_replace('_', ' ', $order->shipping_method)) }}</p>
            @endif
            @if($order->tracking_number)
                <p><strong>Tracking Number:</strong> {{ $order->tracking_number }}</p>
            @endif
        </div>

        @if($order->notes)
            <div class="payment-info">
                <h3>Order Notes</h3>
                <p>{{ $order->notes }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>For questions about this invoice, please contact us at orders@davidswood.com</p>
            <p style="margin-top: 15px;">
                <strong>David's Wood Furniture</strong> | Handcrafted Excellence Since 2003
            </p>
        </div>
    </div>
</body>
</html>
