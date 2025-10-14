@extends('layouts.app')

@section('title', $product->name . ' - David\'s Wood Furniture')

@section('content')
<style>
/* Product Details Styles */
.product-details-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 2rem;
    font-size: 0.875rem;
    color: #666;
}

.breadcrumb a {
    color: #666;
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb a:hover {
    color: #1a1a1a;
}

.breadcrumb .separator {
    color: #ccc;
}

.product-grid-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-bottom: 4rem;
}

@media (max-width: 968px) {
    .product-grid-layout {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}

/* Image Gallery */
.image-gallery {
    position: sticky;
    top: 80px;
    height: fit-content;
}

.main-image-container {
    position: relative;
    width: 100%;
    aspect-ratio: 1;
    background: #f9f9f9;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    cursor: zoom-in;
    transition: transform 0.3s ease;
}

.main-image:hover {
    transform: scale(1.05);
}

.badge-container {
    position: absolute;
    top: 1rem;
    left: 1rem;
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    backdrop-filter: blur(10px);
}

.badge-sale {
    background: rgba(220, 38, 38, 0.9);
    color: white;
}

.badge-featured {
    background: rgba(16, 185, 129, 0.9);
    color: white;
}

.badge-stock {
    background: rgba(59, 130, 246, 0.9);
    color: white;
}

.badge-low-stock {
    background: rgba(245, 158, 11, 0.9);
    color: white;
}

.badge-out-of-stock {
    background: rgba(107, 114, 128, 0.9);
    color: white;
}

.thumbnail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 0.75rem;
}

.thumbnail {
    aspect-ratio: 1;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.2s ease;
}

.thumbnail:hover {
    border-color: #1a1a1a;
}

.thumbnail.active {
    border-color: #1a1a1a;
    box-shadow: 0 0 0 2px rgba(26, 26, 26, 0.1);
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Product Info */
.product-info {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.product-title {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1.2;
    color: #1a1a1a;
    margin: 0;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
}

.stars {
    display: flex;
    gap: 0.25rem;
    color: #fbbf24;
}

.review-count {
    color: #666;
}

.price-section {
    display: flex;
    align-items: baseline;
    gap: 1rem;
    padding: 1.5rem 0;
    border-top: 1px solid #e5e5e5;
    border-bottom: 1px solid #e5e5e5;
}

.current-price {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a1a1a;
}

.original-price {
    font-size: 1.5rem;
    color: #999;
    text-decoration: line-through;
}

.discount-badge {
    background: #ef4444;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
}

.product-description {
    font-size: 1rem;
    line-height: 1.75;
    color: #444;
}

/* Specifications */
.specifications {
    background: #f9fafb;
    border-radius: 12px;
    padding: 1.5rem;
}

.specifications h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #1a1a1a;
}

.spec-grid {
    display: grid;
    gap: 1rem;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    align-items: start;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.spec-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.spec-label {
    font-weight: 600;
    color: #666;
    min-width: 140px;
}

.spec-value {
    color: #1a1a1a;
    text-align: right;
    flex: 1;
}

/* Actions */
.action-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1.5rem;
    background: #f9fafb;
    border-radius: 12px;
}

.quantity-selector {
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: none;
    border: none;
}

.quantity-label {
    font-weight: 600;
    color: #1a1a1a;
}

.quantity-controls {
    display: inline-flex;
    align-items: center;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    overflow: hidden;
    background: white;
    box-shadow: none !important;
}

.quantity-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
    color: #1a1a1a;
    font-size: 1.25rem;
    font-weight: 600;
    padding: 0;
    outline: none;
    box-shadow: none !important;
}

.quantity-btn:hover {
    background: #f3f4f6;
}

.quantity-btn:focus {
    outline: none;
    box-shadow: none !important;
}

