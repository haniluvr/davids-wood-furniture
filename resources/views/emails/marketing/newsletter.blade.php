@extends('emails.layouts.branded')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #8B7355; margin: 0;">Latest News & Featured Products</h1>
    <p style="color: #666; margin: 10px 0 0 0; font-style: italic;">David's Wood Furnitures</p>
</div>

<div style="background: #F8F8F8; padding: 30px; border-radius: 8px; margin-bottom: 30px; border: 0.75px solid #8B7355;">
    <h2 style="color: #8B7355; margin: 0 0 20px 0;">Hello {{ $subscriber->name ?? 'Valued Customer' }},</h2>
    
    <p style="color: #555; line-height: 1.6; margin: 0;">
        Thank you for subscribing to our newsletter! Here's what's new at David's Wood Furnitures this month.
    </p>
</div>

@if($featuredProducts && count($featuredProducts) > 0)
<h2 style="color: #8B7355; margin: 30px 0 15px 0;">Featured Products</h2>
<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <p style="color: #555; margin: 0;">Discover our handpicked selection of premium furniture pieces, crafted with the finest materials and attention to detail.</p>
</div>

@foreach($featuredProducts as $product)
<div style="border: 1px solid #E5E5E5; border-radius: 8px; padding: 20px; margin: 20px 0; background-color: #F8F8F8;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">{{ $product->name }}</h3>
    <p style="margin: 0 0 15px 0; color: #666;">{{ Str::limit($product->description, 150) }}</p>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin: 15px 0;">
        <div>
            <span style="font-size: 24px; font-weight: 600; color: #8B7355;">₱{{ number_format($product->price, 2) }}</span>
            @if($product->sale_price && $product->sale_price < $product->price)
                <span style="text-decoration: line-through; color: #999; margin-left: 10px;">₱{{ number_format($product->price, 2) }}</span>
                <span style="background-color: #8B7355; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px; margin-left: 10px;">SALE</span>
            @endif
        </div>
        <div>
            <a href="{{ url('/products/' . $product->id) }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 8px 20px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 600; display: inline-block;">View Product</a>
        </div>
    </div>
    
    @if($product->average_rating > 0)
    <div style="margin: 10px 0;">
        <span style="color: #fbbf24; font-size: 18px;">
            @for($i = 1; $i <= 5; $i++)
                <span style="display: inline-block; margin-right: 2px;">{{ $i <= $product->average_rating ? '★' : '☆' }}</span>
            @endfor
        </span>
        <span style="color: #666; font-size: 14px;">({{ number_format($product->average_rating, 1) }}/5 - {{ $product->reviews_count ?? 0 }} reviews)</span>
    </div>
    @endif
</div>
@endforeach
@endif

@if($promotions && count($promotions) > 0)
<h2 style="color: #8B7355; margin: 30px 0 15px 0;">Special Offers</h2>
@foreach($promotions as $promotion)
<div style="background: #efe3df; border: 1px solid #8B7355; padding: 20px; border-radius: 6px; margin: 20px 0;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">{{ $promotion->title }}</h3>
    <p style="margin: 0 0 15px 0; color: #555;">{{ $promotion->description }}</p>
    @if($promotion->discount_code)
        <p style="margin: 0 0 15px 0; color: #555;"><strong>Use Code:</strong> <span style="background-color: #8B7355; color: white; padding: 4px 8px; border-radius: 4px; font-family: monospace;">{{ $promotion->discount_code }}</span></p>
    @endif
    @if($promotion->valid_until)
        <p style="margin: 0; color: #8B7355; font-weight: 600;">Valid until {{ \Carbon\Carbon::parse($promotion->valid_until)->format('M d, Y') }}</p>
    @endif
</div>
@endforeach
@endif

<h2 style="color: #8B7355; margin: 30px 0 15px 0;">What's New</h2>
<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">New Collection: Modern Minimalist</h3>
    <p style="color: #555; margin: 0 0 15px 0;">Introducing our latest collection featuring clean lines, natural wood finishes, and contemporary design. Perfect for modern homes and offices.</p>
    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ url('/products?collection=modern-minimalist') }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px;">Explore Collection</a>
    </div>
</div>

<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">Custom Furniture Services</h3>
    <p style="color: #555; margin: 0 0 15px 0;">Looking for something unique? Our master craftsmen can create custom furniture pieces tailored to your exact specifications and style preferences.</p>
    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ url('/custom-furniture') }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px;">Learn More</a>
    </div>
</div>

<div style="text-align: center; margin: 40px 0;">
    <a href="{{ url('/products') }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px; margin: 5px;">Shop All Products</a>
    <a href="{{ url('/showroom') }}" style="background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px; margin: 5px;">Visit Showroom</a>
</div>

<p style="color: #555; margin: 20px 0;">Thank you for being part of the David's Wood Furnitures family. We appreciate your continued support and look forward to helping you create beautiful spaces in your home.</p>

<p style="color: #555; margin: 20px 0;">Happy shopping!</p>
@endsection

