@extends('user.layouts.master')

@push('css')

@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="payment-conformation">
            <div class="payment-loader-wrapper">
                <div class="payment-loader">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                    </svg>
                </div>
                <h4 class="title">{{ __('Withdrawal Request Submitted') }}.</h4>
                <p class="text-center text-muted">{{ __('Please wait for admin confirmation.') }}</p>
                <div class="recive-dwonload-btn">
                    <a href="{{ setRoute('user.money-out.index') }}" class="recive-btn"><i class="las la-angle-double-left"></i> {{ __('Back to Withdraw') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($transaction)
<div class="row justify-content-center receipt-print-area">
    <div class="col-lg-6 col-md-8">
        <div class="banking-statement-area">
            <div class="receipt-head text-center mb-3">
                <img src="{{ get_logo() }}" alt="Bank Logo" style="max-width:140px;">
                <h3 class="mt-2">{{ __('Withdrawal Receipt') }}</h3>
            </div>
            <div class="receipt-info mb-3">
                <p><strong>{{ __('Date') }} :</strong> {{ dateFormat('d F Y', @$transaction->created_at) }}</p>
                <p><strong>{{ __('Account Number') }} :</strong> {{ @$transaction->user->account_no }}</p>
                <p><strong>{{ __('Account Holder') }} :</strong> {{ @$transaction->user->fullname }}</p>
            </div>
            <div class="table-responsive">
                <table class="table receipt-table">
                    <tbody>
                        <tr>
                            <td>{{ __('Transaction Id') }}</td>
                            <td>{{ @$transaction->trx_id }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Gateway') }}</td>
                            <td>{{ @$transaction->gateway_currency->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Request Amount') }}</td>
                            <td>{{ get_amount(@$transaction->request_amount, @$transaction->request_currency) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Fees & Charge') }}</td>
                            <td>{{ get_amount(@$transaction->total_charge, @$transaction->request_currency) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Remark') }}</td>
                            <td>{{ !empty(@$transaction->remark) ? @$transaction->remark : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Status') }}</td>
                            <td><span class="{{ @$transaction->stringStatus->class }}">{{ @$transaction->stringStatus->value }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-end mb-3">
                <p class="mb-0"><strong>{{ __('Total') }} :</strong> {{ get_default_currency_symbol() }}{{ get_amount(@$transaction->total_payable) }}</p>
            </div>
            <div class="text-center print-hide">
                <button type="button" onclick="window.print()" class="recive-btn"><i class="las la-print"></i> {{ __('Print Receipt') }}</button>
            </div>
            <div class="text-center mt-3 receipt-footer">
                <p class="mb-0">{{ __('For inquiries, contact us at') }} {{ @$basic_settings->site_name }}.</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('script')

@endpush
