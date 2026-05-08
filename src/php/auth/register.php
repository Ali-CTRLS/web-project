<?php
// register.php - User Registration Handler

require_once '../config.php';
require_once '../db.php';
require_once '../session.php';

ensure_session_started();

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Input validation
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Check required fields
    if (empty($name) || empty($email) || empty($password)) {
        header("Location: /src/pages/register.html?error=missing");
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /src/pages/register.html?error=invalid_email");
        exit;
    }
    
    try {
        $pdo = db_connect();
        
        // Check if user already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existing_user = $stmt->fetch();
        
        if ($existing_user) {
            header("Location: /src/pages/register.html?error=exists");
            exit;
        }
        
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'patient')");
        $stmt->execute([$name, $email, $password]);
        
        // Get the newly created user
        $user_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Set session and redirect
        set_user_session($user);
        header("Location: /src/pages/patient-dashboard.html");
        exit;
        
    } catch (PDOException $e) {
        // Database error
        header("Location: /src/pages/register.html?error=db");
        exit;
    }
}

// GET request or invalid method
header("Location: /src/pages/register.html");
exit;
?>
