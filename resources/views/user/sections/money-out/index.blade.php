@extends('user.layouts.rise-master')

@push('css')
<style>
.mo-tabs { display: flex; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; padding: 4px; margin-bottom: 20px; }
.mo-tab { flex: 1; padding: 14px 8px; border-radius: 11px; font-size: 14px; font-weight: 600; color: var(--text-secondary); text-align: center; cursor: pointer; transition: all 0.15s; border: none; background: none; -webkit-tap-highlight-color: transparent; }
.mo-tab.active { background: var(--accent); color: var(--text-on-accent); }
.mo-tab-content { display: none; }
.mo-tab-content.active { display: block; }

.mo-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; margin-bottom: 16px; }
.mo-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; display: block; }
.mo-label .req { color: var(--danger); }
.mo-input-wrap { display: flex; align-items: center; border: 1.5px solid var(--border-color); border-radius: 12px; overflow: hidden; transition: border-color 0.15s; background: var(--bg-primary); }
.mo-input-wrap:focus-within { border-color: var(--accent); }
.mo-input { flex: 1; border: none; outline: none; padding: 14px 16px; font-size: 16px; font-weight: 500; color: var(--text-primary); background: transparent; min-width: 0; }
.mo-input::placeholder { color: var(--placeholder); }
.mo-input-pill { padding: 0 14px; font-size: 13px; font-weight: 600; color: var(--text-secondary); background: rgba(127,127,127,0.08); align-self: stretch; display: flex; align-items: center; white-space: nowrap; }
.mo-field-group { margin-bottom: 16px; }
.mo-field-group:last-child { margin-bottom: 0; }
.mo-hint { font-size: 12px; color: var(--text-muted); margin-top: 6px; display: block; }
.mo-error { font-size: 12px; color: var(--danger); margin-top: 4px; display: block; }

.mo-submit-btn { width: 100%; padding: 16px; border-radius: 100px; font-size: 16px; font-weight: 700; border: none; background: var(--accent); color: var(--text-on-accent); cursor: pointer; transition: opacity 0.15s, transform 0.15s; -webkit-tap-highlight-color: transparent; }
.mo-submit-btn:hover { opacity: 0.92; }
.mo-submit-btn:active { transform: scale(0.98); }
.mo-submit-btn:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

.mo-select { width: 100%; padding: 14px 16px; border: 1.5px solid var(--border-color); border-radius: 12px; font-size: 16px; background: var(--bg-primary); outline: none; color: var(--text-primary); -webkit-appearance: none; appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2394A3B8%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22%3E%3Cpolyline points=%226 9 12 15 18 9%22%3E%3C/polyline%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 14px center; padding-right: 40px; }

/* Crypto coin list */
.cw-coin-list { display: flex; flex-direction: column; gap: 8px; }
.cw-coin-card { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid var(--border-color); border-radius: 12px; cursor: pointer; transition: all 0.15s; background: var(--bg-primary); }
.cw-coin-card:hover { border-color: var(--border-strong); }
.cw-coin-card.selected { border-color: var(--accent); background: var(--accent-soft); }
.cw-coin-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; color: var(--text-on-accent); flex-shrink: 0; }
.cw-coin-info { flex: 1; }
.cw-coin-name { font-size: 14px; font-weight: 600; color: var(--text-primary); display: block; }
.cw-coin-network { font-size: 12px; color: var(--text-muted); }
.cw-coin-badge { display: inline-block; padding: 2px 8px; border-radius: 100px; font-size: 10px; font-weight: 700; background: var(--accent-soft); color: var(--accent); margin-left: 6px; vertical-align: middle; }
.cw-radio-dot { width: 20px; height: 20px; border-radius: 50%; border: 2px solid var(--border-strong); display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; }
.cw-radio-dot.filled { border-color: var(--accent); }
.cw-radio-dot.filled::after { content: ""; width: 10px; height: 10px; border-radius: 50%; background: var(--accent); }

.mo-warning { display: flex; align-items: flex-start; gap: 8px; padding: 12px 14px; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.18); border-radius: 10px; font-size: 12px; color: #FCA5A5; line-height: 1.5; margin-bottom: 16px; }
.mo-warning svg { flex-shrink: 0; margin-top: 1px; }
</style>
@endpush

