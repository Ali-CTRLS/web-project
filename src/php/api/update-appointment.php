<?php
/**
 * API: update-appointment.php
 * Handles doctor actions (confirm/cancel appointments).
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();
$user = get_user();

if (!$user || $user['role'] !== 'doctor') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Forbidden: Doctor access only']);
    exit;
}

try {
    $pdo = db_connect();

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    $appointmentId = $input['appointmentId'] ?? null;
    $action = $input['action'] ?? null; // 'confirm' or 'cancel'

    if (!$appointmentId || !in_array($action, ['confirm', 'cancel'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }

    // Update appointment status
    $status = $action === 'confirm' ? 'confirmed' : 'cancelled';
    $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->execute([$status, $appointmentId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Appointment updated successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Appointment not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to update appointment']);
}
?>