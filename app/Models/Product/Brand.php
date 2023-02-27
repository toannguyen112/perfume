<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jamstackvietnam\Support\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jamstackvietnam\Support\Traits\Searchable;
use Jamstackvietnam\Support\Models\Sluggable;
use Jamstackvietnam\Support\Traits\HasMedia;

class Brand extends BaseModel
{
    use HasFactory, Sluggable, SoftDeletes, HasMedia;
    use Searchable;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE => "Hoạt động",
        self::STATUS_DISABLE => "Ẩn",
    ];

    protected $table = 'brands';

    public $fillable = [
        'name',
        'slug',
        'status',
        'description',
        'is_hot',
        'meta_title',
        'custom_slug',
        'meta_description',
        'custom_meta',
    ];

    protected $appends = [
        'url'
    ];

    protected $with = [
        'files',
    ];

    protected $slugAttribute = 'name';

    public $casts = [
        'custom_meta' => 'array',
    ];

    protected $mediaFields = [
        'thumbnail',
    ];

    protected $searchable = [
        'columns' => [
            'brands.name' => 10,
            'brands.id' => 3,
        ],
    ];

    public function modelRules(): array
    {
        return [
            'all' => [
                'name' => 'required|string|max:255',
            ],
        ];
    }

    public static function list()
    {
        return self::active()
            ->get()
            ->map(fn ($item) => $item->transform());
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'brand_ref_categories');
    }

    public function getThumbnailPathAttribute()
    {
        return $this->getFilePath('thumbnail');
    }

    public function getThumbnailAttribute()
    {
        return $this->getFile('thumbnail');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getUrlAttribute()
    {
        $url = null;
        if ($this->status == self::STATUS_ACTIVE) {
            $url = route("products.index", [
                'slug' => $this->custom_slug ?? $this->slug
            ]);
        }
        return $url;
    }

    public function transform()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->custom_slug ?? $this->slug,
            'description' => transform_richtext($this->description),
            'thumbnail' => $this->thumbnail_path,
        ];
    }

    public function transformDetails()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->custom_slug ?? $this->slug,
            'content' => transform_richtext($this->description),
            'thumbnail' => $this->thumbnail_path,
            'meta_title' => $this->meta_title ?? $this->name,
            'meta_description' => $this->meta_description ?? $this->description,
            'custom_meta' => $this->custom_meta
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
