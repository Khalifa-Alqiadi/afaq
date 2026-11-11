@php
    $language = @Helper::currentLanguage()->code;
    $fallbackLanguage = config('smartend.default_language');
    $isArabic = $language === 'ar';
    $sectors = @$TopicBlockContents->module_id
        ? Helper::Topics($TopicBlockContents->module_id, @$TopicBlockContents->category_ids, @$TopicBlockContents->records_count, 0, @$TopicBlockContents->records_order)
        : [];
    $heading = $TopicBlockContents->{'title_'.$language} ?: ($TopicBlockContents->{'title_'.$fallbackLanguage} ?? '');
    $intro = $TopicBlockContents->{'desc_'.$language} ?: ($TopicBlockContents->{'desc_'.$fallbackLanguage} ?? '');
    $sectionUrl = @$TopicBlockContents->module_id ? Helper::sectionURL($TopicBlockContents->module_id) : '';
    $fallbackIcons = ['fa-solid fa-building', 'fa-solid fa-industry', 'fa-solid fa-landmark', 'fa-solid fa-house', 'fa-solid fa-seedling'];
@endphp
@if(count($sectors))
<section id="landing-block-{{ (int) $TopicBlock->id }}" class="afaq-sectors landing-block {{ $TopicBlock->css_classes }}" dir="{{ $isArabic ? 'rtl' : 'ltr' }}" aria-label="{{ $heading }}">
    <div class="container">
        <header class="afaq-sectors__header">
            <div class="afaq-sectors__heading">
                <span class="afaq-sectors__eyebrow"><span class="afaq-sectors__eyebrow-mark"></span>AFAQ <span>/</span> {{ __('afaq.sectors_eyebrow') }}</span>
                @if($TopicBlock->title_status && $heading !== '')<h2>{{ $heading }}</h2>@endif
            </div>
            @if($TopicBlock->desc_status && $intro !== '')<p>{{ $intro }}</p>@endif
        </header>

        <div class="afaq-sectors__showcase">
            <div class="afaq-sectors__visual">
                <img src="{{ route('fileView', ['path' => 'banners/afaq-profile-page-3.png']) }}" alt="{{ __('afaq.sector_image_alt') }}" loading="lazy">
                <span class="afaq-sectors__visual-label"><span class="afaq-sectors__visual-dot"></span>AFAQ / {{ __('afaq.energy_engineered') }}</span>
                <span class="afaq-sectors__visual-index" aria-hidden="true">07<span> / </span> AFAQ</span>
            </div>
            <div class="afaq-sectors__content">
                <div class="afaq-sectors__content-top"><span>{{ __('afaq.sector_solutions') }}</span><span class="afaq-sectors__count">01 / {{ str_pad(count($sectors), 2, '0', STR_PAD_LEFT) }}</span></div>
                <div class="afaq-sectors__slider swiper">
                    <div class="swiper-wrapper">
                        @foreach($sectors as $sector)
                            @php
                                $sectorTitle = $sector->{'title_'.$language} ?: $sector->{'title_'.$fallbackLanguage};
                                $sectorDetails = $sector->{'details_'.$language} ?: $sector->{'details_'.$fallbackLanguage};
                                $sectorDescription = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $sectorDetails)));
                                $sectorIcon = trim((string) $sector->icon);
                                $iconClass = $sectorIcon !== '' && preg_match('/^[a-zA-Z0-9_\s-]+$/', $sectorIcon) ? $sectorIcon : $fallbackIcons[$loop->index % count($fallbackIcons)];
                            @endphp
                            <div class="swiper-slide">
                                <article class="afaq-sectors__slide">
                                    <span class="afaq-sectors__slide-icon" aria-hidden="true"><i class="{{ $iconClass }}"></i></span>
                                    <span class="afaq-sectors__slide-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} <span>/ {{ str_pad(count($sectors), 2, '0', STR_PAD_LEFT) }}</span></span>
                                    <h3>{{ $sectorTitle }}</h3>
                                    @if($sectorDescription !== '')<p>{{ $sectorDescription }}</p>@endif
                                    <a href="{{ Helper::topicURL($sector->id, '', $sector) }}" class="afaq-sectors__explore">{{ __('afaq.explore_sector') }} <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="afaq-sectors__controls">
                    <div class="afaq-sectors__progress" aria-hidden="true"></div>
                    <div class="afaq-sectors__arrows">
                        <button type="button" class="afaq-sectors__prev" aria-label="{{ __('afaq.previous_sector') }}"><i class="fa-solid fa-arrow-left-long" aria-hidden="true"></i></button>
                        <button type="button" class="afaq-sectors__next" aria-label="{{ __('afaq.next_sector') }}"><i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="afaq-sectors__tabs" role="group" aria-label="{{ __('afaq.choose_sector') }}">
            @foreach($sectors as $sector)
                @php
                    $sectorTitle = $sector->{'title_'.$language} ?: $sector->{'title_'.$fallbackLanguage};
                    $sectorIcon = trim((string) $sector->icon);
                    $iconClass = $sectorIcon !== '' && preg_match('/^[a-zA-Z0-9_\s-]+$/', $sectorIcon) ? $sectorIcon : $fallbackIcons[$loop->index % count($fallbackIcons)];
                @endphp
                <button type="button" class="afaq-sectors__tab{{ $loop->first ? ' is-active' : '' }}" data-slide="{{ $loop->index }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}"><span class="afaq-sectors__tab-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><i class="{{ $iconClass }}" aria-hidden="true"></i><span>{{ $sectorTitle }}</span><i class="fa-solid fa-arrow-up-right-from-square afaq-sectors__tab-arrow" aria-hidden="true"></i></button>
            @endforeach
        </div>
        @if($TopicBlock->more_btn_status && $sectionUrl !== '')<a class="afaq-sectors__more" href="{{ url($sectionUrl) }}">{{ __('frontend.viewMore') }} <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>@endif
    </div>
