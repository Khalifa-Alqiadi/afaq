<header class="yh-section-head meu-reveal">
    <div class="yh-section-head__copy">
        <span class="yh-section-head__eyebrow">{{ $yhEyebrow ?? __('frontend.latestNews') }}</span>
        @if($TopicBlock->title_status && $yhData->blockTitle)
            <h2>{{ $yhData->blockTitle }}</h2>
        @endif
        @if($TopicBlock->desc_status && $yhData->blockDescription)
            <p>{{ $yhData->blockDescription }}</p>
        @endif
    </div>
    @if($TopicBlock->more_btn_status)
        <a class="yh-section-head__more" href="{{ $yhData->sectionUrl }}">
            <span>{{ __('yemen_home.view_all') }}</span>
            <i class="fa-solid fa-arrow-{{ @Helper::currentLanguage()->right }}-long" aria-hidden="true"></i>
        </a>
    @endif
</header>
