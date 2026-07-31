<div class="fcar-wrap">
    <div class="fcar-tabs">
        <button class="fcar-tab active" data-cat="all">All</button>
        @foreach($categories as $cat)
            <button class="fcar-tab" data-cat="{{ $cat }}">{{ $cat }}</button>
        @endforeach
    </div>
    <div class="fcar-stage">
        <div class="fcar-progress">
            @foreach($articles as $i => $article)
            <span class="fcar-prog-seg {{ $i === 0 ? 'fcar-prog-active' : 'fcar-prog-pending' }}" data-idx="{{ $i }}">
                <span class="fcar-prog-fill" style="width:{{ $i === 0 ? '0%' : '0%' }}"></span>
            </span>
            @endforeach
        </div>
        <div class="fcar-viewport">
            <div class="fcar-track">
                @forelse($articles as $article)
                <div class="fcar-slide {{ $loop->first ? 'fcar-slide-in' : '' }}"
                     style="background-image:url('{{ $article['imageUrl'] ?? $article->data->thumb_url ?? '' }}')"
                     data-id="{{ $article['id'] ?? $article->slug ?? '' }}">
                    <div class="fcar-overlay"></div>
                    <div class="fcar-content">
                        <span class="fcar-cat">{{ $article['category'] ?? $article->category->name ?? 'General' }}</span>
                        <h3 class="fcar-title">{{ $article['title'] ?? $article->title ?? '' }}</h3>
                        <p class="fcar-desc">{{ Str::limit(strip_tags($article['description'] ?? $article->data->description ?? ''), 120) }}</p>
                    </div>
                </div>
                @empty
                <div class="fcar-empty">No articles yet — check back soon.</div>
                @endforelse
            </div>
            <button class="fcar-nav fcar-prev" aria-label="Previous">‹</button>
            <button class="fcar-nav fcar-next" aria-label="Next">›</button>
        </div>
    </div>
</div>