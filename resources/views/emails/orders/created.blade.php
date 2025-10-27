@extends('emails.layouts.branded')

@section('content')
<h1>Order Confirmation</h1>

<p>Hello {{ $order->user->name }},</p>

<p>Thank you for your order! We have received your order and are processing it. You will receive another email when your order ships.</p>

<div class="info-box">
    <h2>Order Details</h2>
    <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'Not specified' }}</p>
</div>

<h2>Shipping Address</h2>
<div class="info-box">
    <p><strong>{{ $order->shipping_address['name'] ?? $order->user->name }}</strong></p>
    <p>{{ $order->shipping_address['address_line_1'] ?? '' }}</p>
    @if($order->shipping_address['address_line_2'] ?? false)
        <p>{{ $order->shipping_address['address_line_2'] }}</p>
    @endif
    <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
    <p>{{ $order->shipping_address['country'] ?? '' }}</p>
    @if($order->shipping_address['phone'] ?? false)
        <p><strong>Phone:</strong> {{ $order->shipping_address['phone'] }}</p>
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
        @foreach($order->items as $item)
        <tr>
            <td>
                <strong>{{ $item->product->name }}</strong>
                @if($item->product->sku)
                    <br><small>SKU: {{ $item->product->sku }}</small>
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
        @endif
        @if($order->tax_amount > 0)
        <tr>
            <td colspan="3"><strong>Tax:</strong></td>
            <td><strong>₱{{ number_format($order->tax_amount, 2) }}</strong></td>
        </tr>
        @endif
        @if($order->discount_amount > 0)
        <tr>
            <td colspan="3"><strong>Discount:</strong></td>
            <td><strong>-₱{{ number_format($order->discount_amount, 2) }}</strong></td>
        </tr>
        @endif
        <tr class="total-row">
            <td colspan="3"><strong>Total:</strong></td>
            <td><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/account/orders/' . $order->id) }}" class="button">View Order Details</a>
</div>

<p>We will send you another email when your order ships with tracking information.</p>

<p>If you have any questions about your order, please don't hesitate to contact our customer service team.</p>

<p>Thank you for choosing David's Wood Furniture!</p>
@endsection

