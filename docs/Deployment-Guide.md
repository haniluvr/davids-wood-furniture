# Laravel Deployment Guide

This guide covers deploying your Laravel application to EC2 using GitHub Actions and traditional Apache deployment.

## Overview

This deployment strategy uses:
- **GitHub Actions** for CI/CD automation
- **Apache** as the web server
- **PHP 8.2** for application runtime
- **MySQL** for database
- **SSH** for deployment to EC2

## Prerequisites

### 1. EC2 Instance Setup
Follow the [EC2 Setup Guide](EC2-Setup-Guide.md) to prepare your server.

### 2. GitHub Repository
Your code should be in a GitHub repository with the following structure:
```
davids-wood-furniture/
├── .github/workflows/deploy.yml
├── .apache/davidswood.conf
├── docs/
├── public/
├── resources/
├── routes/
└── ...
```

### 3. Domain Name (Optional)
For production, you'll need a domain name pointing to your EC2 instance.

## GitHub Secrets Configuration

Go to your GitHub repository → Settings → Secrets and variables → Actions

### Required Secrets

| Secret Name | Description | Example Value |
|-------------|-------------|---------------|
| `EC2_HOST` | Your EC2 public IP or domain | `13.211.143.224` or `yourdomain.com` |
| `EC2_USER` | SSH username | `ubuntu` |
| `EC2_SSH_KEY` | Private SSH key (PEM content) | `-----BEGIN RSA PRIVATE KEY-----...` |
| `APP_KEY` | Laravel application key | Run `php artisan key:generate --show` locally |
| `DB_HOST` | Database host | `127.0.0.1` |
| `DB_DATABASE` | Database name | `davids_wood` |
| `DB_USERNAME` | Database username | `davidswood_user` |
| `DB_PASSWORD` | Database password | `your_secure_password` |
| `APP_URL` | Application URL | `https://yourdomain.com` |

### Optional Secrets

| Secret Name | Description | Example Value |
|-------------|-------------|---------------|
| `APP_ENV` | Application environment | `production` |
| `APP_DEBUG` | Debug mode | `false` |
| `MAIL_HOST` | SMTP host | `smtp.gmail.com` |
| `MAIL_PORT` | SMTP port | `587` |
| `MAIL_USERNAME` | SMTP username | `your-email@gmail.com` |
| `MAIL_PASSWORD` | SMTP password | `your-app-password` |
| `MAIL_FROM_ADDRESS` | From email | `noreply@yourdomain.com` |
| `MAIL_FROM_NAME` | From name | `David's Wood Furniture` |

## Deployment Process

### Automatic Deployment

1. **Push to main branch**
   ```bash
   git add .
   git commit -m "Your changes"
   git push origin main
   ```

2. **GitHub Actions will automatically:**
   - Run tests
   - Build frontend assets
   - Deploy to EC2
   - Run migrations
   - Clear caches
   - Reload Apache

### Manual Deployment

If you need to deploy manually:

1. **SSH to your EC2 instance**
   ```bash
   ssh -i your-key.pem ubuntu@your-ec2-ip
   ```

2. **Navigate to application directory**
   ```bash
   cd /var/www/html/davids-wood-furniture
   ```

3. **Pull latest code**
   ```bash
   git pull origin main
   ```

4. **Install dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci
   npm run build
   ```

5. **Run migrations**
   ```bash
   php artisan migrate --force
   ```

6. **Clear caches**
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```

7. **Optimize for production**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

8. **Reload Apache**
   ```bash
   sudo systemctl reload apache2
   ```

## Deployment Workflow

The GitHub Actions workflow (`.github/workflows/deploy.yml`) includes:

### 1. Test Job
- Runs PHPUnit tests with SQLite
- Removes MySQL-specific migrations for CI
- Times out after 8 minutes

### 2. Code Quality Job
- Runs Laravel Pint for code formatting
- Times out after 3 minutes

### 3. Frontend Build Job
- Installs Node.js dependencies
- Builds frontend assets
- Uploads build artifacts

### 4. Deploy Job
- Installs PHP dependencies
- Builds frontend assets
- SSH to EC2 and deploys:
  - Pulls latest code
  - Creates `.env` file from secrets
  - Installs dependencies
  - Builds assets
  - Runs migrations
  - Clears caches
  - Optimizes for production
  - Fixes permissions
  - Reloads Apache
  - Tests deployment

## Environment Configuration

The deployment automatically creates a `.env` file on the server with values from GitHub secrets:

```bash
# Application
APP_NAME="David's Wood Furniture"
APP_ENV=production
APP_DEBUG=false
APP_KEY=your-app-key
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=davids_wood
DB_USERNAME=davidswood_user
DB_PASSWORD=your-password

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="David's Wood Furniture"
```

## Troubleshooting

### Common Issues

