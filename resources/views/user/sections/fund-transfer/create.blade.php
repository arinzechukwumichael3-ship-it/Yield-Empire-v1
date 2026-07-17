@extends('user.layouts.master')

@section('content')
@php
    $default_code = '';
    foreach ($user_wallets as $uw) {
        if ($uw->currency && $uw->currency->default) { $default_code = $uw->currency->code; break; }
    }
    if (!$default_code && $user_wallets->first() && $user_wallets->first()->currency) {
        $default_code = $user_wallets->first()->currency->code;
    }
@endphp
<div class="transfer-money-area pt-3">
    <div class="row mb-40-none">
        <div class="col-lg-6 mb-40">
           <div class="transfer-money-title pb-10">
               <h3 class="title">{{ __($page_title) }} {{ __("To") }} <span class="text--base">{{ $temp_data->data->beneficiary->account_holder_name }}</span></h3>
           </div>

           <div class="transfer-card animate-in">
               <form class="card-form" action="{{ setRoute('user.fund-transfer.submit') }}" method="POST" id="transferForm">
                    @csrf
                    <input type="hidden" name="temp_token" value="{{ $token }}">
                    <input type="hidden" name="currency" id="currencyInput" value="{{ $default_code }}">

                    <div class="form-group">
                        <label>{{ __('Transfer From (Wallet)') }} <span>*</span></label>
                        <div class="wallet-selector">
                            @foreach($user_wallets as $uw)
                                @if($uw->currency)
                                <div class="wallet-card {{ ($uw->currency->default) ? 'active' : '' }}" data-code="{{ $uw->currency->code }}" data-balance="{{ $uw->balance }}" data-symbol="{{ $uw->currency->symbol }}">
                                    <div class="wallet-card-top">
                                        <span class="wallet-flag">{{ $uw->currency->code }}</span>
                                        <span class="wallet-sym">{{ $uw->currency->symbol }}</span>
                                    </div>
                                    <div class="wallet-bal">{{ get_amount($uw->balance, $uw->currency->code) }}</div>
                                    <div class="wallet-label">{{ __('Balance') }}</div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Enter Amount') }} <span>*</span></label>
                        <div class="input-group currency-type">
                            <input type="number" class="form--control" name="amount" id="amountInput" placeholder="{{ __('Enter Amount') }}" min="0" step="0.01">
                            <div class="currency">
                                <p id="amountCurrency">{{ $default_code ?: get_default_currency_code() }}</p>
                            </div>
                        </div>
                        <div class="available-balance">
                            {{ __('Available Balance') }}: <strong id="availBalance" class="text--success">--</strong>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{__('Remarks')}} <span>({{ __('Optional') }})</span></label>
                        <textarea class="form--control" name="remarks" placeholder="{{ __('Explain Request Purposes Here') }}…"></textarea>
                    </div>

                    <div class="note-area">
                        <code class="text--base limit-show">--</code>
                        <code class="text--base charge-show">--</code>
                    </div>

                    <button type="submit" class="btn--base btn-loading w-100 mt-2">{{ __('Transfer Money') }} <i class="las la-chevron-right"></i></button>
               </form>
           </div>
        </div>

        <div class="col-lg-6 mb-40">
            <div class="transfer-preview-area animate-in" style="animation-delay:.1s">
                <div class="preview-area-title pb-10">
                    <h3 class="title">{{ __('Transfer Preview') }}</h3>
                </div>
                <div class="receipt-preview">
                    <div class="receipt-row">
                        <span>{{ __('Recipient') }}</span>
                        <strong class="text--base">{{ $temp_data->data->beneficiary->account_holder_name }}</strong>
                    </div>
                    <div class="receipt-row">
                        <span>{{ __('Entered Amount') }}</span>
                        <strong class="text--success enter-amount">--</strong>
                    </div>
                    <div class="receipt-row">
                        <span>{{ __('Total Fees & Charges') }}</span>
                        <strong class="text--warning fees">--</strong>
                    </div>
                    <div class="receipt-row">
                        <span>{{ __('Receiver Will Get') }}</span>
                        <strong class="text--danger will-get">--</strong>
                    </div>
                    <div class="receipt-row total">
                        <span>{{ __('Total Payable') }}</span>
                        <strong class="text--info payable">--</strong>
                    </div>
                </div>
            </div>

            <div class="transfer-preview-area mt-4 animate-in" style="animation-delay:.2s">
                <div class="preview-area-title pb-10">
                    <h3 class="title">{{ __('Limit Information') }}</h3>
                </div>
                <div class="preview-list-wrapper">
                    <div class="preview-list-item">
                        <div class="preview-list-left"><div class="preview-list-user-wrapper"><div class="preview-list-user-icon"><i class="las la-receipt"></i></div><div class="preview-list-user-content"><span>{{ __('Transaction Limit') }}</span></div></div></div>
                        <div class="preview-list-right"><span class="text--success">{{ get_amount($fees_and_charge->min_limit, get_default_currency_code()) }} - {{ get_amount($fees_and_charge->max_limit, get_default_currency_code()) }}</span></div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left"><div class="preview-list-user-wrapper"><div class="preview-list-user-icon"><i class="las la-battery-half"></i></div><div class="preview-list-user-content"><span>{{ __('Daily Limit') }}</span></div></div></div>
                        <div class="preview-list-right"><span class="text--warning">{{ get_amount($fees_and_charge->daily_limit, get_default_currency_code()) }}</span></div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left"><div class="preview-list-user-wrapper"><div class="preview-list-user-icon"><i class="las la-battery-half"></i></div><div class="preview-list-user-content"><span>{{ __('Remaining Daily Limit') }}</span></div></div></div>
                        <div class="preview-list-right"><span class="text--danger">{{ get_amount($remaining_daily_amount,get_default_currency_code()) }}</span></div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left"><div class="preview-list-user-wrapper"><div class="preview-list-user-icon"><i class="las la-money-check-alt"></i></div><div class="preview-list-user-content"><span>{{ __('Monthly Limit') }}</span></div></div></div>
                        <div class="preview-list-right"><span class="text--info">{{ get_amount($fees_and_charge->monthly_limit,get_default_currency_code()) }}</span></div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left"><div class="preview-list-user-wrapper"><div class="preview-list-user-icon"><i class="las la-money-check-alt"></i></div><div class="preview-list-user-content"><span>{{ __('Remaining Monthly Limit') }}</span></div></div></div>
                        <div class="preview-list-right"><span class="text--info">{{ get_amount($remaining_monthly_amount,get_default_currency_code()) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .transfer-card { background:#fff; border-radius:16px; padding:22px; box-shadow:0 10px 30px rgba(0,0,0,.06); }
    .animate-in { opacity:0; transform:translateY(14px); animation:fadeUp .5s ease forwards; }
    @keyframes fadeUp { to { opacity:1; transform:translateY(0); } }
    .wallet-selector { display:flex; flex-wrap:wrap; gap:12px; }
    .wallet-card { flex:1 1 120px; border:2px solid #e7ecf3; border-radius:14px; padding:14px 12px; cursor:pointer; transition:all .25s ease; background:#f8fafc; position:relative; }
    .wallet-card:hover { transform:translateY(-3px); border-color:#c7d2e6; }
    .wallet-card.active { border-color:var(--base,#2a7de1); background:linear-gradient(135deg, rgba(42,125,225,.10), rgba(42,125,225,.02)); box-shadow:0 8px 20px rgba(42,125,225,.18); }
    .wallet-card.active::after { content:"\f00c"; font-family:"Line Awesome Free"; font-weight:900; position:absolute; top:8px; right:10px; color:#fff; background:var(--base,#2a7de1); width:20px; height:20px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; }
    .wallet-card-top { display:flex; justify-content:space-between; align-items:center; }
    .wallet-flag { font-weight:700; color:#1f2d3d; letter-spacing:.5px; }
    .wallet-sym { font-size:18px; color:var(--base,#2a7de1); }
    .wallet-bal { font-size:17px; font-weight:700; margin-top:8px; color:#111; }
    .wallet-label { font-size:11px; color:#8a94a6; text-transform:uppercase; letter-spacing:.5px; }
    .available-balance { margin-top:8px; font-size:13px; color:#6b7280; }
    .available-balance strong { font-size:14px; }
    .receipt-preview { border:1px dashed #d7dee8; border-radius:14px; padding:16px 18px; background:#fbfdff; }
    .receipt-row { display:flex; justify-content:space-between; align-items:center; padding:9px 0; border-bottom:1px solid #eef2f7; }
    .receipt-row:last-child { border-bottom:none; }
    .receipt-row span { color:#6b7280; font-size:14px; }
    .receipt-row strong { font-size:15px; transition:color .3s ease; }
    .receipt-row.total { margin-top:6px; border-top:2px solid #e3e9f2; border-bottom:none; padding-top:14px; }
    .receipt-row.total strong { font-size:18px; }
</style>
@endpush

@push('script')
    <script>
    function selectWallet(card){
        document.querySelectorAll('.wallet-card').forEach(c => c.classList.remove('active'));
        card.classList.add('active');
        const code = card.dataset.code;
        const symbol = card.dataset.symbol;
        const balance = parseFloat(card.dataset.balance || 0);
        document.getElementById('currencyInput').value = code;
        document.getElementById('amountCurrency').textContent = code;
        document.getElementById('availBalance').textContent =
            symbol + ' ' + balance.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        run();
    }
    document.querySelectorAll('.wallet-card').forEach(c => c.addEventListener('click', () => selectWallet(c)));

    let precision = 2;
    var limitText = "{{ __('Limit') }}";
    var chargeText = "{{ __('Charge') }}";

    function run() {
        let cur = document.getElementById('amountCurrency').textContent;
        let enterAmount = $("input[name=amount]").val();
        (enterAmount == null || enterAmount == "") ? enterAmount = 0 : enterAmount = parseFloat(enterAmount);

        let minLimit = '{{ $fees_and_charge->min_limit }}';
        let maxLimit = '{{ $fees_and_charge->max_limit }}';
        var fixedCharge = "{{ $fees_and_charge->fixed_charge }}";
        var percentCharge = "{{ $fees_and_charge->percent_charge }}";

        $(".limit-show").text(`• ${limitText}  ${parseFloat(minLimit).toFixed(precision)} ${cur} - ${parseFloat(maxLimit).toFixed(precision)} ${cur}`);
        let percentChargeCalc = ((parseFloat(enterAmount) / 100) * parseFloat(percentCharge));
        $(".charge-show").text(`• ${chargeText} ${parseFloat(fixedCharge).toFixed(precision)} ${cur} + ${parseFloat(percentCharge).toFixed(precision)}% `);

        let totalCharges = parseFloat(fixedCharge) + parseFloat(percentChargeCalc);

        $(".enter-amount").text(`${parseFloat(enterAmount).toFixed(precision)} ${cur}`);
        $(".fees").text(`${parseFloat(totalCharges).toFixed(precision)} ${cur}`);

        let payable = (parseFloat(enterAmount)) + (parseFloat(totalCharges));
        $(".payable").text(`${removeTrailingZeros(parseFloat(payable).toFixed(precision))} ${cur}`);
        $(".will-get").text(`${parseFloat(enterAmount).toFixed(precision)} ${cur}`);

        return true;
    }

    $("input[name=amount]").keyup(function() { run(); });

    const def = document.querySelector('.wallet-card.active') || document.querySelector('.wallet-card');
    if (def) selectWallet(def);
    </script>
@endpush
