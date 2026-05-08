# Task: php/auth/logout.php

## Goal
Log out the current user and clear session data.

## Senior Engineer's Advice
> [!IMPORTANT]
> To truly log a user out, you must clear the `$_SESSION` array, destroy the session data on the server, and expire the session cookie in the user's browser.

## Detailed Steps
1. **Bootstrap**: Include `session.php` and call `ensure_session_started()`.
2. **Clear Session**:
   ```php
   $_SESSION = []; // Clear the array
   ```
3. **Destroy Cookie**:
   ```php
   if (ini_get("session.use_cookies")) {
       $params = session_get_cookie_params();
       setcookie(session_name(), '', time() - 42000,
           $params["path"], $params["domain"],
           $params["secure"], $params["httponly"]
       );
   }
   ```
4. **Destroy Session**: Call `session_destroy();`.
5. **Redirect**: `header("Location: /src/pages/login.html"); exit;`.

## Done When
- Visiting this script immediately clears the user's login state and sends them back to the login page.
