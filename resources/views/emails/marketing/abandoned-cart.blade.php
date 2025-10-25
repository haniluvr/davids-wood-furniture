@extends('emails.layouts.branded')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #8B7355; margin: 0;">Don't Forget Your Items!</h1>
    <p style="color: #666; margin: 10px 0 0 0; font-style: italic;">David's Wood Furnitures</p>
</div>

<div style="background: #F8F8F8; padding: 30px; border-radius: 8px; margin-bottom: 30px; border: 0.75px solid #8B7355;">
    <h2 style="color: #8B7355; margin: 0 0 20px 0;">Hello {{ $user->name }},</h2>
    
    <p style="color: #555; line-height: 1.6; margin: 0;">
        We noticed you left some beautiful furniture pieces in your cart. Don't let them slip away!
    </p>
</div>

<div style="background: #efe3df; border: 1px solid #8B7355; padding: 20px; border-radius: 6px; margin: 20px 0;">
    <h2 style="color: #8B7355; margin: 0 0 15px 0;">⏰ Limited Time Offer</h2>
    <p style="color: #555; margin: 0 0 15px 0;">Complete your purchase within the next 24 hours and save <strong>15%</strong> on your entire order!</p>
    <p style="color: #555; margin: 0 0 15px 0;"><strong>Use Code:</strong> <span style="background-color: #8B7355; color: white; padding: 4px 8px; border-radius: 4px; font-family: monospace; font-size: 16px;">CART15</span></p>
    <p style="margin: 0; color: #8B7355; font-weight: 600;">Expires in 24 hours</p>
</div>

<h2 style="color: #8B7355; margin: 30px 0 15px 0;">Your Cart Items</h2>
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
            <td>₱{{ number_format($item->price, 2) }}</td>
            <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3"><strong>Subtotal:</strong></td>
            <td><strong>₱{{ number_format($cartTotal, 2) }}</strong></td>
        </tr>
        <tr style="background-color: #f0fdf4;">
            <td colspan="3"><strong>Discount (15%):</strong></td>
            <td><strong style="color: #059669;">-₱{{ number_format($cartTotal * 0.15, 2) }}</strong></td>
        </tr>
        <tr class="total-row">
            <td colspan="3"><strong>Total After Discount:</strong></td>
            <td><strong>₱{{ number_format($cartTotal * 0.85, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>
@endif

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/checkout') }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px; margin: 5px;">Complete Purchase</a>
    <a href="{{ url('/cart') }}" style="background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px; margin: 5px;">View Cart</a>
</div>

<h2 style="color: #8B7355; margin: 30px 0 15px 0;">Why Choose David's Wood Furnitures?</h2>
<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">🏆 Quality Guarantee</h3>
    <p style="color: #555; margin: 0;">All our furniture comes with a comprehensive warranty and satisfaction guarantee. We stand behind the quality of our craftsmanship.</p>
</div>

<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">🚚 Free Shipping</h3>
    <p style="color: #555; margin: 0;">Enjoy free shipping on orders over ₱25,000. We carefully package and deliver your furniture right to your door.</p>
</div>

<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">🛠️ Assembly Service</h3>
    <p style="color: #555; margin: 0;">Need help with assembly? Our professional team can assemble your furniture for a small additional fee.</p>
</div>

<h2 style="color: #8B7355; margin: 30px 0 15px 0;">Need Help?</h2>
<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <p style="color: #555; margin: 0 0 15px 0;">Our customer service team is here to help:</p>
    <ul style="margin: 0; padding-left: 20px; color: #555;">
        <li><strong>Phone:</strong> +63 (917) 123-4567</li>
        <li><strong>Email:</strong> hello@davidswood.shop</li>
        <li><strong>Live Chat:</strong> Available on our website</li>
    </ul>
</div>

<div style="text-align: center; margin: 40px 0;">
    <a href="{{ url('/checkout') }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px;">Complete Your Order</a>
</div>

<p style="color: #555; margin: 20px 0;">Don't miss out on these beautiful pieces and your special discount. Complete your purchase now!</p>

<p style="color: #555; margin: 20px 0;">Thank you for considering David's Wood Furnitures for your home furnishing needs.</p>
@endsection

