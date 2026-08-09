@extends('user.layouts.rise-master')

@push('css')
<style>
.pin-banner { position: relative; padding: 40px 16px 24px; text-align: center; }
.pin-banner-icon {
    width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 16px;
    display: flex; align-items: center; justify-content: center;
    background: var(--accent-soft);
    border: 2px solid var(--accent-soft);
}
.pin-banner-icon svg { color: var(--accent); width: 36px; height: 36px; }
.pin-banner-title { font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
.pin-banner-sub { font-size: 13px; color: var(--text-secondary); }

.pin-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 24px 20px; margin: 0 16px 20px; }
.pin-card-title { font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
.pin-card-title svg { color: var(--accent); width: 18px; height: 18px; }

.pin-field { margin-bottom: 16px; }
.pin-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
.pin-input-wrap { position: relative; display: flex; align-items: center; }
.pin-input {
    width: 100%; height: 48px; padding: 0 14px; border: 1.5px solid var(--border-color); border-radius: 12px;
    background: var(--input-bg); color: var(--text-primary); font-size: 20px; font-weight: 700; letter-spacing: 8px;
    outline: none; transition: border-color 0.2s;
}
.pin-input:focus { border-color: var(--accent); }
.pin-input::placeholder { letter-spacing: 0; font-size: 14px; font-weight: 400; color: var(--placeholder); }
.pin-eye {
    position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); cursor: pointer; display: flex; padding: 4px;
    background: none; border: none; transition: color 0.2s;
}
.pin-eye:hover { color: var(--text-secondary); }

