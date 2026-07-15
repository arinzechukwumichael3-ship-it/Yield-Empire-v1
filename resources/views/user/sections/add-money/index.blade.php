@extends('user.layouts.rise-master')

@push('css')
@endpush

@section('content')
@php
$payment_gateways = $payment_gateways ?? [];
@endphp

<div class="am-header">
    <h1 class="am-header-title">Add Money</h1>
</div>

<div class="am-body">
    <!-- Exchange Rate Banner -->
    <div class="am-rate-banner">
        <span class="am-rate-label">Exchange Rate</span>
        <span class="am-rate-value" id="liveRate">--</span>
    </div>

    <!-- Form Card -->
    <form class="am-card" method="POST" action="{{ setRoute('user.add.money.submit') }}">
        @csrf
        <div class="am-card-title">Add Funds</div>

        <div class="am-field-group">
            <label class="am-label">Amount</label>
            <div class="am-input-wrap">
                <input type="text" name="amount" placeholder="0.00" id="amount" maxlength="20" oninput="updatePreview()">
                <span class="am-input-pill">{{ get_default_currency_code() ?? 'USD' }}</span>
            </div>
            <span class="am-hint" id="limitHint">Min: -- &nbsp;|&nbsp; Max: --</span>
        </div>

        <div class="am-field-group">
            <label class="am-label">Payment Gateway</label>
            <div class="am-input-wrap">
                <select name="gateway_currency" id="gateway" onchange="updateGateway()">
                    <option value="" selected disabled>Choose Gateway</option>
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

        <button type="submit" class="am-btn" style="margin-top:16px;">Add Amount →</button>
    </form>

    <!-- Preview Card -->
    <div class="am-card" id="previewSection" style="display:none;">
        <div class="am-card-title">Add Money Preview</div>
        
        <div class="am-preview-row">
            <div class="am-preview-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <span class="am-preview-label">Request Amount</span>
            <span class="am-preview-value" id="previewAmount">--</span>
        </div>
        <div class="am-preview-row">
            <div class="am-preview-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
            <span class="am-preview-label">Exchange Rate</span>
            <span class="am-preview-value" id="previewRate">--</span>
        </div>
        <div class="am-preview-row">
            <div class="am-preview-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <span class="am-preview-label">Fees & Charges</span>
            <span class="am-preview-value" id="previewFees">--</span>
        </div>
        <div class="am-preview-row">
            <div class="am-preview-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            </div>
            <span class="am-preview-label">Will Get</span>
            <span class="am-preview-value" id="previewWillGet">--</span>
        </div>
        <div class="am-preview-row">
            <div class="am-preview-icon" style="border-color:#059669;color:#059669;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <span class="am-preview-label">Total Payable Amount</span>
            <span class="am-preview-value" style="color:#059669;" id="previewTotal">--</span>
        </div>
    </div>

    <!-- Add Money Log -->
    <div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <span style="font-size:16px;font-weight:700;">Add Money Log</span>
            <a href="{{ setRoute('user.transactions.index') }}" class="am-log-link">View More</a>
        </div>
        <div class="rw-tx-list">
            @forelse(($transactions ?? collect([]))->take(5) as $tx)
            <div class="rw-tx-item">
                <div class="rw-tx-icon green">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                </div>
                <div class="rw-tx-info">
                    <span class="rw-tx-name">Add Money</span>
                    <span class="rw-tx-date">{{ $tx->created_at ? $tx->created_at->diffForHumans() : '' }}</span>
                </div>
                <span class="rw-tx-amount positive">+${{ number_format($tx->request_amount ?? 0, 2) }}</span>
            </div>
            @empty
            <div class="rw-empty" style="padding:20px;">
                <span class="rw-empty-title">No deposits yet</span>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('script')
<script>
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
    const willGet = amount * rate;
    const total = amount + fees;
    
    document.getElementById('previewFees').textContent = fees.toFixed(2) + ' USD';
    document.getElementById('previewWillGet').textContent = willGet.toFixed(2) + ' ' + data.currency_code;
    document.getElementById('previewTotal').textContent = total.toFixed(2) + ' USD';
    document.getElementById('chargeShow').textContent = 'Charge: ' + percentCharge + '% + ' + fixedCharge + ' ' + data.currency_code;
}

document.getElementById('amount')?.addEventListener('input', updatePreview);
</script>
@endpush
@endsection