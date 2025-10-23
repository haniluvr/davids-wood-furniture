# Quick Setup Checklist

Use this checklist to quickly set up your Laravel deployment on EC2.

## Pre-Deployment Checklist

### ✅ EC2 Instance Setup
- [ ] Ubuntu 22.04 LTS instance running
- [ ] Security groups configured (ports 22, 80, 443 open)
- [ ] SSH key pair downloaded and accessible
- [ ] Public IP address noted

### ✅ GitHub Repository
- [ ] Code pushed to GitHub repository
- [ ] GitHub Actions enabled
- [ ] Repository is public or has proper access

### ✅ Local Development
- [ ] Laravel application working locally
- [ ] Tests passing locally
- [ ] Frontend assets building successfully
- [ ] APP_KEY generated: `php artisan key:generate --show`

## EC2 Server Setup (One-time)

### ✅ Basic Server Setup
```bash
# SSH to your EC2 instance
ssh -i your-key.pem ubuntu@your-ec2-ip

# Update system
sudo apt update && sudo apt upgrade -y

# Install essential packages
sudo apt install -y curl wget git unzip software-properties-common
```

### ✅ Install Apache
```bash
# Install Apache
sudo apt install -y apache2

# Enable modules
sudo a2enmod rewrite headers ssl

# Start Apache
sudo systemctl start apache2
sudo systemctl enable apache2
```

### ✅ Install PHP 8.2
```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP and extensions
sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath php8.2-intl php8.2-soap php8.2-redis php8.2-imagick libapache2-mod-php8.2

# Restart Apache
sudo systemctl restart apache2
```

### ✅ Install Composer
```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### ✅ Install Node.js 18
```bash
# Add NodeSource repository
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -

# Install Node.js
sudo apt install -y nodejs
```

### ✅ Install MySQL
```bash
# Install MySQL
sudo apt install -y mysql-server

# Secure MySQL
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

In MySQL prompt:
```sql
CREATE DATABASE davids_wood;
CREATE USER 'davidswood_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON davids_wood.* TO 'davidswood_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### ✅ Configure Apache Virtual Host
```bash
# Create application directory
sudo mkdir -p /var/www/html/davids-wood-furniture
sudo chown -R ubuntu:ubuntu /var/www/html/davids-wood-furniture

# Copy virtual host configuration
sudo cp /var/www/html/davids-wood-furniture/.apache/davidswood.conf /etc/apache2/sites-available/

# Enable site
sudo a2ensite davidswood.conf
sudo a2dissite 000-default
sudo systemctl reload apache2
```

### ✅ Clone Repository
```bash
# Clone repository
cd /var/www/html
sudo git clone https://github.com/yourusername/davids-wood-furniture.git
sudo chown -R www-data:www-data davids-wood-furniture
```

## GitHub Secrets Configuration

### ✅ Required Secrets
Go to GitHub repository → Settings → Secrets and variables → Actions

| Secret Name | Value | How to Get |
|-------------|-------|------------|
| `EC2_HOST` | Your EC2 public IP | AWS Console → EC2 → Instances |
| `EC2_USER` | `ubuntu` | Default for Ubuntu instances |
| `EC2_SSH_KEY` | Content of your `.pem` file | Download from AWS Console |
| `APP_KEY` | Generated key | Run `php artisan key:generate --show` locally |
| `DB_HOST` | `127.0.0.1` | Local MySQL on same server |
| `DB_DATABASE` | `davids_wood` | Database name you created |
| `DB_USERNAME` | `davidswood_user` | Database user you created |
| `DB_PASSWORD` | Your database password | Password you set in MySQL |
| `APP_URL` | `http://your-ec2-ip` | Your EC2 public IP or domain |

### ✅ Optional Secrets
| Secret Name | Value | Description |
|-------------|-------|-------------|
| `APP_ENV` | `production` | Application environment |
| `APP_DEBUG` | `false` | Debug mode |
| `MAIL_HOST` | `smtp.gmail.com` | SMTP server |
| `MAIL_PORT` | `587` | SMTP port |
| `MAIL_USERNAME` | `your-email@gmail.com` | Your email |
| `MAIL_PASSWORD` | `your-app-password` | App password for Gmail |
| `MAIL_FROM_ADDRESS` | `noreply@yourdomain.com` | From email |
| `MAIL_FROM_NAME` | `David's Wood Furniture` | From name |

## Test Deployment

### ✅ First Deployment
1. **Push to main branch**:
   ```bash
   git add .
   git commit -m "Initial deployment setup"
   git push origin main
   ```

