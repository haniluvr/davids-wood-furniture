@extends('emails.layouts.branded')

@section('content')
<h1>Order Status Update</h1>

<p>Hello {{ $order->user->name }},</p>

<p>Your order status has been updated. Here are the details:</p>

<div class="info-box">
    <h2>Order Information</h2>
    <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
    <p><strong>Previous Status:</strong> {{ ucfirst($oldStatus ?? 'Unknown') }}</p>
    <p><strong>New Status:</strong> 
        @if($newStatus === 'shipped')
            <span style="color: #3b82f6; font-weight: 600;">Shipped</span>
        @elseif($newStatus === 'delivered')
            <span style="color: #10b981; font-weight: 600;">Delivered</span>
        @elseif($newStatus === 'cancelled')
            <span style="color: #ef4444; font-weight: 600;">Cancelled</span>
        @elseif($newStatus === 'processing')
            <span style="color: #f59e0b; font-weight: 600;">Processing</span>
        @else
            <span style="font-weight: 600;">{{ ucfirst($newStatus) }}</span>
        @endif
    </p>
    <p><strong>Updated:</strong> {{ now()->format('M d, Y \a\t g:i A') }}</p>
</div>

@if($newStatus === 'shipped')
    <div class="info-box">
        <h2>Shipping Information</h2>
        <p>Your order has been shipped and is on its way!</p>
        @if($order->tracking_number ?? false)
            <p><strong>Tracking Number:</strong> {{ $order->tracking_number }}</p>
        @endif
        @if($order->estimated_delivery ?? false)
            <p><strong>Estimated Delivery:</strong> {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('M d, Y') }}</p>
        @endif
    </div>
@elseif($newStatus === 'delivered')
    <div class="info-box">
        <h2>Delivery Confirmation</h2>
        <p>Great news! Your order has been delivered.</p>
        @if($order->delivered_at ?? false)
            <p><strong>Delivered on:</strong> {{ \Carbon\Carbon::parse($order->delivered_at)->format('M d, Y \a\t g:i A') }}</p>
        @endif
    </div>
@elseif($newStatus === 'cancelled')
    <div class="info-box">
        <h2>Order Cancelled</h2>
        <p>We're sorry to inform you that your order has been cancelled.</p>
        @if($order->cancellation_reason ?? false)
            <p><strong>Reason:</strong> {{ $order->cancellation_reason }}</p>
        @endif
        <p>If you have any questions about this cancellation, please contact our customer service team.</p>
    </div>
@elseif($newStatus === 'processing')
    <div class="info-box">
        <h2>Processing Your Order</h2>
        <p>We're currently processing your order and preparing it for shipment.</p>
        <p>You'll receive another email once your order ships with tracking information.</p>
    </div>
@endif

<h2>Order Timeline</h2>
<div class="info-box">
    <p><strong>Order Placed:</strong> {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
    @if($order->status === 'processing' || $order->status === 'shipped' || $order->status === 'delivered')
        <p><strong>Processing Started:</strong> {{ $order->updated_at->format('M d, Y \a\t g:i A') }}</p>
    @endif
    @if($order->status === 'shipped' || $order->status === 'delivered')
        <p><strong>Shipped:</strong> {{ $order->updated_at->format('M d, Y \a\t g:i A') }}</p>
    @endif
    @if($order->status === 'delivered')
        <p><strong>Delivered:</strong> {{ $order->delivered_at ? \Carbon\Carbon::parse($order->delivered_at)->format('M d, Y \a\t g:i A') : 'Recently' }}</p>
    @endif
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/account/orders/' . $order->id) }}" class="button">View Order Details</a>
</div>

@if($newStatus === 'delivered')
    <p>We hope you love your new furniture! If you have any questions or need assistance, please don't hesitate to contact us.</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/products') }}" class="button">Shop More Products</a>
    </div>
@endif

<p>Thank you for choosing David's Wood Furniture!</p>
@endsection