</section>
@push('after-scripts')
<script>
(function () {
    var section = document.getElementById('landing-block-{{ (int) $TopicBlock->id }}');
    if (!section) return;
    var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!reducedMotion && 'IntersectionObserver' in window) {
        section.classList.add('afaq-sectors--motion');
        var observer = new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting) { section.classList.add('afaq-sectors--visible'); observer.disconnect(); }
        }, { threshold: 0.12 });
        observer.observe(section);
    }
    function initSlider() {
        if (typeof Swiper === 'undefined') return;
        var element = section.querySelector('.afaq-sectors__slider');
        if (!element || element.classList.contains('swiper-initialized')) return;
        var tabs = section.querySelectorAll('.afaq-sectors__tab');
        var count = section.querySelector('.afaq-sectors__count');
        var total = tabs.length;
        var slider = new Swiper(element, {
            slidesPerView: 1,
            speed: reducedMotion ? 0 : 700,
            autoHeight: true,
            watchOverflow: true,
            keyboard: { enabled: true, onlyInViewport: true },
            navigation: { prevEl: section.querySelector('.afaq-sectors__prev'), nextEl: section.querySelector('.afaq-sectors__next') },
            pagination: { el: section.querySelector('.afaq-sectors__progress'), type: 'progressbar' },
            on: { slideChange: function () {
                var index = this.activeIndex;
                count.textContent = String(index + 1).padStart(2, '0') + ' / ' + String(total).padStart(2, '0');
                tabs.forEach(function (tab, i) { tab.classList.toggle('is-active', i === index); tab.setAttribute('aria-pressed', i === index ? 'true' : 'false'); });
            } }
        });
        tabs.forEach(function (tab) { tab.addEventListener('click', function () { slider.slideTo(Number(tab.dataset.slide)); }); });
    }
    if (document.readyState === 'complete') initSlider(); else window.addEventListener('load', initSlider, { once: true });
})();
</script>
@endpush
@endif
