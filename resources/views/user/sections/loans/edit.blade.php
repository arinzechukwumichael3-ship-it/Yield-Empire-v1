@extends('user.layouts.rise-master')

@push('css')
<style>
.loan-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}
.loan-grid .am-field-group { margin-bottom: 0; }
.loan-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: var(--accent-soft, rgba(59,130,246,0.1));
    border: 1px solid var(--accent, #3B82F6);
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 18px;
    font-size: 12.5px;
    line-height: 1.5;
    color: var(--text-secondary);
}
.loan-note svg { flex-shrink: 0; margin-top: 1px; color: var(--accent, #3B82F6); }
.loan-actions { margin-top: 20px; }
@media (max-width: 560px) {
    .loan-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ __('Edit Loan') }}</h1>
    <a href="{{ route('user.loans.index') }}" class="rw-section-link-pill">← {{ __('Back') }}</a>
</div>

<div class="am-body">
    <div class="am-card">
        <form method="POST" action="{{ route('user.loans.update', $loan->id) }}">
            @csrf
            @method('PUT')
            <div class="loan-note">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                <span>{{ __('EnzoBank is an international bank. Applicants from any country can apply — choose your country and preferred currency below.') }}</span>
            </div>
            <div class="loan-grid">
                <div class="am-field-group">
                    <label class="am-label">{{ __('Country') }}</label>
                    <div class="am-input-wrap">
                        <select name="country">
                            <option value="">{{ __('Select country') }}</option>
                            @foreach($countries as $c)
                                <option value="{{ $c }}" @selected($loan->country === $c)>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Currency') }}</label>
                    <div class="am-input-wrap">
                        <select name="currency">
                            @foreach($currencies as $cur)
                                <option value="{{ $cur }}" @selected($loan->currency === $cur)>{{ $cur }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Interest Rate (%)') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" step="0.0001" min="0" name="interest_rate" required value="{{ $loan->interest_rate }}">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Term (months)') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" min="1" max="480" name="term_months" required value="{{ $loan->term_months }}">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Start Date') }}</label>
                    <div class="am-input-wrap">
                        <input type="date" name="start_date" value="{{ $loan->start_date?->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Interest Method') }}</label>
                    <div class="am-input-wrap">
                        <select name="interest_method">
                            @foreach(['amortized' => __('Amortized'), 'simple' => __('Simple'), 'compound' => __('Compound')] as $val => $label)
                                <option value="{{ $val }}" @selected($loan->interest_method === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Payment Frequency') }}</label>
                    <div class="am-input-wrap">
                        <select name="payment_frequency">
                            @foreach(['monthly' => __('Monthly'), 'biweekly' => __('Biweekly'), 'weekly' => __('Weekly')] as $val => $label)
                                <option value="{{ $val }}" @selected($loan->payment_frequency === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Grace Days') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" min="0" max="60" name="grace_days" value="{{ $loan->grace_days }}">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Late Fee Type') }}</label>
                    <div class="am-input-wrap">
                        <select name="late_fee_type">
                            @foreach(['percent' => __('Percent'), 'flat' => __('Flat')] as $val => $label)
                                <option value="{{ $val }}" @selected($loan->late_fee_type === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Late Fee Value') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" step="0.0001" min="0" name="late_fee_value" value="{{ $loan->late_fee_value }}">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Early Settlement Fee (%)') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" step="0.0001" min="0" name="early_settlement_fee_percent" value="{{ $loan->early_settlement_fee_percent }}">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Status') }}</label>
                    <div class="am-input-wrap">
                        <select name="status" required>
                            @foreach(['pending','active','closed','defaulted'] as $s)
                                <option value="{{ $s }}" @selected($loan->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="loan-actions">
                <button type="submit" class="am-btn">{{ __('Save Changes') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
