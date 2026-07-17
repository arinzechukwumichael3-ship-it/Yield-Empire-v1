@extends('user.layouts.rise-master')

@push('css')
<style>
.inv-offer-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 10px; }
.inv-offer-name { font-size: 15px; font-weight: 700; color: #fff; line-height: 1.2; }
.inv-offer-sub { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
.inv-offer-section { margin-top: 12px; }
.inv-offer-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); margin-bottom: 4px; }
.inv-offer-value { font-size: 13px; color: var(--text-secondary); }
.inv-offer-tier { display: inline-block; margin-right: 8px; }
.inv-offer-figures { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 14px; }
.inv-offer-figures .inv-offer-label { margin-bottom: 2px; }
.inv-offer-earn { font-size: 15px; font-weight: 700; color: #fff; }
.inv-offer-roi { font-size: 15px; font-weight: 700; color: var(--success); text-align: right; }
.inv-offer-goal { display: flex; justify-content: space-between; font-size: 11px; color: var(--text-muted); margin-top: 4px; }
</style>
@endpush

@section('content')

<div class="am-header">
    <h1 class="am-header-title">{{ __($page_title) }}</h1>
</div>

<div class="am-body">

    <!-- Filter -->
    <div class="am-card">
        <div class="am-card-title">{{ __('Filter Opportunities') }}</div>
        <form method="GET" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="am-input-wrap" style="flex: 1; min-width: 220px;">
                <select name="type">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach(['fixed_deposit'=>__('Fixed Deposits'),'mutual_fund'=>__('Mutual Funds'),'gov_bond'=>__('Government Bonds'),'corp_bond'=>__('Corporate Bonds'),'stock'=>__('Stocks'),'retirement'=>__('Retirement Accounts')] as $t => $label)
                        <option value="{{ $t }}" @selected(request('type')===$t)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button class="am-btn" style="width: auto; padding: 14px 28px; border-radius: 100px;">{{ __('Filter') }}</button>
        </form>
    </div>

    <!-- Compound Interest Calculator -->
    <div class="am-card">
        <div class="am-card-title">{{ __('Projected Returns Calculator') }}</div>
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="am-label">{{ __('Investment Amount') }}</label>
                <div class="am-input-wrap">
                    <input type="number" step="0.01" min="0" id="cmp-amount" value="1000">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label class="am-label">{{ __('Maturity (months)') }}</label>
                <div class="am-input-wrap">
                    <input type="number" min="1" id="cmp-months" value="12">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label class="am-label">{{ __('Compounding') }}</label>
                <div class="am-input-wrap">
                    <select id="cmp-frequency">
                        <option value="12">{{ __('Monthly') }}</option>
                        <option value="4">{{ __('Quarterly') }}</option>
                        <option value="1">{{ __('Annually') }}</option>
                        <option value="0">{{ __('Simple') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <button class="am-btn" id="cmp-calc">{{ __('Recalculate') }}</button>
            </div>
        </div>
    </div>

    <!-- Offers grid -->
    <div class="row g-3" id="offers-grid" data-assets='@json($assetPayload)'>
        @foreach($assets as $a)
        <div class="col-12 col-md-6 col-xl-4 d-flex">
            <div class="inv-plan-card h-100" style="width: 100%;">
                <div class="inv-offer-head">
                    <div>
                        <div class="inv-offer-name">{{ $a->name }}</div>
                        <div class="inv-offer-sub">{{ strtoupper(str_replace('_',' ', $a->offering_type)) }} • {{ $a->symbol }}</div>
                    </div>
                    <div class="text-end">
                        <span class="inv-badge {{ $a->risk_score >=4 ? 'closed' : ($a->risk_score==3 ? 'pending' : 'active') }}">
                            <span class="inv-badge-dot"></span>{{ __('Risk') }} {{ $a->risk_score }}
                        </span>
                    </div>
                </div>

                <div class="inv-offer-section">
                    <div class="inv-offer-label">{{ __('Tiered Rates') }}</div>
                    <div class="inv-offer-value">
                        @php $tiers = $a->tiers ?? []; @endphp
                        @if($tiers)
                            @foreach($tiers as $t)
                                <span class="inv-offer-tier">{{ get_amount($t['min']) }}–{{ $t['max'] ? get_amount($t['max']) : __('∞') }}: {{ number_format($t['rate'],2) }}%</span>
                            @endforeach
                        @else
                            <span>{{ number_format($a->base_yield,2) }}%</span>
                        @endif
                    </div>
                </div>

                <div class="inv-offer-section">
                    <div class="inv-offer-label">{{ __('Maturities') }}</div>
                    <div class="inv-offer-value">{{ collect($a->maturities ?? [6,12,24])->implode(', ') }} {{ __('months') }}</div>
                </div>

                <div class="inv-offer-figures">
                    <div>
                        <div class="inv-offer-label">{{ __('Projected Earnings') }}</div>
                        <div class="inv-offer-earn" data-proj-earn="asset-{{ $a->id }}">—</div>
                    </div>
                    <div style="text-align: right;">
                        <div class="inv-offer-label">{{ __('Projected ROI') }}</div>
                        <div class="inv-offer-roi" data-proj-roi="asset-{{ $a->id }}">—</div>
                    </div>
                </div>

                <div class="inv-offer-section">
                    <div class="inv-progress">
                        <div class="inv-progress-fill blue" data-goal-progress="asset-{{ $a->id }}"></div>
                    </div>
                    <div class="inv-offer-goal">
                        <span>{{ __('Goal Progress') }}</span>
                        <span data-goal-text="asset-{{ $a->id }}">0%</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{ $assets->links() }}
</div>
@endsection

@push('script')
<script>
function tierRate(tiers, base, amount) {
    if (!tiers || !tiers.length) return base;
    let r = base;
    for (const t of tiers) {
        const min = parseFloat(t.min ?? 0);
        const max = t.max ? parseFloat(t.max) : Infinity;
        if (amount >= min && amount <= max) {
            r = parseFloat(t.rate);
            break;
        }
    }
    return r;
}
function projected(amount, months, rate, compPeriods) {
    const years = months / 12.0;
    if (compPeriods > 0) {
        const r = rate/100.0;
        const f = Math.pow(1 + r/compPeriods, compPeriods*years);
        const value = amount * f;
        return { value, earnings: value - amount, roi: ((value-amount)/amount)*100 };
    } else {
        const earnings = amount * (rate/100.0) * years;
        const value = amount + earnings;
        return { value, earnings, roi: (earnings/amount)*100 };
    }
}
function updateProjections() {
    const amount = parseFloat(document.getElementById('cmp-amount').value || 0);
    const months = parseInt(document.getElementById('cmp-months').value || 0);
    const comp = parseInt(document.getElementById('cmp-frequency').value);
    const grid = document.getElementById('offers-grid');
    const assets = JSON.parse(grid.dataset.assets || '[]');
    assets.forEach(a => {
        const rate = tierRate(a.tiers, a.base_yield, amount);
        const p = projected(amount, months, rate, comp);
        const earnEl = document.querySelector(`[data-proj-earn="asset-${a.id}"]`);
        const roiEl = document.querySelector(`[data-proj-roi="asset-${a.id}"]`);
        if (earnEl) earnEl.textContent = p.earnings.toFixed(2);
        if (roiEl) roiEl.textContent = p.roi.toFixed(2) + '%';
        const goalBar = document.querySelector(`[data-goal-progress="asset-${a.id}"]`);
        const goalText = document.querySelector(`[data-goal-text="asset-${a.id}"]`);
        const target = amount > 0 ? amount : 1;
        const progress = Math.min(100, (p.value/ (target*1.2)) * 100);
        if (goalBar) goalBar.style.width = progress.toFixed(0) + '%';
        if (goalText) goalText.textContent = progress.toFixed(0) + '%';
    });
}
document.getElementById('cmp-calc').addEventListener('click', function(e){ e.preventDefault(); updateProjections(); });
['cmp-amount','cmp-months','cmp-frequency'].forEach(id => {
    document.getElementById(id).addEventListener('input', updateProjections);
    document.getElementById(id).addEventListener('change', updateProjections);
});
updateProjections();
</script>
@endpush
