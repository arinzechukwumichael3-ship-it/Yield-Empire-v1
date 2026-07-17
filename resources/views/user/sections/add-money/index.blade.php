@extends('user.layouts.rise-master')

@push('css')
<style>
/* Section rhythm */
.cd-stack > * + * { margin-top: 24px; }
.am-body--funds { gap: 24px; }

/* Crypto coin selection */
.cd-coin-list { display: flex; flex-direction: column; gap: 12px; }
.cd-coin-card { display: flex; align-items: center; gap: 14px; padding: 14px 16px; background: #111827; border: 1.5px solid #1E293B; border-radius: 14px; cursor: pointer; transition: all 0.15s; }
.cd-coin-card:hover { border-color: #3B82F6; }
.cd-coin-card.selected { border-color: #3B82F6; background: rgba(59,130,246,0.08); }
.cd-coin-icon { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; color: #fff; flex-shrink: 0; }
.cd-coin-info { flex: 1; display: flex; flex-direction: column; gap: 2px; }
.cd-coin-name { font-size: 15px; font-weight: 600; color: #fff; }
.cd-coin-network { font-size: 12px; color: #94A3B8; }
.cd-coin-badge { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 10px; background: #1E293B; color: #94A3B8; margin-left: 6px; vertical-align: middle; }
.cd-radio-dot { width: 22px; height: 22px; border-radius: 50%; border: 2px solid #334155; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; }
.cd-radio-dot.filled { border-color: #3B82F6; background: #3B82F6; }
.cd-radio-dot.filled::after { content: ""; width: 8px; height: 8px; border-radius: 50%; background: #fff; }
.cd-submit-btn { width: 100%; padding: 16px; background: #3B82F6; color: #fff; border: none; border-radius: 999px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.15s; }
.cd-submit-btn:disabled { background: #334155; color: #64748B; cursor: not-allowed; }
.cd-hint { font-size: 12px; color: #64748B; margin-top: 6px; display: block; }

[data-theme="light"] .cd-coin-card { background: #fff; border-color: #E2E8F0; }
[data-theme="light"] .cd-coin-name { color: #1F2937; }
[data-theme="light"] .cd-coin-badge { background: #F1F5F9; color: #64748B; }

/* Add Money log cards (theme-aware) */
.cd-log-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.cd-log-title { font-size: 16px; font-weight: 700; color: var(--text-primary); }
.cd-log-list { display: flex; flex-direction: column; gap: 12px; }
.cd-log-card {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 16px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    box-shadow: var(--card-shadow);
    animation: cdLogIn 0.3s ease both;
}
.cd-log-badge {
    width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    background: var(--inv-success-bg);
    color: var(--inv-success-text);
}
.cd-log-info { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 2px; }
.cd-log-name { font-size: 14px; font-weight: 600; color: var(--text-primary); }
.cd-log-date { font-size: 12px; color: var(--text-secondary); }
.cd-log-amount { font-size: 15px; font-weight: 700; color: var(--inv-success-text); white-space: nowrap; }
.cd-log-empty {
    padding: 20px; text-align: center; font-size: 13px;
    color: var(--text-secondary);
    background: var(--bg-card);
    border: 1px dashed var(--border-color);
    border-radius: 14px;
}
@keyframes cdLogIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
@media (prefers-reduced-motion: reduce) { .cd-log-card { animation: none; } }
</style>
@endpush

@section('content')
@php
$payment_gateways = $payment_gateways ?? [];
$coins = config("crypto_deposit.coins", []);
@endphp

<div class="am-header">
    <h1 class="am-header-title">{{ __('Add Funds') }}</h1>
</div>

<div class="am-body am-body--funds">
    {{-- Crypto Deposit Form (sole method) --}}
    <form method="POST" action="{{ route('user.crypto.deposit.store') }}" class="cd-stack">
        @csrf
        <div class="am-card">
            <div class="am-field-group">
                <label class="am-label">{{ __('Amount (USD)') }}</label>
                <div class="am-input-wrap">
                    <input type="number" name="amount" id="cryptoAmount" class="send-input" placeholder="0.00" step="0.01" min="10" required>
                    <span class="am-input-pill">USD</span>
                </div>
                <span class="cd-hint">{{ __('Minimum: $10.00') }}</span>
            </div>
        </div>

        <div class="am-card">
            <span class="am-label" style="display:block;margin-bottom:12px;">{{ __('Select Cryptocurrency') }}</span>
            <div class="cd-coin-list">
                @foreach($coins as $key => $coin)
                <label class="cd-coin-card" data-key="{{ $key }}">
                    <div class="cd-coin-icon" style="background:{{ $coin["color"] }}">
                        {{ $coin["symbol"] }}
                    </div>
                    <div class="cd-coin-info">
                        <span class="cd-coin-name">
                            {{ $coin["name"] }}
                            @if($coin["badge"])<span class="cd-coin-badge">{{ $coin["badge"] }}</span>@endif
                        </span>
                        <span class="cd-coin-network">{{ $coin["network"] }}</span>
                    </div>
                    <div class="cd-radio-dot"></div>
                    <input type="radio" name="coin_key" value="{{ $key }}" style="display:none">
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="cd-submit-btn" id="cryptoContinueBtn" disabled>
            {{ __('Continue to Deposit') }} &rarr;
        </button>
    </form>

    <!-- Add Money Log -->
    <div class="cd-log-section">
        <div class="cd-log-head">
            <span class="cd-log-title">{{ __('Add Money Log') }}</span>
            <a href="{{ setRoute('user.transactions.index') }}" class="am-log-link">{{ __('View More') }}</a>
        </div>
        <div class="cd-log-list">
            @forelse(($transactions ?? collect([]))->take(5) as $i => $tx)
            <div class="cd-log-card" style="animation-delay: {{ $i * 50 }}ms">
                <div class="cd-log-badge">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                </div>
                <div class="cd-log-info">
                    <span class="cd-log-name">{{ __('Add Money') }}</span>
                    <span class="cd-log-date">{{ $tx->created_at ? $tx->created_at->diffForHumans() : '' }}</span>
                </div>
                <span class="cd-log-amount">+${{ number_format($tx->request_amount ?? 0, 2) }}</span>
            </div>
            @empty
            <div class="cd-log-empty">{{ __('No deposits yet') }}</div>
            @endforelse
        </div>
    </div>
</div>

@push('script')
<script>
(function() {
    var coinCards = document.querySelectorAll('.cd-coin-card');
    var amountInput = document.getElementById('cryptoAmount');
    var continueBtn = document.getElementById('cryptoContinueBtn');
    function checkForm() {
        var selected = document.querySelector('.cd-coin-card.selected');
        var amount = parseFloat(amountInput.value);
        continueBtn.disabled = !(selected && amount >= 10);
    }
    coinCards.forEach(function(card) {
        card.addEventListener('click', function() {
            coinCards.forEach(function(c) {
                c.classList.remove('selected');
                c.querySelector('.cd-radio-dot').classList.remove('filled');
                c.querySelector('input[type="radio"]').checked = false;
            });
            this.classList.add('selected');
            this.querySelector('.cd-radio-dot').classList.add('filled');
            this.querySelector('input[type="radio"]').checked = true;
            checkForm();
        });
    });
    if (amountInput) amountInput.addEventListener('input', checkForm);
})();
</script>
@endpush
@endsection