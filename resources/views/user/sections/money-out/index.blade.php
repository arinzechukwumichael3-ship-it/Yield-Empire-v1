@extends('user.layouts.rise-master')

@push('css')
<style>

</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ __($page_title) }}</h1>
</div>
<div class="am-body">
    <!-- Exchange Rate Banner -->
    <div class="am-rate-banner">
        <div>
            <div class="am-rate-label">{{ __('Exchange Rate') }}</div>
            <div class="am-rate-value exchange-rate rate">--</div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="am-card">
        <form method="POST" action="{{ setRoute('user.money-out.submit') }}">
            @csrf
            <div class="am-field-group">
                <label class="am-label">{{ __('Enter Amount') }}<span>*</span></label>
                <div class="am-input-wrap">
                    <input type="number" name="amount" placeholder="{{ __('Enter Amount') }}" step="any">
                    <span class="am-input-pill">{{ get_default_currency_code() }}</span>
                </div>
            </div>
            <div class="am-field-group">
                <label class="am-label">{{ __('Payment Gateway') }}<span>*</span></label>
                <div class="am-input-wrap">
                    <select name="payment_gateway">
                        @php $old_payment_gateway = old('payment_gateway'); @endphp
                        <option selected disabled>{{ __('Select Gateway') }}</option>
                        @foreach ($payment_gateways as $item)
                            <option value="{{ $item->alias }}" data-item="{{ json_encode($item->currencies()->select(['name','rate','currency_code','percent_charge','fixed_charge','min_limit','max_limit'])->first()) }}" @if ($old_payment_gateway == $item->alias) @selected(true) @endif>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="am-field-group">
                <span class="am-hint limit-show">--</span>
                <span class="am-hint fees-show">--</span>
            </div>
            <button type="submit" class="am-btn">{{ __('Money Out') }} <i class="las la-chevron-right"></i></button>
        </form>
    </div>

    <!-- Preview Card -->
    <div class="am-card">
        <div class="am-card-title">{{ __('Money Out Preview') }}</div>
        <div class="am-preview-row">
            <div class="am-preview-icon"><i class="las la-receipt"></i></div>
            <div class="am-preview-label">{{ __('Money Out Amount') }}</div>
            <div class="am-preview-value withdraw-amount">--</div>
        </div>
        <div class="am-preview-row">
            <div class="am-preview-icon"><i class="las la-battery-half"></i></div>
            <div class="am-preview-label">{{ __('Total Fees & Charges') }}</div>
            <div class="am-preview-value total-charges">--</div>
        </div>
        <div class="am-preview-row">
            <div class="am-preview-icon"><i class="lab la-get-pocket"></i></div>
            <div class="am-preview-label">{{ __('Will Get') }}</div>
            <div class="am-preview-value will-get">--</div>
        </div>
        <div class="am-preview-row">
            <div class="am-preview-icon"><i class="las la-money-check-alt"></i></div>
            <div class="am-preview-label">{{ __('Total Payable Amount') }}</div>
            <div class="am-preview-value payable">--</div>
        </div>
    </div>

    <!-- Transaction Log -->
    <div class="am-card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div class="am-card-title" style="margin-bottom:0;">{{ __('Money Out Log') }}</div>
            <a href="{{ setRoute('user.transactions.index', 'money-out') }}" class="am-log-link">{{ __('View More') }} <i class="las la-chevron-right"></i></a>
        </div>
        @include('user.components.transaction.log', compact('transactions'))
    </div>
</div>
@endsection

@push('script')
    <script>

        let default_currency_code = "{{ get_default_currency_code() }}";

        $("select[name=payment_gateway]").change(function() {
            run();
        });

        $("input[name=amount]").keyup(function() {
            run();
        });

        function run() {
            let paymentGatewaySelect = $("select[name=payment_gateway]");
            let gatewaySelectedValue = paymentGatewaySelect.val();

            if(gatewaySelectedValue == null || gatewaySelectedValue == "") return false;

            let amount = $("input[name=amount]").val();

            let gatewayCurrency = JSON.parse(paymentGatewaySelect.find(":selected").attr("data-item"));

            (amount == null || amount == "" || !$.isNumeric(amount)) ? amount = 0 : amount = amount;

            amount = parseFloat(amount).toFixed(2);

            $(".withdraw-amount").text(`${amount} ${default_currency_code}`);

            let fixedCharge         = gatewayCurrency.fixed_charge ?? 0;
            let percentCharge       = gatewayCurrency.percent_charge ?? 0;
            let minLimit            = gatewayCurrency.min_limit ?? 0;
            let maxLimit            = gatewayCurrency.max_limit ?? 0;
            let rate                = gatewayCurrency.rate ?? 1;
            let gatewayCurrencyCode = gatewayCurrency.currency_code ?? "-";

            var min_limit_calc = parseFloat(minLimit/rate).toFixed(2);
            var max_limit_clac = parseFloat(maxLimit/rate).toFixed(2);

            $('.limit-show').html("Limit " + min_limit_calc + " " + default_currency_code + " - " + max_limit_clac + " " + default_currency_code);

            $(".exchange-rate").text(`1 ${default_currency_code} = ${parseFloat(rate).toFixed(2)} ${gatewayCurrencyCode}`);

            let fixedChargeCalc = (parseFloat(fixedCharge) / parseFloat(rate)); // default currency fixed charge
            let percentChargeCalc = ((((parseFloat(amount) * parseFloat(rate)) / 100) * parseFloat(percentCharge)) / parseFloat(rate));

            let totalCharge = parseFloat(fixedChargeCalc) + parseFloat(percentChargeCalc) // total charge in default currency
            $(".total-charges").text(`${parseFloat(totalCharge).toFixed(2)} ${default_currency_code}`);

            $(".fees-show").html("Charge: " + parseFloat(fixedChargeCalc).toFixed(2) + " " + default_currency_code + " + " + parseFloat(percentCharge).toFixed(2) + "%");

            let willGet = parseFloat(amount) * parseFloat(rate); // get amount with gateway currency
            willGet = willGet.toFixed(2);

            $('.will-get').text(`${willGet} ${gatewayCurrencyCode}`);

            let totalPayable = parseFloat(amount) + parseFloat(totalCharge);
            totalPayable = totalPayable.toFixed(2);
            $(".payable").text(`${totalPayable} ${default_currency_code}`);
        }
    </script>
@endpush
