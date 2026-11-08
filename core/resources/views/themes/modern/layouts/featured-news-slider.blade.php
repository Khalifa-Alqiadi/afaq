@php
    $featuredCarouselId = 'featuredNewsCarousel-'.$TopicBlock->id;
    $featuredSlideCount = $featuredNews->count();
    $featuredSliderLabel = $TopicBlock->title_status && $yhData->blockTitle
        ? $yhData->blockTitle
        : __('yemen_home.featured_news_slider');
@endphp

<section id="landing-block-{{ $TopicBlock->id }}" class="yh-featured-news afaq-featured-split {{ $TopicBlock->css_classes }}" aria-label="{{ $featuredSliderLabel }}" style="--featured-slide-duration: {{ $featuredSliderSpeed }}ms">
    <div id="{{ $featuredCarouselId }}" class="carousel slide carousel-fade afaq-featured-carousel {{ $featuredSlideCount === 1 ? 'hero-single-slide' : '' }}"
         @if($featuredSliderAutoplay && $featuredSlideCount > 1) data-bs-ride="carousel" data-bs-interval="{{ $featuredSliderSpeed }}" @endif>
        <div class="carousel-inner">
            @foreach($featuredNews as $featuredItem)
                @php
                    $featuredTitle = $yhData->title($featuredItem);
                    $featuredDetails = \Illuminate\Support\Str::limit(trim(strip_tags((string) $yhData->details($featuredItem))), 170);
                    $featuredUrl = Helper::topicURL($featuredItem->id, '', $featuredItem);
                @endphp
                <article class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <div class="afaq-featured-media" aria-hidden="true">
                        @if($featuredItem->photo_file)
                            <img src="{{ route('fileView', ['path' => 'topics/'.$featuredItem->photo_file]) }}" alt="" loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async">
                        @else
                            <div class="afaq-featured-media-fallback"><i class="fa-regular fa-newspaper"></i></div>
                        @endif
                        <span class="afaq-featured-media-line"></span>
                    </div>
                    <div class="afaq-featured-panel">
                        <div class="afaq-featured-panel-inner">
                            <div class="afaq-featured-eyebrow">
                                <span class="afaq-featured-mark" aria-hidden="true"></span>
                                <span>{{ $featuredSliderLabel }}</span>
                                <span class="afaq-featured-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} <small>/ {{ str_pad((string) $featuredSlideCount, 2, '0', STR_PAD_LEFT) }}</small></span>
                            </div>
                            <h2 class="afaq-featured-title"><a href="{{ $featuredUrl }}">{{ $featuredTitle }}</a></h2>
                            @if($featuredDetails)
                                <p class="afaq-featured-description">{{ $featuredDetails }}</p>
                            @endif
                            <a href="{{ $featuredUrl }}" class="afaq-featured-link">
                                <span>{{ __('yemen_home.news_read') }}</span>
                                <i class="bi bi-arrow-{{ @Helper::currentLanguage()->right }}" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        @if($featuredSlideCount > 1)
            <div class="afaq-featured-navigation">
                <div class="carousel-indicators">
                    @foreach($featuredNews as $featuredItem)
                        <button type="button" data-bs-target="#{{ $featuredCarouselId }}" data-bs-slide-to="{{ $loop->index }}"
                                class="{{ $loop->first ? 'active' : '' }}" @if($loop->first) aria-current="true" @endif
                                aria-label="{{ __('yemen_home.news_featured') }} {{ $loop->iteration }}"></button>
                    @endforeach
                </div>
                <div class="afaq-featured-arrows">
                    <button class="carousel-control-prev" type="button" data-bs-target="#{{ $featuredCarouselId }}" data-bs-slide="prev" aria-label="{{ __('yemen_home.previous') }}">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#{{ $featuredCarouselId }}" data-bs-slide="next" aria-label="{{ __('yemen_home.next') }}">
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</section>
