@extends('user.layouts.rise-master')

@push('css')
<style>
.loan-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}
.loan-grid .am-field-group { margin-bottom: 0; }
.loan-actions { margin-top: 20px; }
@media (max-width: 560px) {
    .loan-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ __('Apply Loan') }}</h1>
    <a href="{{ route('user.loans.index') }}" class="rw-section-link-pill">← {{ __('Back') }}</a>
</div>

<div class="am-body">
    <div class="am-card">
        <form method="POST" action="{{ route('user.loans.store') }}">
            @csrf
            <div class="loan-grid">
                <div class="am-field-group">
                    <label class="am-label">{{ __('Loan Product') }}</label>
                    <div class="am-input-wrap">
                        <select name="loan_product_id">
                            <option value="">{{ __('Custom') }}</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ number_format($p->interest_rate,2) }}% / {{ $p->term_months }} {{ __('mo') }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Principal Amount') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" step="0.01" min="0.01" name="principal" required placeholder="0.00">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Interest Rate (%)') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" step="0.0001" min="0" name="interest_rate" required placeholder="e.g. 5.0">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Term (months)') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" min="1" max="480" name="term_months" required placeholder="e.g. 12">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Start Date') }}</label>
                    <div class="am-input-wrap">
                        <input type="date" name="start_date">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Interest Method') }}</label>
                    <div class="am-input-wrap">
                        <select name="interest_method">
                            <option value="amortized">{{ __('Amortized') }}</option>
                            <option value="simple">{{ __('Simple') }}</option>
                            <option value="compound">{{ __('Compound') }}</option>
                        </select>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Payment Frequency') }}</label>
                    <div class="am-input-wrap">
                        <select name="payment_frequency">
                            <option value="monthly">{{ __('Monthly') }}</option>
                            <option value="biweekly">{{ __('Biweekly') }}</option>
                            <option value="weekly">{{ __('Weekly') }}</option>
                        </select>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Grace Days') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" min="0" max="60" name="grace_days" value="0">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Late Fee Type') }}</label>
                    <div class="am-input-wrap">
                        <select name="late_fee_type">
                            <option value="percent">{{ __('Percent') }}</option>
                            <option value="flat">{{ __('Flat') }}</option>
                        </select>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Late Fee Value') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" step="0.0001" min="0" name="late_fee_value" value="0">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Early Settlement Fee (%)') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" step="0.0001" min="0" name="early_settlement_fee_percent" value="0">
                    </div>
                </div>
            </div>
            <div class="loan-actions">
                <button type="submit" class="am-btn">{{ __('Submit Application') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
