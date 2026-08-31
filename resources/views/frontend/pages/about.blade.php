@extends('frontend.layouts.master')
@php
    $app_local  = get_default_language_code();
    $default    = App\Constants\LanguageConst::NOT_REMOVABLE;
    $slug       = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::ABOUT_US_SECTION);
    $data       = App\Models\Admin\SiteSections::getData($slug)->first();
@endphp

@section('content')
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    About Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

<section class="about-section pt-60">
    <div class="container">
        <div class="row mb-30-none">
            <div class="col-xl-6 col-lg-12 mb-30">
                <div class="about-img">
                    <img src="{{ get_image($data->value->image ?? '' ,'site-section') }}" alt="img">
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 mb-30">
               <div class="about-area">
                    <div class="about-section-tag">
                        <h2 class="title"><i class="fas fa-info-circle text--base mb-20"></i> {{ $data->value->language->$app_local->title ?? $data->value->language->$default->title ?? '' }}</h2>
                    </div>
                    <div class="about-section-title pb-20">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="title">{{ $data->value->language->$app_local->heading ?? $data->value->language->$default->heading ?? '' }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="about-details">
                        <p>{{ $data->value->language->$app_local->sub_heading ?? $data->value->language->$default->sub_heading ?? '' }}</p>
                    </div>

                    <div class="about-operator" style="margin-top:28px;padding:20px;border:1px solid rgba(120,130,160,0.25);border-radius:12px;background:rgba(120,130,160,0.06);">
                        <h4 style="font-size:16px;margin-bottom:10px;color:#0a1f5c;">Operator &amp; Legal Information</h4>
                        <p style="font-size:14px;line-height:1.7;color:#33415c;margin-bottom:8px;">
                            {{ $basic_settings->site_name ?? 'YieldEmpire' }} is a financial technology platform operated at yieldempire.org.
                            We are a financial technology provider, <strong>not a licensed bank</strong> and not a deposit-taking institution.
                            We do not hold or custody customer funds as a regulated bank would, and customer balances are not insured by the FDIC, FCA, or any government deposit-insurance scheme.
                        </p>
                        <p style="font-size:14px;line-height:1.7;color:#33415c;margin-bottom:8px;">
                            To avoid confusion: YieldEmpire is independent and <strong>not affiliated</strong> with any government grant program (including YEIDEP), or with unrelated real-estate or other businesses that may share a similar name.
                        </p>
                        <p style="font-size:14px;line-height:1.7;color:#33415c;margin-bottom:0;">
                            Registered entity, jurisdiction, and licensing details (where applicable) are maintained by our operator and available on request via our
                            <a href="{{ setRoute('frontend.contact') }}">Contact Support</a> channel. Investments carry risk, including possible loss of principal; projected returns are not guaranteed.
                        </p>
                    </div>
               </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.sections.faq')

@include('frontend.sections.testimonial')

@endsection