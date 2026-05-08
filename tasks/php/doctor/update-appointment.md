# Task: php/doctor/update-appointment.php

## Goal
Confirm or cancel an appointment.

## Senior Engineer's Advice
> [!IMPORTANT]
> Always validate that the provided ID exists and the user has permission to modify it. Use `updated_at = CURRENT_TIMESTAMP` to keep track of changes.

## Detailed Steps
1. **Bootstrap**: Include dependencies and call `require_login('doctor');`.
2. **Validate Input**:
   - Ensure `appointment_id` is an integer.
   - Ensure `action` is either 'confirm' or 'cancel'.
3. **Map Status**:
   - If 'confirm' -> `status = 'confirmed'`
   - If 'cancel' -> `status = 'canceled'`
4. **Update Database**:
   ```sql
   UPDATE appointments 
   SET status = ?, updated_at = CURRENT_TIMESTAMP 
   WHERE id = ?
   ```
5. **Return Response**:
   ```php
   header('Content-Type: application/json');
   echo json_encode(['success' => true]);
   exit;
   ```

## Done When
- The doctor can click a button on the dashboard, and the appointment status is updated in the database without a full page reload.
