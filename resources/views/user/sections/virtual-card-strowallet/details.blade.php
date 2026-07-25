@extends('user.layouts.rise-master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __(@$page_title)])
@endsection

@section('content')
<div class="dashboard-area mt-10">

</div>
<div class="ibankng-card">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="dash-payment-item-wrapper">
                <div class="dash-payment-item active">
                    <div class="dash-payment-title-area">
                        <span class="dash-payment-badge">!</span>
                        <h5 class="title">{{ __(@$page_title) }}</h5>
                    </div>
                    <div class="dash-payment-body">
                        <div class="exchange-area-wrapper text-center">
                            <div class="exchange-area mb-20">
                                <code class="d-block text-center"><span>{{ __("Current Balance") }}</span>
                                    {{ getAmount(@$myCard->balance,2) }} {{ get_default_currency_code() }}
                                </code>
                            </div>
                        </div>
                        <div class="preview-list-wrapper">
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-hourglass-end"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Card Type") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--warning">{{ __((ucwords(@$myCard->card_type))) }}</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-hourglass-end"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Card Brand") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--warning">{{ __((ucwords(@$myCard->card_brand??"Visa"))) }}</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-credit-card"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Card ID") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span>{{ @$myCard->card_id }}</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-hourglass-end "></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Customer ID") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span>{{ @$myCard->customer_id }}</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-user-tag"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Card Number") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    @php
                                    $card_pan = str_split($myCard->card_number, 4);
                                   @endphp
                                       @foreach($card_pan as $key => $value)
                                       <span>{{ @$value }}</span>
                                       @endforeach
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-hourglass-start"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Cvv") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="vcv-detail-value" id="detailCvvValue" data-vc-cvv>—</span>
                                    <button type="button" class="vc-copy-btn" id="detailCvvBtn" data-target="{{ $myCard->id }}" title="Show / hide CVV">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-business-time"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{__("Expiration")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span>{{@$myCard->expiry }}</span>
                                </div>
                            </div>
    
    
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-city"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("City") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span>{{ @$myCard->user->strowallet_customer->city??"" }}</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-city"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("State") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span>{{ $myCard->user->strowallet_customer->state??"" }}</span>
                                </div>
                            </div>
    
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-file-archive"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{__("Zip Code")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span>{{ @$myCard->user->strowallet_customer->zipCode??"" }}</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-battery-half"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{__("Status") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <div class="toggle-container">
                                        @include('admin.components.form.switcher',[  
                                            'name'          => "is_active",
                                            'value'         => old('is_active',@$myCard->is_active),
                                            'options'       => [__('UnFreeze') => 1,__('Freeze') => 0],
                                            'onload'        => true,
                                            'data_target'   => @$myCard->id,
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="dash-payment-item-wrapper">
                <div class="dash-payment-item active">
                    <div class="dash-payment-title-area">
                        <span class="dash-payment-badge">!</span>
                        <h5 class="title">{{ __("Billing Address") }}</h5>
                    </div>
                    <div class="dash-payment-body">
                        <ul class="billing-list">
                            <li>
                                <span>{{ __("Billing Country") }}</span>
                                <h6>{{ __("United State") }}</h6>
                            </li>
                            <li>
                                <span>{{ __("Billing City") }}</span>
                                <h6>{{ __("Miami") }}</h6>
                            </li>
                            <li>
                                <span>{{ __("Billing State") }}</span>
                                <h6>3401 N. Miami, Ave. Ste 230</h6>
                            </li>
                            <li>
                                <span>{{ __("Billing Zip Code") }}</span>
                                <h6>33127</h6>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function () {
        switcherAjax("{{ setRoute('user.strowallet.virtual.card.change.status') }}");

        $('#detailCvvBtn').on('click', function () {
            var $btn = $(this);
            var $val = $('#detailCvvValue');
            var eye = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
            var eyeOff = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
            if ($val.data('revealed')) {
                $val.text('—'); $val.data('revealed', false); $btn.find('svg').replaceWith(eye);
                return;
            }
            var csrf = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route("user.strowallet.virtual.card.cvv") }}',
                method: 'POST',
                data: { data_target: $btn.data('target'), _token: csrf },
                success: function (res) {
                    if (res.type === 'success' && res.data && res.data.cvv) {
                        $val.text(res.data.cvv); $val.data('revealed', true); $btn.find('svg').replaceWith(eyeOff);
                    } else {
                        alert((res.message && res.message.error && res.message.error[0]) || 'Could not load CVV');
                    }
                },
                error: function () { alert('Network error - try again'); }
            });
        });
    });
</script>
@endpush
