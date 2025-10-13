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
    // Username availability check
    const usernameInput = document.getElementById('signup-username');
    if (usernameInput) {
        usernameInput.addEventListener('input', function() {
            const username = this.value.trim();
            checkUsernameAvailability(username);
        });
        
        usernameInput.addEventListener('blur', function() {
            const username = this.value.trim();
            if (username.length >= 3) {
                checkUsernameAvailability(username);
            }
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
                    
                    // Hide modals and reload page
                    document.getElementById('modal-login')?.classList.add('hidden');
                    location.reload();
                } else {
                    console.error('Login failed:', result.message || 'Unknown error');
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
                    
                    // Hide modals and reload page
                    document.getElementById('modal-signup')?.classList.add('hidden');
                    
                    // Add a small delay to ensure session is saved
                    setTimeout(() => {
                        location.reload();
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
