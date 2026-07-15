@extends('user.layouts.rise-master')

@section('content')
<div class="rf-detail-header">
    <a href="{{ setRoute('user.rise.feed') }}" class="rf-detail-back">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
        </svg>
        Back
    </a>
</div>

<div class="rf-detail-body">
    @php
        $gradient = $article->data->thumb_gradient ?? 'linear-gradient(135deg, #2563EB, #1D4ED8)';
        $icon = $article->data->thumb_icon ?? 'default';
    @endphp

    <div class="rf-detail-image">
        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:{{ $gradient }};">
            @if($icon === 'card')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="4" width="22" height="16" rx="2"/>
                <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
            @elseif($icon === 'chart')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            @elseif($icon === 'shield')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            @elseif($icon === 'globe')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="2" y1="12" x2="22" y2="12"/>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
            </svg>
            @else
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            @endif
        </div>
    </div>

    <h1 class="rf-detail-title">{{ $article->title ?? 'Untitled' }}</h1>

    <div class="rf-detail-meta">
        <span class="rf-detail-category">{{ $article->category->name ?? 'General' }}</span>
        <span class="rf-detail-date">{{ \Carbon\Carbon::parse($article->created_at)->format('jS M, Y') }}</span>
    </div>

    <div class="rf-detail-body-text">
        {{ $article->data->description ?? 'No content available.' }}
    </div>

    <button class="rf-detail-share" onclick="shareArticle()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:6px;">
            <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
        </svg>
        Share Article
    </button>
</div>

@push("script")
<script>
function shareArticle() {
    if (navigator.share) {
        navigator.share({
            title: '{{ addslashes($article->title ?? "EnzoBank Article") }}',
            text: '{{ addslashes(Str::limit(strip_tags($article->data->description ?? ""), 120)) }}',
            url: window.location.href,
        });
    } else {
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert('Link copied to clipboard!');
        });
    }
}
</script>
@endpush
@endsection
