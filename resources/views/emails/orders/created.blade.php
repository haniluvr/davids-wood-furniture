@extends('emails.layouts.branded')

@section('content')
<h1>Order Confirmation</h1>

<p>Hello {{ $order->user->name }},</p>

<p>Thank you for your order! We have received your order and are processing it. You will receive another email when your order ships.</p>

<div style="background: #F8F8F8; padding: 30px; border-radius: 8px; margin-bottom: 30px; border: 0.75px solid #8B7355;">
    <h2>Order Details</h2>
    <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'Not specified' }}</p>
    <p><strong>Payment Status:</strong> 
        @if($order->payment_status === 'paid')
            <span style="color: #10b981; font-weight: 600;">Paid</span>
        @elseif($order->payment_status === 'pending')
            <span style="color: #f59e0b; font-weight: 600;">Pending</span>
        @else
            <span style="color: #ef4444; font-weight: 600;">{{ ucfirst($order->payment_status) }}</span>
        @endif
    </p>
    @if($order->shipping_address && isset($order->shipping_address['estimated_delivery_days']))
        <p><strong>Estimated Delivery:</strong> {{ $order->shipping_address['estimated_delivery_days'] }}</p>
    @endif
</div>

<h2>Shipping Address</h2>
<div class="info-box">
    <p><strong>{{ $order->shipping_address['first_name'] ?? '' }} {{ $order->shipping_address['last_name'] ?? '' }}</strong></p>
    <p>{{ $order->shipping_address['address_line_1'] ?? '' }}</p>
    @if($order->shipping_address['address_line_2'] ?? false)
        <p>{{ $order->shipping_address['address_line_2'] }}</p>
    @endif
    @if($order->shipping_address['barangay'] ?? false)
        <p>{{ $order->shipping_address['barangay'] }}</p>
    @endif
    <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['province'] ?? '' }} {{ $order->shipping_address['zip_code'] ?? '' }}</p>
    <p>{{ $order->shipping_address['region'] ?? '' }}</p>
    @if($order->shipping_address['phone'] ?? false)
        <p><strong>Phone:</strong> {{ $order->shipping_address['phone'] }}</p>
    @endif
    @if($order->shipping_address['email'] ?? false)
        <p><strong>Email:</strong> {{ $order->shipping_address['email'] }}</p>
    @endif
</div>

<h2>Order Items</h2>
<table class="order-table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->orderItems as $item)
        <tr>
            <td>
                <strong>{{ $item->product_name }}</strong>
                @if($item->product_sku)
                    <br><small>SKU: {{ $item->product_sku }}</small>
                @endif
            </td>
            <td>{{ $item->quantity }}</td>
            <td>₱{{ number_format($item->unit_price, 2) }}</td>
            <td>₱{{ number_format($item->total_price, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3"><strong>Subtotal:</strong></td>
            <td><strong>₱{{ number_format($order->subtotal, 2) }}</strong></td>
        </tr>
        @if($order->shipping_cost > 0)
        <tr>
            <td colspan="3"><strong>Shipping:</strong></td>
            <td><strong>₱{{ number_format($order->shipping_cost, 2) }}</strong></td>
        </tr>
        @else
        <tr>
            <td colspan="3"><strong>Shipping:</strong></td>
            <td><strong style="color: #10b981;">Free</strong></td>
        </tr>
        @endif
        @if($order->tax_amount > 0)
        <tr>
            <td colspan="3"><strong>Tax (VAT 12%):</strong></td>
            <td><strong>₱{{ number_format($order->tax_amount, 2) }}</strong></td>
        </tr>
        @endif
        @if($order->discount_amount > 0)
        <tr>
            <td colspan="3"><strong>Discount:</strong></td>
            <td><strong style="color: #10b981;">-₱{{ number_format($order->discount_amount, 2) }}</strong></td>
        </tr>
        @endif
        <tr class="total-row">
            <td colspan="3"><strong>Total:</strong></td>
            <td><strong style="font-size: 18px; color: #8B7355;">₱{{ number_format($order->total_amount, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>

@if($order->notes)
<h2>Order Notes</h2>
<div class="info-box">
    <p>{{ $order->notes }}</p>
</div>
@endif

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/account/orders/' . $order->id) }}" class="button">View Order Details</a>
</div>

<p>We will send you another email when your order ships with tracking information.</p>

<p>If you have any questions about your order, please don't hesitate to contact our customer service team.</p>

<p>Thank you for choosing David's Wood Furniture!</p>
@endsection

