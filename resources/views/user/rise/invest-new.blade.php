@extends('user.layouts.rise-master')

@section('content')
@php
$plans = $plans ?? collect([]);
$wallets = $wallets ?? collect([]);
@endphp

<div class="am-header">
    <h1 class="am-header-title">New Investment Plan</h1>
</div>

<div class="am-body">
    <!-- Step 1: Select Plan -->
    <div class="am-card" id="stepPlan">
        <div class="am-card-title">Select Plan</div>
        <div style="display:flex;flex-direction:column;gap:12px;">
            @forelse($plans as $plan)
            <div class="ip-select-card" data-plan-id="{{ $plan->id }}" data-min="{{ $plan->min_amount }}" data-max="{{ $plan->max_amount ?? 999999999 }}" data-roi="{{ $plan->roi_percent }}" data-days="{{ $plan->duration_days }}" data-name="{{ $plan->name }}" onclick="selectPlan(this)">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-weight:700;font-size:16px;">{{ $plan->name }}</div>
                        <div style="font-size:13px;color:#3B82F6;font-weight:500;">${{ number_format($plan->min_amount,2) }} — ${{ $plan->max_amount ? number_format($plan->max_amount,2) : '∞' }}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="padding:4px 12px;border-radius:20px;background:rgba(16,185,129,0.1);color:#059669;font-size:12px;font-weight:600;">{{ $plan->roi_percent }}% ROI</span>
                        <span class="ip-select-btn">Select</span>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:20px;color:#9CA3AF;">No investment plans available.</div>
            @endforelse
        </div>
    </div>

    <!-- Step 2: Plan Summary (hidden until selected) -->
    <div class="am-card" id="stepSummary" style="display:none;border-left:4px solid #3B82F6;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div>
                <div style="font-size:13px;color:#6B7280;">Selected Plan</div>
                <div style="font-weight:700;font-size:18px;" id="summaryName">-</div>
                <div style="font-size:13px;color:#3B82F6;" id="summaryRange">-</div>
                <div style="margin-top:6px;display:flex;gap:10px;">
                    <span style="font-size:12px;color:#059669;font-weight:600;" id="summaryRoi">-</span>
                    <span style="font-size:12px;color:#6B7280;" id="summaryDuration">-</span>
                </div>
            </div>
            <a href="#" style="font-size:13px;color:#3B82F6;font-weight:500;" onclick="resetPlan();return false;">Change Plan</a>
        </div>
    </div>

    <!-- Step 3: Payment Method Card -->
    <div class="am-card" id="stepPayment" style="display:none;">
        <div class="am-card-title">Select Payment Method</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;" id="cryptoGrid">
            @foreach(['BTC' => 'Bitcoin', 'ETH' => 'Ethereum', 'USDT-TRC20' => 'USDT (TRC20)', 'USDT-ERC20' => 'USDT (ERC20)', 'USDT-BEP20' => 'USDT (BEP20)', 'TRX' => 'Tron'] as $key => $name)
            <div class="cp-method-card" data-method="{{ explode('-', $key)[0] }}" data-network="{{ explode('-', $key)[1] ?? explode('-', $key)[0] }}" onclick="selectMethod(this)">
                <div style="font-size:24px;margin-bottom:4px;">
                    @switch(explode('-', $key)[0])
                        @case('BTC') 🟠 @break
                        @case('ETH') 🔵 @break
                        @case('USDT') 💚 @break
                        @case('TRX') 🔴 @break
                        @default 💰
                    @endswitch
                </div>
                <div style="font-size:12px;font-weight:600;">{{ explode('-', $key)[0] }}</div>
                <div style="font-size:10px;color:#9CA3AF;">{{ explode('-', $key)[1] ?? explode('-', $key)[0] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Step 4: Deposit Amount Card -->
    <div class="am-card" id="stepAmount" style="display:none;">
        <div class="am-card-title">Deposit Amount</div>
        <div class="am-field-group">
            <div class="am-input-wrap">
                <input type="number" id="depositAmount" placeholder="0.00" step="0.01" min="0" oninput="validateAmount()">
                <span class="am-input-pill" id="currencyPill">USD</span>
            </div>
            <span class="am-hint" id="rangeHint">Min: $0.00 — Max: $0.00</span>
            <span class="am-hint" id="validationMsg" style="color:#DC2626;display:none;">Amount out of range</span>
        </div>
    </div>

    <!-- Step 5: Order Summary -->
    <div class="am-card" id="stepOrder" style="display:none;">
        <div class="am-card-title">Order Summary</div>
        <div class="am-preview-row"><span class="am-preview-label">💼 Plan</span><span class="am-preview-value" id="orderPlan">-</span></div>
        <div class="am-preview-row"><span class="am-preview-label">💰 Deposit Amount</span><span class="am-preview-value" id="orderAmount">-</span></div>
        <div class="am-preview-row"><span class="am-preview-label">📈 Expected ROI</span><span class="am-preview-value" id="orderRoi">-</span></div>
        <div class="am-preview-row"><span class="am-preview-label">📅 Duration</span><span class="am-preview-value" id="orderDuration">-</span></div>
        <div class="am-preview-row"><span class="am-preview-label">🔄 Payment via</span><span class="am-preview-value" id="orderMethod">-</span></div>
        <div class="am-preview-row"><span class="am-preview-label">✅ You will receive</span><span class="am-preview-value" id="orderReturn" style="color:#059669;">-</span></div>
    </div>

    <!-- Proceed Button -->
    <button class="am-btn" id="proceedBtn" disabled style="border-radius:100px;" onclick="proceedDeposit()">Proceed to Deposit →</button>
</div>

<form id="depositForm" method="POST" action="{{ route('user.invest.deposit') }}" style="display:none;">
    @csrf
    <input type="hidden" name="plan_id" id="fPlanId">
    <input type="hidden" name="amount" id="fAmount">
    <input type="hidden" name="method" id="fMethod">
    <input type="hidden" name="network" id="fNetwork">
</form>

@push('script')
<script>
let selectedPlan = null;
let selectedMethod = null;
let selectedNetwork = null;

function selectPlan(el) {
    document.querySelectorAll('.ip-select-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    selectedPlan = {
        id: el.dataset.planId,
        name: el.dataset.name,
        min: parseFloat(el.dataset.min),
        max: parseFloat(el.dataset.max),
        roi: parseFloat(el.dataset.roi),
        days: parseInt(el.dataset.days)
    };
    document.getElementById('stepSummary').style.display = 'block';
    document.getElementById('stepPayment').style.display = 'block';
    document.getElementById('summaryName').textContent = selectedPlan.name;
    document.getElementById('summaryRange').textContent = '$' + selectedPlan.min.toFixed(2) + ' — $' + (selectedPlan.max >= 999999999 ? '∞' : selectedPlan.max.toFixed(2));
    document.getElementById('summaryRoi').textContent = selectedPlan.roi + '% ROI';
    document.getElementById('summaryDuration').textContent = selectedPlan.days + ' days';
    document.getElementById('depositAmount').value = selectedPlan.min;
    document.getElementById('rangeHint').textContent = 'Min: $' + selectedPlan.min.toFixed(2) + ' — Max: $' + (selectedPlan.max >= 999999999 ? '∞' : selectedPlan.max.toFixed(2));
    document.getElementById('stepAmount').style.display = 'block';
    validateAmount();
}

function selectMethod(el) {
    document.querySelectorAll('.cp-method-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    selectedMethod = el.dataset.method;
    selectedNetwork = el.dataset.network;
    document.getElementById('currencyPill').textContent = selectedMethod;
    updateOrderSummary();
}

function validateAmount() {
    const val = parseFloat(document.getElementById('depositAmount').value) || 0;
    const msg = document.getElementById('validationMsg');
    if (!selectedPlan) return;
    if (val < selectedPlan.min || val > selectedPlan.max) {
        msg.style.display = 'block';
        document.getElementById('depositAmount').style.borderColor = '#DC2626';
    } else {
        msg.style.display = 'none';
        document.getElementById('depositAmount').style.borderColor = '#E5E7EB';
    }
    updateOrderSummary();
}

function updateOrderSummary() {
    const val = parseFloat(document.getElementById('depositAmount').value) || 0;
    if (!selectedPlan || !selectedMethod) return;
    document.getElementById('stepOrder').style.display = 'block';
    document.getElementById('orderPlan').textContent = selectedPlan.name;
    document.getElementById('orderAmount').textContent = '$' + val.toFixed(2);
    document.getElementById('orderRoi').textContent = selectedPlan.roi + '%';
    document.getElementById('orderDuration').textContent = selectedPlan.days + ' days';
    document.getElementById('orderMethod').textContent = selectedMethod + ' (' + selectedNetwork + ')';
    const ret = val + (val * selectedPlan.roi / 100);
    document.getElementById('orderReturn').textContent = '$' + ret.toFixed(2);

    const valid = selectedPlan && val >= selectedPlan.min && val <= selectedPlan.max && selectedMethod;
    document.getElementById('proceedBtn').disabled = !valid;
}

function proceedDeposit() {
    const val = parseFloat(document.getElementById('depositAmount').value) || 0;
    if (!selectedPlan || !selectedMethod || !selectedNetwork || val < selectedPlan.min || val > selectedPlan.max) return;
    document.getElementById('fPlanId').value = selectedPlan.id;
    document.getElementById('fAmount').value = val;
    document.getElementById('fMethod').value = selectedMethod;
    document.getElementById('fNetwork').value = selectedNetwork;
    document.getElementById('depositForm').submit();
}

function resetPlan() {
    selectedPlan = null;
    selectedMethod = null;
    selectedNetwork = null;
    document.getElementById('stepSummary').style.display = 'none';
    document.getElementById('stepPayment').style.display = 'none';
    document.getElementById('stepAmount').style.display = 'none';
    document.getElementById('stepOrder').style.display = 'none';
    document.getElementById('proceedBtn').disabled = true;
    document.querySelectorAll('.ip-select-card').forEach(c => c.classList.remove('selected'));
    document.querySelectorAll('.cp-method-card').forEach(c => c.classList.remove('selected'));
}
</script>
@endpush
@endsection
