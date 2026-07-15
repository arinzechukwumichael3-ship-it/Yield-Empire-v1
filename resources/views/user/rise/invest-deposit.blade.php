@extends('user.layouts.rise-master')

@section('content')
@php
$plan = $plan ?? null;
$wallet = $wallet ?? null;
$amount = $amount ?? 0;
$returnAmount = $returnAmount ?? 0;
@endphp

<div class="am-header">
    <h1 class="am-header-title">Complete Deposit</h1>
</div>

<div class="am-body">
    <!-- Plan Summary -->
    <div class="am-card" style="border-left:4px solid #3B82F6;">
        <div style="font-size:13px;color:#6B7280;margin-bottom:4px;">{{ $plan->name ?? '-' }}</div>
        <div style="font-weight:700;font-size:20px;">${{ number_format($amount, 2) }}</div>
        <div style="font-size:13px;color:#059669;margin-top:4px;">Returns: ${{ number_format($returnAmount, 2) }} ({{ $plan->roi_percent ?? 0 }}% ROI)</div>
    </div>

    <!-- Wallet Address Card -->
    <div class="am-card">
        <div class="am-card-title">Send Payment To</div>
        
        <!-- QR Code Placeholder -->
        <div style="display:flex;justify-content:center;margin-bottom:20px;">
            <div id="qrcode" style="width:180px;height:180px;background:#F3F4F6;border-radius:16px;display:flex;align-items:center;justify-content:center;border:2px dashed #D1D5DB;">
                <span style="font-size:12px;color:#9CA3AF;text-align:center;padding:10px;">QR Code<br><small>{{ $wallet->symbol ?? '' }}</small></span>
            </div>
        </div>

        <!-- Wallet Address -->
        <div class="am-field-group">
            <label class="am-label">Wallet Address ({{ $wallet->network ?? '' }})</label>
            <div style="display:flex;gap:8px;">
                <input class="ps-input" id="walletAddr" value="{{ $wallet->wallet_address ?? '' }}" readonly style="flex:1;font-size:12px;word-break:break-all;">
                <button class="vc-action-btn" style="flex-shrink:0;padding:10px 14px;" onclick="copyAddr()">📋 Copy</button>
            </div>
        </div>

        <!-- Warning -->
        <div style="margin-top:12px;padding:12px 16px;background:#FFFBEB;border:1px solid #FDE68A;border-radius:12px;font-size:13px;color:#92400E;">
            <strong>⚠️ Important</strong><br>
            Send exactly <strong>${{ number_format($amount, 2) }}</strong> worth of <strong>{{ $wallet->symbol ?? '' }}</strong> ({{ $wallet->network ?? '' }}). Wrong amount or network = lost funds.
        </div>

        <!-- Timer -->
        <div style="text-align:center;margin-top:12px;font-size:14px;color:#6B7280;">
            ⏱ This address expires in <span id="timer" style="font-weight:700;color:#3B82F6;">30:00</span>
        </div>
    </div>

    <!-- Upload Proof Form -->
    <form class="am-card" method="POST" action="{{ route('user.invest.submit.proof') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="plan_id" value="{{ $plan->id ?? '' }}">
        <input type="hidden" name="amount" value="{{ $amount }}">
        <input type="hidden" name="method" value="{{ $wallet->symbol ?? '' }}">
        <input type="hidden" name="network" value="{{ $wallet->network ?? '' }}">
        <input type="hidden" name="wallet_address_used" value="{{ $wallet->wallet_address ?? '' }}">

        <div class="am-card-title">Upload Payment Proof</div>

        <div class="am-field-group">
            <label class="am-label">Upload Screenshot</label>
            <div style="border:2px dashed #D1D5DB;border-radius:12px;padding:30px;text-align:center;cursor:pointer;background:#FAFAFA;" onclick="document.getElementById('proofInput').click()">
                <div style="font-size:36px;color:#D1D5DB;">📁</div>
                <div style="font-size:13px;color:#9CA3AF;margin-top:8px;">Tap to upload transaction screenshot</div>
                <input type="file" id="proofInput" name="proof" accept="image/*" style="display:none;" onchange="this.closest('div').querySelector('.file-name').textContent = this.files[0]?.name || ''">
                <div class="file-name" style="font-size:12px;color:#3B82F6;margin-top:4px;"></div>
            </div>
        </div>

        <div class="am-field-group">
            <label class="am-label">Or enter Transaction Hash/ID</label>
            <div class="am-input-wrap">
                <input type="text" name="tx_hash" placeholder="0x... / TX Hash / Transaction ID" required>
            </div>
        </div>

        <button type="submit" class="am-btn" style="border-radius:100px;">Submit for Review →</button>
    </form>
</div>

@push('script')
<script>
function copyAddr() {
    const input = document.getElementById('walletAddr');
    input.select();
    navigator.clipboard.writeText(input.value).then(() => {
        alert('Address copied!');
    });
}

// Countdown timer
let minutes = 29;
let seconds = 59;
function updateTimer() {
    document.getElementById('timer').textContent = 
        String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    if (seconds === 0) {
        if (minutes === 0) { return; }
        minutes--;
        seconds = 59;
    } else {
        seconds--;
    }
}
setInterval(updateTimer, 1000);
</script>
@endpush
@endsection
