<?php
/**
 * API: get-patients.php
 * Returns a list of patients (id and name) for doctor use
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();

$user = get_user();
if (!$user) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// Only doctors should fetch full patient lists
if (($user['role'] ?? '') !== 'doctor') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Doctor access required']);
    exit;
}

try {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE role = 'patient' ORDER BY name ASC");
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $patients]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to fetch patients']);
}
exit;

?>