@section('content')
@php
$coins = config("crypto_deposit.coins", []);
@endphp
<div class="am-header">
    <h1 class="am-header-title">{{ __('Withdraw') }}</h1>
</div>
<div class="am-body">
    <p class="mo-hint" style="margin-bottom:16px;">Choose how you'd like to withdraw. International transfers settle in 1-5 business days; crypto withdrawals are processed after network confirmation.</p>

    {{-- Tab Toggle --}}
    <div class="mo-tabs" role="tablist">
        <button class="mo-tab active" data-tab="international" role="tab">🌍 International Bank</button>
        <button class="mo-tab" data-tab="crypto" role="tab">₿ Crypto</button>
    </div>

    {{-- ====== TAB 1: International Bank Transfer ====== --}}
    <div class="mo-tab-content active" id="tab-international">
        <form method="POST" action="{{ route('user.money-out.international.submit') }}" id="internationalWithdrawForm">
            @csrf
            <div class="mo-card">
                <div class="mo-field-group">
                    <label class="mo-label">{{ __('Recipient Full Name') }}<span class="req">*</span></label>
                    <div class="mo-input-wrap">
                        <input type="text" name="recipient_name" class="mo-input" placeholder="e.g. Jane Smith" value="{{ old('recipient_name') }}">
                    </div>
                    @error('recipient_name')<span class="mo-error">{{ $message }}</span>@enderror
                </div>
                <div class="mo-field-group">
                    <label class="mo-label">{{ __('Bank Name') }}<span class="req">*</span></label>
                    <div class="mo-input-wrap">
                        <input type="text" name="bank_name" class="mo-input" placeholder="e.g. Barclays" value="{{ old('bank_name') }}">
                    </div>
                    @error('bank_name')<span class="mo-error">{{ $message }}</span>@enderror
                </div>
                <div class="mo-field-group">
                    <label class="mo-label">{{ __('Account Number / IBAN') }}<span class="req">*</span></label>
                    <div class="mo-input-wrap">
                        <input type="text" name="account_number" class="mo-input" placeholder="e.g. GB29NWBK60161331926819" value="{{ old('account_number') }}">
                    </div>
                    @error('account_number')<span class="mo-error">{{ $message }}</span>@enderror
                </div>
                <div class="mo-field-group">
                    <label class="mo-label">{{ __('SWIFT / BIC Code') }}<span class="req">*</span></label>
                    <div class="mo-input-wrap">
                        <input type="text" name="swift_code" class="mo-input" placeholder="e.g. NWBKGB2L" value="{{ old('swift_code') }}">
                    </div>
                    @error('swift_code')<span class="mo-error">{{ $message }}</span>@enderror
                </div>
                <div class="mo-field-group">
                    <label class="mo-label">{{ __('Country') }}<span class="req">*</span></label>
                    <div class="mo-input-wrap">
                        <input type="text" name="country" class="mo-input" placeholder="e.g. United Kingdom" value="{{ old('country') }}">
                    </div>
                    @error('country')<span class="mo-error">{{ $message }}</span>@enderror
                </div>
                <div class="mo-field-group">
                    <label class="mo-label">{{ __('Amount (USD)') }}<span class="req">*</span></label>
                    <div class="mo-input-wrap">
                        <input type="number" step="0.01" min="10" name="amount" class="mo-input" placeholder="0.00" value="{{ old('amount') }}">
                        <span class="mo-input-pill">USD</span>
                    </div>
                    <span class="mo-hint">Minimum: $10.00 · Flat fee: $15.00</span>
                    @error('amount')<span class="mo-error">{{ $message }}</span>@enderror
                </div>
                <div class="mo-field-group">
                    <label class="mo-label">{{ __('Transfer Rail') }}</label>
                    <select class="mo-select" name="rail">
                        <option value="swift" {{ old('rail', 'swift') == 'swift' ? 'selected' : '' }}>SWIFT — Global (1-5 business days)</option>
                        <option value="sepa" {{ old('rail') == 'sepa' ? 'selected' : '' }}>SEPA — Europe (1-2 business days)</option>
                        <option value="ach" {{ old('rail') == 'ach' ? 'selected' : '' }}>ACH — US (2-3 business days)</option>
                    </select>
                </div>
                <div class="mo-warning">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>International transfers use SWIFT / SEPA / ACH rails. A flat $15.00 fee applies and is deducted from your balance.</span>
                </div>
                <button type="submit" class="mo-submit-btn">{{ __('Submit Withdrawal') }} &rarr;</button>
            </div>
        </form>
    </div>

    {{-- ====== TAB 2: Crypto Withdrawal ====== --}}
    <div class="mo-tab-content" id="tab-crypto">
        <form method="POST" action="{{ route('user.money-out.crypto.submit') }}" id="cryptoWithdrawForm">
            @csrf
            <div class="mo-card">
                <div class="mo-field-group">
                    <label class="mo-label">{{ __('Destination Wallet Address') }}<span class="req">*</span></label>
                    <div class="mo-input-wrap">
                        <input type="text" class="mo-input" id="walletAddress" name="wallet_address" placeholder="Enter the recipient's wallet address" value="{{ old('wallet_address') }}" autocomplete="off">
                    </div>
                    <span class="mo-hint" id="addressHint">Paste the destination wallet address carefully. Addresses are case-sensitive.</span>
                    @error('wallet_address')<span class="mo-error">{{ $message }}</span>@enderror
                </div>
                <div class="mo-field-group">
                    <label class="mo-label">{{ __('Amount (USD)') }}<span class="req">*</span></label>
                    <div class="mo-input-wrap">
                        <input type="number" step="0.01" min="10" name="amount" class="mo-input" id="cryptoAmount" placeholder="0.00" value="{{ old('amount') }}">
                        <span class="mo-input-pill">USD</span>
                    </div>
                    <span class="mo-hint">Minimum: $10.00</span>
                    @error('amount')<span class="mo-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="mo-card">
                <span class="mo-label" style="display:block;margin-bottom:12px;">{{ __('Select Cryptocurrency') }}</span>
                <div class="cw-coin-list">
                    @foreach($coins as $key => $coin)
                    <label class="cw-coin-card" data-key="{{ $key }}">
                        <div class="cw-coin-icon" style="background:{{ $coin["color"] }}">
                            {{ $coin["symbol"] }}
                        </div>
                        <div class="cw-coin-info">
                            <span class="cw-coin-name">
                                {{ $coin["name"] }}
                                @if($coin["badge"])<span class="cw-coin-badge">{{ $coin["badge"] }}</span>@endif
                            </span>
                            <span class="cw-coin-network">{{ $coin["network"] }}</span>
                        </div>
                        <div class="cw-radio-dot"></div>
                        <input type="radio" name="coin_key" value="{{ $key }}" style="display:none" {{ old("coin_key") === $key ? "checked" : "" }}>
                    </label>
                    @endforeach
                </div>
                @error('coin_key')<span class="mo-error" style="margin-top:8px;">{{ $message }}</span>@enderror
            </div>

            <div class="mo-warning">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    <strong>⚠️ Wrong network = lost funds.</strong><br>
                    Always confirm the destination wallet address matches the network you selected. Sending USDT (TRC20) to a BEP20/ERC20 address will result in permanent loss of funds.
                </div>
            </div>

            <button type="submit" class="mo-submit-btn" id="cryptoSubmitBtn" disabled>{{ __('Withdraw Crypto') }}</button>
        </form>
    </div>

    <!-- Transaction Log -->
    <div class="mo-card" style="margin-top:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div class="am-card-title" style="margin-bottom:0;">{{ __('Money Out Log') }}</div>
            <a href="{{ setRoute('user.transactions.index', 'money-out') }}" class="am-log-link">{{ __('View More') }} <i class="las la-chevron-right"></i></a>
        </div>
        @include('user.components.transaction.log', compact('transactions'))
    </div>
