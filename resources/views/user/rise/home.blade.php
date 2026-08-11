@extends('user.layouts.rise-master')

@section('content')
@php
$user = auth()->user();
$wallet = $wallet ?? \App\Models\UserWallet::auth()->first();
$usdWallet = $usd_wallet ?? $wallet;
$gbpWallet = $gbp_wallet ?? null;
$eurWallet = $eur_wallet ?? null;
$usdBalance = $usdWallet ? $usdWallet->balance : 0;
$gbpBalance = $gbpWallet ? $gbpWallet->balance : 0;
$eurBalance = $eurWallet ? $eurWallet->balance : 0;
$balance = $usdBalance;
$transactions = $transactions ?? collect([]);
$todayTransactions = $todayTransactions ?? $transactions;
$accountNo = $user->account_no ?? '0000000000';
$userInitial = strtoupper(substr($user->firstname ?? $user->username, 0, 1));

/* Net change across today's activity (credit = +, debit = -) */
$netToday = 0;
foreach ($todayTransactions as $t) {
    $isReceived = ($t->attribute ?? '') === 'RECEIVED'
        || (in_array($t->type ?? '', ['ADD-MONEY', 'TRANSFER-MONEY']) && ($t->receiver_id ?? null) == $user->id);
    $amt = (float)($t->request_amount ?? 0);
    $netToday += $isReceived ? $amt : -$amt;
}
$pnl = $netToday;
$pnlPct = $balance > 0 ? ($pnl / $balance) * 100 : 0;
$pnlIsPos = $pnl >= 0;

/* Invested = adjustable from controller (real investment flow); fall back to holdings market value */
$invested = isset($investedAmount) ? (float)$investedAmount : 0;
if ($invested <= 0 && $portfolio && $portfolio->holdings) {
    $invested = $portfolio->holdings->sum(function ($h) {
        return (float)($h->quantity ?? 0) * (float)($h->asset->current_price ?? 0);
    });
}
@endphp

