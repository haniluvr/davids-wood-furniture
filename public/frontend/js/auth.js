// AUTH.JS - Authentication System
// Global force-enable signup functionality
window.forceEnableSignupButton = function() {
    const button = document.getElementById('signup-submit');
    if (button) {
        button.disabled = false;
        button.classList.remove('opacity-50');
        button.style.opacity = '1';
    }
};

// Button enabling when passwords match
if (typeof window.addEventListener === 'function') {
    // Run every second to enable button when passwords match
    setInterval(function() {
        const password = document.getElementById('signup-password');
        const confirmPassword = document.getElementById('signup-confirm-password');
        const button = document.getElementById('signup-submit');
        
        if (password && confirmPassword && button) {
            const passwordVal = password.value.trim();
            const confirmVal = confirmPassword.value.trim();
            
            if (passwordVal === confirmVal && passwordVal.length >= 8) {
                button.disabled = false;
                button.classList.remove('opacity-50');
                button.style.opacity = '1';
                button.style.pointerEvents = 'auto';
            }
        }
    }, 100); // Every 100ms
}

// Username availability check
let usernameCheckTimeout = null;
let isUsernameAvailable = false;

// Form validation state tracking
let formValidationState = {
    firstName: false,
    lastName: false,
    email: false,
    username: false,
    password: false,
    confirmPassword: false
};

// Validation functions
function validateFirstName(value) {
    const isValid = value.trim().length > 0;
    updateFieldValidation('firstName', isValid);
    return isValid;
}

function validateLastName(value) {
    const isValid = value.trim().length > 0;
    updateFieldValidation('lastName', isValid);
    return isValid;
}

async function validateEmail(value) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isValidFormat = emailRegex.test(value.trim());
    
    if (!isValidFormat) {
        updateFieldValidation('email', false);
        return false;
    }
    
    // Check if email is already taken
    try {
        const response = await fetch(`/api/check-email/${encodeURIComponent(value.trim())}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            if (result.exists) {
                // Email already exists
                updateFieldValidation('email', false);
                showEmailExistsAlert();
                return false;
            } else {
                // Email is available
                updateFieldValidation('email', true);
                return true;
            }
        }
    } catch (error) {
        console.error('Error checking email:', error);
    }
    
    // If check fails, just validate format
    updateFieldValidation('email', isValidFormat);
    return isValidFormat;
}

function showEmailExistsAlert() {
    // Create and show alert
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg shadow-lg z-50 max-w-md';
    alertDiv.innerHTML = `
        <div class="flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mr-3 mt-0.5"></i>
            <div>
                <h3 class="font-semibold text-red-800">Account Already Exists</h3>
                <p class="text-sm text-red-700 mt-1">You already have an account with this email address.</p>
                <div class="mt-3 flex gap-2">
                    <button onclick="redirectToSignIn()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
                        Sign In Instead
                    </button>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm font-medium">
                        Dismiss
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Re-initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Auto-remove after 10 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 10000);
}

function redirectToSignIn() {
    // Close signup modal
    const signupModal = document.getElementById('modal-signup');
    if (signupModal) {
        signupModal.classList.add('hidden');
    }
    
    // Open login modal
    const loginModal = document.getElementById('modal-login');
    if (loginModal) {
        loginModal.classList.remove('hidden');
    }
    
    // Remove alert
    const alerts = document.querySelectorAll('.fixed.top-4.right-4');
    alerts.forEach(alert => alert.remove());
}

// Make redirectToSignIn globally available
window.redirectToSignIn = redirectToSignIn;

// Make handleForgotPasswordClick globally available
window.handleForgotPasswordClick = function() {
    console.log('handleForgotPasswordClick called directly');
    
    const username = document.getElementById('forgot-username').value;
    const submitBtn = document.getElementById('forgot-password-submit');
    
    console.log('Username from direct click:', username);
    console.log('Submit button from direct click:', submitBtn);
    
    if (!username) {
        console.log('No username provided in direct click handler');
        alert('Please enter a username');
        return;
    }
    
    // Call the shared function
    handleForgotPasswordSubmission(username);
};

