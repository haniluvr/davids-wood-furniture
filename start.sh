#!/bin/bash

# Railway startup script for Laravel
echo "Starting Laravel application..."

# Set basic environment variables
export APP_ENV=production
export APP_DEBUG=false
export DB_CONNECTION=mysql

# Debug all environment variables first
echo "--- All environment variables ---"
printenv | grep -E "(MYSQL|DB_)" | head -10
echo "--- End environment variables ---"

# Parse MYSQL_URL if provided (Railway format)
if [ -n "$MYSQL_URL" ]; then
    echo "Parsing MYSQL_URL..."
    echo "MYSQL_URL: $MYSQL_URL"
    # MYSQL_URL format: mysql://username:password@host:port/database
    export DB_HOST=$(echo $MYSQL_URL | sed 's/.*@\([^:]*\):.*/\1/')
    export DB_PORT=$(echo $MYSQL_URL | sed 's/.*:\([0-9]*\)\/.*/\1/')
    export DB_DATABASE=$(echo $MYSQL_URL | sed 's/.*\/\([^?]*\).*/\1/')
    export DB_USERNAME=$(echo $MYSQL_URL | sed 's/mysql:\/\/\([^:]*\):.*/\1/')
    export DB_PASSWORD=$(echo $MYSQL_URL | sed 's/mysql:\/\/[^:]*:\([^@]*\)@.*/\1/')
    echo "Parsed DB_HOST: $DB_HOST"
    echo "Parsed DB_PORT: $DB_PORT"
    echo "Parsed DB_DATABASE: $DB_DATABASE"
    echo "Parsed DB_USERNAME: $DB_USERNAME"
else
    echo "MYSQL_URL not found, checking individual variables..."
    echo "DB_HOST: ${DB_HOST:-not set}"
    echo "DB_PORT: ${DB_PORT:-not set}"
    echo "DB_DATABASE: ${DB_DATABASE:-not set}"
    echo "DB_USERNAME: ${DB_USERNAME:-not set}"
    echo "DB_PASSWORD: ${DB_PASSWORD:-not set}"
    
    # Fallback to individual variables
    export DB_HOST=${DB_HOST:-mysql}
    export DB_PORT=${DB_PORT:-3306}
    export DB_DATABASE=${DB_DATABASE:-davidswood_furniture}
    export DB_USERNAME=${DB_USERNAME:-root}
    export DB_PASSWORD=${DB_PASSWORD:-}
    echo "Using fallback values:"
    echo "Fallback DB_HOST: $DB_HOST"
    echo "Fallback DB_PORT: $DB_PORT"
    echo "Fallback DB_DATABASE: $DB_DATABASE"
fi

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

DB_CONNECTION=mysql
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=database
SESSION_LIFETIME=120

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@davidswood.test"
MAIL_FROM_NAME="David's Wood Furniture"

VITE_APP_NAME="David's Wood Furniture"
EOF

# Generate APP_KEY
echo "Generating APP_KEY..."
php artisan key:generate --force

# Verify APP_KEY was generated and reload it
echo "Verifying APP_KEY..."
if grep -q "APP_KEY=" .env && ! grep -q "APP_KEY=$" .env; then
    echo "APP_KEY generated successfully"
    # Extract the APP_KEY from .env and export it
    APP_KEY_VALUE=$(grep "APP_KEY=" .env | cut -d'=' -f2)
    export APP_KEY="$APP_KEY_VALUE"
    echo "Exported APP_KEY: $APP_KEY"
else
    echo "APP_KEY generation failed, creating manually..."
    # Generate a 32-character random key manually
    APP_KEY_VALUE="base64:$(openssl rand -base64 32)"
    sed -i "s/APP_KEY=/APP_KEY=$APP_KEY_VALUE/" .env
    export APP_KEY="$APP_KEY_VALUE"
    echo "Manually created APP_KEY: $APP_KEY"
fi

# Create necessary directories
echo "Creating directories..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set permissions
chmod -R 775 storage bootstrap/cache

# Wait for MySQL to be ready (with timeout)
echo "Waiting for MySQL connection..."
timeout=30
counter=0
until php artisan tinker --execute="DB::connection()->getPdo();" 2>/dev/null; do
    echo "MySQL is unavailable - sleeping ($counter/$timeout)"
    sleep 2
    counter=$((counter + 2))
    if [ $counter -ge $timeout ]; then
        echo "MySQL connection timeout - continuing with file sessions"
        # Switch to file sessions if MySQL is not available
        sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=file/' .env
        break
    fi
done

if [ $counter -lt $timeout ]; then
    echo "MySQL is ready!"
    # Run migrations only if MySQL is connected
    echo "Running migrations..."
    php artisan migrate --force
else
    echo "Skipping migrations due to MySQL connection issues"
fi

# Test Laravel configuration
echo "Testing Laravel configuration..."
php artisan config:show app.name
php artisan config:show app.key

# Final APP_KEY verification
echo "Final APP_KEY verification..."
if [ -n "$APP_KEY" ] && [ "$APP_KEY" != "" ]; then
    echo "APP_KEY is properly set: $APP_KEY"
else
    echo "ERROR: APP_KEY is still not set!"
    echo "Contents of .env APP_KEY line:"
    grep "APP_KEY=" .env
    exit 1
fi

# Debug environment variables
echo "--- Environment variables before starting PHP server ---"
echo "PORT: $PORT"
echo "APP_ENV: $APP_ENV"
echo "DB_HOST: $DB_HOST"
echo "DB_DATABASE: $DB_DATABASE"
echo "--- End environment variables ---"

# Start the application
echo "Starting PHP server on port $PORT..."
echo "Server will be available at: http://0.0.0.0:$PORT"
php artisan serve --host=0.0.0.0 --port=$PORT --verbose