2. **Check GitHub Actions**:
   - Go to GitHub → Actions tab
   - Watch the deployment workflow
   - Check for any errors

3. **Test Application**:
   - Visit `http://your-ec2-ip`
   - Check health endpoint: `http://your-ec2-ip/health`
   - Verify database connection

### ✅ Verify Deployment
```bash
# SSH to EC2 and check
ssh -i your-key.pem ubuntu@your-ec2-ip

# Check Apache status
sudo systemctl status apache2

# Check application logs
tail -f /var/www/html/davids-wood-furniture/storage/logs/laravel.log

# Test database connection
mysql -u davidswood_user -p -e "SELECT 1;"
```

## Post-Deployment Setup

### ✅ SSL Certificate (Production)
```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-apache

# Get SSL certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

### ✅ Security Hardening
```bash
# Configure firewall
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 'Apache Full'
sudo ufw enable

# Install fail2ban
sudo apt install -y fail2ban
sudo systemctl start fail2ban
sudo systemctl enable fail2ban
```

### ✅ Monitoring Setup
```bash
# Install monitoring tools
sudo apt install -y htop iotop nethogs

# Set up log rotation
sudo nano /etc/logrotate.d/laravel
```

Add:
```
/var/www/html/davids-wood-furniture/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 www-data www-data
}
```

## Troubleshooting Checklist

### ✅ Common Issues
- [ ] **SSH Connection**: Test SSH key format and permissions
- [ ] **Database Connection**: Verify MySQL user and password
- [ ] **Apache Status**: Check if Apache is running
- [ ] **File Permissions**: Ensure proper ownership and permissions
- [ ] **PHP Extensions**: Verify all required extensions are installed
- [ ] **Composer Dependencies**: Check if all packages are installed
- [ ] **Node.js Build**: Verify frontend assets are building
- [ ] **GitHub Actions**: Check workflow logs for errors

### ✅ Health Checks
- [ ] **Application**: `curl http://your-ec2-ip`
- [ ] **Health Endpoint**: `curl http://your-ec2-ip/health`
- [ ] **Database**: `mysql -u davidswood_user -p -e "SELECT 1;"`
- [ ] **Apache**: `sudo systemctl status apache2`
- [ ] **PHP**: `php -v`

## Success Criteria

### ✅ Deployment Success
- [ ] GitHub Actions workflow completes successfully
- [ ] Application loads without errors
- [ ] Health check returns 200 status
- [ ] Database connection works
- [ ] All pages load correctly
- [ ] No errors in logs

### ✅ Performance Check
- [ ] Page load times under 3 seconds
- [ ] No memory errors
- [ ] Apache serving requests efficiently
- [ ] Database queries optimized

## Next Steps

### ✅ Production Ready
- [ ] SSL certificate installed
- [ ] Domain name configured
- [ ] Monitoring set up
- [ ] Backups configured
- [ ] Security hardened
- [ ] Performance optimized

### ✅ Maintenance
- [ ] Regular updates scheduled
- [ ] Log monitoring set up
- [ ] Backup verification
- [ ] Security scanning
- [ ] Performance monitoring

## Support Resources

### ✅ Documentation
- [ ] EC2 Setup Guide: `docs/EC2-Setup-Guide.md`
- [ ] Deployment Guide: `docs/Deployment-Guide.md`
- [ ] GitHub Secrets Setup: `docs/GitHub-Secrets-Setup.md`

### ✅ Troubleshooting
- [ ] Check Apache logs: `/var/log/apache2/error.log`
- [ ] Check Laravel logs: `storage/logs/laravel.log`
- [ ] Check GitHub Actions logs: GitHub → Actions tab
- [ ] Test SSH connection: `ssh -i key.pem ubuntu@your-ec2-ip`

Your Laravel application is now ready for production! 🚀

## Quick Commands Reference

```bash
# Check Apache status
sudo systemctl status apache2

# Restart Apache
sudo systemctl restart apache2

# Check PHP version
php -v

# Check Composer
composer --version

# Check Node.js
node --version

# Check MySQL
sudo systemctl status mysql

# Test database connection
mysql -u davidswood_user -p -e "SELECT 1;"

# Check application logs
tail -f /var/www/html/davids-wood-furniture/storage/logs/laravel.log

# Fix permissions
sudo chown -R www-data:www-data /var/www/html/davids-wood-furniture
sudo chmod -R 775 /var/www/html/davids-wood-furniture/storage
sudo chmod -R 775 /var/www/html/davids-wood-furniture/bootstrap/cache
```