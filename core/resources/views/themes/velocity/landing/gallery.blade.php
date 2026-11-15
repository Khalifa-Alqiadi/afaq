@php
    $velocityGalleryTopics = ($TopicBlockContents->module_id ?? 0) > 0
        ? Helper::Topics(
            $TopicBlockContents->module_id,
            $TopicBlockContents->category_ids ?? '',
            $TopicBlockContents->records_count ?? 12,
            0,
            $TopicBlockContents->records_order ?? 0
        )
        : collect();
    $velocityCode = @Helper::currentLanguage()->code;
    $velocityDefaultCode = config('smartend.default_language');
    $velocityGalleryItems = collect();
    foreach ($velocityGalleryTopics as $velocityGalleryTopic) {
        $velocityGalleryTitle = $velocityGalleryTopic->{'title_'.$velocityCode} ?: $velocityGalleryTopic->{'title_'.$velocityDefaultCode};
        if (filled($velocityGalleryTopic->photo_file)) {
            $velocityGalleryItems->push(['file' => $velocityGalleryTopic->photo_file, 'title' => $velocityGalleryTitle, 'url' => Helper::topicURL($velocityGalleryTopic->id, '', $velocityGalleryTopic)]);
        }
    }
    $velocitySectionUrl = ($TopicBlockContents->module_id ?? 0) > 0 ? Helper::sectionURL($TopicBlockContents->module_id) : null;
@endphp
@if($velocityGalleryItems->isNotEmpty())
    @component(theme_view('components.landing-section'), ['block' => $TopicBlock, 'contents' => $TopicBlockContents, 'wide' => true])
        <div class="velocity-gallery-grid">
            @foreach($velocityGalleryItems as $velocityGalleryItem)
                <a class="velocity-gallery-grid__item" href="{{ $velocityGalleryItem['url'] }}" title="{{ $velocityGalleryItem['title'] }}">
                    <img src="{{ route('fileView', ['path' => 'topics/'.$velocityGalleryItem['file']]) }}?w=700&h=700&r=fit" alt="{{ $velocityGalleryItem['title'] }}" width="700" height="700" loading="lazy" decoding="async">
                </a>
            @endforeach
        </div>
        @if($TopicBlock->more_btn_status)
            @slot('actions')
                @include(theme_view('components.more-link'), ['url' => $velocitySectionUrl])
            @endslot
        @endif
    @endcomponent
@endif
