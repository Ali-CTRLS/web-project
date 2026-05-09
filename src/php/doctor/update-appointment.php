<?php
// update-appointment.php - Doctor appointment status update endpoint

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

header('Content-Type: application/json');

require_login('doctor');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['success' => false, 'error' => 'Method not allowed']);
	exit;
}

$appointment_id = filter_input(INPUT_POST, 'appointment_id', FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';

if (!$appointment_id || !in_array($action, ['confirm', 'cancel'], true)) {
	http_response_code(400);
	echo json_encode(['success' => false, 'error' => 'Invalid appointment ID or action']);
	exit;
}

$status = $action === 'confirm' ? 'confirmed' : 'canceled';

try {
	$pdo = db_connect();

	$check = $pdo->prepare('SELECT id FROM appointments WHERE id = ? LIMIT 1');
	$check->execute([$appointment_id]);
	$appointment = $check->fetch(PDO::FETCH_ASSOC);

	if (!$appointment) {
		http_response_code(404);
		echo json_encode(['success' => false, 'error' => 'Appointment not found']);
		exit;
	}

	$update = $pdo->prepare('UPDATE appointments SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
	$update->execute([$status, $appointment_id]);

	echo json_encode(['success' => true]);
} catch (Throwable $e) {
	http_response_code(500);
	echo json_encode(['success' => false, 'error' => 'Failed to update appointment']);
}
exit;
?>
