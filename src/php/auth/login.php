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
        header("Location: ../../pages/login.html?error=1");
        exit;
    }
    
    try {
        $pdo = db_connect();
        
        $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && $user['password'] === $password) {
            set_user_session($user);
            
            // تصحيح روابط لوحة التحكم: العودة للخلف مرتين للخروج من php/auth
            if ($user['role'] === 'doctor') {
                header("Location: ../../pages/doctor-dashboard.html");
            } else {
                header("Location: ../../pages/patient-dashboard.html");
            }
            exit;
        } else {
            // تصحيح رابط فشل تسجيل الدخول
            header("Location: ../../pages/login.html?error=1");
            exit;
        }
        
    } catch (PDOException $e) {
        header("Location: ../../pages/login.html?error=1");
        exit;
    }
}

// تصحيح رابط الوصول المباشر للملف
header("Location: ../../pages/login.html");
exit;
?>