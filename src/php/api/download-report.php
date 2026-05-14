<?php
/**
 * download-report.php
 * Returns a downloadable HTML version of a medical report (attachment)
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();
$user = get_user();
if (!$user) {
    http_response_code(401);
    echo 'Not authenticated';
    exit;
}

$reportId = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$reportId) {
    http_response_code(400);
    echo 'Report ID required';
    exit;
}

try {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT id, patient_id, doctor_id, COALESCE(doctor_name, (SELECT name FROM users WHERE id = doctor_id), 'Doctor') AS doctor_name, report_type, report_date, findings AS content, diagnosis, treatment_plan, medications, followup_instructions, additional_notes AS notes, status, created_at, (SELECT name FROM users WHERE id = patient_id) as patient_name FROM reports WHERE id = ? LIMIT 1");
    $stmt->execute([$reportId]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$report) {
        http_response_code(404);
        echo 'Report not found';
        exit;
    }

    // Authorization
    $currentRole = $user['role'] ?? '';
    $currentUserId = $user['id'] ?? null;
    if ($currentRole !== 'doctor' && $currentUserId != $report['patient_id']) {
        http_response_code(403);
        echo 'Access denied';
        exit;
    }

    $patientName = $report['patient_name'] ?? 'Patient';
    $doctorName = $report['doctor_name'] ?? 'Doctor';
    $reportType = $report['report_type'] ?? 'Medical Report';
    $createdAt = $report['created_at'] ?? date('c');
    $content = $report['content'] ?? '';
    $notes = $report['notes'] ?? '';

    $filename = sprintf('report-%d-%s.html', $reportId, preg_replace('/[^a-z0-9\-]/i', '-', strtolower($patientName)));

    $html = "<!doctype html>\n<html><head><meta charset=\"utf-8\"><title>Medical Report #{$reportId}</title></head><body>";
    $html .= "<h1>" . htmlspecialchars($reportType) . "</h1>";
    $html .= "<p><strong>Patient:</strong> " . htmlspecialchars($patientName) . " (ID: " . htmlspecialchars($report['patient_id']) . ")</p>";
    $html .= "<p><strong>Doctor:</strong> " . htmlspecialchars($doctorName) . "</p>";
    $html .= "<p><strong>Created:</strong> " . htmlspecialchars($createdAt) . "</p>";
    $html .= "<hr>";
    $html .= "<h2>Report</h2><div>" . nl2br(htmlspecialchars($content)) . "</div>";
    $html .= "<h3>Notes</h3><div>" . nl2br(htmlspecialchars($notes)) . "</div>";
    $html .= "</body></html>";

    header('Content-Type: text/html; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
    header('Content-Length: ' . strlen($html));
    echo $html;
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo 'Error generating download';
    error_log($e->getMessage());
    exit;
}
?>