.quantity-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.quantity-input {
    width: 60px;
    height: 40px;
    border: none;
    border-left: 1px solid #e5e7eb;
    border-right: 1px solid #e5e7eb;
    text-align: center;
    font-weight: 600;
    font-size: 1rem;
    background: white;
    outline: none !important;
    box-shadow: none !important;
    -webkit-appearance: none;
    -moz-appearance: textfield;
}

.quantity-input:focus {
    outline: none !important;
    box-shadow: none !important;
    border-left: 1px solid #e5e7eb;
    border-right: 1px solid #e5e7eb;
}

/* Hide number input spinner arrows */
.quantity-input::-webkit-inner-spin-button,
.quantity-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
    display: none;
}

.quantity-input[type=number] {
    -moz-appearance: textfield;
}

.action-buttons {
    display: flex;
    gap: 1rem;
}

.btn-add-cart {
    flex: 1;
    padding: 1rem 2rem;
    background: #1a1a1a;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-add-cart:hover {
    background: #2a2a2a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-add-cart:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-wishlist {
    width: 56px;
    height: 56px;
    background: white;
    border: 2px solid #e5e5e5;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-wishlist:hover {
    border-color: #1a1a1a;
    background: #f9fafb;
}

.btn-wishlist.active {
    background: #fef2f2;
    border-color: #ef4444;
    color: #ef4444;
}

.btn-wishlist.active i {
    color: #ef4444;
}

.btn-wishlist i.active {
    color: #ef4444;
    fill: #ef4444;
}

/* Tabs */
.product-tabs {
    border-bottom: 2px solid #e5e5e5;
    margin-bottom: 2rem;
}

.tab-buttons {
    display: flex;
    gap: 2rem;
}

.tab-btn {
    padding: 1rem 0;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    font-weight: 600;
    font-size: 1rem;
    color: #666;
    cursor: pointer;
    transition: all 0.2s;
    margin-bottom: -2px;
}

.tab-btn:hover {
    color: #1a1a1a;
}

.tab-btn.active {
    color: #1a1a1a;
    border-bottom-color: #1a1a1a;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Related Products */
.related-products {
    margin-top: 5rem;
}

.related-products h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 2rem;
    text-align: center;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
}

/* Stock Status */
.stock-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
}

.stock-status.in-stock {
    background: #d1fae5;
    color: #065f46;
}

.stock-status.low-stock {
    background: #fef3c7;
    color: #92400e;
}

.stock-status.out-of-stock {
    background: #fee2e2;
    color: #991b1b;
}

.stock-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}
</style>

