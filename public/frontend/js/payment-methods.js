/**
 * Payment Methods Management JavaScript
 * Handles adding, editing, deleting, and managing payment methods
 */

class PaymentMethodManager {
    constructor() {
        this.paymentMethods = [];
        this.currentEditingId = null;
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.loadPaymentMethods();
    }
    
    bindEvents() {
        // Payment type radio buttons
        const typeRadios = document.querySelectorAll('input[name="payment_type"]');
        typeRadios.forEach(radio => {
            radio.addEventListener('change', () => this.toggleFormFields());
        });
        
        // Form submission
        const form = document.getElementById('payment-method-form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
        
        // Card number formatting
        const cardNumberInput = document.getElementById('card-number');
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', (e) => {
                e.target.value = this.formatCardNumber(e.target.value);
            });
        }
        
        // Card expiry formatting
        const cardExpiryInput = document.getElementById('card-expiry');
        if (cardExpiryInput) {
            cardExpiryInput.addEventListener('input', (e) => {
                e.target.value = this.formatCardExpiry(e.target.value);
            });
        }
        
        // CVV formatting
        const cvvInput = document.getElementById('card-cvv');
        if (cvvInput) {
            cvvInput.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        }
        
        // GCash number formatting
        const gcashNumberInput = document.getElementById('gcash-number');
        if (gcashNumberInput) {
            gcashNumberInput.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        }
    }
    
    async loadPaymentMethods() {
        try {
            const response = await fetch('/api/payment-methods');
            const data = await response.json();
            
            if (data.success) {
                this.paymentMethods = data.data;
                this.renderPaymentMethods();
            } else {
                this.showError('Failed to load payment methods');
            }
        } catch (error) {
            console.error('Error loading payment methods:', error);
            this.showError('Failed to load payment methods');
        }
    }
    
    renderPaymentMethods() {
        const container = document.getElementById('payment-methods-list');
        const emptyState = document.getElementById('payment-methods-empty');
        
        if (!container) return;
        
        if (this.paymentMethods.length === 0) {
            container.innerHTML = '';
            if (emptyState) {
                emptyState.classList.remove('hidden');
            }
            return;
        }
        
        if (emptyState) {
            emptyState.classList.add('hidden');
        }
        
        container.innerHTML = this.paymentMethods.map(pm => this.renderPaymentMethodCard(pm)).join('');
    }
    
    renderPaymentMethodCard(paymentMethod) {
        const isExpired = paymentMethod.type === 'card' && this.isCardExpired(paymentMethod);
        const expiredText = isExpired ? '<span class="text-red-600 font-medium ml-2">(Expired)</span>' : '';
        
        return `
            <div class="payment-method-card border border-gray-200 rounded-lg p-4" data-payment-method-id="${paymentMethod.id}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            ${paymentMethod.type === 'card' ? 
                                '<i data-lucide="credit-card" class="w-5 h-5 text-gray-600 mr-3"></i>' : 
                                '<i data-lucide="smartphone" class="w-5 h-5 text-gray-600 mr-3"></i>'
                            }
                            <div>
                                <h4 class="font-medium text-gray-900">${this.getDisplayName(paymentMethod)}</h4>
                                <p class="text-sm text-gray-600">
                                    ${paymentMethod.type === 'card' ? 
                                        `${this.getMaskedNumber(paymentMethod)} • Expires ${this.getFormattedExpiry(paymentMethod)}${expiredText}` : 
                                        paymentMethod.gcash_name
                                    }
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        ${paymentMethod.is_default ? 
                            '<span class="px-2 py-1 text-xs font-medium bg-[#8b7355] text-white rounded">Default</span>' : 
                            `<button onclick="paymentMethodManager.setDefaultPaymentMethod(${paymentMethod.id})" class="text-sm text-[#8b7355] hover:text-[#6b5b47] font-medium">Set as Default</button>`
                        }
                        
                        <button onclick="paymentMethodManager.editPaymentMethod(${paymentMethod.id})" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Edit</button>
                        
                        <button onclick="paymentMethodManager.deletePaymentMethod(${paymentMethod.id})" class="text-sm text-red-600 hover:text-red-800 font-medium">Delete</button>
                    </div>
                </div>
            </div>
        `;
    }
    
    showAddForm() {
        this.currentEditingId = null;
        this.resetForm();
        document.getElementById('add-payment-method-form').style.display = 'block';
        document.querySelector('#add-payment-method-form h3').textContent = 'Add Payment Method';
        this.toggleFormFields();
        
        // Scroll to the form
        document.getElementById('add-payment-method-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    editPaymentMethod(id) {
        const paymentMethod = this.paymentMethods.find(pm => pm.id === id);
        if (!paymentMethod) return;
        
        this.currentEditingId = id;
        this.populateForm(paymentMethod);
        document.getElementById('add-payment-method-form').style.display = 'block';
        document.querySelector('#add-payment-method-form h3').textContent = 'Edit Payment Method';
        this.toggleFormFields();
        
        // Scroll to the form
        document.getElementById('add-payment-method-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    async deletePaymentMethod(id) {
        if (!confirm('Are you sure you want to delete this payment method?')) {
            return;
        }
        
        try {
            const response = await fetch(`/api/payment-methods/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Payment method deleted successfully');
                this.loadPaymentMethods();
            } else {
                this.showError('Failed to delete payment method');
            }
        } catch (error) {
            console.error('Error deleting payment method:', error);
            this.showError('Failed to delete payment method');
        }
    }
    
    async setDefaultPaymentMethod(id) {
        try {
            const response = await fetch(`/api/payment-methods/${id}/set-default`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Default payment method updated');
                this.loadPaymentMethods();
            } else {
                this.showError('Failed to update default payment method');
            }
        } catch (error) {
            console.error('Error setting default payment method:', error);
            this.showError('Failed to update default payment method');
        }
    }
    
    async handleFormSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        
        // Handle card expiry parsing
        if (data.payment_type === 'card' && data.card_expiry) {
            const [month, year] = data.card_expiry.split('/');
            data.card_expiry_month = parseInt(month);
            data.card_expiry_year = parseInt('20' + year);
            delete data.card_expiry;
        }
        
        // Handle billing address
        if (data['billing_address_line_1']) {
            data.billing_address = {
                address_line_1: data['billing_address_line_1'],
                address_line_2: data['billing_address_line_2'],
                city: data['billing_city'],
                province: data['billing_province'],
                region: data['billing_region'],
                barangay: data['billing_barangay'],
                zip_code: data['billing_zip_code']
            };
            
            // Remove the original keys
            delete data['billing_address_line_1'];
            delete data['billing_address_line_2'];
            delete data['billing_city'];
            delete data['billing_province'];
            delete data['billing_region'];
            delete data['billing_barangay'];
            delete data['billing_zip_code'];
        }
        
        // Add card type detection for cards
        if (data.payment_type === 'card' && data.card_number) {
            data.card_type = this.detectCardType(data.card_number);
        }
        
        // Map payment_type to type for API
        data.type = data.payment_type;
        delete data.payment_type;
        
        try {
            const url = this.currentEditingId ? 
                `/api/payment-methods/${this.currentEditingId}` : 
                '/api/payment-methods';
            
            const method = this.currentEditingId ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess(this.currentEditingId ? 'Payment method updated successfully' : 'Payment method added successfully');
                this.hideAddForm();
                this.loadPaymentMethods();
            } else {
                this.showError(result.message || 'Failed to save payment method');
            }
        } catch (error) {
            console.error('Error saving payment method:', error);
            this.showError('Failed to save payment method');
        }
    }
    
    populateForm(paymentMethod) {
        document.querySelector(`input[name="payment_type"][value="${paymentMethod.type}"]`).checked = true;
        
        if (paymentMethod.type === 'card') {
            document.getElementById('card-number').value = '';
            document.getElementById('card-expiry').value = paymentMethod.card_expiry_month && paymentMethod.card_expiry_year ? 
                `${paymentMethod.card_expiry_month.toString().padStart(2, '0')}/${paymentMethod.card_expiry_year}` : '';
            document.getElementById('card-cvv').value = '';
            document.querySelector('input[name="card_holder_name"]').value = paymentMethod.card_holder_name || '';
            
            if (paymentMethod.billing_address) {
                const billingAddress = typeof paymentMethod.billing_address === 'string' ? 
                    JSON.parse(paymentMethod.billing_address) : 
                    paymentMethod.billing_address;
                
                document.querySelector('input[name="billing_address_line_1"]').value = billingAddress.address_line_1 || '';
                document.querySelector('input[name="billing_address_line_2"]').value = billingAddress.address_line_2 || '';
                document.querySelector('select[name="billing_region"]').value = billingAddress.region || '';
                document.querySelector('select[name="billing_province"]').value = billingAddress.province || '';
                document.querySelector('select[name="billing_city"]').value = billingAddress.city || '';
                document.querySelector('select[name="billing_barangay"]').value = billingAddress.barangay || '';
                document.querySelector('input[name="billing_zip_code"]').value = billingAddress.zip_code || '';
            }
        } else if (paymentMethod.type === 'gcash') {
            document.getElementById('gcash-number').value = paymentMethod.gcash_number || '';
            document.querySelector('input[name="gcash_name"]').value = paymentMethod.gcash_name || '';
        }
        
        document.querySelector('input[name="is_default"]').checked = paymentMethod.is_default || false;
    }
    
    resetForm() {
        document.getElementById('payment-method-form').reset();
        document.querySelector('input[name="payment_type"][value="card"]').checked = true;
    }
    
    toggleFormFields() {
        const selectedType = document.querySelector('input[name="payment_type"]:checked').value;
        const cardFields = document.getElementById('card-fields');
        const gcashFields = document.getElementById('gcash-fields');
        
        if (selectedType === 'card') {
            cardFields.style.display = 'block';
            gcashFields.style.display = 'none';
        } else {
            cardFields.style.display = 'none';
            gcashFields.style.display = 'block';
        }
    }
    
    hideAddForm() {
        document.getElementById('add-payment-method-form').style.display = 'none';
        this.currentEditingId = null;
    }
    
    formatCardNumber(value) {
        const cleaned = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        const formatted = cleaned.match(/.{1,4}/g)?.join(' ') || cleaned;
        return formatted;
    }
    
    formatCardExpiry(value) {
        const cleaned = value.replace(/\D/g, '');
        if (cleaned.length >= 2) {
            return cleaned.substring(0, 2) + '/' + cleaned.substring(2, 4);
        }
        return cleaned;
    }
    
    detectCardType(cardNumber) {
        const cleaned = cardNumber.replace(/\D/g, '');
        
        if (/^4/.test(cleaned)) return 'Visa';
        if (/^5[1-5]/.test(cleaned)) return 'Mastercard';
        if (/^3[47]/.test(cleaned)) return 'American Express';
        if (/^6/.test(cleaned)) return 'Discover';
        
        return 'Unknown';
    }
    
    getDisplayName(paymentMethod) {
        if (paymentMethod.type === 'card') {
            return `${paymentMethod.card_type} •••• ${paymentMethod.card_last_four}`;
        } else if (paymentMethod.type === 'gcash') {
            return `GCash •••• ${paymentMethod.gcash_number.slice(-4)}`;
        }
        return 'Unknown Payment Method';
    }
    
    getMaskedNumber(paymentMethod) {
        if (paymentMethod.type === 'card') {
            return `•••• •••• •••• ${paymentMethod.card_last_four}`;
        } else if (paymentMethod.type === 'gcash') {
            return `•••• •••• ${paymentMethod.gcash_number.slice(-4)}`;
        }
        return '•••• •••• •••• ••••';
    }
    
    getFormattedExpiry(paymentMethod) {
        if (paymentMethod.card_expiry_month && paymentMethod.card_expiry_year) {
            return `${paymentMethod.card_expiry_month.toString().padStart(2, '0')}/${paymentMethod.card_expiry_year}`;
        }
        return '';
    }
    
    isCardExpired(paymentMethod) {
        if (paymentMethod.type !== 'card' || !paymentMethod.card_expiry_month || !paymentMethod.card_expiry_year) {
            return false;
        }
        
        const currentYear = new Date().getFullYear();
        const currentMonth = new Date().getMonth() + 1;
        
        return paymentMethod.card_expiry_year < currentYear || 
               (paymentMethod.card_expiry_year == currentYear && paymentMethod.card_expiry_month < currentMonth);
    }
    
    showSuccess(message) {
        this.showMessage(message, 'success');
    }
    
    showError(message) {
        this.showMessage(message, 'error');
    }
    
    showMessage(message, type) {
        // Create or update message element
        let messageElement = document.getElementById('payment-method-message');
        if (!messageElement) {
            messageElement = document.createElement('div');
            messageElement.id = 'payment-method-message';
            messageElement.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50';
            document.body.appendChild(messageElement);
        }
        
        messageElement.textContent = message;
        messageElement.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        messageElement.style.display = 'block';
        
        // Auto-hide after 3 seconds
        setTimeout(() => {
            messageElement.style.display = 'none';
        }, 3000);
    }
}

// Global functions for onclick handlers
function showAddPaymentMethodForm() {
    if (window.paymentMethodManager) {
        window.paymentMethodManager.showAddForm();
    }
}

function hideAddPaymentMethodForm() {
    if (window.paymentMethodManager) {
        window.paymentMethodManager.hideAddForm();
    }
}

function setDefaultPaymentMethod(id) {
    if (window.paymentMethodManager) {
        window.paymentMethodManager.setDefaultPaymentMethod(id);
    }
}

function editPaymentMethod(id) {
    if (window.paymentMethodManager) {
        window.paymentMethodManager.editPaymentMethod(id);
    }
}

function deletePaymentMethod(id) {
    if (window.paymentMethodManager) {
        window.paymentMethodManager.deletePaymentMethod(id);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on the account page with payment methods section
    if (document.getElementById('payment-methods-section')) {
        window.paymentMethodManager = new PaymentMethodManager();
    }
});

// Export for use in other scripts
window.PaymentMethodManager = PaymentMethodManager;
