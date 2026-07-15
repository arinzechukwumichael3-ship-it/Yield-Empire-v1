<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/baking-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/rise-theme.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('frontend/css/enzo-theme.css') }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @include("partials.header-asset")
    <script>
        (function() {
            var saved = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>
    <title>{{ (isset($page_title) ? __($page_title) : __("Dashboard")) }}</title>
    @stack("css")
</head>
<body>
    @include('frontend.partials.body-overlay')
    @include('partials.global-nav')

    <div class="rise-app">
        <div class="rise-screen">
            @yield('content')
        </div>
        @include('user.partials.glass-bottom-nav')
    </div>

    @include('partials.footer-asset')
    @include('user.partials.push-notification')


    <!-- WhatsApp Floating Widget -->
    <div class="whatsapp-widget" data-aos="zoom-in" data-aos-delay="500" style="bottom: 120px; right: 20px;">
        <a href="https://wa.me/message/ZW7EJRXHGL3GG1" target="_blank" class="whatsapp-btn" rel="noopener noreferrer" aria-label="Contact Support on WhatsApp">
            <div class="whatsapp-icon">
                <i class="lab la-whatsapp"></i>
                <span class="online-dot"></span>
            </div>
            <div class="whatsapp-text d-none d-lg-flex">
                <span>{{ __('Chat with us') }}</span>
            </div>
        </a>
    </div>

    @stack("script")
    <script>
        AOS.init({ duration: 1000, once: true });
    </script>
    <script>
    (function(){
        document.addEventListener('DOMContentLoaded', function(){ document.documentElement.classList.remove('no-transitions'); }, { once: true });
    })();
    </script>
    <script>
    // --- Site-wide currency preference ---
    (function(){
        var currencySymbols = { usd: '$', eur: '€', gbp: '£' };
        var saved = localStorage.getItem('dash_currency');
        window.__preferredCurrency = saved || 'usd';

        // Update any element with data-currency-amount attribute
        var els = document.querySelectorAll('[data-currency-amount]');
        if (els.length) {
            var sym = currencySymbols[window.__preferredCurrency] || '$';
            els.forEach(function(el) {
                var val = el.getAttribute('data-currency-amount');
                if (val) el.textContent = sym + val;
            });
        }
    })();
    </script>
</body>
</html>
