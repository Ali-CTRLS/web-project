<?php
// register.php - User Registration Handler

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // تصحيح: العودة للخلف مرتين للوصول لـ pages/register.html
    if (empty($name) || empty($email) || empty($password)) {
        header("Location: " . BASE_URL . "src/pages/register.php?error=missing");
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: " . BASE_URL . "src/pages/register.php?error=invalid_email");
        exit;
    }
    
    try {
        $pdo = db_connect();
        
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existing_user = $stmt->fetch();
        
        if ($existing_user) {
            header("Location: " . BASE_URL . "src/pages/register.php?error=exists");
            exit;
        }
        
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'patient')");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([$name, $email, $hashed_password]);
        
        $user_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        set_user_session($user);
        
        // تصحيح: التوجه للوحة تحكم المريض بمسار نسبي
        header("Location: " . BASE_URL . "src/pages/patient-dashboard.php");
        exit;
        
    } catch (PDOException $e) {
        header("Location: " . BASE_URL . "src/pages/register.php?error=db");
        exit;
    }
}

header("Location: " . BASE_URL . "src/pages/register.php");
exit;
?>