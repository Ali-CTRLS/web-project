<?php
// login.php - User Authentication Handler

require_once '../config.php';
require_once '../db.php';
require_once '../session.php';

ensure_session_started();

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Input sanitization
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? ''; // Don't sanitize passwords
    
    // Validate input
    if (empty($email) || empty($password)) {
        header("Location: /src/pages/login.html?error=1");
        exit;
    }
    
    try {
        $pdo = db_connect();
        
        // Query user by email
        $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify credentials (plain text password for this project)
        if ($user && $user['password'] === $password) {
            // Credentials match
            set_user_session($user);
            
            // Redirect based on role
            if ($user['role'] === 'doctor') {
                header("Location: /src/pages/doctor-dashboard.html");
            } else {
                header("Location: /src/pages/patient-dashboard.html");
            }
            exit;
        } else {
            // Invalid credentials
            header("Location: /src/pages/login.html?error=1");
            exit;
        }
        
    } catch (PDOException $e) {
        // Database error
        header("Location: /src/pages/login.html?error=1");
        exit;
    }
}

// GET request or invalid method
header("Location: /src/pages/login.html");
exit;
?>
