#!/bin/bash

# EC2 Connection Troubleshooting Script
# Run this to diagnose connection issues

echo "=== EC2 Connection Troubleshooting ==="
echo "Target: 13.211.143.224:8080"
echo ""

# Check if we can reach the server at all
echo "1. Testing basic connectivity..."
if ping -c 3 13.211.143.224 > /dev/null 2>&1; then
    echo "✅ Server is reachable via ping"
else
    echo "❌ Server is not reachable via ping"
    echo "   This might indicate the EC2 instance is down or security groups are blocking ICMP"
fi

# Check if port 8080 is open
echo ""
echo "2. Testing port 8080..."
if timeout 10 bash -c "</dev/tcp/13.211.143.224/8080" 2>/dev/null; then
    echo "✅ Port 8080 is open and accepting connections"
else
    echo "❌ Port 8080 is not accessible"
    echo "   This indicates a security group or firewall issue"
fi

# Check if port 80 is open
echo ""
echo "3. Testing port 80..."
if timeout 10 bash -c "</dev/tcp/13.211.143.224/80" 2>/dev/null; then
    echo "✅ Port 80 is open and accepting connections"
else
    echo "❌ Port 80 is not accessible"
fi

# Check if port 22 (SSH) is open
echo ""
echo "4. Testing SSH port 22..."
if timeout 10 bash -c "</dev/tcp/13.211.143.224/22" 2>/dev/null; then
    echo "✅ SSH port 22 is open"
else
    echo "❌ SSH port 22 is not accessible"
fi

echo ""
echo "=== Troubleshooting Steps ==="
echo ""
echo "If port 8080 is not accessible, check your EC2 security group:"
echo "1. Go to AWS EC2 Console"
echo "2. Select your instance"
echo "3. Go to 'Security' tab"
echo "4. Click on the security group"
echo "5. Add inbound rule:"
echo "   - Type: Custom TCP"
echo "   - Port: 8080"
echo "   - Source: 0.0.0.0/0"
echo ""
echo "If port 80 is not accessible, add:"
echo "   - Type: HTTP"
echo "   - Port: 80"
echo "   - Source: 0.0.0.0/0"
echo ""
echo "For HTTPS (if you get a domain):"
echo "   - Type: HTTPS"
echo "   - Port: 443"
echo "   - Source: 0.0.0.0/0"
