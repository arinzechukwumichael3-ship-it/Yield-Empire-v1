@extends('user.layouts.rise-master')

@section('content')
@php
$earnings = $earnings ?? collect([]);
$totalEarned = $totalEarned ?? 0;
@endphp

<div class="am-header">
    <h1 class="am-header-title">Earnings</h1>
</div>

<div class="am-body">
    <!-- Total Earned -->
    <div class="am-card" style="text-align:center;">
        <div style="font-size:13px;color:#6B7280;">Total Earned</div>
        <div style="font-size:32px;font-weight:800;color:#3B82F6;">${{ number_format($totalEarned, 2) }}</div>
    </div>

    <!-- Filter Tabs -->
    <div class="tl-filter-scroll">
        <button class="tl-filter active" data-filter="all">All</button>
        <button class="tl-filter" data-filter="credited">Credited</button>
        <button class="tl-filter" data-filter="pending">Pending</button>
    </div>

    <!-- Earnings List -->
    <div style="display:flex;flex-direction:column;gap:8px;">
        @forelse($earnings as $earning)
        <div class="tl-item" data-type="{{ $earning->type }}">
            <div class="tl-item-icon {{ $earning->type === 'credited' ? 'credit' : 'pending' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                </svg>
            </div>
            <div class="tl-item-info">
                <span class="tl-item-name">{{ $earning->investment->plan->name ?? 'Investment' }}</span>
                <span class="tl-item-date">{{ $earning->credited_at ? $earning->credited_at->format('M d, Y') : ($earning->created_at ? $earning->created_at->format('M d, Y') : '') }}</span>
            </div>
            <div style="text-align:right;">
                <span class="tl-item-amount credit">+${{ number_format($earning->amount ?? 0, 2) }}</span>
                <div><span style="padding:2px 8px;border-radius:10px;font-size:10px;font-weight:600;{{ $earning->type === 'credited' ? 'background:rgba(16,185,129,0.1);color:#059669;' : 'background:rgba(245,158,11,0.1);color:#D97706;' }}">{{ ucfirst($earning->type) }}</span></div>
            </div>
        </div>
        @empty
        <div style="display:flex;flex-direction:column;align-items:center;padding:60px 20px;gap:8px;text-align:center;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            <span style="font-size:16px;font-weight:700;">No earnings yet</span>
            <span style="font-size:13px;color:#9CA3AF;">Earnings will appear here once your investments mature</span>
        </div>
        @endforelse
    </div>

    @if($earnings->hasPages())
    <div style="margin-top:16px;">{{ $earnings->links() }}</div>
    @endif
</div>

@push('script')
<script>
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
</script>
@endpush
@endsection
