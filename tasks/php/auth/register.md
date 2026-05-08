# Task: php/auth/register.php

## Goal
Register a new patient account and start a session.

## Senior Engineer's Advice
> [!TIP]
> Always check if a user already exists before trying to insert. Use a `try-catch` block for database operations to handle unique constraint violations gracefully.

## Detailed Steps
1. **Bootstrap**: Include `config.php`, `db.php`, and `session.php`. Call `ensure_session_started()`.
2. **Validation**:
   - Ensure `name`, `email`, and `password` are present.
   - Check email format using `filter_var()`.
3. **Check Duplicate**:
   - Query: `SELECT id FROM users WHERE email = ?`
   - If found, redirect to `register.html?error=exists`.
4. **Insert User**:
   ```sql
   INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'patient')
   ```
5. **Post-Registration**:
   - Get the last inserted ID: `$user_id = $pdo->lastInsertId();`.
   - Fetch the new user or construct a user array.
   - Call `set_user_session($user)`.
   - Redirect to `/src/pages/patient-dashboard.html`.

## Done When
- A new user can fill out the register form and is immediately redirected to their dashboard as a logged-in user.
