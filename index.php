<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — RouteSafe Barangay 770</title>
  <link rel="stylesheet" href="navbar.css" />
  <style>
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--rs-bg-base);
      position: relative;
      overflow: hidden;
    }

    /* Grid bg */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(255,255,255,0.018) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.018) 1px, transparent 1px);
      background-size: 48px 48px;
      pointer-events: none;
    }

    /* Red glow */
    body::after {
      content: '';
      position: fixed;
      width: 500px; height: 500px;
      border-radius: 50%;
      background: radial-gradient(ellipse, rgba(230,57,70,0.07) 0%, transparent 70%);
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      pointer-events: none;
    }

    /* ── Card ── */
    .login-card {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 400px;
      margin: 24px;
      background: var(--rs-bg-surface);
      border: 1px solid var(--rs-border);
      border-radius: var(--rs-radius-lg);
      padding: 40px 36px;
    }

    /* Brand */
    .login-brand {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 32px;
      text-align: center;
    }

    .login-logo {
      width: 52px; height: 52px;
      border-radius: 14px;
      background: var(--rs-accent);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: var(--rs-font-display);
      font-size: 20px;
      font-weight: 700;
      color: white;
      margin-bottom: 16px;
      letter-spacing: -0.5px;
    }

    .login-title {
      font-family: var(--rs-font-display);
      font-size: 20px;
      font-weight: 700;
      color: var(--rs-text-primary);
      margin-bottom: 4px;
    }

    .login-sub {
      color: var(--rs-text-muted);
      font-size: 12px;
    }

    /* Tabs */
    .login-tabs {
      display: flex;
      background: var(--rs-bg-raised);
      border-radius: var(--rs-radius-md);
      padding: 3px;
      margin-bottom: 28px;
    }

    .login-tab {
      flex: 1;
      padding: 8px;
      text-align: center;
      font-size: 13px;
      font-weight: 600;
      color: var(--rs-text-muted);
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.15s;
      user-select: none;
    }

    .login-tab.active {
      background: var(--rs-bg-surface);
      color: var(--rs-text-primary);
      border: 1px solid var(--rs-border);
    }

    /* Form */
    .form-group {
      margin-bottom: 16px;
    }

    .form-label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: var(--rs-text-secondary);
      margin-bottom: 7px;
      letter-spacing: 0.2px;
    }

    .form-input {
      width: 100%;
      padding: 10px 12px;
      background: var(--rs-bg-raised);
      border: 1px solid var(--rs-border);
      border-radius: var(--rs-radius-sm);
      color: var(--rs-text-primary);
      font-family: var(--rs-font-body);
      font-size: 13px;
      outline: none;
      transition: border-color 0.15s;
    }

    .form-input::placeholder { color: var(--rs-text-dim); }
    .form-input:focus { border-color: var(--rs-accent); }

    .form-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      font-size: 12px;
    }

    .form-check {
      display: flex;
      align-items: center;
      gap: 7px;
      color: var(--rs-text-secondary);
      cursor: pointer;
    }

    .form-check input[type="checkbox"] {
      accent-color: var(--rs-accent);
      width: 14px; height: 14px;
    }

    .form-forgot {
      color: var(--rs-accent);
      font-size: 12px;
      cursor: pointer;
      font-weight: 500;
    }
    .form-forgot:hover { text-decoration: underline; }

    /* Submit button */
    .btn-login {
      width: 100%;
      padding: 11px;
      background: var(--rs-accent);
      color: white;
      border: none;
      border-radius: var(--rs-radius-sm);
      font-family: var(--rs-font-display);
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.15s, transform 0.1s;
      letter-spacing: 0.2px;
    }
    .btn-login:hover { background: var(--rs-accent-hover); }
    .btn-login:active { transform: scale(0.99); }

    /* Divider */
    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 20px 0;
      color: var(--rs-text-dim);
      font-size: 11px;
    }
    .divider::before, .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--rs-border);
    }

    /* Guest button */
    .btn-guest {
      width: 100%;
      padding: 10px;
      background: transparent;
      color: var(--rs-text-secondary);
      border: 1px solid var(--rs-border);
      border-radius: var(--rs-radius-sm);
      font-family: var(--rs-font-body);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.15s;
    }
    .btn-guest:hover {
      background: var(--rs-bg-raised);
      color: var(--rs-text-primary);
      border-color: var(--rs-border-hover);
    }

    /* Register panel (hidden by default) */
    #register-panel { display: none; }

    /* Footer note */
    .login-footer {
      margin-top: 24px;
      text-align: center;
      color: var(--rs-text-muted);
      font-size: 11px;
      line-height: 1.6;
    }

    /* Error / success messages */
    .alert {
      padding: 9px 12px;
      border-radius: var(--rs-radius-sm);
      font-size: 12px;
      font-weight: 500;
      margin-bottom: 16px;
      display: none;
    }
    .alert-error   { background: rgba(230,57,70,0.1);  color: #e63946; border: 1px solid rgba(230,57,70,0.2); }
    .alert-success { background: rgba(34,197,94,0.1);  color: #22c55e; border: 1px solid rgba(34,197,94,0.2); }
    .alert.show { display: block; }
  </style>
</head>
<body>

  <div class="login-card">

    <!-- Brand -->
    <div class="login-brand">
      <div class="login-logo">RS</div>
      <div class="login-title">RouteSafe Barangay 770</div>
      <div class="login-sub">Sta. Ana, Manila · Safe Night Routes</div>
    </div>

    <!-- Tabs -->
    <div class="login-tabs">
      <div class="login-tab active" id="tab-login" onclick="switchTab('login')">Sign In</div>
      <div class="login-tab" id="tab-register" onclick="switchTab('register')">Register</div>
    </div>

    <!-- Alerts -->
    <div class="alert alert-error" id="alert-error">Invalid username or password.</div>
    <div class="alert alert-success" id="alert-success">Account created! Please sign in.</div>

    <!-- LOGIN PANEL -->
    <div id="login-panel">
      <div class="form-group">
        <label class="form-label">Username</label>
        <input class="form-input" type="text" id="login-user" placeholder="Enter your username" />
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <input class="form-input" type="password" id="login-pass" placeholder="Enter your password" />
      </div>
      <div class="form-row">
        <label class="form-check">
          <input type="checkbox" /> Remember me
        </label>
        <span class="form-forgot">Forgot password?</span>
      </div>
      <button class="btn-login" onclick="handleLogin()">Sign In to RouteSafe</button>

      <div class="divider">or continue as</div>
      <button class="btn-guest" onclick="window.location.href='home.html'">
        👤 Continue as Guest
      </button>
    </div>

    <!-- REGISTER PANEL -->
    <div id="register-panel">
      <div class="form-group">
        <label class="form-label">Full Name</label>
        <input class="form-input" type="text" id="reg-name" placeholder="Juan dela Cruz" />
      </div>
      <div class="form-group">
        <label class="form-label">Username</label>
        <input class="form-input" type="text" id="reg-user" placeholder="Choose a username" />
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <input class="form-input" type="password" id="reg-pass" placeholder="Create a password" />
      </div>
      <div class="form-group">
        <label class="form-label">Confirm Password</label>
        <input class="form-input" type="password" id="reg-pass2" placeholder="Repeat password" />
      </div>
      <button class="btn-login" onclick="handleRegister()" style="margin-top:4px;">Create Account</button>
    </div>

    <!-- Footer -->
    <div class="login-footer">
      Barangay 770 · Sta. Ana, Manila<br>
      Walking Path Recommender · Dijkstra's Algorithm
    </div>

  </div>

  <script>
    function switchTab(tab) {
      document.getElementById('tab-login').classList.toggle('active', tab === 'login');
      document.getElementById('tab-register').classList.toggle('active', tab === 'register');
      document.getElementById('login-panel').style.display    = tab === 'login'    ? 'block' : 'none';
      document.getElementById('register-panel').style.display = tab === 'register' ? 'block' : 'none';
      document.getElementById('alert-error').classList.remove('show');
      document.getElementById('alert-success').classList.remove('show');
    }

    function handleLogin() {
      const user = document.getElementById('login-user').value.trim();
      const pass = document.getElementById('login-pass').value.trim();

      // Basic demo auth — replace with real backend call (PHP/MySQL)
      if (user === 'admin' && pass === 'admin123') {
        window.location.href = 'home.html';
      } else if (user && pass) {
        // Check localStorage for registered accounts (demo only)
        const accounts = JSON.parse(localStorage.getItem('rs_accounts') || '{}');
        if (accounts[user] && accounts[user] === pass) {
          window.location.href = 'home.html';
        } else {
          showAlert('error', 'Invalid username or password.');
        }
      } else {
        showAlert('error', 'Please enter your username and password.');
      }
    }

    function handleRegister() {
      const name  = document.getElementById('reg-name').value.trim();
      const user  = document.getElementById('reg-user').value.trim();
      const pass  = document.getElementById('reg-pass').value.trim();
      const pass2 = document.getElementById('reg-pass2').value.trim();

      if (!name || !user || !pass || !pass2) {
        showAlert('error', 'Please fill in all fields.');
        return;
      }
      if (pass !== pass2) {
        showAlert('error', 'Passwords do not match.');
        return;
      }
      if (pass.length < 6) {
        showAlert('error', 'Password must be at least 6 characters.');
        return;
      }

      // Save to localStorage (demo only — connect to PHP/MySQL for production)
      const accounts = JSON.parse(localStorage.getItem('rs_accounts') || '{}');
      accounts[user] = pass;
      localStorage.setItem('rs_accounts', JSON.stringify(accounts));

      showAlert('success', 'Account created! You can now sign in.');
      setTimeout(() => switchTab('login'), 1500);
    }

    function showAlert(type, msg) {
      const el = document.getElementById('alert-' + type);
      el.textContent = msg;
      el.classList.add('show');
      setTimeout(() => el.classList.remove('show'), 4000);
    }

    // Enter key support
    document.addEventListener('keydown', e => {
      if (e.key === 'Enter') {
        const loginVisible = document.getElementById('login-panel').style.display !== 'none';
        loginVisible ? handleLogin() : handleRegister();
      }
    });
  </script>
</body>
</html>