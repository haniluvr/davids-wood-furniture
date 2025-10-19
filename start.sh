#!/bin/bash

# Railway startup script for Laravel
echo "Starting Laravel application..."

# Set basic environment variables
export APP_ENV=production
export APP_DEBUG=false
export DB_CONNECTION=sqlite
export DB_DATABASE=/var/www/html/database/database.sqlite

# Create .env file with basic configuration
echo "Creating .env file..."
cat > .env << EOF
APP_NAME="David's Wood Furniture"
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@davidswood.test"
MAIL_FROM_NAME="David's Wood Furniture"

VITE_APP_NAME="David's Wood Furniture"
EOF

# Generate APP_KEY
echo "Generating APP_KEY..."
php artisan key:generate --force

# Create necessary directories
echo "Creating directories..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache
mkdir -p database

# Create SQLite database
echo "Creating SQLite database..."
touch database/database.sqlite
chmod 664 database/database.sqlite

# Set permissions
chmod -R 775 storage bootstrap/cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Start the application
echo "Starting PHP server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT --verbose
