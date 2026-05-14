<?php
/**
 * test-auth.php
 * Simple endpoint to test authentication
 */

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

header('Content-Type: application/json');

try {
    ensure_session_started();
    $user = get_user();
    
    if (!$user) {
        echo json_encode([
            'authenticated' => false,
            'message' => 'Not logged in',
            'session_data' => $_SESSION ?? []
        ]);
    } else {
        echo json_encode([
            'authenticated' => true,
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'],
            'message' => 'User is logged in as ' . $user['role'],
            'is_doctor' => $user['role'] === 'doctor',
            'all_session_data' => $_SESSION
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
?>
