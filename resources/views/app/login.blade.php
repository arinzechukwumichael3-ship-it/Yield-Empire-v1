<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Log In — EnzoBank</title>
<style>html,body{background:#0A0E1A;margin:0;height:100%;}*{margin:0;padding:0;box-sizing:border-box;}</style>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
html, body { height:100%; width:100%; overflow:hidden; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; }

.login-screen {
  position:fixed; inset:0;
  background: linear-gradient(160deg, #0A0E1A 0%, #141B2D 50%, #0A0E1A 100%);
  display:flex; flex-direction:column; overflow-y:auto;
}
.login-screen::before {
  content:''; position:absolute; top:-120px; left:-60px;
  width:280px; height:280px;
  background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
  border-radius:50%;
}
.login-screen::after {
  content:''; position:absolute; top:-80px; right:-40px;
  width:200px; height:200px;
  background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, transparent 70%);
  border-radius:50%;
}

.login-container {
  position:relative; z-index:1;
  padding:48px 24px 32px; flex:1;
  display:flex; flex-direction:column;
  max-width:400px; margin:0 auto; width:100%;
}

.top-bar {
  display:flex; align-items:center; gap:12px; margin-bottom:40px;
  animation: slideDown 0.6s ease-out;
}
.top-bar .mini-logo {
  width:36px; height:36px;
  background: linear-gradient(135deg, #3B82F6, #2563EB);
  border-radius:10px; display:flex; align-items:center; justify-content:center;
}
.top-bar .mini-logo svg { width:18px; height:18px; fill:#fff; }
.top-bar .brand-name { font-size:18px; font-weight:700; color:#F1F5F9; }
.top-bar .brand-name span { color:#3B82F6; }
.app-logo { width:36px; height:36px; object-fit:contain; display:block; }
@keyframes slideDown { from { opacity:0; transform:translateY(-20px); } to { opacity:1; transform:translateY(0); } }

.welcome-section { margin-bottom:32px; }
.welcome-section h1 {
  font-size:28px; font-weight:800; color:#F1F5F9;
  animation: fadeUp 0.6s ease-out 0.1s both;
}
.welcome-section p {
  font-size:14px; color:#64748B; margin-top:6px;
  animation: fadeUp 0.6s ease-out 0.2s both;
}
@keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }

.login-form { flex:1; display:flex; flex-direction:column; }
.input-group {
  margin-bottom:16px;
  animation: fadeUp 0.6s ease-out 0.25s both;
}
.input-group label {
  display:block; font-size:12px; font-weight:600; color:#94A3B8; margin-bottom:6px;
  text-transform:uppercase; letter-spacing:0.5px;
}
.input-group input {
  width:100%; padding:16px 14px;
  background:rgba(30,41,59,0.6); border:1.5px solid rgba(148,163,184,0.12);
  border-radius:12px; font-size:16px; color:#F1F5F9; outline:none;
  transition: all 0.3s ease;
}
.input-group input:focus { border-color:#3B82F6; background:rgba(30,41,59,0.8); box-shadow:0 0 0 3px rgba(59,130,246,0.08); }
.input-group input::placeholder { color:#475569; }
.input-group .input-error { font-size:12px; color:#EF4444; margin-top:4px; display:none; }

.password-row {
  display:flex; justify-content:space-between; align-items:center;
  animation: fadeUp 0.6s ease-out 0.3s both;
}
.password-row a {
  font-size:13px; color:#3B82F6; text-decoration:none; transition:color 0.2s;
}
.password-row a:hover { color:#60A5FA; }

.login-btn {
  width:100%; padding:16px;
  background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
  border:none; border-radius:12px;
  font-size:16px; font-weight:700; color:#fff;
  cursor:pointer; margin-top:auto; margin-bottom:12px;
  transition: all 0.3s ease;
  position:relative; overflow:hidden;
  animation: fadeUp 0.6s ease-out 0.35s both;
}
.login-btn:hover { transform:translateY(-1px); box-shadow:0 8px 25px rgba(59,130,246,0.3); }
.login-btn:active { transform:scale(0.98); }
@keyframes spin { to { transform:rotate(360deg); } }

.divider {
  display:flex; align-items:center; gap:12px; margin:6px 0 16px;
  animation: fadeUp 0.6s ease-out 0.4s both;
}
.divider-line { flex:1; height:1px; background:rgba(148,163,184,0.1); }
.divider-text { font-size:12px; color:#475569; white-space:nowrap; }

.alternatives {
  display:flex; gap:12px;
  animation: fadeUp 0.6s ease-out 0.45s both;
}
.alt-btn {
  flex:1; padding:14px;
  background:rgba(30,41,59,0.5); border:1.5px solid rgba(148,163,184,0.1);
  border-radius:12px; display:flex; align-items:center; justify-content:center; gap:8px;
  color:#94A3B8; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none;
  transition: all 0.2s;
}
.alt-btn:hover { background:rgba(30,41,59,0.8); border-color:rgba(148,163,184,0.2); color:#F1F5F9; }
.alt-btn svg { width:18px; height:18px; stroke:currentColor; fill:none; stroke-width:2; }

.signup-row {
  display:flex; align-items:center; justify-content:center; gap:6px; margin-top:20px;
  animation: fadeUp 0.6s ease-out 0.5s both;
}
.signup-text { font-size:13px; color:#64748B; }
.signup-link { font-size:13px; font-weight:700; color:#3B82F6; text-decoration:none; }
.signup-link:hover { color:#60A5FA; }

.toast-error {
  position:fixed; top:24px; left:50%; transform:translateX(-50%) translateY(-100px);
  background:#EF4444; color:#fff; padding:14px 24px;
  border-radius:12px; font-size:14px; font-weight:600;
  box-shadow:0 8px 32px rgba(239,68,68,0.25);
  transition:transform 0.4s cubic-bezier(0.34,1.56,0.64,1); z-index:100;
  max-width:90%; text-align:center;
}
.toast-error.show { transform:translateX(-50%) translateY(0); }
</style>
</head>
<body>
<div class="page-loader" id="pageLoader">
  <div class="loader-ring">
    <svg class="loader-ring-svg" width="72" height="72" viewBox="0 0 72 72">
      <defs><linearGradient id="plGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#3B82F6"/><stop offset="50%" stop-color="#6366F1"/><stop offset="100%" stop-color="#8B5CF6"/>
      </linearGradient></defs>
      <circle class="loader-track" cx="36" cy="36" r="30"/>
      <circle class="loader-arc" cx="36" cy="36" r="30"/>
    </svg>
    <div class="loader-dot-ring"></div>
  </div>
  <div class="loader-text">
    <span>Loading</span>
    <span class="loader-dots"><span></span><span></span><span></span></span>
  </div>
  <div class="loader-shimmer"></div>
</div>
<style>
.page-loader { position:fixed; inset:0; z-index:99999; background:rgba(10,14,26,0.96); display:flex; flex-direction:column; align-items:center; justify-content:center; transition:opacity 0.5s ease, visibility 0.5s ease; }
.page-loader.loaded { opacity:0; visibility:hidden; pointer-events:none; }
.page-loader .loader-ring { position:relative; width:72px; height:72px; margin-bottom:32px; }
.page-loader .loader-ring-svg { position:absolute; inset:0; transform:rotate(-90deg); }
.page-loader .loader-track { fill:none; stroke:rgba(59,130,246,0.12); stroke-width:3; }
.page-loader .loader-arc { fill:none; stroke:url(#plGrad); stroke-width:3; stroke-linecap:round; stroke-dasharray:170; stroke-dashoffset:340; animation: plSpin 1.6s cubic-bezier(0.4,0,0.2,1) infinite; }
@keyframes plSpin { 0% { stroke-dashoffset:340; transform:rotate(0deg); } 50% { stroke-dashoffset:0; transform:rotate(270deg); } 100% { stroke-dashoffset:340; transform:rotate(360deg); } }
.page-loader .loader-dot-ring { position:absolute; inset:8px; border-radius:50%; display:flex; align-items:center; justify-content:center; }
.page-loader .loader-dot-ring::before { content:''; width:8px; height:8px; border-radius:50%; background:#3B82F6; animation: plPulse 1.6s ease-in-out infinite; box-shadow:0 0 12px rgba(59,130,246,0.4); }
@keyframes plPulse { 0%,100% { transform:scale(0.8); opacity:0.6; } 50% { transform:scale(1.2); opacity:1; } }
.page-loader .loader-text { font-size:15px; font-weight:600; color:#94A3B8; letter-spacing:0.3px; display:flex; align-items:center; gap:3px; }
.page-loader .loader-dots { display:flex; gap:3px; }
.page-loader .loader-dots span { width:4px; height:4px; border-radius:50%; background:#3B82F6; animation: plDotBounce 1.2s ease-in-out infinite; }
.page-loader .loader-dots span:nth-child(2) { animation-delay:0.2s; }
.page-loader .loader-dots span:nth-child(3) { animation-delay:0.4s; }
@keyframes plDotBounce { 0%,80%,100% { transform:scale(0.4); opacity:0.3; } 40% { transform:scale(1); opacity:1; } }
.page-loader .loader-shimmer { width:80px; height:2px; border-radius:1px; margin-top:14px; background:linear-gradient(90deg, transparent 0%, rgba(59,130,246,0.3) 50%, transparent 100%); background-size:200% 100%; animation: plShimmer 1.5s ease-in-out infinite; }
@keyframes plShimmer { 0% { background-position:-200% 0; } 100% { background-position:200% 0; } }
</style>
<script>window.addEventListener('load',function(){setTimeout(function(){document.getElementById('pageLoader').classList.add('loaded')},300)});</script>
<div class="login-screen">
  <div class="login-container">
    <div class="top-bar">
      <img src="{{ asset('backend/images/web-settings/image-assets/enzobank-logo.png') }}" alt="EnzoBank" class="app-logo">
    </div>

    <div class="welcome-section">
      <h1>Welcome back</h1>
      <p>Sign in to your account</p>
    </div>

    <form class="login-form" id="loginForm" autocomplete="off">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="input-group">
        <label>Email or Phone</label>
        <input type="text" id="emailInput" name="credentials" placeholder="your@email.com" autocomplete="username" inputmode="email">
        <div class="input-error" id="emailError">Please enter a valid email</div>
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" id="passwordInput" name="password" placeholder="Enter your password" autocomplete="current-password">
        <div class="input-error" id="passwordError">Password is required</div>
      </div>

      <div class="password-row">
        <a href="#">Forgot password?</a>
      </div>

      <button type="submit" class="login-btn" id="loginBtn">
        Sign In
      </button>

      <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">or continue with</span>
        <div class="divider-line"></div>
      </div>

      <div class="alternatives">
        <a href="/app/pin" class="alt-btn">
          <svg viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 6V4a6 6 0 1 1 12 0v2"/></svg>
          Use PIN
        </a>
        <a href="/app/biometric" class="alt-btn">
          <svg viewBox="0 0 24 24"><path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
          Fingerprint
        </a>
      </div>

      <div class="signup-row">
        <span class="signup-text">Don't have an account?</span>
        <a href="{{ route('user.register') }}" class="signup-link" target="_blank" rel="noopener noreferrer">Sign Up</a>
      </div>
    </form>
  </div>
</div>

<div class="toast-error" id="toastError"></div>

<script>
(function() {
  const form = document.getElementById('loginForm');
  const emailInput = document.getElementById('emailInput');
  const passwordInput = document.getElementById('passwordInput');
  const loginBtn = document.getElementById('loginBtn');
  const toast = document.getElementById('toastError');
  const emailError = document.getElementById('emailError');
  const passwordError = document.getElementById('passwordError');
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  function showToast(msg) {
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 4000);
  }

  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    emailError.style.display = 'none';
    passwordError.style.display = 'none';

    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();

    if (!email) { emailError.textContent = 'Email or phone is required'; emailError.style.display = 'block'; return; }
    if (!password) { passwordError.style.display = 'block'; return; }

    showLoader('Signing in');

    try {
      // POST to the web login endpoint — this creates the session cookie
      const response = await fetch('/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ credentials: email, password: password })
      });

      const data = await response.json();

      hideLoader();
      if (response.ok && data.success) {
        localStorage.setItem('enzobank_user', JSON.stringify(data.user || {}));
        window.location.href = data.redirect || '/app/pin';
      } else {
        const msg = data.errors?.credentials?.[0] || data.message?.error?.[0] || data.message || 'Invalid credentials. Please try again.';
        showToast(msg);
      }
    } catch(err) {
      hideLoader();
      showToast('Connection error. Please check your internet.');
    }
  });

  // If already logged in (server-side check handles redirect, this is a backup)
  fetch('/app/pin/status', { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(data => { if (data.authenticated) window.location.href = '/app/pin'; })
    .catch(() => {});
// Hide page loader once fully rendered
document.getElementById('pageLoader').classList.add('loaded');
})();
</script>
@include('partials.app-loader')
</body>
</html>