<div class="product-details-container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="separator">/</span>
        <a href="{{ route('products') }}">Products</a>
        <span class="separator">/</span>
        @if($product->category)
            <a href="{{ route('products', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
            <span class="separator">/</span>
        @endif
        <span>{{ $product->name }}</span>
    </nav>

    <!-- Product Grid -->
    <div class="product-grid-layout">
        <!-- Image Gallery -->
        <div class="image-gallery">
            <div class="main-image-container">
                @php
                    $mainImage = $product->images && is_array($product->images) && count($product->images) > 0 
                        ? asset('storage/' . $product->images[0]) 
                        : asset('frontend/assets/placeholder.jpg');
                @endphp
                <img 
                    src="{{ $mainImage }}" 
                    alt="{{ $product->name }}" 
                    class="main-image" 
                    id="mainImage"
                >
                
                <!-- Badges -->
                <div class="badge-container">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="badge badge-sale">
                            Save {{ $product->discount_percentage }}%
                        </span>
                    @endif
                    
                    @if($product->featured)
                        <span class="badge badge-featured">Featured</span>
                    @endif
                    
                    @if($product->stock_quantity > 0 && $product->stock_quantity <= 10)
                        <span class="badge badge-low-stock">Only {{ $product->stock_quantity }} left</span>
                    @elseif($product->stock_quantity > 0)
                        <span class="badge badge-stock">In Stock</span>
                    @else
                        <span class="badge badge-out-of-stock">Out of Stock</span>
                    @endif
                </div>
            </div>

            <!-- Thumbnails -->
            @if($product->images && is_array($product->images) && count($product->images) > 1)
                <div class="thumbnail-grid">
                    @foreach($product->images as $index => $image)
                        <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeImage('{{ asset('storage/' . $image) }}', this)">
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }} - Image {{ $index + 1 }}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="product-info">
            <div>
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <!-- Rating -->
                <div class="product-rating">
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($product->average_rating))
                                <i data-lucide="star" class="w-5 h-5" style="fill: currentColor;"></i>
                            @elseif($i - 0.5 <= $product->average_rating)
                                <i data-lucide="star-half" class="w-5 h-5" style="fill: currentColor;"></i>
                            @else
                                <i data-lucide="star" class="w-5 h-5"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="font-semibold">{{ number_format($product->average_rating, 1) }}</span>
                    <span class="review-count">({{ $product->reviews_count }} {{ Str::plural('review', $product->reviews_count) }})</span>
                </div>
            </div>

            <!-- Price -->
            <div class="price-section">
                <span class="current-price">₱{{ number_format($product->current_price, 2) }}</span>
                
                @if($product->sale_price && $product->sale_price < $product->price)
                    <span class="original-price">₱{{ number_format($product->price, 2) }}</span>
                    <span class="discount-badge">-{{ $product->discount_percentage }}%</span>
                @endif
            </div>

            <!-- Description -->
            <div class="product-description">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.75rem; color: #1a1a1a;">Description</h3>
                <p style="line-height: 1.8; color: #444;">{{ $product->description }}</p>
            </div>

            <!-- Specifications -->
            <div class="specifications">
                <h3>Product Specifications</h3>
                <div class="spec-grid">
                    @if($product->sku)
                        <div class="spec-item">
                            <span class="spec-label">
                                <i data-lucide="hash" class="w-4 h-4 inline mr-1"></i>
                                SKU
                            </span>
                            <span class="spec-value">{{ $product->sku }}</span>
                        </div>
                    @endif
                    
                    @if($product->material)
                        <div class="spec-item">
                            <span class="spec-label">
                                <i data-lucide="tree-deciduous" class="w-4 h-4 inline mr-1"></i>
                                Material
                            </span>
                            <span class="spec-value">{{ $product->material }}</span>
                        </div>
                    @endif
                    
                    @if($product->dimensions)
                        <div class="spec-item">
                            <span class="spec-label">
                                <i data-lucide="ruler" class="w-4 h-4 inline mr-1"></i>
                                Dimensions
                            </span>
                            <span class="spec-value">{{ $product->dimensions }}</span>
                        </div>
                    @endif
                    
                    @if($product->weight)
                        <div class="spec-item">
                            <span class="spec-label">
                                <i data-lucide="weight" class="w-4 h-4 inline mr-1"></i>
                                Weight
                            </span>
                            <span class="spec-value">{{ $product->weight }}</span>
                        </div>
                    @endif
                    
                    @if($product->category)
                        <div class="spec-item">
                            <span class="spec-label">
                                <i data-lucide="tag" class="w-4 h-4 inline mr-1"></i>
                                Category
                            </span>
                            <span class="spec-value">{{ $product->category->name }}</span>
                        </div>
                    @endif
                    
                    <div class="spec-item">
                        <span class="spec-label">
                            <i data-lucide="package" class="w-4 h-4 inline mr-1"></i>
                            Availability
                        </span>
                        <span class="spec-value">
                            @if($product->stock_quantity > 0)
                                <span class="stock-status in-stock">
                                    <span class="stock-indicator"></span>
                                    In Stock ({{ $product->stock_quantity }} available)
                                </span>
                            @else
                                <span class="stock-status out-of-stock">
                                    <span class="stock-indicator"></span>
                                    Out of Stock
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="action-section">
                @if($product->stock_quantity > 0)
                    <div class="quantity-selector">
                        <span class="quantity-label">Quantity:</span>
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn" onclick="decrementQuantity()">-</button>
                            <input type="number" class="quantity-input" id="productQuantity" value="1" min="1" max="{{ $product->stock_quantity }}" readonly>
                            <button type="button" class="quantity-btn" onclick="incrementQuantity()">+</button>
                        </div>
                    </div>
                @endif

                <div class="action-buttons">
                    <button 
                        class="btn-add-cart" 
                        id="addToCartBtn"
                        data-product-id="{{ $product->id }}"
                        {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}
                    >
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        <span>{{ $product->stock_quantity > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
                    </button>
                    
                    <button 
                        class="btn-wishlist" 
                        id="wishlistBtn-{{ $product->id }}"
                        data-product-id="{{ $product->id }}"
                        title="Add to Wishlist"
                    >
                        <i data-lucide="heart" class="w-6 h-6" id="heart-icon-{{ $product->id }}"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Reviews Section -->
    <div class="reviews-section" style="margin-top: 4rem; margin-bottom: 4rem;">
        <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem;">Customer Reviews</h2>
        
        <!-- Reviews Summary -->
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem; margin-bottom: 3rem; background: #f9fafb; padding: 2rem; border-radius: 12px;">
            <!-- Overall Rating -->
            <div style="text-align: center; border-right: 1px solid #e5e7eb; padding-right: 2rem;">
                <div style="font-size: 3.5rem; font-weight: 700; color: #1a1a1a;">{{ number_format($product->average_rating, 1) }}</div>
                <div class="stars" style="display: flex; justify-content: center; gap: 0.25rem; margin: 0.5rem 0; color: #fbbf24;">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->average_rating))
                            <i data-lucide="star" class="w-6 h-6" style="fill: currentColor;"></i>
                        @elseif($i - 0.5 <= $product->average_rating)
                            <i data-lucide="star-half" class="w-6 h-6" style="fill: currentColor;"></i>
                        @else
                            <i data-lucide="star" class="w-6 h-6"></i>
                        @endif
                    @endfor
                </div>
                <p style="color: #666; font-size: 0.875rem;">Based on {{ $product->reviews_count }} {{ Str::plural('review', $product->reviews_count) }}</p>
            </div>
            
            <!-- Rating Distribution -->
            <div>
                @foreach([5,4,3,2,1] as $rating)
                    @php
                        $count = $ratingDistribution[$rating];
                        $percentage = $product->reviews_count > 0 ? ($count / $product->reviews_count) * 100 : 0;
                    @endphp
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                        <span style="width: 60px; font-size: 0.875rem; color: #666;">{{ $rating }} stars</span>
                        <div style="flex: 1; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; background: #fbbf24; width: {{ $percentage }}%;"></div>
                        </div>
                        <span style="width: 50px; text-align: right; font-size: 0.875rem; color: #666;">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Write Review Button (for authenticated users) -->
        @auth
            <div style="margin-bottom: 2rem;">
                <button 
                    id="writeReviewBtn" 
                    style="padding: 0.75rem 1.5rem; background: #1a1a1a; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;"
                    onclick="showReviewForm()"
                >
                    <i data-lucide="pencil" class="w-5 h-5"></i>
                    Write a Review
                </button>
            </div>

            <!-- Review Form (hidden by default) -->
            <div id="reviewForm" style="display: none; background: #f9fafb; padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Write Your Review</h3>
                <form id="submitReviewForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <!-- Rating -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Rating *</label>
                        <div class="star-rating" style="display: flex; gap: 0.5rem; font-size: 2rem; color: #d1d5db; cursor: pointer;">
                            <i data-lucide="star" class="rating-star" data-rating="1"></i>
                            <i data-lucide="star" class="rating-star" data-rating="2"></i>
                            <i data-lucide="star" class="rating-star" data-rating="3"></i>
                            <i data-lucide="star" class="rating-star" data-rating="4"></i>
                            <i data-lucide="star" class="rating-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="selectedRating" required>
                    </div>

                    <!-- Title -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Review Title</label>
                        <input 
                            type="text" 
                            name="title" 
                            style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;"
                            placeholder="e.g., Great quality furniture!"
                        >
                    </div>

                    <!-- Review Text -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Your Review *</label>
                        <textarea 
                            name="review" 
                            rows="5" 
                            style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;"
                            placeholder="Share your experience with this product..."
                            required
                            minlength="10"
                        ></textarea>
                    </div>

                    <!-- Order Selection -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Select Your Order *</label>
                        <select 
                            name="order_id" 
                            style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;"
                            required
                        >
                            <option value="">Select an order</option>
                            <!-- Orders will be populated via JavaScript -->
                        </select>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <button 
                            type="submit" 
                            style="padding: 0.75rem 2rem; background: #1a1a1a; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;"
                        >
                            Submit Review
                        </button>
                        <button 
                            type="button" 
                            onclick="hideReviewForm()" 
                            style="padding: 0.75rem 2rem; background: #e5e7eb; color: #1a1a1a; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div style="margin-bottom: 2rem; padding: 1rem; background: #f0f9ff; border: 1px solid #bfdbfe; border-radius: 8px; color: #1e40af;">
                <i data-lucide="info" class="w-5 h-5 inline mr-2"></i>
                Please <a href="#" onclick="showmodallogin(); return false;" style="text-decoration: underline; font-weight: 600;">log in</a> to write a review.
            </div>
        @endauth

        <!-- Reviews List -->
        <div class="reviews-list">
            @forelse($reviews as $review)
                <div style="border-bottom: 1px solid #e5e7eb; padding: 1.5rem 0;">
                    <div style="display: flex; justify-content: space-between; align-items-start; margin-bottom: 1rem;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <div class="stars" style="display: flex; gap: 0.25rem; color: #fbbf24;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i data-lucide="star" class="w-4 h-4" style="fill: currentColor;"></i>
                                        @else
                                            <i data-lucide="star" class="w-4 h-4"></i>
                                        @endif
                                    @endfor
                                </div>
                                @if($review->is_verified_purchase)
                                    <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                                        <i data-lucide="check-circle" class="w-3 h-3 inline"></i> Verified Purchase
                                    </span>
                                @endif
                            </div>
                            @if($review->title)
                                <h4 style="font-weight: 600; font-size: 1.125rem; margin-bottom: 0.5rem;">{{ $review->title }}</h4>
                            @endif
                            <p style="color: #666; font-size: 0.875rem;">
                                By {{ $review->user->first_name }} {{ substr($review->user->last_name, 0, 1) }}. on {{ $review->created_at->format('F d, Y') }}
                            </p>
                        </div>
                    </div>
                    <p style="color: #444; line-height: 1.75;">{{ $review->review }}</p>
                </div>
            @empty
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <i data-lucide="message-circle" class="w-12 h-12 mx-auto mb-2" style="opacity: 0.5;"></i>
                    <p>No reviews yet. Be the first to review this product!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

    <!-- Related Products -->
    @if($relatedProducts && count($relatedProducts) > 0)
        <div class="related-products">
            <h2>You May Also Like</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="related-products-grid">
                <!-- Related products will be rendered here via JavaScript or server-side -->
                @foreach($relatedProducts as $relatedProduct)
                    <div class="card product-card flex flex-col h-full rounded-2xl border bg-white">
                        <div class="relative">
                            @php
                                $relatedImage = $relatedProduct->images && is_array($relatedProduct->images) && count($relatedProduct->images) > 0 
                                    ? asset('storage/' . $relatedProduct->images[0]) 
                                    : asset('frontend/assets/placeholder.jpg');
                            @endphp
                            <img src="{{ $relatedImage }}" class="w-full h-64 object-cover rounded-t-2xl" alt="{{ $relatedProduct->name }}">
                            <div class="absolute top-4 right-4">
                                <button class="wishlist-btn" data-product-id="{{ $relatedProduct->id }}">
                                    <i data-lucide="heart" class="heart-toggle-icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                <div class="md:col-span-2">
                                    <h6 class="product-title text-lg font-semibold">
                                        {{ $relatedProduct->name }}
                                    </h6>
                                    <p class="product-desc text-sm text-gray-600">{{ Str::limit($relatedProduct->short_description ?? $relatedProduct->description, 60) }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-gray-500 text-sm">Price</div>
                                    <div class="price text-xl font-bold">₱{{ number_format($relatedProduct->current_price, 0) }}</div>
                                    <span class="rating flex items-center justify-end mt-1">
                                        <i data-lucide="star" class="lucide-small mr-1"></i> {{ number_format($relatedProduct->average_rating, 1) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-auto flex p-4 justify-between">
                            <button class="btn btn-quick-view max-w-[45%] shrink flex items-center justify-center py-2 px-0" data-product-id="{{ $relatedProduct->id }}" data-product-slug="{{ $relatedProduct->slug }}">
                                <i data-lucide="proportions" class="lucide-small"></i> 
                                <span class="font-medium ml-2">Quick view</span>
                            </button>
                            <button class="btn btn-add-to-cart max-w-[45%] shrink flex items-center justify-center py-2 px-0" data-product-id="{{ $relatedProduct->id }}">
                                <i data-lucide="shopping-cart" class="lucide-small"></i> 
                                <span class="font-medium ml-2">Add to cart</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Initialize wishlist button state
    initWishlistButton();
    
    // Initialize add to cart button
    initAddToCartButton();

    // Initialize related products buttons
    initRelatedProductsButtons();
});

// Image Gallery
function changeImage(imageUrl, thumbnail) {
    const mainImage = document.getElementById('mainImage');
    mainImage.src = imageUrl;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    thumbnail.classList.add('active');
}

// Quantity Controls
function incrementQuantity() {
    const input = document.getElementById('productQuantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    
    if (current < max) {
        input.value = current + 1;
    }
}

function decrementQuantity() {
    const input = document.getElementById('productQuantity');
    const min = parseInt(input.getAttribute('min'));
    const current = parseInt(input.value);
    
    if (current > min) {
        input.value = current - 1;
    }
}

// Wishlist
async function initWishlistButton() {
    const wishlistBtn = document.getElementById('wishlistBtn');
    if (!wishlistBtn) return;
    
    const productId = wishlistBtn.getAttribute('data-product-id');
    
    // Check if product is in wishlist
    try {
        const response = await fetch(`/api/wishlist/check/${productId}`);
        const data = await response.json();
        
        if (data.in_wishlist) {
            wishlistBtn.classList.add('active');
            const icon = document.getElementById('wishlistIcon');
            if (icon) {
                icon.setAttribute('data-lucide', 'heart');
                icon.style.fill = 'currentColor';
            }
        }
    } catch (error) {
        console.error('Error checking wishlist status:', error);
    }
    
    // Add click handler
    wishlistBtn.addEventListener('click', async function() {
        try {
            const response = await fetch('/api/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.classList.toggle('active');
                const icon = document.getElementById('wishlistIcon');
                
                if (data.action === 'added') {
                    icon.style.fill = 'currentColor';
                } else {
                    icon.style.fill = 'none';
                }
                
                // Reinitialize icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        } catch (error) {
            console.error('Error toggling wishlist:', error);
        }
    });
}

// Add to Cart
function initAddToCartButton() {
    const addToCartBtn = document.getElementById('addToCartBtn');
    if (!addToCartBtn) return;
    
    addToCartBtn.addEventListener('click', async function() {
        const productId = this.getAttribute('data-product-id');
        const quantityInput = document.getElementById('productQuantity');
        const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
        
        // Disable button during request
        this.disabled = true;
        const originalText = this.querySelector('span').textContent;
        this.querySelector('span').textContent = 'Adding...';
        
        try {
            const response = await fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.querySelector('span').textContent = 'Added!';
                
                // Update cart count in navbar if available
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    const currentCount = parseInt(cartCount.textContent) || 0;
                    cartCount.textContent = currentCount + quantity;
                }
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    this.querySelector('span').textContent = originalText;
                    this.disabled = false;
                }, 2000);
            } else {
                this.querySelector('span').textContent = 'Error!';
                setTimeout(() => {
                    this.querySelector('span').textContent = originalText;
                    this.disabled = false;
                }, 2000);
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            this.querySelector('span').textContent = 'Error!';
            setTimeout(() => {
                this.querySelector('span').textContent = originalText;
                this.disabled = false;
            }, 2000);
        }
    });
}

