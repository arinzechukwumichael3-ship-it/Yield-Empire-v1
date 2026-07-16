@extends('user.layouts.rise-master')

@push('css')
<style>
/* ── Analytics Dashboard ── */
.la-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 16px; }
.la-stat-card {
    background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; padding: 16px;
    display: flex; flex-direction: column; gap: 6px;
}
.la-stat-icon {
    width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center;
    justify-content: center; font-size: 16px;
}
.la-stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; }
.la-stat-value { font-size: 20px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.3px; }
.la-stat-sub { font-size: 11px; color: var(--text-muted); }

/* Health Score Card */
.la-health-card {
    margin: 0 16px 16px; background: var(--gradient);
    border-radius: 18px; padding: 24px; position: relative; overflow: hidden;
}
.la-health-card::before {
    content: ''; position: absolute; top: -50%; right: -30%; width: 200px; height: 200px;
    background: rgba(255,255,255,0.04); border-radius: 50%;
}
.la-health-rank {
    position: absolute; top: 14px; right: 14px;
    background: rgba(255,255,255,0.15); backdrop-filter: blur(4px);
    border-radius: 100px; padding: 4px 12px; font-size: 11px; font-weight: 600;
    color: #fff; letter-spacing: 0.3px;
}
.la-health-title { font-size: 14px; font-weight: 600; color: rgba(255,255,255,0.8); margin-bottom: 12px; }
.la-health-score { font-size: 42px; font-weight: 800; color: #fff; line-height: 1; }
.la-health-label { font-size: 13px; color: rgba(255,255,255,0.7); margin-top: 4px; }
.la-health-chart { display: flex; justify-content: center; margin: 10px 0; }

/* Repayment Metrics */
.la-metrics { margin: 0 16px 16px; }
.la-metric-card {
    background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; padding: 18px; margin-bottom: 10px;
}
.la-metric-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
.la-metric-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); }
.la-metric-pct { font-size: 15px; font-weight: 700; color: var(--accent); }
.la-bar { height: 6px; background: var(--border-color); border-radius: 100px; overflow: hidden; }
.la-bar-fill {
    height: 100%; border-radius: 100px; background: linear-gradient(90deg, var(--accent), #60A5FA);
    width: 0; transition: width 1.4s ease-out;
}
.la-bar-fill.green { background: linear-gradient(90deg, var(--success), #4ADE80); }
.la-bar-fill.yellow { background: linear-gradient(90deg, var(--warning), #FBBF24); }
.la-bar-fill.orange { background: linear-gradient(90deg, #F97316, #FB923C); }

/* Payoff Progress */
.la-payoff {
    margin: 0 16px 16px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px;
    padding: 20px; display: flex; align-items: center; gap: 20px;
}
.la-payoff-ring { position: relative; width: 100px; height: 100px; flex-shrink: 0; }
.la-payoff-ring svg { transform: rotate(-90deg); }
.la-payoff-ring circle {
    fill: none; stroke-width: 8; cx: 50; cy: 50; r: 42;
}
.la-payoff-ring .bg { stroke: var(--border-color); }
.la-payoff-ring .progress {
    stroke: var(--accent); stroke-linecap: round;
    stroke-dasharray: 263.89; stroke-dashoffset: 263.89;
    transition: stroke-dashoffset 1.6s ease-out;
}
.la-payoff-pct {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 800; color: var(--text-primary);
}
.la-payoff-info { flex: 1; }
.la-payoff-title { font-size: 14px; font-weight: 600; color: var(--text-secondary); }
.la-payoff-detail { font-size: 12px; color: var(--text-muted); margin-top: 4px; line-height: 1.5; }
.la-payoff-amount { font-size: 20px; font-weight: 700; color: var(--success); margin-top: 4px; }

/* Table section uses layout from existing master */
.la-table-section { padding: 0 16px 120px; }
.la-search-bar {
    display: flex; align-items: center; gap: 8px; margin-bottom: 12px;
    position: relative;
}
.la-search-bar input {
    flex: 1; height: 42px; padding: 0 14px 0 38px; border: 1.5px solid var(--border-color);
    border-radius: 12px; background: var(--bg-card); color: var(--text-primary); font-size: 14px;
    outline: none; transition: border-color 0.2s;
}
.la-search-bar input:focus { border-color: var(--accent); }
.la-search-bar input::placeholder { color: var(--text-muted); }
.la-search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.la-table {
    width: 100%; border-collapse: separate; border-spacing: 0 6px;
}
.la-table th { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; padding: 8px 12px; text-align: left; }
.la-table td { padding: 12px; background: var(--bg-card); font-size: 13px; color: var(--text-secondary); }
.la-table tr td:first-child { border-radius: 10px 0 0 10px; }
.la-table tr td:last-child { border-radius: 0 10px 10px 0; }
.la-badge {
    display: inline-block; padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: 600;
}
.la-badge.active { background: rgba(34,197,94,0.12); color: var(--success); }
.la-badge.pending { background: rgba(245,158,11,0.12); color: var(--warning); }
.la-badge.closed { background: rgba(148,163,184,0.12); color: var(--text-secondary); }
.la-badge.defaulted { background: rgba(239,68,68,0.12); color: var(--danger); }
.la-btn-sm {
    display: inline-flex; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 600;
    transition: all 0.15s; text-decoration: none;
}
.la-btn-sm.primary { background: var(--accent); color: #fff; }
.la-btn-sm.primary:hover { opacity: 0.85; }
.la-btn-sm.success { background: var(--success); color: #fff; }
.la-btn-sm.info { background: rgba(59,130,246,0.1); color: var(--accent); }
.la-empty { text-align: center; padding: 40px; color: var(--text-muted); }

/* Light mode */
[data-theme="light"] .la-stat-card { background: var(--bg-primary); border-color: var(--text-secondary); }
[data-theme="light"] .la-stat-value { color: var(--text-primary); }
[data-theme="light"] .la-metric-card { background: var(--bg-primary); border-color: var(--text-secondary); }
[data-theme="light"] .la-metric-label { color: var(--text-primary); }
[data-theme="light"] .la-bar { background: var(--text-primary); }
[data-theme="light"] .la-payoff { background: var(--bg-primary); border-color: var(--text-secondary); }
[data-theme="light"] .la-payoff-title { color: var(--text-primary); }
[data-theme="light"] .la-table td { background: var(--bg-primary); }
[data-theme="light"] .la-search-bar input { background: var(--bg-primary); border-color: var(--text-secondary); color: var(--text-primary); }
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ __('Loans') }}</h1>
    <a href="{{ route('user.loans.create') }}" class="rw-section-link-pill">{{ __('Apply Loan') }} →</a>
</div>

<div class="am-body" style="padding-bottom:120px;">

    {{-- ===== Stats Grid ===== --}}
    <div class="la-grid">
        <div class="la-stat-card">
            <div class="la-stat-icon" style="background:rgba(59,130,246,0.12);color:var(--accent);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <span class="la-stat-label">{{ __('Principal') }}</span>
            <span class="la-stat-value">${{ number_format($totalPrincipal, 2) }}</span>
            <span class="la-stat-sub">{{ $activeCount }} {{ __('active') }}</span>
        </div>
        <div class="la-stat-card">
            <div class="la-stat-icon" style="background:rgba(34,197,94,0.12);color:var(--success);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            </div>
            <span class="la-stat-label">{{ __('Remaining') }}</span>
            <span class="la-stat-value">${{ number_format($totalBalance, 2) }}</span>
            <span class="la-stat-sub">{{ round(($totalBalance/max($totalPrincipal,1))*100,1) }}% {{ __('of principal') }}</span>
        </div>
        <div class="la-stat-card">
            <div class="la-stat-icon" style="background:rgba(245,158,11,0.12);color:var(--warning);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <span class="la-stat-label">{{ __('Next Payment') }}</span>
            <span class="la-stat-value">{{ $nextPayment ? $nextPayment->due_date->format('M d') : '—' }}</span>
            <span class="la-stat-sub">{{ $nextPayment ? '$'.number_format($nextPayment->amount_due, 2).' due' : __('No upcoming') }}</span>
        </div>
        <div class="la-stat-card">
            <div class="la-stat-icon" style="background:rgba(168,85,247,0.12);color:#A855F7;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <span class="la-stat-label">{{ __('Interest Rate') }}</span>
            <span class="la-stat-value">{{ number_format($avgRate, 1) }}%</span>
            <span class="la-stat-sub">{{ __('Weighted avg') }}</span>
        </div>
    </div>

    {{-- ===== Loan Health Score ===== --}}
    <div class="la-health-card">
        <span class="la-health-rank">{{ $rankLabel }}</span>
        <div class="la-health-title">{{ __('Loan Health Score') }}</div>
        <div class="la-health-chart">
            <svg width="160" height="120" viewBox="0 0 160 120" xmlns="http://www.w3.org/2000/svg">
                {{-- Radar chart with 4 axes --}}
                @php
                $cx = 80; $cy = 75; $r = 50;
                $axes = [
                    ['label' => 'Payments', 'value' => min($onTimeRate/100, 1)],
                    ['label' => 'DTI',      'value' => 1 - min(max(($monthlyPayments > 0 ? 30 : 0)/100, 0), 1)],
                    ['label' => 'Utilization', 'value' => 1 - min($utilization/100, 1)],
                    ['label' => 'History',  'value' => min($payoffPercent/100, 1)],
                ];
                $points = [];
                $n = count($axes);
                foreach ($axes as $i => $axis) {
                    $angle = deg2rad(-90 + ($i * 360 / $n));
                    $px = $cx + $r * $axis['value'] * cos($angle);
                    $py = $cy + $r * $axis['value'] * sin($angle);
                    $points[] = "$px,$py";
                }
                $bgPoints = [];
                foreach ($axes as $i => $axis) {
                    $angle = deg2rad(-90 + ($i * 360 / $n));
                    $px = $cx + $r * cos($angle);
                    $py = $cy + $r * sin($angle);
                    $bgPoints[] = "$px,$py";
                }
                @endphp
                {{-- Background grid --}}
                <polygon points="{{ implode(' ', $bgPoints) }}" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                {{-- Data polygon --}}
                <polygon points="{{ implode(' ', $points) }}" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.6)" stroke-width="1.5"/>
                {{-- Axis lines --}}
                @foreach ($axes as $i => $axis)
                @php $angle = deg2rad(-90 + ($i * 360 / $n)); @endphp
                <line x1="{{ $cx }}" y1="{{ $cy }}" x2="{{ $cx + $r * cos($angle) }}" y2="{{ $cy + $r * sin($angle) }}" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                @php
                $lx = $cx + ($r + 18) * cos($angle);
                $ly = $cy + ($r + 18) * sin($angle);
                @endphp
                <text x="{{ $lx }}" y="{{ $ly }}" text-anchor="middle" dominant-baseline="middle" font-size="8" fill="rgba(255,255,255,0.5)" font-weight="500">{{ $axis['label'] }}</text>
                @endforeach
            </svg>
        </div>
        <div class="la-health-score">{{ number_format($healthScore, 1) }} <span style="font-size:16px;font-weight:400;opacity:0.6;">/10</span></div>
        <div class="la-health-label">{{ $healthLabel }}</div>
    </div>

    {{-- ===== Repayment Metrics ===== --}}
    <div class="la-metrics">
        <div class="la-metric-card">
            <div class="la-metric-header">
                <span class="la-metric-label">{{ __('On-Time Payments') }}</span>
                <span class="la-metric-pct">{{ $onTimeRate }}%</span>
            </div>
            <div class="la-bar">
                <div class="la-bar-fill {{ $onTimeRate >= 80 ? 'green' : ($onTimeRate >= 50 ? 'yellow' : 'orange') }}" style="width:{{ $onTimeRate }}%"></div>
            </div>
        </div>
        <div class="la-metric-card">
            <div class="la-metric-header">
                <span class="la-metric-label">{{ __('Credit Utilization') }}</span>
                <span class="la-metric-pct">{{ $utilization }}%</span>
            </div>
            <div class="la-bar">
                <div class="la-bar-fill {{ $utilization <= 50 ? 'green' : ($utilization <= 75 ? 'yellow' : 'orange') }}" style="width:{{ $utilization }}%"></div>
            </div>
        </div>
        <div class="la-metric-card">
            <div class="la-metric-header">
                <span class="la-metric-label">{{ __('Payoff Progress') }}</span>
                <span class="la-metric-pct">{{ $payoffPercent }}%</span>
            </div>
            <div class="la-bar">
                <div class="la-bar-fill green" style="width:{{ $payoffPercent }}%"></div>
            </div>
        </div>
    </div>

    {{-- ===== Payoff Progress ===== --}}
    <div class="la-payoff">
        <div class="la-payoff-ring">
            <svg width="100" height="100" viewBox="0 0 100 100">
                <circle class="bg" cx="50" cy="50" r="42"/>
                <circle class="progress" cx="50" cy="50" r="42" stroke-dasharray="263.89" stroke-dashoffset="{{ 263.89 - (263.89 * $payoffPercent / 100) }}"/>
            </svg>
            <span class="la-payoff-pct">{{ $payoffPercent }}%</span>
        </div>
        <div class="la-payoff-info">
            <div class="la-payoff-title">{{ __('Payoff Progress') }}</div>
            <div class="la-payoff-amount">${{ number_format($totalPaid, 2) }} <span style="font-size:13px;color:var(--text-muted);font-weight:400;">/ ${{ number_format($totalPrincipal, 2) }}</span></div>
            <div class="la-payoff-detail">
                @if($payoffMonthsEarly > 0)
                    {{ __('On track to pay off') }} {{ $payoffMonthsEarly }} {{ __('months early') }}
                @elseif($totalPaid > 0)
                    {{ __('Making steady progress') }}
                @else
                    {{ __('No payments made yet') }}
                @endif
            </div>
        </div>
    </div>

    {{-- ===== Loans Table ===== --}}
    <div class="la-table-section">
        <div class="la-search-bar">
            <span class="la-search-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </span>
            <input type="text" id="loanSearch" placeholder="{{ __('Search loans...') }}">
        </div>

        <table class="la-table">
            <thead>
                <tr>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('Principal') }}</th>
                    <th>{{ __('Rate') }}</th>
                    <th>{{ __('Balance') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Next Due') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                <tr>
                    <td>{{ $loan->product->name ?? __('Custom') }}</td>
                    <td>${{ number_format($loan->principal, 2) }}</td>
                    <td>{{ number_format($loan->interest_rate, 1) }}%</td>
                    <td>${{ number_format($loan->balance_principal, 2) }}</td>
                    <td><span class="la-badge {{ $loan->status }}">{{ ucfirst($loan->status) }}</span></td>
                    <td>{{ $loan->next_due_date ? $loan->next_due_date->format('M d, Y') : '—' }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('user.loans.schedule', $loan->id) }}" class="la-btn-sm info">{{ __('Schedule') }}</a>
                            <a href="{{ route('user.loans.edit', $loan->id) }}" class="la-btn-sm primary">{{ __('Edit') }}</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="la-empty">{{ __('No loans found. Apply for your first loan!') }}</td></tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:16px;">
            {{ $loans->links() }}
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
// Search
document.getElementById('loanSearch')?.addEventListener('input', function() {
    const val = this.value.trim();
    const url = new URL(window.location.href);
    if (val) url.searchParams.set('q', val); else url.searchParams.delete('q');
    window.location.href = url.toString();
});

// Animate progress bars and ring on scroll
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                // Bars animate via CSS transition on width
                // Ring animates via CSS transition on stroke-dashoffset (already set in HTML)
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    const bars = document.querySelectorAll('.la-bar-fill');
    bars.forEach(function(bar) { observer.observe(bar); });
});
</script>
@endpush
