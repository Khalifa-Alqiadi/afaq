@php
    $hasGallery = $Topic->photos->isNotEmpty();
    $hasMainPhoto = filled($Topic->photo_file);
    $mediaType = $WebmasterSection->type == 2 && filled($Topic->video_file) ? 'video'
        : ($WebmasterSection->type == 3 && filled($Topic->audio_file) ? 'audio'
        : ($hasGallery ? 'gallery' : 'photo'));
@endphp

@if($mediaType === 'video')
    <section class="afaq-topic-media afaq-topic-media--video" aria-label="{{ __('afaq.video') }}">
        <div class="afaq-topic-media__heading"><span>{{ __('afaq.media') }}</span><h2>{{ __('afaq.video') }}</h2></div>
        <div class="afaq-topic-media__video">
            @if($Topic->video_type == 1)
                @php
                    $videoId = Helper::Get_youtube_video_id($Topic->video_file);
                @endphp
                @if($videoId !== '')
                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}" title="{{ $title }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
                @endif
            @elseif($Topic->video_type == 2)
                @php
                    $videoId = Helper::Get_vimeo_video_id($Topic->video_file);
                @endphp
                @if($videoId !== '')
                    <iframe src="https://player.vimeo.com/video/{{ $videoId }}?title=0&amp;byline=0" title="{{ $title }}" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                @endif
            @elseif($Topic->video_type == 3)
                {!! $Topic->video_file !!}
            @else
                <video controls preload="metadata" @if($hasMainPhoto) poster="{{ route('fileView', ['path' => 'topics/'.$Topic->photo_file]) }}" @endif>
                    <source src="{{ route('fileView', ['path' => 'topics/'.$Topic->video_file]) }}" type="video/mp4">
                    {{ __('afaq.video_unsupported') }}
                </video>
            @endif
        </div>
    </section>
@elseif($mediaType === 'audio')
    <section class="afaq-topic-media afaq-topic-media--audio" aria-label="{{ __('afaq.audio') }}">
        <div class="afaq-topic-media__heading"><span>{{ __('afaq.media') }}</span><h2>{{ __('afaq.audio') }}</h2></div>
        <div class="afaq-topic-media__audio">
            @if($hasMainPhoto)<img src="{{ route('fileView', ['path' => 'topics/'.$Topic->photo_file]) }}?w=1100&h=650&r=fit" alt="{{ Helper::topicPhotoAlt($Topic) }}" loading="lazy">@endif
            <div class="afaq-topic-media__audio-player">
                <i class="fa-solid fa-headphones" aria-hidden="true"></i>
                <strong>{{ $title }}</strong>
                @if($Topic->video_type == 3)
                    <div class="audio-embed">{!! $Topic->audio_file !!}</div>
                @else
                    <div class="audio-player"><audio controls preload="none" crossorigin><source src="{{ route('fileView', ['path' => 'topics/'.$Topic->audio_file]) }}" type="audio/mpeg"></audio></div>
                @endif
            </div>
        </div>
    </section>
@elseif($mediaType === 'gallery')
    <section class="afaq-topic-media afaq-topic-media--gallery" aria-label="{{ __('afaq.photos') }}">
        <div class="afaq-topic-media__heading"><span>{{ __('afaq.media') }}</span><h2>{{ __('afaq.photos') }}</h2><small>{{ $Topic->photos->count() + ($hasMainPhoto ? 1 : 0) }}</small></div>
        <div class="afaq-topic-media__gallery">
            @if($hasMainPhoto)
                <figure class="afaq-topic-media__photo afaq-topic-media__photo--main">
                    <a class="galelry-lightbox" data-gallery="topic-{{ $Topic->id }}" href="{{ route('fileView', ['path' => 'topics/'.$Topic->photo_file]) }}" title="{{ Helper::topicPhotoAlt($Topic) }}">
                        <img src="{{ route('fileView', ['path' => 'topics/'.$Topic->photo_file]) }}?w=1400&h=950&r=fit" alt="{{ Helper::topicPhotoAlt($Topic) }}" loading="lazy">
                        <span class="afaq-topic-media__expand" aria-hidden="true"><i class="fa-solid fa-expand"></i></span>
                    </a>
                    @if(trim((string) ($Topic->{'photo_alt_'.@Helper::currentLanguage()->code} ?? '')) !== '')<figcaption>{{ Helper::topicPhotoAlt($Topic) }}</figcaption>@endif
                </figure>
            @endif
            @foreach($Topic->photos as $photo)
                <figure class="afaq-topic-media__photo{{ !$hasMainPhoto && $loop->first ? ' afaq-topic-media__photo--main' : '' }}">
                    <a class="galelry-lightbox" data-gallery="topic-{{ $Topic->id }}" href="{{ route('fileView', ['path' => 'topics/'.$photo->file]) }}" title="{{ $photo->title ?: $title }}">
                        <img src="{{ route('fileView', ['path' => 'topics/'.$photo->file]) }}?w=900&h=700&r=fit" alt="{{ $photo->title ?: $title }}" loading="lazy">
                        <span class="afaq-topic-media__expand" aria-hidden="true"><i class="fa-solid fa-expand"></i></span>
                    </a>
                    @if(trim((string) $photo->title) !== '')<figcaption>{{ $photo->title }}</figcaption>@endif
                </figure>
            @endforeach
        </div>
    </section>
@else
    @php
        $singlePhoto = $hasMainPhoto ? $Topic->photo_file : null;
        if (!$singlePhoto) {
            foreach ($Topic->webmasterSection->customFields->where('type', 8) as $photoField) {
                if ($photoField->lang_code !== 'all' && $photoField->lang_code !== @Helper::currentLanguage()->code) continue;
                $fieldPhoto = $Topic->fields->first(fn ($field) => $field->field_id == $photoField->id && filled($field->field_value));
                if ($fieldPhoto) { $singlePhoto = $fieldPhoto->field_value; break; }
            }
        }
    @endphp
    @if($singlePhoto)
        <figure class="afaq-topic-media afaq-topic-media--single">
            <a class="galelry-lightbox" data-gallery="topic-{{ $Topic->id }}" href="{{ route('fileView', ['path' => 'topics/'.$singlePhoto]) }}" title="{{ $title }}">
                <img src="{{ route('fileView', ['path' => 'topics/'.$singlePhoto]) }}?w=1400&h=950&r=fit" alt="{{ $hasMainPhoto ? Helper::topicPhotoAlt($Topic) : $title }}" loading="lazy">
                <span class="afaq-topic-media__expand" aria-hidden="true"><i class="fa-solid fa-expand"></i></span>
            </a>
            @if($hasMainPhoto && trim((string) ($Topic->{'photo_alt_'.@Helper::currentLanguage()->code} ?? '')) !== '')<figcaption>{{ Helper::topicPhotoAlt($Topic) }}</figcaption>@endif
        </figure>
    @endif
@endif
