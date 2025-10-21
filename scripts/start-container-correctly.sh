#!/bin/bash

echo "=== Starting Container Correctly ==="

# Stop and remove any existing container
echo "Cleaning up existing containers..."
docker stop davids-wood-furniture 2>/dev/null || true
docker rm davids-wood-furniture 2>/dev/null || true

# Login to ECR
echo "Logging into ECR..."
aws ecr get-login-password --region ap-southeast-2 | docker login --username AWS --password-stdin 795065187861.dkr.ecr.ap-southeast-2.amazonaws.com

# Pull the latest image
echo "Pulling latest image..."
docker pull 795065187861.dkr.ecr.ap-southeast-2.amazonaws.com/davids-wood-furniture:latest

# Start the container with correct syntax
echo "Starting container..."
docker run -d \
  --name davids-wood-furniture \
  --restart unless-stopped \
  -p 8080:80 \
  --add-host=host.docker.internal:host-gateway \
  -e PORT=80 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e APP_URL='http://13.211.143.224:8080' \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=host.docker.internal \
  -e DB_PORT=3306 \
  -e DB_DATABASE=davids_wood \
  -e DB_USERNAME=davidswood_user \
  -e DB_PASSWORD='DWF#2025$Secure!' \
  -e REDIS_HOST=localhost \
  -e REDIS_PORT=6379 \
  795065187861.dkr.ecr.ap-southeast-2.amazonaws.com/davids-wood-furniture:latest

echo "=== Waiting for container to start ==="
sleep 10

echo "=== Container Status ==="
docker ps -a | grep davids-wood-furniture

echo "=== Testing endpoints ==="
for i in {1..5}; do
  echo "Attempt $i/5: Testing endpoints..."
  if curl -f http://localhost:8080/test.php 2>/dev/null; then
    echo "✅ Application is responding on port 8080"
    break
  else
    echo "❌ Not responding yet, waiting..."
    sleep 5
  fi
done

echo "=== Final Status ==="
docker ps -a
echo "Container logs:"
docker logs davids-wood-furniture --tail 20
