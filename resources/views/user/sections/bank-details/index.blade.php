@extends('user.layouts.rise-master')

@section('content')
<div class="feed-page">
    <div class="feed-header">
        <h1 class="feed-header-title">Bank Details</h1>
        <p class="feed-header-sub">Manage your receiving bank details for all currencies</p>
    </div>

    {{-- Bank Detail Cards --}}
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
