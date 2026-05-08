# Task: php/doctor/appointments.php

## Goal
Return a list of appointments for a doctor so the UI can display them.

## Senior Engineer's Advice
> [!TIP]
> When building APIs for a frontend, always set the correct `Content-Type` header and handle empty result sets by returning an empty array `[]` instead of `null` or an error.

## Detailed Steps
1. **Bootstrap**: Include `config.php`, `db.php`, and `session.php`. Call `require_login('doctor');`.
2. **Handle Filters**:
   ```php
   $status = $_GET['status'] ?? null;
   $date = $_GET['date'] ?? null;
   ```
3. **Build Dynamic Query**:
   ```php
   $sql = "SELECT * FROM appointments WHERE 1=1";
   $params = [];
   if ($status) {
       $sql .= " AND status = ?";
       $params[] = $status;
   }
   if ($date) {
       $sql .= " AND preferred_date = ?";
       $params[] = $date;
   }
   ```
4. **Execute & Response**:
   - Fetch all records using `db_query($pdo, $sql, $params)`.
   - Set Header: `header('Content-Type: application/json');`.
   - Output: `echo json_encode($appointments);`.

## Done When
- Making a GET request to this file returns a valid JSON array of appointments.
