# CI/CD Pipeline Setup Guide: GitHub Actions + AWS EC2

This guide will help you set up a complete CI/CD pipeline for your Laravel furniture store application using GitHub Actions and AWS EC2.

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [AWS EC2 Setup](#aws-ec2-setup)
3. [GitHub Actions Configuration](#github-actions-configuration)
4. [Deployment Process](#deployment-process)
5. [Monitoring and Maintenance](#monitoring-and-maintenance)
6. [Troubleshooting](#troubleshooting)

## Prerequisites

### Required Accounts and Services
- GitHub account with repository access
- AWS account with EC2 access
- Domain name (optional but recommended)
- SSL certificate (Let's Encrypt is free)

### Required Knowledge
- Basic understanding of Linux commands
- Familiarity with Laravel applications
- Basic understanding of CI/CD concepts

## AWS EC2 Setup

### Step 1: Launch EC2 Instance

1. **Log into AWS Console**
   - Go to [AWS EC2 Console](https://console.aws.amazon.com/ec2/)
   - Click "Launch Instance"

2. **Choose Instance Configuration**
   ```
   Name: davids-wood-furniture-server
   AMI: Ubuntu Server 22.04 LTS
   Instance Type: t3.medium (or larger for production)
   Key Pair: Create new or use existing
   Security Group: Create new with these rules:
     - SSH (22) - Your IP only
     - HTTP (80) - Anywhere
     - HTTPS (443) - Anywhere
     - MySQL (3306) - Your IP only (optional)
   ```

3. **Storage Configuration**
   ```
   Root Volume: 20 GB (gp3)
   Additional Volumes: 50 GB for application data (optional)
   ```

4. **Launch Instance**
   - Review settings
   - Launch instance
   - Download key pair (.pem file)

### Step 2: Connect to EC2 Instance

```bash
# Make key file secure
chmod 400 your-key-pair.pem

# Connect to instance
ssh -i your-key-pair.pem ubuntu@your-ec2-public-ip
```

### Step 3: Run Server Setup Script

1. **Upload setup script to EC2**
   ```bash
   scp -i your-key-pair.pem scripts/setup-ec2-server.sh ubuntu@your-ec2-public-ip:/tmp/
   ```

2. **Run setup script**
   ```bash
   ssh -i your-key-pair.pem ubuntu@your-ec2-public-ip
   chmod +x /tmp/setup-ec2-server.sh
   sudo /tmp/setup-ec2-server.sh
   ```

3. **Setup MySQL Database**
   ```bash
   sudo mysql -u root -p
   ```
   ```sql
   CREATE DATABASE davidswood_furniture;
   CREATE USER 'davidswood_user'@'localhost' IDENTIFIED BY 'your_secure_password';
   GRANT ALL PRIVILEGES ON davidswood_furniture.* TO 'davidswood_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

### Step 4: Configure Domain and SSL (Optional)

1. **Point domain to EC2**
   - Update DNS A record to point to your EC2 public IP
   - Wait for DNS propagation (up to 48 hours)

2. **Install SSL Certificate**
   ```bash
   sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
   ```

## GitHub Actions Configuration

### Step 1: Create GitHub Secrets

Go to your GitHub repository → Settings → Secrets and variables → Actions

Add these secrets:

```
AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key
EC2_INSTANCE_ID=i-1234567890abcdef0
EC2_HOST=your-ec2-public-ip
EC2_USER=ubuntu
EC2_SSH_KEY=your-private-key-content
APP_URL=https://yourdomain.com
```

### Step 2: Create AWS IAM User

1. **Go to AWS IAM Console**
   - Create new user: `github-actions-deploy`
   - Attach policy: `AmazonEC2FullAccess`
   - Create access keys
   - Add keys to GitHub secrets

### Step 3: Setup SSH Key for Deployment

1. **Generate SSH key pair**
   ```bash
   ssh-keygen -t rsa -b 4096 -C "github-actions-deploy"
   ```

2. **Add public key to EC2**
   ```bash
   ssh-copy-id -i id_rsa.pub ubuntu@your-ec2-public-ip
   ```

3. **Add private key to GitHub secrets**
   - Copy content of `id_rsa` file
   - Add as `EC2_SSH_KEY` secret

### Step 4: Configure Deployment Script

1. **Upload deployment script to EC2**
   ```bash
   scp -i your-key-pair.pem deploy.sh ubuntu@your-ec2-public-ip:/home/ubuntu/
   ```

2. **Make script executable**
   ```bash
   ssh -i your-key-pair.pem ubuntu@your-ec2-public-ip
   chmod +x /home/ubuntu/deploy.sh
   ```

## Deployment Process

### Automatic Deployment

The CI/CD pipeline will automatically deploy when you push to the `main` branch:

1. **Code Quality Checks**
   - PHP syntax and style checks
   - Frontend build verification
   - Security scans

2. **Testing**
   - Unit tests
   - Integration tests
   - Database migrations

3. **Deployment**
   - Create deployment package
   - Upload to EC2
   - Run deployment script
   - Health checks

### Manual Deployment

You can also trigger manual deployments:

1. Go to GitHub Actions tab
2. Select "Deploy to AWS EC2" workflow
3. Click "Run workflow"
4. Choose environment (production/staging)
5. Click "Run workflow"

### Deployment Script Features

The deployment script includes:

- **Backup Creation**: Automatic backup before deployment
- **Zero-Downtime**: Rolling deployment with health checks
- **Rollback**: Automatic rollback on failure
- **Optimization**: Laravel cache optimization
- **Service Management**: Automatic service restarts

## Monitoring and Maintenance

### Health Monitoring

1. **Health Check Endpoint**
   - URL: `https://yourdomain.com/health.php`
   - Returns JSON with service status
   - Monitors PHP, MySQL, Redis

2. **Log Monitoring**
   ```bash
   # Application logs
   tail -f /var/www/davids-wood-furniture/storage/logs/laravel.log
   
   # Nginx logs
   tail -f /var/log/nginx/access.log
   tail -f /var/log/nginx/error.log
   
   # PHP-FPM logs
   tail -f /var/log/php8.2-fpm.log
   ```

### Performance Monitoring

1. **Server Resources**
   ```bash
   # CPU and memory usage
   htop
   
   # Disk usage
   df -h
   
   # Process monitoring
   ps aux | grep php
   ```

2. **Application Performance**
   - Monitor response times
   - Check queue worker status
   - Monitor database performance

### Backup Strategy

1. **Automatic Backups**
   - Application backups before each deployment
   - Database backups (configure separately)
   - Keep last 5 application backups

2. **Manual Backups**
   ```bash
   # Create manual backup
   sudo cp -r /var/www/davids-wood-furniture /var/backups/davids-wood-furniture/manual-$(date +%Y%m%d-%H%M%S)
   
   # Database backup
   mysqldump -u davidswood_user -p davidswood_furniture > backup-$(date +%Y%m%d-%H%M%S).sql
   ```

## Troubleshooting

### Common Issues

1. **Deployment Fails**
   ```bash
   # Check deployment logs
   tail -f /var/log/deployment.log
   
   # Check service status
   sudo systemctl status nginx
   sudo systemctl status php8.2-fpm
   sudo systemctl status mysql
   ```

2. **Application Not Loading**
   ```bash
   # Check Nginx configuration
   sudo nginx -t
   
   # Check file permissions
   ls -la /var/www/davids-wood-furniture/
   
   # Check Laravel logs
   tail -f /var/www/davids-wood-furniture/storage/logs/laravel.log
   ```

3. **Database Connection Issues**
   ```bash
   # Test MySQL connection
   mysql -u davidswood_user -p -h 127.0.0.1 davidswood_furniture
   
   # Check MySQL status
   sudo systemctl status mysql
   ```

4. **SSL Certificate Issues**
   ```bash
   # Check certificate status
   sudo certbot certificates
   
   # Renew certificate
   sudo certbot renew --dry-run
   ```

### Performance Issues

1. **High CPU Usage**
   - Check for infinite loops in code
   - Monitor queue workers
   - Optimize database queries

2. **High Memory Usage**
   - Check PHP-FPM pool settings
   - Monitor Redis memory usage
   - Optimize Laravel caching

3. **Slow Response Times**
   - Enable OPcache
   - Optimize database indexes
   - Use Redis for sessions and cache

### Security Issues

1. **Failed Login Attempts**
   ```bash
   # Check fail2ban status
   sudo fail2ban-client status
   
   # Check SSH logs
   sudo tail -f /var/log/auth.log
   ```

2. **Suspicious Activity**
   ```bash
   # Check Nginx access logs
   sudo tail -f /var/log/nginx/access.log | grep -E "(404|403|500)"
   
   # Check application logs
   tail -f /var/www/davids-wood-furniture/storage/logs/laravel.log
   ```

## Best Practices

### Security
- Keep system packages updated
- Use strong passwords
- Enable fail2ban
- Regular security audits
- Use HTTPS everywhere

### Performance
- Enable OPcache
- Use Redis for caching
- Optimize database queries
- Monitor resource usage
- Regular performance testing

### Maintenance
- Regular backups
- Monitor logs
- Update dependencies
- Test deployments
- Document changes

## Support

For issues with this setup:

1. Check the troubleshooting section
2. Review GitHub Actions logs
3. Check server logs
4. Consult Laravel documentation
5. Check AWS EC2 documentation

## Additional Resources

- [Laravel Deployment Documentation](https://laravel.com/docs/deployment)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [AWS EC2 Documentation](https://docs.aws.amazon.com/ec2/)
- [Nginx Configuration Guide](https://nginx.org/en/docs/)
- [Let's Encrypt Documentation](https://letsencrypt.org/docs/)

