@extends('user.layouts.rise-master')

@push('css')
<style>
.am-fund-tabs { display: flex; background: #1E293B; border-radius: 12px; padding: 4px; margin-bottom: 20px; }
.am-fund-tab { flex: 1; padding: 12px 8px; border-radius: 10px; font-size: 13px; font-weight: 600; color: #94A3B8; text-align: center; cursor: pointer; transition: all 0.15s; border: none; background: none; -webkit-tap-highlight-color: transparent; }
.am-fund-tab.active { background: #3B82F6; color: #fff; }
.am-fund-tab-content { display: none; }
.am-fund-tab-content.active { display: block; }

.cd-coin-list { display: flex; flex-direction: column; gap: 10px; }
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

[data-theme="light"] .am-fund-tabs { background: #E2E8F0; }
[data-theme="light"] .am-fund-tab { color: #64748B; }
[data-theme="light"] .am-fund-tab.active { background: #3B82F6; color: #fff; }
[data-theme="light"] .cd-coin-card { background: #fff; border-color: #E2E8F0; }
[data-theme="light"] .cd-coin-name { color: #1F2937; }
[data-theme="light"] .cd-coin-badge { background: #F1F5F9; color: #64748B; }
</style>
@endpush

@section('content')
@php
$payment_gateways = $payment_gateways ?? [];
$coins = config("crypto_deposit.coins", []);
@endphp

<div class="am-header">
    <h1 class="am-header-title">{{ __('Add Money') }}</h1>
</div>

<div class="am-body">
    {{-- Tab Toggle --}}
    <div class="am-fund-tabs" role="tablist">
        <button class="am-fund-tab active" data-tab="fiat" role="tab">🏦 Fiat Deposit</button>
        <button class="am-fund-tab" data-tab="crypto" role="tab">₿ Crypto Deposit</button>
    </div>

    {{-- ====== TAB 1: Fiat Deposit ====== --}}
    <div class="am-fund-tab-content active" id="tab-fiat">
        <!-- Exchange Rate Banner -->
        <div class="am-rate-banner">
            <span class="am-rate-label">{{ __('Exchange Rate') }}</span>
            <span class="am-rate-value" id="liveRate">--</span>
        </div>

        <!-- Form Card -->
        <form class="am-card" method="POST" action="{{ setRoute('user.add.money.submit') }}">
            @csrf
            <div class="am-card-title">{{ __('Add Funds') }}</div>

            <div class="am-field-group">
                <label class="am-label">{{ __('Amount') }}</label>
                <div class="am-input-wrap">
                    <input type="text" name="amount" placeholder="0.00" id="amount" maxlength="20" oninput="updatePreview()">
                    <span class="am-input-pill">{{ get_default_currency_code() ?? 'USD' }}</span>
                </div>
                <span class="am-hint" id="limitHint">Min: -- &nbsp;|&nbsp; Max: --</span>
            </div>

            <div class="am-field-group">
                <label class="am-label">{{ __('Payment Gateway') }}</label>
                <div class="am-input-wrap">
                    <select name="gateway_currency" id="gateway" onchange="updateGateway()">
                        <option value="" selected disabled>{{ __('Choose Gateway') }}</option>
                        @foreach ($payment_gateways ?? [] as $gateway)
                            @foreach ($gateway->currencies as $currency)
                                <option data-item="{{ $currency->getOnly(['currency_code','rate','min_limit','max_limit','percent_charge','fixed_charge','crypto'])->makeJson() }}" value="{{ $currency->alias }}">{{ $gateway->name . " " . $currency->currency_code }} @if ($gateway->isManual()) (Manual)@endif</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-top:8px;">
                <code class="am-hint" id="chargeShow">--</code>
            </div>

            <button type="submit" class="am-btn" style="margin-top:16px;">{{ __('Add Amount') }} →</button>
        </form>

        <!-- Preview Card -->
        <div class="am-card" id="previewSection" style="display:none;">
            <div class="am-card-title">{{ __('Add Money Preview') }}</div>
            
            <div class="am-preview-row">
                <div class="am-preview-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <span class="am-preview-label">{{ __('Request Amount') }}</span>
                <span class="am-preview-value" id="previewAmount">--</span>
            </div>
            <div class="am-preview-row">
                <div class="am-preview-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                </div>
                <span class="am-preview-label">{{ __('Exchange Rate') }}</span>
                <span class="am-preview-value" id="previewRate">--</span>
            </div>
            <div class="am-preview-row">
                <div class="am-preview-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <span class="am-preview-label">{{ __('Fees & Charges') }}</span>
                <span class="am-preview-value" id="previewFees">--</span>
            </div>
            <div class="am-preview-row">
                <div class="am-preview-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                </div>
                <span class="am-preview-label">{{ __('Will Get') }}</span>
                <span class="am-preview-value" id="previewWillGet">--</span>
            </div>
            <div class="am-preview-row">
                <div class="am-preview-icon" style="border-color:#059669;color:#059669;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <span class="am-preview-label">{{ __('Total Payable Amount') }}</span>
                <span class="am-preview-value" style="color:#059669;" id="previewTotal">--</span>
            </div>
        </div>
    </div>

    {{-- ====== TAB 2: Crypto Deposit ====== --}}
    <div class="am-fund-tab-content" id="tab-crypto">
        <form method="POST" action="{{ route('user.crypto.deposit.store') }}">
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
    </div>

    <!-- Add Money Log (shared) -->
    <div style="margin-top:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <span style="font-size:16px;font-weight:700;">{{ __('Add Money Log') }}</span>
            <a href="{{ setRoute('user.transactions.index') }}" class="am-log-link">{{ __('View More') }}</a>
        </div>
        <div class="rw-tx-list">
            @forelse(($transactions ?? collect([]))->take(5) as $tx)
            <div class="rw-tx-item">
                <div class="rw-tx-icon green">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                </div>
                <div class="rw-tx-info">
                    <span class="rw-tx-name">{{ __('Add Money') }}</span>
                    <span class="rw-tx-date">{{ $tx->created_at ? $tx->created_at->diffForHumans() : '' }}</span>
                </div>
                <span class="rw-tx-amount positive">+${{ number_format($tx->request_amount ?? 0, 2) }}</span>
            </div>
            @empty
            <div class="rw-empty" style="padding:20px;">
                <span class="rw-empty-title">{{ __('No deposits yet') }}</span>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('script')
<script>
// ── Tab switching ──
document.querySelectorAll('.am-fund-tab').forEach(function(tab) {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.am-fund-tab').forEach(function(t) { t.classList.remove('active'); });
        document.querySelectorAll('.am-fund-tab-content').forEach(function(c) { c.classList.remove('active'); });
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
});

// ── Fiat gateway preview ──
function updateGateway() {
    const sel = document.getElementById('gateway');
    const opt = sel.options[sel.selectedIndex];
    if (!opt || !opt.dataset.item) return;
    const data = JSON.parse(opt.dataset.item);
    document.getElementById('liveRate').textContent = '1 USD = ' + data.rate + ' ' + data.currency_code;
    document.getElementById('previewRate').textContent = '1 USD = ' + data.rate + ' ' + data.currency_code;
    document.getElementById('limitHint').textContent = 'Min: ' + data.min_limit + ' | Max: ' + data.max_limit;
    updatePreview();
}
function updatePreview() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const sel = document.getElementById('gateway');
    const opt = sel.options[sel.selectedIndex];
    if (!opt || !opt.dataset.item || amount <= 0) {
        document.getElementById('previewSection').style.display = 'none';
        return;
    }
    const data = JSON.parse(opt.dataset.item);
    document.getElementById('previewSection').style.display = 'block';
    document.getElementById('previewAmount').textContent = amount + ' USD';
    const rate = parseFloat(data.rate) || 1;
    const percentCharge = parseFloat(data.percent_charge) || 0;
    const fixedCharge = parseFloat(data.fixed_charge) || 0;
    const fees = (amount * percentCharge / 100) + fixedCharge;
    document.getElementById('previewFees').textContent = fees.toFixed(2) + ' USD';
    document.getElementById('previewWillGet').textContent = (amount * rate).toFixed(2) + ' ' + data.currency_code;
    document.getElementById('previewTotal').textContent = (amount + fees).toFixed(2) + ' USD';
    document.getElementById('chargeShow').textContent = 'Charge: ' + percentCharge + '% + ' + fixedCharge + ' ' + data.currency_code;
}
document.getElementById('amount')?.addEventListener('input', updatePreview);

// ── Crypto coin selection ──
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