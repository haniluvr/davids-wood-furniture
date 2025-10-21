#!/bin/bash

# Simplified Laravel startup script for Docker Compose
echo "Starting Laravel application..."

# Set default PORT if not provided
export PORT=${PORT:-80}

# Create .env file with environment variables from Docker Compose
echo "Creating .env file..."
cat > .env << EOF
APP_NAME="David's Wood Furniture"
APP_ENV=${APP_ENV:-production}
APP_DEBUG=${APP_DEBUG:-false}
APP_KEY=
APP_URL=${APP_URL:-http://localhost:$PORT}

LOG_CHANNEL=stack
LOG_LEVEL=${LOG_LEVEL:-error}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST:-mysql}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-davids_wood}
DB_USERNAME=${DB_USERNAME:-davidswood_user}
DB_PASSWORD=${DB_PASSWORD}

BROADCAST_DRIVER=log
CACHE_DRIVER=${CACHE_DRIVER:-redis}
FILESYSTEM_DISK=local
QUEUE_CONNECTION=${QUEUE_CONNECTION:-redis}
SESSION_DRIVER=${SESSION_DRIVER:-redis}
SESSION_LIFETIME=120

REDIS_HOST=${REDIS_HOST:-redis}
REDIS_PASSWORD=${REDIS_PASSWORD}
REDIS_PORT=${REDIS_PORT:-6379}

MAIL_MAILER=${MAIL_MAILER:-smtp}
MAIL_HOST=${MAIL_HOST}
MAIL_PORT=${MAIL_PORT}
MAIL_USERNAME=${MAIL_USERNAME}
MAIL_PASSWORD=${MAIL_PASSWORD}
MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-tls}
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}
MAIL_FROM_NAME=${MAIL_FROM_NAME:-"David's Wood Furniture"}

VITE_APP_NAME="David's Wood Furniture"
EOF

# Generate APP_KEY
echo "Generating APP_KEY..."
APP_KEY_VALUE="base64:$(openssl rand -base64 32)"
echo "Generated APP_KEY value: $APP_KEY_VALUE"

# Update .env file with the generated APP_KEY
sed -i "s/^APP_KEY=.*/APP_KEY=$APP_KEY_VALUE/" .env

# Verify the APP_KEY was set correctly
echo "Verifying APP_KEY in .env file:"
if grep "APP_KEY=" .env; then
  echo "APP_KEY verification successful"
else
  echo "ERROR: APP_KEY verification failed!"
  exit 1
fi

export APP_KEY="$APP_KEY_VALUE"
echo "APP_KEY exported: $APP_KEY"

# Clear Laravel config cache
echo "Clearing Laravel config cache..."
php artisan config:clear

# Create necessary directories
echo "Creating directories..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set permissions
chmod -R 775 storage bootstrap/cache

# Wait for database to be ready (Docker Compose handles this with depends_on)
echo "Waiting for database connection..."
timeout=60
counter=0
until php artisan tinker --execute="DB::connection()->getPdo();" 2>/dev/null; do
    echo "Database is unavailable - sleeping ($counter/$timeout)"
    sleep 2
    counter=$((counter + 2))
    if [ $counter -ge $timeout ]; then
        echo "Database connection timeout - continuing anyway"
        break
    fi
done

if [ $counter -lt $timeout ]; then
    echo "Database is ready!"
    # Run migrations
    echo "Running migrations..."
    if php artisan tinker --execute="DB::table('migrations')->count();" 2>/dev/null; then
        echo "Database already initialized, running pending migrations..."
        php artisan migrate --force || echo "Some migrations may have failed, but continuing..."
    else
        echo "Fresh database detected, running fresh migrations..."
        php artisan migrate:fresh --force --seed || echo "Fresh migrations failed, but continuing..."
    fi
else
    echo "Skipping migrations due to database connection issues"
fi

# Test Laravel configuration
echo "Testing Laravel configuration..."
php artisan config:show app.name
php artisan config:show app.key

# Configure Apache for the correct port
echo "Configuring Apache for port $PORT..."
if [ "$PORT" != "80" ]; then
    sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-available/000-default.conf
fi

# Test Apache configuration
echo "Testing Apache configuration..."
apache2ctl configtest

# Start Apache
echo "Starting Apache on port $PORT..."
apache2ctl start

# Wait a moment and check if Apache is running
sleep 3
if pgrep apache2 > /dev/null; then
    echo "Apache started successfully"
    echo "Server is available at: http://0.0.0.0:$PORT"
    echo "Health endpoint: http://0.0.0.0:$PORT/health"
    
    # Keep Apache running in foreground
    apache2ctl -D FOREGROUND
else
    echo "Apache failed to start"
    echo "Checking Apache error logs..."
    tail -20 /var/log/apache2/error.log
    exit 1
fi