@extends("user.layouts.rise-master")

@push("css")
<style>
.cd-succ-page { background: var(--bg-primary); min-height: calc(100vh - 72px); padding-bottom: 24px; display: flex; flex-direction: column; align-items: center; padding-top: 60px; }
.cd-succ-check { width: 80px; height: 80px; border-radius: 50%; background: var(--accent-soft); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; animation: cdPopIn 0.4s ease; }
@keyframes cdPopIn { 0% { transform: scale(0); opacity: 0; } 70% { transform: scale(1.1); } 100% { transform: scale(1); opacity: 1; } }
.cd-succ-check svg { width: 40px; height: 40px; color: var(--accent); }
.cd-succ-title { font-size: 22px; font-weight: 800; color: var(--text-primary); margin-bottom: 8px; text-align: center; }
.cd-succ-sub { font-size: 14px; color: var(--text-secondary); text-align: center; line-height: 1.5; max-width: 300px; margin-bottom: 28px; }
.cd-succ-card { background: var(--bg-card); border-radius: 20px; padding: 20px; box-shadow: var(--card-shadow); width: calc(100% - 32px); max-width: 400px; margin-bottom: 24px; }
.cd-succ-card-title { font-size: 16px; font-weight: 700; color: var(--text-primary); margin-bottom: 14px; }
.cd-succ-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; font-size: 14px; }
.cd-succ-row + .cd-succ-row { border-top: 1px solid var(--border-color); }
.cd-succ-label { color: var(--text-secondary); }
.cd-succ-value { color: var(--text-primary); font-weight: 600; text-align: right; }
.cd-succ-status { font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 999px; background: var(--warning-bg); color: var(--warning-text); }
.cd-succ-actions { display: flex; flex-direction: column; gap: 12px; width: calc(100% - 32px); max-width: 400px; }
.cd-succ-btn-primary { width: 100%; padding: 16px; background: var(--accent); color: var(--text-on-accent); border: none; border-radius: 999px; font-size: 16px; font-weight: 700; cursor: pointer; text-align: center; text-decoration: none; transition: all 0.15s; }
.cd-succ-btn-primary:hover { background: var(--blue); }
.cd-succ-btn-secondary { width: 100%; padding: 16px; background: var(--bg-card); color: var(--accent); border: 1.5px solid var(--accent); border-radius: 999px; font-size: 16px; font-weight: 700; cursor: pointer; text-align: center; text-decoration: none; transition: all 0.15s; }
.cd-succ-btn-secondary:hover { background: var(--accent-soft); }
</style>
@endpush

@section("content")
<div class="cd-succ-page">
    <div class="cd-succ-check">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
    </div>

    <div class="cd-succ-title">Payment Submitted!</div>
    <div class="cd-succ-sub">
        Your deposit is under review. We&rsquo;ll credit your account within 1-3 hours after confirmation.
    </div>

    <div class="cd-succ-card">
        <div class="cd-succ-card-title">Deposit Details</div>
        <div class="cd-succ-row">
            <span class="cd-succ-label">Coin</span>
            <span class="cd-succ-value">{{ $deposit->coin_symbol }}</span>
        </div>
        <div class="cd-succ-row">
            <span class="cd-succ-label">Network</span>
            <span class="cd-succ-value">{{ $deposit->network }}</span>
        </div>
        <div class="cd-succ-row">
            <span class="cd-succ-label">Amount</span>
            <span class="cd-succ-value">${{ number_format($deposit->amount_usd, 2) }}</span>
        </div>
        @if($deposit->tx_hash)
        <div class="cd-succ-row">
            <span class="cd-succ-label">TX Hash</span>
            <span class="cd-succ-value" style="font-family:monospace;font-size:11px;max-width:160px;word-break:break-all">{{ $deposit->tx_hash }}</span>
        </div>
        @endif
        <div class="cd-succ-row">
            <span class="cd-succ-label">Status</span>
            <span class="cd-succ-value"><span class="cd-succ-status">&#9679; Pending Confirmation</span></span>
        </div>
    </div>

    <div class="cd-succ-actions">
        <a href="{{ setRoute("user.rise.wallet") }}" class="cd-succ-btn-primary">View Wallet</a>
        <a href="{{ setRoute("user.crypto.deposit.index") }}" class="cd-succ-btn-secondary">Make Another Deposit</a>
    </div>
</div>
@endsection
