{{--
    Shared investment plan card.

    Two variants:
      - compact  (default): slim, horizontally-scrollable card used on the
        logged-in dashboard ("Invest & Grow" section).
      - home     : richer marketing card used on the public homepage,
        with feature bullets and optional "featured" flag.

    Data source: pass an `$plan` (InvestmentAsset) for data-driven rendering,
    or override any field with the named props (badge, name, rate, duration,
    features, ...).
--}}
@props([
    'plan' => null,
    'variant' => 'compact',
    'badge' => null,
    'badgeGold' => false,
    'flag' => null,
    'name' => null,
    'rate' => null,
    'rateUnit' => 'APY / yr',
    'duration' => null,
    'features' => [],
    'featured' => false,
    'href' => null,
    'delay' => '0ms',
])

@php
    // InvestmentPlan: roi_percent + duration_days  |  InvestmentAsset: base_yield + maturities
    $termMs = $plan && is_array($plan->maturities) ? $plan->maturities : [];
    $days = $plan->duration_days ?? null;
    $termLabel = $duration ?: (
        $days != null
            ? ($days % 30 === 0 ? ($days / 30) . ' month' . ($days > 30 ? 's' : '') : $days . ' days')
            : (count($termMs) > 1
                ? min($termMs) . '-' . max($termMs) . ' months'
                : ((count($termMs) === 1 && ($termMs[0] ?? 0) > 0) ? $termMs[0] . ' months' : 'Flexible'))
    );

    $cardBadge = $badge ?: ($plan->symbol ?? (is_object($plan) ? strtoupper(explode(' ', trim($plan->name ?? 'Plan'))[0]) : 'USD'));
    $cardName = $name ?: ($plan->name ?? 'Investment Plan');
    $cardRate = $rate ?: rtrim(rtrim(number_format((float)($plan->roi_percent ?? $plan->base_yield ?? 0.00), 2), '0'), '.');
    $isPerTerm = $plan && $plan->roi_percent != null;
    $rateSuffix = $isPerTerm ? 'ROI' : '/yr';
    $rateHeld = $rateUnit === 'APY / yr' ? ($isPerTerm ? 'ROI' : $rateUnit) : $rateUnit;
    $cardHref = $href ?: route('user.invest.new');
    $checkColor = $featured ? '#F5B84C' : '#3B82F6';
@endphp

@if($variant === 'compact')

    <div class="dash-invest-card">
        <div class="dash-invest-badge">{{ strtoupper($cardBadge) }}</div>
        <div class="dash-invest-name">{{ $cardName }}</div>
        <div class="dash-invest-rate">{{ $cardRate }}% <span class="dash-invest-rate-unit">{{ $rateSuffix }}</span></div>
        <div class="dash-invest-duration">{{ $termLabel }}</div>
        <a href="{{ $cardHref }}" class="dash-invest-btn">{{ __('Invest Now') }}</a>
    </div>

@else

    <div class="plans-card {{ $featured ? 'plans-card-featured' : '' }} animate-on-scroll" style="transition-delay:{{ $delay }}">
        @if($flag)
            <span class="plans-card-flag">{{ $flag }}</span>
        @endif
        <div class="plans-card-top">
            <span class="plans-card-badge {{ $badgeGold ? 'plans-card-badge-gold' : '' }}">{{ $cardBadge }}</span>
            <span class="plans-card-symbol">$</span>
        </div>
        <h3 class="plans-card-name">{{ $cardName }}</h3>
        <div class="plans-card-rate">{{ $cardRate }}% <span class="plans-card-rate-unit">{{ $rateHeld }}</span></div>
        @if(count($features))
            <ul class="plans-card-features">
                @foreach($features as $feature)
                    <li><svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="{{ $checkColor }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 6L8 14L4 10"/></svg>{{ $feature }}</li>
                @endforeach
            </ul>
        @endif
        <a href="{{ $cardHref }}" class="plans-card-btn {{ $featured ? 'plans-card-btn-gold' : '' }}">{{ __('Invest Now') }}</a>
    </div>

@endif