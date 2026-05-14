<?php
/**
 * API: get-doctor-appointments.php
 * Returns all appointments for doctor to manage as JSON
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();
require_login('doctor');

try {
    $pdo = db_connect();
    
    $stmt = $pdo->prepare("SELECT a.id, a.patient_id, a.doctor_name, a.preferred_date, a.preferred_time, a.reason, a.status, u.name as patient_name
                          FROM appointments a
                          LEFT JOIN users u ON u.id = a.patient_id
                          ORDER BY a.preferred_date ASC, a.preferred_time ASC
                          LIMIT 20");
    $stmt->execute([]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'count' => count($appointments),
        'data' => $appointments
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch appointments'
    ]);
}
?>
