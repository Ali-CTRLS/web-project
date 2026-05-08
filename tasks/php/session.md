# Task: php/session.php

## Goal
Centralize session management and authentication guards.

## Senior Engineer's Advice
> [!CAUTION]
> Always call `session_start()` at the very beginning of your script, before any output (HTML or whitespace). Use a wrapper function to prevent "headers already sent" errors.

## Detailed Steps
1. **Start Session Securely**:
   ```php
   function ensure_session_started() {
       if (session_status() === PHP_SESSION_NONE) {
           session_start();
       }
   }
   ```
2. **Authentication Guard**: Create `require_login($required_role = null)`.
   - Check if `$_SESSION['user_id']` is set.
   - If not, use `header("Location: /src/pages/login.html"); exit;`.
   - If a role is required but doesn't match `$_SESSION['role']`, redirect to a "permission denied" or home page.
3. **Session Helpers**:
   - `set_user_session($user)`: Stores `id`, `name`, `role` in `$_SESSION`.
   - `is_logged_in()`: Returns true if user data exists in session.
   - `logout_user()`: Unsets `$_SESSION`, destroys the session, and clears the cookie.

## Done When
- Protecting a page is as simple as calling `require_login('doctor');` at the top of the file.
