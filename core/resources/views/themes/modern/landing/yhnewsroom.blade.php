@php
    $yhData = new \App\Support\LandingBlockData($TopicBlockContents);
@endphp

@if($yhData->topics->isNotEmpty())
    @php
        $lead = $yhData->topics->first();
        $leadCategory = $lead->category($lead->id);
        $leadCategoryTitle = optional($leadCategory)->{$yhData->titleField}
            ?: optional($leadCategory)->{$yhData->fallbackTitleField}
            ?: __('yemen_home.news_desk');
        $recentTopics = $yhData->topics->slice(1, 3);
        $moreTopics = $yhData->topics->slice(4, 3);
    @endphp

    <section id="landing-block-{{ $TopicBlock->id }}" class="yh-home-section yh-news-ledger {{ $TopicBlock->css_classes }}" aria-label="{{ $yhData->blockTitle ?: __('yemen_home.news_title') }}">
        <div class="scholars-ornament2"></div>
        <div class="container">
            <header class="yh-news-ledger__head">
                <div class="yh-news-ledger__head-copy">
                    <span class="yh-news-ledger__eyebrow"><span aria-hidden="true"></span>{{ __('yemen_home.news_eyebrow') }}</span>
                    @if($TopicBlock->title_status)
                        <h2 id="yh-news-title-{{ $TopicBlock->id }}">{{ $yhData->blockTitle ?: __('yemen_home.news_title') }}</h2>
                    @endif
                    @if($TopicBlock->desc_status && $yhData->blockDescription)
                        <p>{{ $yhData->blockDescription }}</p>
                    @endif
                </div>
                @if($TopicBlock->more_btn_status)
                    <a class="yh-news-ledger__all" href="{{ $yhData->sectionUrl }}">
                        {{ __('yemen_home.news_all') }}
                        <i class="fa-solid fa-arrow-{{ @Helper::currentLanguage()->right }}-long" aria-hidden="true"></i>
                    </a>
                @endif
            </header>

            <div class="yh-news-ledger__grid">
                <article class="yh-news-ledger__feature">
                    <a class="yh-news-ledger__feature-media" href="{{ Helper::topicURL($lead->id, '', $lead) }}" aria-label="{{ $yhData->title($lead) }}">
                        @if($lead->photo_file)
                            <img src="{{ route('fileView', ['path' => 'topics/'.$lead->photo_file]) }}" alt="{{ $yhData->title($lead) }}" loading="lazy" decoding="async">
                        @else
                            <span class="yh-news-ledger__image-fallback" aria-hidden="true"><i class="fa-regular fa-newspaper"></i></span>
                        @endif
                    </a>
                    <div class="yh-news-ledger__feature-copy">
                        <h3><a href="{{ Helper::topicURL($lead->id, '', $lead) }}">{{ $yhData->title($lead) }}</a></h3>
                        @if($yhData->details($lead))
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($yhData->details($lead)), 165) }}</p>
                        @endif
                        <a class="yh-news-ledger__read" href="{{ Helper::topicURL($lead->id, '', $lead) }}">
                            {{ __('yemen_home.news_read') }}
                            <i class="fa-solid fa-arrow-{{ @Helper::currentLanguage()->right }}-long" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>

                @if($recentTopics->isNotEmpty())
                    <aside class="yh-news-ledger__latest" aria-label="{{ __('yemen_home.news_latest') }}">
                        <div class="yh-news-ledger__latest-head">
                            <h3>{{ __('yemen_home.news_latest') }}</h3>
                        </div>
                        <div class="yh-news-ledger__latest-list">
                            @foreach($recentTopics as $item)
                                @php
                                    $category = $item->category($item->id);
                                    $categoryTitle = optional($category)->{$yhData->titleField}
                                        ?: optional($category)->{$yhData->fallbackTitleField}
                                        ?: __('yemen_home.news_desk');
                                @endphp
                                <a class="yh-news-ledger__latest-item" href="{{ Helper::topicURL($item->id, '', $item) }}">
                                    <span class="yh-news-ledger__latest-image">
                                        @if($item->photo_file)
                                            <img src="{{ route('fileView', ['path' => 'topics/'.$item->photo_file]) }}" alt="" loading="lazy" decoding="async">
                                        @else
                                            <span class="yh-news-ledger__image-fallback" aria-hidden="true"><i class="fa-regular fa-image"></i></span>
                                        @endif
                                    </span>
                                    <span class="yh-news-ledger__latest-copy">
                                        <strong>{{ $yhData->title($item) }}</strong>
                                    </span>
                                    <i class="fa-solid fa-arrow-{{ @Helper::currentLanguage()->right }}-long" aria-hidden="true"></i>
                                </a>
                            @endforeach
                        </div>
                    </aside>
                @endif
            </div>

            @if($moreTopics->isNotEmpty())
                <div class="yh-news-ledger__more" aria-label="{{ __('yemen_home.news_latest') }}">
                    @foreach($moreTopics as $item)
                        @php
                            $category = $item->category($item->id);
                            $categoryTitle = optional($category)->{$yhData->titleField}
                                ?: optional($category)->{$yhData->fallbackTitleField}
                                ?: __('yemen_home.news_desk');
                        @endphp
                        <a href="{{ Helper::topicURL($item->id, '', $item) }}">
                            <span class="yh-news-ledger__more-copy"><strong>{{ $yhData->title($item) }}</strong></span>
                            <i class="fa-solid fa-arrow-{{ @Helper::currentLanguage()->right }}-long" aria-hidden="true"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif
