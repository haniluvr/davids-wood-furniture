@extends('emails.layouts.branded')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #8B7355; margin: 0;">Reset Your Admin Password</h1>
    <p style="color: #666; margin: 10px 0 0 0; font-style: italic;">David's Wood Furniture Admin Panel</p>
</div>

<div style="background: #F8F8F8; padding: 30px; border-radius: 8px; margin-bottom: 30px; border: 0.75px solid #8B7355;">
    <h2 style="color: #8B7355; margin: 0 0 20px 0;">Hello {{ $admin->first_name }},</h2>
    
    <p style="color: #555; line-height: 1.6; margin: 0 0 20px 0;">
        You requested to reset your password for your admin account ({{ $admin->email }}). Click the button below to reset your password.
    </p>
    
    <div style="background: #FFF3CD; border: 1px solid #8B7355; padding: 20px; border-radius: 6px; margin: 20px 0;">
        <h3 style="color: #8B7355; margin: 0 0 15px 0;">🔐 Reset Your Password</h3>
        <p style="color: #555; margin: 0 0 15px 0;">Click the button below to reset your password. This link will expire in 1 hour for security reasons.</p>
        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ $resetUrl }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px;">Reset Password</a>
        </div>
        <p style="margin: 0; color: #8B7355; font-weight: 600; font-size: 14px;">This link expires in 1 hour.</p>
        <p style="margin: 10px 0 0 0; color: #666; font-size: 12px;">If the button doesn't work, copy and paste this link into your browser:</p>
        <p style="margin: 5px 0 0 0; color: #8B7355; font-size: 12px; word-break: break-all;">{{ $resetUrl }}</p>
    </div>
</div>

<div style="background: #FEF3C7; border: 1px solid #F59E0B; border-radius: 8px; padding: 20px; margin: 20px 0;">
    <h3 style="color: #92400E; margin: 0 0 10px 0;">⚠️ Security Notice</h3>
    <p style="color: #92400E; margin: 0;">If you didn't request this password reset, please ignore this email. Your password will remain unchanged.</p>
</div>

<p style="color: #555; margin: 20px 0;">Thank you,<br>
The David's Wood Furniture Admin Team</p>
@endsection

