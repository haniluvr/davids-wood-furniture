@extends('emails.layouts.branded')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #8B7355; margin: 0;">Admin Login Verification</h1>
    <p style="color: #666; margin: 10px 0 0 0; font-style: italic;">David's Wood Furniture Admin Panel</p>
</div>

<div style="background: #F8F8F8; padding: 30px; border-radius: 8px; margin-bottom: 30px; border: 0.75px solid #8B7355;">
    <h2 style="color: #8B7355; margin: 0 0 20px 0;">Hello {{ $admin->first_name }},</h2>
    
    <p style="color: #555; line-height: 1.6; margin: 0 0 20px 0;">
        You're signing in to the admin panel. Use the verification code below to complete your login:
    </p>

    <div style="text-align: center; margin: 30px 0;">
        <div style="background: #8B7355; color: white; padding: 20px; border-radius: 8px; display: inline-block; font-size: 32px; font-weight: bold; letter-spacing: 8px; font-family: monospace;">
            {{ $otpCode }}
        </div>
    </div>

    <p style="color: #666; font-size: 14px; margin: 20px 0 0 0;">
        This code will expire in 5 minutes for security reasons.
    </p>
</div>

<div style="border-top: 1px solid #E5E5E5; padding-top: 20px; text-align: center;">
    <p style="color: #666; font-size: 12px; margin: 0;">
        If you didn't request this login, please ignore this email or contact support if you have concerns.
    </p>
    <p style="color: #666; font-size: 12px; margin: 10px 0 0 0;">
        This email was sent to your email
    </p>
</div>
@endsection
