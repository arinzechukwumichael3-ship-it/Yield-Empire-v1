@extends('user.layouts.rise-master')

@push('css')
<style>
.feed-article-card {
    animation: feedFadeUp 0.5s ease-out forwards;
    opacity: 0;
}
@keyframes feedFadeUp {
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@section('content')
<div class="feed-page">
    <div class="feed-header">
        <h1 class="feed-header-title">Feed</h1>
        <p class="feed-header-sub">Latest updates and announcements</p>
    </div>

    <!-- Filter Tabs -->
    <div class="feed-filter-scroll">
        <button class="feed-filter active" data-filter="all">All</button>
        <button class="feed-filter" data-filter="Company updates">Company updates</button>
        <button class="feed-filter" data-filter="Portfolio reports">Portfolio reports</button>
        <button class="feed-filter" data-filter="Market updates">Market updates</button>
    </div>

    <!-- Articles -->
    <div class="feed-articles" id="feedArticles">
        @forelse($articles as $article)
        <a href="{{ setRoute('user.rise.feed.detail', $article->slug) }}"
           class="feed-article-card"
           data-category="{{ $article->category->name ?? 'General' }}"
           style="animation-delay:{{ $loop->index * 0.06 }}s;">
            @php
                $gradient = $article->data->thumb_gradient ?? 'linear-gradient(135deg, #2563EB, #1D4ED8)';
                $icon = $article->data->thumb_icon ?? 'default';
                $thumbUrl = $article->data->thumb_url ?? null;
            @endphp
            <div class="feed-article-thumb" style="background: {{ $gradient }};{{ $thumbUrl ? ' background-image:url('.$thumbUrl.');background-size:cover;background-position:center;' : '' }}">
                @if(!$thumbUrl)
                    @if($icon === 'card')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    @elseif($icon === 'chart')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    @elseif($icon === 'shield')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    @elseif($icon === 'globe')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    @endif
                @endif
            </div>
            <div class="feed-article-body">
                <h3 class="feed-article-title">{{ $article->title ?? 'Untitled' }}</h3>
                <p class="feed-article-excerpt">{{ Str::limit(strip_tags($article->data->description ?? ''), 120) }}</p>
                <div class="feed-article-meta">
                    <span class="feed-article-cat">{{ $article->category->name ?? 'General' }}</span>
                    <span class="feed-article-date">{{ \Carbon\Carbon::parse($article->created_at)->format('jS M, Y') }}</span>
                </div>
            </div>
        </a>
        @empty
        <div class="feed-empty">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
            </svg>
            <span class="feed-empty-title">No articles yet</span>
            <span class="feed-empty-sub">Check back soon for updates</span>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push("script")
<script>
document.querySelectorAll('.feed-filter').forEach(function(f) {
    f.addEventListener('click', function() {
        document.querySelectorAll('.feed-filter').forEach(function(ff) { ff.classList.remove('active'); });
        this.classList.add('active');
        var filter = this.getAttribute('data-filter');
        document.querySelectorAll('.feed-article-card').forEach(function(item) {
            if (filter === 'all' || item.getAttribute('data-category') === filter) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
