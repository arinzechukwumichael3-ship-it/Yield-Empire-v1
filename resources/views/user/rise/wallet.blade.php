@extends('user.layouts.rise-master')

@push("css")
<style>
.rw-balance-dec { font-size: 0.6em; font-weight: 500; color: #9CA3AF; }
.rw-eye-toggle { cursor: pointer; user-select: none; }
.rw-balance-digits { position: relative; }
.rw-balance-digits[data-visible="false"] .rw-balance-int,
.rw-balance-digits[data-visible="false"] .rw-balance-dec { visibility: hidden; }
.rw-balance-digits[data-visible="false"]::after {
    content: "****";
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    font-size: 28px;
    letter-spacing: 4px;
    color: #D1D5DB;
}
.rw-section-link-pill {
    font-size: 13px;
    font-weight: 600;
    color: #3B82F6;
    padding: 6px 18px;
    border: 1.5px solid #3B82F6;
    border-radius: 20px;
    transition: all 0.15s;
}
.rw-section-link-pill:hover {
    background: #3B82F6;
    color: #fff;
}
</style>
@endpush

@section('content')
@php
$usdWallet = $usd_wallet ?? \App\Models\UserWallet::auth()->first();
$ngnWallet = $ngn_wallet ?? null;
$balance = $usdWallet ? $usdWallet->balance : 0;
$ngnBalance = $ngnWallet ? $ngnWallet->balance : 0;
$usdParts = explode('.', number_format($balance, 2));
$ngnParts = explode('.', number_format($ngnBalance, 2));
@endphp

<div class="rw-header">
    <div class="rw-header-left">
        <div class="rw-logo-icon">E</div>
        <span class="rw-header-title">EnzoBank Wallet</span>
    </div>
    <a href="#" class="rw-bell">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
    </a>
</div>

<div class="rw-body">
    <!-- Currency Toggle -->
    <div class="rw-currency-toggle">
        <button class="rw-curr-btn active" data-curr="usd">🇺🇸 USD Wallet</button>
        <button class="rw-curr-btn" data-curr="gbp">🇬🇧 GBP Wallet</button>
    </div>

    <!-- USD Wallet -->
    <div class="rw-wallet-content active" id="wallet-usd">
        <div class="rw-balance-display">
            <span class="rw-balance-label">USD Balance <span class="rw-eye-toggle" data-visible="true">👁</span></span>
            <span class="rw-balance-digits" data-visible="true">$<span class="rw-balance-int">{{ $usdParts[0] }}</span>.<span class="rw-balance-dec">{{ $usdParts[1] }}</span></span>
        </div>
        <div class="rw-dots">
            <span class="rw-dot active"></span>
            <span class="rw-dot"></span>
        </div>
        <div class="rw-actions">
            <a href="{{ setRoute('user.money-out.index') }}" class="rw-action">
                <div class="rw-action-icon light">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                </div>
                <span>Withdraw</span>
            </a>
            <a href="{{ setRoute('user.crypto.deposit.index') }}" class="rw-action">
                <div class="rw-action-icon blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <span>Fund</span>
            </a>
            <a href="#" class="rw-action">
                <div class="rw-action-icon light">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="4" height="4" rx="1"/><rect x="17" y="3" width="4" height="4" rx="1"/><rect x="3" y="17" width="4" height="4" rx="1"/><rect x="17" y="17" width="4" height="4" rx="1"/></svg>
                </div>
                <span>More</span>
            </a>
        </div>
        <div class="rw-interest-row">
            <span>Wallet Interest • 8% P/A</span>
            <span>$0.00 ›</span>
        </div>
    </div>

    <!-- GBP Wallet -->
    <div class="rw-wallet-content" id="wallet-gbp">
        <div class="rw-balance-display">
            <span class="rw-balance-label">GBP Balance <span class="rw-eye-toggle" data-visible="true">👁</span></span>
            <span class="rw-balance-digits" data-visible="true">£<span class="rw-balance-int">{{ $ngnParts[0] }}</span>.<span class="rw-balance-dec">{{ $ngnParts[1] }}</span></span>
        </div>
        <div class="rw-dots">
            <span class="rw-dot"></span>
            <span class="rw-dot active"></span>
        </div>
        <div class="rw-actions">
            <a href="{{ setRoute('user.money-out.index') }}" class="rw-action">
                <div class="rw-action-icon light">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                </div>
                <span>Withdraw</span>
            </a>
            <a href="{{ setRoute('user.crypto.deposit.index') }}" class="rw-action">
                <div class="rw-action-icon blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <span>Fund</span>
            </a>
            <a href="#" class="rw-action">
                <div class="rw-action-icon light">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="4" height="4" rx="1"/><rect x="17" y="3" width="4" height="4" rx="1"/><rect x="3" y="17" width="4" height="4" rx="1"/><rect x="17" y="17" width="4" height="4" rx="1"/></svg>
                </div>
                <span>More</span>
            </a>
        </div>
        <div class="rw-vault-promo">
            <span class="rw-vault-emoji">🔒</span>
            <div class="rw-vault-text">
                <span class="rw-vault-title">Save with the Sterling Vault</span>
                <span class="rw-vault-sub">Lock your Sterling and earn up to 23% annual interest, paid monthly.</span>
            </div>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="rw-section-row">
        <span class="rw-section-title">Recent Transactions</span>
        <a href="{{ setRoute('user.transactions.index') }}" class="rw-section-link-pill">See all</a>
    </div>

    @if($transactions->count() > 0)
    <div class="rw-tx-list">
        @foreach($transactions->take(5) as $tx)
        <div class="rw-tx-item">
            <div class="rw-tx-icon {{ $tx->type === 'ADD-MONEY' ? 'green' : ($tx->type === 'MONEY-OUT' ? 'red' : 'blue') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    @if($tx->type === 'ADD-MONEY')
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                    @else
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/>
                    @endif
                </svg>
            </div>
            <div class="rw-tx-info">
                <span class="rw-tx-name">{{ $tx->type }}</span>
                <span class="rw-tx-date">{{ $tx->created_at->diffForHumans() }}</span>
            </div>
            <span class="rw-tx-amount {{ in_array($tx->type, ['ADD-MONEY', 'TRANSFER-MONEY']) && $tx->receiver_id == auth()->id() ? 'positive' : 'negative' }}">
                {{ in_array($tx->type, ['ADD-MONEY', 'TRANSFER-MONEY']) && $tx->receiver_id == auth()->id() ? '+' : '-' }}${{ number_format($tx->request_amount, 2) }}
            </span>
        </div>
        @endforeach
    </div>
    @else
    <div class="rw-empty">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5"><rect x="3" y="6" width="18" height="15" rx="2"/><path d="M3 10h18"/><path d="M7 15h2"/><path d="M11 15h6"/></svg>
        <span class="rw-empty-title">No transactions</span>
        <span class="rw-empty-sub">Your transactions will appear here</span>
    </div>
    @endif
</div>

@push("script")
<script>
// Currency toggle
document.querySelectorAll('.rw-curr-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.rw-curr-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.rw-wallet-content').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('wallet-' + this.dataset.curr).classList.add('active');
    });
});

// Eye toggle
document.querySelectorAll('.rw-eye-toggle').forEach(eye => {
    eye.addEventListener('click', function(e) {
        e.stopPropagation();
        const display = this.closest('.rw-balance-display');
        const digits = display.querySelector('.rw-balance-digits');
        const isVisible = digits.dataset.visible === 'true';
        digits.dataset.visible = isVisible ? 'false' : 'true';
        this.textContent = isVisible ? '👁‍🗨' : '👁';
        this.dataset.visible = isVisible ? 'false' : 'true';
    });
});
</script>
@endpush
@endsection
