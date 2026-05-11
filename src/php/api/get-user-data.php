<?php
/**
 * API: get-user-data.php
 * Returns current user's session data as JSON
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();
$user = get_user();

echo json_encode([
    'success' => true,
    'user' => [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role']
    ]
]);
?>
