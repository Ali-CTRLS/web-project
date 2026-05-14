<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

header('Content-Type: application/json');

ensure_session_started();
$user = get_user();
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

// Get report ID from GET parameter
$reportId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$reportId) {
    http_response_code(400);
    echo json_encode(['error' => 'Report ID is required']);
    exit;
}

try {
    $pdo = db_connect();

    // Fetch medical report by ID
    $sql = "
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
            (SELECT name FROM users WHERE id = patient_id) as patient_name
        FROM reports
        WHERE id = ?
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$reportId]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$report) {
        http_response_code(404);
        echo json_encode(['error' => 'Report not found']);
        exit;
    }

    // Fetch patient name if not already included
    if (!isset($report['patient_name']) && $report['patient_id']) {
        $patientSql = "SELECT name FROM users WHERE id = ?";
        $patientStmt = $pdo->prepare($patientSql);
        $patientStmt->execute([$report['patient_id']]);
        $patient = $patientStmt->fetch(PDO::FETCH_ASSOC);
        if ($patient) {
            $report['patient_name'] = $patient['name'];
        }
    }

    // Authorization: allow doctors or the patient who owns the report
    $currentRole = $user['role'] ?? '';
    $currentUserId = $user['id'] ?? null;
    if ($currentRole !== 'doctor' && $currentUserId != $report['patient_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode($report);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch report']);
    error_log($e->getMessage());
    exit;
}
?>