// Handle forgot password submission
async function handleForgotPasswordSubmission(username) {
    console.log('handleForgotPasswordSubmission called with username:', username);
    
    try {
        console.log('Sending forgot password request for username:', username);
        const response = await fetch('/forgot-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ username: username })
        });
        
        console.log('Forgot password response:', response);
        const result = await response.json();
        console.log('Forgot password result:', result);
        
        if (result.success) {
            console.log('Password reset success, showing success message for username:', username);
            console.log('Debug info:', result.debug_info);
            
            // Show success message in login modal
            showPasswordResetSuccessInLoginModal(result.user_email || 'your email', result.debug_info);
        } else {
            console.log('Password reset failed:', result.message);
            showMessage('error', result.message || 'Failed to send reset link. Please try again.');
        }
    } catch (error) {
        console.error('Error in forgot password submission:', error);
        showMessage('error', 'Error sending reset link. Please try again.');
    }
}

function showForgotPasswordModal() {
    console.log('showForgotPasswordModal called - sending password reset email automatically');
    
    // Get the login modal
    const loginModal = document.getElementById('modal-login');
    if (!loginModal) {
        console.log('Login modal not found');
        return;
    }
    
    // Get the username from the login form
    const usernameInput = loginModal.querySelector('#login-username');
    const username = usernameInput ? usernameInput.value : '';
    
    console.log('Username from login form:', username);
    
    if (!username) {
        alert('Please enter your username first');
        return;
    }
    
    // Show loading state
    const forgotPasswordLink = loginModal.querySelector('#forgot-password-link');
    if (forgotPasswordLink) {
        forgotPasswordLink.textContent = 'Sending reset link...';
        forgotPasswordLink.style.pointerEvents = 'none';
    }
    
    // Automatically send the password reset email
    handleForgotPasswordSubmission(username);
}

function showPasswordResetSuccessInLoginModal(email, debugInfo = null) {
    console.log('showPasswordResetSuccessInLoginModal called with email:', email);
    console.log('Debug info:', debugInfo);
    
    // Hash the email for display
    const hashedEmail = hashEmail(email);
    console.log('Hashed email:', hashedEmail);
    
    // Get the login modal
    const loginModal = document.getElementById('modal-login');
    if (!loginModal) {
        console.log('Login modal not found');
        return;
    }
    
    // Reset the forgot password link to original text
    const forgotPasswordLink = loginModal.querySelector('#forgot-password-link');
    if (forgotPasswordLink) {
        forgotPasswordLink.textContent = 'Forgot your password?';
        forgotPasswordLink.style.pointerEvents = 'auto';
    }
    
    // Remove existing success message if any
    const existingSuccess = loginModal.querySelector('#password-reset-success');
    if (existingSuccess) {
        existingSuccess.remove();
    }
    
    // Create success message
    const successMessage = document.createElement('div');
    successMessage.id = 'password-reset-success';
    successMessage.className = 'bg-green-50 border border-green-200 text-green-800 px-1 py-1 rounded text-xs mb-1 break-words max-w-full';
    
    // Remove debug section - no longer needed
    
    successMessage.innerHTML = `
        <div class="flex items-center">
            <i data-lucide="check-circle" class="w-3 h-3 text-green-500 mr-1 mt-0.5 flex-shrink-0"></i>
            <span class="text-green-800 break-words text-xs">Password reset link sent to your email:<br><strong>${hashedEmail}</strong></span>
        </div>
    `;
    
    // Insert before the forgot password link
    if (forgotPasswordLink) {
        forgotPasswordLink.parentNode.insertBefore(successMessage, forgotPasswordLink);
    }
    
    // Re-initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Auto-remove after 10 seconds
    setTimeout(() => {
        if (successMessage.parentNode) {
            successMessage.remove();
        }
    }, 10000);
}

