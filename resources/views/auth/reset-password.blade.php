@extends('layouts.app')

@section('title', 'Reset Password | David\'s Wood Furniture')

@push('styles')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f3efe7;
        min-height: 100vh;
    }
    
    .reset-container {
        min-height: calc(100vh - 8rem);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }
    
    .reset-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%;
        overflow: hidden;
        border: 1px solid rgba(139, 115, 85, 0.1);
    }
    
    .reset-header {
        background: linear-gradient(135deg, #8B7355 0%, #b7a99a 100%);
        padding: 3rem 2rem;
        text-align: center;
        color: white;
        position: relative;
    }
    
    .reset-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    
    .reset-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 1;
    }
    
    .reset-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 1;
    }
    
    .reset-subtitle {
        font-size: 1.1rem;
        opacity: 0.95;
        font-weight: 400;
        position: relative;
        z-index: 1;
    }
    
    .reset-content {
        padding: 3rem 2rem;
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
    
    .password-container {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #6b7280;
    }
    
    .password-toggle:hover {
        color: #374151;
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
    
    .password-strength {
        margin-top: 0.5rem;
    }
    
    .strength-bar {
        width: 100%;
        height: 4px;
        background: #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }
    
    .strength-fill {
        height: 100%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }
    
    .strength-weak .strength-fill {
        background: #ef4444;
        width: 25%;
    }
    
    .strength-fair .strength-fill {
        background: #f59e0b;
        width: 50%;
    }
    
    .strength-good .strength-fill {
        background: #10b981;
        width: 75%;
    }
    
    .strength-strong .strength-fill {
        background: #059669;
        width: 100%;
    }
    
    .strength-text {
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .strength-weak .strength-text {
        color: #ef4444;
    }
    
    .strength-fair .strength-text {
        color: #f59e0b;
    }
    
    .strength-good .strength-text {
        color: #10b981;
    }
    
    .strength-strong .strength-text {
        color: #059669;
    }
    
    @media (max-width: 640px) {
        .reset-header {
            padding: 2rem 1.5rem;
        }
        
        .reset-content {
            padding: 2rem 1.5rem;
        }
        
        .reset-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="reset-container">
    <div class="reset-card">
        <!-- Header -->
        <div class="reset-header">
            <div class="reset-icon">
                <i data-lucide="key" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="reset-title">Reset Password</h1>
            <p class="reset-subtitle">Enter your new password below</p>
        </div>
        
        <!-- Content -->
        <div class="reset-content">
            <!-- Status Messages -->
            <div id="status-messages"></div>
            
            <form id="reset-password-form">
                @csrf
                <input type="hidden" id="token" name="token" value="{{ $token }}">
                
                <!-- New Password -->
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <div class="password-container">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Enter your new password"
                            required
                            minlength="8"
                        >
                        <button type="button" class="password-toggle" id="toggle-password">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="password-strength" style="display: none;">
                        <div class="strength-bar">
                            <div class="strength-fill"></div>
                        </div>
                        <div class="strength-text"></div>
                    </div>
                </div>
                
                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <div class="password-container">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-input" 
                            placeholder="Confirm your new password"
                            required
                        >
                        <button type="button" class="password-toggle" id="toggle-password-confirmation">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-primary" id="reset-submit">
                    <span id="reset-text">Reset Password</span>
                    <span id="reset-loading" style="display: none;">
                        <i data-lucide="loader-2" class="w-4 h-4 inline-block animate-spin mr-2"></i>
                        Resetting...
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Password toggle functionality
    const passwordToggle = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const confirmToggle = document.getElementById('toggle-password-confirmation');
    const confirmInput = document.getElementById('password_confirmation');
    
    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.setAttribute('data-lucide', type === 'password' ? 'eye' : 'eye-off');
            lucide.createIcons();
        });
    }
    
    if (confirmToggle && confirmInput) {
        confirmToggle.addEventListener('click', function() {
            const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmInput.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.setAttribute('data-lucide', type === 'password' ? 'eye' : 'eye-off');
            lucide.createIcons();
        });
    }
    
    // Password strength checker
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });
    }
    
    // Form submission
    const form = document.getElementById('reset-password-form');
    const submitBtn = document.getElementById('reset-submit');
    const resetText = document.getElementById('reset-text');
    const resetLoading = document.getElementById('reset-loading');
    const statusMessages = document.getElementById('status-messages');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const token = document.getElementById('token').value;
            
            // Validate passwords match
            if (password !== passwordConfirmation) {
                showMessage('error', 'Passwords do not match.');
                return;
            }
            
            // Validate password strength
            if (password.length < 8) {
                showMessage('error', 'Password must be at least 8 characters long.');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            resetText.style.display = 'none';
            resetLoading.style.display = 'inline';
            
            // Clear previous messages
            statusMessages.innerHTML = '';
            
            try {
                const response = await fetch('/reset-password', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        token: token,
                        password: password,
                        password_confirmation: passwordConfirmation
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showMessage('success', 'Password reset successfully! You can now sign in with your new password.');
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                } else {
                    showMessage('error', result.message || 'Failed to reset password. Please try again.');
                }
            } catch (error) {
                console.error('Error resetting password:', error);
                showMessage('error', 'Error resetting password. Please try again.');
            } finally {
                // Reset button state
                submitBtn.disabled = false;
                resetText.style.display = 'inline';
                resetLoading.style.display = 'none';
            }
        });
    }
    
    function checkPasswordStrength(password) {
        const strengthContainer = document.getElementById('password-strength');
        const strengthBar = strengthContainer.querySelector('.strength-fill');
        const strengthText = strengthContainer.querySelector('.strength-text');
        
        if (password.length === 0) {
            strengthContainer.style.display = 'none';
            return;
        }
        
        strengthContainer.style.display = 'block';
        
        let score = 0;
        let feedback = '';
        
        // Length check
        if (password.length >= 8) score++;
        if (password.length >= 12) score++;
        
        // Character variety checks
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        
        // Remove all strength classes
        strengthContainer.className = 'password-strength';
        
        if (score < 3) {
            strengthContainer.classList.add('strength-weak');
            feedback = 'Weak';
        } else if (score < 4) {
            strengthContainer.classList.add('strength-fair');
            feedback = 'Fair';
        } else if (score < 6) {
            strengthContainer.classList.add('strength-good');
            feedback = 'Good';
        } else {
            strengthContainer.classList.add('strength-strong');
            feedback = 'Strong';
        }
        
        strengthText.textContent = feedback;
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