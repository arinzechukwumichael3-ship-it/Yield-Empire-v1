@extends('user.layouts.rise-master')

@section('content')
@php
$investments = $investments ?? collect([]);
$totalInvested = $totalInvested ?? 0;
$activeCount = $activeCount ?? 0;
$totalEarnings = $totalEarnings ?? 0;
@endphp

<div class="am-header">
    <h1 class="am-header-title">My Investments</h1>
</div>

<div class="am-body">
    <!-- Stats Row -->
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
        <div class="am-card" style="text-align:center;padding:16px 10px;">
            <div style="font-size:12px;color:#6B7280;">Total Invested</div>
            <div style="font-size:18px;font-weight:700;color:#111827;">${{ number_format($totalInvested, 2) }}</div>
        </div>
        <div class="am-card" style="text-align:center;padding:16px 10px;">
            <div style="font-size:12px;color:#6B7280;">Active Plans</div>
            <div style="font-size:18px;font-weight:700;color:#3B82F6;">{{ $activeCount }}</div>
        </div>
        <div class="am-card" style="text-align:center;padding:16px 10px;">
            <div style="font-size:12px;color:#6B7280;">Total Earnings</div>
            <div style="font-size:18px;font-weight:700;color:#059669;">${{ number_format($totalEarnings, 2) }}</div>
        </div>
    </div>

    <!-- Investment List -->
    <div style="display:flex;flex-direction:column;gap:12px;">
        @forelse($investments as $inv)
        <div class="am-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                <span style="font-weight:700;font-size:16px;">{{ $inv->plan->name ?? 'Unknown Plan' }}</span>
                @php
                    $statusClass = match($inv->status) {
                        'active' => 'background:rgba(16,185,129,0.1);color:#059669;',
                        'pending' => 'background:rgba(245,158,11,0.1);color:#D97706;',
                        'completed' => 'background:rgba(59,130,246,0.1);color:#2563EB;',
                        'cancelled' => 'background:rgba(239,68,68,0.1);color:#DC2626;',
                        default => 'background:#F3F4F6;color:#6B7280;'
                    };
                @endphp
                <span style="padding:3px 12px;border-radius:20px;font-size:11px;font-weight:600;{{ $statusClass }}">{{ ucfirst($inv->status) }}</span>
            </div>

            @if($inv->status === 'active' || $inv->status === 'completed')
            @php
                $start = $inv->created_at;
                $end = $inv->maturity_date;
                $total = $start && $end ? $start->diffInDays($end) : 1;
                $elapsed = $start ? $start->diffInDays(now()) : 0;
                $pct = min(100, ($elapsed / max($total, 1)) * 100);
            @endphp
            <div style="height:6px;background:#E5E7EB;border-radius:3px;margin-bottom:10px;overflow:hidden;">
                <div style="height:100%;width:{{ $pct }}%;background:#3B82F6;border-radius:3px;transition:width 0.5s;"></div>
            </div>
            @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:13px;">
                <div><span style="color:#6B7280;">Invested:</span> <strong>${{ number_format($inv->amount, 2) }}</strong></div>
                <div><span style="color:#6B7280;">Return:</span> <strong style="color:#059669;">${{ number_format($inv->expected_return ?? 0, 2) }}</strong></div>
                @if($inv->maturity_date)
                <div style="grid-column:1/-1;"><span style="color:#6B7280;">Days left:</span> <strong>{{ max(0, now()->diffInDays($inv->maturity_date, false)) }}</strong></div>
                @endif
                <div style="grid-column:1/-1;font-size:11px;color:#9CA3AF;">{{ $inv->payment_method ?? '' }}</div>
            </div>
        </div>
        @empty
        <div style="display:flex;flex-direction:column;align-items:center;padding:60px 20px;text-align:center;gap:16px;">
            <div style="width:64px;height:64px;border-radius:50%;background:rgba(59,130,246,0.1);display:flex;align-items:center;justify-content:center;font-size:32px;color:#3B82F6;">+</div>
            <div style="font-size:16px;font-weight:700;">No active investments</div>
            <div style="font-size:13px;color:#9CA3AF;">Start your investment journey today</div>
            <a href="{{ route('user.invest.new') }}" class="am-btn" style="border-radius:100px;max-width:200px;">Start Investing</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
