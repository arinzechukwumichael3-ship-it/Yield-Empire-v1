@php
    $cookie = App\Models\Admin\SiteSections::where('key','site_cookie')->first();
    //cookies results
    $approval_status      = request()->cookie('approval_status');
    $c_user_agent         = request()->cookie('user_agent');
    $c_ip_address         = request()->cookie('ip_address');
    $c_browser            = request()->cookie('browser');
    $c_platform           = request()->cookie('platform');
    //system informations
    $s_ipAddress    = request()->ip();
    $s_location     = geoip()->getLocation($s_ipAddress);
    $s_browser      = Agent::browser();
    $s_platform     = Agent::platform();
    $s_agent        = request()->header('User-Agent');
@endphp
@php
    // Public homepage renders the navy/light theme; every other frontend
    // page (auth, about, contact, ...) now defaults to light theme.
    $hp_theme = trim((string) \Illuminate\Support\Facades\View::getSection('pageTheme')) ?: 'light';
@endphp
<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}" data-theme="{{ $hp_theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $basic_settings->site_name ?? 'YieldEmpire' }} - {{ $basic_settings->site_title ?? 'Secure Financial Technology & Investment Platform' }}">
    <meta name="keywords" content="YieldEmpire, Financial Technology, Virtual Cards, Investment Platform, Global Payments">
    <meta name="author" content="YieldEmpire"/>
    <meta name="application-name" content="YieldEmpire">
    <meta name="theme-color" content="{{ $hp_theme === 'light' ? '#f7f8fa' : '#0A0E1A' }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ URL::current() }}">
    @php
        $current_url = URL::current();
    @endphp
    @if($current_url == setRoute('frontend.index'))
        <title>{{ __($basic_settings->site_name ?? '') }}  - {{ __($basic_settings->site_title ?? "") }}</title>
    @else
        <title>{{$basic_settings->site_name ?? ''}} - {{ $page_title ?? '' }}</title>
    @endif
    <script>
        (function() {
            document.documentElement.setAttribute('data-theme', '{{ $hp_theme }}');
            document.documentElement.classList.add('js');
        })();
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('partials.header-asset')
    <link rel="stylesheet" href="{{ asset('frontend/css/enzo-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/frontend-light.css') }}">

    @stack('css')

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "YieldEmpire",
        "alternateName": "YieldEmpire Financial Services",
        "url": "{{ config('app.url') }}",
        "logo": "{{ asset('backend/images/web-settings/image-assets/yieldempire-logo.png') }}",
        "description": "YieldEmpire is a financial technology platform for secure investing and global payments.",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+44-7464-483316",
            "contactType": "customer service",
            "availableLanguage": ["English"]
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "YieldEmpire",
        "url": "{{ config('app.url') }}",
        "description": "Financial technology platform offering virtual cards, global payments, and investment tools."
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FinancialService",
        "name": "YieldEmpire",
        "image": "{{ asset('backend/images/web-settings/image-assets/yieldempire-logo.png') }}",
        "@id": "{{ config('app.url') }}",
        "url": "{{ config('app.url') }}",
        "telephone": "+44-7464-483316",
        "priceRange": "$$"
    }
    </script>
