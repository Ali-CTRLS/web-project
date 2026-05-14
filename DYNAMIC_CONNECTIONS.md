# MedCare Application - Dynamic Connection Architecture

## Overview
The application now has a fully integrated dynamic system where PHP backend communicates with HTML frontend through:
1. **Form Submissions** - Direct POST/GET to handlers
2. **API Endpoints** - JSON responses for AJAX calls
3. **Session Management** - Persistent user authentication
4. **Database Integration** - SQLite with real-time data queries

---

## Connection Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    HTML PAGES                               │
│  (login, register, forms, dashboards)                       │
└──────────────────────┬──────────────────────────────────────┘
                       │
        ┌──────────────┼──────────────┐
        │              │              │
        ↓              ↓              ↓
   ┌────────────┐ ┌────────────┐ ┌──────────┐
   │Auth Handler│ │   Forms    │ │   API    │
   │  (PHP)     │ │ Handlers   │ │Endpoints │
   └────────────┘ └────────────┘ └──────────┘
        │              │              │
        │ login        │ save         │ fetch
        │ register     │ injury       │ appts
        │ logout       │ appt         │ stats
        │              │ report       │ data
        │
        └──────────────┬──────────────┘
                       │
                    SESSION
                   (Persistent)
                       │
                       ↓
              ┌─────────────────┐
              │   Database      │
              │   (SQLite)      │
              ├─────────────────┤
              │ - users         │
              │ - appointments  │
              │ - injuries      │
              │ - reports       │
              └─────────────────┘
```

---

## File Connections

### Authentication Flow
```
login.html (form)
    ↓
src/php/auth/login.php (validates credentials)
    ↓
session.php (set_user_session)
    ↓
Redirect to dashboard

register.html (form)
    ↓
src/php/auth/register.php (creates new user)
    ↓
session.php (set_user_session)
    ↓
Redirect to patient dashboard

logout.php
    ↓
session.php (logout_user)
    ↓
Redirect to login
```

### Patient Forms
```
injury-form.html
    ↓
src/php/patient/save-injury.php
    ↓
db.php (db_connect, PDO query)
    ↓
injuries table
    ↓
patient-dashboard.html

appointments.html
    ↓
src/php/patient/save-appointment.php
    ↓
db.php (db_connect, PDO query)
    ↓
appointments table
    ↓
patient-dashboard.html
```

### Doctor Forms
```
report-form.html
    ↓
src/php/doctor/save-report.php
    ↓
db.php (db_connect, PDO query)
    ↓
reports table
    ↓
doctor-dashboard.html

doctor-dashboard.html
    ↓
src/php/doctor/update-appointment.php
    ↓
db.php (db_connect, PDO query)
    ↓
appointments table
    ↓
doctor-dashboard.html
```

### API Endpoints (AJAX)
```
patient-dashboard.html (on load)
    ↓
src/php/api/get-user-data.php
    ↓ (returns JSON)
Update greeting with user name

patient-dashboard.html (on load)
    ↓
src/php/api/get-appointments.php
    ↓ (returns JSON)
Load appointments table

patient-dashboard.html (on load)
    ↓
src/php/api/get-injuries.php
    ↓ (returns JSON)
Load recent injury report

doctor-dashboard.html (on load)
    ↓
src/php/api/get-doctor-appointments.php
    ↓ (returns JSON)
Load appointments table

doctor-dashboard.html (on load)
    ↓
src/php/api/get-reports.php
    ↓ (returns JSON)
Load recent reports
```

---

## Session Management
All files use `session.php` which provides:
- `ensure_session_started()` - Safely starts PHP session
- `get_user()` - Returns current user from session (or demo user)
- `set_user_session($user)` - Stores user in session after login
- `require_login($role)` - Guards endpoints by role
- `logout_user()` - Clears session on logout

---

## Database Connections
All queries go through `db.php`:
- `db_connect()` - Returns PDO connection to SQLite
- `db_query($pdo, $sql, $params)` - Execute SELECT queries
- `db_init($pdo)` - Creates tables if they don't exist

Tables:
- **users** - id, name, email, password, role
- **appointments** - id, patient_id, doctor_name, date, time, reason, status
- **injuries** - id, patient_id, full_name, date, location, severity, cause, description, medications, symptoms
- **reports** - id, patient_id, report_type, date, findings, diagnosis, treatment_plan, medications, followup, notes

---

## Path Configuration
All paths use absolute URLs for consistency:
- Form actions: `/src/php/auth/...` or `/src/php/patient/...` or `/src/php/doctor/...`
- API calls: `/src/php/api/...`
- Redirects: `/src/pages/...`
- Static assets: `/src/css/...`, `/src/js/...`

---

## AJAX Data Loading
`src/js/main.js` provides dynamic dashboard loading:
- `loadDashboardData()` - Fetches from API endpoints
- Updates page content with real database data
- Runs on page load for dashboard pages

---

## Security Features
- Session-based authentication
- Role-based access control (require_login)
- PDO prepared statements (SQL injection prevention)
- Error logging for debugging
- CSRF protection via session

---

## Testing Connections
1. **Register** via register.html → Creates user in DB
2. **Login** via login.html → Sets session, redirects to dashboard
3. **Submit injury form** → Saves to injuries table → Shows on dashboard
4. **Book appointment** → Saves to appointments table → Shows on dashboard
5. **Doctor creates report** → Saves to reports table → Shows on doctor dashboard
6. **Logout** → Clears session → Redirects to login

All connections are now fully dynamic and connected!
