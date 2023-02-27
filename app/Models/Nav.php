<?php

namespace App\Models;

use App\Models\Blog\Post;
use App\Models\Blog\PostCategory;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Jamstackvietnam\Support\Models\BaseModel;

/**
 * @package App\Models
 */
class Nav extends BaseModel
{
    public const TYPE_PRODUCT = 'PRODUCT';
    public const TYPE_PRODUCT_CATEGORY = 'PRODUCT_CATEGORY';
    public const TYPE_BRAND = 'BRAND';
    public const TYPE_BLOG = 'BLOG';
    public const TYPE_BLOG_CATEGORY = 'BLOG_CATEGORY';
    public const TYPE_LINK = 'LINK';

    public const TYPE_LIST = [
        self::TYPE_PRODUCT => 'Sản phẩm',
        self::TYPE_PRODUCT_CATEGORY => 'Danh mục sản phẩm',
        self::TYPE_BRAND => 'Thương hiệu',
        self::TYPE_BLOG => 'Bài viết',
        self::TYPE_BLOG_CATEGORY => 'Danh mục bài viết',
        self::TYPE_LINK => 'Link tùy chỉnh',
    ];

    public const TARGET_SELF = '_self';
    public const TARGET_BLANK = '_blank';
    public const LIST_TARGET = [
        self::TARGET_SELF => 'Mở tab hiện tại',
        self::TARGET_BLANK => 'Mở tab mới',
    ];

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLE = 2;
    public const STATUS_LIST = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    public $fillable = [
        'parent_id',
        'title',
        'slug',
        'link',
        'target',
        'type',
        'type_id',

        'icon',
        'left_banner_url',
        'right_banner_url',
        'center_banner_url',
        'link_header_left',
        'link_header_center',
        'link_header_right',

        'position',
        'status',

        'creator_id',
        'editor_id',
    ];

    protected $appends = [
        'url'
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (request()->getRequestUri() == null) return;

            $model->parent_id = $model->parent_id ?? 0;
            $model->position = $model->position ?? 0;

            $model->slug = $model->generateSlug();
        });
    }

    public function modelRules(): array
    {
        return [
            'all' => [
                'title' => 'required|string|max:255',
            ],
        ];
    }

    public static function getMenuPrimary()
    {
        return static::orderByRaw('-position DESC')
            ->orderBy('parent_id')->get();
    }

    public static function getRoot()
    {
        return static::query()
            ->with('nodes')
            ->orderByRaw('-position DESC')
            ->orderBy('parent_id')
            ->whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->get();
    }

    public function nodes(): HasMany
    {
        return $this->children()
            ->with('nodes')
            ->orderByRaw('-position DESC')
            ->orderBy('parent_id')
            ->orderBy('id', 'desc');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->orderByRaw('-position DESC');
    }

    public function getUrlAttribute()
    {
        return $this->generateUrl();
    }

    public function transform(): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'icon' => $this->icon,
            'slug' => $this->slug,
            'target' => $this->target,
            'type_id' => (int) $this->type_id,
            'type' => self::TYPE_LIST[$this->type],
            'link' => $this->link,
            'left_banner_url' => static_url($this->left_banner_url),
            'right_banner_url' => static_url($this->right_banner_url),
            'center_banner_url' => static_url($this->center_banner_url),
            'link_header_left' => $this->link_header_left,
            'link_header_center' => $this->link_header_center,
            'link_header_right' => $this->link_header_right,
            'position' => $this->position,
            'url' => $this->url
        ];

        if ($this->nodes->count() > 0) {
            $data['child'] = $this->nodes->map(function ($item) {
                return $item->transform();
            });
        }

        return $data;
    }

    public function generateUrl()
    {
        $type_id = $this->type_id;

        if (!$type_id) return null;

        if ($this->link == self::TYPE_LINK) {
            return $this->link;
        }

        $model = [
            self::TYPE_PRODUCT => Product::class,
            self::TYPE_PRODUCT_CATEGORY => ProductCategory::class,
            self::TYPE_BRAND => Brand::class,
            self::TYPE_BLOG => Post::class,
            self::TYPE_BLOG_CATEGORY => PostCategory::class,
        ][$this->type];

        $model = new $model;

        return $model->setEagerLoads([])
            ->select($model->fillable)
            ->where('id', $type_id)
            ->first()?->url;
    }

    public function generateSlug()
    {
        $type_id = $this->type_id;

        if (!$type_id) return null;

        switch ($this->type) {
            case self::TYPE_PRODUCT:
                $item = Product::where('id', $type_id)->select('slug')->first();
                return $item->custom_slug ?? $item->slug;

            case self::TYPE_PRODUCT_CATEGORY:
                $item = ProductCategory::where('id', $type_id)->select('slug')->first();
                return $item->custom_slug ?? $item->slug;

            case self::TYPE_PRODUCT:
                $item = Post::where('id', $type_id)->select('slug')->first()->slug;
                return $item->custom_slug ?? $item->slug;

            default:
                return null;
        }
    }

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        $positions = explode(',', $filters['position']);

        $query->when($positions ?? false, function (Builder $query, $positions) {
            $query->where(function ($query) use ($positions) {
                foreach ($positions as $position) {
                    $query->orWhere('position', 'LIKE', $position . '%');
                }
            });
        });

        if (isset($filters['parent_id']) && is_numeric($filters['parent_id'])) {
            $query->where('parent_id', $filters['parent_id']);
        }

        return $query;
    }
}
