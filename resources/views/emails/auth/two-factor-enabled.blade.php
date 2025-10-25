@extends('emails.layouts.branded')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #8B7355; margin: 0;">Two-Factor Authentication Enabled</h1>
    <p style="color: #666; margin: 10px 0 0 0; font-style: italic;">David's Wood Furnitures</p>
</div>

<div style="background: #F8F8F8; padding: 30px; border-radius: 8px; margin-bottom: 30px; border: 0.75px solid #8B7355;">
    <h2 style="color: #8B7355; margin: 0 0 20px 0;">Hello {{ $user->first_name }},</h2>
    
    <p style="color: #555; line-height: 1.6; margin: 0 0 20px 0;">
        Great news! You've successfully enabled two-factor authentication for your account. This adds an extra layer of security to protect your account.
    </p>

    <div style="background: #efe3df; border: 1px solid #8B7355; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <p style="color: #8B7355; margin: 0; font-weight: bold;">
            ✓ Two-Factor Authentication is now active
        </p>
    </div>

    <p style="color: #555; line-height: 1.6; margin: 20px 0 0 0;">
        From now on, when you log in, you'll receive a secure magic link via email to complete your authentication. This helps keep your account safe from unauthorized access.
    </p>
</div>

<div style="border-top: 1px solid #E5E5E5; padding-top: 20px; text-align: center;">
    <p style="color: #666; font-size: 12px; margin: 0;">
        If you didn't enable this feature, please contact our support team immediately.
    </p>
    <p style="color: #666; font-size: 12px; margin: 10px 0 0 0;">
        This email was sent to {{ $user->email }}
    </p>
</div>
@endsection
