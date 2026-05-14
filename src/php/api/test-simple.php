<?php
/**
 * test-simple.php
 * Simple test endpoint to verify server is working
 */

header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'message' => 'Server is working',
    'timestamp' => date('Y-m-d H:i:s')
]);
?>
