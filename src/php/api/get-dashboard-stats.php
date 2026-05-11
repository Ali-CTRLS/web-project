<?php
/**
 * API: get-dashboard-stats.php
 * Returns dashboard statistics based on user role
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();
$user = get_user();

try {
    $pdo = db_connect();
    $stats = [];
    
    if ($user['role'] === 'patient') {
        // Patient stats
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM appointments WHERE patient_id = ? AND status = 'pending'");
        $stmt->execute([$user['id']]);
        $pending = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM appointments WHERE patient_id = ? AND status = 'confirmed'");
        $stmt->execute([$user['id']]);
        $confirmed = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM injuries WHERE patient_id = ?");
        $stmt->execute([$user['id']]);
        $injuries = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stats = [
            'pending_appointments' => $pending['count'] ?? 0,
            'confirmed_appointments' => $confirmed['count'] ?? 0,
            'total_injuries' => $injuries['count'] ?? 0
        ];
    } else if ($user['role'] === 'doctor') {
        // Doctor stats
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'");
        $stmt->execute([]);
        $pending = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM appointments WHERE status = 'confirmed'");
        $stmt->execute([]);
        $confirmed = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stats = [
            'pending_appointments' => $pending['count'] ?? 0,
            'confirmed_appointments' => $confirmed['count'] ?? 0
        ];
    }
    
    echo json_encode([
        'success' => true,
        'stats' => $stats
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch stats'
    ]);
}
?>
