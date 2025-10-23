# GitHub Secrets Configuration Guide

This guide will help you configure all the required GitHub secrets for your Laravel deployment.

## Accessing GitHub Secrets

1. Go to your GitHub repository
2. Click on **Settings** tab
3. Click on **Secrets and variables** → **Actions**
4. Click **New repository secret** for each secret

## Required Secrets

### 1. EC2_HOST
**Description**: Your EC2 instance public IP address or domain name
**Example**: `13.211.143.224` or `yourdomain.com`
**How to get**: 
- From AWS Console → EC2 → Instances → Your instance → Public IPv4 address
- Or use your domain name if you have one

### 2. EC2_USER
**Description**: SSH username for your EC2 instance
**Example**: `ubuntu`
**Default**: Usually `ubuntu` for Ubuntu instances

### 3. EC2_SSH_KEY
**Description**: Your private SSH key (PEM file content)
**How to get**:
1. Download your key pair from AWS Console
2. Open the `.pem` file in a text editor
3. Copy the entire content including:
   ```
   -----BEGIN RSA PRIVATE KEY-----
   MIIEpAIBAAKCAQEA...
   ...your key content...
   -----END RSA PRIVATE KEY-----
   ```

### 4. APP_KEY
**Description**: Laravel application encryption key
**How to generate**:
```bash
# Run this locally in your project directory
php artisan key:generate --show
```
**Example**: `base64:abcd1234efgh5678ijkl9012mnop3456qrst7890uvwx=`

### 5. DB_HOST
**Description**: Database host address
**Example**: `127.0.0.1` (for local MySQL) or RDS endpoint
**Default**: `127.0.0.1` for MySQL on same server

### 6. DB_DATABASE
**Description**: Database name
**Example**: `davids_wood`
**Note**: Must match the database you created on EC2

### 7. DB_USERNAME
**Description**: Database username
**Example**: `davidswood_user`
**Note**: Must match the user you created on EC2

### 8. DB_PASSWORD
**Description**: Database password
**Example**: `DWF#2025$Secure!`
**Note**: Use the same password you set when creating the database user

### 9. APP_URL
**Description**: Your application URL
**Example**: `https://yourdomain.com` or `http://your-ec2-ip`
**Note**: Use HTTPS for production, HTTP for testing

## Optional Secrets

### 10. APP_ENV
**Description**: Application environment
**Example**: `production`
**Default**: `production` (if not set)

### 11. APP_DEBUG
**Description**: Debug mode
**Example**: `false`
**Default**: `false` (if not set)

### 12. MAIL_HOST
**Description**: SMTP server host
**Example**: `smtp.gmail.com`
**Note**: Required if you want email functionality

### 13. MAIL_PORT
**Description**: SMTP server port
**Example**: `587`
**Default**: `587` for TLS

### 14. MAIL_USERNAME
**Description**: SMTP username
**Example**: `your-email@gmail.com`
**Note**: Your email address

### 15. MAIL_PASSWORD
**Description**: SMTP password or app password
**Example**: `your-app-password`
**Note**: For Gmail, use App Password, not your regular password

### 16. MAIL_FROM_ADDRESS
**Description**: From email address
**Example**: `noreply@yourdomain.com`
**Note**: Should be a valid email address

### 17. MAIL_FROM_NAME
**Description**: From name
**Example**: `David's Wood Furniture`
**Default**: `David's Wood Furniture` (if not set)

## Step-by-Step Setup

### Step 1: Get Your EC2 Information
1. Go to AWS Console → EC2 → Instances
2. Find your instance and note:
   - **Public IPv4 address** (for EC2_HOST)
   - **Key pair name** (for EC2_SSH_KEY)

### Step 2: Generate APP_KEY
```bash
# In your local project directory
php artisan key:generate --show
```
Copy the output and save it as `APP_KEY` secret.

