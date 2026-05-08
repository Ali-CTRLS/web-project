# Team Distribution & Sequence Plan (5-Hour Deadline)

To meet the deadline, we will use a **Foundation-First** approach. Dev 1 will build the engine, and Dev 2 will build the feature modules on top of it.

## The Sequence
1.  **Foundation (Shared)**: `config.php`, `db.php`, `session.php`.
2.  **Authentication**: `register.php`, `login.php`.
3.  **Features (Parallel)**: Injury Reporting & Appointment Management.

---

## Developer 1: The Architect (Infrastructure & Auth)
**Goal**: Get the system running so Dev 2 can save data.

| Time | Task | Folder/File |
| :--- | :--- | :--- |
| **0:00 - 0:45** | **Core Setup** | `php/config.php`, `php/db.php` |
| **0:45 - 1:30** | **Session & Auth Logic** | `php/session.php`, `php/auth/register.php` |
| **1:30 - 2:30** | **Secure Access** | `php/auth/login.php`, `php/auth/logout.php` |
| **2:30 - 4:00** | **Patient Features** | `php/patient/save-injury.php` |
| **4:00 - 5:00** | **Integration & QA** | Testing full flow with Dev 2. |

## Developer 2: The Feature Lead (API & Business Logic)
**Goal**: Build the data-heavy doctor features and ensure the UI connects to the backend.

| Time | Task | Key Deliverables |
| :--- | :--- | :--- |
| **0:00 - 0:45** | **Frontend Audit** | Sync form `name` attributes in HTML with DB columns; add `action` URLs. |
| **0:45 - 1:30** | **Data Seeding** | Use DB Browser or SQL to insert test users (patient/doctor) and appointments. |
| **1:30 - 3:00** | **Doctor Dashboard API** | Build `appointments.php` to fetch and return JSON for the dashboard table. |
| **3:00 - 4:15** | **Appointment Actions** | Build `update-appointment.php` to handle AJAX status changes (Confirm/Cancel). |
| **4:15 - 5:00** | **UI Connection** | Add basic `fetch()` scripts to the HTML pages to display the real data. |

## Developer 2: Technical Strategy
> [!TIP]
> **Don't wait for Dev 1.** You can build the Doctor API logic using an array of dummy data inside the PHP file first. Once Dev 1 finishes `db.php`, you just replace the array with a `db_query()` call.

### Dev 2 Implementation Focus:
1.  **JSON First**: All doctor-facing PHP files should use `header('Content-Type: application/json');`. This allows the dashboard to update without refreshing.
2.  **State Management**: For "Confirm/Cancel" actions, ensure you return the *new* state in the JSON response so the UI can update the button color/text immediately.
3.  **Data Integrity**: When seeding test data, make sure every appointment has a valid `patient_id` that exists in your `users` table.

---

## Critical Handoff Point
> [!IMPORTANT]
> **Dev 1 must finish `db.php` and `session.php` within the first 60-90 minutes.** 
> Dev 2 cannot write or read data from the database until `db_connect()` and `db_init()` are functional.

## Communication Rule
- Dev 2 should use **temporary static JSON files** if Dev 1 is delayed on the DB setup, then swap to the real API later.
