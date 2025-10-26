@extends('layouts.app')

@section('title', 'Verify Your Email | David\'s Wood Furniture')

@push('styles')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f3efe7;
        min-height: 100vh;
    }
    
    .verification-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
    }
    
    .verification-content {
        max-width: 800px;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }
    
    .verification-info {
        padding-right: 2rem;
    }
    
    .verification-title {
        font-size: 3rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        line-height: 3rem;
    }
    
    .verification-description {
        font-size: 1.1rem;
        color: #4a4a4a;
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    
    .verification-form {
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
    
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .btn-secondary {
        background: transparent;
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
        text-decoration: none;
        display: inline-block;
        text-align: center;
        margin-top: 1rem;
        width: 100%;
    }
    
    .btn-secondary:hover {
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
        .verification-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .verification-info {
            padding-right: 0;
            text-align: center;
        }
        
        .verification-title {
            font-size: 2rem;
        }
        
        .brown-accent {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="verification-container">
    <div class="brown-accent"></div>
    
    <div class="verification-content">
        <!-- Left Section - Information -->
        <div class="verification-info">
            <h1 class="verification-title">Verify Your Email</h1>
            <p class="verification-description">
                We've sent a verification link to your email address. Please check your inbox and click the link to complete your registration.
            </p>
        </div>
        
        <!-- Right Section - Form -->
        <div class="verification-form">
            <!-- Status Messages -->
            <div id="status-messages"></div>
            
            <form id="resend-verification-form">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="Enter your email address"
                        value="{{ old('email', session('verification_email')) }}"
                        required
                    >
        </div>
        
                <button type="submit" class="btn-primary" id="resend-btn">
                    <span id="resend-text">Resend Verification Email</span>
                    <span id="resend-loading" style="display: none;">
                        <i data-lucide="loader-2" class="w-4 h-4 inline-block animate-spin mr-2"></i>
                        Sending...
                    </span>
                </button>
                
            </form>
            
            <!-- Action Buttons -->
            <a href="{{ route('user.login.form') }}" class="btn-secondary">
                Sign In Instead
            </a>
            <a href="{{ route('home') }}" class="btn-secondary">
                Back to Home
            </a>
                </div>
            </div>
        </div>
        
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Get email from sessionStorage if available
    const emailFromStorage = sessionStorage.getItem('verification_email');
    if (emailFromStorage) {
        const emailInput = document.getElementById('email');
        if (emailInput && !emailInput.value) {
            emailInput.value = emailFromStorage;
        }
        // Clear from sessionStorage after using it
        sessionStorage.removeItem('verification_email');
    }
    
    
    // Handle resend verification form
    const resendForm = document.getElementById('resend-verification-form');
    const resendBtn = document.getElementById('resend-btn');
    const resendText = document.getElementById('resend-text');
    const resendLoading = document.getElementById('resend-loading');
    const statusMessages = document.getElementById('status-messages');
    
    if (resendForm) {
        resendForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const email = formData.get('email');
            
            // Show loading state
            resendBtn.disabled = true;
            resendText.style.display = 'none';
            resendLoading.style.display = 'inline';
            
            // Clear previous messages
            statusMessages.innerHTML = '';
            
            try {
                const response = await fetch('{{ route("auth.resend-verification") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showMessage('success', 'Verification email sent! Please check your inbox.');
                } else {
                    showMessage('error', result.message || 'Failed to send verification email. Please try again.');
                }
            } catch (error) {
                console.error('Error sending verification email:', error);
                showMessage('error', 'Error sending verification email. Please try again.');
            } finally {
                // Reset button state
                resendBtn.disabled = false;
                resendText.style.display = 'inline';
                resendLoading.style.display = 'none';
            }
        });
    }
    
    function showMessage(type, message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `status-message ${type}`;
        messageDiv.innerHTML = `<i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-4 h-4"></i> ${message}`;
        statusMessages.appendChild(messageDiv);
        
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