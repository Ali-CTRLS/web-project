<?php
/**
 * save-appointment.php
 * Saves a patient's appointment booking request.
 */

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

header('Content-Type: application/json');

ensure_session_started();
require_login('patient');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$user = get_user();
$patient_id = $user['id'];

$doctor_name = trim($_POST['doctor_name'] ?? '');
$preferred_date = $_POST['preferred_date'] ?? '';
$preferred_time = $_POST['preferred_time'] ?? '';
$reason = trim($_POST['reason'] ?? '');

if (empty($doctor_name) || empty($preferred_date) || empty($preferred_time) || empty($reason)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $pdo = db_connect();
    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_name, preferred_date, preferred_time, reason, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$patient_id, $doctor_name, $preferred_date, $preferred_time, $reason]);

    echo json_encode(['success' => true, 'message' => 'Appointment booked successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to save appointment: ' . $e->getMessage()]);
}
exit;
?>