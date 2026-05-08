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
**Goal**: Build the data-heavy doctor features.

| Time | Task | Folder/File |
| :--- | :--- | :--- |
| **0:00 - 0:45** | **Frontend Review** | Audit `src/pages/` to ensure form `name` attributes match. |
| **0:45 - 1:30** | **Data Mocking** | Create dummy data in `app.sqlite` for testing. |
| **1:30 - 3:00** | **Doctor Dashboard API** | `php/doctor/appointments.php` |
| **3:00 - 4:15** | **Appointment Actions** | `php/doctor/update-appointment.php` |
| **4:15 - 5:00** | **Final Debugging** | Fixing JSON issues and UI redirects. |

---

## Critical Handoff Point
> [!IMPORTANT]
> **Dev 1 must finish `db.php` and `session.php` within the first 60-90 minutes.** 
> Dev 2 cannot write or read data from the database until `db_connect()` and `db_init()` are functional.

## Communication Rule
- Dev 2 should use **temporary static JSON files** if Dev 1 is delayed on the DB setup, then swap to the real API later.
