<?php
// Debug endpoint to check application status
header('Content-Type: application/json');

$debug_info = [
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
    'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'Unknown',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
    'http_host' => $_SERVER['HTTP_HOST'] ?? 'Unknown',
    'server_port' => $_SERVER['SERVER_PORT'] ?? 'Unknown',
    'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
    'environment' => [
        'APP_ENV' => $_ENV['APP_ENV'] ?? 'not set',
        'APP_DEBUG' => $_ENV['APP_DEBUG'] ?? 'not set',
        'APP_KEY' => isset($_ENV['APP_KEY']) ? 'set' : 'not set',
        'DB_CONNECTION' => $_ENV['DB_CONNECTION'] ?? 'not set',
        'DB_HOST' => $_ENV['DB_HOST'] ?? 'not set',
        'DB_DATABASE' => $_ENV['DB_DATABASE'] ?? 'not set',
        'PORT' => $_ENV['PORT'] ?? 'not set',
    ],
    'files' => [
        'env_exists' => file_exists(__DIR__.'/../.env'),
        'autoload_exists' => file_exists(__DIR__.'/../vendor/autoload.php'),
        'app_exists' => file_exists(__DIR__.'/../bootstrap/app.php'),
        'storage_writable' => is_writable(__DIR__.'/../storage'),
        'bootstrap_cache_writable' => is_writable(__DIR__.'/../bootstrap/cache'),
    ],
    'directories' => [
        'current_dir' => getcwd(),
        'script_dir' => __DIR__,
        'parent_dir' => dirname(__DIR__),
    ],
    'apache_modules' => [
        'mod_rewrite' => function_exists('apache_get_modules') ? in_array('mod_rewrite', apache_get_modules()) : 'unknown',
    ],
];

// Try to test database connection
try {
    if (file_exists(__DIR__.'/../.env')) {
        $env_content = file_get_contents(__DIR__.'/../.env');
        $debug_info['env_sample'] = substr($env_content, 0, 300) . '...';
        
        // Try to load Laravel and test DB
        if (file_exists(__DIR__.'/../vendor/autoload.php')) {
            require_once __DIR__.'/../vendor/autoload.php';
            $app = require_once __DIR__.'/../bootstrap/app.php';
            $debug_info['laravel_loaded'] = true;
            
            try {
                $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
                $debug_info['database_connection'] = 'success';
            } catch (Exception $e) {
                $debug_info['database_connection'] = 'failed: ' . $e->getMessage();
            }
        }
    }
} catch (Exception $e) {
    $debug_info['error'] = $e->getMessage();
}

http_response_code(200);
echo json_encode($debug_info, JSON_PRETTY_PRINT);
?>

