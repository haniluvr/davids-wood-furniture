#!/bin/bash

echo "=== Docker Container Status ==="
docker ps -a

echo -e "\n=== Container Logs (last 50 lines) ==="
docker logs davids-wood-furniture --tail 50

echo -e "\n=== Port Usage ==="
sudo netstat -tlnp | grep :80
sudo netstat -tlnp | grep :8080

echo -e "\n=== Testing Endpoints ==="
echo "Testing localhost:80/test.php..."
curl -f http://localhost:80/test.php 2>/dev/null && echo "✅ Port 80 working" || echo "❌ Port 80 not responding"

echo "Testing localhost:8080/test.php..."
curl -f http://localhost:8080/test.php 2>/dev/null && echo "✅ Port 8080 working" || echo "❌ Port 8080 not responding"

echo -e "\n=== Container Environment ==="
docker exec davids-wood-furniture env | grep -E "(PORT|APP_|DB_)" | head -10

echo -e "\n=== Container Processes ==="
docker exec davids-wood-furniture ps aux | head -10
