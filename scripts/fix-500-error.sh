#!/bin/bash

echo "=== Fixing 500 Error Issues ==="

echo "=== 1. Setting proper permissions ==="
docker exec davids-wood-furniture chown -R www-data:www-data /var/www/html/storage
docker exec davids-wood-furniture chown -R www-data:www-data /var/www/html/bootstrap/cache
docker exec davids-wood-furniture chmod -R 775 /var/www/html/storage
docker exec davids-wood-furniture chmod -R 775 /var/www/html/bootstrap/cache

echo "=== 2. Clearing Laravel caches ==="
docker exec davids-wood-furniture php artisan config:clear
docker exec davids-wood-furniture php artisan cache:clear
docker exec davids-wood-furniture php artisan route:clear
docker exec davids-wood-furniture php artisan view:clear

echo "=== 3. Generating APP_KEY if missing ==="
docker exec davids-wood-furniture php artisan key:generate --force

echo "=== 4. Running database migrations ==="
docker exec davids-wood-furniture php artisan migrate --force

echo "=== 5. Optimizing Laravel ==="
docker exec davids-wood-furniture php artisan config:cache
docker exec davids-wood-furniture php artisan route:cache
docker exec davids-wood-furniture php artisan view:cache

echo "=== 6. Restarting Apache ==="
docker exec davids-wood-furniture service apache2 restart

echo "=== 7. Testing endpoints ==="
echo "Testing /test.php..."
curl -f http://localhost:8080/test.php && echo "✅ /test.php working" || echo "❌ /test.php failed"

echo "Testing /health.php..."
curl -f http://localhost:8080/health.php && echo "✅ /health.php working" || echo "❌ /health.php failed"

echo "Testing main application..."
curl -f http://localhost:8080/ && echo "✅ Main app working" || echo "❌ Main app failed"

echo "=== Final Status ==="
docker ps | grep davids-wood-furniture
