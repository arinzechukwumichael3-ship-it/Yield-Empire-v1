@extends('user.layouts.rise-master')

@push('css')
<style>
/* ── Carousel ── */
.fcar-wrap {
    width: 100%;
    padding: 0 16px;
}

/* Tabs */
.fcar-tabs {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding: 4px 0 12px;
    -webkit-overflow-scrolling: touch;
}
.fcar-tabs::-webkit-scrollbar { display: none; }
.fcar-tab {
    padding: 7px 18px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    cursor: pointer;
    flex-shrink: 0;
    border: 1.5px solid var(--border-color, #334155);
    background: transparent;
    color: var(--text-secondary, #94A3B8);
    transition: all 0.15s ease;
}
.fcar-tab:hover { border-color: var(--accent); color: var(--accent); }
.fcar-tab.active { background: var(--accent); color: #fff; border-color: var(--accent); }

/* Stage — fixed height card */
.fcar-stage { position: relative; }
.fcar-viewport {
    position: relative;
    width: 100%;
    height: 300px;
    border-radius: 16px;
    overflow: hidden;
    background: var(--bg-card, #111827);
    user-select: none;
    -webkit-user-select: none;
    touch-action: pan-y;
}
.fcar-track { position: relative; width: 100%; height: 100%; }

/* Slide — uses <img> with object-fit:cover inside a .fcar-img-wrap */
.fcar-slide {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    opacity: 0;
    transition: opacity 300ms ease, transform 300ms ease;
    will-change: opacity, transform;
}
.fcar-slide.fcar-slide-in { opacity: 1; z-index: 2; }
.fcar-slide-in-left  { opacity: 1; transform: translateX(0); z-index: 2; }
.fcar-slide-out-left { opacity: 0; transform: translateX(-40px); }
.fcar-slide-in-right  { opacity: 1; transform: translateX(0); z-index: 2; }
.fcar-slide-out-right { opacity: 0; transform: translateX(40px); }

.fcar-img-wrap {
    position: absolute;
    inset: 0;
    overflow: hidden;
}
.fcar-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Gradient overlay */
.fcar-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.5) 35%, rgba(0,0,0,0.15) 60%, transparent 100%);
    pointer-events: none;
    z-index: 1;
}

/* Progress bar pinned to bottom edge of the card (overlays image) */
.fcar-prog-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    z-index: 3;
    background: rgba(255,255,255,0.15);
    overflow: hidden;
    border-radius: 0 0 16px 16px;
    pointer-events: none;
}
.fcar-prog-fill {
    display: block;
    height: 100%;
    width: 0%;
    background: var(--accent);
    border-radius: 0 0 0 16px;
    transition: none;
}
.fcar-prog-fill.active {
    transition: width 8s linear;
    width: 100%;
}

