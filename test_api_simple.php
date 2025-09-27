<?php
echo "Testing API endpoint...\n";
$response = file_get_contents('http://localhost:8000/api/products');
echo "Response: " . $response . "\n";
?>
