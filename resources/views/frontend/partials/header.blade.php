@php
    $setup_pages = DB::table("setup_pages")->where("status", true)->get();
@endphp

<!-- ====== NEW ENZOBANK NAVBAR ====== -->
<header class="enzonav" id="enzonav">
    <div class="enzonav-inner">
        <!-- Logo -->
        <a href="{{ setRoute("frontend.index") }}" class="enzonav-logo">
            <img src="{{ asset('backend/images/web-settings/image-assets/enzobank-logo.png') }}" alt="EnzoBank" class="enzonav-logo-img">
            <span class="enzonav-logo-text">Enzo<span class="enzonav-logo-accent">Bank</span></span>
        </a>

        <!-- Desktop Nav Links -->
        <nav class="enzonav-links" id="enzonavLinks">
            <a href="{{ setRoute("frontend.index") }}" class="enzonav-link active">Home</a>
            <a href="{{ setRoute("frontend.index") }}#features" class="enzonav-link">Features</a>
            <a href="{{ setRoute("frontend.index") }}#how-it-works" class="enzonav-link">How It Works</a>
            <a href="{{ setRoute("frontend.index") }}#security" class="enzonav-link">Security</a>
            <a href="{{ setRoute("frontend.index") }}#testimonials" class="enzonav-link">Reviews</a>
            <a href="{{ setRoute("frontend.contact") }}" class="enzonav-link">Contact</a>
        </nav>

        <!-- Desktop Actions -->
        <div class="enzonav-actions">
            <!-- Theme Toggle -->
            <button class="theme-toggle" id="themeToggleDesktop" aria-label="Toggle theme">
                <svg class="sun-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg class="moon-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>
            @auth
                <a href="{{ setRoute("user.rise.home") }}" class="enzonav-btn enzonav-btn-primary">Dashboard</a>
            @else
                <a href="{{ setRoute("user.login") }}" class="enzonav-btn enzonav-btn-ghost">Sign In</a>
                <a href="{{ setRoute("user.register") }}" class="enzonav-btn enzonav-btn-primary">Get Started</a>
            @endauth
            <button class="enzonav-hamburger" id="enzonavHamburger" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="enzonav-mobile" id="enzonavMobile">
        <div class="enzonav-mobile-theme">
            <button class="theme-toggle" id="themeToggleMobile" aria-label="Toggle theme">
                <svg class="sun-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg class="moon-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                <span style="margin-left:8px;font-size:14px;">{{ __("Theme") }}</span>
            </button>
        </div>
        <a href="{{ setRoute("frontend.index") }}" class="enzonav-mobile-link">Home</a>
        <a href="{{ setRoute("frontend.index") }}#features" class="enzonav-mobile-link">Features</a>
        <a href="{{ setRoute("frontend.index") }}#how-it-works" class="enzonav-mobile-link">How It Works</a>
        <a href="{{ setRoute("frontend.index") }}#security" class="enzonav-mobile-link">Security</a>
        <a href="{{ setRoute("frontend.index") }}#testimonials" class="enzonav-mobile-link">Reviews</a>
        <a href="{{ setRoute("frontend.contact") }}" class="enzonav-mobile-link">Contact</a>
        <div class="enzonav-mobile-divider"></div>
        @auth
            <a href="{{ setRoute("user.rise.home") }}" class="enzonav-mobile-btn">Dashboard</a>
        @else
            <a href="{{ setRoute("user.login") }}" class="enzonav-mobile-btn enzonav-mobile-btn-ghost">Sign In</a>
            <a href="{{ setRoute("user.register") }}" class="enzonav-mobile-btn enzonav-mobile-btn-primary">Get Started</a>
        @endauth
    </div>
</header>
