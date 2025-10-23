<?php

// Simple test endpoint
header('Content-Type: text/html');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: #28a745; }
        .info { color: #17a2b8; }
        .error { color: #dc3545; }
    </style>
</head>
<body>
    <div class='container'>
        <h1 class='success'>✅ Application is Running!</h1>
        <p><strong>Server Time:</strong> ".date('Y-m-d H:i:s').'</p>
        <p><strong>PHP Version:</strong> '.PHP_VERSION.'</p>
        <p><strong>Server Software:</strong> '.($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown').'</p>
        <p><strong>Document Root:</strong> '.($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown').'</p>
        <p><strong>Request URI:</strong> '.($_SERVER['REQUEST_URI'] ?? 'Unknown')."</p>
        
        <h2>Quick Links:</h2>
        <ul>
            <li><a href='/health.php'>Health Check</a></li>
            <li><a href='/debug.php'>Debug Info</a></li>
            <li><a href='/error.php'>Error Info</a></li>
        </ul>
        
        <h2>Environment:</h2>
        <p><strong>APP_ENV:</strong> ".($_ENV['APP_ENV'] ?? 'not set').'</p>
        <p><strong>PORT:</strong> '.($_ENV['PORT'] ?? 'not set').'</p>
        <p><strong>DB_CONNECTION:</strong> '.($_ENV['DB_CONNECTION'] ?? 'not set').'</p>
    </div>
</body>
</html>';
