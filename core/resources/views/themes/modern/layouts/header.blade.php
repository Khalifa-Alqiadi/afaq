<header id="header" class="afaq-header {{ (Helper::GeneralSiteSettings("style_header"))?"fixed-top":"" }} {{ (Helper::GeneralSiteSettings("style_bg_type"))?"header-no-bg":"header-bg" }}">
    <div class="container afaq-header-inner d-flex align-items-center">
        <a class="logo me-auto" href="{{ Helper::homeURL() }}">
            @php
                $afaqLogoFile = basename((string) Helper::GeneralSiteSettings('style_logo_'.Helper::currentLanguage()->code));
                $afaqLogoAvailable = $afaqLogoFile !== '' && is_file(base_path('../uploads/settings/'.$afaqLogoFile));
            @endphp
            @if($afaqLogoAvailable)
                <img alt="{{ Helper::GeneralSiteSettings('site_title_'.Helper::currentLanguage()->code) ?: 'AFAQ' }}"
                     src="{{ route('fileView', ['path' => 'settings/'.$afaqLogoFile]) }}" class="img-fluid" width="230" height="50">
            @else
                <span class="afaq-wordmark" aria-label="AFAQ">
                    <strong>AFAQ</strong>
                    <small>{{ @Helper::currentLanguage()->code === 'ar' ? 'آفاق للطاقة' : 'ENERGY & SOLUTIONS' }}</small>
                </span>
            @endif
        </a>

        @include(theme_view('layouts.menu'))

        <a class="afaq-header-contact" href="{{ url((Helper::currentLanguage()->code !== config('smartend.default_language') ? Helper::currentLanguage()->code.'/' : '').'afaq-contact') }}">
            <span>{{ @Helper::currentLanguage()->code === 'ar' ? 'لنتحدث عن مشروعك' : 'Discuss your project' }}</span>
            <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
        </a>
    </div>
</header>
