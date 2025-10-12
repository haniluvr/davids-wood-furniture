// Frontend Configuration
window.APP_CONFIG = {
    // API Configuration
    API_BASE_URL: window.location.origin + '/api',
    
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
// Always use the current domain origin for API calls
window.APP_CONFIG.API_BASE_URL = window.location.origin + '/api';
