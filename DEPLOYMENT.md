# Doctor Patient Portal - Deployment Guide

## 🚀 Production Deployment Guide

This guide covers deploying the Doctor Patient Portal to a production environment with security best practices.

## Prerequisites

### Server Requirements
- **PHP**: 8.0 or higher
- **MySQL**: 8.0 or higher
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **SSL Certificate**: Required for production
- **Memory**: Minimum 512MB RAM
- **Storage**: Minimum 1GB free space

### Required PHP Extensions
```bash
php-mysqli
php-json
php-curl
php-gd
php-mbstring
php-zip
```

## 1. Server Setup

### Ubuntu/Debian
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install LAMP stack
sudo apt install apache2 mysql-server php8.0 php8.0-mysqli php8.0-json php8.0-curl php8.0-gd php8.0-mbstring php8.0-zip -y

# Enable Apache modules
sudo a2enmod rewrite ssl headers
sudo systemctl restart apache2
```

### CentOS/RHEL
```bash
# Update system
sudo yum update -y

# Install LAMP stack
sudo yum install httpd mysql-server php php-mysqli php-json php-curl php-gd php-mbstring -y

# Start services
sudo systemctl start httpd mysql
sudo systemctl enable httpd mysql
```

## 2. Database Setup

### Create Database User
```sql
-- Connect to MySQL as root
mysql -u root -p

-- Create database
CREATE DATABASE dpp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create dedicated user
CREATE USER 'dppuser'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON dpp.* TO 'dppuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Import Database Schema
```bash
mysql -u dppuser -p dpp < sqlfiles/dpp.sql
```

## 3. Application Deployment

### Upload Files
```bash
# Upload application files to web directory
sudo cp -r /path/to/dpp/* /var/www/html/

# Set proper ownership
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/
```

### Create Required Directories
```bash
# Create upload directories with proper permissions
sudo mkdir -p /var/www/html/{doctor,patient,uploads,logs}
sudo chown -R www-data:www-data /var/www/html/{doctor,patient,uploads,logs}
sudo chmod -R 755 /var/www/html/{doctor,patient,uploads,logs}
```

### Configure Application
```bash
# Run installation script
php install.php

# Or manually configure
cp config/config.example.php config/config.php
# Edit config/config.php with your settings
```

## 4. Web Server Configuration

### Apache Configuration
Create `/etc/apache2/sites-available/dpp.conf`:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/html
    
    # Redirect to HTTPS
    Redirect permanent / https://yourdomain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/html
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    # Security Headers
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
    Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'"
    
    # Directory Configuration
    <Directory /var/www/html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # Protect sensitive files
    <Files "config.php">
        Require all denied
    </Files>
    
    <Files "*.sql">
        Require all denied
    </Files>
    
    # API Configuration
    <Directory /var/www/html/api>
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php [QSA,L]
    </Directory>
    
    # Logging
    ErrorLog ${APACHE_LOG_DIR}/dpp_error.log
    CustomLog ${APACHE_LOG_DIR}/dpp_access.log combined
</VirtualHost>
```

Enable the site:
```bash
sudo a2ensite dpp.conf
sudo a2dissite 000-default.conf
sudo systemctl reload apache2
```

### Nginx Configuration
Create `/etc/nginx/sites-available/dpp`:
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /var/www/html;
    index index.php index.html;
    
    # SSL Configuration
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    ssl_prefer_server_ciphers off;
    
    # Security Headers
    add_header X-Content-Type-Options nosniff;
    add_header X-Frame-Options DENY;
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=63072000; includeSubDomains; preload";
    
    # PHP Configuration
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # API Routes
    location /api/ {
        try_files $uri $uri/ /api/index.php?$query_string;
    }
    
    # Protect sensitive files
    location ~ /config/ {
        deny all;
    }
    
    location ~ \.sql$ {
        deny all;
    }
    
    # Static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/dpp /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 5. SSL Certificate Setup

### Using Let's Encrypt (Recommended)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Get certificate
sudo certbot --apache -d yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

## 6. Security Hardening

### File Permissions
```bash
# Set secure permissions
sudo find /var/www/html -type f -exec chmod 644 {} \;
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo chmod 600 /var/www/html/config/config.php
```

### PHP Security
Edit `/etc/php/8.0/apache2/php.ini`:
```ini
# Hide PHP version
expose_php = Off

