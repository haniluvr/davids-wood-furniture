@extends('emails.layouts.branded')

@section('content')
<h1>Don't Forget Your Items!</h1>

<p>Hello {{ $user->name }},</p>

<p>We noticed you left some beautiful furniture pieces in your cart. Don't let them slip away!</p>

<div class="info-box" style="border-left-color: #f59e0b; background-color: #fffbeb;">
    <h2 style="color: #d97706;">⏰ Limited Time Offer</h2>
    <p>Complete your purchase within the next 24 hours and save <strong>15%</strong> on your entire order!</p>
    <p><strong>Use Code:</strong> <span style="background-color: #d97706; color: white; padding: 4px 8px; border-radius: 4px; font-family: monospace; font-size: 16px;">CART15</span></p>
    <p style="margin: 0; color: #d97706; font-weight: 600;">Expires in 24 hours</p>
</div>

<h2>Your Cart Items</h2>
@if($cartItems && count($cartItems) > 0)
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
        @foreach($cartItems as $item)
        <tr>
            <td>
                <div style="display: flex; align-items: center;">
                    @if($item->product->images && count($item->product->images) > 0)
                        <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="{{ $item->product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; margin-right: 15px;">
                    @endif
                    <div>
                        <strong>{{ $item->product->name }}</strong>
                        @if($item->product->sku)
                            <br><small>SKU: {{ $item->product->sku }}</small>
                        @endif
                    </div>
                </div>
            </td>
            <td>{{ $item->quantity }}</td>
            <td>${{ number_format($item->price, 2) }}</td>
            <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3"><strong>Subtotal:</strong></td>
            <td><strong>${{ number_format($cartTotal, 2) }}</strong></td>
        </tr>
        <tr style="background-color: #f0fdf4;">
            <td colspan="3"><strong>Discount (15%):</strong></td>
            <td><strong style="color: #059669;">-${{ number_format($cartTotal * 0.15, 2) }}</strong></td>
        </tr>
        <tr class="total-row">
            <td colspan="3"><strong>Total After Discount:</strong></td>
            <td><strong>${{ number_format($cartTotal * 0.85, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>
@endif

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/checkout') }}" class="button">Complete Purchase</a>
    <a href="{{ url('/cart') }}" class="button" style="background: #6b7280; margin-left: 10px;">View Cart</a>
</div>

<h2>Why Choose David's Wood Furniture?</h2>
<div class="info-box">
    <h3>🏆 Quality Guarantee</h3>
    <p>All our furniture comes with a comprehensive warranty and satisfaction guarantee. We stand behind the quality of our craftsmanship.</p>
</div>

<div class="info-box">
    <h3>🚚 Free Shipping</h3>
    <p>Enjoy free shipping on orders over $500. We carefully package and deliver your furniture right to your door.</p>
</div>

<div class="info-box">
    <h3>🛠️ Assembly Service</h3>
    <p>Need help with assembly? Our professional team can assemble your furniture for a small additional fee.</p>
</div>

<h2>Customer Reviews</h2>
<div class="info-box">
    <p style="font-style: italic; margin: 0 0 15px 0;">"I absolutely love my new dining table from David's Wood Furniture. The quality is exceptional and the customer service was outstanding. Highly recommended!"</p>
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <p style="margin: 0; color: #6b7280;">- Jennifer L.</p>
        <span class="rating">
            <span class="star">★</span>
            <span class="star">★</span>
            <span class="star">★</span>
            <span class="star">★</span>
            <span class="star">★</span>
        </span>
    </div>
</div>

<h2>Need Help?</h2>
<div class="info-box">
    <p>Our customer service team is here to help:</p>
    <ul style="margin: 0; padding-left: 20px;">
        <li><strong>Phone:</strong> (555) 123-4567</li>
        <li><strong>Email:</strong> support@davidswood.com</li>
        <li><strong>Live Chat:</strong> Available on our website</li>
    </ul>
</div>

<div style="text-align: center; margin: 40px 0;">
    <a href="{{ url('/checkout') }}" class="button">Complete Your Order</a>
</div>

<p>Don't miss out on these beautiful pieces and your special discount. Complete your purchase now!</p>

<p>Thank you for considering David's Wood Furniture for your home furnishing needs.</p>
@endsection