function showPasswordResetSuccess(email, debugInfo = null) {
    console.log('showPasswordResetSuccess called with email:', email);
    console.log('Debug info:', debugInfo);
    
    // Hash the email for display
    const hashedEmail = hashEmail(email);
    console.log('Hashed email:', hashedEmail);
    
    // Show success message in login modal
    const loginModal = document.getElementById('modal-login');
    console.log('Login modal found:', loginModal);
    if (loginModal) {
        // Add success message container
        const successContainer = document.createElement('div');
        successContainer.id = 'password-reset-success';
        successContainer.className = 'bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4';
        
        let debugSection = '';
        if (debugInfo) {
            debugSection = `
                <div class="mt-3 p-2 bg-blue-50 border border-blue-200 rounded text-xs">
                    <p class="text-blue-800 font-semibold">Development Mode:</p>
                    <p class="text-blue-700">Email logged to storage/logs/laravel.log</p>
                    <p class="text-blue-700">Reset URL: <a href="${debugInfo.reset_url}" target="_blank" class="underline">${debugInfo.reset_url}</a></p>
                </div>
            `;
        }
        
        successContainer.innerHTML = `
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-3"></i>
                <div class="flex-1">
                    <h3 class="font-semibold text-green-800">Password Reset Link Sent!</h3>
                    <p class="text-sm text-green-700 mt-1">Check your email at <strong>${hashedEmail}</strong> for the password reset link.</p>
                    ${debugSection}
                </div>
            </div>
        `;
        
        // Insert after the form
        const form = loginModal.querySelector('form');
        if (form) {
            form.parentNode.insertBefore(successContainer, form.nextSibling);
        }
        
        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Auto-remove after 15 seconds (longer for debug info)
        setTimeout(() => {
            if (successContainer.parentNode) {
                successContainer.remove();
            }
        }, 15000);
    }
}

function hashEmail(email) {
    if (!email || !email.includes('@')) {
        return 'your email';
    }
    
    const [localPart, domain] = email.split('@');
    
    if (localPart.length <= 2) {
        return `${localPart[0]}****@${domain}`;
    }
    
    // Take first two characters
    const firstTwo = localPart.substring(0, 2);
    // Take last character before @
    const lastChar = localPart[localPart.length - 1];
    // Create stars for middle characters (minimum 3 stars)
    const middleStars = '*'.repeat(Math.max(3, localPart.length - 3));
    
    const result = `${firstTwo}${middleStars}${lastChar}@${domain}`;
    console.log(`Email hashing: ${email} -> ${result}`);
    return result;
}

