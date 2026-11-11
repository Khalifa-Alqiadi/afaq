@if(@$BannersSettingsId > 0)
    <?php
    $SliderBanners = Helper::BannersList($BannersSettingsId);
    ?>
    @if(count($SliderBanners) > 0)
                @foreach($SliderBanners->slice(0, 1) as $SliderBanner)
                    <?php
                    try {
                        $SliderBanner_type = $SliderBanner->webmasterBanner->type;
                    } catch (Exception $e) {
                        $SliderBanner_type = 0;
                    }
                    ?>
                @endforeach
        <section id="hero" class="modern-hero {{ $SliderBanner_type == 1 ? 'afaq-campaign-hero' : '' }}" aria-label="{{ strip_tags(Helper::GeneralSiteSettings('site_title_' . @Helper::currentLanguage()->code)) }}">
            <div class="hero-container hero-slider">
                <?php
                $title_var = "title_".@Helper::currentLanguage()->code;
                $title_var2 = "title_".config('smartend.default_language');
                $details_var = "details_".@Helper::currentLanguage()->code;
                $details_var2 = "details_".config('smartend.default_language');
                $file_var = "file_".@Helper::currentLanguage()->code;
                $file_var2 = "file_".config('smartend.default_language');
                $link_var = "link_".@Helper::currentLanguage()->code;
                $link_var2 = "link_".config('smartend.default_language');
                ?>
                @if($SliderBanner_type == 0)
                    {{-- Text/Code Banners --}}
                    <div class="text-center">
                        @foreach($SliderBanners as $SliderBanner)
                            <?php
                            if ($SliderBanner->$details_var != "") {
                                $BDetails = $SliderBanner->$details_var;
                            } else {
                                $BDetails = $SliderBanner->$details_var2;
                            }
                            ?>
                            @if($BDetails != "")
                                <div>{!! $BDetails !!}</div>
                            @endif
                        @endforeach
                    </div>
                @elseif($SliderBanner_type == 1)
                    {{-- Photo Slider Banners --}}
                    @php($sliderCount = count($SliderBanners))
                    <div id="heroCarousel"
                         class="modern-hero-carousel carousel carousel-fade slide {{ $sliderCount === 1 ? 'is-static' : '' }}"
                         data-bs-ride="{{ $sliderCount > 1 ? 'carousel' : 'false' }}"
                         data-bs-interval="6500"
                         data-bs-pause="hover"
                         data-bs-touch="true"
                         aria-roledescription="carousel"
                         aria-label="{{ strip_tags(Helper::GeneralSiteSettings('site_title_' . @Helper::currentLanguage()->code)) }}">
                        <div class="carousel-inner">
                            @php($i = 0)
                            @foreach($SliderBanners as $SliderBanner)
                                <?php
                                if ($SliderBanner->$title_var != "") {
                                    $BTitle = $SliderBanner->$title_var;
                                } else {
                                    $BTitle = $SliderBanner->$title_var2;
                                }
                                $BTitle = trim(str_replace(['\\n', '\\r', "\r", "\n"], ' ', (string) $BTitle));
                                if ($SliderBanner->$details_var != "") {
                                    $BDetails = $SliderBanner->$details_var;
                                } else {
                                    $BDetails = $SliderBanner->$details_var2;
                                }
                                $BDetails = trim(str_replace(['\\n', '\\r'], "\n", (string) $BDetails));
                                if ($SliderBanner->$file_var != "") {
                                    $BFile = $SliderBanner->$file_var;
                                } else {
                                    $BFile = $SliderBanner->$file_var2;
                                }
                                if ($SliderBanner->$link_var != "") {
                                    $BLink = $SliderBanner->$link_var;
                                } else {
                                    $BLink = $SliderBanner->$link_var2;
                                }
                                $isDemoBanner = preg_match('/^(بنر رقم|banner\s*#)/iu', $BTitle) === 1;
                                if ($isDemoBanner) {
                                    $BTitle = __('afaq.demo_slider_title');
                                    $BDetails = __('afaq.demo_slider_details');
                                    $BLink = url((@Helper::currentLanguage()->code !== config('smartend.default_language') ? @Helper::currentLanguage()->code.'/' : '').'afaq-contact');
                                }
                                $BFile = basename((string) $BFile);
                                if ($BFile === '' || $BFile === 'noimg.png' || !is_file(base_path('../uploads/banners/'.$BFile))) {
                                    $BFile = 'afaq-profile-page-'.(($i % 3) + 1).'.png';
                                }
                                $slideNumber = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
                                $slideTotal = str_pad((string) $sliderCount, 2, '0', STR_PAD_LEFT);
                                ?>
                                <div class="carousel-item {{ $i === 0 ? 'active' : '' }}"
                                     role="group"
                                     aria-roledescription="slide"
                                     aria-label="{{ $i + 1 }} / {{ $sliderCount }}">
                                    <div class="modern-hero-media" aria-hidden="true">
                                        <img class="modern-hero-image"
                                             src="{{ route("fileView",["path" => 'banners/'.$BFile]) }}"
                                             alt=""
                                             width="1920"
                                             height="1080"
                                             loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                                             @if($i === 0) fetchpriority="high" @endif>
                                    </div>
                                    <div class="modern-hero-shade" aria-hidden="true"></div>
                                    <div class="afaq-hero-ribbon" aria-hidden="true"><span></span></div>
                                    <div class="afaq-hero-service-tag" aria-hidden="true">
                                        @if(@Helper::currentLanguage()->code === 'ar')
                                            <span>الطاقة الشمسية</span><span>التخزين</span><span>الكهرباء</span><span>الهندسة</span>
                                        @else
                                            <span>SOLAR</span><span>STORAGE</span><span>ELECTRICAL</span><span>ENGINEERING</span>
                                        @endif
                                    </div>

                                    @if($BTitle != "" || $BDetails != "" || $BLink != "")
                                        <div class="modern-hero-container container">
                                            <div class="modern-hero-content">
                                                <div class="afaq-hero-eyebrow"><span></span>{{ __('afaq.brand_description') }}</div>
                                                @if($BTitle != "")
                                                    <h2 class="modern-hero-title">{{ $BTitle }}</h2>
                                                @endif

                                                @if($BDetails != "")
                                                    <p class="modern-hero-description">{!! nl2br(e($BDetails)) !!}</p>
                                                @endif

                                                @if($BLink != "")
                                                    <a href="{{ $BLink }}" class="modern-hero-cta slider-link">
                                                        <span>{{ __('frontend.moreDetails') }}</span>
                                                        <span class="modern-hero-cta-icon" aria-hidden="true">
                                                            <i class="bi bi-arrow-up-right"></i>
                                                        </span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    <div class="modern-hero-image-note" aria-hidden="true">
                                        <span class="modern-hero-image-note-index">{{ $slideNumber }} <span>/ {{ $slideTotal }}</span></span>
                                        <span class="modern-hero-image-note-rule"></span>
                                    </div>
                                </div>
                                @php($i++)
                            @endforeach
                        </div>

                        {{-- Kept for backwards compatibility with the legacy theme initializer. --}}
                        <ol id="hero-carousel-indicators" hidden aria-hidden="true"></ol>

                        <div class="modern-hero-rail container">
                            <div class="modern-hero-indicators carousel-indicators">
                                @php($indicatorIndex = 0)
                                @foreach($SliderBanners as $SliderBanner)
                                    <button type="button"
                                            data-bs-target="#heroCarousel"
                                            data-bs-slide-to="{{ $indicatorIndex }}"
                                            class="modern-hero-indicator {{ $indicatorIndex === 0 ? 'active' : '' }}"
                                            @if($indicatorIndex === 0) aria-current="true" @endif
                                            aria-label="{{ ($SliderBanner->$title_var ?: $SliderBanner->$title_var2) ?: ($indicatorIndex + 1).' / '.$sliderCount }}">
                                        <span>{{ str_pad((string) ($indicatorIndex + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                    </button>
                                    @php($indicatorIndex++)
                                @endforeach
                            </div>

                            @if($sliderCount > 1)
                                <div class="modern-hero-actions">
                                    <button type="button"
                                            class="modern-hero-nav modern-hero-prev"
                                            data-bs-target="#heroCarousel"
                                            data-bs-slide="prev"
                                            aria-label="{{ html_entity_decode(__('pagination.previous')) }}">
                                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                                    </button>
                                    <button type="button"
                                            class="modern-hero-nav modern-hero-next"
                                            data-bs-target="#heroCarousel"
                                            data-bs-slide="next"
                                            aria-label="{{ html_entity_decode(__('pagination.next')) }}">
                                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <span class="modern-hero-vertical-label" aria-hidden="true">AFAQ / {{ __('afaq.energy_engineered') }}</span>
                    </div>
                @else
                    {{-- Video Banners --}}
                    <div class="text-center">
                        @foreach($SliderBanners as $SliderBanner)
                            <?php
                            if ($SliderBanner->$title_var != "") {
                                $BTitle = $SliderBanner->$title_var;
                            } else {
                                $BTitle = $SliderBanner->$title_var2;
                            }
                            if ($SliderBanner->$details_var != "") {
                                $BDetails = $SliderBanner->$details_var;
                            } else {
                                $BDetails = $SliderBanner->$details_var2;
                            }
                            if ($SliderBanner->$file_var != "") {
                                $BFile = $SliderBanner->$file_var;
                            } else {
                                $BFile = $SliderBanner->$file_var2;
                            }
                            ?>
                            @if($SliderBanner->youtube_link != "")
                                @if($SliderBanner->video_type == 1)
                                    <?php
                                    $Youtube_id = Helper::Get_youtube_video_id($SliderBanner->youtube_link);
                                    ?>
                                    @if($Youtube_id != "")
                                        {{-- Youtube Video --}}
                                        <iframe width="100%" height="500" frameborder="0" allowfullscreen
                                                src="https://www.youtube.com/embed/{{ $Youtube_id }}?autoplay=1&mute=1"
                                                allow="autoplay">
                                        </iframe>
                                    @endif
                                @elseif($SliderBanner->video_type == 2)
                                    <?php
                                    $Vimeo_id = Helper::Get_vimeo_video_id($SliderBanner->youtube_link);
                                    ?>
                                    @if($Vimeo_id != "")
                                        {{-- Vimeo Video --}}
                                        <iframe width="100%" height="500" frameborder="0" allowfullscreen
                                                src="https://player.vimeo.com/video/{{ $Vimeo_id }}?title=0&amp;byline=0">
                                        </iframe>
                                    @endif
                                @endif
                            @endif
                            @if($SliderBanner->video_type == 0)
                                @if($BFile != "")
                                    {{-- Direct Video --}}
                                    <video width="100%" height="500" controls autoplay>
                                        <source src="{{ route("fileView",["path" => 'banners/'.$BFile]) }}"
                                                type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            @endif
                            @if($BDetails != "")
                                <div>{!! $BDetails !!}</div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    @push('after-styles')
        @if(Helper::GeneralSiteSettings("style_header") && Helper::GeneralSiteSettings("style_bg_type"))
            <style>
                .fixed-top-margin {
                    margin-top: 0 !important;
                }

                .header-bg, .header-bg a {
                    color: #444444;
                }

                @media (min-width: 968px) {
                    .header-no-bg, .header-no-bg a, .topbar-no-bg, .topbar-no-bg a {
                        color: #fff;
                    }

                    .header-no-bg .navbar a, .topbar-no-bg .header-dropdown .btn {
                        color: #fff;
                    }

                    .dropdown-item {
                        color: #212529 !important;
                    }

                    .header-scrolled .navbar a, .header-scrolled .header-dropdown .btn {
                        color: #444444;
                    }
                }

                .topbar-no-bg {
                    box-shadow: 0 0 1px rgba(255, 255, 255, 0.5) !important;
                }
            </style>
        @endif
    @endpush
@endif
