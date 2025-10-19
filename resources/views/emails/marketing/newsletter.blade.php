@extends('emails.layouts.branded')

@section('content')
<h1>Latest News & Featured Products</h1>

<p>Hello {{ $subscriber->name ?? 'Valued Customer' }},</p>

<p>Thank you for subscribing to our newsletter! Here's what's new at David's Wood Furniture this month.</p>

@if($featuredProducts && count($featuredProducts) > 0)
<h2>Featured Products</h2>
<div class="info-box">
    <p>Discover our handpicked selection of premium furniture pieces, crafted with the finest materials and attention to detail.</p>
</div>

@foreach($featuredProducts as $product)
<div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin: 20px 0; background-color: #f9fafb;">
    <h3 style="color: #1e40af; margin: 0 0 10px 0;">{{ $product->name }}</h3>
    <p style="margin: 0 0 15px 0; color: #6b7280;">{{ Str::limit($product->description, 150) }}</p>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin: 15px 0;">
        <div>
            <span style="font-size: 24px; font-weight: 600; color: #1e40af;">${{ number_format($product->price, 2) }}</span>
            @if($product->sale_price && $product->sale_price < $product->price)
                <span style="text-decoration: line-through; color: #9ca3af; margin-left: 10px;">${{ number_format($product->price, 2) }}</span>
                <span style="background-color: #ef4444; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px; margin-left: 10px;">SALE</span>
            @endif
        </div>
        <a href="{{ url('/products/' . $product->id) }}" class="button" style="padding: 8px 20px; font-size: 14px;">View Product</a>
    </div>
    
    @if($product->average_rating > 0)
    <div style="margin: 10px 0;">
        <span class="rating">
            @for($i = 1; $i <= 5; $i++)
                <span class="star">{{ $i <= $product->average_rating ? '★' : '☆' }}</span>
            @endfor
        </span>
        <span style="color: #6b7280; font-size: 14px;">({{ number_format($product->average_rating, 1) }}/5 - {{ $product->reviews_count ?? 0 }} reviews)</span>
    </div>
    @endif
</div>
@endforeach
@endif

@if($promotions && count($promotions) > 0)
<h2>Special Offers</h2>
@foreach($promotions as $promotion)
<div class="info-box" style="border-left-color: #10b981; background-color: #f0fdf4;">
    <h3 style="color: #059669; margin: 0 0 10px 0;">{{ $promotion->title }}</h3>
    <p style="margin: 0 0 15px 0;">{{ $promotion->description }}</p>
    @if($promotion->discount_code)
        <p style="margin: 0 0 15px 0;"><strong>Use Code:</strong> <span style="background-color: #059669; color: white; padding: 4px 8px; border-radius: 4px; font-family: monospace;">{{ $promotion->discount_code }}</span></p>
    @endif
    @if($promotion->valid_until)
        <p style="margin: 0; color: #059669; font-weight: 600;">Valid until {{ \Carbon\Carbon::parse($promotion->valid_until)->format('M d, Y') }}</p>
    @endif
</div>
@endforeach
@endif

<h2>What's New</h2>
<div class="info-box">
    <h3>New Collection: Modern Minimalist</h3>
    <p>Introducing our latest collection featuring clean lines, natural wood finishes, and contemporary design. Perfect for modern homes and offices.</p>
    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ url('/products?collection=modern-minimalist') }}" class="button">Explore Collection</a>
    </div>
</div>

<div class="info-box">
    <h3>Custom Furniture Services</h3>
    <p>Looking for something unique? Our master craftsmen can create custom furniture pieces tailored to your exact specifications and style preferences.</p>
    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ url('/custom-furniture') }}" class="button">Learn More</a>
    </div>
</div>

<h2>Customer Spotlight</h2>
<div class="info-box">
    <p style="font-style: italic; margin: 0 0 15px 0;">"The quality of David's Wood Furniture exceeded my expectations. The craftsmanship is outstanding, and the customer service was exceptional. I'll definitely be a returning customer!"</p>
    <p style="margin: 0; text-align: right; color: #6b7280;">- Sarah M., Happy Customer</p>
</div>

<h2>Upcoming Events</h2>
<div class="info-box">
    <h3>Furniture Care Workshop</h3>
    <p><strong>Date:</strong> Saturday, March 15th, 2024</p>
    <p><strong>Time:</strong> 10:00 AM - 12:00 PM</p>
    <p><strong>Location:</strong> Our Showroom</p>
    <p>Learn how to properly care for and maintain your wood furniture to keep it looking beautiful for years to come. Free workshop with refreshments provided.</p>
    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ url('/events/furniture-care-workshop') }}" class="button">Register Now</a>
    </div>
</div>

<div style="text-align: center; margin: 40px 0;">
    <a href="{{ url('/products') }}" class="button">Shop All Products</a>
    <a href="{{ url('/showroom') }}" class="button" style="background: #6b7280; margin-left: 10px;">Visit Showroom</a>
</div>

<p>Thank you for being part of the David's Wood Furniture family. We appreciate your continued support and look forward to helping you create beautiful spaces in your home.</p>

<p>Happy shopping!</p>
@endsection

