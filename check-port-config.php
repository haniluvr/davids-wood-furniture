<?php

/**
 * Quick diagnostic script to check port 8080 configuration
 * Run this with: php check-port-config.php
 */
echo "=== David's Wood Furniture - Port 8080 Configuration Check ===\n\n";

// Check 1: .env file exists
echo "1. Checking .env file...\n";
if (file_exists('.env')) {
    echo "   ✓ .env file exists\n";

    // Read .env and check APP_URL
    $envContent = file_get_contents('.env');
    if (preg_match('/APP_URL=(.+)/', $envContent, $matches)) {
        $appUrl = trim($matches[1]);
        echo "   Current APP_URL: $appUrl\n";

        if (strpos($appUrl, ':8080') !== false) {
            echo "   ✓ APP_URL is configured for port 8080\n";
        } else {
            echo "   ⚠ APP_URL should include :8080 for Apache on port 8080\n";
            echo "   Recommended: APP_URL=http://davidswood.test:8080\n";
        }
    } else {
        echo "   ⚠ APP_URL not found in .env\n";
    }
} else {
    echo "   ✗ .env file not found\n";
    echo "   Action: Copy .env.example to .env (or create one)\n";
    echo "   Then run: php artisan key:generate\n";
}

echo "\n2. Checking Apache configuration files...\n";

// Check for httpd-vhosts.conf
$vhostsPath = 'C:\\xampp\\apache\\conf\\extra\\httpd-vhosts.conf';
if (file_exists($vhostsPath)) {
    echo "   ✓ Found Apache vhosts config\n";
    $vhostsContent = file_get_contents($vhostsPath);

    $port80Count = substr_count($vhostsContent, '<VirtualHost *:80>');
    $port8080Count = substr_count($vhostsContent, '<VirtualHost *:8080>');

    echo "   VirtualHost entries on port 80: $port80Count\n";
    echo "   VirtualHost entries on port 8080: $port8080Count\n";

    if ($port8080Count >= 2) {
        echo "   ✓ Virtual hosts configured for port 8080\n";
    } else {
        echo "   ⚠ Virtual hosts need to be updated to port 8080\n";
        echo "   See PORT_8080_SETUP.md for details\n";
    }
} else {
    echo "   ℹ Apache vhosts config not found at default location\n";
    echo "   Check your XAMPP installation path\n";
}

// Check for httpd.conf
$httpdPath = 'C:\\xampp\\apache\\conf\\httpd.conf';
if (file_exists($httpdPath)) {
    echo "   ✓ Found Apache main config\n";
    $httpdContent = file_get_contents($httpdPath);

    if (preg_match('/^Listen\s+8080/m', $httpdContent)) {
        echo "   ✓ Apache is configured to Listen on port 8080\n";
    } elseif (preg_match('/^Listen\s+80/m', $httpdContent)) {
        echo "   ⚠ Apache is configured to Listen on port 80 (should be 8080)\n";
        echo "   Update 'Listen 80' to 'Listen 8080' in httpd.conf\n";
    } else {
        echo "   ℹ Could not determine Listen port\n";
    }
}

echo "\n3. Checking hosts file...\n";
$hostsPath = 'C:\\Windows\\System32\\drivers\\etc\\hosts';
if (file_exists($hostsPath)) {
    echo "   ✓ Found hosts file\n";
    $hostsContent = file_get_contents($hostsPath);

    $requiredHosts = [
        'davidswood.test',
        'admin.davidswood.test',
    ];

    foreach ($requiredHosts as $host) {
        if (preg_match('/127\.0\.0\.1\s+'.preg_quote($host, '/').'/m', $hostsContent)) {
            echo "   ✓ $host is configured\n";
        } else {
            echo "   ✗ $host is NOT configured\n";
            echo "   Add: 127.0.0.1    $host\n";
        }
    }
} else {
    echo "   ⚠ Cannot access hosts file (requires admin privileges)\n";
}

echo "\n4. Checking project files...\n";

// Check public directory
if (file_exists('public/index.php')) {
    echo "   ✓ public/index.php exists\n";
} else {
    echo "   ✗ public/index.php not found\n";
}

// Check .htaccess
if (file_exists('public/.htaccess')) {
    echo "   ✓ public/.htaccess exists\n";
} else {
    echo "   ⚠ public/.htaccess not found (required for URL rewriting)\n";
}

// Check routes
if (file_exists('routes/web.php')) {
    echo "   ✓ routes/web.php exists\n";
} else {
    echo "   ✗ routes/web.php not found\n";
}

// Check JavaScript config
if (file_exists('public/frontend/js/config.js')) {
    echo "   ✓ Frontend config.js exists\n";
    $configContent = file_get_contents('public/frontend/js/config.js');
    if (strpos($configContent, 'window.location.origin') !== false) {
        echo "   ✓ config.js uses dynamic origin (port-agnostic)\n";
    }
}

if (file_exists('public/frontend/js/api.js')) {
    echo "   ✓ Frontend api.js exists\n";
    $apiContent = file_get_contents('public/frontend/js/api.js');
    if (strpos($apiContent, 'window.location.origin') !== false) {
        echo "   ✓ api.js uses dynamic origin (port-agnostic)\n";
    }
}

echo "\n5. Testing network connectivity...\n";

// Check if port 8080 is available/in use
$socket = @fsockopen('localhost', 8080, $errno, $errstr, 1);
if ($socket) {
    echo "   ✓ Port 8080 is open and responding\n";
    fclose($socket);
} else {
    echo "   ⚠ Port 8080 is not responding\n";
    echo "   Make sure Apache is running\n";
}

// Check if IIS is on port 80
$socket = @fsockopen('localhost', 80, $errno, $errstr, 1);
if ($socket) {
    echo "   ✓ Port 80 is open (IIS should be running here)\n";
    fclose($socket);
} else {
    echo "   ℹ Port 80 is not responding\n";
}

echo "\n=== Summary ===\n";
echo "Review the checks above and follow PORT_8080_SETUP.md for detailed instructions.\n";
echo "\nKey steps:\n";
echo "1. Update Apache httpd.conf: Listen 8080\n";
echo "2. Update Apache httpd-vhosts.conf: <VirtualHost *:8080>\n";
echo "3. Update .env: APP_URL=http://davidswood.test:8080\n";
echo "4. Update hosts file: 127.0.0.1 davidswood.test and admin.davidswood.test\n";
echo "5. Restart Apache\n";
echo "6. Access: http://davidswood.test:8080\n\n";
