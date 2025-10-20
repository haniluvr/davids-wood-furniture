#!/bin/bash

# Laravel Deployment Script for AWS EC2
# This script should be placed on your EC2 instance at /home/ubuntu/deploy.sh

set -e  # Exit on any error

# Configuration
APP_DIR="/var/www/davids-wood-furniture"
BACKUP_DIR="/var/backups/davids-wood-furniture"
NGINX_SITES_AVAILABLE="/etc/nginx/sites-available"
NGINX_SITES_ENABLED="/etc/nginx/sites-enabled"
PHP_FPM_POOL="/etc/php/8.2/fpm/pool.d"
LOG_FILE="/var/log/deployment.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a $LOG_FILE
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a $LOG_FILE
}

success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a $LOG_FILE
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a $LOG_FILE
}

# Function to check if running as root
check_root() {
    if [[ $EUID -eq 0 ]]; then
        error "This script should not be run as root for security reasons"
        exit 1
    fi
}

# Function to create backup
create_backup() {
    log "Creating backup of current application..."
    
    if [ -d "$APP_DIR" ]; then
        BACKUP_NAME="backup-$(date +%Y%m%d-%H%M%S)"
        mkdir -p $BACKUP_DIR
        
        # Create backup
        sudo cp -r $APP_DIR $BACKUP_DIR/$BACKUP_NAME
        
        # Keep only last 5 backups
        cd $BACKUP_DIR
        ls -t | tail -n +6 | xargs -r sudo rm -rf
        
        success "Backup created: $BACKUP_NAME"
    else
        warning "No existing application found, skipping backup"
    fi
}

# Function to extract deployment package
extract_deployment() {
    log "Extracting deployment package..."
    
    # Create application directory if it doesn't exist
    sudo mkdir -p $APP_DIR
    
    # Extract new deployment
    sudo tar -xzf /tmp/deployment.tar.gz -C $APP_DIR --strip-components=1
    
    # Set ownership
    sudo chown -R www-data:www-data $APP_DIR
    sudo chmod -R 755 $APP_DIR
    
    success "Deployment package extracted"
}

# Function to install dependencies
install_dependencies() {
    log "Installing PHP dependencies..."
    
    cd $APP_DIR
    
    # Install Composer dependencies
    sudo -u www-data composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
    
    success "PHP dependencies installed"
}

# Function to setup environment
setup_environment() {
    log "Setting up environment configuration..."
    
    cd $APP_DIR
    
    # Copy environment file if it doesn't exist
    if [ ! -f .env ]; then
        sudo cp .env.example .env
        sudo chown www-data:www-data .env
    fi
    
    # Generate application key if not set
    if ! grep -q "APP_KEY=base64:" .env; then
        sudo -u www-data php artisan key:generate --force
    fi
    
    # Set proper permissions
    sudo chmod -R 775 storage bootstrap/cache
    sudo chown -R www-data:www-data storage bootstrap/cache
    
    success "Environment configured"
}

# Function to run database migrations
run_migrations() {
    log "Running database migrations..."
    
    cd $APP_DIR
    
    # Run migrations
    sudo -u www-data php artisan migrate --force
    
    success "Database migrations completed"
}

# Function to optimize Laravel
optimize_laravel() {
    log "Optimizing Laravel application..."
    
    cd $APP_DIR
    
    # Clear and cache configurations
    sudo -u www-data php artisan config:clear
    sudo -u www-data php artisan config:cache
    
    # Clear and cache routes
    sudo -u www-data php artisan route:clear
    sudo -u www-data php artisan route:cache
    
    # Clear and cache views
    sudo -u www-data php artisan view:clear
    sudo -u www-data php artisan view:cache
    
    # Restart queue workers
    sudo -u www-data php artisan queue:restart
    
    success "Laravel optimization completed"
}

# Function to restart services
restart_services() {
    log "Restarting services..."
    
    # Restart PHP-FPM
    sudo systemctl restart php8.2-fpm
    
    # Restart Nginx
    sudo systemctl restart nginx
    
    # Restart queue workers (if using supervisor)
    if systemctl is-active --quiet supervisor; then
        sudo supervisorctl restart all
    fi
    
    success "Services restarted"
}

# Function to run health check
health_check() {
    log "Running health check..."
    
    # Wait a moment for services to start
    sleep 5
    
    # Check if Nginx is running
    if ! systemctl is-active --quiet nginx; then
        error "Nginx is not running"
        return 1
    fi
    
    # Check if PHP-FPM is running
    if ! systemctl is-active --quiet php8.2-fpm; then
        error "PHP-FPM is not running"
        return 1
    fi
    
    # Check application health endpoint
    if [ -f "$APP_DIR/public/health.php" ]; then
        HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health.php)
        if [ "$HTTP_STATUS" != "200" ]; then
            error "Health check failed with status: $HTTP_STATUS"
            return 1
        fi
    fi
    
    success "Health check passed"
}

# Function to cleanup
cleanup() {
    log "Cleaning up temporary files..."
    
    # Remove deployment package
    sudo rm -f /tmp/deployment.tar.gz
    
    success "Cleanup completed"
}

# Function to rollback
rollback() {
    error "Deployment failed, attempting rollback..."
    
    if [ -d "$BACKUP_DIR" ]; then
        LATEST_BACKUP=$(ls -t $BACKUP_DIR | head -n1)
        if [ -n "$LATEST_BACKUP" ]; then
            log "Rolling back to: $LATEST_BACKUP"
            sudo rm -rf $APP_DIR
            sudo cp -r $BACKUP_DIR/$LATEST_BACKUP $APP_DIR
            sudo chown -R www-data:www-data $APP_DIR
            sudo chmod -R 755 $APP_DIR
            restart_services
            success "Rollback completed"
        else
            error "No backup found for rollback"
        fi
    else
        error "No backup directory found"
    fi
}

# Main deployment function
main() {
    log "Starting deployment process..."
    log "Environment: ${DEPLOYMENT_ENV:-production}"
    log "Version: ${APP_VERSION:-unknown}"
    
    # Check if running as root
    check_root
    
    # Create backup
    create_backup
    
    # Extract deployment package
    extract_deployment
    
    # Install dependencies
    install_dependencies
    
    # Setup environment
    setup_environment
    
    # Run migrations
    run_migrations
    
    # Optimize Laravel
    optimize_laravel
    
    # Restart services
    restart_services
    
    # Health check
    if ! health_check; then
        rollback
        exit 1
    fi
    
    # Cleanup
    cleanup
    
    success "Deployment completed successfully!"
    log "Application is now running at: http://$(curl -s http://169.254.169.254/latest/meta-data/public-ipv4)"
}

# Trap errors and run rollback
trap 'rollback; exit 1' ERR

# Run main function
main "$@"