#### 1. SSH Connection Failed
```bash
# Check SSH key format
head -1 your-key.pem
# Should show: -----BEGIN RSA PRIVATE KEY-----

# Test SSH connection
ssh -i your-key.pem ubuntu@your-ec2-ip
```

#### 2. Permission Denied
```bash
# Fix file permissions
sudo chown -R www-data:www-data /var/www/html/davids-wood-furniture
sudo chmod -R 775 /var/www/html/davids-wood-furniture/storage
sudo chmod -R 775 /var/www/html/davids-wood-furniture/bootstrap/cache
```

#### 3. Database Connection Failed
```bash
# Test database connection
mysql -u davidswood_user -p -e "SELECT 1;"

# Check MySQL status
sudo systemctl status mysql
```

#### 4. Apache Not Starting
```bash
# Check Apache status
sudo systemctl status apache2

# Check Apache error logs
sudo tail -f /var/log/apache2/error.log

# Test Apache configuration
sudo apache2ctl configtest
```

#### 5. Composer Dependencies Failed
```bash
# Clear Composer cache
composer clear-cache

# Reinstall dependencies
composer install --no-dev --optimize-autoloader
```

#### 6. Frontend Build Failed
```bash
# Clear npm cache
npm cache clean --force

# Reinstall dependencies
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Log Locations

- **Apache Error Log**: `/var/log/apache2/error.log`
- **Apache Access Log**: `/var/log/apache2/access.log`
- **Laravel Logs**: `/var/www/html/davids-wood-furniture/storage/logs/`
- **MySQL Logs**: `/var/log/mysql/error.log`
- **GitHub Actions Logs**: GitHub repository → Actions tab

### Health Checks

The deployment includes automatic health checks:

1. **Local health check**: `curl http://localhost/health`
2. **External health check**: `curl http://your-ec2-ip/health`

If health checks fail, check:
- Apache is running: `sudo systemctl status apache2`
- PHP is working: `php -v`
- Database is accessible: `mysql -u davidswood_user -p`
- Application logs: `tail -f storage/logs/laravel.log`

## Rollback Procedures

### Quick Rollback
```bash
# SSH to EC2
ssh -i your-key.pem ubuntu@your-ec2-ip

# Navigate to application
cd /var/www/html/davids-wood-furniture

# Rollback to previous commit
git log --oneline -5
git reset --hard HEAD~1

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Reload Apache
sudo systemctl reload apache2
```

### Database Rollback
```bash
# Rollback last migration
php artisan migrate:rollback

# Rollback specific migration
php artisan migrate:rollback --step=1
```

## Security Considerations

### 1. File Permissions
```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/html/davids-wood-furniture
sudo chmod -R 755 /var/www/html/davids-wood-furniture
sudo chmod -R 775 /var/www/html/davids-wood-furniture/storage
sudo chmod -R 775 /var/www/html/davids-wood-furniture/bootstrap/cache
```

### 2. Environment Security
- Never commit `.env` files
- Use strong database passwords
- Enable SSL/HTTPS in production
- Keep dependencies updated

### 3. Server Security
- Configure firewall (UFW)
- Install fail2ban
- Regular security updates
- Monitor logs for suspicious activity

## Performance Optimization

### 1. Laravel Optimization
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### 2. Apache Optimization
- Enable mod_deflate for compression
- Configure mod_expires for caching
- Use HTTP/2 if available

### 3. Database Optimization
- Add database indexes
- Use database connection pooling
- Consider Redis for caching

## Monitoring

### 1. Application Monitoring
- Set up log monitoring
- Use Laravel Telescope (development only)
- Monitor error rates and response times

### 2. Server Monitoring
- Monitor CPU, memory, and disk usage
- Set up alerts for high resource usage
- Monitor Apache and MySQL performance

### 3. Uptime Monitoring
- Use services like UptimeRobot
- Set up health check endpoints
- Monitor SSL certificate expiration

## Backup Strategy

### 1. Code Backups
- Git repository serves as code backup
- Keep multiple branches for rollback options

### 2. Database Backups
```bash
# Create database backup
mysqldump -u davidswood_user -p davids_wood > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore from backup
mysql -u davidswood_user -p davids_wood < backup_file.sql
```

### 3. File Backups
- Backup uploaded files
- Backup configuration files
- Store backups in secure location

## Scaling Considerations

### Vertical Scaling
- Upgrade EC2 instance size
- Add more CPU and memory
- Use faster storage (SSD)

### Horizontal Scaling
- Use load balancer
- Multiple EC2 instances
- Database clustering
- CDN for static assets

## Support and Maintenance

### Regular Tasks
- Update dependencies monthly
- Monitor security advisories
- Review and rotate secrets
- Clean up old logs
- Test backup restoration

### Emergency Procedures
- Keep SSH access available
- Document rollback procedures
- Have contact information ready
- Test disaster recovery plan

Your Laravel application is now ready for production deployment! 🚀




