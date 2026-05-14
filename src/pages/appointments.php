<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Appointments — MedCare Demo</title>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/utilities.css">
</head>

<body>
  <div id="componentHeader"></div>
  <main class="container">
    <h1 class="section-title">Manage Appointments</h1>
    <p class="muted">View and manage your upcoming appointments with healthcare providers.</p>

    <div class="form-card">
      <h2 style="margin-top:0">📅 Book New Appointment</h2>
      <div id="formMessage"></div>
      <form id="appointmentForm" onsubmit="handleAppointmentSubmit(event)">
        <div class="form-group">
          <label for="doctor">Select Doctor:</label>
          <select id="doctor" name="doctor_name" required>
            <option value="">-- Choose a doctor --</option>
            <option value="Dr. Sarah Smith - Cardiologist">Dr. Sarah Smith - Cardiologist</option>
            <option value="Dr. Michael Jones - Orthopedic">Dr. Michael Jones - Orthopedic</option>
            <option value="Dr. Jennifer Lee - Neurology">Dr. Jennifer Lee - Neurology</option>
            <option value="Dr. Rajesh Patel - Dermatology">Dr. Rajesh Patel - Dermatology</option>
          </select>
          <div class="form-error"></div>
        </div>
        <div class="form-group">
          <label for="date">Preferred Date:</label>
          <input type="date" id="date" name="preferred_date" required>
          <div class="form-error"></div>
        </div>
        <div class="form-group">
          <label for="time">Preferred Time:</label>
          <input type="time" id="time" name="preferred_time" required>
          <div class="form-error"></div>
        </div>
        <div class="form-group">
          <label for="reason">Reason for Visit:</label>
          <textarea id="reason" name="reason" placeholder="Brief description of your visit reason..." required></textarea>
          <div class="form-error"></div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn primary" id="submitBtn">Book Appointment</button>
          <button type="reset" class="btn ghost">Clear</button>
        </div>
      </form>
    </div>

    <div class="section-margin">
      <h2>Your Upcoming Appointments</h2>
      <p class="muted-small" id="appointmentCount">Loading appointments...</p>
      <div id="appointmentsList"></div>
    </div>
  </main>
  <div id="componentFooter"></div>
  <script src="../js/main.js"></script>
  <script>
    async function loadAppointments() {
      try {
        const response = await fetch('../php/api/get-appointments.php');
        const data = await response.json();
        
        const appointmentsList = document.getElementById('appointmentsList');
        const appointmentCount = document.getElementById('appointmentCount');

        if (!response.ok || !data.success) {
          appointmentsList.innerHTML = '<p style="color: var(--muted); text-align: center; padding: 2rem;">No appointments scheduled. Book your first appointment above!</p>';
          appointmentCount.textContent = 'You have 0 scheduled appointments';
          return;
        }

        const appointments = Array.isArray(data.data) ? data.data : [];
        appointmentCount.textContent = `You have ${appointments.length} scheduled appointment${appointments.length !== 1 ? 's' : ''}`;

        if (appointments.length === 0) {
          appointmentsList.innerHTML = '<p style="color: var(--muted); text-align: center; padding: 2rem;">No appointments scheduled. Book your first appointment above!</p>';
          return;
        }

        appointmentsList.innerHTML = appointments.map(appt => {
          const dateTime = formatAppointmentDateTime(appt.preferred_date, appt.preferred_time);
          const doctorEmoji = getDoctorEmoji(appt.doctor_name);
          const statusClass = appt.status === 'confirmed' ? 'success' : (appt.status === 'canceled' ? 'danger' : 'warning');
          const canReschedule = appt.status !== 'canceled';
          const canCancel = appt.status !== 'canceled';

          return `
            <div class="list-item">
              <div>
                <div class="list-item-title">${doctorEmoji} ${escapeHtml(appt.doctor_name || 'Doctor')}</div>
                <div class="list-item-desc">📅 ${dateTime}</div>
                <div class="list-item-desc">📝 ${escapeHtml(appt.reason || 'Appointment')}</div>
              </div>
              <div class="list-item-meta">
                <span class="badge ${statusClass}">${escapeHtml(appt.status).toUpperCase()}</span>
                ${canReschedule ? `<button class="btn small ghost" onclick="rescheduleAppointment(${appt.id})">Reschedule</button>` : ''}
                ${canCancel ? `<button class="btn small ghost" onclick="cancelAppointment(${appt.id})">Cancel</button>` : ''}
              </div>
            </div>
          `;
        }).join('');
      } catch (error) {
        console.error('Failed to load appointments', error);
        document.getElementById('appointmentsList').innerHTML = '<p style="color: var(--muted); text-align: center; padding: 2rem;">Error loading appointments</p>';
      }
    }

    async function rescheduleAppointment(appointmentId) {
      const newDate = prompt('Enter new date (YYYY-MM-DD):');
      if (!newDate) return;

      const newTime = prompt('Enter new time (HH:MM, 24-hour format):');
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
          alert(result.error || 'Could not reschedule appointment');
          return;
        }

        alert('Appointment rescheduled successfully!');
        await loadAppointments();
      } catch (error) {
        console.error('Reschedule failed', error);
        alert('Error rescheduling appointment');
      }
    }

    async function cancelAppointment(appointmentId) {
      if (!confirm('Are you sure you want to cancel this appointment?')) {
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
          alert(result.error || 'Could not cancel appointment');
          return;
        }

        alert('Appointment cancelled successfully!');
        await loadAppointments();
      } catch (error) {
        console.error('Cancel failed', error);
        alert('Error cancelling appointment');
      }
    }

    function escapeHtml(text) {
      if (!text) return '';
      return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function formatAppointmentDateTime(date, time) {
      if (!date || !time) return 'N/A';
      const d = new Date(date + 'T' + time);
      const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      const month = monthNames[d.getMonth()];
      const day = d.getDate();
      const year = d.getFullYear();
      const hours = String(d.getHours()).padStart(2, '0');
      const minutes = String(d.getMinutes()).padStart(2, '0');
      return `${month} ${day}, ${year} at ${hours}:${minutes}`;
    }

    function getDoctorEmoji(doctorName) {
      if (!doctorName) return '👨‍⚕️';
      const name = doctorName.toLowerCase();
      if (name.includes('cardio')) return '🫀';
      if (name.includes('orthop')) return '🦴';
      if (name.includes('neuro')) return '🧠';
      if (name.includes('derma')) return '🧴';
      return '👨‍⚕️';
    }

    function handleAppointmentSubmit(e) {
      e.preventDefault();
      
      const form = e.target;
      const doctor = document.getElementById('doctor').value;
      const date = document.getElementById('date').value;
      const time = document.getElementById('time').value;
      const reason = document.getElementById('reason').value;
      
      // Clear previous errors and messages
      document.querySelectorAll('.form-error').forEach(el => el.textContent = '');
      document.querySelectorAll('.form-group').forEach(el => el.classList.remove('has-error', 'has-success'));
      document.getElementById('formMessage').innerHTML = '';
      
      let hasErrors = false;
      
      if (!doctor) {
        document.getElementById('doctor').parentElement.querySelector('.form-error').textContent = 'Please select a doctor';
        document.getElementById('doctor').parentElement.classList.add('has-error');
        hasErrors = true;
      }
      if (!date) {
        document.getElementById('date').parentElement.querySelector('.form-error').textContent = 'Please select a date';
        document.getElementById('date').parentElement.classList.add('has-error');
        hasErrors = true;
      }
      if (!time) {
        document.getElementById('time').parentElement.querySelector('.form-error').textContent = 'Please select a time';
        document.getElementById('time').parentElement.classList.add('has-error');
        hasErrors = true;
      }
      if (!reason || reason.trim().length < 5) {
        document.getElementById('reason').parentElement.querySelector('.form-error').textContent = 'Please provide a reason for visit (at least 5 characters)';
        document.getElementById('reason').parentElement.classList.add('has-error');
        hasErrors = true;
      }
      
      if (hasErrors) {
        return;
      }

      // Submit via fetch
      const formData = new FormData();
      formData.append('doctor_name', doctor);
      formData.append('preferred_date', date);
      formData.append('preferred_time', time);
      formData.append('reason', reason);

      const submitBtn = document.getElementById('submitBtn');
      const originalText = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = '⏳ Booking...';

      fetch('../php/patient/save-appointment.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(result => {
        if (result.success) {
          const message = document.getElementById('formMessage');
          message.innerHTML = '<div style="background-color: #d1f4e7; color: #0f766e; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #0f766e;">✓ ' + result.message + ' The doctor will see it shortly.</div>';
          document.getElementById('appointmentForm').reset();
          // Reload the appointments list
          loadAppointments();
          submitBtn.disabled = false;
          submitBtn.textContent = originalText;
        } else {
          const message = document.getElementById('formMessage');
          message.innerHTML = '<div style="background-color: #fecccc; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #b91c1c;">✗ ' + (result.error || 'Error booking appointment') + '</div>';
          submitBtn.disabled = false;
          submitBtn.textContent = originalText;
        }
      })
      .catch(err => {
        console.error('Error booking appointment', err);
        const message = document.getElementById('formMessage');
        message.innerHTML = '<div style="background-color: #fecccc; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #b91c1c;">✗ Network error. Please try again.</div>';
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      });
    }

    document.addEventListener('DOMContentLoaded', loadAppointments);
  </script>
</body>

</html>
