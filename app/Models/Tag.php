<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jamstackvietnam\Support\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jamstackvietnam\Support\Traits\Searchable;
use App\Models\Product\Product;
use App\Models\Blog\Post;

class Tag extends BaseModel
{
    use HasFactory, SoftDeletes, Searchable;

    public const TYPE_PRODUCT = 'PRODUCT';
    public const TYPE_CATEGORY = 'CATEGORY';
    public const TYPE_POST = 'POST';

    public const TYPE_LIST = [
        self::TYPE_PRODUCT => 'Sản phẩm',
        self::TYPE_CATEGORY => 'Danh mục sản phẩm',
        self::TYPE_POST => 'Bài viết'
    ];

    public $fillable = [
        'name',
        'type',
        'color',
        'icon'
    ];

    protected $searchable = [
        'columns' => [
            'tags.name' => 20,
            'tags.type' => 8,
        ],
    ];

    public function modelRules()
    {
        return [
            'all' => [
                'name' => 'required|string|max:255',
                'type' => 'required',
            ],
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }

    public static function getProduct()
    {
        return static::whereNotIn('type', [self::TYPE_POST])
            ->get();
    }

    public static function getCategory()
    {
        return static::whereType(self::TYPE_CATEGORY)
            ->get();
    }

    public static function getCategories()
    {
        return static::whereType(self::TYPE_CATEGORY)->get();
    }

    public static function getPost()
    {
        return static::whereType(self::TYPE_POST)->get();
    }

    public function getFormattedTypeAttribute()
    {
        return self::TYPE_LIST[$this->attributes['type']];
    }

    public function scopeGetProductTags($query)
    {
        return $query->where('type', 'product')->orderBy('name')->get();
    }

    public function transform()
    {
        return  [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'color' => $this->color,
        ];
    }
}
