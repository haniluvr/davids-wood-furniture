# Use Ubuntu base image for better reliability
FROM ubuntu:22.04

# Set environment variables
ENV DEBIAN_FRONTEND=noninteractive
ENV PHP_VERSION=8.2

# Install system dependencies including Apache
RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    wget \
    git \
    unzip \
    zip \
    ca-certificates \
    gnupg \
    lsb-release

# Add PHP repository
RUN add-apt-repository ppa:ondrej/php -y

# Add Node.js repository
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

# Update package list after adding repositories
RUN apt-get update

# Install Apache and PHP
RUN apt-get install -y \
    apache2 \
    libapache2-mod-php8.2 \
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
    php8.2-common

# Install Node.js
RUN apt-get install -y nodejs

# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers ssl

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

# Configure Apache
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE 80

# Copy and make startup script executable
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Start the application using the startup script
CMD ["/start.sh"]
