<?php
require_once '../php/session.php';
require_login('patient');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Patient Dashboard — MedCare Demo</title>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/utilities.css">
</head>
<body>
  <div id="componentHeader"></div>
  <main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
      <div>
        <h1 class="section-title">Patient Dashboard</h1>
        <p class="muted">Welcome back, <?php echo htmlspecialchars($_SESSION['name']); ?>. Manage your health and medical records.</p>
      </div>
      <a href="appointments.php" class="btn primary">📅 Book Appointment</a>
    </div>

    <h2 style="margin: 2.5rem 0 1.5rem; font-size: 1.3rem;">📊 Your Health Overview</h2>
    <div class="dashboard-grid">
      <div class="dashboard-card">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
          <h3 style="margin: 0; font-size: 1rem;">🏥 Active Doctors</h3>
        </div>
        <div class="stat">4</div>
        <p style="margin: 0.5rem 0 0; color: var(--muted); font-size: 0.9rem;">Healthcare providers</p>
      </div>
      
      <div class="dashboard-card">
        <h3 style="margin: 0 0 1rem; font-size: 1rem;">📅 Upcoming Appointments</h3>
        <div class="stat" id="upcomingAppointmentCount">2</div>
        <p style="margin: 0.5rem 0 0; color: var(--muted); font-size: 0.9rem;">Scheduled appointments</p>
      </div>
      
      <div class="dashboard-card">
        <h3 style="margin: 0 0 1rem; font-size: 1rem;">📋 Medical Records</h3>
        <div class="stat">12</div>
        <p style="margin: 0.5rem 0 0; color: var(--muted); font-size: 0.9rem;">Reports and documents</p>
      </div>
      
      <div class="dashboard-card">
        <h3 style="margin: 0 0 1rem; font-size: 1rem;">💊 Medications</h3>
        <div class="stat">3</div>
        <p style="margin: 0.5rem 0 0; color: var(--muted); font-size: 0.9rem;">Active prescriptions</p>
      </div>
    </div>

    <div class="section-margin">
      <h2 style="margin-bottom: 1rem; font-size: 1.3rem;">📅 Upcoming Appointment Details</h2>
      <div id="appointmentList" style="display: grid; gap: 1rem;"></div>
    </div>

    <div class="two-col-section">
      <div class="dashboard-card">
        <h3>🩺 Recent Check-up</h3>
        <div class="info-margin">
          <p><strong>Date:</strong> May 8, 2026</p>
          <p><strong>Doctor:</strong> Dr. Sarah Smith</p>
          <p><strong>Status:</strong> <span class="badge success">Healthy</span></p>
        </div>
        <button class="btn small primary" onclick="window.location.href='appointments.php'">View Full Report</button>
      </div>
      
      <div class="dashboard-card">
        <h3>🧪 Lab Results</h3>
        <div class="info-margin">
          <p><strong>Test:</strong> Blood Work</p>
          <p><strong>Date:</strong> April 30, 2026</p>
          <p><strong>Status:</strong> <span class="badge success">Normal</span></p>
        </div>
        <button class="btn small primary" onclick="window.location.href='appointments.php'">View Results</button>
      </div>
    </div>

    <div class="section-margin">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;">
        <h2 style="margin:0;font-size:1.3rem;">📝 My Medical Reports</h2>
        <a href="report-view.php" class="btn primary">View All Reports</a>
      </div>
      <div id="reportList" style="display:grid;gap:1rem;">
        <div class="list-item">
          <div>
            <div class="list-item-title">Loading reports...</div>
            <div class="list-item-desc">Fetching your latest reports from the doctor.</div>
          </div>
        </div>
      </div>
    </div>

    <h2 style="margin: 2.5rem 0 1.5rem; font-size: 1.3rem;">📝 Recent Activity</h2>
    <div class="list-item">
      <div>
        <div class="list-item-title">✅ Appointment Confirmed</div>
        <div class="list-item-desc">Cardiology appointment with Dr. Sarah Smith</div>
        <div class="list-item-desc" style="color: var(--muted); font-size: 0.85rem;">May 15, 2026</div>
      </div>
      <span class="badge success">Completed</span>
    </div>

    <div class="list-item">
      <div>
        <div class="list-item-title">📋 Lab Results Available</div>
        <div class="list-item-desc">Your blood work results are now available for review</div>
        <div class="list-item-desc" style="color: var(--muted); font-size: 0.85rem;">April 30, 2026</div>
      </div>
      <span class="badge">New</span>
    </div>

    <div class="list-item">
      <div>
        <div class="list-item-title">💊 Prescription Renewed</div>
        <div class="list-item-desc">Your hypertension medication has been renewed</div>
        <div class="list-item-desc" style="color: var(--muted); font-size: 0.85rem;">April 25, 2026</div>
      </div>
      <span class="badge">Processed</span>
    </div>

  </main>
  <div id="componentFooter"></div>
  <script src="../js/main.js"></script>
  <script>
    function escapeHtml(value) {
      return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function formatAppointmentTime(date, time) {
      return `${date} at ${time}`;
    }

    function formatDate(dateStr) {
      if (!dateStr) return 'N/A';
      const d = new Date(dateStr);
      return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    async function refreshAppointments() {
      await loadPatientData();
    }

    async function cancelAppointment(appointmentId) {
      if (!window.confirm('Cancel this appointment? It will be removed from your upcoming appointments.')) {
        return;
      }

      try {
        const response = await fetch('../php/patient/cancel-appointment.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ appointment_id: String(appointmentId) })
        });

        const result = await response.json();
        if (!response.ok || !result.success) {
          alert(result.error || 'Could not cancel the appointment.');
          return;
        }

        await refreshAppointments();
      } catch (error) {
        console.error('Failed to cancel appointment', error);
        alert('Could not cancel the appointment.');
      }
    }

    async function rescheduleAppointment(appointmentId) {
      const newDate = window.prompt('Enter a new date for this appointment (YYYY-MM-DD):');
      if (!newDate) return;

      const newTime = window.prompt('Enter a new time for this appointment (HH:MM, 24-hour):');
      if (!newTime) return;

      try {
        const response = await fetch('../php/patient/reschedule-appointment.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({
            appointment_id: String(appointmentId),
            preferred_date: newDate,
            preferred_time: newTime
          })
        });

        const result = await response.json();
        if (!response.ok || !result.success) {
          alert(result.error || 'Could not reschedule the appointment.');
          return;
        }

        await refreshAppointments();
      } catch (error) {
        console.error('Failed to reschedule appointment', error);
        alert('Could not reschedule the appointment.');
      }
    }

    async function loadPatientData() {
      try {
        const response = await fetch('../php/api/get-appointments.php');
        const data = await response.json();
        const appointmentList = document.getElementById('appointmentList');
        const appointmentCount = document.getElementById('upcomingAppointmentCount');

        if (!response.ok || !data.success) {
          if (appointmentList) {
            appointmentList.innerHTML = '<div class="list-item"><div><div class="list-item-title">No appointments yet</div><div class="list-item-desc">Book your first appointment to see it here.</div></div></div>';
          }
          if (appointmentCount) appointmentCount.textContent = '0';
          return;
        }

        const appointments = Array.isArray(data.data) ? data.data : [];
        if (appointmentCount) appointmentCount.textContent = String(appointments.length);

        if (appointmentList) {
          if (appointments.length === 0) {
            appointmentList.innerHTML = '<div class="list-item"><div><div class="list-item-title">No appointments yet</div><div class="list-item-desc">Book your first appointment to see it here.</div></div></div>';
          } else {
            appointmentList.innerHTML = appointments.map(appt => `
              <div class="list-item">
                <div>
                  <div class="list-item-title">📌 ${escapeHtml(appt.doctor_name || 'Appointment')}</div>
                  <div class="list-item-desc">📅 ${escapeHtml(formatAppointmentTime(appt.preferred_date, appt.preferred_time))}</div>
                  <div class="list-item-desc">📝 ${escapeHtml(appt.reason)}</div>
                </div>
                <div class="list-item-meta">
                  <span class="badge ${appt.status === 'confirmed' ? 'success' : 'warning'}">${escapeHtml(appt.status)}</span>
                  <button class="btn small ghost" type="button" onclick="rescheduleAppointment(${appt.id})">Reschedule</button>
                  <button class="btn small ghost" type="button" onclick="cancelAppointment(${appt.id})">Cancel</button>
                </div>
              </div>
            `).join('');
          }
        }

        document.querySelectorAll('.dashboard-card, .list-item').forEach((card, index) => {
          setTimeout(() => {
            card.style.opacity = '1';
          }, index * 100);
        });
      } catch (error) {
        console.error('Failed to load patient appointments', error);
      }
    }

    async function loadPatientReports() {
      try {
        const response = await fetch('../php/api/get-reports.php');
        const data = await response.json();
        const reportList = document.getElementById('reportList');
        const reportCount = document.querySelector('.dashboard-card:nth-of-type(3) .stat');

        if (!response.ok || !data.success) {
          if (reportList) {
            reportList.innerHTML = '<div class="list-item"><div><div class="list-item-title">No reports yet</div><div class="list-item-desc">Your doctor-created reports will appear here.</div></div></div>';
          }
          if (reportCount) reportCount.textContent = '0';
          return;
        }

        const reports = Array.isArray(data.data) ? data.data : [];
        if (reportCount) reportCount.textContent = String(reports.length);

        if (reportList) {
          if (reports.length === 0) {
            reportList.innerHTML = '<div class="list-item"><div><div class="list-item-title">No reports yet</div><div class="list-item-desc">Your doctor-created reports will appear here.</div></div></div>';
          } else {
            reportList.innerHTML = reports.slice(0, 5).map(report => `
              <div class="list-item">
                <div>
                  <div class="list-item-title">📄 ${escapeHtml(report.report_type || 'Medical Report')}</div>
                  <div class="list-item-desc">👨‍⚕️ ${escapeHtml(report.doctor_name || 'Doctor')} · ${escapeHtml(formatDate(report.created_at))}</div>
                  <div class="list-item-desc">${escapeHtml(report.notes || 'No notes provided')}</div>
                </div>
                <div class="list-item-meta" style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
                  <button class="btn small ghost" type="button" onclick="window.location.href='report-view.php'">View</button>
                  <button class="btn small primary" type="button" onclick="window.open('../php/api/download-report.php?id=${report.id}', '_blank')">Download</button>
                </div>
              </div>
            `).join('');
          }
        }
      } catch (error) {
        console.error('Failed to load patient reports', error);
      }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
      loadPatientData();
      loadPatientReports();
    });
  </script>
</body>
</html>
