<?php
/**
 * API: get-reports.php
 * Returns reports - for patients: their own reports, for doctors: all reports they created
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();
$user = get_user();

if (!$user) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'Not authenticated'
    ]);
    exit;
}

try {
    $pdo = db_connect();
    
    // Determine if user is doctor or patient
    $isDoctor = isset($user['role']) && strtolower($user['role']) === 'doctor';
    
    if ($isDoctor) {
        // Doctor: Get all reports they created
        $stmt = $pdo->prepare("
            SELECT
                id,
                patient_id,
                doctor_id,
                COALESCE(doctor_name, (SELECT name FROM users WHERE id = doctor_id), 'Doctor') AS doctor_name,
                report_type,
                report_date,
                findings AS content,
                diagnosis,
                treatment_plan,
                medications,
                followup_instructions,
                additional_notes AS notes,
                status,
                created_at,
                (SELECT name FROM users WHERE id = patient_id) AS patient_name
            FROM reports 
            WHERE doctor_id = ? OR doctor_name LIKE ?
            ORDER BY created_at DESC 
            LIMIT 50
        ");
        $stmt->execute([$user['id'], '%' . ($user['name'] ?? '') . '%']);
    } else {
        // Patient: Get only their own reports
        $stmt = $pdo->prepare("
            SELECT
                id,
                patient_id,
                doctor_id,
                COALESCE(doctor_name, (SELECT name FROM users WHERE id = doctor_id), 'Doctor') AS doctor_name,
                report_type,
                report_date,
                findings AS content,
                diagnosis,
                treatment_plan,
                medications,
                followup_instructions,
                additional_notes AS notes,
                status,
                created_at,
                (SELECT name FROM users WHERE id = patient_id) AS patient_name
            FROM reports 
            WHERE patient_id = ?
            ORDER BY created_at DESC 
            LIMIT 50
        ");
        $stmt->execute([$user['id']]);
    }
    
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Ensure all required fields are present
    foreach ($reports as &$report) {
        if (!isset($report['patient_name']) && isset($report['patient_id'])) {
            $userSql = $pdo->prepare("SELECT name FROM users WHERE id = ?");
            $userSql->execute([$report['patient_id']]);
            $patientUser = $userSql->fetch(PDO::FETCH_ASSOC);
            $report['patient_name'] = $patientUser ? $patientUser['name'] : 'Patient';
        }
        if (!isset($report['doctor_name'])) {
            $report['doctor_name'] = 'Doctor';
        }
    }
    
    echo json_encode([
        'success' => true,
        'count' => count($reports),
        'data' => $reports
    ]);
} catch (Exception $e) {
    error_log('Error in get-reports: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch reports'
    ]);
}
?>
