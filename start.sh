#!/bin/bash

# Railway startup script for Laravel
echo "Starting Laravel application..."

# Set default environment variables if not set
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}
export DB_CONNECTION=${DB_CONNECTION:-sqlite}

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Load .env variables into the current shell environment
echo "Loading environment variables..."
set -a
. ./.env
set +a

# Debug: Check if APP_KEY is set
echo "APP_KEY status: ${APP_KEY:+SET}"
if [ -n "$APP_KEY" ]; then
    echo "APP_KEY length: ${#APP_KEY}"
else
    echo "APP_KEY is not set!"
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
