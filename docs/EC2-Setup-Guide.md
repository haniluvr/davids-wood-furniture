# EC2 Server Setup Guide for Laravel Deployment

This guide will help you set up an Ubuntu 22.04 LTS EC2 instance for hosting your Laravel application using Apache, PHP, and MySQL.

## Prerequisites

- AWS EC2 instance running Ubuntu 22.04 LTS
- SSH access to your EC2 instance
- Domain name (optional, but recommended for production)

## Step 1: Initial Server Setup

### Connect to your EC2 instance
```bash
ssh -i your-key.pem ubuntu@your-ec2-public-ip
```

### Update the system
```bash
sudo apt update && sudo apt upgrade -y
```

### Install essential packages
```bash
sudo apt install -y curl wget git unzip software-properties-common
```

## Step 2: Install Apache

### Install Apache
```bash
sudo apt install -y apache2
```

### Enable Apache modules
```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod ssl
```

### Start and enable Apache
```bash
sudo systemctl start apache2
sudo systemctl enable apache2
```

### Test Apache
```bash
curl http://localhost
```

## Step 3: Install PHP 8.2

### Add PHP repository
```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
```

### Install PHP and extensions
```bash
sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath php8.2-intl php8.2-soap php8.2-redis php8.2-imagick
```

### Install Apache PHP module
```bash
sudo apt install -y libapache2-mod-php8.2
```

### Restart Apache
```bash
sudo systemctl restart apache2
```

### Verify PHP installation
```bash
php -v
```

## Step 4: Install Composer

### Download and install Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### Verify Composer installation
```bash
composer --version
```

## Step 5: Install Node.js 18

### Add NodeSource repository
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
```

### Install Node.js
```bash
sudo apt install -y nodejs
```

### Verify Node.js installation
```bash
node --version
npm --version
```

## Step 6: Install MySQL

### Install MySQL
```bash
sudo apt install -y mysql-server
```

### Secure MySQL installation
```bash
sudo mysql_secure_installation
```

### Create database and user
```bash
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

## Step 7: Configure Apache Virtual Host

### Create application directory
```bash
sudo mkdir -p /var/www/html/davids-wood-furniture
sudo chown -R ubuntu:ubuntu /var/www/html/davids-wood-furniture
```

### Create Apache virtual host
```bash
sudo nano /etc/apache2/sites-available/davidswood.conf
```

Add the following configuration:
```apache
<VirtualHost *:80>
    ServerName davidswood.shop
    ServerAlias www.davidswood.shop
    DocumentRoot /var/www/html/davids-wood-furniture/public

    <Directory /var/www/html/davids-wood-furniture/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/davidswood_error.log
    CustomLog ${APACHE_LOG_DIR}/davidswood_access.log combined
</VirtualHost>

<VirtualHost *:80>
    ServerName admin.davidswood.shop
    DocumentRoot /var/www/html/davids-wood-furniture/public

    <Directory /var/www/html/davids-wood-furniture/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/davidswood_admin_error.log
    CustomLog ${APACHE_LOG_DIR}/davidswood_admin_access.log combined
</VirtualHost>
```

### Enable the site
```bash
sudo a2ensite davidswood.conf
sudo a2dissite 000-default
sudo systemctl reload apache2
```

## Step 8: Set Up SSL with Let's Encrypt (Production)

### Install Certbot
```bash
sudo apt install -y certbot python3-certbot-apache
```

### Get SSL certificate
```bash
sudo certbot --apache -d davidswood.shop -d www.davidswood.shop -d admin.davidswood.shop
```

### Test SSL renewal
```bash
sudo certbot renew --dry-run
```

## Step 9: Configure Firewall

### Install UFW
```bash
sudo apt install -y ufw
```

### Configure firewall rules
```bash
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 'Apache Full'
sudo ufw enable
```

### Check firewall status
```bash
sudo ufw status
```

## Step 10: Set Up Deployment User

### Create deployment user (optional)
```bash
sudo adduser deploy
sudo usermod -aG www-data deploy
sudo usermod -aG ubuntu deploy
```

