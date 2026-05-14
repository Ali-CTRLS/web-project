<?php
// login.php - User Authentication Handler

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../session.php';

ensure_session_started();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? ''; 
    
    // تصحيح روابط الخطأ: العودة للخلف للخروج من php/auth ثم الدخول لـ pages
    if (empty($email) || empty($password)) {
        header("Location: " . BASE_URL . "src/pages/login.php?error=1");
        exit;
    }
    
    try {
        $pdo = db_connect();
        
        $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
         if ($user && password_verify($password, $user['password'])) {
            set_user_session($user);
            
            // تصحيح روابط لوحة التحكم: العودة للخلف مرتين للخروج من php/auth
            if ($user['role'] === 'doctor') {
                header("Location: " . BASE_URL . "src/pages/doctor-dashboard.php");
            } else {
                header("Location: " . BASE_URL . "src/pages/patient-dashboard.php");
            }
            exit;
        } else {
            // تصحيح رابط فشل تسجيل الدخول
            header("Location: " . BASE_URL . "src/pages/login.php?error=1");
            exit;
        }
        
    } catch (PDOException $e) {
        header("Location: " . BASE_URL . "src/pages/login.php?error=1");
        exit;
    }
}

// تصحيح رابط الوصول المباشر للملف
header("Location: " . BASE_URL . "src/pages/login.php");
exit;
?>