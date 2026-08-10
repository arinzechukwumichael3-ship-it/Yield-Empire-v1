<!-- Floating pill bottom navigation — 5 destinations with labels, shared across authenticated pages -->
<nav class="glass-nav" role="navigation" aria-label="Main navigation">
    <div class="glass-nav-inner">
        <div class="glass-nav-group">
            <a href="{{ route("user.rise.home") }}" class="glass-nav-item {{ request()->routeIs(["user.rise.home", "user.dashboard", "user.add.money.*", "user.money-out.*"]) ? "active" : "" }}" aria-label="Home">
                <svg class="glass-nav-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span class="glass-nav-label">Home</span>
            </a>
            <a href="{{ route("user.rise.invest") }}" class="glass-nav-item {{ request()->routeIs(["user.rise.invest", "user.portfolios.*"]) ? "active" : "" }}" aria-label="Invest">
                <svg class="glass-nav-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                <span class="glass-nav-label">Invest</span>
            </a>
            <a href="{{ route("user.rise.feed") }}" class="glass-nav-item {{ request()->routeIs(["user.rise.feed", "user.transactions.*", "user.rise.send", "user.rise.withdraw.crypto"]) ? "active" : "" }}" aria-label="Activity">
                <svg class="glass-nav-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                <span class="glass-nav-label">Activity</span>
            </a>
            <a href="{{ setRoute("user.investments.offers") }}" class="glass-nav-item {{ request()->routeIs(["user.investments.*", "user.invest.*"]) ? "active" : "" }}" aria-label="Earn">
                <svg class="glass-nav-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span class="glass-nav-label">Earn</span>
            </a>
            <a href="{{ route("user.rise.wallet") }}" class="glass-nav-item {{ request()->routeIs(["user.rise.wallet"]) ? "active" : "" }}" aria-label="Wallet">
                <svg class="glass-nav-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                <span class="glass-nav-label">Wallet</span>
            </a>
        </div>
    </div>
</nav>