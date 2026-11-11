@php
    $afaqLanguage = @Helper::currentLanguage()->code;
    $afaqIsArabic = $afaqLanguage === 'ar';
    $afaqSectionUrl = Helper::sectionURL($WebmasterSection->id);
    $breadcrumbItems = [['label' => $webmaster_section_title, 'url' => $afaqSectionUrl]];
    if ($category_title !== '') $breadcrumbItems[] = ['label' => $category_title, 'url' => Helper::categoryURL($CurrentCategory->id)];
    $breadcrumbItems[] = ['label' => $title];
    $afaqIcon = trim((string) $Topic->icon);
    $afaqIcon = $afaqIcon !== '' && preg_match('/^[a-zA-Z0-9_\s-]+$/', $afaqIcon) ? $afaqIcon : [
        'services' => 'fa-solid fa-compass-drafting',
        'components' => 'fa-solid fa-solar-panel',
        'systems' => 'fa-solid fa-bolt',
        'sectors' => 'fa-solid fa-building',
        'pages' => 'fa-solid fa-sun',
    ][$afaqModule];
    $afaqBody = (string) ($Topic->{$details} ?? '');
    $afaqPlain = trim(preg_replace('/\s+/u', ' ', strip_tags($afaqBody)));
    $afaqLead = mb_strlen($afaqPlain) > 240 ? \Illuminate\Support\Str::limit($afaqPlain, 185) : '';
    $afaqPhoto = trim((string) $Topic->photo_file);
    $afaqHasPhoto = $afaqPhoto !== '' && !in_array(strtolower($afaqPhoto), ['default.png', 'noimg.png'], true);
@endphp
<main class="afaq-inner afaq-inner--{{ $afaqModule }} afaq-detail" dir="{{ $afaqIsArabic ? 'rtl' : 'ltr' }}">
    <header class="afaq-detail__hero">
        <div class="container">
            @include(theme_view('layouts.breadcrumbs'), ['breadcrumbEmbedded' => true])
            <div class="afaq-detail__hero-grid">
                <div class="afaq-detail__hero-copy">
                    <div class="afaq-detail__overline"><span class="afaq-detail__overline-line"></span>AFAQ / {{ $afaqModule === 'pages' ? __('afaq.about') : strtoupper($afaqModule) }}</div>
                    <h1>{{ $title }}</h1>
                    @if($afaqLead !== '')<p>{{ $afaqLead }}</p>@endif
                    <span class="afaq-detail__hero-rule" aria-hidden="true"></span>
                </div>
            </div>
        </div>
    </header>
    @if(!$Topic->status)
        <div class="container"><div class="alert alert-warning mt-4" role="alert">{{ __('backend.pageIsNotPublished') }}</div></div>
    @endif
    <section class="afaq-detail__main">
        <div class="container afaq-detail__layout">
            <aside class="afaq-detail__aside">
                <span class="afaq-detail__aside-label">{{ __('afaq.in_this_page') }}</span>
                <strong>{{ $title }}</strong>
                @if($afaqSectionUrl)<a href="{{ $afaqSectionUrl }}"><i class="fa-solid fa-arrow-left-long" aria-hidden="true"></i>{{ __('afaq.all_section', ['section' => $webmaster_section_title]) }}</a>@endif
                <span class="afaq-detail__aside-brand">AFAQ / {{ __('afaq.energy_engineered') }}</span>
            </aside>
            <article class="afaq-detail__article">
                <div class="afaq-detail__article-kicker"><span>{{ __('afaq.details') }}</span><span class="afaq-detail__article-line"></span></div>
                @if($WebmasterSection->source_status && filled($Topic->source))<p class="afaq-detail__source">{{ __('backend.source') }}: {{ $Topic->source }}</p>@endif
                @include(theme_view('topic.fields'), ['cols' => 6, 'Fields' => @$WebmasterSection->customFields->where('in_page', true)->where('type', '!=', 9)])
                <div class="afaq-detail__prose">@if(@$WebmasterSection->editor_status){!! str_replace('"#', '"'.Request::url().'#', $afaqBody) !!}@else{!! nl2br($afaqBody) !!}@endif</div>
                @if($custom_body_code !== '')<div class="afaq-detail__prose">{!! Blade::render($custom_body_code) !!}</div>@endif
                @if($attach_file !== '')<a class="afaq-detail__download" href="{{ route('fileView', ['path' => 'topics/'.$Topic->attach_file]) }}" target="_blank" rel="noopener"><i class="fa-solid fa-download" aria-hidden="true"></i>{{ __('frontend.downloadAttach') }}</a>@endif
                @if($Topic->photos->isNotEmpty())
                    <div class="afaq-detail__gallery">
                        @foreach($Topic->photos as $photo)
                            <a class="galelry-lightbox" href="{{ route('fileView', ['path' => 'topics/'.$photo->file]) }}" title="{{ $photo->title }}"><img src="{{ route('fileView', ['path' => 'topics/'.$photo->file]) }}?w=900&h=650&r=fit" alt="{{ $photo->title ?: $title }}" loading="lazy"></a>
                        @endforeach
                    </div>
                @endif
                @include(theme_view('topic.files'))
                @include(theme_view('topic.maps'))
                @include(theme_view('topic.tags'))
                @include(theme_view('topic.share'))
                @if($afaqModule !== 'pages')@include(theme_view('topic.related'))@endif
            </article>
        </div>
    </section>
</main>
