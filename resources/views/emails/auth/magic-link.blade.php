@extends('emails.layouts.branded')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #8B7355; margin: 0;">Complete Your Login</h1>
    <p style="color: #666; margin: 10px 0 0 0; font-style: italic;">David's Wood Furnitures</p>
</div>

<div style="background: #F8F8F8; padding: 30px; border-radius: 8px; margin-bottom: 30px; border: 0.75px solid #8B7355;">
    <h2 style="color: #8B7355; margin: 0 0 20px 0;">Hello {{ $user->first_name }},</h2>
    
    <p style="color: #555; line-height: 1.6; margin: 0 0 20px 0;">
        You're almost logged in! Click the button below to complete your two-factor authentication and access your account.
    </p>

    <div style="text-align: center; margin: 30px 0;">
        @if($user instanceof \App\Models\Admin)
            <a href="{{ admin_route('verify-magic-link', $token) }}" 
               style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px;">
                Complete Login
            </a>
        @else
            <a href="{{ route('auth.verify-email', $token) }}" 
               style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px;">
                Complete Login
            </a>
        @endif
    </div>

    <p style="color: #666; font-size: 14px; margin: 20px 0 0 0;">
        This link will expire in 1 hour for security reasons.
    </p>
</div>

<div style="border-top: 1px solid #E5E5E5; padding-top: 20px; text-align: center;">
    <p style="color: #666; font-size: 12px; margin: 0;">
        If you didn't request this login, please ignore this email or contact support if you have concerns.
    </p>
    <p style="color: #666; font-size: 12px; margin: 10px 0 0 0;">
        This email was sent to {{ $user->email }}
    </p>
</div>
@endsection
