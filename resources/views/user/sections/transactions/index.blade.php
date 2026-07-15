@extends('user.layouts.rise-master')

@section('content')
@php
$transactions = $transactions ?? collect([]);
@endphp

<div class="tl-header">
    <h1 class="tl-header-title">Transaction Log</h1>
    <div class="tl-search-wrap">
        <span class="tl-search-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </span>
        <input type="text" placeholder="Search transactions..." id="txSearch">
    </div>
</div>

<div class="tl-body">
    <!-- Filter Pills -->
    <div class="tl-filter-scroll">
        <button class="tl-filter active" data-filter="all">All</button>
        <button class="tl-filter" data-filter="credit">Credit</button>
        <button class="tl-filter" data-filter="debit">Debit</button>
        <button class="tl-filter" data-filter="pending">Pending</button>
    </div>

    <!-- Transactions List -->
    <div class="tl-list" id="txList">
        @forelse($transactions as $tx)
        @php
            $isCredit = in_array($tx->type ?? '', ['ADD-MONEY', 'TRANSFER-MONEY']) && ($tx->receiver_id ?? null) == auth()->id();
            $txType = $isCredit ? 'credit' : 'debit';
            $txAmount = $tx->request_amount ?? 0;
        @endphp
        <div class="tl-item" data-type="{{ $txType }}">
            <div class="tl-item-icon {{ $txType }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    @if($isCredit)
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                    @else
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/>
                    @endif
                </svg>
            </div>
            <div class="tl-item-info">
                <span class="tl-item-name">{{ $tx->type ?? 'Transaction' }}</span>
                <span class="tl-item-date">{{ $tx->created_at ? $tx->created_at->format('M d, Y · h:i A') : '' }}</span>
            </div>
            <span class="tl-item-amount {{ $txType }}">{{ $isCredit ? '+' : '-' }}${{ number_format($txAmount, 2) }}</span>
        </div>
        @empty
        <div class="tl-empty">
            <div class="tl-empty-icon">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="6" width="18" height="15" rx="2"/><path d="M3 10h18"/><path d="M7 15h2"/><path d="M11 15h6"/></svg>
            </div>
            <span class="tl-empty-title">No transactions yet</span>
            <span class="tl-empty-sub">Your transactions will appear here</span>
            <a href="{{ setRoute('user.add.money.index') }}" class="tl-empty-btn">Fund Account</a>
        </div>
        @endforelse
    </div>
</div>

@push('script')
<script>
// Filter functionality
document.querySelectorAll('.tl-filter').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tl-filter').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.dataset.filter;
        document.querySelectorAll('.tl-item').forEach(item => {
            if (filter === 'all' || item.dataset.type === filter) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Search
document.getElementById('txSearch')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.tl-item').forEach(item => {
        const name = item.querySelector('.tl-item-name')?.textContent?.toLowerCase() || '';
        item.style.display = name.includes(q) ? 'flex' : 'none';
    });
});
</script>
@endpush
@endsection