@extends('emails.layouts.branded')

@section('content')
<h1>Low Stock Alert</h1>

<p>Hello Admin,</p>

<p>This is an automated alert to notify you that one of your products is running low on stock and may need to be restocked soon.</p>

<div class="info-box">
    <h2>Product Information</h2>
    <p><strong>Product Name:</strong> {{ $product->name }}</p>
    <p><strong>SKU:</strong> {{ $product->sku ?? 'Not set' }}</p>
    <p><strong>Category:</strong> {{ $product->category->name ?? 'Uncategorized' }}</p>
    <p><strong>Current Stock:</strong> {{ $product->stock_quantity }} units</p>
    <p><strong>Low Stock Threshold:</strong> {{ $product->low_stock_threshold ?? 'Not set' }} units</p>
    <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
    <p><strong>Status:</strong> {{ $product->is_active ? 'Active' : 'Inactive' }}</p>
</div>

@if($product->stock_quantity <= 0)
    <div class="info-box" style="border-left-color: #ef4444; background-color: #fef2f2;">
        <h2 style="color: #dc2626;">⚠️ OUT OF STOCK</h2>
        <p style="color: #dc2626; font-weight: 600;">This product is completely out of stock and needs immediate attention!</p>
    </div>
@elseif($product->stock_quantity <= 5)
    <div class="info-box" style="border-left-color: #f59e0b; background-color: #fffbeb;">
        <h2 style="color: #d97706;">⚠️ CRITICALLY LOW STOCK</h2>
        <p style="color: #d97706; font-weight: 600;">This product has critically low stock and should be restocked immediately.</p>
    </div>
@else
    <div class="info-box" style="border-left-color: #f59e0b; background-color: #fffbeb;">
        <h2 style="color: #d97706;">⚠️ LOW STOCK WARNING</h2>
        <p style="color: #d97706; font-weight: 600;">This product is approaching its low stock threshold.</p>
    </div>
@endif

<h2>Recommended Actions</h2>
<div class="info-box">
    <ul style="margin: 0; padding-left: 20px;">
        <li>Review current stock levels and sales velocity</li>
        <li>Place a restock order with your supplier</li>
        <li>Consider temporarily marking the product as "Out of Stock" if needed</li>
        <li>Update the low stock threshold if necessary</li>
        <li>Notify customers if there will be a delay in availability</li>
    </ul>
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/admin/products/' . $product->id . '/edit') }}" class="button">Update Stock</a>
    <a href="{{ url('/admin/products/' . $product->id) }}" class="button" style="background: #6b7280; margin-left: 10px;">View Product</a>
</div>

<h2>Stock History</h2>
<div class="info-box">
    <p><strong>Last Updated:</strong> {{ $product->updated_at->format('M d, Y \a\t g:i A') }}</p>
    @if($product->stock_quantity > 0)
        <p><strong>Estimated Days Remaining:</strong> {{ $product->stock_quantity > 0 ? 'Based on current sales velocity' : 'N/A' }}</p>
    @endif
</div>

<p>This is an automated alert. Please take appropriate action to ensure product availability for your customers.</p>

<p>If you have any questions about inventory management, please contact the system administrator.</p>
@endsection

