@php
    $language = @Helper::currentLanguage()->code;
    $fallbackLanguage = config('smartend.default_language');
    $isArabic = $language === 'ar';
    $title = $TopicBlockContents->{'title_'.$language} ?: ($TopicBlockContents->{'title_'.$fallbackLanguage} ?? '');
    $description = $TopicBlockContents->{'desc_'.$language} ?: ($TopicBlockContents->{'desc_'.$fallbackLanguage} ?? '');
    $details = $TopicBlockContents->{'details_'.$language} ?: ($TopicBlockContents->{'details_'.$fallbackLanguage} ?? '');
    $photo = $TopicBlockContents->{'bg_'.$language} ?: ($TopicBlockContents->{'bg_'.$fallbackLanguage} ?? '');
    $photoPath = $photo && \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->exists('topics/'.$photo)
        ? 'topics/'.$photo : 'banners/afaq-profile-page-2.png';
    $focuses = $isArabic
        ? ['الاستهلاك', 'تكلفة النظام', 'الإنتاج المتوقع', 'استرداد رأس المال']
        : ['Consumption', 'System cost', 'Expected output', 'Payback'];
@endphp

<section id="landing-block-{{ (int) $TopicBlock->id }}" class="landing-block afaq-economic {{ $TopicBlock->css_classes }} {{ $TopicBlock->divider_status ? 'divider' : '' }}" @if($TopicBlock->bg_color) style="background-color: {{ $TopicBlock->bg_color }}" @endif aria-label="{{ $title }}">
    <div class="container afaq-economic__layout">
        <div class="afaq-economic__visual">
            <div class="afaq-economic__image-frame">
                <img src="{{ route('fileView', ['path' => $photoPath]) }}?w=1400&h=1150&r=fit" alt="{{ $title }}" loading="lazy" width="1400" height="1150">
                <span class="afaq-economic__image-index" aria-hidden="true">AFAQ / 06</span>
            </div>
            <div class="afaq-economic__image-caption"><span class="afaq-economic__caption-rule"></span><span>{{ $isArabic ? 'طاقة مدروسة. قرار واثق.' : 'ENGINEERED ENERGY. INFORMED DECISIONS.' }}</span></div>
        </div>
        <div class="afaq-economic__copy">
            <div class="afaq-economic__eyebrow"><span class="afaq-economic__eyebrow-line"></span>AFAQ / {{ $isArabic ? 'جدوى الطاقة' : 'ENERGY ECONOMICS' }}<span class="afaq-economic__eyebrow-number">06</span></div>
            @if($TopicBlock->title_status && $title !== '')<h2>{{ $title }}</h2>@endif
            @if($TopicBlock->desc_status && $description !== '')<p class="afaq-economic__summary">{{ $description }}</p>@endif
            @if($details !== '')<div class="afaq-economic__details">{!! str_replace('"#', '"'.Request::url().'#', $details) !!}</div>@endif
            <div class="afaq-economic__focus-heading"><span>{{ $isArabic ? 'ما الذي ندرسه؟' : 'WHAT WE ASSESS' }}</span><span class="afaq-economic__focus-heading-line"></span></div>
            <ul class="afaq-economic__focuses">
                @foreach($focuses as $focus)
                    <li><span class="afaq-economic__focus-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span>{{ $focus }}</span><i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
@push('after-scripts')
<script>
(function () {
    var section = document.getElementById('landing-block-{{ (int) $TopicBlock->id }}');
    if (!section || !('IntersectionObserver' in window) || (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) return;
    section.classList.add('afaq-economic--motion');
    var observer = new IntersectionObserver(function (entries) {
        if (entries[0].isIntersecting) {
            section.classList.add('afaq-economic--visible');
            observer.disconnect();
        }
    }, { threshold: 0.12 });
    observer.observe(section);
})();
</script>
@endpush
