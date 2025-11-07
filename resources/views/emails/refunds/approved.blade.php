@extends('emails.layouts.branded')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
    <h2 style="color: #8b7355; margin-bottom: 20px;">Refund Request Approved</h2>
    
    <p>Hello {{ ($returnRepair->user->first_name ?? '') . ' ' . ($returnRepair->user->last_name ?? '') ?: $returnRepair->user->email }},</p>
    
    <p>We're pleased to inform you that your refund request has been approved.</p>
    
    <div style="background-color: #f9f9f9; border-left: 4px solid #8b7355; padding: 15px; margin: 20px 0;">
        <p style="margin: 0;"><strong>RMA Number:</strong> {{ $returnRepair->rma_number }}</p>
        <p style="margin: 5px 0 0 0;"><strong>Order Number:</strong> #{{ $returnRepair->order->order_number ?? 'N/A' }}</p>
        <p style="margin: 5px 0 0 0;"><strong>Status:</strong> {{ ucfirst($returnRepair->status) }}</p>
    </div>
    
    <h3 style="color: #8b7355; margin-top: 30px; margin-bottom: 15px;">What's Next?</h3>
    
    <p>Our team will now process your refund. Here's what you can expect:</p>
    
    <ul style="line-height: 1.8; margin: 15px 0;">
        <li>You may be asked to return the item(s) if you haven't already</li>
        <li>Once we receive the item(s), we'll process your refund</li>
        <li>Refunds are typically processed within 5-10 business days</li>
        <li>You'll receive a confirmation email once the refund is complete</li>
    </ul>
    
    <p>If you have any questions or concerns, please don't hesitate to contact us.</p>
    
    <div style="margin-top: 30px; text-align: center;">
        <a href="{{ route('account') }}#orders" style="display: inline-block; background-color: #8b7355; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">View Your Orders</a>
    </div>
    
    <p style="margin-top: 30px; color: #666; font-size: 14px;">
        Thank you for your patience,<br>
        The David's Wood Furniture Team
    </p>
</div>
@endsection

