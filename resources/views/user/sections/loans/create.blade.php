@extends('user.layouts.master')

@push("css")
<style>
.loan-form .form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary, #94A3B8);
    margin-bottom: 6px;
    display: block;
}
.loan-form .form-control,
.loan-form .form-select {
    padding: 14px 16px;
    border: 1.5px solid var(--border-color, #334155);
    border-radius: 12px;
    font-size: 16px;
    background: var(--bg-card, #1E293B);
    outline: none;
    color: var(--text-primary, #fff);
    width: 100%;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    -webkit-appearance: none;
    appearance: none;
}
.loan-form .form-control:focus,
.loan-form .form-select:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.loan-form .form-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 40px;
}
.loan-form .form-select option {
    background: #1E293B;
    color: #fff;
}
[data-theme="light"] .loan-form .form-control,
[data-theme="light"] .loan-form .form-select {
    background: var(--bg-primary, #fff);
    border-color: #D1D5DB;
    color: #1F2937;
}
[data-theme="light"] .loan-form .form-select option {
    background: #fff;
    color: #1F2937;
}
[data-theme="light"] .loan-form .form-control:focus,
[data-theme="light"] .loan-form .form-select:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}
.loan-form .btn--base {
    padding: 14px 32px;
    border-radius: 100px;
    font-size: 15px;
    font-weight: 600;
    border: none;
    background: linear-gradient(135deg, #3B82F6, #2563EB);
    color: #fff;
    cursor: pointer;
    transition: opacity 0.15s, transform 0.15s;
}
.loan-form .btn--base:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
.loan-form .btn--base:active {
    transform: translateY(0);
}
</style>
@endpush

@section('content')
<div class="dashboard-area mt-10">
    <div class="dashboard-header-wrapper">
        <h3 class="title">{{ __($page_title) }}</h3>
    </div>
</div>
<div class="custom-card mt-3 p-3 loan-form">
    <form method="POST" action="{{ route('user.loans.store') }}" class="row g-3">
        @csrf
        <div class="col-md-6">
            <label class="form-label">{{ __('Loan Product') }}</label>
            <select name="loan_product_id" class="form-select">
                <option value="">{{ __('Custom') }}</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} ({{ number_format($p->interest_rate,2) }}% / {{ $p->term_months }} {{ __('mo') }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Principal Amount') }}</label>
            <input type="number" step="0.01" min="0.01" class="form-control" name="principal" required placeholder="0.00">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Interest Rate (%)') }}</label>
            <input type="number" step="0.0001" min="0" class="form-control" name="interest_rate" required placeholder="e.g. 5.0">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Term (months)') }}</label>
            <input type="number" min="1" max="480" class="form-control" name="term_months" required placeholder="e.g. 12">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Start Date') }}</label>
            <input type="date" class="form-control" name="start_date">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Interest Method') }}</label>
            <select name="interest_method" class="form-select">
                <option value="amortized">{{ __('Amortized') }}</option>
                <option value="simple">{{ __('Simple') }}</option>
                <option value="compound">{{ __('Compound') }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Payment Frequency') }}</label>
            <select name="payment_frequency" class="form-select">
                <option value="monthly">{{ __('Monthly') }}</option>
                <option value="biweekly">{{ __('Biweekly') }}</option>
                <option value="weekly">{{ __('Weekly') }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Grace Days') }}</label>
            <input type="number" min="0" max="60" class="form-control" name="grace_days" value="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Late Fee Type') }}</label>
            <select name="late_fee_type" class="form-select">
                <option value="percent">{{ __('Percent') }}</option>
                <option value="flat">{{ __('Flat') }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Late Fee Value') }}</label>
            <input type="number" step="0.0001" min="0" class="form-control" name="late_fee_value" value="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Early Settlement Fee (%)') }}</label>
            <input type="number" step="0.0001" min="0" class="form-control" name="early_settlement_fee_percent" value="0">
        </div>
        <div class="col-12 d-flex justify-content-end">
            <button class="btn--base">{{ __('Submit Application') }}</button>
        </div>
    </form>
</div>
@endsection
