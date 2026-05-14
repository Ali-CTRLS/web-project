<?php
// session.php - Session Management & Authentication Guards

require_once __DIR__ . '/config.php';

function ensure_session_started() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function set_user_session(array $user) {
    ensure_session_started();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
}

function get_user() {
    ensure_session_started();
    
    // إذا لم يكن هناك جلسة، نعيد null بدلاً من بيانات ديمو وهمية
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['name'],
        'email' => $_SESSION['email'],
        'role' => $_SESSION['role']
    ];
}

function is_logged_in() {
    return get_user() !== null;
}

/**
 * حارس البوابة (Authentication Guard)
 * تم تفعيله الآن ليقوم بالتوجيه الحقيقي عند محاولة دخول صفحة محمية
 */
function require_login($required_role = null) {
    $user = get_user();
    
    // 1. إذا لم يكن مسجل دخول، وجهه لصفحة تسجيل الدخول
    if (!$user) {
        header("Location: " . BASE_URL . "src/pages/login.php");
        exit;
    }
    
    // 2. إذا كان مسجل دخول ولكن يحاول دخول صفحة ليست من صلاحياته (مثلاً مريض يحاول دخول صفحة طبيب)
    if ($required_role && $user['role'] !== $required_role) {
        if ($user['role'] === 'doctor') {
            header("Location: " . BASE_URL . "src/pages/doctor-dashboard.php");
        } else {
            header("Location: " . BASE_URL . "src/pages/patient-dashboard.php");
        }
        exit;
    }
}

function logout_user() {
    ensure_session_started();
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
?>