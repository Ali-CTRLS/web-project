<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MedCare — Care With Clarity</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Serif:wght@400;600&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/utilities.css">
  <style>
    .hero {
      animation: fadeIn 0.8s ease-out;
    }
    
    .panel {
      transform: translateY(10px);
      opacity: 0;
      animation: rise 0.7s ease both;
    }
    
    .feature {
      cursor: pointer;
    }
    
    .feature::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, var(--primary-light), transparent);
      opacity: 0;
      border-radius: var(--radius);
      transition: opacity 0.3s ease;
    }
    
    .pill {
      cursor: pointer;
      border: 1px solid transparent;
      transition: all 0.3s ease;
    }
    
    .pill:hover {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      transform: translateX(4px);
    }
  </style>
</head>

<body>
  <div id="componentHeader"></div>

  <main class="hero">
    <div class="hero-bg">
      <div class="orb orb-a"></div>
      <div class="orb orb-b"></div>
      <div class="gridlines"></div>
    </div>
    <div class="container hero-inner">
      <div class="hero-content">
        <span class="eyebrow">✨ Patient-first demo</span>
        <h1 class="title">Care with clarity, built for calm decisions.</h1>
        <p class="lead">A clean, responsive front end for appointments, reports, and injury submissions. Designed to
          feel trustworthy and fast.</p>
        <div class="hero-ctas">
          <a class="btn primary" href="pages/login.php">Start as patient</a>
          <a class="btn ghost" href="pages/home.php">Explore demo</a>
        </div>
        <div class="hero-meta">
          <div class="meta-item">
            <span class="meta-label">⏱️ Avg. check-in</span>
            <span class="meta-value">2m 12s</span>
          </div>
          <div class="meta-item">
            <span class="meta-label">📱 Patient updates</span>
            <span class="meta-value">Same day</span>
          </div>
          <div class="meta-item">
            <span class="meta-label">📋 Reports</span>
            <span class="meta-value">Auto-organized</span>
          </div>
        </div>
      </div>
      <div class="hero-art">
        <div class="panel panel-top">
          <div class="panel-header">
            <span>📅 Upcoming</span>
            <strong>3 appointments</strong>
          </div>
          <div class="panel-row">
            <div>
              <div class="panel-title">🫀 Dr. Sarah Smith</div>
              <div class="panel-sub">Cardiology • May 15, 10:00 AM</div>
            </div>
            <span class="badge success">Confirmed</span>
          </div>
          <div class="panel-row">
            <div>
              <div class="panel-title">🦴 Dr. Michael Jones</div>
              <div class="panel-sub">Orthopedic • May 22, 2:30 PM</div>
            </div>
            <span class="badge warning">Pending</span>
          </div>
        </div>
        <div class="panel panel-bottom">
          <div class="panel-header">
            <span>🏥 Injury report</span>
            <strong>Submitted today</strong>
          </div>
          <div class="panel-row">
            <div>
              <div class="panel-title">Right knee strain</div>
              <div class="panel-sub">Severity: Moderate • Status: Active</div>
            </div>
            <button class="btn small primary" type="button">View</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <section class="feature-slab">
    <div class="container slab-inner">
      <div>
        <h2 class="section-title">A steady flow from intake to report.</h2>
        <p class="muted">Everything you need to navigate as a patient, with the doctor view ready for confirmations and
          follow-ups. Built for simplicity, designed for trust.</p>
      </div>
      <div class="pill-grid">
        <div class="pill">✓ One-click updates</div>
        <div class="pill">✓ Structured intake</div>
        <div class="pill">✓ Clear status labels</div>
        <div class="pill">✓ Fast backend</div>
      </div>
    </div>
  </section>

  <section class="features">
    <div class="container">
      <h2 class="section-title">What you'll love</h2>
      <div class="grid">
        <div class="feature">
          <h3>⚡ Fast workflows</h3>
          <p>Appointment booking, reporting, and form capture optimized for speed and efficiency.</p>
        </div>
        <div class="feature">
          <h3>♿ Accessible</h3>
          <p>High contrast, keyboard friendly patterns, and responsive layouts for all users.</p>
        </div>
        <div class="feature">
          <h3>🎨 Grounded design</h3>
          <p>Confident typography and calm color layers that build trust and confidence.</p>
        </div>
        <div class="feature">
          <h3>🔒 Secure</h3>
          <p>Professional security practices to keep your health data safe and protected.</p>
        </div>
        <div class="feature">
          <h3>📱 Responsive</h3>
          <p>Works perfectly on desktop, tablet, and mobile devices for on-the-go access.</p>
        </div>
        <div class="feature">
          <h3>💙 Patient-first</h3>
          <p>Designed around real patient needs for a seamless healthcare experience.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="pathway">
    <div class="container">
      <div class="pathway-card">
        <div>
          <span class="eyebrow">🚀 Patient journey</span>
          <h2 class="section-title">Navigate every page with purpose.</h2>
          <p class="muted">Start as a patient, submit an injury report, and track appointment updates without leaving
            the flow. Everything is streamlined for your convenience.</p>
        </div>
        <div class="pathway-steps">
          <div class="step">
            <span>1</span>
            <div>
              <h4>🔐 Sign in or register</h4>
              <p>Create your account or sign in to access your personalized dashboard and health records.</p>
            </div>
          </div>
          <div class="step">
            <span>2</span>
            <div>
              <h4>🏥 Report injury or book appointment</h4>
              <p>Quickly submit injury reports with detailed symptoms or schedule appointments with doctors.</p>
            </div>
          </div>
          <div class="step">
            <span>3</span>
            <div>
              <h4>📊 Track updates in real-time</h4>
              <p>Receive instant confirmations and updates on your appointments and medical reports.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section style="padding: 4rem 0; background: linear-gradient(135deg, var(--primary-light), rgba(244, 162, 97, 0.05));">
    <div class="container">
      <div style="text-align: center;">
        <h2 class="section-title">Ready to get started?</h2>
        <p class="muted" style="max-width: 500px; margin: 1rem auto;">Experience seamless healthcare management with MedCare. Sign in or register to explore the demo.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem;">
          <a class="btn primary" href="pages/login.php">Sign in</a>
          <a class="btn ghost" href="pages/register.php">Create account</a>
        </div>
      </div>
    </div>
  </section>

  <div id="componentFooter"></div>

  <script src="js/main.js"></script>
</body>

</html>
