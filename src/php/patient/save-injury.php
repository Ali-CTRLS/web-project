<?php
/**
 * save-injury.php
 * Saves a patient's injury report.
 */

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();
require_login('patient');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/patient-dashboard.html");
    exit;
}

$user = get_user();
$patient_id = $user['id'];

// قراءة البيانات من الفورم
$fullname = trim($_POST['fullname'] ?? '');
$injury_date = $_POST['date'] ?? '';
$location = trim($_POST['location'] ?? '');
$severity = $_POST['severity'] ?? '';
$cause = trim($_POST['cause'] ?? '');
$description = trim($_POST['description'] ?? '');
$medications = trim($_POST['medications'] ?? '');
$symptoms = trim($_POST['symptoms'] ?? '');

// التحقق من البيانات المطلوبة
if (empty($fullname) || empty($injury_date) || empty($location) || empty($severity) || empty($cause) || empty($description) || empty($symptoms)) {
    header("Location: ../../pages/report-injury.html?error=missing_fields");
    exit;
}

try {
    $pdo = db_connect();

    $sql = "INSERT INTO injuries (
        patient_id, full_name, injury_date, location, 
        severity, cause, description, medications, symptoms
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        $patient_id, $fullname, $injury_date, $location,
        $severity, $cause, $description, $medications, $symptoms
    ]);

    if ($success) {
        header("Location: ../../pages/patient-dashboard.html?success=injury_saved");
    } else {
        header("Location: ../../pages/report-injury.html?error=save_failed");
    }
} catch (PDOException $e) {
    header("Location: ../../pages/report-injury.html?error=db_error");
}
exit;
?>