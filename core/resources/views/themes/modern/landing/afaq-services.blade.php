@php
    $language = @Helper::currentLanguage()->code;
    $fallbackLanguage = config('smartend.default_language');
    $isArabic = $language === 'ar';
    $services = @$TopicBlockContents->module_id
        ? Helper::Topics($TopicBlockContents->module_id, @$TopicBlockContents->category_ids, @$TopicBlockContents->records_count, 0, @$TopicBlockContents->records_order)
        : [];
    $sectionUrl = @$TopicBlockContents->module_id ? Helper::sectionURL($TopicBlockContents->module_id) : '';
    $heading = $TopicBlockContents->{'title_'.$language} ?: ($TopicBlockContents->{'title_'.$fallbackLanguage} ?? '');
    $intro = $TopicBlockContents->{'desc_'.$language} ?: ($TopicBlockContents->{'desc_'.$fallbackLanguage} ?? '');
    $fallbackIcons = ['fa-solid fa-compass-drafting', 'fa-solid fa-boxes-stacked', 'fa-solid fa-screwdriver-wrench', 'fa-solid fa-gauge-high'];
@endphp
@if(count($services))
<section id="landing-block-{{ (int) $TopicBlock->id }}" class="afaq-services landing-block {{ $TopicBlock->css_classes }}" aria-labelledby="afaq-services-heading-{{ (int) $TopicBlock->id }}">
    <div class="container">
        <div class="afaq-services__head">
            <div>
                <div class="afaq-services__eyebrow"><span class="afaq-services__eyebrow-line"></span>AFAQ / {{ __('afaq.services_eyebrow') }}</div>
                @if($TopicBlock->title_status && $heading !== '')<h2 id="afaq-services-heading-{{ (int) $TopicBlock->id }}">{{ $heading }}</h2>@endif
            </div>
            @if($TopicBlock->desc_status && $intro !== '')<p class="afaq-services__intro">{{ $intro }}</p>@endif
        </div>
        <div class="afaq-services__slider swiper" dir="{{ $isArabic ? 'rtl' : 'ltr' }}" aria-label="{{ __('afaq.work_stages') }}">
            <div class="swiper-wrapper afaq-services__journey">
            @foreach($services as $service)
                @php
                    $serviceTitle = $service->{'title_'.$language} ?: $service->{'title_'.$fallbackLanguage};
                    $fullDetails = $service->{'details_'.$language} ?: $service->{'details_'.$fallbackLanguage};
                    $plainDetails = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $fullDetails)));
                    $parts = preg_split('/(?:المخرج|المخرجات|Deliverable)\s*[:：]\s*/iu', $plainDetails, 2);
                    $serviceDescription = trim($parts[0] ?? '');
                    $serviceResult = trim($parts[1] ?? '');
                    $serviceIcon = trim((string) $service->icon);
                    $iconClass = $serviceIcon !== '' && preg_match('/^[a-zA-Z0-9_\s-]+$/', $serviceIcon)
                        ? $serviceIcon : $fallbackIcons[$loop->index % count($fallbackIcons)];
                @endphp
                <article class="swiper-slide afaq-services__stage">
                    <a class="afaq-services__card" href="{{ Helper::topicURL($service->id, '', $service) }}" aria-label="{{ $serviceTitle }}">
                        <span class="afaq-services__card-top"><span class="afaq-services__number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="afaq-services__icon" aria-hidden="true"><i class="{{ $iconClass }}"></i></span></span>
                        <span class="afaq-services__card-main"><strong>{{ $serviceTitle }}</strong><span>{{ $serviceDescription }}</span></span>
                        @if($serviceResult !== '')
                            <span class="afaq-services__result"><span class="afaq-services__result-label"><i class="fa-solid fa-check" aria-hidden="true"></i>{{ __('afaq.deliverable') }}</span><span>{{ $serviceResult }}</span></span>
                        @endif
                        <span class="afaq-services__card-footer"><span>{{ __('afaq.explore_stage') }}</span><i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></span>
                    </a>
                </article>
            @endforeach
            </div>
        </div>
        <div class="afaq-services__controls" aria-label="{{ __('afaq.navigate_stages') }}">
            <span class="afaq-services__counter" aria-hidden="true"><span class="afaq-services__current">01</span><span class="afaq-services__counter-divider">/</span>{{ str_pad(count($services), 2, '0', STR_PAD_LEFT) }}</span>
            <div class="afaq-services__progress" aria-hidden="true"></div>
            <div class="afaq-services__navigation">
                <button type="button" class="afaq-services__prev" aria-label="{{ __('afaq.previous_stage') }}"><i class="fa-solid fa-arrow-left-long" aria-hidden="true"></i></button>
                <button type="button" class="afaq-services__next" aria-label="{{ __('afaq.next_stage') }}"><i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></button>
            </div>
        </div>
        @if($TopicBlock->more_btn_status && $sectionUrl !== '')
            <a class="afaq-services__more" href="{{ url($sectionUrl) }}">{{ __('frontend.viewMore') }} <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>
        @endif
    </div>
</section>
@push('after-scripts')
<script>
(function () {
    var section = document.getElementById('landing-block-{{ (int) $TopicBlock->id }}');
    if (!section) return;
    var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!reducedMotion && 'IntersectionObserver' in window) {
        section.classList.add('afaq-services--motion');
        var observer = new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting) {
                section.classList.add('afaq-services--visible');
                observer.disconnect();
            }
        }, { threshold: 0.12 });
        observer.observe(section);
    }
    function initSlider() {
        if (typeof Swiper === 'undefined') return;
        var slider = section.querySelector('.afaq-services__slider');
        if (!slider || slider.classList.contains('swiper-initialized')) return;
        var counter = section.querySelector('.afaq-services__current');
        var swiper = new Swiper(slider, {
            slidesPerView: 1.08,
            spaceBetween: 14,
            speed: reducedMotion ? 0 : 650,
            watchOverflow: true,
            grabCursor: true,
            keyboard: { enabled: true, onlyInViewport: true },
            navigation: { prevEl: section.querySelector('.afaq-services__prev'), nextEl: section.querySelector('.afaq-services__next') },
            pagination: { el: section.querySelector('.afaq-services__progress'), type: 'progressbar' },
            breakpoints: {
                640: { slidesPerView: 1.7, spaceBetween: 16 },
                900: { slidesPerView: 2.2, spaceBetween: 18 },
                1200: { slidesPerView: 3.12, spaceBetween: 20 }
            },
            on: { slideChange: function () { counter.textContent = String(this.activeIndex + 1).padStart(2, '0'); } }
        });
        if (swiper.isLocked) {
            section.classList.add('afaq-services--slider-locked');
        }
    }
    if (document.readyState === 'complete') initSlider();
    else window.addEventListener('load', initSlider, { once: true });
})();
</script>
@endpush
@endif
