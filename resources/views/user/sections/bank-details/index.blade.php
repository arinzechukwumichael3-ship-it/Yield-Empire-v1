@extends('user.layouts.rise-master')

@push('css')
<style>
/* Bank Details Page — professional, theme-aware */
.bd-page { background: var(--bg-primary); min-height: calc(100vh - 72px); padding-bottom: 24px; }
.bd-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 16px; position: sticky; top: 0; background: var(--bg-primary); z-index: 10; }
.bd-header-left { display: flex; align-items: center; gap: 12px; }
.bd-back { width: 36px; height: 36px; border-radius: 50%; background: var(--bg-card); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); box-shadow: var(--card-shadow); text-decoration: none; }
.bd-title { font-size: 18px; font-weight: 700; color: var(--text-primary); }
.bd-body { padding: 0 16px; display: flex; flex-direction: column; gap: 20px; }

/* Section card */
.bd-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow); }
.bd-section-title { font-size: 16px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
.bd-section-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; }

/* Empty state */
.bd-empty { text-align: center; padding: 32px 16px; color: var(--text-muted); }
.bd-empty-icon { width: 64px; height: 64px; border-radius: 50%; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 24px; }
.bd-empty-title { font-size: 16px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px; }
.bd-empty-sub { font-size: 13px; color: var(--text-secondary); }

/* Bank detail card */
.bd-card { background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: 14px; padding: 18px; transition: all 0.15s; }
.bd-card:hover { border-color: var(--border-strong); }
.bd-card-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
.bd-card-info { flex: 1; min-width: 0; }
.bd-card-name { font-size: 16px; font-weight: 700; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bd-card-bank { font-size: 13px; color: var(--text-secondary); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bd-badge { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px; flex-shrink: 0; white-space: nowrap; }
.bd-badge--active { background: var(--success-bg); color: var(--success-text); border: 1px solid var(--success); }
.bd-badge--inactive { background: var(--danger-bg); color: var(--danger-text); border: 1px solid var(--danger); }

.bd-card-details { margin-top: 14px; display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px; }
.bd-detail-row { display: flex; flex-direction: column; gap: 4px; }
.bd-detail-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); }
.bd-detail-value { font-size: 14px; font-weight: 600; color: var(--text-primary); font-family: "SF Mono", "Monaco", "Cascadia Code", "Roboto Mono", monospace; word-break: break-all; }
.bd-detail-value--copy { cursor: pointer; position: relative; }
.bd-detail-value--copy:hover { color: var(--accent); }

.bd-card-actions { margin-top: 16px; display: flex; gap: 8px; flex-wrap: wrap; }
.bd-btn { padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 600; border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary); cursor: pointer; transition: all 0.15s; display: inline-flex; align-items: center; gap: 6px; }
.bd-btn:hover { border-color: var(--accent); color: var(--accent); }
.bd-btn--danger { border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.1); color: #EF4444; }
.bd-btn--danger:hover { background: #EF4444; color: #fff; }

/* Add/Edit Form */
.bd-form-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow); }
.bd-form-title { font-size: 16px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
.bd-form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
.bd-field { display: flex; flex-direction: column; gap: 6px; }
.bd-field--full { grid-column: 1 / -1; }
.bd-label { font-size: 13px; font-weight: 600; color: var(--text-primary); }
.bd-input, .bd-select {
    width: 100%; padding: 12px 14px; border-radius: 10px;
    border: 1.5px solid var(--border-color); background: var(--input-bg);
    font-size: 14px; color: var(--text-primary); outline: none; transition: border-color 0.15s;
}
.bd-input:focus, .bd-select:focus { border-color: var(--accent); }
.bd-input::placeholder { color: var(--placeholder); }
.bd-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 40px; }
.bd-error { font-size: 12px; color: var(--danger); }

.bd-form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-color); }
.bd-btn-primary { padding: 12px 24px; border-radius: 999px; font-size: 14px; font-weight: 700; background: var(--accent); color: var(--text-on-accent); border: none; cursor: pointer; transition: all 0.15s; }
.bd-btn-primary:hover { background: var(--blue); }
.bd-btn-secondary { padding: 12px 24px; border-radius: 999px; font-size: 14px; font-weight: 600; background: var(--bg-secondary); color: var(--text-primary); border: 1.5px solid var(--border-color); cursor: pointer; transition: all 0.15s; }
.bd-btn-secondary:hover { border-color: var(--accent); color: var(--accent); }

