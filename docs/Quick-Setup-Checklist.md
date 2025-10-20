# Quick Setup Checklist: GitHub Actions + AWS EC2

## Pre-Setup Checklist

- [ ] AWS account with EC2 access
- [ ] GitHub repository with your Laravel application
- [ ] Domain name (optional but recommended)
- [ ] SSH client installed on your local machine

## AWS EC2 Setup (15-20 minutes)

### 1. Launch EC2 Instance
- [ ] Launch Ubuntu 22.04 LTS instance
- [ ] Choose t3.medium or larger instance type
- [ ] Configure security group (SSH, HTTP, HTTPS)
- [ ] Create/download key pair (.pem file)
- [ ] Note down public IP address

### 2. Connect and Setup Server
```bash
# Connect to instance
ssh -i your-key.pem ubuntu@your-ec2-ip

# Upload and run setup script
scp -i your-key.pem scripts/setup-ec2-server.sh ubuntu@your-ec2-ip:/tmp/
ssh -i your-key.pem ubuntu@your-ec2-ip
sudo /tmp/setup-ec2-server.sh
```

### 3. Configure Database
```bash
sudo mysql -u root -p
# Run these SQL commands:
CREATE DATABASE davidswood_furniture;
CREATE USER 'davidswood_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON davidswood_furniture.* TO 'davidswood_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## GitHub Actions Setup (10-15 minutes)

### 1. Create AWS IAM User
- [ ] Go to AWS IAM Console
- [ ] Create user: `github-actions-deploy`
- [ ] Attach policy: `AmazonEC2FullAccess`
- [ ] Create access keys
- [ ] Save access key ID and secret

### 2. Generate SSH Key for Deployment
```bash
ssh-keygen -t rsa -b 4096 -C "github-actions-deploy"
# Save as: github-deploy-key
```

### 3. Add SSH Key to EC2
```bash
ssh-copy-id -i github-deploy-key.pub ubuntu@your-ec2-ip
```

### 4. Configure GitHub Secrets
Go to: Repository → Settings → Secrets and variables → Actions

Add these secrets:
- [ ] `AWS_ACCESS_KEY_ID` = your AWS access key
- [ ] `AWS_SECRET_ACCESS_KEY` = your AWS secret key
- [ ] `EC2_INSTANCE_ID` = i-1234567890abcdef0 (from EC2 console)
- [ ] `EC2_HOST` = your EC2 public IP
- [ ] `EC2_USER` = ubuntu
- [ ] `EC2_SSH_KEY` = content of github-deploy-key (private key)
- [ ] `APP_URL` = https://yourdomain.com (or http://your-ec2-ip)

### 5. Upload Deployment Script
```bash
scp -i your-key.pem deploy.sh ubuntu@your-ec2-ip:/home/ubuntu/
ssh -i your-key.pem ubuntu@your-ec2-ip
chmod +x /home/ubuntu/deploy.sh
```

## Domain and SSL Setup (Optional - 10 minutes)

### 1. Configure Domain
- [ ] Point domain A record to EC2 public IP
- [ ] Wait for DNS propagation (up to 48 hours)

### 2. Install SSL Certificate
```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

## Test Deployment (5 minutes)

### 1. Push to Main Branch
```bash
git add .
git commit -m "Setup CI/CD pipeline"
git push origin main
```

### 2. Monitor Deployment
- [ ] Go to GitHub Actions tab
- [ ] Watch "Deploy to AWS EC2" workflow
- [ ] Check for any errors

### 3. Verify Application
- [ ] Visit your application URL
- [ ] Check health endpoint: `/health.php`
- [ ] Test basic functionality

## Post-Setup Verification

### Server Health Check
```bash
# Check services
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql
sudo systemctl status redis-server

# Check application
curl http://localhost/health.php
```

### Application Verification
- [ ] Homepage loads correctly
- [ ] Database connection works
- [ ] User registration/login works
- [ ] Admin panel accessible
- [ ] File uploads work
- [ ] Email functionality works

## Troubleshooting Quick Fixes

### If deployment fails:
```bash
# Check deployment logs
tail -f /var/log/deployment.log

# Check service status
sudo systemctl status nginx php8.2-fpm mysql
```

### If application doesn't load:
```bash
# Check Nginx configuration
sudo nginx -t

# Check file permissions
sudo chown -R www-data:www-data /var/www/davids-wood-furniture
sudo chmod -R 755 /var/www/davids-wood-furniture
```

### If database connection fails:
```bash
# Test connection
mysql -u davidswood_user -p -h 127.0.0.1 davidswood_furniture

# Check MySQL status
sudo systemctl status mysql
```

## Security Checklist

- [ ] Firewall configured (UFW enabled)
- [ ] Fail2ban installed and running
- [ ] SSH key authentication only
- [ ] Strong database passwords
- [ ] SSL certificate installed
- [ ] Regular security updates enabled

## Performance Optimization

- [ ] OPcache enabled
- [ ] Redis configured for sessions/cache
- [ ] Nginx gzip compression enabled
- [ ] Static file caching configured
- [ ] Queue workers running

## Monitoring Setup

- [ ] Health check endpoint working
- [ ] Log rotation configured
- [ ] Backup strategy in place
- [ ] Monitoring alerts set up (optional)

## Next Steps

1. **Set up monitoring** (CloudWatch, New Relic, etc.)
2. **Configure backups** (automated database backups)
3. **Set up staging environment** (duplicate setup for testing)
4. **Implement blue-green deployments** (advanced)
5. **Set up load balancing** (if needed for high traffic)

## Support Resources

- [Complete Setup Guide](./CI-CD-Setup-Guide.md)
- [Laravel Deployment Docs](https://laravel.com/docs/deployment)
- [GitHub Actions Docs](https://docs.github.com/en/actions)
- [AWS EC2 Docs](https://docs.aws.amazon.com/ec2/)

---

**Total Setup Time: 30-45 minutes**

Once completed, your Laravel application will automatically deploy to AWS EC2 whenever you push to the main branch!

