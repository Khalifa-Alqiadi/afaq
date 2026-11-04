@php
    $yhData = new \App\Support\LandingBlockData($TopicBlockContents);
    $authorArticles = $yhData->topics;
    $canLinkAuthors = (bool) Helper::GeneralWebmasterSettings('authors_status');
@endphp

@if($authorArticles->isNotEmpty())
    <section id="landing-block-{{ $TopicBlock->id }}" class="yh-home-section yh-author-articles {{ $TopicBlock->css_classes }}"
             aria-label="{{ $yhData->blockTitle ?: __('yemen_home.author_articles_title') }}">
        <div class="scholars-ornament2"></div>
        <div class="container">
            <header class="yh-author-articles__head meu-reveal">
                <div class="yh-author-articles__heading">
                    <span class="yh-author-articles__eyebrow"><i aria-hidden="true"></i>{{ __('yemen_home.author_articles_eyebrow') }}</span>
                    @if($TopicBlock->title_status)
                        <h2>{{ $yhData->blockTitle ?: __('yemen_home.author_articles_title') }}</h2>
                    @endif
                    @if($TopicBlock->desc_status)
                        <p>{{ $yhData->blockDescription ?: __('yemen_home.author_articles_description') }}</p>
                    @endif
                </div>
                @if($TopicBlock->more_btn_status)
                    <a class="yh-author-articles__all" href="{{ $yhData->sectionUrl }}">
                        {{ __('yemen_home.author_articles_all') }}
                        <i class="fa-solid fa-arrow-{{ @Helper::currentLanguage()->right }}-long" aria-hidden="true"></i>
                    </a>
                @endif
            </header>

            <div class="yh-author-articles__grid meu-stagger">
                @foreach($authorArticles as $item)
                    @php
                        $articleAuthor = $item->authors->firstWhere('status', true);
                        $authorName = $articleAuthor?->{'name_'.$yhData->language}
                            ?: $articleAuthor?->{'name_'.$yhData->fallbackLanguage}
                            ?: __('yemen_home.opinions_editorial_team');
                        $authorUrl = $articleAuthor && $canLinkAuthors
                            ? ($yhData->language === $yhData->fallbackLanguage
                                ? route('authorProfile', ['author' => $articleAuthor->id])
                                : route('authorProfileLocalized', ['lang' => $yhData->language, 'author' => $articleAuthor->id]))
                            : null;
                        $articleUrl = Helper::topicURL($item->id, '', $item);
                        $articleCategory = $item->category($item->id);
                        $categoryName = $articleCategory?->{$yhData->titleField}
                            ?: $articleCategory?->{$yhData->fallbackTitleField}
                            ?: __('yemen_home.opinions_category');
                    @endphp
                    <article class="yh-author-article">
                        <div class="yh-author-article__portrait-wrap">
                            <span class="yh-author-article__ornament" aria-hidden="true"></span>
                            <div class="yh-author-article__portrait">
                                @if($articleAuthor?->photo)
                                    <img src="{{ route('fileView', ['path' => 'authors/'.$articleAuthor->photo]) }}?w=720&h=900&r=fit"
                                         alt="{{ $authorName }}" loading="lazy" decoding="async">
                                @else
                                    <span class="yh-author-article__initial" aria-label="{{ $authorName }}">{{ mb_substr($authorName, 0, 1) }}</span>
                                @endif
                            </div>
                            <span class="yh-author-article__index" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <div class="yh-author-article__copy">
                            <span class="yh-author-article__category">{{ $categoryName }}</span>
                            <h3><a href="{{ $articleUrl }}">{{ $yhData->title($item) }}</a></h3>
                            <div class="yh-author-article__byline">
                                <span class="yh-author-article__byline-mark" aria-hidden="true"><i class="fa-solid fa-feather-pointed"></i></span>
                                <span>
                                    <small>{{ __('yemen_home.author_articles_author') }}</small>
                                    @if($authorUrl)
                                        <a href="{{ $authorUrl }}">{{ $authorName }}</a>
                                    @else
                                        <strong>{{ $authorName }}</strong>
                                    @endif
                                </span>
                            </div>
                            <div class="yh-author-article__foot">
                                <a href="{{ $articleUrl }}" aria-label="{{ __('yemen_home.author_articles_read') }}: {{ $yhData->title($item) }}">
                                    <span>{{ __('yemen_home.author_articles_read') }}</span>
                                    <i class="fa-solid fa-arrow-{{ @Helper::currentLanguage()->right }}-long" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
