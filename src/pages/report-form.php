<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Create Medical Report — MedCare Demo</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/utilities.css">
</head>
<body>
  <div id="componentHeader"></div>
  <main class="container">
    <h1 class="section-title">Create Medical Report</h1>
    <p class="muted">Generate a new medical report for a patient.</p>

    <div class="form-card">
      <h2 class="no-margin-top">Medical Report</h2>
      <form action="/myapp/web-project/src/php/doctor/save-report.php" method="POST">
        <div class="form-group">
          <label for="patient">Select Patient:</label>
          <select id="patient" name="patient_id" required>
            <option value="">Loading patients...</option>
          </select>
        </div>

        <div class="form-group">
          <label for="reporttype">Report Type:</label>
          <select id="reporttype" name="report_type" required>
            <option value="">-- Select type --</option>
            <option value="physical">Physical Examination</option>
            <option value="lab">Laboratory Results</option>
            <option value="imaging">Imaging Report</option>
            <option value="consultation">Consultation Notes</option>
            <option value="followup">Follow-up Report</option>
            <option value="discharge">Discharge Summary</option>
          </select>
        </div>

        <div class="form-group">
          <label for="date">Report Date:</label>
          <input type="date" id="date" name="report_date" required>
        </div>

        <div class="form-group">
          <label for="findings">Clinical Findings:</label>
          <textarea id="findings" name="findings" placeholder="Document your observations and clinical findings..." required></textarea>
        </div>

        <div class="form-group">
          <label for="diagnosis">Diagnosis:</label>
          <textarea id="diagnosis" name="diagnosis" placeholder="Primary and secondary diagnoses..." required></textarea>
        </div>

        <div class="form-group">
          <label for="treatment">Treatment Plan:</label>
          <textarea id="treatment" name="treatment" placeholder="Recommended treatment and medications..." required></textarea>
        </div>

        <div class="form-group">
          <label for="medications">Prescribed Medications:</label>
          <textarea id="medications" name="medications" placeholder="List medications: name, dosage, frequency, duration..." required></textarea>
        </div>

        <div class="form-group">
          <label for="followup">Follow-up Instructions:</label>
          <textarea id="followup" name="followup" placeholder="Instructions for patient follow-up care..."></textarea>
        </div>

        <div class="form-group">
          <label for="notes">Additional Notes:</label>
          <textarea id="notes" name="notes" placeholder="Any other relevant information..."></textarea>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn primary">Save Report</button>
          <button type="button" class="btn ghost" onclick="window.print()">Print Report</button>
          <button type="reset" class="btn ghost">Clear</button>
        </div>
      </form>
    </div>
  </main>
  <div id="componentFooter"></div>
  <script src="../js/main.js"></script>
  <script>
    async function loadPatientsForReport() {
      const select = document.getElementById('patient');
      try {
        const res = await fetch('../php/api/get-patients.php');
        if (!res.ok) {
          select.innerHTML = '<option value="">Unable to load patients</option>';
          return;
        }
        const data = await res.json();
        if (!data.success || !Array.isArray(data.data)) {
          select.innerHTML = '<option value="">No patients available</option>';
          return;
        }
        select.innerHTML = '';
        data.data.forEach(p => {
          const opt = document.createElement('option');
          opt.value = p.id;
          opt.textContent = `${p.name} (ID: ${p.id})`;
          select.appendChild(opt);
        });
      } catch (err) {
        console.error('Failed to load patients', err);
        select.innerHTML = '<option value="">Error loading patients</option>';
      }
    }

    document.addEventListener('DOMContentLoaded', loadPatientsForReport);
  </script>
</body>
</html>
