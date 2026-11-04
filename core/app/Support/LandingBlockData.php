<?php

namespace App\Support;

use App\Helpers\Helper;
use Illuminate\Support\Collection;

class LandingBlockData
{
    public const STYLE_LABELS = [
        'YhScholars' => 'style_directory',
        'YhNewsroom' => 'style_news_grid',
        'YhOpinions' => 'style_article_slider',
        'YhAuthorArticles' => 'style_author_articles',
        'YhInfographics' => 'style_image_grid',
        'YhVisuals' => 'style_video_slider',
        'YhBooks' => 'style_book_grid',
        'YhMagazine' => 'style_magazine',
        'YhFeaturedNews' => 'style_featured_slider',
        'Carousel' => 'style_carousel',
        'Categories' => 'style_categories',
        'FAQ' => 'style_faq',
        'Gallery' => 'style_gallery',
        'Grid' => 'style_grid',
        'News' => 'style_news',
        'Partners' => 'style_partners',
        'Staff' => 'style_staff',
        'Testimonials' => 'style_testimonials',
    ];

    public static function styleLabel(?string $style): string
    {
        $key = self::STYLE_LABELS[$style ?? ''] ?? null;
        return $key ? __('yemen_home.'.$key) : (string) $style;
    }

    public string $language;
    public string $fallbackLanguage;
    public string $titleField;
    public string $fallbackTitleField;
    public string $detailsField;
    public string $fallbackDetailsField;
    public string $blockTitle;
    public string $blockDescription;
    public Collection $topics;
    public string $sectionUrl = '#';

    public function __construct(object $contents, bool $loadTopics = true)
    {
        $this->language = optional(Helper::currentLanguage())->code ?: config('smartend.default_language');
        $this->fallbackLanguage = config('smartend.default_language');
        $this->titleField = 'title_'.$this->language;
        $this->fallbackTitleField = 'title_'.$this->fallbackLanguage;
        $this->detailsField = 'details_'.$this->language;
        $this->fallbackDetailsField = 'details_'.$this->fallbackLanguage;
        $this->blockTitle = (string) (data_get($contents, $this->titleField) ?: data_get($contents, $this->fallbackTitleField, ''));
        $this->blockDescription = (string) (data_get($contents, 'desc_'.$this->language) ?: data_get($contents, 'desc_'.$this->fallbackLanguage, ''));
        $this->topics = collect();

        $moduleId = (int) data_get($contents, 'module_id');
        if ($moduleId > 0) {
            $this->sectionUrl = url(Helper::sectionURL($moduleId));
            if ($loadTopics) {
                $this->topics = collect(Helper::Topics(
                    $moduleId,
                    data_get($contents, 'category_ids', ''),
                    min(200, max(1, (int) data_get($contents, 'records_count', 8))),
                    0,
                    (int) data_get($contents, 'records_order', 1)
                ));
            }
        }
    }

    public function title(object $item): string
    {
        return (string) ($item->{$this->titleField} ?: $item->{$this->fallbackTitleField});
    }

    public function details(object $item): string
    {
        return (string) ($item->{$this->detailsField} ?: $item->{$this->fallbackDetailsField});
    }
}
