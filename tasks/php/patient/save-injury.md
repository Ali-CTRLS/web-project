# Task: php/patient/save-injury.php

## Goal
Save a patient injury report to storage.

## Senior Engineer's Advice
> [!TIP]
> Use the `patient_id` from the session (`$_SESSION['user_id']`) to ensure that the report is linked to the correct logged-in user. Never trust the client to provide the `patient_id` in a hidden field if it's already in the session.

## Detailed Steps
1. **Bootstrap**: Include dependencies and call `require_login('patient');`.
2. **Collect Data**: Use `$_POST` to get `fullname`, `injury_date`, `location`, `severity`, `cause`, `description`, `medications`, and `symptoms`.
3. **Get Patient ID**: `$patient_id = $_SESSION['user_id'];`.
4. **Insert Record**:
   ```sql
   INSERT INTO injuries (
       patient_id, full_name, injury_date, location, 
       severity, cause, description, medications, symptoms
   ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
   ```
5. **Redirect**: After successful insertion, redirect to `patient-dashboard.html?success=injury_saved`.

## Done When
- A patient submits the injury form, a new record appears in the `injuries` table with their ID, and they are returned to their dashboard.
