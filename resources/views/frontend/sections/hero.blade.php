<!-- ====== HERO SECTION — Professional ====== -->
<section class="enzo-hero" id="hero">
    <!-- Animated background image (CSS-powered, no video dependency) -->
    <div class="enzo-hero-bg-wrapper">
        <div class="enzo-hero-bg-image" style="background-image: url('{{ asset('frontend/images/hero-poster.jpg') }}')"></div>
        <div class="enzo-hero-bg-overlay"></div>
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
                <div class="hp-card-stack">
                    <!-- Card 1: Balance -->
                    <div class="hp-stat-card hp-stat-card-1">
                        <div class="hp-stat-card-header">
                            <span class="hp-stat-live-dot"></span>
                            <span class="hp-stat-label">Live balance</span>
                        </div>
                        <div class="hp-stat-balance">$12,847.32</div>
                        <div class="hp-stat-change">↑ 8.4%</div>
                    </div>
                    <!-- Card 2: Yield -->
                    <div class="hp-stat-card hp-stat-card-2">
                        <div class="hp-stat-row">
                            <span>Investment yield</span>
                            <span class="hp-stat-up">+$842.10</span>
                        </div>
                        <div class="hp-stat-row">
                            <span>Active plans</span>
                            <span>3</span>
                        </div>
                    </div>
                    <!-- Feature chips -->
                    <div class="hp-features">
                        <span class="hp-feat-chip">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2.5" stroke-linecap="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Payouts live
                        </span>
                        <span class="hp-feat-chip">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                            Instant
                        </span>
                        <span class="hp-feat-chip">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                            Global
                        </span>
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
