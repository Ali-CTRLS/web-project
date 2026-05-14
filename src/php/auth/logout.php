<?php
// logout.php - User Logout Handler

require_once '../session.php';

ensure_session_started();

// Clear session data
$_SESSION = [];

// Destroy session cookie if using cookies
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
header("Location: ../../pages/login.html");
exit;
?>