#!/bin/bash

# Quick Domain Setup Script
# This helps you set up a domain for your application

echo "=== Domain Setup for David's Wood Furniture ==="
echo ""

echo "Choose your preferred option:"
echo "1. Free domain with Freenom (recommended)"
echo "2. Use existing domain with Route 53"
echo "3. Free subdomain with No-IP"
echo "4. Free subdomain with DuckDNS"
echo ""

read -p "Enter your choice (1-4): " choice

case $choice in
    1)
        echo ""
        echo "=== Freenom Setup ==="
        echo "1. Go to https://www.freenom.com"
        echo "2. Search for available domains (try: davidswood.tk, davidswood.ml)"
        echo "3. Register the domain (free for 12 months)"
        echo "4. In Freenom control panel:"
        echo "   - Go to 'My Domains'"
        echo "   - Click 'Manage Domain'"
        echo "   - Go to 'Nameservers' tab"
        echo "   - Set to: ns1.aws.amazon.com, ns2.aws.amazon.com"
        echo ""
        echo "5. Then create Route 53 hosted zone:"
        echo "   - Go to AWS Route 53 console"
        echo "   - Create hosted zone for your domain"
        echo "   - Update nameservers in Freenom with AWS nameservers"
        echo "   - Create A record: @ → 13.211.143.224"
        echo "   - Create A record: www → 13.211.143.224"
        ;;
    2)
        echo ""
        echo "=== Existing Domain Setup ==="
        echo "1. Go to AWS Route 53 console"
        echo "2. Create hosted zone for your domain"
        echo "3. Update your domain's nameservers to AWS nameservers"
        echo "4. Create A record: @ → 13.211.143.224"
        echo "5. Create A record: www → 13.211.143.224"
        ;;
    3)
        echo ""
        echo "=== No-IP Setup ==="
        echo "1. Go to https://www.noip.com"
        echo "2. Create account and get free subdomain"
        echo "3. Install No-IP client on EC2:"
        echo "   sudo apt update"
        echo "   sudo apt install noip2"
        echo "   sudo noip2 -C"
        echo "4. Update APP_URL in GitHub secrets"
        ;;
    4)
        echo ""
        echo "=== DuckDNS Setup ==="
        echo "1. Go to https://www.duckdns.org"
        echo "2. Create account and get subdomain"
        echo "3. Update DNS records to point to 13.211.143.224"
        echo "4. Update APP_URL in GitHub secrets"
        ;;
    *)
        echo "Invalid choice. Please run the script again."
        exit 1
        ;;
esac

echo ""
echo "=== Next Steps ==="
echo "1. Complete the domain setup above"
echo "2. Update your GitHub secrets:"
echo "   - APP_URL: https://yourdomain.com (or http:// if no SSL)"
echo "3. Check EC2 security groups allow ports 80 and 8080"
echo "4. Redeploy your application"
echo ""
echo "=== Security Group Check ==="
echo "Make sure your EC2 security group has these inbound rules:"
echo "- HTTP (Port 80): 0.0.0.0/0"
echo "- Custom TCP (Port 8080): 0.0.0.0/0"
echo "- HTTPS (Port 443): 0.0.0.0/0 (if using SSL)"
echo ""
echo "=== SSL Certificate (Optional) ==="
echo "For HTTPS, you can get a free SSL certificate:"
echo "1. SSH into your EC2 instance"
echo "2. Install Certbot: sudo apt install certbot python3-certbot-apache"
echo "3. Get certificate: sudo certbot --apache -d yourdomain.com"
