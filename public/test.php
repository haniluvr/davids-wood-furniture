<?php

// Simple test endpoint to verify PHP server is working
header('Content-Type: application/json');

$response = [
    'status' => 'ok',
    'message' => 'PHP server is working',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'server_info' => [
        'SERVER_SOFTWARE' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
        'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
        'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'unknown',
        'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'unknown',
    ],
    'environment' => [
        'APP_ENV' => $_ENV['APP_ENV'] ?? 'not set',
        'PORT' => $_ENV['PORT'] ?? 'not set',
    ],
];

http_response_code(200);
echo json_encode($response, JSON_PRETTY_PRINT);
