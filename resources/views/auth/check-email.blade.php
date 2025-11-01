@extends('layouts.app')

@push('styles')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f3efe7;
        min-height: 100vh;
    }
    
    .check-email-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
    }
    
    .check-email-content {
        max-width: 800px;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }
    
    .check-email-info {
        padding-right: 2rem;
    }
    
    .check-email-title {
        font-size: 3rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        line-height: 3rem;
    }
    
    .check-email-description {
        font-size: 1.1rem;
        color: #4a4a4a;
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    
    .check-email-form {
        background: white;
        padding: 2rem;
        border-radius: 8px;
    }
    
    .form-group {
        margin-bottom: 2rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem 0;
        border: none;
        border-bottom: 2px solid #8B7355;
        background: transparent;
        font-size: 1rem;
        color: #1a1a1a;
        transition: border-color 0.3s ease;
    }
    
    .form-input:focus {
        outline: none;
        border-bottom-color: #6b5b47;
    }
    
    .form-input::placeholder {
        color: #999;
    }
    
    .btn-primary {
        background: white;
        color: #1a1a1a;
        border: 2px solid #8B7355;
        padding: 1rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-primary:hover {
        background: #8B7355;
        color: white;
    }
    
    .status-message {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
    }
    
    .status-message.success {
        background: #f0f9f0;
        color: #065f46;
        border: 1px solid #10b981;
    }
    
    .status-message.error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #ef4444;
    }
    
    .status-message i {
        margin-right: 0.5rem;
    }
    
    .brown-accent {
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: #8B7355;
    }
    
    @media (max-width: 768px) {
        .check-email-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .check-email-info {
            padding-right: 0;
            text-align: center;
        }
        
        .check-email-title {
            font-size: 2rem;
        }
        
        .brown-accent {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="check-email-container">
    <div class="brown-accent"></div>
    
    <div class="check-email-content">
        <!-- Left Section - Information -->
        <div class="check-email-info">
            <h1 class="check-email-title">Check Your Email</h1>
            <p class="check-email-description">
                We've sent you a secure magic link to complete your login. Please check your email inbox and click the link to continue.
            </p>
        </div>
        
        <!-- Right Section - Form -->
        <div class="check-email-form">
            <!-- Status Messages -->
            <div id="status-messages"></div>
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" placeholder="your.email@example.com" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Status</label>
                <input type="text" class="form-input" value="Magic link sent" readonly>
            </div>
            
            <button type="button" class="btn-primary" onclick="window.location.href='{{ route('login') }}'">
                Back to Login
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    function showMessage(type, message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `status-message ${type}`;
        messageDiv.innerHTML = `<i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-4 h-4"></i> ${message}`;
        document.getElementById('status-messages').appendChild(messageDiv);
        
        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    }
});
</script>
@endsection


