<div class="bottom-article mb-3">
    <div class="d-flex justify-content-between">
        <div class="share-title text-muted d-flex"><i class="fa-solid fa-square-share-nodes"></i>  {{ __('frontend.share') }}</div>
        <ul class="social-network share d-flex">
            <li><a href="{{ Helper::SocialShare("facebook", @$PageTitle)}}"
                   class="facebook"
                   data-bs-toggle="tooltip"
                   title="{{ __('afaq.facebook') }}" aria-label="{{ __('afaq.facebook') }}" target="_blank" rel="noopener noreferrer"><i
                        class="fa-brands fa-facebook"></i></a>
            </li>
            <li><a href="{{ Helper::SocialShare("whatsapp", @$PageTitle)}}"
                   class="twitter"
                   data-bs-toggle="tooltip" title="{{ __('afaq.whatsapp') }}" aria-label="{{ __('afaq.whatsapp') }}"
                   style="background-color:#067f4b "
                   target="_blank" rel="noopener noreferrer"><i
                        class="fa-brands fa-whatsapp"></i></a></li>
            <li><a href="{{ Helper::SocialShare("twitter", @$PageTitle)}}"
                   class="twitter"
                   data-bs-toggle="tooltip" title="{{ __('afaq.x') }}" aria-label="{{ __('afaq.x') }}"
                   target="_blank" rel="noopener noreferrer"><i
                        class="bi bi-twitter-x"></i></a></li>
            <li><a href="{{ Helper::SocialShare("linkedin", @$PageTitle)}}"
                   class="linkedin"
                   data-bs-toggle="tooltip" title="{{ __('afaq.linkedin') }}" aria-label="{{ __('afaq.linkedin') }}"
                   target="_blank" rel="noopener noreferrer"><i
                        class="fa-brands fa-linkedin"></i></a></li>
            <li><a href="{{ Helper::SocialShare("tumblr", @$PageTitle)}}" class="tumblr"
                   data-bs-toggle="tooltip" title="{{ __('afaq.tumblr') }}" aria-label="{{ __('afaq.tumblr') }}"
                   target="_blank" rel="noopener noreferrer"><i
                        class="fa-brands fa-tumblr"></i></a></li>
        </ul>
    </div>
</div>
