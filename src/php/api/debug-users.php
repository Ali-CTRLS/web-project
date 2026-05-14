<?php
/**
 * debug-users.php
 * Shows all users in the database and their roles
 * DANGER: Only for debugging! Remove before production.
 */

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

try {
    $pdo = db_connect();
    
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users ORDER BY id");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'user_count' => count($users),
        'users' => $users,
        'db_path' => SQLITE_PATH
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
