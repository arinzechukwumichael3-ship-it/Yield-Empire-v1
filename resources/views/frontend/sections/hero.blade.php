<!-- ====== HERO SECTION — Professional Video Background ====== -->
<section class="enzo-hero" id="hero">
    <!-- YouTube Video Background -->
    <div class="enzo-hero-video-bg">
        <iframe 
            src="https://www.youtube.com/embed/HvVqN4dK0zo?autoplay=1&mute=1&loop=1&playlist=HvVqN4dK0zo&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&enablejsapi=1"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            class="hero-youtube-iframe"
            loading="lazy"
        ></iframe>
        <div class="enzo-hero-video-overlay"></div>
        <!-- Poster overlay to hide YouTube branding before load -->
        <div class="hero-video-poster" style="background-image: url('{{ asset('frontend/images/hero-bg.jpg') }}')"></div>
    </div>

    <div class="enzo-hero-content">
        <div class="enzo-hero-split">
            <div class="enzo-hero-left">
                <div class="enzo-hero-badge">
                    <span class="enzo-hero-badge-dot"></span>
                    SECURE FINANCIAL TECHNOLOGY &amp; INVESTMENTS
                </div>

                <h1 class="enzo-hero-title">
                    <span class="enzo-hero-title-line">Turn your trading skills</span>
                    <span class="enzo-hero-title-line">into <span class="hp-title-accent">income</span></span>
                </h1>

                <p class="enzo-hero-sub">
                    A transparent investment platform built for steady growth. Fund your account, choose a plan, and track your yield in real time.
                </p>

                <div class="enzo-hero-actions">
                    <a href="{{ setRoute('user.register') }}" class="enzo-btn enzo-btn-primary">
                        Get Started
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                    </a>
                    <a href="{{ setRoute('user.login') }}" class="enzo-btn enzo-btn-secondary">
                        Log In
                    </a>
                </div>
            </div>

            <div class="enzo-hero-right">
                <div class="hp-video-card">
                    <iframe 
                        src="https://www.youtube.com/embed/HvVqN4dK0zo?autoplay=1&mute=1&loop=1&playlist=HvVqN4dK0zo&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&iv_load_policy=3"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        class="hero-youtube-iframe"
                        style="position:absolute;inset:0;width:100%;height:100%;"
                    ></iframe>
                    <div class="hp-video-card-overlay"></div>
                    <div class="hp-video-card-content">
                        <div class="hp-video-stat">
                            <span class="hp-video-stat-label">Total balance</span>
                            <span class="hp-video-stat-value">$12,847.32</span>
                        </div>
                        <div class="hp-video-stat">
                            <span class="hp-video-stat-label">Investment yield</span>
                            <span class="hp-video-stat-value hp-video-stat-up">+$842.10</span>
                        </div>
                        <div class="hp-video-stat">
                            <span class="hp-video-stat-label">Active plans</span>
                            <span class="hp-video-stat-value">3</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="enzo-hero-stats">
            <div class="enzo-hero-stat">
                <span class="enzo-hero-stat-num">256-bit</span>
                <span class="enzo-hero-stat-label">AES Encryption</span>
            </div>
            <div class="enzo-hero-stat-divider"></div>
            <div class="enzo-hero-stat">
                <span class="enzo-hero-stat-num">24/7</span>
                <span class="enzo-hero-stat-label">Support</span>
            </div>
            <div class="enzo-hero-stat-divider"></div>
            <div class="enzo-hero-stat">
                <span class="enzo-hero-stat-num">2FA</span>
                <span class="enzo-hero-stat-label">Protected Login</span>
            </div>
            <div class="enzo-hero-stat-divider"></div>
            <div class="enzo-hero-stat">
                <span class="enzo-hero-stat-num">Real-Time</span>
                <span class="enzo-hero-stat-label">Yield Tracking</span>
            </div>
        </div>
    </div>
</section>

<script>
(function(){
    var iframes = document.querySelectorAll('.hero-youtube-iframe');
    var poster = document.querySelector('.hero-video-poster');
    var loaded = 0;
    iframes.forEach(function(frame){
        frame.addEventListener('load', function(){
            loaded++;
            if (loaded >= 1) {
                frame.classList.add('loaded');
                if (poster) poster.classList.add('hidden');
            }
        });
        // Fallback: hide poster after 3s even if load event missed
        setTimeout(function(){
            frame.classList.add('loaded');
            if (poster) poster.classList.add('hidden');
        }, 3000);
    });
})();
</script>
