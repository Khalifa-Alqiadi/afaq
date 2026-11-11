@php
    $componentIcon = trim((string) $Topic->icon);
    $componentDetails = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $Topic->$details)));
@endphp
<article class="afaq-component-card">
    <a href="{{ $topic_link_url }}" class="afaq-component-card__link" aria-label="{{ $title }}">
        <div class="afaq-component-card__topline">
            <span class="afaq-component-card__index">{{ str_pad($component_number, 2, '0', STR_PAD_LEFT) }}</span>
            <span class="afaq-component-card__line" aria-hidden="true"></span>
            <span class="afaq-component-card__brand">AFAQ / SYSTEMS</span>
        </div>
        <div class="afaq-component-card__icon" aria-hidden="true">
            @if($componentIcon !== '' && preg_match('/^[a-zA-Z0-9_\s-]+$/', $componentIcon))
                <i class="{{ $componentIcon }}"></i>
            @else
                <span class="afaq-component-card__icon-placeholder"><span></span><span></span><span></span></span>
            @endif
        </div>
        <div class="afaq-component-card__body">
            <h3 class="afaq-component-card__title">{{ $title }}</h3>
            @if($componentDetails !== '')
                <p class="afaq-component-card__description">{{ \Illuminate\Support\Str::limit($componentDetails, 120) }}</p>
            @endif
        </div>
        <div class="afaq-component-card__footer">
            <span>{{ @Helper::currentLanguage()->code === 'ar' ? 'استكشف المكوّن' : 'EXPLORE COMPONENT' }}</span>
            <span class="afaq-component-card__arrow" aria-hidden="true"><i class="fa-solid fa-arrow-right-long"></i></span>
        </div>
    </a>
</article>
