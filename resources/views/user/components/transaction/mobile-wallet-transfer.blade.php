@isset($transaction)
@php
    $precesion = 2;
    $details = $transaction->details;
    $senderName = $details->sender_name ?? ($transaction->user->fullname ?? "N/A");
    $senderBank = $details->sender_bank ?? "EnzoBank";
    $receiverName = $details->receiver_name ?? "N/A";
@endphp
<div class="dashboard-list-wrapper">
    <div class="dashboard-list-item-wrapper">
        <div class="dashboard-list-item sent">
            <div class="dashboard-list-left">
                <div class="dashboard-list-user-wrapper">
                    <div class="dashboard-list-user-icon">
                        @if ($transaction->userTrxType == global_const()::SEND)
                            <i class="las la-arrow-up"></i>
                        @else
                            <i class="las la-arrow-down"></i>
                        @endif
                    </div>
                    <div class="dashboard-list-user-content">
                        @if ($transaction->userTrxType == global_const()::SEND)
                            <h4 class="title">{{ __("Transfer money to") }} {{ $receiverName }}</h4>
                        @else
                            <h4 class="title">{{ __("Received Money from") }} {{ $senderName }} ({{ $senderBank }})</h4>
                        @endif
                        <span class="{{ $transaction->stringStatus->class }}">{{ __($transaction->stringStatus->value) }} &nbsp; <span class="text-secondary">#{{ $transaction->trx_id }}</span></span>
                    </div>
                </div>
            </div>
            <div class="dashboard-list-right">
                @if ($transaction->userTrxType == global_const()::SEND)
                    <h4 class="main-money text--base">{{ get_amount(@$transaction->request_amount, @$transaction->request_currency, $precesion) }}</h4>
                @else
                    <h4 class="main-money text--base">{{ get_amount(@$transaction->request_amount, @$transaction->payment_currency, $precesion) }}</h4>
                @endif
            </div>
        </div>
        <div class="preview-list-wrapper">
            <div class="preview-list-item">
                <div class="preview-list-left">
                    <div class="preview-list-user-wrapper">
                        <div class="preview-list-user-icon">
                            <i class="lab la-tumblr"></i>
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

            @if ($transaction->userTrxType == global_const()::SEND)
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-exchange-alt"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Transfer Type") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ __("EnzoBank Internal") }}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-wallet"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Amount Sent") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span class="text--base">{{ get_amount(@$transaction->request_amount,@$transaction->request_currency,$precesion) }}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-user"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Recipient") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ $receiverName }}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-university"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Receiving Bank") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ $senderBank }}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-balance-scale"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Available Balance") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span class="text--info">{{ get_amount($transaction->available_balance, @$transaction->request_currency,$precesion) }}</span>
                    </div>
                </div>
            @else
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-wallet"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Amount Received") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span class="text--base">{{ get_amount(@$transaction->request_amount,@$transaction->payment_currency,$precesion) }}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-user"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Sent by") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ $senderName }}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-university"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("From Bank") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ $senderBank }}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-balance-scale"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Available Balance") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span class="text--info">{{ get_amount($transaction->available_balance, @$transaction->request_currency,$precesion) }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endisset
