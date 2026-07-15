@extends('user.layouts.rise-master')

@push('css')
<style>
.vc-card-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 1.586/1;
    cursor: pointer;
    transform-style: preserve-3d;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}
.vc-card-wrapper.flipped { transform: rotateY(180deg); }
.vc-card-front, .vc-card-back {
    position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    border-radius: 16px; backface-visibility: hidden; -webkit-backface-visibility: hidden;
    padding: 24px; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden;
}
.vc-card-front {
    background: linear-gradient(135deg, #1E1B4B, #312E81, #4F46E5);
    box-shadow: 0 25px 50px rgba(79,70,229,0.4);
}
.vc-card-back {
    background: linear-gradient(135deg, #1E1B4B, #312E81);
    transform: rotateY(180deg); padding: 0;
}
.vc-card-back-inner { padding: 24px; display: flex; flex-direction: column; height: 100%; justify-content: space-between; }
</style>
@endpush

@section('content')
@php
$cardCharge = $cardCharge ?? null;
$cardReloadCharge = $cardReloadCharge ?? null;
$transactions = $transactions ?? collect([]);
$myCards = $myCards ?? collect([]);
$firstCard = $myCards->first();
$cardNumber = $firstCard->card_number ?? '4242424242424242';
$cardPan = str_split($cardNumber, 4);
$cardName = strtoupper(auth()->user()->fullname ?? auth()->user()->username ?? 'CARD HOLDER');
$expMonth = $firstCard->expiry_month ?? '12';
$expYear = $firstCard->expiry_year ?? '28';
$cvv = $firstCard->cvv ?? '***';
$cardStatus = $firstCard->is_active ?? true;
@endphp

<div class="vc-header">
    <h1 class="vc-header-title">My Card</h1>
    @if(($customer_card ?? 0) < $card_limit)
    <a href="{{ setRoute('user.strowallet.virtual.card.create') }}" class="ps-btn-blue" style="width:auto;padding:10px 18px;font-size:13px;">+ New Card</a>
    @endif
</div>

<div class="vc-body">
    <!-- Card Flip -->
    <div class="vc-card-scene">
        <div class="vc-card-wrapper" id="cardWrapper" onclick="this.classList.toggle('flipped')">
            <!-- FRONT -->
            <div class="vc-card-front">
                <div class="vc-card-shimmer"></div>
                <div class="vc-card-top">
                    <span class="vc-card-logo">EnzoBank</span>
                    <span class="vc-card-visa">VISA</span>
                </div>
                <div>
                    <div class="vc-card-chip">
                        <div class="vc-card-chip-line"></div>
                        <div class="vc-card-chip-line"></div>
                        <div class="vc-card-chip-line"></div>
                    </div>
                    <div class="vc-card-number">•••• •••• •••• {{ substr($cardNumber, -4) }}</div>
                </div>
                <div class="vc-card-bottom">
                    <div class="vc-card-holder">
                        <span class="vc-card-holder-label">Card Holder</span>
                        <span class="vc-card-holder-name">{{ $cardName }}</span>
                    </div>
                    <div class="vc-card-expiry">
                        <span class="vc-card-expiry-label">Valid Thru</span>
                        <span class="vc-card-expiry-date">{{ $expMonth }}/{{ $expYear }}</span>
                    </div>
                </div>
            </div>
            <!-- BACK -->
            <div class="vc-card-back">
                <div class="vc-card-shimmer"></div>
                <div class="vc-card-magstripe"></div>
                <div class="vc-card-signature">
                    <div class="vc-card-signature-line"></div>
                    <span class="vc-card-cvv">{{ $cvv }}</span>
                </div>
                <div class="vc-card-back-inner" style="padding-top:0;">
                    <div></div>
                    <div class="vc-card-footer-text">This card is issued by EnzoBank pursuant to a license from VISA. For customer service contact support@enzobank.org</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="vc-status-row">
        <span class="vc-status-badge {{ $cardStatus ? 'active' : 'inactive' }}">
            <span class="vc-status-dot {{ $cardStatus ? 'active' : 'inactive' }}"></span>
            {{ $cardStatus ? 'Active' : 'Inactive' }}
        </span>
    </div>

    <!-- Actions -->
    <div class="vc-actions">
        <button class="vc-action-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Freeze Card
        </button>
        <button class="vc-action-btn" onclick="document.getElementById('cardWrapper').classList.toggle('flipped')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            View CVV
        </button>
        <button class="vc-action-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            Cancel Card
        </button>
    </div>

    <!-- Card Details -->
    <div class="vc-details-card">
        <div class="vc-details-title">Card Details</div>
        <div class="vc-detail-row">
            <span class="vc-detail-label">Card Number</span>
            <span class="vc-detail-value">•••• •••• •••• {{ substr($cardNumber, -4) }} <span class="vc-copy-btn" onclick="navigator.clipboard.writeText('{{ $cardNumber }}')">📋</span></span>
        </div>
        <div class="vc-detail-row">
            <span class="vc-detail-label">Expiry Date</span>
            <span class="vc-detail-value">{{ $expMonth }}/{{ $expYear }}</span>
        </div>
        <div class="vc-detail-row">
            <span class="vc-detail-label">Card Type</span>
            <span class="vc-detail-value">Virtual Debit</span>
        </div>
        <div class="vc-detail-row">
            <span class="vc-detail-label">Spending Limit</span>
            <span class="vc-detail-value">$5,000.00</span>
        </div>
    </div>

    <!-- Card Transactions -->
    <div>
        <div class="vc-tx-section-title">Card Transactions</div>
        <div class="rw-tx-list">
            @forelse($transactions as $tx)
            <div class="rw-tx-item">
                <div class="rw-tx-icon {{ $tx->type === 'ADD-MONEY' ? 'green' : 'red' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="{{ $tx->type === 'ADD-MONEY' ? '23 6 13.5 15.5 8.5 10.5 1 18' : '23 18 13.5 8.5 8.5 13.5 1 6' }}"/>
                    </svg>
                </div>
                <div class="rw-tx-info">
                    <span class="rw-tx-name">{{ $tx->type }}</span>
                    <span class="rw-tx-date">{{ $tx->created_at ? $tx->created_at->diffForHumans() : '' }}</span>
                </div>
                <span class="rw-tx-amount {{ $tx->type === 'ADD-MONEY' ? 'positive' : 'negative' }}">
                    {{ $tx->type === 'ADD-MONEY' ? '+' : '-' }}${{ number_format($tx->request_amount ?? 0, 2) }}
                </span>
            </div>
            @empty
            <div class="rw-empty" style="padding:30px 20px;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5"><rect x="3" y="6" width="18" height="15" rx="2"/><path d="M3 10h18"/><path d="M7 15h2"/><path d="M11 15h6"/></svg>
                <span class="rw-empty-title">No transactions</span>
                <span class="rw-empty-sub">Card transactions will appear here</span>
            </div>
            @endforelse
        </div>
    </div>
</div>

@if($myCards->count() > 1)
<div style="padding:0 16px 20px;">
    <div class="vc-details-title">Your Cards</div>
    <div style="display:flex;gap:10px;overflow-x:auto;">
        @foreach($myCards as $card)
        <div style="min-width:120px;padding:12px;background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.06);text-align:center;">
            <span style="font-size:12px;color:#6B7280;">•••• {{ substr($card->card_number ?? '', -4) }}</span>
            <span style="display:block;font-size:11px;color:#9CA3AF;margin-top:4px;">{{ $card->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection