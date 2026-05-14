<?php
/**
 * reschedule-appointment.php
 * Updates a patient's appointment date and time.
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
$preferred_date = trim($_POST['preferred_date'] ?? '');
$preferred_time = trim($_POST['preferred_time'] ?? '');

if (!$appointment_id || $preferred_date === '' || $preferred_time === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing appointment details']);
    exit;
}

$user = get_user();

try {
    $pdo = db_connect();

    $stmt = $pdo->prepare('UPDATE appointments SET preferred_date = ?, preferred_time = ?, status = ? WHERE id = ? AND patient_id = ?');
    $stmt->execute([$preferred_date, $preferred_time, 'pending', $appointment_id, $user['id']]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Appointment not found']);
        exit;
    }

    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to reschedule appointment']);
}
exit;
?>