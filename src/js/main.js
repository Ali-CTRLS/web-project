// ========================================
// Enhanced MedCare Frontend JavaScript
// ========================================

// Load header and footer components, wire nav toggle, set footer year
async function loadComponent(id, url) {
  const el = document.getElementById(id);
  if (!el) return;
  try {
    const res = await fetch(url);
    if (!res.ok) throw new Error('Not found');
    el.innerHTML = await res.text();
  } catch (e) {
    console.warn('Component load failed', url, e);
  }
}

// Utility: Show loading spinner on button
function setButtonLoading(button, isLoading = true) {
  if (isLoading) {
    button.disabled = true;
    button.dataset.originalText = button.textContent;
    button.innerHTML = '<span class="spinner" style="margin-right: 0.5rem;"></span>Loading...';
  } else {
    button.disabled = false;
    button.textContent = button.dataset.originalText || 'Submit';
  }
}

// Utility: Show form success message
function showFormSuccess(form, message = 'Form submitted successfully!') {
  const successDiv = document.createElement('div');
  successDiv.className = 'success-message';
  successDiv.style.cssText = `
    padding: 1rem;
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid #10b981;
    border-radius: 8px;
    color: #065f46;
    margin-bottom: 1.5rem;
    animation: slideIn 0.3s ease-out;
  `;
  successDiv.textContent = '✓ ' + message;
  form.parentElement.insertBefore(successDiv, form);
  
  setTimeout(() => {
    successDiv.remove();
  }, 3000);
}

// Utility: Show form error message
function showFormError(form, message = 'An error occurred. Please try again.') {
  const errorDiv = document.createElement('div');
  errorDiv.className = 'error-message';
  errorDiv.style.cssText = `
    padding: 1rem;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid #ef4444;
    border-radius: 8px;
    color: #991b1b;
    margin-bottom: 1.5rem;
    animation: slideIn 0.3s ease-out;
  `;
  errorDiv.textContent = '✗ ' + message;
  form.parentElement.insertBefore(errorDiv, form);
  
  setTimeout(() => {
    errorDiv.remove();
  }, 5000);
}

// Fetch API data and update dashboard dynamically
async function loadDashboardData() {
  try {
    // Load user data
    const userRes = await fetch('/myapp/web-project/src/php/api/get-user-data.php');
    const userData = await userRes.json();
    
    if (userData.success) {
      const user = userData.user;
      const greeting = document.querySelector('.section-title + p');
      if (greeting) greeting.textContent = `Welcome back, ${user.name}. Manage your health and medical records.`;
    }
    
    // Load stats
    const statsRes = await fetch('/myapp/web-project/src/php/api/get-dashboard-stats.php');
    const statsData = await statsRes.json();
    
    if (statsData.success && statsData.stats) {
      const stats = statsData.stats;
      const cards = document.querySelectorAll('.dashboard-card');
      
      if (userData.user.role === 'patient') {
        if (cards[1]) cards[1].querySelector('.stat').textContent = stats.confirmed_appointments || 0;
      }
    }
    
    // Load appointments
    const apptRes = await fetch('/myapp/web-project/src/php/api/get-appointments.php');
    const apptData = await apptRes.json();
    
    const appointmentList = document.getElementById('appointmentList');
    const upcomingAppointmentCount = document.getElementById('upcomingAppointmentCount');
    if (apptData.success && Array.isArray(apptData.data)) {
      if (upcomingAppointmentCount) {
        upcomingAppointmentCount.textContent = String(apptData.data.length);
      }

      if (appointmentList) {
        if (apptData.data.length === 0) {
          appointmentList.innerHTML = '<div class="list-item"><div><div class="list-item-title">No appointments yet</div><div class="list-item-desc">Book your first appointment to see it here.</div></div></div>';
        } else {
          appointmentList.innerHTML = apptData.data.map(apt => `
            <div class="list-item">
              <div>
                <div class="list-item-title">📌 ${apt.doctor_name || 'Appointment'}</div>
                <div class="list-item-desc">📅 ${apt.preferred_date} at ${apt.preferred_time}</div>
                <div class="list-item-desc">📝 ${apt.reason}</div>
              </div>
              <span class="badge ${apt.status === 'confirmed' ? 'success' : 'warning'}">${apt.status}</span>
            </div>
          `).join('');
        }
      }
    }
    
  } catch (e) {
    console.warn('Dashboard data load failed', e);
  }
}

// Enhanced form handling with better UX
function setupFormHandlers() {
  document.querySelectorAll('form').forEach(form => {
    // Clear errors on input
    form.querySelectorAll('input, select, textarea').forEach(field => {
      field.addEventListener('input', function() {
        const group = this.parentElement;
        if (group.classList.contains('has-error')) {
          group.classList.remove('has-error');
          const errorMsg = group.querySelector('.form-error');
          if (errorMsg) errorMsg.textContent = '';
        }
      });
    });
  });
}

// Initialize page interactions
document.addEventListener('DOMContentLoaded', async () => {
  // Load components
  await loadComponent('componentHeader', '/myapp/web-project/src/components/header.php');
  await loadComponent('componentFooter', '/myapp/web-project/src/components/footer.php');

  // Load dynamic data for dashboards
  if (document.querySelector('.dashboard-grid')) {
    await loadDashboardData();
  }

  // Set footer year
  const y = document.getElementById('year');
  if (y) y.textContent = new Date().getFullYear();

  // Navigation toggle
  const navToggle = document.getElementById('navToggle');
  navToggle?.addEventListener('click', () => {
    const nav = document.getElementById('mainNav');
    if (!nav) return;
    nav.style.display = (getComputedStyle(nav).display === 'flex') ? 'none' : 'flex';
  });

  // Setup form handlers
  setupFormHandlers();

  // Smooth fade-in animation for cards and content
  requestAnimationFrame(() => {
    document.querySelectorAll('.card, .feature, .hero-content, .dashboard-card, .list-item').forEach((n, i) => {
      n.style.animation = `fadeIn 0.6s ease ${i * 50}ms forwards`;
      n.style.opacity = '0';
    });
  });

  // Add smooth scroll behavior
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
});
