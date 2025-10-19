#!/bin/bash

# Railway startup script for Laravel
echo "Starting Laravel application..."

# Set default environment variables if not set
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}
export DB_CONNECTION=${DB_CONNECTION:-sqlite}

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Clear and cache config
echo "Caching configuration..."
php artisan config:clear
php artisan config:cache

# Create storage directories if they don't exist
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set proper permissions
chmod -R 775 storage bootstrap/cache

# Start the application
echo "Starting PHP server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT --verbose