### Step 3: Set Up Database
If you haven't already, create the database and user on your EC2 instance:
```bash
# SSH to your EC2 instance
ssh -i your-key.pem ubuntu@your-ec2-ip

# Connect to MySQL
sudo mysql -u root -p

# Create database and user
CREATE DATABASE davids_wood;
CREATE USER 'davidswood_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON davids_wood.* TO 'davidswood_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 4: Configure GitHub Secrets
Go to your GitHub repository → Settings → Secrets and variables → Actions

Add each secret one by one:

1. **EC2_HOST**: Your EC2 public IP
2. **EC2_USER**: `ubuntu`
3. **EC2_SSH_KEY**: Content of your `.pem` file
4. **APP_KEY**: Generated key from step 2
5. **DB_HOST**: `127.0.0.1`
6. **DB_DATABASE**: `davids_wood`
7. **DB_USERNAME**: `davidswood_user`
8. **DB_PASSWORD**: The password you set in step 3
9. **APP_URL**: `http://your-ec2-ip` (or `https://yourdomain.com`)

### Step 5: Test the Configuration
1. Push a small change to your main branch
2. Go to GitHub → Actions tab
3. Watch the deployment workflow
4. Check if it completes successfully

## Troubleshooting Secrets

### Common Issues

#### 1. SSH Connection Failed
- **Problem**: `Permission denied (publickey)`
- **Solution**: Check EC2_SSH_KEY format
- **Fix**: Ensure the key starts with `-----BEGIN RSA PRIVATE KEY-----`

#### 2. Database Connection Failed
- **Problem**: `Access denied for user`
- **Solution**: Check DB_USERNAME and DB_PASSWORD
- **Fix**: Verify the user exists and password is correct

#### 3. APP_KEY Issues
- **Problem**: `No application encryption key has been specified`
- **Solution**: Check APP_KEY format
- **Fix**: Ensure it starts with `base64:`

#### 4. Permission Denied
- **Problem**: `Permission denied` during deployment
- **Solution**: Check EC2_USER
- **Fix**: Usually should be `ubuntu`

### Testing Secrets

#### Test SSH Connection
```bash
# Test SSH key locally
ssh -i your-key.pem ubuntu@your-ec2-ip
```

#### Test Database Connection
```bash
# SSH to EC2 and test database
ssh -i your-key.pem ubuntu@your-ec2-ip
mysql -u davidswood_user -p -e "SELECT 1;"
```

#### Test APP_KEY
```bash
# Test APP_KEY locally
php artisan key:generate --show
```

## Security Best Practices

### 1. Secret Management
- Never commit secrets to code
- Use strong, unique passwords
- Rotate secrets regularly
- Use environment-specific secrets

### 2. SSH Key Security
- Keep your private key secure
- Use strong passphrases
- Consider using SSH agent
- Regularly rotate SSH keys

### 3. Database Security
- Use strong database passwords
- Limit database user permissions
- Enable SSL for database connections
- Regular security updates

### 4. Application Security
- Use HTTPS in production
- Enable security headers
- Regular dependency updates
- Monitor for security issues

## Advanced Configuration

### Environment-Specific Secrets
You can set different secrets for different environments:

1. **Production**: Set secrets at repository level
2. **Staging**: Use environment-specific secrets
3. **Development**: Use local `.env` file

### Secret Rotation
Regularly rotate your secrets:
- SSH keys: Every 6 months
- Database passwords: Every 3 months
- APP_KEY: When compromised
- Mail passwords: As needed

### Monitoring Secrets
- Monitor secret usage in GitHub Actions logs
- Set up alerts for failed deployments
- Track secret access patterns
- Regular security audits

## Support

If you encounter issues with secrets:

1. **Check the format**: Ensure no extra spaces or characters
2. **Verify permissions**: Check EC2 user permissions
3. **Test locally**: Test SSH and database connections
4. **Check logs**: Review GitHub Actions logs for errors
5. **Contact support**: If issues persist

Your GitHub secrets are now configured for deployment! 🚀



