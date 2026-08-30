<!-- ====== HERO SECTION — LIGHT / NAVY ====== -->
<section class="enzo-hero" id="hero">
    <!-- Background effects wrapper (prevents content clipping) -->
    <div class="enzo-hero-bg-wrapper">
        <div class="orb-1"></div>
        <div class="orb-2"></div>
        <div class="hero-bg"></div>
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
                <div class="hp-phone-wrap" role="img" aria-label="YieldEmpire dashboard preview on a phone">
                    <!-- Floating feature chips -->
                    <span class="hp-chip hp-chip-1">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Payouts live
                    </span>
                    <span class="hp-chip hp-chip-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2f5fd6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        Instant
                    </span>
                    <span class="hp-chip hp-chip-3">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0a1f5c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                        Global
                    </span>

                    <!-- Phone mockup -->
                    <div class="hp-phone">
                        <div class="hp-phone-notch"></div>
                        <div class="hp-phone-screen">
                            <div class="hp-phone-hud">
                                <span>9:41</span>
                                <span class="hp-phone-live">Live balance</span>
                            </div>
                            <div class="hp-phone-balance">
                                <div class="hp-phone-balance-label">Total balance</div>
                                <div class="hp-phone-balance-value">$12,847.32 <small>&uarr; 8.4%</small></div>
                            </div>
                            <div class="hp-phone-chart">
                                <svg viewBox="0 0 260 92" fill="none" xmlns="http://www.w3.org/2000/svg" width="100%" height="auto">
                                    <defs>
                                        <linearGradient id="hpChartFill" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0" stop-color="#2f6bff" stop-opacity="0.35"/>
                                            <stop offset="1" stop-color="#2f6bff" stop-opacity="0"/>
                                        </linearGradient>
                                    </defs>
                                    <path d="M2 78 C 30 74, 45 60, 66 62 S 100 70, 122 58 S 160 40, 180 44 S 220 28, 258 16" stroke="#2f6bff" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                                    <path d="M2 78 C 30 74, 45 60, 66 62 S 100 70, 122 58 S 160 40, 180 44 S 220 28, 258 16 L 258 92 L 2 92 Z" fill="url(#hpChartFill)"/>
                                    <circle cx="258" cy="16" r="4" fill="#2f6bff"/>
                                    <circle cx="258" cy="16" r="7" stroke="#2f6bff" stroke-opacity="0.3"/>
                                </svg>
                            </div>
                            <div class="hp-phone-rows">
                                <div class="hp-phone-row">
                                    <span>Investment yield</span>
                                    <span class="hp-phone-row-up">+$842.10</span>
                                </div>
                                <div class="hp-phone-row">
                                    <span>Active plans</span>
                                    <span>3</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Glow pulse beneath phone -->
                    <div class="card-glow"></div>
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
