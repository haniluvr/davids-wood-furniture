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
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
    }
    
    .reset-content {
        max-width: 800px;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }
    
    .reset-info {
        padding-right: 2rem;
    }
    
    .reset-title {
        font-size: 3rem;
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        line-height: 3rem;
    }
    
    .reset-description {
        font-size: 1.1rem;
        color: #4a4a4a;
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    
    .reset-form {
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
    
    .password-container {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #8B7355;
        padding: 0.5rem;
    }
    
    .password-toggle:hover {
        color: #6b5b47;
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
    
    .password-strength {
        margin-top: 0.5rem;
    }
    
    .strength-bar {
        width: 100%;
        height: 2px;
        background: #e5e7eb;
        border-radius: 1px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }
    
    .strength-fill {
        height: 100%;
        transition: all 0.3s ease;
        border-radius: 1px;
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
        font-size: 0.8rem;
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
    
    .brown-accent {
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: #8B7355;
    }
    
    @media (max-width: 768px) {
        .reset-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .reset-info {
            padding-right: 0;
            text-align: center;
        }
        
        .reset-title {
            font-size: 2rem;
        }
        
        .brown-accent {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="reset-container">
    <div class="brown-accent"></div>
    
    <div class="reset-content">
        <!-- Left Section - Information -->
        <div class="reset-info">
            <h1 class="reset-title">Reset Password</h1>
            <p class="reset-description">
                Enter your new password below. Make sure it's secure and easy for you to remember.
            </p>
        </div>
        
        <!-- Right Section - Form -->
        <div class="reset-form">
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