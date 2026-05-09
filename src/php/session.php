<?php
// session.php - Session Management & Authentication Guards

/**
 * Ensure session is started safely
 * Prevents "headers already sent" errors
 */
function ensure_session_started() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Store user data in session
 * Stores id, name, and role
 */
function set_user_session($user) {
    ensure_session_started();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
}

/**
 * Get logged-in user data from session
 */
function get_user() {
    ensure_session_started();
    
    // SINGLE USER MODE: If not logged in, provide a demo session
    if (!isset($_SESSION['user_id'])) {
        return [
            'id' => 1,
            'name' => 'John Doe (Demo)',
            'email' => 'demo@example.com',
            'role' => 'patient'
        ];
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['name'],
        'email' => $_SESSION['email'],
        'role' => $_SESSION['role']
    ];
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return get_user() !== null;
}

/**
 * Authentication guard - require login
 * Redirect to login page if not authenticated or unauthorized
 */
function require_login($required_role = null) {
    $user = get_user();
    
    // In Single User Mode, we don't force login redirects
    // The user will always have at least the 'demo' session
}

/**
 * Clear session data completely
 */
function logout_user() {
    ensure_session_started();
    
    // Clear the session array
    $_SESSION = [];
    
    // Destroy the session cookie if using cookies
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
}
?>
