<?php
/**
 * appointments.php
 * API endpoint to list appointments with optional filters.
 * Returns JSON.
 */

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

header('Content-Type: application/json');

require_login('doctor');

try {
    $pdo = db_connect();

    $status = $_GET['status'] ?? null;
    $date = $_GET['date'] ?? null;

    $sql = 'SELECT * FROM appointments WHERE 1=1';
    $params = [];

    if ($status !== null && $status !== '') {
        $sql .= ' AND status = ?';
        $params[] = $status;
    }

    if ($date !== null && $date !== '') {
        $sql .= ' AND preferred_date = ?';
        $params[] = $date;
    }

    $sql .= ' ORDER BY preferred_date ASC, preferred_time ASC';

    $appointments = db_query($pdo, $sql, $params);
    echo json_encode($appointments ?: []);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch appointments']);
}
exit;
?>
