<?php

// Simple error page for debugging
header('Content-Type: application/json');

$error = [
    'status' => 'error',
    'message' => 'Laravel application error',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'environment' => [
        'APP_ENV' => $_ENV['APP_ENV'] ?? 'not set',
        'APP_DEBUG' => $_ENV['APP_DEBUG'] ?? 'not set',
        'APP_KEY' => isset($_ENV['APP_KEY']) ? 'set' : 'not set',
        'DB_CONNECTION' => $_ENV['DB_CONNECTION'] ?? 'not set',
        'DB_HOST' => $_ENV['DB_HOST'] ?? 'not set',
        'DB_DATABASE' => $_ENV['DB_DATABASE'] ?? 'not set',
        'PORT' => $_ENV['PORT'] ?? 'not set',
    ],
    'directories' => [
        'storage_writable' => is_writable(__DIR__.'/../storage'),
        'bootstrap_cache_writable' => is_writable(__DIR__.'/../bootstrap/cache'),
        'storage_exists' => file_exists(__DIR__.'/../storage'),
        'bootstrap_cache_exists' => file_exists(__DIR__.'/../bootstrap/cache'),
        'env_exists' => file_exists(__DIR__.'/../.env'),
    ],
    'files' => [
        'autoload_exists' => file_exists(__DIR__.'/../vendor/autoload.php'),
        'app_exists' => file_exists(__DIR__.'/../bootstrap/app.php'),
    ],
];

// Try to test database connection
try {
    if (file_exists(__DIR__.'/../.env')) {
        $env_content = file_get_contents(__DIR__.'/../.env');
        $error['env_sample'] = substr($env_content, 0, 200) . '...';
    }
} catch (Exception $e) {
    $error['env_error'] = $e->getMessage();
}

http_response_code(500);
echo json_encode($error, JSON_PRETTY_PRINT);
