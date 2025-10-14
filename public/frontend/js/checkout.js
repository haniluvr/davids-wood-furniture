/**
 * Checkout Flow JavaScript
 * Handles form validation, shipping calculator, and flow management
 */

// Checkout flow management
class CheckoutFlow {
    constructor() {
        this.currentStep = 1;
        this.shippingRates = {
            'National Capital Region (NCR)': 50,
            'Metro Manila': 50,
            'default': 100
        };
        this.freeShippingThreshold = 5000;
        this.codLimit = 3000;
        this.vatRate = 0.12;
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.updateProgressIndicator();
    }
    
    bindEvents() {
        // Shipping form validation
        const shippingForm = document.getElementById('shipping-form');
        if (shippingForm) {
            this.setupShippingForm(shippingForm);
        }
        
        // Payment form validation
        const paymentForm = document.getElementById('payment-form');
        if (paymentForm) {
            this.setupPaymentForm(paymentForm);
        }
        
        // Review form validation
        const reviewForm = document.getElementById('review-form');
        if (reviewForm) {
            this.setupReviewForm(reviewForm);
        }
        
        // Real-time shipping calculation
        const regionSelect = document.getElementById('region');
        if (regionSelect) {
            regionSelect.addEventListener('change', () => this.calculateShipping());
        }
    }
    
