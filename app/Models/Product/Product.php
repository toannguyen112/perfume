<?php

namespace App\Models\Product;

use Jamstackvietnam\Support\Models\BaseModel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Jamstackvietnam\Support\Models\Sluggable;
use Jamstackvietnam\Support\Traits\HasCustomFields;
use Jamstackvietnam\Support\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use App\Models\Tag;

class Product extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use HasCustomFields;
    use Sluggable;

    public const STATUS_DISABLE = 0;
    public const STATUS_ACTIVE = 1;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    public const SOURCE_SHEIN = 'SHEIN';

    public const SOURCE_LIST = [
        self::SOURCE_SHEIN,
    ];

    protected $table = 'products';

    public $fillable = [
        'id',
        'name',
        'slug',
        'content',
        'views',
        'video_urls',

        'size_guide_table',
        'size_guide_tutorial',
        'size_guide_image',
        'ingredients',

        'status',
        'brand_id',

        'meta_title',
        'custom_slug',
        'meta_description',
        'custom_meta',

        'is_sale',
        'is_hot',
        'is_new',
        'is_limited',

        'creator_id',
        'editor_id'
    ];

    protected $hidden = [];

    protected $casts = [
        'custom_meta' => 'array',
        'video_urls'  => 'array',
    ];

    protected $with = [
        'variants',
        'variantOptions.children',
        'requiredProductOptions',
        'images',
        'tags',
        'comments',
        'categories',
        'brand',
        'options'
    ];

    protected $appends = [
        'image_url',
        'rate_score',
        'url'
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected $slugAttribute = 'name';

    protected $customFields = [
        'richtext' => ['detail'],
    ];

    protected $searchable = [
        'columns' => [
            'products.name' => 20,
            'products.id' => 3,
            'product_variants.sku' => 1,
        ],
        'joins' => [
            'product_variants' => ['product_variants.product_id', 'products.id'],
        ],
    ];

    protected static function booted()
    {
        static::saved(function ($model) {
            if (request()->getRequestUri() !== '/admin/products/store') return;
            $model->saveVariants($model);
            $model->saveImages($model);
            $model->saveTags($model);
            $model->saveCategories($model);
        });
    }

    public function saveImages($model, $images = null)
    {
        $images = $images ?? request()->input('images', []);

        $diff = Arr::pluck($images, 'id');
        $ids = $model->images()->pluck('id')->diff($diff);

        if ($ids->isNotEmpty()) {
            $model->images()->whereIn('id', $ids)->delete();
        }
        self::withoutEvents(function () use ($images, $model) {
            foreach ($images as $index => $item) {
                $item['position'] = $index;
                $imageName = $item['filename'] ?? $item['image_name'];
                $item['image_name'] = (strlen($imageName) > 1  && strrpos($imageName, '.') > 0) ? substr($imageName, 0, strrpos($imageName, '.')) : $imageName;
                $model->images()->updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    $item
                );
            }
        });
    }

    private function saveTags($model)
    {
        $tagIds = array_column(request()->input('tags', []), 'id');
        $model->tags()->sync($tagIds, 'id');
    }

    private function saveCategories($model)
    {
        $categoryIds = array_column(request()->input('categories', []), 'id');
        $saleIds = array_column(request()->input('sale_categories', []), 'id');
        $useCaseIds = array_column(request()->input('use_case_categories', []), 'id');

        $model->categories()->sync(array_merge($categoryIds, $saleIds, $useCaseIds), 'id');
    }

    private function saveVariants($model)
    {
        $parents = collect(request()->input('list_option', []))
            ->filter(fn ($item) => !empty($item['id']) && !empty($item['nodes']));
        $children = $parents->pluck('nodes')->flatten(1);

        $refs = ProductVariantRefOption::query()
            ->where('product_id', $model->id)
            ->pluck('option_id');

        $ids = $refs->diff($children->pluck('id'));

        if ($ids->isNotEmpty()) {
            ProductVariantRefOption::query()
                ->where('product_id', $model->id)
                ->whereIn('option_id', $ids->toArray())
                ->delete();
        }

        self::withoutEvents(function () use ($model, $parents, $children) {
            foreach ($parents as $parent) {
                ProductVariantRefOption::create([
                    'option_id' => $parent['id'],
                    'product_id' => $model->id,
                    'position' => $parent['position'] ?? 0,
                    'is_required' => empty($parent['is_required']) ? false : true
                ]);
            }
            foreach ($children as $child) {
                ProductVariantRefOption::firstOrCreate([
                    'option_id' => $child['id'],
                    'product_id' => $model->id
                ]);
            }
        });
    }

    public function modelRules(): array
    {
        return [
            'all' => [
                'name' => 'required|string|max:255',
            ],
            'merge' => [
                'ids' => 'required|array',
            ],
        ];
    }

    public function orderLines(): HasMany
    {
        return $this->hasMany(OrderLine::class, 'product_id', 'id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'product_ref_categories',
            'product_id',
            'product_category_id',
        )
            ->orderBy('parent_id', 'desc');
    }

    public function category()
    {
        return $this->hasOne(
            ProductCategory::class,
            'product_ref_categories',
            'product_id',
            'product_category_id',
        )->ofMany('position', 'min');
    }

    public function variantOptions(): BelongsToMany
    {
        return $this->options()->where('options.parent_id', '!=', 0);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'product_tags',
            'product_id',
            'tag_id',
            'id'
        )
            ->orderBy('id', 'desc');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getCategoryNameAttribute()
    {
        if ($category = $this->category()->first()) {
            return $category->name ?? null;
        }

        return null;
    }

    public function getSaleCategoriesAttribute()
    {
        return $this->categories()
            ->select('id', 'name')
            ->where('type', ProductCategory::TYPE_SALE)
            ->get();
    }

    public function getUseCaseCategoriesAttribute()
    {
        return $this->categories()
            ->select('id', 'name')
            ->where('type', ProductCategory::TYPE_USE_CASE)
            ->get();
    }

    public function getBreadcrumbsAttribute(): Collection
    {
        $category = $this->category()->first();
        return ProductCategory::transformAsBreadcrumb($category);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(
            Option::class,
            'product_variant_ref_options',
            'product_id',
            'option_id',
        )
            ->withPivot('position', 'is_required', 'product_variant_id')
            ->orderBy('is_required', 'DESC')
            ->orderBy('position', 'DESC');
    }

    public function parentOptions(): BelongsToMany
    {
        return $this->options()->whereRoot();
    }

    public function childrenOptions(): BelongsToMany
    {
        return $this->options()->whereChildren();
    }

    public function productOptions(): BelongsToMany
    {
        return $this->options()->where('options.parent_id', 0);
    }

    public function getRateScoreAttribute()
    {
        return $this->comments
            ->where('status', Comment::STATUS_ACTIVE)
            ->avg('rate_score') ?? 0;
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments
            ->where('status', Comment::STATUS_ACTIVE)
            ->count();
    }

    public function requiredProductOptions(): BelongsToMany
    {
        return $this->productOptions()->wherePivot('is_required', true);
    }

    public function getListVariantOptionAttribute(): Collection
    {
        $variantOptions = $this->variantOptions;
        return Option::transformNested($this->requiredProductOptions, $variantOptions);
    }

    public function optionsMappingThumbnail($options, $primaryOptionValues): Collection
    {
        $values = $primaryOptionValues->pluck('thumbnail_url', 'primary_option.id')->toArray();
        return $options->map(function ($option) use ($values) {
            $option['show_name'] = $option['id'] !== 1;
            $option['nodes'] = $option['nodes']->map(function ($node) use ($values) {
                if (isset($values[$node['id']])) {
                    $node['image_url'] = $values[$node['id']];
                }
                return $node;
            });
            return $option;
        });
    }

    public function getListOptionAttribute()
    {
        $variantOptions = $this->variantOptions;
        return Option::transformNested($this->productOptions, $variantOptions);
    }

    public function getSpecsAttribute(): Collection
    {
        $variantOptions = $this->variantOptions;
        return Option::transformNested($this->options, $variantOptions);
    }

    public function variants(): HasMany
    {
        return $this
            ->hasMany(ProductVariant::class, 'product_id', 'id')
            ->orderBy('is_default', 'DESC')
            ->orderBy('id', 'ASC');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id', 'image_name');
    }

    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        $query->when($filters['keyword'] ?? false, function (Builder $query, $value) {
            $query
                ->selectRaw(
                    "max(
                    (CASE WHEN LOWER(`products`.`name`) LIKE '{$value}' THEN 300 ELSE 0 END) +
                    (CASE WHEN LOWER(`products`.`name`) LIKE '{$value}%' THEN 100 ELSE 0 END) +
                    (CASE WHEN LOWER(`products`.`name`) LIKE '%{$value}%' THEN 20 ELSE 0 END)
                ) AS relevance"
                )
                ->orderBy('relevance', 'DESC')
                ->groupBy('products.id');
        });

        $query->when($filters['status'] ?? false, function (Builder $query, $value) {
            $query->where('products.status', $value);
        });

        $query->when($filters['category_ids'] ?? false, function (Builder $query, $value) {
            $query->whereHas('categories', function ($query) use ($value) {
                $query->whereIn('id', $value);
            });
        });

        $query->when($filters['option_slugs'] ?? false, function (Builder $query, $slugs) {
            foreach ($slugs as $slug) {
                $query->whereHas('options', function ($query) use ($slug) {
                    $query->where('slug', $slug);
                });
            }
        });

        $query->when($filters['brand_id'] ?? false, function (Builder $query, $value) {
            $query->whereHas('brand', function ($query) use ($value) {
                $query->where('id', $value);
            });
        });

        $query->when($filters['brands'] ?? false, function (Builder $query, $value) {
            $value = explode(',', $value);
            $query->whereHas('brand', function ($query) use ($value) {
                $query->whereIn('id', $value);
            });
        });

        $query->when($filters['tags'] ?? false, function (Builder $query, $value) {
            $value = explode(',', $value);
            $query->whereHas('tags', function ($query) use ($value) {
                $query->whereIn('id', $value);
            });
        });

        $query->when($filters['price'] ?? false, function (Builder $query, $value) {
            $value = explode('-', $value);
            $priceFrom = $value[0];
            $priceTo = $value[1];

            $query->whereHas('variants', function ($query) use ($priceFrom, $priceTo) {
                $query->active()
                    ->where('price', '>=', $priceFrom)
                    ->where('price', '<=', $priceTo);
            });
        });

        $query->when($filters['rate_score'] ?? false, function (Builder $query, $value) {
            $query
                ->withAvg('comments', 'rate_score')
                ->having('comments_avg_rate_score', '>=', $value);
        });

        $query->when($filters['ids'] ?? false, function (Builder $query, $value) {
            $query->whereIn('id', $value);
        });

        $query->when($filters['sort'] ?? false, function (Builder $query, $value) {
            $query->join('product_variants', function ($join) {
                $join->on('product_variants.product_id', '=', 'products.id')
                    ->where('product_variants.is_default', true)
                    ->where('product_variants.price', '>', 0)
                    ->where('product_variants.status', ProductVariant::STATUS_ACTIVE)
                    ->where('products.status', self::STATUS_ACTIVE);
            });

            switch ($value) {
                case 'discount':
                    $query
                        ->orderByRaw('((product_variants.old_price - product_variants.price)/product_variants.old_price * 100) DESC');
                    break;
                case 'price_asc':
                    $query->orderBy('product_variants.price', 'ASC');
                    break;
                case 'price_desc':
                    $query->orderBy('product_variants.price', 'DESC');
                    break;
                case 'popular':
                    $query->orderBy('products.views', 'DESC');
                    break;
                default:
                    $query->orderBy('products.id', 'DESC');
                    break;
            }
        });

        return $query;
    }

    public function scopeSkipVariantEmptyPrice(Builder $query): Builder
    {
        return $query->whereHas('variants', function ($q) {
            $q->active()->where('price', '>', 0);
        })
            ->withCount('variants')->having('variants_count', '>', 0);
    }

    public function transformSearch(): array
    {
        [$variant, $variants] = $this->getVariants($this->variants);

        return [
            'id' => $this->id,
            'variant_id' => $variant['id'],

            'name' => $this->name,
            'slug' => $this->custom_slug ?? $this->slug,
            'rate_score' => $this->rate_score,

            'image_url' => $this->image_url,
            'price' => $variant['price'],
            'old_price' => $variant['old_price'],
            'url' => $this->url,
        ];
    }

    public function transform($selectedVariantId = null): array
    {
        return $this->transformGeneral($selectedVariantId);
    }

    public function transformDetails($selectedVariantId = null): array
    {
        $category = $this->categories?->first();
        [$highlight, $incenses] = $this->getRequiredOptions();

        $general = $this->transformGeneral($selectedVariantId);

        $specs = $this->specs
            ->whereNotIn('id', $general['options']->pluck('id')->toArray())
            ->groupBy('display_position');

        $use_cases = $this->categories?->where('type', ProductCategory::TYPE_USE_CASE)
            ->map(fn ($item) => $item->transformUseCase())->values();

        return array_merge($general, [
            'highlight_features' => $highlight,
            'incenses' => $incenses,
            'specs' => $specs,
            'content' => transform_richtext($this->content),

            'breadcrumbs' => ProductCategory::transformAsBreadcrumb($category),
            'size_guide' => [
                'table' => $this->size_guide_table,
                'guide' => $this->size_guide_tutorial,
                'image' => $this->size_guide_image
            ],

            'ingredients' => transform_richtext($this->ingredients),
            'use_cases' => $use_cases,

            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'custom_meta' => $this->custom_meta,
        ]);
    }

    public function getImageUrlAttribute()
    {
        return collect($this->images)->first()['static_url'] ?? null;
    }

    public function getImageUrlsAttribute()
    {
        return collect($this->images)->map(fn ($item) => $item['static_url'])->toArray();
    }

    public function getUrlAttribute()
    {
        $url = null;
        if ($this->status == self::STATUS_ACTIVE) {
            $url = route("product.show", [
                'productSlug' => $this->custom_slug ?? $this->slug,
                'productId' => $this->id
            ]);
        }
        return $url;
    }

    private function getVariants($variants, $selectedVariantId = null): array
    {
        $transformVariants = $variants->where('price', '>', 0)->map(function (ProductVariant $item) use ($selectedVariantId) {
            return $item->transform($this, $selectedVariantId);
        });

        $selectedVariant = $transformVariants->where('selected', true)->first();

        if ($selectedVariant === null) {
            if ($variant = $variants->where('is_default', true)->first()) {
                $selectedVariant = $variant->transform($this);
            } elseif ($variant = $variants->first()) {
                $selectedVariant = $variant->transform($this);
            }

            $transformVariants = $transformVariants->where('price', '>', 0)->map(function ($item) use ($selectedVariant) {
                if ($item['id'] === $selectedVariant['id']) {
                    $item['selected'] = true;
                }

                return $item;
            })->values();
        }


        return [$selectedVariant, $transformVariants];
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

    public function scopeActive($query)
    {
        return $query
            ->where('products.status', self::STATUS_ACTIVE)
            ->skipVariantEmptyPrice();
    }

    private function getPrimaryOptionValues($variants)
    {
        return collect($variants->unique('primary_option'))->map(function ($variant) {
            return [
                'variant_id' => $variant['id'],
                'image_url' => $variant['image_url'],
                'thumbnail_url' => $variant['thumbnail_url'],
                'default_thumbnail_url' => $variant['default_thumbnail_url'],
                'primary_option' => $variant['primary_option'],
                'formatted_price' => $variant['formatted_price'],
                'formatted_old_price' => $variant['formatted_old_price'],
                'selected' => $variant['selected'],
            ];
        })->values();
    }

    private function getRequiredOptions()
    {
        $highlight = $this->specs
            ->firstWhere('display_type', Option::DISPLAY_TYPE_LIST)['nodes'] ?? [];

        $incenses = $this->specs
            ->whereIn('slug', ['huong-dau', 'huong-giua', 'huong-cuoi'])
            ->values();

        return [$highlight, $incenses];
    }

    public function transformGeneral($selectedVariantId = null): array
    {
        [$variant, $variants] = $this->getVariants($this->variants, $selectedVariantId);
        $primaryOptionValues = $this->getPrimaryOptionValues($variants);
        $options = $this->optionsMappingThumbnail($this->list_variant_option, $primaryOptionValues);

        $category = $this->categories?->firstWhere('type', ProductCategory::TYPE_PRODUCT)?->transform();

        return [
            'id' => $this->id,
            'variant_id' => $variant ? $variant['id'] : null,

            'name' => $this->name,
            'slug' => $this->custom_slug ?? $this->slug,
            'category' => $category,
            'brand' => $this->brand?->transform(),
            'rate_score' => $this->rate_score,
            'comments_count' => $this->comments_count,

            'price' => $variant['price'],
            'old_price' => $variant['old_price'],
            'formatted_price' => price_format($variant['price']),
            'formatted_old_price' => price_format($variant['old_price']),

            'url' => $this->url,

            // Tags
            'is_hot' => $this->is_hot,
            'is_new' => $this->is_new,
            'is_limited' => $this->is_limited,
            'tags' => $this->tags->map(fn ($item) => $item->name)->toArray(),

            'colors' => $primaryOptionValues->whereNotNull('default_thumbnail_url'),
            'options' => $options,
            'option_values' => $variant['option_values'],
            'variants' => $variants->values()->toArray(),

            'image_url' => $this->image_url,
            'image_urls' => $this->image_urls
        ];
    }
}
