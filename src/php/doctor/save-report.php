<?php
/**
 * save-report.php
 * Handles medical report creation by doctors.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();
require_login('doctor');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/report-form.html");
    exit;
}

$patient_id = $_POST['patient_id'] ?? '';
$report_type = $_POST['report_type'] ?? '';
$report_date = $_POST['report_date'] ?? '';
$findings = trim($_POST['findings'] ?? '');
$diagnosis = trim($_POST['diagnosis'] ?? '');
$treatment = trim($_POST['treatment'] ?? '');
$medications = trim($_POST['medications'] ?? '');
$followup = trim($_POST['followup'] ?? '');
$notes = trim($_POST['notes'] ?? '');

if (empty($patient_id) || empty($report_type) || empty($report_date) || empty($diagnosis)) {
    header("Location: ../../pages/report-form.html?error=missing_fields");
    exit;
}

try {
    $pdo = db_connect();

    $sql = "INSERT INTO reports (
        patient_id, report_type, report_date, findings, diagnosis, 
        treatment_plan, medications, followup_instructions, additional_notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        $patient_id, $report_type, $report_date, $findings, $diagnosis,
        $treatment, $medications, $followup, $notes
    ]);

    if ($success) {
        // توجيه للطبيب عند النجاح
        header("Location: ../../pages/doctor-dashboard.html?success=report_saved");
    } else {
        header("Location: ../../pages/report-form.html?error=save_failed");
    }
} catch (PDOException $e) {
    // يفضل تسجيل الخطأ في السيرفر للمتابعة
    error_log("Database Error in save-report: " . $e->getMessage());
    header("Location: ../../pages/report-form.html?error=db");
}
exit;
?>