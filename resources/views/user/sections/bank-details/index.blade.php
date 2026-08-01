@extends('user.layouts.rise-master')

@section('content')
<div class="feed-page">
    <div class="feed-header">
        <h1 class="feed-header-title">Bank Details</h1>
        <p class="feed-header-sub">Manage your receiving bank details for all currencies</p>
    </div>

    {{-- Your Auto-Generated EnzoBank International Details --}}
    <div class="bank-detail-card" style="background:linear-gradient(135deg,rgba(59,130,246,0.1),rgba(37,99,235,0.05));border:1px solid rgba(59,130,246,0.3);border-radius:14px;padding:20px;margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
            <span style="font-size:24px;">🏦</span>
            <h4 style="margin:0;font-size:18px;font-weight:700;color:var(--text-primary,#fff);">{{ __('Your EnzoBank International Details') }}</h4>
            <span style="margin-left:auto;padding:4px 12px;border-radius:999px;font-size:11px;font-weight:700;background:rgba(34,197,94,0.12);color:#22C55E;">{{ __('Auto-Generated') }}</span>
        </div>
        <p style="margin:0 0 16px;font-size:13px;color:var(--text-secondary,#94A3B8);">These details are automatically generated for your account. Share them with other EnzoBank users to receive instant transfers.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;">
            <div>
                <label style="display:block;font-size:11px;color:var(--text-muted,#64748B);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">{{ __('Bank Name') }}</label>
                <div style="font-size:15px;font-weight:600;color:var(--text-primary,#fff);">{{ $user->network_bank_name ?? 'EnzoBank' }}</div>
            </div>
            <div>
                <label style="display:block;font-size:11px;color:var(--text-muted,#64748B);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">{{ __('Account Number') }}</label>
                <div style="font-size:15px;font-weight:600;color:var(--text-primary,#fff);font-family:monospace;">{{ $user->network_account_number }}</div>
            </div>
            <div>
                <label style="display:block;font-size:11px;color:var(--text-muted,#64748B);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">{{ __('IBAN') }}</label>
                <div style="font-size:15px;font-weight:600;color:var(--text-primary,#fff);font-family:monospace;word-break:break-all;">{{ $user->network_iban }}</div>
            </div>
            <div>
                <label style="display:block;font-size:11px;color:var(--text-muted,#64748B);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">{{ __('SWIFT / BIC') }}</label>
                <div style="font-size:15px;font-weight:600;color:var(--text-primary,#fff);font-family:monospace;">{{ $user->network_swift ?? 'ENZOUS33' }}</div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap;">
            <button onclick="navigator.clipboard.writeText('{{ $user->network_account_number }}')" style="padding:8px 16px;border-radius:8px;font-size:12px;font-weight:600;background:var(--bg-elevated,#1E293B);border:1px solid var(--border-color,#334155);color:var(--text-secondary,#94A3B8);cursor:pointer;">{{ __('Copy Account #') }}</button>
            <button onclick="navigator.clipboard.writeText('{{ $user->network_iban }}')" style="padding:8px 16px;border-radius:8px;font-size:12px;font-weight:600;background:var(--bg-elevated,#1E293B);border:1px solid var(--border-color,#334155);color:var(--text-secondary,#94A3B8);cursor:pointer;">{{ __('Copy IBAN') }}</button>
            <button onclick="navigator.clipboard.writeText('{{ $user->network_swift ?? 'ENZOUS33' }}')" style="padding:8px 16px;border-radius:8px;font-size:12px;font-weight:600;background:var(--bg-elevated,#1E293B);border:1px solid var(--border-color,#334155);color:var(--text-secondary,#94A3B8);cursor:pointer;">{{ __('Copy SWIFT') }}</button>
        </div>
    </div>

    {{-- Bank Detail Cards (for external bank transfers) --}}
    <div class="bank-details-list" style="display:flex;flex-direction:column;gap:12px;padding-bottom:16px;">
        @forelse($user->bankDetails as $detail)
        <div class="bank-detail-card" style="background:var(--bg-card,#111827);border:1px solid var(--border-color,#1E293B);border-radius:14px;padding:16px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <h4 style="margin:0;font-size:16px;font-weight:700;color:var(--text-primary,#fff);">{{ $detail->recipient_name }}</h4>
                    <p style="margin:4px 0 0;font-size:13px;color:var(--text-secondary,#94A3B8);">{{ $detail->bank_name }}</p>
                </div>
                <span style="padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;background:{{ $detail->status ? 'rgba(34,197,94,0.12)' : 'rgba(239,68,68,0.12)' }};color:{{ $detail->status ? '#22C55E' : '#EF4444' }};">{{ $detail->status ? 'Active' : 'Inactive' }}</span>
            </div>
            <div style="margin-top:12px;display:flex;flex-direction:column;gap:4px;font-size:13px;">
                <div style="display:flex;justify-content:space-between;"><span style="color:var(--text-muted,#64748B);">Account / IBAN</span><span style="color:var(--text-primary,#fff);font-weight:600;">{{ $detail->account_number_iban }}</span></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:var(--text-muted,#64748B);">Country</span><span style="color:var(--text-primary,#fff);font-weight:600;">{{ $detail->country }}</span></div>
                @if($detail->swift_bic)
                <div style="display:flex;justify-content:space-between;"><span style="color:var(--text-muted,#64748B);">SWIFT / BIC</span><span style="color:var(--text-primary,#fff);font-weight:600;">{{ $detail->swift_bic }}</span></div>
                @endif
            </div>
            <div style="margin-top:12px;display:flex;gap:8px;">
                <form method="POST" action="{{ route('user.bank.details.toggle', $detail->id) }}">
                    @csrf @method('PUT')
                    <button type="submit" style="padding:6px 14px;border-radius:8px;font-size:12px;font-weight:600;background:var(--bg-elevated,#1E293B);border:1px solid var(--border-color,#334155);color:var(--text-secondary,#94A3B8);cursor:pointer;">{{ $detail->status ? 'Deactivate' : 'Activate' }}</button>
                </form>
                <form method="POST" action="{{ route('user.bank.details.destroy', $detail->id) }}" onsubmit="return confirm('Remove this bank detail?');">
                    @csrf @method('DELETE')
                    <button type="submit" style="padding:6px 14px;border-radius:8px;font-size:12px;font-weight:600;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#EF4444;cursor:pointer;">Remove</button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:32px 16px;color:var(--text-muted,#64748B);">
            <p style="margin:0;font-size:14px;">No bank details added yet.</p>
            <p style="margin:4px 0 0;font-size:12px;">Add your bank details below to receive money in any currency.</p>
        </div>
        @endforelse
    </div>

    {{-- Add Bank Detail Form --}}
    <div class="custom-card mt-15">
        <div class="card-header">
            <h6 class="title">{{ __("Add New Bank Detail") }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('user.bank.details.store') }}">
                @csrf
                <div class="row mb-10-none">
                    <div class="col-xl-6 col-lg-6 form-group">
                        <label class="form-label">Recipient Full Name *</label>
                        <input type="text" name="recipient_name" class="form--control" value="{{ old('recipient_name') }}" placeholder="e.g. Jane Doe" required>
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        <label class="form-label">Bank Name *</label>
                        <input type="text" name="bank_name" class="form--control" value="{{ old('bank_name') }}" placeholder="e.g. Barclays UK" required>
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        <label class="form-label">Account Number / IBAN *</label>
                        <input type="text" name="account_number_iban" class="form--control" value="{{ old('account_number_iban') }}" placeholder="e.g. GB29 NWBK 6016 1331 9268 19" required>
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        <label class="form-label">Country *</label>
                        <select name="country" class="form--control" required>
                            <option value="" disabled selected>{{ __("Select country") }}</option>
                            @foreach($countries as $countryName)
                                <option value="{{ $countryName }}" {{ old('country') == $countryName ? 'selected' : '' }}>{{ $countryName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        <label class="form-label">SWIFT / BIC (optional)</label>
                        <input type="text" name="swift_bic" class="form--control" value="{{ old('swift_bic') }}" placeholder="e.g. NWBKGB2L" maxlength="11">
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                        <button type="button" class="btn btn--danger modal-close">{{ __("Close") }}</button>
                        <button type="submit" class="btn btn--base">{{ __("Add Bank Detail") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
