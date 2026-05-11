<?php
/**
 * save-appointment.php
 * Saves a patient's appointment booking request.
 */

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

// التأكد من أن المستخدم "مريض" ومسجل دخول
require_login('patient');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // تصحيح: العودة للخلف مرتين للوصول لـ pages/appointments.html
    header('Location: ../../pages/appointments.html');
    exit;
}

$user = get_user();
$patient_id = $user['id'];

$doctor_name = trim($_POST['doctor_name'] ?? '');
$preferred_date = $_POST['preferred_date'] ?? '';
$preferred_time = $_POST['preferred_time'] ?? '';
$reason = trim($_POST['reason'] ?? '');

if (empty($doctor_name) || empty($preferred_date) || empty($preferred_time) || empty($reason)) {
    header('Location: ../../pages/appointments.html?error=missing_fields');
    exit;
}

try {
    $pdo = db_connect();
    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_name, preferred_date, preferred_time, reason, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$patient_id, $doctor_name, $preferred_date, $preferred_time, $reason]);

    // النجاح: التوجه للوحة تحكم المريض
    header('Location: ../../pages/patient-dashboard.html?success=appointment_booked');
} catch (PDOException $e) {
    // الخطأ: العودة لصفحة الحجز
    header('Location: ../../pages/appointments.html?error=save_failed');
}
exit;
?>