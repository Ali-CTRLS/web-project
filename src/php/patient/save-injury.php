<?php
/**
 * save-injury.php
 * Saves a patient's injury report.
 */

require_once '../db.php';
require_once '../session.php';

// Only patients can access this
require_login('patient');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /src/pages/patient-dashboard.html");
    exit;
}

$user = get_current_user_data();
$patient_id = $user['id'];

// Read inputs (matching form field names)
$fullname = trim($_POST['fullname'] ?? '');
$injury_date = $_POST['date'] ?? '';
$location = trim($_POST['location'] ?? '');
$severity = $_POST['severity'] ?? '';
$cause = trim($_POST['cause'] ?? '');
$description = trim($_POST['description'] ?? '');
$medications = trim($_POST['medications'] ?? '');
$symptoms = trim($_POST['symptoms'] ?? '');

// Basic validation for required fields
if (empty($fullname) || empty($injury_date) || empty($location) || empty($severity) || empty($cause) || empty($description) || empty($symptoms)) {
    header("Location: /src/pages/injury-form.html?error=missing_fields");
    exit;
}

$pdo = db_connect();

$sql = "INSERT INTO injuries (
    patient_id, full_name, injury_date, location, 
    severity, cause, description, medications, symptoms
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$success = db_execute($pdo, $sql, [
    $patient_id, $fullname, $injury_date, $location,
    $severity, $cause, $description, $medications, $symptoms
]);

if ($success) {
    header("Location: /src/pages/patient-dashboard.html?success=injury_saved");
} else {
    header("Location: /src/pages/injury-form.html?error=save_failed");
}
exit;
?>
