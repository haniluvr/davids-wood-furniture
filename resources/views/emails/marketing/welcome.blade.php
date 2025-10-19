@extends('emails.layouts.branded')

@section('content')
<h1>Welcome to David's Wood Furniture!</h1>

<p>Hello {{ $user->name }},</p>

<p>Welcome to David's Wood Furniture! We're thrilled to have you join our community of furniture enthusiasts and home decor lovers.</p>

<div class="info-box" style="border-left-color: #10b981; background-color: #f0fdf4;">
    <h2 style="color: #059669;">🎉 Welcome Gift</h2>
    <p>As a thank you for joining us, we're giving you a <strong>10% discount</strong> on your first purchase!</p>
    <p><strong>Use Code:</strong> <span style="background-color: #059669; color: white; padding: 4px 8px; border-radius: 4px; font-family: monospace; font-size: 16px;">WELCOME10</span></p>
    <p style="margin: 0; color: #059669; font-weight: 600;">Valid for 30 days from today</p>
</div>

<h2>What You Can Expect</h2>
<div class="info-box">
    <h3>🏠 Quality Furniture</h3>
    <p>Discover our carefully curated collection of handcrafted wood furniture, from dining tables to bedroom sets, all made with premium materials and expert craftsmanship.</p>
</div>

<div class="info-box">
    <h3>🚚 Free Shipping</h3>
    <p>Enjoy free shipping on orders over $500. We carefully package and deliver your furniture right to your door.</p>
</div>

<div class="info-box">
    <h3>🛠️ Expert Support</h3>
    <p>Our knowledgeable team is here to help you find the perfect pieces for your home. From design advice to assembly assistance, we've got you covered.</p>
</div>

<h2>Getting Started</h2>
<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/products') }}" class="button">Browse Products</a>
    <a href="{{ url('/account') }}" class="button" style="background: #6b7280; margin-left: 10px;">My Account</a>
</div>

<h2>Popular Categories</h2>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0;">
    <div style="text-align: center; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; background-color: #f9fafb;">
        <h4 style="margin: 0 0 10px 0; color: #1e40af;">Dining Room</h4>
        <p style="margin: 0 0 15px 0; color: #6b7280; font-size: 14px;">Tables, chairs, and storage</p>
        <a href="{{ url('/products?category=dining-room') }}" class="button" style="padding: 6px 16px; font-size: 14px;">Shop Now</a>
    </div>
    
    <div style="text-align: center; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; background-color: #f9fafb;">
        <h4 style="margin: 0 0 10px 0; color: #1e40af;">Bedroom</h4>
        <p style="margin: 0 0 15px 0; color: #6b7280; font-size: 14px;">Beds, dressers, and nightstands</p>
        <a href="{{ url('/products?category=bedroom') }}" class="button" style="padding: 6px 16px; font-size: 14px;">Shop Now</a>
    </div>
    
    <div style="text-align: center; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; background-color: #f9fafb;">
        <h4 style="margin: 0 0 10px 0; color: #1e40af;">Living Room</h4>
        <p style="margin: 0 0 15px 0; color: #6b7280; font-size: 14px;">Sofas, coffee tables, and storage</p>
        <a href="{{ url('/products?category=living-room') }}" class="button" style="padding: 6px 16px; font-size: 14px;">Shop Now</a>
    </div>
</div>

<h2>Stay Connected</h2>
<div class="info-box">
    <p>Follow us on social media for design inspiration, new product announcements, and exclusive offers:</p>
    <div style="text-align: center; margin: 20px 0;">
        <a href="#" style="display: inline-block; margin: 0 10px; color: #3b82f6; text-decoration: none;">📘 Facebook</a>
        <a href="#" style="display: inline-block; margin: 0 10px; color: #e1306c; text-decoration: none;">📷 Instagram</a>
        <a href="#" style="display: inline-block; margin: 0 10px; color: #1da1f2; text-decoration: none;">🐦 Twitter</a>
        <a href="#" style="display: inline-block; margin: 0 10px; color: #bd081c; text-decoration: none;">📌 Pinterest</a>
    </div>
</div>

<h2>Need Help?</h2>
<div class="info-box">
    <p>Our customer service team is here to help you with any questions:</p>
    <ul style="margin: 0; padding-left: 20px;">
        <li><strong>Phone:</strong> (555) 123-4567</li>
        <li><strong>Email:</strong> support@davidswood.com</li>
        <li><strong>Live Chat:</strong> Available on our website</li>
        <li><strong>Showroom:</strong> Visit us at 123 Woodcraft Lane, Furniture City</li>
    </ul>
</div>

<div style="text-align: center; margin: 40px 0;">
    <a href="{{ url('/products') }}" class="button">Start Shopping</a>
</div>

<p>Thank you for choosing David's Wood Furniture. We look forward to helping you create beautiful spaces in your home!</p>

<p>Best regards,<br>
The David's Wood Furniture Team</p>
@endsection

