@extends('user.layouts.master')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="payment-conformation animate-pop">
            <div class="payment-loader-wrapper">
                <div class="payment-loader">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                    </svg>
                </div>
                <h4 class="title">{{ __('Transfer Money Successfully') }}</h4>
                <div class="recive-dwonload-btn">
                    <a href="{{ setRoute('user.fund-transfer.index') }}" class="recive-btn"><i class="las la-angle-double-left"></i> {{ __('Transfer Again') }}</a>
                    <a href="{{ route('user.fund-transfer.pdf.download', $trx_id) }}" class="recive-btn"><i class="las la-download"></i> {{__('Download PDF')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($transaction)
<div class="row justify-content-center receipt-print-area">
    <div class="col-lg-7 col-md-9">
        <div class="banking-statement-area animate-up">
            <div class="receipt-head text-center mb-3">
                <img src="{{ get_logo() }}" alt="Bank Logo" style="max-width:140px;">
                <h3 class="mt-2">{{ __('Fund Transfer Receipt') }}</h3>
            </div>

            <div class="receipt-summary mb-3">
                <div class="rs-amount text--success">+{{ get_amount(@$transaction->request_amount, @$transaction->request_currency) }}</div>
                <div class="rs-status"><span class="{{ @$transaction->stringStatus->class }}">{{ @$transaction->stringStatus->value }}</span></div>
                <div class="rs-meta">
                    <span><i class="las la-calendar"></i> {{ dateFormat('d F Y', @$transaction->created_at) }}</span>
                    <span><i class="las la-hashtag"></i> {{ @$transaction->trx_id }}</span>
                </div>
            </div>

            <div class="receipt-info mb-3">
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
                        @if (@$transaction->details->beneficiary->method)
                        <tr>
                            <td>{{ __('Transaction Type') }}</td>
                            <td>{{ @$transaction->details->beneficiary->method->name }}</td>
                        </tr>
                        @endif
                        @if (@$transaction->details->beneficiary)
                        <tr>
                            <td>{{ __('Recipient Name') }}</td>
                            <td>{{ @$transaction->details->beneficiary->account_holder_name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Recipient Account') }}</td>
                            <td>{{ @$transaction->details->beneficiary->account_number }}</td>
                        </tr>
                        @endif
                        @if (@$transaction->fundReceiverInfo)
                        <tr>
                            <td>{{ @$transaction->fundReceiverInfo->receiver_holder_title }}</td>
                            <td>{{ @$transaction->fundReceiverInfo->receiver_holder_value }}</td>
                        </tr>
                        <tr>
                            <td>{{ @$transaction->fundReceiverInfo->receiver_number_title }}</td>
                            <td>{{ @$transaction->fundReceiverInfo->receiver_number_value }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>{{ __('Request Amount') }}</td>
                            <td>{{ get_amount(@$transaction->request_amount, @$transaction->request_currency) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Fees & Charge') }}</td>
                            <td>{{ get_amount(@$transaction->total_charge, @$transaction->request_currency) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Total Payable') }}</td>
                            <td>{{ get_amount(@$transaction->total_payable, @$transaction->request_currency) }}</td>
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
                <p class="mb-0"><strong>{{ __('Total') }} :</strong> {{ get_amount(@$transaction->total_payable, @$transaction->request_currency) }}</p>
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

@push('style')
<style>
    .animate-pop { animation: pop .45s cubic-bezier(.2,.8,.3,1.2) both; }
    @keyframes pop { from { opacity:0; transform: scale(.92); } to { opacity:1; transform: scale(1); } }
    .animate-up { opacity:0; transform: translateY(18px); animation: up .55s ease .15s forwards; }
    @keyframes up { to { opacity:1; transform: translateY(0); } }
    .receipt-summary { text-align:center; padding:18px; border-radius:16px; background:linear-gradient(135deg, rgba(40,167,69,.08), rgba(40,167,69,.02)); border:1px solid rgba(40,167,69,.18); }
    .rs-amount { font-size:30px; font-weight:800; }
    .rs-status { margin:6px 0; }
    .rs-status span { display:inline-block; padding:3px 14px; border-radius:30px; font-size:13px; }
    .rs-meta { display:flex; gap:18px; justify-content:center; flex-wrap:wrap; font-size:13px; color:#6b7280; margin-top:6px; }
    .rs-meta i { margin-right:5px; }
</style>
@endpush

@push('script')

@endpush
