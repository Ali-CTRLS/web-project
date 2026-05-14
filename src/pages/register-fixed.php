<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Register — MedCare</title>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/utilities.css">
  <style>
    .register-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      background: linear-gradient(135deg, rgba(15, 118, 110, 0.05), rgba(244, 162, 97, 0.05));
    }
    
    .register-wrapper {
      width: 100%;
      max-width: 420px;
    }
    
    .register-header {
      text-align: center;
      margin-bottom: 2.5rem;
    }
    
    .register-header h1 {
      font-size: 2rem;
      margin-bottom: 0.5rem;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .register-header p {
      color: var(--muted);
      font-size: 0.95rem;
    }
    
    .form-card {
      animation: rise 0.6s ease both;
    }
    
    .form-link {
      text-align: center;
      margin-top: 1.5rem;
    }
    
    .form-link a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .form-link a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div id="componentHeader"></div>
  
  <div class="register-container">
    <div class="register-wrapper">
      <div class="register-header">
        <h1>Join MedCare</h1>
        <p>Create your account to manage your health records</p>
      </div>
      
      <div class="form-card">
        <form action="../php/auth/register.php" method="POST" id="registerForm" onsubmit="handleRegisterSubmit(event)">
          <div class="form-group">
            <label for="name">Full Name</label>
            <input id="name" name="name" type="text" required placeholder="Jane Doe" autocomplete="name">
            <div class="form-error"></div>
          </div>

          <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" name="email" type="email" required placeholder="you@example.com" autocomplete="email">
            <div class="form-error"></div>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required placeholder="Create a secure password" autocomplete="new-password">
            <div class="form-error"></div>
            <small class="muted-small" style="display: block; margin-top: 0.5rem;">At least 8 characters, including uppercase and numbers</small>
          </div>

          <button class="btn primary" type="submit" style="width: 100%; justify-content: center; margin-top: 1.5rem;">
            Create Account
          </button>
        </form>
        
        <div class="form-link">
          Already have an account? <a href="login.php">Sign in</a>
        </div>
      </div>
    </div>
  </div>
  
  <div id="componentFooter"></div>
  <script src="../js/main.js"></script>
  <script>
    function handleRegisterSubmit(e) {
      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      let hasErrors = false;
      
      document.querySelectorAll('.form-error').forEach(el => el.textContent = '');
      document.querySelectorAll('.form-group').forEach(el => el.classList.remove('has-error'));
      
      if (!name || name.trim().length < 3) {
        document.getElementById('name').parentElement.querySelector('.form-error').textContent = 'Please enter your full name (at least 3 characters)';
        document.getElementById('name').parentElement.classList.add('has-error');
        hasErrors = true;
      }
      
      if (!email || !email.includes('@')) {
        document.getElementById('email').parentElement.querySelector('.form-error').textContent = 'Please enter a valid email address';
        document.getElementById('email').parentElement.classList.add('has-error');
        hasErrors = true;
      }
      
      if (!password || password.length < 8) {
        document.getElementById('password').parentElement.querySelector('.form-error').textContent = 'Password must be at least 8 characters';
        document.getElementById('password').parentElement.classList.add('has-error');
        hasErrors = true;
      }
      
      if (hasErrors) {
        e.preventDefault();
      }
      // If no errors, form submits normally to the PHP backend
    }
  </script>
</body>
</html>
