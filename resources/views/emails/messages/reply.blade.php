@extends('emails.layouts.branded')

@section('content')
<h1>Response to Your Inquiry</h1>

<p>Hello {{ $contactMessage->name }},</p>

<p>Thank you for contacting David's Wood Furniture. We have received your message and are happy to respond.</p>

@if($contactMessage->message)
<div class="info-box" style="background-color: #F8F8F8; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #8B7355;">
    <p style="margin: 0 0 10px 0; font-weight: 600; color: #374151;">Your Original Message:</p>
    <p style="margin: 0; color: #6b7280; font-style: italic;">{{ $contactMessage->message }}</p>
</div>
@endif

<div style="background-color: #F8F8F8; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #E5E5E5;">
    <p style="margin: 0 0 10px 0; font-weight: 600; color: #374151;">Our Response:</p>
    <div style="color: #333333; white-space: pre-wrap;">{!! nl2br(e($replyMessage)) !!}</div>
</div>

<p>If you have any further questions or concerns, please don't hesitate to reach out to us. We're here to help!</p>

<p>Best regards,<br>
<strong>{{ $admin->full_name }}</strong><br>
David's Wood Furniture Team</p>

<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E5E5;">
    <p style="font-size: 12px; color: #6b7280; margin: 0;">
        This is an automated response to your inquiry submitted on {{ $contactMessage->created_at->format('M d, Y \a\t g:i A') }}.
    </p>
</div>
@endsection

