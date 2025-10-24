#!/bin/bash

# Docker Compose Deployment Script for David's Wood Furniture
# This script handles deployment using docker-compose on EC2

set -e

echo "🚀 Starting Docker Compose deployment..."

# Configuration
COMPOSE_FILE="docker-compose.prod.yml"
ENV_FILE=".env.production"
CONTAINER_NAME="davids-wood-furniture"
APP_PORT=8080

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if required environment variables are set
check_environment() {
    print_status "Checking environment variables..."
    
    required_vars=(
        "ECR_REGISTRY"
        "ECR_REPOSITORY" 
        "IMAGE_TAG"
        "DB_PASSWORD"
        "MYSQL_ROOT_PASSWORD"
        "APP_URL"
    )
    
    missing_vars=()
    for var in "${required_vars[@]}"; do
        if [ -z "${!var}" ]; then
            missing_vars+=("$var")
        fi
    done
    
    if [ ${#missing_vars[@]} -ne 0 ]; then
        print_error "Missing required environment variables:"
        printf '%s\n' "${missing_vars[@]}"
        exit 1
    fi
    
    print_status "All required environment variables are set"
}

# Create production environment file
create_env_file() {
    print_status "Creating production environment file..."
    
    cat > "$ENV_FILE" << EOF
# Production Environment Configuration
# Generated on $(date)

# Application
APP_NAME="David's Wood Furniture"
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=$APP_URL

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=davids_wood
DB_USERNAME=davidswood_user
DB_PASSWORD=$DB_PASSWORD

# MySQL Root
MYSQL_ROOT_PASSWORD=$MYSQL_ROOT_PASSWORD

# Redis
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=

# Cache & Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120
QUEUE_CONNECTION=redis

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=$MAIL_HOST
MAIL_PORT=$MAIL_PORT
MAIL_USERNAME=$MAIL_USERNAME
MAIL_PASSWORD=$MAIL_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=$MAIL_FROM_ADDRESS
MAIL_FROM_NAME=$MAIL_FROM_NAME

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error

# Broadcasting
BROADCAST_DRIVER=log

# Filesystem
FILESYSTEM_DISK=local

# Vite
VITE_APP_NAME="David's Wood Furniture"

# ECR Configuration
ECR_REGISTRY=$ECR_REGISTRY
ECR_REPOSITORY=$ECR_REPOSITORY
IMAGE_TAG=$IMAGE_TAG
EOF

    print_status "Environment file created: $ENV_FILE"
}

# Login to ECR
login_to_ecr() {
    print_status "Logging in to ECR..."
    aws ecr get-login-password --region ap-southeast-2 | docker login --username AWS --password-stdin "$ECR_REGISTRY"
    print_status "ECR login successful"
}

# Stop existing containers
stop_existing_containers() {
    print_status "Stopping existing containers..."
    
    # Stop containers using docker-compose if it exists
    if [ -f "$COMPOSE_FILE" ]; then
        docker-compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" down || true
    fi
    
    # Stop any containers with our name
    if [ "$(docker ps -q -f name=$CONTAINER_NAME)" ]; then
        print_status "Stopping existing container: $CONTAINER_NAME"
        docker stop "$CONTAINER_NAME" || true
    fi
    
    # Remove any containers with our name
    if [ "$(docker ps -aq -f name=$CONTAINER_NAME)" ]; then
        print_status "Removing existing container: $CONTAINER_NAME"
        docker rm "$CONTAINER_NAME" || true
    fi
    
    print_status "Existing containers stopped"
}

# Pull latest images
pull_images() {
    print_status "Pulling latest images..."
    
    # Pull the app image
    print_status "Pulling app image: $ECR_REGISTRY/$ECR_REPOSITORY:$IMAGE_TAG"
    docker pull "$ECR_REGISTRY/$ECR_REPOSITORY:$IMAGE_TAG"
    
    # Pull MySQL and Redis images
    print_status "Pulling MySQL image..."
    docker pull mysql:8.0
    
    print_status "Pulling Redis image..."
    docker pull redis:7-alpine
    
    print_status "All images pulled successfully"
}

# Start services with docker-compose
start_services() {
    print_status "Starting services with docker-compose..."
    
    # Start services in detached mode
    docker-compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" up -d
    
    print_status "Services started successfully"
}

# Wait for services to be healthy
wait_for_health() {
    print_status "Waiting for services to be healthy..."
    
    # Wait for MySQL
    print_status "Waiting for MySQL to be ready..."
    timeout=60
    counter=0
    until docker-compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T mysql mysqladmin ping -h localhost -u davidswood_user -p"$DB_PASSWORD" 2>/dev/null; do
        echo "MySQL is unavailable - sleeping ($counter/$timeout)"
        sleep 2
        counter=$((counter + 2))
        if [ $counter -ge $timeout ]; then
            print_error "MySQL health check timeout"
            return 1
        fi
    done
    print_status "MySQL is ready"
    
    # Wait for Redis
    print_status "Waiting for Redis to be ready..."
    timeout=30
    counter=0
    until docker-compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T redis redis-cli ping 2>/dev/null; do
        echo "Redis is unavailable - sleeping ($counter/$timeout)"
        sleep 2
        counter=$((counter + 2))
        if [ $counter -ge $timeout ]; then
            print_error "Redis health check timeout"
            return 1
        fi
    done
    print_status "Redis is ready"
    
    # Wait for app
    print_status "Waiting for app to be ready..."
    timeout=60
    counter=0
    until curl -f "http://localhost:$APP_PORT/health" >/dev/null 2>&1; do
        echo "App is unavailable - sleeping ($counter/$timeout)"
        sleep 3
        counter=$((counter + 3))
        if [ $counter -ge $timeout ]; then
            print_error "App health check timeout"
            return 1
        fi
    done
    print_status "App is ready"
}

# Run database migrations
run_migrations() {
    print_status "Running database migrations..."
    
    # Check if migrations table exists
    if docker-compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T app php artisan tinker --execute="DB::table('migrations')->count();" 2>/dev/null; then
        print_status "Database already initialized, running pending migrations..."
        docker-compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T app php artisan migrate --force || print_warning "Some migrations may have failed"
    else
        print_status "Fresh database detected, running fresh migrations..."
        docker-compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T app php artisan migrate:fresh --force --seed || print_warning "Fresh migrations failed"
    fi
    
    print_status "Migrations completed"
}

# Verify deployment
verify_deployment() {
    print_status "Verifying deployment..."
    
    # Check container status
    print_status "Checking container status..."
    docker-compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" ps
    
    # Test endpoints
    print_status "Testing endpoints..."
    
    # Test health endpoint
    if curl -f "http://localhost:$APP_PORT/health" >/dev/null 2>&1; then
        print_status "Health endpoint: ✅ OK"
    else
        print_error "Health endpoint: ❌ FAILED"
        return 1
    fi
    
    # Test test-route endpoint
    if curl -f "http://localhost:$APP_PORT/test-route" >/dev/null 2>&1; then
        print_status "Test route endpoint: ✅ OK"
    else
        print_error "Test route endpoint: ❌ FAILED"
        return 1
    fi
    
    print_status "All endpoints are working correctly"
}

# Clean up old images
cleanup() {
    print_status "Cleaning up old Docker images..."
    docker image prune -f || true
    print_status "Cleanup completed"
}

# Main deployment function
main() {
    print_status "Starting Docker Compose deployment for David's Wood Furniture"
    print_status "Deployment started at: $(date)"
    
    # Run deployment steps
    check_environment
    create_env_file
    login_to_ecr
    stop_existing_containers
    pull_images
    start_services
    wait_for_health
    run_migrations
    verify_deployment
    cleanup
    
    print_status "🎉 Deployment completed successfully!"
    print_status "Application is available at: http://localhost:$APP_PORT"
    print_status "Health check: http://localhost:$APP_PORT/health"
    print_status "Deployment finished at: $(date)"
}

# Run main function
main "$@"




