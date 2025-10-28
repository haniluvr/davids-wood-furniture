@extends('emails.layouts.branded')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #8B7355; margin: 0;">Welcome to David's Wood Furnitures!</h1>
    <p style="color: #666; margin: 10px 0 0 0; font-style: italic;">Nature's grain shaped by artistry</p>
</div>

<div style="background: #F8F8F8; padding: 30px; border-radius: 8px; margin-bottom: 30px; border: 0.75px solid #8B7355;">
    <h2 style="color: #8B7355; margin: 0 0 20px 0;">Hello {{ $user->name }},</h2>
    
    <p style="color: #555; line-height: 1.6; margin: 0 0 20px 0;">
        Welcome to David's Wood Furnitures! We're thrilled to have you join our community of furniture enthusiasts and home decor lovers.
    </p>
    
    @if(isset($magicLink) && $magicLink)
    <div style="background: #E8F5E8; border: 1px solid #8B7355; padding: 20px; border-radius: 6px; margin: 20px 0;">
        <h3 style="color: #8B7355; margin: 0 0 15px 0;">🔐 Complete Your Account Setup</h3>
        <p style="color: #555; margin: 0 0 15px 0;">To complete your account setup and set your password, please click the button below:</p>
        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ $magicLink }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px;">Set Up Password</a>
        </div>
        <p style="margin: 0; color: #8B7355; font-weight: 600; font-size: 14px;">This link will expire in 24 hours for security reasons.</p>
    </div>
    @endif
</div>

<div style="background: #E8F5E8; border: 1px solid #8B7355; padding: 20px; border-radius: 6px; margin: 20px 0;">
    <h2 style="color: #8B7355; margin: 0 0 15px 0;">🎉 Welcome Gift</h2>
    <p style="color: #555; margin: 0 0 15px 0;">As a thank you for joining us, we're giving you a <strong>10% discount</strong> on your first purchase!</p>
    <p style="color: #555; margin: 0 0 15px 0;"><strong>Use Code:</strong> <span style="background-color: #8B7355; color: white; padding: 4px 8px; border-radius: 4px; font-family: monospace; font-size: 16px;">WELCOME10</span></p>
    <p style="margin: 0; color: #8B7355; font-weight: 600;">Valid for 30 days from today</p>
</div>

<h2 style="color: #8B7355; margin: 30px 0 15px 0;">What You Can Expect</h2>

<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">🏠 Quality Furniture</h3>
    <p style="color: #555; margin: 0;">Discover our carefully curated collection of handcrafted wood furniture, from dining tables to bedroom sets, all made with premium materials and expert craftsmanship.</p>
</div>

<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">🚚 Free Shipping</h3>
    <p style="color: #555; margin: 0;">Enjoy free shipping on orders over ₱25,000. We carefully package and deliver your furniture right to your door.</p>
</div>

<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">🛠️ Expert Support</h3>
    <p style="color: #555; margin: 0;">Our knowledgeable team is here to help you find the perfect pieces for your home. From design advice to assembly assistance, we've got you covered.</p>
</div>

<h2 style="color: #8B7355; margin: 30px 0 15px 0;">Getting Started</h2>
<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/products') }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px; margin: 5px;">Browse Products</a>
    <a href="{{ url('/account') }}" style="background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px; margin: 5px;">My Account</a>
</div>

<h2 style="color: #8B7355; margin: 30px 0 15px 0;">Popular Categories</h2>
<div style="display: table; width: 100%; margin: 20px 0;">
    <div style="display: table-cell; width: 33.33%; padding: 0 10px; vertical-align: top;">
        <div style="text-align: center; padding: 20px; border: 1px solid #E5E5E5; border-radius: 8px; background-color: #F8F8F8;">
            <h4 style="margin: 0 0 10px 0; color: #8B7355;">Dining Room</h4>
            <p style="margin: 0 0 15px 0; color: #666; font-size: 14px;">Tables, chairs, and storage</p>
            <a href="{{ url('/products?category=dining-room') }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 6px 16px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 600;">Shop Now</a>
        </div>
    </div>
    
    <div style="display: table-cell; width: 33.33%; padding: 0 10px; vertical-align: top;">
        <div style="text-align: center; padding: 20px; border: 1px solid #E5E5E5; border-radius: 8px; background-color: #F8F8F8;">
            <h4 style="margin: 0 0 10px 0; color: #8B7355;">Bedroom</h4>
            <p style="margin: 0 0 15px 0; color: #666; font-size: 14px;">Beds, dressers, and nightstands</p>
            <a href="{{ url('/products?category=bedroom') }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 6px 16px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 600;">Shop Now</a>
        </div>
    </div>
    
    <div style="display: table-cell; width: 33.33%; padding: 0 10px; vertical-align: top;">
        <div style="text-align: center; padding: 20px; border: 1px solid #E5E5E5; border-radius: 8px; background-color: #F8F8F8;">
            <h4 style="margin: 0 0 10px 0; color: #8B7355;">Living Room</h4>
            <p style="margin: 0 0 15px 0; color: #666; font-size: 14px;">Sofas, coffee tables, and storage</p>
            <a href="{{ url('/products?category=living-room') }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 6px 16px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 600;">Shop Now</a>
        </div>
    </div>
</div>

<h2 style="color: #8B7355; margin: 30px 0 15px 0;">Stay Connected</h2>
<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <p style="color: #555; margin: 0 0 15px 0;">Follow us on social media for design inspiration, new product announcements, and exclusive offers:</p>
    <div style="text-align: center; margin: 20px 0;">
        <a href="#" style="display: inline-block; margin: 0 10px; color: #8B7355; text-decoration: none;">LinkedIn</a> | 
        <a href="#" style="display: inline-block; margin: 0 10px; color: #8B7355; text-decoration: none;">Instagram</a> | 
        <a href="#" style="display: inline-block; margin: 0 10px; color: #8B7355; text-decoration: none;">Facebook</a> | 
        <a href="#" style="display: inline-block; margin: 0 10px; color: #8B7355; text-decoration: none;">Twitter</a>
    </div>
</div>

<h2 style="color: #8B7355; margin: 30px 0 15px 0;">Need Help?</h2>
<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <p style="color: #555; margin: 0 0 15px 0;">Our customer service team is here to help you with any questions:</p>
    <ul style="margin: 0; padding-left: 20px; color: #555;">
        <li><strong>Phone:</strong> +63 (917) 123-4567</li>
        <li><strong>Email:</strong> hello@davidswood.shop</li>
        <li><strong>Live Chat:</strong> Available on our website</li>
        <li><strong>Showroom:</strong> 123 Santa Rosa - Tagaytay Rd, Silang, 4118 Cavite</li>
    </ul>
</div>

<div style="text-align: center; margin: 40px 0;">
    <a href="{{ url('/products') }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px;">Start Shopping</a>
</div>

<p style="color: #555; margin: 20px 0;">Thank you for choosing David's Wood Furnitures. We look forward to helping you create beautiful spaces in your home!</p>

<p style="color: #555; margin: 20px 0;">Best regards,<br>
The David's Wood Furnitures Team</p>
@endsection