// Initialize Related Products Buttons
function initRelatedProductsButtons() {
    // Initialize quick view buttons
    if (typeof initModalQuickView === 'function') {
        initModalQuickView();
    }
    
    // Initialize add to cart buttons
    if (typeof initAddToCartButtons === 'function') {
        initAddToCartButtons();
    }
    
    // Initialize wishlist buttons
    if (typeof initWishlistButtons === 'function') {
        initWishlistButtons();
    }
}

// Review Form Functions
function showReviewForm() {
    document.getElementById('reviewForm').style.display = 'block';
    document.getElementById('writeReviewBtn').style.display = 'none';
    
    // Load user's orders that contain this product
    loadUserOrders();
    
    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function hideReviewForm() {
    document.getElementById('reviewForm').style.display = 'none';
    document.getElementById('writeReviewBtn').style.display = 'flex';
    
    // Reset form
    document.getElementById('submitReviewForm').reset();
    document.getElementById('selectedRating').value = '';
    
    // Reset stars
    document.querySelectorAll('.rating-star').forEach(star => {
        star.style.fill = 'none';
        star.style.color = '#d1d5db';
    });
}

// Star Rating Interaction
document.addEventListener('DOMContentLoaded', function() {
    const ratingStars = document.querySelectorAll('.rating-star');
    
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            document.getElementById('selectedRating').value = rating;
            
            // Update star colors
            ratingStars.forEach((s, index) => {
                if (index < rating) {
                    s.style.fill = '#fbbf24';
                    s.style.color = '#fbbf24';
                } else {
                    s.style.fill = 'none';
                    s.style.color = '#d1d5db';
                }
            });
            
            // Reinitialize icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
        
        // Hover effect
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            ratingStars.forEach((s, index) => {
                if (index < rating) {
                    s.style.color = '#fbbf24';
                }
            });
        });
    });
    
    document.querySelector('.star-rating')?.addEventListener('mouseleave', function() {
        const selectedRating = parseInt(document.getElementById('selectedRating')?.value || 0);
        ratingStars.forEach((s, index) => {
            if (index < selectedRating) {
                s.style.color = '#fbbf24';
            } else {
                s.style.color = '#d1d5db';
            }
        });
    });
});