/* Content overlay */
.fcar-content {
    position: relative;
    z-index: 2;
    padding: 16px 16px 20px;
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.fcar-cat {
    align-self: flex-start;
    padding: 3px 12px;
    border-radius: 999px;
    background: var(--accent);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.fcar-title {
    margin: 4px 0 0;
    font-size: 18px;
    font-weight: 800;
    color: #fff;
    line-height: 1.25;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    text-shadow: 0 1px 4px rgba(0,0,0,0.3);
}
.fcar-desc {
    margin: 0;
    font-size: 12px;
    color: rgba(255,255,255,0.8);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Pagination dots */
.fcar-dots {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 12px 0 4px;
    max-width: 100%;
    overflow: hidden;
}
.fcar-dot {
    min-width: 6px;
    min-height: 6px;
    max-width: 6px;
    max-height: 6px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    border: none;
    padding: 0;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 1;
}
.fcar-dot:hover { background: rgba(255,255,255,0.5); }
.fcar-dot.active {
    min-width: 10px;
    max-width: 10px;
    min-height: 10px;
    max-height: 10px;
    background: var(--accent);
    flex-shrink: 0;
}
[data-theme="light"] .fcar-dot { background: rgba(0,0,0,0.18); }
[data-theme="light"] .fcar-dot:hover { background: rgba(0,0,0,0.35); }
[data-theme="light"] .fcar-dot.active { background: var(--accent); }

/* Nav buttons */
.fcar-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 6;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(0,0,0,0.4);
    color: #fff;
    border: none;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s ease, background 0.2s ease;
}
.fcar-viewport:hover .fcar-nav { opacity: 1; }
.fcar-nav:hover { background: rgba(0,0,0,0.7); }
.fcar-nav:active { transform: translateY(-50%) scale(0.92); }
.fcar-prev { left: 8px; }
.fcar-next { right: 8px; }

/* Empty state */
.fcar-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-muted, #64748B);
    font-size: 14px;
    text-align: center;
    padding: 24px;
}

/* ── Static feed list ── */
.feed-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 16px 16px 0;
}
.feed-list-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary, #fff);
    margin: 0;
}
.fl-card {
    display: flex;
    gap: 12px;
    padding: 12px;
    border-radius: 14px;
    background: var(--bg-card, #111827);
    border: 1px solid var(--border-color, #1E293B);
    text-decoration: none;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.fl-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}
.fl-thumb {
    width: 80px;
    min-width: 80px;
    height: 60px;
    border-radius: 10px;
    overflow: hidden;
    background: var(--bg-elevated, #1E293B);
    flex-shrink: 0;
}
.fl-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.fl-body {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 2px;
    justify-content: center;
}
.fl-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary, #fff);
    margin: 0;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.fl-excerpt {
    font-size: 12px;
    color: var(--text-secondary, #94A3B8);
    margin: 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.fl-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    margin-top: 2px;
}
.fl-cat {
    padding: 1px 8px;
    border-radius: 6px;
    background: rgba(47,107,255,0.10);
    color: var(--accent);
    font-weight: 600;
}
.fl-date {
    color: var(--text-muted, #64748B);
}

/* Light mode */
[data-theme="light"] .fcar-viewport { background: var(--bg-elevated, #f1f5f9); }
[data-theme="light"] .fcar-tab { border-color: var(--border-color, #e2e8f0); color: var(--text-secondary, #64748b); }
[data-theme="light"] .fcar-tab:hover { border-color: var(--accent); color: var(--accent); }
[data-theme="light"] .fcar-nav { background: rgba(0,0,0,0.25); }
[data-theme="light"] .fcar-nav:hover { background: rgba(0,0,0,0.4); }
[data-theme="light"] .fl-card { background: var(--bg-card); border-color: var(--border-color); }
[data-theme="light"] .fl-thumb { background: var(--bg-elevated); }
</style>
@endpush

@section('content')
<div class="feed-page">
    <div class="feed-header">
        <h1 class="feed-header-title">Feed</h1>
        <p class="feed-header-sub">Latest updates and announcements</p>
    </div>

    <div id="feedCarousel"></div>
    <div id="feedList"></div>
</div>
@endsection

@push("script")
<script>
// YieldEmpire Feed Carousel + List — inline, no deps
(function(){
  var allSlides = {!! json_encode($carouselSlides) !!};
  if (!allSlides || !allSlides.length) {
    document.getElementById('feedCarousel').innerHTML =
      '<div style="display:flex;align-items:center;justify-content:center;height:200px;color:#64748B;font-size:14px;">No articles yet.</div>';
    return;
  }

  /* ─── helpers ─── */
  function esc(s){ var d=document.createElement('div'); d.textContent=s||''; return d.innerHTML; }

  /* ─── DATA ─── */
  // Deep copy so the list never mutates with the carousel
  var masterSlides = JSON.parse(JSON.stringify(allSlides));
  var carouselSlides = masterSlides.slice();    // filtered subset for carousel
  var listSlides = masterSlides.slice(1);       // everything after first, static
  var current = 0;
  var timer = null;
  var paused = false;
  var transitioning = false;
  var activeFilter = 'all';

  var container = document.getElementById('feedCarousel');
  var listEl = document.getElementById('feedList');

  /* ─── render carousel ─── */
  function renderCarousel(){
    var html = '<div class="fcar-wrap">';
    // tabs
    var cats = ['all','Company updates','Portfolio reports','Market updates'];
    html += '<div class="fcar-tabs">';
    cats.forEach(function(c){
      html += '<button class="fcar-tab'+(c===activeFilter?' active':'')+'" data-cat="'+esc(c)+'">'+(c==='all'?'All':esc(c))+'</button>';
    });
    html += '</div>';
    html += '<div class="fcar-stage"><div class="fcar-viewport"><div class="fcar-track">';
    // slides (render all, only current is visible)
    carouselSlides.forEach(function(s,i){
      html += '<div class="fcar-slide'+(i===current?' fcar-slide-in':'')+'" data-idx="'+i+'">';
      html += '<div class="fcar-img-wrap"><img src="'+esc(s.imageUrl)+'" alt="'+esc(s.title)+'" loading="'+(i===0?'eager':'lazy')+'"></div>';
      html += '<div class="fcar-overlay"></div>';
      html += '<div class="fcar-prog-bar"><span class="fcar-prog-fill" id="fcarFill_'+i+'"></span></div>';
      html += '<div class="fcar-content">';
      html += '<span class="fcar-cat">'+esc(s.category)+'</span>';
      html += '<h3 class="fcar-title">'+esc(s.title)+'</h3>';
      html += '<p class="fcar-desc">'+esc(s.description)+'</p>';
      html += '</div></div>';
    });
    html += '</div></div>'; // .fcar-track
    html += '<button class="fcar-nav fcar-prev">‹</button><button class="fcar-nav fcar-next">›</button>';
    html += '</div></div></div>'; // .fcar-viewport .fcar-stage
    // pagination dots (inside .fcar-wrap so same padding)
    html += '<div class="fcar-dots">';
    carouselSlides.forEach(function(s,i){
      html += '<button class="fcar-dot'+(i===current?' active':'')+'" data-dot-idx="'+i+'"></button>';
    });
    html += '</div>';
    html += '</div>'; // .fcar-wrap

    container.innerHTML = html;
    bindCarouselEvents();
    bindDotEvents();
    startProgress();
  }

  /* ─── bind carousel events ─── */
  function bindCarouselEvents(){
    var vp = container.querySelector('.fcar-viewport');

    // tabs
    container.querySelectorAll('.fcar-tab').forEach(function(tab){
      tab.addEventListener('click',function(){ filterBy(tab.dataset.cat); });
    });

    // prev/next
    container.querySelector('.fcar-prev').addEventListener('click',prev);
    container.querySelector('.fcar-next').addEventListener('click',next);

    // touch
    var sx=0, touching=false;
    vp.addEventListener('touchstart',function(e){ sx=e.touches[0].clientX; touching=true; pause(); },{passive:true});
    vp.addEventListener('touchend',function(e){
      if(!touching) return; touching=false;
      var dx=e.changedTouches[0].clientX-sx;
      if(Math.abs(dx)>40){ if(dx<0) next(); else prev(); }
      resume();
    },{passive:true});

    // mouse drag
    var mousedown=false, mx=0;
    vp.addEventListener('mousedown',function(e){ mousedown=true; mx=e.clientX; pause(); });
    document.addEventListener('mouseup',function(e){
      if(!mousedown) return; mousedown=false;
      var dx=e.clientX-mx; resume();
      if(Math.abs(dx)>40){ if(dx<0) next(); else prev(); }
    });
  }

  /* ─── navigation ─── */
  function goTo(idx,dir){
    if(transitioning || !carouselSlides.length) return;
    if(idx<0) idx=carouselSlides.length-1;
    if(idx>=carouselSlides.length) idx=0;
    if(idx===current) return;
    clearTimer();
    dir = dir || (idx>current?1:-1);
    var prevIdx = current;
    current = idx;
    transitionSlide(prevIdx, current, dir);
    updateDots();
    renderList();
    startProgress();
  }

  function next(){ goTo(current+1,1); }
  function prev(){ goTo(current-1,-1); }

  function transitionSlide(from, to, dir){
    var track = container.querySelector('.fcar-track');
    var slides = track.querySelectorAll('.fcar-slide');
    var outDir = dir===1 ? 'left' : 'right';
    slides.forEach(function(sl){
      var idx = parseInt(sl.dataset.idx);
      sl.classList.remove('fcar-slide-in','fcar-slide-in-left','fcar-slide-in-right','fcar-slide-out-left','fcar-slide-out-right');
      if(idx === from) sl.classList.add('fcar-slide-out-'+outDir);
      else if(idx === to) sl.classList.add('fcar-slide-in-'+outDir);
    });
    transitioning = true;
    setTimeout(function(){ transitioning=false; }, 350);
  }

  /* ─── progress bar — keyed per slide, CSS transition ─── */
  function startProgress(){
    // 1. Deactivate all fills (removes transition, snaps to 0%)
    document.querySelectorAll('.fcar-prog-fill').forEach(function(f){
      f.classList.remove('active');
      f.style.width = '0%';
    });
    if(carouselSlides.length<2) return;

    // 2. Force reflow so 0% sticks
    void container.querySelector('.fcar-viewport').offsetHeight;

    // 3. Activate the current slide's fill — fresh DOM element = fresh CSS transition
    var fill = document.getElementById('fcarFill_'+current);
    if(fill){
      fill.style.width = '0%';
      void fill.offsetHeight;        // force reflow
      fill.classList.add('active');  // triggers transition: width 8s linear → 100%
    }

    // 4. Schedule next slide
    clearTimer();
    timer = setTimeout(next, 8000);
  }

  function clearTimer(){
    if(timer){ clearTimeout(timer); timer=null; }
  }

  function pause(){ paused=true; }
  function resume(){ paused=false; }

  /* ─── pagination dots ─── */
  function bindDotEvents(){
    container.querySelectorAll('.fcar-dot').forEach(function(dot){
      dot.addEventListener('click',function(){ jumpTo(parseInt(dot.dataset.dotIdx)); });
    });
  }
  function updateDots(){
    container.querySelectorAll('.fcar-dot').forEach(function(dot,i){
      dot.classList.toggle('active',i===current);
    });
  }

  function jumpTo(idx){
    if(idx===current || transitioning) return;
    clearTimer();
    var dir = idx>current ? 1 : -1;
    var prevIdx = current;
    current = idx;
    transitionSlide(prevIdx, current, dir);
    updateDots();
    renderList();
    startProgress();
  }

  /* ─── filter ─── */
  function filterBy(cat){
    activeFilter = cat;
    carouselSlides = cat==='all'
      ? masterSlides.slice()
      : masterSlides.filter(function(s){ return s.category===cat; });
    current = 0;
    clearTimer();
    renderCarousel();
    // rebuild list from master (exclude first of filtered carousel)
    listSlides = masterSlides.filter(function(s){
      if(!carouselSlides.length) return true;
      return s.id !== carouselSlides[0].id;
    });
    renderList();
  }

  /* ─── static feed list ─── */
  function renderList(){
    var items = listSlides;
    if(!items.length){
      listEl.innerHTML = '';
      return;
    }
    var html = '<div class="feed-list"><h4 class="feed-list-title">More from Feed</h4>';
    items.forEach(function(s){
      html += '<a class="fl-card" href="#">';
      html += '<div class="fl-thumb"><img src="'+esc(s.imageUrl)+'" alt="'+esc(s.title)+'" loading="lazy"></div>';
      html += '<div class="fl-body">';
      html += '<h4 class="fl-title">'+esc(s.title)+'</h4>';
      html += '<p class="fl-excerpt">'+esc(s.description)+'</p>';
      html += '<div class="fl-meta"><span class="fl-cat">'+esc(s.category)+'</span></div>';
      html += '</div></a>';
    });
    html += '</div>';
    listEl.innerHTML = html;
  }

  /* ─── init ─── */
  renderCarousel();
  renderList();
})();
</script>
@endpush