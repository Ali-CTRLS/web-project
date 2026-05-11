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
        header("Location: ../../pages/register.html?error=missing");
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../../pages/register.html?error=invalid_email");
        exit;
    }
    
    try {
        $pdo = db_connect();
        
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existing_user = $stmt->fetch();
        
        if ($existing_user) {
            header("Location: ../../pages/register.html?error=exists");
            exit;
        }
        
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'patient')");
        $stmt->execute([$name, $email, $password]);
        
        $user_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        set_user_session($user);
        
        // تصحيح: التوجه للوحة تحكم المريض بمسار نسبي
        header("Location: ../../pages/patient-dashboard.html");
        exit;
        
    } catch (PDOException $e) {
        header("Location: ../../pages/register.html?error=db");
        exit;
    }
}

header("Location: ../../pages/register.html");
exit;
?>