<?php
/**
 * appointments.php
 * API endpoint to list appointments for doctor view.
 * Returns JSON.
 */

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

header('Content-Type: application/json');

try {
    ensure_session_started();
    
    // Check authentication without redirecting
    $user = get_user();
    if (!$user) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Session expired']);
        exit;
    }
    
    $userRole = $user['role'] ?? 'none';
    if (strtolower($userRole) !== 'doctor') {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Doctor access required. Current role: ' . $userRole]);
        exit;
    }

    $pdo = db_connect();

    // Simple query - just get all appointments with patient names
    $sql = "SELECT 
        a.id, 
        a.patient_id, 
        a.doctor_name, 
        a.preferred_date, 
        a.preferred_time, 
        a.reason, 
        a.status, 
        COALESCE(u.name, 'Unknown Patient') as patient_name
    FROM appointments a
    LEFT JOIN users u ON a.patient_id = u.id
    ORDER BY a.preferred_date ASC, a.preferred_time ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $appointments ?: []]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
}
exit;
?>
