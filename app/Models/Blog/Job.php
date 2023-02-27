<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jamstackvietnam\Support\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jamstackvietnam\Support\Traits\HasRichText;
use Jamstackvietnam\Support\Traits\HasMetaData;
use Jamstackvietnam\Support\Traits\Searchable;
use Jamstackvietnam\Support\Models\Sluggable;

class Job extends BaseModel
{
    use HasFactory, SoftDeletes, HasMetaData, HasRichText, Sluggable;
    use Searchable;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    public $fillable = [
        'title',
        'slug',
        'description',
        'address',
        'salary',
        'position',
        'count',
        'work_form',
        'work_time',
        'status',
        'expected_time',
        'is_featured',
        'posted_at',
        'meta_title',
        'custom_slug',
        'meta_description',
        'custom_meta'
    ];

    protected $appends = [
        'url'
    ];

    public $searchable = [
        'columns' => [
            'job.title' => 10,
            'job.slug' => 2,
        ],
    ];

    protected $richtext = [
        'description',
    ];

    public $casts = [
        'custom_meta' => 'array',
    ];

    protected $slugAttribute = 'title';

    public function modelRules()
    {
        return [
            'all' => [
                'title' => 'required|string|max:255',
            ],
        ];
    }

    public function getFormattedExpectedTimeAttribute()
    {
        return datetime_format($this->attributes['expected_time'], 'd/m/Y');
    }

    public function getFormattedPostedAtAttribute()
    {
        return datetime_format($this->attributes['posted_at'], 'd/m/Y');
    }

    public function getUrlAttribute()
    {
        $url = null;
        if ($this->status == self::STATUS_ACTIVE && $this->expected_time >= now()) {
            $url = route("job.show", [
                'slug' => $this->custom_slug ?? $this->slug
            ]);
        }
        return $url;
    }

    public function transform()
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->custom_slug ?? $this->slug,
            "work_form" => $this->work_form,
            "address" => $this->address,
            "formatted_expected_time" => $this->formatted_expected_time,
            "custom_meta" => $this->custom_meta
        ];
    }

    public function transformDetails()
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->custom_slug ?? $this->slug,
            'custom_meta' => $this->custom_meta,
            "position" => $this->position,
            "description" => transform_richtext($this->description),
            "address" => $this->address,
            "salary" => $this->salary,
            "count" => $this->count,
            "work_time" => $this->work_time,
            "formatted_expected_time" => $this->formatted_expected_time,
            "formatted_posted_at" => $this->formatted_posted_at,
            "meta_data" => [
                "custom_slug" => $this->custom_slug ?? $this->slug,
                "meta_title" => $this->meta_title ?? $this->title,
                "meta_description" => transform_richtext($this->meta_description) ?? transform_richtext($this->description),
            ],
            "custom_meta" => $this->custom_meta
        ];
    }
}
