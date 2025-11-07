@extends('emails.layouts.branded')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
    <h2 style="color: #8b7355; margin-bottom: 20px;">Refund Request Update</h2>
    
    <p>Hello {{ ($returnRepair->user->first_name ?? '') . ' ' . ($returnRepair->user->last_name ?? '') ?: $returnRepair->user->email }},</p>
    
    <p>We regret to inform you that your refund request has been rejected.</p>
    
    <div style="background-color: #f9f9f9; border-left: 4px solid #8b7355; padding: 15px; margin: 20px 0;">
        <p style="margin: 0;"><strong>RMA Number:</strong> {{ $returnRepair->rma_number }}</p>
        <p style="margin: 5px 0 0 0;"><strong>Order Number:</strong> #{{ $returnRepair->order->order_number ?? 'N/A' }}</p>
        <p style="margin: 5px 0 0 0;"><strong>Status:</strong> {{ ucfirst($returnRepair->status) }}</p>
    </div>
    
    <h3 style="color: #8b7355; margin-top: 30px; margin-bottom: 15px;">Reason for Rejection</h3>
    
    <div style="background-color: #fff3cd; border: 1px solid #ffc107; padding: 15px; margin: 15px 0; border-radius: 5px;">
        <p style="margin: 0; color: #856404;">{{ $rejectionReason }}</p>
    </div>
    
    <h3 style="color: #8b7355; margin-top: 30px; margin-bottom: 15px;">What You Can Do</h3>
    
    <p>If you believe this decision was made in error or if you have additional information that might change the outcome, please contact our customer service team:</p>
    
    <ul style="line-height: 1.8; margin: 15px 0;">
        <li>Email: <a href="mailto:hello@davidswood.shop" style="color: #8b7355;">hello@davidswood.shop</a></li>
        <li>Include your RMA number ({{ $returnRepair->rma_number }}) in your inquiry</li>
        <li>Our team will review your case and respond within 2-3 business days</li>
    </ul>
    
    <p>We appreciate your understanding and are here to help resolve any concerns you may have.</p>
    
    <div style="margin-top: 30px; text-align: center;">
        <a href="{{ route('account') }}#orders" style="display: inline-block; background-color: #8b7355; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">View Your Orders</a>
    </div>
    
    <p style="margin-top: 30px; color: #666; font-size: 14px;">
        Best regards,<br>
        The David's Wood Furniture Team
    </p>
</div>
@endsection

