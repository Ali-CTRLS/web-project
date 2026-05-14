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
    header("Location: ../../pages/report-form.php");
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
    header("Location: ../../pages/report-form.php?error=missing_fields");
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
        // try to attach doctor info to the report record
        $reportId = $pdo->lastInsertId();
        $currentUser = get_user();
        $doctorId = $currentUser['id'] ?? null;
        $doctorName = $currentUser['name'] ?? null;
        $updateSql = "UPDATE reports SET doctor_id = ?, doctor_name = ? WHERE id = ?";

        if ($reportId && ($doctorId || $doctorName)) {
            try {
                // First attempt to update in case columns exist
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute([$doctorId, $doctorName, $reportId]);
            } catch (PDOException $e) {
                // If columns don't exist, try to add them (SQLite supports ADD COLUMN)
                try {
                    $pdo->exec("ALTER TABLE reports ADD COLUMN doctor_id INTEGER");
                    $pdo->exec("ALTER TABLE reports ADD COLUMN doctor_name TEXT");
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([$doctorId, $doctorName, $reportId]);
                } catch (Exception $inner) {
                    // ignore if alter fails
                }
            }
        }

        // redirect doctor on success
        header("Location: ../../pages/doctor-dashboard.php?success=report_saved");
    } else {
        header("Location: ../../pages/report-form.php?error=save_failed");
    }
} catch (PDOException $e) {
    // يفضل تسجيل الخطأ في السيرفر للمتابعة
    error_log("Database Error in save-report: " . $e->getMessage());
    header("Location: ../../pages/report-form.php?error=db");
}
exit;
?>