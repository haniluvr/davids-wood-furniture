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
        // Special handling for auth routes - use web routes, not API routes
        const authRoutes = ['/logout', '/login', '/register'];
        const url = authRoutes.includes(endpoint) 
            ? window.location.origin + endpoint 
            : `${this.baseURL}${endpoint}`;
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
    async login(username, password, remember = false) {
        const response = await this.request('/login', {
            method: 'POST',
            body: JSON.stringify({ username, password, remember }),
        });

        // No need to set token for session-based auth
        return response;
    }

    async register(userData) {
        const response = await this.request('/register', {
            method: 'POST',
            body: JSON.stringify(userData),
        });

        // No need to set token for session-based auth
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
        this.isAuthenticated = false; // Will be set by checkAuth()
        this.user = null;
    }

    async checkAuth() {
        try {
            // Always check with server to verify session is still valid
            const response = await fetch('/api/user/check', {
                method: 'GET',
                credentials: 'include', // Include cookies for session management
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();

                if (data.authenticated && data.user) {
                    this.isAuthenticated = true;
                    this.user = data.user;
                    this.updateUI();
                    return true;
                }
            } else if (response.status === 419) {

            } else {

            }
        } catch (error) {
            console.error('Auth check failed:', error);
        }
        
        // If we get here, user is not authenticated
        this.isAuthenticated = false;
        this.user = null;
        this.updateUI();
        return false;
    }

    async login(username, password, remember = false) {
        try {
            const response = await window.api.login(username, password, remember);
            if (response.success) {
                this.isAuthenticated = true;
                this.user = response.user;
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
                this.user = response.user;
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

        try {

            await window.api.logout();

        } catch (error) {
            console.error('🟡 AUTH MANAGER LOGOUT: Error occurred', error);
        } finally {

            // Always clear local state
            this.isAuthenticated = false;
            this.user = null;

            this.updateUI();

        }
    }

    updateUI() {
        // Don't modify the navbar HTML, but ensure auth state is properly managed
        // This prevents the "appearing logged out" issue after page navigation
        
        // The navbar template handles the UI display based on @auth/@guest directives
        // We just need to ensure the frontend auth state is consistent
        // No UI modifications needed - Laravel handles this server-side
        
        // Log auth state for debugging

        return;
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

        } catch (error) {
            console.error('Error in wishlist migration:', error);
        }
    }
}

// Create global auth manager
window.authManager = new AuthManager();
