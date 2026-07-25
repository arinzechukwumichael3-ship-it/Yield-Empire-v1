<style>
/* ====== ADVANCED LOADING OVERLAY ====== */
.loader-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(10,14,26,0.92);
  backdrop-filter:blur(24px); -webkit-backdrop-filter:blur(24px);
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  opacity:0; visibility:hidden; transition:opacity 0.4s ease, visibility 0.4s ease;
}
.loader-overlay.active { opacity:1; visibility:visible; }

.loader-ring {
  position:relative; width:72px; height:72px; margin-bottom:32px;
}
.loader-ring-svg { position:absolute; inset:0; transform:rotate(-90deg); }
.loader-track { fill:none; stroke:rgba(59,130,246,0.12); stroke-width:3; }
.loader-arc {
  fill:none; stroke:url(#loaderGrad); stroke-width:3; stroke-linecap:round;
  stroke-dasharray:170; stroke-dashoffset:340;
  animation: loaderSpin 1.6s cubic-bezier(0.4,0,0.2,1) infinite;
}
@keyframes loaderSpin {
  0% { stroke-dashoffset:340; transform:rotate(0deg); }
  50% { stroke-dashoffset:0; transform:rotate(270deg); }
  100% { stroke-dashoffset:340; transform:rotate(360deg); }
}

.loader-dot-ring {
  position:absolute; inset:8px; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
}
.loader-dot-ring::before {
  content:''; width:8px; height:8px; border-radius:50%;
  background:#3B82F6; animation: loaderPulse 1.6s ease-in-out infinite;
  box-shadow:0 0 12px rgba(59,130,246,0.4);
}
@keyframes loaderPulse {
  0%,100% { transform:scale(0.8); opacity:0.6; box-shadow:0 0 8px rgba(59,130,246,0.3); }
  50% { transform:scale(1.2); opacity:1; box-shadow:0 0 20px rgba(59,130,246,0.6); }
}

.loader-text {
  font-size:15px; font-weight:600; color:#94A3B8; letter-spacing:0.3px;
  display:flex; align-items:center; gap:3px;
}
.loader-dots { display:flex; gap:3px; }
.loader-dots span {
  width:4px; height:4px; border-radius:50%; background:#3B82F6;
  animation: loaderDotBounce 1.2s ease-in-out infinite;
}
.loader-dots span:nth-child(2) { animation-delay:0.2s; }
.loader-dots span:nth-child(3) { animation-delay:0.4s; }
@keyframes loaderDotBounce {
  0%,80%,100% { transform:scale(0.4); opacity:0.3; }
  40% { transform:scale(1); opacity:1; }
}

.loader-shimmer {
  width:80px; height:2px; border-radius:1px; margin-top:14px;
  background:linear-gradient(90deg, transparent 0%, rgba(59,130,246,0.3) 50%, transparent 100%);
  background-size:200% 100%; animation: shimmerSlide 1.5s ease-in-out infinite;
}
@keyframes shimmerSlide {
  0% { background-position:-200% 0; }
  100% { background-position:200% 0; }
}
</style>

<div class="loader-overlay" id="loaderOverlay">
  <div class="loader-ring">
    <svg class="loader-ring-svg" width="72" height="72" viewBox="0 0 72 72">
      <defs>
        <linearGradient id="loaderGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#3B82F6"/>
          <stop offset="50%" stop-color="#6366F1"/>
          <stop offset="100%" stop-color="#8B5CF6"/>
        </linearGradient>
      </defs>
      <circle class="loader-track" cx="36" cy="36" r="30"/>
      <circle class="loader-arc" cx="36" cy="36" r="30"/>
    </svg>
    <div class="loader-dot-ring"></div>
  </div>

  <div class="loader-text">
    <span id="loaderLabel">Loading</span>
    <span class="loader-dots"><span></span><span></span><span></span></span>
  </div>
  <div class="loader-shimmer"></div>
</div>

<script>
var loaderOv = document.getElementById('loaderOverlay');
var loaderLb = document.getElementById('loaderLabel');
function showLoader(label) { if (label) loaderLb.textContent = label; loaderOv.classList.add('active'); }
function hideLoader() { loaderOv.classList.remove('active'); }
</script>