<div class="dash-page dash-home">

    <!--===== TOP HEADER: identity + icon cluster =====-->
    <div class="yh-head">
        <div class="yh-head-side">
            <div class="yh-avatar">
                @if($user->userImage && !str_contains($user->userImage, 'profile-default'))
                    <img src="{{ $user->userImage }}" alt="{{ $user->username }}">
                @else
                    <span>{{ $userInitial }}</span>
                @endif
            </div>
            <div class="yh-ident">
                <span class="yh-ident-name">{{ $user->fullname ?? $user->username }}</span>
                <span class="yh-ident-mask">ACCOUNT ••••{{ substr($accountNo, -4) }}</span>
            </div>
        </div>
    </div>

    <!--===== HERO BALANCE CARD =====-->
    <div class="dash-balance-card">
        <div class="dash-balance-top">
            <div class="dash-balance-currency-tabs">
                <button class="dash-currency-tab active" data-currency="usd">USD</button>
                <button class="dash-currency-tab" data-currency="eur">EUR</button>
                <button class="dash-currency-tab" data-currency="gbp">GBP</button>
            </div>
            <div class="dash-balance-actions-top">
                <button class="dash-copy-btn" onclick="copyAccountNo('{{ $accountNo }}', this)" aria-label="Copy account number">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                </button>
                <button class="dash-eye-btn" id="dashBalanceToggle" aria-label="Toggle balance visibility">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
        </div>
        <div class="dash-balance-account-row">
            <span class="dash-balance-account-label">ACCOUNT</span>
            <span class="dash-balance-account-no">****{{ substr($accountNo, -4) }}</span>
        </div>
        <div class="dash-balance-amount-row">
            <span class="dash-balance-label">Total Assets</span>
            <span class="dash-balance-amount" id="dashBalanceAmount" data-usd="{{ number_format($usdBalance, 2) }}" data-gbp="{{ number_format($gbpBalance, 2) }}" data-eur="{{ number_format($eurBalance, 2) }}">${{ number_format($balance, 2) }}</span>
        </div>
        <div class="yh-pnl">
            <span class="yh-pnl-cap">Today</span>
            <span class="yh-pnl-badge {{ $pnlIsPos ? 'pos' : 'neg' }}">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="{{ $pnlIsPos ? '23 18 13.5 8.5 8.5 13.5 1 6' : '23 6 13.5 15.5 8.5 10.5 1 18' }}"/><polyline points="{{ $pnlIsPos ? '17 18 23 18 23 12' : '17 6 23 6 23 12' }}"/></svg>
                {{ $pnlIsPos ? '+' : '-' }}{{ number_format(abs($pnl), 2) }}
                ({{ $pnlIsPos ? '+' : '' }}{{ number_format($pnlPct, 2) }}%)
            </span>
            <span class="yh-pnl-sub">vs total assets</span>
        </div>

        <div class="live-balance-badge" style="margin-top:8px;">{{ __('Live Balance') }}</div>

        <!-- Primary + secondary actions -->
        <div class="yh-cta">
            <a href="{{ setRoute('user.add.money.index') }}" class="yh-cta-btn yh-cta-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                {{ __('Deposit') }}
            </a>
            <a href="{{ setRoute('user.money-out.index') }}" class="yh-cta-btn yh-cta-ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                {{ __('Withdraw') }}
            </a>
        </div>

        <div class="payouts-live-pill" style="margin-top:14px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            {{ __('Payouts live') }}
        </div>
    </div>

    <!--===== STAT ROW: AVAILABLE / INVESTED =====-->
    <div class="dash-stats-row">
        <a href="{{ route('user.rise.wallet') }}" class="dash-stat-card dash-stat-link" aria-label="Available balance">
            <div class="dash-stat-icon-wrap dash-stat-icon-accent">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
            </div>
            <div class="dash-stat-info">
                <span class="dash-stat-label">Available balance</span>
                <span class="dash-stat-value">${{ number_format($usdBalance, 2) }}</span>
            </div>
        </a>
        <a href="{{ setRoute('user.investments.offers') }}" class="dash-stat-card dash-stat-link" aria-label="Invested">
            <div class="dash-stat-icon-wrap dash-stat-icon-neutral">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div class="dash-stat-info">
                <span class="dash-stat-label">Invested</span>
                <span class="dash-stat-value">${{ number_format($invested, 2) }}</span>
            </div>
        </a>
    </div>

    <!--===== QUICK ACTIONS (circular) =====-->
    <div class="dash-actions-row">
        <a href="{{ setRoute('user.rise.send') }}" class="dash-action-pill">
            <div class="dash-action-icon dash-action-icon-accent">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </div>
            <span class="dash-action-label">Transfer</span>
        </a>
        <a href="{{ setRoute('user.investments.offers') }}" class="dash-action-pill">
            <div class="dash-action-icon dash-action-icon-neutral">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
            </div>
            <span class="dash-action-label">Invest</span>
        </a>
        <a href="{{ setRoute('user.strowallet.virtual.card.index') }}" class="dash-action-pill">
            <div class="dash-action-icon dash-action-icon-neutral">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/><line x1="5" y1="15" x2="9" y2="15"/></svg>
            </div>
            <span class="dash-action-label">Cards</span>
        </a>
        <a href="{{ route('user.loans.index') }}" class="dash-action-pill">
            <div class="dash-action-icon dash-action-icon-neutral">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <span class="dash-action-label">Loans</span>
        </a>
    </div>

    <!--===== REFERRAL / EARN BANNER =====-->
    <div class="dash-referral-banner">
        <div class="dash-referral-banner-content">
            <div class="dash-referral-banner-text">
                <span class="dash-referral-banner-title">Refer & Earn</span>
                <span class="dash-referral-banner-sub">Get $50 for each friend you invite</span>
            </div>
            <a href="{{ route('user.rise.refer') }}" class="dash-referral-banner-btn">Invite</a>
        </div>
    </div>

    <!--===== MY CARD PREVIEW =====-->
    <div class="dash-card-preview">
        <div class="dash-card-preview-inner">
            <div class="dash-card-preview-chip">
                <svg width="32" height="24" viewBox="0 0 40 30" fill="none"><rect x="0.5" y="0.5" width="39" height="29" rx="4.5" fill="#2f6bff" fill-opacity="0.35"/><rect x="3" y="3" width="12" height="9" rx="2" fill="#2f6bff" fill-opacity="0.7"/><rect x="3" y="17" width="12" height="9" rx="2" fill="#2f6bff" fill-opacity="0.7"/><rect x="18" y="3" width="18" height="23" rx="3" fill="#2f6bff" fill-opacity="0.45"/></svg>
            </div>
            <div class="dash-card-preview-number">**** **** **** 4242</div>
            <div class="dash-card-preview-bottom">
                <div class="dash-card-preview-holder">
                    <span class="dash-card-preview-label">CARD HOLDER</span>
                    <span class="dash-card-preview-name">{{ strtoupper($user->firstname ?? 'USER') }} {{ strtoupper($user->lastname ?? '') }}</span>
                </div>
                <div class="dash-card-preview-expiry">
                    <span class="dash-card-preview-label">EXPIRES</span>
                    <span class="dash-card-preview-date">12/28</span>
                </div>
            </div>
            <a href="{{ setRoute('user.strowallet.virtual.card.index') }}" class="dash-card-preview-link" aria-label="Manage card">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
    </div>

    <!--===== INVEST & GROW =====-->
    <div class="dash-section-header">
        <span class="dash-section-title">Invest & Grow</span>
        <a href="{{ setRoute('user.investments.offers') }}" class="dash-section-link">See all</a>
    </div>
    <div class="dash-invest-scroll">
        @forelse($investment_plans->take(8) as $plan)
        <x-plan-card :plan="$plan" variant="compact" href="{{ route('user.invest.new') }}" />
        @empty
        <div class="dash-invest-card">
            <div class="dash-invest-badge">PLAN</div>
            <div class="dash-invest-name">No active plans</div>
            <div class="dash-invest-rate">New offers soon</div>
            <div class="dash-invest-duration">Check back shortly</div>
            <a href="{{ setRoute('user.investments.offers') }}" class="dash-invest-btn">{{ __('Invest Now') }}</a>
        </div>
        @endforelse
    </div>

    <!--===== ASSETS / HOLDINGS =====-->
    <div class="dash-section-header">
        <span class="dash-section-title">Assets</span>
        <a href="{{ route('user.rise.wallet') }}" class="dash-section-link">Wallet</a>
    </div>
    <div class="yh-assets">
        <div class="yh-asset">
            <div class="yh-asset-ico">US</div>
            <div class="yh-asset-mid">
                <span class="yh-asset-name">US Dollar</span>
                <span class="yh-asset-ticker">USD Main Account</span>
            </div>
            <div class="yh-asset-right">
                <span class="yh-asset-val">${{ number_format($usdBalance, 2) }}</span>
                <span class="yh-asset-chg flat">&mdash;</span>
            </div>
        </div>
        <div class="yh-asset">
            <div class="yh-asset-ico eu">EU</div>
            <div class="yh-asset-mid">
                <span class="yh-asset-name">Euro</span>
                <span class="yh-asset-ticker">EUR Account</span>
            </div>
            <div class="yh-asset-right">
                <span class="yh-asset-val">&euro;{{ number_format($eurBalance, 2) }}</span>
                <span class="yh-asset-chg flat">&mdash;</span>
            </div>
        </div>
        <div class="yh-asset">
            <div class="yh-asset-ico gbp">GB</div>
            <div class="yh-asset-mid">
                <span class="yh-asset-name">Pound Sterling</span>
                <span class="yh-asset-ticker">GBP Account</span>
            </div>
            <div class="yh-asset-right">
                <span class="yh-asset-val">&pound;{{ number_format($gbpBalance, 2) }}</span>
                <span class="yh-asset-chg flat">&mdash;</span>
            </div>
        </div>
    </div>

    <!--===== RECENT TRANSACTIONS =====-->
    <div class="dash-section-header">
        <span class="dash-section-title">Recent Transactions</span>
        <a href="{{ setRoute('user.transactions.index') }}" class="dash-section-link">View all</a>
    </div>
    <div class="dash-tx-list">
        @forelse($transactions->take(5) as $tx)
        @php
            $txDetails = is_string($tx->details) ? json_decode($tx->details) : ($tx->details ?? null);
            $isReceived = ($tx->attribute ?? '') === 'RECEIVED' || (in_array($tx->type ?? '', ['ADD-MONEY', 'TRANSFER-MONEY']) && ($tx->receiver_id ?? null) == auth()->id());
            $isCredit = $isReceived;
            if ($tx->type === 'MOBILE-WALLET-TRANSFER' && $txDetails) {
                $txLabel = $isReceived ? 'From: ' . ($txDetails->sender_name ?? 'Someone') : 'To: ' . ($txDetails->receiver_name ?? 'Someone');
            } else {
                $txLabel = $tx->type ?? 'Transaction';
            }
        @endphp
        <div class="dash-tx-item">
            <div class="dash-tx-icon dash-tx-icon-{{ $isCredit ? 'green' : 'red' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="{{ $isCredit ? '23 6 13.5 15.5 8.5 10.5 1 18' : '23 18 13.5 8.5 8.5 13.5 1 6' }}"/>
                </svg>
            </div>
            <div class="dash-tx-info">
                <span class="dash-tx-name">{{ $txLabel }}</span>
                <span class="dash-tx-date">{{ $tx->created_at ? $tx->created_at->diffForHumans() : '' }}</span>
            </div>
            <span class="dash-tx-amount {{ $isCredit ? 'dash-tx-positive' : 'dash-tx-negative' }}">{{ $isCredit ? '+' : '-' }}${{ number_format($tx->request_amount ?? 0, 2) }}</span>
        </div>
        @empty
        <div class="dash-tx-empty">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            <span>No transactions yet</span>
        </div>
        @endforelse
    </div>

    <!--===== CONTACT US =====-->
    <a href="{{ setRoute('frontend.contact') }}" class="dash-contact-row">
        <div class="dash-contact-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="dash-contact-text">
            <span class="dash-contact-title">Contact Us</span>
            <span class="dash-contact-sub">We're here to help 24/7</span>
        </div>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </a>

