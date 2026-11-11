@php
    $aboutLanguage = @Helper::currentLanguage()->code;
    $aboutTitle = $TopicBlockContents->{'title_'.$aboutLanguage} ?? '';
    $aboutDescription = $TopicBlockContents->{'desc_'.$aboutLanguage} ?? '';
    $aboutDetails = $TopicBlockContents->{'details_'.$aboutLanguage} ?? '';
    $aboutPage = \App\Models\Topic::query()->where('seo_url_slug_en', 'afaq-about')->first();
    $aboutPhoto = trim((string) (($Topic->photo_file ?? null) ?: ($aboutPage->photo_file ?? '')));
    $aboutUrl = $aboutPage ? Helper::topicURL($aboutPage->id, '', $aboutPage) : url('afaq-about');
@endphp

<section id="landing-block-{{ $TopicBlock->id }}" class="landing-block afaq-about {{ $TopicBlock->css_classes }}" aria-label="{{ $aboutTitle }}">
    <div class="container afaq-about-layout">
        <div class="afaq-about-copy">
            <div class="afaq-about-eyebrow"><span class="afaq-about-eyebrow-rule" aria-hidden="true"></span><span>{{ __('afaq.about_eyebrow') }}</span><span class="afaq-about-eyebrow-index" aria-hidden="true">01 / AFAQ</span></div>
            @if($TopicBlock->title_status && $aboutTitle)
                <h2 class="afaq-about-title">{{ $aboutTitle }}</h2>
            @endif
            <div class="afaq-about-lead">
                @if($TopicBlock->desc_status && $aboutDescription)
                    <p>{{ $aboutDescription }}</p>
                @endif
                {!! str_replace('"#', '"'.Request::url().'#', $aboutDetails) !!}
            </div>
            <a class="afaq-about-link" href="{{ $aboutUrl }}">
                <span>{{ __('frontend.moreDetails') }}</span>
                <span class="afaq-about-link-icon" aria-hidden="true"><i class="bi bi-arrow-up-{{ $aboutLanguage === 'ar' ? 'left' : 'right' }}"></i></span>
            </a>
            <div class="afaq-about-disciplines" aria-label="{{ __('afaq.about_disciplines') }}">
                <span>{{ __('afaq.solar') }}</span>
                <span>{{ __('afaq.storage') }}</span>
                <span>{{ __('afaq.electrical') }}</span>
                <span>{{ __('afaq.engineering') }}</span>
            </div>
        </div>

        <div class="afaq-about-visual">
            <div class="afaq-about-image-shell">
                @if($aboutPhoto !== '')
                    <img src="{{ route('fileView', ['path' => 'topics/'.$aboutPhoto]) }}" alt="{{ $aboutTitle }}" loading="lazy" decoding="async">
                @else
                    <img src="{{ route('fileView', ['path' => 'banners/afaq-profile-page-2.png']) }}" alt="{{ $aboutTitle }}" loading="lazy" decoding="async">
                @endif
                <span class="afaq-about-image-glow" aria-hidden="true"></span>
            </div>
            <div class="afaq-about-image-caption" aria-hidden="true">
                <span class="afaq-about-image-caption-dot"></span>
                <span>{{ __('afaq.about_motto') }}</span>
            </div>
        </div>
    </div>
</section>

@push('after-scripts')
<script>
    (function () {
        var section = document.getElementById('landing-block-{{ $TopicBlock->id }}');
        if (!section || !('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        section.classList.add('afaq-about--motion');
        var observer = new IntersectionObserver(function (entries) {
            if (!entries[0].isIntersecting) return;
            section.classList.add('afaq-about--in');
            observer.disconnect();
        }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

        requestAnimationFrame(function () { observer.observe(section); });
    })();
</script>
@endpush