# Disable dangerous functions
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source

# File upload security
file_uploads = On
upload_max_filesize = 5M
max_file_uploads = 10

# Session security
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1

# Error reporting (disable in production)
display_errors = Off
log_errors = On
error_log = /var/log/php_errors.log
```

### Database Security
```sql
-- Remove test database
DROP DATABASE IF EXISTS test;

-- Remove anonymous users
DELETE FROM mysql.user WHERE User='';

-- Disable remote root login
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');

FLUSH PRIVILEGES;
```

## 7. Monitoring & Logging

### Log Rotation
Create `/etc/logrotate.d/dpp`:
```
/var/www/html/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

### Health Check Script
Create `/var/www/html/health.php`:
```php
<?php
header('Content-Type: application/json');

$checks = [
    'database' => false,
    'files' => false,
    'permissions' => false
];

// Database check
try {
    require_once 'includes/conn.php';
    $checks['database'] = (bool)$con;
} catch (Exception $e) {
    // Database connection failed
}

// File system check
$checks['files'] = file_exists('config/config.php') && is_readable('config/config.php');

// Permissions check
$checks['permissions'] = is_writable('uploads/') && is_writable('logs/');

$status = array_reduce($checks, function($carry, $item) {
    return $carry && $item;
}, true);

http_response_code($status ? 200 : 503);
echo json_encode([
    'status' => $status ? 'healthy' : 'unhealthy',
    'checks' => $checks,
    'timestamp' => date('c')
]);
?>
```

## 8. Backup Strategy

### Database Backup Script
Create `/usr/local/bin/backup-dpp.sh`:
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/dpp"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="dpp"
DB_USER="dppuser"
DB_PASS="your_password"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/dpp_db_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/dpp_files_$DATE.tar.gz -C /var/www/html .

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

Make executable and schedule:
```bash
sudo chmod +x /usr/local/bin/backup-dpp.sh
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-dpp.sh
```

## 9. Performance Optimization

### PHP OPcache
Enable in `/etc/php/8.0/apache2/php.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### MySQL Optimization
Add to `/etc/mysql/mysql.conf.d/mysqld.cnf`:
```ini
[mysqld]
innodb_buffer_pool_size = 256M
query_cache_type = 1
query_cache_size = 64M
max_connections = 100
```

## 10. Final Checklist

- [ ] Database user created with minimal privileges
- [ ] SSL certificate installed and configured
- [ ] Security headers configured
- [ ] File permissions set correctly
- [ ] PHP security settings applied
- [ ] Logging configured
- [ ] Backup system in place
- [ ] Health monitoring setup
- [ ] Performance optimization applied
- [ ] Demo data created (optional)

## 11. Post-Deployment Testing

```bash
# Run API tests
php tests/ApiTest.php

# Check health endpoint
curl https://yourdomain.com/health.php

# Verify SSL
curl -I https://yourdomain.com

# Test application functionality
# - Login as admin
# - Create doctor account
# - Create patient account
# - Book appointment
# - Send message
```

## 12. Maintenance

### Regular Tasks
- Update system packages monthly
- Review logs weekly
- Test backups monthly
- Update SSL certificates (automated with Let's Encrypt)
- Monitor disk space and performance

### Security Updates
- Monitor PHP security advisories
- Update application dependencies
- Review and update security configurations
- Conduct security audits quarterly

## Support

For deployment issues or questions:
1. Check logs in `/var/log/apache2/` or `/var/log/nginx/`
2. Review application logs in `/var/www/html/logs/`
3. Test database connectivity
4. Verify file permissions
5. Check SSL certificate validity

---

**Note**: Replace `yourdomain.com` with your actual domain name and update all passwords and paths according to your environment.