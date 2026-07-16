@extends('user.layouts.rise-master')

@push('css')
<style>
.cw-body { padding: 0 16px 24px; }
.cw-card { background: #111827; border: 1px solid #1E293B; border-radius: 16px; padding: 20px; margin-bottom: 16px; }
.cw-label { font-size: 13px; font-weight: 600; color: #94A3B8; margin-bottom: 8px; display: block; }
.cw-input-wrap { display: flex; align-items: center; border: 1.5px solid #334155; border-radius: 12px; overflow: hidden; transition: border-color 0.15s; background: #1E293B; }
.cw-input-wrap:focus-within { border-color: #3B82F6; }
.cw-input { flex: 1; border: none; outline: none; padding: 14px 16px; font-size: 16px; font-weight: 500; color: #fff; background: transparent; min-width: 0; }
.cw-input::placeholder { color: #4B5563; }
.cw-select { width:100%; padding:14px 16px; border:1.5px solid #334155; border-radius:12px; font-size:16px; background:#1E293B; outline:none; color:#fff; -webkit-appearance:none; appearance:none; background-image:url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2394A3B8%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22%3E%3Cpolyline points=%226 9 12 15 18 9%22%3E%3C/polyline%3E%3C/svg%3E');background-repeat:no-repeat;background-position:right 14px center;padding-right:40px; }
.cw-select:focus { border-color:#3B82F6; }
.cw-coin-list { display: flex; flex-direction: column; gap: 8px; }
.cw-coin-card { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid #334155; border-radius: 12px; cursor: pointer; transition: all 0.15s; background: #1E293B; }
.cw-coin-card:hover { border-color: #3B82F6; }
.cw-coin-card.selected { border-color: #3B82F6; background: rgba(59,130,246,0.08); }
.cw-coin-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; color: #fff; flex-shrink: 0; }
.cw-coin-info { flex: 1; }
.cw-coin-name { font-size: 14px; font-weight: 600; color: #fff; display: block; }
.cw-coin-network { font-size: 12px; color: #94A3B8; }
.cw-coin-badge { display: inline-block; padding: 2px 8px; border-radius: 100px; font-size: 10px; font-weight: 700; background: rgba(59,130,246,0.15); color: #3B82F6; margin-left: 6px; vertical-align: middle; }
.cw-radio-dot { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #334155; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; }
.cw-radio-dot.filled { border-color: #3B82F6; }
.cw-radio-dot.filled::after { content: ""; width: 10px; height: 10px; border-radius: 50%; background: #3B82F6; }
.cw-submit { width:100%; padding:16px; border-radius:100px; font-size:16px; font-weight:700; border:none; background:linear-gradient(135deg, #3B82F6, #2563EB); color:#fff; cursor:pointer; transition:opacity 0.15s, transform 0.15s; }
.cw-submit:hover { opacity:0.92; }
.cw-submit:disabled { opacity:0.4; cursor:not-allowed; }
.cw-warning { display:flex; align-items:flex-start; gap:8px; padding:12px 14px; background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.15); border-radius:10px; font-size:12px; color:#FCA5A5; line-height:1.5; margin-bottom:16px; }
.cw-warning svg { flex-shrink:0; margin-top:1px; }
.cw-hint { font-size: 12px; color: #64748B; margin-top: 6px; display: block; }
[data-theme="light"] .cw-card { background: #fff; border-color: #E2E8F0; }
[data-theme="light"] .cw-input-wrap { background: #F8FAFC; border-color: #D1D5DB; }
[data-theme="light"] .cw-input { color: #1F2937; }
[data-theme="light"] .cw-coin-card { background: #fff; border-color: #E2E8F0; }
[data-theme="light"] .cw-coin-name { color: #1F2937; }
[data-theme="light"] .cw-select { background: #fff; border-color: #D1D5DB; color: #1F2937; }
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ __('Crypto Withdraw') }}</h1>
</div>
<div class="am-body">
    <form method="POST" action="{{ route('user.money-out.crypto.submit') }}">
        @csrf
        <div class="cw-body">
            {{-- Wallet Address --}}
            <div class="cw-card">
                <label class="cw-label">{{ __('Destination Wallet Address') }}</label>
                <div class="cw-input-wrap">
                    <input type="text" class="cw-input" id="walletAddress" name="wallet_address" placeholder="Enter the recipient's wallet address" required>
                </div>
                <span class="cw-hint">Paste the recipient's wallet address carefully. Addresses are case-sensitive.</span>
            </div>

            {{-- Amount --}}
            <div class="cw-card">
                <label class="cw-label">{{ __('Amount') }}</label>
                <div class="cw-input-wrap">
                    <input type="number" step="0.00000001" min="0.00001" class="cw-input" id="cwAmount" name="amount" placeholder="0.00" required>
                    <span class="cw-input-pill" style="padding:0 14px;font-size:13px;font-weight:600;color:#94A3B8;background:rgba(255,255,255,0.04);display:flex;align-items:center;white-space:nowrap;">USD</span>
                </div>
                <span class="cw-hint">Enter the amount you want to withdraw in USD equivalent.</span>
            </div>

            {{-- Choose Crypto --}}
            <div class="cw-card">
                <span class="cw-label">{{ __('Select Cryptocurrency') }}</span>
                <div class="cw-coin-list" style="margin-top:12px">
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
                        <input type="radio" name="coin_key" value="{{ $key }}" style="display:none">
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Network Warning --}}
            <div class="cw-warning">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    <strong>⚠️ Wrong network = lost funds.</strong><br>
                    Always confirm the destination wallet address matches the network you selected. Sending USDT (TRC20) to a BEP20 address will result in permanent loss of funds.
                </div>
            </div>

            <button type="submit" class="cw-submit" id="cwSubmitBtn" disabled>{{ __('Withdraw Crypto') }}</button>
        </div>
    </form>
</div>

@push('script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    var coinCards = document.querySelectorAll(".cw-coin-card");
    var addressInput = document.getElementById("walletAddress");
    var amountInput = document.getElementById("cwAmount");
    var submitBtn = document.getElementById("cwSubmitBtn");

    function checkForm() {
        var selected = document.querySelector(".cw-coin-card.selected");
        var hasAddress = addressInput && addressInput.value.trim().length >= 10;
        var hasAmount = amountInput && parseFloat(amountInput.value) > 0;
        submitBtn.disabled = !(selected && hasAddress && hasAmount);
    }

    coinCards.forEach(function(card) {
        card.addEventListener("click", function() {
            coinCards.forEach(function(c) {
                c.classList.remove("selected");
                c.querySelector(".cw-radio-dot").classList.remove("filled");
                c.querySelector('input[type="radio"]').checked = false;
            });
            this.classList.add("selected");
            this.querySelector(".cw-radio-dot").classList.add("filled");
            this.querySelector('input[type="radio"]').checked = true;
            checkForm();
        });
    });

    if (addressInput) addressInput.addEventListener("input", checkForm);
    if (amountInput) amountInput.addEventListener("input", checkForm);
});
</script>
@endpush
@endsection
