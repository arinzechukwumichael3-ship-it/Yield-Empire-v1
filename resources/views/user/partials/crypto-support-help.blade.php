@php
    $coinName = $coin['coin'] ?? '';
    $coinNetwork = $coin['network'] ?? '';
    $walletAddress = $walletAddress ?? ($coin['address'] ?? '');
    $usdAmount = isset($amount) ? number_format((float) $amount, 2) : '';
    $cryptoAmount = $cryptoAmount ?? $usdAmount;
    $supportMessage = 'Hello YieldEmpire Support, I need help with my crypto deposit.' . PHP_EOL . PHP_EOL
        . 'Deposit details:' . PHP_EOL
        . ($usdAmount !== '' ? '• Amount: $' . $usdAmount . ' USD' . PHP_EOL : '')
        . ($coinName !== '' ? '• Coin: ' . $coinName . ($coinNetwork !== '' ? ' (' . $coinNetwork . ')' : '') . PHP_EOL : '')
        . ($walletAddress !== '' ? '• Wallet address: ' . $walletAddress . PHP_EOL : '')
        . PHP_EOL . 'I am not sure how to complete the transfer. Can you guide me?';
@endphp

<div class="cd-help-card">
    <div class="cd-help-head">
        <div class="cd-help-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="cd-help-text">
            <div class="cd-help-title">{{ $helpTitle ?? __('Need help with your deposit?') }}</div>
            <div class="cd-help-sub">{{ $helpIntro ?? __('Our support team can walk you through your crypto deposit step by step, on WhatsApp.') }}</div>
        </div>
    </div>

    <a href="{{ support_whatsapp_link($supportMessage) }}" target="_blank" rel="noopener noreferrer" class="cd-help-cta">
        <i class="lab la-whatsapp"></i>
        <span>{{ __('Chat with Support on WhatsApp') }}</span>
        <svg class="cd-help-cta-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>

    <button type="button" class="cd-help-accordion" onclick="toggleCryptoHelp()">
        <span>{{ __("I don't have a crypto wallet or app — how do I send crypto?") }}</span>
        <span class="cd-help-chevron" id="helpChevron">&#9660;</span>
    </button>

    <div class="cd-help-steps" id="helpSteps">
        <div class="cd-help-step">
            <span class="cd-help-step-num">1</span>
            <div class="cd-help-step-body">
                <div class="cd-help-step-title">{{ __('Install a crypto wallet app') }}</div>
                <div class="cd-help-step-desc">{{ __('Download any popular wallet (Trust Wallet, Binance, or Coinbase) from the official app store and follow the in-app setup.') }}</div>
            </div>
        </div>
        <div class="cd-help-step">
            <span class="cd-help-step-num">2</span>
            <div class="cd-help-step-body">
                <div class="cd-help-step-title">{{ __('Buy or send the exact amount') }}</div>
                <div class="cd-help-step-desc">{{ __('From your wallet, choose "Send", paste the address above, enter the exact amount shown, and select the same network (') . $coinNetwork . __(').') }}</div>
            </div>
        </div>
        <div class="cd-help-step">
            <span class="cd-help-step-num">3</span>
            <div class="cd-help-step-body">
                <div class="cd-help-step-title">{{ __('Confirm on the next screen') }}</div>
                <div class="cd-help-step-desc">{{ __('Once the payment leaves your wallet, tick the confirmation box and submit. Our team verifies deposits manually and will credit your balance.') }}</div>
            </div>
        </div>
        <div class="cd-help-note">
            {{ __('Not sure about anything? No problem — chat with us on WhatsApp and we will guide you through it, free of charge.') }}
        </div>
    </div>
</div>

@push("script")
<script>
function toggleCryptoHelp() {
    var steps = document.getElementById("helpSteps");
    var chevron = document.getElementById("helpChevron");
    var open = steps.style.display !== "none";
    steps.style.display = open ? "none" : "block";
    chevron.style.transform = open ? "rotate(0deg)" : "rotate(180deg)";
}
</script>
@endpush
