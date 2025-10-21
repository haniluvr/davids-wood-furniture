#!/bin/bash

echo "=== Debugging 500 Error ==="

echo "=== Container Status ==="
docker ps -a | grep davids-wood-furniture

echo -e "\n=== Container Logs (last 30 lines) ==="
docker logs davids-wood-furniture --tail 30

echo -e "\n=== Testing Simple Endpoints ==="
echo "Testing /test.php..."
curl -v http://localhost:8080/test.php 2>&1 | head -10

echo -e "\nTesting /debug.php..."
curl -v http://localhost:8080/debug.php 2>&1 | head -10

echo -e "\nTesting /health.php..."
curl -v http://localhost:8080/health.php 2>&1 | head -10

echo -e "\n=== Container Environment ==="
docker exec davids-wood-furniture env | grep -E "(APP_|DB_|PORT)" | head -10

echo -e "\n=== Laravel Logs ==="
docker exec davids-wood-furniture tail -20 /var/www/html/storage/logs/laravel.log 2>/dev/null || echo "No Laravel logs found"

echo -e "\n=== Apache Error Logs ==="
docker exec davids-wood-furniture tail -10 /var/log/apache2/error.log 2>/dev/null || echo "No Apache error logs found"

echo -e "\n=== Database Connection Test ==="
docker exec davids-wood-furniture php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connected successfully'; } catch(Exception \$e) { echo 'Database error: ' . \$e->getMessage(); }" 2>/dev/null || echo "Database test failed"

echo -e "\n=== Laravel Configuration ==="
docker exec davids-wood-furniture php artisan config:show app 2>/dev/null | head -10 || echo "Config show failed"
