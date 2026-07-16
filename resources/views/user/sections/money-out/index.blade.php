@extends('user.layouts.rise-master')

@push('css')
<style>
.mo-tabs { display: flex; background: #1E293B; border-radius: 12px; padding: 4px; margin-bottom: 20px; }
.mo-tab { flex: 1; padding: 12px 8px; border-radius: 10px; font-size: 13px; font-weight: 600; color: #94A3B8; text-align: center; cursor: pointer; transition: all 0.15s; border: none; background: none; -webkit-tap-highlight-color: transparent; }
.mo-tab.active { background: #3B82F6; color: #fff; }
.mo-tab-content { display: none; }
.mo-tab-content.active { display: block; }
.cw-coin-list { display: flex; flex-direction: column; gap: 8px; }
.cw-coin-card { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid #334155; border-radius: 12px; cursor: pointer; transition: all 0.15s; background: #1E293B; }
.cw-coin-card:hover { border-color: #3B82F6; }
.cw-coin-card.selected { border-color: #3B82F6; background: rgba(59,130,246,0.08); }
.cw-coin-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; color: #fff; flex-shrink: 0; }
.cw-coin-info { flex: 1; }
.cw-coin-name { font-size: 14px; font-weight: 600; color: #fff; display: block; }
.cw-coin-network { font-size: 12px; color: #94A3B8; }
.cw-coin-badge { display: inline-block; padding: 2px 8px; border-radius: 100px; font-size: 10px; font-weight: 700; background: rgba(59,130,246,0.15); color: #3B82F6; margin-left: 6px; vertical-align: middle; }
.cw-radio-dot { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #334155; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; }
.cw-radio-dot.filled { border-color: #3B82F6; }
.cw-radio-dot.filled::after { content: ""; width: 10px; height: 10px; border-radius: 50%; background: #3B82F6; }
.mo-warning { display:flex; align-items:flex-start; gap:8px; padding:12px 14px; background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.15); border-radius:10px; font-size:12px; color:#FCA5A5; line-height:1.5; margin-bottom:16px; }
.mo-warning svg { flex-shrink:0; margin-top:1px; }
[data-theme="light"] .mo-tabs { background: #E2E8F0; }
[data-theme="light"] .mo-tab { color: #64748B; }
[data-theme="light"] .mo-tab.active { background: #3B82F6; color: #fff; }
[data-theme="light"] .cw-coin-card { background: #fff; border-color: #E2E8F0; }
[data-theme="light"] .cw-coin-name { color: #1F2937; }
</style>
@endpush

@section('content')
@php
$coins = config("crypto_deposit.coins", []);
@endphp
<div class="am-header">
    <h1 class="am-header-title">{{ __('Withdraw') }}</h1>
</div>
<div class="am-body">
    {{-- Tab Toggle --}}
    <div class="mo-tabs" role="tablist">
        <button class="mo-tab active" data-tab="fiat" role="tab">🏦 Fiat</button>
        <button class="mo-tab" data-tab="crypto" role="tab">₿ Crypto</button>
        <button class="mo-tab" data-tab="international" role="tab">🌍 Intl. Bank</button>
    </div>

    {{-- ====== TAB 1: Fiat Withdrawal (existing) ====== --}}
    <div class="mo-tab-content active" id="tab-fiat">
        <div class="am-rate-banner">
            <div>
                <div class="am-rate-label">{{ __('Exchange Rate') }}</div>
                <div class="am-rate-value exchange-rate rate">--</div>
            </div>
        </div>
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
    </div>

    {{-- ====== TAB 2: Crypto Withdrawal ====== --}}
    <div class="mo-tab-content" id="tab-crypto">
        <form method="GET" action="{{ route('user.rise.withdraw.crypto') }}">
            <div class="am-card">
                <label class="am-label">{{ __('Destination Wallet Address') }}</label>
                <div class="am-input-wrap" style="margin-top:8px;">
                    <input type="text" class="send-input" placeholder="Enter the recipient's wallet address" readonly style="cursor:default">
                </div>
                <span class="am-hint">Paste the recipient's wallet address carefully. Addresses are case-sensitive.</span>
            </div>
            <div class="am-card">
                <label class="am-label">{{ __('Select Cryptocurrency') }}</label>
                <div class="cw-coin-list" style="margin-top:12px">
                    @foreach($coins as $key => $coin)
                    <div class="cw-coin-card" style="cursor:default;opacity:0.7;">
                        <div class="cw-coin-icon" style="background:{{ $coin["color"] }}">{{ $coin["symbol"] }}</div>
                        <div class="cw-coin-info">
                            <span class="cw-coin-name">{{ $coin["name"] }}@if($coin["badge"])<span class="cw-coin-badge">{{ $coin["badge"] }}</span>@endif</span>
                            <span class="cw-coin-network">{{ $coin["network"] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="mo-warning">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div><strong>⚠️ Wrong network = lost funds.</strong><br>Always confirm the destination wallet address matches the network you selected.</div>
            </div>
            <button type="submit" class="am-btn" style="text-align:center;">{{ __('Continue to Crypto Withdraw') }} →</button>
        </form>
    </div>

    {{-- ====== TAB 3: International Bank Withdrawal ====== --}}
    <div class="mo-tab-content" id="tab-international">
        <form method="POST" action="{{ route('user.fund-transfer.submit') }}">
            @csrf
            <input type="hidden" name="type" value="international_withdrawal">
            <div class="am-card">
                <div class="am-field-group">
                    <label class="am-label">{{ __('Recipient Full Name') }}<span>*</span></label>
                    <div class="am-input-wrap">
                        <input type="text" name="recipient_name" class="send-input" placeholder="e.g. Jane Smith">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Bank Name') }}<span>*</span></label>
                    <div class="am-input-wrap">
                        <input type="text" name="bank_name" class="send-input" placeholder="e.g. Barclays">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Account Number / IBAN') }}<span>*</span></label>
                    <div class="am-input-wrap">
                        <input type="text" name="account_number" class="send-input" placeholder="e.g. GB29NWBK60161331926819">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('SWIFT / BIC Code') }}<span>*</span></label>
                    <div class="am-input-wrap">
                        <input type="text" name="swift_code" class="send-input" placeholder="e.g. NWBKGB2L">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Amount (USD)') }}<span>*</span></label>
                    <div class="am-input-wrap">
                        <input type="number" step="0.01" min="0.01" name="amount" class="send-input" placeholder="0.00">
                        <span class="am-input-pill">USD</span>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Transfer Rail') }}</label>
                    <select class="send-input" name="rail" style="width:100%;padding:14px 16px;border:1.5px solid #334155;border-radius:12px;font-size:16px;background:#1E293B;outline:none;color:#fff;-webkit-appearance:none;appearance:none;background-image:url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2394A3B8%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22%3E%3Cpolyline points=%226 9 12 15 18 9%22%3E%3C/polyline%3E%3C/svg%3E');background-repeat:no-repeat;background-position:right 14px center;padding-right:40px;">
                        <option value="swift">SWIFT — Global (1-5 business days)</option>
                        <option value="sepa">SEPA — Europe (1-2 business days)</option>
                        <option value="ach">ACH — US (2-3 business days)</option>
                    </select>
                </div>
                <div class="mo-warning">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>International transfers use SWIFT / SEPA / ACH rails. Processing times and fees vary by destination.</span>
                </div>
                <button type="submit" class="am-btn" style="text-align:center;">{{ __('Submit Withdrawal') }} →</button>
            </div>
        </form>
    </div>

    <!-- Transaction Log -->
    <div class="am-card" style="margin-top:20px;">
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
    document.querySelectorAll('.mo-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.mo-tab').forEach(function(t) { t.classList.remove('active'); });
            document.querySelectorAll('.mo-tab-content').forEach(function(c) { c.classList.remove('active'); });
            this.classList.add('active');
            document.getElementById('tab-' + this.dataset.tab).classList.add('active');
        });
    });

    let default_currency_code = "{{ get_default_currency_code() }}";

    $("select[name=payment_gateway]").change(function() { run(); });
    $("input[name=amount]").keyup(function() { run(); });

    function run() {
        let paymentGatewaySelect = $("select[name=payment_gateway]");
        let gatewaySelectedValue = paymentGatewaySelect.val();
        if(gatewaySelectedValue == null || gatewaySelectedValue == "") return false;
        let amount = $("input[name=amount]").val();
        let gatewayCurrency = JSON.parse(paymentGatewaySelect.find(":selected").attr("data-item"));
        (amount == null || amount == "" || !$.isNumeric(amount)) ? amount = 0 : amount = amount;
        amount = parseFloat(amount).toFixed(2);
        $(".withdraw-amount").text(`${amount} ${default_currency_code}`);
        let fixedCharge = gatewayCurrency.fixed_charge ?? 0;
        let percentCharge = gatewayCurrency.percent_charge ?? 0;
        let minLimit = gatewayCurrency.min_limit ?? 0;
        let maxLimit = gatewayCurrency.max_limit ?? 0;
        let rate = gatewayCurrency.rate ?? 1;
        let gatewayCurrencyCode = gatewayCurrency.currency_code ?? "-";
        var min_limit_calc = parseFloat(minLimit/rate).toFixed(2);
        var max_limit_clac = parseFloat(maxLimit/rate).toFixed(2);
        $('.limit-show').html("Limit " + min_limit_calc + " " + default_currency_code + " - " + max_limit_clac + " " + default_currency_code);
        $(".exchange-rate").text(`1 ${default_currency_code} = ${parseFloat(rate).toFixed(2)} ${gatewayCurrencyCode}`);
        let fixedChargeCalc = (parseFloat(fixedCharge) / parseFloat(rate));
        let percentChargeCalc = ((((parseFloat(amount) * parseFloat(rate)) / 100) * parseFloat(percentCharge)) / parseFloat(rate));
        let totalCharge = parseFloat(fixedChargeCalc) + parseFloat(percentChargeCalc);
        $(".total-charges").text(`${parseFloat(totalCharge).toFixed(2)} ${default_currency_code}`);
        $(".fees-show").html("Charge: " + parseFloat(fixedChargeCalc).toFixed(2) + " " + default_currency_code + " + " + parseFloat(percentCharge).toFixed(2) + "%");
        let willGet = parseFloat(amount) * parseFloat(rate);
        willGet = willGet.toFixed(2);
        $('.will-get').text(`${willGet} ${gatewayCurrencyCode}`);
        let totalPayable = parseFloat(amount) + parseFloat(totalCharge);
        totalPayable = totalPayable.toFixed(2);
        $(".payable").text(`${totalPayable} ${default_currency_code}`);
    }
    </script>
@endpush