// Load User Orders
async function loadUserOrders() {
    try {
        const response = await fetch('/api/account/orders', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            const select = document.querySelector('select[name="order_id"]');
            select.innerHTML = '<option value="">Select an order</option>';
            
            // Filter orders that contain this product
            const productId = {{ $product->id }};
            const relevantOrders = data.orders.filter(order => 
                order.order_items.some(item => item.product_id === productId)
            );
            
            if (relevantOrders.length === 0) {
                select.innerHTML = '<option value="">You haven\'t purchased this product yet</option>';
                select.disabled = true;
            } else {
                relevantOrders.forEach(order => {
                    const option = document.createElement('option');
                    option.value = order.id;
                    option.textContent = `Order #${order.order_number} - ${new Date(order.created_at).toLocaleDateString()}`;
                    select.appendChild(option);
                });
            }
        }
    } catch (error) {
        console.error('Error loading orders:', error);
    }
}

// Submit Review
document.getElementById('submitReviewForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Validate rating
    if (!data.rating) {
        alert('Please select a rating');
        return;
    }
    
    try {
        const response = await fetch('/api/reviews/submit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            hideReviewForm();
            // Optionally reload the page to show the new review (after approval)
            // location.reload();
        } else {
            alert(result.message || 'Error submitting review');
        }
    } catch (error) {
        console.error('Error submitting review:', error);
        alert('Error submitting review. Please try again.');
    }
});

