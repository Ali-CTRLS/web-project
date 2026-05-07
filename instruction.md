👥 TASK SPLIT (2 CS STUDENTS)

### Student A (Frontend + Layout)

- Create all HTML pages
- Build shared CSS (white + blue theme)
- Add navbar + footer
- Create basic form layouts

**Keyword:** `FRONTEND_MINI`

### Milestones (Frontend)

- M1: Skeleton pages (Home, Login, Register, Patient Dashboard, Doctor Dashboard, Injury Form, Appointments, Report Form, Report View)
	- Keyword: `FE_PAGES_SKELETON_ALL`
	- Estimated time: 6 hours (deadline within 24 hours)
- M2: Shared layout (navbar + footer + responsive base)
	- Keyword: `FE_LAYOUT_NAV_FOOTER_BASE`
	- Estimated time: 4 hours (deadline within 24 hours)
- M3: Shared CSS theme (white + blue)
	- Keyword: `FE_THEME_WHITE_BLUE_CSS`
	- Estimated time: 3 hours (deadline within 24 hours)
- M4: Form layouts (Login, Register, Injury Form, Report Form)
	- Keyword: `FE_FORMS_LAYOUTS_CORE`
	- Estimated time: 3 hours (deadline within 24 hours)

### Student B (PHP + Data)

- Setup SQLite (or JSON) data storage
- Implement login/register (patient only)
- Implement patient injury form save
- Implement appointment list + doctor confirm/cancel

**Keyword:** `BACKEND_MINI`

### Milestones (Backend)

- M1: Data storage setup (SQLite or JSON, minimal fields)
	- Keyword: `BE_STORAGE_SQLITE_OR_JSON_SETUP`
	- Estimated time: 4 hours (deadline within 24 hours)
- M2: Auth (patient login + register, PHP sessions, no encryption)
	- Keyword: `BE_AUTH_PATIENT_LOGIN_REGISTER_SESSIONS`
	- Estimated time: 5 hours (deadline within 24 hours)
- M3: Injury form save (patient submits, stored)
	- Keyword: `BE_INJURY_FORM_SAVE_STORE`
	- Estimated time: 4 hours (deadline within 24 hours)
- M4: Appointments list + doctor confirm/cancel
	- Keyword: `BE_APPOINTMENTS_LIST_CONFIRM_CANCEL`
	- Estimated time: 5 hours (deadline within 24 hours)

---

## ✅ NEXT STEP

Pick one keyword to start with and tell me which student is doing it.

---

## ⏱️ 24-Hour Deadline Note

All milestones are scoped to fit within a 24-hour total window.

---

## 📁 Project Structure (Proposed)

```
injurySystem/
	instruction.md
	src/
		index.html
		css/
			style.css
		pages/
			home.html
			login.html
			register.html
			patient-dashboard.html
			doctor-dashboard.html
			injury-form.html
			appointments.html
			report-form.html
			report-view.html
		php/
			config.php
			db.php
			session.php
			auth/
				login.php
				register.php
				logout.php
			patient/
				save-injury.php
			doctor/
				appointments.php
				update-appointment.php
		data/
			app.sqlite
```
