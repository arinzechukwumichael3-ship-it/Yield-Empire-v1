@extends("user.layouts.rise-master")

@push("css")
<style>
.cd-page { background: var(--bg-primary); min-height: calc(100vh - 72px); padding-bottom: 24px; }
.cd-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 16px; position: sticky; top: 0; background: var(--bg-primary); z-index: 10; }
.cd-header-left { display: flex; align-items: center; gap: 12px; }
.cd-back { width: 36px; height: 36px; border-radius: 50%; background: var(--bg-card); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); box-shadow: 0 1px 3px rgba(0,0,0,0.06); text-decoration: none; }
.cd-title { font-size: 18px; font-weight: 700; color: var(--text-primary); }
.cd-body { padding: 0 16px; display: flex; flex-direction: column; gap: 20px; }
.cd-card { background: var(--bg-card); border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
.cd-label { font-size: 14px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; display: block; }
.cd-input-wrap { display: flex; align-items: center; border: 1.5px solid var(--border-color); border-radius: 12px; overflow: hidden; transition: border-color 0.15s; }
.cd-input-wrap:focus-within { border-color: var(--accent); }
.cd-input { flex: 1; border: none; outline: none; padding: 14px 16px; font-size: 18px; font-weight: 600; color: var(--text-primary); background: transparent; }
.cd-input::placeholder { color: var(--placeholder); font-weight: 400; font-size: 16px; }
.cd-currency-pill { background: var(--accent); color: var(--text-on-accent); padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-right: 6px; }
.cd-hint { font-size: 12px; color: var(--text-muted); margin-top: 6px; }
.cd-section-title { font-size: 16px; font-weight: 700; color: var(--text-primary); }
.cd-coin-list { display: flex; flex-direction: column; gap: 10px; }
.cd-coin-card { display: flex; align-items: center; gap: 14px; padding: 14px 16px; background: var(--bg-card); border: 1.5px solid var(--border-color); border-radius: 14px; cursor: pointer; transition: all 0.15s; }
.cd-coin-card:hover { border-color: var(--border-strong); }
.cd-coin-card.selected { border-color: var(--accent); background: var(--accent-soft); }
.cd-coin-icon { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; color: var(--text-on-accent); flex-shrink: 0; }
.cd-coin-info { flex: 1; display: flex; flex-direction: column; gap: 2px; }
.cd-coin-name { font-size: 15px; font-weight: 600; color: var(--text-primary); }
.cd-coin-network { font-size: 12px; color: var(--text-muted); }
.cd-coin-badge { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 10px; background: var(--bg-primary); color: var(--text-secondary); margin-left: 6px; vertical-align: middle; }
.cd-radio-dot { width: 22px; height: 22px; border-radius: 50%; border: 2px solid var(--border-strong); display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; }
.cd-radio-dot.filled { border-color: var(--accent); background: var(--accent); }
.cd-radio-dot.filled::after { content: ""; width: 8px; height: 8px; border-radius: 50%; background: var(--text-on-accent); }
.cd-submit-btn { width: 100%; padding: 16px; background: var(--accent); color: var(--text-on-accent); border: none; border-radius: 999px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.15s; }
.cd-submit-btn:disabled { background: var(--border-strong); color: var(--text-muted); cursor: not-allowed; }
.cd-submit-btn:not(:disabled):hover { background: var(--blue); }
.cd-error { font-size: 12px; color: var(--danger); margin-top: 4px; }
</style>
@endpush

@section("content")
<div class="cd-page">
    <div class="cd-header">
        <div class="cd-header-left">
            <a href="{{ setRoute("user.rise.wallet") }}" class="cd-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            </a>
            <span class="cd-title">Fund Wallet</span>
        </div>
    </div>

    <form method="POST" action="{{ setRoute("user.crypto.deposit.store") }}" id="depositForm">
        @csrf
        <div class="cd-body">
            <div class="cd-card">
                <label class="cd-label">Enter Amount</label>
                <div class="cd-input-wrap">
                    <input type="number" name="amount" id="amount" class="cd-input"
                           placeholder="0.00" step="0.01" min="10"
                           value="{{ old("amount") }}" required>
                    <span class="cd-currency-pill">USD</span>
                </div>
                <span class="cd-hint">Minimum: $10.00</span>
                @error("amount")<div class="cd-error">{{ $message }}</div>@enderror
            </div>

            <div>
                <span class="cd-section-title">Choose Crypto</span>
                <div class="cd-coin-list" style="margin-top:12px">
                    @foreach($coins as $key => $coin)
                    <label class="cd-coin-card" data-key="{{ $key }}">
                        @if(!empty($coin["logo_image"]))
                        <div class="cd-coin-icon" style="background:{{ $coin["color"] }};overflow:hidden;">
                            <img src="{{ $coin["logo_image"] }}" alt="{{ $coin["coin"] }}" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        @else
                        <div class="cd-coin-icon" style="background:{{ $coin["color"] }}">
                            {{ $coin["symbol"] }}
                        </div>
                        @endif
                        <div class="cd-coin-info">
                            <span class="cd-coin-name">
                                {{ $coin["name"] }}
                                @if($coin["badge"])<span class="cd-coin-badge">{{ $coin["badge"] }}</span>@endif
                            </span>
                            <span class="cd-coin-network">{{ $coin["network"] }}</span>
                        </div>
                        <div class="cd-radio-dot"></div>
                        <input type="radio" name="coin_key" value="{{ $key }}"
                               style="display:none" {{ old("coin_key") === $key ? "checked" : "" }}>
                    </label>
                    @endforeach
                </div>
                @error("coin_key")<div class="cd-error" style="margin-top:8px">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="cd-submit-btn" id="continueBtn" disabled>
                Continue to Deposit &rarr;
            </button>
        </div>
    </form>
</div>

@push("script")
<script>
document.addEventListener("DOMContentLoaded", function() {
    var coinCards = document.querySelectorAll(".cd-coin-card");
    var amountInput = document.getElementById("amount");
    var continueBtn = document.getElementById("continueBtn");

    function checkForm() {
        var selected = document.querySelector(".cd-coin-card.selected");
        var amount = parseFloat(amountInput.value);
        continueBtn.disabled = !(selected && amount >= 10);
    }

    coinCards.forEach(function(card) {
        card.addEventListener("click", function() {
            coinCards.forEach(function(c) {
                c.classList.remove("selected");
                c.querySelector(".cd-radio-dot").classList.remove("filled");
                c.querySelector('input[type="radio"]').checked = false;
            });
            this.classList.add("selected");
            this.querySelector(".cd-radio-dot").classList.add("filled");
            this.querySelector('input[type="radio"]').checked = true;
            checkForm();
        });
    });

    amountInput.addEventListener("input", checkForm);

    var checked = document.querySelector('.cd-coin-card input[type="radio"]:checked');
    if (checked) {
        var parent = checked.closest(".cd-coin-card");
        if (parent) {
            parent.classList.add("selected");
            parent.querySelector(".cd-radio-dot").classList.add("filled");
            checkForm();
        }
    }
});
</script>
@endpush
@endsection
