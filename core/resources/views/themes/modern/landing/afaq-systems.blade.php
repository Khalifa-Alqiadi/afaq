@php
    $language = @Helper::currentLanguage()->code;
    $fallbackLanguage = config('smartend.default_language');
    $systems = @$TopicBlockContents->module_id
        ? Helper::Topics($TopicBlockContents->module_id, @$TopicBlockContents->category_ids, @$TopicBlockContents->records_count, 0, @$TopicBlockContents->records_order)
        : [];
    $sectionUrl = @$TopicBlockContents->module_id ? Helper::sectionURL($TopicBlockContents->module_id) : '';
    $heading = $TopicBlockContents->{'title_'.$language} ?? $TopicBlockContents->{'title_'.$fallbackLanguage} ?? '';
    $intro = $TopicBlockContents->{'desc_'.$language} ?? $TopicBlockContents->{'desc_'.$fallbackLanguage} ?? '';
    $isArabic = $language === 'ar';
@endphp
@if(count($systems))
<section id="landing-block-{{ (int) $TopicBlock->id }}" class="afaq-systems landing-block {{ $TopicBlock->css_classes }}" aria-labelledby="afaq-systems-heading-{{ (int) $TopicBlock->id }}">
    <div class="container">
        <div class="afaq-systems__head">
            <div class="afaq-systems__heading">
                <div class="afaq-systems__eyebrow"><span class="afaq-systems__eyebrow-line"></span> AFAQ / {{ __('afaq.systems_eyebrow') }} <span class="afaq-systems__eyebrow-number">04</span></div>
                @if($TopicBlock->title_status && $heading !== '')<h2 id="afaq-systems-heading-{{ (int) $TopicBlock->id }}">{{ $heading }}</h2>@endif
            </div>
            @if($TopicBlock->desc_status && $intro !== '')<p class="afaq-systems__intro">{{ $intro }}</p>@endif
        </div>
        <div class="afaq-systems__layout">
            <div class="afaq-systems__diagram" aria-hidden="true">
                <div class="afaq-systems__diagram-top"><span>AFAQ / {{ __('afaq.energy_engineered') }}</span><span>01 — 03</span></div>
                <div class="afaq-systems__diagram-art">
                    <span class="afaq-systems__sun"></span>
                    <span class="afaq-systems__sun-rays"></span>
                    <span class="afaq-systems__diagram-orbit"></span>
                    <svg class="afaq-systems__solar" viewBox="0 0 330 170" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M42 128 94 32h193l-52 96H42Z" fill="#C7FB5F" stroke="#012A33" stroke-width="3"/><path d="m66 84 195 1M91 36l-49 92m99-92-49 92m99-92-49 92m99-92-49 92" stroke="#01434F" stroke-width="2" opacity=".75"/><path d="M64 129v19m152-19v19" stroke="#012A33" stroke-width="4" stroke-linecap="round"/></svg>
                    <span class="afaq-systems__diagram-flow afaq-systems__diagram-flow--a"></span>
                    <span class="afaq-systems__diagram-flow afaq-systems__diagram-flow--b"></span>
                    <span class="afaq-systems__node afaq-systems__node--grid"><i class="fa-solid fa-tower-broadcast"></i></span>
                    <span class="afaq-systems__node afaq-systems__node--battery"><i class="fa-solid fa-battery-full"></i></span>
                    <span class="afaq-systems__node afaq-systems__node--home"><i class="fa-solid fa-house"></i></span>
                </div>
                <div class="afaq-systems__diagram-bottom"><span>{{ __('afaq.sun_to_need') }}</span><span class="afaq-systems__diagram-spark">✳</span></div>
            </div>
            <div class="afaq-systems__list">
                @foreach($systems as $system)
                    @php
                        $systemTitle = $system->{'title_'.$language} ?: $system->{'title_'.$fallbackLanguage};
                        $systemDetails = $system->{'details_'.$language} ?: $system->{'details_'.$fallbackLanguage};
                        $systemIcon = trim((string) $system->icon);
                        $defaultIcons = ['fa-solid fa-solar-panel', 'fa-solid fa-battery-full', 'fa-solid fa-arrows-rotate'];
                    @endphp
                    <a class="afaq-systems__item" data-system-index="{{ $loop->iteration }}" href="{{ Helper::topicURL($system->id, '', $system) }}" aria-label="{{ $systemTitle }}">
                        <span class="afaq-systems__item-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="afaq-systems__item-icon" aria-hidden="true"><i class="{{ $systemIcon !== '' && preg_match('/^[a-zA-Z0-9_\s-]+$/', $systemIcon) ? $systemIcon : $defaultIcons[($loop->index) % count($defaultIcons)] }}"></i></span>
                        <span class="afaq-systems__item-copy"><strong>{{ $systemTitle }}</strong><span>{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags((string) $systemDetails))), 155) }}</span></span>
                        <span class="afaq-systems__item-arrow" aria-hidden="true"><i class="fa-solid fa-arrow-right-long"></i></span>
                    </a>
                @endforeach
                @if($TopicBlock->more_btn_status && $sectionUrl !== '')
                    <a class="afaq-systems__more" href="{{ url($sectionUrl) }}">{{ __('frontend.viewMore') }} <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>
                @endif
            </div>
        </div>
    </div>
</section>
@push('after-scripts')
<script>
(function () {
    var section = document.getElementById('landing-block-{{ (int) $TopicBlock->id }}');
    if (!section) return;
    var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!reducedMotion && 'IntersectionObserver' in window) {
        section.classList.add('afaq-systems--motion');
        var observer = new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting) {
                section.classList.add('afaq-systems--visible');
                observer.disconnect();
            }
        }, { threshold: 0.13 });
        observer.observe(section);
    }
    var diagram = section.querySelector('.afaq-systems__diagram');
    section.querySelectorAll('.afaq-systems__item').forEach(function (item) {
        item.addEventListener('mouseenter', function () { diagram.dataset.activeSystem = item.dataset.systemIndex; });
        item.addEventListener('focus', function () { diagram.dataset.activeSystem = item.dataset.systemIndex; });
        item.addEventListener('mouseleave', function () { delete diagram.dataset.activeSystem; });
        item.addEventListener('blur', function () { delete diagram.dataset.activeSystem; });
    });
})();
</script>
@endpush
@endif
