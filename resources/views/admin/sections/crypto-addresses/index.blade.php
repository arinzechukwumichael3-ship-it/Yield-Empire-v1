@extends('admin.layouts.master')

@push('css')
<style>
.ca-form-card { max-width: 800px; }
.ca-coin-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 100px;
    font-size: 11px; font-weight: 600; text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>
@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        ['name'  => __("Dashboard"), 'url'   => setRoute("admin.dashboard")],
    ], 'active' => __("Crypto Addresses")])
@endsection

@section('content')
<div class="custom-card ca-form-card">
    <div class="card-header">
        <h6 class="title">{{ __("Add / Edit Crypto Address") }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.crypto.addresses.store') }}">
            @csrf
            <div class="row mb-10-none">
                <div class="col-xl-6 col-lg-6 form-group">
                    <label>{{ __("Coin Name") }}<span>*</span></label>
                    <input type="text" class="form--control" name="coin_name" placeholder="e.g. Bitcoin" value="{{ old('coin_name') }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    <label>{{ __("Symbol") }}<span>*</span></label>
                    <input type="text" class="form--control" name="symbol" placeholder="e.g. BTC" value="{{ old('symbol') }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    <label>{{ __("Network") }}</label>
                    <input type="text" class="form--control" name="network" placeholder="e.g. Bitcoin Network, ERC20, TRC20" value="{{ old('network') }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    <label>{{ __("Wallet Address") }}<span>*</span></label>
                    <input type="text" class="form--control" name="wallet_address" placeholder="Enter wallet address" value="{{ old('wallet_address') }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    <label>{{ __("For User (optional)") }}</label>
                    <select name="user_id" class="form--control select2-auto-tokenize">
                        <option value="">{{ __("Global (all users)") }}</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>{{ $u->fullname }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                    <small class="text-muted">{{ __("Leave empty to use as default address for all users") }}</small>
                </div>
                <div class="col-xl-12 col-lg-12 form-group mt-4">
                    <button type="submit" class="btn--base w-100 btn-loading">{{ __("Save Address") }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="custom-card mt-15">
    <div class="card-header">
        <h6 class="title">{{ __("All Crypto Addresses") }}</h6>
    </div>
    <div class="card-body">
        @if ($wallets->count() > 0)
            <div class="row">
                @foreach ($wallets as $wallet)
                    <div class="col-md-6 col-xl-4 mb-15">
                        <div class="ca-card">
                            <div class="ca-card-header">
                                <div class="ca-coin-icon" style="background: {{ $wallet->color ?? '#1D4ED8' }}">
                                    {{ $wallet->symbol }}
                                </div>
                                <div class="ca-coin-info">
                                    <div class="ca-coin-name">{{ $wallet->coin_name }}
                                        @if ($wallet->user_id)
                                            <span class="ca-coin-badge" style="background:rgba(29,78,216,0.12);color:#1D4ED8">{{ __("User") }}</span>
                                        @else
                                            <span class="ca-coin-badge" style="background:rgba(5,150,105,0.12);color:#059669">{{ __("Global") }}</span>
                                        @endif
                                    </div>
                                    <div class="ca-coin-network">{{ $wallet->network ?? __('No network') }}</div>
                                </div>
                                <div>
                                    @include('admin.components.form.switcher',[
                                        'label' => '',
                                        'name'  => 'type',
                                        'value' => $wallet->is_active,
                                        'options' => ['Active' => 1,'Inactive' => 0],
                                        'onload' => true,
                                        'permission' => 'admin.crypto.addresses.status',
                                        'attribute' => "data-target-url=".route('admin.crypto.addresses.status', $wallet->id),
                                    ])
                                </div>
                            </div>
                            <div class="ca-card-body">
                                <div class="ca-address">{{ $wallet->wallet_address }}</div>
                                @if ($wallet->user)
                                    <div class="mb-2" style="font-size:12px;color:var(--admin-text-muted)">
                                        {{ __("Assigned to") }}: <strong>{{ $wallet->user->fullname }}</strong>
                                    </div>
                                @endif
                                <div class="ca-actions">
                                    <button type="button" class="enzo-admin-btn enzo-admin-btn-primary enzo-admin-btn-sm copy-addr-btn" data-address="{{ $wallet->wallet_address }}">
                                        <i class="las la-copy"></i> {{ __("Copy") }}
                                    </button>
                                    <a href="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data={{ urlencode($wallet->wallet_address) }}" target="_blank" class="enzo-admin-btn enzo-admin-btn-success enzo-admin-btn-sm">
                                        <i class="las la-qrcode"></i> {{ __("QR Code") }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.crypto.addresses.delete', $wallet->id) }}" class="d-inline" onsubmit="return confirm('{{ __("Delete this address?") }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="enzo-admin-btn enzo-admin-btn-danger enzo-admin-btn-sm">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="mt-2" style="display:none" class="ca-qr-preview">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode($wallet->wallet_address) }}" alt="QR" class="ca-qr-img">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="wl-empty" style="padding:44px 20px;text-align:center">
                <span class="wl-empty-title" style="font-size:16px;font-weight:700;color:var(--admin-text)">{{ __("No crypto addresses configured") }}</span>
                <span class="wl-empty-sub" style="font-size:13px;color:var(--admin-text-muted)">{{ __("Add your first crypto address above") }}</span>
            </div>
        @endif
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function(){
    $('.copy-addr-btn').on('click', function(){
        var addr = $(this).data('address');
        navigator.clipboard.writeText(addr).then(function(){
            throwMessage('success', ['{{ __("Address copied!") }}']);
        }).catch(function(){
            // Fallback
            var $input = $('<input>');
            $('body').append($input);
            $input.val(addr).select();
            document.execCommand('copy');
            $input.remove();
            throwMessage('success', ['{{ __("Address copied!") }}']);
        });
    });
});
</script>
@endpush
