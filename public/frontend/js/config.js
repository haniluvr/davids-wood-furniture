// Frontend Configuration
window.APP_CONFIG = {
    // API Configuration
    API_BASE_URL: 'http://localhost:8000/api',
    
    // App Settings
    APP_NAME: "David's Wood",
    APP_VERSION: "1.0.0",
    
    // Feature Flags
    FEATURES: {
        ENABLE_CART: true,
        ENABLE_WISHLIST: true,
        ENABLE_REVIEWS: true,
        ENABLE_SEARCH: true,
        ENABLE_FILTERS: true,
    },
    
    // UI Settings
    UI: {
        ITEMS_PER_PAGE: 12,
        CART_ANIMATION_DURATION: 300,
        NOTIFICATION_DURATION: 5000,
    },
    
    // Local Storage Keys
    STORAGE_KEYS: {
        AUTH_TOKEN: 'auth_token',
        CART_ITEMS: 'cart_items',
        WISHLIST_ITEMS: 'wishlist_items',
        USER_PREFERENCES: 'user_preferences',
    }
};

// Environment-specific configurations
if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    // Development environment
    window.APP_CONFIG.API_BASE_URL = 'http://localhost:8000/api';
} else {
    // Production environment
    window.APP_CONFIG.API_BASE_URL = 'https://your-domain.com/api';
}
