<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jamstackvietnam\Support\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jamstackvietnam\Support\Traits\Searchable;
use Jamstackvietnam\Support\Traits\HasCustomFields;
use Jamstackvietnam\Support\Models\Sluggable;

class PostCategory extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use HasCustomFields;
    use Sluggable;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLE = 2;
    public const STATUS_LIST = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    protected $table = 'post_categories';

    protected $searchable = [
        'columns' => [
            'post_categories.title' => 10,
            'post_categories.id' => 3,
        ],
    ];

    public $fillable = [
        'id',
        'title',
        'slug',

        'status',

        'meta_title',
        'custom_slug',
        'meta_description',
        'custom_meta',

        'creator_id',
        'editor_id'
    ];

    protected $appends = [
        'url'
    ];

    public $casts = [
        'custom_meta' => 'array',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_ref_categories');
    }

    protected $slugAttribute = 'title';

    public function modelRules()
    {
        return [
            'all' => [
                'title' => 'required|string|max:255',
            ],
        ];
    }

    public function getUrlAttribute()
    {
        $url = null;
        if ($this->status == self::STATUS_ACTIVE) {
            $url = route("post.category-show", [
                'categorySlug' => $this->custom_slug ?? $this->slug
            ]);
        }
        return $url;
    }

    public function transform()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->custom_slug ?? $this->slug,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description
        ];
    }

    public static function lazySearch($data)
    {
        $keyword = $data['keyword'];
        $limit = $data['limit'] ?? 10;

        return static::query()
            ->search($keyword)
            ->limit($limit)
            ->get();
    }
}
