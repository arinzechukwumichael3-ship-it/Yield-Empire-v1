@extends('user.layouts.rise-master')

@push('css')
<style>
/* ── Send Money ── */
.send-tabs {
    display: flex;
    background: #1E293B;
    border-radius: 12px;
    padding: 4px;
    margin-bottom: 20px;
}
.send-tab {
    flex: 1;
    padding: 12px 8px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #94A3B8;
    text-align: center;
    cursor: pointer;
    transition: all 0.15s;
    border: none;
    background: none;
    -webkit-tap-highlight-color: transparent;
}
.send-tab.active {
    background: #3B82F6;
    color: #fff;
}
.send-tab-content { display: none; }
.send-tab-content.active { display: block; }

/* Form Fields */
.send-field-group { margin-bottom: 16px; }
.send-label {
    font-size: 13px;
    font-weight: 600;
    color: #94A3B8;
    margin-bottom: 6px;
    display: block;
}
.send-input-wrap {
    display: flex;
    align-items: center;
    border: 1.5px solid #334155;
    border-radius: 12px;
    overflow: hidden;
    transition: border-color 0.15s;
    background: #1E293B;
}
.send-input-wrap:focus-within { border-color: #3B82F6; }
.send-input {
    flex: 1;
    border: none;
    outline: none;
    padding: 14px 16px;
    font-size: 16px;
    font-weight: 500;
    color: #fff;
    background: transparent;
    min-width: 0;
}
.send-input::placeholder { color: #4B5563; }
.send-input-pill {
    padding: 0 14px;
    font-size: 13px;
    font-weight: 600;
    color: #94A3B8;
    background: rgba(255,255,255,0.04);
    align-self: stretch;
    display: flex;
    align-items: center;
    white-space: nowrap;
}

/* Recipient preview */
.send-recipient-preview {
    display: none;
    background: rgba(59,130,246,0.08);
    border: 1px solid rgba(59,130,246,0.2);
    border-radius: 12px;
    padding: 14px 16px;
    margin-top: 12px;
    align-items: center;
    gap: 12px;
}
.send-recipient-preview.show { display: flex; }
.send-recipient-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #3B82F6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 16px;
    flex-shrink: 0;
}
.send-recipient-info { flex: 1; }
.send-recipient-name { font-size: 15px; font-weight: 600; color: #fff; }
.send-recipient-detail { font-size: 12px; color: #94A3B8; }
.send-recipient-check { color: #22C55E; flex-shrink: 0; }

/* Fee/Info cards */
.send-fee-card {
    background: rgba(59,130,246,0.06);
    border: 1px solid rgba(59,130,246,0.12);
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 16px;
}
.send-fee-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
    font-size: 13px;
}
.send-fee-label { color: #94A3B8; }
.send-fee-value { color: #fff; font-weight: 600; }
.send-fee-divider { height: 1px; background: rgba(59,130,246,0.1); margin: 8px 0; }

/* Submit buttons */
.send-btn {
    width: 100%;
    padding: 16px;
    border-radius: 100px;
    font-size: 16px;
    font-weight: 700;
    border: none;
    background: linear-gradient(135deg, #3B82F6, #2563EB);
    color: #fff;
    cursor: pointer;
    transition: opacity 0.15s, transform 0.15s;
    -webkit-tap-highlight-color: transparent;
}
.send-btn:hover { opacity: 0.92; }
.send-btn:active { transform: scale(0.98); }
.send-btn:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

/* Light mode */
[data-theme="light"] .send-tabs { background: #E2E8F0; }
[data-theme="light"] .send-tab { color: #64748B; }
[data-theme="light"] .send-tab.active { background: #3B82F6; color: #fff; }
[data-theme="light"] .send-input-wrap { background: #fff; border-color: #D1D5DB; }
[data-theme="light"] .send-input { color: #1F2937; }
[data-theme="light"] .send-input::placeholder { color: #9CA3AF; }
[data-theme="light"] .send-input-pill { color: #64748B; background: rgba(0,0,0,0.03); }
[data-theme="light"] .send-fee-card { background: rgba(59,130,246,0.04); border-color: rgba(59,130,246,0.1); }
[data-theme="light"] .send-fee-label { color: #64748B; }
[data-theme="light"] .send-fee-value { color: #1F2937; }
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ __('Send Money') }}</h1>
</div>
<div class="am-body">

    {{-- Tab Toggle --}}
    <div class="send-tabs" role="tablist">
        <button class="send-tab active" data-tab="internal" role="tab">🏦 EnzoBank Account</button>
        <button class="send-tab" data-tab="other" role="tab">🌍 Other Bank</button>
    </div>

    {{-- ====== TAB 1: Internal EnzoBank Transfer ====== --}}
    <div class="send-tab-content active" id="tab-internal">
        <div class="am-card">
            <form method="POST" action="{{ route('user.rise.send.submit') }}">
                @csrf
                <input type="hidden" name="type" value="internal">
                <div class="send-field-group">
                    <label class="send-label">{{ __('Recipient Account / Username') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="account" id="recipientLookup" placeholder="Enter account number or username" autocomplete="off">
                    </div>
                    {{-- Recipient preview --}}
                    <div class="send-recipient-preview" id="recipientPreview">
                        <div class="send-recipient-avatar" id="recipientAvatar">J</div>
                        <div class="send-recipient-info">
                            <div class="send-recipient-name" id="recipientName">John Doe</div>
                            <div class="send-recipient-detail" id="recipientDetail">Account: •••• 4242</div>
                        </div>
                        <span class="send-recipient-check">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                    </div>
                </div>

                <div class="send-field-group">
                    <label class="send-label">{{ __('Amount (USD)') }}</label>
                    <div class="send-input-wrap">
                        <input type="number" step="0.01" min="0.01" class="send-input" name="amount" id="internalAmount" placeholder="0.00">
                        <span class="send-input-pill">USD</span>
                    </div>
                </div>

                <div class="send-field-group">
                    <label class="send-label">{{ __('Description (optional)') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="description" placeholder="What's this for?">
                    </div>
                </div>

                {{-- Fee preview --}}
                <div class="send-fee-card">
                    <div class="send-fee-row">
                        <span class="send-fee-label">Transfer Fee</span>
                        <span class="send-fee-value">$0.00</span>
                    </div>
                    <div class="send-fee-row">
                        <span class="send-fee-label">You'll send</span>
                        <span class="send-fee-value" id="internalTotal">$0.00</span>
                    </div>
                    <div class="send-fee-divider"></div>
                    <div class="send-fee-row">
                        <span class="send-fee-label">Recipient gets</span>
                        <span class="send-fee-value" id="internalRecipientGets" style="color:#22C55E">$0.00</span>
                    </div>
                    <div class="send-fee-row">
                        <span class="send-fee-label">Arrives</span>
                        <span class="send-fee-value" style="color:#22C55E">Instantly</span>
                    </div>
                </div>

                <button type="submit" class="send-btn" id="sendInternalBtn" disabled>{{ __('Send Money') }}</button>
            </form>
        </div>
    </div>

    {{-- ====== TAB 2: Other International Bank ====== --}}
    <div class="send-tab-content" id="tab-other">
        <div class="am-card">
            <form method="POST" action="{{ route('user.rise.send.submit') }}">
                @csrf
                <input type="hidden" name="type" value="other_bank">
                <div class="send-field-group">
                    <label class="send-label">{{ __('Recipient Full Name') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="recipient_name" id="obName" placeholder="Jane Doe" autocomplete="off">
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('Bank Name') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="bank_name" id="obBank" placeholder="e.g. Barclays UK" autocomplete="off">
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('Account Number / IBAN') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="account_number" id="obAccount" placeholder="GB29 NWBK 6016 1331 9268 19" autocomplete="off">
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('Country') }}</label>
                    <div class="send-input-wrap">
                        <select class="send-input" name="country" id="obCountry">
                            <option value="">{{ __('Select country') }}</option>
                            @foreach($countries as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('SWIFT / BIC (optional)') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="swift" id="obSwift" placeholder="NWBKGB2L" autocomplete="off">
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('Amount (USD)') }}</label>
                    <div class="send-input-wrap">
                        <input type="number" step="0.01" min="0.01" class="send-input" name="amount" id="obAmount" placeholder="0.00">
                        <span class="send-input-pill">USD</span>
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('Description (optional)') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="description" placeholder="What's this for?">
                    </div>
                </div>
                <div class="send-fee-card">
                    <div class="send-fee-row">
                        <span class="send-fee-label">{{ __('You send') }}</span>
                        <span class="send-fee-value" id="obTotal">$0.00</span>
                    </div>
                    <div class="send-fee-row">
                        <span class="send-fee-label">{{ __('Arrives') }}</span>
                        <span class="send-fee-value" style="color:var(--success,#22C55E)">{{ __('1-2 business days') }}</span>
                    </div>
                </div>
                <button type="submit" class="send-btn" id="sendOtherBtn" disabled>{{ __('Send to Bank') }}</button>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
// ── Recipient lookup (validates existence, shows real name) ──
var lookupInput = document.getElementById('recipientLookup');
var preview = document.getElementById('recipientPreview');
var sendBtn = document.getElementById('sendInternalBtn');
var lookupTimer;

if (lookupInput) {
    lookupInput.addEventListener('input', function() {
        var val = this.value.trim();
        clearTimeout(lookupTimer);
        sendBtn.disabled = true;
        if (val.length < 3) {
            preview.classList.remove('show');
            return;
        }
        lookupTimer = setTimeout(function() {
            fetch("{{ route('user.info.account') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ text: val })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.type === 'success' && data.data) {
                    var u = data.data;
                    var name = u.fullname || ((u.firstname || '') + ' ' + (u.lastname || '')).trim() || u.username;
                    preview.classList.add('show');
                    document.getElementById('recipientName').textContent = name;
                    document.getElementById('recipientDetail').textContent = 'EnzoBank • ' + (u.account_no || '');
                    document.getElementById('recipientAvatar').textContent = (u.firstname || u.username || '?').charAt(0).toUpperCase();
                    sendBtn.disabled = false;
                } else {
                    preview.classList.remove('show');
                    sendBtn.disabled = true;
                }
            })
            .catch(function() {
                preview.classList.remove('show');
                sendBtn.disabled = true;
            });
        }, 400);
    });
}

// ── Internal amount calculation ──
var internalAmount = document.getElementById('internalAmount');
if (internalAmount) {
    internalAmount.addEventListener('input', function() {
        var amt = parseFloat(this.value) || 0;
        document.getElementById('internalTotal').textContent = '$' + amt.toFixed(2);
        document.getElementById('internalRecipientGets').textContent = '$' + amt.toFixed(2);
    });
}

// ── Tab switching ──
document.querySelectorAll('.send-tab').forEach(function(tab) {
    tab.addEventListener('click', function() {
        var target = this.getAttribute('data-tab');
        document.querySelectorAll('.send-tab').forEach(function(t) { t.classList.remove('active'); });
        this.classList.add('active');
        document.querySelectorAll('.send-tab-content').forEach(function(c) { c.classList.remove('active'); });
        var content = document.getElementById('tab-' + target);
        if (content) content.classList.add('active');
    });
});

// ── Other bank validation + amount ──
var obBtn = document.getElementById('sendOtherBtn');
function validateOtherBank() {
    if (!obBtn) return;
    var name = document.getElementById('obName').value.trim();
    var bank = document.getElementById('obBank').value.trim();
    var acc = document.getElementById('obAccount').value.trim();
    var country = document.getElementById('obCountry').value.trim();
    var amt = parseFloat(document.getElementById('obAmount').value) || 0;
    obBtn.disabled = !(name && bank && acc && country && amt > 0);
}
['obName','obBank','obAccount','obCountry','obAmount','obSwift'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el) { el.addEventListener('input', validateOtherBank); el.addEventListener('change', validateOtherBank); }
});
var obAmount = document.getElementById('obAmount');
if (obAmount) {
    obAmount.addEventListener('input', function() {
        var amt = parseFloat(this.value) || 0;
        var total = document.getElementById('obTotal');
        if (total) total.textContent = '$' + amt.toFixed(2);
    });
}

// ── Other bank: require a $10 virtual card before sending ──
window.__hasVirtualCard = {{ $hasVirtualCard ? 'true' : 'false' }};
window.__virtualCardUrl = "{{ $virtualCardUrl }}";
var obForm = document.getElementById('tab-other') ? document.getElementById('tab-other').querySelector('form') : null;
if (obForm) {
    obForm.addEventListener('submit', function(e) {
        if (!window.__hasVirtualCard) {
            e.preventDefault();
            alert("To send money to another bank you must first get a virtual card for $10 USD.\n\nYour virtual card unlocks international bank transfers.");
            window.location = window.__virtualCardUrl;
            return false;
        }
    });
}
</script>
@endpush
@endsection