function showLoginError(message) {
    // Show error message in login modal
    const loginModal = document.getElementById('modal-login');
    if (loginModal) {
        // Remove existing error message if any
        const existingError = loginModal.querySelector('#login-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Add error message container
        const errorContainer = document.createElement('div');
        errorContainer.id = 'login-error';
        errorContainer.className = 'bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4';
        errorContainer.innerHTML = `
            <div class="flex items-center">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mr-3"></i>
                <div>
                    <h3 class="font-semibold text-red-800">Login Failed</h3>
                    <p class="text-sm text-red-700 mt-1">${message}</p>
                </div>
            </div>
        `;
        
        // Insert after the form
        const form = loginModal.querySelector('form');
        if (form) {
            form.parentNode.insertBefore(errorContainer, form.nextSibling);
        }
        
        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Auto-remove after 8 seconds
        setTimeout(() => {
            if (errorContainer.parentNode) {
                errorContainer.remove();
            }
        }, 8000);
    }
}

function showMessage(type, message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 max-w-md ${
        type === 'success' 
            ? 'bg-green-50 border border-green-200 text-green-800' 
            : 'bg-red-50 border border-red-200 text-red-800'
    }`;
    messageDiv.innerHTML = `
        <div class="flex items-start">
            <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5 mr-3 mt-0.5"></i>
            <div>
                <h3 class="font-semibold">${type === 'success' ? 'Success' : 'Error'}</h3>
                <p class="text-sm mt-1">${message}</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(messageDiv);
    
    // Re-initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 5000);
}

function validatePassword(value) {
    const isValid = value.length >= 8;
    updateFieldValidation('password', isValid);
    return isValid;
}

function validateConfirmPassword(password, confirmPassword) {
    const isValid = password === confirmPassword && password.length > 0 && confirmPassword.length > 0;
    updateFieldValidation('confirmPassword', isValid);
    return isValid;
}

function updateFieldValidation(fieldName, isValid) {
    formValidationState[fieldName] = isValid;
    const fieldElement = document.getElementById(`signup-${fieldName}`);
    if (!fieldElement) return;
    
    // Remove existing validation classes
    fieldElement.classList.remove('border-green-500', 'border-red-500', 'border-gray-300');
    
    if (isValid) {
        fieldElement.classList.add('border-green-500');
    } else if (fieldElement.value.trim().length > 0) {
        fieldElement.classList.add('border-red-500');
    } else {
        fieldElement.classList.add('border-gray-300');
    }
}

async function checkUsernameAvailability(username) {
    // Clear any existing timeout
    if (usernameCheckTimeout) {
        clearTimeout(usernameCheckTimeout);
    }
    
    const usernameInput = document.getElementById('signup-username');
    const validationHint = document.getElementById('username-validation-hint');
    
    if (!usernameInput || !validationHint) return;
    
    // Reset if username is too short
    if (!username || username.length < 3) {
        usernameInput.classList.remove('border-green-500', 'border-red-500');
        usernameInput.classList.add('border-gray-300');
        validationHint.style.display = 'none';
        validationHint.textContent = '';
        isUsernameAvailable = false;
        return;
    }
    
    // Check if username contains only valid characters
    const validUsernamePattern = /^[a-zA-Z0-9_]+$/;
    if (!validUsernamePattern.test(username)) {
        usernameInput.classList.remove('border-green-500', 'border-gray-300');
        usernameInput.classList.add('border-red-500');
        validationHint.style.display = 'flex';
        validationHint.classList.remove('text-green-600', 'text-gray-600');
        validationHint.classList.add('text-red-600');
        validationHint.innerHTML = '<i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i> Username can only contain letters, numbers, and underscores';
        isUsernameAvailable = false;
        
        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        return;
    }
    
    // Show checking state
    validationHint.style.display = 'flex';
    validationHint.classList.remove('text-green-600', 'text-red-600');
    validationHint.classList.add('text-gray-600');
    validationHint.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 mr-1 animate-spin"></i> Checking availability...';
    
    // Re-initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Debounce the API call
    usernameCheckTimeout = setTimeout(async () => {
        try {
            const response = await fetch(`/api/check-username/${encodeURIComponent(username)}`, {
                method: 'GET',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response:', text);
                validationHint.style.display = 'flex';
                validationHint.classList.remove('text-green-600', 'text-gray-600');
                validationHint.classList.add('text-red-600');
                validationHint.innerHTML = '<i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i> Error checking username availability';
                isUsernameAvailable = false;
                
                // Re-initialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                return;
            }
            
            const result = await response.json();
            
            if (result.available) {
                // Username is available
                usernameInput.classList.remove('border-gray-300', 'border-red-500');
                usernameInput.classList.add('border-green-500');
                validationHint.style.display = 'flex';
                validationHint.classList.remove('text-red-600', 'text-gray-600');
                validationHint.classList.add('text-green-600');
                validationHint.innerHTML = '<i data-lucide="check" class="w-4 h-4 mr-1"></i> Username is available';
                isUsernameAvailable = true;
                updateFieldValidation('username', true);
                
                // Re-initialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            } else {
                // Username is taken
                usernameInput.classList.remove('border-gray-300', 'border-green-500');
                usernameInput.classList.add('border-red-500');
                validationHint.style.display = 'flex';
                validationHint.classList.remove('text-green-600', 'text-gray-600');
                validationHint.classList.add('text-red-600');
                validationHint.innerHTML = '<i data-lucide="x" class="w-4 h-4 mr-1"></i> Username is already taken';
                isUsernameAvailable = false;
                updateFieldValidation('username', false);
                
                // Re-initialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        } catch (error) {
            console.error('Error checking username:', error);
            validationHint.style.display = 'flex';
            validationHint.classList.remove('text-green-600', 'text-gray-600');
            validationHint.classList.add('text-red-600');
            validationHint.innerHTML = '<i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i> Error checking username availability';
            isUsernameAvailable = false;
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    }, 500); // Wait 500ms after user stops typing
}

// Authentication and Login Handler
document.addEventListener('DOMContentLoaded', function() {
    // First Name validation
    const firstNameInput = document.getElementById('signup-firstname');
    if (firstNameInput) {
        firstNameInput.addEventListener('input', function() {
            validateFirstName(this.value);
        });
        
        firstNameInput.addEventListener('blur', function() {
            validateFirstName(this.value);
        });
    }
    
    // Last Name validation
    const lastNameInput = document.getElementById('signup-lastname');
    if (lastNameInput) {
        lastNameInput.addEventListener('input', function() {
            validateLastName(this.value);
        });
        
        lastNameInput.addEventListener('blur', function() {
            validateLastName(this.value);
        });
    }
    
    // Email validation
    const emailInput = document.getElementById('signup-email');
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            validateEmail(this.value);
        });
        
        emailInput.addEventListener('blur', function() {
            validateEmail(this.value);
        });
    }
    
    // Username availability check
    const usernameInput = document.getElementById('signup-username');
    if (usernameInput) {
        usernameInput.addEventListener('input', function() {
            const username = this.value.trim();
            checkUsernameAvailability(username);
            // Also validate username format
            const isValid = username.length >= 3 && /^[a-zA-Z0-9_]+$/.test(username);
            updateFieldValidation('username', isValid && isUsernameAvailable);
        });
        
        usernameInput.addEventListener('blur', function() {
            const username = this.value.trim();
            if (username.length >= 3) {
                checkUsernameAvailability(username);
            }
            // Also validate username format
            const isValid = username.length >= 3 && /^[a-zA-Z0-9_]+$/.test(username);
            updateFieldValidation('username', isValid && isUsernameAvailable);
        });
    }
    
    // Password validation
    const passwordInput = document.getElementById('signup-password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            validatePassword(this.value);
        });
        
        passwordInput.addEventListener('blur', function() {
            validatePassword(this.value);
        });
    }
    
    // Confirm Password validation
    const confirmPasswordInput = document.getElementById('signup-confirm-password');
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            const password = document.getElementById('signup-password').value;
            validateConfirmPassword(password, this.value);
        });
        
        confirmPasswordInput.addEventListener('blur', function() {
            const password = document.getElementById('signup-password').value;
            validateConfirmPassword(password, this.value);
        });
    }
    
    // Forgot Password Link
    const forgotPasswordLink = document.getElementById('forgot-password-link');
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', function(e) {
            e.preventDefault();
            showForgotPasswordModal();
        });
    }
    
    // Enable/disable signup button based on form validation
    const signupButton = document.getElementById('signup-submit');
    const signupForm = document.getElementById('signup-form');
    
    if (signupButton && signupForm) {
        
        function updateSignupButton() {
            const firstName = document.getElementById('signup-firstname');
            const lastName = document.getElementById('signup-lastname');
            const email = document.getElementById('signup-email');
            const username = document.getElementById('signup-username');
            const password = document.getElementById('signup-password');
            const confirmPassword = document.getElementById('signup-confirm-password');
            
            // Check if all elements exist
            if (!firstName || !lastName || !email || !username || !password || !confirmPassword) {
                return;
            }
            
            // Try to get the button dynamically
            const currentSignupButton = document.getElementById('signup-submit');
            if (!currentSignupButton) {
                return;
            }
            
            const firstNameVal = firstName.value.trim();
            const lastNameVal = lastName.value.trim();
            const emailVal = email.value.trim();
            const usernameVal = username.value.trim();
            const passwordVal = password.value.trim();
            const confirmPasswordVal = confirmPassword.value.trim();
            
            // Enhanced validation logic focusing on confirm password
            const allFieldsFilled = firstNameVal.length > 0 && lastNameVal.length > 0 && emailVal.length > 0 && usernameVal.length > 0 && passwordVal.length > 0 && confirmPasswordVal.length > 0;
            const passwordsMatch = passwordVal === confirmPasswordVal && passwordVal.length > 0 && confirmPasswordVal.length > 0;
            const emailValid = emailVal.includes('@') && emailVal.includes('.');
            const validPasswordLength = passwordVal.length >= 8;
            const confirmPasswordNotEmpty = confirmPasswordVal.length > 0;
            const usernameValid = usernameVal.length >= 3 && isUsernameAvailable;
            
            const shouldEnable = allFieldsFilled && passwordsMatch && emailValid && validPasswordLength && confirmPasswordNotEmpty && usernameValid;
            
            // Force enable if validation is true and manually remove disable attribute
            if (shouldEnable) {
                currentSignupButton.disabled = false;
                currentSignupButton.style.pointerEvents = 'auto';
                currentSignupButton.classList.remove('opacity-50');
                currentSignupButton.removeAttribute('disabled');
                currentSignupButton.style.opacity = '1';
            } else {
                currentSignupButton.disabled = true;
                currentSignupButton.style.pointerEvents = 'none';
                currentSignupButton.classList.add('opacity-50');
                currentSignupButton.style.opacity = '0.5';
            }
        }
        
        // Add event listeners to ALL form inputs (not just required)
        const signupInputs = signupForm.querySelectorAll('input');
        signupInputs.forEach(input => {
            input.addEventListener('input', function() {
                setTimeout(updateSignupButton, 100); // Small delay to ensure value is captured
            });
            input.addEventListener('keyup', function() {
                setTimeout(updateSignupButton, 100);
            });
            input.addEventListener('change', function() {
                setTimeout(updateSignupButton, 100);
            });
            input.addEventListener('keypress', function() {
                setTimeout(updateSignupButton, 100);
            });
            input.addEventListener('paste', function() {
                setTimeout(updateSignupButton, 200); // Longer delay for paste
            });
        });
        
        // SPECIAL: Extra focus on confirm password validation
        const confirmPasswordField = document.getElementById('signup-confirm-password');
        if (confirmPasswordField) {
            confirmPasswordField.addEventListener('input', function() {
                const password = document.getElementById('signup-password').value;
                const confirm = this.value;
                const errorElement = document.getElementById('password-match-error');
                
                if (confirm.length > 0) {
                    if (password === confirm) {
                        const button = document.getElementById('signup-submit');
                        if (button) {
                            // Force enable immediately - no delays
                            button.disabled = false;
                            button.removeAttribute('disabled');
                            button.classList.remove('opacity-50');
                            button.style.opacity = '1';
                            button.style.pointerEvents = 'auto';
                        }
                        
                        this.classList.remove('is-invalid');
                        if (errorElement) errorElement.textContent = '';
                        // Call the button update immediately on password match
                        setTimeout(updateSignupButton, 10);
                    } else {
                        this.classList.add('is-invalid');
                        if (errorElement) errorElement.textContent = 'Passwords do not match';
                    }
                }
                // Also call updateSignupButton in any case to handle toggle
                setTimeout(updateSignupButton, 100);
            });
        }
        
        // Also hook into the password validation system if it exists
        const passwordInput = document.getElementById('signup-password');
        if (passwordInput) {
            // Add observer for input changes
            const observer = new MutationObserver(function() {
                setTimeout(updateSignupButton, 50);
            });
            
            // Observe password input specifically
            passwordInput.addEventListener('anychange', function() {
                setTimeout(updateSignupButton, 50);
            });
        }
        
        // Direct button click listener
        const submitButton = document.getElementById('signup-submit');
        if (submitButton) {
            submitButton.addEventListener('click', function(e) {
                if (this.disabled) {
                    e.preventDefault();
                }
            });
        }
        
        // Even more direct notification of standard button control working!
        // ...
        function forceEnableOnLastValidation() {
            updateSignupButton(); // Run the validation once last time
        }
        
        // Direct Override - Forcecheck every 100ms specifically checking confirm password
        const forceInterval = setInterval(function() {
            const firstName = document.getElementById('signup-firstname');
            const lastName = document.getElementById('signup-lastname');
            const email = document.getElementById('signup-email');
            const username = document.getElementById('signup-username');
            const password = document.getElementById('signup-password');
            const confirmPassword = document.getElementById('signup-confirm-password');
            
            if (!firstName || !lastName || !email || !username || !password || !confirmPassword) {
                return;
            }
            
            const firstNameVal = firstName.value.trim();
            const lastNameVal = lastName.value.trim();
            const emailVal = email.value.trim();
            const usernameVal = username.value.trim();
            const passwordVal = password.value.trim();
            const confirmPasswordVal = confirmPassword.value.trim();
            
            const allFieldsFilled = firstNameVal.length > 0 && lastNameVal.length > 0 && emailVal.length > 0 && usernameVal.length > 0 && passwordVal.length > 0 && confirmPasswordVal.length > 0;
            const passwordsMatch = passwordVal === confirmPasswordVal && passwordVal.length > 0 && confirmPasswordVal.length > 0;
            const emailValid = emailVal.includes('@') && emailVal.includes('.');
            const validPasswordLength = passwordVal.length >= 8;
            const usernameValid = usernameVal.length >= 3 && isUsernameAvailable;
            
            if (allFieldsFilled && passwordsMatch && emailValid && validPasswordLength && usernameValid) {
                const theButton = document.getElementById('signup-submit');
                if (theButton) {
                    theButton.disabled = false;
                    theButton.removeAttribute('disabled');
                    theButton.classList.remove('opacity-50');
                    theButton.style.opacity = '1';
                    theButton.style.pointerEvents = 'auto';
                }
            }
        }, 100);
        
        // Use setInterval as backup to ensure button state stays updated
        setInterval(updateSignupButton, 500);
        
        // Initialize button state
        setTimeout(updateSignupButton, 200);
        
    }
    
    // Handle Login Form Submission
    let isSubmittingLogin = false; // Prevent duplicate submissions
    const loginForm = document.getElementById('login-form');
    
    if (loginForm && !loginForm.dataset.listenerAttached) {
        // Mark that we've attached the listener to prevent duplicates
        loginForm.dataset.listenerAttached = 'true';
        
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation(); // Stop event from bubbling
            
            // Prevent duplicate submissions
            if (isSubmittingLogin) {
                return;
            }
            
            isSubmittingLogin = true;
            
            const formData = new FormData(this);
            const data = {
                username: formData.get('username'),
                password: formData.get('password'),
                remember: formData.get('keepLoggedIn') === 'on'
            };

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    credentials: 'include', // Include cookies for session management
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Migrate wishlist before reloading
                    try {
                        await fetch('/api/wishlist/migrate', {
                            method: 'POST',
                            credentials: 'include',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            }
                        });
                    } catch (migrationError) {
                        console.log('Wishlist migration failed:', migrationError);
                        // Continue with login even if migration fails
                    }
                    
                    // Hide modals and redirect to intended URL or reload current page
                    document.getElementById('modal-login')?.classList.add('hidden');
                    
                    if (result.redirect && result.redirect !== window.location.href) {
                        // Redirect to the intended URL
                        window.location.href = result.redirect;
                    } else {
                        // Reload current page if no specific redirect
                        location.reload();
                    }
                } else {
                    console.error('Login failed:', result.message || 'Unknown error');
                    
                    // Show error message in login modal
                    showLoginError(result.message || 'Login failed. Please check your credentials.');
                    isSubmittingLogin = false;
                }
            } catch (error) {
                console.error('Login error:', error);
                isSubmittingLogin = false;
            }
        }, { once: false }); // Don't use once:true as it would allow re-attaching
    }

    // Handle Registration Form Submission
    // signupForm already declared above, so reuse it
    let isSubmittingRegistration = false; // Prevent duplicate submissions
    
    if (signupForm && !signupForm.dataset.listenerAttached) {
        // Mark that we've attached the listener to prevent duplicates
        signupForm.dataset.listenerAttached = 'true';
        
        signupForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation(); // Stop event from bubbling
            
            // Prevent duplicate submissions
            if (isSubmittingRegistration) {
                return;
            }
            
            isSubmittingRegistration = true;
            
            // Disable button during submission
            const signupButton = document.getElementById('signup-submit');
            if (signupButton) {
                signupButton.disabled = true;
            }
            
            const formData = new FormData(this);
            const data = {
                firstName: formData.get('firstName'),
                lastName: formData.get('lastName'),
                email: formData.get('email'),
                username: formData.get('username'),
                password: formData.get('password')
            };

            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    credentials: 'include', // Include cookies for session management
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response from registration:', text);
                    // Silent failure - no alert popup
                    if (signupButton) {
                        signupButton.disabled = false;
                    }
                    isSubmittingRegistration = false;
                    return;
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Migrate wishlist before reloading
                    try {
                        await fetch('/api/wishlist/migrate', {
                            method: 'POST',
                            credentials: 'include',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            }
                        });
                    } catch (migrationError) {
                        console.log('Wishlist migration failed:', migrationError);
                        // Continue with registration even if migration fails
                    }
                    
                    // Hide modals and redirect to intended URL or reload current page
                    document.getElementById('modal-signup')?.classList.add('hidden');
                    
                    // Add a small delay to ensure session is saved
                    setTimeout(() => {
                        if (result.redirect && result.redirect !== window.location.href) {
                            // Redirect to the intended URL
                            window.location.href = result.redirect;
                        } else {
                            // Reload current page if no specific redirect
                            location.reload();
                        }
                    }, 300);
                } else {
                    // Log validation errors to console only
                    if (result.errors) {
                        console.error('Registration validation errors:', result.errors);
                    } else if (result.message) {
                        console.error('Registration failed:', result.message);
                    }
                    
                    // Re-enable button on error
                    if (signupButton) {
                        signupButton.disabled = false;
                    }
                    isSubmittingRegistration = false;
                }
            } catch (error) {
                console.error('Registration error:', error);
                // Re-enable button on error
                if (signupButton) {
                    signupButton.disabled = false;
                }
                isSubmittingRegistration = false;
            }
        }, { once: false }); // Don't use once:true as it would allow re-attaching
    }

    // Handle Logout - Simplified
    document.addEventListener('click', async function(e) {
        if (e.target && e.target.id === 'logout-btn') {
            console.log('🟢 LOGOUT BUTTON: Logout button clicked');
            
            e.preventDefault();
            e.stopPropagation();
            
            // Disable button to prevent multiple clicks
            const logoutBtn = e.target;
            logoutBtn.disabled = true;
            console.log('🟢 LOGOUT BUTTON: Button disabled');
            
            try {
                console.log('🟢 LOGOUT BUTTON: Checking if authManager exists', !!window.authManager);
                
                // Use the centralized logout from AuthManager
                if (window.authManager) {
                    console.log('🟢 LOGOUT BUTTON: Calling authManager.logout()');
                    await window.authManager.logout();
                    console.log('🟢 LOGOUT BUTTON: authManager.logout() completed');
                } else {
                    console.error('🟢 LOGOUT BUTTON: authManager not found!');
                }
                
                console.log('🟢 LOGOUT BUTTON: Redirecting to homepage');
                // Redirect to homepage after logout
                window.location.href = '/';
            } catch (error) {
                console.error('🟢 LOGOUT BUTTON: Error occurred', error);
                // Still redirect to homepage to ensure logout completes
                window.location.href = '/';
            }
        }
    });
});

// Weather API Integration Example
async function fetchWeatherData(city = 'London') {
    try {
        const response = await fetch(`/api/weather?city=${encodeURIComponent(city)}`, {
            credentials: 'include' // Include cookies for session management
        });
        const data = await response.json();
        
        if (data.success) {
            return data.data;
        } else {
            console.error('Weather API Error:', data.message);
            return null;
        }
    } catch (error) {
        console.error('Weather fetch error:', error);
        return null;
    }
}
