@extends("user.layouts.rise-master")

@push("css")
<style>
.cd-addr-page { background: #F3F4F6; min-height: calc(100vh - 72px); padding-bottom: 24px; }
.cd-addr-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 16px; position: sticky; top: 0; background: #F3F4F6; z-index: 10; }
.cd-addr-header-left { display: flex; align-items: center; gap: 12px; }
.cd-addr-back { width: 36px; height: 36px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; color: #374151; box-shadow: 0 1px 3px rgba(0,0,0,0.06); text-decoration: none; }
.cd-addr-title { font-size: 16px; font-weight: 700; color: #111827; }
.cd-addr-body { padding: 0 16px; display: flex; flex-direction: column; gap: 16px; }
.cd-warning-banner { display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 14px; font-size: 13px; color: #92400E; line-height: 1.5; }
.cd-warning-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
.cd-qr-card { background: white; border-radius: 20px; padding: 28px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); display: flex; flex-direction: column; align-items: center; gap: 12px; }
.cd-qr-img { width: 240px; height: 240px; border-radius: 12px; }
.cd-qr-caption { font-size: 13px; color: #9CA3AF; }
.cd-amount-card { background: linear-gradient(135deg, #1E3A5F, #1E40AF); border-radius: 16px; padding: 20px; color: white; text-align: center; }
.cd-amount-label { font-size: 13px; opacity: 0.85; margin-bottom: 4px; }
.cd-amount-value { font-size: 32px; font-weight: 800; letter-spacing: -0.5px; }
.cd-amount-usd { font-size: 14px; opacity: 0.75; margin-top: 4px; }
.cd-address-card { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
.cd-address-label { font-size: 13px; font-weight: 600; color: #6B7280; margin-bottom: 8px; }
.cd-address-text { font-size: 13px; font-family: "SF Mono", "Monaco", "Cascadia Code", "Roboto Mono", monospace; word-break: break-all; line-height: 1.6; color: #111827; background: #F9FAFB; padding: 12px; border-radius: 10px; }
.cd-address-row { display: flex; align-items: flex-start; gap: 10px; }
.cd-address-row .cd-address-text { flex: 1; }
.cd-copy-btn { width: 40px; height: 40px; border-radius: 10px; background: rgba(59,130,246,0.08); color: #3B82F6; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: all 0.15s; }
.cd-copy-btn:hover { background: #3B82F6; color: white; }
.cd-action-row { display: flex; gap: 12px; }
.cd-action-primary { flex: 1; padding: 14px; background: #3B82F6; color: white; border: none; border-radius: 999px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.15s; }
.cd-action-primary:hover { background: #1D4ED8; }
.cd-action-secondary { flex: 1; padding: 14px; background: white; color: #3B82F6; border: 1.5px solid #3B82F6; border-radius: 999px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.15s; }
.cd-action-secondary:hover { background: rgba(59,130,246,0.08); }
.cd-toast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%) translateY(10px); background: #3B82F6; color: white; padding: 12px 24px; border-radius: 999px; font-size: 14px; font-weight: 600; opacity: 0; transition: all 0.3s; pointer-events: none; z-index: 100; }
.cd-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
.cd-confirm-section { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
.cd-confirm-title { font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; }
.cd-checkbox-wrap { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
.cd-checkbox-wrap input[type="checkbox"] { width: 20px; height: 20px; accent-color: #3B82F6; cursor: pointer; }
.cd-checkbox-wrap label { font-size: 14px; color: #374151; cursor: pointer; }
.cd-confirm-btn { width: 100%; padding: 16px; background: #3B82F6; color: white; border: none; border-radius: 999px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.15s; }
.cd-confirm-btn:disabled { background: #D1D5DB; color: #9CA3AF; cursor: not-allowed; }
.cd-confirm-btn:not(:disabled):hover { background: #1D4ED8; }
.cd-share-link { text-decoration: none; display: inline-flex; }
</style>
@endpush

@section("content")
<div class="cd-addr-page">
    <div class="cd-addr-header">
        <div class="cd-addr-header-left">
            <a href="{{ setRoute("user.crypto.deposit.index") }}" class="cd-addr-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            </a>
            <span class="cd-addr-title">Deposit {{ $coin["coin"] }} ({{ $coin["network"] }})</span>
        </div>
    </div>

    <div class="cd-addr-body">
        <div class="cd-warning-banner">
            <span class="cd-warning-icon">&#9888;&#65039;</span>
            <span>Send <strong>{{ $coin["coin"] }} ({{ $coin["network"] }})</strong> to this address only. Sending other assets to this address cannot be recovered.</span>
        </div>

        <div class="cd-qr-card">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data={{ urlencode($coin["address"]) }}"
                 alt="QR Code" class="cd-qr-img" id="qrImg">
            <span class="cd-qr-caption">Scan to copy address</span>
        </div>

        <div class="cd-amount-card">
            <div class="cd-amount-label">Send exactly:</div>
            <div class="cd-amount-value">
                @php
                    $cryptoAmount = $amount;
                    if ($coin["coin"] === "BTC") $cryptoAmount = number_format($amount / 60000, 8);
                    elseif ($coin["coin"] === "USDT") $cryptoAmount = number_format($amount, 2);
                    elseif ($coin["coin"] === "ETH") $cryptoAmount = number_format($amount / 1800, 6);
                    elseif ($coin["coin"] === "BCH") $cryptoAmount = number_format($amount / 300, 6);
                    else $cryptoAmount = number_format($amount, 2);
                @endphp
                {{ $cryptoAmount }} {{ $coin["coin"] }}
            </div>
            <div class="cd-amount-usd">${{ number_format($amount, 2) }} USD</div>
        </div>

        <div class="cd-address-card">
            <div class="cd-address-label">Wallet Address</div>
            <div class="cd-address-row">
                <div class="cd-address-text" id="walletAddress">{{ $coin["address"] }}</div>
                <button class="cd-copy-btn" onclick="copyAddress()" title="Copy address">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                </button>
            </div>
        </div>

        <div class="cd-action-row">
            <button class="cd-action-primary" onclick="copyAddress()">
                &#128203; Copy Address
            </button>
            <a href="https://wa.me/?text={{ urlencode("Please send {$cryptoAmount} {$coin["coin"]} to this address: {$coin["address"]}") }}"
               target="_blank" class="cd-action-secondary cd-share-link">
                &#8599;&#65039; Share Address
            </a>
        </div>

        <div class="cd-confirm-section">
            <div class="cd-confirm-title">Confirm Payment</div>
            <div class="cd-checkbox-wrap">
                <input type="checkbox" id="sentCheckbox">
                <label for="sentCheckbox">I have sent the exact amount to this address</label>
            </div>
            <form method="GET" action="{{ setRoute("user.crypto.deposit.confirm", ["coin_key" => $coinKey, "amount" => $amount]) }}">
                <button type="submit" class="cd-confirm-btn" id="madePaymentBtn" disabled>
                    I&rsquo;ve Made Payment &rarr;
                </button>
            </form>
        </div>
    </div>

    <div class="cd-toast" id="toast">Copied!</div>
</div>

@push("script")
<script>
function copyAddress() {
    var addr = document.getElementById("walletAddress");
    var text = addr.textContent.trim();

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            showToast();
        });
    } else {
        var textarea = document.createElement("textarea");
        textarea.value = text;
        textarea.style.position = "fixed";
        textarea.style.opacity = "0";
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
        showToast();
    }
}

function showToast() {
    var toast = document.getElementById("toast");
    toast.classList.add("show");
    setTimeout(function() {
        toast.classList.remove("show");
    }, 2000);
}

document.addEventListener("DOMContentLoaded", function() {
    var checkbox = document.getElementById("sentCheckbox");
    var btn = document.getElementById("madePaymentBtn");
    checkbox.addEventListener("change", function() {
        btn.disabled = !this.checked;
    });
});
</script>
@endpush
@endsection
