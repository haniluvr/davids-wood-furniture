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
        min-height: calc(100vh - 8rem);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }
    
    .verification-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        max-width: 700px;
        width: 100%;
        overflow: hidden;
        border: 1px solid rgba(139, 115, 85, 0.1);
    }
    
    .verification-header {
        background: linear-gradient(135deg, #8B7355 0%, #b7a99a 100%);
        padding: 4rem 3rem;
        text-align: center;
        color: white;
        position: relative;
    }
    
    .verification-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    
    .verification-icon {
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 1;
    }
    
    .verification-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 1;
    }
    
    .verification-subtitle {
        font-size: 1.2rem;
        opacity: 0.95;
        font-weight: 400;
        position: relative;
        z-index: 1;
    }
    
    .verification-content {
        padding: 3rem;
    }
    
    .main-content {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .main-content h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
    }
    
    .main-content p {
        font-size: 1.1rem;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    
    .steps-container {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid #e5e7eb;
    }
    
    .steps-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .steps-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 1rem;
    }
    
    .steps-list li {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    
    .steps-list li:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .step-number {
        background: #8B7355;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 600;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .step-text {
        color: #374151;
        font-weight: 500;
    }
    
    .resend-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 16px;
        padding: 2.5rem;
        border: 1px solid #dee2e6;
    }
    
    .resend-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
        text-align: center;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input {
        width: 100%;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: white;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #8B7355;
        box-shadow: 0 0 0 3px rgba(139, 115, 85, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #8B7355 0%, #b7a99a 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-primary:hover::before {
        left: 100%;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 115, 85, 0.3);
    }
    
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-secondary {
        background: transparent;
        color: #8B7355;
        border: 2px solid #8B7355;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .btn-secondary:hover {
        background: #8B7355;
        color: white;
        transform: translateY(-2px);
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .action-buttons .btn-secondary {
        flex: 1;
    }
    
    .status-message {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
    }
    
    .status-message.success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }
    
    .status-message.error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #ef4444;
    }
    
    .status-message i {
        margin-right: 0.5rem;
    }
    
    @media (max-width: 640px) {
        .verification-header {
            padding: 3rem 2rem;
        }
        
        .verification-content {
            padding: 2rem;
        }
        
        .verification-title {
            font-size: 2rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .steps-list {
            gap: 0.75rem;
        }
        
        .steps-list li {
            padding: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="verification-container">
    <div class="verification-card">
        <!-- Header -->
        <div class="verification-header">
            <div class="verification-icon">
                <i data-lucide="mail" class="w-10 h-10 text-white"></i>
            </div>
            <h1 class="verification-title">Check Your Email</h1>
            <p class="verification-subtitle">We've sent you a verification link to complete your registration</p>
        </div>
        
        <!-- Content -->
        <div class="verification-content">
            <!-- Status Messages -->
            <div id="status-messages"></div>
            
            <!-- Main Content -->
            <div class="main-content">
                <h2>Almost there!</h2>
                <p>We've sent a verification link to your email address. Follow these simple steps to complete your registration:</p>
            </div>
            
            <!-- Steps Container -->
            <div class="steps-container">
                <h3 class="steps-title">What to do next:</h3>
                <ol class="steps-list">
                    <li>
                        <div class="step-number">1</div>
                        <div class="step-text">Check your email inbox (and spam folder)</div>
                    </li>
                    <li>
                        <div class="step-number">2</div>
                        <div class="step-text">Click the verification link in the email</div>
                    </li>
                    <li>
                        <div class="step-number">3</div>
                        <div class="step-text">You'll be automatically logged in</div>
                    </li>
                    <li>
                        <div class="step-number">4</div>
                        <div class="step-text">Start exploring our beautiful furniture collection</div>
                    </li>
                </ol>
            </div>
            
            <!-- Resend Section -->
            <div class="resend-section">
                <h3 class="resend-title">Didn't receive the email?</h3>
                <p class="text-center text-gray-600 mb-6">
                    Check your spam folder or request a new verification email. The link expires in 1 hour.
                </p>
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
                    
                    <!-- Debug: Test Email Button (remove in production) -->
                    @if(config('app.debug'))
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800 mb-2">Debug Mode: Test email functionality</p>
                        <button type="button" id="test-email-btn" class="text-sm bg-yellow-200 hover:bg-yellow-300 text-yellow-800 px-3 py-1 rounded">
                            Test Email Sending
                        </button>
                    </div>
                    @endif
                </form>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('user.login.form') }}" class="btn-secondary">
                    <i data-lucide="log-in" class="w-4 h-4 inline-block mr-2"></i>
                    Sign In Instead
                </a>
                <a href="{{ route('home') }}" class="btn-secondary">
                    <i data-lucide="home" class="w-4 h-4 inline-block mr-2"></i>
                    Back to Home
                </a>
            </div>
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
    
    // Test email functionality (debug mode only)
    const testEmailBtn = document.getElementById('test-email-btn');
    if (testEmailBtn) {
        testEmailBtn.addEventListener('click', async function() {
            const email = document.getElementById('email').value;
            if (!email) {
                showMessage('error', 'Please enter an email address first');
                return;
            }
            
            try {
                const response = await fetch('/test-email', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                });
                
                const result = await response.json();
                if (result.success) {
                    showMessage('success', 'Test email sent! Check your inbox.');
                } else {
                    showMessage('error', 'Failed to send test email: ' + result.message);
                }
            } catch (error) {
                showMessage('error', 'Error sending test email: ' + error.message);
            }
        });
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