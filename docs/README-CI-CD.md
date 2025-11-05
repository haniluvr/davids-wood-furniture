# CI/CD Pipeline: GitHub Actions + AWS EC2

This repository now includes a complete CI/CD pipeline setup for deploying your Laravel furniture store application to AWS EC2 using GitHub Actions.

## 🚀 What's Included

### GitHub Actions Workflows
- **`.github/workflows/deploy-ec2.yml`** - Main deployment workflow
- **`.github/workflows/ci.yml`** - Continuous Integration (testing, code quality)
- **`.github/workflows/security.yml`** - Security scanning

### Deployment Scripts
- **`deploy.sh`** - Deployment script that runs on EC2 server
- **`scripts/setup-ec2-server.sh`** - Complete EC2 server setup script

### Configuration Files
- **`env.production.template`** - Production environment template
- **`docs/CI-CD-Setup-Guide.md`** - Comprehensive setup guide
- **`docs/Quick-Setup-Checklist.md`** - Quick setup checklist

## 📋 Quick Start

### 1. AWS EC2 Setup (15-20 minutes)
```bash
# Launch Ubuntu 22.04 EC2 instance
# Upload and run setup script
scp -i your-key.pem scripts/setup-ec2-server.sh ubuntu@your-ec2-ip:/tmp/
ssh -i your-key.pem ubuntu@your-ec2-ip
sudo /tmp/setup-ec2-server.sh
```

### 2. GitHub Actions Configuration (10-15 minutes)
1. Create AWS IAM user with EC2 access
2. Generate SSH key for deployment
3. Add GitHub secrets (see checklist)
4. Upload deployment script to EC2

### 3. Deploy Your Application
```bash
git add .
git commit -m "Setup CI/CD pipeline"
git push origin main
```

## 🔧 Features

### Automated Deployment
- ✅ Zero-downtime deployments
- ✅ Automatic rollback on failure
- ✅ Health checks and monitoring
- ✅ Backup creation before deployment
- ✅ Laravel optimization (cache, routes, views)

### Security & Performance
- ✅ Nginx with SSL support
- ✅ PHP-FPM optimization
- ✅ Redis for sessions and caching
- ✅ Firewall and fail2ban protection
- ✅ Queue workers with Supervisor

### Monitoring & Maintenance
- ✅ Health check endpoint (`/health.php`)
- ✅ Comprehensive logging
- ✅ Automatic backup rotation
- ✅ Service monitoring

## 📁 File Structure

```
├── .github/workflows/
│   ├── deploy-ec2.yml          # Main deployment workflow
│   ├── ci.yml                  # CI pipeline
│   └── security.yml            # Security scanning
├── scripts/
│   └── setup-ec2-server.sh     # EC2 server setup
├── docs/
│   ├── CI-CD-Setup-Guide.md    # Complete setup guide
│   └── Quick-Setup-Checklist.md # Quick setup checklist
├── deploy.sh                   # Deployment script
├── env.production.template     # Production env template
└── README-CI-CD.md            # This file
```

## 🔐 Required GitHub Secrets

Add these to your repository secrets:

```
AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key
EC2_INSTANCE_ID=i-1234567890abcdef0
EC2_HOST=your-ec2-public-ip
EC2_USER=ubuntu
EC2_SSH_KEY=your-private-key-content
APP_URL=https://yourdomain.com
```

## 🛠️ Server Requirements

### Minimum EC2 Instance
- **Instance Type**: t3.medium
- **OS**: Ubuntu 22.04 LTS
- **Storage**: 20 GB (gp3)
- **Security Groups**: SSH (22), HTTP (80), HTTPS (443)

### Installed Software
- Nginx web server
- PHP 8.2 with FPM
- MySQL 8.0
- Redis server
- Composer
- Node.js 18
- Supervisor (for queue workers)

## 📊 Deployment Process

1. **Code Quality Checks**
   - PHP syntax and style validation
   - Frontend build verification
   - Security vulnerability scanning

2. **Testing**
   - Unit tests execution
   - Integration tests
   - Database migration testing

3. **Deployment**
   - Create deployment package
   - Upload to EC2 server
   - Run deployment script
   - Health check verification

4. **Post-Deployment**
   - Laravel optimization
   - Service restarts
   - Queue worker restart

## 🔍 Monitoring

### Health Check Endpoint
```
GET https://yourdomain.com/health.php
```

Returns JSON with service status:
```json
{
  "status": "healthy",
  "timestamp": "2024-01-15 10:30:00",
  "version": "1.0.0",
  "services": {
    "php": "8.2.0",
    "mysql": "connected",
    "redis": "connected"
  }
}
```

### Log Files
- Application: `/var/www/davids-wood-furniture/storage/logs/laravel.log`
- Nginx: `/var/log/nginx/access.log`, `/var/log/nginx/error.log`
- PHP-FPM: `/var/log/php8.2-fpm.log`
- Deployment: `/var/log/deployment.log`

## 🚨 Troubleshooting

### Common Issues

1. **Deployment Fails**
   ```bash
   # Check deployment logs
   tail -f /var/log/deployment.log
   ```

2. **Application Not Loading**
   ```bash
   # Check service status
   sudo systemctl status nginx php8.2-fpm mysql
   ```

3. **Database Connection Issues**
   ```bash
   # Test connection
   mysql -u davidswood_user -p -h 127.0.0.1 davidswood_furniture
   ```

## 📚 Documentation

- **[Complete Setup Guide](docs/CI-CD-Setup-Guide.md)** - Detailed step-by-step instructions
- **[Quick Setup Checklist](docs/Quick-Setup-Checklist.md)** - 30-minute setup checklist
- **[Laravel Deployment Docs](https://laravel.com/docs/deployment)**
- **[GitHub Actions Docs](https://docs.github.com/en/actions)**
- **[AWS EC2 Docs](https://docs.aws.amazon.com/ec2/)**

## 🎯 Next Steps

1. **Set up monitoring** (CloudWatch, New Relic, etc.)
2. **Configure automated backups**
3. **Set up staging environment**
4. **Implement blue-green deployments**
5. **Set up load balancing** (if needed)

## 💡 Tips

- Always test deployments on a staging environment first
- Monitor your application after each deployment
- Keep your server packages updated
- Regular security audits and updates
- Monitor resource usage and scale as needed

---

**Ready to deploy?** Follow the [Quick Setup Checklist](docs/Quick-Setup-Checklist.md) to get started in 30 minutes!