</head>
<body>
    @if(request()->routeIs('frontend.index'))
        @include('frontend.partials.promo-topbar')
    @endif
    @include('frontend.partials.body-overlay')
    @include('frontend.partials.scroll-to-top')
    @include('partials.global-nav')

    @yield("content")

    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start cookie
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div class="cookie-main-wrapper">
        <div class="cookie-content">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M21.598 11.064a1.006 1.006 0 0 0-.854-.172A2.938 2.938 0 0 1 20 11c-1.654 0-3-1.346-3.003-2.937c.005-.034.016-.136.017-.17a.998.998 0 0 0-1.254-1.006A2.963 2.963 0 0 1 15 7c-1.654 0-3-1.346-3-3c0-.217.031-.444.099-.716a1 1 0 0 0-1.067-1.236A9.956 9.956 0 0 0 2 12c0 5.514 4.486 10 10 10s10-4.486 10-10c0-.049-.003-.097-.007-.16a1.004 1.004 0 0 0-.395-.776zM12 20c-4.411 0-8-3.589-8-8a7.962 7.962 0 0 1 6.006-7.75A5.006 5.006 0 0 0 15 9l.101-.001a5.007 5.007 0 0 0 4.837 4C19.444 16.941 16.073 20 12 20z"/><circle cx="12.5" cy="11.5" r="1.5"/><circle cx="8.5" cy="8.5" r="1.5"/><circle cx="7.5" cy="12.5" r="1.5"/><circle cx="15.5" cy="15.5" r="1.5"/><circle cx="10.5" cy="16.5" r="1.5"/></svg>
            @if ($cookie && $cookie->value)
                <p class="text-white">{{ __(strip_tags($cookie->value->desc ?? '')) }} <a href="{{ url('link').'/'.$cookie->value->link ?? '' }}">{{ __("Privacy Policy") }}</a></p>
            @endif
        </div>
        <div class="cookie-btn-area">
            <button class="cookie-btn">{{__("Allow")}}</button>
            <button class="cookie-btn-cross">{{__("Decline")}}</button>
        </div>
    </div>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End cookie
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    @include('frontend.partials.footer')
    @include('partials.footer-asset')
    @include('frontend.partials.extensions.tawk-to')

    <!-- WhatsApp Floating Widget (dismissible, compact, auto-shrink) -->
    <div class="whatsapp-widget" id="waWidget" data-aos="zoom-in" data-aos-delay="500">
        <button type="button" class="wa-dismiss" id="waDismiss" aria-label="Hide chat widget" title="Hide">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
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
    <script>
    (function(){
        var widget = document.getElementById('waWidget');
        var dismiss = document.getElementById('waDismiss');
        if (!widget || !dismiss) return;
        try {
            if (localStorage.getItem('wa_widget_hidden') === '1') {
                widget.style.display = 'none';
                return;
            }
        } catch (e) {}
        dismiss.addEventListener('click', function(){
            widget.style.transition = 'opacity .25s ease, transform .25s ease';
            widget.style.opacity = '0';
            widget.style.transform = 'translateY(8px)';
            setTimeout(function(){ widget.style.display = 'none'; }, 250);
            try { localStorage.setItem('wa_widget_hidden', '1'); } catch (e) {}
        });

        // Auto-shrink to icon-only when near bottom of page
        var footer = document.getElementById('footer');
        var cookieBanner = document.querySelector('.cookie-main-wrapper');
        var triggerShrink = false;
        function checkShrink() {
            if (!footer) return;
            var footerRect = footer.getBoundingClientRect();
            var viewportHeight = window.innerHeight;
            var widgetRect = widget.getBoundingClientRect();
            // Shrink when widget overlaps footer or is within 100px of it
            if (widgetRect.bottom > footerRect.top - 100) {
                if (!triggerShrink) {
                    widget.classList.add('wa-mini');
                    triggerShrink = true;
                }
            } else if (triggerShrink) {
                widget.classList.remove('wa-mini');
                triggerShrink = false;
            }
        }
        window.addEventListener('scroll', checkShrink, { passive: true });
        window.addEventListener('resize', checkShrink);
        checkShrink(); // Initial check
    })();
    </script>

    @stack('script')
    <script>
    var status = "{{  @$cookie->status }}";
     //cookies results
     var approval_status      = "{{ $approval_status}}";
     var c_user_agent         = "{{ $c_user_agent}}";
     var c_ip_address         = "{{ $c_ip_address}}";
     var c_browser            = "{{ $c_browser}}";
     var c_platform           = "{{ $c_platform}}";
     //system informations
    var s_ipAddress    = "{{ $s_ipAddress}}";
    var s_browser      = "{{ $s_browser}}";
    var s_platform     = "{{ $s_platform}}";
    var s_agent        = "{{ $s_agent}}";
    const pop = document.querySelector('.cookie-main-wrapper')
    if( status == 1){
        if(approval_status == 'allow' || approval_status == 'decline' || c_user_agent === s_agent || c_ip_address === s_ipAddress || c_browser === s_browser || c_platform === s_platform){
            pop.style.bottom = "-300px";
        }else{
            window.onload = function(){
            setTimeout(function(){
                pop.style.bottom = "20px";
            }, 2000)
        }
        }
    }else{
        pop.style.bottom = "-300px";
    }
    // })
</script>
<script>
    (function ($) {
        "use strict";
        //Allow
        $('.cookie-btn').on('click', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var postData = {
                type: "allow",
            };
            $.post('{{ route('global.set.cookie') }}', postData, function(response) {
                throwMessage('success', [response]);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            });
        });
        //Decline
        $('.cookie-btn-cross').on('click', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var postData = {
                type: "decline",
            };
            $.post('{{ route('global.set.cookie') }}', postData, function(response) {
                throwMessage('error',[response]);
                setTimeout(function(){
                    location.reload();
                },1000);
            });
        });
    })(jQuery)
</script>
</body>
</html>
