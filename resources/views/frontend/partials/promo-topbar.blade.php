@php
    $app_local = get_default_language_code();
    $default   = App\Constants\LanguageConst::NOT_REMOVABLE;
    $banner      = App\Models\Admin\SiteSections::getData('banner-section')->first();
    $lang        = optional(optional($banner)->value)->language ?? null;
    $banner_text = optional($lang)->$app_local->heading ?? optional($lang)->$default->heading ?? '';
    $banner_cta  = optional($lang)->$app_local->button_name ?? optional($lang)->$default->button_name ?? 'Get Started';
@endphp
@if($banner && trim(strip_tags($banner_text)) !== '')
<div class="hp-topbar" id="hpTopbar">
    <span class="hp-topbar-text">{{ __(strip_tags($banner_text)) }}</span>
    <a href="{{ setRoute('user.register') }}" class="hp-topbar-cta">{{ __($banner_cta) }}</a>
    <button type="button" class="hp-topbar-close" id="hpTopbarClose" aria-label="Dismiss announcement">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
</div>
<script>
(function(){
    var bar = document.getElementById('hpTopbar');
    var close = document.getElementById('hpTopbarClose');
    if (!bar || !close) return;
    try {
        if (localStorage.getItem('hp_topbar_hidden') === '1') { bar.style.display = 'none'; return; }
    } catch (e) {}
    close.addEventListener('click', function(){
        bar.style.height = bar.offsetHeight + 'px';
        bar.style.overflow = 'hidden';
        bar.style.transition = 'height .3s ease, opacity .3s ease';
        bar.style.opacity = '0';
        setTimeout(function(){ bar.style.display = 'none'; }, 300);
        try { localStorage.setItem('hp_topbar_hidden', '1'); } catch (e) {}
    });
})();
</script>
@endif
