# Task: php/auth/login.php

## Goal
Log in a user (patient or doctor) and create a session.

## Senior Engineer's Advice
> [!IMPORTANT]
> Since we are using plain text passwords for this project, the logic is simpler. However, in a real-world app, you would use `password_verify()`. Always redirect and `exit()` after setting headers to stop script execution.

## Detailed Steps
1. **Bootstrap**:
   ```php
   require_once '../config.php';
   require_once '../db.php';
   require_once '../session.php';
   ensure_session_started();
   ```
2. **Handle POST**: Check `$_SERVER['REQUEST_METHOD'] === 'POST'`.
3. **Input Sanitization**:
   ```php
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $password = $_POST['password']; // In real life, don't sanitize passwords
   ```
4. **Authentication Logic**:
   - Query: `SELECT * FROM users WHERE email = ? LIMIT 1`
   - Compare: `if ($user && $user['password'] === $password)`
5. **Session & Redirect**:
   - Call `set_user_session($user)`.
   - Logic:
     ```php
     if ($user['role'] === 'doctor') {
         header("Location: /src/pages/doctor-dashboard.html");
     } else {
         header("Location: /src/pages/patient-dashboard.html");
     }
     exit;
     ```
6. **Error Handling**: If login fails, redirect to `login.html?error=1`.

## Done When
- A user can log in with their email and password and is taken to the correct dashboard.
