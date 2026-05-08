<?php
/**
 * appointments.php
 * API endpoint to list appointments with optional filters.
 * Returns JSON.
 */

require_once '../db.php';
require_once '../session.php';

// Only doctors can access this list
require_login('doctor');

$pdo = db_connect();

// Filters from GET
$status = $_GET['status'] ?? null;
$date = $_GET['date'] ?? null;

$sql = "SELECT * FROM appointments WHERE 1=1";
$params = [];

if ($status) {
    $sql .= " AND status = ?";
    $params[] = $status;
}

if ($date) {
    $sql .= " AND preferred_date = ?";
    $params[] = $date;
}

// Order by date and time
$sql .= " ORDER BY preferred_date ASC, preferred_time ASC";

try {
    $appointments = db_query($pdo, $sql, $params);
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($appointments);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch appointments: ' . $e->getMessage()]);
}
exit;
?>
