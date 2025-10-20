#!/bin/bash

# AWS EC2 Server Setup Script for Laravel Application
# Run this script on a fresh Ubuntu 22.04 EC2 instance

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Update system
update_system() {
    log "Updating system packages..."
    sudo apt update && sudo apt upgrade -y
    success "System updated"
}

# Install basic packages
install_basic_packages() {
    log "Installing basic packages..."
    sudo apt install -y \
        curl \
        wget \
        git \
        unzip \
        zip \
        software-properties-common \
        apt-transport-https \
        ca-certificates \
        gnupg \
        lsb-release \
        htop \
        nano \
        vim \
        ufw \
        fail2ban
    success "Basic packages installed"
}

# Install Nginx
install_nginx() {
    log "Installing Nginx..."
    sudo apt install -y nginx
    sudo systemctl enable nginx
    sudo systemctl start nginx
    success "Nginx installed and started"
}

# Install PHP 8.2
install_php() {
    log "Installing PHP 8.2 and extensions..."
    
    # Add PHP repository
    sudo add-apt-repository ppa:ondrej/php -y
    sudo apt update
    
    # Install PHP and extensions
    sudo apt install -y \
        php8.2 \
        php8.2-cli \
        php8.2-fpm \
        php8.2-mysql \
        php8.2-xml \
        php8.2-gd \
        php8.2-curl \
        php8.2-mbstring \
        php8.2-zip \
        php8.2-bcmath \
        php8.2-intl \
        php8.2-redis \
        php8.2-imagick \
        php8.2-soap
    
    # Configure PHP-FPM
    sudo systemctl enable php8.2-fpm
    sudo systemctl start php8.2-fpm
    
    success "PHP 8.2 installed and configured"
}

# Install Composer
install_composer() {
    log "Installing Composer..."
    cd /tmp
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
    success "Composer installed"
}

# Install Node.js
install_nodejs() {
    log "Installing Node.js 18..."
    curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
    sudo apt install -y nodejs
    success "Node.js installed"
}

# Install MySQL
install_mysql() {
    log "Installing MySQL..."
    sudo apt install -y mysql-server
    sudo systemctl enable mysql
    sudo systemctl start mysql
    
    # Secure MySQL installation
    sudo mysql_secure_installation
    
    success "MySQL installed and secured"
}

# Install Redis
install_redis() {
    log "Installing Redis..."
    sudo apt install -y redis-server
    sudo systemctl enable redis-server
    sudo systemctl start redis-server
    success "Redis installed and started"
}

# Configure firewall
configure_firewall() {
    log "Configuring firewall..."
    sudo ufw --force enable
    sudo ufw allow ssh
    sudo ufw allow 'Nginx Full'
    sudo ufw allow 3306/tcp  # MySQL (if needed externally)
    success "Firewall configured"
}

# Configure fail2ban
configure_fail2ban() {
    log "Configuring fail2ban..."
    sudo systemctl enable fail2ban
    sudo systemctl start fail2ban
    success "Fail2ban configured"
}

# Create application user
create_app_user() {
    log "Creating application user..."
    
    # Create user if it doesn't exist
    if ! id "ubuntu" &>/dev/null; then
        sudo useradd -m -s /bin/bash ubuntu
    fi
    
    # Add user to www-data group
    sudo usermod -a -G www-data ubuntu
    
    success "Application user configured"
}

# Create application directory
create_app_directory() {
    log "Creating application directory..."
    sudo mkdir -p /var/www/davids-wood-furniture
    sudo chown -R ubuntu:www-data /var/www/davids-wood-furniture
    sudo chmod -R 755 /var/www/davids-wood-furniture
    success "Application directory created"
}

# Configure Nginx
configure_nginx() {
    log "Configuring Nginx..."
    
    # Create Nginx configuration
    sudo tee /etc/nginx/sites-available/davids-wood-furniture > /dev/null <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name _;
    root /var/www/davids-wood-furniture/public;
    index index.php index.html index.htm;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/javascript;

    # Handle Laravel routes
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }

    # Deny access to sensitive files
    location ~ /(\.env|\.git|composer\.(json|lock)|package\.(json|lock)|yarn\.lock|\.htaccess) {
        deny all;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|ttf|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files \$uri =404;
    }

    # Health check endpoint
    location /health.php {
        access_log off;
        try_files \$uri =404;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }
}
EOF

    # Enable the site
    sudo ln -sf /etc/nginx/sites-available/davids-wood-furniture /etc/nginx/sites-enabled/
    sudo rm -f /etc/nginx/sites-enabled/default
    
    # Test Nginx configuration
    sudo nginx -t
    
    # Restart Nginx
    sudo systemctl restart nginx
    
    success "Nginx configured"
}

