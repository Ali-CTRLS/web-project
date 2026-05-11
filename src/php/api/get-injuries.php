<?php
/**
 * API: get-injuries.php
 * Returns patient's injury reports as JSON
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();
$user = get_user();

try {
    $pdo = db_connect();
    
    $stmt = $pdo->prepare("SELECT id, full_name, injury_date, location, severity, cause, description 
                          FROM injuries 
                          WHERE patient_id = ? 
                          ORDER BY injury_date DESC 
                          LIMIT 10");
    $stmt->execute([$user['id']]);
    $injuries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'count' => count($injuries),
        'data' => $injuries
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch injuries'
    ]);
}
?>
