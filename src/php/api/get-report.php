<?php
session_start();
require_once './db.php';

// Get report ID from GET parameter
$reportId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$reportId) {
    http_response_code(400);
    echo json_encode(['error' => 'Report ID is required']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->connect();

    // Fetch medical report by ID
    // This query assumes a 'reports' or 'medical_reports' table exists
    $sql = "
        SELECT id, patient_id, doctor_name, report_type, content, notes, status, created_at,
               (SELECT name FROM users WHERE id = patient_id) as patient_name
        FROM reports
        WHERE id = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$reportId]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no report found, try alternative table name
    if (!$report) {
        $sql = "
            SELECT id, patient_id, doctor_id, report_type, content, notes, status, created_at
            FROM medical_reports
            WHERE id = ?
            LIMIT 1
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$reportId]);
        $report = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($report && $report['doctor_id']) {
            $doctorSql = "SELECT name FROM users WHERE id = ?";
            $doctorStmt = $conn->prepare($doctorSql);
            $doctorStmt->execute([$report['doctor_id']]);
            $doctor = $doctorStmt->fetch(PDO::FETCH_ASSOC);
            if ($doctor) {
                $report['doctor_name'] = $doctor['name'];
            }
        }
    }

    if (!$report) {
        http_response_code(404);
        echo json_encode(['error' => 'Report not found']);
        exit;
    }

    // Fetch patient name if not already included
    if (!isset($report['patient_name']) && $report['patient_id']) {
        $patientSql = "SELECT name FROM users WHERE id = ?";
        $patientStmt = $conn->prepare($patientSql);
        $patientStmt->execute([$report['patient_id']]);
        $patient = $patientStmt->fetch(PDO::FETCH_ASSOC);
        if ($patient) {
            $report['patient_name'] = $patient['name'];
        }
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
