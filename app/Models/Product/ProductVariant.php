<?php

namespace App\Models\Product;

use Jamstackvietnam\Support\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jamstackvietnam\Support\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends BaseModel
{
    use HasFactory, SoftDeletes, HasMedia;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    public const SOURCE_SHEIN = 'SHEIN';

    public const SOURCE_LIST = [
        self::SOURCE_SHEIN,
    ];

    protected $table = 'product_variants';

    public $fillable = [
        'id',
        'product_id',
        'product_name',
        'sku',
        'status',
        'barcode',
        'title',
        'price',
        'old_price',
        'position',
        'quantity',
        'is_default',

        'thumbnail_url',
        'default_image_url',

        'source',
        'source_sku',
        'source_product_id',
        'source_variant_id',
        'source_category_id',
        'source_url',
        'source_raw',

        'creator_id',
        'editor_id',
    ];

    protected $casts = [
        'price' => 'int',
        'old_price' => 'int',
    ];

    public function modelRules(): array
    {
        return [
            'all' => [
                'title' => 'required|string',
            ],

            'store' => [
                'options' => 'array',
            ]
        ];
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->sku = $model->sku ?? generate_code(10);

            if (!request()->has('title')) {
                $model->title = request()->input('product_name') . ' ' . $model->sku;
            }

            if (request()->route()?->getName() !== 'sidebar.variants.store') return;

            $images = collect(request()->input('image_ids', []));
            $defaultImage = $images->firstWhere('is_default', true) ?? $images->first();
            $model->default_image_url = $defaultImage['image_url'];

            if ($model->isDirty('is_default') && $model->is_default) {
                self::withoutEvents(function () use ($model) {
                    static::query()
                        ->where('product_id', $model->product_id)
                        ->where('is_default', true)
                        ->update(['is_default' => false]);
                });
            }
        });

        static::saved(function ($model) {
            $model->saveOptions($model);
            $model->saveImages($model);
        });
    }

    public static function saveOptions($model)
    {
        if (request()->input('options')) {
            $options = [];
            foreach (array_column(request()->input('options'), 'id') as $optionId) {
                if (is_null($optionId)) continue;
                $options[] = [
                    'option_id' => $optionId,
                    'product_id' => $model->product_id,
                    'product_variant_id' => $model->id
                ];
            }
            ProductVariantRefOption::where('product_variant_id', $model->id)->delete();
            ProductVariantRefOption::insert($options);
        }
    }

    public function saveImages($model)
    {
        $model_id = $model->id;
        ProductImage::query()
            ->where('product_id', $model->product_id)
            ->whereJsonContains('variant_ids', $model->id)
            ->get()->each(function ($image) use ($model_id) {
                $variantIds = $image->variant_ids;
                unset($variantIds[array_search($model_id, $variantIds)]);
                $image->update(['variant_ids' => array_values($variantIds)]);
            });

        $productsImage = ProductImage::find(array_column(request()->input('image_ids', []), 'id'));

        foreach ($productsImage as $productImage) {
            if (!empty($productImage)) {
                $variantIds = $productImage->variant_ids ?? [];
                $variantIds[] = $model->id;
                $productImage->update(['variant_ids' => array_unique($variantIds)]);
            }
        }
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(
            Option::class,
            'product_variant_ref_options',
            'product_variant_id',
            'option_id',
            'id',
            'id'
        );
    }

    public function getProductImagesAttribute()
    {
        $defaultThumbnailUrl = $this->default_image_url;
        $images = ProductImage::where('product_id', $this->product_id)
            ->get();

        $images->map(function ($image) use ($defaultThumbnailUrl) {
            $image->is_default = (int) ($image->image_url === $defaultThumbnailUrl);
            return $image;
        });

        return $images;
    }

    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        $query->when($filters['product_id'] ?? false, function (Builder $query, $productId) {
            $query->where('product_id', $productId);
        });

        return $query;
    }

    public function transform($product = null, $selectedVariantId = null): array
    {
        $product = $product ?? $this->product;

        $options = $this->getOptions($product);
        $images = $this->getImages($product);
        $videos = $this->getVideos($product);
        $optionHaveImage = $this->getOptionHaveImage($options);

        $optionValues = Option::transformNested($product->requiredProductOptions, $options)
            ->map(function ($option) {
                $name = $option['nodes']->pluck('name');
                if (count($name) == 0) return false;
                return $option['name'] . ': ' . $name->join(', ');
            })->filter()->values();

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'sku' => $this->sku,
            'title' => $this->title,
            'option_name' => $options->pluck('name')->join(', '),
            'option_values' => $optionValues,
            'slug' => $product->slug,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'formatted_price' => price_format($this->price),
            'formatted_old_price' => price_format($this->old_price),
            'position' => $this->position,
            'thumbnail_url' => static_url($this->thumbnail_url),
            'default_thumbnail_url' => static_url($this->thumbnail_url) ?? $optionHaveImage['image_url'] ?? null,
            'primary_option' => $optionHaveImage,
            'image_url' => $images->first(),
            'image_urls' => $images,
            'video_urls' => $videos,
            'options' => $options->pluck('slug')->toArray(),
            'selected' => $this->id === (int) $selectedVariantId,
        ];
    }

    public function transformCart($product = null): array
    {
        $product = $product ?? $this->product;
        $images = $this->getImages($product);
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'sku' => $this->sku,
            'title' => $this->title,
            'slug' => $product->slug,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'formatted_price' => price_format($this->price),
            'formatted_old_price' => price_format($this->old_price),
            'position' => $this->position,
            'image_url' => $images->first(),
        ];
    }

    private function getImages($product)
    {
        $images =  collect($product->images)->where(function ($item) {
            return $item['variant_ids'] && in_array($this->id, $item['variant_ids']);
        })->pluck('static_url');

        if ($this->default_image_url) {
            $images = $images->prepend(static_url($this->default_image_url))
                ->unique()
                ->values();
        }
        return $images;
    }

    private function getVideos($product)
    {
        $urls = collect($product->video_urls)->where(function ($item) {
            return isset($item['variant_ids']) && $item['variant_ids'] && in_array($this->id, $item['variant_ids']);
        })->pluck('static_url')->filter();

        if ($urls->count() === 0) {
            $urls = collect($product->video_urls)->pluck('static_url')->filter();
        }

        return $urls;
    }

    private function getOptions($product)
    {
        $variantId = $this->id;
        $requiredProductOptionIds = $product->requiredProductOptions->pluck('id')->toArray();

        return $product->variantOptions
            ->filter(function ($option) use ($requiredProductOptionIds, $variantId) {
                return in_array($option->parent_id, $requiredProductOptionIds) && $option->pivot->product_variant_id === $variantId;
            });
    }

    private function getOptionHaveImage($options)
    {
        $option = $options->whereNotNull('image_url')->first()?->only('id', 'image_url');
        $option = $option ?? $options->first()?->only('id', 'image_url');

        if (empty($option)) return null;

        return [
            'id' => $option['id'],
            'image_url' => static_url($this->thumbnail_url) ?? static_url($option['image_url']),
        ];
    }

    public function transformToProduct(): array
    {
        return $this->product->transform($this->id);
    }
}
