<?php
/**
 * create-doctor.php
 * Creates a doctor user in the database
 */

require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

try {
    $pdo = db_connect();
    
    // Check if doctor already exists
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $check->execute(['doctor@example.com']);
    $existing = $check->fetch();
    
    if ($existing) {
        echo json_encode([
            'success' => false,
            'message' => 'Doctor user already exists with email: doctor@example.com'
        ]);
    } else {
        // Create doctor user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([
            'Dr. Ahmed Hassan',
            'doctor@example.com',
            password_hash('password123', PASSWORD_DEFAULT),
            'doctor'
        ]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Doctor user created successfully!',
                'login_details' => [
                    'email' => 'doctor@example.com',
                    'password' => 'password123'
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create doctor user'
            ]);
        }
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
