<?php
/**
 * cancel-appointment.php
 * Deletes a patient's appointment.
 */

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

header('Content-Type: application/json');

ensure_session_started();
require_login('patient');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$appointment_id = filter_input(INPUT_POST, 'appointment_id', FILTER_VALIDATE_INT);
if (!$appointment_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid appointment ID']);
    exit;
}

$user = get_user();

try {
    $pdo = db_connect();

    $stmt = $pdo->prepare('DELETE FROM appointments WHERE id = ? AND patient_id = ?');
    $stmt->execute([$appointment_id, $user['id']]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Appointment not found']);
        exit;
    }

    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to cancel appointment']);
}
exit;
?>