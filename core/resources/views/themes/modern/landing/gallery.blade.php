@php
    $language = @Helper::currentLanguage()->code;
    $fallbackLanguage = config('smartend.default_language');
    $isArabic = $language === 'ar';
    $topics = @$TopicBlockContents->module_id
        ? Helper::Topics($TopicBlockContents->module_id, @$TopicBlockContents->category_ids, @$TopicBlockContents->records_count, 0, @$TopicBlockContents->records_order)
        : [];
    $sectionUrl = @$TopicBlockContents->module_id ? Helper::sectionURL($TopicBlockContents->module_id) : '';
    $limit = max(1, (int) (@$TopicBlockContents->records_count ?: 6));
    $heading = $TopicBlockContents->{'title_'.$language} ?: ($TopicBlockContents->{'title_'.$fallbackLanguage} ?? '');
    $intro = $TopicBlockContents->{'desc_'.$language} ?: ($TopicBlockContents->{'desc_'.$fallbackLanguage} ?? '');
    $backgroundFile = $TopicBlock->image_status ? ($TopicBlockContents->{'bg_'.$language} ?? '') : '';
    $backgroundStyle = trim(($TopicBlock->bg_color ? 'background-color: '.$TopicBlock->bg_color.';' : '')
        .($backgroundFile ? 'background-image: url('.route('fileView', ['path' => 'topics/'.$backgroundFile]).'); background-size: cover; background-position: center;' : ''));
    $disk = \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'));
    $items = [];
    foreach ($topics as $topic) {
        $title = $topic->{'title_'.$language} ?: $topic->{'title_'.$fallbackLanguage};
        $details = $topic->{'details_'.$language} ?: $topic->{'details_'.$fallbackLanguage};
        $description = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags((string) $details))), 140);
        $isProjectImage = static fn ($file) => $file && !in_array(strtolower(basename((string) $file)), ['default.png', 'noimg.png', 'nav-bg.png', 'nofile.png'], true);
        $photos = $topic->photos->pluck('file')->filter($isProjectImage)->values();
        if ($photos->isEmpty() && $isProjectImage($topic->photo_file)) $photos = collect([$topic->photo_file]);
        if ($photos->isEmpty()) $photos = collect([null]);
        foreach ($photos as $file) {
            $hasImage = $file && $disk->exists('topics/'.$file);
            $items[] = [
                'title' => $title,
                'description' => $description,
                'image' => $hasImage ? route('fileView', ['path' => 'topics/'.$file]) : null,
                'link' => Helper::topicURL($topic->id, '', $topic),
            ];
            if (count($items) >= $limit) break 2;
        }
    }
@endphp

