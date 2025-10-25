@extends('emails.layouts.branded')

@section('content')
<div style="text-align: center; padding: 2rem 0;">
    <h1 style="color: #8B7355; font-size: 2rem; margin-bottom: 1rem;">Welcome to David's Wood Furniture!</h1>
    <p style="font-size: 1.1rem; color: #6b7280; margin-bottom: 2rem;">
        Thank you for registering with us. Please verify your email address to complete your account setup.
    </p>
    
    <div style="background: #f8f9fa; border-radius: 12px; padding: 2rem; margin: 2rem 0; border: 1px solid #e5e7eb;">
        <h2 style="color: #374151; font-size: 1.25rem; margin-bottom: 1rem;">Verify Your Email Address</h2>
        <p style="color: #6b7280; margin-bottom: 1.5rem;">
            Click the button below to verify your email address and activate your account:
        </p>
        
        <a href="{{ $verificationUrl }}" 
           style="display: inline-block; background: linear-gradient(135deg, #8B7355 0%, #b7a99a 100%); color: white; padding: 1rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1rem;">
            Verify Email Address
        </a>
        
        <p style="color: #9ca3af; font-size: 0.875rem; margin-top: 1rem;">
            This link expires in 1 hour.
        </p>
    </div>
    
    <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 1rem; margin: 1rem 0;">
        <p style="color: #92400e; margin: 0; font-size: 0.875rem;">
            <strong>Important:</strong> If you didn't create an account with David's Wood Furniture, please ignore this email.
        </p>
    </div>
    
    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">
            If the button doesn't work, copy and paste this link into your browser:
        </p>
        <p style="color: #8B7355; font-size: 0.875rem; margin: 0.5rem 0 0 0; word-break: break-all;">
            {{ $verificationUrl }}
        </p>
    </div>
</div>
@endsection