    setupShippingForm(form) {
        const requiredFields = form.querySelectorAll('[required]');
        
        form.addEventListener('submit', (e) => {
            if (!this.validateShippingForm(requiredFields)) {
                e.preventDefault();
                this.showError('Please fill in all required fields.');
            }
        });
        
        // Real-time validation
        requiredFields.forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
        });
    }
    
    setupPaymentForm(form) {
        const paymentMethodRadios = form.querySelectorAll('input[name="payment_method"]');
        const newPaymentForm = document.getElementById('new-payment-form');
        const cardForm = document.getElementById('card-form');
        const gcashForm = document.getElementById('gcash-form');
        const paymentTypeRadios = form.querySelectorAll('input[name="new_payment_type"]');
        
        // Handle payment method selection
        paymentMethodRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.value === 'new') {
                    newPaymentForm.classList.remove('hidden');
                } else {
                    newPaymentForm.classList.add('hidden');
                }
                
                this.validatePaymentMethod(radio.value);
            });
        });
        
        // Handle payment type selection
        paymentTypeRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.value === 'card') {
                    cardForm.classList.remove('hidden');
                    gcashForm.classList.add('hidden');
                } else if (radio.value === 'gcash') {
                    cardForm.classList.add('hidden');
                    gcashForm.classList.remove('hidden');
                }
            });
        });
        
        // Card number formatting
        const cardNumberInput = document.getElementById('card_number');
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', (e) => {
                e.target.value = this.formatCardNumber(e.target.value);
            });
        }
        
        // CVV formatting
        const cvvInput = document.getElementById('card_cvv');
        if (cvvInput) {
            cvvInput.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        }
        
        // GCash number formatting
        const gcashNumberInput = document.getElementById('gcash_number');
        if (gcashNumberInput) {
            gcashNumberInput.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        }
        
        form.addEventListener('submit', (e) => {
            if (!this.validatePaymentForm()) {
                e.preventDefault();
                this.showError('Please complete all required payment information.');
            }
        });
    }
    
    setupReviewForm(form) {
        const termsCheckbox = document.getElementById('terms_accepted');
        const placeOrderBtn = document.getElementById('place-order-btn');
        
        if (termsCheckbox && placeOrderBtn) {
            termsCheckbox.addEventListener('change', () => {
                placeOrderBtn.disabled = !termsCheckbox.checked;
            });
            
            // Initialize button state
            placeOrderBtn.disabled = !termsCheckbox.checked;
        }
        
        form.addEventListener('submit', (e) => {
            if (!termsCheckbox.checked) {
                e.preventDefault();
                this.showError('Please accept the Terms and Conditions to continue.');
                return;
            }
            
            // Disable button to prevent double submission
            placeOrderBtn.disabled = true;
            placeOrderBtn.textContent = 'Processing...';
        });
    }
    
    validateShippingForm(requiredFields) {
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        return isValid;
    }
    
    validateField(field) {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            return false;
        } else {
            field.classList.remove('border-red-500');
            return true;
        }
    }
    
    validatePaymentMethod(method) {
        if (method === 'cod') {
            const total = this.calculateTotal();
            if (total > this.codLimit) {
                this.showError(`Cash on Delivery is only available for orders ₱${this.codLimit.toLocaleString()} and below. Your order total is ₱${total.toLocaleString()}.`);
                return false;
            }
        }
        return true;
    }
    
    validatePaymentForm() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedMethod) {
            this.showError('Please select a payment method.');
            return false;
        }
        
        if (selectedMethod.value === 'new') {
            const selectedType = document.querySelector('input[name="new_payment_type"]:checked');
            if (!selectedType) {
                this.showError('Please select a payment type.');
                return false;
            }
            
            if (selectedType.value === 'card') {
                return this.validateCardForm();
            } else if (selectedType.value === 'gcash') {
                return this.validateGcashForm();
            }
        }
        
        return true;
    }
    
    validateCardForm() {
        const requiredFields = [
            'card_number', 'card_holder_name', 'card_expiry_month', 
            'card_expiry_year', 'card_cvv'
        ];
        
        let isValid = true;
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field && !field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
            } else if (field) {
                field.classList.remove('border-red-500');
            }
        });
        
        // Validate billing address
        const billingFields = [
            'billing_address_line_1', 'billing_city', 'billing_province', 
            'billing_region', 'billing_zip_code'
        ];
        
        billingFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field && !field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
            } else if (field) {
                field.classList.remove('border-red-500');
            }
        });
        
        return isValid;
    }
    
    validateGcashForm() {
        const gcashNumber = document.getElementById('gcash_number');
        const gcashName = document.getElementById('gcash_name');
        
        let isValid = true;
        
        if (!gcashNumber.value.trim()) {
            isValid = false;
            gcashNumber.classList.add('border-red-500');
        } else {
            gcashNumber.classList.remove('border-red-500');
        }
        
        if (!gcashName.value.trim()) {
            isValid = false;
            gcashName.classList.add('border-red-500');
        } else {
            gcashName.classList.remove('border-red-500');
        }
        
        return isValid;
    }
    
    calculateShipping() {
        const regionSelect = document.getElementById('region');
        const subtotal = this.getSubtotal();
        
        if (!regionSelect || !subtotal) return;
        
        const region = regionSelect.value;
        let shippingCost = 0;
        
        if (subtotal >= this.freeShippingThreshold) {
            shippingCost = 0;
        } else {
            shippingCost = this.shippingRates[region] || this.shippingRates.default;
        }
        
        this.updateShippingDisplay(shippingCost);
        this.updateTotalDisplay();
    }
    
    calculateTotal() {
        const subtotal = this.getSubtotal();
        const shippingCost = this.getShippingCost();
        const taxAmount = subtotal * this.vatRate;
        
        return subtotal + shippingCost + taxAmount;
    }
    
    getSubtotal() {
        const subtotalElement = document.querySelector('[data-subtotal]');
        if (subtotalElement) {
            return parseFloat(subtotalElement.dataset.subtotal) || 0;
        }
        
        // Fallback: calculate from cart items
        const cartItems = document.querySelectorAll('[data-item-total]');
        let subtotal = 0;
        cartItems.forEach(item => {
            subtotal += parseFloat(item.dataset.itemTotal) || 0;
        });
        
        return subtotal;
    }
    
    getShippingCost() {
        const shippingElement = document.querySelector('[data-shipping]');
        if (shippingElement) {
            return parseFloat(shippingElement.dataset.shipping) || 0;
        }
        return 0;
    }
    
    updateShippingDisplay(cost) {
        const shippingElements = document.querySelectorAll('[data-shipping-display]');
        shippingElements.forEach(element => {
            if (cost === 0) {
                element.textContent = 'Free';
                element.classList.add('text-green-600');
            } else {
                element.textContent = `₱${cost.toLocaleString()}`;
                element.classList.remove('text-green-600');
            }
        });
    }
    
    updateTotalDisplay() {
        const total = this.calculateTotal();
        const totalElements = document.querySelectorAll('[data-total-display]');
        totalElements.forEach(element => {
            element.textContent = `₱${total.toLocaleString()}`;
        });
    }
    
    formatCardNumber(value) {
        const cleaned = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        const formatted = cleaned.match(/.{1,4}/g)?.join(' ') || cleaned;
        return formatted;
    }
    
    updateProgressIndicator() {
        const progressSteps = document.querySelectorAll('.progress-step');
        progressSteps.forEach((step, index) => {
            if (index < this.currentStep) {
                step.classList.add('completed');
            } else {
                step.classList.remove('completed');
            }
        });
    }
    
    showError(message) {
        // Create or update error message
        let errorElement = document.getElementById('checkout-error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.id = 'checkout-error';
            errorElement.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            document.body.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            errorElement.style.display = 'none';
        }, 5000);
    }
    
    showSuccess(message) {
        // Create or update success message
        let successElement = document.getElementById('checkout-success');
        if (!successElement) {
            successElement = document.createElement('div');
            successElement.id = 'checkout-success';
            successElement.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            document.body.appendChild(successElement);
        }
        
        successElement.textContent = message;
        successElement.style.display = 'block';
        
        // Auto-hide after 3 seconds
        setTimeout(() => {
            successElement.style.display = 'none';
        }, 3000);
    }
}

// Handle checkout button click from cart offcanvas
function handleCheckout() {
    // Check if user is authenticated
    fetch('/api/user/check')
        .then(response => response.json())
        .then(data => {
            if (data.authenticated) {
                // User is logged in, redirect to checkout
                window.location.href = '/checkout';
            } else {
                // User is not logged in, show login modal
                showLoginModal();
            }
        })
        .catch(error => {
            console.error('Error checking authentication:', error);
            // Fallback: show login modal
            showLoginModal();
        });
}

// Show login modal (assuming this function exists in app.js)
function showLoginModal() {
    // This function should be defined in app.js
    if (typeof window.showLoginModal === 'function') {
        window.showLoginModal();
    } else {
        // Fallback: redirect to login
        window.location.href = '/login';
    }
}

// Initialize checkout flow when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on a checkout page
    if (document.querySelector('.checkout-page') || window.location.pathname.includes('/checkout')) {
        new CheckoutFlow();
    }
});

// Export for use in other scripts
window.CheckoutFlow = CheckoutFlow;
window.handleCheckout = handleCheckout;
