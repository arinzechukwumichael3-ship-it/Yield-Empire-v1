@php
    $app_local = get_default_language_code();
    $default   = App\Constants\LanguageConst::NOT_REMOVABLE;
    $slug      = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::CLIENT_FEEDBACK_SECTION);
    $data      = App\Models\Admin\SiteSections::getData($slug)->first();

    $title   = $data->value->language->$app_local->title ?? $data->value->language->$default->title ?? 'What Our Customers Say';
    $heading = $data->value->language->$app_local->heading ?? $data->value->language->$default->heading ?? 'Join Our Growing Community';
    $items   = $data->value->items ?? [];
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Testimonials Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="enzo-testimonials">
    <div class="container">
        <div class="enzo-section-header text-center" data-aos="fade-up">
            <h2 class="enzo-section-title">{{ $heading }}</h2>
            <p class="enzo-section-sub">{{ $title }}</p>
        </div>
        <div class="enzo-testimonials-grid" data-aos="fade-up" data-aos-delay="100">
            @forelse($items as $item)
                <div class="enzo-testimonial-card">
                    <div class="enzo-testimonial-stars">
                        @for($i = 1; $i <= ($item->star ?? 5); $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                    </div>
                    <p class="enzo-testimonial-text">"{{ $item->language->$app_local->comment ?? $item->language->$default->comment ?? '' }}"</p>
                    <div class="enzo-testimonial-author">
                        @if(!empty($item->image))
                            <img src="{{ get_image($item->image, 'site-section') }}" alt="{{ $item->name ?? '' }}" class="enzo-testimonial-avatar">
                        @else
                            <div class="enzo-testimonial-avatar-placeholder"><i class="las la-user"></i></div>
                        @endif
                        <div>
                            <div class="enzo-testimonial-name">{{ $item->name ?? '' }}</div>
                            <div class="enzo-testimonial-role">{{ $item->designation ?? '' }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="enzo-testimonial-card">
                    <div class="enzo-testimonial-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="enzo-testimonial-text">"YieldEmpire makes managing my business finances effortless. The instant transfers and virtual cards have transformed how I pay suppliers and manage cash flow."</p>
                    <div class="enzo-testimonial-author">
                        <div class="enzo-testimonial-avatar-placeholder"><i class="las la-user"></i></div>
                        <div>
                            <div class="enzo-testimonial-name">{{ __('Michael Chen') }}</div>
                            <div class="enzo-testimonial-role">{{ __('Small Business Owner, San Francisco, CA') }}</div>
                        </div>
                    </div>
                </div>
                <div class="enzo-testimonial-card">
                    <div class="enzo-testimonial-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="enzo-testimonial-text">"I save 10% of every freelance payment into my YieldEmpire goal savings. The interest rates are better than anything I got from traditional banks, and I can withdraw anytime without penalties."</p>
                    <div class="enzo-testimonial-author">
                        <div class="enzo-testimonial-avatar-placeholder"><i class="las la-user"></i></div>
                        <div>
                            <div class="enzo-testimonial-name">{{ __('Jessica Williams') }}</div>
                            <div class="enzo-testimonial-role">{{ __('Freelance Designer, Austin, TX') }}</div>
                        </div>
                    </div>
                </div>
                <div class="enzo-testimonial-card">
                    <div class="enzo-testimonial-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="enzo-testimonial-text">"I was initially skeptical about digital-only banks, but YieldEmpire exceeded all my expectations. Their support team resolved an issue within minutes, and the debit card arrived in 2 days."</p>
                    <div class="enzo-testimonial-author">
                        <div class="enzo-testimonial-avatar-placeholder"><i class="las la-user"></i></div>
                        <div>
                            <div class="enzo-testimonial-name">{{ __('Dr. Robert Kim') }}</div>
                            <div class="enzo-testimonial-role">{{ __('Professor, University of Washington, Seattle') }}</div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Testimonials Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
