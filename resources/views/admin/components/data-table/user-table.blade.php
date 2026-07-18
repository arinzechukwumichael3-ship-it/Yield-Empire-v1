<table class="custom-table user-search-table d-none d-lg-table">
    <thead>
        <tr>
            <th></th>
            <th>{{ __("Username") }}</th>
            <th>{{ __("Email") }}</th>
            <th>{{ __("Phone") }}</th>
            <th>{{ __("Status") }}</th>
            <th>{{ __("Action") }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users ?? [] as $key => $item)
            <tr>
                <td>
                    <ul class="user-list">
                        <li><img src="{{ $item->userImage }}" alt="user"></li>
                    </ul>
                </td>
                <td><span>{{ $item->username }}</span></td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->full_mobile ?? "N/A" }}</td>
                <td>
                    @if (Route::currentRouteName() == "admin.users.kyc.unverified")
                        <span class="{{ $item->kycStringStatus->class }}">{{ $item->kycStringStatus->value }}</span>
                    @else
                        <span class="{{ $item->stringStatus->class }}">{{ $item->stringStatus->value }}</span>
                    @endif
                </td>
                <td>
                    @php
                        $ud = [
                            "id" => $item->id,
                            "username" => $item->username,
                            "email" => $item->email,
                            "full_mobile" => $item->full_mobile ?? "N/A",
                            "firstname" => $item->firstname ?? "",
                            "lastname" => $item->lastname ?? "",
                            "avatar" => $item->userImage,
                            "status" => $item->status,
                            "status_text" => $item->stringStatus->value,
                            "status_class" => $item->stringStatus->class,
                            "account_no" => $item->account_no ?? "N/A",
                            "created_at" => $item->created_at->format("d M Y"),
                            "has_qualifying_deposit" => $item->hasQualifyingDeposit(),
                            "card_unlocked" => $item->card_unlocked,
                            "withdrawal_unlocked" => $item->withdrawal_unlocked,
                        ];
                        $wls = $item->wallet ? [$item->wallet->currency_id => ["id" => $item->wallet->id, "balance" => $item->wallet->balance, "currency_code" => $item->wallet->currency->code ?? "USD"]] : [];
                    @endphp
                    <div class="d-flex flex-wrap justify-content-center gap-1">
                        <a href="{{ route('admin.users.details', $item->username) }}" class="btn btn--base" title="{{ __('View Details') }}"><i class="las la-eye"></i></a>
                        <form method="POST" action="{{ route('admin.users.login.as.member', $item->username) }}" style="display:inline-block;margin:0;">
                            @csrf
                            <input type="hidden" name="target" value="{{ $item->username }}">
                            <button type="submit" class="btn btn--base" title="{{ __('Login as User') }}"><i class="las la-sign-in-alt"></i></button>
                        </form>
                        @if($item->status == 1)
                            <form method="POST" action="{{ route('admin.users.status.update', $item->username) }}" style="display:inline-block;margin:0;">
                                @csrf
                                <button type="submit" class="btn btn--base" title="{{ __('Suspend') }}"><i class="las la-ban"></i></button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.status.update', $item->username) }}" style="display:inline-block;margin:0;">
                                @csrf
                                <button type="submit" class="btn btn--base" title="{{ __('Activate') }}"><i class="las la-check-circle"></i></button>
                            </form>
                        @endif
                        <button type="button" class="btn btn--base user-detail-btn" 
                            data-user='@json($ud)'
                            data-wallets='@json($wls)' title="{{ __('More') }}">
                            <i class="las la-info-circle"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            @include("admin.components.alerts.empty",["colspan" => 7])
        @endforelse
    </tbody>
</table>

<!-- Mobile User Cards -->
<div class="user-cards-container d-block d-lg-none">
    @forelse ($users ?? [] as $key => $item)
        <div class="user-card">
            <div class="user-card-left">
                <img src="{{ $item->userImage }}" alt="user" class="user-card-avatar">
                <div class="user-card-info">
                    <span class="user-card-name">{{ $item->username }}</span>
                    <span class="user-card-email">{{ $item->email }}</span>
                </div>
            </div>
            <div class="user-card-right">
                @if (Route::currentRouteName() == "admin.users.kyc.unverified")
                    <span class="{{ $item->kycStringStatus->class }}">{{ $item->kycStringStatus->value }}</span>
                @else
                    <span class="{{ $item->stringStatus->class }}">{{ $item->stringStatus->value }}</span>
                @endif
                @php
                    $ud = [
                        "id" => $item->id,
                        "username" => $item->username,
                        "email" => $item->email,
                        "full_mobile" => $item->full_mobile ?? "N/A",
                        "firstname" => $item->firstname ?? "",
                        "lastname" => $item->lastname ?? "",
                        "avatar" => $item->userImage,
                        "status" => $item->status,
                        "status_text" => $item->stringStatus->value,
                        "status_class" => $item->stringStatus->class,
                        "account_no" => $item->account_no ?? "N/A",
                        "created_at" => $item->created_at->format("d M Y"),
                        "has_qualifying_deposit" => $item->hasQualifyingDeposit(),
                        "card_unlocked" => $item->card_unlocked,
                        "withdrawal_unlocked" => $item->withdrawal_unlocked,
                    ];
                    $wls = $item->wallet ? [$item->wallet->currency_id => ["id" => $item->wallet->id, "balance" => $item->wallet->balance, "currency_code" => $item->wallet->currency->code ?? "USD"]] : [];
                @endphp
                    <div class="d-flex flex-wrap justify-content-center gap-1">
                        <a href="{{ route('admin.users.details', $item->username) }}" class="btn btn--base" title="{{ __('View Details') }}"><i class="las la-eye"></i></a>
                        <form method="POST" action="{{ route('admin.users.login.as.member', $item->username) }}" style="display:inline-block;margin:0;">
                            @csrf
                            <input type="hidden" name="target" value="{{ $item->username }}">
                            <button type="submit" class="btn btn--base" title="{{ __('Login as User') }}"><i class="las la-sign-in-alt"></i></button>
                        </form>
                        @if($item->status == 1)
                            <form method="POST" action="{{ route('admin.users.status.update', $item->username) }}" style="display:inline-block;margin:0;">
                                @csrf
                                <button type="submit" class="btn btn--base" title="{{ __('Suspend') }}"><i class="las la-ban"></i></button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.status.update', $item->username) }}" style="display:inline-block;margin:0;">
                                @csrf
                                <button type="submit" class="btn btn--base" title="{{ __('Activate') }}"><i class="las la-check-circle"></i></button>
                            </form>
                        @endif
                        <button type="button" class="btn btn--base user-detail-btn" 
                            data-user='@json($ud)'
                            data-wallets='@json($wls)' title="{{ __('More') }}">
                            <i class="las la-info-circle"></i>
                        </button>
                    </div>
            </div>
        </div>
    @empty
        @include("admin.components.alerts.empty",["colspan" => 7])
    @endforelse
</div>
