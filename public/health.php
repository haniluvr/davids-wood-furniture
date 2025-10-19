<?php
// Simple health check endpoint for Railway
// This bypasses Laravel to ensure it works even if Laravel has issues

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

try {
    $response = [
        'status' => 'ok',
        'timestamp' => date('Y-m-d H:i:s'),
        'service' => 'davids-wood-furniture',
        'php_version' => PHP_VERSION,
        'server_time' => time()
    ];
    
    http_response_code(200);
    echo json_encode($response, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>
