@isset($transaction)
@php
    $precision = 2;
    $currency = $transaction->request_currency ?? 'USD';
@endphp
<div class="dashboard-list-wrapper">
    <div class="dashboard-list-item-wrapper">
        <div class="dashboard-list-item">
            <div class="dashboard-list-left">
                <div class="dashboard-list-user-wrapper">
                    <div class="dashboard-list-user-icon">
                        <i class="las la-chart-pie"></i>
                    </div>
                    <div class="dashboard-list-user-content">
                        <h4 class="title">
                            {{ __("Investment") }} @if(isset($transaction->details->plan_name)) &middot; {{ $transaction->details->plan_name }} @endif
                        </h4>
                        <span class="{{ $transaction->stringStatus->class }}">{{ __($transaction->stringStatus->value) }} &nbsp; <span class="text-secondary">#{{ $transaction->trx_id }}</span></span>
                    </div>
                </div>
            </div>
            <div class="dashboard-list-right">
                <h4 class="main-money text--base">{{ get_amount($transaction->request_amount, $currency, $precision) }}</h4>
            </div>
        </div>
        <div class="preview-list-wrapper">
            <div class="preview-list-item">
                <div class="preview-list-left">
                    <div class="preview-list-user-wrapper">
                        <div class="preview-list-user-icon">
                            <i class="las la-receipt"></i>
                        </div>
                        <div class="preview-list-user-content">
                            <span>{{ __("Transaction ID") }}</span>
                        </div>
                    </div>
                </div>
                <div class="preview-list-right">
                    <span>{{ $transaction->trx_id }}</span>
                </div>
            </div>
            <div class="preview-list-item">
                <div class="preview-list-left">
                    <div class="preview-list-user-wrapper">
                        <div class="preview-list-user-icon">
                            <i class="las la-coins"></i>
                        </div>
                        <div class="preview-list-user-content">
                            <span>{{ __("Investment Amount") }}</span>
                        </div>
                    </div>
                </div>
                <div class="preview-list-right">
                    <span class="text--base">{{ get_amount($transaction->request_amount, $currency, $precision) }}</span>
                </div>
            </div>
            @if(isset($transaction->details->expected_return))
            <div class="preview-list-item">
                <div class="preview-list-left">
                    <div class="preview-list-user-wrapper">
                        <div class="preview-list-user-icon">
                            <i class="las la-percentage"></i>
                        </div>
                        <div class="preview-list-user-content">
                            <span>{{ __("Expected Return") }}</span>
                        </div>
                    </div>
                </div>
                <div class="preview-list-right">
                    <span class="text--success">{{ get_amount($transaction->details->expected_return, $currency, $precision) }}</span>
                </div>
            </div>
            @endif
            @if(isset($transaction->details->method))
            <div class="preview-list-item">
                <div class="preview-list-left">
                    <div class="preview-list-user-wrapper">
                        <div class="preview-list-user-icon">
                            <i class="las la-wallet"></i>
                        </div>
                        <div class="preview-list-user-content">
                            <span>{{ __("Payment Method") }}</span>
                        </div>
                    </div>
                </div>
                <div class="preview-list-right">
                    <span>{{ $transaction->details->method }}@if(isset($transaction->details->network)) ({{ $transaction->details->network }})@endif</span>
                </div>
            </div>
            @endif
            @if(isset($transaction->details->tx_hash))
            <div class="preview-list-item">
                <div class="preview-list-left">
                    <div class="preview-list-user-wrapper">
                        <div class="preview-list-user-icon">
                            <i class="las la-link"></i>
                        </div>
                        <div class="preview-list-user-content">
                            <span>{{ __("Tx Hash") }}</span>
                        </div>
                    </div>
                </div>
                <div class="preview-list-right">
                    <span class="text-break">{{ $transaction->details->tx_hash }}</span>
                </div>
            </div>
            @endif
            <div class="preview-list-item">
                <div class="preview-list-left">
                    <div class="preview-list-user-wrapper">
                        <div class="preview-list-user-icon">
                            <i class="las la-info-circle"></i>
                        </div>
                        <div class="preview-list-user-content">
                            <span>{{ __("Status") }}</span>
                        </div>
                    </div>
                </div>
                <div class="preview-list-right">
                    <span class="{{ $transaction->stringStatus->class }}">{{ $transaction->stringStatus->value }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endisset