@if(count($items))
<section id="landing-block-{{ (int) $TopicBlock->id }}" class="gallery afaq-gallery landing-block {{ $TopicBlock->css_classes }} {{ $TopicBlock->divider_status ? 'divider' : '' }}" @if($backgroundStyle !== '') style="{{ $backgroundStyle }}" @endif>
    <div class="container">
        <div class="afaq-gallery__heading">
            <div>
                <div class="afaq-gallery__eyebrow"><span class="afaq-gallery__eyebrow-line"></span>AFAQ / {{ __('afaq.gallery_eyebrow') }}</div>
                @if($TopicBlock->title_status && $heading !== '')<h2>{{ $heading }}</h2>@endif
            </div>
            @if($TopicBlock->desc_status && $intro !== '')<p>{{ $intro }}</p>@endif
        </div>
        <div class="afaq-gallery__stage">
            <div class="afaq-gallery__slider swiper" dir="{{ $isArabic ? 'rtl' : 'ltr' }}" aria-label="{{ __('afaq.project_gallery') }}">
                <div class="swiper-wrapper">
                    @foreach($items as $item)
                        <div class="swiper-slide afaq-gallery__slide">
                            <a class="afaq-gallery__media {{ $item['image'] ? 'galelry-lightbox' : 'afaq-gallery__media--empty' }}" href="{{ $item['image'] ?: $item['link'] }}" @if($item['image']) data-gallery="afaq-gallery-{{ (int) $TopicBlock->id }}" @endif title="{{ $item['title'] }}" aria-label="{{ $item['image'] ? __('afaq.view_image', ['title' => $item['title']]) : $item['title'] }}">
                                @if($item['image'])
                                    <img src="{{ $item['image'] }}?w=1600&h=950&r=fit" alt="{{ $item['title'] }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}" width="1600" height="950">
                                @else
                                    <span class="afaq-gallery__geometry" aria-hidden="true"><span></span><span></span><span></span></span>
                                @endif
                            </a>
                            <div class="afaq-gallery__veil" aria-hidden="true"></div>
                            <div class="afaq-gallery__slide-top"><span>AFAQ <span class="afaq-gallery__slash">/</span> {{ __('afaq.selected_work') }}</span><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ str_pad(count($items), 2, '0', STR_PAD_LEFT) }}</span></div>
                            <div class="afaq-gallery__slide-copy">
                                <span class="afaq-gallery__slide-kicker">{{ __('afaq.project') }} {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3>{{ $item['title'] }}</h3>
                                <a class="afaq-gallery__project-link" href="{{ $item['link'] }}">{{ __('afaq.project_details') }} <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="afaq-gallery__side-label" aria-hidden="true">AFAQ <span>—</span> {{ __('afaq.energy_engineered') }}</div>
        </div>
        <div class="afaq-gallery__toolbar">
            <div class="afaq-gallery__navigation">
                <button type="button" class="afaq-gallery__prev" aria-label="{{ __('afaq.previous_project') }}"><i class="fa-solid fa-arrow-left-long" aria-hidden="true"></i></button>
                <button type="button" class="afaq-gallery__next" aria-label="{{ __('afaq.next_project') }}"><i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></button>
            </div>
            <div class="afaq-gallery__progress" aria-hidden="true"></div>
            <span class="afaq-gallery__counter" aria-hidden="true"><strong class="afaq-gallery__current">01</strong><span>/</span>{{ str_pad(count($items), 2, '0', STR_PAD_LEFT) }}</span>
            @if($TopicBlock->more_btn_status && $sectionUrl !== '')<a href="{{ url($sectionUrl) }}" class="afaq-gallery__more">{{ __('frontend.viewMore') }} <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>@endif
        </div>
        @if(count($items) > 1)
            <div class="afaq-gallery__filmstrip" aria-label="{{ __('afaq.choose_project') }}">
                @foreach($items as $item)
                    <button type="button" class="afaq-gallery__film-item {{ $loop->first ? 'is-active' : '' }}" data-gallery-index="{{ $loop->index }}" aria-label="{{ $item['title'] }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                        <span class="afaq-gallery__film-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="afaq-gallery__film-title">{{ $item['title'] }}</span>
                        <span class="afaq-gallery__film-line" aria-hidden="true"></span>
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</section>
@push('after-scripts')
<script>
(function () {
    var section = document.getElementById('landing-block-{{ (int) $TopicBlock->id }}');
    if (!section) return;
    var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    function initGallery() {
        if (typeof Swiper === 'undefined') return;
        var element = section.querySelector('.afaq-gallery__slider');
        if (!element || element.classList.contains('swiper-initialized')) return;
        var tabs = Array.prototype.slice.call(section.querySelectorAll('.afaq-gallery__film-item'));
        var current = section.querySelector('.afaq-gallery__current');
        var slider = new Swiper(element, {
            slidesPerView: 1,
            speed: reducedMotion ? 0 : 850,
            effect: reducedMotion ? 'slide' : 'fade',
            fadeEffect: { crossFade: true },
            rewind: true,
            keyboard: { enabled: true, onlyInViewport: true },
            navigation: { prevEl: section.querySelector('.afaq-gallery__prev'), nextEl: section.querySelector('.afaq-gallery__next') },
            pagination: { el: section.querySelector('.afaq-gallery__progress'), type: 'progressbar' },
            on: { slideChange: function () {
                current.textContent = String(this.activeIndex + 1).padStart(2, '0');
                var activeIndex = this.activeIndex;
                tabs.forEach(function (tab, index) {
                    var active = index === activeIndex;
                    tab.classList.toggle('is-active', active);
                    tab.setAttribute('aria-pressed', active ? 'true' : 'false');
                });
            } }
        });
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () { slider.slideTo(Number(tab.dataset.galleryIndex)); });
        });
        if (slider.slides.length <= 1) section.classList.add('afaq-gallery--single');
    }
    if (document.readyState === 'complete') initGallery();
    else window.addEventListener('load', initGallery, { once: true });
})();
</script>
@endpush
@endif
