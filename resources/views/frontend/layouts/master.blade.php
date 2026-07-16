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
<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $basic_settings->site_name ?? 'EnzoBank' }} - {{ $basic_settings->site_title ?? 'Secure USA Digital Banking Platform' }}">
    <meta name="keywords" content="EnzoBank, USA Digital Bank, Online Banking, Virtual Cards, FDIC Insured, US Financial Services">
    <meta name="author" content="EnzoBank"/>
    <meta name="application-name" content="EnzoBank">
    <meta name="geo.region" content="US-NY">
    <meta name="geo.placename" content="New York">
    <meta name="theme-color" content="#0A0E1A">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ URL::current() }}">
    @php
        $current_url = URL::current();
    @endphp
    @if($current_url == setRoute('frontend.index'))
        <title>{{ __($basic_settings->site_name) ?? ''}}  - {{ __($basic_settings->site_title) ?? "" }}</title>
    @else
        <title>{{$basic_settings->site_name ?? ''}} - {{ $page_title ?? '' }}</title>
    @endif
    <script>
        (function() {
            var saved = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', saved);
            document.documentElement.classList.add('js');
        })();
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('partials.header-asset')
    <link rel="stylesheet" href="{{ asset('frontend/css/enzo-theme.css') }}?v={{ filemtime(public_path('frontend/css/enzo-theme.css')) }}">

    @stack('css')

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "EnzoBank",
        "alternateName": "EnzoBank Financial Services",
        "url": "{{ config('app.url') }}",
        "logo": "{{ asset('frontend/images/logo.png') }}",
        "description": "EnzoBank is a US-based financial technology company providing secure digital banking services.",
        "foundingDate": "2022",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "123 Financial Plaza, Suite 400",
            "addressLocality": "New York",
            "addressRegion": "NY",
            "postalCode": "10001",
            "addressCountry": "US"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+1-800-123-4567",
            "contactType": "customer service",
            "areaServed": "US",
            "availableLanguage": ["English"]
        },
        "sameAs": [
            "https://x.com/enzobank",
            "https://www.linkedin.com/company/enzobank",
            "https://www.facebook.com/enzobank"
        ]
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "EnzoBank",
        "url": "{{ config('app.url') }}",
        "description": "Secure USA digital banking platform offering virtual cards, global payments, and smart investments.",
        "about": {
            "@type": "FinancialService",
            "feesAndCommissionsSpecification": "https://enzobank.org/fees",
            "areaServed": {
                "@type": "Country",
                "name": "United States"
            }
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FinancialService",
        "name": "EnzoBank",
        "image": "{{ asset('frontend/images/logo.png') }}",
        "@id": "{{ config('app.url') }}",
        "url": "{{ config('app.url') }}",
        "telephone": "+1-800-123-4567",
        "priceRange": "$$",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "123 Financial Plaza, Suite 400",
            "addressLocality": "New York",
            "addressRegion": "NY",
            "postalCode": "10001",
            "addressCountry": "US"
        },
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
                "opens": "09:00",
                "closes": "18:00"
            }
        ],
        "areaServed": [
            {
                "@type": "Country",
                "name": "United States"
            }
        ]
    }
    </script>
</head>
<body>
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

    <!-- WhatsApp Floating Widget -->
    <div class="whatsapp-widget" data-aos="zoom-in" data-aos-delay="500" style="bottom: 30px">
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
