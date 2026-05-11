// Load header and footer components, wire nav toggle, set footer year
async function loadComponent(id, url){
  const el = document.getElementById(id);
  if(!el) return;
  try{
    const res = await fetch(url);
    if(!res.ok) throw new Error('Not found');
    el.innerHTML = await res.text();
  }catch(e){
    console.warn('Component load failed', url, e);
  }
}

// Fetch API data and update dashboard dynamically
async function loadDashboardData(){
  try {
    // Load user data
    const userRes = await fetch('/src/php/api/get-user-data.php');
    const userData = await userRes.json();
    
    if(userData.success) {
      const user = userData.user;
      const greeting = document.querySelector('.section-title + p');
      if(greeting) greeting.textContent = `Welcome back, ${user.name}. Manage your health and medical records.`;
    }
    
    // Load stats
    const statsRes = await fetch('/src/php/api/get-dashboard-stats.php');
    const statsData = await statsRes.json();
    
    if(statsData.success && statsData.stats) {
      const stats = statsData.stats;
      const cards = document.querySelectorAll('.dashboard-card');
      
      if(userData.user.role === 'patient') {
        if(cards[1]) cards[1].querySelector('.stat').textContent = stats.confirmed_appointments || 0;
      }
    }
    
    // Load appointments
    const apptRes = await fetch('/src/php/api/get-appointments.php');
    const apptData = await apptRes.json();
    
    if(apptData.success && apptData.data && apptData.data.length > 0) {
      const appointmentsList = document.querySelector('table tbody');
      if(appointmentsList) {
        appointmentsList.innerHTML = apptData.data.map(apt => `
          <tr>
            <td>${new Date(apt.preferred_date + ' ' + apt.preferred_time).toLocaleTimeString()}</td>
            <td><div style="font-weight:600">Patient ${apt.id}</div></td>
            <td>${apt.reason}</td>
            <td><span class="badge">${apt.status}</span></td>
            <td><button class="btn small ghost">View</button></td>
          </tr>
        `).join('');
      }
    }
    
    // Load injuries
    const injuryRes = await fetch('/src/php/api/get-injuries.php');
    const injuryData = await injuryRes.json();
    
    if(injuryData.success && injuryData.data && injuryData.data.length > 0) {
      const firstInjury = injuryData.data[0];
      const healthCard = document.querySelector('.dashboard-card h3');
      if(healthCard) {
        healthCard.parentElement.innerHTML = `
          <h3>🩺 Recent Injury Report</h3>
          <div style="margin:1rem 0">
            <p><strong>Location:</strong> ${firstInjury.location}</p>
            <p><strong>Severity:</strong> ${firstInjury.severity}</p>
            <p><strong>Date:</strong> ${firstInjury.injury_date}</p>
          </div>
          <button class="btn small primary">View Report</button>
        `;
      }
    }
  }catch(e){
    console.warn('Dashboard data load failed', e);
  }
}

document.addEventListener('DOMContentLoaded', async ()=>{
  await loadComponent('componentHeader','/src/components/header.html');
  await loadComponent('componentFooter','/src/components/footer.html');

  // Load dynamic data for dashboards
  if(document.querySelector('.dashboard-grid')) {
    await loadDashboardData();
  }

  // footer year
  const y = document.getElementById('year'); if(y) y.textContent = new Date().getFullYear();

  // nav toggle
  const navToggle = document.getElementById('navToggle');
  navToggle?.addEventListener('click', ()=>{
    const nav = document.getElementById('mainNav');
    if(!nav) return;
    nav.style.display = (getComputedStyle(nav).display === 'flex') ? 'none' : 'flex';
  });

  // simple fade-in animation for cards
  requestAnimationFrame(()=>{
    document.querySelectorAll('.card, .feature, .hero-content').forEach((n,i)=>{n.style.transform='translateY(0)';n.style.opacity='1';});
  });
});
