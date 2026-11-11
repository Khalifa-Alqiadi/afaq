<nav class="afaq-inner-crumbs" aria-label="{{ $afaqIsArabic ? 'مسار الصفحة' : 'Breadcrumb' }}">
    <ol>
        <li><a href="{{ Helper::homeURL() }}">{{ $afaqIsArabic ? 'الرئيسية' : 'Home' }}</a></li>
        @if(!empty($afaqSectionTitle) && !empty($afaqSectionUrl))
            <li><a href="{{ $afaqSectionUrl }}">{{ $afaqSectionTitle }}</a></li>
        @endif
        @if(!empty($afaqCategoryTitle) && !empty($afaqCategoryUrl))
            <li><a href="{{ $afaqCategoryUrl }}">{{ $afaqCategoryTitle }}</a></li>
        @endif
        @if(!empty($afaqCurrentTitle))
            <li aria-current="page">{{ $afaqCurrentTitle }}</li>
        @endif
    </ol>
</nav>
