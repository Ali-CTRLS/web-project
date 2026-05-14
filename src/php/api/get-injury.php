<?php
session_start();
require_once './db.php';

// Get injury ID from GET parameter
$injuryId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$injuryId) {
    http_response_code(400);
    echo json_encode(['error' => 'Injury ID is required']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->connect();

    // Fetch injury by ID
    $sql = "
        SELECT i.id, i.patient_id, i.injury_type, i.date_of_injury, 
               i.severity, i.description, i.treatment_notes, i.created_at,
               u.name as patient_name
        FROM injuries i
        LEFT JOIN users u ON i.patient_id = u.id
        WHERE i.id = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$injuryId]);
    $injury = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$injury) {
        http_response_code(404);
        echo json_encode(['error' => 'Injury report not found']);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode($injury);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch injury report']);
    error_log($e->getMessage());
    exit;
}
?>