// Initialize Wishlist Button
async function initializeWishlistButton() {
    const productId = {{ $product->id }};
    const wishlistBtn = document.getElementById('wishlistBtn-' + productId);
    const heartIcon = document.getElementById('heart-icon-' + productId);
    
    if (!wishlistBtn || !heartIcon) {
        console.warn('Wishlist button or icon not found');
        return;
    }
    
    try {
        // Check if product is in wishlist
        const response = await window.api.checkWishlist(productId);
        const isInWishlist = response.in_wishlist;
        
        // Update icon state
        if (isInWishlist) {
            heartIcon.classList.add('active');
            heartIcon.setAttribute('fill', 'currentColor');
            heartIcon.setAttribute('stroke', 'none');
            heartIcon.style.fill = '#ef4444';
            heartIcon.style.color = '#ef4444';
            wishlistBtn.classList.add('active');
        } else {
            heartIcon.classList.remove('active');
            heartIcon.setAttribute('fill', 'none');
            heartIcon.setAttribute('stroke', 'currentColor');
            heartIcon.style.fill = 'none';
            heartIcon.style.color = '';
            wishlistBtn.classList.remove('active');
        }
        
        // Reinitialize lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    } catch (error) {
        console.warn('Failed to check wishlist status:', error);
    }
}

