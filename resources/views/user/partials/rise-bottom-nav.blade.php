<!-- Rise Bottom Navigation -->
<nav class="rise-bottom-nav">
    <a href="{{ setRoute('user.rise.home') }}" class="rise-nav-item {{ request()->routeIs('user.rise.home') ? 'active' : '' }}">
        <svg class="rise-nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        <span class="rise-nav-label">Home</span>
    </a>
    <a href="{{ setRoute('user.rise.invest') }}" class="rise-nav-item {{ request()->routeIs('user.rise.invest') ? 'active' : '' }}">
        <svg class="rise-nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
        </svg>
        <span class="rise-nav-label">Invest</span>
    </a>
    <a href="{{ setRoute('user.rise.wallet') }}" class="rise-nav-item {{ request()->routeIs('user.rise.wallet') ? 'active' : '' }}">
        <svg class="rise-nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="6" width="22" height="16" rx="2" ry="2"/>
            <line x1="1" y1="10" x2="23" y2="10"/>
            <circle cx="16" cy="14" r="2"/>
        </svg>
        <span class="rise-nav-label">Wallet</span>
    </a>
    <a href="{{ setRoute('user.rise.feed') }}" class="rise-nav-item {{ request()->routeIs('user.rise.feed') ? 'active' : '' }}">
        <svg class="rise-nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 11a9 9 0 0 1 9 9"/>
            <path d="M4 4a16 16 0 0 1 16 16"/>
            <circle cx="5" cy="19" r="1"/>
        </svg>
        <span class="rise-nav-label">Feed</span>
    </a>
    <a href="{{ setRoute('user.rise.account') }}" class="rise-nav-item {{ request()->routeIs('user.rise.account') ? 'active' : '' }}">
        <svg class="rise-nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
        </svg>
        <span class="rise-nav-label">Account</span>
    </a>
</nav>
