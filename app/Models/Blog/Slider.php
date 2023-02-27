<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jamstackvietnam\Support\Models\BaseModel;
use Jamstackvietnam\Support\Traits\HasMedia;
use Jamstackvietnam\Support\Models\Sluggable;
use Jamstackvietnam\Support\Scopes\HasActive;

class Slider extends BaseModel
{
    use HasFactory, HasMedia, Sluggable;

    protected $table = 'sliders';

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLE = 2;
    public const STATUS_LIST = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    public const HOME_POSITION_SLIDER = "HOME_SLIDER";
    public const HOME_POSITION_TOP_LEFT = "HOME_TOP_LEFT";
    public const HOME_POSITION_TOP_RIGHT = "HOME_TOP_RIGHT";
    public const HOME_POSITION_FOOTER_TOP = 'HOME_FOOTER_TOP';
    public const HOME_POSITION_FOOTER_BOTTOM_LEFT = 'HOME_FOOTER_BOTTOM_LEFT';
    public const HOME_POSITION_FOOTER_BOTTOM_CENTER = 'HOME_FOOTER_BOTTOM_CENTER';
    public const HOME_POSITION_FOOTER_BOTTOM_RIGHT = 'HOME_FOOTER_BOTTOM_RIGHT';
    public const POST_POSITION_CENTER = "POST_CENTER";
    public const POST_POSITION_RIGHT = 'POST_RIGHT';
    public const HEADER_BRAND_POSITION_LEFT = 'HEADER_BRAND_LEFT';
    public const HEADER_BRAND_POSITION_CENTER = 'HEADER_BRAND_CENTER';
    public const HEADER_BRAND_POSITION_RIGHT = 'HEADER_BRAND_RIGHT';

    public const POSITION_LIST = [
        self::HOME_POSITION_SLIDER => 'homepage_slider',
        self::HOME_POSITION_TOP_LEFT => 'homepage_top_side_left',
        self::HOME_POSITION_TOP_RIGHT => 'homepage_top_side_right',
        self::HOME_POSITION_FOOTER_TOP => 'homepage_footer_above',
        self::HOME_POSITION_FOOTER_BOTTOM_LEFT => 'homepage_footer_bottom_side_left',
        self::HOME_POSITION_FOOTER_BOTTOM_CENTER => 'homepage_footer_center',
        self::HOME_POSITION_FOOTER_BOTTOM_RIGHT => 'homepage_footer_bottom_side_right',
        self::POST_POSITION_CENTER => 'post_detail_center',
        self::POST_POSITION_RIGHT => 'post_detail_right',
        self::HEADER_BRAND_POSITION_LEFT => 'brand_header_side_left',
        self::HEADER_BRAND_POSITION_CENTER => 'brand_header_center',
        self::HEADER_BRAND_POSITION_RIGHT => 'brand_header_side_right',
    ];

    public $fillable = [
        'title',
        'slug',
        'link',
        'status',
        'position',
        'position_sort'
    ];

    protected $with = [
        'files',
    ];

    protected $mediaFields = [
        'thumbnail', 'thumbnail_mobile'
    ];

    protected $searchable = [
        'columns' => [
            'sliders.title' => 10,
            'sliders.slug' => 2,
        ]
    ];

    protected $slugAttribute = 'title';

    protected static function booted()
    {
        static::saving(function (self $model) {
            if (request()->route() === null) return;
            $model->saveStatus($model);
        });
    }

    public function saveStatus($model)
    {
        if (request()->input('status', []) == 1 && $model->position != self::HOME_POSITION_SLIDER) {
            Slider::where('position', $model->position)
                ->where('id', '<>', $model->id)
                ->update(['status' => 2]);
        }
    }

    public function modelRules()
    {
        return [
            'all' => [
                'title' => 'required|string|max:255',
            ],
        ];
    }

    public function getFormattedPositionAttribute(): string
    {
        if (!empty($this->position)) {
            switch ($this->position) {
                case 'HOME_SLIDER':
                    return self::POSITION_LIST[self::HOME_POSITION_SLIDER];
                case 'HOME_TOP_LEFT':
                    return self::POSITION_LIST[self::HOME_POSITION_TOP_LEFT];
                case 'HOME_TOP_RIGHT':
                    return self::POSITION_LIST[self::HOME_POSITION_TOP_RIGHT];
                case 'HOME_FOOTER_TOP':
                    return self::POSITION_LIST[self::HOME_POSITION_FOOTER_TOP];
                case 'HOME_FOOTER_BOTTOM_LEFT':
                    return self::POSITION_LIST[self::HOME_POSITION_FOOTER_BOTTOM_LEFT];
                case 'HOME_FOOTER_BOTTOM_CENTER':
                    return self::POSITION_LIST[self::HOME_POSITION_FOOTER_BOTTOM_CENTER];
                case 'HOME_FOOTER_BOTTOM_RIGHT':
                    return self::POSITION_LIST[self::HOME_POSITION_FOOTER_BOTTOM_RIGHT];
                case 'POST_CENTER':
                    return self::POSITION_LIST[self::POST_POSITION_CENTER];
                case 'POST_RIGHT':
                    return self::POSITION_LIST[self::POST_POSITION_RIGHT];
                case 'HEADER_BRAND_LEFT':
                    return self::POSITION_LIST[self::HEADER_BRAND_POSITION_LEFT];
                case 'HEADER_BRAND_CENTER':
                    return self::POSITION_LIST[self::HEADER_BRAND_POSITION_CENTER];
                case 'HEADER_BRAND_RIGHT':
                    return self::POSITION_LIST[self::HEADER_BRAND_POSITION_RIGHT];
                default:
                    return '';
            }
        }
        return '';
    }

    public function getThumbnailPathAttribute()
    {
        return $this->getFilePath('thumbnail');
    }

    public function getThumbnailAttribute()
    {
        return $this->getFile('thumbnail');
    }

    public function getThumbnailMobilePathAttribute()
    {
        return $this->getFilePath('thumbnail_mobile');
    }

    public function getThumbnailMobileAttribute()
    {
        return $this->getFile('thumbnail_mobile');
    }

    public function transform()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'link' => $this->link,
            'thumbnail' => $this->thumbnail_path,
            'thumbnail_mobile' => $this->thumbnail_mobile_path,
        ];
    }

    public function transformDetails()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'position_sort' => $this->position_sort,
            'link' => $this->link,
            'thumbnail' => $this->thumbnail_path,
            'thumbnail_mobile' => $this->thumbnail_mobile_path,
        ];
    }
}
