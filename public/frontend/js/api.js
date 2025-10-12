// API Configuration and Helper Functions
class DavidsWoodAPI {
    constructor() {
        this.baseURL = window.APP_CONFIG?.API_BASE_URL || window.location.origin + '/api';
        this.token = localStorage.getItem(window.APP_CONFIG?.STORAGE_KEYS?.AUTH_TOKEN || 'auth_token');
    }

    // Set authentication token
    setToken(token) {
        this.token = token;
        localStorage.setItem(window.APP_CONFIG?.STORAGE_KEYS?.AUTH_TOKEN || 'auth_token', token);
    }

    // Remove authentication token
    removeToken() {
        this.token = null;
        localStorage.removeItem(window.APP_CONFIG?.STORAGE_KEYS?.AUTH_TOKEN || 'auth_token');
    }

    // Get headers for API requests
    getHeaders() {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };

        // Add CSRF token for all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        return headers;
    }

    // Make API request
    async request(endpoint, options = {}) {
        // Special handling for logout - use web routes, not API routes
        const url = endpoint === '/logout' ? window.location.origin + '/logout' : `${this.baseURL}${endpoint}`;
        const config = {
            headers: this.getHeaders(),
            credentials: 'include', // Include cookies for session management
            ...options,
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'API request failed');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // Authentication methods
    async login(email, password) {
        const response = await this.request('/login', {
            method: 'POST',
            body: JSON.stringify({ email, password }),
        });

        if (response.success && response.data.token) {
            this.setToken(response.data.token);
        }

        return response;
    }

    async register(userData) {
        const response = await this.request('/register', {
            method: 'POST',
            body: JSON.stringify(userData),
        });

        if (response.success && response.data.token) {
            this.setToken(response.data.token);
        }

        return response;
    }

    async logout() {
        try {
            const response = await this.request('/logout', {
                method: 'POST',
                body: JSON.stringify({})
            });
            return response;
            
        } catch (error) {
            console.error('Logout error:', error);
            return { success: true, message: 'Logged out successfully' };
        } finally {
            // Always clear local state
            this.removeToken();
            localStorage.removeItem('cart_items');
            localStorage.removeItem('wishlist_items');
            if (typeof clearCartState === 'function') {
                clearCartState();
            }
        }
    }

    async getCurrentUser() {
        return await this.request('/user');
    }

    // Product methods
    async getProducts(filters = {}) {
        const params = new URLSearchParams(filters);
        return await this.request(`/products?${params}`);
    }

    async getFeaturedProducts() {
        return await this.request('/products/featured');
    }

    async getCategories() {
        return await this.request('/products/categories');
    }

    async getProduct(slug) {
        return await this.request(`/products/${slug}`);
    }

    async getProductById(id) {
        return await this.request(`/product/${id}`);
    }

    async searchProducts(query) {
        const params = new URLSearchParams({ q: query });
        return await this.request(`/search?${params}`);
    }

    // Cart methods
    async getCart() {
        return await this.request('/cart');
    }

    async addToCart(productId, quantity = 1) {
        return await this.request('/cart/add', {
            method: 'POST',
            body: JSON.stringify({ product_id: productId, quantity }),
        });
    }

    async updateCartItem(productId, quantity) {
        return await this.request('/cart/update', {
            method: 'PUT',
            body: JSON.stringify({ product_id: productId, quantity }),
        });
    }

    async removeFromCart(productId) {
        return await this.request('/cart/remove', {
            method: 'DELETE',
            body: JSON.stringify({ product_id: productId }),
        });
    }

    async clearCart() {
        return await this.request('/cart/clear', {
            method: 'DELETE',
        });
    }

    // Wishlist methods
    async getWishlist() {
        return await this.request('/wishlist');
    }

    async addToWishlist(productId) {
        return await this.request('/wishlist/add', {
            method: 'POST',
            body: JSON.stringify({ product_id: productId }),
        });
    }

    async removeFromWishlist(productId) {
        return await this.request('/wishlist/remove', {
            method: 'DELETE',
            body: JSON.stringify({ product_id: productId }),
        });
    }

    async checkWishlist(productId) {
        if (!productId) {
            throw new Error('Product ID is required for wishlist check');
        }
        return await this.request(`/wishlist/check/${productId}`);
    }

    async migrateWishlist(guestWishlist) {
        return await this.request('/wishlist/migrate', {
            method: 'POST',
            body: JSON.stringify({ guest_wishlist: guestWishlist }),
        });
    }

    async toggleWishlist(productId) {
        return await this.request('/wishlist/toggle', {
            method: 'POST',
            body: JSON.stringify({ product_id: productId }),
        });
    }

    async clearWishlist() {
        return await this.request('/wishlist/clear', {
            method: 'DELETE',
        });
    }

    // Order methods
    async getOrders() {
        return await this.request('/orders');
    }

    async createOrder(orderData) {
        return await this.request('/orders', {
            method: 'POST',
            body: JSON.stringify(orderData),
        });
    }

    async getOrder(orderId) {
        return await this.request(`/orders/${orderId}`);
    }

    async cancelOrder(orderId) {
        return await this.request(`/orders/${orderId}/cancel`, {
            method: 'POST',
        });
    }
}

