<?php
// update-appointment.php - Doctor appointment status update endpoint

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

header('Content-Type: application/json');

try {
	ensure_session_started();
	
	// Check authentication without redirecting
	$user = get_user();
	if (!$user) {
		http_response_code(401);
		echo json_encode(['success' => false, 'error' => 'Your session has expired. Please log in again.']);
		exit;
	}
	
	$userRole = $user['role'] ?? 'none';
	if (strtolower($userRole) !== 'doctor') {
		http_response_code(403);
		echo json_encode([
			'success' => false, 
			'error' => 'Doctor access required. Your current role is: ' . $userRole . '. Please log out and log in as a doctor.'
		]);
		exit;
	}

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
	
} catch (PDOException $e) {
	http_response_code(500);
	echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Throwable $e) {
	http_response_code(500);
	echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
}
exit;
?>
