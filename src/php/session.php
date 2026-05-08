<?php
// session.php - Simple Session Helper

function ensure_session() {
    if (session_status() === PHP_SESSION_NONE) session_start();
}

function set_user($user) {
    ensure_session();
    $_SESSION['user'] = $user;
}

function get_user() {
    ensure_session();
    return $_SESSION['user'] ?? null;
}

function require_login($role = null) {
    $user = get_user();
    if (!$user) {
        header("Location: /src/pages/login.html");
        exit;
    }
    if ($role && $user['role'] !== $role) {
        header("Location: /src/pages/login.html?error=unauthorized");
        exit;
    }
}

function logout() {
    ensure_session();
    session_destroy();
    header("Location: /src/pages/login.html");
    exit;
}
?>
