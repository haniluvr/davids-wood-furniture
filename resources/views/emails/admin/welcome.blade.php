@extends('emails.layouts.branded')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #8B7355; margin: 0;">Welcome to David's Wood Admin Panel!</h1>
    <p style="color: #666; margin: 10px 0 0 0; font-style: italic;">Your account has been created</p>
</div>

<div style="background: #F8F8F8; padding: 30px; border-radius: 8px; margin-bottom: 30px; border: 0.75px solid #8B7355;">
    <h2 style="color: #8B7355; margin: 0 0 20px 0;">Hello {{ $admin->first_name }},</h2>
    
    <p style="color: #555; line-height: 1.6; margin: 0 0 20px 0;">
        Welcome to David's Wood Furniture Admin Panel! Your administrator account has been successfully created.
    </p>
    
    <div style="background: #E8F5E8; border: 1px solid #8B7355; padding: 20px; border-radius: 6px; margin: 20px 0;">
        <h3 style="color: #8B7355; margin: 0 0 15px 0;">📋 Your Account Details</h3>
        <p style="color: #555; margin: 0 0 10px 0;"><strong>Login Email:</strong> {{ $admin->email }}</p>
        <p style="color: #555; margin: 0 0 10px 0;"><strong>Role:</strong> {{ ucwords(str_replace('_', ' ', $admin->role)) }}</p>
        <p style="color: #555; margin: 0;">Your account is ready to use once you set up your password.</p>
    </div>
    
    @if(isset($magicLink) && $magicLink)
    <div style="background: #FFF3CD; border: 1px solid #8B7355; padding: 20px; border-radius: 6px; margin: 20px 0;">
        <h3 style="color: #8B7355; margin: 0 0 15px 0;">🔐 Complete Your Account Setup</h3>
        <p style="color: #555; margin: 0 0 15px 0;">To complete your account setup and set your password, please click the button below:</p>
        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ $magicLink }}" style="background: linear-gradient(135deg, #8B7355 0%, #A68B5B 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px;">Set Up Password</a>
        </div>
        <p style="margin: 0; color: #8B7355; font-weight: 600; font-size: 14px;">This link will expire in 24 hours for security reasons.</p>
        <p style="margin: 10px 0 0 0; color: #666; font-size: 12px;">If the button doesn't work, copy and paste this link into your browser:</p>
        <p style="margin: 5px 0 0 0; color: #8B7355; font-size: 12px; word-break: break-all;">{{ $magicLink }}</p>
    </div>
    @endif
</div>

<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">🔒 Security Reminder</h3>
    <p style="color: #555; margin: 0;">Please keep your login credentials secure and do not share them with anyone. If you have any questions or concerns, please contact your system administrator.</p>
</div>

<div style="background: #F8F8F8; border-left: 4px solid #8B7355; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;">
    <h3 style="color: #8B7355; margin: 0 0 10px 0;">📚 Getting Started</h3>
    <p style="color: #555; margin: 0 0 10px 0;">Once you've set up your password, you can access the admin panel at:</p>
    <p style="color: #8B7355; margin: 0; font-weight: 600;"><a href="{{ admin_route('dashboard') }}" style="color: #8B7355; text-decoration: underline;">{{ admin_route('dashboard') }}</a></p>
</div>

<p style="color: #555; margin: 20px 0;">Thank you for being part of the David's Wood Furniture team!</p>

<p style="color: #555; margin: 20px 0;">Best regards,<br>
The David's Wood Furniture Admin Team</p>
@endsection