/* Copy toast */
.bd-toast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%) translateY(10px); background: var(--accent); color: var(--text-on-accent); padding: 12px 24px; border-radius: 999px; font-size: 14px; font-weight: 600; opacity: 0; transition: all 0.3s; pointer-events: none; z-index: 100; }
.bd-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

/* Light theme overrides */
[data-theme="light"] .bd-card { background: var(--bg-primary); }
[data-theme="light"] .bd-card:hover { border-color: #CBD5E1; box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
[data-theme="light"] .bd-input, [data-theme="light"] .bd-select { background: #fff; border-color: #E2E8F0; }
[data-theme="light"] .bd-input:focus, [data-theme="light"] .bd-select:focus { border-color: #3B82F6; }
[data-theme="light"] .bd-btn { background: #F1F5F9; border-color: #E2E8F0; color: #334155; }
[data-theme="light"] .bd-btn:hover { border-color: #3B82F6; color: #2563EB; }
[data-theme="light"] .bd-btn-secondary { background: #F1F5F9; border-color: #E2E8F0; color: #334155; }
[data-theme="light"] .bd-btn-secondary:hover { border-color: #3B82F6; color: #2563EB; }

@media (prefers-reduced-motion: reduce) {
    .bd-card, .bd-input, .bd-select, .bd-btn, .bd-btn-primary, .bd-btn-secondary, .bd-toast { transition: none; }
}
</style>
@endpush

@section('content')
<div class="bd-page">
    <div class="bd-header">
        <div class="bd-header-left">
            <a href="{{ route('user.rise.home') }}" class="bd-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            </a>
            <span class="bd-title">{{ __('Bank Details') }}</span>
        </div>
    </div>

    <div class="bd-body">
        {{-- External Bank Details (user-added) --}}
        <div class="bd-section">
            <h3 class="bd-section-title">
                <span class="bd-section-icon" style="background:rgba(59,130,246,0.12);color:#3B82F6;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </span>
                {{ __('External Bank Accounts') }}
            </h3>

            @if(session('success'))
                <div class="bd-toast" id="bdToast">{{ session('success')[0] }}</div>
            @elseif(session('error'))
                <div class="bd-toast" id="bdToast" style="background:var(--danger);">{{ session('error')[0] }}</div>
            @endif

            <div class="bank-details-list" style="display:flex;flex-direction:column;gap:12px;">
                @forelse($user->bankDetails as $detail)
                <div class="bd-card">
                    <div class="bd-card-head">
                        <div class="bd-card-info">
                            <div class="bd-card-name">{{ $detail->recipient_name }}</div>
                            <div class="bd-card-bank">{{ $detail->bank_name }}</div>
                        </div>
                        <span class="bd-badge {{ $detail->status ? 'bd-badge--active' : 'bd-badge--inactive' }}">
                            {{ $detail->status ? __('Active') : __('Inactive') }}
                        </span>
                    </div>

                    <div class="bd-card-details">
                        <div class="bd-detail-row">
                            <span class="bd-detail-label">{{ __('Account / IBAN') }}</span>
                            <span class="bd-detail-value bd-detail-value--copy" data-copy="{{ $detail->account_number_iban }}" title="{{ __('Click to copy') }}">
                                {{ $detail->account_number_iban }}
                            </span>
                        </div>
                        <div class="bd-detail-row">
                            <span class="bd-detail-label">{{ __('Country') }}</span>
                            <span class="bd-detail-value">{{ $detail->country }}</span>
                        </div>
                        @if($detail->swift_bic)
                        <div class="bd-detail-row">
                            <span class="bd-detail-label">{{ __('SWIFT / BIC') }}</span>
                            <span class="bd-detail-value bd-detail-value--copy" data-copy="{{ $detail->swift_bic }}" title="{{ __('Click to copy') }}">
                                {{ $detail->swift_bic }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="bd-card-actions">
                        <form method="POST" action="{{ route('user.bank.details.toggle', $detail->id) }}">
                            @csrf @method('PUT')
                            <button type="submit" class="bd-btn">
                                {{ $detail->status ? __('Deactivate') : __('Activate') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('user.bank.details.destroy', $detail->id) }}" onsubmit="return confirm('{{ __('Remove this bank detail?') }}');">
                            @csrf @method('DELETE')
                            <button type="submit" class="bd-btn bd-btn--danger">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                {{ __('Remove') }}
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="bd-empty">
                    <div class="bd-empty-icon" style="background:rgba(59,130,246,0.12);color:#3B82F6;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    </div>
                    <div class="bd-empty-title">{{ __('No external bank details yet') }}</div>
                    <div class="bd-empty-sub">{{ __('Add your first bank account below to enable internal transfers and receive money.') }}</div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Add / Edit Bank Detail Form --}}
        <div class="bd-form-section">
            <h3 class="bd-form-title">
                <span class="bd-section-icon" style="background:rgba(34,197,94,0.12);color:#22C55E;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </span>
                {{ __('Add New Bank Detail') }}
            </h3>

            <form method="POST" action="{{ route('user.bank.details.store') }}" id="bdForm">
                @csrf
                <div class="bd-form-grid">
                    <div class="bd-field">
                        <label class="bd-label">{{ __('Recipient Full Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="recipient_name" class="bd-input" value="{{ old('recipient_name') }}" placeholder="{{ __('e.g. Jane Doe') }}" required>
                        @error('recipient_name')<span class="bd-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="bd-field">
                        <label class="bd-label">{{ __('Bank Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" class="bd-input" value="{{ old('bank_name') }}" placeholder="{{ __('e.g. Barclays UK') }}" required>
                        @error('bank_name')<span class="bd-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="bd-field">
                        <label class="bd-label">{{ __('Account Number / IBAN') }} <span class="text-danger">*</span></label>
                        <input type="text" name="account_number_iban" class="bd-input" value="{{ old('account_number_iban') }}" placeholder="{{ __('e.g. GB29 NWBK 6016 1331 9268 19') }}" required>
                        @error('account_number_iban')<span class="bd-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="bd-field">
                        <label class="bd-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                        <select name="country" class="bd-select" required>
                            <option value="" disabled selected>{{ __('Select country') }}</option>
                            @foreach($countries as $countryName)
                                <option value="{{ $countryName }}" {{ old('country') == $countryName ? 'selected' : '' }}>{{ $countryName }}</option>
                            @endforeach
                        </select>
                        @error('country')<span class="bd-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="bd-field">
                        <label class="bd-label">{{ __('SWIFT / BIC') }} <span class="text-muted">({{ __('optional') }})</span></label>
                        <input type="text" name="swift_bic" class="bd-input" value="{{ old('swift_bic') }}" placeholder="{{ __('e.g. NWBKGB2L') }}" maxlength="11">
                        @error('swift_bic')<span class="bd-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="bd-form-actions">
                    <button type="button" class="bd-btn-secondary" onclick="window.location.href='{{ route('user.rise.home') }}'">{{ __('Cancel') }}</button>
                    <button type="submit" class="bd-btn-primary">{{ __('Add Bank Detail') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
(function() {
    // Copy to clipboard for account/IBAN/SWIFT values
    document.querySelectorAll('.bd-detail-value--copy').forEach(function(el) {
        el.style.cursor = 'pointer';
        el.addEventListener('click', function() {
            var text = this.getAttribute('data-copy');
            if (!text) return;
            navigator.clipboard.writeText(text).then(function() {
                showToast('Copied to clipboard');
            }).catch(function() {
                // Fallback
                var ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                ta.remove();
                showToast('Copied to clipboard');
            });
        });
    });

    function showToast(message) {
        var toast = document.getElementById('bdToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'bdToast';
            toast.className = 'bd-toast';
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        toast.style.background = 'var(--accent)';
        toast.classList.add('show');
        setTimeout(function() { toast.classList.remove('show'); }, 2500);
    }

    // Auto-show session toast on load
    var sessionToast = document.getElementById('bdToast');
    if (sessionToast && sessionToast.textContent.trim()) {
        sessionToast.classList.add('show');
        setTimeout(function() { sessionToast.classList.remove('show'); }, 3000);
    }
})();
</script>
@endpush
@endsection