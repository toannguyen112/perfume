<?php

namespace App\Models\Product;

use App\Models\Tag;
use Jamstackvietnam\Support\Traits\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jamstackvietnam\Support\Models\BaseModel;
use Jamstackvietnam\Support\Models\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jamstackvietnam\Support\Traits\Searchable;

class ProductCategory extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;
    use HasMedia;
    use Searchable;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    const TYPE_PRODUCT = 'PRODUCT';
    const TYPE_SALE = 'SALE';
    const TYPE_USE_CASE = 'USE_CASE';

    public const TYPE_LIST = [
        self::TYPE_PRODUCT,
        self::TYPE_SALE,
        self::TYPE_USE_CASE,
    ];

    public $fillable = [
        'parent_id',
        'name',
        'slug',
        'icon',
        'content',
        'position',
        'is_hot',
        'is_home',
        'is_menu',
        'status',
        'links',
        'discount',
        'start_day',
        'end_day',
        'type',

        'meta_title',
        'custom_slug',
        'meta_description',
        'custom_meta',

        'creator_id',
        'editor_id',
    ];

    protected $slugAttribute = 'name';

    protected $appends = [
        'url',
        'formatted_created_at',
        'formatted_updated_at',
        'link_header_left',
        'link_header_center',
        'link_header_right',
        'thumbnail_left',
        'thumbnail_center',
        'thumbnail_right',
        'thumbnail'
    ];

    public $casts = [
        'links' => 'array',
        'custom_meta' => 'array',
    ];

    protected $with = [
        'files',
        'tags',
        'parent'
    ];

    protected $mediaFields = [
        'thumbnail_left', 'thumbnail_center', 'thumbnail_right', 'thumbnail',
    ];

    protected $searchable = [
        'columns' => [
            'product_categories.name' => 10,
            'product_categories.id' => 5,
        ],
    ];

    protected static function booted()
    {
        static::saving(function (self $model) {
            if (!contains(request()->getRequestUri(), '/product-categories/store')) return;
            $model->saveLink($model);
        });
        static::saved(function (self $model) {
            if (!contains(request()->getRequestUri(), '/product-categories/store')) return;
            $model->saveOptions($model);
            $model->saveBrands($model);
            $model->saveTags($model);
        });
    }

    public function saveLink($model)
    {
        $model->links = [
            'headerLeft' => request()->input('link_header_left'),
            'headerCenter' => request()->input('link_header_center'),
            'headerRight' => request()->input('link_header_right')
        ];
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'tag_ref_categories',
        )
            ->orderBy('id', 'desc');
    }

    private function saveBrands($model)
    {
        $ids = array_column(request()->input('brands', []), 'id');
        $model->brands()->sync($ids, 'id');
    }

    private function saveTags($model)
    {
        $tags_category =  request()->input('tags', []) ?? [];
        $tagIds = array_column($tags_category, 'id');
        $model->tags()->sync($tagIds, 'id');
    }

    public static function getTreeListSales()
    {
        return static::orderBy('position')
            ->with('nodes.options', 'options')
            ->orderByRaw('-position DESC')
            ->where(['type' => self::TYPE_SALE, 'parent_id' => 0])
            ->get();
    }

    public static function getTreeListUseCases()
    {
        return static::orderBy('position')
            ->orderByRaw('-position DESC')
            ->where(['type' => self::TYPE_USE_CASE, 'parent_id' => 0])
            ->get();
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeWhereSlug($query, $slug)
    {
        return $query
            ->where('custom_slug', $slug)
            ->orWhere('slug', $slug);
    }

    public function scopeWhereRoot($query)
    {
        return $query->whereNull('parent_id')->orWhere('parent_id', 0);
    }

    public function scopeTypeProductActive($query)
    {
        return $query->active()->where('type', self::TYPE_PRODUCT);
    }

    private function saveOptions($model)
    {
        $ids = array_column(request()->input('options', []), 'id');
        $model->options()->sync($ids, 'id');
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'brand_ref_categories');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->orderByRaw('-position DESC')
            ->orderBy('position', 'asc')
            ->orderBy('parent_id')
            ->orderBy('id', 'desc');
    }

    public function productIds(): HasMany
    {
        return $this->hasMany(
            ProductRefCategory::class,
            'product_category_id',
            'id'
        );
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_ref_categories'
        );
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(
            Option::class,
            'option_ref_categories'
        );
    }

    public static function getRootProduct()
    {
        return static::orderBy('position')
            ->with('nodes.options', 'options')
            ->withCount('products')
            ->orderByRaw('-position DESC')
            ->orderBy('parent_id')
            ->where('type', self::TYPE_PRODUCT)
            ->where(function ($query) {
                $query->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->get();
    }

    public static function getNav()
    {
        return static::query()
            ->active()
            ->with('children.children')
            ->where('is_menu', 1)
            ->orderByRaw('-position DESC')
            ->orderBy('parent_id')
            ->take(9)
            ->get();
    }

    public static function treeList(array $types = self::TYPE_LIST)
    {
        return self::query()
            ->with('nodes')
            ->active()
            ->whereIn('type', $types)
            ->orderBy('parent_id')
            ->whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->get()
            ->map(fn ($item) => $item->transformDetails(['is_show_nodes' => 1]));
    }

    public function nodes()
    {
        return $this->children()
            ->with('nodes')
            ->withCount('products')
            ->orderByRaw('-position DESC')
            ->orderBy('parent_id');
    }

    public function activeNodes()
    {
        return $this->children()
            ->active()
            ->with('activeNodes')
            ->orderByRaw('-position DESC')
            ->orderBy('parent_id');
    }

    public function transform(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->custom_slug ?? $this->slug,
            'icon' => $this->icon,
        ];
    }


    public function transformUseCase(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => transform_richtext($this->content),
        ];
    }

    public function getLinkHeaderLeftAttribute()
    {
        return $this->links['headerLeft'] ?? null;
    }

    public function getLinkHeaderCenterAttribute()
    {
        return $this->links['headerCenter'] ?? null;
    }

    public function getLinkHeaderRightAttribute()
    {
        return $this->links['headerRight'] ?? null;
    }

    public function getThumbnailLeftPathAttribute()
    {
        return $this->getFilePath('thumbnail_left');
    }

    public function getThumbnailLeftAttribute()
    {
        return $this->getFile('thumbnail_left');
    }

    public function getThumbnailCenterPathAttribute()
    {
        return $this->getFilePath('thumbnail_center');
    }

    public function getThumbnailCenterAttribute()
    {
        return $this->getFile('thumbnail_center');
    }

    public function getThumbnailRightPathAttribute()
    {
        return $this->getFilePath('thumbnail_right');
    }

    public function getThumbnailRightAttribute()
    {
        return $this->getFile('thumbnail_right');
    }

    public function getThumbnailPathAttribute()
    {
        return $this->getFilePath('thumbnail');
    }

    public function getThumbnailAttribute()
    {
        return $this->getFile('thumbnail');
    }

    public function transformNav($level = 1): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'slug' => $this->custom_slug ?? $this->slug,
            'content' => transform_richtext($this->content),
            'image_url' => $this->image_url,
            'links' => [
                ['image_url' => $this->thumbnail_left_path, 'url' => $this->link_header_left],
                ['image_url' => $this->thumbnail_center_path, 'url' => $this->link_header_center],
                ['image_url' => $this->thumbnail_right_path, 'url' => $this->link_header_right],
            ],
        ];

        if ($level <= 2) {
            $data['children'] = $this->children->map(fn ($item) => $item->transformNav($level + 1));
        }

        return $data;
    }

    public function transformDetails($condition = []): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->custom_slug ?? $this->slug,
            'icon' => $this->icon,
            'content' => transform_richtext($this->content),
            'image_url' => $this->image_url,
        ];

        if ($this->is_home == 1) {
            $data['links'] = [
                ['image_url' => $this->thumbnail_left_path, 'url' => $this->link_header_left],
                ['image_url' => $this->thumbnail_center_path, 'url' => $this->link_header_center],
                ['image_url' => $this->thumbnail_right_path, 'url' => $this->link_header_right],
            ];
        }

        $data['meta_title'] = $this->meta_title;
        $data['meta_description'] = $this->meta_description;
        $data['custom_meta'] = $this->custom_meta;

        if (!empty($condition['is_show_options'])) {
            $data['options'] = $this->options->map(fn ($item) => $item->transform());
        }

        if (!empty($condition['is_show_brands'])) {
            $data['brands'] = $this->brands->map(fn ($item) => $item->transform());
        }

        return $data;
    }

    public function transformToBreadcrumbItems()
    {
        return collect(self::flatAncestors($this))
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->custom_slug ?? $item->slug,
                ];
            })
            ->reverse()->values();
    }

    public static function transformAsBreadcrumb($category)
    {
        if ($category) {
            return collect(self::flatAncestors($category))
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'slug' => $item->custom_slug ?? $item->slug,
                    ];
                })
                ->reverse()->values();
        }
        return [];
    }

    public static function flatDescendants($model): array
    {
        if (!$model) return [];

        $result[] = $model;
        foreach ($model->activeNodes as $child) {
            $result[] = $child;
            if (!empty($child->activeNodes)) {
                $result = array_merge($result, self::flatDescendants($child));
            }
        }

        return $result;
    }

    public static function flatAncestors($model): array
    {
        if (!$model) return [];

        $result[] = $model;
        if (!empty($model->parent)) {
            $result = array_merge($result, self::flatAncestors($model->parent));
        }

        return $result;
    }

    public function modelRules(): array
    {
        return [
            'all' => [
                'name' => 'required|string|max:255',
            ],
        ];
    }

    public function getUrlAttribute()
    {
        $url = null;
        if ($this->status == self::STATUS_ACTIVE) {
            if ($this->type == self::TYPE_PRODUCT) {
                $url = route("products.index", [
                    'slug' => $this->custom_slug ?? $this->slug
                ]);
            }
            if ($this->type == self::TYPE_SALE) {
                $url = route("sale.show", [
                    'saleSlug' => $this->custom_slug ?? $this->slug,
                    'saleId' => $this->id
                ]);
            }
        }
        return $url;
    }

    public function getFormattedStartDayAttribute(): string
    {
        return datetime_format($this->start_day, 'd/m/Y');
    }

    public function getFormattedEndDayAttribute(): string
    {
        return datetime_format($this->end_day, 'd/m/Y');
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

    public function transformSale()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->custom_slug ?? $this->slug,
            'discount' => $this->discount,
            'start_day' => $this->formatted_start_day,
            'end_day' => $this->formatted_end_day,
            'thumbnail'  => $this->thumbnail_path,
        ];
    }

    public function transformHome()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->custom_slug ?? $this->slug,
            'icon' => $this->icon,
            'is_hot' => $this->is_hot,
        ];
    }

    public function transformHomeProduct()
    {
        return [
            'name' => $this->name,
            'slug' => $this->custom_slug ?? $this->slug,
            'products' => $this->products->map(fn ($item) => $item->transform()),
        ];
    }

    public function transformChildrenIds()
    {
        return collect(self::flatDescendants($this))
            ->map(fn ($item) => $item->id)
            ->unique()
            ->values()
            ->toArray();
    }

    public function transformChildren()
    {
        return $this->children->map(fn ($item) => $item->transform());
    }
}
