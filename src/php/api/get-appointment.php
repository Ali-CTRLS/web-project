<?php
session_start();
require_once './db.php';

// Get appointment ID from GET parameter
$appointmentId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$appointmentId) {
    http_response_code(400);
    echo json_encode(['error' => 'Appointment ID is required']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->connect();

    // Fetch appointment by ID
    // Note: In a multi-user system, you'd add permission checks to ensure the user
    // has access to this appointment (is the doctor or patient assigned to it)
    $sql = "
        SELECT id, patient_id, doctor_name, preferred_date, preferred_time, 
               reason, status, notes, created_at
        FROM appointments 
        WHERE id = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$appointmentId]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        http_response_code(404);
        echo json_encode(['error' => 'Appointment not found']);
        exit;
    }

    // If patient_id is available, fetch patient name for additional detail
    if ($appointment['patient_id']) {
        $patientSql = "SELECT id, name FROM users WHERE id = ?";
        $patientStmt = $conn->prepare($patientSql);
        $patientStmt->execute([$appointment['patient_id']]);
        $patient = $patientStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($patient && !$appointment['patient_name']) {
            $appointment['patient_name'] = $patient['name'];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($appointment);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch appointment']);
    error_log($e->getMessage());
    exit;
}
?>
