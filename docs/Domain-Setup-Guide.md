# Domain Setup Guide for David's Wood Furniture

## Option 1: Free Domain with Freenom (Recommended)

### Step 1: Get a Free Domain
1. Go to [Freenom.com](https://www.freenom.com)
2. Search for available domains (e.g., `davidswood.tk`, `davidswood.ml`, `davidswood.ga`)
3. Register the domain (free for 12 months)

### Step 2: Point Domain to Your EC2 Instance
1. In Freenom control panel, go to "My Domains"
2. Click "Manage Domain" for your domain
3. Go to "Nameservers" tab
4. Set nameservers to:
   - `ns1.aws.amazon.com`
   - `ns2.aws.amazon.com`

### Step 3: Create Route 53 Hosted Zone
1. Go to AWS Route 53 console
2. Create a new hosted zone for your domain
3. Note the 4 nameservers provided
4. Update your domain's nameservers in Freenom with these 4 AWS nameservers

### Step 4: Create DNS Records
Create these records in Route 53:
- **A Record**: `@` → `13.211.143.224`
- **A Record**: `www` → `13.211.143.224`
- **A Record**: `admin` → `13.211.143.224`

## Option 2: Use AWS Route 53 with Existing Domain

If you have a domain already:
1. Go to Route 53 console
2. Create hosted zone for your domain
3. Update your domain's nameservers to AWS nameservers
4. Create the same DNS records as above

## Option 3: Use a Subdomain Service

### Using No-IP (Free)
1. Go to [noip.com](https://www.noip.com)
2. Create account and get free subdomain (e.g., `davidswood.ddns.net`)
3. Install No-IP client on your EC2 instance
4. Update your application to use the subdomain

### Using DuckDNS (Free)
1. Go to [duckdns.org](https://www.duckdns.org)
2. Create account and get subdomain (e.g., `davidswood.duckdns.org`)
3. Update DNS records to point to your EC2 IP

## Security Group Configuration

Make sure your EC2 security group allows:
- **HTTP (Port 80)**: 0.0.0.0/0
- **HTTPS (Port 443)**: 0.0.0.0/0
- **Custom Port 8080**: 0.0.0.0/0 (if using port 8080)

## Application Configuration

Update your GitHub secrets:
- `APP_URL`: `https://yourdomain.com` (or `http://` if no SSL)
- Update any hardcoded URLs in your application

## SSL Certificate (Optional but Recommended)

### Using Let's Encrypt (Free)
1. SSH into your EC2 instance
2. Install Certbot: `sudo apt install certbot python3-certbot-apache`
3. Get certificate: `sudo certbot --apache -d yourdomain.com -d www.yourdomain.com`
4. Configure auto-renewal

### Using AWS Certificate Manager
1. Request certificate in ACM
2. Validate domain ownership
3. Use with Application Load Balancer or CloudFront
