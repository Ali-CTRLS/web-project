# Student Workflow Checklist: Patient Journey

This workflow describes the complete end-to-end journey for a patient using the MedCare system. 

> [!NOTE]
> **Technical Note**: Since XAMPP is currently unavailable, this workflow focuses on the UI navigation and the logic flow between pages.

---

## 🟢 Phase 1: Access & Identity
- [ ] **1. Landing Page**: Open `src/index.html` in your browser. Review the features and click "Get Started" or "Register".
- [ ] **2. Registration**: 
    - Navigate to `src/pages/register.html`.
    - Fill in a test name, email, and password.
    - Submit the form.
    - **Expected**: You should be automatically logged in and redirected to the Patient Dashboard.

## 🟡 Phase 2: Reporting & Clinical Data
- [ ] **3. Patient Dashboard**: Review the welcome message and dashboard cards.
- [ ] **4. Injury Reporting**:
    - Click the link to "Report an Injury" or go directly to `src/pages/injury-form.html`.
    - Fill in all required fields:
        - Full Name
        - Date of Injury
        - Location (Body Part)
        - Severity & Cause
        - Detailed Description & Symptoms
    - Submit the report.
    - **Expected**: Data is saved, and you are redirected back to the dashboard.

## 🔵 Phase 3: Appointments & Management
- [ ] **5. Booking Appointments**:
    - Navigate to `src/pages/appointments.html`.
    - Select a doctor and choose your preferred date and time.
    - Provide a reason for the visit.
    - Submit the booking.
- [ ] **6. Activity Review**:
    - Return to `src/pages/patient-dashboard.html`.
    - **Verify**: Your new appointment should now appear in the "Upcoming Appointments" section.
    - **Verify**: Your recent injury report should appear in the "Recent Activity" feed.

## 🟣 Phase 4: Reports & Analytics
- [ ] **7. View Medical Reports**: 
    - Navigate to `src/pages/report-view.html`.
    - Review the sample clinical data and export options.

---

# Student Workflow Checklist: Doctor Journey

This workflow describes the clinical management side of the system.

## 🟠 Phase 5: Clinical Oversight
- [ ] **9. Doctor Login**:
    - Go to `src/pages/login.html`.
    - Log in using a pre-seeded doctor account (e.g., `doctor@medcare.com`).
    - **Expected**: Redirected to the Doctor Dashboard.
- [ ] **10. Dashboard Review**: 
    - Check the "Total Patients" and "Pending Reports" metric cards.
    - **Verify**: The appointment table loads real data using the `fetch()` connection.
- [ ] **11. Appointment Management**:
    - Locate an appointment in the table.
    - Click **Confirm** or **Cancel**.
    - **Expected**: The status badge updates immediately to "Confirmed" or "Canceled" without a page reload.

## 🔴 Phase 6: Patient Records & Search
- [ ] **12. Review Submissions**:
    - In the "Recent Patient Submissions" section, click "Review" on a new injury report.
- [ ] **13. Patient Search**:
    - Use the search bar at the bottom to look for a patient by name.
- [ ] **14. End Session**:
    - Use the Logout link to return to the login page.
    - **Expected**: Redirected to `login.html`.

---

## 🏆 Final Verification
- [ ] **Database Integrity**: Open `src/data/app.sqlite` and verify that all status changes and new entries are correctly stored in the tables.
- [ ] **Console Check**: Open Browser DevTools (F12) and ensure there are no red JavaScript errors during the flow.
