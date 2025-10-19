# Use Ubuntu base image for better reliability
FROM ubuntu:22.04

# Set environment variables
ENV DEBIAN_FRONTEND=noninteractive
ENV PHP_VERSION=8.2

# Install system dependencies
RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    wget \
    git \
    unzip \
    zip \
    && add-apt-repository ppa:ondrej/php \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y \
    php8.2 \
    php8.2-cli \
    php8.2-mysql \
    php8.2-xml \
    php8.2-gd \
    php8.2-curl \
    php8.2-mbstring \
    php8.2-zip \
    php8.2-bcmath \
    php8.2-intl \
    nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install Node dependencies with legacy peer deps
RUN npm install --legacy-peer-deps --verbose

# Build frontend assets
RUN npm run build

# Laravel optimizations (skip route cache to avoid conflicts)
RUN php artisan config:cache && \
    php artisan view:cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# Expose port (Railway will set the PORT environment variable)
EXPOSE 80

# Start PHP built-in server
CMD php artisan serve --host=0.0.0.0 --port=$PORT
