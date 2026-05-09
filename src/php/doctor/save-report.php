<?php
/**
 * save-report.php
 * Handles medical report creation by doctors.
 */

require_once '../config.php';
require_once '../db.php';
require_once '../session.php';

ensure_session_started();

// Only doctors can create reports
require_login('doctor');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /src/pages/report-form.html");
    exit;
}

// Read inputs
$patient_id = $_POST['patient_id'] ?? '';
$report_type = $_POST['report_type'] ?? '';
$report_date = $_POST['report_date'] ?? '';
$findings = trim($_POST['findings'] ?? '');
$diagnosis = trim($_POST['diagnosis'] ?? '');
$treatment = trim($_POST['treatment'] ?? '');
$medications = trim($_POST['medications'] ?? '');
$followup = trim($_POST['followup'] ?? '');
$notes = trim($_POST['notes'] ?? '');

// Basic validation
if (empty($patient_id) || empty($report_type) || empty($report_date) || empty($diagnosis)) {
    header("Location: /src/pages/report-form.html?error=missing_fields");
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
        header("Location: /src/pages/doctor-dashboard.html?success=report_saved");
    } else {
        header("Location: /src/pages/report-form.html?error=save_failed");
    }
} catch (PDOException $e) {
    header("Location: /src/pages/report-form.html?error=db");
}
exit;
?>
