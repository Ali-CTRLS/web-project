<?php
/**
 * API: get-appointments.php
 * Returns patient's upcoming appointments as JSON
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();
$user = get_user();

try {
    $pdo = db_connect();
    
    $stmt = $pdo->prepare("SELECT id, doctor_name, preferred_date, preferred_time, reason, status 
                          FROM appointments 
                          WHERE patient_id = ? 
                          ORDER BY preferred_date DESC 
                          LIMIT 10");
    $stmt->execute([$user['id']]);
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