// Create global API instance
window.api = new DavidsWoodAPI();

// Authentication state management
class AuthManager {
    constructor() {
        this.isAuthenticated = !!window.api.token;
        this.user = null;
    }

    async checkAuth() {
        if (this.isAuthenticated && window.api.token) {
            try {
                const response = await window.api.getCurrentUser();
                if (response.success) {
                    this.user = response.data.user;
                    this.updateUI();
                    return true;
                }
            } catch (error) {
                console.error('Auth check failed:', error);
                this.logout();
            }
        }
        return false;
    }

    async login(email, password) {
        try {
            const response = await window.api.login(email, password);
            if (response.success) {
                this.isAuthenticated = true;
                this.user = response.data.user;
                this.updateUI();
                
                // Migrate guest wishlist to user account
                this.migrateGuestWishlist();
                
                return { success: true, message: 'Login successful' };
            }
        } catch (error) {
            return { success: false, message: error.message };
        }
    }

    async register(userData) {
        try {
            const response = await window.api.register(userData);
            if (response.success) {
                this.isAuthenticated = true;
                this.user = response.data.user;
                this.updateUI();
                
                // Migrate guest wishlist to user account
                this.migrateGuestWishlist();
                
                return { success: true, message: 'Registration successful' };
            }
        } catch (error) {
            return { success: false, message: error.message };
        }
    }

    async logout() {
        console.log('🟡 AUTH MANAGER LOGOUT: Starting logout process');
        
        try {
            console.log('🟡 AUTH MANAGER LOGOUT: Calling API logout');
            await window.api.logout();
            console.log('🟡 AUTH MANAGER LOGOUT: API logout completed');
        } catch (error) {
            console.error('🟡 AUTH MANAGER LOGOUT: Error occurred', error);
        } finally {
            console.log('🟡 AUTH MANAGER LOGOUT: Clearing authentication state');
            // Always clear local state
            this.isAuthenticated = false;
            this.user = null;
            console.log('🟡 AUTH MANAGER LOGOUT: Authentication state cleared');
            
            console.log('🟡 AUTH MANAGER LOGOUT: Updating UI');
            this.updateUI();
            console.log('🟡 AUTH MANAGER LOGOUT: UI updated');
        }
    }

    updateUI() {
        // Update navbar based on authentication state
        const accountDropdown = document.getElementById('account-dropdown');
        const accountMenu = document.getElementById('account-menu');
        
        if (this.isAuthenticated && this.user) {
            // Show user name and logout option
            if (accountDropdown) {
                accountDropdown.innerHTML = `
                    <span class="text-sm font-medium text-gray-700">${this.user.name}</span>
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                `;
            }
            
            if (accountMenu) {
                accountMenu.innerHTML = `
                    <div class="py-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Account</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Wishlist</a>
                        <hr class="my-1">
                        <a href="#" id="logout-btn" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                    </div>
                `;
                
                // Add logout event listener
                const logoutBtn = document.getElementById('logout-btn');
                if (logoutBtn) {
                    logoutBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.logout();
                    });
                }
            }
        } else {
            // Show login/signup options
            if (accountDropdown) {
                accountDropdown.innerHTML = `
                    <span class="text-sm font-medium text-gray-700">Account</span>
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                `;
            }
            
            if (accountMenu) {
                accountMenu.innerHTML = `
                    <div class="py-1">
                        <a href="#" id="open-login-modal" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign In</a>
                        <a href="#" id="open-signup-modal" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Create Account</a>
                    </div>
                `;
            }
        }
        
        // Re-initialize icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    /**
     * Migrate guest wishlist to user account
     * Note: With the new database structure, wishlist migration is handled automatically
     * by the backend when users authenticate. No frontend action needed.
     */
    async migrateGuestWishlist() {
        try {
            // With the new session-based structure, wishlist migration is handled
            // automatically by the backend. Just clear any localStorage wishlist items
            localStorage.removeItem('wishlist_items');
            console.log('Guest wishlist migration handled by backend');
        } catch (error) {
            console.error('Error in wishlist migration:', error);
        }
    }
}

// Create global auth manager
window.authManager = new AuthManager();