</div>

@push('script')
<script>
window.__hasVirtualCard = {{ $hasVirtualCard ? 'true' : 'false' }};
window.__virtualCardUrl = "{{ $virtualCardUrl }}";
window.__cardFee = {{ get_virtual_card_fee() }};
document.addEventListener("DOMContentLoaded", function() {
    // Tab switching
    document.querySelectorAll('.mo-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.mo-tab').forEach(function(t) { t.classList.remove('active'); });
            document.querySelectorAll('.mo-tab-content').forEach(function(c) { c.classList.remove('active'); });
            this.classList.add('active');
            document.getElementById('tab-' + this.dataset.tab).classList.add('active');
        });
    });

    // Virtual card gate: block withdrawal submit without a $10 virtual card
    function gateVirtualCard(e) {
        if (!window.__hasVirtualCard) {
            e.preventDefault();
            alert("To withdraw you must first get a virtual card for $" + window.__cardFee + " USD.\n\nYour virtual card unlocks withdrawals from your EnzoBank account.");
            window.location = window.__virtualCardUrl;
            return false;
        }
    }
    var intlForm = document.getElementById('internationalWithdrawForm');
    var cryptoForm = document.getElementById('cryptoWithdrawForm');
    if (intlForm) intlForm.addEventListener('submit', gateVirtualCard);
    if (cryptoForm) cryptoForm.addEventListener('submit', gateVirtualCard);

    // Crypto coin selection + validation
    var coinCards   = document.querySelectorAll('.cw-coin-card');
    var addressInput = document.getElementById('walletAddress');
    var amountInput  = document.getElementById('cryptoAmount');
    var submitBtn    = document.getElementById('cryptoSubmitBtn');
    var addressHint  = document.getElementById('addressHint');

    var addressRegex = {
        btc:        /^(bc1|[13])[a-zA-HJ-NP-Z0-9]{25,39}$/,
        usdt_trc20: /^T[a-zA-HJ-NP-Z0-9]{25,34}$/,
        usdt_bep20: /^0x[a-fA-F0-9]{40}$/,
        eth_erc20:  /^0x[a-fA-F0-9]{40}$/,
        bch:        /^(bitcoincash:|[pqrstuvwxyz23456789]{25,})/
    };

    function updateAddressHint() {
        var selected = document.querySelector('.cw-coin-card.selected');
        if (!selected || !addressHint) return;
        var key = selected.dataset.key;
        var map = {
            btc: 'Bitcoin (starts with 1, 3 or bc1)',
            usdt_trc20: 'Tron (starts with T)',
            usdt_bep20: 'Ethereum / BEP20 (starts with 0x)',
            eth_erc20: 'Ethereum / ERC20 (starts with 0x)',
            bch: 'Bitcoin Cash'
        };
        addressHint.textContent = 'Expected format: ' + (map[key] || 'valid wallet address') + '.';
    }

    function checkCryptoForm() {
        var selected = document.querySelector('.cw-coin-card.selected');
        var hasAddress = addressInput && addressInput.value.trim().length >= 20;
        var hasAmount = amountInput && parseFloat(amountInput.value) >= 10;
        submitBtn.disabled = !(selected && hasAddress && hasAmount);
    }

    coinCards.forEach(function(card) {
        card.addEventListener('click', function() {
            coinCards.forEach(function(c) {
                c.classList.remove('selected');
                c.querySelector('.cw-radio-dot').classList.remove('filled');
                c.querySelector('input[type="radio"]').checked = false;
            });
            this.classList.add('selected');
            this.querySelector('.cw-radio-dot').classList.add('filled');
            this.querySelector('input[type="radio"]').checked = true;
            updateAddressHint();
            checkCryptoForm();
        });
    });

    if (addressInput) addressInput.addEventListener('input', checkCryptoForm);
    if (amountInput)  amountInput.addEventListener('input', checkCryptoForm);

    // Restore selected coin from old() input
    var checked = document.querySelector('.cw-coin-card input[type="radio"]:checked');
    if (checked) {
        var parent = checked.closest('.cw-coin-card');
        if (parent) {
            parent.classList.add('selected');
            parent.querySelector('.cw-radio-dot').classList.add('filled');
            updateAddressHint();
            checkCryptoForm();
        }
    }
});
</script>
@endpush
@endsection
