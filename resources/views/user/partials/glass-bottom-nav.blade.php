<!-- Floating glass pill bottom navigation — shared across all authenticated pages -->
@php $gnavUser = auth()->user(); $gnavInitial = $gnavUser ? strtoupper(substr($gnavUser->firstname ?? $gnavUser->username, 0, 1)) : "?"; $gnavAvatar = $gnavUser ? $gnavUser->userImage : null; @endphp
<nav class="glass-nav" role="navigation" aria-label="Main navigation">
    <div class="glass-nav-inner">
        <a href="{{ route("user.rise.home") }}" class="glass-nav-item {{ request()->routeIs(["user.rise.home", "user.dashboard"]) ? "active" : "" }}" aria-label="Home">
            <svg class="glass-nav-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <a href="{{ route("user.rise.invest") }}" class="glass-nav-item {{ request()->routeIs(["user.rise.invest", "user.investments.*", "user.invest.*", "user.portfolios.*"]) ? "active" : "" }}" aria-label="Invest">
            <svg class="glass-nav-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </a>
        <a href="{{ route("user.rise.wallet") }}" class="glass-nav-item {{ request()->routeIs(["user.rise.wallet"]) ? "active" : "" }}" aria-label="Wallet">
            <svg class="glass-nav-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
        </a>
        <a href="{{ route("user.add.money.index") }}" class="glass-nav-center" aria-label="Add Money">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </a>
        <a href="{{ route("user.rise.feed") }}" class="glass-nav-item {{ request()->routeIs(["user.rise.feed", "user.transactions.*", "user.loans.*", "user.rise.send", "user.rise.withdraw.crypto"]) ? "active" : "" }}" aria-label="Feed">
            <svg class="glass-nav-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></svg>
        </a>
        <a href="{{ route("user.rise.account") }}" class="glass-nav-item glass-nav-avatar {{ request()->routeIs(["user.rise.account", "user.profile.*", "user.security.*", "user.setup.pin.*", "user.support.ticket.*", "user.kyc.*"]) ? "active" : "" }}" aria-label="Account">
            @if($gnavAvatar && !str_contains($gnavAvatar, "profile-default"))
                <img src="{{ $gnavAvatar }}" alt="" class="glass-nav-avatar-img" loading="lazy">
            @else
                <span class="glass-nav-initial">{{ $gnavInitial }}</span>
            @endif
        </a>
    </div>
</nav>
