@php
    $authorLanguage = Helper::currentLanguage()->code;
    $authorDefaultLanguage = config('smartend.default_language');
@endphp
@if(Helper::GeneralWebmasterSettings('authors_status') && collect($authors)->isNotEmpty())
    <div class="modern-author-links">

        @foreach($authors as $author)
            <div class="modern-author-links__label">
                <img src="{{route("fileView",["path" =>'authors/'.$author->photo ])}}" alt="{{ $author->{'name_'.$authorLanguage} ?: $author->name_ar }}" loading="lazy" width="70" height="70" class="rounded-circle">
            </div>
            @if($linked ?? true)
                <a href="{{ $authorLanguage === $authorDefaultLanguage ? route('authorProfile', ['author' => $author->id]) : route('authorProfileLocalized', ['lang' => $authorLanguage, 'author' => $author->id]) }}">
                    {{ $author->{'name_'.$authorLanguage} ?: $author->name_ar }}
                </a>
            @else
                <span class="modern-author-links__name">{{ $author->{'name_'.$authorLanguage} ?: $author->name_ar }}</span>
            @endif
        @endforeach
    </div>
@endif
