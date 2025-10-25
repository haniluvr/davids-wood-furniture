<?php

// Simple script to get CSRF token
session_start();

// Generate CSRF token
$token = bin2hex(random_bytes(32));
$_SESSION['_token'] = $token;

echo "CSRF Token: $token\n";
echo 'Session ID: '.session_id()."\n";
