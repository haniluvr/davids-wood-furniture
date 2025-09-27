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

// Authentication and Login Handler
document.addEventListener('DOMContentLoaded', function() {
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
            
            const shouldEnable = allFieldsFilled && passwordsMatch && emailValid && validPasswordLength && confirmPasswordNotEmpty;
            
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
            
            if (allFieldsFilled && passwordsMatch && emailValid && validPasswordLength) {
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
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = {
                username: formData.get('username'),
                password: formData.get('password'),
            };

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Hide modals and reload page
                    document.getElementById('modal-login')?.classList.add('hidden');
                    location.reload();
                } else {
                    alert(result.message || 'Login failed');
                }
            } catch (error) {
                alert('An error occurred during login');
            }
        });
    }

    // Handle Registration Form Submission
    // signupForm already declared above, so reuse it
    if (signupForm) {
        signupForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Ensure button doesn't get stuck disabled
            const signupButton = document.getElementById('signup-submit');
            if (signupButton) {
                signupButton.disabled = false; // Enable it right before processing
                signupButton.disabled = true; // Then properly disable during submission
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
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Hide modals and reload page
                    document.getElementById('modal-signup')?.classList.add('hidden');
                    location.reload();
                } else {
                    if (result.errors) {
                        let errorMessage = '';
                        Object.keys(result.errors).forEach(key => {
                            errorMessage += `${key}: ${result.errors[key].join(', ')}\n`;
                        });
                        alert(errorMessage);
                    } else {
                        alert(result.message || 'Registration failed');
                    }
                    
                    // Re-enable button on error
                    if (signupButton) {
                        signupButton.disabled = false;
                    }
                }
            } catch (error) {
                alert('An error occurred during registration: ' + error.message);
                // Re-enable button on error
                if (signupButton) {
                    signupButton.disabled = false;
                }
            }
        });
    }

    // Handle Logout
    document.addEventListener('click', async function(e) {
        if (e.target && e.target.id === 'logout-btn') {
            e.preventDefault();
            
            try {
                const response = await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    location.reload();
                } else {
                    alert('Logout failed');
                }
            } catch (error) {
                alert('An error occurred during logout');
            }
        }
    });
});

// Weather API Integration Example
async function fetchWeatherData(city = 'London') {
    try {
        const response = await fetch(`/api/weather?city=${encodeURIComponent(city)}`);
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
