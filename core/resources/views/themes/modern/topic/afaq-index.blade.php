@php
    $afaqLanguage = @Helper::currentLanguage()->code;
    $afaqFallback = config('smartend.default_language');
    $afaqIsArabic = $afaqLanguage === 'ar';
    $afaqSectionTitle = $webmaster_section_title;
    $afaqSectionUrl = $category_title !== '' ? Helper::sectionURL($WebmasterSection->id) : null;
    $afaqCategoryTitle = $category_title;
    $afaqCategoryUrl = $category_title !== '' ? Helper::categoryURL($CurrentCategory->id) : null;
    $afaqCurrentTitle = $category_title !== '' ? $category_title : $webmaster_section_title;
    $afaqHeroImages = ['services' => 'afaq-profile-page-2.png', 'components' => 'afaq-profile-page-1.png', 'systems' => 'afaq-profile-page-2.png', 'sectors' => 'afaq-profile-page-3.png'];
    $afaqIcons = [
        'services' => ['fa-solid fa-compass-drafting', 'fa-solid fa-boxes-stacked', 'fa-solid fa-screwdriver-wrench', 'fa-solid fa-gauge-high'],
        'components' => ['fa-solid fa-solar-panel', 'fa-solid fa-battery-full', 'fa-solid fa-bolt', 'fa-solid fa-layer-group'],
        'systems' => ['fa-solid fa-solar-panel', 'fa-solid fa-battery-full', 'fa-solid fa-arrows-rotate'],
        'sectors' => ['fa-solid fa-building', 'fa-solid fa-industry', 'fa-solid fa-landmark', 'fa-solid fa-house', 'fa-solid fa-seedling'],
    ];
    $afaqLabels = [
        'services' => $afaqIsArabic ? 'من الفكرة إلى التشغيل' : 'FROM IDEA TO OPERATION',
        'components' => $afaqIsArabic ? 'هندسة كل تفصيل' : 'ENGINEERED IN EVERY DETAIL',
        'systems' => $afaqIsArabic ? 'نظام يناسب موقعك' : 'THE RIGHT SYSTEM FOR YOUR SITE',
        'sectors' => $afaqIsArabic ? 'حلول لكل قطاع' : 'SOLUTIONS FOR EVERY SECTOR',
    ];
    $afaqIntro = trim(strip_tags((string) $Category_description));
@endphp
<main class="afaq-inner afaq-inner--{{ $afaqModule }} afaq-index" dir="{{ $afaqIsArabic ? 'rtl' : 'ltr' }}">
    <header class="afaq-inner-hero">
        <div class="container afaq-inner-hero__grid">
            <div class="afaq-inner-hero__copy">
                @include(theme_view('topic.afaq-breadcrumbs'))
                <span class="afaq-inner-hero__eyebrow"><span></span>AFAQ / {{ $afaqLabels[$afaqModule] }}</span>
                <h1>{{ $page_title }}</h1>
                @if($afaqIntro !== '')<p>{{ $afaqIntro }}</p>@endif
                <span class="afaq-inner-hero__count">{{ str_pad($Topics->total(), 2, '0', STR_PAD_LEFT) }} <span>{{ $afaqIsArabic ? 'مجالات للاستكشاف' : 'AREAS TO EXPLORE' }}</span></span>
            </div>
            <div class="afaq-inner-hero__media">
                <img src="{{ $category_image ?: route('fileView', ['path' => 'banners/'.$afaqHeroImages[$afaqModule]]) }}" alt="{{ $page_title }}" loading="eager">
                <span class="afaq-inner-hero__media-label">AFAQ <span>—</span> ENERGY ENGINEERED</span>
            </div>
        </div>
    </header>
    <section class="afaq-index__body" aria-label="{{ $page_title }}">
        <div class="container">
            <div class="afaq-index__intro"><span>{{ $afaqIsArabic ? 'استكشف' : 'EXPLORE' }} / {{ $page_title }}</span><span class="afaq-index__intro-line"></span><span>{{ str_pad($Topics->total(), 2, '0', STR_PAD_LEFT) }}</span></div>
            @if($Topics->total() === 0)
                <div class="afaq-index__empty">{{ __('frontend.noData') }}</div>
            @else
                <div class="afaq-index__grid">
                    @foreach($Topics as $item)
                        @php
                            $itemTitle = $item->{'title_'.$afaqLanguage} ?: $item->{'title_'.$afaqFallback};
                            $itemDetails = $item->{'details_'.$afaqLanguage} ?: $item->{'details_'.$afaqFallback};
                            $itemDescription = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags((string) $itemDetails))), 190);
                            $itemIcon = trim((string) $item->icon);
                            $iconClass = $itemIcon !== '' && preg_match('/^[a-zA-Z0-9_\s-]+$/', $itemIcon)
                                ? $itemIcon : $afaqIcons[$afaqModule][$loop->index % count($afaqIcons[$afaqModule])];
                            $itemImage = trim((string) $item->photo_file);
                            $itemUrl = Helper::topicURL($item->id, '', $item);
                        @endphp
                        <article class="afaq-index-card" style="--afaq-card-order: {{ min($loop->index, 8) }}">
                            <a href="{{ $itemUrl }}" class="afaq-index-card__link" aria-label="{{ $itemTitle }}">
                                <span class="afaq-index-card__top"><span>{{ str_pad($Topics->firstItem() + $loop->index, 2, '0', STR_PAD_LEFT) }}</span><span>AFAQ / {{ strtoupper($afaqModule) }}</span></span>
                                @if($itemImage !== '' && !in_array(strtolower($itemImage), ['default.png', 'noimg.png'], true))
                                    <span class="afaq-index-card__photo"><img src="{{ route('fileView', ['path' => 'topics/'.$itemImage]) }}?w=900&h=600&r=fit" alt="" loading="lazy"></span>
                                @else
                                    <span class="afaq-index-card__symbol" aria-hidden="true"><i class="{{ $iconClass }}"></i></span>
                                @endif
                                <span class="afaq-index-card__content"><strong>{{ $itemTitle }}</strong>@if($itemDescription !== '')<span>{{ $itemDescription }}</span>@endif</span>
                                <span class="afaq-index-card__foot"><span>{{ $afaqIsArabic ? 'اكتشف التفاصيل' : 'EXPLORE DETAILS' }}</span><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></span>
                            </a>
                        </article>
                    @endforeach
                </div>
                @if(method_exists($Topics, 'links'))<div class="afaq-index__pagination">{{ $Topics->links() }}</div>@endif
            @endif
        </div>
    </section>
</main>
