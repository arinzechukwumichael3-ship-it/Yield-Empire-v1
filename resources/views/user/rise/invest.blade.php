@extends('user.layouts.rise-master')

@section('content')
@php
$portfolio = $portfolio ?? null;
$holdings = $holdings ?? collect([]);
$assets = $assets ?? collect([]);
@endphp

<div class="io-header">
    <h1 class="io-title">Investment Opportunities</h1>
    <select class="io-dropdown">
        <option>All Types ▼</option>
        <option>Stocks</option>
        <option>Bonds</option>
        <option>Real Estate</option>
        <option>Crypto</option>
    </select>
    <button class="io-filter-btn">Filter</button>
</div>

<div class="io-body">
    <!-- Plan Cards Grid -->
    <div class="io-plans-grid">
        <div class="ip-card" style="min-width:unset;">
            <div class="ip-badge">★ EUR</div>
            <div class="ip-name">Fixed Income</div>
            <div class="ip-rate">8.5% /yr</div>
            <div class="ip-duration">3-12 months</div>
            <a href="#" class="ip-invest-btn">Invest Now</a>
        </div>
        <div class="ip-card" style="min-width:unset;">
            <div class="ip-badge">★ USD</div>
            <div class="ip-name">Growth Fund</div>
            <div class="ip-rate">12% /yr</div>
            <div class="ip-duration">6-18 months</div>
            <a href="#" class="ip-invest-btn">Invest Now</a>
        </div>
        <div class="ip-card" style="min-width:unset;">
            <div class="ip-badge">★ USD</div>
            <div class="ip-name">Premium Plus</div>
            <div class="ip-rate">15% /yr</div>
            <div class="ip-duration">12-24 months</div>
            <a href="#" class="ip-invest-btn">Invest Now</a>
        </div>
        <div class="ip-card" style="min-width:unset;">
            <div class="ip-badge">★ GBP</div>
            <div class="ip-name">Sterling Vault</div>
            <div class="ip-rate">23% /yr</div>
            <div class="ip-duration">12 months</div>
            <a href="#" class="ip-invest-btn">Invest Now</a>
        </div>
    </div>

    <!-- Investment Calculator -->
    <div class="ic-card">
        <div class="ic-title">Investment Calculator</div>
        <div class="ic-field">
            <label class="ic-label">Investment Amount</label>
            <input type="number" class="ic-input" placeholder="Enter amount" id="calcAmount">
        </div>
        <div class="ic-field">
            <label class="ic-label">Maturity (months)</label>
            <input type="number" class="ic-input" placeholder="Enter months" id="calcMonths">
        </div>
        <div class="ic-field">
            <label class="ic-label">Compounding</label>
            <select class="ic-select" id="calcCompound">
                <option value="monthly">Monthly</option>
                <option value="quarterly">Quarterly</option>
                <option value="annually">Annually</option>
            </select>
        </div>
        <button class="ic-btn" id="calcBtn">Recalculate</button>
        <div class="ic-result" id="calcResult" style="display:none;">
            <div class="ic-result-row">
                <span class="ic-result-label">Investment</span>
                <span class="ic-result-value" id="calcInvestAmount">-</span>
            </div>
            <div class="ic-result-row">
                <span class="ic-result-label">Est. Returns</span>
                <span class="ic-result-value" id="calcReturns">-</span>
            </div>
            <div class="ic-result-row">
                <span class="ic-result-label">Maturity Value</span>
                <span class="ic-result-value" id="calcMaturity">-</span>
            </div>
        </div>
    </div>

    <!-- Existing Tabs (USD/GBP/Portfolio) -->
    <div class="ri-tabs">
        <button class="ri-tab active" data-tab="usd">USD Plans</button>
        <button class="ri-tab" data-tab="gbp">GBP Plans</button>
        <button class="ri-tab" data-tab="portfolio">Portfolio</button>
    </div>

    <div class="ri-tab-content active" id="tab-usd">
        <div class="ri-section-label">Your Dollar Plans <span class="ri-count-badge">0</span></div>
        <div class="ri-plans-grid">
            <a href="#" class="ri-plan-add-card">
                <div class="ri-plan-add-icon">+</div>
                <span>Create investment plan</span>
            </a>
        </div>
        <a href="#" class="ri-gift-card">
            <span class="ri-gift-icon">🎁</span>
            <div class="ri-gift-text">
                <span class="ri-gift-title">Gift a plan</span>
                <span class="ri-gift-sub">Send someone a plan as low as $10</span>
            </div>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    <div class="ri-tab-content" id="tab-gbp">
        <div class="ri-section-label">Your Sterling Plans <span class="ri-count-badge">0</span></div>
        <div class="ri-plans-grid">
            <a href="#" class="ri-plan-add-card">
                <div class="ri-plan-add-icon">+</div>
                <span>Create investment plan</span>
            </a>
        </div>
    </div>

    <div class="ri-tab-content" id="tab-portfolio">
        @if($portfolio)
        <div class="ri-portfolio-summary">
            <div class="ri-portfolio-row">
                <span>Investment balance</span>
                <span class="ri-portfolio-value">${{ number_format($portfolio->total_value ?? 0, 2) }}</span>
            </div>
            <div class="ri-portfolio-row">
                <span>YTD Returns</span>
                <span class="ri-portfolio-value green">+${{ number_format($portfolio->ytd_returns ?? 0, 2) }}</span>
            </div>
            <div class="ri-portfolio-row">
                <span>Asset classes</span>
                <span class="ri-portfolio-value">{{ $holdings->count() ?? 0 }}</span>
            </div>
        </div>
        <div class="ri-portfolio-composition">
            <div class="ri-comp-header">PORTFOLIO COMPOSITION</div>
            <p class="ri-comp-sub">Your investments are spread across these asset classes</p>
            <div class="ri-comp-legend">
                <span><span class="ri-dot orange"></span> Real Estate</span>
                <span><span class="ri-dot blue"></span> Growth Stocks</span>
                <span><span class="ri-dot green"></span> Fixed Income</span>
            </div>
            @foreach($holdings as $holding)
            <div class="ri-asset-row">
                <span class="ri-asset-icon">{{ $holding->asset->icon ?? '📊' }}</span>
                <div class="ri-asset-info">
                    <span class="ri-asset-name">{{ $holding->asset->name ?? $holding->asset_type ?? 'Asset' }}</span>
                    <span class="ri-asset-pct">{{ $holding->allocation_percent ?? 0 }}%</span>
                </div>
                <span class="ri-asset-value">${{ number_format($holding->value ?? 0, 2) }}</span>
            </div>
            @endforeach
        </div>
        @else
        <div class="ri-empty-state">
            <span class="ri-empty-icon">📊</span>
            <span class="ri-empty-title">No portfolio yet</span>
            <span class="ri-empty-sub">Start investing to build your portfolio</span>
        </div>
        @endif
    </div>
</div>

<a href="#" class="ri-create-cta">+ Create investment plan</a>

@push("script")
<script>
// Tab switching
document.querySelectorAll('.ri-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.ri-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.ri-tab-content').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
});

// Calculator
document.getElementById('calcBtn')?.addEventListener('click', function() {
    const amount = parseFloat(document.getElementById('calcAmount').value) || 0;
    const months = parseFloat(document.getElementById('calcMonths').value) || 0;
    if (!amount || !months) return;
    const rate = 0.10; // 10% annual
    const maturity = amount * Math.pow(1 + rate/12, months);
    const returns = maturity - amount;
    document.getElementById('calcInvestAmount').textContent = '$' + amount.toFixed(2);
    document.getElementById('calcReturns').textContent = '$' + returns.toFixed(2);
    document.getElementById('calcMaturity').textContent = '$' + maturity.toFixed(2);
    document.getElementById('calcResult').style.display = 'block';
});
</script>
@endpush
@endsection