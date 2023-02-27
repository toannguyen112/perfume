<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jamstackvietnam\Support\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jamstackvietnam\Support\Traits\Searchable;
use Jamstackvietnam\Support\Models\Sluggable;
use Jamstackvietnam\Support\Traits\HasMedia;
use App\Models\Tag;

class Post extends BaseModel
{
    use HasFactory, SoftDeletes, HasMedia, Sluggable, Searchable;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    public const TYPE_POLICY = 'POLICY';
    public const TYPE_POST = 'POST';

    public const TYPE_LIST = [
        self::TYPE_POLICY => 'Policy',
        self::TYPE_POST => 'Post'
    ];

    public $fillable = [
        'title',
        'slug',
        'content',
        'description',
        'is_home',
        'author',
        'is_featured',

        'status',
        'type',
        'position',

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

    protected $slugAttribute = 'title';

    public $casts = [
        'custom_meta' => 'array',
    ];

    protected $with = [
        'files',
        'categories'
    ];

    protected $mediaFields = [
        'thumbnail',
        'banner_thumbnail'
    ];

    protected $searchable = [
        'columns' => [
            'posts.title' => 10,
            'posts.id' => 3,
            'posts.slug' => 5,
        ]
    ];

    public function modelRules()
    {
        return [
            'all' => [
                'title' => 'required|string|max:255',
                'description' => 'max:255',
            ],
        ];
    }

    protected static function booted()
    {
        static::saved(function (self $model) {
            if (request()->route() === null) return;
            $model->saveCategories($model);
            $model->saveRelatedPosts($model);
            $model->saveTags($model);
        });
    }

    private function saveTags($model)
    {
        $tagIds = array_column(request()->input('tags', []), 'id');
        $model->tags()->sync($tagIds, 'id');
    }

    public function saveRelatedPosts($model)
    {
        $categories = array_column(request()->input('related_posts', []), 'id');
        $model->categories()->sync($categories, 'id');
    }

    public function saveCategories($model)
    {
        $categories = array_column(request()->input('categories', []), 'id');
        $model->categories()->sync($categories, 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            PostCategory::class,
            'post_ref_categories',
            'post_id',
            'post_category_id'
        );
    }

    public static function getPostByCategory()
    {
        return Post::belongsToMany(
            PostCategory::class,
            'post_ref_categories',
            'post_id',
            'post_category_id'
        );
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tags',
            'post_id',
            'tag_id',
            'id'
        )
            ->orderBy('id', 'desc');
    }

    public static function getPost()
    {
        return static::whereType(self::TYPE_POST)->get();
    }

    public function getPolicy()
    {
        return static::whereType(self::TYPE_POLICY)->get();
    }

    public function getTagsAttribute()
    {
        return $this->tags()->select('id', 'name')->get();
    }

    public function getCategoriesAttribute()
    {
        return $this->categories()->get();
    }

    public function getCategoryNameAttribute()
    {
        return $this->categories()->first()?->title;
    }

    public function getThumbnailPathAttribute()
    {
        return $this->getFilePath('thumbnail');
    }

    public function getThumbnailAttribute()
    {
        return $this->getFile('thumbnail');
    }

    public function relatedPosts()
    {
        return $this->belongsToMany(
            self::class,
            'post_related',
            'post_id',
            'related_post_id'
        );
    }

    public function getRelated()
    {
        return [
            'relatedPosts' => $this->relatedPosts
        ];
    }

    public function getBannerThumbnailPathAttribute()
    {
        return $this->getFilePath('banner_thumbnail');
    }

    public function getBannerThumbnailAttribute()
    {
        return $this->getFile('banner_thumbnail');
    }

    public function getReadingTimeAttribute()
    {
        $word = str_word_count(strip_tags($this->content));
        return floor($word / 200);
    }

    public function getCategoryAttribute()
    {
        return $this->categories?->first();
    }

    public function getUrlAttribute()
    {
        $url = null;
        $categorySlug = $this->category?->transform()['slug'];
        $slug = $this->custom_slug ?? $this->slug;
        if ($this->status == self::STATUS_ACTIVE) {
            if ($this->type == self::TYPE_POST && !empty($categorySlug)) {
                $url = route("post.show", [
                    'categorySlug' => $categorySlug,
                    'slug' => $slug
                ]);
            }
            if ($this->type == self::TYPE_POLICY) {
                $url = route("policy.show", [
                    'slug' => $slug
                ]);
            }
        }
        return $url;
    }

    public function transform()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'slug' => $this->custom_slug ?? $this->slug,
            'reading_time' => $this->reading_time,
            'category' => $this->category?->transform(),
            'description' => $this->description,
            'content' => $this->content,
            'thumbnail' => $this->thumbnail_path,
            'position' => $this->position,
            'formatted_created_at' => $this->formatted_created_at,
        ];
    }

    public function transformDetails()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'slug' => $this->custom_slug ?? $this->slug,
            'reading_time' => $this->reading_time,
            'content' => $this->content,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail_path,
            'banner_thumbnail' => $this->banner_thumbnail_path,
            'position' => $this->position,
            'category' => $this->category?->transform(),
            'formatted_created_at' => $this->formatted_created_at,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description ?? $this->description,
            'custom_meta' => $this->custom_meta
        ];
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        if (!empty($this->created_at)) {
            return $this->created_at->format('d/m/Y');
        }
        return '';
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