.pin-btn {
    width: 100%; height: 48px; border: none; border-radius: 12px;
    font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.pin-btn-primary { background: var(--accent); color: var(--text-on-accent); }
.pin-btn-primary:hover { filter: brightness(1.1); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(59,130,246,0.3); }
.pin-btn-primary:active { transform: translateY(0); }
.pin-btn-outline { background: transparent; color: var(--accent); border: 1.5px solid var(--border-color); }
.pin-btn-outline:hover { border-color: var(--accent); background: var(--accent-soft); }

/* Success state */
.pin-success { text-align: center; padding: 8px 0; }
.pin-success-icon {
    width: 72px; height: 72px; border-radius: 50%; margin: 0 auto 16px;
    display: flex; align-items: center; justify-content: center;
    background: var(--success-bg); border: 2px solid var(--success-bg);
}
.pin-success-icon svg { color: var(--success); width: 32px; height: 32px; }
.pin-success-title { font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; }
.pin-success-text { font-size: 13px; color: var(--text-secondary); line-height: 1.5; margin-bottom: 20px; }

/* Flash messages */
.pin-flash { margin:16px; padding:14px 16px; border-radius:12px; display:flex; align-items:center; gap:10px; font-size:13px; font-weight:500; }
.pin-flash-success { background:var(--success-bg); border:1px solid var(--success-bg); color:var(--success-text); }
.pin-flash-error { background:var(--danger-bg); border:1px solid var(--danger-bg); color:var(--danger-text); }
.pin-flash svg { flex-shrink:0; width:18px; height:18px; }
.pin-flash-success svg { color:var(--success); }
.pin-flash-error svg { color:var(--danger); }

/* Tips */
.pin-tip-number {
    width:18px; height:18px; border-radius:50%; flex-shrink:0; margin-top:1px;
    display:flex; align-items:center; justify-content:center;
    font-size:10px; font-weight:700; color:var(--accent); background:var(--accent-soft);
}
.pin-tip-text { font-size:13px; color:var(--text-secondary); line-height:1.4; }
.pin-tip-sep { width:100%; height:1px; background:var(--border-color); margin:0; }

@media (max-width: 400px) {
    .pin-card { padding: 20px 16px; }
    .pin-banner-icon { width: 64px; height: 64px; }
    .pin-banner-icon svg { width: 28px; height: 28px; }
}
</style>
@endpush

@section('content')
@php $user = auth()->user(); @endphp

@if(session('success'))
    @foreach(session('success') as $msg)
    <div class="pin-flash pin-flash-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        <span>{{ __($msg) }}</span>
    </div>
    @endforeach
@endif
@if(session('error'))
    @foreach(session('error') as $msg)
    <div class="pin-flash pin-flash-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        <span>{{ __($msg) }}</span>
    </div>
    @endforeach
@endif

<div class="pin-banner">
    <div class="pin-banner-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
    </div>
    <div class="pin-banner-title">{{ $user->pin_status ? __('PIN Protection Active') : __('Set Up Your PIN') }}</div>
    <div class="pin-banner-sub">{{ $user->pin_status ? __('Your account is secured with a transaction PIN') : __('Create a 4-digit PIN to secure your transactions') }}</div>
</div>

@if($user->pin_status)
    {{-- PIN already set — show success + change option --}}
    <div class="pin-card">
        <div class="pin-success">
            <div class="pin-success-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="pin-success-title">{{ __('PIN Set Up Successfully') }}</div>
            <div class="pin-success-text">{{ __('Your transaction PIN is active. You can change it below if needed.') }}</div>
            <button type="button" class="pin-btn pin-btn-outline" onclick="document.getElementById('changePinForm').style.display='block';this.style.display='none'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                {{ __('Change PIN') }}
            </button>
        </div>
        <form id="changePinForm" class="card-form" action="{{ setRoute('user.setup.pin.update') }}" method="POST" style="display:none;">
            @csrf
            <div class="pin-field">
                <label class="pin-label">{{ __('Current PIN') }}</label>
                <div class="pin-input-wrap">
                    <input type="password" class="pin-input" placeholder="Enter current PIN" name="old_pin" maxlength="4" inputmode="numeric" pattern="[0-9]*" required>
                    <button type="button" class="pin-eye" onclick="togglePin(this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>
            <div class="pin-field">
                <label class="pin-label">{{ __('New PIN') }}</label>
                <div class="pin-input-wrap">
                    <input type="password" class="pin-input" placeholder="Enter new PIN" name="new_pin" maxlength="4" inputmode="numeric" pattern="[0-9]*" required>
                    <button type="button" class="pin-eye" onclick="togglePin(this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>
            <div class="pin-field" style="margin-bottom:0;">
                <label class="pin-label">{{ __('Confirm PIN') }}</label>
                <div class="pin-input-wrap">
                    <input type="password" class="pin-input" placeholder="Re-enter new PIN" name="new_pin_confirmation" maxlength="4" inputmode="numeric" pattern="[0-9]*" oninput="validatePinMatch(this)" required>
                    <button type="button" class="pin-eye" onclick="togglePin(this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <span id="pinMatchMsg" style="margin-top:4px;display:none;"></span>
            </div>
            <button type="submit" class="pin-btn pin-btn-primary" style="margin-top:20px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ __('Update PIN') }}
            </button>
        </form>
    </div>
@else
    {{-- No PIN yet — show setup form --}}
    <div class="pin-card">
        <div class="pin-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            {{ __('Create Transaction PIN') }}
        </div>
        <form class="card-form" action="{{ setRoute('user.setup.pin.store') }}" method="POST">
            @csrf
            <div class="pin-field">
                <label class="pin-label">{{ __('Enter PIN') }}</label>
                <div class="pin-input-wrap">
                    <input type="password" class="pin-input" placeholder="Enter 4-digit PIN" name="pin_code" maxlength="4" inputmode="numeric" pattern="[0-9]*" required>
                    <button type="button" class="pin-eye" onclick="togglePin(this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>
            <div class="pin-field" style="margin-bottom:0;">
                <label class="pin-label">{{ __('Confirm PIN') }}</label>
                <div class="pin-input-wrap">
                    <input type="password" class="pin-input" placeholder="Re-enter PIN" name="pin_code_confirmation" maxlength="4" inputmode="numeric" pattern="[0-9]*" oninput="validatePinMatch(this)" required>
                    <button type="button" class="pin-eye" onclick="togglePin(this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <span id="pinMatchMsg" style="margin-top:4px;display:none;"></span>
            </div>
            <button type="submit" class="pin-btn pin-btn-primary" style="margin-top:20px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ __('Set Up PIN') }}
            </button>
        </form>
    </div>
@endif

{{-- Security Tips --}}
<hr class="pin-tip-sep" style="margin:0 16px 16px;">
<div class="pin-card">
    <div class="pin-card-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        {{ __('Security Tips') }}
    </div>
    <div style="display:flex;flex-direction:column;gap:10px;">
        <div style="display:flex;align-items:flex-start;gap:10px;">
            <span class="pin-tip-number">1</span>
            <span class="pin-tip-text">{{ __('Never share your PIN with anyone, including YieldEmpire staff.') }}</span>
        </div>
        <div style="display:flex;align-items:flex-start;gap:10px;">
            <span class="pin-tip-number">2</span>
            <span class="pin-tip-text">{{ __('Use a unique PIN that is different from your other passwords.') }}</span>
        </div>
        <div style="display:flex;align-items:flex-start;gap:10px;">
            <span class="pin-tip-number">3</span>
            <span class="pin-tip-text">{{ __('Change your PIN regularly to keep your account secure.') }}</span>
        </div>
    </div>
</div>

@push('script')
<script>
function togglePin(btn) {
    var input = btn.parentElement.querySelector('input');
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.innerHTML = input.type === 'password'
        ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>'
        : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
}
function validatePinMatch(input) {
    var form = input.closest('form');
    var pinInputs = form.querySelectorAll('input[name="pin_code"], input[name="new_pin"]');
    var pin = pinInputs.length > 0 ? pinInputs[0].value : '';
    var msg = document.getElementById('pinMatchMsg');
    if (!msg) return;
    if (input.value.length === 0) { msg.style.display = 'none'; return; }
    if (input.value !== pin) {
        msg.style.display = 'block';
        msg.style.color = 'var(--danger)';
        msg.textContent = 'PINs do not match';
    } else {
        msg.style.display = 'block';
        msg.style.color = 'var(--success)';
        msg.textContent = 'PINs match';
    }
}
</script>
@endpush
@endsection