### Set up SSH key for deployment user
```bash
sudo mkdir -p /home/deploy/.ssh
sudo cp /home/ubuntu/.ssh/authorized_keys /home/deploy/.ssh/
sudo chown -R deploy:deploy /home/deploy/.ssh
sudo chmod 700 /home/deploy/.ssh
sudo chmod 600 /home/deploy/.ssh/authorized_keys
```

## Step 11: Configure File Permissions

### Set up proper permissions for Laravel
```bash
sudo chown -R www-data:www-data /var/www/html/davids-wood-furniture
sudo chmod -R 755 /var/www/html/davids-wood-furniture
sudo chmod -R 775 /var/www/html/davids-wood-furniture/storage
sudo chmod -R 775 /var/www/html/davids-wood-furniture/bootstrap/cache
```

## Step 12: Install Additional Tools

### Install Redis (optional, for caching)
```bash
sudo apt install -y redis-server
sudo systemctl start redis-server
sudo systemctl enable redis-server
```

### Install Supervisor (for queue workers)
```bash
sudo apt install -y supervisor
```

### Install monitoring tools
```bash
sudo apt install -y htop iotop nethogs
```

## Step 13: Configure PHP Settings

### Edit PHP configuration
```bash
sudo nano /etc/php/8.2/apache2/php.ini
```

Update these settings:
```ini
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
memory_limit = 256M
```

### Restart Apache
```bash
sudo systemctl restart apache2
```

## Step 14: Set Up Log Rotation

### Configure log rotation for Laravel
```bash
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

## Step 15: Security Hardening

### Install fail2ban
```bash
sudo apt install -y fail2ban
sudo systemctl start fail2ban
sudo systemctl enable fail2ban
```

### Configure fail2ban for Apache
```bash
sudo nano /etc/fail2ban/jail.local
```

Add:
```ini
[apache-auth]
enabled = true

[apache-noscript]
enabled = true

[apache-overflows]
enabled = true
```

### Restart fail2ban
```bash
sudo systemctl restart fail2ban
```

## Step 16: Final Verification

### Check all services are running
```bash
sudo systemctl status apache2
sudo systemctl status mysql
sudo systemctl status redis-server
```

### Test PHP
```bash
php -m | grep -E "(mysql|redis|curl|gd|mbstring)"
```

### Test database connection
```bash
mysql -u davidswood_user -p -e "SHOW DATABASES;"
```

## Step 17: Clone Your Repository

### Clone the repository
```bash
cd /var/www/html
sudo git clone https://github.com/yourusername/davids-wood-furniture.git
sudo chown -R www-data:www-data davids-wood-furniture
```

### Set up initial environment
```bash
cd davids-wood-furniture
sudo cp .env.example .env
sudo nano .env
```

## Troubleshooting

### Common Issues

1. **Apache not starting**
   ```bash
   sudo systemctl status apache2
   sudo journalctl -u apache2
   ```

2. **PHP not working**
   ```bash
   sudo a2enmod php8.2
   sudo systemctl restart apache2
   ```

3. **Permission issues**
   ```bash
   sudo chown -R www-data:www-data /var/www/html/davids-wood-furniture
   sudo chmod -R 775 /var/www/html/davids-wood-furniture/storage
   ```

4. **Database connection issues**
   ```bash
   mysql -u davidswood_user -p -e "SELECT 1;"
   ```

### Log Locations

- Apache error log: `/var/log/apache2/error.log`
- Apache access log: `/var/log/apache2/access.log`
- Laravel logs: `/var/www/html/davids-wood-furniture/storage/logs/`
- MySQL logs: `/var/log/mysql/error.log`

## Next Steps

1. Configure GitHub secrets for deployment
2. Test the deployment workflow
3. Set up monitoring and backups
4. Configure SSL certificates
5. Set up automated backups

## Security Checklist

- [ ] Firewall configured (UFW)
- [ ] SSH key authentication only
- [ ] Fail2ban installed and configured
- [ ] Regular security updates enabled
- [ ] SSL certificates installed
- [ ] Database secured with strong passwords
- [ ] File permissions properly set
- [ ] Log rotation configured

Your EC2 instance is now ready for Laravel deployment! 🚀
