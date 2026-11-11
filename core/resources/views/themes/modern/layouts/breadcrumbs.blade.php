@php
    $breadcrumbItems = $breadcrumbItems ?? [];
    $breadcrumbHeading = $breadcrumbHeading ?? null;
    $breadcrumbEmbedded = $breadcrumbEmbedded ?? false;
@endphp
<section class="site-breadcrumbs{{ $breadcrumbEmbedded ? ' site-breadcrumbs--embedded' : '' }}">
    <div class="{{ $breadcrumbEmbedded ? 'site-breadcrumbs__inner' : 'container site-breadcrumbs__inner' }}">
        @if($breadcrumbHeading)<h1>{{ $breadcrumbHeading }}</h1>@endif
        <nav aria-label="Breadcrumb">
            <ol>
                <li><a href="{{ Helper::homeURL() }}">{{ __('backend.home') }}</a></li>
                @foreach($breadcrumbItems as $breadcrumbItem)
                    <li @if(empty($breadcrumbItem['url'])) aria-current="page" @endif>
                        @if(!empty($breadcrumbItem['url']))<a href="{{ $breadcrumbItem['url'] }}">{{ $breadcrumbItem['label'] }}</a>@else{{ $breadcrumbItem['label'] }}@endif
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>
</section>
