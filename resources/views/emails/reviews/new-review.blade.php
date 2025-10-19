@extends('emails.layouts.branded')

@section('content')
<h1>New Product Review</h1>

<p>Hello Admin,</p>

<p>A new product review has been submitted and requires your attention.</p>

<div class="info-box">
    <h2>Review Information</h2>
    <p><strong>Product:</strong> {{ $review->product->name }}</p>
    <p><strong>Customer:</strong> {{ $review->user ? $review->user->name : 'Anonymous' }}</p>
    <p><strong>Email:</strong> {{ $review->user ? $review->user->email : 'Not provided' }}</p>
    <p><strong>Rating:</strong> 
        <span class="rating">
            @for($i = 1; $i <= 5; $i++)
                <span class="star">{{ $i <= $review->rating ? '★' : '☆' }}</span>
            @endfor
        </span>
        ({{ $review->rating }}/5)
    </p>
    <p><strong>Status:</strong> {{ $review->is_approved ? 'Approved' : 'Pending Approval' }}</p>
    <p><strong>Submitted:</strong> {{ $review->created_at->format('M d, Y \a\t g:i A') }}</p>
</div>

<h2>Review Content</h2>
<div class="info-box">
    <p><strong>Title:</strong> {{ $review->title ?? 'No title provided' }}</p>
    <p><strong>Review:</strong></p>
    <div style="background-color: #f9fafb; padding: 15px; border-radius: 6px; margin-top: 10px;">
        <p style="margin: 0; font-style: italic;">"{{ $review->review }}"</p>
    </div>
</div>

@if($review->pros)
<h2>Pros</h2>
<div class="info-box">
    <p>{{ $review->pros }}</p>
</div>
@endif

@if($review->cons)
<h2>Cons</h2>
<div class="info-box">
    <p>{{ $review->cons }}</p>
</div>
@endif

<h2>Product Details</h2>
<div class="info-box">
    <p><strong>Product Name:</strong> {{ $review->product->name }}</p>
    <p><strong>SKU:</strong> {{ $review->product->sku ?? 'Not set' }}</p>
    <p><strong>Category:</strong> {{ $review->product->category->name ?? 'Uncategorized' }}</p>
    <p><strong>Price:</strong> ${{ number_format($review->product->price, 2) }}</p>
    <p><strong>Current Rating:</strong> {{ number_format($review->product->average_rating ?? 0, 1) }}/5 ({{ $review->product->reviews_count ?? 0 }} reviews)</p>
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/admin/reviews/' . $review->id) }}" class="button">View Review</a>
    <a href="{{ url('/admin/products/' . $review->product->id) }}" class="button" style="background: #6b7280; margin-left: 10px;">View Product</a>
</div>

<h2>Quick Actions</h2>
<div class="info-box">
    <p>You can take the following actions on this review:</p>
    <ul style="margin: 0; padding-left: 20px;">
        <li><strong>Approve:</strong> Make the review visible to customers</li>
        <li><strong>Reject:</strong> Hide the review from public view</li>
        <li><strong>Respond:</strong> Add a public response to the review</li>
        <li><strong>Edit:</strong> Modify the review content if needed</li>
        <li><strong>Delete:</strong> Remove the review completely</li>
    </ul>
</div>

@if($review->rating >= 4)
    <div class="info-box" style="border-left-color: #10b981; background-color: #f0fdf4;">
        <h2 style="color: #059669;">⭐ Positive Review</h2>
        <p style="color: #059669; font-weight: 600;">This is a positive review that could help boost product sales!</p>
    </div>
@elseif($review->rating <= 2)
    <div class="info-box" style="border-left-color: #ef4444; background-color: #fef2f2;">
        <h2 style="color: #dc2626;">⚠️ Negative Review</h2>
        <p style="color: #dc2626; font-weight: 600;">This is a negative review that may require attention and response.</p>
    </div>
@endif

<p>Please review this feedback and take appropriate action to maintain the quality of your product reviews.</p>

<p>This is an automated notification. You can manage review settings in the admin panel.</p>
@endsection