# Configure PHP-FPM
configure_php_fpm() {
    log "Configuring PHP-FPM..."
    
    # Backup original pool configuration
    sudo cp /etc/php/8.2/fpm/pool.d/www.conf /etc/php/8.2/fpm/pool.d/www.conf.backup
    
    # Create optimized pool configuration
    sudo tee /etc/php/8.2/fpm/pool.d/www.conf > /dev/null <<EOF
[www]
user = www-data
group = www-data
listen = /var/run/php/php8.2-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500

php_admin_value[error_log] = /var/log/php8.2-fpm.log
php_admin_flag[log_errors] = on
php_value[session.save_handler] = files
php_value[session.save_path] = /var/lib/php/sessions
php_value[soap.wsdl_cache_dir] = /var/lib/php/wsdlcache
EOF

    # Restart PHP-FPM
    sudo systemctl restart php8.2-fpm
    
    success "PHP-FPM configured"
}

# Install Supervisor for queue workers
install_supervisor() {
    log "Installing Supervisor..."
    sudo apt install -y supervisor
    
    # Create Laravel queue worker configuration
    sudo tee /etc/supervisor/conf.d/laravel-worker.conf > /dev/null <<EOF
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/davids-wood-furniture/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/laravel-worker.log
stopwaitsecs=3600
EOF

    # Reload and start supervisor
    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl start all
    
    success "Supervisor installed and configured"
}

# Setup SSL with Let's Encrypt (optional)
setup_ssl() {
    log "Setting up SSL with Let's Encrypt..."
    
    # Install Certbot
    sudo apt install -y certbot python3-certbot-nginx
    
    # Note: SSL setup requires domain name
    warning "SSL setup requires a domain name. Run the following command after setting up your domain:"
    echo "sudo certbot --nginx -d yourdomain.com"
    
    success "SSL tools installed"
}

# Create backup directory
create_backup_directory() {
    log "Creating backup directory..."
    sudo mkdir -p /var/backups/davids-wood-furniture
    sudo chown ubuntu:ubuntu /var/backups/davids-wood-furniture
    success "Backup directory created"
}

# Setup deployment script
setup_deployment_script() {
    log "Setting up deployment script..."
    
    # Copy deployment script to home directory
    sudo cp /tmp/deploy.sh /home/ubuntu/deploy.sh
    sudo chmod +x /home/ubuntu/deploy.sh
    sudo chown ubuntu:ubuntu /home/ubuntu/deploy.sh
    
    success "Deployment script configured"
}

# Create health check endpoint
create_health_check() {
    log "Creating health check endpoint..."
    
    sudo tee /var/www/davids-wood-furniture/public/health.php > /dev/null <<EOF
<?php
// Simple health check endpoint
header('Content-Type: application/json');

\$health = [
    'status' => 'healthy',
    'timestamp' => date('Y-m-d H:i:s'),
    'version' => '1.0.0',
    'services' => [
        'php' => phpversion(),
        'mysql' => 'connected',
        'redis' => 'connected'
    ]
];

// Check MySQL connection
try {
    \$pdo = new PDO('mysql:host=localhost;dbname=davidswood_furniture', 'root', '');
    \$health['services']['mysql'] = 'connected';
} catch (PDOException \$e) {
    \$health['services']['mysql'] = 'disconnected';
    \$health['status'] = 'unhealthy';
}

// Check Redis connection
try {
    \$redis = new Redis();
    \$redis->connect('127.0.0.1', 6379);
    \$health['services']['redis'] = 'connected';
} catch (Exception \$e) {
    \$health['services']['redis'] = 'disconnected';
    \$health['status'] = 'unhealthy';
}

http_response_code(\$health['status'] === 'healthy' ? 200 : 503);
echo json_encode(\$health, JSON_PRETTY_PRINT);
EOF

    sudo chown www-data:www-data /var/www/davids-wood-furniture/public/health.php
    sudo chmod 644 /var/www/davids-wood-furniture/public/health.php
    
    success "Health check endpoint created"
}

# Main setup function
main() {
    log "Starting EC2 server setup for Laravel application..."
    
    # Update system
    update_system
    
    # Install packages
    install_basic_packages
    install_nginx
    install_php
    install_composer
    install_nodejs
    install_mysql
    install_redis
    
    # Configure services
    configure_firewall
    configure_fail2ban
    create_app_user
    create_app_directory
    configure_nginx
    configure_php_fpm
    install_supervisor
    setup_ssl
    create_backup_directory
    setup_deployment_script
    create_health_check
    
    success "EC2 server setup completed!"
    log "Next steps:"
    echo "1. Configure your domain DNS to point to this server's IP"
    echo "2. Set up SSL certificate: sudo certbot --nginx -d yourdomain.com"
    echo "3. Configure GitHub Actions secrets"
    echo "4. Deploy your application using GitHub Actions"
    
    # Display server information
    log "Server Information:"
    echo "Public IP: $(curl -s http://169.254.169.254/latest/meta-data/public-ipv4)"
    echo "Private IP: $(curl -s http://169.254.169.254/latest/meta-data/local-ipv4)"
    echo "Instance ID: $(curl -s http://169.254.169.254/latest/meta-data/instance-id)"
}

# Run main function
main "$@"
