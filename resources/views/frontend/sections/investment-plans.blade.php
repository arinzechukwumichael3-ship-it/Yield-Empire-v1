<!-- ====== INVESTMENT PLANS / PRODUCTS ====== -->
<section class="enzo-section enzo-section-dark" id="plans">
    <div class="enzo-container">
        <div class="enzo-section-header">
            <span class="enzo-badge">INVESTMENT PLANS</span>
            <h2 class="enzo-section-title">Plans Built for <span class="enzo-text-grad">Steady Growth</span></h2>
            <p class="enzo-section-sub">Choose the plan that fits your goals. Clear terms, no hidden fees, and payouts you can track in real time.</p>
        </div>

        <div class="plans-grid">
            @forelse($plans ?? collect([]) as $i => $plan)
                @php
                    $min = (float)$plan->min_amount;
                    $max = $plan->max_amount !== null ? (float)$plan->max_amount : null;
                    $range = '$' . number_format($min, $min === floor($min) ? 0 : 2) . ($max ? ' – $' . number_format($max, $max === floor($max) ? 0 : 2) : '+');
                    $termMonths = $plan->duration_days % 30 === 0 ? $plan->duration_days / 30 : null;
                @endphp
                <x-plan-card variant="home" :plan="$plan"
                    badge="{{ strtoupper(explode(' ', trim($plan->name))[0] ?? 'PLAN') }}"
                    :badge-gold="$i === 1"
                    :featured="$i === 1"
                    :flag="$i === 1 ? 'MOST POPULAR' : null"
                    name="{{ $plan->name }}"
                    rate="{{ rtrim(rtrim(number_format((float)$plan->roi_percent, 2), '0'), '.') }}"
                    delay="{{ $i * 120 }}ms"
                    :features="[
                        'Range: ' . $range,
                        'Term: ' . ($termMonths ? $termMonths . ' month' . ($termMonths > 1 ? 's' : '') : $plan->duration_days . ' days'),
                        'Flat ' . rtrim(rtrim(number_format((float)$plan->roi_percent, 2), '0'), '.') . '% ROI on maturity',
                    ]"
                    href="{{ setRoute('user.invest.new') }}" />
            @empty
                <div class="plans-empty">
                    <span class="plans-empty-title">Plans are being finalized</span>
                    <span class="plans-empty-sub">New offerings will appear here shortly.</span>
                </div>
            @endforelse
        </div>

        <p class="plans-disclaimer">Rates and terms shown reflect the current live plans. Yield is paid on maturity and updated in real time.</p>
    </div>
</section>