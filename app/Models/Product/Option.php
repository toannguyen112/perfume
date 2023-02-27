<?php

namespace App\Models\Product;

use Jamstackvietnam\Support\Models\BaseModel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Jamstackvietnam\Support\Models\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends BaseModel
{
    use SoftDeletes;
    use HasFactory;
    use Sluggable;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLE = 2;
    public const STATUS_LIST = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    public const DISPLAY_TYPE_ALL = 1;
    public const DISPLAY_TYPE_TEXT = 2;
    public const DISPLAY_TYPE_IMAGE = 3;
    public const DISPLAY_TYPE_LIST = [
        self::DISPLAY_TYPE_ALL => 'ALL',
        self::DISPLAY_TYPE_TEXT => 'TEXT',
        self::DISPLAY_TYPE_IMAGE => 'IMAGE',
    ];

    public const DISPLAY_POSITION_THONG_SO = 1;
    public const DISPLAY_POSITION_NOI_BAT = 2;
    public const DISPLAY_POSITION_NOT_HUONG = 3;
    public const DISPLAY_POSITION_LIST = [
        self::DISPLAY_POSITION_THONG_SO => 'THONG_SO',
        self::DISPLAY_POSITION_NOI_BAT => 'NOI_BAT',
        self::DISPLAY_POSITION_NOT_HUONG => 'NOT_HUONG',
    ];

    protected $table = 'options';

    public $fillable = [
        'parent_id',
        'name',
        'slug',
        'image_url',
        'status',
        'position',

        'display_type',
        'display_position',

        'creator_id',
        'editor_id',
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    protected $slugAttribute = 'name';

    protected static function booted()
    {
        static::saved(function (self $model) {
            if (request()->route() === null) return;

            $model->saveCategories($model);

            $childrenItems = request()->input('children', []);
            $diff = Arr::pluck($childrenItems, 'id');
            $ids = $model->children()->pluck('id')->diff($diff);

            if ($ids->isNotEmpty()) {
                $model->children()->whereIn('id', $ids)->delete();
            }

            self::withoutEvents(function () use ($childrenItems, $model) {
                foreach ($childrenItems as $index => $item) {
                    $item['position'] = $index;
                    $item['status'] = self::STATUS_ACTIVE;
                    $item['slug'] = $model->generateSlug($item['name']);
                    $model->children()->updateOrCreate(
                        ['id' => $item['id'] ?? null],
                        $item
                    );
                }
            });
        });
    }

    public function modelRules(): array
    {
        return [
            'all' => [
                'name' => 'required|string|max:255',
            ],
        ];
    }

    private function saveCategories($model)
    {
        $ids = array_column(request()->input('categories', []), 'id');
        $model->categories()->sync($ids, 'id');
    }

    public function productVariants(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_variant_ref_options',
            'option_id',
            'product_variant_id',
            'id',
            'id'
        );
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'option_ref_categories',
            'option_id',
            'product_category_id',
        );
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_variant_ref_options',
            'option_id',
            'product_id',
            'id',
            'id'
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->orderBy('position', 'asc');
    }

    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        if (isset($filters['parent_id']) && is_numeric($filters['parent_id'])) {
            $query->where('parent_id', $filters['parent_id']);
        }

        if (isset($filters['status']) && is_numeric($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $query->when($filters['keyword'] ?? false, function (Builder $query, $value) {
            $query->where('name', 'LIKE', '%' . $value . '%');
        });

        return $query;
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public static function getRoot()
    {
        return static::query()
            ->with('nodes')
            ->orWhere('parent_id', 0)
            ->orderBy('position')
            ->orderBy('parent_id')
            ->get()->map(fn ($item) => $item->transformNode());
    }

    public static function getRootOnly()
    {
        return static::orderBy('position')
            ->orderBy('parent_id')
            ->orWhere('parent_id', 0)
            ->get();
    }

    public function nodes()
    {
        return $this->children()
            ->where('parent_id', '!=', 0)
            ->orderBy('position', 'asc')
            ->orderBy('parent_id')
            ->orderBy('id', 'desc');
    }

    public function scopeOrderByPosition($query)
    {
        return $query
            ->orderByRaw('-position DESC')
            ->orderBy('position', 'asc');
    }

    public function scopeWhereRoot($query)
    {
        return $query->whereNull('parent_id')
            ->orWhere('parent_id', 0);
    }

    public function scopeWhereChildren($query)
    {
        return $query->whereNotNull('parent_id')
            ->orWhere('parent_id', '!=', 0);
    }

    public static function transformNestedFrontend($parents, $children, $condition = []): array
    {
        $items = $parents->map(function ($parent) use ($children, $condition) {
            $children = $children
                ->where('parent_id', $parent->id)
                ->map(fn ($item) => $item->transform($condition))
                ->values()
                ->toArray();

            $parentActive = false;

            if ($option = collect($children)->firstWhere('active', true)) {
                $children = [$option];
                $parentActive = true;
            }

            return [
                'id' => $parent->id,
                'name' => $parent->name,
                'slug' => $parent->slug,
                'image_url' => static_url($parent->image_url),
                'children' => $children,
                'active' => $parentActive
            ];
        })->sortByDesc('active')->values()->toArray();
        return $items;
    }

    public function transform($condition = []): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image_url' => static_url($this->image_url),
            'active' => false,
        ];

        if (!empty($condition['active_slugs'])) {
            $data['active'] = in_array($data['slug'], $condition['active_slugs']);
        }

        return $data;
    }

    public function transformNode(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image_url' => static_url($this->image_url)
        ];

        $data['nodes'] = [];

        if ($this->parent_id === 0) {
            $data['nodes'] = $this->nodes->map(function ($item) {
                return $item->transformNode();
            });
        }
        return $data;
    }

    public static function transformNested($parents, $children): Collection
    {
        $options = $parents->map(function ($parent) use ($children) {
            $nodes = $children
                ->filter(fn ($item) => $item['parent_id'] === $parent->id)
                ->map(fn ($item) => $item->transformNode())
                ->unique()
                ->values();

            return [
                'id' => $parent->id,
                'name' => $parent->name,
                'slug' => $parent->slug,
                'display_type' => self::DISPLAY_TYPE_LIST[$parent->display_type] ?? null,
                'display_position' => self::DISPLAY_POSITION_LIST[$parent->display_position] ?? null,
                'is_required' => $parent->pivot->is_required,
                'position' => $parent->pivot->position,
                'nodes' => $nodes
            ];
        })
            ->sortBy('position')
            ->sortByDesc('is_required')
            ->values();

        return $options;
    }
}