</div>
@endsection

@push("script")
<script>
(function(){
    // --- Persisted state keys ---
    var STORAGE_KEY_CURRENCY = 'dash_currency';
    var STORAGE_KEY_HIDDEN = 'dash_hidden';

    var currencySymbols = { usd: '$', eur: '€', gbp: '£' };
    var toggleBtn = document.getElementById('dashBalanceToggle');
    var balanceEl = document.getElementById('dashBalanceAmount');
    var openEye = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
    var closedEye = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

    // --- Balance visibility toggle (persisted) ---
    if (toggleBtn && balanceEl) {
        var savedHidden = localStorage.getItem(STORAGE_KEY_HIDDEN);
        if (savedHidden === 'true') {
            balanceEl.classList.add('dash-balance-hidden');
            toggleBtn.querySelector('svg')?.outerHTML !== undefined && (toggleBtn.innerHTML = closedEye);
        }
        toggleBtn.addEventListener('click', function() {
            var isHidden = balanceEl.classList.toggle('dash-balance-hidden');
            localStorage.setItem(STORAGE_KEY_HIDDEN, isHidden ? 'true' : 'false');
            this.innerHTML = isHidden ? closedEye : openEye;
        });
    }

    // --- Currency tab switching (persisted) ---
    var tabs = document.querySelectorAll('.dash-currency-tab');
    if (tabs.length && balanceEl) {
        var savedCurrency = localStorage.getItem(STORAGE_KEY_CURRENCY);
        if (savedCurrency) {
            var savedTab = document.querySelector('.dash-currency-tab[data-currency="' + savedCurrency + '"]');
            if (savedTab) {
                tabs.forEach(function(t) { t.classList.remove('active'); });
                savedTab.classList.add('active');
                var symbol = currencySymbols[savedCurrency] || '$';
                var stored = balanceEl.getAttribute('data-' + savedCurrency);
                if (stored) balanceEl.textContent = symbol + stored;
            }
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var currency = this.getAttribute('data-currency');
                if (!currency) return;
                tabs.forEach(function(t) { t.classList.remove('active'); });
                this.classList.add('active');
                localStorage.setItem(STORAGE_KEY_CURRENCY, currency);
                var symbol = currencySymbols[currency] || '$';
                var stored = balanceEl.getAttribute('data-' + currency);
                if (stored) {
                    balanceEl.classList.add('currency-switching');
                    setTimeout(function() {
                        balanceEl.textContent = symbol + stored;
                        balanceEl.classList.remove('currency-switching');
                    }, 120);
                }
            });
        });
    }

    // Copy account number helper
    window.copyAccountNo = function(accountNo, btn) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(accountNo).then(function() {
                var orig = btn.innerHTML;
                btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
                setTimeout(function() { btn.innerHTML = orig; }, 1500);
            });
        }
    };

    // Entrance animation
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('dash-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.yh-head, .dash-balance-card, .dash-card-preview, .dash-referral-banner, .dash-action-pill, .dash-invest-card, .yh-assets, .dash-tx-item, .dash-contact-row, .dash-section-header').forEach(function(el) {
        el.classList.add('dash-fade-in');
        observer.observe(el);
    });
})();
</script>
@endpush