<?php
/**
 * API: get-injuries.php
 * Returns patient's injury reports (for patients) or all injuries (for doctors)
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();
$user = get_user();

try {
    $pdo = db_connect();
    
    // Check if user is a patient or doctor
    // Doctors have role='doctor', patients have role='patient'
    $isDoctor = isset($user['role']) && strtolower($user['role']) === 'doctor';
    
    if ($isDoctor) {
        // Doctor: Get all injuries from all patients
        $stmt = $pdo->prepare("
            SELECT i.id, i.patient_id, i.injury_type, i.date_of_injury, 
                   i.severity, i.description, i.treatment_notes, i.created_at,
                   u.name as patient_name
            FROM injuries i
            LEFT JOIN users u ON i.patient_id = u.id
            ORDER BY i.created_at DESC 
            LIMIT 20
        ");
        $stmt->execute();
    } else {
        // Patient: Get only their own injuries
        $stmt = $pdo->prepare("
            SELECT i.id, i.patient_id, i.injury_type, i.date_of_injury, 
                   i.severity, i.description, i.treatment_notes, i.created_at,
                   u.name as patient_name
            FROM injuries i
            LEFT JOIN users u ON i.patient_id = u.id
            WHERE i.patient_id = ? 
            ORDER BY i.date_of_injury DESC 
            LIMIT 10
        ");
        $stmt->execute([$user['id']]);
    }
    
    $injuries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'count' => count($injuries),
        'data' => $injuries
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch injuries',
        'debug' => $e->getMessage()
    ]);
}
?>