// Wishlist Button Click Handler
document.addEventListener('DOMContentLoaded', function() {
    const productId = {{ $product->id }};
    const wishlistBtn = document.getElementById('wishlistBtn-' + productId);
    
    // Initialize button state
    initializeWishlistButton();
    
    // Add click event listener
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            try {
                // Check if API is available
                if (!window.api) {
                    console.error('window.api is not available!');
                    showNotification('API not available', 'error');
                    return;
                }
                
                // Toggle wishlist via API
                const response = await window.api.toggleWishlist(productId);
                
                // Update button state
                const heartIcon = document.getElementById('heart-icon-' + productId);
                if (heartIcon && response.success) {
                    const wasAdded = response.was_added;
                    
                    if (wasAdded) {
                        // Item was added to wishlist
                        heartIcon.classList.add('active');
                        heartIcon.setAttribute('fill', 'currentColor');
                        heartIcon.setAttribute('stroke', 'none');
                        heartIcon.style.fill = '#ef4444';
                        heartIcon.style.color = '#ef4444';
                        wishlistBtn.classList.add('active');
                        showNotification('Added to wishlist', 'success');
                    } else {
                        // Item was removed from wishlist
                        heartIcon.classList.remove('active');
                        heartIcon.setAttribute('fill', 'none');
                        heartIcon.setAttribute('stroke', 'currentColor');
                        heartIcon.style.fill = 'none';
                        heartIcon.style.color = '';
                        wishlistBtn.classList.remove('active');
                        showNotification('Removed from wishlist', 'success');
                    }
                    
                    // Reinitialize lucide icons
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
                
                // Update wishlist count badge
                if (typeof updateWishlistCount === 'function') {
                    await updateWishlistCount();
                }
                
                // Update offcanvas if open
                const offcanvas = document.getElementById('offcanvas-wishlist');
                if (offcanvas && getComputedStyle(offcanvas).visibility !== 'hidden') {
                    if (typeof updateWishlistOffcanvas === 'function') {
                        await updateWishlistOffcanvas();
                    }
                }
                
            } catch (error) {
                console.error('Wishlist toggle error:', error);
                showNotification('Failed to update wishlist', 'error');
            }
        });
    }
});
</script>
@endpush
@endsection

