@extends("frontend.layouts.master")

@section('pageTheme', 'light')

@push("css")
<link rel="stylesheet" href="{{ asset('frontend/css/enzo-home.css') }}">
<style>
body { background: #f7f8fa; font-family: "Inter", system-ui, sans-serif; }
.body-overlay { display: none !important; }
</style>
@endpush

@section("content")

    @include("frontend.sections.hero")
    @include("frontend.sections.trust-bar")
    @include("frontend.sections.payment-rails")
    @include("frontend.sections.features-new")
    @include("frontend.sections.stats-section")
    @include("frontend.sections.how-it-works")
    @include("frontend.sections.investment-plans")
    @include("frontend.sections.building-block")
    @include("frontend.sections.card-showcase")
    @include("frontend.sections.testimonials")
    @include("frontend.sections.security-new")
    @include("frontend.sections.cta-section")
@endsection

@push("script")
<script>
(function() {
    // Scroll animations with IntersectionObserver
    var animObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                animObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0, rootMargin: "0px 0px 15% 0px" });

    document.querySelectorAll(".animate-on-scroll").forEach(function(el) {
        animObserver.observe(el);
    });

    // Also observe legacy .scroll-fade elements
    var fadeObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                fadeObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });

    document.querySelectorAll(".scroll-fade").forEach(function(el) {
        fadeObserver.observe(el);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll("a[href^=\"#\"]").forEach(function(anchor) {
        anchor.addEventListener("click", function(e) {
            var target = document.querySelector(this.getAttribute("href"));
            if (target) {
                e.preventDefault();
                window.scrollTo({ top: target.getBoundingClientRect().top + window.pageYOffset - 80, behavior: "smooth" });
            }
        });
    });

    // Count-up animation for stats.
    // Compact notation keeps large values short (2M, not 2,000,000) so all
    // four stats share the same visual weight and never overflow their cell.
    function countUp(el, target, duration) {
        duration = duration || 2000;
        var suffix = el.getAttribute("data-suffix") || "";
        var prefix = el.getAttribute("data-prefix") || "";
        var formatter = target >= 10000
            ? new Intl.NumberFormat('en-US', { notation: 'compact', maximumFractionDigits: 1 })
            : new Intl.NumberFormat('en-US', { maximumFractionDigits: 1 });
        var start = 0;
        var increment = target / (duration / 16);
        var timer = setInterval(function() {
            start += increment;
            if (start >= target) {
                el.textContent = prefix + formatter.format(target) + suffix;
                clearInterval(timer);
            } else {
                el.textContent = prefix + formatter.format(Math.floor(start)) + suffix;
            }
        }, 16);
    }

    var counted = false;
    var statsSection = document.getElementById("stats");
    if (statsSection) {
        var countObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting && !counted) {
                    counted = true;
                    var nums = statsSection.querySelectorAll("[data-target]");
                    nums.forEach(function(el) {
                        var target = parseFloat(el.getAttribute("data-target"));
                        countUp(el, target, 2000);
                    });
                    countObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });
        countObserver.observe(statsSection);
    }
})();
</script>
@endpush
